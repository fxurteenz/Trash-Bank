<div class="flex flex-col w-full space-y-6" x-data="ManageRewardCategories()" x-init="initData()">
    <!-- Category Management Card -->
    <div class="bg-white shadow-sm rounded-lg p-6 flex flex-col h-full min-h-[calc(100vh-6rem)]">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <h2 class="text-2xl font-bold">จัดการหมวดหมู่รางวัล</h2>
            <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                <input type="text" x-model="catSearchQuery" @input="filterCategories()" placeholder="ค้นหาหมวดหมู่..."
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent w-full sm:w-64">
                <button @click="openCatCreateDialog"
                    class="flex items-center justify-center px-4 py-2 bg-emerald-500 text-white rounded-full hover:bg-emerald-600 transition-colors font-medium whitespace-nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    เพิ่มหมวดหมู่
                </button>
            </div>
        </div>

        <div class="overflow-x-auto border border-gray-200 rounded-lg">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 tracking-wider border-b text-center w-24">
                            ลำดับ</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 tracking-wider border-b text-left">
                            ชื่อหมวดหมู่</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 tracking-wider border-b text-center">
                            จำนวนรายการของรางวัล</th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 tracking-wider border-b text-center w-48">
                            จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <template x-for="(category, index) in filteredCategories" :key="category.donation_item_category_id">
                        <tr :class="catEditingId === category.donation_item_category_id ? 'bg-amber-50' : 'hover:bg-gray-50 cursor-pointer'"
                            class="transition duration-200"
                            @click="if(catEditingId !== category.donation_item_category_id) window.location.href = `/admin/manage/reward_category/detail/${category.donation_item_category_id}`">
                            <td class="py-2 px-6 whitespace-nowrap text-sm text-gray-500 text-center"
                                x-text="index + 1"></td>
                            <td class="py-2 px-6 whitespace-nowrap text-sm font-semibold text-gray-900">
                                <template x-if="catEditingId !== category.donation_item_category_id">
                                    <span x-text="category.donation_item_category_name"></span>
                                </template>
                                <template x-if="catEditingId === category.donation_item_category_id">
                                    <input type="text" x-model="catEditForm.donation_item_category_name"
                                        @keydown.enter="saveCatEdit()" @click.stop
                                        class="w-full border border-gray-300 rounded p-1 text-sm bg-white focus:ring-amber-500 focus:border-amber-500 outline-none">
                                </template>
                            </td>
                            <td class="py-2 px-6 whitespace-nowrap text-sm text-gray-900 text-center"
                                x-text="category.item_count || '0'"></td>
                            <td
                                class="px-6 py-2 whitespace-nowrap text-center text-sm flex justify-center items-center gap-2">
                                <template x-if="catEditingId !== category.donation_item_category_id">
                                    <div class="flex justify-center items-center gap-2">
                                        <button @click.stop="startCatEdit(category)"
                                            class="bg-gradient-to-br from-amber-400 to-amber-500 p-2 text-white hover:scale-105 rounded-md"
                                            title="แก้ไข"><svg xmlns="http://www.w3.org/2000/svg" width="18" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg></button>
                                        <button @click.stop="confirmCatDelete(category)"
                                            class="bg-gradient-to-br from-red-400 to-red-500 p-2 text-white hover:scale-105 rounded-md"
                                            title="ลบ"><svg xmlns="http://www.w3.org/2000/svg" width="18" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg></button>
                                    </div>
                                </template>
                                <template x-if="catEditingId === category.donation_item_category_id">
                                    <div class="flex gap-2">
                                        <button @click.stop="saveCatEdit()"
                                            class="bg-gradient-to-br from-emerald-500 to-emerald-600 p-2 text-white hover:scale-105 rounded-md"
                                            title="บันทึก"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg></button>
                                        <button @click.stop="cancelCatEdit()"
                                            class="bg-gray-400 p-2 text-white hover:bg-gray-500 hover:scale-105 rounded-md"
                                            title="ยกเลิก"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg></button>
                                    </div>
                                </template>
                            </td>
                        </tr>
                    </template>
                    <template x-if="filteredCategories.length === 0">
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">ไม่มีข้อมูลหมวดหมู่</td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Category Create Dialog -->
    <dialog x-ref="catCreateDialog" class="fixed inset-0 mx-auto my-auto p-0 bg-transparent z-50 backdrop:bg-black/30"
        @click.self="catCreateDialogShow = false" @close="catCreateDialogShow = false" x-show="catCreateDialogShow"
        x-cloak
        x-init="$watch('catCreateDialogShow', value => value ? $refs.catCreateDialog.showModal() : $refs.catCreateDialog.close())">
        <div class="bg-white p-6 rounded-lg shadow-xl w-96 border border-gray-200">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg text-gray-800">เพิ่มหมวดหมู่ใหม่</h3>
                <button @click="catCreateDialogShow = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <form @submit.prevent="submitCatForm" class="space-y-3 text-sm">
                <div>
                    <label for="category_name" class="block text-gray-700 font-medium mb-1">ชื่อหมวดหมู่ <span
                            class="text-red-500">*</span></label>
                    <input type="text" x-model="catForm.donation_item_category_name" required id="category_name"
                        class="w-full border border-gray-300 rounded p-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition"
                        placeholder="ระบุชื่อหมวดหมู่">
                </div>
                <div class="pt-4 flex justify-end space-x-2">
                    <button type="button" @click="catCreateDialogShow = false"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 cursor-pointer">ยกเลิก</button>
                    <button type="submit"
                        class="px-4 py-2 text-white rounded shadow transition-colors cursor-pointer bg-emerald-500 hover:bg-emerald-600">ยืนยันเพิ่ม</button>
                </div>
            </form>
        </div>
    </dialog>
