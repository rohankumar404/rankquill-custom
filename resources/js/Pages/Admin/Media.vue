<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref, onMounted, watch } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    initialFolders: {
        type: Array,
        default: () => [],
    },
    initialFiles: {
        type: Array,
        default: () => [],
    },
    initialBreadcrumbs: {
        type: Array,
        default: () => [],
    },
    initialFolderId: {
        type: Number,
        default: null,
    },
});

// State management
const currentFolderId = ref(props.initialFolderId);
const folders = ref(props.initialFolders);
const files = ref(props.initialFiles);
const breadcrumbs = ref(props.initialBreadcrumbs);

const searchQuery = ref('');
const filterType = ref(''); // '', 'image', 'video', 'document'
const isLoading = ref(false);
const isUploading = ref(false);
const uploadProgress = ref(0);
const dragOverActive = ref(false);

// Selection and details
const selectedFile = ref(null);
const selectedFolder = ref(null);
const selectedItems = ref({ folders: [], files: [] }); // For bulk operations

// Modals
const showNewFolderModal = ref(false);
const newFolderName = ref('');
const showRenameModal = ref(false);
const renameTarget = ref(null); // { type: 'folder'|'file', id: number, name: string }
const showMoveModal = ref(false);
const allFoldersList = ref([]);
const moveTargetFolderId = ref('');
const isMoveLoading = ref(false);
const showPreviewModal = ref(false);

// Search debounce timeout
let searchTimeout = null;

// Human-friendly byte sizes
const formatBytes = (bytes, decimals = 2) => {
    if (!bytes) return '0 Bytes';
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
};

// Fetch folder contents
const fetchFolderContents = async (folderId = currentFolderId.value) => {
    isLoading.value = true;
    try {
        const params = new URLSearchParams();
        if (folderId) params.append('folder_id', folderId);
        if (searchQuery.value) params.append('search', searchQuery.value);
        if (filterType.value) params.append('type', filterType.value);

        const response = await fetch(`/admin/media?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
        });
        const data = await response.json();
        
        folders.value = data.folders;
        files.value = data.files;
        breadcrumbs.value = data.breadcrumbs;
        currentFolderId.value = data.current_folder_id ? parseInt(data.current_folder_id) : null;
        
        // Reset selections
        selectedFile.value = null;
        selectedFolder.value = null;
        selectedItems.value = { folders: [], files: [] };
    } catch (e) {
        console.error('Error loading media contents', e);
    } finally {
        isLoading.value = false;
    }
};

// Navigate to folder
const navigateToFolder = (folderId) => {
    searchQuery.value = '';
    fetchFolderContents(folderId);
};

// Selection helpers
const selectFile = (file) => {
    selectedFolder.value = null;
    selectedFile.value = file;
};

const selectFolder = (folder) => {
    selectedFile.value = null;
    selectedFolder.value = folder;
};

// Toggle selection checkbox for bulk operations
const toggleItemSelection = (type, id) => {
    const list = selectedItems.value[type];
    const index = list.indexOf(id);
    if (index > -1) {
        list.splice(index, 1);
    } else {
        list.push(id);
    }
};

const isItemSelected = (type, id) => {
    return selectedItems.value[type].includes(id);
};

// Copy URL
const copyToClipboard = (text) => {
    navigator.clipboard.writeText(text);
    alert('Link copied to clipboard!');
};

// Watch for search and filter changes
watch([searchQuery, filterType], () => {
    if (searchTimeout) clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        fetchFolderContents();
    }, 300);
});

// File upload handler
const handleFileUpload = async (event) => {
    const filesUploaded = event.target.files || event.dataTransfer.files;
    if (!filesUploaded.length) return;

    isUploading.value = true;
    uploadProgress.value = 0;

    for (let i = 0; i < filesUploaded.length; i++) {
        const formData = new FormData();
        formData.append('file', filesUploaded[i]);
        if (currentFolderId.value) {
            formData.append('folder_id', currentFolderId.value);
        }

        try {
            const response = await fetch('/admin/media/upload', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                },
                body: formData,
            });
            const data = await response.json();
            if (!data.success) {
                alert('Upload failed: ' + (data.message || 'Unknown error'));
            }
        } catch (e) {
            console.error('Upload failed', e);
            alert('Upload failed for file ' + filesUploaded[i].name);
        }
    }

    isUploading.value = false;
    fetchFolderContents();
};

// Folder actions
const createFolder = async () => {
    if (!newFolderName.value.trim()) return;

    try {
        const response = await fetch('/admin/media/folders', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            },
            body: JSON.stringify({
                name: newFolderName.value,
                parent_id: currentFolderId.value,
            }),
        });
        const data = await response.json();
        if (data.success) {
            showNewFolderModal.value = false;
            newFolderName.value = '';
            fetchFolderContents();
        }
    } catch (e) {
        console.error('Failed to create folder', e);
    }
};

const renameItem = async () => {
    if (!renameTarget.value || !renameTarget.value.name.trim()) return;
    
    const { type, id, name } = renameTarget.value;
    const url = type === 'folder' ? `/admin/media/folders/${id}` : `/admin/media/files/${id}`;
    
    try {
        const response = await fetch(url, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            },
            body: JSON.stringify(type === 'folder' ? { name } : { name, alt_text: selectedFile.value?.alt_text }),
        });
        const data = await response.json();
        if (data.success) {
            showRenameModal.value = false;
            renameTarget.value = null;
            fetchFolderContents();
        }
    } catch (e) {
        console.error('Rename failed', e);
    }
};

const deleteFolder = async (folderId) => {
    if (!confirm('Are you sure you want to delete this folder? This will delete all subfolders and files inside recursively.')) return;

    try {
        const response = await fetch(`/admin/media/folders/${folderId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            },
        });
        const data = await response.json();
        if (data.success) {
            fetchFolderContents();
        }
    } catch (e) {
        console.error('Failed to delete folder', e);
    }
};

