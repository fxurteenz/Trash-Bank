<div class="space-y-4 w-full">
    <div x-data="WasteCategoryTypeManagement()" x-init="init()" class="bg-white shadow-sm rounded-lg p-6">

        <div class="mb-4">
            <h1 class="text-2xl font-bold text-slate-900">จัดการหมวดหมู่ขยะ</h1>
            <p class="text-slate-600 font-light text-sm">เพิ่ม/แก้ไข/ลบข้อมูลหมวดหมู่ขยะ</p>
        </div>

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
                    <span class="font-medium">เพิ่มหมวดหมู่</span>
                </div>
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
                    <input required="" placeholder="ค้นหาหมวดหมู่..." x-model="searchCategoryQuery"
                        class="block w-full py-1 ps-10 pe-10 text-sm text-gray-900 border border-gray-300 rounded bg-gray-50 focus:ring-blue-500 outline-none focus:border-blue-500"
                        id="default-search" type="text" />
                </div>
            </div>
        </div>

        <div class="mt-2 text-lg text-gray-700 space-y-4 border-t border-gray-100 py-2">

            <div x-show="categories.length === 0 && isLoadingCategories" class="text-center py-4 text-gray-400 text-sm">
                กำลังโหลดข้อมูล...
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                <template x-for="category in categories" :key="category.waste_category_id">
                    <div class="grid grid-cols-2 px-4 py-3 border border-gray-100 shadow-xs rounded transition duration-200 hover:shadow-md hover:bg-purple-50 cursor-pointer"
                        :class="selectedCategory?.waste_category_id === category.waste_category_id ? 'ring-2 ring-purple-400 bg-purple-50' : 'bg-white'"
                        @click="selectCategory(category)">
                        <div>
                            <div class="font-semibold flex flex-col">
                                <span x-text="category.waste_category_name"></span>
                                <span x-show="category.waste_category_co2_per_kg" class="text-xs text-gray-500"
                                    x-text="`CO2e : ${category.waste_category_co2_per_kg}`"></span>
                                <span class="text-xs text-gray-400"
                                    x-text="`รหัสหมวดหมู่ : ${category.waste_category_id || 'ไม่ระบุ'}`">
                                </span>
                            </div>

                        </div>
                        <div class="flex flex-col gap-1 justify-end items-end">
                            <button @click.stop="openEditCategoryDialog(category)"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 scale-90"
                                x-transition:enter-end="opacity-100 scale-100"
                                class="bg-amber-100 text-amber-700 p-2 rounded-lg hover:bg-amber-200 border border-amber-200 font-medium text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>

                            <button @click.stop="selectCategory(category); confirmDeleteCurrentCategory()"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 scale-90"
                                x-transition:enter-end="opacity-100 scale-100"
                                class="bg-red-100 text-red-700 p-2 rounded-lg hover:bg-red-200 border border-red-200 font-medium text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Pagination for Categories -->
            <div class="flex items-center justify-between mt-4 text-xs">
                <button @click="fetchCategories(catPage - 1)" :disabled="catPage <= 1"
                    class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50">
                    ก่อนหน้า
                </button>
                <div class="flex items-center space-x-2">
                    <template x-for="p in catTotalPages">
                        <button class="px-2 py-1 rounded"
                            :class="p === catPage ? 'bg-emerald-500 text-white' : 'bg-gray-200 hover:bg-gray-300'"
                            @click="catPage = p; fetchCategories(p)" x-text="p"></button>
                    </template>
                </div>
                <button @click="fetchCategories(catPage + 1)" :disabled="catPage >= catTotalPages"
                    class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50">
                    ถัดไป
                </button>
            </div>

            <div x-show="selectedCategoryShow" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-3" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-3"
                class="overflow-x-auto pt-2 border border-gray-100 rounded mt-4">
                <div class="flex justify-between items-start px-2 mb-2">
                    <div class="flex flex-col">
                        <h3 class="text-xl font-bold text-blue-700 text-shadow-xs"
                            x-text="selectedCategory ? selectedCategory.waste_category_name : 'เลือกหมวดหมู่'"></h3>
                        <p class="text-xs text-gray-500"
                            x-text="`CO2e : ${selectedCategory?.waste_category_co2_per_kg}`"></p>
                    </div>

                    <button @click="closeCategoryDetail()"
                        class="text-gray-400 hover:text-red-500 hover:cursor-pointer transition duration-200 hover:scale-110">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="p-4 bg-slate-50 rounded border border-slate-100 space-y-2">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-2">
                        <h4 class="font-semibold text-gray-600 text-sm">รายการประเภทขยะในหมวดหมู่นี้</h4>

                        <div class="flex items-center space-x-2">
                            <button x-show="selectedTypeIds.length > 0" @click="deleteSelectedTypes"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 scale-90"
                                x-transition:enter-end="opacity-100 scale-100"
                                class="flex items-center space-x-1 bg-red-100 text-red-700 px-2 py-1 rounded-lg hover:bg-red-200 border border-red-200 text-xs font-medium cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span x-text="`ลบ (${selectedTypeIds.length})`"></span>
                            </button>

                            <div @click="openCreateTypeDialog"
                                :class="createTypeDialogShow && !isEditingType && 'bg-teal-300'"
                                class="group cursor-pointer flex items-center py-1 px-2 border-2 border-teal-500 rounded-lg hover:bg-teal-300 space-x-1 bg-white transition-colors">
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

                    <div class="overflow-hidden bg-white rounded shadow-sm">
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
                                        <th class="border border-gray-300 px-2 py-1 lg:px-4 lg:py-2 text-left">
                                            ประเภท
                                        </th>
                                        <th class="border border-gray-300 px-2 py-1 lg:px-4 lg:py-2 text-right">
                                            ราคา (บาท)
                                        </th>
                                        <th class="border border-gray-300 px-2 py-1 lg:px-4 lg:py-2 text-right">
                                            CO2e
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

                                            <td class="border border-gray-300 py-1 px-1.5 lg:px-2 lg:py-2 overflow-hidden text-ellipsis text-left group-hover:text-amber-700"
                                                x-text="type.waste_type_name"></td>
                                            <td class="border border-gray-300 py-1 px-1.5 lg:px-2 lg:py-2 overflow-hidden text-ellipsis text-right"
                                                x-text="Number(type.waste_type_price).toFixed(2)"></td>
                                            <td class="border border-gray-300 py-1 px-1.5 lg:px-2 lg:py-2 overflow-hidden text-ellipsis text-right"
                                                x-text="Number(type.waste_type_co2).toFixed(3)"></td>
                                            <td class="border border-gray-300 py-1 px-1.5 lg:px-2 lg:py-2 overflow-hidden text-ellipsis text-right group-hover:text-amber-700"
                                                x-text="type.waste_type_active ? '✅ เปิดรับฝาก' : '❌ ปิดรับฝาก'"></td>
                                        </tr>
                                    </template>
                                    <tr x-show="wasteTypes.length === 0">
                                        <td colspan="5" class="py-8 text-center text-gray-500 italic">
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

        <dialog x-ref="createCategoryDialog"
            class="fixed inset-0 mx-auto my-auto p-0 bg-transparent z-50 backdrop:bg-black/30"
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
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 cursor-pointer">ยกเลิก</button>
                        <button type="submit"
                            class="px-4 py-2 text-white rounded shadow transition-colors cursor-pointer"
                            :class="isEditingCategory ? 'bg-amber-500 hover:bg-amber-600' : 'bg-sky-500 hover:bg-sky-600'"
                            x-text="isEditingCategory ? 'บันทึกแก้ไข' : 'ยืนยันเพิ่ม'"></button>
                    </div>
                </form>
            </div>
        </dialog>

        <dialog x-ref="createTypeDialog"
            class="fixed inset-0 mx-auto my-auto p-0 bg-transparent z-50 backdrop:bg-black/30"
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
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 cursor-pointer">ยกเลิก</button>
                        <button type="submit"
                            class="px-4 py-2 text-white rounded shadow transition-colors cursor-pointer"
                            :class="isEditingType ? 'bg-amber-500 hover:bg-amber-600' : 'bg-teal-500 hover:bg-teal-600'"
                            x-text="isEditingType ? 'บันทึกแก้ไข' : 'ยืนยันเพิ่ม'"></button>
                    </div>
                </form>
            </div>
        </dialog>

    </div>

    <div x-data="WasteTypeTable()" x-init="init()" class="bg-white shadow-sm rounded-lg p-6">
        <div class="mb-4">
            <h1 class="text-2xl font-bold text-slate-900">จัดการประเภทขยะทั้งหมด</h1>
            <p class="text-slate-600 font-light text-sm">เพิ่ม/แก้ไข/ลบข้อมูลประเภทขยะทั้งหมดในระบบ</p>
        </div>

        <div class="flex flex-col md:flex-row justify-between gap-4 mb-4">
            <div @click="openCreateDialog()"
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
                <span class="font-medium">เพิ่มประเภทขยะ</span>
            </div>
            <div class="relative">
                <input type="text" x-model="search" @input.debounce.500ms="fetchWasteTypes(1)"
                    placeholder="ค้นหาประเภทขยะ..."
                    class="block w-full py-2 ps-4 pr-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 tracking-wider border-b">
                            ชื่อประเภท
                        </th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 tracking-wider border-b">
                            หมวดหมู่
                        </th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 tracking-wider border-b">
                            ราคา(บาท)
                        </th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 tracking-wider border-b">
                            CO2e/kg
                        </th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 tracking-wider border-b">
                            สถานะ
                        </th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 tracking-wider border-b">
                            จัดการ
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <template x-if="isLoading">
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500">กำลังโหลด...</td>
                        </tr>
                    </template>
                    <template x-if="!isLoading && wasteTypes.length === 0">
                        <tr class="hover:bg-gray-50 transition duration-200">
                            <td colspan="6" class="text-center py-4 text-gray-500">ไม่พบข้อมูล</td>
                        </tr>
                    </template>
                    <template x-for="type in wasteTypes" :key="type.waste_type_id">
                        <tr class="hover:bg-gray-50 transition duration-200">
                            <td class="py-2 px-2 whitespace-nowrap text-sm text-gray-900" x-text="type.waste_type_name">
                            </td>
                            <td class="py-2 px-2 whitespace-nowrap text-sm text-gray-900 text-end"
                                x-text="type.waste_category_name"></td>
                            <td class="py-2 px-2 whitespace-nowrap text-sm text-gray-900 text-right"
                                x-text="Number(type.waste_type_price).toFixed(2)"></td>
                            <td class="py-2 px-2 whitespace-nowrap text-sm text-gray-900 text-right"
                                x-text="Number(type.waste_type_co2).toFixed(3)"></td>
                            <td class="py-2 px-2 whitespace-nowrap text-sm text-gray-900 text-center">
                                <span
                                    :class="type.waste_type_active == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full text-center"
                                    x-text="type.waste_type_active == 1 ? 'เปิดใช้งาน' : 'ปิดใช้งาน'"></span>
                            </td>

                            <td
                                class="px-2 py-2 whitespace-nowrap text-center text-sm flex justify-center items-center gap-2">
                                <button @click.stop="openEditDialog(type)"
                                    class=" bg-amber-100 text-amber-700 hover:bg-amber-200 border border-amber-200 hover:cursor-pointer transition duration-200 px-3 py-1 rounded-full flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg> <span>&nbsp;แก้ไข</span>
                                </button>
                                <button @click.stop="confirmDelete(type)"
                                    class="bg-red-100 hover:bg-red-200 border border-red-200 hover:cursor-pointer text-red-700 cursor-pointer transition duration-200 px-3 py-1 rounded-full flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg> <span>&nbsp;ลบ</span>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between mt-4 text-xs">
            <button @click="fetchWasteTypes(page - 1)" :disabled="page <= 1"
                class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50">
                ก่อนหน้า
            </button>
            <div class="flex items-center space-x-2">
                <template x-for="p in totalPages">
                    <button class="px-2 py-1 rounded"
                        :class="p === page ? 'bg-emerald-500 text-white' : 'bg-gray-200 hover:bg-gray-300'"
                        @click="page = p; fetchWasteTypes(p)" x-text="p"></button>
                </template>
            </div>
            <!-- <span x-text="`หน้า ${page} จาก ${totalPages}`"></span> -->
            <button @click="fetchWasteTypes(page + 1)" :disabled="page >= totalPages"
                class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50">
                ถัดไป
            </button>
        </div>

        <!-- Create/Edit Modal -->
        <dialog x-ref="formDialog" class="fixed inset-0 mx-auto my-auto p-0 bg-transparent z-50"
            @click.self="dialogShow = false" @close="dialogShow = false" x-show="dialogShow"
            x-init="$watch('dialogShow', value => value ? $refs.formDialog.showModal() : $refs.formDialog.close())">
            <div class="bg-white p-6 rounded-lg shadow-xl w-96 border border-gray-200">
                <h3 class="font-bold text-lg" x-text="isEditing ? 'แก้ไขประเภทขยะ' : 'เพิ่มประเภทขยะใหม่'"></h3>
                <form @submit.prevent="isEditing ? submitEditForm() : submitCreateForm()"
                    class="space-y-4 mt-4 text-sm">
                    <div>
                        <label class="block mb-1">ชื่อประเภท</label>
                        <input type="text" x-model="form.waste_type_name" required
                            class="w-full border border-gray-300 rounded p-2">
                    </div>
                    <div>
                        <label class="block mb-1">หมวดหมู่</label>
                        <select x-model="form.waste_category_id" required
                            class="w-full border border-gray-300 rounded p-2 bg-white">
                            <option value="">-- เลือกหมวดหมู่ --</option>
                            <template x-for="cat in categories" :key="cat.waste_category_id">
                                <option :value="cat.waste_category_id" x-text="cat.waste_category_name"></option>
                            </template>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1">ราคา (บาท)</label>
                        <input type="number" step="0.01" min="0" x-model="form.waste_type_price" required
                            class="w-full border border-gray-300 rounded p-2">
                    </div>
                    <div>
                        <label class="block mb-1">ปริมาณ CO2e ที่ลดได้ (kgCO2e/kg)</label>
                        <input type="number" step="0.001" min="0" x-model="form.waste_type_co2" required
                            class="w-full border border-gray-300 rounded p-2">
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" x-model="form.waste_type_active" id="type_active_all"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded">
                        <label for="type_active_all">เปิดใช้งาน</label>
                    </div>
                    <div class="pt-4 flex justify-end space-x-2">
                        <button type="button" @click="dialogShow = false"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">ยกเลิก</button>
                        <button type="submit" class="px-4 py-2 text-white rounded"
                            :class="isEditing ? 'bg-amber-500 hover:bg-amber-600' : 'bg-sky-500 hover:bg-sky-600'"
                            x-text="isEditing ? 'บันทึก' : 'เพิ่ม'"></button>
                    </div>
                </form>
            </div>
        </dialog>
    </div>
