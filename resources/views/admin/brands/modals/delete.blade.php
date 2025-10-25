<!-- Delete Brand Modal -->
<div x-show="openDeleteId" x-cloak x-transition
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">
    <div class="bg-white rounded-xl w-full max-w-md p-6 relative shadow-xl">
        <div class="flex justify-between items-center border-b pb-2 mb-4">
            <h2 class="text-2xl font-bold text-red-600">Delete Brand</h2>
            <button @click="openDeleteId = null" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>

        <p>Are you sure you want to delete <strong x-text="selectedBrand.name"></strong>?</p>

        <form :action="`/brands/${selectedBrand.id}`" method="POST" class="mt-4">
            @csrf
            @method('DELETE')
            <div class="flex justify-end space-x-3">
                <button type="button" @click="openDeleteId = null"
                    class="px-5 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition">Cancel</button>
                <button type="submit"
                    class="px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">Delete</button>
            </div>
        </form>
    </div>
</div>