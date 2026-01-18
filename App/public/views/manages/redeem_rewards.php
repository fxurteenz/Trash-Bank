<div class="space-y-6" x-data="RedeemRewardsPage()" x-init="init()">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">รายการแลกของรวม</p>
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
                    <p class="text-sm text-gray-600">รายการรอรับ</p>
                    <p class="text-2xl font-bold text-yellow-700" x-text="getTotalByStatus('pending')"></p>
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
                    <p class="text-sm text-gray-600">รายการที่รับแล้ว</p>
                    <p class="text-2xl font-bold text-blue-700" x-text="getTotalByStatus('received')"></p>
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
            <h2 class="text-2xl font-bold">ประวัติการแลกของรางวัล</h2>
            <button @click="openCreate()"
                class="flex items-center px-4 py-2 bg-emerald-500 text-white rounded-full hover:bg-emerald-600 transition-colors font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                เพิ่มรายการ
            </button>
        </div>

        <div class="mb-4 flex flex-col md:flex-row gap-4">
            <input type="text" x-model="filters.search" @input.debounce.300ms="fetch()"
                placeholder="ค้นหาชื่อสมาชิก/ของรางวัล..."
                class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <select x-model="filters.status" @change="fetch()"
                class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                <option value="">สถานะทั้งหมด</option>
                <option value="pending">รอรับ</option>
                <option value="received">ได้รับแล้ว</option>
                <option value="cancelled">ยกเลิก</option>
            </select>
            <input type="date" x-model="filters.date" @change="fetch()"
                class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-emerald-100 rounded-lg">
                <thead class="bg-emerald-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">วันที่</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">สมาชิก</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">ของรางวัล</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">จำนวน</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">แต้มที่ใช้</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">สถานะ</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <template x-for="(row, idx) in redemptions" :key="row.member_reward_id">
                        <tr class="hover:bg-emerald-50 transition duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="((page - 1) * limit) + idx + 1"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="formatDate(row.member_reward_date)"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="row.member_name || '-' "></td>
                            <td class="px-6 py-4 text-sm text-gray-900" x-text="row.reward_name || '-' "></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-900" x-text="row.member_reward_qty"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-900" x-text="row.member_reward_point_used"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-3 py-1 text-xs rounded-full font-medium"
                                    :class="{
                                        'bg-yellow-100 text-yellow-800': row.member_reward_status === 'pending',
                                        'bg-green-100 text-green-800': row.member_reward_status === 'received',
                                        'bg-red-100 text-red-800': row.member_reward_status === 'cancelled'
                                    }"
                                    x-text="formatStatus(row.member_reward_status)"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm space-x-2">
                                <button @click="openEdit(row)" class="text-emerald-600 hover:text-emerald-900 hover:bg-emerald-100 p-2 rounded-full transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button @click="confirmDelete(row.member_reward_id)" class="text-red-600 hover:text-red-900 hover:bg-red-100 p-2 rounded-full transition" x-show="row.member_reward_status !== 'cancelled'">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between mt-4">
            <button class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50"
                :disabled="page <= 1" @click="page--; fetch()">ก่อนหน้า</button>
            <span class="text-sm text-gray-600">หน้า <span x-text="page"></span> จาก <span x-text="totalPages"></span></span>
            <button class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50"
                :disabled="page >= totalPages" @click="page++; fetch()">ถัดไป</button>
        </div>
    </div>

    <dialog x-show="dialogShow" x-ref="dialog" @click.self="dialogShow=false" @close="dialogShow=false"
        class="fixed inset-0 mx-auto my-auto p-0 bg-transparent z-50"
        x-init="$watch('dialogShow', v => v ? $refs.dialog.showModal() : $refs.dialog.close())">
        <div class="bg-white shadow-sm rounded-lg p-6 w-[28rem] max-w-full">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg" x-text="isEditing ? 'แก้ไขการแลก' : 'เพิ่มการแลกใหม่'"></h3>
                <button @click="dialogShow=false" class="text-gray-500 hover:text-gray-700">✕</button>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">สมาชิก</label>
                    <select x-model="form.member_id" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">-- เลือกสมาชิก --</option>
                        <template x-for="m in members" :key="m.member_id">
                            <option :value="String(m.member_id)" x-text="m.member_name + ' (#' + m.member_id + ') - ' + m.total_points + ' แต้ม'"> </option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ของรางวัล</label>
                    <select x-model="form.reward_id" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">-- เลือกของรางวัล --</option>
                        <template x-for="r in rewards" :key="r.reward_id">
                            <option :value="String(r.reward_id)" x-text="r.reward_name + ' (' + r.reward_point_required + ' แต้ม, คงเหลือ: ' + r.reward_stock + ')'"> </option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">จำนวน</label>
                    <input type="number" x-model="form.member_reward_qty" min="1" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="1">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">สถานะ</label>
                    <select x-model="form.member_reward_status" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="pending">รอรับ</option>
                        <option value="received">ได้รับแล้ว</option>
                        <option value="cancelled">ยกเลิก</option>
                    </select>
                </div>

                <div x-show="form.member_reward_status === 'received'" x-transition>
                    <label class="block text-sm font-medium text-gray-700 mb-1">วันที่รับ</label>
                    <input type="date" x-model="form.member_reward_redeem_date" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-2">
                <button @click="dialogShow=false" class="px-4 py-2 bg-gray-200 rounded-full hover:bg-gray-300 font-medium">ยกเลิก</button>
                <button @click="submit()" class="px-4 py-2 bg-emerald-500 text-white rounded-full hover:bg-emerald-600 font-medium">
                    <span x-text="isEditing ? 'บันทึก' : 'เพิ่ม'"></span>
                </button>
            </div>
        </div>
    </dialog>
