<?php
// Donation History - Shows all donation records
?>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">ประวัติการรับของบริจาค</h1>
            <p class="text-gray-600 mt-2">ดูรายการบริจาคทั้งหมด</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div x-data="DonationHistoryData()" x-init="fetchDonations()" class="space-y-4">
                
                <!-- Search & Filter -->
                <div class="flex gap-4 mb-4">
                    <input type="text" x-model="searchQuery" @input.debounce.300ms="fetchDonations(1)"
                        placeholder="ค้นหาผู้บริจาค/คณะ"
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100 border-b-2 border-gray-300">
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">#</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">ผู้บริจาค</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">ชนิดของ</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-700">จำนวน</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-700">มูลค่า</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-700">วันที่</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-700">หมายเหตุ</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-700">ดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(donation, index) in donations" :key="donation.donation_id">
                                <tr class="border-b border-gray-200 hover:bg-gray-50" @click="showDetail(donation)" style="cursor: pointer;">
                                    <td class="px-4 py-3 text-sm text-gray-900" x-text="(currentPage - 1) * 10 + index + 1"></td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        <p class="font-semibold" x-text="donation.donor_name || donation.faculty_name"></p>
                                        <p class="text-xs text-gray-500" x-text="donation.donor_phone || ''"></p>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700" x-text="donation.donation_item_name"></td>
                                    <td class="px-4 py-3 text-sm text-center text-gray-700" x-text="donation.donation_quantity"></td>
                                    <td class="px-4 py-3 text-sm text-center font-semibold text-purple-600" x-text="donation.donation_item_value + ' บาท'"></td>
                                    <td class="px-4 py-3 text-sm text-center text-gray-600" x-text="new Date(donation.created_at).toLocaleDateString('th-TH')"></td>
                                    <td class="px-4 py-3 text-sm text-gray-600" x-text="donation.donation_description || '-'"></td>
                                    <td class="px-4 py-3 text-center">
                                        <button @click.stop="deleteRecord(donation.donation_id)" class="text-red-600 hover:text-red-700 font-semibold">
                                            🗑️ ลบ
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="donations.length === 0">
                                <tr>
                                    <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                                        ไม่มีข้อมูลบริจาค
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="flex items-center justify-between mt-4">
                    <button @click="fetchDonations(currentPage - 1)" :disabled="currentPage === 1"
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50">
                        ก่อนหน้า
                    </button>
                    <span x-text="`หน้า ${currentPage} จาก ${totalPages}`" class="text-gray-700"></span>
                    <button @click="fetchDonations(currentPage + 1)" :disabled="currentPage >= totalPages"
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50">
                        ถัดไป
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function DonationHistoryData() {
    return {
        donations: [],
        currentPage: 1,
        totalPages: 1,
        searchQuery: '',
        
        async fetchDonations(page = 1) {
            try {
                this.currentPage = page;
                const res = await fetch(`/api/donations?page=${page}&search=${encodeURIComponent(this.searchQuery)}`);
                const json = await res.json();
                if (json.success) {
                    this.donations = json.data.data || [];
                    this.totalPages = Math.ceil(json.data.total / 10) || 1;
                }
            } catch (error) {
                console.error('Error fetching donations:', error);
            }
        },

        showDetail(donation) {
            alert(`รายละเอียด: ${donation.donation_item_name}\nจำนวน: ${donation.donation_quantity}\nมูลค่า: ${donation.donation_item_value} บาท`);
        },

        async deleteRecord(donationId) {
            if (confirm('คุณแน่ใจหรือว่าต้องการลบรายการนี้?')) {
                try {
                    const response = await fetch(`/api/donations/delete`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ donation_id: donationId })
                    });
                    if (response.ok) {
                        alert('ลบรายการสำเร็จ');
                        this.fetchDonations(1);
                    }
                } catch (error) {
                    alert('เกิดข้อผิดพลาดในการลบ: ' + error.message);
                }
            }
        }
    }
}
</script>
