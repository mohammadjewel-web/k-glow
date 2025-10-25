<!-- Delete Category Modal -->
<div x-show="openDeleteId" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg w-full max-w-sm p-6 relative">
        <h2 class="text-xl font-bold mb-4 text-red-600">Confirm Delete</h2>
        <p class="mb-4">Are you sure you want to delete this category?</p>
        <form :action="'/categories/' + openDeleteId" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex justify-end space-x-2">
                <button type="button" @click="openDeleteId = null"
                    class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Delete</button>
            </div>
        </form>
    </div>
</div>