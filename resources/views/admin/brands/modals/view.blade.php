<!-- View Brand Modal -->
<div x-show="openViewId" x-cloak x-transition
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">
    <div class="bg-white rounded-xl w-full max-w-lg p-6 relative shadow-xl">
        <div class="flex justify-between items-center border-b pb-2 mb-4">
            <h2 class="text-2xl font-bold text-[--brand-orange]">View Brand</h2>
            <button @click="openViewId = null" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>

        <div class="space-y-4">
            <div>
                <h3 class="font-semibold">Name:</h3>
                <p x-text="selectedBrand.name"></p>
            </div>
            <div>
                <h3 class="font-semibold">Logo:</h3>
                <template x-if="selectedBrand.logo">
                    <img :src="'/admin-assets/brands/' + selectedBrand.logo" class="w-24 h-24 rounded mt-2" />
                </template>
                <template x-if="!selectedBrand.logo">
                    <div class="w-24 h-24 bg-gray-200 rounded flex items-center justify-center text-gray-500 mt-2">N/A
                    </div>
                </template>
            </div>
            <div>
                <h3 class="font-semibold">Status:</h3>
                <span :class="selectedBrand.status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                    class="px-2 py-1 rounded" x-text="selectedBrand.status ? 'Active' : 'Inactive'"></span>
            </div>
        </div>

        <div class="flex justify-end mt-6">
            <button @click="openViewId = null"
                class="px-5 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition">Close</button>
        </div>
    </div>
</div>