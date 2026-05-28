<?php
$faculty_id = (int) $fid ?? "null";
?>

<div x-data="FacultyDetailReport()" x-init="initData()" class="max-w-7xl mx-auto bg-white p-8">
    <div class="absolute right-0 top-0 flex flex-col items-end gap-2">
        <p class="text-sm text-gray-600" x-text="`ข้อมูล ณ วันที่: ${new Date().toLocaleDateString('th-TH')}`"></p>
    </div>
    <div class="header-container flex flex-col items-center justify-center mb-6 relative">
        <h2 class="text-2xl font-bold mb-2 mt-12 text-center"
            x-text="`รายงานรายละเอียดคณะ: ${faculty.faculty_name || ''}`"></h2>

        <div class="absolute right-0 top-0 flex flex-col items-end gap-2">
            <button @click="window.print()"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded print:hidden">
                พิมพ์รายงาน
            </button>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-6">
        <div>
            <p class="text-sm font-semibold text-gray-700">รหัสย่อ: <span class="font-normal"
                    x-text="faculty.faculty_code || '-'"></span></p>
            <p class="text-sm font-semibold text-gray-700">แต้มคงเหลือ: <span class="font-normal"
                    x-text="Number(faculty.faculty_point || 0).toLocaleString()"></span></p>
        </div>
        <div class="text-right">
            <p class="text-sm font-semibold text-gray-700">จำนวนสาขา: <span class="font-normal"
                    x-text="Number(faculty.major_count_total || 0).toLocaleString()"></span></p>
            <p class="text-sm font-semibold text-gray-700">จำนวนสมาชิกรวม: <span class="font-normal"
                    x-text="Number(faculty.total_member || 0).toLocaleString()"></span></p>
        </div>
    </div>

    <!-- รายการสาขา -->
    <h3 class="text-lg font-bold mb-2">รายชื่อสาขา</h3>
    <table class="min-w-full bg-white border border-gray-200 mb-6">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 border text-center text-xs font-semibold text-gray-600 uppercase w-16">ลำดับ</th>
                <th class="px-4 py-2 border text-left text-xs font-semibold text-gray-600 uppercase">ชื่อสาขา (TH)</th>
                <th class="px-4 py-2 border text-left text-xs font-semibold text-gray-600 uppercase">ชื่อสาขา (EN)</th>
                <th class="px-4 py-2 border text-center text-xs font-semibold text-gray-600 uppercase w-32">รหัสย่อ</th>
            </tr>
        </thead>
        <tbody>
            <template x-for="(major, index) in majors" :key="major.major_id">
                <tr>
                    <td class="px-4 py-2 border text-center text-xs" x-text="index + 1"></td>
                    <td class="px-4 py-2 border text-xs" x-text="major.major_name || '-'"></td>
                    <td class="px-4 py-2 border text-xs" x-text="major.major_name_en || '-'"></td>
                    <td class="px-4 py-2 border text-center text-xs" x-text="major.major_code || '-'"></td>
                </tr>
            </template>
            <template x-if="majors.length === 0">
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-gray-500 border">ไม่มีข้อมูลสาขา</td>
                </tr>
            </template>
        </tbody>
    </table>

    <!-- รายการสมาชิก -->
    <h3 class="text-lg font-bold mb-2">รายชื่อสมาชิก</h3>
    <table class="min-w-full bg-white border border-gray-200 mb-6">
        <thead class="bg-gray-100">
            <tr>
               <th class="px-4 py-2 border text-center text-xs font-semibold text-gray-600 uppercase w-16">ลำดับ</th>
                <th class="px-4 py-2 border text-left text-xs font-semibold text-gray-600 uppercase">ชื่อสมาชิก</th>
                <th class="px-4 py-2 border text-left text-xs font-semibold text-gray-600 uppercase">บทบาท</th>
                <th class="px-4 py-2 border text-left text-xs font-semibold text-gray-600 uppercase">สาขา</th>
                <th class="px-4 py-2 border text-right text-xs font-semibold text-gray-600 uppercase">แต้มขยะ</th>
                <th class="px-4 py-2 border text-right text-xs font-semibold text-gray-600 uppercase">แต้มความดี</th>
            </tr>
        </thead>
        <tbody>
            <template x-for="(member, index) in members" :key="member.member_id">
                <tr>
                    <td class="px-4 py-2 border text-center text-xs" x-text="index + 1"></td>
                    <td class="px-4 py-2 border text-xs" x-text="member.member_name || member.member_phone || '-'"></td>
                    <td class="px-4 py-2 border text-xs" x-text="member.role_name_th || '-'"></td>
                    <td class="px-4 py-2 border text-xs" x-text="member.major_name || '-'"></td>
                    <td class="px-4 py-2 border text-right text-xs"
                        x-text="Number(parseInt(member.member_waste_point) || 0).toLocaleString()"></td>
                    <td class="px-4 py-2 border text-right text-xs"
                        x-text="Number(parseInt(member.member_goodness_point) || 0).toLocaleString()"></td>
                </tr>
            </template>
            <template x-if="members.length === 0">
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500 border">ไม่มีข้อมูลสมาชิก</td>
                </tr>
            </template>
        </tbody>
    </table>

    <h3 class="text-lg font-bold mb-2">รายการขยะในคลัง</h3>
    <table class="min-w-full bg-white border border-gray-200">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 border text-center text-xs font-semibold text-gray-600 uppercase w-16">ลำดับ</th>
                <th class="px-4 py-2 border text-left text-xs font-semibold text-gray-600 uppercase">หมวดหมู่่</th>
                <th class="px-4 py-2 border text-left text-xs font-semibold text-gray-600 uppercase">ประเภท</th>
                <th class="px-4 py-2 border text-left text-xs font-semibold text-gray-600 uppercase text-right">น้ำหนัก (กก.)</th>
        </thead>
        <tbody>
            <template x-for="(stock, index) in stocks" :key="stock.waste_type_id">
                <tr>
                    <td class="px-4 py-2 border text-center text-xs" x-text="index + 1"></td>
                    <td class="px-4 py-2 border text-xs" x-text="stock.waste_category_name"></td>
                    <td class="px-4 py-2 border text-xs" x-text="stock.waste_type_name"></td>
                    <td class="px-4 py-2 border text-xs text-right" x-text="stock.stock_weight"></td>
                </tr>
            </template>
            <template x-if="stocks.length === 0">
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500 border">ไม่มีข้อมูลขยะในคลัง</td>
                </tr>
            </template>
        </tbody>
    </table>