// File actions
const updateFileMetadata = async () => {
    if (!selectedFile.value) return;

    try {
        const response = await fetch(`/admin/media/files/${selectedFile.value.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            },
            body: JSON.stringify({
                name: selectedFile.value.name,
                alt_text: selectedFile.value.alt_text,
            }),
        });
        const data = await response.json();
        if (data.success) {
            alert('File details saved!');
            fetchFolderContents();
        }
    } catch (e) {
        console.error('Failed to save file metadata', e);
    }
};

const deleteFile = async (fileId) => {
    if (!confirm('Are you sure you want to delete this file permanently?')) return;

    try {
        const response = await fetch(`/admin/media/files/${fileId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            },
        });
        const data = await response.json();
        if (data.success) {
            fetchFolderContents();
        }
    } catch (e) {
        console.error('Failed to delete file', e);
    }
};

// Move operations
const openMoveModal = async (type = null, id = null) => {
    // Populate moving selection
    if (type && id) {
        selectedItems.value = { folders: [], files: [] };
        selectedItems.value[type].push(id);
    }

    if (selectedItems.value.folders.length === 0 && selectedItems.value.files.length === 0) {
        alert('Please select at least one file or folder to move.');
        return;
    }

    try {
        const response = await fetch('/admin/media?all_folders=true', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await response.json();
        
        // Filter out selected folders and their children to prevent circular move loops
        allFoldersList.value = data.folders.filter(f => {
            return !selectedItems.value.folders.includes(f.id);
        });

        moveTargetFolderId.value = '';
        showMoveModal.value = true;
    } catch (e) {
        console.error('Error fetching all folders list', e);
    }
};

const executeMove = async () => {
    isMoveLoading.value = true;
    try {
        const response = await fetch('/admin/media/move', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            },
            body: JSON.stringify({
                folder_id: moveTargetFolderId.value === 'root' ? null : moveTargetFolderId.value,
                folder_ids: selectedItems.value.folders,
                file_ids: selectedItems.value.files,
            }),
        });
        const data = await response.json();
        if (data.success) {
            showMoveModal.value = false;
            fetchFolderContents();
        }
    } catch (e) {
        console.error('Move operation failed', e);
    } finally {
        isMoveLoading.value = false;
    }
};

// Drag and drop events
const onDragOver = (e) => {
    e.preventDefault();
    dragOverActive.value = true;
};

const onDragLeave = () => {
    dragOverActive.value = false;
};

const onDrop = (e) => {
    e.preventDefault();
    dragOverActive.value = false;
    handleFileUpload(e);
};

