<div class="grid grid-cols-1 gap-4">

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-800">จัดการประเภทขยะและเรทราคา</h2>
    </div>

    <div class="container space-y-6" x-data="wasteManagement()">

        <div class="bg-white shadow rounded-lg p-4 flex flex-col md:flex-row justify-between items-center gap-4">

            <div class="relative w-full md:w-64">
                <input type="text" placeholder="ค้นหาประเภทขยะ..." x-model="searchQuery"
                    class="pl-10 pr-4 py-2 border rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <div class="absolute left-3 top-2.5 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <div class="flex space-x-3 w-full md:w-auto justify-end">
                <button x-show="selectedIds.length > 0" @click="confirmDeleteSelected"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="flex items-center space-x-2 bg-red-100 text-red-700 px-4 py-2 rounded-lg hover:bg-red-200 focus:outline-none border border-red-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span x-text="`ลบ ${selectedIds.length} รายการ`"></span>
                </button>

                <button @click="openAddDialog"
                    class="flex items-center space-x-2 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 focus:outline-none shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>เพิ่มข้อมูล</span>
                </button>
            </div>
        </div>

        <div class="bg-white shadow-xl rounded-lg p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-center w-10">
                                <input type="checkbox" @change="toggleSelectAll" :checked="isAllSelected"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-4 w-4">
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ชื่อประเภทขยะ</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                เรทราคา (บาท)</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                หน่วย</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="waste in filteredWasteTypes" :key="waste.waste_type_id">
                            <tr class="hover:bg-indigo-50 cursor-pointer transition-colors duration-150"
                                @click="openEditDialog(waste)">

                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <input type="checkbox" :value="waste.waste_type_id" x-model="selectedIds"
                                        @click.stop
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-4 w-4">
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                    x-text="waste.waste_type_name"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                    x-text="waste.waste_type_rate.toFixed(2)"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                    x-text="waste.waste_type_unit"></td>
                            </tr>
                        </template>
                        <tr x-show="filteredWasteTypes.length === 0">
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                ไม่พบข้อมูล
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-sm text-gray-500 flex justify-between">
                <span x-text="`ทั้งหมด ${filteredWasteTypes.length} รายการ`"></span>
                <span x-show="selectedIds.length > 0" x-text="`เลือกอยู่ ${selectedIds.length} รายการ`"></span>
            </div>
        </div>

        <dialog x-ref="wasteDialog" class="fixed inset-0 mx-auto my-auto bg-transparent "
            @click.self="$refs.wasteDialog.close(); clearForm()">

            <div class="bg-white p-6 rounded-lg shadow-xl w-80">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900"
                        x-text="isEditing ? '✏️ แก้ไขข้อมูลประเภทขยะ' : '➕ เพิ่มข้อมูลประเภทขยะ'">
                    </h3>
                    <button @click="$refs.wasteDialog.close(); clearForm()" class="text-gray-400 hover:text-gray-500">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="confirmSaveWaste">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">ชื่อประเภทขยะ</label>
                            <input type="text" x-model="wasteTypeForm.waste_type_name" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">อัตรา/เรทราคา (บาท)</label>
                            <input type="number" x-model="wasteTypeForm.waste_type_rate" step="0.01" min="0" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">หน่วย</label>
                            <input type="text" x-model="wasteTypeForm.waste_type_unit" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <div class="mt-6 flex flex-row-reverse gap-2">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm"
                            :class="isEditing ? 'bg-orange-600 hover:bg-orange-700 focus:ring-orange-500' : 'bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500'"
                            x-text="isEditing ? 'บันทึกการแก้ไข' : 'ยืนยันการเพิ่ม'">
                        </button>
                        <button type="button" @click="$refs.wasteDialog.close(); clearForm()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            ยกเลิก
                        </button>
                    </div>
                </form>
            </div>

        </dialog>

    </div>
</div>

