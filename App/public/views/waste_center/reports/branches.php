<div x-data="BranchReport()" x-init="initData()" class="max-w-7xl mx-auto bg-white p-8">
    <div class="header-container flex flex-col items-center justify-center mb-6 relative">
        <h2 class="text-2xl font-bold mb-2">รายงานรายชื่อหน่วยบริการ</h2>

        <div class="absolute right-0 top-0 flex flex-col items-end gap-2">
            <button @click="window.print()"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded print:hidden">
                พิมพ์รายงาน
            </button>
            <p class="text-sm text-gray-600" x-text="`ข้อมูล ณ วันที่: ${new Date().toLocaleDateString('th-TH')}`"></p>
        </div>
    </div>

    <div class="mb-4">
        <p class="text-sm font-semibold text-gray-700" x-text="`จำนวนหน่วยบริการรวม: ${branches.length} แห่ง`"></p>
    </div>

    <!-- Table -->
    <table class="min-w-full bg-white border border-gray-200">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 border text-center text-xs font-semibold text-gray-600 uppercase w-16">ลำดับ</th>
                <th class="px-4 py-2 border text-left text-xs font-semibold text-gray-600 uppercase w-32">รหัส</th>
                <th class="px-4 py-2 border text-left text-xs font-semibold text-gray-600 uppercase">ชื่อหน่วยบริการ
                </th>
                <th class="px-4 py-2 border text-right text-xs font-semibold text-gray-600 uppercase">แต้มคงเหลือ</th>
                <th class="px-4 py-2 border text-center text-xs font-semibold text-gray-600 uppercase">จำนวนเจ้าหน้าที่
                </th>
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
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500 border">ไม่พบข้อมูล</td>
                </tr>
            </template>
        </tbody>
        <tfoot class="bg-gray-50" x-show="branches.length > 0">
            <tr>
                <td colspan="3" class="px-4 py-2 border text-right text-sm font-bold text-gray-700">รวมทั้งหมด</td>
                <td class="px-4 py-2 border text-right text-sm font-bold text-gray-700"
                    x-text="Number(totalPoints).toLocaleString()"></td>
                <td class="px-4 py-2 border text-center text-sm font-bold text-gray-700"
                    x-text="Number(totalMembers).toLocaleString()"></td>
            </tr>
        </tfoot>
    </table>
</div>

<script>
    function BranchReport() {
        return {
            branches: [],

            get totalPoints() { return this.branches.reduce((sum, b) => sum + (parseInt(b.faculty_point) || 0), 0); },
            get totalMembers() { return this.branches.reduce((sum, b) => sum + (parseInt(b.total_member) || 0), 0); },

            async initData() {
                await this.fetchData();
                setTimeout(() => { window.print(); }, 500);
            },

            async fetchData() {
                try {
                    const res = await fetch(`/api/faculties?only_branch=true`);
                    const result = await res.json();

                    if (result.success || result.data || result.result) {
                        this.branches = result.data || result.result || [];
                    }
                } catch (err) {
                    console.error("Failed to load branches for report:", err);
                    alert("ไม่สามารถโหลดข้อมูลรายงานได้");
                }
            }
        };
    }
</script>