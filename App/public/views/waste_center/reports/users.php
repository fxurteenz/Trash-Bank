<div x-data="UserReport()" x-init="initData()" class="max-w-7xl mx-auto bg-white p-8">
    <div class="header-container flex flex-col items-center justify-center mb-6 relative">
        <h2 class="text-2xl font-bold mb-2">รายงานรายชื่อผู้ใช้งาน</h2>
        <div class="flex flex-wrap justify-center gap-2 mb-2" x-show="Object.keys(activeFilters).length > 0" x-cloak>
            <template x-for="(value, key) in activeFilters" :key="key">
                <span
                    class="inline-block bg-gray-100 px-3 py-1 rounded-full border border-gray-200 text-sm font-medium text-gray-800"
                    x-text="`${key}: ${value}`"></span>
            </template>
        </div>
        <p class="text-sm text-gray-600 mb-4" x-text="`ข้อมูล ณ วันที่: ${new Date().toLocaleDateString('th-TH')}`"></p>

        <button @click="window.print()"
            class="absolute right-0 top-0 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded print:hidden">
            พิมพ์รายงาน
        </button>
    </div>

    <!-- Table -->
    <table class="min-w-full bg-white border border-gray-200">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 border text-center text-xs font-semibold text-gray-600 uppercase">ลำดับ</th>
                <th class="px-4 py-2 border text-left text-xs font-semibold text-gray-600 uppercase">ชื่อ-นามสกุล</th>
                <th class="px-4 py-2 border text-left text-xs font-semibold text-gray-600 uppercase">เบอร์โทรศัพท์</th>
                <th x-show="!activeFilters['บทบาท'] || multipleRoles"
                    class="px-4 py-2 border text-left text-xs font-semibold text-gray-600 uppercase">บทบาท</th>
                <th x-show="!activeFilters['คณะ']"
                    class="px-4 py-2 border text-left text-xs font-semibold text-gray-600 uppercase">คณะ</th>
                <th class="px-4 py-2 border text-left text-xs font-semibold text-gray-600 uppercase">สาขา</th>
                <th class="px-4 py-2 border text-right text-xs font-semibold text-gray-600 uppercase">แต้มขยะ</th>
                <th class="px-4 py-2 border text-right text-xs font-semibold text-gray-600 uppercase">แต้มความดี</th>
            </tr>
        </thead>
        <tbody>
            <template x-for="(member, index) in members" :key="member.member_id">
                <tr>
                    <td class="px-4 py-2 border text-center text-xs" x-text="index + 1"></td>
                    <td class="px-4 py-2 border text-xs" x-text="member.member_name || 'ไม่ระบุ'"></td>
                    <td class="px-4 py-2 border text-xs" x-text="member.member_phone || 'ไม่ระบุ'"></td>
                    <td x-show="!activeFilters['บทบาท'] || multipleRoles" class="px-4 py-2 border text-xs"
                        x-text="member.role_name_th || 'ไม่ระบุ'"></td>
                    <td x-show="!activeFilters['คณะ']" class="px-4 py-2 border text-xs"
                        x-text="member.faculty_name || '-'"></td>
                    <td class="px-4 py-2 border text-xs" x-text="member.major_name || '-'"></td>
                    <td class="px-4 py-2 border text-right text-xs"
                        x-text="Number(parseInt(member.member_waste_point) || 0).toLocaleString()"></td>
                    <td class="px-4 py-2 border text-right text-xs"
                        x-text="Number(parseInt(member.member_goodness_point) || 0).toLocaleString()"></td>
                </tr>
            </template>
            <template x-if="members.length === 0">
                <tr>
                    <td :colspan="8 - (activeFilters['บทบาท'] && !multipleRoles ? 1 : 0) - (activeFilters['คณะ'] ? 1 : 0)"
                        class="px-4 py-8 text-center text-gray-500 border">ไม่พบข้อมูล</td>
                </tr>
            </template>
        </tbody>
    </table>
</div>

<script>
    function UserReport() {
        return {
            members: [],
            multipleRoles: false,
            activeFilters: {},

            async initData() {
                const urlParams = new URLSearchParams(window.location.search);
                await this.parseFilters(urlParams);
                await this.fetchData(urlParams);
                setTimeout(() => {
                    window.print();
                }, 500);
            },

            async parseFilters(urlParams) {
                const faculty = urlParams.get('faculty');
                let facultyName = urlParams.get('faculty_name');
                const major = urlParams.get('major_id');
                let majorName = urlParams.get('major_name');
                const role = urlParams.get('role');
                let roleName = urlParams.get('role_name');
                const search = urlParams.get('search');

                // หากมีรหัสคณะ แต่ไม่มีชื่อคณะ ให้ดึงจาก API
                if (faculty && !facultyName) {
                    try {
                        const res = await fetch(`/api/faculties/${faculty}`);
                        const result = await res.json();
                        const data = result.data || result.result;
                        if (result.success && data) facultyName = data.faculty_name;
                    } catch (e) { console.error(e); }
                }

                // หากมีรหัสสาขา แต่ไม่มีชื่อสาขา ให้ดึงจาก API
                if (major && !majorName) {
                    try {
                        const res = await fetch(`/api/majors/${major}`);
                        const result = await res.json();
                        const data = result.data || result.result;
                        if (result.success && data) majorName = data.major_name;
                    } catch (e) { console.error(e); }
                }

                // หากมีรหัสบทบาท แต่ไม่มีชื่อบทบาท ให้ดึงจาก API
                if (role && !roleName) {
                    try {
                        const res = await fetch('/api/members/count');
                        const result = await res.json();
                        if (result.success && result.data && result.data.roles) {
                            const roleIds = role.split(',');
                            const roleNames = roleIds.map(id => {
                                const foundRole = result.data.roles.find(r => r.role_id == id);
                                return foundRole ? foundRole.role_name_th : null;
                            }).filter(Boolean);
                            if (roleNames.length > 0) roleName = roleNames.join(', ');
                        }
                    } catch (e) { console.error(e); }
                }

                if (faculty) this.activeFilters['คณะ'] = facultyName || faculty;
                if (major) this.activeFilters['สาขา'] = majorName || major;
                if (role) {
                    this.activeFilters['บทบาท'] = roleName || role;
                    this.multipleRoles = role.split(',').length > 1;
                }
                if (search) this.activeFilters['คำค้นหา'] = search;
            },

            async fetchData(urlParams) {
                try {
                    urlParams.set('page', '1');
                    urlParams.set('limit', '10000');

                    const res = await fetch(`/api/members?${urlParams.toString()}`);
                    const result = await res.json();

                    if (result.success || result.data) {
                        this.members = result.data;
                    }
                } catch (err) {
                    console.error("Failed to load members for report:", err);
                    alert("ไม่สามารถโหลดข้อมูลรายงานได้");
                }
            }
        };
    }
</script>