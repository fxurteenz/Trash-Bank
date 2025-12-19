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
                <div class="w-2/3 flex flex-col">
                    <input x-model="form.deposit_weight" required min="0.01" step="0.01"
                        class="px-3 py-2 border border-gray-300 shadow-sm rounded focus:ring-blue-500 focus:border-blue-500"
                        type="number" placeholder="น้ำหนักขยะ(กิโลกรัม)">
                    <span class="text-gray-400 text-xs text-end">หลังบันทึกระบบจะปัดน้ำนหนักเป็นทศนิยม 1 ตำแหน่ง</span>
                </div>

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

    <div x-data="wasteTransactionManager()" x-init="init()" class="bg-white md:col-span-5 rounded-lg shadow p-6">

        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <h2 class="text-xl font-bold">ประวัติการดำเนินการ</h2>
            <div class="flex gap-2">
                <button @click="resetFilters"
                    class="text-sm text-gray-500 hover:text-gray-700 underline">ล้างตัวกรอง</button>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6 p-4 bg-gray-50 rounded-lg border border-gray-100">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">ค้นหาผู้ฝาก</label>
                <input type="text" x-model="filters.account_search" @input.debounce.500ms="applyFilters"
                    placeholder="ชื่อ/รหัส/เบอร์โทร"
                    class="w-full text-sm border border-gray-300 rounded px-2 py-1.5 bg-white focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">คณะ</label>
                <select x-model="filters.faculty" @change="applyFilters"
                    class="w-full text-sm border border-gray-300 rounded px-2 py-1.5 bg-white">
                    <option value="">ทั้งหมด</option>
                    <template x-for="faculty in faculties" :key="faculty.faculty_id">
                        <option :value="faculty.faculty_id" x-text="faculty.faculty_name"></option>
                    </template>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">หมวดหมู่ขยะ</label>
                <select x-model="filters.category" @change="applyFilters"
                    class="w-full text-sm border border-gray-300 rounded px-2 py-1.5 bg-white">
                    <option value="">ทั้งหมด</option>
                    <template x-for="cat in categories" :key="cat.waste_category_id">
                        <option :value="cat.waste_category_id" x-text="cat.waste_category_name"></option>
                    </template>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">วันที่เริ่มต้น</label>
                <input type="date" x-model="filters.start_date" @change="applyFilters"
                    class="w-full text-sm border border-gray-300 bg-white rounded px-2 py-1.5">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">วันที่สิ้นสุด</label>
                <input type="date" x-model="filters.end_date" @change="applyFilters"
                    class="w-full text-sm border border-gray-300 bg-white rounded px-2 py-1.5">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse border border-gray-300 text-sm">
                <thead class="bg-gray-200 text-xs">
                    <tr>
                        <th class="px-4 py-2 text-center">วันที่</th>
                        <th class="px-4 py-2 text-center">เวลา</th>
                        <th class="px-4 py-2 text-left">ผู้ฝาก</th>
                        <th class="px-4 py-2 text-right">หมวดหมู่</th>
                        <th class="px-4 py-2 text-right">ขนิด</th>
                        <th class="px-4 py-2 text-center">น้ำหนัก</th>
                        <th class="px-4 py-2 text-center">มูลค่า</th>
                        <th class="px-4 py-2 text-center">สถานะ</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-if="loading">
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="flex justify-center items-center">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900"></div>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-if="!loading && transactions.length === 0">
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500">No transactions found.</td>
                        </tr>
                    </template>
                    <template x-for="transaction in transactions" :key="transaction.waste_transaction_id">
                        <tr class="border-b border-gray-100">
                            <td class="border border-gray-300 px-2 py-1 text-center"
                                x-text="formatDate(transaction.waste_transaction_create_date)"></td>
                            <td class="border border-gray-300 px-2 py-1 text-center"
                                x-text="formatTime(transaction.waste_transaction_create_time)"></td>
                            <td class="border border-gray-300 px-2 py-1"
                                x-text="transaction.account_name || transaction.account_email || transaction.account_tel || transaction.account_personal_id">
                            </td>
                            <td class="border border-gray-300 px-2 py-1 text-right"
                                x-text="transaction.waste_category_name"></td>
                            <td class="border border-gray-300 px-2 py-1 text-right"
                                x-text="transaction.waste_type_name"></td>
                            <td class="border border-gray-300 px-2 py-1 text-right"
                                x-text="transaction.waste_transaction_weight"></td>
                            <td class="border border-gray-300 px-2 py-1 text-right"
                                x-text="transaction.waste_transaction_value"></td>
                            <td class="border border-gray-300 px-2 py-1 text-right"
                                x-text="transaction.waste_transaction_status"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div class="flex justify-between items-center mt-4">
            <div>
                <button @click="prevPage" :disabled="currentPage === 1" class="bg-gray-200 px-3 py-1 rounded-md text-sm"
                    :class="{'cursor-not-allowed': currentPage === 1}">Previous</button>

            </div>
            <div>
                <span class="text-sm text-gray-700">
                    Page <span x-text="currentPage"></span> of <span x-text="totalPages"></span>
                </span>
            </div>
            <div>
                <button @click="nextPage" :disabled="currentPage === totalPages"
                    class="bg-gray-200 px-3 py-1 rounded-md text-sm ml-2"
                    :class="{'cursor-not-allowed': currentPage === totalPages}">Next</button>
            </div>
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
                    console.log(result);

                    if (response.ok) {
                        this.wasteTypes = result.result.data || [];
                        this.totalPages = Math.ceil(result.result.total / this.limit);
                    } else {
                        throw new Error(result.message || 'Failed to fetch data');
                    }

                } catch (error) {
                    console.error('Error fetching waste types:', error);
                    await Swal.fire('Error', 'ไม่สามารถโหลดข้อมูลได้', 'error');
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

                    this.DropdownWasteCategories = result.result.data || [];
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
                        this.DropdownWasteTypes = result.result.data || [];
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
                            <p><b>น้ำหนัก:</b> ${this.form.deposit_weight} กก.</p>
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

<script>
    function wasteTransactionManager() {
        return {
            transactions: [],
            loading: false,
            currentPage: 1,
            totalPages: 1,
            limit: 10,
            faculties: [],
            categories: [],
            filters: {
                account_search: '',
                faculty: '',
                category: '',
                start_date: '',
                end_date: ''
            },

            init() {
                this.fetchInitialData();
                this.fetchTransactions();
            },

            async fetchInitialData() {
                try {
                    const [facRes, catRes] = await Promise.all([
                        fetch('/api/faculties'),
                        fetch('/api/categories')
                    ]);
                    const faculties = await facRes.json();
                    const categories = await catRes.json();

                    this.faculties = faculties.result || [];
                    this.categories = categories.result.data || [];
                } catch (error) {
                    console.error('Error loading filter data:', error);
                }
            },

            formatDate(dateStr) {
                if (!dateStr) return '-';
                const date = new Date(dateStr);
                return new Intl.DateTimeFormat('th-TH', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                }).format(date);
            },

            formatTime(timeStr) {
                if (!timeStr) return '-';
                // หากเป็นรูปแบบ HH:mm:ss ให้เติมวันที่สมมติเพื่อให้ Parse ได้
                const date = timeStr.includes('-') ? new Date(timeStr) : new Date(`1970-01-01T${timeStr}`);
                if (isNaN(date.getTime())) return timeStr;
                return new Intl.DateTimeFormat('th-TH', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                }).format(date) + ' น.';
            },

            applyFilters() {
                this.currentPage = 1;
                this.fetchTransactions();
            },

            resetFilters() {
                this.filters = {
                    account_search: '',
                    faculty: '',
                    category: '',
                    start_date: '',
                    end_date: ''
                };
                this.applyFilters();
            },

            async fetchTransactions() {
                this.loading = true;
                try {
                    const queryParams = new URLSearchParams({
                        page: this.currentPage,
                        limit: this.limit,
                        ...Object.fromEntries(Object.entries(this.filters).filter(([_, v]) => v !== ''))
                    });

                    const response = await fetch(`/api/waste_transactions/me?${queryParams.toString()}`);
                    const data = await response.json();
                    console.log(data);

                    if (data.success) {
                        this.transactions = data.result.data;
                        this.totalPages = Math.ceil(data.result.total / this.limit);
                    }
                } catch (error) {
                    console.error('Error fetching transactions:', error);
                    Swal.fire('Error', 'Failed to fetch transactions', 'error');
                } finally {
                    this.loading = false;
                }
            },

            nextPage() {
                if (this.currentPage < this.totalPages) {
                    this.currentPage++;
                    this.fetchTransactions();
                }
            },

            prevPage() {
                if (this.currentPage > 1) {
                    this.currentPage--;
                    this.fetchTransactions();
                }
            }
        };
    }
</script>