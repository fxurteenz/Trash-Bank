<div class="space-y-6">
    <!-- Page Intro -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 mb-2">🧹 เคลียร์ยอดฝากขยะ</h1>
        <p class="text-slate-600 text-lg">ยืนยันและเคลียร์บัญชีฝากขยะของคณะ</p>
    </div>

    <!-- Clear Request Form -->
    <div x-data="centerConfirmForm()" x-init="init()" class="bg-white rounded-xl shadow-md p-6 card-hover">
        <h2 class="text-2xl font-bold text-slate-900 mb-5">📝 ส่งคำขอเคลียร์ยอด</h2>

        <form @submit.prevent="confirmSubmit" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">คณะ</label>
                    <select x-model="form.faculty_id" required
                        class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition bg-white">
                        <option value="">-- เลือกคณะ --</option>
                        <template x-for="f in faculties" :key="f.faculty_id">
                            <option :value="f.faculty_id" x-text="f.faculty_name"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">จากวันที่</label>
                    <input type="date" x-model="form.waste_clearance_period_start" required
                        class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition bg-white">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">ถึงวันที่</label>
                    <input type="date" x-model="form.waste_clearance_period_end" required
                        class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition bg-white">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <button type="submit" :disabled="submitting"
                    class="px-8 py-3 bg-emerald-600 hover:bg-emerald-700 disabled:bg-emerald-400 text-white rounded-lg font-semibold transition-colors flex items-center gap-2">
                    <span x-show="!submitting">✅</span>
                    <span x-show="submitting">⏳</span>
                    <span x-text="submitting ? 'กำลังประมวลผล...' : 'ส่งคำขอ'"></span>
                </button>
            </div>
        </form>
    </div>

    <!-- History Section -->
    <div x-data="clearanceHistory()" x-init="init()" @clearance-updated.window="fetchClearances()"
        class="space-y-6">
        
        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-md p-6 card-hover">
            <h2 class="text-xl font-bold text-slate-900 mb-5">🔍 ตัวกรองประวัติ</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">คณะ</label>
                    <select x-model="filters.faculty" @change="fetchClearances()"
                        class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition bg-white">
                        <option value="">ทั้งหมด</option>
                        <template x-for="f in faculties" :key="f.faculty_id">
                            <option :value="f.faculty_id" x-text="f.faculty_name"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">จากวันที่</label>
                    <input type="date" x-model="filters.start_date" @change="fetchClearances()"
                        class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition bg-white">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">ถึงวันที่</label>
                    <input type="date" x-model="filters.end_date" @change="fetchClearances()"
                        class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition bg-white">
                </div>
                <button @click="resetFilters()"
                    class="px-4 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg font-semibold transition-colors">
                    🔄 ล้าง
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-900">📋 ประวัติการเคลียร์ยอด</h2>
                <p class="text-sm text-slate-600 mt-1">พบ <span x-text="items.length"></span> รายการ</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-100 border-b-2 border-slate-300">
                        <tr class="text-left text-sm font-bold text-slate-700">
                            <th class="px-6 py-4">วันที่ทำรายการ</th>
                            <th class="px-6 py-4">คณะ</th>
                            <th class="px-6 py-4">ช่วงเวลา</th>
                            <th class="px-6 py-4 text-center">สถานะ</th>
                            <th class="px-6 py-4 text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <template x-if="loading">
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center">
                                    <span class="text-slate-500">⏳ กำลังโหลด...</span>
                                </td>
                            </tr>
                        </template>
                        <template x-if="!loading && items.length === 0">
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                    <p class="text-lg font-medium">📭 ไม่มีข้อมูล</p>
                                </td>
                            </tr>
                        </template>
                        <template x-for="item in items" :key="item.waste_clearance_id">
                            <tr class="hover:bg-slate-50 transition text-sm text-slate-700">
                                <td class="px-6 py-4 font-medium" x-text="formatDate(item.created_at)"></td>
                                <td class="px-6 py-4" x-text="item.faculty_name || item.faculty_id || '-'"></td>
                                <td class="px-6 py-4">
                                    <span x-text="formatDate(item.waste_clearance_period_start)"></span> - 
                                    <span x-text="formatDate(item.waste_clearance_period_end)"></span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <template x-if="item.waste_clearance_status === 'รอการยืนยัน'">
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">⏳ รอการยืนยัน</span>
                                    </template>
                                    <template x-if="item.waste_clearance_status === 'ยืนยันแล้ว'">
                                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">✅ ยืนยันแล้ว</span>
                                    </template>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <button @click="openDetail(item)"
                                            class="px-3 py-2 text-sm bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition-colors">
                                            👁️ ดู
                                        </button>
                                        <template x-if="item.waste_clearance_status === 'รอการยืนยัน' && userRole === 'admin'">
                                            <button @click="openManagePage(item.waste_clearance_id)"
                                                class="px-3 py-2 text-sm bg-emerald-100 hover:bg-emerald-200 text-emerald-700 rounded-lg transition-colors">
                                                ✏️ จัดการ
                                            </button>
                                        </template>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <dialog x-show="detailDialogShow" x-ref="detailDialog" @click.self="detailDialogShow = false"
        class="fixed inset-0 mx-auto my-auto p-6 bg-transparent z-50 backdrop:bg-black/50"
        x-init="$watch('detailDialogShow', value => {if (value) $refs.detailDialog.showModal();else $refs.detailDialog.close();})">
        <div class="bg-white p-8 rounded-xl shadow-2xl max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-2xl font-bold text-slate-900">📋 รายละเอียดการเคลียร์ยอด</h3>
                    <template x-if="detailItem">
                        <p class="text-sm text-slate-600 mt-2">
                            <span x-text="formatDate(detailItem.waste_clearance_period_start)"></span> ถึง
                            <span x-text="formatDate(detailItem.waste_clearance_period_end)"></span>
                        </p>
                    </template>
                </div>
                <button @click="detailDialogShow = false" class="text-slate-400 hover:text-slate-600">
                    <span class="text-2xl">✕</span>
                </button>
            </div>

            <div class="space-y-6" x-show="detailItem">
                <!-- Info Grid -->
                <div class="grid grid-cols-3 gap-4 bg-slate-50 p-4 rounded-lg border border-slate-200">
                    <div>
                        <p class="text-slate-600 text-sm font-medium">วันที่ทำรายการ</p>
                        <p class="font-bold text-slate-900" x-text="formatDate(detailItem.created_at)"></p>
                    </div>
                    <div>
                        <p class="text-slate-600 text-sm font-medium">คณะ</p>
                        <p class="font-bold text-slate-900" x-text="detailItem.faculty_name || '-'"></p>
                    </div>
                    <div>
                        <p class="text-slate-600 text-sm font-medium">สถานะ</p>
                        <p class="font-bold" :class="detailItem.waste_clearance_status === 'ยืนยันแล้ว' ? 'text-green-600' : 'text-yellow-600'"
                            x-text="detailItem.waste_clearance_status"></p>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="border rounded-lg overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-100 border-b border-slate-300">
                            <tr class="text-left font-bold text-slate-700">
                                <th class="px-4 py-3">หมวดหมู่</th>
                                <th class="px-4 py-3">ประเภท</th>
                                <th class="px-4 py-3 text-right">น้ำหนัก (ระบบ)</th>
                                <th class="px-4 py-3 text-right">น้ำหนัก (จริง)</th>
                                <th class="px-4 py-3 text-center">สถานะ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            <template x-if="detailLoading">
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-slate-500">⏳ กำลังโหลด...</td>
                                </tr>
                            </template>
                            <template x-if="!detailLoading && detailList.length === 0">
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-slate-500">📭 ไม่มีรายการ</td>
                                </tr>
                            </template>
                            <template x-for="d in detailList" :key="d.clearance_detail_id">
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-4 py-3 font-medium text-slate-900" x-text="d.waste_category_name"></td>
                                    <td class="px-4 py-3" x-text="d.waste_type_name"></td>
                                    <td class="px-4 py-3 text-right font-medium" x-text="parseFloat(d.clearance_detail_transaction_weight).toFixed(2)"></td>
                                    <td class="px-4 py-3 text-right" x-text="d.clearance_detail_clearance_weight ? parseFloat(d.clearance_detail_clearance_weight).toFixed(2) : '-'"></td>
                                    <td class="px-4 py-3 text-center">
                                        <span x-show="d.clearance_detail_success == 1" class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-semibold">✅ สำเร็จ</span>
                                        <span x-show="d.clearance_detail_success != 1" class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-semibold">⏳ รอ</span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </dialog>
