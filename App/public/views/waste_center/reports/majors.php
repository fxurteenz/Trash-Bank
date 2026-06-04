<div x-data="FacultyMajorsReport()" x-init="initData()" class="max-w-7xl mx-auto bg-white p-8">
    <div class="absolute right-0 top-0 flex flex-col items-end gap-2">
        <p class="text-sm text-gray-600" x-text="`ข้อมูล ณ วันที่: ${new Date().toLocaleDateString('th-TH')}`"></p>
    </div>
    <div class="header-container flex flex-col items-center justify-center mb-6 relative">
        <h2 x-show="facultyName" class="text-2xl font-bold mb-2 mt-12 text-center"
            x-text="`รายงานสาขาในคณะ ${facultyName}`"></h2>
        <h2 x-show="!facultyName" class="text-2xl font-bold mb-2 mt-12 text-center" x-text="`รายงานสาขา`"></h2>

        <div class="absolute right-0 top-0 flex flex-col items-end gap-2">
            <button @click="window.print()"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded print:hidden">
                พิมพ์รายงาน
            </button>
        </div>
    </div>

    <h3 class="text-lg font-bold mb-2">รายชื่อสาขา</h3>
    <table class="min-w-full bg-white border border-gray-200 mb-6">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 border text-center text-xs font-semibold text-gray-600 uppercase w-16">ลำดับ</th>
                <th class="px-4 py-2 border text-left text-xs font-semibold text-gray-600 uppercase">ชื่อสาขา (TH)</th>
                <th class="px-4 py-2 border text-left text-xs font-semibold text-gray-600 uppercase">ชื่อสาขา (EN)</th>
                <th class="px-4 py-2 border text-center text-xs font-semibold text-gray-600 uppercase w-16">รหัสย่อ</th>
                <th class="px-4 py-2 border text-center text-xs font-semibold text-gray-600 uppercase w-16">
                    สมาชิกในสาขา(คน)</th>
            </tr>
        </thead>
        <tbody>
            <template x-for="(major, index) in majors" :key="major.major_id">
                <tr>
                    <td class="px-4 py-2 border text-center text-xs" x-text="index + 1"></td>
                    <td class="px-4 py-2 border text-xs" x-text="major.major_name || '-'"></td>
                    <td class="px-4 py-2 border text-xs" x-text="major.major_name_en || '-'"></td>
                    <td class="px-4 py-2 border text-center text-xs" x-text="major.major_code || '-'"></td>
                    <td class="px-4 py-2 border text-center text-xs" x-text="major.major_member_total || '0'"></td>
                </tr>
            </template>
            <template x-if="majors.length === 0">
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-gray-500 border">ไม่มีข้อมูลสาขา</td>
                </tr>
            </template>
        </tbody>
    </table>
</div>

<script>
    function FacultyMajorsReport() {
        return {
            facultyId: <?= $faculty_id || 'null' ?>,
            facultyName: '',
            majors: [],

            async initData() {
                try {
                    const urlParams = new URLSearchParams(window.location.search);
                    const faculty = urlParams.get('f');
                    let facultyName = urlParams.get('fname');
                    if (faculty && facultyName) {
                        this.facultyId = faculty;
                        this.facultyName = facultyName;
                        await this.fetchFacultyMajors();
                    } else {
                        await this.fetchAllMajors();
                    }
                    setTimeout(() => { window.print(); }, 500);
                } catch (error) {
                    console.error('Error : ', error)
                }

            },
            async fetchFacultyMajors() {
                try {
                    const res = await fetch(`/api/majors?faculty=${this.facultyId}`);
                    const result = await res.json();
                    if (result.success || result.data) {
                        this.majors = result.data || [];
                    }
                } catch (e) {
                    console.error('Failed to fetch members:', e);
                }
            },
            async fetchAllMajors() {
                try {
                    const res = await fetch(`/api/majors`);
                    const result = await res.json();
                    if (result.success || result.data) {
                        this.majors = result.data || [];
                    }
                } catch (e) {
                    console.error('Failed to fetch members:', e);
                }
            }
        };
    }
</script>