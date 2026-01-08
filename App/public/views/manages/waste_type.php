<div class="space-y-4 w-full">
    <div class="app-card p-6 overflow-x-auto w-full hidden md:block">
        <h2 class="text-xl font-bold text-gray-700">ภาพรวมการจัดการประเภทและชนิดขยะ</h2>
        <p class="text-gray-500 text-sm">เลือกหมวดหมู่ด้านล่างเพื่อจัดการประเภทขยะและราคา</p>
    </div>

    <div x-data="WasteCategoryTypeManagement()" x-init="init()" class="app-card p-6">

        <h2 class="text-xl font-bold mb-2">
            หมวดหมู่ขยะ
        </h2>

        <div class="flex flex-col md:flex-row justify-between gap-4">
            <div class="flex gap-2">
                <div @click="openCreateCategoryDialog"
                    :class="createCategoryDialogShow && !isEditingCategory && 'bg-emerald-300'"
                    class="group cursor-pointer flex items-center py-2 px-4 border-2 border-emerald-500 rounded-full hover:bg-emerald-100 space-x-1 w-fit transition-colors font-medium text-emerald-700">
                    <button class="group-hover:rotate-90 duration-300 focus:outline-none" title="Add New">
                        <svg class="stroke-emerald-600 fill-none group-active:stroke-emerald-300 group-active:duration-0 duration-300"
                            viewBox="0 0 24 24" height="24" width="24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-width="1.5"
                                d="M12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22Z">
                            </path>
                            <path stroke-width="1.5" d="M8 12H16"></path>
                            <path stroke-width="1.5" d="M12 16V8"></path>
                        </svg>
                    </button>
                    <span class="font-medium">เพิ่ม</span>
                </div>

                <button x-show="selectedCategory" @click="openEditCategoryDialog(selectedCategory)"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="flex items-center space-x-1 bg-emerald-100 text-emerald-700 px-4 py-2 rounded-full hover:bg-emerald-200 border border-emerald-200 font-medium text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>แก้ไข</span>
                </button>

                <button x-show="selectedCategory" @click="confirmDeleteCurrentCategory"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="flex items-center space-x-1 bg-red-100 text-red-700 px-4 py-2 rounded-full hover:bg-red-200 border border-red-200 font-medium text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span x-text="`ลบ`"></span>
                </button>
            </div>

            <div class="">
                <label class="mb-2 text-sm font-medium text-gray-900 sr-only" for="default-search">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
                            class="w-4 h-4 text-gray-500">
                            <path d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" stroke-width="2"
                                stroke-linejoin="round" stroke-linecap="round" stroke="currentColor"></path>
                        </svg>
                    </div>
                    <input required="" placeholder="Search" x-model="searchCategoryQuery"
                        class="block w-full py-1 ps-10 pe-10 text-sm text-gray-900 border border-gray-300 rounded bg-gray-50 focus:ring-blue-500 outline-none focus:border-blue-500"
                        id="default-search" type="text" />
                </div>
            </div>
        </div>

        <span class="text-xs font-light text-gray-500">
            เลือกหมวดหมู่เพื่อดูรายการประเภทขยะภายใน
        </span>

        <div class="mt-2 text-lg text-gray-700 space-y-4 border-t border-gray-100 py-2">

            <div x-show="categories.length === 0 && isLoadingCategories" class="text-center py-4 text-gray-400 text-sm">
                กำลังโหลดข้อมูล...
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                <template x-for="category in filteredCategories" :key="category.waste_category_id">
                    <div class="relative px-4 py-3 border border-gray-100 shadow-xs rounded transition duration-200 text-center hover:shadow-md hover:bg-sky-50 group cursor-pointer"
                        :class="[selectedCategory?.waste_category_id === category.waste_category_id ? 'ring-2 ring-sky-400 bg-sky-50' : 'bg-white', category.waste_category_active == 0 ? 'border-l-4 border-l-orange-500' : '']"
                        @click="selectCategory(category)">

                        <span class="font-medium block truncate mt-1" x-text="category.waste_category_name"></span>
                        <span class="text-xs text-gray-400"
                            x-text="`Carbon: ${category.waste_category_co2_per_kg}`"></span>
                    </div>
                </template>
            </div>

            <div x-show="selectedCategoryShow" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-3" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-3"
                class="overflow-x-auto pt-2 border border-gray-100 rounded">

                <div class="flex justify-between items-start px-2">
                    <div class="flex flex-col">
                        <h3 class="text-xl font-bold text-blue-700 text-shadow-xs"
                            x-text="selectedCategory ? selectedCategory.waste_category_name : 'เลือกหมวดหมู่'"></h3>
                        <p class="text-xs text-gray-500" x-text="selectedCategory?.waste_category_co2_per_kg"></p>
                    </div>

                    <button @click="closeCategoryDetail()"
                        class="text-gray-400 hover:text-red-500 hover:cursor-pointer transition duration-200 hover:scale-110">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="p-4 rounded space-y-2">

                    <div class="flex flex-col md:flex-row justify-between items-center gap-2">
                        <h4 class="font-semibold text-gray-600 text-sm">รายการประเภทขยะในหมวดหมู่นี้</h4>

                        <div class="flex items-center space-x-2">
                            <button x-show="selectedTypeIds.length > 0" @click="deleteSelectedTypes"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 scale-90"
                                x-transition:enter-end="opacity-100 scale-100"
                                class="flex items-center space-x-1 bg-red-100 text-red-700 px-2 py-1 rounded-lg hover:bg-red-200 border border-red-200 text-xs font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span x-text="`ลบ (${selectedTypeIds.length})`"></span>
                            </button>

                            <div @click="openCreateTypeDialog"
                                :class="createTypeDialogShow && !isEditingType && 'bg-teal-300'"
                                class="group cursor-pointer flex items-center py-1 px-2 border-2 border-teal-500 rounded-lg hover:bg-teal-300 space-x-1 bg-white">
                                <button class="group-hover:rotate-90 duration-300 focus:outline-none">
                                    <svg class="stroke-teal-500 fill-none group-active:stroke-teal-200 group-active:duration-0 duration-300"
                                        viewBox="0 0 24 24" height="18" width="18">
                                        <path stroke-width="1.5"
                                            d="M12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22Z">
                                        </path>
                                        <path stroke-width="1.5" d="M8 12H16"></path>
                                        <path stroke-width="1.5" d="M12 16V8"></path>
                                    </svg>
                                </button>
                                <span class="text-xs font-medium">เพิ่มประเภท</span>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-hidden">
                        <template x-if="wasteTypesLoading">
                            <table class="w-full table-auto border-collapse border border-gray-300 text-sm">
                                <thead class="bg-gray-200 text-xs">
                                    <tr>
                                        <th class="px-4 py-2 w-1/12">#</th>
                                        <th class="px-4 py-2 w-4/12 text-left">ชื่อประเภทขยะ</th>
                                        <th class="px-4 py-2 w-3/12 text-right">ราคา (บาท)</th>
                                        <th class="px-4 py-2 w-4/12 text-center">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="i in 3" :key="i">
                                        <tr class="border-b border-gray-100">
                                            <td class="border border-gray-300 px-2 py-1">
                                                <div class="h-3 bg-gray-200 rounded animate-pulse w-4 mx-auto"></div>
                                            </td>
                                            <td class="border border-gray-300 px-2 py-1">
                                                <div class="h-3 bg-gray-200 rounded animate-pulse w-3/4"></div>
                                            </td>
                                            <td class="border border-gray-300 px-2 py-1">
                                                <div class="h-3 bg-gray-200 rounded animate-pulse w-1/2 ml-auto"></div>
                                            </td>
                                            <td class="border border-gray-300 px-2 py-1">
                                                <div class="h-3 bg-gray-200 rounded animate-pulse w-1/2 mx-auto"></div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </template>

                        <div x-show="!wasteTypesLoading">
                            <table class="w-full table-auto border-collapse border border-gray-300 text-sm">
                                <thead class=" bg-gray-200 text-xs">
                                    <tr>
                                        <th class="border border-gray-300 px-2 py-1 lg:px-4 lg:py-2 w-10 text-center">
                                            <input type="checkbox" @change="toggleAllTypes" id="allTypeCheckbox"
                                                :checked="isAllTypesSelected && wasteTypes.length > 0"
                                                class="p-1 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                        </th>
                                        <th class="border border-gray-300 px-2 py-1 lg:px-4 lg:py-2 text-right">
                                            ประเภท
                                        </th>
                                        <th class="border border-gray-300 px-2 py-1 lg:px-4 lg:py-2 text-right">ราคา
                                            (บาท)
                                        </th>
                                        <th class="border border-gray-300 px-2 py-1 lg:px-4 lg:py-2 text-right">
                                            สถานะ
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="type in wasteTypes" :key="type.waste_type_id">
                                        <tr class="hover:bg-amber-50 cursor-pointer border-b border-gray-100 last:border-0 transition-colors group text-sm"
                                            @click="openEditTypeDialog(type)">

                                            <td class="border border-gray-300 py-1 px-1.5 lg:px-2 lg:py-2 text-center"
                                                @click.stop>
                                                <input type="checkbox" :value="type.waste_type_id"
                                                    x-model="selectedTypeIds" :id="type.waste_type_id"
                                                    class="p-1 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                            </td>

                                            <td class="border border-gray-300 py-1 px-1.5 lg:px-2 lg:py-2 overflow-hidden text-ellipsis text-right group-hover:text-amber-700"
                                                x-text="type.waste_type_name"></td>
                                            <td class="border border-gray-300 py-1 px-1.5 lg:px-2 lg:py-2 overflow-hidden text-ellipsis text-right"
                                                x-text="Number(type.waste_type_price).toFixed(2)"></td>
                                            <td class="border border-gray-300 py-1 px-1.5 lg:px-2 lg:py-2 overflow-hidden text-ellipsis text-right group-hover:text-amber-700"
                                                x-text="type.waste_type_active ? '✅ เปิดรับฝาก' : '❌ ปิดรับฝาก'"></td>
                                        </tr>
                                    </template>
                                    <tr x-show="wasteTypes.length === 0">
                                        <td colspan="4" class="py-8 text-center text-gray-500 italic">
                                            ยังไม่มีข้อมูลประเภทขยะในหมวดหมู่นี้
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <dialog x-ref="createCategoryDialog" class="fixed inset-0 mx-auto my-auto p-0 bg-transparent z-50"
            @click.self="createCategoryDialogShow = false" @close="createCategoryDialogShow = false"
            x-show="createCategoryDialogShow" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-3" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-3"
            x-init="$watch('createCategoryDialogShow', value => value ? $refs.createCategoryDialog.showModal() : $refs.createCategoryDialog.close())">
            <div class="bg-white p-6 rounded-lg shadow-xl w-96 border border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-lg text-gray-800"
                        x-text="isEditingCategory ? 'แก้ไขหมวดหมู่' : 'เพิ่มหมวดหมู่ใหม่'"></h3>
                    <button @click="createCategoryDialogShow = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form @submit.prevent="submitCategoryForm" class="space-y-3 text-sm">
                    <div>
                        <label for="category_name" class="block text-gray-700 font-medium mb-1">ชื่อหมวดหมู่</label>
                        <input type="text" x-model="categoryForm.waste_category_name" required id="category_name"
                            class="w-full border border-gray-300 rounded p-2 focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none transition">
                    </div>
                    <div>
                        <label for="category_co2_per_kg" class="block text-gray-700 font-medium mb-1">ปริมาณการลด CO2 /
                            กิโลกรัม</label>
                        <input type="number" step="0.01" x-model="categoryForm.waste_category_co2_per_kg" required
                            id="category_co2_per_kg"
                            class="w-full border border-gray-300 rounded p-2 focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none transition">
                    </div>
                    <div x-show="isEditingCategory" class="flex items-center gap-2 mt-2">
                        <input type="checkbox" x-model="categoryForm.waste_category_active" id="category_active"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                        <label for="category_active" class="text-gray-700 font-medium">เปิดรับฝาก</label>
                    </div>
                    <div class="pt-4 flex justify-end space-x-2">
                        <button type="button" @click="createCategoryDialogShow = false"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">ยกเลิก</button>
                        <button type="submit" class="px-4 py-2 text-white rounded shadow transition-colors"
                            :class="isEditingCategory ? 'bg-amber-500 hover:bg-amber-600' : 'bg-sky-500 hover:bg-sky-600'"
                            x-text="isEditingCategory ? 'บันทึกแก้ไข' : 'ยืนยันเพิ่ม'"></button>
                    </div>
                </form>
            </div>
        </dialog>

        <dialog x-ref="createTypeDialog" class="fixed inset-0 mx-auto my-auto p-0 bg-transparent z-50"
            @click.self="createTypeDialogShow = false" @close="createTypeDialogShow = false"
            x-show="createTypeDialogShow" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-3" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-3"
            x-init="$watch('createTypeDialogShow', value => value ? $refs.createTypeDialog.showModal() : $refs.createTypeDialog.close())">
            <div class="bg-white p-6 rounded-lg shadow-xl w-96 border border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-lg text-gray-800"
                        x-text="isEditingType ? 'แก้ไขประเภทขยะ' : 'เพิ่มประเภทขยะใหม่'"></h3>
                    <button @click="createTypeDialogShow = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form @submit.prevent="submitTypeForm" class="space-y-3 text-sm">
                    <div class="bg-gray-50 p-2 rounded border border-gray-100 mb-2">
                        <span class="text-xs text-gray-500 block">หมวดหมู่</span>
                        <span class="font-semibold text-gray-800" x-text="selectedCategory?.waste_category_name"></span>
                    </div>
                    <div>
                        <label for="type_name" class="block text-gray-700 font-medium mb-1">
                            ชื่อประเภทขยะ
                        </label>
                        <input type="text" x-model="typeForm.waste_type_name" required id="type_name"
                            class="w-full border border-gray-300 rounded p-2 focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none transition">
                    </div>
                    <div>
                        <label for="type_price" class="block text-gray-700 font-medium mb-1">
                            ราคา (บาท)
                        </label>
                        <input type="number" step="0.01" min="0" x-model="typeForm.waste_type_price" required
                            id="type_price"
                            class="w-full border border-gray-300 rounded p-2 focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none transition">
                    </div>
                    <div>
                        <label for="type_co2" class="block text-gray-700 font-medium mb-1">
                            ปริมาณการลด CO2 / กิโลกรัม
                        </label>
                        <input type="number" step="0.001" min="0" x-model="typeForm.waste_type_co2" required
                            id="type_co2"
                            class="w-full border border-gray-300 rounded p-2 focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none transition">
                    </div>
                    <div x-show="isEditingType" class="flex items-center gap-2 mt-2">
                        <input type="checkbox" x-model="typeForm.waste_type_active" id="type_active"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                        <label for="type_active" class="text-gray-700 font-medium">เปิดรับฝาก</label>
                    </div>
                    <div class="pt-4 flex justify-end space-x-2">
                        <button type="button" @click="createTypeDialogShow = false"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">ยกเลิก</button>
                        <button type="submit" class="px-4 py-2 text-white rounded shadow transition-colors"
                            :class="isEditingType ? 'bg-amber-500 hover:bg-amber-600' : 'bg-teal-500 hover:bg-teal-600'"
                            x-text="isEditingType ? 'บันทึกแก้ไข' : 'ยืนยันเพิ่ม'"></button>
                    </div>
                </form>
            </div>
        </dialog>

    </div>
