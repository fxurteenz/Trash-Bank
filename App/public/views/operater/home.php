<div class="grid grid-cols-1 md:grid-cols-5 gap-4">

    <div x-data="depositFormHandler()" x-init="initData()"
        class="bg-white md:col-span-3 rounded-lg space-y-4 shadow px-4 py-4">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold">ฝากขยะ</h2>
            <div x-show="isSubmitting" class="text-xs text-blue-600">กำลังบันทึก...</div>
        </div>

        <form @submit.prevent="confirmSubmit" class="space-y-4 text-sm">
            <div class="w-full flex items-center justify-between">
                <label class="w-1/3 font-medium text-gray-700">ผู้ฝาก</label>
                <input x-model="form.depositor_account" required
                    class="w-2/3 px-3 py-2 border border-gray-300 shadow-sm rounded focus:ring-blue-500 focus:border-blue-500"
                    type="text" placeholder="เบอร์โทรศัพท์/รหัสประจำตัว">
            </div>

            <div class="w-full flex items-center justify-between">
                <label class="w-1/3 font-medium text-gray-700">หมวดหมู่/ประเภท</label>
                <select x-model="form.waste_category_id" @change="fetchTypeByCategory" required
                    class="w-2/3 px-3 py-2 border border-gray-300 shadow-sm rounded focus:ring-blue-500 focus:border-blue-500 bg-white">
                    <option value="" disabled selected>-- เลือกหมวดหมู่ขยะ --</option>
                    <template x-for="category in DropdownWasteCategories"
                        :key="'waste_category-' + category.waste_category_id">
                        <option :value="category.waste_category_id" x-text="category.waste_category_name"></option>
                    </template>
                </select>
            </div>

            <div class="w-full flex items-center justify-between">
                <label class="w-1/3 font-medium text-gray-700">ชนิด/รูปแบบ</label>
                <select x-model="form.waste_type_id" required :disabled="!form.waste_category_id || isFetchingTypes"
                    class="w-2/3 px-3 py-2 border border-gray-300 shadow-sm rounded focus:ring-blue-500 focus:border-blue-500 bg-white disabled:bg-gray-100 disabled:cursor-not-allowed">
                    <option value="" disabled selected>-- กรุณาเลือกหมวดหมู่ก่อน --</option>
                    <template x-for="type in DropdownWasteTypes" :key="'waste_type-' + type.waste_type_id">
                        <option :value="type.waste_type_id" x-text="type.waste_type_name"></option>
                    </template>
                </select>
            </div>

            <div class="w-full flex items-center justify-between">
                <label class="w-1/3 font-medium text-gray-700">น้ำหนัก (กก.)</label>
                <input x-model="form.deposit_weight" required min="0.01" step="0.01"
                    class="w-2/3 px-3 py-2 border border-gray-300 shadow-sm rounded focus:ring-blue-500 focus:border-blue-500"
                    type="number" placeholder="0.00">
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" :disabled="isSubmitting"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow disabled:opacity-50 disabled:cursor-not-allowed transition">
                    บันทึกรายการ
                </button>
            </div>
        </form>
    </div>

    <div x-data="wasteTypeManager()" x-init="fetchData()"
        class="bg-white md:col-span-2 rounded-lg space-y-2 shadow px-4 py-4">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold">เรทปัจจุบัน</h2>
            <span x-show="loading" class="text-xs text-gray-500 animate-pulse">กำลังโหลด...</span>
        </div>

        <div class="space-y-2 text-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-500 border-b">
                            <th class="py-2">ประเภทขยะ</th>
                            <th class="py-2 text-right">ราคา/กิโลกรัม</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template x-for="item in wasteTypes" :key="item.waste_type_id">
                            <tr>
                                <td class="py-2" x-text="item.waste_type_name"></td>
                                <td class="py-2 text-right font-medium" x-text="item.waste_type_price + ' บาท'"></td>
                            </tr>
                        </template>

                        <tr x-show="!loading && wasteTypes.length === 0">
                            <td colspan="2" class="py-4 text-center text-gray-400">ไม่พบข้อมูล</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between items-center pt-2 border-t mt-2">
                <button @click="prevPage()" :disabled="page === 1"
                    class="px-3 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded disabled:opacity-50 disabled:cursor-not-allowed">
                    ก่อนหน้า
                </button>

                <span class="text-xs text-gray-500">
                    หน้า <span x-text="page"></span> / <span x-text="totalPages"></span>
                </span>

                <button @click="nextPage()" :disabled="page >= totalPages"
                    class="px-3 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded disabled:opacity-50 disabled:cursor-not-allowed">
                    ถัดไป
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white md:col-span-5 rounded-lg shadow p-6">
        <div class="flex">
            <h2 class="text-xl font-bold">ประวัติการดำเนินการ</h2>

        </div>
    </div>

