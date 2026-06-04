<div x-data="CombinedReport()" x-init="initData()" class="max-w-7xl mx-auto bg-white p-8">
    <div class="absolute right-0 top-0 flex flex-col items-end gap-2">
        <p class="text-sm text-gray-600" x-text="`ข้อมูล ณ วันที่: ${new Date().toLocaleDateString('th-TH')}`"></p>
    </div>
    <div class="header-container flex flex-col items-center justify-center mb-6 relative">
        <h2 class="text-2xl font-bold mb-2 mt-8">รายงานข้อมูลคณะและหน่วยบริการทั้งหมด</h2>
        <div class="absolute right-0 top-0 flex flex-col items-end gap-2">
            <button @click="window.print()"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded print:hidden">
                พิมพ์รายงาน
            </button>
        </div>
    </div>

    <!-- Faculties Section -->
    <div class="mb-8">
        <div class="mb-2 flex justify-between items-end">
            <h3 class="text-lg font-bold text-gray-800">1. รายชื่อคณะ/หน่วยงาน</h3>
            <p class="text-sm font-semibold text-gray-700" x-text="`จำนวนรวม: ${faculties.length} แห่ง`"></p>
        </div>
        <table class="min-w-full bg-white border border-gray-200 mb-4">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border text-center text-xs font-semibold text-gray-600 uppercase w-16">ลำดับ
                    </th>
                    <th class="px-4 py-2 border text-left text-xs font-semibold text-gray-600 uppercase w-32">รหัส</th>
                    <th class="px-4 py-2 border text-left text-xs font-semibold text-gray-600 uppercase">
                        ชื่อคณะ/หน่วยงาน</th>
                    <th class="px-4 py-2 border text-right text-xs font-semibold text-gray-600 uppercase">แต้มคงเหลือ
                    </th>
                    <th class="px-4 py-2 border text-center text-xs font-semibold text-gray-600 uppercase">จำนวนสาขา
                    </th>
                    <th class="px-4 py-2 border text-center text-xs font-semibold text-gray-600 uppercase">จำนวนสมาชิก
                    </th>
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
                        <td colspan="6" class="px-4 py-4 text-center text-gray-500 border">ไม่พบข้อมูล</td>
                    </tr>
                </template>
            </tbody>
            <tfoot class="bg-gray-50" x-show="faculties.length > 0">
                <tr>
                    <td colspan="3" class="px-4 py-2 border text-right text-sm font-bold text-gray-700">รวมทั้งหมด (คณะ)
                    </td>
                    <td class="px-4 py-2 border text-right text-sm font-bold text-gray-700"
                        x-text="Number(faculties.reduce((sum, f) => sum + (parseInt(f.faculty_point) || 0), 0)).toLocaleString()">
                    </td>
                    <td class="px-4 py-2 border text-center text-sm font-bold text-gray-700"
                        x-text="Number(faculties.reduce((sum, f) => sum + (parseInt(f.major_count_total) || 0), 0)).toLocaleString()">
                    </td>
                    <td class="px-4 py-2 border text-center text-sm font-bold text-gray-700"
                        x-text="Number(faculties.reduce((sum, f) => sum + (parseInt(f.total_member) || 0), 0)).toLocaleString()">
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Branches Section -->
    <div class="mb-4">
        <div class="mb-2 flex justify-between items-end">
            <h3 class="text-lg font-bold text-gray-800">2. รายชื่อหน่วยบริการ</h3>
            <p class="text-sm font-semibold text-gray-700" x-text="`จำนวนรวม: ${branches.length} แห่ง`"></p>
        </div>
        <table class="min-w-full bg-white border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border text-center text-xs font-semibold text-gray-600 uppercase w-16">ลำดับ
                    </th>
                    <th class="px-4 py-2 border text-left text-xs font-semibold text-gray-600 uppercase w-32">รหัส</th>
                    <th class="px-4 py-2 border text-left text-xs font-semibold text-gray-600 uppercase">ชื่อหน่วยบริการ
                    </th>
                    <th class="px-4 py-2 border text-right text-xs font-semibold text-gray-600 uppercase">แต้มคงเหลือ
                    </th>
                    <th class="px-4 py-2 border text-center text-xs font-semibold text-gray-600 uppercase">
                        จำนวนเจ้าหน้าที่</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="(branch, index) in branches" :key="branch.faculty_id">
                    <tr>
                        <td class="px-4 py-2 border text-center text-xs" x-text="index + 1"></td>
                        <td class="px-4 py-2 border text-xs" x-text="branch.faculty_code || '-'"></td>
                        <td class="px-4 py-2 border text-xs" x-text="branch.faculty_name || '-'"></td>
                        <td class="px-4 py-2 border text-right text-xs"
                            x-text="Number(parseInt(branch.faculty_point) || 0).toLocaleString()"></td>
                        <td class="px-4 py-2 border text-center text-xs"
                            x-text="Number(parseInt(branch.total_member) || 0).toLocaleString()"></td>
                    </tr>
                </template>
                <template x-if="branches.length === 0">
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-gray-500 border">ไม่พบข้อมูล</td>
                    </tr>
                </template>
            </tbody>
            <tfoot class="bg-gray-50" x-show="branches.length > 0">
                <tr>
                    <td colspan="3" class="px-4 py-2 border text-right text-sm font-bold text-gray-700">รวมทั้งหมด
                        (หน่วยบริการ)</td>
                    <td class="px-4 py-2 border text-right text-sm font-bold text-gray-700"
                        x-text="Number(branches.reduce((sum, b) => sum + (parseInt(b.faculty_point) || 0), 0)).toLocaleString()">
                    </td>
                    <td class="px-4 py-2 border text-center text-sm font-bold text-gray-700"
                        x-text="Number(branches.reduce((sum, b) => sum + (parseInt(b.total_member) || 0), 0)).toLocaleString()">
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Summary Section -->
    <div class="mt-8 border-t-2 border-gray-300 pt-4 flex justify-end">
        <div class="w-full md:w-1/2">
            <table class="min-w-full">
                <tbody>
                    <tr>
                        <td class="py-2 text-right font-bold text-gray-800 pr-4">รวมแต้มคงเหลือทั้งหมดในระบบ:</td>
                        <td class="py-2 text-right font-bold text-xl text-emerald-600"
                            x-text="Number(totalAllPoints).toLocaleString()"></td>
                    </tr>
                    <tr>
                        <td class="py-2 text-right font-bold text-gray-800 pr-4">รวมจำนวนสมาชิกและบุคลากรทั้งหมด:</td>
                        <td class="py-2 text-right font-bold text-xl text-blue-600"
                            x-text="Number(totalAllMembers).toLocaleString()"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function CombinedReport() {
        return {
            faculties: [],
            branches: [],

            get totalAllPoints() { return this.faculties.reduce((sum, f) => sum + (parseInt(f.faculty_point) || 0), 0) + this.branches.reduce((sum, b) => sum + (parseInt(b.faculty_point) || 0), 0); },
            get totalAllMembers() { return this.faculties.reduce((sum, f) => sum + (parseInt(f.total_member) || 0), 0) + this.branches.reduce((sum, b) => sum + (parseInt(b.total_member) || 0), 0); },

            async initData() {
                await Promise.all([this.fetchFaculties(), this.fetchBranches()]);
                setTimeout(() => { window.print(); }, 800);
            },

            async fetchFaculties() {
                try {
                    const res = await fetch(`/api/faculties?show_branch=true`);
                    const result = await res.json();
                    if (result.success || result.data || result.result) this.faculties = result.data || result.result || [];
                } catch (err) { console.error("Failed to load faculties for report:", err); }
            },

            async fetchBranches() {
                try {
                    const res = await fetch(`/api/faculties?only_branch=true`);
                    const result = await res.json();
                    if (result.success || result.data || result.result) this.branches = result.data || result.result || [];
                } catch (err) { console.error("Failed to load branches for report:", err); }
            }
        };
    }
</script>