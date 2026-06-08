<div class="w-full">
    <div class="flex justify-between items-center mb-4">
        <div class="flex items-center gap-4">
            <button @click="window.history.back()"
                class="text-gray-500 hover:text-gray-700 transition cursor-pointer hover:scale-105 active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </button>
            <h1 class="text-2xl font-bold text-slate-900">หมวดหมู่ขยะ</h1>
        </div>

    </div>
    <div x-data="WasteCategoryTypeManagement()" x-init="init()" class="bg-white shadow-sm rounded-lg p-6">

        <div class="flex justify-between mb-4">
            <div class="">
                <h1 class="text-2xl font-bold text-slate-900">รายการหมวดหมู่ขยะ</h1>
                <p class="text-slate-600 font-light text-sm">จัดการข้อมูลหมวดหมู่ขยะ</p>
            </div>
            <div class="flex items-center gap-2">
                <div @click="openCreateCategoryDialog" :class="createCategoryDialogShow && 'bg-emerald-300'"
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
                    <span class="font-medium text-sm">เพิ่มหมวดหมู่</span>
                </div>
                <button @click="window.open('/report/waste_categories', '_blank','noopener')"
                    class="group cursor-pointer flex items-center py-2 px-3 border-2 border-blue-500 rounded-full hover:bg-blue-100 space-x-1 w-fit transition-colors font-medium text-blue-700">
                    <svg class="w-5 h-5 stroke-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                        </path>
                    </svg>
                    <span class="font-medium text-sm">พิมพ์รายงาน</span>
                </button>
            </div>
        </div>

        <div class="mt-4 border-t border-gray-100 py-4 space-y-4">
            <!-- <div class="flex flex-col sm:flex-row justify-between gap-4">

                <div class="w-full sm:w-auto flex-1">
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
                            class="block w-full py-2 ps-10 pe-4 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-emerald-500 outline-none focus:border-emerald-500"
                            id="default-search" type="text" />
                    </div>
                </div>
            </div> -->

            <div x-show="categories.length === 0 && isLoadingCategories" class="text-center py-4 text-gray-400 text-sm">
                กำลังโหลดข้อมูล...
            </div>

            <div class="overflow-x-auto border border-gray-200 rounded-lg">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-6 py-3 text-xs font-medium text-gray-500 tracking-wider border-b text-center w-24">
                                รหัส
                            </th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 tracking-wider border-b text-left">
                                ชื่อหมวดหมู่
                            </th>
                            <th class="text-xs font-medium text-gray-500 tracking-wider border-b text-center">
                                จำนวนประเภท
                            </th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 tracking-wider border-b text-center">
                                จัดการ
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <template x-for="category in categories" :key="category.waste_category_id">
                            <tr :class="editingCategoryId === category.waste_category_id ? 'bg-amber-50' : 'hover:bg-gray-50'"
                                class="transition duration-200 cursor-pointer"
                                @click="if(editingCategoryId !== category.waste_category_id) window.location.href = `/admin/manage/waste_category/${category.waste_category_id}`">
                                <td class="py-2 px-6 whitespace-nowrap text-sm text-gray-500 text-center"
                                    x-text="category.waste_category_id || '-'">
                                </td>
                                <td class="py-2 px-6 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    <template x-if="editingCategoryId !== category.waste_category_id">
                                        <span x-text="category.waste_category_name"></span>
                                    </template>
                                    <template x-if="editingCategoryId === category.waste_category_id">
                                        <input type="text" x-model="editCategoryForm.waste_category_name" @click.stop
                                            @keydown.enter="saveEditCategory()"
                                            class="w-full border border-gray-300 rounded p-1 text-sm bg-white focus:ring-amber-500 focus:border-amber-500 outline-none">
                                    </template>
                                </td>
                                <td class="py-2 px-6 whitespace-nowrap text-sm font-semibold text-gray-900 text-center"
                                    x-text="category.waste_type_count || '-'">
                                </td>

                                <td
                                    class="px-6 py-2 whitespace-nowrap text-center text-sm flex justify-center items-center gap-2">
                                    <template x-if="editingCategoryId !== category.waste_category_id">
                                        <div class="flex justify-center items-center gap-2">
                                            <button @click.stop="startEditCategory(category)"
                                                class="bg-gradient-to-br from-amber-400 to-amber-500 p-2 text-white hover:bg-gradient-to-br hover:from-amber-500 hover:to-amber-600 hover:scale-105 cursor-pointer rounded-md">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button @click.stop="confirmDeleteCategory(category)"
                                                class="bg-gradient-to-br from-red-400 to-red-500 p-2 text-white hover:bg-gradient-to-br hover:from-red-600 hover:to-red-700 hover:scale-105 cursor-pointer rounded-md">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                    <template x-if="editingCategoryId === category.waste_category_id">
                                        <div class="flex gap-2">
                                            <button @click.stop="saveEditCategory()"
                                                class="bg-gradient-to-br from-emerald-500 to-emerald-600 p-2 text-white hover:scale-105 cursor-pointer rounded-md">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                            <button @click.stop="cancelEditCategory()"
                                                class="bg-gray-400 p-2 text-white hover:bg-gray-500 hover:scale-105 cursor-pointer rounded-md">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
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
                    <h3 class="font-bold text-lg text-gray-800">เพิ่มหมวดหมู่ใหม่</h3>
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
                    <div class="pt-4 flex justify-end space-x-2">
                        <button type="button" @click="createCategoryDialogShow = false"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 cursor-pointer">ยกเลิก</button>
                        <button type="submit"
                            class="px-4 py-2 text-white rounded shadow transition-colors cursor-pointer bg-sky-500 hover:bg-sky-600">ยืนยันเพิ่ม</button>
                    </div>
                </form>
            </div>
        </dialog>
    </div>

    <div class="">

    </div>
