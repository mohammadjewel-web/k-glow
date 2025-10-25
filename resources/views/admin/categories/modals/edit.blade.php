<!-- Edit Modal -->
<div x-show="openEditId !== null" x-cloak
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white p-6 rounded-lg w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">Edit Category</h2>

        <form :action="'/categories/' + selectedCategory.id" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Name</label>
                <input type="text" name="name" x-model="selectedCategory.name"
                    class="w-full border px-3 py-2 rounded focus:outline-none focus:ring-1 focus:ring-[--brand-orange]">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Status</label>
                <select name="status" x-model="selectedCategory.status"
                    class="w-full border px-3 py-2 rounded focus:outline-none focus:ring-1 focus:ring-[--brand-orange]">
                    <option :value="1">Active</option>
                    <option :value="0">Inactive</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Image</label>
                <input type="file" name="image" class="w-full border px-3 py-2 rounded">
                <template x-if="selectedCategory.image">
                    <img :src="'/admin-assets/categories/' + selectedCategory.image" class="w-20 h-20 mt-2 rounded">
                </template>
            </div>

            <div class="flex justify-end space-x-2">
                <button type="button" @click="openEditId = null"
                    class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">Cancel</button>
                <button type="submit"
                    class="px-4 py-2 rounded bg-[--brand-orange] text-white hover:bg-orange-600">Update</button>
            </div>
        </form>
    </div>
</div>