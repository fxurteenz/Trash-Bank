<?php
// Redeem Reward History - Shows all redeem reward records
?>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">ประวัติการแลกของรางวัล</h1>
            <p class="text-gray-600 mt-2">ดูรายการแลกของทั้งหมด</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div x-data="RedeemRewardHistoryData()" x-init="fetchRedeems()" class="space-y-4">
                
                <!-- Search & Filter -->
                <div class="flex gap-4 mb-4">
                    <input type="text" x-model="searchQuery" @input.debounce.300ms="fetchRedeems(1)"
                        placeholder="ค้นหาสมาชิก/รางวัล"
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100 border-b-2 border-gray-300">
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">#</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">สมาชิก</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">รางวัล</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-700">แต้มใช้</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-700">วันที่</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-700">สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(redeem, index) in redeems" :key="redeem.member_reward_id">
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900" x-text="(currentPage - 1) * 10 + index + 1"></td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        <p class="font-semibold" x-text="redeem.member_name"></p>
                                        <p class="text-xs text-gray-500" x-text="redeem.member_phone || ''"></p>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700" x-text="redeem.reward_name"></td>
                                    <td class="px-4 py-3 text-sm text-center font-semibold text-blue-600" x-text="redeem.member_reward_point"></td>
                                    <td class="px-4 py-3 text-sm text-center text-gray-600" x-text="new Date(redeem.created_at).toLocaleDateString('th-TH')"></td>
                                    <td class="px-4 py-3 text-sm text-center">
                                        <span x-show="redeem.member_reward_status === 'complete'" class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">สำเร็จ</span>
                                        <span x-show="redeem.member_reward_status === 'pending'" class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">รอดำเนินการ</span>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="redeems.length === 0">
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                        ไม่มีข้อมูลการแลก
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="flex items-center justify-between mt-4">
                    <button @click="fetchRedeems(currentPage - 1)" :disabled="currentPage === 1"
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50">
                        ก่อนหน้า
                    </button>
                    <span x-text="`หน้า ${currentPage} จาก ${totalPages}`" class="text-gray-700"></span>
                    <button @click="fetchRedeems(currentPage + 1)" :disabled="currentPage >= totalPages"
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50">
                        ถัดไป
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function RedeemRewardHistoryData() {
    return {
        redeems: [],
        currentPage: 1,
        totalPages: 1,
        searchQuery: '',
        
        async fetchRedeems(page = 1) {
            try {
                this.currentPage = page;
                const res = await fetch(`/api/member_rewards?page=${page}&search=${encodeURIComponent(this.searchQuery)}`);
                const json = await res.json();
                if (json.success) {
                    this.redeems = json.data.data || [];
                    this.totalPages = Math.ceil(json.data.total / 10) || 1;
                }
            } catch (error) {
                console.error('Error fetching redeems:', error);
            }
        }
    }
}
</script>
