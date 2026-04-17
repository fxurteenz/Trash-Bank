<?php
$facultyId = $user->faculty_id;
?>
<div x-data="WasteStockTable()" x-init="fetchStock()" class="space-y-4 w-full">

    <div class="bg-white rounded-md shadow p-6 overflow-x-auto w-full">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <h2 class="text-xl font-bold">คลังขยะ</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
            <div>
                <label for="filter_search" class="block text-xs font-medium text-gray-700 mb-1">ค้นหา</label>
                <input type="text" id="filter_search" x-model="filters.search"
                    @input.debounce.500ms="handleFilterChange()" placeholder="ชื่อขยะ/รหัส"
                    class="w-full text-xs border border-gray-300 rounded px-2 py-1.5 bg-white focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                            รหัส
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                            ชื่อขยะ
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                            หมวดหมู่
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                            น้ำหนัก (กก.)
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <template x-for="stock in stocks" :key="stock.waste_type_id">
                        <tr class="hover:bg-emerald-50 cursor-pointer transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="stock.waste_type_id"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="stock.waste_type_name"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="stock.waste_category_name"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right" x-text="parseFloat(stock.stock_weight).toFixed(2)"></td>
                        </tr>
                    </template>
                     <template x-if="stocks.length === 0">
                        <tr>
                            <td colspan="4" class="text-center py-4">ไม่พบข้อมูล</td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between mt-4 text-xs">
             <div class="flex items-center gap-2">
                <span class="text-gray-600">แสดง</span>
                <select x-model="limit" @change="handleFilterChange()" class="px-2 py-1 bg-gray-100 border border-gray-300 rounded">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="text-gray-600" x-text="`จากทั้งหมด ${total} รายการ`"></span>
            </div>
            <div class="flex items-center space-x-2">
                <button class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50" :disabled="page <= 1"
                    @click="page--; fetchStock()">
                    ก่อนหน้า
                </button>
                <template x-for="p in totalPages">
                    <button class="px-2 py-1 rounded"
                        :class="p === page ? 'bg-emerald-500 text-white' : 'bg-gray-200 hover:bg-gray-300'"
                        @click="page = p; fetchStock()" x-text="p"></button>
                </template>
                <button class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50"
                    :disabled="page >= totalPages" @click="page++; fetchStock()">
                    ถัดไป
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    function WasteStockTable() {
        return {
            facultyId: <?php echo $facultyId; ?>,
            stocks: [],
            page: 1,
            limit: 10,
            totalPages: 1,
            total: 0,
            filters: {
                search: "",
            },

            async fetchStock() {
                try {
                    const params = new URLSearchParams();
                    params.append("page", this.page);
                    params.append("limit", this.limit);
                    if (this.filters.search) {
                        params.append("search", this.filters.search);
                    }

                    const res = await fetch(`/api/faculty_stock/${this.facultyId}?${params.toString()}`);
                    let result = await res.json();

                    if (result.success) {
                        this.stocks = result.data;
                        this.total = result.total;
                        this.totalPages = Math.ceil(result.total / this.limit);
                    }

                } catch (err) {
                    console.error("Failed to load waste stock", err);
                    alert('โหลดข้อมูลคลังขยะล้มเหลว');
                }
            },

            handleFilterChange() {
                this.page = 1;
                this.fetchStock();
            },
        };
    }
</script>