</div>

<script>
    function WasteTypeTable() {
        return {
            wasteTypes: [],
            categories: [],
            isLoading: false,
            page: 1,
            limit: 5,
            total: 0,
            totalPages: 1,
            search: '',

            dialogShow: false,
            isEditing: false,
            form: {
                waste_type_id: null,
                waste_type_name: '',
                waste_type_price: '',
                waste_type_co2: '',
                waste_category_id: '',
                waste_type_active: true,
            },

            init() {
                this.fetchWasteTypes();
                this.fetchCategories();
            },

            async fetchWasteTypes(page = 1) {
                if (page < 1 || (page > this.totalPages && this.total > 0)) return;
                this.isLoading = true;
                this.page = page;

                try {
                    const params = new URLSearchParams({ page: this.page, limit: this.limit });
                    if (this.search) {
                        params.append('search', this.search);
                    }
                    const response = await fetch(`/api/waste_types?${params.toString()}`);
                    const result = await response.json();

                    if (result.success) {
                        this.wasteTypes = result.data;
                        this.total = result.total;
                        this.totalPages = Math.ceil(result.total / this.limit);
                    }
                } catch (error) {
                    console.error('Error fetching waste types:', error);
                    Swal.fire('Error', 'Failed to fetch waste types', 'error');
                } finally {
                    this.isLoading = false;
                }
            },

            async fetchCategories() {
                try {
                    const response = await fetch('/api/waste_categories');
                    const result = await response.json();
                    if (result.success) {
                        this.categories = result.data;
                    }
                } catch (error) {
                    console.error('Error fetching categories:', error);
                }
            },

            resetForm() {
                this.form = {
                    waste_type_id: null,
                    waste_type_name: '',
                    waste_type_price: '',
                    waste_type_co2: '',
                    waste_category_id: '',
                    waste_type_active: true,
                };
            },

            openCreateDialog() {
                this.isEditing = false;
                this.resetForm();
                this.dialogShow = true;
            },

            openEditDialog(type) {
                this.isEditing = true;
                this.form = {
                    ...type,
                    waste_type_active: type.waste_type_active == 1
                };
                this.dialogShow = true;
            },

            async submitCreateForm() {
                try {
                    const payload = {
                        waste_type_name: this.form.waste_type_name,
                        waste_type_price: this.form.waste_type_price,
                        waste_type_co2: this.form.waste_type_co2,
                        waste_category_id: this.form.waste_category_id,
                        waste_type_active: this.form.waste_type_active ? 1 : 0
                    };
                    const response = await fetch('/api/waste_types', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload)
                    });
                    const result = await response.json();
                    this.dialogShow = false;
                    if (result.success) {
                        Swal.fire('สำเร็จ', 'เพิ่มประเภทขยะเรียบร้อย', 'success');
                        this.resetForm();
                        this.fetchWasteTypes();
                    } else {
                        throw new Error(result.message);
                    }
                } catch (error) {
                    console.error('Error creating waste type:', error);
                    Swal.fire('ผิดพลาด', error.message || 'ไม่สามารถเพิ่มข้อมูลได้', 'error');
                    this.dialogShow = false;

                }
            },

            async submitEditForm() {
                try {
                    const payload = {
                        waste_type_name: this.form.waste_type_name,
                        waste_type_price: this.form.waste_type_price,
                        waste_type_co2: this.form.waste_type_co2,
                        waste_category_id: this.form.waste_category_id,
                        waste_type_active: this.form.waste_type_active ? 1 : 0
                    };
                    const response = await fetch(`/api/waste_types/update/${this.form.waste_type_id}`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload)
                    });
                    const result = await response.json();
                    this.dialogShow = false;

                    if (result.success) {
                        Swal.fire('สำเร็จ', 'แก้ไขข้อมูลเรียบร้อย', 'success');
                        this.resetForm();
                        this.fetchWasteTypes(this.page);
                    } else {
                        throw new Error(result.message);
                    }
                } catch (error) {
                    console.error('Error updating waste type:', error);
                    Swal.fire('ผิดพลาด', error.message || 'ไม่สามารถแก้ไขข้อมูลได้', 'error');
                    this.dialogShow = true;
                }
            },

            async confirmDelete(type) {
                Swal.fire({
                    title: 'ยืนยันการลบ?',
                    text: `ต้องการลบ "${type.waste_type_name}" ใช่หรือไม่?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'ลบ',
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonColor: '#d33',
                    didOpen: () => {
                        Swal.getConfirmButton().focus();
                    }
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch('/api/waste_types/delete', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ waste_type_ids: [type.waste_type_id] })
                            });
                            const resData = await response.json();
                            if (resData.success) {
                                Swal.fire('ลบสำเร็จ!', 'ข้อมูลถูกลบเรียบร้อย', 'success');
                                this.fetchWasteTypes(this.page);
                            } else {
                                throw new Error(resData.message);
                            }
                        } catch (error) {
                            Swal.fire('ผิดพลาด', error.message || 'ไม่สามารถลบข้อมูลได้', 'error');
                        }
                    }
                });
            }
        }
    }

    function WasteCategoryTypeManagement() {
        return {
            // Data States
            categories: [],
            wasteTypes: [],
            isLoadingCategories: false,
            catPage: 1,
            catLimit: 8,
            catTotal: 0,
            catTotalPages: 1,
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

            // Edit States
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
                waste_type_price: '',
                waste_type_co2: '', // [FIX] Added missing field
                waste_type_active: false
            },

            init() {
                this.fetchCategories();
            },

            // --- Categories Logic ---
            async fetchCategories(page = 1) {
                if (page < 1 || (page > this.catTotalPages && this.catTotal > 0)) return;
                this.isLoadingCategories = true;
                this.catPage = page;
                try {
                    const params = new URLSearchParams({ page: this.catPage, limit: this.catLimit });
                    if (this.searchCategoryQuery) {
                        params.append('search', this.searchCategoryQuery);
                    }
                    const response = await fetch(`/api/waste_categories?${params.toString()}`);
                    const result = await response.json();
                    if (result.success) {
                        this.categories = result.data;
                        this.catTotal = result.total;
                        this.catTotalPages = Math.ceil(result.total / this.catLimit);
                    }
                } catch (error) {
                    console.error('Error fetching categories:', error);
                    await Swal.fire('ข้อผิดพลาด', error.message, 'error');
                } finally {
                    this.isLoadingCategories = false;
                }
            },

            selectCategory(category) {
                // Prevent reloading if same category
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
                            this.selectedCategory = { ...this.categoryForm }; // Update selected view immediately
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
                    const response = await fetch('/api/waste_categories/delete', {
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
                // [FIX] Reset all fields including co2
                this.typeForm = { waste_type_id: null, waste_type_name: '', waste_type_price: '', waste_type_co2: '', waste_type_active: true };
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
                    ? `/api/waste_types/update/${this.typeForm.waste_type_id}`
                    : '/api/waste_types';

                // [FIX] Ensure payload has all fields and correct format
                const payload = {
                    ...this.typeForm,
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
                        // Reset Form cleanly
                        this.typeForm = { waste_type_name: '', waste_type_price: '', waste_type_co2: '' };
                    } else {
                        throw new Error(data.message || 'Error saving waste type');
                    }
                } catch (error) {
                    await Swal.fire('ข้อผิดพลาด', error.message, 'error');
                    this.createTypeDialogShow = true;
                }
            },

            toggleTypeSelection(id) {
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