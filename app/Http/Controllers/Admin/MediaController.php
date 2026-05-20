<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaFolder;
use App\Models\MediaItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class MediaController extends Controller
{
    /**
     * Display media items and subfolders for the current folder view.
     */
    public function index(Request $request)
    {
        if ($request->get('all_folders')) {
            $allFolders = MediaFolder::all()->map(function ($f) {
                $path = [];
                $curr = $f;
                while ($curr) {
                    array_unshift($path, $curr->name);
                    $curr = $curr->parent;
                }

                return [
                    'id' => $f->id,
                    'name' => implode(' / ', $path),
                ];
            })->sortBy('name')->values();

            return response()->json([
                'folders' => $allFolders,
            ]);
        }

        $currentFolderId = $request->get('folder_id');
        $search = $request->get('search');
        $type = $request->get('type');

        // Folders list (only show inside current directory, unless searching globally)
        if ($search) {
            $folders = MediaFolder::where('name', 'like', "%{$search}%")
                ->orderBy('name')
                ->get();
        } else {
            $folders = MediaFolder::where('parent_id', $currentFolderId)
                ->orderBy('name')
                ->get();
        }

        // Files query
        $filesQuery = MediaItem::query();

        if ($search) {
            $filesQuery->where('name', 'like', "%{$search}%");
        } else {
            $filesQuery->where('folder_id', $currentFolderId);
        }

        if ($type) {
            if ($type === 'image') {
                $filesQuery->where('mime_type', 'like', 'image/%');
            } elseif ($type === 'video') {
                $filesQuery->where('mime_type', 'like', 'video/%');
            } elseif ($type === 'document') {
                $filesQuery->where(function ($q) {
                    $q->where('mime_type', 'not like', 'image/%')
                        ->where('mime_type', 'not like', 'video/%');
                });
            }
        }

        $files = $filesQuery->orderBy('created_at', 'desc')->get();

        // Build breadcrumbs path
        $breadcrumbs = [];
        $temp = $currentFolderId ? MediaFolder::find($currentFolderId) : null;
        while ($temp) {
            array_unshift($breadcrumbs, [
                'id' => $temp->id,
                'name' => $temp->name,
            ]);
            $temp = $temp->parent;
        }

        // Return JSON for async/modal explorer queries, Inertia for standard requests
        if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'folders' => $folders,
                'files' => $files,
                'breadcrumbs' => $breadcrumbs,
                'current_folder_id' => $currentFolderId,
            ]);
        }

        return Inertia::render('Admin/Media', [
            'initialFolders' => $folders,
            'initialFiles' => $files,
            'initialBreadcrumbs' => $breadcrumbs,
            'initialFolderId' => $currentFolderId ? (int) $currentFolderId : null,
        ]);
    }

    /**
     * Create a new folder.
     */
    public function storeFolder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:media_folders,id',
        ]);

        $folder = MediaFolder::create([
            'name' => $request->get('name'),
            'parent_id' => $request->get('parent_id'),
        ]);

        return response()->json([
            'success' => true,
            'folder' => $folder,
        ]);
    }

    /**
     * Rename an existing folder.
     */
    public function renameFolder(Request $request, MediaFolder $folder)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $folder->update([
            'name' => $request->get('name'),
        ]);

        return response()->json([
            'success' => true,
            'folder' => $folder,
        ]);
    }

    /**
     * Delete a folder and its contents recursively.
     */
    public function destroyFolder(MediaFolder $folder)
    {
        $this->deleteFolderRecursive($folder);

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Upload and optimize a file (with format conversion and responsive image generations).
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:51200', // Max 50MB
            'folder_id' => 'nullable|exists:media_folders,id',
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $name = pathinfo($originalName, PATHINFO_FILENAME);
        $mimeType = $file->getMimeType();

        $yearMonth = date('Y/m');
        $folderPath = "media/{$yearMonth}";

        $isConvertibleImage = str_starts_with($mimeType, 'image/') &&
                              $mimeType !== 'image/svg+xml' &&
                              $mimeType !== 'image/gif';

        $savedPath = null;
        $conversions = [];
        $finalSize = 0;

        if ($isConvertibleImage) {
            try {
                $imageResource = @imagecreatefromstring(file_get_contents($file));
                if (! $imageResource) {
                    throw new \Exception('Could not create image resource from file data.');
                }

                // Standardize main format to WebP
                $fileName = Str::random(40).'.webp';
                $savedPath = "{$folderPath}/{$fileName}";

                // Write WebP output
                $finalSize = $this->convertToWebpAndSave($imageResource, $savedPath, 85);
                $mimeType = 'image/webp';

                // Responsive copies (large: 1200, medium: 800, thumbnail: 150)
                $origWidth = imagesx($imageResource);
                $origHeight = imagesy($imageResource);

                $sizes = [
                    'large' => 1200,
                    'medium' => 800,
                    'thumbnail' => 150,
                ];

                foreach ($sizes as $key => $targetWidth) {
                    if ($key === 'thumbnail' || $origWidth > $targetWidth) {
                        $targetHeight = (int) round(($origHeight * $targetWidth) / $origWidth);
                        $scaledImage = imagescale($imageResource, $targetWidth, $targetHeight);
                        if ($scaledImage) {
                            $convFileName = Str::random(40).'.webp';
                            $convPath = "{$folderPath}/{$convFileName}";
                            $this->convertToWebpAndSave($scaledImage, $convPath, 80);
                            $conversions[$key] = $convPath;
                            imagedestroy($scaledImage);
                        }
                    }
                }

                imagedestroy($imageResource);

            } catch (\Throwable $e) {
                // Fallback: save original file
                $fileName = Str::random(40).'.'.$file->getClientOriginalExtension();
                $savedPath = $file->storeAs($folderPath, $fileName, 'public');
                $finalSize = $file->getSize();
                $conversions = null;
            }
        } else {
            // Non-image file or gif/svg
            $fileName = Str::random(40).'.'.$file->getClientOriginalExtension();
            $savedPath = $file->storeAs($folderPath, $fileName, 'public');
            $finalSize = $file->getSize();
            $conversions = null;
        }

        $mediaItem = MediaItem::create([
            'folder_id' => $request->get('folder_id'),
            'name' => $name,
            'file_name' => basename($savedPath),
            'path' => $savedPath,
            'mime_type' => $mimeType,
            'size' => $finalSize,
            'alt_text' => $name,
            'conversions' => $conversions,
        ]);

        return response()->json([
            'success' => true,
            'media' => $mediaItem,
        ]);
    }

    /**
     * Update metadata for a media file (name, alt_text).
     */
    public function updateFile(Request $request, MediaItem $file)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'alt_text' => 'nullable|string|max:255',
        ]);

        $file->update([
            'name' => $request->get('name'),
            'alt_text' => $request->get('alt_text'),
        ]);

        return response()->json([
            'success' => true,
            'file' => $file,
        ]);
    }

    /**
     * Delete a media item and its physical assets on disk.
     */
    public function destroyFile(MediaItem $file)
    {
        Storage::disk('public')->delete($file->path);

        if ($file->conversions) {
            foreach ($file->conversions as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        $file->delete();

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Move files and folders into a target folder.
     */
    public function moveItems(Request $request)
    {
        $request->validate([
            'folder_id' => 'nullable|exists:media_folders,id',
            'folder_ids' => 'nullable|array',
            'folder_ids.*' => 'exists:media_folders,id',
            'file_ids' => 'nullable|array',
            'file_ids.*' => 'exists:media_items,id',
        ]);

        $targetFolderId = $request->get('folder_id');
        $folderIds = $request->get('folder_ids') ?: [];
        $fileIds = $request->get('file_ids') ?: [];

        // Move files
        if (! empty($fileIds)) {
            MediaItem::whereIn('id', $fileIds)->update([
                'folder_id' => $targetFolderId,
            ]);
        }

        // Move folders (avoiding circular loops)
        $movedFoldersCount = 0;
        foreach ($folderIds as $folderId) {
            if ($targetFolderId && $this->isDescendantOrSelf($folderId, $targetFolderId)) {
                // Prevent circular references by skipping invalid targets
                continue;
            }
            MediaFolder::where('id', $folderId)->update([
                'parent_id' => $targetFolderId,
            ]);
            $movedFoldersCount++;
        }

        return response()->json([
            'success' => true,
            'moved_files_count' => count($fileIds),
            'moved_folders_count' => $movedFoldersCount,
        ]);
    }

    /**
     * Convert the image resource to WebP format and save to storage.
     */
    private function convertToWebpAndSave($imageResource, string $path, int $quality): int
    {
        ob_start();
        imagewebp($imageResource, null, $quality);
        $data = ob_get_clean();
        Storage::disk('public')->put($path, $data);

        return strlen($data);
    }

    /**
     * Recursively delete folder, subfolders, and associated physical files.
     */
    private function deleteFolderRecursive(MediaFolder $folder)
    {
        foreach ($folder->children as $child) {
            $this->deleteFolderRecursive($child);
        }

        foreach ($folder->files as $file) {
            Storage::disk('public')->delete($file->path);
            if ($file->conversions) {
                foreach ($file->conversions as $path) {
                    Storage::disk('public')->delete($path);
                }
            }
            $file->delete();
        }

        $folder->delete();
    }

    /**
     * Checks if parentFolderId is a descendant of childFolderId, or matches.
     */
    private function isDescendantOrSelf($childFolderId, $parentFolderId): bool
    {
        if ($childFolderId == $parentFolderId) {
            return true;
        }

        $folder = MediaFolder::find($parentFolderId);
        if (! $folder) {
            return false;
        }

        if ($folder->parent_id == $childFolderId) {
            return true;
        }

        return $this->isDescendantOrSelf($childFolderId, $folder->parent_id);
    }
}
