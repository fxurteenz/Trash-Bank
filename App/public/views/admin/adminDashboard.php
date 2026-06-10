<div class="space-y-4" x-data="adminDashboard()" x-init="init()">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-4xl font-bold text-slate-900">📊 Dashboard Admin</h1>
            <p class="text-slate-600 text-lg">ภาพรวมระบบจัดการขยะธนาคาร</p>
        </div>

        <div class="flex bg-slate-200 p-1 rounded-lg w-full md:w-auto">
            <button @click="period = 'today'"
                :class="period === 'today' ? 'bg-white shadow text-emerald-600' : 'text-slate-600 hover:text-slate-900'"
                class="flex-1 md:flex-none px-4 py-2 rounded-md text-sm font-medium transition-all">วันนี้</button>
            <button @click="period = 'month'"
                :class="period === 'month' ? 'bg-white shadow text-emerald-600' : 'text-slate-600 hover:text-slate-900'"
                class="flex-1 md:flex-none px-4 py-2 rounded-md text-sm font-medium transition-all">เดือนนี้</button>
            <button @click="period = 'all'"
                :class="period === 'all' ? 'bg-white shadow text-emerald-600' : 'text-slate-600 hover:text-slate-900'"
                class="flex-1 md:flex-none px-4 py-2 rounded-md text-sm font-medium transition-all">ทั้งหมด</button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Waste Volume -->
        <div 
            class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-lg p-6 text-white hover:scale-102 hover:shadow-xl cursor-pointer duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-white/20 backdrop-blur-sm rounded-full p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                        class="text-white">
                        <g fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round"
                                d="M3 5h5.43a2 2 0 0 0 1.664-.89l.812-1.22A2 2 0 0 1 12.57 2h4.488a2 2 0 0 1 1.898 1.368L19.5 5M21 5H8" />
                            <path stroke-linecap="round"
                                d="m19.5 5l-.62 9.906q-.031.49-.061.917M4.5 5l.605 9.897c.154 2.414.232 3.62.874 4.489c.317.429.726.791 1.2 1.063c.96.551 2.244.551 4.814.551H14.5" />
                            <path d="M20 19a3 3 0 1 0-6 0a3 3 0 0 0 6 0Z" />
                        </g>
                    </svg>
                </div>
                <div>
                    <p class="text-4xl font-bold"
                        x-text="Number(currentSummary?.total_weight || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})">
                    </p>
                    <p class="text-sm text-emerald-100 mt-1">กิโลกรัม</p>
                </div>

            </div>
            <p class="text-emerald-100 text-sm mb-1" x-text="`ปริมาณขยะ ${periodText}`"></p>

        </div>

        <!-- Carbon Reduction -->
        <div 
            class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white hover:scale-102 hover:shadow-xl cursor-pointer duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-white/20 backdrop-blur-sm rounded-full p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"
                        class="text-white">
                        <path fill="currentColor"
                            d="M16 2a14 14 0 0 0-1.474 27.922c.07-.612.221-1.278.452-1.972l-.04-.003A11.92 11.92 0 0 1 7.91 24.84c-.16-1.13-.41-3.17.39-4.24a2 2 0 0 1 1.7-.92a2.62 2.62 0 0 0 2-1.13a3.64 3.64 0 0 0 .16-3.11c-.26-1-.4-1.67.1-2.34a4.5 4.5 0 0 1 1.23-.53l.035-.012c1.266-.428 2.969-1.005 3.405-2.828c.547-2.277-.357-3.923-1.191-5.443l-.059-.107l-.05-.18H16c2.263 0 4.48.646 6.39 1.86a21.7 21.7 0 0 1-3.76 4.4c-1.38 1.25-.37 2.69.3 3.64a4 4 0 0 1 1.073 2.588a11.4 11.4 0 0 1 1.968-.64a6.2 6.2 0 0 0-1.401-3.078a3.8 3.8 0 0 1-.6-1a23.3 23.3 0 0 0 4-4.67a11.95 11.95 0 0 1 4.003 8.592c.71.1 1.39.229 2.027.374V16A14 14 0 0 0 16 2m-2.51 2.27c.16.31.32.6.49.9l.027.05c.75 1.338 1.398 2.499.973 4.05c-.17.68-1 1-2.14 1.4a4.3 4.3 0 0 0-2.14 1.23a4.31 4.31 0 0 0-.43 4c.19.74.3 1.2.1 1.5c-.142.214-.158.214-.347.222c-.076.003-.18.008-.333.028a3.94 3.94 0 0 0-3 1.7a5.3 5.3 0 0 0-.93 2.84A11.85 11.85 0 0 1 4 16a12 12 0 0 1 9.49-11.73m4.887 23.035C19.15 28.114 20.557 29 23 29c4.294 0 5.638-5.53 6.249-8.042c.1-.414.18-.746.251-.958c.152-.454.543-.79.982-1.044c.69-.396.706-1.04-.064-1.242c-3.629-.951-9.03-1.482-12.418 1.905c-1.404 1.404-1.382 3.244-1.093 4.587q.087-.122.178-.245c1.574-2.13 3.879-4.11 6.92-5.168a.75.75 0 0 1 .493 1.416c-2.71.943-4.78 2.713-6.207 4.644c-1.424 1.928-2.17 3.967-2.291 5.422a.75.75 0 0 0 1.497.033c.07-.907.36-1.95.88-3.002"
                            stroke-width="0.2" stroke="currentColor" />
                    </svg>
                </div>
                <div>
                    <p class="text-4xl font-bold"
                        x-text="Number(currentSummary?.total_co2e || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})">
                    </p>
                    <p class="text-sm text-blue-100 mt-1">กิโลกรัม CO₂e</p>
                </div>
            </div>
            <p class="text-blue-100 text-sm mb-1" x-text="`การลดคาร์บอน ${periodText}`"></p>

        </div>

        <!-- User Transactions -->
        <div @click="window.location.href = '/admin/history/waste_transaction'"
            class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white hover:scale-102 hover:shadow-xl cursor-pointer duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-white/20 backdrop-blur-sm rounded-full p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 14 14"
                        class="text-white">
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="1.1">
                            <path d="M9.284 3.503a1.621 1.621 0 1 0 3.242 0a1.621 1.621 0 1 0-3.242 0" />
                            <path
                                d="M8.473 8.367v-.81a2.432 2.432 0 0 1 4.865 0v.81M6.6 8.369h6.738M3.604 5.612a1.712 1.712 0 1 0 0-3.425a1.712 1.712 0 0 0 0 3.425" />
                            <path
                                d="M6.6 8.609a2.996 2.996 0 1 0-5.993 0v1.284h1.285l.428 3.424h2.568l.428-3.424H6.6z" />
                        </g>
                    </svg>
                </div>
                <div>
                    <p class="text-4xl font-bold"
                        x-text="Number(currentSummary?.transaction_count || 0).toLocaleString()"></p>
                    <p class="text-sm text-purple-100 mt-1">คน</p>

                </div>
            </div>
            <p class="text-purple-100 text-sm mb-1" x-text="`รายการฝากขยะ ${periodText}`"></p>

        </div>

        <!-- Growth -->
        <div 
            class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white hover:scale-102 hover:shadow-xl cursor-pointer duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-white/20 backdrop-blur-sm rounded-full p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                        class="text-white">
                        <g fill="none" stroke="currentColor" stroke-width="2">
                            <path
                                d="M2 12c0-4.714 0-7.071 1.464-8.536C4.93 2 7.286 2 12 2s7.071 0 8.535 1.464C22 4.93 22 7.286 22 12s0 7.071-1.465 8.535C19.072 22 16.714 22 12 22s-7.071 0-8.536-1.465C2 19.072 2 16.714 2 12Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m7 14l2.293-2.293a1 1 0 0 1 1.414 0l1.586 1.586a1 1 0 0 0 1.414 0L17 10m0 0v2.5m0-2.5h-2.5" />
                        </g>
                    </svg>
                </div>
                <div>
                    <p class="text-4xl font-bold" x-text="Number(dashboardData.total_member || 0).toLocaleString()"></p>
                    <p class="text-sm text-orange-100 mt-1">ครั้ง</p>
                </div>
            </div>
            <p class="text-orange-100 text-sm mb-1">ผู้ใช้งานระบบทั้งหมด</p>

        </div>
    </div>

    <!-- Quick Actions Section -->
    <div>
        <h2 class="text-2xl font-bold text-slate-900 mb-2">⚡ การเข้าถึงด่วน</h2>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <a href="/admin/transactions/waste"
                class="bg-white rounded-xl shadow-md p-4 text-center hover:shadow-lg transition-all cursor-pointer group card-hover">
                <div class="flex justify-center mb-4">
                    <div
                        class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center text-teal-600 group-hover:bg-teal-200 group-hover:scale-110 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <line x1="19" y1="8" x2="19" y2="14" />
                            <line x1="22" y1="11" x2="16" y2="11" />
                        </svg>
                    </div>
                </div>
                <p class="font-semibold text-slate-900">ฝากขยะ</p>
                <p class="text-sm text-slate-600 mt-1">Deposit</p>
            </a>

            <a href="/admin/transactions/clear_waste"
                class="bg-white rounded-xl shadow-md p-4 text-center hover:shadow-lg transition-all cursor-pointer group card-hover">
                <div class="flex justify-center mb-4">
                    <div
                        class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center text-amber-600 group-hover:bg-amber-200 group-hover:scale-110 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <polyline points="9 11 12 14 22 4" />
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" />
                        </svg>
                    </div>
                </div>
                <p class="font-semibold text-slate-900">เคลียร์ยอด</p>
                <p class="text-sm text-slate-600 mt-1">Clear</p>
            </a>

            <a href="/admin/transactions/waste_sale"
                class="bg-white rounded-xl shadow-md p-4 text-center hover:shadow-lg transition-all cursor-pointer group card-hover">
                <div class="flex justify-center mb-4">
                    <div
                        class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 group-hover:bg-blue-200 group-hover:scale-110 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 14 14">
                            <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="1.5">
                                <path d="M7 13.5a6.5 6.5 0 1 0 0-13a6.5 6.5 0 0 0 0 13" />
                                <path
                                    d="M8.702 5.222a1.33 1.33 0 0 0-1.258-.889H6.412a1.19 1.19 0 0 0-.254 2.353l1.571.344a1.334 1.334 0 0 1-.285 2.637h-.888a1.33 1.33 0 0 1-1.258-.89M7 4.333V3m0 8V9.666" />
                            </g>
                        </svg>
                    </div>
                </div>
                <p class="font-semibold text-slate-900">จำหน่ายขยะ</p>
                <p class="text-sm text-slate-600 mt-1">Sell</p>
            </a>

            <a href="/admin/transactions/donation"
                class="bg-white rounded-xl shadow-md p-4 text-center hover:shadow-lg transition-all cursor-pointer group card-hover">
                <div class="flex justify-center mb-4">
                    <div
                        class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 group-hover:bg-purple-200 group-hover:scale-110 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M4 21h9.62a4 4 0 0 0 3.037-1.397l5.102-5.952a1 1 0 0 0-.442-1.6l-1.968-.656a3.04 3.04 0 0 0-2.823.503l-3.185 2.547l-.617-1.235A3.98 3.98 0 0 0 9.146 11H4c-1.103 0-2 .897-2 2v6c0 1.103.897 2 2 2m0-8h5.146c.763 0 1.448.423 1.789 1.105l.447.895H7v2h6.014a1 1 0 0 0 .442-.11l.003-.001l.004-.002h.003l.002-.001h.004l.001-.001c.009.003.003-.001.003-.001c.01 0 .002-.001.002-.001h.001l.002-.001l.003-.001l.002-.001l.002-.001l.003-.001l.002-.001c.003 0 .001-.001.002-.001l.003-.002l.002-.001l.002-.001l.003-.001l.002-.001h.001l.002-.001h.001l.002-.001l.002-.001c.009-.001.003-.001.003-.001l.002-.001a1 1 0 0 0 .11-.078l4.146-3.317c.262-.208.623-.273.94-.167l.557.186l-4.133 4.823a2.03 2.03 0 0 1-1.52.688H4zM16 2h-.017c-.163.002-1.006.039-1.983.705c-.951-.648-1.774-.7-1.968-.704L12.002 2h-.004c-.801 0-1.555.313-2.119.878C9.313 3.445 9 4.198 9 5s.313 1.555.861 2.104l3.414 3.586a1.006 1.006 0 0 0 1.45-.001l3.396-3.568C18.688 6.555 19 5.802 19 5s-.313-1.555-.878-2.121A2.98 2.98 0 0 0 16.002 2zm1 3c0 .267-.104.518-.311.725L14 8.55l-2.707-2.843C11.104 5.518 11 5.267 11 5s.104-.518.294-.708A.98.98 0 0 1 11.979 4c.025.001.502.032 1.067.485q.121.098.247.222l.707.707l.707-.707q.126-.124.247-.222c.529-.425.976-.478 1.052-.484a1 1 0 0 1 .701.292c.189.189.293.44.293.707"
                                stroke-width="0.5" stroke="currentColor" />
                        </svg>
                    </div>
                </div>
                <p class="font-semibold text-slate-900">รับสิ่งของ</p>
                <p class="text-sm text-slate-600 mt-1">Donation</p>
            </a>

            <a href="/admin/transactions/redeem_item"
                class="bg-white rounded-xl shadow-md p-4 text-center hover:shadow-lg transition-all cursor-pointer group card-hover">
                <div class="flex justify-center mb-4">
                    <div
                        class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 group-hover:bg-purple-200 group-hover:scale-110 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 14 14">
                            <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="1.5">
                                <path
                                    d="M13.5 2.5h-7v5h7zm-3.5 0v5M8.5.5l1.5 2l1.5-2M.5 11l2.444 2.036a2 2 0 0 0 1.28.464h6.443c.46 0 .833-.374.833-.834c0-.92-.746-1.667-1.667-1.667H5.354" />
                                <path d="m3.5 10l.75.75a1.06 1.06 0 0 0 1.5-1.5L4.586 8.085A2 2 0 0 0 3.17 7.5H.5" />
                            </g>
                        </svg>
                    </div>
                </div>
                <p class="font-semibold text-slate-900">แลกของรางวัล</p>
                <p class="text-sm text-slate-600 mt-1">Redeem</p>
            </a>

            <a href="/admin/manage/users"
                class="bg-white rounded-xl shadow-md p-4 text-center hover:shadow-lg transition-all cursor-pointer group card-hover">
                <div class="flex justify-center mb-4">
                    <div
                        class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 group-hover:bg-emerald-200 group-hover:scale-110 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                    </div>
                </div>
                <p class="font-semibold text-slate-900">จัดการผู้ใช้</p>
                <p class="text-sm text-slate-600 mt-1">Users</p>
            </a>

            <a href="/admin/manage/faculty"
                class="bg-white rounded-xl shadow-md p-4 text-center hover:shadow-lg transition-all cursor-pointer group card-hover">
                <div class="flex justify-center mb-4">
                    <div
                        class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 group-hover:bg-blue-200 group-hover:scale-110 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                            <polyline points="9 22 9 12 15 12 15 22" />
                        </svg>
                    </div>
                </div>
                <p class="font-semibold text-slate-900">คณะ/สาขา</p>
                <p class="text-sm text-slate-600 mt-1">Faculty</p>
            </a>

            <a href="/admin/manage/reward"
                class="bg-white rounded-xl shadow-md p-4 text-center hover:shadow-lg transition-all cursor-pointer group card-hover">
                <div class="flex justify-center mb-4">
                    <div
                        class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 group-hover:bg-purple-200 group-hover:scale-110 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 56 56">
                            <path fill="currentColor"
                                d="M9.66 16.094c-2.742 0-4.453 1.804-4.453 4.664v5.906c0 2.461 1.195 4.148 3.375 4.57v14.578c0 4.43 2.414 6.75 6.844 6.75h25.148c4.43 0 6.844-2.32 6.844-6.75V31.235c2.203-.422 3.375-2.109 3.375-4.57v-5.906c0-2.86-1.57-4.664-4.453-4.664h-4.97c1.313-1.29 2.086-2.977 2.086-4.875c0-4.547-3.586-7.781-8.133-7.781c-3.351 0-6.094 1.851-7.312 5.156c-1.22-3.305-3.985-5.156-7.336-5.156c-4.524 0-8.133 3.234-8.133 7.78c0 1.9.75 3.587 2.062 4.876Zm12.773 0c-3.867 0-5.906-2.274-5.906-4.711c0-2.531 1.875-4.031 4.383-4.031c2.883 0 5.156 2.226 5.156 5.953v2.789Zm11.133 0h-3.633v-2.79c0-3.726 2.274-5.952 5.157-5.952c2.508 0 4.406 1.5 4.406 4.03c0 2.438-2.11 4.712-5.93 4.712m-22.945 3.539h15.305v8.156H10.62c-1.172 0-1.64-.492-1.64-1.664v-4.852c0-1.171.468-1.64 1.64-1.64m34.781 0c1.172 0 1.617.469 1.617 1.64v4.852c0 1.172-.445 1.664-1.617 1.664H30.074v-8.156Zm-30 29.414c-1.968 0-3.046-1.102-3.046-3.047V31.328h13.57v17.719ZM43.645 46c0 1.945-1.079 3.047-3.024 3.047H30.074V31.328h13.57Z"
                                stroke-width="1.5" stroke="currentColor" />
                        </svg>
                    </div>
                </div>
                <p class="font-semibold text-slate-900">รางวัล</p>
                <p class="text-sm text-slate-600 mt-1">Rewards</p>
            </a>

            <a href="/admin/manage/badge"
                class="bg-white rounded-xl shadow-md p-4 text-center hover:shadow-lg transition-all cursor-pointer group card-hover">
                <div class="flex justify-center mb-4">
                    <div
                        class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center text-orange-600 group-hover:bg-orange-200 group-hover:scale-110 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="8" r="7" />
                            <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88" />
                        </svg>
                    </div>
                </div>
                <p class="font-semibold text-slate-900">เหรียญตรา</p>
                <p class="text-sm text-slate-600 mt-1">Badges</p>
            </a>

            <a href="/admin/manage/waste_type"
                class="bg-white rounded-xl shadow-md p-4 text-center hover:shadow-lg transition-all cursor-pointer group card-hover">
                <div class="flex justify-center mb-4">
                    <div
                        class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center text-green-600 group-hover:bg-green-200 group-hover:scale-110 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path d="M20 7h-9" />
                            <path d="M14 17H5" />
                            <circle cx="17" cy="17" r="3" />
                            <circle cx="7" cy="7" r="3" />
                        </svg>
                    </div>
                </div>
                <p class="font-semibold text-slate-900">หมวดหมู่ขยะ</p>
                <p class="text-sm text-slate-600 mt-1">Waste Types</p>
            </a>

        </div>
    </div>

    <!-- Leaderboards Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mt-8 mb-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">🏆 จัดอันดับ (Leaderboards)</h2>
        </div>
        <div class="flex bg-slate-200 p-1 rounded-lg w-full md:w-auto">
            <button @click="leaderboardPeriod = 'month'"
                :class="leaderboardPeriod === 'month' ? 'bg-white shadow text-emerald-600' : 'text-slate-600 hover:text-slate-900'"
                class="flex-1 md:flex-none px-4 py-2 rounded-md text-sm font-medium transition-all">เดือนนี้</button>
            <button @click="leaderboardPeriod = 'year'"
                :class="leaderboardPeriod === 'year' ? 'bg-white shadow text-emerald-600' : 'text-slate-600 hover:text-slate-900'"
                class="flex-1 md:flex-none px-4 py-2 rounded-md text-sm font-medium transition-all">ปีนี้</button>
            <button @click="leaderboardPeriod = 'all'"
                :class="leaderboardPeriod === 'all' ? 'bg-white shadow text-emerald-600' : 'text-slate-600 hover:text-slate-900'"
                class="flex-1 md:flex-none px-4 py-2 rounded-md text-sm font-medium transition-all">ทั้งหมด</button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Faculty Leaderboard -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-slate-900">🏆 5 อันดับคณะยอดเยี่ยม</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-600">
                    <thead class="text-xs text-slate-700 uppercase bg-slate-100">
                        <tr>
                            <th class="px-4 py-2 rounded-l-lg">อันดับ</th>
                            <th class="px-4 py-2 cursor-pointer hover:bg-slate-200 transition select-none"
                                @click="sortFaculties('name')">
                                คณะ <span x-show="facultySort === 'name'"
                                    x-text="facultySortOrder === 'ASC' ? '↑' : '↓'" x-cloak></span>
                            </th>
                            <th class="px-4 py-2 text-right cursor-pointer hover:bg-slate-200 transition select-none"
                                @click="sortFaculties('weight')">
                                น้ำหนัก (กก.) <span x-show="facultySort === 'weight'"
                                    x-text="facultySortOrder === 'ASC' ? '↑' : '↓'" x-cloak></span>
                            </th>
                            <th class="px-4 py-2 text-right cursor-pointer hover:bg-slate-200 transition select-none"
                                @click="sortFaculties('point')">
                                คะแนน <span x-show="facultySort === 'point'"
                                    x-text="facultySortOrder === 'ASC' ? '↑' : '↓'" x-cloak></span>
                            </th>
                            <th class="px-4 py-2 text-right rounded-r-lg">co2e</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <template x-for="(faculty, index) in facultyLeaderboard" :key="faculty.faculty_id">
                            <tr class="bg-white hover:bg-slate-50 transition">
                                <td class="px-4 py-3 font-bold text-slate-900" x-text="index + 1"></td>
                                <td class="px-4 py-3 font-medium text-slate-700" x-text="faculty.faculty_name"></td>
                                <td class="px-4 py-3 text-right font-medium text-emerald-600"
                                    x-text="Number(faculty.total_weight || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})">
                                </td>
                                <td class="px-4 py-3 text-right font-bold text-amber-500"
                                    x-text="Number(faculty.total_point || 0).toLocaleString()"></td>
                                <td class="px-4 py-3 text-right font-bold text-sky-500"
                                    x-text="Number(faculty.total_co2e || 0).toLocaleString()"></td>
                            </tr>
                        </template>
                        <template x-if="facultyLeaderboard.length === 0">
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-slate-500">ไม่มีข้อมูล</td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Member Leaderboard -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-slate-900">🏅 5 อันดับสมาชิกยอดเยี่ยม</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-600">
                    <thead class="text-xs text-slate-700 uppercase bg-slate-100">
                        <tr>
                            <th class="px-4 py-2 rounded-l-lg">อันดับ</th>
                            <th class="px-4 py-2 cursor-pointer hover:bg-slate-200 transition select-none"
                                @click="sortMembers('name')">
                                ชื่อผู้ใช้งาน <span x-show="memberSort === 'name'"
                                    x-text="memberSortOrder === 'ASC' ? '↑' : '↓'" x-cloak></span>
                            </th>
                            <th class="px-4 py-2 text-right cursor-pointer hover:bg-slate-200 transition select-none"
                                @click="sortMembers('point')">
                                แต้มขยะ <span x-show="memberSort === 'point'"
                                    x-text="memberSortOrder === 'ASC' ? '↑' : '↓'" x-cloak></span>
                            </th>
                            <th class="px-4 py-2 text-right cursor-pointer hover:bg-slate-200 transition select-none"
                                @click="sortMembers('goodness')">
                                แต้มความดี <span x-show="memberSort === 'goodness'"
                                    x-text="memberSortOrder === 'ASC' ? '↑' : '↓'" x-cloak></span>
                            </th>
                            <th class="px-4 py-2 text-right rounded-r-lg">co2e</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <template x-for="(member, index) in memberLeaderboard" :key="member.member_id">
                            <tr class="bg-white hover:bg-slate-50 transition">
                                <td class="px-4 py-3 font-bold text-slate-900" x-text="index + 1"></td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-slate-700" x-text="member.member_name || 'ไม่มีชื่อ'">
                                    </div>
                                    <div class="text-xs text-slate-400 mt-0.5" x-text="member.member_phone || '-'">
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-right font-medium text-emerald-600"
                                    x-text="Number(member.total_point || 0).toLocaleString()">
                                </td>
                                <td class="px-4 py-3 text-right font-bold text-amber-500"
                                    x-text="Number(member.total_goodness || 0).toLocaleString()"></td>
                                <td class="px-4 py-3 text-right font-bold text-sky-500"
                                    x-text="Number(member.total_co2e || 0).toLocaleString()"></td>
                            </tr>
                        </template>
                        <template x-if="memberLeaderboard.length === 0">
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-slate-500">ไม่มีข้อมูล</td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
    function adminDashboard() {
        return {
            dashboardData: {},
            period: 'month', // 'all', 'month', 'today'
            leaderboardPeriod: 'all', // 'all', 'month', 'year'
            facultySort: 'point', // 'point', 'weight', 'name'
            facultySortOrder: 'DESC',
            memberSort: 'point', // 'point', 'goodness', 'name'
            memberSortOrder: 'DESC',
            facultyLeaderboard: [],
            memberLeaderboard: [],

            get currentSummary() {
                if (this.period === 'all') return this.dashboardData.summary || {};
                if (this.period === 'today') return this.dashboardData.summary_today || {};
                return this.dashboardData.summary_month || {};
            },

            get periodText() {
                if (this.period === 'all') return '(ทั้งหมด)';
                if (this.period === 'today') return '(วันนี้)';
                return '(เดือนนี้)';
            },

            async init() {
                this.$watch('leaderboardPeriod', () => {
                    this.fetchFacultyLeaderboard();
                    this.fetchMemberLeaderboard();
                });
                this.$watch('facultySort', () => {
                    this.fetchFacultyLeaderboard();
                });
                this.$watch('memberSort', () => {
                    this.fetchMemberLeaderboard();
                });

                try {
                    const res = await fetch('/api/dashboards/center');
                    const json = await res.json();
                    if (json.success || json.data) {
                        this.dashboardData = json.data;
                    }
                } catch (error) {
                    console.error('Error fetching dashboard data:', error);
                }

                await Promise.all([this.fetchFacultyLeaderboard(), this.fetchMemberLeaderboard()]);
            },

            sortMembers(column) {
                if (this.memberSort === column) {
                    this.memberSortOrder = this.memberSortOrder === 'ASC' ? 'DESC' : 'ASC';
                    this.fetchMemberLeaderboard();
                } else {
                    this.memberSortOrder = 'DESC';
                    this.memberSort = column; // การเปลี่ยนค่าตรงนี้จะไป trigger watch ให้ fetch ข้อมูลอัตโนมัติ
                }
            },

            sortFaculties(column) {
                if (this.facultySort === column) {
                    this.facultySortOrder = this.facultySortOrder === 'ASC' ? 'DESC' : 'ASC';
                    this.fetchFacultyLeaderboard();
                } else {
                    this.facultySortOrder = 'DESC';
                    this.facultySort = column;
                }
            },

            async fetchFacultyLeaderboard() {
                try {
                    let params = `?limit=5&page=1&sort=${this.facultySort}&order=${this.facultySortOrder}`;
                    const now = new Date();
                    if (this.leaderboardPeriod === 'month') {
                        params += `&month=${now.getMonth() + 1}&year=${now.getFullYear()}`;
                    } else if (this.leaderboardPeriod === 'year') {
                        params += `&year=${now.getFullYear()}`;
                    }

                    const res = await fetch(`/api/leaders/faculty${params}`);
                    const json = await res.json();
                    if (json.success) this.facultyLeaderboard = json.data || [];
                } catch (e) {
                    console.error('Error fetching faculty leaderboard:', e);
                }
            },

            async fetchMemberLeaderboard() {
                try {
                    let params = `?limit=5&page=1&sort=${this.memberSort}&order=${this.memberSortOrder}`;
                    const now = new Date();
                    if (this.leaderboardPeriod === 'month') {
                        params += `&month=${now.getMonth() + 1}&year=${now.getFullYear()}`;
                    } else if (this.leaderboardPeriod === 'year') {
                        params += `&year=${now.getFullYear()}`;
                    }

                    const res = await fetch(`/api/leaders/member${params}`);
                    const json = await res.json();
                    if (json.success) this.memberLeaderboard = json.result || [];
                } catch (e) {
                    console.error('Error fetching leaderboards:', e);
                }
            },
        }
    }
</script>