<script>
    function DonationsPage() {
        return {
            donations: [],
            total: 0,
            page: 1,
            limit: 10,
            totalPages: 1,
            members: [],
            faculties: [],
            filters: { search: '', date: '' },
            dialogShow: false,
            isEditing: false,
            form: {
                donation_id: null,
                member_id: '',
                faculty_id: '',
                donation_description: '',
                donation_total_value: '',
                donation_goodness_point: '',
                donation_reason: '',
                donation_date: new Date().toISOString().split('T')[0],
            },

            async init() {
                await Promise.all([this.fetchMembers(), this.fetchFaculties()]);
                await this.fetch();
            },

            autoFillFaculty() {
                if (!this.form.member_id) {
                    this.form.faculty_id = '';
                    return;
                }
                const member = this.members.find(m => String(m.member_id) === String(this.form.member_id));
                if (member && member.faculty_id) {
                    this.form.faculty_id = String(member.faculty_id);
                } else {
                    this.form.faculty_id = '';
                }
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

            async fetchFaculties() {
                try {
                    const params = new URLSearchParams({ page: '1', limit: '999' });
                    const res = await fetch(`/api/faculties?${params.toString()}`);
                    const json = await res.json();
                    this.faculties = json?.success && Array.isArray(json.data) ? json.data : [];
                } catch {
                    this.faculties = [];
                }
            },

            async fetch() {
                try {
                    const params = new URLSearchParams();
                    params.append('page', String(this.page));
                    params.append('limit', String(this.limit));
                    if (this.filters.search) params.append('search', this.filters.search);
                    if (this.filters.date) params.append('date', this.filters.date);

                    const res = await fetch(`/api/donations?${params.toString()}`);
                    const json = await res.json();
                    if (!json.success) throw new Error(json.message || 'โหลดข้อมูลล้มเหลว');

                    this.donations = Array.isArray(json.data) ? json.data : [];
                    this.total = Number(json.total || 0);
                    this.totalPages = Math.ceil(this.total / this.limit) || 1;
                    if (this.page > this.totalPages) this.page = this.totalPages;
                } catch (e) {
                    console.error(e);
                    this.donations = [];
                    this.total = 0;
                    this.totalPages = 1;
                }
            },

            getTotalValue() {
                return this.donations.reduce((s, d) => s + Number(d.donation_total_value || 0), 0);
            },

            getTotalGoodness() {
                return this.donations.reduce((s, d) => s + Number(d.donation_goodness_point || 0), 0);
            },

            openCreate() {
                this.isEditing = false;
                this.form = {
                    donation_id: null,
                    member_id: '',
                    faculty_id: '',
                    donation_description: '',
                    donation_total_value: '',
                    donation_goodness_point: '',
                    donation_reason: '',
                    donation_date: new Date().toISOString().split('T')[0],
                };
                this.dialogShow = true;
            },

            openEdit(row) {
                this.isEditing = true;
                this.form = {
                    donation_id: row.donation_id,
                    member_id: row.member_id ? String(row.member_id) : '',
                    faculty_id: row.faculty_id ? String(row.faculty_id) : '',
                    donation_description: row.donation_description || '',
                    donation_total_value: row.donation_total_value ?? '',
                    donation_goodness_point: row.donation_goodness_point ?? '',
                    donation_reason: row.donation_reason || '',
                    donation_date: row.donation_date || new Date().toISOString().split('T')[0],
                };
                this.dialogShow = true;
            },

            async submit() {
                try {
                    const formData = new FormData();
                    Object.entries(this.form).forEach(([k, v]) => {
                        if (k === 'donation_id') return;
                        formData.append(k, v ?? '');
                    });

                    let url = '/api/donations';
                    if (this.isEditing && this.form.donation_id) {
                        url = `/api/donations/update/${this.form.donation_id}`;
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
                if (!confirm('ยืนยันการลบรายการนี้?')) return;
                try {
                    const formData = new FormData();
                    formData.append('donation_id', String(id));
                    const res = await fetch('/api/donations/delete', { method: 'POST', body: formData });
                    const json = await res.json();
                    if (!json.success) throw new Error(json.message || 'ลบไม่สำเร็จ');
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


<div class="space-y-6" x-data="DonationsPage()" x-init="init()">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">รายการบริจาคทั้งหมด</p>
                    <p class="text-2xl font-bold text-emerald-700" x-text="total"></p>
                </div>
                <div class="p-3 bg-emerald-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">มูลค่ารวม</p>
                    <p class="text-2xl font-bold text-blue-700"
                        x-text="`฿ ${getTotalValue().toLocaleString('th-TH', {minimumFractionDigits: 2})}`"></p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">แต้มความดีรวม</p>
                    <p class="text-2xl font-bold text-purple-700" x-text="getTotalGoodness()"></p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">รายการบริจาค</h2>
            <button @click="window.open('/waste_center/transactions/donation', '_blank')"
                class="flex items-center px-4 py-2 bg-emerald-500 text-white rounded-full hover:bg-emerald-600 transition-colors font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                เพิ่มรายการ
            </button>
        </div>

        <div class="mb-4 flex flex-col md:flex-row gap-4">
            <input type="text" x-model="filters.search" @input.debounce.300ms="fetch()"
                placeholder="ค้นหาชื่อสมาชิก/รายละเอียด..."
                class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <input type="date" x-model="filters.date" @change="fetch()"
                class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-emerald-100 rounded-lg">
                <thead class="bg-emerald-50">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">
                            #</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">
                            วันที่</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">
                            สมาชิก</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">
                            มูลค่า</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">
                            แต้มความดี</th>
                        <!-- <th
                            class="px-6 py-3 text-center text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">
                            จัดการ</th> -->
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <template x-for="(row, idx) in donations" :key="row.donation_id">
                        <tr class="hover:bg-emerald-50 transition duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                x-text="((page - 1) * limit) + idx + 1"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                x-text="formatDate(row.created_at)"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                x-text="row.donor_name || '-'"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                x-text="row.donation_total_value ?? '-'"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                x-text="row.donation_goodness_point ?? '-'"></td>
                            <!-- <td class="px-6 py-4 whitespace-nowrap text-center text-sm space-x-2">
                                <button @click="openEdit(row)"
                                    class="text-emerald-600 hover:text-emerald-900 hover:bg-emerald-100 p-2 rounded-full transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button @click="confirmDelete(row.donation_id)"
                                    class="text-red-600 hover:text-red-900 hover:bg-red-100 p-2 rounded-full transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td> -->
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between mt-4">
            <button class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50" :disabled="page <= 1"
                @click="page--; fetch()">ก่อนหน้า</button>
            <span class="text-sm text-gray-600">หน้า <span x-text="page"></span> จาก <span
                    x-text="totalPages"></span></span>
            <button class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50"
                :disabled="page >= totalPages" @click="page++; fetch()">ถัดไป</button>
        </div>
    </div>

    <dialog x-show="dialogShow" x-ref="dialog" @click.self="dialogShow=false" @close="dialogShow=false"
        class="fixed inset-0 mx-auto my-auto p-0 bg-transparent z-50"
        x-init="$watch('dialogShow', v => v ? $refs.dialog.showModal() : $refs.dialog.close())">
        <div class="bg-white shadow-sm rounded-lg p-6 w-[28rem] max-w-full">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg" x-text="isEditing ? 'แก้ไขรายการบริจาค' : 'เพิ่มรายการบริจาค'"></h3>
                <button @click="dialogShow=false" class="text-gray-500 hover:text-gray-700">✕</button>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">สมาชิก (ไม่บังคับ)</label>
                    <select x-model="form.member_id" @change="autoFillFaculty()"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">-- ไม่ระบุ --</option>
                        <template x-for="m in members" :key="m.member_id">
                            <option :value="String(m.member_id)" x-text="m.member_name + ' (#' + m.member_id + ')'">
                            </option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">คณะ (ไม่บังคับ)</label>
                    <select x-model="form.faculty_id" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">-- ไม่ระบุ --</option>
                        <template x-for="f in faculties" :key="f.faculty_id">
                            <option :value="String(f.faculty_id)" x-text="f.faculty_name"> </option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">รายละเอียด</label>
                    <textarea x-model="form.donation_description" rows="3"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2"
                        placeholder="รายละเอียดการบริจาค"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">มูลค่าโดยประมาณ</label>
                        <input type="number" step="0.01" x-model="form.donation_total_value"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="0.00">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">แต้มความดี</label>
                        <input type="number" x-model="form.donation_goodness_point"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="0">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">เหตุผล/หมายเหตุ</label>
                    <input type="text" x-model="form.donation_reason"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="หมายเหตุ">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">วันที่</label>
                    <input type="date" x-model="form.donation_date"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-2">
                <button @click="dialogShow=false"
                    class="px-4 py-2 bg-gray-200 rounded-full hover:bg-gray-300 font-medium">ยกเลิก</button>
                <button @click="submit()"
                    class="px-4 py-2 bg-emerald-500 text-white rounded-full hover:bg-emerald-600 font-medium">
                    <span x-text="isEditing ? 'บันทึก' : 'เพิ่ม'"></span>
                </button>
            </div>
        </div>
    </dialog>
</div>