// Helpers for file type icons
const getFileIcon = (mimeType) => {
    if (mimeType.includes('pdf')) return 'PDF';
    if (mimeType.includes('zip') || mimeType.includes('tar') || mimeType.includes('rar')) return 'ZIP';
    if (mimeType.includes('video/')) return 'VIDEO';
    if (mimeType.includes('audio/')) return 'AUDIO';
    if (mimeType.includes('sheet') || mimeType.includes('excel') || mimeType.includes('csv')) return 'XLS';
    return 'DOC';
};

const getFileIconColor = (mimeType) => {
    if (mimeType.includes('pdf')) return 'bg-rose-500/20 text-rose-400 border-rose-500/30';
    if (mimeType.includes('zip') || mimeType.includes('tar') || mimeType.includes('rar')) return 'bg-amber-500/20 text-amber-400 border-amber-500/30';
    if (mimeType.includes('video/')) return 'bg-sky-500/20 text-sky-400 border-sky-500/30';
    if (mimeType.includes('audio/')) return 'bg-violet-500/20 text-violet-400 border-violet-500/30';
    if (mimeType.includes('sheet') || mimeType.includes('excel') || mimeType.includes('csv')) return 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30';
    return 'bg-slate-700/50 text-slate-300 border-slate-700';
};
</script>

