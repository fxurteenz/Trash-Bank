<div x-data="DepositHistory()" x-init="init()" class="space-y-6">
    <div class="md:w-1/3 flex flex-col justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 mb-2 flex items-center gap-3">
                <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path class="w-10 h-10 text-slate-400 group-hover:text-emerald-600" fill="none"
                        stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M11 22c-.818 0-1.6-.33-3.163-.99C3.946 19.366 2 18.543 2 17.16V7m9 15V11.355M11 22c.34 0 .646-.057 1-.172M20 7v4.5M18 18l.906-.905M22 18a4 4 0 1 0-8 0a4 4 0 0 0 8 0M7.326 9.691L4.405 8.278C2.802 7.502 2 7.114 2 6.5s.802-1.002 2.405-1.778l2.92-1.413C9.13 2.436 10.03 2 11 2s1.871.436 3.674 1.309l2.921 1.413C19.198 5.498 20 5.886 20 6.5s-.802 1.002-2.405 1.778l-2.92 1.413C12.87 10.564 11.97 11 11 11s-1.871-.436-3.674-1.309M5 12l2 1m9-9L6 9" />
                </svg>
                <span>ประวัติการฝากขยะ</span>
            </h1>
            <p class="text-slate-600 text-sm">ประวัติการทำรายการฝากขยะ</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-blue-500 flex justify-between">
            <p class="text-slate-600 text-sm">รายการ (หน้านี้)</p>
            <p class="text-xl font-bold text-blue-600" x-text="rows.length"></p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-blue-500 flex justify-between">
            <p class="text-slate-600 text-sm">น้ำหนัก (หน้านี้)</p>
            <p class="text-xl font-bold text-purple-600">
                <span x-text="rows.reduce((s,x)=>s+Number(x.waste_transaction_total_weight||0),0).toFixed(2)"></span>
                <span class="text-lg">กก.</span>
            </p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-blue-500 flex justify-between">
            <p class="text-slate-600 text-sm">คะแนน (หน้านี้)</p>
            <p class="text-xl font-bold text-emerald-600"
                x-text="rows.reduce((s,x)=>s+Number(x.waste_transaction_total_point||0),0).toFixed(0)"></p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-blue-500 flex justify-between">
            <p class="text-slate-600 text-sm">เฉลี่ย/ครั้ง</p>
            <p class="text-xl font-bold text-orange-600"
                x-text="(rows.length>0?(rows.reduce((s,x)=>s+Number(x.waste_transaction_total_weight||0),0)/rows.length).toFixed(2):0)">
            </p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md p-4 card-hover flex flex-col md:flex-row justify-center gap-4 items-end">
        <div class="w-full md:w-auto flex-1">
            <label class="block text-sm font-semibold text-slate-700">วันที่เริ่มต้น</label>
            <input x-model="start" @change="apply(1)" type="date"
                class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500">
        </div>
        <div class="w-full md:w-auto flex-1">
            <label class="block text-sm font-semibold text-slate-700">วันที่สิ้นสุด</label>
            <input x-model="end" @change="apply(1)" type="date"
                class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500">
        </div>
        <div class="w-full md:w-auto flex-[2]">
            <label class="block text-sm font-semibold text-slate-700">ค้นหาผู้ฝาก</label>
            <input x-model="memberSearch" @keydown.enter="apply(1)" type="text" placeholder="ชื่อ/รหัส/เบอร์/อีเมล"
                class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500">
        </div>
        <div class="flex gap-2 w-full md:w-auto">
            <button @click="apply(1)"
                class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold whitespace-nowrap">ค้นหา</button>
            <button @click="clear()"
                class="flex-1 px-4 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg font-semibold whitespace-nowrap">ล้าง</button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden flex flex-col">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-900">รายการฝาก</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-100 border-b-2 border-slate-300">
                    <tr class="text-left text-sm font-bold text-slate-700">
                        <th class="px-6 py-4 w-12">#</th>
                        <th class="px-6 py-4">วันที่</th>
                        <th class="px-6 py-4">ผู้ฝาก</th>
                        <th class="px-6 py-4">เจ้าหน้าที่</th>
                        <th class="px-6 py-4">คณะ</th>
                        <th class="px-6 py-4 text-right">รวมน้ำหนัก</th>
                        <th class="px-6 py-4 text-right">รวมคะแนน</th>
                        <th class="px-6 py-4 text-center">ดำเนินการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <template x-for="(r,i) in rows" :key="r.waste_transaction_id">
                        <tr class="hover:bg-slate-50 transition text-sm text-slate-700"
                            @click="openDetail(r.waste_transaction_id)" style="cursor:pointer;">
                            <td class="px-6 py-4" x-text="(page - 1) * limit + i + 1"></td>
                            <td class="px-6 py-4" x-text="new Date(r.created_at).toLocaleDateString('th-TH')"></td>
                            <td class="px-6 py-4" x-text="r.member_name||'-'"></td>
                            <td class="px-6 py-4" x-text="r.staff_name||'-'"></td>
                            <td class="px-6 py-4" x-text="r.faculty_name||'-'"></td>
                            <td class="px-6 py-4 text-right"
                                x-text="Number(r.waste_transaction_total_weight||0).toFixed(2)"></td>
                            <td class="px-6 py-4 text-right"
                                x-text="Number(r.waste_transaction_total_point||0).toFixed(0)"></td>
                            <td class="px-6 py-4 text-center">
                                <button @click.stop="openDetail(r.waste_transaction_id)"
                                    class="text-blue-600 hover:text-blue-700 font-semibold mr-3">👁️ ดู</button>
                                <button @click.stop="confirmDelete()"
                                    class="text-red-600 hover:text-red-700 font-semibold">🗑️ ลบ</button>
                            </td>
                        </tr>
                    </template>
                    <template x-if="rows.length===0">
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-slate-500">
                                <p class="text-lg">ไม่มีข้อมูล</p>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div
            class="p-4 border-t border-slate-200 bg-slate-50 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-sm text-slate-600">
                แสดง <span class="font-bold text-slate-900" x-text="from"></span> ถึง <span
                    class="font-bold text-slate-900" x-text="to"></span>
                จากทั้งหมด <span class="font-bold text-slate-900" x-text="total"></span> รายการ
            </div>

            <div class="flex items-center gap-2">
                <button @click="changePage(page - 1)" :disabled="page <= 1"
                    class="px-3 py-2 rounded-lg border border-slate-300 bg-white text-slate-600 hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed transition">
                    &larr; ก่อนหน้า
                </button>

                <div class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 font-medium">
                    หน้า <span x-text="page"></span> / <span x-text="last_page"></span>
                </div>

                <button @click="changePage(page + 1)" :disabled="page >= last_page"
                    class="px-3 py-2 rounded-lg border border-slate-300 bg-white text-slate-600 hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed transition">
                    ถัดไป &rarr;
                </button>
            </div>
        </div>
    </div>

    <div x-show="showModal" class="fixed inset-0 bg-black/40 flex items-center justify-center p-4 z-50"
        style="display:none;" x-cloak>
        <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl" @click.away="showModal=false">
            <div class="flex items-center justify-between p-4 border-b">
                <h3 class="text-lg font-bold">รายละเอียดการฝาก</h3>
                <button class="text-slate-500 hover:text-slate-700" @click="showModal=false">✖</button>
            </div>

            <template x-if="detail && detail.transaction">
                <div class="p-4 space-y-3">
                    <div class="text-sm text-slate-600">
                        วันที่: <span class="font-semibold text-slate-900"
                            x-text="new Date(detail.transaction.created_at).toLocaleDateString('th-TH')"></span>
                    </div>
                    <div class="text-sm text-slate-600">
                        ผู้ฝาก: <span class="font-semibold text-slate-900"
                            x-text="detail.transaction.member_name"></span>
                    </div>
                    <div class="text-sm text-slate-600">
                        สรุป: <span class="font-semibold text-slate-900"
                            x-text="Number(detail.transaction.waste_transaction_total_weight||0).toFixed(2)+' กก. / '+Number(detail.transaction.waste_transaction_total_point||0).toFixed(0)+' คะแนน'"></span>
                    </div>

                    <div class="overflow-x-auto mt-4 border rounded-lg">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-100 text-slate-700">
                                <tr>
                                    <th class="px-3 py-2 text-left">หมวด/ชนิด</th>
                                    <th class="px-3 py-2 text-right">น้ำหนัก (กก.)</th>
                                    <th class="px-3 py-2 text-right">คะแนน</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <template x-for="it in detail.detail" :key="it.waste_transaction_detail_id">
                                    <tr>
                                        <td class="px-3 py-2"
                                            x-text="`${it.waste_category_name} - ${it.waste_type_name}`">
                                        </td>
                                        <td class="px-3 py-2 text-right"
                                            x-text="Number(it.waste_transaction_detail_weight||0).toFixed(2)"></td>
                                        <td class="px-3 py-2 text-right"
                                            x-text="Number(it.waste_transaction_detail_point||0).toFixed(0)"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </template>

            <template x-if="!detail">
                <div class="p-8 text-center text-slate-500">
                    <span class="inline-block animate-spin mr-2">⏳</span> กำลังโหลดข้อมูล...
                </div>
            </template>

            <div class="p-4 border-t text-right">
                <button class="px-4 py-2 rounded bg-slate-200 hover:bg-slate-300 text-slate-800 font-medium"
                    @click="showModal=false">ปิด</button>
            </div>
        </div>
    </div>