</div>

<script>
    function centerConfirmForm() {
        return {
            faculties: [],
            submitting: false,
            form: {
                faculty_id: '',
                waste_clearance_period_start: '',
                waste_clearance_period_end: ''
            },

            async init() {
                try {
                    const res = await fetch('/api/faculties');
                    const data = await res.json();
                    this.faculties = data.data || [];
                } catch (err) {
                    console.error('Failed to load faculties', err);
                }
            },

            confirmSubmit() {
                if (!this.form.faculty_id || !this.form.waste_clearance_period_start || !this.form.waste_clearance_period_end) {
                    alert('กรุณากรอกข้อมูลให้ครบถ้วน');
                    return;
                }

                const faculty = this.faculties.find(f => f.faculty_id == this.form.faculty_id);
                if (confirm(`ยืนยันการทำรายการ?\n\nคณะ: ${faculty?.faculty_name || '-'}\nวันที่: ${this.form.waste_clearance_period_start} ถึง ${this.form.waste_clearance_period_end}`)) {
                    this.submit();
                }
            },

            async submit() {
                this.submitting = true;
                try {
                    const res = await fetch('/api/clearances', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(this.form)
                    });
                    const data = await res.json();
                    if (res.ok) {
                        alert('ส่งคำขอสำเร็จ');
                        this.form.faculty_id = '';
                        this.form.waste_clearance_period_start = '';
                        this.form.waste_clearance_period_end = '';
                        window.dispatchEvent(new CustomEvent('clearance-updated'));
                    } else {
                        throw new Error(data.message || 'เกิดข้อผิดพลาด');
                    }
                } catch (err) {
                    console.error(err);
                    alert('เกิดข้อผิดพลาด: ' + err.message);
                } finally {
                    this.submitting = false;
                }
            }
        }
    }

    function clearanceHistory() {
        return {
            userRole: '<?php echo $user->role_name ?? ""; ?>',
            items: [],
            faculties: [],
            filters: {
                faculty: '',
                start_date: '',
                end_date: ''
            },
            detailDialogShow: false,
            detailItem: null,
            detailList: [],
            detailLoading: false,
            loading: false,

            async init() {
                await Promise.all([this.fetchFaculties(), this.fetchClearances()]);
            },

            async fetchFaculties() {
                try {
                    const res = await fetch('/api/faculties');
                    const data = await res.json();
                    this.faculties = data.data || [];
                } catch (err) {
                    console.error('Failed to load faculties', err);
                }
            },

            async fetchClearances() {
                this.loading = true;
                try {
                    const params = new URLSearchParams();
                    if (this.filters.faculty) params.append('faculty', this.filters.faculty);
                    if (this.filters.start_date) params.append('start_date', this.filters.start_date);
                    if (this.filters.end_date) params.append('end_date', this.filters.end_date);

                    const res = await fetch(`/api/clearances?${params.toString()}`);
                    const data = await res.json();

                    if (data.success) {
                        this.items = data.data || [];
                    }
                } catch (err) {
                    console.error(err);
                } finally {
                    this.loading = false;
                }
            },

            async openDetail(item) {
                this.detailItem = item;
                this.detailDialogShow = true;
                this.detailList = [];
                this.detailLoading = true;
                try {
                    const res = await fetch(`/api/clearances/${item.waste_clearance_id}`);
                    const result = await res.json();
                    if (result.success) {
                        this.detailList = result.data.detail || [];
                    }
                } catch (err) {
                    console.error(err);
                } finally {
                    this.detailLoading = false;
                }
            },

            openManagePage(wcid) {
                if (this.userRole === 'admin') {
                    window.location.href = `/admin/transactions/clear_waste/manage/${wcid}`;
                } else if (this.userRole === 'center') {
                    window.location.href = `/waste_center/transactions/clear_waste/manage/${wcid}`;
                }
            },

            resetFilters() {
                this.filters = { faculty: '', start_date: '', end_date: '' };
                this.fetchClearances();
            },

            formatDate(d) {
                if (!d) return '-';
                return new Date(d).toLocaleDateString('th-TH', { day: 'numeric', month: 'short', year: '2-digit' });
            }
        }
    }
</script>
