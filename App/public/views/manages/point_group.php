<div x-data="PointGroupTable()" x-init="initData()" class="space-y-4 w-full">

    <div class="bg-white rounded-md shadow p-6 overflow-x-auto w-full">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            
        <div class="mb-4">
            <h1 class="text-2xl font-bold text-slate-900">จัดการกลุ่มแต้ม</h1>
            <p class="text-slate-600 font-light text-sm">เพิ่ม/แก้ไข/ลบ กลุ่มแต้ม สำหรับแลกของในระบบ</p>
        </div>

            <div @click="openCreateDialog"
                class="group cursor-pointer flex items-center py-2 px-4 border-2 border-emerald-500 rounded-full hover:bg-emerald-100 space-x-1 transition-colors font-medium text-emerald-700">
                <button class="group-hover:rotate-90 duration-300" title="Add New">
                    <svg class="stroke-teal-500 fill-none group-active:stroke-teal-200 group-active:duration-0 duration-300"
                        viewBox="0 0 24 24" height="24" width="24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-width="1.5"
                            d="M12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22Z">
                        </path>
                        <path stroke-width="1.5" d="M8 12H16"></path>
                        <path stroke-width="1.5" d="M12 16V8"></path>
                    </svg>
                </button>
                <span>เพิ่ม</span>
            </div>
        </div>

        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                            ID
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                            Redeem Point
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <template x-for="group in pointGroups" :key="group.donation_item_point_id">
                        <tr class="hover:bg-emerald-50 cursor-pointer transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                x-text="group.donation_item_point_id"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="group.redeem_point">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button @click.stop="selectingRow(group)"
                                    class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                <button @click.stop="confirmDelete(group.donation_item_point_id)"
                                    class="text-red-600 hover:text-red-900 ml-4">Delete</button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between mt-4 text-xs">
            <button class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50" :disabled="page <= 1"
                @click="page--; fetchPointGroups()">
                ก่อนหน้า
            </button>
            <div class="flex items-center space-x-2">
                <template x-for="p in totalPages">
                    <button class="px-2 py-1 rounded"
                        :class="p === page ? 'bg-emerald-500 text-white' : 'bg-gray-200 hover:bg-gray-300'"
                        @click="page = p; fetchPointGroups()" x-text="p"></button>
                </template>
            </div>
            <button class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50"
                :disabled="page >= totalPages" @click="page++; fetchPointGroups()">
                ถัดไป
            </button>
        </div>
    </div>

    <!-- Create Dialog -->
    <dialog x-show="createDialogShow" x-ref="createDialog" @click.self="createDialogShow = false"
        @close="createDialogShow = false" class="fixed inset-0 mx-auto my-auto p-0 bg-transparent z-50"
        x-init="$watch('createDialogShow', value => {if (value) $refs.createDialog.showModal();else $refs.createDialog.close();})">
        <div class="bg-white rounded-md shadow p-6 w-96 max-w-full">
            <h3 class="font-bold text-lg mb-3">เพิ่มกลุ่มแต้มใหม่</h3>
            <div class="grid grid-1 space-y-2 text-xs">
                <div class="flex flex-col space-y-1">
                    <label for="create_redeem_point">
                        Redeem Point <span class="text-red-500">*</span>
                    </label>
                    <input
                        class="border border-gray-300 rounded p-1.5 focus:ring-emerald-300 focus:ring-3 focus:border-emerald-200"
                        type="number" id="create_redeem_point" x-model="createForm.redeem_point"
                        placeholder="e.g. 100">
                    <span x-show="errors.create && errors.create.redeem_point" class="text-red-500 text-xs">
                        กรุณากรอกแต้มสำหรับแลก
                    </span>
                </div>
            </div>
            <div class="mt-4 text-right">
                <button @click="submitCreate"
                    class="px-3 py-1 bg-sky-300 rounded hover:bg-sky-400 cursor-pointer">ยืนยัน</button>
                <button @click="createDialogShow = false"
                    class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 cursor-pointer">ยกเลิก</button>
            </div>
        </div>
    </dialog>

    <!-- Edit Dialog -->
    <dialog x-ref="editDialog" x-show="editDialogShow" @click.self="editDialogShow = false"
        @close="editDialogShow = false" class="fixed inset-0 mx-auto my-auto p-0 bg-transparent z-50"
        x-init="$watch('editDialogShow', value => {if (value) $refs.editDialog.showModal();else $refs.editDialog.close();})">
        <div class="bg-white rounded-md shadow p-6 w-96 max-w-full">
            <h3 class="font-bold text-lg mb-3">แก้ไขกลุ่มแต้ม</h3>
            <div class="grid grid-1 space-y-2 text-xs">
                <div class="flex flex-col space-y-1">
                    <label for="edit_redeem_point">
                        Redeem Point <span class="text-red-500">*</span>
                    </label>
                    <input
                        class="border border-gray-300 rounded p-1.5 focus:ring-emerald-300 focus:ring-3 focus:border-emerald-200"
                        type="number" id="edit_redeem_point" x-model="editForm.redeem_point"
                        placeholder="e.g. 100">
                    <span x-show="errors.edit && errors.edit.redeem_point" class="text-red-500 text-xs">
                        กรุณากรอกแต้มสำหรับแลก
                    </span>
                </div>
            </div>
            <div class="mt-4 text-right space-x-2">
                <button @click="submitEdit"
                    class="px-4 py-2 bg-emerald-500 text-white rounded-full hover:bg-emerald-600 cursor-pointer font-medium transition-colors">ยืนยัน</button>
                <button @click="editDialogShow = false"
                    class="px-4 py-2 bg-gray-200 rounded-full hover:bg-gray-300 cursor-pointer transition-colors">ยกเลิก</button>
            </div>
        </div>
    </dialog>
