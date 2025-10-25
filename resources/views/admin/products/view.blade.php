<!-- View Product Modal -->

<div x-show="openViewModal" x-cloak x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-90"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">

    <div class="bg-white rounded-xl w-full max-w-5xl p-6 relative shadow-xl overflow-y-auto max-h-[90vh]">

        <!-- Header -->
        <div class="flex justify-between items-center border-b pb-2 mb-4">
            <h2 class="text-2xl font-bold text-[--brand-orange]">View Product</h2>
            <button @click="openViewModal=false" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>

        <div class="space-y-4">

            <!-- Images -->
            <div class="flex flex-wrap gap-4">
                <img :src="'/admin-assets/products/' + selectedProduct.thumbnail" class="w-32 h-32 rounded mb-4" />
                <div class="flex flex-wrap gap-2">
                    <template x-for="img in selectedProduct.images" :key="img">
                        <img :src="'/admin-assets/products/' + img" class="w-24 h-24 rounded mr-2 inline-block" />
                    </template>
                </div>
            </div>

            <!-- Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="font-semibold">Name:</span> <span x-text="selectedProduct.name"></span>
                </div>
                <div>
                    <span class="font-semibold">Slug:</span> <span x-text="selectedProduct.slug"></span>
                </div>
                <div>
                    <span class="font-semibold">Category:</span> <span x-text="selectedProduct.category?.name"></span>
                </div>
                <div>
                    <span class="font-semibold">Subcategory:</span> <span
                        x-text="selectedProduct.subcategory?.name"></span>
                </div>
                <div>
                    <span class="font-semibold">Brand:</span> <span x-text="selectedProduct.brand?.name"></span>
                </div>
            </div>

            <!-- Pricing & Stock -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <span class="font-semibold">Price:</span> $<span x-text="selectedProduct.price"></span>
                </div>
                <div>
                    <span class="font-semibold">Discount Price:</span> $<span
                        x-text="selectedProduct.discount_price"></span>
                </div>
                <div>
                    <span class="font-semibold">SKU:</span> <span x-text="selectedProduct.sku"></span>
                </div>
                <div>
                    <span class="font-semibold">Barcode:</span> <span x-text="selectedProduct.barcode"></span>
                </div>
                <div>
                    <span class="font-semibold">Stock:</span> <span x-text="selectedProduct.stock"></span>
                </div>
                <div>
                    <span class="font-semibold">Weight:</span> <span x-text="selectedProduct.weight"></span>
                </div>
                <div>
                    <span class="font-semibold">Dimensions:</span> <span x-text="selectedProduct.dimensions"></span>
                </div>
            </div>

            <!-- Attributes -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <span class="font-semibold">Colors:</span> <span x-text="selectedProduct.colors"></span>
                </div>
                <div>
                    <span class="font-semibold">Sizes:</span> <span x-text="selectedProduct.sizes"></span>
                </div>
                <div>
                    <span class="font-semibold">Material:</span> <span x-text="selectedProduct.material"></span>
                </div>
                <div class="md:col-span-3">
                    <span class="font-semibold">Tags:</span> <span x-text="selectedProduct.tags"></span>
                </div>
            </div>

            <!-- Description -->
            <div>
                <span class="font-semibold">Short Description:</span>
                <p x-text="selectedProduct.short_description"></p>
            </div>
            <div>
                <span class="font-semibold">Description:</span>
                <p x-text="selectedProduct.description"></p>
            </div>

            <!-- SEO -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="font-semibold">Meta Title:</span> <span x-text="selectedProduct.meta_title"></span>
                </div>
                <div>
                    <span class="font-semibold">Meta Description:</span> <span
                        x-text="selectedProduct.meta_description"></span>
                </div>
            </div>

            <!-- Flags -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <span class="font-semibold">Status:</span> <span
                        x-text="selectedProduct.status ? 'Active' : 'Inactive'"></span>
                </div>
                <div>
                    <span class="font-semibold">Featured:</span> <span
                        x-text="selectedProduct.is_featured ? 'Yes' : 'No'"></span>
                </div>
                <div>
                    <span class="font-semibold">New Arrival:</span> <span
                        x-text="selectedProduct.is_new ? 'Yes' : 'No'"></span>
                </div>
                <div>
                    <span class="font-semibold">Best Seller:</span> <span
                        x-text="selectedProduct.is_best_seller ? 'Yes' : 'No'"></span>
                </div>
                <div>
                    <span class="font-semibold">Flash Sale:</span> <span
                        x-text="selectedProduct.is_flash_sale ? 'Yes' : 'No'"></span>
                </div>
            </div>

            <!-- Flash Sale Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <span class="font-semibold">Flash Sale Price:</span> $<span
                        x-text="selectedProduct.flash_sale_price"></span>
                </div>
                <div>
                    <span class="font-semibold">Flash Sale Start:</span> <span
                        x-text="selectedProduct.flash_sale_start"></span>
                </div>
                <div>
                    <span class="font-semibold">Flash Sale End:</span> <span
                        x-text="selectedProduct.flash_sale_end"></span>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="font-semibold">Views Count:</span> <span x-text="selectedProduct.views_count"></span>
                </div>
                <div>
                    <span class="font-semibold">Sold Count:</span> <span x-text="selectedProduct.sold_count"></span>
                </div>
            </div>

        </div>

        <!-- Footer -->
        <div class="flex justify-end mt-6">
            <button @click="openViewModal=false"
                class="px-5 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition">Close</button>
        </div>

    </div>
</div>