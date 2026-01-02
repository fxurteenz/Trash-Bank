<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">ยืนยันขยะ (ศูนย์กลาง)</h2>
    </div>

    <div x-data="centerConfirmForm()" x-init="init()" class="space-y-4">
        <form @submit.prevent="confirmSubmit" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">คณะ</label>
                <select x-model="form.faculty_id" required
                    class="w-full text-sm border border-gray-300 rounded px-2 py-1.5 bg-white">
                    <option value="">-- เลือกคณะ --</option>
                    <template x-for="f in faculties" :key="f.faculty_id">
                        <option :value="f.faculty_id" x-text="f.faculty_name"></option>
                    </template>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">เริ่ม (จาก)</label>
                <input type="date" x-model="form.start_date" required
                    class="w-full text-sm border border-gray-300 bg-white rounded px-2 py-1.5">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">สิ้นสุด (ถึง)</label>
                <input type="date" x-model="form.end_date" required
                    class="w-full text-sm border border-gray-300 bg-white rounded px-2 py-1.5">
            </div>

            <div class="md:col-span-3 flex justify-end">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition">ยืนยัน</button>
            </div>
        </form>

        <div x-show="submitting" class="text-sm text-blue-600">กำลังส่งคำขอยืนยัน...</div>
    </div>
</div>

<script>
    function centerConfirmForm() {
        return {
            faculties: [],
            submitting: false,
            form: {
                faculty_id: '',
                start_date: '',
                end_date: ''
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
                if (!this.form.faculty_id || !this.form.start_date || !this.form.end_date) {
                    Swal.fire('แจ้งเตือน', 'กรุณากรอกข้อมูลให้ครบถ้วน', 'warning');
                    return;
                }

                Swal.fire({
                    title: 'ยืนยันการยืนยันรายการขยะ?',
                    html: `<div class="text-left text-sm">
                        <p><b>คณะ:</b> ${this.faculties.find(f=>f.faculty_id==this.form.faculty_id)?.faculty_name || '-'} </p>
                        <p><b>ช่วงเวลา:</b> ${this.form.start_date} — ${this.form.end_date}</p>
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
                    const res = await fetch('/api/waste_transactions/confirm', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(this.form) 
                    });
                    const data = await res.json();
                    if (res.ok) {
                        Swal.fire('สำเร็จ', data.message || 'ดำเนินการเรียบร้อย', 'success');
                        // optionally reset
                        this.form.faculty_id = '';
                        this.form.start_date = '';
                        this.form.end_date = '';
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
</script>