<template>
    <AdminLayout>
        <div class="h-full flex flex-col space-y-6" @dragover="onDragOver" @drop="onDrop">
            <!-- Title Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-100 tracking-tight">Media Manager</h1>
                    <p class="text-xs text-slate-400 mt-1">Manage, convert, and optimize digital assets and gallery images.</p>
                </div>
                <div class="flex items-center space-x-3">
                    <button 
                        @click="showNewFolderModal = true" 
                        class="px-4 py-2 text-xs font-semibold bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-xl transition-colors flex items-center space-x-1.5"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                        </svg>
                        <span>New Folder</span>
                    </button>
                    <label 
                        class="px-4 py-2 text-xs font-semibold bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl shadow-md shadow-indigo-600/10 transition-colors flex items-center space-x-1.5 cursor-pointer"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        <span>Upload File</span>
                        <input type="file" multiple class="hidden" @change="handleFileUpload" />
                    </label>
                </div>
            </div>

            <!-- Toolbar filters -->
            <div class="p-4 bg-slate-950/40 border border-slate-800/80 rounded-2xl flex flex-col md:flex-row md:items-center justify-between gap-4">
                <!-- Search -->
                <div class="relative max-w-md w-full">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input 
                        v-model="searchQuery" 
                        type="text" 
                        placeholder="Search media files or folders..." 
                        class="w-full pl-9 pr-4 py-2 bg-slate-900/80 border border-slate-800 focus:border-indigo-500 focus:outline-none rounded-xl text-xs text-slate-200 placeholder-slate-500 transition-colors"
                    />
                </div>

                <!-- Type Filters -->
                <div class="flex items-center space-x-3">
                    <select 
                        v-model="filterType"
                        class="bg-slate-900 border border-slate-800 focus:border-indigo-500 focus:outline-none rounded-xl text-xs text-slate-200 px-3 py-2 transition-colors cursor-pointer"
                    >
                        <option value="">All Files</option>
                        <option value="image">Images</option>
                        <option value="video">Videos</option>
                        <option value="document">Documents</option>
                    </select>

                    <!-- Bulk Actions -->
                    <button 
                        v-if="selectedItems.folders.length || selectedItems.files.length"
                        @click="openMoveModal()"
                        class="px-3.5 py-2 bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 hover:bg-indigo-500/20 text-xs font-semibold rounded-xl transition-all"
                    >
                        Move Selected ({{ selectedItems.folders.length + selectedItems.files.length }})
                    </button>
                </div>
            </div>

            <!-- Breadcrumbs -->
            <nav class="flex items-center space-x-2 text-xs text-slate-400 bg-slate-950/20 py-2.5 px-4 rounded-xl border border-slate-800/40">
                <button 
                    @click="navigateToFolder(null)" 
                    class="hover:text-slate-200 font-semibold transition-colors flex items-center space-x-1"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Root</span>
                </button>
                <template v-for="(crumb, idx) in breadcrumbs" :key="crumb.id">
                    <span class="text-slate-600 font-medium">/</span>
                    <button 
                        @click="navigateToFolder(crumb.id)" 
                        :class="[idx === breadcrumbs.length - 1 ? 'text-indigo-400 pointer-events-none' : 'hover:text-slate-200', 'transition-colors font-semibold']"
                    >
                        {{ crumb.name }}
                    </button>
                </template>
            </nav>

            <!-- Workspace & Details Sidebar Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 flex-1 min-h-0">
                <!-- Media Explorer grid -->
                <div 
                    :class="[
                        dragOverActive ? 'border-2 border-dashed border-indigo-500 bg-indigo-500/5' : '',
                        'lg:col-span-3 p-6 bg-slate-950/20 border border-slate-800 rounded-2xl flex flex-col justify-between relative overflow-y-auto min-h-[450px]'
                    ]"
                    @dragleave="onDragLeave"
                >
                    <!-- Drag & Drop visual overlay -->
                    <div v-if="dragOverActive" class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none bg-slate-950/80 rounded-2xl z-10">
                        <svg class="h-16 w-16 text-indigo-400 animate-bounce mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        <p class="text-lg font-bold text-slate-100">Drop files to upload here</p>
                        <p class="text-xs text-slate-400 mt-1">Files will convert to WebP and scale to responsive resolutions</p>
                    </div>

                    <!-- Loader overlay -->
                    <div v-if="isLoading" class="absolute inset-0 flex items-center justify-center bg-slate-950/30 rounded-2xl z-10">
                        <div class="h-10 w-10 border-4 border-indigo-500/20 border-t-indigo-500 rounded-full animate-spin"></div>
                    </div>

                    <!-- Uploading indicators -->
                    <div v-if="isUploading" class="mb-4 p-4 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="h-5 w-5 border-2 border-indigo-500/20 border-t-indigo-500 rounded-full animate-spin"></div>
                            <span class="text-xs font-semibold text-slate-300">Uploading & converting files...</span>
                        </div>
                    </div>

                    <!-- Grid Layout -->
                    <div v-if="!folders.length && !files.length" class="flex-1 flex flex-col items-center justify-center text-slate-500 py-12">
                        <svg class="h-12 w-12 text-slate-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h5l2 2h9a2 2 0 012 2v8a2 2 0 01-2 2H5z" />
                        </svg>
                        <p class="text-sm font-medium">This folder is empty.</p>
                        <p class="text-xs text-slate-600 mt-1">Drag files here or create subfolders to organize your library.</p>
                    </div>

                    <div v-else class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-4">
                        <!-- Folders -->
                        <div 
                            v-for="folder in folders" 
                            :key="folder.id"
                            @click="selectFolder(folder)"
                            @dblclick="navigateToFolder(folder.id)"
                            :class="[
                                selectedFolder?.id === folder.id ? 'border-indigo-600 bg-slate-900/60 shadow-lg shadow-indigo-500/5' : 'border-slate-800/80 bg-slate-950/40 hover:bg-slate-900/30',
                                'p-4 rounded-xl border flex flex-col justify-between cursor-pointer group transition-all relative'
                            ]"
                        >
                            <!-- Checkbox and Actions -->
                            <div class="flex items-center justify-between mb-3">
                                <input 
                                    type="checkbox" 
                                    :checked="isItemSelected('folders', folder.id)"
                                    @click.stop="toggleItemSelection('folders', folder.id)"
                                    class="h-3.5 w-3.5 text-indigo-600 bg-slate-900 border-slate-700 rounded focus:ring-indigo-500 cursor-pointer"
                                />
                                
                                <div class="opacity-0 group-hover:opacity-100 flex items-center space-x-1.5 transition-opacity">
                                    <button 
                                        @click.stop="renameTarget = { type: 'folder', id: folder.id, name: folder.name }; showRenameModal = true;"
                                        class="p-1 hover:text-indigo-400 text-slate-500 transition-colors"
                                        title="Rename"
                                    >
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                    <button 
                                        @click.stop="deleteFolder(folder.id)"
                                        class="p-1 hover:text-rose-400 text-slate-500 transition-colors"
                                        title="Delete"
                                    >
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3 truncate">
                                <svg class="h-9 w-9 text-indigo-400/90 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V8a2 2 0 00-2-2h-5L9 4H4z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-xs font-semibold text-slate-200 truncate">{{ folder.name }}</span>
                            </div>
                        </div>

                        <!-- Files -->
                        <div 
                            v-for="file in files" 
                            :key="file.id"
                            @click="selectFile(file)"
                            @dblclick="showPreviewModal = true"
                            :class="[
                                selectedFile?.id === file.id ? 'border-indigo-600 bg-slate-900/60 shadow-lg shadow-indigo-500/5' : 'border-slate-800/80 bg-slate-950/40 hover:bg-slate-900/30',
                                'p-3 rounded-xl border flex flex-col justify-between cursor-pointer group transition-all relative'
                            ]"
                        >
                            <!-- Selection Checkbox and actions -->
                            <div class="flex items-center justify-between mb-2">
                                <input 
                                    type="checkbox" 
                                    :checked="isItemSelected('files', file.id)"
                                    @click.stop="toggleItemSelection('files', file.id)"
                                    class="h-3.5 w-3.5 text-indigo-600 bg-slate-900 border-slate-700 rounded focus:ring-indigo-500 cursor-pointer"
                                />

                                <div class="opacity-0 group-hover:opacity-100 flex items-center space-x-1.5 transition-opacity">
                                    <button 
                                        @click.stop="renameTarget = { type: 'file', id: file.id, name: file.name }; showRenameModal = true;"
                                        class="p-1 hover:text-indigo-400 text-slate-500 transition-colors"
                                        title="Rename"
                                    >
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                    <button 
                                        @click.stop="deleteFile(file.id)"
                                        class="p-1 hover:text-rose-400 text-slate-500 transition-colors"
                                        title="Delete File"
                                    >
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Thumbnail or Icon preview container -->
                            <div class="h-28 w-full bg-slate-900 rounded-lg overflow-hidden flex items-center justify-center mb-3.5 border border-slate-800">
                                <img 
                                    v-if="file.mime_type.startsWith('image/')"
                                    :src="file.conversions_urls?.thumbnail || file.url"
                                    :alt="file.alt_text"
                                    class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300"
                                    loading="lazy"
                                />
                                <div 
                                    v-else 
                                    :class="[
                                        getFileIconColor(file.mime_type),
                                        'h-12 w-12 rounded-xl flex items-center justify-center font-bold text-xs border'
                                    ]"
                                >
                                    {{ getFileIcon(file.mime_type) }}
                                </div>
                            </div>

                            <span class="text-[11px] font-semibold text-slate-200 truncate block">{{ file.name }}</span>
                            <span class="text-[10px] text-slate-500 mt-0.5 block">{{ formatBytes(file.size) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Sidebar details drawer -->
                <div class="p-6 bg-slate-950/40 border border-slate-800 rounded-2xl flex flex-col min-w-0">
                    <h2 class="text-sm font-bold text-slate-200 border-b border-slate-800 pb-3">Item Details</h2>
                    
                    <div v-if="selectedFile" class="flex-1 flex flex-col justify-between mt-6 space-y-6 overflow-y-auto">
                        <div class="space-y-6">
                            <!-- Visual preview -->
                            <div class="h-44 bg-slate-900 rounded-xl overflow-hidden flex items-center justify-center border border-slate-800 relative group">
                                <img 
                                    v-if="selectedFile.mime_type.startsWith('image/')"
                                    :src="selectedFile.url"
                                    class="h-full w-full object-contain"
                                />
                                <div 
                                    v-else 
                                    :class="[
                                        getFileIconColor(selectedFile.mime_type),
                                        'h-16 w-16 rounded-xl flex items-center justify-center font-bold text-sm border'
                                    ]"
                                >
                                    {{ getFileIcon(selectedFile.mime_type) }}
                                </div>
                                <button 
                                    @click="showPreviewModal = true"
                                    class="absolute inset-0 bg-slate-950/60 opacity-0 group-hover:opacity-100 flex items-center justify-center text-xs font-semibold transition-opacity"
                                >
                                    View Full Screen
                                </button>
                            </div>

                            <!-- Meta Info -->
                            <div class="space-y-3">
                                <div>
                                    <label class="text-[10px] uppercase font-bold tracking-wider text-slate-500">File Name</label>
                                    <input 
                                        v-model="selectedFile.name"
                                        type="text"
                                        class="w-full bg-slate-900 border border-slate-800 focus:border-indigo-500 focus:outline-none rounded-lg text-xs text-slate-200 px-3 py-2 mt-1.5 transition-colors"
                                    />
                                </div>

                                <div>
                                    <label class="text-[10px] uppercase font-bold tracking-wider text-slate-500">Alt Text (SEO)</label>
                                    <input 
                                        v-model="selectedFile.alt_text"
                                        type="text"
                                        placeholder="Add descriptive alt text"
                                        class="w-full bg-slate-900 border border-slate-800 focus:border-indigo-500 focus:outline-none rounded-lg text-xs text-slate-200 px-3 py-2 mt-1.5 transition-colors"
                                    />
                                </div>

                                <div class="grid grid-cols-2 gap-4 pt-2 text-[11px]">
                                    <div>
                                        <span class="text-slate-500 block">File Size:</span>
                                        <span class="text-slate-300 font-semibold mt-0.5 block">{{ formatBytes(selectedFile.size) }}</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-500 block">Type:</span>
                                        <span class="text-slate-300 font-semibold mt-0.5 block truncate">{{ selectedFile.mime_type }}</span>
                                    </div>
                                    <div class="col-span-2">
                                        <span class="text-slate-500 block">Public URL:</span>
                                        <div class="flex items-center space-x-1.5 mt-1">
                                            <input 
                                                readonly
                                                :value="selectedFile.url"
                                                class="w-full bg-slate-900 border border-slate-800/80 rounded-lg text-[10px] text-slate-400 px-2 py-1.5 focus:outline-none"
                                            />
                                            <button 
                                                @click="copyToClipboard(selectedFile.url)"
                                                class="p-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg border border-slate-700 transition-colors"
                                                title="Copy public URL"
                                            >
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar Actions -->
                        <div class="flex items-center gap-3 pt-4 border-t border-slate-800/80">
                            <button 
                                @click="updateFileMetadata()"
                                class="flex-1 py-2 text-xs font-semibold bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg transition-colors"
                            >
                                Save Changes
                            </button>
                            <button 
                                @click="openMoveModal('files', selectedFile.id)"
                                class="p-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-lg text-slate-300 transition-colors"
                                title="Move File"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                            </button>
                            <button 
                                @click="deleteFile(selectedFile.id)"
                                class="p-2 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/30 text-rose-400 rounded-lg transition-colors"
                                title="Delete File"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div v-else-if="selectedFolder" class="flex-1 flex flex-col justify-between mt-6">
                        <div class="space-y-6">
                            <div class="h-32 bg-slate-900 rounded-xl flex items-center justify-center border border-slate-800 text-indigo-400">
                                <svg class="h-16 w-16" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V8a2 2 0 00-2-2h-5L9 4H4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="space-y-2.5">
                                <label class="text-[10px] uppercase font-bold tracking-wider text-slate-500">Folder Name</label>
                                <p class="text-sm font-semibold text-slate-200">{{ selectedFolder.name }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 pt-4 border-t border-slate-800/80">
                            <button 
                                @click="renameTarget = { type: 'folder', id: selectedFolder.id, name: selectedFolder.name }; showRenameModal = true;"
                                class="flex-1 py-2 text-xs font-semibold bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 rounded-lg transition-colors"
                            >
                                Rename Folder
                            </button>
                            <button 
                                @click="openMoveModal('folders', selectedFolder.id)"
                                class="p-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-lg text-slate-300 transition-colors"
                                title="Move Folder"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                            </button>
                            <button 
                                @click="deleteFolder(selectedFolder.id)"
                                class="p-2 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/30 text-rose-400 rounded-lg transition-colors"
                                title="Delete Folder"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div v-else class="flex-1 flex items-center justify-center text-slate-600 text-xs py-12 text-center">
                        Select a file or folder to view its properties and perform actions.
                    </div>
                </div>
            </div>
        </div>

        <!-- NEW FOLDER MODAL -->
        <div v-if="showNewFolderModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" @click="showNewFolderModal = false"></div>
            <div class="relative bg-slate-900 border border-slate-800 rounded-2xl max-w-md w-full p-6 shadow-2xl z-10">
                <h3 class="text-sm font-bold text-slate-200">Create New Folder</h3>
                <input 
                    v-model="newFolderName"
                    type="text" 
                    placeholder="Folder Name"
                    class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:outline-none rounded-xl text-xs text-slate-200 px-4 py-3 mt-4 transition-colors"
                    @keyup.enter="createFolder()"
                />
                <div class="flex items-center justify-end space-x-3 mt-6">
                    <button 
                        @click="showNewFolderModal = false" 
                        class="px-4 py-2 text-xs font-semibold bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl transition-colors"
                    >
                        Cancel
                    </button>
                    <button 
                        @click="createFolder()"
                        class="px-4 py-2 text-xs font-semibold bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl shadow-md shadow-indigo-600/10 transition-colors"
                    >
                        Create
                    </button>
                </div>
            </div>
        </div>

        <!-- RENAME MODAL -->
        <div v-if="showRenameModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" @click="showRenameModal = false"></div>
            <div class="relative bg-slate-900 border border-slate-800 rounded-2xl max-w-md w-full p-6 shadow-2xl z-10">
                <h3 class="text-sm font-bold text-slate-200">Rename Item</h3>
                <input 
                    v-model="renameTarget.name"
                    type="text" 
                    class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:outline-none rounded-xl text-xs text-slate-200 px-4 py-3 mt-4 transition-colors"
                    @keyup.enter="renameItem()"
                />
                <div class="flex items-center justify-end space-x-3 mt-6">
                    <button 
                        @click="showRenameModal = false" 
                        class="px-4 py-2 text-xs font-semibold bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl transition-colors"
                    >
                        Cancel
                    </button>
                    <button 
                        @click="renameItem()"
                        class="px-4 py-2 text-xs font-semibold bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl shadow-md shadow-indigo-600/10 transition-colors"
                    >
                        Save
                    </button>
                </div>
            </div>
        </div>

        <!-- MOVE MODAL -->
        <div v-if="showMoveModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" @click="showMoveModal = false"></div>
            <div class="relative bg-slate-900 border border-slate-800 rounded-2xl max-w-md w-full p-6 shadow-2xl z-10">
                <h3 class="text-sm font-bold text-slate-200">Move Items</h3>
                <p class="text-xs text-slate-400 mt-1">Select the target folder where you want to move the selected items.</p>
                
                <select 
                    v-model="moveTargetFolderId"
                    class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:outline-none rounded-xl text-xs text-slate-200 px-4 py-3 mt-4 transition-colors cursor-pointer"
                >
                    <option value="" disabled>Choose target destination...</option>
                    <option value="root">Root Folder (Main Directory)</option>
                    <option v-for="folder in allFoldersList" :key="folder.id" :value="folder.id">
                        {{ folder.name }}
                    </option>
                </select>

                <div class="flex items-center justify-end space-x-3 mt-6">
                    <button 
                        @click="showMoveModal = false" 
                        class="px-4 py-2 text-xs font-semibold bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl transition-colors"
                    >
                        Cancel
                    </button>
                    <button 
                        @click="executeMove()"
                        :disabled="isMoveLoading || !moveTargetFolderId"
                        class="px-4 py-2 text-xs font-semibold bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl disabled:opacity-50 transition-colors flex items-center space-x-1.5"
                    >
                        <span v-if="isMoveLoading" class="h-3 w-3 border border-white/20 border-t-white rounded-full animate-spin"></span>
                        <span>Move</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- FULL PREVIEW MODAL -->
        <div v-if="showPreviewModal && selectedFile" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-950/90 backdrop-blur-sm" @click="showPreviewModal = false"></div>
            <div class="relative max-w-5xl w-full flex flex-col z-10 max-h-[90vh]">
                <button 
                    @click="showPreviewModal = false"
                    class="absolute -top-10 right-0 text-slate-400 hover:text-white text-xs font-semibold flex items-center space-x-1"
                >
                    <span>Close</span>
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                
                <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden flex items-center justify-center p-4">
                    <img 
                        v-if="selectedFile.mime_type.startsWith('image/')"
                        :src="selectedFile.url"
                        class="max-h-[75vh] max-w-full object-contain rounded-lg"
                    />
                    <video 
                        v-else-if="selectedFile.mime_type.startsWith('video/')"
                        :src="selectedFile.url"
                        controls
                        class="max-h-[75vh] max-w-full rounded-lg"
                    ></video>
                    <div v-else class="text-center py-20 text-slate-400">
                        No live preview available for this file type.
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-between text-xs text-slate-400 px-2">
                    <span class="font-semibold text-slate-300">{{ selectedFile.name }}</span>
                    <span>Dimensions / Type: {{ selectedFile.mime_type }} | Size: {{ formatBytes(selectedFile.size) }}</span>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
