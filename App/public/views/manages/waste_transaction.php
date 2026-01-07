<div class="space-y-4 w-full">
    <div class="bg-white rounded-lg shadow p-6 overflow-x-auto w-full hidden md:block">
        <h2 class="text-xl font-bold text-gray-700">ภาพรวมรายการฝากขยะ</h2>
        <p class="text-gray-500 text-sm">เลือกขอบเขตข้อมูลที่ต้องการจัดกลุ่ม ด้านล่างเพื่อดูรายการ</p>
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
        <div
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6 p-4 bg-gray-50 rounded-lg border border-gray-100">
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

        <!-- Transaction Table -->
        <div class="w-full overflow-x-auto">
            <table class="w-full table-fixed border-collapse border border-gray-300 text-sm">
                <thead class="bg-gray-200 text-xs">
                    <tr>
                        <th class="px-2 py-1 text-center w-2/14">วันที่</th>
                        <th class="px-2 py-1 text-center w-2/14">เวลา</th>
                        <th class="px-2 py-1 text-center w-3/14">ผู้ฝาก</th>
                        <th class="px-2 py-1 text-right w-2/14">หมวดหมู่</th>
                        <th class="px-2 py-1 text-right w-2/14">ขนิด</th>
                        <th class="px-2 py-1 text-center w-1/14">น้ำหนัก</th>
                        <th class="px-2 py-1 text-center w-2/14">สถานะ</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-if="loading">
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="flex justify-center items-center">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900"></div>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-if="!loading && transactions.length === 0">
                        <tr>
                            <td colspan="7" class="text-center py-4 text-gray-500">No transactions found.</td>
                        </tr>
                    </template>
                    <template x-for="transaction in transactions" :key="transaction.waste_transaction_id">
                        <tr class="border-b border-gray-100 text-xs lg:text-sm">
                            <td class="border border-gray-300 px-2 py-1 text-center"
                                x-text="formatDate(transaction.waste_transaction_create_date)"></td>
                            <td class="border border-gray-300 px-2 py-1 text-center"
                                x-text="formatTime(transaction.waste_transaction_create_time)"></td>
                            <td class="border border-gray-300 px-2 py-1 overflow-hidden text-ellipsis"
                                x-text="transaction.account_name || transaction.account_email || transaction.account_tel || transaction.account_personal_id">
                            </td>
                            <td class="border border-gray-300 px-2 py-1 text-right"
                                x-text="transaction.waste_category_name"></td>
                            <td class="border border-gray-300 px-2 py-1 text-right"
                                x-text="transaction.waste_type_name">
                            </td>
                            <td class="border border-gray-300 px-2 py-1 text-right"
                                x-text="transaction.waste_transaction_weight"></td>
                            <td class="border border-gray-300 px-2 py-1 text-right"
                                x-text="transaction.waste_transaction_status"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
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

                    const response = await fetch(`/api/waste_transactions?${queryParams.toString()}`);
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