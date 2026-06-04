<?php
$wcid = $wcid ?? "null";
?>
<div class="w-full" x-data="WasteTypeTable()" x-init="init()">
    <div class="flex items-center gap-4 mb-6">
        <button @click="window.history.back()"
            class="text-gray-500 hover:text-gray-700 transition cursor-pointer hover:scale-105 active:scale-95">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </button>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">ประเภทขยะ</h1>
            <!-- <p class="text-slate-600 font-light text-sm">หมวดหมู่: <span class="font-semibold text-emerald-600"
                    x-text="categoryName || 'กำลังโหลด...'"></span></p> -->
        </div>
    </div>

    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex justify-between mb-4">
            <div class="">
                <h1 class="text-2xl font-bold text-slate-900">รายการประเภทขยะ <span class="text-emerald-600"
                        x-text="categoryName || 'กำลังโหลด...'"></span></h1>
                <p class="text-slate-600 font-light text-sm">จัดการประเภทขยะ</p>
            </div>
            <div class="flex items-center gap-2">
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
                <button @click="window.open('', '_blank','noopener')"
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

        <div class="overflow-x-auto mt-4 border-t border-gray-100 py-4">
            <!-- <div class="flex flex-col md:flex-row justify-end gap-4 mb-4">
                <div class="relative">
                    <input type="text" x-model="search" @input.debounce.500ms="fetchWasteTypes(1)"
                        placeholder="ค้นหาประเภทขยะ..."
                        class="block w-full py-2 ps-4 pr-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div> -->
            <table class="min-w-full bg-white border-gray-200 border rounded-lg">
                <thead class="bg-gray-50">
                    <tr>
                        <th class=" py-3 text-xs font-medium text-gray-500 tracking-wider border-b text-center">
                            รหัสประเภท
                        </th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 tracking-wider border-b text-center">
                            ชื่อประเภท
                        </th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 tracking-wider border-b text-right">
                            ราคา(บาท)
                        </th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 tracking-wider border-b text-right">
                            CO2e/kg
                        </th>
                        <th class="py-3 text-xs font-medium text-gray-500 tracking-wider border-b text-right">
                            จำนวนที่มีในคลัง(กก.)
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
                            <td colspan="7" class="text-center py-4 text-gray-500">กำลังโหลด...</td>
                        </tr>
                    </template>
                    <template x-if="!isLoading && wasteTypes.length === 0">
                        <tr class="hover:bg-gray-50 transition duration-200">
                            <td colspan="7" class="text-center py-4 text-gray-500">ไม่พบข้อมูล</td>
                        </tr>
                    </template>
                    <template x-for="type in wasteTypes" :key="type.waste_type_id">
                        <tr :class="editingTypeId === type.waste_type_id ? 'bg-amber-50' : 'hover:bg-gray-50'"
                            class="transition duration-200">
                            <td class="py-2 px-2 text-center whitespace-nowrap text-sm text-gray-900"
                                x-text="type.waste_type_id">
                            </td>
                            <td class="py-2 px-2 whitespace-nowrap text-sm text-gray-900">
                                <template x-if="editingTypeId !== type.waste_type_id">
                                    <span x-text="type.waste_type_name"></span>
                                </template>
                                <template x-if="editingTypeId === type.waste_type_id">
                                    <input type="text" x-model="editForm.waste_type_name"
                                        class="w-full border border-gray-300 rounded p-1 text-sm bg-white focus:ring-amber-500 focus:border-amber-500 outline-none">
                                </template>
                            </td>
                            <td class="py-2 px-2 whitespace-nowrap text-sm text-gray-900 text-right">
                                <template x-if="editingTypeId !== type.waste_type_id">
                                    <span x-text="Number(type.waste_type_price).toFixed(2)"></span>
                                </template>
                                <template x-if="editingTypeId === type.waste_type_id">
                                    <input type="number" step="0.01" min="0" x-model="editForm.waste_type_price"
                                        class="w-24 border border-gray-300 rounded p-1 text-sm text-right bg-white focus:ring-amber-500 focus:border-amber-500 outline-none">
                                </template>
                            </td>
                            <td class="py-2 px-2 whitespace-nowrap text-sm text-gray-900 text-right">
                                <template x-if="editingTypeId !== type.waste_type_id">
                                    <span x-text="Number(type.waste_type_co2).toFixed(3)"></span>
                                </template>
                                <template x-if="editingTypeId === type.waste_type_id">
                                    <input type="number" step="0.001" min="0" x-model="editForm.waste_type_co2"
                                        class="w-24 border border-gray-300 rounded p-1 text-sm text-right bg-white focus:ring-amber-500 focus:border-amber-500 outline-none">
                                </template>
                            </td>
                            <td class="py-2 px-2 whitespace-nowrap text-sm text-gray-900 text-right"
                                x-text="Number(type.stock_weight || 0).toFixed(3)"></td>
                            <td class="py-2 px-2 whitespace-nowrap text-sm text-gray-900 text-center">
                                <button @click.stop="toggleActiveStatus(type)"
                                    :class="type.waste_type_active == 1 ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200'"
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full text-center cursor-pointer transition-colors"
                                    x-text="type.waste_type_active == 1 ? 'เปิดรับฝาก' : 'ปิดรับฝาก'"></button>
                            </td>

                            <td
                                class="px-2 py-2 whitespace-nowrap text-center text-sm flex justify-center items-center gap-2">
                                <template x-if="editingTypeId !== type.waste_type_id">
                                    <div class="flex gap-2">
                                        <button @click.stop="startEdit(type)"
                                            class="bg-gradient-to-br from-amber-400 to-amber-500 p-2 text-white hover:bg-gradient-to-br hover:from-amber-500 hover:to-amber-600 hover:scale-105 cursor-pointer rounded-md">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button @click.stop="confirmDelete(type)"
                                            class="bg-gradient-to-br from-red-400 to-red-500 p-2 text-white hover:bg-gradient-to-br hover:from-red-600 hover:to-red-700 hover:scale-105 cursor-pointer rounded-md">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                                <template x-if="editingTypeId === type.waste_type_id">
                                    <div class="flex gap-2">
                                        <button @click.stop="saveEdit()"
                                            class="bg-gradient-to-br from-emerald-500 to-emerald-600 p-2 text-white hover:scale-105 cursor-pointer rounded-md">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                        <button @click.stop="cancelEdit()"
                                            class="bg-gray-400 p-2 text-white hover:bg-gray-500 hover:scale-105 cursor-pointer rounded-md">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
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
            <button @click="fetchWasteTypes(page + 1)" :disabled="page >= totalPages"
                class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50">
                ถัดไป
            </button>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <dialog x-ref="formDialog" class="fixed inset-0 mx-auto my-auto p-0 bg-transparent z-50 backdrop:bg-black/30"
        @click.self="dialogShow = false" @close="dialogShow = false" x-show="dialogShow"
        x-init="$watch('dialogShow', value => value ? $refs.formDialog.showModal() : $refs.formDialog.close())">
        <div class="bg-white p-6 rounded-lg shadow-xl w-96 border border-gray-200">
            <h3 class="font-bold text-lg">เพิ่มประเภทขยะใหม่</h3>
            <form @submit.prevent="submitCreateForm()" class="space-y-4 mt-4 text-sm">
                <div>
                    <label class="block mb-1">ชื่อประเภท</label>
                    <input type="text" x-model="form.waste_type_name" required
                        class="w-full border border-gray-300 rounded p-2">
                </div>
                <div>
                    <label class="block mb-1">หมวดหมู่</label>
                    <select x-model="form.waste_category_id" required disabled
                        class="w-full border border-gray-300 rounded p-2 bg-gray-100 cursor-not-allowed">
                        <template x-for="cat in categories" :key="cat.waste_category_id">
                            <option :value="cat.waste_category_id" x-text="cat.waste_category_name"
                                :selected="cat.waste_category_id === categoryId"></option>
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
                    <button type="submit"
                        class="px-4 py-2 text-white rounded bg-sky-500 hover:bg-sky-600">เพิ่ม</button>
                </div>
            </form>
        </div>
    </dialog>
