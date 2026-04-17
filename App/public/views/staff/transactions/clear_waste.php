<div class="grid grid-cols-1 gap-4">
    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="mb-4">
            <h2 class="text-xl font-bold">เคลียร์ยอดฝากขยะ</h2>
            <h2 class="text-sm font-light text-gray-500">เปิดรายการ</h2>
        </div>

        <div x-data="centerConfirmForm()" class="space-y-4">
            <form @submit.prevent="confirmSubmit" class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">จากวันที่</label>
                    <input type="date" x-model="form.waste_clearance_period_start" required
                        class="w-full text-sm border border-gray-300 bg-white rounded px-2 py-1.5">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">ถึงวันที่</label>
                    <input type="date" x-model="form.waste_clearance_period_end" required
                        class="w-full text-sm border border-gray-300 bg-white rounded px-2 py-1.5">
                </div>

                <div class="md:col-span-3 flex justify-end">
                    <button type="submit"
                        class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2 px-4 rounded-full shadow transition">ยืนยัน</button>
                </div>
            </form>

            <div x-show="submitting" class="text-sm text-emerald-600">กำลังส่งคำขอยืนยัน...</div>
        </div>

    </div>

    <div x-data="clearanceHistory()" x-init="init()" @clearance-updated.window="fetchClearances()"
        class="bg-white shadow-md rounded-lg p-6 space-y-4">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold">ประวัติการเคลียร์ยอด</h2>
                <h2 class="text-sm font-light text-gray-500">ตรวจสอบและยืนยันรายการ</h2>
            </div>
            <button @click="fetchClearances()" class="text-sm text-emerald-600 hover:underline">รีเฟรช</button>
        </div>
        <!-- Filters -->
        <div class="bg-gray-50 p-4 rounded border border-gray-100">
            <div class="w-full flex flex-row-reverse gap-4 items-end">
                <button @click="resetFilters()"
                    class="text-sm text-gray-500 hover:text-gray-700 hover:underline">ล้างตัวกรอง</button>

            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">จากวันที่</label>
                    <input type="date" x-model="filters.start_date" @change="fetchClearances()"
                        class="w-full text-sm border border-gray-300 bg-white rounded px-2 py-1.5">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">ถึงวันที่</label>
                    <input type="date" x-model="filters.end_date" @change="fetchClearances()"
                        class="w-full text-sm border border-gray-300 bg-white rounded px-2 py-1.5">
                </div>
            </div>
        </div>
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-gray-700 border-b border-sky-300 bg-gradient-to-b from-sky-50 to-sky-100">
                    <tr>
                        <th class="py-2 px-4">วันที่ทำรายการ</th>
                        <th class="py-2 px-4">คณะ</th>
                        <th class="py-2 px-4">ช่วงเวลา</th>
                        <th class="py-2 px-4 text-center">สถานะ</th>
                        <th class="py-2 px-4 text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <template x-if="loading">
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-500">
                                <div class="flex justify-center items-center">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900"></div>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-if="!loading && items.length === 0">
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-500">ไม่พบข้อมูล</td>
                        </tr>
                    </template>
                    <template x-for="item in items" :key="item.waste_clearance_id">
                        <tr class="hover:bg-gray-50 hover:cursor-pointer">
                            <td class="py-3 px-4" x-text="formatDate(item.created_at)" @click="openDetail(item)"></td>
                            <td class="py-3 px-4" x-text="item.faculty_name || item.faculty_id || '-'"
                                @click="openDetail(item)"></td>
                            <td class="py-3 px-4" @click="openDetail(item)">
                                <span x-text="formatDate(item.waste_clearance_period_start)"></span> -
                                <span x-text="formatDate(item.waste_clearance_period_end)"></span>
                            </td>
                            <td class="py-3 px-4 text-center" @click="openDetail(item)">
                                <span x-show="item.waste_clearance_status === 'รอการยืนยัน'"
                                    class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">รอการยืนยัน</span>
                                <span x-show="item.waste_clearance_status === 'อนุมัติ'"
                                    class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">อนุมัติ</span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <button x-show="item.waste_clearance_status === 'รอการยืนยัน'"
                                    class="text-sky-500 hover:text-sky-700 hover:cursor-pointer"
                                    @click="openManagePage(item.waste_clearance_id)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                        <path fill="currentColor"
                                            d="M14.5 11c.28 0 .5.22.5.5V13H9v-1.5c0-.28.22-.5.5-.5zm5.5 2.55V10h-2v3.06c.69.08 1.36.25 2 .49M21 9H3V3h18zm-2-4H5v2h14zM8.85 19H6v-9H4v11h5.78c-.24-.39-.46-.81-.64-1.25zM17 18c-.56 0-1 .44-1 1s.44 1 1 1s1-.44 1-1s-.44-1-1-1m6 1c-.94 2.34-3.27 4-6 4s-5.06-1.66-6-4c.94-2.34 3.27-4 6-4s5.06 1.66 6 4m-3.5 0a2.5 2.5 0 0 0-5 0a2.5 2.5 0 0 0 5 0" />
                                    </svg>
                                </button>
                                <span x-show="item.waste_clearance_status === 'ยืนยันแล้ว'"
                                    class="text-green-600 flex justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M20 6 9 17l-5-5" />
                                    </svg>
                                </span>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Detail Dialog -->
        <dialog x-show="detailDialogShow" x-ref="detailDialog" @click.self="detailDialogShow = false"
            @close="detailDialogShow = false"
            class="fixed inset-0 mx-auto my-auto p-0 bg-transparent z-50 backdrop:bg-gray-500/50"
            x-init="$watch('detailDialogShow', value => {if (value) $refs.detailDialog.showModal();else $refs.detailDialog.close();})">
            <div class="bg-white p-6 rounded-lg shadow-xl max-w-4xl max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">รายละเอียดการเคลียร์ยอด</h3>
                        <template x-if="detailItem">
                            <p class="text-sm text-gray-600">
                                รอบ: <span x-text="formatDate(detailItem.waste_clearance_period_start)"></span> - <span
                                    x-text="formatDate(detailItem.waste_clearance_period_end)"></span>
                            </p>
                        </template>
                    </div>
                    <button @click="detailDialogShow = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                <div class="space-y-4" x-show="detailItem">
                    <div
                        class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm bg-gray-50 p-3 rounded border border-gray-100">
                        <div>
                            <span class="block text-gray-500 text-xs">วันที่ทำรายการ</span>
                            <span class="font-medium" x-text="formatDate(detailItem.created_at)"></span>
                        </div>
                        <div>
                            <span class="block text-gray-500 text-xs">คณะ</span>
                            <span class="font-medium" x-text="detailItem.faculty_name || '-'"></span>
                        </div>
                        <div>
                            <span class="block text-gray-500 text-xs">สถานะ</span>
                            <span class="font-medium" x-text="detailItem.waste_clearance_status"></span>
                        </div>
                    </div>

                    <div class="overflow-x-auto border rounded-lg">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-100 text-gray-700">
                                <tr>
                                    <th class="py-2 px-3">หมวดหมู่</th>
                                    <th class="py-2 px-3">ประเภท</th>
                                    <th class="py-2 px-3 text-right">น้ำหนัก (ระบบ)</th>
                                    <th class="py-2 px-3 text-right">น้ำหนัก (จริง)</th>
                                    <th class="py-2 px-3 text-center">สถานะ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <template x-if="detailLoading">
                                    <tr>
                                        <td colspan="5" class="text-center py-4">กำลังโหลด...</td>
                                    </tr>
                                </template>
                                <template x-if="!detailLoading && detailList.length === 0">
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-gray-500">ไม่มีรายการ</td>
                                    </tr>
                                </template>
                                <template x-for="d in detailList" :key="d.clearance_detail_id">
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-2 px-3" x-text="d.waste_category_name"></td>
                                        <td class="py-2 px-3" x-text="d.waste_type_name"></td>
                                        <td class="py-2 px-3 text-right" x-text="d.clearance_detail_transaction_weight">
                                        </td>
                                        <td class="py-2 px-3 text-right"
                                            x-text="d.clearance_detail_clearance_weight || '-'"></td>
                                        <td class="py-2 px-3 text-center"
                                            x-text="d.clearance_detail_success == 1 ? 'สำเร็จ' : 'รอ/ไม่สำเร็จ'"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </dialog>

    </div>
