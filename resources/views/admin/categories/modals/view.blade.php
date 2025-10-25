<!-- View Modal -->
<div x-show="openViewId !== null" x-cloak
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white p-6 rounded-lg w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">View Category</h2>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Name</label>
            <p x-text="selectedCategory.name" class="border px-3 py-2 rounded bg-gray-100"></p>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Status</label>
            <p x-text="selectedCategory.status ? 'Active' : 'Inactive'" class="border px-3 py-2 rounded bg-gray-100">
            </p>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Image</label>
            <template x-if="selectedCategory.image">
                <img :src="'/admin-assets/categories/' + selectedCategory.image" class="w-32 h-32 rounded">
            </template>
            <template x-if="!selectedCategory.image">
                <div class="w-32 h-32 bg-gray-200 rounded flex items-center justify-center text-gray-500">
                    N/A
                </div>
            </template>
        </div>

        <div class="flex justify-end">
            <button @click="openViewId = null" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">Close</button>
        </div>
    </div>
</div>