</div>

<script>
    function WasteTypeTable() {
        return {
            categoryId: '<?= $wcid; ?>',
            categoryName: '',
            wasteTypes: [],
            categories: [],
            isLoading: false,
            page: 1,
            limit: 10,
            total: 0,
            totalPages: 1,
            search: '',

            dialogShow: false,
            form: {
                waste_type_id: null,
                waste_type_name: '',
                waste_type_price: '',
                waste_type_co2: '',
                waste_category_id: this.categoryId,
                waste_type_active: true,
            },

            editingTypeId: null,
            editForm: {
                waste_type_id: null,
                waste_type_name: '',
                waste_type_price: '',
                waste_type_co2: '',
                waste_category_id: this.categoryId,
                waste_type_active: true,
            },

            init() {
                if (!this.categoryId) {
                    Swal.fire('ข้อผิดพลาด', 'ไม่พบรหัสหมวดหมู่', 'error').then(() => window.history.back());
                    return;
                }
                this.fetchCategories();
                this.fetchWasteTypes();
            },

            async fetchCategories() {
                try {
                    const response = await fetch('/api/waste_categories');
                    const result = await response.json();
                    if (result.success) {
                        this.categories = result.data || result.result?.data || [];
                        const currentCat = this.categories.find(c => c.waste_category_id == this.categoryId);
                        if (currentCat) {
                            this.categoryName = currentCat.waste_category_name;
                        }
                    }
                } catch (error) {
                    console.error('Error fetching categories:', error);
                }
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
                    const response = await fetch(`/api/waste_types/${this.categoryId}?${params.toString()}`);
                    const result = await response.json();

                    if (result.success) {
                        this.wasteTypes = result.data || result.result?.data || [];
                        this.total = result.total || result.result?.total || this.wasteTypes.length;
                        this.totalPages = Math.ceil(this.total / this.limit) || 1;
                    } else {
                        this.wasteTypes = [];
                    }
                } catch (error) {
                    console.error('Error fetching waste types:', error);
                } finally {
                    this.isLoading = false;
                }
            },

            resetForm() {
                this.form = {
                    waste_type_id: null,
                    waste_type_name: '',
                    waste_type_price: '',
                    waste_type_co2: '',
                    waste_category_id: this.categoryId,
                    waste_type_active: true,
                };
            },

            openCreateDialog() {
                this.resetForm();
                this.dialogShow = true;
            },

            startEdit(type) {
                this.editingTypeId = type.waste_type_id;
                this.editForm = {
                    ...type,
                    waste_type_active: type.waste_type_active == 1
                };
            },

            cancelEdit() {
                this.editingTypeId = null;
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
                        this.fetchWasteTypes(this.page);
                    } else {
                        throw new Error(result.message);
                    }
                } catch (error) {
                    console.error('Error creating waste type:', error);
                    Swal.fire('ผิดพลาด', error.message || 'ไม่สามารถเพิ่มข้อมูลได้', 'error');
                    this.dialogShow = false;
                }
            },

            async toggleActiveStatus(type) {
                try {
                    const response = await fetch('/api/waste_types/activate', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ waste_type_ids: [type.waste_type_id] })
                    });
                    const result = await response.json();

                    if (result.success) {
                        this.fetchWasteTypes(this.page);
                    } else {
                        throw new Error(result.message);
                    }
                } catch (error) {
                    console.error('Error toggling status:', error);
                    Swal.fire('ผิดพลาด', error.message || 'ไม่สามารถอัปเดตสถานะได้', 'error');
                }
            },

            async saveEdit() {
                try {
                    const payload = {
                        waste_type_name: this.editForm.waste_type_name,
                        waste_type_price: this.editForm.waste_type_price,
                        waste_type_co2: this.editForm.waste_type_co2,
                        waste_category_id: this.editForm.waste_category_id,
                        waste_type_active: this.editForm.waste_type_active ? 1 : 0
                    };
                    const response = await fetch(`/api/waste_types/update/${this.editForm.waste_type_id}`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload)
                    });
                    const result = await response.json();

                    if (result.success) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'แก้ไขข้อมูลเรียบร้อย',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        this.editingTypeId = null;
                        this.fetchWasteTypes(this.page);
                    } else {
                        throw new Error(result.message);
                    }
                } catch (error) {
                    console.error('Error updating waste type:', error);
                    Swal.fire('ผิดพลาด', error.message || 'ไม่สามารถแก้ไขข้อมูลได้', 'error');
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
</script>