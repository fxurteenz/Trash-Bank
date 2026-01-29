<div x-data="DonationHistoryHandler()" x-init="init()" class="space-y-6">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 mb-2">💝 ประวัติการรับของบริจาค</h1>
        <p class="text-slate-600 text-lg">ดึงจากตารางหลัก คลิกเพื่อดูรายละเอียด</p>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6 card-hover">
        <h2 class="text-xl font-bold text-slate-900 mb-5">🔍 ตัวกรองข้อมูล</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">วันที่เริ่มต้น</label>
                <input x-model="filterStartDate" @change="applyFilters()" type="date"
                    class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">วันที่สิ้นสุด</label>
                <input x-model="filterEndDate" @change="applyFilters()" type="date"
                    class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">ค้นหา</label>
                <input x-model="filterSearch" @keydown.enter="applyFilters()" type="text"
                    placeholder="ชื่อผู้บริจาค/สิ่งของ"
                    class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
            </div>
            <div class="flex gap-2">
                <button @click="applyFilters()"
                    class="flex-1 px-4 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-semibold transition-colors">🔍
                    ค้นหา</button>
                <button @click="clearFilters()"
                    class="flex-1 px-4 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg font-semibold transition-colors">🔄
                    ล้าง</button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500 card-hover">
            <p class="text-slate-600 text-sm font-medium mb-2">📦 รวมรายการ</p>
            <p class="text-4xl font-bold text-purple-600" x-text="donations.length"></p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-pink-500 card-hover">
            <p class="text-slate-600 text-sm font-medium mb-2">📊 จำนวนรวม</p>
            <p class="text-3xl font-bold text-pink-600"
                x-text="donations.reduce((s,x)=>s+Number(x.donation_quantity||0),0)"></p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-rose-500 card-hover">
            <p class="text-slate-600 text-sm font-medium mb-2">💰 มูลค่ารวม</p>
            <p class="text-3xl font-bold text-rose-600">
                <span x-text="donations.reduce((s,x)=>s+Number(x.donation_item_value||0),0).toFixed(2)"></span>
                <span class="text-lg ml-2">฿</span>
            </p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-orange-500 card-hover">
            <p class="text-slate-600 text-sm font-medium mb-2">📊 เฉลี่ย/รายการ</p>
            <p class="text-3xl font-bold text-orange-600"
                x-text="(donations.length>0?(donations.reduce((s,x)=>s+Number(x.donation_item_value||0),0)/donations.length).toFixed(2):0) + ' ฿'">
            </p>
        </div>
    </div>

    <!-- Main Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-100 border-b-2 border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-bold text-slate-700">#</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-slate-700">วันที่</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-slate-700">ผู้บริจาค</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-slate-700">ชนิดสิ่งของ</th>
                        <th class="px-6 py-4 text-center text-sm font-bold text-slate-700">จำนวน</th>
                        <th class="px-6 py-4 text-center text-sm font-bold text-slate-700">มูลค่า (฿)</th>
                        <th class="px-6 py-4 text-center text-sm font-bold text-slate-700">ดำเนินการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <template x-for="(donation, index) in donations" :key="donation.donation_id">
                        <tr class="hover:bg-purple-50 transition cursor-pointer" @click="openDetail(donation)">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900" x-text="index + 1"></td>
                            <td class="px-6 py-4 text-sm text-slate-600"
                                x-text="new Date(donation.created_at).toLocaleDateString('th-TH')"></td>
                            <td class="px-6 py-4 text-sm text-slate-900 font-medium"
                                x-text="donation.member_name || 'บริจาค'"></td>
                            <td class="px-6 py-4 text-sm text-slate-700" x-text="donation.donation_description || '-'">
                            </td>
                            <td class="px-6 py-4 text-sm text-center text-slate-700"
                                x-text="donation.donation_quantity"></td>
                            <td class="px-6 py-4 text-sm text-center font-semibold text-purple-600"
                                x-text="Number(donation.donation_item_value||0).toFixed(2)"></td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <button @click.stop="openDetail(donation)"
                                        class="px-3 py-2 bg-purple-100 text-purple-700 hover:bg-purple-200 rounded-lg font-medium text-sm transition">👁️
                                        ดู</button>
                                    <button @click.stop="confirmDelete(donation.donation_id)"
                                        class="px-3 py-2 bg-red-100 text-red-700 hover:bg-red-200 rounded-lg font-medium text-sm transition">🗑️
                                        ลบ</button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-if="donations.length === 0">
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-500 font-medium">ไม่มีข้อมูลบริจาค
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Detail Modal -->
    <div x-show="showDetailModal" @click.self="showDetailModal = false"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto" @click.stop>
            <div
                class="sticky top-0 bg-gradient-to-r from-purple-600 to-purple-700 text-white px-6 py-4 flex justify-between items-center">
                <h2 class="text-2xl font-bold">💝 รายละเอียดการบริจาค</h2>
                <button @click="showDetailModal = false" class="text-2xl hover:opacity-75">✕</button>
            </div>

            <div class="p-6 space-y-6">
                <!-- Header Info -->
                <template x-if="selectedDonation">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 mb-4">📋 ข้อมูลหลัก</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-lg">
                            <div>
                                <p class="text-sm text-slate-600 font-medium">ผู้บริจาค</p>
                                <p class="text-base font-bold text-slate-900"
                                    x-text="selectedDonation.member_name || 'บริจาค'"></p>
                            </div>
                            <div>
                                <p class="text-sm text-slate-600 font-medium">วันที่บริจาค</p>
                                <p class="text-base font-bold text-slate-900"
                                    x-text="new Date(selectedDonation.created_at).toLocaleDateString('th-TH')"></p>
                            </div>
                            <div>
                                <p class="text-sm text-slate-600 font-medium">เจ้าหน้าที่รับ</p>
                                <p class="text-base font-bold text-slate-900"
                                    x-text="selectedDonation.staff_name || '-'"></p>
                            </div>
                            <div>
                                <p class="text-sm text-slate-600 font-medium">หมายเหตุ</p>
                                <p class="text-base font-bold text-slate-900"
                                    x-text="selectedDonation.donation_description || '-'"></p>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Detail Items -->
                <template x-if="selectedDonation && detailItems.length > 0">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 mb-4">📦 รายการสิ่งของ</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-slate-100 border-b border-slate-300">
                                    <tr>
                                        <th class="px-4 py-2 text-left font-bold text-slate-700">#</th>
                                        <th class="px-4 py-2 text-left font-bold text-slate-700">รายการ</th>
                                        <th class="px-4 py-2 text-center font-bold text-slate-700">จำนวน</th>
                                        <th class="px-4 py-2 text-right font-bold text-slate-700">มูลค่า (฿)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200">
                                    <template x-for="(item, idx) in detailItems" :key="idx">
                                        <tr class="hover:bg-slate-50">
                                            <td class="px-4 py-3 font-medium text-slate-900" x-text="idx + 1"></td>
                                            <td class="px-4 py-3 text-slate-700" x-text="item.donation_item_name"></td>
                                            <td class="px-4 py-3 text-center text-slate-700"
                                                x-text="item.donation_quantity"></td>
                                            <td class="px-4 py-3 text-right font-semibold text-purple-600"
                                                x-text="Number(item.donation_item_value||0).toFixed(2)"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </template>
            </div>

            <div class="bg-slate-50 px-6 py-4 flex justify-end gap-2 border-t border-slate-200">
                <button @click="showDetailModal = false"
                    class="px-4 py-2 bg-slate-300 hover:bg-slate-400 text-slate-800 rounded-lg font-medium transition">ปิด</button>
            </div>
        </div>
    </div>

    <!-- Confirmation Dialog -->
    <div x-show="showConfirmDialog" @click.self="showConfirmDialog = false"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-lg max-w-sm w-full" @click.stop>
            <div class="bg-gradient-to-r from-red-600 to-red-700 text-white px-6 py-4">
                <h2 class="text-xl font-bold">⚠️ ยืนยันการลบ</h2>
            </div>
            <div class="p-6">
                <p class="text-slate-700 text-base mb-2">คุณแน่ใจที่จะลบรายการบริจาคนี้หรือไม่?</p>
                <p class="text-slate-500 text-sm">การลบจะไม่สามารถยกเลิกได้</p>
            </div>
            <div class="bg-slate-50 px-6 py-4 flex justify-end gap-2 border-t border-slate-200">
                <button @click="showConfirmDialog = false"
                    class="px-4 py-2 bg-slate-300 hover:bg-slate-400 text-slate-800 rounded-lg font-medium transition">ยกเลิก</button>
                <button @click="deleteRecord(selectedDeleteId)"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition">ลบรายการ</button>
            </div>
        </div>
    </div>