</div>

<script>
function RedeemRewardsPage() {
    return {
        redemptions: [],
        total: 0,
        page: 1,
        limit: 10,
        totalPages: 1,
        members: [],
        rewards: [],
        filters: { search: '', status: '', date: '' },
        dialogShow: false,
        isEditing: false,
        form: {
            member_reward_id: null,
            member_id: '',
            reward_id: '',
            member_reward_qty: '1',
            member_reward_status: 'pending',
            member_reward_redeem_date: new Date().toISOString().split('T')[0],
        },

        async init() {
            await Promise.all([this.fetchMembers(), this.fetchRewards()]);
            await this.fetch();
        },

        async fetchMembers() {
            try {
                const params = new URLSearchParams({ page: '1', limit: '999' });
                const res = await fetch(`/api/members?${params.toString()}`);
                const json = await res.json();
                this.members = json?.success && Array.isArray(json.data) ? json.data : [];
            } catch {
                this.members = [];
            }
        },

        async fetchRewards() {
            try {
                const params = new URLSearchParams({ page: '1', limit: '999' });
                const res = await fetch(`/api/rewards?${params.toString()}`);
                const json = await res.json();
                this.rewards = json?.success && Array.isArray(json.data) ? json.data : [];
            } catch {
                this.rewards = [];
            }
        },

        async fetch() {
            try {
                const params = new URLSearchParams();
                params.append('page', String(this.page));
                params.append('limit', String(this.limit));
                if (this.filters.search) params.append('search', this.filters.search);
                if (this.filters.status) params.append('status', this.filters.status);
                if (this.filters.date) params.append('date', this.filters.date);

                const res = await fetch(`/api/member_rewards?${params.toString()}`);
                const json = await res.json();
                if (!json.success) throw new Error(json.message || 'โหลดข้อมูลล้มเหลว');

                this.redemptions = Array.isArray(json.data) ? json.data : [];
                this.total = Number(json.total || 0);
                this.totalPages = Math.ceil(this.total / this.limit) || 1;
                if (this.page > this.totalPages) this.page = this.totalPages;
            } catch (e) {
                console.error(e);
                this.redemptions = [];
                this.total = 0;
                this.totalPages = 1;
            }
        },

        getTotalByStatus(status) {
            return this.redemptions.filter(r => r.member_reward_status === status).length;
        },

        openCreate() {
            this.isEditing = false;
            this.form = {
                member_reward_id: null,
                member_id: '',
                reward_id: '',
                member_reward_qty: '1',
                member_reward_status: 'pending',
                member_reward_redeem_date: new Date().toISOString().split('T')[0],
            };
            this.dialogShow = true;
        },

        openEdit(row) {
            this.isEditing = true;
            this.form = {
                member_reward_id: row.member_reward_id,
                member_id: String(row.member_id || ''),
                reward_id: String(row.reward_id || ''),
                member_reward_qty: String(row.member_reward_qty || '1'),
                member_reward_status: row.member_reward_status || 'pending',
                member_reward_redeem_date: row.member_reward_redeem_date || new Date().toISOString().split('T')[0],
            };
            this.dialogShow = true;
        },

        async submit() {
            try {
                const formData = new FormData();
                Object.entries(this.form).forEach(([k, v]) => {
                    if (k === 'member_reward_id') return;
                    formData.append(k, v ?? '');
                });

                let url = '/api/member_rewards';
                if (this.isEditing && this.form.member_reward_id) {
                    url = `/api/member_rewards/update/${this.form.member_reward_id}`;
                }

                const res = await fetch(url, { method: 'POST', body: formData });
                const json = await res.json();
                if (!json.success) throw new Error(json.message || 'บันทึกไม่สำเร็จ');

                this.dialogShow = false;
                await this.fetch();
            } catch (e) {
                console.error(e);
                alert(e.message || 'เกิดข้อผิดพลาด');
            }
        },

        async confirmDelete(id) {
            if (!confirm('ยืนยันการยกเลิกรายการนี้?')) return;
            try {
                const formData = new FormData();
                formData.append('member_reward_id', String(id));
                formData.append('member_reward_status', 'cancelled');
                
                const res = await fetch(`/api/member_rewards/update/${id}`, { method: 'POST', body: formData });
                const json = await res.json();
                if (!json.success) throw new Error(json.message || 'ยกเลิกไม่สำเร็จ');
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

        formatStatus(status) {
            const statusMap = { 'pending': 'รอรับ', 'received': 'ได้รับแล้ว', 'cancelled': 'ยกเลิก' };
            return statusMap[status] || status;
        }
    }
}
</script>
