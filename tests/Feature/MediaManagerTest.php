<?php

namespace Tests\Feature;

use App\Models\MediaFolder;
use App\Models\MediaItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MediaManagerTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create role and admin user
        Role::firstOrCreate(['name' => 'admin']);
        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole('admin');

        // Fake public storage disk
        Storage::fake('public');
    }

    public function test_admin_can_create_a_folder(): void
    {
        $this->actingAs($this->adminUser);

        $response = $this->postJson(route('admin.media.folders.store'), [
            'name' => 'Marketing Assets',
            'parent_id' => null,
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('media_folders', [
            'name' => 'Marketing Assets',
            'parent_id' => null,
        ]);
    }

    public function test_admin_can_rename_a_folder(): void
    {
        $this->actingAs($this->adminUser);

        $folder = MediaFolder::create([
            'name' => 'Old Folder Name',
            'parent_id' => null,
        ]);

        $response = $this->putJson(route('admin.media.folders.rename', $folder), [
            'name' => 'New Folder Name',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);

        $folder->refresh();
        $this->assertEquals('New Folder Name', $folder->name);
    }

    public function test_admin_can_delete_a_folder_recursively(): void
    {
        $this->actingAs($this->adminUser);

        // Create parent folder
        $parentFolder = MediaFolder::create(['name' => 'Parent']);

        // Create nested subfolder
        $subFolder = MediaFolder::create(['name' => 'Sub', 'parent_id' => $parentFolder->id]);

        // Create dummy file inside subfolder
        $file = MediaItem::create([
            'folder_id' => $subFolder->id,
            'name' => 'Nested File',
            'file_name' => 'nested.webp',
            'path' => 'media/2026/05/nested.webp',
            'mime_type' => 'image/webp',
            'size' => 1024,
            'conversions' => ['thumbnail' => 'media/2026/05/nested_thumb.webp'],
        ]);

        // Put fake files on storage
        Storage::disk('public')->put($file->path, 'mock-webp-data');
        Storage::disk('public')->put('media/2026/05/nested_thumb.webp', 'mock-webp-thumb');

        // Delete parent folder
        $response = $this->deleteJson(route('admin.media.folders.destroy', $parentFolder));

        $response->assertOk();

        // Assert parent and subfolders are deleted from database
        $this->assertDatabaseMissing('media_folders', ['id' => $parentFolder->id]);
        $this->assertDatabaseMissing('media_folders', ['id' => $subFolder->id]);
        $this->assertDatabaseMissing('media_items', ['id' => $file->id]);

        // Assert physical files are deleted from storage
        Storage::disk('public')->assertMissing($file->path);
        Storage::disk('public')->assertMissing('media/2026/05/nested_thumb.webp');
    }

    public function test_admin_can_upload_image_and_convert_to_webp_and_generate_responsive_sizes(): void
    {
        $this->actingAs($this->adminUser);

        // Upload fake JPEG image (1500 x 1000px)
        $file = UploadedFile::fake()->image('scenery.jpg', 1500, 1000);

        $response = $this->postJson(route('admin.media.upload'), [
            'file' => $file,
            'folder_id' => null,
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);

        // Verify media database entry
        $this->assertDatabaseHas('media_items', [
            'name' => 'scenery',
            'mime_type' => 'image/webp',
        ]);

        $item = MediaItem::first();
        $this->assertNotNull($item->path);
        $this->assertNotNull($item->conversions);

        // Width is 1500, so large (1200), medium (800), and thumbnail (150) should all be generated
        $this->assertArrayHasKey('large', $item->conversions);
        $this->assertArrayHasKey('medium', $item->conversions);
        $this->assertArrayHasKey('thumbnail', $item->conversions);

        // Verify physical file presence on storage disk
        Storage::disk('public')->assertExists($item->path);
        Storage::disk('public')->assertExists($item->conversions['large']);
        Storage::disk('public')->assertExists($item->conversions['medium']);
        Storage::disk('public')->assertExists($item->conversions['thumbnail']);
    }

    public function test_admin_can_update_file_metadata(): void
    {
        $this->actingAs($this->adminUser);

        $file = MediaItem::create([
            'folder_id' => null,
            'name' => 'photo',
            'file_name' => 'photo.webp',
            'path' => 'media/photo.webp',
            'mime_type' => 'image/webp',
            'size' => 1234,
            'alt_text' => 'old alt text',
        ]);

        $response = $this->putJson(route('admin.media.files.update', $file), [
            'name' => 'new-photo-name',
            'alt_text' => 'new awesome alt text',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);

        $file->refresh();
        $this->assertEquals('new-photo-name', $file->name);
        $this->assertEquals('new awesome alt text', $file->alt_text);
    }

    public function test_admin_can_delete_file(): void
    {
        $this->actingAs($this->adminUser);

        $file = MediaItem::create([
            'folder_id' => null,
            'name' => 'trash',
            'file_name' => 'trash.webp',
            'path' => 'media/2026/05/trash.webp',
            'mime_type' => 'image/webp',
            'size' => 500,
            'conversions' => ['thumbnail' => 'media/2026/05/trash_thumb.webp'],
        ]);

        Storage::disk('public')->put($file->path, 'trash-webp-data');
        Storage::disk('public')->put('media/2026/05/trash_thumb.webp', 'trash-thumb-data');

        $response = $this->deleteJson(route('admin.media.files.destroy', $file));

        $response->assertOk();
        $this->assertDatabaseMissing('media_items', ['id' => $file->id]);

        Storage::disk('public')->assertMissing($file->path);
        Storage::disk('public')->assertMissing('media/2026/05/trash_thumb.webp');
    }

    public function test_admin_can_move_items(): void
    {
        $this->actingAs($this->adminUser);

        $folderA = MediaFolder::create(['name' => 'Folder A']);
        $folderB = MediaFolder::create(['name' => 'Folder B']);

        $file = MediaItem::create([
            'folder_id' => $folderA->id,
            'name' => 'moving-file',
            'file_name' => 'move.webp',
            'path' => 'media/move.webp',
            'mime_type' => 'image/webp',
            'size' => 100,
        ]);

        // Move Folder A and the file into Folder B
        $response = $this->postJson(route('admin.media.items.move'), [
            'folder_id' => $folderB->id,
            'folder_ids' => [$folderA->id],
            'file_ids' => [$file->id],
        ]);

        $response->assertOk();

        $folderA->refresh();
        $file->refresh();

        $this->assertEquals($folderB->id, $folderA->parent_id);
        $this->assertEquals($folderB->id, $file->folder_id);
    }
}
