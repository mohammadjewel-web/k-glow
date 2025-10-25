<!-- View Subcategory Modal -->
<div x-show="openViewId" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-xl w-full max-w-lg p-6 shadow-xl">
        <div class="flex justify-between items-center border-b pb-2 mb-4">
            <h2 class="text-2xl font-bold text-[--brand-orange]">View Subcategory</h2>
            <button @click="openViewId = null" class="text-gray-500 text-2xl">&times;</button>
        </div>

        <div class="space-y-4">
            <p><strong>Name:</strong> <span x-text="selectedSubcategory.name"></span></p>
            <p><strong>Description:</strong> <span x-text="selectedSubcategory.description || 'N/A'"></span></p>
            <p><strong>Category:</strong> <span x-text="selectedSubcategory.category_name || 'N/A'"></span></p>
            <p><strong>Status:</strong>
                <span
                    :class="selectedSubcategory.status ? 'bg-green-100 text-green-800 px-2 py-1 rounded' : 'bg-red-100 text-red-800 px-2 py-1 rounded'"
                    x-text="selectedSubcategory.status ? 'Active' : 'Inactive'"></span>
            </p>
            <div>
                <strong>Image:</strong>
                <template x-if="selectedSubcategory.image">
                    <img :src="'/admin-assets/subcategories/' + selectedSubcategory.image"
                        class="w-32 h-32 rounded mt-2">
                </template>
                <template x-if="!selectedSubcategory.image">
                    <div class="w-32 h-32 bg-gray-200 rounded flex items-center justify-center mt-2">N/A</div>
                </template>
            </div>
        </div>

        <div class="flex justify-end mt-6">
            <button @click="openViewId = null" class="px-5 py-2 bg-gray-200 rounded hover:bg-gray-300">Close</button>
        </div>
    </div>
</div>