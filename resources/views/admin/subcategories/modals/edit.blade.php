<div x-show="openEditId" x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">
    <div class="bg-white rounded-xl w-full max-w-lg p-6 relative shadow-xl">

        <div class="flex justify-between items-center border-b pb-2 mb-4">
            <h2 class="text-2xl font-bold text-[--brand-orange]">Edit Subcategory</h2>
            <button @click="closeEditModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>

        <form :action="`/subcategories/${selectedSubcategory.id}`" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label>Name</label>
                    <input type="text" name="name" x-model="selectedSubcategory.name"
                        class="w-full px-4 py-2 border rounded-lg">
                </div>

                <div>
                    <label>Parent Category</label>
                    <select name="category_id" x-model="selectedSubcategory.category_id"
                        class="w-full px-4 py-2 border rounded-lg">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>Description</label>
                    <textarea name="description" x-model="selectedSubcategory.description"
                        class="w-full px-4 py-2 border rounded-lg"></textarea>
                </div>

                <div>
                    <label>Image</label>
                    <input type="file" name="image" class="w-full px-3 py-2 border rounded-lg">
                    <template x-if="selectedSubcategory.image">
                        <img :src="`/admin-assets/subcategories/${selectedSubcategory.image}`"
                            class="w-20 h-20 mt-2 rounded">
                    </template>
                </div>

                <div>
                    <label>
                        <input type="checkbox" name="status" value="1" x-model="selectedSubcategory.status">
                        Active
                    </label>
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" @click="closeEditModal()" class="px-5 py-2 bg-gray-200 rounded">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-[--brand-orange] text-white rounded">Update</button>
            </div>
        </form>
    </div>
</div>