</div>

<script>
    function WasteCategoryTypeManagement() {
        return {
            // Data States
            categories: [],
            wasteTypes: [],
            isLoadingCategories: false,
            catPage: 1,
            catLimit: 10,
            catTotal: 0,
            catTotalPages: 1,
            wasteTypesLoading: false,

            // Selection States
            selectedCategoryIds: [],

            // UI States
            searchCategoryQuery: '',
            createCategoryDialogShow: false,

            // Edit States
            editingCategoryId: null,
            editCategoryForm: {
                waste_category_id: null,
                waste_category_name: '',
                waste_category_active: 1
            },

            // Forms
            categoryForm: {
                waste_category_id: null,
                waste_category_name: '',
                waste_category_co2_per_kg: '',
                waste_category_active: ''
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
                        this.categories = result.data || result.result?.data || [];
                        this.catTotal = result.total || result.result?.total || 0;
                        this.catTotalPages = Math.ceil(this.catTotal / this.catLimit) || 1;
                    }
                } catch (error) {
                    console.error('Error fetching categories:', error);
                    await Swal.fire('ข้อผิดพลาด', error.message, 'error');
                } finally {
                    this.isLoadingCategories = false;
                }
            },


            // --- CRUD Category ---
            openCreateCategoryDialog() {
                this.categoryForm = { waste_category_id: null, waste_category_name: '', waste_category_co2_per_kg: '' };
                this.createCategoryDialogShow = true;
            },

            startEditCategory(category) {
                this.editingCategoryId = category.waste_category_id;
                this.editCategoryForm = { ...category };
            },

            cancelEditCategory() {
                this.editingCategoryId = null;
            },

            async submitCategoryForm() {
                this.createCategoryDialogShow = false;
                try {
                    const response = await fetch('/api/waste_categories', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(this.categoryForm)
                    });
                    const data = await response.json();

                    if (data.success) {
                        Swal.fire('สำเร็จ', 'เพิ่มข้อมูลเรียบร้อย', 'success');
                        this.fetchCategories();
                    } else {
                        throw new Error(data.message || 'Error saving category');
                    }
                } catch (error) {
                    console.error(error);
                    await Swal.fire('ข้อผิดพลาด', error.message, 'error');
                    this.createCategoryDialogShow = true;
                }
            },

            async saveEditCategory() {
                try {
                    const payload = {
                        waste_category_name: this.editCategoryForm.waste_category_name,
                        waste_category_active: this.editCategoryForm.waste_category_active
                    };

                    const response = await fetch(`/api/waste_categories/update/${this.editCategoryForm.waste_category_id}`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload)
                    });
                    const data = await response.json();

                    if (data.success) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'แก้ไขข้อมูลเรียบร้อย',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        this.editingCategoryId = null;
                        this.fetchCategories(this.catPage);
                    } else {
                        throw new Error(data.message || 'Error saving category');
                    }
                } catch (error) {
                    console.error(error);
                    Swal.fire('ข้อผิดพลาด', error.message, 'error');
                }
            },

            confirmDeleteCategory(category) {
                Swal.fire({
                    title: 'ยืนยันการลบ?',
                    text: `คุณต้องการลบหมวดหมู่ "${category.waste_category_name}" ใช่หรือไม่?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'ลบเลย',
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonColor: '#d33'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch('/api/waste_categories/delete', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({
                                    waste_category_ids: [category.waste_category_id]
                                })
                            });
                            const data = await response.json();
                            if (data.success) {
                                Swal.fire('ลบสำเร็จ', 'ข้อมูลถูกลบเรียบร้อยแล้ว', 'success');
                                this.fetchCategories();
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