</div>

<script>
    function FacultyDetailReport() {
        return {
            facultyId: <?= $faculty_id; ?>,
            faculty: {},
            majors: [],
            members: [],
            stocks: [],

            async initData() {
                if (!this.facultyId) {
                    alert('ไม่พบข้อมูลคณะ');
                    return;
                }
                await Promise.all([
                    this.fetchFaculty(),
                    this.fetchMembers(),
                    this.fetchMajors()
                ]);
                setTimeout(() => { window.print(); }, 500);
            },

            async fetchFaculty() {
                try {
                    const res = await fetch(`/api/dashboards/faculty/${this.facultyId}`);
                    const result = await res.json();
                    if (result.success) {
                        this.faculty = result.data.faculty;
                        this.stocks = result.data.stocks ||[];
                    }
                } catch (e) {
                    console.error('Failed to fetch faculty:', e);
                }
            },

            async fetchMembers() {
                try {
                    const res = await fetch(`/api/members?faculty=${this.facultyId}&limit=10000`);
                    const result = await res.json();
                    if (result.success || result.data) {
                        this.members = result.data || result.result?.data || [];
                    }
                } catch (e) {
                    console.error('Failed to fetch members:', e);
                }
            },
            async fetchMajors() {
                try {
                    const res = await fetch(`/api/majors?faculty=${this.facultyId}`);
                    const result = await res.json();
                    if (result.success || result.data) {
                        this.majors = result.data || result.result?.data || [];
                    }
                } catch (e) {
                    console.error('Failed to fetch members:', e);
                }
            }
        };
    }
</script>