</div>

<script>
    function ManageRewardCategories() {
        return {
            categories: [],
            filteredCategories: [],
            catSearchQuery: '',
            catCreateDialogShow: false,
            catEditingId: null,
            catEditForm: { donation_item_category_id: null, donation_item_category_name: '' },
            catForm: { donation_item_category_name: '' },

            async initData() {
                await this.fetchCategories();
            },

            async fetchCategories() {
                try {
                    const res = await fetch('/api/donations/items/category');
                    const json = await res.json();
                    if (json.success) {
                        this.categories = json.data || [];
                        this.filterCategories();
                    }
                } catch (error) {
                    console.error('Error fetching categories:', error);
                }
            },

            filterCategories() {
                if (this.catSearchQuery) {
                    const query = this.catSearchQuery.toLowerCase();
                    this.filteredCategories = this.categories.filter(c =>
                        c.donation_item_category_name.toLowerCase().includes(query)
                    );
                } else {
                    this.filteredCategories = [...this.categories];
                }
            },

            openCatCreateDialog() {
                this.catForm = { donation_item_category_name: '' };
                this.catCreateDialogShow = true;
            },

            startCatEdit(category) {
                this.catEditingId = category.donation_item_category_id;
                this.catEditForm = { ...category };
            },

            cancelCatEdit() {
                this.catEditingId = null;
            },

            async submitCatForm() {
                if (!this.catForm.donation_item_category_name) return;
                this.catCreateDialogShow = false;
                try {
                    const response = await fetch('/api/donations/items/category', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(this.catForm)
                    });
                    const data = await response.json();

                    if (data.success) {
                        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'เพิ่มหมวดหมู่สำเร็จ', showConfirmButton: false, timer: 1500 });
                        this.fetchCategories();
                    } else {
                        throw new Error(data.message || 'Error saving category');
                    }
                } catch (error) {
                    console.error(error);
                    await Swal.fire('ข้อผิดพลาด', error.message, 'error');
                    this.catCreateDialogShow = true;
                }
            },

            async saveCatEdit() {
                if (!this.catEditForm.donation_item_category_name) return;
                try {
                    const response = await fetch(`/api/donations/items/category/update/${this.catEditForm.donation_item_category_id}`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            donation_item_category_name: this.catEditForm.donation_item_category_name
                        })
                    });
                    const data = await response.json();

                    if (data.success) {
                        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'แก้ไขข้อมูลสำเร็จ', showConfirmButton: false, timer: 1500 });
                        this.catEditingId = null;
                        this.fetchCategories();
                    } else {
                        throw new Error(data.message || 'Error saving category');
                    }
                } catch (error) {
                    console.error(error);
                    Swal.fire('ข้อผิดพลาด', error.message, 'error');
                }
            },

            async confirmCatDelete(category) {
                const result = await Swal.fire({
                    title: 'ยืนยันการลบ?',
                    text: `คุณต้องการลบหมวดหมู่ "${category.donation_item_category_name}" ใช่หรือไม่?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'ลบเลย',
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonColor: '#d33'
                });

                if (result.isConfirmed) {
                    try {
                        const response = await fetch('/api/donations/items/category/delete', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                donation_item_category_ids: [category.donation_item_category_id]
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
            }
        }
    }
</script>