<div class="space-y-6" x-data="RedeemRewardsPage()" x-init="init()">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">จำนวนการแลกรางวัลทั้งหมด</p>
                    <p class="text-2xl font-bold text-emerald-700" x-text="total"></p>
                </div>
                <div class="p-3 bg-emerald-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">รอการตรวจสอบ</p>
                    <p class="text-2xl font-bold text-yellow-700" x-text="getCountByStatus('pending')"></p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">เสร็จสิ้นแล้ว</p>
                    <p class="text-2xl font-bold text-blue-700" x-text="getCountByStatus('completed')"></p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">รายการแลกของรางวัล</h2>
        </div>

        <div class="mb-4 flex flex-col md:flex-row gap-4">
            <input type="text" x-model="filters.search" @input.debounce="300ms" @input="fetch()"
                placeholder="ค้นหาชื่อสมาชิก/รางวัล..."
                class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <select x-model="filters.status" @change="fetch()"
                class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                <option value="">สถานะทั้งหมด</option>
                <option value="pending">รอการตรวจสอบ</option>
                <option value="completed">เสร็จสิ้นแล้ว</option>
                <option value="cancelled">ยกเลิก</option>
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-emerald-100 rounded-lg">
                <thead class="bg-emerald-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">วันที่</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">สมาชิก</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">รางวัล</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">จำนวน</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">แต้มที่ใช้</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">สถานะ</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <template x-for="(row, idx) in rewards" :key="row.member_reward_id">
                        <tr class="hover:bg-emerald-50 transition duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="((page - 1) * limit) + idx + 1"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="formatDate(row.created_at)"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="row.member_name || '-'"></td>
                            <td class="px-6 py-4 text-sm text-gray-900" x-text="row.reward_name || '-'"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="row.member_reward_quantity || 1"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="row.reward_required_point || '-'"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                <span :class="getStatusClass(row.member_reward_status)" x-text="getStatusText(row.member_reward_status)"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm space-x-2">
                                <template x-if="row.member_reward_status === 'pending'">
                                    <button @click="confirmRedemption(row)" class="text-green-600 hover:text-green-900 hover:bg-green-100 p-2 rounded-full transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                    <button @click="rejectRedemption(row)" class="text-red-600 hover:text-red-900 hover:bg-red-100 p-2 rounded-full transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </template>
                                <template x-if="row.member_reward_status !== 'pending'">
                                    <span class="text-gray-400">-</span>
                                </template>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>

            <template x-if="rewards.length === 0">
                <div class="text-center py-12">
                    <p class="text-gray-500">ไม่มีข้อมูลการแลกของรางวัล</p>
                </div>
            </template>
        </div>

        <div class="flex items-center justify-between mt-4">
            <button class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50"
                :disabled="page <= 1" @click="page--; fetch()">ก่อนหน้า</button>
            <span class="text-sm text-gray-600">หน้า <span x-text="page"></span> จาก <span x-text="totalPages"></span></span>
            <button class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50"
                :disabled="page >= totalPages" @click="page++; fetch()">ถัดไป</button>
        </div>
    </div>
</div>

<script>
function RedeemRewardsPage() {
    return {
        rewards: [],
        total: 0,
        page: 1,
        limit: 10,
        totalPages: 1,
        filters: { search: '', status: '' },

        async init() {
            await this.fetch();
        },

        async fetch() {
            try {
                const params = new URLSearchParams();
                params.append('page', String(this.page));
                params.append('limit', String(this.limit));
                if (this.filters.search) params.append('search', this.filters.search);
                if (this.filters.status) params.append('status', this.filters.status);

                const res = await fetch(`/api/member_rewards?${params.toString()}`);
                const json = await res.json();
                if (!json.success) throw new Error(json.message || 'โหลดข้อมูลล้มเหลว');

                this.rewards = Array.isArray(json.data) ? json.data : [];
                this.total = Number(json.total || 0);
                this.totalPages = Math.ceil(this.total / this.limit) || 1;
                if (this.page > this.totalPages) this.page = this.totalPages;
            } catch (e) {
                console.error(e);
                this.rewards = [];
                this.total = 0;
                this.totalPages = 1;
            }
        },

        getCountByStatus(status) {
            return this.rewards.filter(r => r.member_reward_status === status).length;
        },

        getStatusClass(status) {
            const classes = "px-3 py-1 text-xs rounded-full font-medium ";
            switch (status) {
                case 'pending': return classes + "bg-yellow-100 text-yellow-800";
                case 'completed': return classes + "bg-green-100 text-green-800";
                case 'cancelled': return classes + "bg-red-100 text-red-800";
                default: return classes + "bg-gray-100 text-gray-800";
            }
        },

        getStatusText(status) {
            switch (status) {
                case 'pending': return 'รอการตรวจสอบ';
                case 'completed': return 'เสร็จสิ้นแล้ว';
                case 'cancelled': return 'ยกเลิก';
                default: return status;
            }
        },

        async confirmRedemption(row) {
            if (!confirm('ยืนยันการแลกของรางวัลนี้?')) return;
            try {
                const formData = new FormData();
                formData.append('member_reward_id', row.member_reward_id);
                formData.append('member_reward_status', 'completed');
                
                const res = await fetch(`/api/member_rewards/update/${row.member_reward_id}`, { 
                    method: 'POST', 
                    body: formData 
                });
                const json = await res.json();
                if (!json.success) throw new Error(json.message || 'บันทึกไม่สำเร็จ');
                
                await this.fetch();
            } catch (e) {
                console.error(e);
                alert(e.message || 'เกิดข้อผิดพลาด');
            }
        },

        async rejectRedemption(row) {
            if (!confirm('ปฏิเสธการแลกของรางวัลนี้?')) return;
            try {
                const formData = new FormData();
                formData.append('member_reward_id', row.member_reward_id);
                formData.append('member_reward_status', 'cancelled');
                
                const res = await fetch(`/api/member_rewards/update/${row.member_reward_id}`, { 
                    method: 'POST', 
                    body: formData 
                });
                const json = await res.json();
                if (!json.success) throw new Error(json.message || 'บันทึกไม่สำเร็จ');
                
                await this.fetch();
            } catch (e) {
                console.error(e);
                alert(e.message || 'เกิดข้อผิดพลาด');
            }
        },

        formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('th-TH', { year: 'numeric', month: 'short', day: 'numeric' });
        },
    }
}
</script>