<script>
    function wasteManagement() {
        return {
            searchQuery: '',
            selectedIds: [],
            wasteTypes: [],

            wasteTypeForm: {
                waste_type_id: null,
                waste_type_name: '',
                waste_type_rate: null,
                waste_type_unit: '',
            },

            isEditing: false,

            init() {
                this.fetchWasteTypes();
            },

            async fetchWasteTypes() {
                try {
                    const response = await fetch('/api/waste_types');
                    const data = await response.json();

                    if (data.success) {
                        this.wasteTypes = data.result;
                    }
                } catch (error) {
                    console.error('Error fetching data:', error);
                }
            },

            get filteredWasteTypes() {
                if (this.searchQuery === '') {
                    return this.wasteTypes;
                }
                return this.wasteTypes.filter(waste => {
                    return waste.waste_type_name.toLowerCase().includes(this.searchQuery.toLowerCase());
                });
            },

            get isAllSelected() {
                return this.filteredWasteTypes.length > 0 && this.selectedIds.length === this.filteredWasteTypes.length;
            },

            toggleSelectAll() {
                if (this.isAllSelected) {
                    this.selectedIds = [];
                } else {
                    this.selectedIds = this.filteredWasteTypes.map(w => w.waste_type_id);
                }
            },

            // --- Dialog Management ---
            openAddDialog() {
                this.isEditing = false;
                this.clearForm();
                this.$refs.wasteDialog.showModal();
            },

            openEditDialog(waste) {
                this.isEditing = true;
                this.wasteTypeForm = {
                    waste_type_id: waste.waste_type_id,
                    waste_type_name: waste.waste_type_name,
                    waste_type_rate: waste.waste_type_rate,
                    waste_type_unit: waste.waste_type_unit
                };
                this.$refs.wasteDialog.showModal();
            },

            // ฟังก์ชันนี้ไม่ได้ถูกเรียกผ่าน @close แล้ว แต่เรียกใช้ใน Script ได้ถ้าจำเป็น
            closeDialog() {
                this.$refs.wasteDialog.close();
                this.clearForm();
            },

            // --- CRUD Operations ---
            validateForm() {
                if (!this.wasteTypeForm.waste_type_name || !this.wasteTypeForm.waste_type_unit) {
                    Swal.fire('ข้อผิดพลาด', 'กรุณากรอกข้อมูลให้ครบ', 'error');
                    return false;
                }
                if (this.wasteTypeForm.waste_type_rate === null || parseFloat(this.wasteTypeForm.waste_type_rate) < 0) {
                    Swal.fire('ข้อผิดพลาด', 'กรุณากรอกราคาที่ถูกต้อง', 'error');
                    return false;
                }
                return true;
            },

            confirmSaveWaste() {
                if (!this.validateForm()) return;

                const action = this.isEditing ? 'บันทึกการแก้ไข' : 'เพิ่ม';

                // ปิด Dialog เฉยๆ ข้อมูลยังอยู่ (เพราะเอา @close ออกแล้ว)
                this.$refs.wasteDialog.close();

                Swal.fire({
                    title: `ยืนยันการ${action}?`,
                    text: `คุณต้องการ${action}ข้อมูลนี้ใช่หรือไม่?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'ยืนยัน',
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (this.isEditing) {
                            this.updateWasteType();
                        } else {
                            this.addWasteType();
                        }
                    } else {
                        // ถ้ากดยกเลิก ก็เปิด Dialog กลับมา ข้อมูลก็ยังอยู่ครบ
                        this.$refs.wasteDialog.showModal();
                    }
                });
            },

            async addWasteType() {
                try {
                    // ตอนนี้ข้อมูลใน wasteTypeForm จะมีค่าแล้วครับ
                    // console.log('Data sending:', JSON.stringify(this.wasteTypeForm)); 

                    const response = await fetch('/api/waste_types', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(this.wasteTypeForm)
                    });

                    const data = await response.json();

                    if (data.success) {
                        await Swal.fire('สำเร็จ', 'เพิ่มข้อมูลเรียบร้อย', 'success');
                        this.fetchWasteTypes();
                        this.clearForm(); // ล้างฟอร์มเมื่อสำเร็จเท่านั้น
                    } else {
                        throw new Error(data.message || 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล');
                    }
                } catch (error) {
                    console.error(error);
                    await Swal.fire('ข้อผิดพลาด', error.message || 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้', 'error');
                    this.$refs.wasteDialog.showModal();
                }
            },

            async updateWasteType() {
                try {
                    const id = this.wasteTypeForm.waste_type_id;
                    const response = await fetch(`/api/waste_types/update/${id}`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(this.wasteTypeForm)
                    });

                    const data = await response.json();

                    if (data.success) {
                        await Swal.fire('สำเร็จ', 'แก้ไขข้อมูลเรียบร้อย', 'success');
                        this.fetchWasteTypes();
                        this.clearForm();
                    } else {
                        throw new Error(data.message || 'เกิดข้อผิดพลาดในการแก้ไขข้อมูล');
                    }
                } catch (error) {
                    console.error(error);
                    await Swal.fire('ข้อผิดพลาด', error.message || 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้', 'error');
                    this.$refs.wasteDialog.showModal();
                }
            },

            confirmDeleteSelected() {
                Swal.fire({
                    title: 'ยืนยันการลบ?',
                    text: `คุณต้องการลบ ${this.selectedIds.length} รายการที่เลือกใช่หรือไม่?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'ลบเลย',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.deleteSelected();
                    }
                });
            },

            async deleteSelected() {
                try {
                    const response = await fetch('/api/waste_types/bulk-del', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            waste_type_ids: this.selectedIds
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        await Swal.fire('ลบสำเร็จ!', 'ข้อมูลถูกลบเรียบร้อยแล้ว', 'success');
                        this.fetchWasteTypes();
                        this.selectedIds = [];
                    } else {
                        throw new Error(data.message || 'เกิดข้อผิดพลาดในการลบข้อมูล');
                    }
                } catch (error) {
                    console.error(error);
                    Swal.fire('ข้อผิดพลาด', error.message || 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้', 'error');
                }
            },

            clearForm() {
                this.wasteTypeForm = {
                    waste_type_id: null,
                    waste_type_name: '',
                    waste_type_rate: null,
                    waste_type_unit: '',
                };
                this.isEditing = false;
            }
        }
    }
</script>