</div>

<script>
    function WasteCategoryTypeManagement() {
        return {
            // Data States
            categories: [],
            wasteTypes: [],
            isLoadingCategories: false,
            wasteTypesLoading: false,

            // Selection States
            selectedCategoryIds: [],
            selectedTypeIds: [],

            // UI States
            searchCategoryQuery: '',
            selectedCategory: null,
            selectedCategoryShow: false,
            createCategoryDialogShow: false,
            createTypeDialogShow: false,

            // Edit States (New)
            isEditingCategory: false,
            isEditingType: false,

            // Forms
            categoryForm: {
                waste_category_id: null,
                waste_category_name: '',
                waste_category_co2_per_kg: '',
                waste_category_active: ''
            },
            typeForm: {
                waste_type_id: null,
                waste_type_name: '',
                waste_type_price: ''
            },

            init() {
                this.fetchCategories();
            },

            // --- Categories Logic ---
            async fetchCategories() {
                this.isLoadingCategories = true;
                try {
                    const response = await fetch('/api/waste_categories');
                    const result = await response.json();
                    if (result.success) {
                        this.categories = result.data;
                    }
                } catch (error) {
                    console.error('Error fetching categories:', error);
                    await Swal.fire('ข้อผิดพลาด', error.message, 'error');
                } finally {
                    this.isLoadingCategories = false;
                }
            },

            get filteredCategories() {
                if (this.searchCategoryQuery === '') return this.categories;
                return this.categories.filter(c =>
                    c.waste_category_name.toLowerCase().includes(this.searchCategoryQuery.toLowerCase())
                );
            },

            selectCategory(category) {
                if (this.selectedCategory?.waste_category_id === category.waste_category_id) return;
                this.selectedCategory = category;
                this.selectedCategoryShow = true;
                this.selectedTypeIds = [];
                this.fetchWasteTypes(category.waste_category_id);
            },

            closeCategoryDetail() {
                this.selectedCategory = null;
                this.selectedCategoryShow = false;
                this.wasteTypes = [];
                this.selectedTypeIds = [];
            },

            // --- CRUD Category ---
            openCreateCategoryDialog() {
                this.isEditingCategory = false;
                this.categoryForm = { waste_category_id: null, waste_category_name: '', waste_category_co2_per_kg: '' };
                this.createCategoryDialogShow = true;
            },

            openEditCategoryDialog(category) {
                this.isEditingCategory = true;
                this.categoryForm = { ...category };
                this.categoryForm.waste_category_active = Boolean(Number(this.categoryForm.waste_category_active));
                this.createCategoryDialogShow = true;
            },

            async submitCategoryForm() {
                this.createCategoryDialogShow = false;

                const url = this.isEditingCategory
                    ? `/api/waste_categories/update/${this.categoryForm.waste_category_id}`
                    : '/api/waste_categories';
                const method = 'POST';

                const payload = { ...this.categoryForm };
                if (this.isEditingCategory) {
                    payload.waste_category_active = this.categoryForm.waste_category_active ? 1 : 0;
                }

                try {
                    const response = await fetch(url, {
                        method: method,
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload)
                    });
                    const data = await response.json();

                    if (data.success) {
                        const msg = this.isEditingCategory ? 'แก้ไขข้อมูลเรียบร้อย' : 'เพิ่มข้อมูลเรียบร้อย';
                        Swal.fire('สำเร็จ', msg, 'success');
                        this.fetchCategories();

                        if (this.selectedCategory && this.selectedCategory.waste_category_id === this.categoryForm.waste_category_id) {
                            this.selectedCategory = { ...this.categoryForm };
                        }
                    } else {
                        throw new Error(data.message || 'Error saving category');
                    }
                } catch (error) {
                    console.error(error);
                    await Swal.fire('ข้อผิดพลาด', error.message, 'error');
                    this.createCategoryDialogShow = true;
                }
            },

            confirmDeleteCurrentCategory() {
                if (!this.selectedCategory) return;
                Swal.fire({
                    title: 'ยืนยันการลบ?',
                    text: `คุณต้องการลบหมวดหมู่ "${this.selectedCategory.waste_category_name}" ใช่หรือไม่?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'ลบเลย',
                    confirmButtonColor: '#d33'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.deleteCurrentCategory();
                    }
                });
            },

            async deleteCurrentCategory() {
                try {
                    const response = await fetch('/api/waste_categories/bulk-del', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            waste_category_ids: [this.selectedCategory.waste_category_id]
                        })
                    });
                    const data = await response.json();
                    if (data.success) {
                        Swal.fire('ลบสำเร็จ', 'ข้อมูลถูกลบเรียบร้อยแล้ว', 'success');
                        this.closeCategoryDetail();
                        this.fetchCategories();
                    } else {
                        throw new Error(data.message);
                    }
                } catch (error) {
                    Swal.fire('ข้อผิดพลาด', error.message, 'error');
                }
            },

            // --- Waste Types Logic ---
            async fetchWasteTypes(categoryId) {
                this.wasteTypesLoading = true;
                this.wasteTypes = [];
                try {
                    const response = await fetch(`/api/waste_types/${categoryId}`);
                    const result = await response.json();
                    if (result.success) {
                        this.wasteTypes = result.data;
                    }
                } catch (error) {
                    console.error('Error fetching waste types:', error);
                    await Swal.fire('ข้อผิดพลาด', error.message, 'error');
                } finally {
                    this.wasteTypesLoading = false;
                }
            },

            get isAllTypesSelected() {
                return this.wasteTypes.length > 0 && this.selectedTypeIds.length === this.wasteTypes.length;
            },

            // --- CRUD Type ---
            openCreateTypeDialog() {
                this.isEditingType = false;
                this.typeForm = { waste_type_id: null, waste_type_name: '', waste_type_price: '' };
                this.createTypeDialogShow = true;
            },

            openEditTypeDialog(type) {
                this.isEditingType = true;
                this.typeForm = { ...type };
                this.typeForm.waste_type_active = Boolean(Number(this.typeForm.waste_type_active));
                this.createTypeDialogShow = true;
            },

            async submitTypeForm() {
                if (!this.selectedCategory) return;

                this.createTypeDialogShow = false;

                const url = this.isEditingType
                    ? `/api/waste_types/update/${this.typeForm.waste_type_id}` // ปรับ endpoint ตามจริง
                    : '/api/waste_types';

                const payload = {
                    waste_type_id: this.typeForm.waste_type_id,
                    waste_type_name: this.typeForm.waste_type_name,
                    waste_type_price: this.typeForm.waste_type_price,
                    waste_type_co2: this.typeForm.waste_type_co2,
                    waste_category_id: this.selectedCategory.waste_category_id,
                    waste_type_active: this.typeForm.waste_type_active ? 1 : 0
                };

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload)
                    });
                    const data = await response.json();

                    if (data.success) {
                        const msg = this.isEditingType ? 'แก้ไขประเภทขยะเรียบร้อย' : 'เพิ่มประเภทขยะเรียบร้อย';
                        Swal.fire('สำเร็จ', msg, 'success');
                        this.fetchWasteTypes(this.selectedCategory.waste_category_id);
                        this.typeForm = { waste_type_name: '', waste_type_price: '' };
                    } else {
                        throw new Error(data.message || 'Error saving waste type');
                    }
                } catch (error) {
                    await Swal.fire('ข้อผิดพลาด', error.message, 'error');
                    this.createTypeDialogShow = true;
                }
            },

            toggleTypeSelection(id) {
                // อันนี้เหลือไว้แค่ถ้ากด Checkbox ตรงๆ
                if (this.selectedTypeIds.includes(id)) {
                    this.selectedTypeIds = this.selectedTypeIds.filter(itemId => itemId !== id);
                } else {
                    this.selectedTypeIds.push(id);
                }
            },

            toggleAllTypes() {
                if (this.wasteTypes.length > 0 && this.selectedTypeIds.length === this.wasteTypes.length) {
                    this.selectedTypeIds = [];
                } else {
                    this.selectedTypeIds = this.wasteTypes.map(t => t.waste_type_id);
                }
            },

            deleteSelectedTypes() {
                Swal.fire({
                    title: 'ยืนยันการลบ?',
                    text: `คุณต้องการลบ ${this.selectedTypeIds.length} รายการที่เลือกใช่หรือไม่?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'ลบเลย',
                    confirmButtonColor: '#d33'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch('/api/waste_types/bulk-del', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ waste_type_ids: this.selectedTypeIds })
                            });
                            const data = await response.json();
                            if (data.success) {
                                Swal.fire('ลบสำเร็จ', 'ข้อมูลถูกลบเรียบร้อยแล้ว', 'success');
                                this.selectedTypeIds = [];
                                this.fetchWasteTypes(this.selectedCategory.waste_category_id);
                            } else {
                                throw new Error(data.message);
                            }
                        } catch (error) {
                            Swal.fire('ข้อผิดพลาด', error.message, 'error');
                        }
                    }
                });
            }
        }
    }
</script>