</div>

<script>
    function DonationHistoryHandler() {
        return {
            donations: [],
            selectedDonation: null,
            detailItems: [],
            showDetailModal: false,
            showConfirmDialog: false,
            selectedDeleteId: null,
            filterStartDate: '',
            filterEndDate: '',
            filterSearch: '',

            async init() {
                await this.applyFilters();
            },

            async applyFilters() {
                try {
                    const query = new URLSearchParams();
                    if (this.filterStartDate) query.append('start_date', this.filterStartDate);
                    if (this.filterEndDate) query.append('end_date', this.filterEndDate);
                    if (this.filterSearch) query.append('search', this.filterSearch);

                    const res = await fetch(`/api/donations?${query.toString()}`);
                    const result = await res.json();
                    if (result.success) {
                        this.donations = result?.data || [];
                    }
                } catch (err) {
                    console.error('Error fetching donations:', err);
                }
            },

            clearFilters() {
                this.filterStartDate = '';
                this.filterEndDate = '';
                this.filterSearch = '';
                this.applyFilters();
            },

            async openDetail(donation) {
                this.selectedDonation = donation;
                // For donation, we fetch details using the GetById endpoint which should return detail items
                try {
                    const res = await fetch(`/api/donations/${donation.donation_id}`);
                    const json = await res.json();
                    if (json.success && json.data) {
                        this.detailItems = json.data.detail || [];
                    }
                } catch (err) {
                    console.error('Error fetching donation detail:', err);
                    this.detailItems = [];
                }
                this.showDetailModal = true;
            },

            confirmDelete(donationId) {
                this.selectedDeleteId = donationId;
                this.showConfirmDialog = true;
            },

            async deleteRecord(donationId) {
                this.showConfirmDialog = false;
                alert('✅ ลบรายการบริจาคสำเร็จ (ยังไม่มี API)');
                // API call will be implemented later
            }
        };
    }
</script>