<div x-data="wasteSaleManager()" x-init="init()" class="space-y-6">
    <!-- Page Intro -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 mb-2">ประวัติการจำหน่ายออก</h1>
        <p class="text-slate-600 text-lg">ดูประวัติและรายละเอียด</p>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4">รายการขายขยะ</h2>

        <!-- Filters -->
        <div class="mb-4">
            <div class="flex items-center space-x-4">
                <input type="text" x-model="filters.search" @input.debounce.500ms="applyFilters" placeholder="ค้นหาผู้ซื้อ หรือ ผู้บันทึก..." class="w-full md:w-1/3 px-3 py-2 border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                <button @click="resetFilters" class="text-sm text-slate-500 hover:text-slate-700">ล้างตัวกรอง</button>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b text-left">
                            วันที่
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b text-left">
                            ผู้ซื้อ
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b text-right">
                            น้ำหนักรวม (กก.)
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b text-right">
                            ราคารวม (บาท)
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b text-left">
                            ผู้บันทึก
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b text-center">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <template x-if="loading">
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="flex justify-center items-center">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-slate-900"></div>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-if="!loading && sales.length === 0">
                        <tr>
                            <td colspan="6" class="text-center py-4 text-slate-500">ไม่พบข้อมูลการขาย</td>
                        </tr>
                    </template>
                    <template x-for="sale in sales" :key="sale.waste_sale_id">
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b"
                                x-text="formatDateTime(sale.created_at)"></td>
                            <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b"
                                x-text="sale.waste_sale_buyer"></td>
                            <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b text-right"
                                x-text="parseFloat(sale.waste_sale_total_weight).toFixed(2)"></td>
                            <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b text-right"
                                x-text="parseFloat(sale.waste_sale_total_price).toFixed(2)"></td>
                            <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b"
                                x-text="sale.creator_name"></td>
                            <td
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b text-center">
                                <button @click="openDetailModal(sale.waste_sale_id)"
                                    class="bg-blue-500 hover:bg-blue-700 text-white text-sm font-bold py-1 px-3 rounded">
                                    ดูรายละเอียด
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between mt-4 text-xs">
            <button class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50" @click="prevPage"
                :disabled="currentPage === 1">
                ก่อนหน้า
            </button>
            <div class="text-sm text-slate-700">
                หน้า <span x-text="currentPage"></span> / <span x-text="totalPages"></span>
            </div>
            <button class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50" @click="nextPage"
                :disabled="currentPage >= totalPages">
                ถัดไป
            </button>
        </div>
    </div>

    <!-- Detail Modal -->
    <div x-show="isModalOpen" @keydown.escape.window="closeModal()"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-2xl" @click.away="closeModal()">
            <h2 class="text-2xl font-bold mb-4">รายละเอียดการจำหน่าย #<span x-text="selectedSaleId"></span></h2>

            <div x-show="loadingDetails" class="text-center">
                <div class="flex justify-center items-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-slate-900"></div>
                </div>
            </div>

            <div x-show="!loadingDetails">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b text-left">
                                ประเภทขยะ</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b text-right">
                                น้ำหนัก (กก.)</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b text-right">
                                ราคา (บาท)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="detail in saleDetails" :key="detail.waste_sale_detail_id">
                            <tr>
                                <td class="px-2 py-2 overflow-hidden text-ellipsis text-xs"
                                    x-text="detail.waste_type_name"></td>
                                <td class="px-2 py-2 overflow-hidden text-ellipsis text-xs text-right"
                                    x-text="parseFloat(detail.waste_sale_detail_weight).toFixed(2)"></td>
                                <td class="px-2 py-2 overflow-hidden text-ellipsis text-xs text-right"
                                    x-text="parseFloat(detail.waste_sale_detail_price).toFixed(2)"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <div class="text-right mt-4">
                    <button @click="closeModal()"
                        class="bg-slate-500 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded">
                        ปิด
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function wasteSaleManager() {
        return {
            sales: [],
            saleDetails: [],
            selectedSaleId: null,
            isModalOpen: false,
            loading: true,
            loadingDetails: false,
            currentPage: 1,
            totalPages: 1,
            limit: 15,
            filters: {
                search: ''
            },

            init() {
                this.fetchSales();
            },

            applyFilters() {
                this.currentPage = 1;
                this.fetchSales();
            },

            resetFilters() {
                this.filters.search = '';
                this.applyFilters();
            },

            async fetchSales() {
                this.loading = true;
                try {
                    const params = new URLSearchParams({
                        page: this.currentPage,
                        limit: this.limit
                    });
                    if (this.filters.search) {
                        params.append('search', this.filters.search);
                    }

                    const response = await fetch(`/api/waste_sales?${params.toString()}`);
                    const result = await response.json();
                    
                    if (result.success) {
                        this.sales = result.result.data;
                        this.totalPages = Math.ceil(result.result.total / this.limit) || 1;
                    } else {
                        throw new Error(result.message);
                    }
                } catch (error) {
                    console.error('Error fetching waste sales:', error);
                    alert('ไม่สามารถโหลดข้อมูลการขายได้');
                } finally {
                    this.loading = false;
                }
            },

            async openDetailModal(saleId) {
                this.selectedSaleId = saleId;
                this.isModalOpen = true;
                this.loadingDetails = true;
                this.saleDetails = [];

                try {
                    const response = await fetch(`/api/waste_sales/${saleId}/details`);
                    const result = await response.json();

                    if (result.success) {
                        this.saleDetails = result.result.data;
                    } else {
                        throw new Error(result.message);
                    }
                } catch (error) {
                    console.error(`Error fetching details for sale #${saleId}:`, error);
                    alert('ไม่สามารถโหลดรายละเอียดการขายได้');
                    this.closeModal();
                } finally {
                    this.loadingDetails = false;
                }
            },

            closeModal() {
                this.isModalOpen = false;
                this.selectedSaleId = null;
                this.saleDetails = [];
            },

            nextPage() {
                if (this.currentPage < this.totalPages) {
                    this.currentPage++;
                    this.fetchSales();
                }
            },

            prevPage() {
                if (this.currentPage > 1) {
                    this.currentPage--;
                    this.fetchSales();
                }
            },

            formatDateTime(dateTimeStr) {
                const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
                return new Date(dateTimeStr).toLocaleDateString('th-TH', options);
            }
        };
    }
</script>