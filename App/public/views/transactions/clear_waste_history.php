<?php
// Clear Waste History - Shows all clear waste records
?>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">ประวัติการเคลียร์ยอด</h1>
            <p class="text-gray-600 mt-2">ดูรายการเคลียร์ยอดฝากขยะทั้งหมด</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div x-data="ClearWasteHistoryData()" x-init="fetchClearances()" class="space-y-4">
                
                <!-- Search & Filter -->
                <div class="flex gap-4 mb-4">
                    <input type="text" x-model="searchQuery" @input.debounce.300ms="fetchClearances(1)"
                        placeholder="ค้นหาคณะ"
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100 border-b-2 border-gray-300">
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">#</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">คณะ</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-700">ช่วงเวลา</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-700">วันที่เคลียร์</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-700">ผู้ดำเนินการ</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-700">หมายเหตุ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(clearance, index) in clearances" :key="clearance.waste_clearance_id">
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900" x-text="(currentPage - 1) * 10 + index + 1"></td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        <p class="font-semibold" x-text="clearance.faculty_name"></p>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-center text-gray-700">
                                        <p x-text="`${new Date(clearance.waste_clearance_period_start).toLocaleDateString('th-TH')} - ${new Date(clearance.waste_clearance_period_end).toLocaleDateString('th-TH')}`"></p>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-center text-gray-600" x-text="new Date(clearance.created_at).toLocaleDateString('th-TH')"></td>
                                    <td class="px-4 py-3 text-sm text-center text-gray-700" x-text="clearance.operated_by || '-'"></td>
                                    <td class="px-4 py-3 text-sm text-gray-600" x-text="clearance.waste_clearance_description || '-'"></td>
                                </tr>
                            </template>
                            <template x-if="clearances.length === 0">
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                        ไม่มีข้อมูลการเคลียร์ยอด
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="flex items-center justify-between mt-4">
                    <button @click="fetchClearances(currentPage - 1)" :disabled="currentPage === 1"
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50">
                        ก่อนหน้า
                    </button>
                    <span x-text="`หน้า ${currentPage} จาก ${totalPages}`" class="text-gray-700"></span>
                    <button @click="fetchClearances(currentPage + 1)" :disabled="currentPage >= totalPages"
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50">
                        ถัดไป
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function ClearWasteHistoryData() {
    return {
        clearances: [],
        currentPage: 1,
        totalPages: 1,
        searchQuery: '',
        
        async fetchClearances(page = 1) {
            try {
                this.currentPage = page;
                const res = await fetch(`/api/clearances?page=${page}&search=${encodeURIComponent(this.searchQuery)}`);
                const json = await res.json();
                if (json.success) {
                    this.clearances = json.data.data || [];
                    this.totalPages = Math.ceil(json.data.total / 10) || 1;
                }
            } catch (error) {
                console.error('Error fetching clearances:', error);
            }
        }
    }
}
</script>
