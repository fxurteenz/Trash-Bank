<div x-data="manageClearance()" x-init="init()" class="grid grid-cols-1 gap-4">
    <!-- Transaction Summary -->
    <div class="bg-white rounded-lg shadow p-6 relative">
        <div class="mb-6 border-b pb-4 flex justify-between items-start">
            <div>
                <h2 class="text-xl font-bold text-gray-800">เคลียร์ยอดฝากขยะ</h2>
                <h2 class="text-sm font-light text-gray-500">รายละเอียดรายการ #<span x-text="wcid"></span></h2>
            </div>
            <a href="/admin/transactions/clear_waste"
                class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6" />
                </svg>
                ย้อนกลับ
            </a>
        </div>

        <template x-if="loading">
            <div class="text-center py-8 text-gray-500">กำลังโหลดข้อมูล...</div>
        </template>

        <template x-if="!loading && transaction">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 text-sm">
                <div>
                    <p class="text-gray-500 text-xs mb-1">คณะ/หน่วยงาน</p>
                    <p class="font-semibold text-gray-800" x-text="transaction.faculty_name || '-'"></p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs mb-1">ช่วงเวลา</p>
                    <p class="font-semibold text-gray-800">
                        <span x-text="formatDate(transaction.waste_clearance_period_start)"></span> -
                        <span x-text="formatDate(transaction.waste_clearance_period_end)"></span>
                    </p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs mb-1">ผู้ทำรายการ</p>
                    <p class="font-semibold text-gray-800" x-text="transaction.creator_name || '-'"></p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs mb-1">สถานะ</p>
                    <span class="px-2 py-1 rounded-full text-xs font-medium" :class="{
                            'bg-yellow-100 text-yellow-800': transaction.waste_clearance_status === 'รอการยืนยัน',
                            'bg-green-100 text-green-800': transaction.waste_clearance_status === 'อนุมัติ',
                            'bg-red-100 text-red-800': transaction.waste_clearance_status === 'ยกเลิก'
                        }" x-text="transaction.waste_clearance_status">
                    </span>
                </div>
            </div>
        </template>
    </div>

    <!-- Details Table -->
    <div class="bg-white rounded-lg shadow p-6" x-show="!loading && details.length > 0">
        <h3 class="text-lg font-bold text-gray-800 mb-4">รายการขยะ</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="bg-gray-50 text-gray-700 border-b border-gray-200">
                    <tr>
                        <th class="py-3 px-4 font-medium">หมวดหมู่</th>
                        <th class="py-3 px-4 font-medium">ประเภท</th>
                        <th class="py-3 px-4 font-medium text-right">ราคา/หน่วย</th>
                        <th class="py-3 px-4 font-medium text-right">น้ำหนักในระบบ (กก.)</th>
                        <th class="py-3 px-4 font-medium text-right">น้ำหนักจริง (กก.)</th>
                        <th class="py-3 px-4 font-medium text-center">สถานะ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <template x-for="(item, index) in details" :key="item.clearance_detail_id">
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4" x-text="item.waste_category_name"></td>
                            <td class="py-3 px-4" x-text="item.waste_type_name"></td>
                            <td class="py-3 px-4 text-right" x-text="item.waste_type_price"></td>
                            <td class="py-3 px-4 text-right font-medium text-gray-600"
                                x-text="item.clearance_detail_transaction_weight"></td>
                            <td class="py-3 px-4 text-right">
                                <div x-show="item.clearance_detail_success == 1">
                                    <span x-text="item.clearance_detail_clearance_weight"></span>
                                </div>
                                <div x-show="item.clearance_detail_success == 0">
                                    <button @click="confirmItem(item)"
                                        class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700 shadow transition">ยืนยัน</button>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <template x-if="item.clearance_detail_success == 1">
                                    <span class="text-green-600 flex justify-center"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 6 9 17l-5-5" />
                                        </svg></span>
                                </template>
                                <template x-if="item.clearance_detail_success == 0">
                                    <span class="text-gray-400 text-xs">รอตรวจสอบ</span>
                                </template>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function manageClearance() {
        return {
            wcid: '<?php echo $wcid ?? ""; ?>',
            transaction: null,
            details: [],
            loading: false,

            async init() {
                if (!this.wcid) {
                    // Fallback if PHP variable is missing, try getting from URL
                    const parts = window.location.pathname.split('/');
                    this.wcid = parts[parts.length - 1];
                }
                await this.fetchData();
            },

            async fetchData() {
                this.loading = true;
                try {
                    const res = await fetch(`/api/clearances/${this.wcid}`);
                    const json = await res.json();

                    if (json.success && json.data) {
                        // API returns transaction as an array, take the first one
                        this.transaction = Array.isArray(json.data.transaction) ? json.data.transaction[0] : json.data.transaction;
                        this.details = json.data.detail || [];
                    } else {
                        Swal.fire('Error', json.message || 'ไม่สามารถโหลดข้อมูลได้', 'error');
                    }
                } catch (err) {
                    console.error(err);
                    Swal.fire('Error', 'เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
                } finally {
                    this.loading = false;
                }
            },

            formatDate(d) {
                if (!d) return '-';
                return new Date(d).toLocaleDateString('th-TH', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
            },

            async confirmItem(item) {
                const { value: weight } = await Swal.fire({
                    title: 'ยืนยันน้ำหนักจริง',
                    html: `รายการ: <b>${item.waste_type_name}</b><br>น้ำหนักในระบบ: ${item.clearance_detail_transaction_weight} กก.`,
                    input: 'number',
                    inputValue: item.clearance_detail_transaction_weight,
                    inputLabel: 'ระบุน้ำหนักที่ชั่งได้จริง (กก.)',
                    inputAttributes: {
                        step: '0.01',
                        min: '0.01'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'บันทึกและยืนยัน',
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonColor: '#2563eb',
                    inputValidator: (value) => {
                        if (!value || value <= 0) {
                            return 'กรุณาระบุน้ำหนักที่ถูกต้อง'
                        }
                    }
                });

                if (weight) {
                    this.loading = true;
                    try {
                        const res = await fetch(`/api/clearances/confirm/${item.clearance_detail_id}`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ weight: weight })
                        });
                        const data = await res.json();
                        if (data.success) {
                            Swal.fire('สำเร็จ', 'บันทึกข้อมูลเรียบร้อยแล้ว', 'success');
                            await this.fetchData(); // Reload data to update status
                        } else {
                            throw new Error(data.message);
                        }
                    } catch (err) {
                        console.error(data.message);
                        Swal.fire('ข้อผิดพลาด', err.message || 'ไม่สามารถบันทึกข้อมูลได้', 'error');
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    }
</script>