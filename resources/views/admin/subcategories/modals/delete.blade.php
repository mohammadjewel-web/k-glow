<!-- Delete Subcategory Modal -->
<div x-show="openDeleteId" x-cloak x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-90"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">

    <div class="bg-white rounded-xl w-full max-w-md p-6 relative shadow-xl">

        <!-- Header -->
        <div class="flex justify-between items-center border-b pb-2 mb-4">
            <h2 class="text-2xl font-bold text-red-600">Delete Subcategory</h2>
            <button @click="openDeleteId=false" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>

        <p>Are you sure you want to delete <span class="font-semibold" x-text="selectedCategory.name"></span>?</p>

        <div class="flex justify-end space-x-3 mt-6">
            <button @click="openDeleteId=false"
                class="px-5 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition">Cancel</button>
            <form :action="`/subcategories/${selectedSubcategory.id}`" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    Delete
                </button>
            </form>
        </div>

    </div>
</div>