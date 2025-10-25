<!-- Create Category Modal -->
<div x-show="openCreateModal" x-cloak x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-90"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">
    <div class="bg-white rounded-xl w-full max-w-lg p-6 relative shadow-xl">

        <!-- Header -->
        <div class="flex justify-between items-center border-b pb-2 mb-4">
            <h2 class="text-2xl font-bold text-[--brand-orange]">Add Category</h2>
            <button @click="openCreateModal=false" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>

        <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block font-semibold mb-1">Name</label>
                    <input type="text" name="name" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-[--brand-orange] focus:border-[--brand-orange] transition">
                </div>
                <div>
                    <label class="block font-semibold mb-1">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-[--brand-orange] focus:border-[--brand-orange] transition"></textarea>
                </div>
                <div>
                    <label class="block font-semibold mb-1">Image</label>
                    <input type="file" name="image" class="w-full px-3 py-2 border rounded-lg">
                </div>
                <div>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="status" value="1" checked
                            class="form-checkbox h-5 w-5 text-[--brand-orange]">
                        <span class="ml-2 font-medium">Active</span>
                    </label>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" @click="openCreateModal=false"
                    class="px-5 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition">Cancel</button>
                <button type="submit"
                    class="px-5 py-2 bg-[--brand-orange] text-white rounded-lg hover:bg-orange-600 transition">Save</button>
            </div>
        </form>
    </div>
</div>