</div>

<script>
    function centerConfirmForm() {
        return {
            submitting: false,
            form: {
                faculty_id: <?php echo json_encode($user->faculty_id ?? null); ?>,
                waste_clearance_period_start: '',
                waste_clearance_period_end: ''
            },

            confirmSubmit() {
                if (!this.form.waste_clearance_period_start || !this.form.waste_clearance_period_end) {
                    Swal.fire('แจ้งเตือน', 'กรุณากรอกข้อมูลให้ครบถ้วน', 'warning');
                    return;
                }

                Swal.fire({
                    title: 'ยืนยันการทำรายการนี้ ?',
                    html: `<div class="text-left text-sm">
                        <p><b>คณะ:</b> ${this.faculties.find(f => f.faculty_id == this.form.faculty_id)?.faculty_name || '-'} </p>
                        <p><b>ช่วงเวลา:</b> ${this.form.waste_clearance_period_start} — ${this.form.waste_clearance_period_end}</p>
                    </div>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'ใช่, ยืนยัน',
                    cancelButtonText: 'ยกเลิก'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        await this.submit();
                    }
                });
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
                        Swal.fire('สำเร็จ', data.message || 'ดำเนินการเรียบร้อย', 'success');
                        // optionally reset
                        this.form.waste_clearance_period_start = '';
                        this.form.waste_clearance_period_end = '';
                        window.dispatchEvent(new CustomEvent('clearance-updated'));
                    } else {
                        throw new Error(data.message || 'เกิดข้อผิดพลาด');
                    }
                } catch (err) {
                    console.error(err);
                    Swal.fire('เกิดข้อผิดพลาด', err.message || 'ไม่สามารถส่งคำขอได้', 'error');
                } finally {
                    this.submitting = false;
                }
            }
        }
    }

    function clearanceHistory() {
        return {
            facultyId: <?php echo json_encode($user->faculty_id ?? null); ?>,
            items: [],
            filters: {
                start_date: '',
                end_date: ''
            },
            detailDialogShow: false,
            detailItem: [],
            detailList: [],
            detailLoading: false,
            loading: false,
            async init() {
                try {
                    await this.fetchClearances();
                } catch (error) {
                    console.error(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด!',
                        text: 'ไม่สามารถโหลดข้อมูลได้',
                    });
                }
            },
            async fetchClearances() {
                this.loading = true;
                try {
                    const params = new URLSearchParams();
                    params.append('faculty', this.facultyId);
                    if (this.filters.start_date) params.append('start_date', this.filters.start_date);
                    if (this.filters.end_date) params.append('end_date', this.filters.end_date);

                    const res = await fetch(`/api/clearances?${params.toString()}`);
                    const data = await res.json();
                    console.log(data);
                    
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
                window.location.href = `/staff/transactions/clear_waste/manage/${wcid}`;
            },
            resetFilters() {
                this.filters = {
                    start_date: '',
                    end_date: ''
                };
                this.fetchClearances();
            },
            formatDate(d) {
                if (!d) return '-';
                return new Date(d).toLocaleDateString('th-TH', { day: 'numeric', month: 'short', year: '2-digit' });
            }
        }
    }
</script>