</div>

<script>
    function DepositHistory() {
        return {
            rows: [],
            start: '',
            end: '',
            memberSearch: '',
            showModal: false,
            detail: null,

            // Pagination State
            page: 1,
            limit: 10,
            total: 0,
            last_page: 1,
            from: 0,
            to: 0,

            async init() {
                const t = new Date();
                const m = new Date(t.getTime() - 30 * 24 * 60 * 60 * 1000);
                this.start = m.toISOString().split('T')[0];
                this.end = t.toISOString().split('T')[0];
                await this.apply(1);
            },

            async apply(newPage = null) {
                if (newPage) this.page = newPage;

                const p = [];
                p.push('scope=header');
                p.push(`page=${this.page}`);
                p.push(`limit=${this.limit}`);

                if (this.start) p.push(`start_date=${this.start}`);
                if (this.end) p.push(`end_date=${this.end}`);
                if (this.memberSearch) p.push(`member_search=${encodeURIComponent(this.memberSearch)}`);

                try {
                    const r = await fetch('/api/waste_transactions?' + p.join('&'));
                    const j = await r.json();

                    if (j.success && j.result) {
                        // รองรับ Pagination แบบ Laravel/Standard Structure
                        // ถ้าระบบส่งมาเป็น array ตรงๆ (ยังไม่ได้ทำ pagination ฝั่ง server) โค้ดนี้จะรองรับแบบพื้นฐาน
                        if (Array.isArray(j.result)) {
                            this.rows = j.result;
                            this.total = j.result.length;
                            this.last_page = 1;
                            this.from = 1;
                            this.to = j.result.length;
                        } else {
                            // กรณี Server ส่ง Pagination Object มา (data, total, last_page, etc.)
                            this.rows = j.result.data || [];
                            this.total = j.result.total || 0;
                            this.last_page = j.result.last_page || 1;
                            this.from = j.result.from || 0;
                            this.to = j.result.to || 0;
                        }
                    } else {
                        this.rows = [];
                        this.total = 0;
                    }
                } catch (e) {
                    console.error("Error fetching transactions:", e);
                    this.rows = [];
                }
            },

            changePage(newPage) {
                if (newPage >= 1 && newPage <= this.last_page) {
                    this.apply(newPage);
                }
            },

            clear() {
                this.start = '';
                this.end = '';
                this.memberSearch = '';
                this.apply(1);
            },

            async openDetail(id) {
                this.detail = null;
                this.showModal = true;

                try {
                    const r = await fetch('/api/waste_transactions/' + id);
                    const j = await r.json();
                    if (j.success) {
                        this.detail = j.result;
                    } else {
                        alert('ไม่พบข้อมูล');
                        this.showModal = false;
                    }
                } catch (e) {
                    console.error("Error fetching detail:", e);
                    alert('เกิดข้อผิดพลาดในการโหลดข้อมูล');
                    this.showModal = false;
                }
            },

            confirmDelete() {
                if (confirm('ยืนยันลบรายการนี้หรือไม่?')) {
                    // Call delete API here
                }
            }
        }
    }
</script>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>