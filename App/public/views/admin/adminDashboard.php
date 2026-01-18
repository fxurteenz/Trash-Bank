<div class="space-y-6">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 mb-2">📊 Dashboard Admin</h1>
        <p class="text-slate-600 text-lg">ภาพรวมระบบจัดการขยะธนาคาร</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Waste Volume -->
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-lg p-6 text-white card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-white/20 backdrop-blur-sm rounded-full p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" class="text-white">
                        <g fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round"
                                d="M3 5h5.43a2 2 0 0 0 1.664-.89l.812-1.22A2 2 0 0 1 12.57 2h4.488a2 2 0 0 1 1.898 1.368L19.5 5M21 5H8" />
                            <path stroke-linecap="round"
                                d="m19.5 5l-.62 9.906q-.031.49-.061.917M4.5 5l.605 9.897c.154 2.414.232 3.62.874 4.489c.317.429.726.791 1.2 1.063c.96.551 2.244.551 4.814.551H14.5" />
                            <path d="M20 19a3 3 0 1 0-6 0a3 3 0 0 0 6 0Z" />
                        </g>
                    </svg>
                </div>
            </div>
            <p class="text-emerald-100 text-sm mb-1">ปริมาณขยะ (เดือนนี้)</p>
            <p class="text-4xl font-bold">1.2K</p>
            <p class="text-sm text-emerald-100 mt-1">กิโลกรัม</p>
        </div>

        <!-- Carbon Reduction -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-white/20 backdrop-blur-sm rounded-full p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" class="text-white">
                        <path fill="currentColor"
                            d="M16 2a14 14 0 0 0-1.474 27.922c.07-.612.221-1.278.452-1.972l-.04-.003A11.92 11.92 0 0 1 7.91 24.84c-.16-1.13-.41-3.17.39-4.24a2 2 0 0 1 1.7-.92a2.62 2.62 0 0 0 2-1.13a3.64 3.64 0 0 0 .16-3.11c-.26-1-.4-1.67.1-2.34a4.5 4.5 0 0 1 1.23-.53l.035-.012c1.266-.428 2.969-1.005 3.405-2.828c.547-2.277-.357-3.923-1.191-5.443l-.059-.107l-.05-.18H16c2.263 0 4.48.646 6.39 1.86a21.7 21.7 0 0 1-3.76 4.4c-1.38 1.25-.37 2.69.3 3.64a4 4 0 0 1 1.073 2.588a11.4 11.4 0 0 1 1.968-.64a6.2 6.2 0 0 0-1.401-3.078a3.8 3.8 0 0 1-.6-1a23.3 23.3 0 0 0 4-4.67a11.95 11.95 0 0 1 4.003 8.592c.71.1 1.39.229 2.027.374V16A14 14 0 0 0 16 2m-2.51 2.27c.16.31.32.6.49.9l.027.05c.75 1.338 1.398 2.499.973 4.05c-.17.68-1 1-2.14 1.4a4.3 4.3 0 0 0-2.14 1.23a4.31 4.31 0 0 0-.43 4c.19.74.3 1.2.1 1.5c-.142.214-.158.214-.347.222c-.076.003-.18.008-.333.028a3.94 3.94 0 0 0-3 1.7a5.3 5.3 0 0 0-.93 2.84A11.85 11.85 0 0 1 4 16a12 12 0 0 1 9.49-11.73m4.887 23.035C19.15 28.114 20.557 29 23 29c4.294 0 5.638-5.53 6.249-8.042c.1-.414.18-.746.251-.958c.152-.454.543-.79.982-1.044c.69-.396.706-1.04-.064-1.242c-3.629-.951-9.03-1.482-12.418 1.905c-1.404 1.404-1.382 3.244-1.093 4.587q.087-.122.178-.245c1.574-2.13 3.879-4.11 6.92-5.168a.75.75 0 0 1 .493 1.416c-2.71.943-4.78 2.713-6.207 4.644c-1.424 1.928-2.17 3.967-2.291 5.422a.75.75 0 0 0 1.497.033c.07-.907.36-1.95.88-3.002"
                            stroke-width="0.2" stroke="currentColor" />
                    </svg>
                </div>
            </div>
            <p class="text-blue-100 text-sm mb-1">การลดคาร์บอน (เดือนนี้)</p>
            <p class="text-4xl font-bold">1.2</p>
            <p class="text-sm text-blue-100 mt-1">กิโลกรัม CO₂</p>
        </div>

        <!-- User Transactions -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-white/20 backdrop-blur-sm rounded-full p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 14 14" class="text-white">
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.1">
                            <path d="M9.284 3.503a1.621 1.621 0 1 0 3.242 0a1.621 1.621 0 1 0-3.242 0" />
                            <path d="M8.473 8.367v-.81a2.432 2.432 0 0 1 4.865 0v.81M6.6 8.369h6.738M3.604 5.612a1.712 1.712 0 1 0 0-3.425a1.712 1.712 0 0 0 0 3.425" />
                            <path d="M6.6 8.609a2.996 2.996 0 1 0-5.993 0v1.284h1.285l.428 3.424h2.568l.428-3.424H6.6z" />
                        </g>
                    </svg>
                </div>
            </div>
            <p class="text-purple-100 text-sm mb-1">ผู้ใช้บริการ (เดือนนี้)</p>
            <p class="text-4xl font-bold">46</p>
            <p class="text-sm text-purple-100 mt-1">ครั้ง</p>
        </div>

        <!-- Growth -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-white/20 backdrop-blur-sm rounded-full p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" class="text-white">
                        <g fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M2 12c0-4.714 0-7.071 1.464-8.536C4.93 2 7.286 2 12 2s7.071 0 8.535 1.464C22 4.93 22 7.286 22 12s0 7.071-1.465 8.535C19.072 22 16.714 22 12 22s-7.071 0-8.536-1.465C2 19.072 2 16.714 2 12Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m7 14l2.293-2.293a1 1 0 0 1 1.414 0l1.586 1.586a1 1 0 0 0 1.414 0L17 10m0 0v2.5m0-2.5h-2.5" />
                        </g>
                    </svg>
                </div>
            </div>
            <p class="text-orange-100 text-sm mb-1">ความพัฒนา (เดือนนี้)</p>
            <p class="text-4xl font-bold">+25%</p>
            <p class="text-sm text-orange-100 mt-1">เพิ่มขึ้น</p>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div>
        <h2 class="text-2xl font-bold text-slate-900 mb-5">⚡ การเข้าถึงด่วน</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="/admin/manage/users" class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-lg transition-all cursor-pointer group card-hover">
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 group-hover:bg-emerald-200 group-hover:scale-110 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                </div>
                <p class="font-semibold text-slate-900">จัดการผู้ใช้</p>
                <p class="text-sm text-slate-600 mt-1">Users</p>
            </a>

            <a href="/admin/manage/faculty" class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-lg transition-all cursor-pointer group card-hover">
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 group-hover:bg-blue-200 group-hover:scale-110 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                            <polyline points="9 22 9 12 15 12 15 22"/>
                        </svg>
                    </div>
                </div>
                <p class="font-semibold text-slate-900">คณะ/สาขา</p>
                <p class="text-sm text-slate-600 mt-1">Faculty</p>
            </a>

            <a href="/admin/manage/rewards" class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-lg transition-all cursor-pointer group card-hover">
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 group-hover:bg-purple-200 group-hover:scale-110 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                </div>
                <p class="font-semibold text-slate-900">รางวัล</p>
                <p class="text-sm text-slate-600 mt-1">Rewards</p>
            </a>

            <a href="/admin/manage/badges" class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-lg transition-all cursor-pointer group card-hover">
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center text-orange-600 group-hover:bg-orange-200 group-hover:scale-110 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="8" r="7"/>
                            <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/>
                        </svg>
                    </div>
                </div>
                <p class="font-semibold text-slate-900">เหรียญตรา</p>
                <p class="text-sm text-slate-600 mt-1">Badges</p>
            </a>

            <a href="/admin/manage/waste_type" class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-lg transition-all cursor-pointer group card-hover">
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center text-green-600 group-hover:bg-green-200 group-hover:scale-110 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 7h-9"/>
                            <path d="M14 17H5"/>
                            <circle cx="17" cy="17" r="3"/>
                            <circle cx="7" cy="7" r="3"/>
                        </svg>
                    </div>
                </div>
                <p class="font-semibold text-slate-900">หมวดหมู่ขยะ</p>
                <p class="text-sm text-slate-600 mt-1">Waste Types</p>
            </a>

            <a href="/admin/transactions/waste" class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-lg transition-all cursor-pointer group card-hover">
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center text-teal-600 group-hover:bg-teal-200 group-hover:scale-110 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <line x1="19" y1="8" x2="19" y2="14"/>
                            <line x1="22" y1="11" x2="16" y2="11"/>
                        </svg>
                    </div>
                </div>
                <p class="font-semibold text-slate-900">ฝากขยะ</p>
                <p class="text-sm text-slate-600 mt-1">Deposit</p>
            </a>

            <a href="/admin/transactions/clear_waste" class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-lg transition-all cursor-pointer group card-hover">
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 group-hover:bg-indigo-200 group-hover:scale-110 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 11 12 14 22 4"/>
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                        </svg>
                    </div>
                </div>
                <p class="font-semibold text-slate-900">เคลียร์ยอด</p>
                <p class="text-sm text-slate-600 mt-1">Clear</p>
            </a>

            <a href="/admin/manage/waste_transaction" class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-lg transition-all cursor-pointer group card-hover">
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center text-pink-600 group-hover:bg-pink-200 group-hover:scale-110 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="1" x2="12" y2="23"/>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                        </svg>
                    </div>
                </div>
                <p class="font-semibold text-slate-900">รายการฝาก</p>
                <p class="text-sm text-slate-600 mt-1">Transactions</p>
            </a>

            <a href="/admin/manage/donations" class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-lg transition-all cursor-pointer group card-hover">
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 bg-rose-100 rounded-full flex items-center justify-center text-rose-600 group-hover:bg-rose-200 group-hover:scale-110 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2m0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8m3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/>
                        </svg>
                    </div>
                </div>
                <p class="font-semibold text-slate-900">บริจาค</p>
                <p class="text-sm text-slate-600 mt-1">Donations</p>
            </a>
        </div>
    </div>

    <!-- Charts Section -->
    <div>
        <h2 class="text-2xl font-bold text-slate-900 mb-5">📈 สถิติและกราฟ</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Waste Total Chart -->
            <div class="bg-white rounded-xl shadow-md p-6 card-hover">
                <h3 class="text-lg font-bold text-slate-900 mb-4">ปริมาณขยะรวม</h3>
                <div class="h-80">
                    <canvas id="waste-total"></canvas>
                </div>
            </div>

            <!-- Carbon Total Chart -->
            <div class="bg-white rounded-xl shadow-md p-6 card-hover">
                <h3 class="text-lg font-bold text-slate-900 mb-4">การลดคาร์บอนรวม</h3>
                <div class="h-80">
                    <canvas id="carbon-total"></canvas>
                </div>
            </div>

            <!-- Waste by Faculty -->
            <div class="bg-white rounded-xl shadow-md p-6 card-hover">
                <h3 class="text-lg font-bold text-slate-900 mb-4">ปริมาณขยะแยกตามคณะ</h3>
                <div class="h-80">
                    <canvas id="waste-by-faculty"></canvas>
                </div>
            </div>

            <!-- Carbon by Faculty -->
            <div class="bg-white rounded-xl shadow-md p-6 card-hover">
                <h3 class="text-lg font-bold text-slate-900 mb-4">คาร์บอนแยกตามคณะ</h3>
                <div class="h-80">
                    <canvas id="carbon-by-faculty"></canvas>
                </div>
            </div>

            <!-- Waste by Year -->
            <div class="bg-white rounded-xl shadow-md p-6 card-hover">
                <h3 class="text-lg font-bold text-slate-900 mb-4">ปริมาณขยะรายปี</h3>
                <div class="h-80">
                    <canvas id="waste-by-year"></canvas>
                </div>
            </div>

            <!-- Carbon by Year -->
            <div class="bg-white rounded-xl shadow-md p-6 card-hover">
                <h3 class="text-lg font-bold text-slate-900 mb-4">คาร์บอนรายปี</h3>
                <div class="h-80">
                    <canvas id="carbon-by-year"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex justify-between items-center mb-5">
            <h2 class="text-2xl font-bold text-slate-900">📋 รายการล่าสุด</h2>
            <a href="/admin/manage/waste_transaction" class="text-emerald-600 hover:text-emerald-700 font-medium text-sm">ดูทั้งหมด →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-100 border-b-2 border-slate-300">
                    <tr class="text-left text-sm font-bold text-slate-700">
                        <th class="px-6 py-3">วันที่</th>
                        <th class="px-6 py-3">ผู้ฝาก</th>
                        <th class="px-6 py-3">ประเภท</th>
                        <th class="px-6 py-3 text-right">น้ำหนัก (กก.)</th>
                        <th class="px-6 py-3 text-right">คะแนน</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <tr class="hover:bg-slate-50 transition text-sm">
                        <td class="px-6 py-4 text-slate-700">13/01/2026</td>
                        <td class="px-6 py-4 font-medium text-slate-900">นายสมชาย ใจดี</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">พลาสติก</span>
                        </td>
                        <td class="px-6 py-4 text-right font-medium">5.50</td>
                        <td class="px-6 py-4 text-right font-bold text-emerald-600">55</td>
                    </tr>
                    <tr class="hover:bg-slate-50 transition text-sm">
                        <td class="px-6 py-4 text-slate-700">13/01/2026</td>
                        <td class="px-6 py-4 font-medium text-slate-900">นางสาวสมหญิง รักษา</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">กระดาษ</span>
                        </td>
                        <td class="px-6 py-4 text-right font-medium">3.20</td>
                        <td class="px-6 py-4 text-right font-bold text-emerald-600">32</td>
                    </tr>
                    <tr class="hover:bg-slate-50 transition text-sm">
                        <td class="px-6 py-4 text-slate-700">13/01/2026</td>
                        <td class="px-6 py-4 font-medium text-slate-900">นายวิทย์ มานะ</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold">ขวด</span>
                        </td>
                        <td class="px-6 py-4 text-right font-medium">8.00</td>
                        <td class="px-6 py-4 text-right font-bold text-emerald-600">80</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
