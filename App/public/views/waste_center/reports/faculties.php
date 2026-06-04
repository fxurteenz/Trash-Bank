<div x-data="FacultyReport()" x-init="initData()" class="max-w-7xl mx-auto bg-white p-8">
    <div class="header-container flex flex-col items-center justify-center mb-6 relative">
        <h2 class="text-2xl font-bold mb-2 mt-2">รายงานรายชื่อคณะ</h2>

        <div class="absolute right-0 top-0 flex flex-col items-end gap-2">
            <button @click="window.print()"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded print:hidden">
                พิมพ์รายงาน
            </button>
            <p class="text-sm text-gray-600" x-text="`ข้อมูล ณ วันที่: ${new Date().toLocaleDateString('th-TH')}`"></p>
        </div>
    </div>

    <div class="mb-4">
        <p class="text-sm font-semibold text-gray-700" x-text="`จำนวนคณะรวม: ${faculties.length} แห่ง`"></p>
    </div>

    <!-- Table -->
    <table class="min-w-full bg-white border border-gray-200">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 border text-center text-xs font-semibold text-gray-600 uppercase w-16">ลำดับ</th>
                <th class="px-4 py-2 border text-left text-xs font-semibold text-gray-600 uppercase w-32">รหัส</th>
                <th class="px-4 py-2 border text-left text-xs font-semibold text-gray-600 uppercase">ชื่อคณะ
                </th>
                <th class="px-4 py-2 border text-right text-xs font-semibold text-gray-600 uppercase">แต้มคงเหลือ</th>
                <th class="px-4 py-2 border text-center text-xs font-semibold text-gray-600 uppercase">จำนวนสาขา</th>
                <th class="px-4 py-2 border text-center text-xs font-semibold text-gray-600 uppercase">จำนวนสมาชิก</th>
            </tr>
        </thead>
        <tbody>
            <template x-for="(faculty, index) in faculties" :key="faculty.faculty_id">
                <tr>
                    <td class="px-4 py-2 border text-center text-xs" x-text="index + 1"></td>
                    <td class="px-4 py-2 border text-xs" x-text="faculty.faculty_code || '-'"></td>
                    <td class="px-4 py-2 border text-xs" x-text="faculty.faculty_name || '-'"></td>
                    <td class="px-4 py-2 border text-right text-xs"
                        x-text="Number(parseInt(faculty.faculty_point) || 0).toLocaleString()"></td>
                    <td class="px-4 py-2 border text-center text-xs"
                        x-text="Number(parseInt(faculty.major_count_total) || 0).toLocaleString()"></td>
                    <td class="px-4 py-2 border text-center text-xs"
                        x-text="Number(parseInt(faculty.total_member) || 0).toLocaleString()"></td>
                </tr>
            </template>
            <template x-if="faculties.length === 0">
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500 border">ไม่พบข้อมูล</td>
                </tr>
            </template>
        </tbody>
        <tfoot class="bg-gray-50" x-show="faculties.length > 0">
            <tr>
                <td colspan="3" class="px-4 py-2 border text-right text-sm font-bold text-gray-700">รวมทั้งหมด</td>
                <td class="px-4 py-2 border text-right text-sm font-bold text-gray-700"
                    x-text="Number(totalPoints).toLocaleString()"></td>
                <td class="px-4 py-2 border text-center text-sm font-bold text-gray-700"
                    x-text="Number(totalMajors).toLocaleString()"></td>
                <td class="px-4 py-2 border text-center text-sm font-bold text-gray-700"
                    x-text="Number(totalMembers).toLocaleString()"></td>
            </tr>
        </tfoot>
    </table>
</div>

<script>
    function FacultyReport() {
        return {
            faculties: [],

            get totalPoints() { return this.faculties.reduce((sum, f) => sum + (parseInt(f.faculty_point) || 0), 0); },
            get totalMajors() { return this.faculties.reduce((sum, f) => sum + (parseInt(f.major_count_total) || 0), 0); },
            get totalMembers() { return this.faculties.reduce((sum, f) => sum + (parseInt(f.total_member) || 0), 0); },

            async initData() {
                await this.fetchData();
                setTimeout(() => { window.print(); }, 500);
            },

            async fetchData() {
                try {
                    const res = await fetch(`/api/faculties`);
                    const result = await res.json();

                    if (result.success || result.data || result.result) {
                        this.faculties = result.data || result.result || [];
                    }
                } catch (err) {
                    console.error("Failed to load faculties for report:", err);
                    alert("ไม่สามารถโหลดข้อมูลรายงานได้");
                }
            }
        };
    }
</script>