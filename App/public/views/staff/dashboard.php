<div class="space-y-6" x-data="facultyDashboard('<?= $facultyId ?? '' ?>')">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">
                <span x-text="loading ? 'คณะ' : 'คณะ ' + (data.faculty.faculty_name || 'Admin')">คณะ </span>
            </h1>
            <p class="text-slate-600 text-lg">ภาพรวมระบบจัดการขยะธนาคาร</p>
        </div>

        <div class="bg-slate-100 p-1 rounded-lg inline-flex self-start sm:self-center">
            <button @click="filter = 'today'"
                :class="filter === 'today' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                class="px-4 py-2 rounded-md text-sm font-bold transition-all">วันนี้</button>
            <button @click="filter = 'all'"
                :class="filter === 'all' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                class="px-4 py-2 rounded-md text-sm font-bold transition-all">ทั้งหมด</button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl shadow-lg p-6 text-white card-hover">
            <div class="flex items-center justify-between">
                <div class="bg-white/20 backdrop-blur-sm rounded-full p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 7h16m-10 4v6m4-6v6M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2l1-12M9 7V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3" />
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-emerald-100 text-sm mb-1">ปริมาณขยะ <span
                            x-text="filter === 'today' ? '(วันนี้)' : '(ทั้งหมด)'"></span></p>
                    <p class="text-5xl font-bold" x-text="formatNumber(currentStats.total_weight)">0</p>
                    <p class="text-sm text-emerald-100 mt-1">กิโลกรัม</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-700 to-emerald-800  rounded-xl shadow-lg p-6 text-white card-hover">
            <div class="flex items-center justify-between">
                <div class="bg-white/20 backdrop-blur-sm rounded-full p-3">
                    <span class="text-2xl font-bold">CO₂</span>
                </div>
                <div class="text-right">
                    <p class="text-blue-100 text-sm mb-1">การลดคาร์บอน <span
                            x-text="filter === 'today' ? '(วันนี้)' : '(ทั้งหมด)'"></span></p>
                    <p class="text-5xl font-bold" x-text="formatNumber(currentStats.total_co2e)">0</p>
                    <p class="text-sm text-blue-100 mt-1">กิโลกรัม CO₂</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-600 to-orange-700 rounded-xl shadow-lg p-6 text-white card-hover">
            <div class="flex items-center justify-between">
                <div class="bg-white/20 backdrop-blur-sm rounded-full p-3">
                    <span class="text-2xl">⭐</span>
                </div>
                <div class="text-right">
                    <p class="text-orange-100 text-sm mb-1">แต้มที่มอบ <span
                            x-text="filter === 'today' ? '(วันนี้)' : '(ทั้งหมด)'"></span></p>
                    <p class="text-5xl font-bold" x-text="formatNumber(currentStats.total_spend_point)">0</p>
                    <p class="text-sm text-orange-100 mt-1">คะแนน</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-700 to-purple-800 rounded-xl shadow-lg p-6 text-white card-hover">
            <div class="flex items-center justify-between">
                <div class="bg-white/20 backdrop-blur-sm rounded-full p-3">
                    <span class="text-2xl">📝</span>
                </div>
                <div class="text-right">
                    <p class="text-purple-100 text-sm mb-1">
                        แต้มที่เหลืออยู่
                    </p>
                    <p class="text-5xl font-bold" x-text="formatNumber(data.faculty.faculty_point)">0</p>
                    <p class="text-sm text-purple-100 mt-1">คะแนน</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-5 gap-4">
            <h2 class="text-2xl font-bold text-slate-900 flex items-center gap-2 ">
                <svg class="w-7 h-7 text-slate-400 group-hover:text-emerald-600" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" viewBox="0 0 16 16">
                    <path fill="currentColor"
                        d="M7.5 9a2 2 0 0 1 2 2c0 .965-.592 1.73-1.411 2.23C7.27 13.728 6.175 14 5 14s-2.27-.272-3.089-.77C1.091 12.73.5 11.965.5 11a2 2 0 0 1 2-2zm-5 1a1 1 0 0 0-1 1c0 .508.304.992.932 1.375S3.966 13 5 13s1.94-.242 2.568-.625S8.5 11.508 8.5 11a1 1 0 0 0-1-1zm11.652-.992A1.5 1.5 0 0 1 15.5 10.5c0 .771-.47 1.409-1.101 1.83c-.636.424-1.486.67-2.399.67c-.699 0-1.36-.146-1.917-.403c.16-.287.28-.601.35-.943c.423.21.964.346 1.567.346c.743 0 1.394-.202 1.844-.502c.453-.302.656-.665.656-.998a.5.5 0 0 0-.4-.49L14 10h-3.674a3 3 0 0 0-.575-.979A1.5 1.5 0 0 1 9.999 9h4zm-1.92-5.5a2.253 2.253 0 0 1 2.022 2.241l-.012.23A2.253 2.253 0 0 1 12.002 8l-.23-.012a2.25 2.25 0 0 1-2.01-2.01l-.012-.23a2.25 2.25 0 0 1 2.252-2.252zM5 2.5A2.75 2.75 0 1 1 5 8a2.75 2.75 0 0 1 0-5.5m7.002 1.997a1.252 1.252 0 1 0 0 2.504a1.252 1.252 0 0 0 0-2.504M5 3.5A1.75 1.75 0 1 0 5 7a1.75 1.75 0 0 0 0-3.5"
                        stroke-width="0.5" stroke="currentColor" />
                </svg>
                <span>สมาชิก</span>
            </h2>
            <div class="flex items-center gap-4">
                <label for="role-filter" class="text-sm font-medium text-slate-700">บทบาท:</label>
                <select id="role-filter" x-model="memberTable.role" @change="memberTable.handleRoleChange()"
                    class="rounded-md border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm transition cursor-pointer">
                    <option value="">ทั้งหมด</option>
                    <option value="2">นักศึกษา</option>
                    <option value="3">เจ้าหน้าที่</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto relative">
            <div x-show="memberTable.loading"
                class="absolute inset-0 bg-white/80 z-10 flex items-center justify-center backdrop-blur-sm transition-opacity">
                <div class="flex flex-col items-center">
                    <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-emerald-600"></div>
                    <span class="mt-2 text-sm text-slate-600 font-medium">กำลังโหลดข้อมูล...</span>
                </div>
            </div>

            <table class="min-w-full w-full bg-white border-gray-200 rounded-lg">
                <thead class="bg-gray-50">
                    <tr class="text-left text-sm font-bold text-slate-700">
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                            อันดับ</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                            ชื่อ-สกุล</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                            บทบาท</th>
                        <th @click="memberTable.changeSort('waste_point')"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider border-b cursor-pointer hover:bg-slate-200 transition group select-none">
                            แต้มขยะ
                            <span class="inline-block ml-1 w-4 text-center">
                                <span x-show="memberTable.sort.column === 'waste_point'">
                                    <span x-text="memberTable.sort.direction === 'desc' ? '▼' : '▲'"></span>
                                </span>
                                <span x-show="memberTable.sort.column !== 'waste_point'"
                                    class="text-slate-400 opacity-0 group-hover:opacity-100">▼</span>
                            </span>
                        </th>
                        <th @click="memberTable.changeSort('goodness_point')"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider border-b cursor-pointer hover:bg-slate-200 transition group select-none">
                            แต้มความดี
                            <span class="inline-block ml-1 w-4 text-center">
                                <span x-show="memberTable.sort.column === 'goodness_point'">
                                    <span x-text="memberTable.sort.direction === 'desc' ? '▼' : '▲'"></span>
                                </span>
                                <span x-show="memberTable.sort.column !== 'goodness_point'"
                                    class="text-slate-400 opacity-0 group-hover:opacity-100">▼</span>
                            </span>
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    <template x-for="(member, index) in memberTable.members" :key="member.member_id || index">
                        <tr class="hover:bg-slate-50 transition text-sm hover:cursor-pointer"
                            @click="window.open(`/staff/manage/members/detail/${member.member_id}`, '_blank')">
                            <td class="px-6 py-4 text-slate-700"
                                x-text="(memberTable.currentPage - 1) * memberTable.limit + index + 1"></td>
                            <td class="px-6 py-4 font-medium text-slate-900"
                                x-text="(member.member_name || member.member_fname + ' ' + member.member_lname)"></td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-semibold"
                                    x-text="member.role_name_th || member.member_role"></span>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-emerald-600"
                                x-text="formatNumber(member.member_waste_point)"></td>
                            <td class="px-6 py-4 text-right font-bold text-blue-600"
                                x-text="formatNumber(member.member_goodness_point)"></td>
                        </tr>
                    </template>

                    <template x-if="!memberTable.loading && memberTable.members.length === 0">
                        <tr>
                            <td colspan="5" class="text-center py-12">
                                <div class="flex flex-col items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24">
                                        <path fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2"
                                            d="M3 7v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V7m-4 4l-4-4l-4 4m-4-8h16" />
                                    </svg>
                                    <p class="text-slate-500 text-lg mt-4">ไม่พบข้อมูลสมาชิก</p>
                                    <p class="text-slate-400 text-sm">ลองเปลี่ยนตัวกรองหรือค้นหาใหม่</p>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between mt-4 text-xs">
            <button class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50"
                @click="memberTable.changePage(memberTable.currentPage - 1)" :disabled="memberTable.currentPage === 1">
                ก่อนหน้า
            </button>
            <div class="flex items-center space-x-2">
                <template x-for="p in memberTable.getPageNumbers()" :key="p">
                    <button class="px-2 py-1 rounded"
                        :class="p === memberTable.currentPage ? 'bg-emerald-500 text-white' : 'bg-gray-200 hover:bg-gray-300'"
                        @click="p !== '...' && memberTable.changePage(p)" x-text="p"></button>
                </template>
            </div>
            <button class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50"
                :disabled="memberTable.currentPage >= memberTable.totalPages" @click="memberTable.changePage(memberTable.currentPage + 1)">
                ถัดไป
            </button>
        </div>

    </div>

    <script>
        function facultyDashboard(facultyId) {
            return {
                filter: 'today',
                loading: true,
                data: {
                    faculty: { faculty_name: '' },
                    summary: { transaction_count: 0, total_weight: 0, total_spend_point: 0, total_co2e: 0 },
                    summary_today: { transaction_count: 0, total_weight: 0, total_spend_point: 0, total_co2e: 0 }
                },

                get currentStats() {
                    return this.filter === 'today' ? this.data.summary_today : this.data.summary;
                },

                formatNumber(num) {
                    return new Intl.NumberFormat('en-US', { maximumFractionDigits: 2 }).format(num || 0);
                },

                async init() {
                    try {
                        const response = await fetch(`/api/dashboards/faculty/${facultyId}`);
                        const result = await response.json();
                        if (result.success) {
                            this.data = result.data;
                        }
                    } catch (error) {
                        console.error('Error loading dashboard:', error);
                    } finally {
                        this.loading = false;
                    }
                    this.memberTable.fetchMembers();
                },

                memberTable: {
                    loading: false,
                    sort: { column: 'waste_point', direction: 'desc' },
                    role: '',
                    members: [],
                    currentPage: 1,
                    totalPages: 1,
                    totalMembers: 0,
                    limit: 10,

                    async fetchMembers() {
                        if (!facultyId) return;
                        this.loading = true;

                        try {
                            const params = new URLSearchParams({
                                faculty: facultyId,
                                role: this.role,
                                sort_by: this.sort.column,
                                order: this.sort.direction,
                                page: this.currentPage,
                                limit: this.limit
                            });

                            const response = await fetch(`/api/members?${params}`);
                            const result = await response.json();

                            if (result.success && result.data) {
                                this.members = result.data;
                                this.totalMembers = result.total;
                                this.totalPages = Math.ceil(result.total / this.limit);
                            } else {
                                this.members = [];
                                this.totalMembers = 0;
                                this.totalPages = 1;
                            }
                        } catch (error) {
                            console.error('Error fetching members:', error);
                            this.members = [];
                        } finally {
                            this.loading = false;
                        }
                    },

                    handleRoleChange() {
                        this.currentPage = 1;
                        this.fetchMembers();
                    },

                    changePage(page) {
                        if (page >= 1 && page <= this.totalPages) {
                            this.currentPage = page;
                            this.fetchMembers();
                        }
                    },

                    changeSort(column) {
                        if (this.sort.column === column) {
                            this.sort.direction = this.sort.direction === 'asc' ? 'desc' : 'asc';
                        } else {
                            this.sort.column = column;
                            this.sort.direction = 'desc';
                        }
                        this.currentPage = 1;
                        this.fetchMembers();
                    },

                    // Helper for creating pagination numbers with ellipses
                    getPageNumbers() {
                        const total = this.totalPages;
                        const current = this.currentPage;
                        const maxPagesToShow = 5;
                        const pages = [];

                        if (total <= maxPagesToShow) {
                            for (let i = 1; i <= total; i++) pages.push(i);
                        } else {
                            pages.push(1);
                            if (current > 3) pages.push('...');

                            const start = Math.max(2, current - 1);
                            const end = Math.min(total - 1, current + 1);

                            for (let i = start; i <= end; i++) pages.push(i);

                            if (current < total - 2) pages.push('...');
                            pages.push(total);
                        }
                        return pages;
                    }
                }
            }
        }
    </script>
</div>