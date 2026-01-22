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
            <h2 class="text-2xl font-bold text-slate-900">👥 สมาชิกในคณะ</h2>
            <div class="flex items-center gap-4">
                <label for="role-filter" class="text-sm font-medium text-slate-700">บทบาท:</label>
                <select id="role-filter" x-model="memberTable.role" @change="memberTable.fetchMembers()"
                    class="rounded-md border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm transition cursor-pointer">
                    <option value="">ทั้งหมด</option>
                    <option value="2">นักศึกษา</option>
                    <option value="3">เจ้าหน้าที่</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto relative min-h-[300px]">
            <div x-show="memberTable.loading"
                class="absolute inset-0 bg-white/80 z-10 flex items-center justify-center backdrop-blur-sm transition-opacity">
                <div class="flex flex-col items-center">
                    <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-emerald-600"></div>
                    <span class="mt-2 text-sm text-slate-600 font-medium">กำลังโหลดข้อมูล...</span>
                </div>
            </div>

            <table class="w-full">
                <thead class="bg-slate-100 border-b-2 border-slate-300">
                    <tr class="text-left text-sm font-bold text-slate-700">
                        <th class="px-6 py-3 w-16">อันดับ</th>
                        <th class="px-6 py-3">ชื่อ-สกุล</th>
                        <th class="px-6 py-3">บทบาท</th>

                        <th @click="memberTable.changeSort('waste_point')"
                            class="px-6 py-3 text-right cursor-pointer hover:bg-slate-200 transition group select-none">
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
                            class="px-6 py-3 text-right cursor-pointer hover:bg-slate-200 transition group select-none">
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

                <tbody class="divide-y divide-slate-200">
                    <template x-for="(member, index) in memberTable.members" :key="member.member_id || index">
                        <tr class="hover:bg-slate-50 transition text-sm">
                            <td class="px-6 py-4 text-slate-700" x-text="index + 1"></td>
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
                                <p class="text-slate-400 text-lg">🚫 ไม่พบข้อมูลสมาชิก</p>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
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

                // Computed Property สำหรับสลับข้อมูล Stats
                get currentStats() {
                    return this.filter === 'today' ? this.data.summary_today : this.data.summary;
                },

                // Helper Format Number
                formatNumber(num) {
                    return new Intl.NumberFormat('en-US', { maximumFractionDigits: 2 }).format(num || 0);
                },

                async init() {
                    // Load Dashboard Stats
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

                    // Load Initial Table Data
                    this.memberTable.fetchMembers();
                },

                // Logic ตารางสมาชิก (Clean Alpine Style)
                memberTable: {
                    loading: false,
                    sort: {
                        column: 'waste_point', // Default sort column
                        direction: 'desc'
                    },
                    role: '', // Bound to Select via x-model
                    members: [],

                    async fetchMembers() {
                        if (!facultyId) return;
                        this.loading = true;

                        try {
                            // ใช้ URLSearchParams สร้าง Query String ง่ายๆ
                            const params = new URLSearchParams({
                                faculty: facultyId,
                                role: this.role,
                                sort_by: this.sort.column, // แก้ให้ตรงกับ PHP (sort_by)
                                order: this.sort.direction // แก้ให้ตรงกับ PHP (asc/desc)
                            });

                            const response = await fetch(`/api/members?${params}`);
                            const result = await response.json();

                            if (result.data) { // เช็ค result.data ตาม Format ที่คืนค่า
                                this.members = result.data;
                            } else {
                                this.members = [];
                            }
                        } catch (error) {
                            console.error('Error fetching members:', error);
                            this.members = [];
                        } finally {
                            this.loading = false;
                        }
                    },

                    // ฟังก์ชั่นเปลี่ยนการเรียงลำดับเมื่อกดหัวตาราง
                    changeSort(column) {
                        if (this.sort.column === column) {
                            // ถ้าคอลัมน์เดิม ให้สลับทิศทาง
                            this.sort.direction = this.sort.direction === 'asc' ? 'desc' : 'asc';
                        } else {
                            // ถ้าคอลัมน์ใหม่ ให้เริ่มที่ desc
                            this.sort.column = column;
                            this.sort.direction = 'desc';
                        }
                        this.fetchMembers(); // โหลดข้อมูลใหม่ทันที
                    }
                }
            }
        }
    </script>
</div>