</div>

<script>
    function wasteTypeManager() {
        return {
            wasteTypes: [],
            page: 1,
            limit: 5,
            totalPages: 1,
            loading: false,

            async fetchData() {
                this.loading = true;
                try {
                    const response = await fetch(`/api/waste_types?page=${this.page}&limit=${this.limit}`);
                    const result = await response.json();

                    if (response.ok) {
                        this.wasteTypes = result.result[0];
                        this.totalPages = Math.ceil(result.total / this.limit);
                    } else {
                        throw new Error(result.message || 'Failed to fetch data');
                    }

                } catch (error) {
                    console.error('Error fetching waste types:', error);
                    this.wasteTypes = [];
                } finally {
                    this.loading = false;
                }
            },

            nextPage() {
                if (this.page < this.totalPages) {
                    this.page++;
                    this.fetchData();
                }
            },

            prevPage() {
                if (this.page > 1) {
                    this.page--;
                    this.fetchData();
                }
            }
        }
    }
</script>

<script>
    function depositFormHandler() {
        return {
            DropdownWasteCategories: [],
            DropdownWasteTypes: [],
            isSubmitting: false,
            isFetchingTypes: false,
            form: {
                depositor_account: null,
                waste_category_id: "",
                waste_type_id: "",
                deposit_weight: null
            },

            async initData() {
                try {
                    const wasteCategoriesResponse = await fetch('/api/categories');
                    const result = await wasteCategoriesResponse.json();

                    this.DropdownWasteCategories = result.result[0] || [];
                } catch (error) {
                    console.error('Error fetching waste categories:', error);
                    await Swal.fire('Error', 'ไม่สามารถโหลดข้อมูลหมวดหมู่ขยะได้', 'error');
                }
            },

            async fetchTypeByCategory() {
                this.DropdownWasteTypes = [];
                this.form.waste_type_id = "";

                if (!this.form.waste_category_id) {
                    return;
                }

                this.isFetchingTypes = true;
                try {
                    const response = await fetch(`/api/waste_types/${this.form.waste_category_id}`);
                    const result = await response.json();
                    console.log(result.result);

                    if (response.ok) {
                        this.DropdownWasteTypes = result.result[0] || [];
                    } else {
                        throw new Error(result.message || 'Failed to fetch waste types');
                    }
                } catch (error) {
                    console.error('Error fetching waste types:', error);
                    await Swal.fire('Error', 'ไม่สามารถโหลดข้อมูลชนิดขยะได้', 'error');
                } finally {
                    this.isFetchingTypes = false;
                }
            },

            confirmSubmit() {
                if (!this.form.depositor_account || !this.form.waste_type_id || !this.form.deposit_weight) {
                    Swal.fire('แจ้งเตือน', 'กรุณากรอกข้อมูลให้ครบถ้วน', 'warning');
                    return;
                }

                const selectedCategory = this.DropdownWasteCategories.find(c => c.waste_category_id == this.form.waste_category_id);
                const selectedType = this.DropdownWasteTypes.find(t => t.waste_type_id == this.form.waste_type_id);
                const categoryName = selectedCategory ? selectedCategory.waste_category_name : 'ไม่ระบุ';
                const typeName = selectedType ? selectedType.waste_type_name : 'ไม่ระบุ';

                Swal.fire({
                    title: 'ยืนยันการฝากขยะ?',
                    html: `
                        <div class="text-left text-sm space-y-1">
                            <p><b>ผู้ฝาก:</b> ${this.form.depositor_account}</p>
                            <p><b>หมวดหมู่:</b> ${categoryName}</p>
                            <p><b>ชนิด:</b> ${typeName}</p>
                            <p><b>น้ำหนัก:</b> ${this.form.weight} กก.</p>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ใช่, บันทึกเลย!',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submitData();
                    }
                });
            },

            async submitData() {
                this.isSubmitting = true;
                try {
                    const response = await fetch('/api/waste_transactions', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(this.form)
                    });

                    const result = await response.json();

                    if (response.ok) {
                        Swal.fire('สำเร็จ!', 'บันทึกข้อมูลเรียบร้อยแล้ว', 'success');
                        this.form.depositor_account = null;
                        this.form.waste_category_id = "";
                        this.form.waste_type_id = "";
                        this.form.deposit_weight = null;
                        this.DropdownWasteTypes = [];
                    } else {
                        throw new Error(result.message || 'เกิดข้อผิดพลาดในการบันทึก');
                    }

                } catch (error) {
                    console.error('Submission error:', error);
                    Swal.fire('เกิดข้อผิดพลาด', error.message, 'error');
                } finally {
                    this.isSubmitting = false;
                }
            }
        }
    }
</script>