</div>

<script>
    function PointGroupTable() {
        return {
            pointGroups: [],
            createDialogShow: false,
            editDialogShow: false,
            createForm: {
                redeem_point: ''
            },
            editForm: {
                donation_item_point_id: null,
                redeem_point: ''
            },
            errors: {},
            page: 1,
            limit: 10,
            totalPages: 1,

            initData() {
                this.fetchPointGroups();
            },

            async fetchPointGroups() {
                try {
                    const params = new URLSearchParams({
                        page: this.page,
                        limit: this.limit
                    });
                    const response = await fetch(`/api/point_groups?${params.toString()}`);
                    const result = await response.json();
                    if (result.success) {
                        this.pointGroups = result.data;
                        this.totalPages = Math.ceil(result.total / this.limit);
                    }
                } catch (err) {
                    console.error("Failed to load point groups", err);
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'ไม่สามารถโหลดข้อมูลกลุ่มแต้มได้'
                    });
                }
            },

            openCreateDialog() {
                this.createForm = {
                    redeem_point: ''
                };
                this.errors.create = null;
                this.createDialogShow = true;
            },

            selectingRow(group) {
                this.editForm = { ...group
                };
                this.errors.edit = null;
                this.editDialogShow = true;
            },

            validateForm(formType) {
                let isValid = true;
                const form = formType === 'create' ? this.createForm : this.editForm;
                const errorStore = {};

                if (!form.redeem_point || form.redeem_point <= 0) {
                    errorStore.redeem_point = 'Redeem point must be a positive number.';
                    isValid = false;
                }

                if (formType === 'create') {
                    this.errors.create = errorStore;
                } else {
                    this.errors.edit = errorStore;
                }

                return isValid;
            },

            async submitCreate() {
                if (!this.validateForm('create')) {
                     Swal.fire({
                        icon: 'warning',
                        title: 'ข้อมูลไม่ถูกต้อง',
                        text: 'กรุณาตรวจสอบข้อมูลที่กรอก'
                    });
                    return;
                }

                try {
                    const response = await fetch('/api/point_groups', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(this.createForm)
                    });

                    const result = await response.json();
                    if (result.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'สร้างสำเร็จ',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        this.createDialogShow = false;
                        this.fetchPointGroups();
                    } else {
                        throw new Error(result.message);
                    }
                } catch (err) {
                    console.error('Create failed', err);
                    Swal.fire({
                        icon: 'error',
                        title: 'สร้างไม่สำเร็จ',
                        text: err.message
                    });
                }
            },

            async submitEdit() {
                if (!this.validateForm('edit')) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'ข้อมูลไม่ถูกต้อง',
                        text: 'กรุณาตรวจสอบข้อมูลที่กรอก'
                    });
                    return;
                }

                try {
                    const response = await fetch(`/api/point_groups/update/${this.editForm.donation_item_point_id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ redeem_point: this.editForm.redeem_point })
                    });
                    const result = await response.json();
                     if (result.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'แก้ไขสำเร็จ',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        this.editDialogShow = false;
                        this.fetchPointGroups();
                    } else {
                        throw new Error(result.message);
                    }
                } catch (err) {
                     console.error('Update failed', err);
                    Swal.fire({
                        icon: 'error',
                        title: 'แก้ไขไม่สำเร็จ',
                        text: err.message
                    });
                }
            },

            async confirmDelete(id) {
                const result = await Swal.fire({
                    title: 'ยืนยันการลบ?',
                    text: "คุณจะไม่สามารถกู้คืนข้อมูลนี้ได้!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'ใช่, ลบเลย!',
                    cancelButtonText: 'ยกเลิก'
                });

                if (result.isConfirmed) {
                    try {
                        const response = await fetch('/api/point_groups/delete', {
                            method: 'POST',
                             headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ point_ids: [id] })
                        });

                        const result = await response.json();

                        if (result.success) {
                            Swal.fire(
                                'ลบแล้ว!',
                                'ข้อมูลถูกลบเรียบร้อย',
                                'success'
                            );
                            this.fetchPointGroups();
                        } else {
                            throw new Error(result.message);
                        }
                    } catch (err) {
                        console.error('Delete failed', err);
                        Swal.fire({
                            icon: 'error',
                            title: 'ลบไม่สำเร็จ',
                            text: err.message
                        });
                    }
                }
            }
        }
    }
</script>