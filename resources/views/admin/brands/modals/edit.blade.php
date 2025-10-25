<!-- Edit Brand Modal -->
<div x-show="openEditId" x-cloak x-transition
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">
    <div class="bg-white rounded-xl w-full max-w-lg p-6 relative shadow-xl">
        <div class="flex justify-between items-center border-b pb-2 mb-4">
            <h2 class="text-2xl font-bold text-[--brand-orange]">Edit Brand</h2>
            <button @click="openEditId = null" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>

        <form :action="`/brands/${selectedBrand.id}`" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block font-semibold mb-1">Name</label>
                    <input type="text" name="name" :value="selectedBrand.name" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-[--brand-orange] focus:border-[--brand-orange] transition">
                </div>
                <div>
                    <label class="block font-semibold mb-1">Logo</label>
                    <input type="file" name="logo" class="w-full px-3 py-2 border rounded-lg">
                    <template x-if="selectedBrand.logo">
                        <img :src="'/admin-assets/brands/' + selectedBrand.logo" class="w-16 h-16 mt-2 rounded" />
                    </template>
                </div>
                <div>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="status" value="1" :checked="selectedBrand.status"
                            class="form-checkbox h-5 w-5 text-[--brand-orange]">
                        <span class="ml-2 font-medium">Active</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" @click="openEditId = null"
                    class="px-5 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition">Cancel</button>
                <button type="submit"
                    class="px-5 py-2 bg-[--brand-orange] text-white rounded-lg hover:bg-orange-600 transition">Update</button>
            </div>
        </form>
    </div>
</div>