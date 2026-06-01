<?php
$faculty_id = (int) $faculty_id ?? "null";
?>
<div x-data="FacultyDetailManager()" x-init="initData()" class="space-y-6">
    <!-- Top actions -->
    <div class="flex justify-between items-center">
        <div class="flex items-center gap-4">
            <button @click="window.history.back()"
                class="text-gray-500 hover:text-gray-700 transition cursor-pointer hover:scale-105 active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </button>
            <h1 class="text-2xl font-bold text-slate-900" x-text="`รายละเอียด ${faculty.faculty_name || ''}`">
            </h1>
        </div>
        <div class="flex items-center gap-2">
            <button @click="window.open(`/report/branch/${facultyId}`, '_blank','noopener')"
                class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-200 transition font-medium flex items-center gap-2 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                พิมพ์รายงาน
            </button>
            <button @click="openEditFacultyDialog()"
                class="bg-amber-100 text-amber-700 px-4 py-2 rounded-lg hover:bg-amber-200 transition font-medium flex items-center gap-2 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path
                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
                แก้ไขข้อมูล
            </button>
        </div>
    </div>

    <!-- Dashboard Stats Card -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center mb-2 gap-4">
        <div class="flex bg-slate-100 p-1 rounded-lg shadow">
            <button @click="statTab = 'today'"
                :class="statTab === 'today' ? 'bg-white shadow-sm text-emerald-600' : 'text-slate-500 hover:text-slate-700'"
                class="px-4 py-1.5 text-sm font-medium rounded-md transition-all cursor-pointer">วันนี้</button>
            <button @click="statTab = 'all'"
                :class="statTab === 'all' ? 'bg-white shadow-sm text-emerald-600' : 'text-slate-500 hover:text-slate-700'"
                class="px-4 py-1.5 text-sm font-medium rounded-md transition-all cursor-pointer">สะสมทั้งหมด</button>
        </div>
    </div>

    <!-- Today -->
    <div x-show="statTab === 'today'" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        class="grid grid-cols-2 md:grid-cols-4 gap-4" x-cloak>
        <div
            class="bg-gradient-to-br from-emerald-500 to-emerald-600 p-4 rounded-lg text-center flex justify-between items-center ">
            <div class="text-white flex-col justify-center items-center gap-1">
                <p class="text-sm font-semibold mb-2 ">แต้มคงเหลือ</p>
                <div class="w-10 h-10 p-2 rounded-full text-emerald-500 bg-white flex justify-center items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="m8.85 16.825l3.15-1.9l3.15 1.925l-.825-3.6l2.775-2.4l-3.65-.325l-1.45-3.4l-1.45 3.375l-3.65.325l2.775 2.425zm3.15.45l-4.15 2.5q-.275.175-.575.15t-.525-.2t-.35-.437t-.05-.588l1.1-4.725L3.775 10.8q-.25-.225-.312-.513t.037-.562t.3-.45t.55-.225l4.85-.425l1.875-4.45q.125-.3.388-.45t.537-.15t.537.15t.388.45l1.875 4.45l4.85.425q.35.05.55.225t.3.45t.038.563t-.313.512l-3.675 3.175l1.1 4.725q.075.325-.05.588t-.35.437t-.525.2t-.575-.15zm0-5.025"
                            stroke-width="0.5" stroke="currentColor" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-white" x-text="Number(faculty.faculty_point || 0).toLocaleString()"></p>
        </div>
        <div
            class="bg-gradient-to-br from-purple-500 to-purple-600 p-4 rounded-lg text-center flex justify-between items-center ">
            <div class="text-white flex-col justify-center items-center gap-1">
                <p class="text-sm font-semibold mb-2 ">แต้มที่จ่ายไป</p>
                <div class="w-10 h-10 p-2 rounded-full text-purple-500 bg-white flex justify-center items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 14 14">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            d="M9.568 1.255a.466.466 0 0 1 .864 0l.587 1.433l1.593.14c.416.036.578.56.255.824l-.947.778a.47.47 0 0 0-.16.46l.314 1.452a.466.466 0 0 1-.715.486L10 5.92l-1.359.91a.466.466 0 0 1-.715-.487L8.24 4.89a.47.47 0 0 0-.16-.459l-.947-.778a.466.466 0 0 1 .255-.825l1.593-.14zM.983 6.37l.692-.043a8 8 0 0 1 2.448.227l1.16.292a1.32 1.32 0 0 1 .99 1.416v0c-.078.765-.79 1.3-1.546 1.166L3.622 9.23l3.897.699l4.037-.958a1.24 1.24 0 0 1 1.482.887v0c.16.603-.153 1.23-.73 1.465l-3.23 1.311a6.93 6.93 0 0 1-4.918.113L.813 11.562"
                            stroke-width="1.5" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-white"
                x-text="Number(summaryToday.total_spend_point || 0).toLocaleString()"></p>
        </div>
        <div
            class="bg-gradient-to-br from-amber-500 to-amber-600 p-4 rounded-lg text-center flex justify-between items-center ">
            <div class="text-white flex-col justify-center items-center gap-1">
                <p class="text-sm font-semibold mb-2 ">น้ำหนักรวม (กก.)</p>
                <div class="w-10 h-10 p-2 rounded-full text-amber-500 bg-white flex justify-center items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 7h16m-10 4v6m4-6v6M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2l1-12M9 7V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-white"
                x-text="Number(summaryToday.total_weight || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})">
            </p>
        </div>

        <div
            class="bg-gradient-to-br from-sky-500 to-sky-600 p-4 rounded-lg text-center flex justify-between items-center ">
            <div class="text-white flex-col justify-center items-center gap-1">
                <p class="text-sm font-semibold mb-2 ">co2e (kgCO2e)</p>
                <div class="w-10 h-10 p-2 rounded-full text-sky-500 bg-white flex justify-center items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
                        <path fill="currentColor"
                            d="M16 2a14 14 0 0 0-1.474 27.922c.07-.612.221-1.278.452-1.972l-.04-.003A11.92 11.92 0 0 1 7.91 24.84c-.16-1.13-.41-3.17.39-4.24a2 2 0 0 1 1.7-.92a2.62 2.62 0 0 0 2-1.13a3.64 3.64 0 0 0 .16-3.11c-.26-1-.4-1.67.1-2.34a4.5 4.5 0 0 1 1.23-.53l.035-.012c1.266-.428 2.969-1.005 3.405-2.828c.547-2.277-.357-3.923-1.191-5.443l-.059-.107l-.05-.18H16c2.263 0 4.48.646 6.39 1.86a21.7 21.7 0 0 1-3.76 4.4c-1.38 1.25-.37 2.69.3 3.64a4 4 0 0 1 1.073 2.588a11.4 11.4 0 0 1 1.968-.64a6.2 6.2 0 0 0-1.401-3.078a3.8 3.8 0 0 1-.6-1a23.3 23.3 0 0 0 4-4.67a11.95 11.95 0 0 1 4.003 8.592c.71.1 1.39.229 2.027.374V16A14 14 0 0 0 16 2m-2.51 2.27c.16.31.32.6.49.9l.027.05c.75 1.338 1.398 2.499.973 4.05c-.17.68-1 1-2.14 1.4a4.3 4.3 0 0 0-2.14 1.23a4.31 4.31 0 0 0-.43 4c.19.74.3 1.2.1 1.5c-.142.214-.158.214-.347.222c-.076.003-.18.008-.333.028a3.94 3.94 0 0 0-3 1.7a5.3 5.3 0 0 0-.93 2.84A11.85 11.85 0 0 1 4 16a12 12 0 0 1 9.49-11.73m4.887 23.035C19.15 28.114 20.557 29 23 29c4.294 0 5.638-5.53 6.249-8.042c.1-.414.18-.746.251-.958c.152-.454.543-.79.982-1.044c.69-.396.706-1.04-.064-1.242c-3.629-.951-9.03-1.482-12.418 1.905c-1.404 1.404-1.382 3.244-1.093 4.587q.087-.122.178-.245c1.574-2.13 3.879-4.11 6.92-5.168a.75.75 0 0 1 .493 1.416c-2.71.943-4.78 2.713-6.207 4.644c-1.424 1.928-2.17 3.967-2.291 5.422a.75.75 0 0 0 1.497.033c.07-.907.36-1.95.88-3.002"
                            stroke-width="0.2" stroke="currentColor" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-white"
                x-text="Number(summaryToday.total_co2e || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})">
            </p>
        </div>
    </div>

    <!-- All Time -->
    <div x-show="statTab === 'all'" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        class="grid grid-cols-2 md:grid-cols-4 gap-4" x-cloak style="display: none;">
        <div
            class="bg-gradient-to-br from-emerald-500 to-emerald-600 p-4 rounded-lg text-center flex justify-between items-center ">
            <div class="text-white flex-col justify-center items-center gap-1">
                <p class="text-sm font-semibold mb-2 ">แต้มคงเหลือ</p>
                <div class="w-10 h-10 p-2 rounded-full text-emerald-500 bg-white flex justify-center items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="m8.85 16.825l3.15-1.9l3.15 1.925l-.825-3.6l2.775-2.4l-3.65-.325l-1.45-3.4l-1.45 3.375l-3.65.325l2.775 2.425zm3.15.45l-4.15 2.5q-.275.175-.575.15t-.525-.2t-.35-.437t-.05-.588l1.1-4.725L3.775 10.8q-.25-.225-.312-.513t.037-.562t.3-.45t.55-.225l4.85-.425l1.875-4.45q.125-.3.388-.45t.537-.15t.537.15t.388.45l1.875 4.45l4.85.425q.35.05.55.225t.3.45t.038.563t-.313.512l-3.675 3.175l1.1 4.725q.075.325-.05.588t-.35.437t-.525.2t-.575-.15zm0-5.025"
                            stroke-width="0.5" stroke="currentColor" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-white" x-text="Number(faculty.faculty_point || 0).toLocaleString()"></p>
        </div>
        <div
            class="bg-gradient-to-br from-purple-500 to-purple-600 p-4 rounded-lg text-center flex justify-between items-center">
            <div class="text-white flex-col justify-center items-center gap-1">
                <p class="text-sm font-semibold mb-2 ">แต้มที่จ่ายไป</p>
                <div class="w-10 h-10 p-2 rounded-full text-purple-500 bg-white flex justify-center items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 14 14">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            d="M9.568 1.255a.466.466 0 0 1 .864 0l.587 1.433l1.593.14c.416.036.578.56.255.824l-.947.778a.47.47 0 0 0-.16.46l.314 1.452a.466.466 0 0 1-.715.486L10 5.92l-1.359.91a.466.466 0 0 1-.715-.487L8.24 4.89a.47.47 0 0 0-.16-.459l-.947-.778a.466.466 0 0 1 .255-.825l1.593-.14zM.983 6.37l.692-.043a8 8 0 0 1 2.448.227l1.16.292a1.32 1.32 0 0 1 .99 1.416v0c-.078.765-.79 1.3-1.546 1.166L3.622 9.23l3.897.699l4.037-.958a1.24 1.24 0 0 1 1.482.887v0c.16.603-.153 1.23-.73 1.465l-3.23 1.311a6.93 6.93 0 0 1-4.918.113L.813 11.562"
                            stroke-width="1.5" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-white" x-text="Number(summary.total_spend_point || 0).toLocaleString()">
            </p>
        </div>
        <div
            class="bg-gradient-to-br from-amber-500 to-amber-600 p-4 rounded-lg text-center flex justify-between items-center">
            <div class="text-white flex-col justify-center items-center gap-1">
                <p class="text-sm font-semibold mb-2 ">น้ำหนักรวม (กก.)</p>
                <div class="w-10 h-10 p-2 rounded-full text-amber-500 bg-white flex justify-center items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 7h16m-10 4v6m4-6v6M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2l1-12M9 7V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-white"
                x-text="Number(summary.total_weight || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})">
            </p>
        </div>

        <div
            class="bg-gradient-to-br from-sky-500 to-sky-600 p-4 rounded-lg text-center flex justify-between items-center">
            <div class="text-white flex-col justify-center items-center gap-1">
                <p class="text-sm font-semibold mb-2 ">co2e (kgCO2e)</p>
                <div class="w-10 h-10 p-2 rounded-full text-sky-500 bg-white flex justify-center items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
                        <path fill="currentColor"
                            d="M16 2a14 14 0 0 0-1.474 27.922c.07-.612.221-1.278.452-1.972l-.04-.003A11.92 11.92 0 0 1 7.91 24.84c-.16-1.13-.41-3.17.39-4.24a2 2 0 0 1 1.7-.92a2.62 2.62 0 0 0 2-1.13a3.64 3.64 0 0 0 .16-3.11c-.26-1-.4-1.67.1-2.34a4.5 4.5 0 0 1 1.23-.53l.035-.012c1.266-.428 2.969-1.005 3.405-2.828c.547-2.277-.357-3.923-1.191-5.443l-.059-.107l-.05-.18H16c2.263 0 4.48.646 6.39 1.86a21.7 21.7 0 0 1-3.76 4.4c-1.38 1.25-.37 2.69.3 3.64a4 4 0 0 1 1.073 2.588a11.4 11.4 0 0 1 1.968-.64a6.2 6.2 0 0 0-1.401-3.078a3.8 3.8 0 0 1-.6-1a23.3 23.3 0 0 0 4-4.67a11.95 11.95 0 0 1 4.003 8.592c.71.1 1.39.229 2.027.374V16A14 14 0 0 0 16 2m-2.51 2.27c.16.31.32.6.49.9l.027.05c.75 1.338 1.398 2.499.973 4.05c-.17.68-1 1-2.14 1.4a4.3 4.3 0 0 0-2.14 1.23a4.31 4.31 0 0 0-.43 4c.19.74.3 1.2.1 1.5c-.142.214-.158.214-.347.222c-.076.003-.18.008-.333.028a3.94 3.94 0 0 0-3 1.7a5.3 5.3 0 0 0-.93 2.84A11.85 11.85 0 0 1 4 16a12 12 0 0 1 9.49-11.73m4.887 23.035C19.15 28.114 20.557 29 23 29c4.294 0 5.638-5.53 6.249-8.042c.1-.414.18-.746.251-.958c.152-.454.543-.79.982-1.044c.69-.396.706-1.04-.064-1.242c-3.629-.951-9.03-1.482-12.418 1.905c-1.404 1.404-1.382 3.244-1.093 4.587q.087-.122.178-.245c1.574-2.13 3.879-4.11 6.92-5.168a.75.75 0 0 1 .493 1.416c-2.71.943-4.78 2.713-6.207 4.644c-1.424 1.928-2.17 3.967-2.291 5.422a.75.75 0 0 0 1.497.033c.07-.907.36-1.95.88-3.002"
                            stroke-width="0.2" stroke="currentColor" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-white"
                x-text="Number(summary.total_co2e || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})">
            </p>
        </div>
    </div>

    <!-- Members List -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-slate-900"
                x-text="`ผู้ดูแล/เจ้าหน้าที่ใน ${faculty?.faculty_name || ''}`"></h2>
            <button @click="openCreateMemberDialog()"
                class="bg-emerald-500 shadow cursor-pointer text-white px-4 py-2 rounded-lg hover:bg-emerald-600 hover:scale-105 active:scale-95 transition font-medium flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                        clip-rule="evenodd" />
                </svg>
                เพิ่มผู้ดูแล/เจ้าหน้าที่
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white  border border-gray-200 ">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ลำดับ
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition select-none"
                            @click="sortMembers('name')">ชื่อ <span x-show="memberSortBy === 'name'"
                                x-text="memberSortOrder === 'ASC' ? '↑' : '↓'"></span></th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">บทบาท
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <template x-for="(member, index) in members" :key="member.member_id">
                        <tr class="hover:bg-gray-50 transition cursor-pointer"
                            @click="window.open(`/${manager_role}/manage/members/detail/${member.member_id}`, '_blank')">
                            <td class="px-2 py-1 whitespace-nowrap text-xs text-gray-500"
                                x-text="(memberPage - 1) * memberLimit + index + 1"></td>
                            <td class="px-2 py-1 whitespace-nowrap text-xs font-medium text-gray-900">
                                <span x-text="member.member_name"></span>
                            </td>
                            <td class="px-2 py-1 whitespace-nowrap text-xs text-gray-500">
                                <span x-text="member.role_name_th || '-'"></span>
                            </td>
                            <td class="px-2 py-2 whitespace-nowrap text-center text-xs" @click.stop>
                                <div class="flex justify-center items-center gap-1"
                                    x-show="currentUserRoleId === 1 || (member.role_id != 1 && member.role_id != 4)">
                                    <button @click.stop="startEditMember(member)"
                                        class="bg-gradient-to-br from-amber-400 to-amber-500 p-2 text-white hover:bg-gradient-to-br hover:from-amber-500 hover:to-amber-600 hover:scale-105 cursor-pointer rounded-md"
                                        title="แก้ไข">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button @click.stop="confirmDeleteMember(member)"
                                        class="bg-gradient-to-br from-red-400 to-red-500 p-2 text-white hover:bg-gradient-to-br hover:from-red-600 hover:to-red-700 hover:scale-105 cursor-pointer rounded-md"
                                        title="ลบ">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-if="members.length === 0">
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">ไม่มีข้อมูลสมาชิก</td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        <div class="flex items-center justify-between mt-4 text-sm">
            <button class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 disabled:opacity-50 transition"
                :disabled="memberPage <= 1" @click="memberPage--; fetchMembers()">ก่อนหน้า</button>
            <span class="text-gray-600">หน้า <span class="font-medium" x-text="memberPage"></span> จาก <span
                    class="font-medium" x-text="memberTotalPages"></span></span>
            <button class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 disabled:opacity-50 transition"
                :disabled="memberPage >= memberTotalPages" @click="memberPage++; fetchMembers()">ถัดไป</button>
        </div>
    </div>

    <!-- Waste Stock List -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <h2 class="text-xl font-bold text-slate-900 mb-6" x-text="`คลังขยะใน ${faculty?.faculty_name || ''}`">
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
            <div>
                <label for="stock_filter_search" class="block text-xs font-medium text-gray-700 mb-1">ค้นหา</label>
                <input type="text" id="stock_filter_search" x-model="stockFilters.search"
                    @input.debounce.500ms="handleStockFilterChange()" placeholder="ชื่อขยะ/รหัส"
                    class="w-full text-xs border border-gray-300 rounded px-2 py-1.5 bg-white focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                            รหัส</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                            ชื่อขยะ</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                            หมวดหมู่</th>
                        <th
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                            น้ำหนัก (กก.)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <template x-for="stock in stocks" :key="stock.waste_type_id">
                        <tr class="hover:bg-emerald-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="stock.waste_type_id">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                x-text="stock.waste_type_name"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                x-text="stock.waste_category_name"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right"
                                x-text="parseFloat(stock.stock_weight).toFixed(3)"></td>
                        </tr>
                    </template>
                    <template x-if="stocks.length === 0">
                        <tr>
                            <td colspan="4" class="text-center py-4">ไม่พบข้อมูลในคลัง</td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between mt-4 text-xs">
            <span class="text-gray-600" x-text="`ทั้งหมด ${stockTotal} รายการ`"></span>
        </div>
    </div>


    <!-- Modals -->
    <!-- Edit Faculty Modal -->
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30" @click.self="facultyDialogShow = false"
        x-show="facultyDialogShow" x-cloak>
        <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md border border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 mb-4">แก้ไขข้อมูลหน่่วยย่อย</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อหน่่วยย่อย <span
                            class="text-red-500">*</span></label>
                    <input type="text" x-model="facultyForm.faculty_name"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">รหัสย่อ</label>
                    <input type="text" x-model="facultyForm.faculty_code"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button @click="facultyDialogShow = false"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">ยกเลิก</button>
                <button @click="submitFacultyForm"
                    class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition">บันทึก</button>
            </div>
        </div>
    </div>

    <!-- Create Member Modal -->
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30"
        @click.self="createUserDialogShow = false" x-show="createUserDialogShow" x-cloak>
        <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md border border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 mb-4">เพิ่มเจ้าหน้าที่/ผู้ดูแลใหม่</h3>
            <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">บทบาท <span
                            class="text-red-500">*</span></label>
                    <select x-model="createUserForm.role_id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none bg-white">
                        <option value="">เลือกบทบาท</option>
                        <option value="4">เจ้าหน้าทีหน่วยย่อย</option>
                        <option value="1">ผู้ดูแลระบบ</option>
                    </select>
                    <span x-show="errors.create.role_id" class="text-red-500 text-xs">กรุณาเลือกบทบาท</span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">เบอร์โทรศัพท์ <span
                            class="text-red-500">*</span></label>
                    <input type="text" x-model="createUserForm.member_phone"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                    <span x-show="errors.create.member_phone"
                        class="text-red-500 text-xs">กรุณากรอกหมายเลขโทรศัพท์</span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">รหัสผ่าน <span
                            class="text-red-500">*</span></label>
                    <input type="password" x-model="createUserForm.member_password"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                    <span x-show="errors.create.member_password" class="text-red-500 text-xs">กรุณากรอกรหัสผ่าน</span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">รหัสประจำตัว</label>
                    <input type="text" x-model="createUserForm.member_personal_id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">อีเมล</label>
                    <input type="email" x-model="createUserForm.member_email"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อที่ใช้แสดงผล</label>
                    <input type="text" x-model="createUserForm.member_name"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button @click="createUserDialogShow = false"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">ยกเลิก</button>
                <button @click="submitCreateMember"
                    class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition">เพิ่มผู้ดูแล/เจ้าหน้าที่</button>
            </div>
        </div>
    </div>

    <!-- Edit Member Modal -->
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30" @click.self="cancelEditMember()"
        x-show="editMemberDialogShow" x-cloak>
        <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md border border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 mb-4">แก้ไขผู้ดูแล/เจ้าหน้าที่</h3>
            <div class="space-y-2 max-h-[60vh] overflow-y-auto pr-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">บทบาท <span
                            class="text-red-500">*</span></label>
                    <select x-model="editUserForm.role_id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-1 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none bg-white">
                        <option value="">เลือกบทบาท</option>
                        <option value="4">เจ้าหน้าที่หน่วยย่อย</option>
                        <option value="1">ผู้ดูแลระบบ</option>
                    </select>
                    <span x-show="errors.edit.role_id" class="text-red-500 text-xs">กรุณาเลือกบทบาท</span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">เบอร์โทรศัพท์ <span
                            class="text-red-500">*</span></label>
                    <input type="text" x-model="editUserForm.member_phone"
                        class="w-full border border-gray-300 rounded-lg px-3 py-1 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                    <span x-show="errors.edit.member_phone" class="text-red-500 text-xs">กรุณากรอกหมายเลขโทรศัพท์</span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">รหัสประจำตัว</label>
                    <input type="text" x-model="editUserForm.member_personal_id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-1 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">อีเมล</label>
                    <input type="email" x-model="editUserForm.member_email"
                        class="w-full border border-gray-300 rounded-lg px-3 py-1 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อที่ใช้แสดงผล</label>
                    <input type="text" x-model="editUserForm.member_name"
                        class="w-full border border-gray-300 rounded-lg px-3 py-1 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                </div>

            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button @click="cancelEditMember()"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">ยกเลิก</button>
                <button @click="saveEditMember"
                    class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition">บันทึกข้อมูล</button>
            </div>
        </div>
    </div>

</div>

<script>
    function FacultyDetailManager() {
        return {
            manager_role: "<?= $user->role_name ?>",
            currentUserRoleId: <?= (int) ($user->role_id ?? 0) ?>,
            facultyId: <?= (int) $faculty_id; ?>,
            faculty: {},

            summary: {},
            summaryToday: {},
            statTab: 'today',

            members: [],
            memberPage: 1,
            memberLimit: 10,
            memberTotalPages: 1,
            memberSortBy: '',
            memberSortOrder: 'DESC',

            stocks: [],
            stockPage: 1,
            stockLimit: 10,
            stockTotalPages: 1,
            stockTotal: 0,
            stockFilters: {
                search: "",
            },

            facultyDialogShow: false,
            facultyForm: { faculty_name: '', faculty_code: '' },

            createUserDialogShow: false,
            editMemberDialogShow: false,
            editingMemberId: null,
            selectedUser: null,
            errors: { create: {}, edit: {} },

            createUserForm: {
                member_personal_id: "",
                member_phone: "",
                member_name: "",
                member_email: "",
                member_password: "",
                faculty_id: <?= $faculty_id; ?>,
                role_id: "",
            },
            editUserForm: {
                member_personal_id: "",
                member_phone: "",
                member_name: "",
                member_email: "",
                faculty_id: <?= $faculty_id; ?>,
                role_id: "",
            },

            async initData() {
                if (!this.facultyId) {
                    Swal.fire('ข้อผิดพลาด', 'ไม่พบข้อมูลหน่่วยย่อย', 'error').then(() => { window.history.back(); });
                    return;
                }

                await Promise.all([
                    this.fetchFaculty(),
                    this.fetchMembers(),
                    this.fetchDashboard(),
                    this.fetchStock()
                ]);
            },

            async fetchDashboard() {
                try {
                    const res = await fetch(`/api/dashboards/faculty/${this.facultyId}`);
                    const result = await res.json();
                    if (result.success) {
                        this.summary = result.data.summary || {};
                        this.summaryToday = result.data.summary_today || {};
                    }
                } catch (e) {
                    console.error('Failed to fetch dashboard:', e);
                }
            },

            async fetchFaculty() {
                try {
                    const res = await fetch(`/api/faculties/${this.facultyId}`);
                    const result = await res.json();
                    if (result.success) {
                        this.faculty = result.data;
                    } else throw new Error(result.message);
                } catch (e) {
                    Swal.fire('ข้อผิดพลาด', 'ไม่สามารถโหลดข้อมูลหน่่วยย่อยได้', 'error');
                }
            },

            async fetchMembers() {
                try {
                    const params = new URLSearchParams({
                        faculty: this.facultyId,
                        page: this.memberPage,
                        limit: this.memberLimit
                    });
                    if (this.memberSortBy) {
                        params.append('sort_by', this.memberSortBy);
                        params.append('order', this.memberSortOrder);
                    }
                    const res = await fetch(`/api/members?${params.toString()}`);
                    const result = await res.json();

                    if (result.success || result.data) {
                        this.members = result.data || result.result?.data || [];
                        const total = result.total || result.result?.total || 0;
                        this.memberTotalPages = Math.ceil(total / this.memberLimit) || 1;
                    }
                } catch (e) {
                    console.error('Failed to fetch members:', e);
                }
            },

            sortMembers(column) {
                if (this.memberSortBy === column) {
                    this.memberSortOrder = this.memberSortOrder === 'ASC' ? 'DESC' : 'ASC';
                } else {
                    this.memberSortBy = column;
                    this.memberSortOrder = 'DESC';
                }
                this.memberPage = 1;
                this.fetchMembers();
            },

            async fetchStock() {
                try {
                    const params = new URLSearchParams();
                    params.append("page", this.stockPage);
                    params.append("limit", this.stockLimit);
                    if (this.stockFilters.search) {
                        params.append("search", this.stockFilters.search);
                    }

                    const res = await fetch(`/api/faculty_stock/${this.facultyId}?${params.toString()}`);
                    let result = await res.json();

                    if (result.success) {
                        this.stocks = result.data;
                        this.stockTotal = result.total;
                        this.stockTotalPages = Math.ceil(result.total / this.stockLimit);
                    }

                } catch (err) {
                    console.error("Failed to load waste stock", err);
                    Swal.fire('ข้อผิดพลาด', 'โหลดข้อมูลคลังขยะล้มเหลว', 'error');
                }
            },

            handleStockFilterChange() {
                this.stockPage = 1;
                this.fetchStock();
            },
            // -- Faculty actions --
            openEditFacultyDialog() {
                this.facultyForm = {
                    faculty_name: this.faculty.faculty_name || '',
                    faculty_code: this.faculty.faculty_code || ''
                };
                this.facultyDialogShow = true;
            },

            async submitFacultyForm() {
                if (!this.facultyForm.faculty_name) {
                    Swal.fire('แจ้งเตือน', 'กรุณากรอกชื่อหน่่วยย่อย', 'warning');
                    return;
                }
                try {
                    const res = await fetch(`/api/faculties/update/${this.facultyId}`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(this.facultyForm)
                    });
                    const result = await res.json();
                    if (result.success) {
                        Swal.fire({ icon: 'success', title: 'สำเร็จ', text: 'บันทึกข้อมูลหน่่วยย่อยแล้ว', timer: 1500, showConfirmButton: false });
                        this.facultyDialogShow = false;
                        this.fetchFaculty();
                    } else throw new Error(result.message);
                } catch (e) {
                    console.log(this.facultyForm);

                    console.error('Failed to submit faculty form:', e);
                    Swal.fire('ข้อผิดพลาด', e.message || 'ไม่สามารถบันทึกข้อมูลได้', 'error');
                }
            },

            // -- Member actions --
            openCreateMemberDialog() {
                this.createUserForm = {
                    member_personal_id: "",
                    member_phone: "",
                    member_name: "",
                    member_email: "",
                    member_password: "",
                    faculty_id: this.facultyId,
                    role_id: "",
                };
                this.errors.create = {};
                this.createUserDialogShow = true;
            },

            startEditMember(user) {
                this.selectedUser = user;
                this.editingMemberId = user.member_id;
                this.editUserForm = {
                    member_personal_id: user.member_personal_id ?? "",
                    member_phone: user.member_phone ?? null,
                    member_name: user.member_name ?? null,
                    member_email: user.member_email ?? null,
                    faculty_id: this.facultyId,
                    role_id: user.role_id ? parseInt(user.role_id) : "",
                };
                this.errors.edit = {};
                this.editMemberDialogShow = true;
            },

            cancelEditMember() {
                this.editingMemberId = null;
                this.editMemberDialogShow = false;
            },

            validateMemberForm(formType) {
                let isValid = true;
                const errors = {};
                const form = formType === "create" ? this.createUserForm : this.editUserForm;

                if (!form.member_phone) {
                    errors.member_phone = true;
                    isValid = false;
                }
                if (!form.role_id) {
                    errors.role_id = true;
                    isValid = false;
                }
                if (formType === "create" && !form.member_password) {
                    errors.member_password = true;
                    isValid = false;
                }

                this.errors[formType] = errors;
                return isValid;
            },

            async submitCreateMember() {
                if (!this.validateMemberForm("create")) {
                    Swal.fire('แจ้งเตือน', 'กรุณากรอกข้อมูลในช่องที่มีเครื่องหมายดอกจัน (*) ให้ครบ', 'warning');
                    return;
                }
                try {
                    const res = await fetch(`/api/members`, {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify(this.createUserForm),
                    });
                    const response = await res.json();
                    if (response.success) {
                        Swal.fire({ icon: "success", title: "เพิ่มสำเร็จ", text: `เพิ่มผู้ดูแล/เจ้าหน้าที่เรียบร้อย`, timer: 2000, showConfirmButton: false });
                        this.createUserDialogShow = false;
                        this.fetchMembers();
                        this.fetchFaculty();
                    } else throw new Error(response.message || "Something went wrong");
                } catch (error) {
                    Swal.fire('ข้อผิดพลาด', error.message || 'ไม่สามารถเพิ่มผู้ดูแล/เจ้าหน้าที่ได้', 'error');
                }
            },

            async saveEditMember() {
                if (!this.validateMemberForm("edit")) {
                    Swal.fire('แจ้งเตือน', 'กรุณากรอกข้อมูลในช่องที่มีเครื่องหมายดอกจัน (*) ให้ครบ', 'warning');
                    return;
                }
                try {
                    const res = await fetch(`/api/members/update/${this.editingMemberId}`, {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify(this.editUserForm),
                    });
                    const response = await res.json();
                    if (response.success) {
                        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'แก้ไขข้อมูลเรียบร้อย', showConfirmButton: false, timer: 1500 });
                        this.editingMemberId = null;
                        this.editMemberDialogShow = false;
                        this.fetchMembers();
                        this.fetchFaculty();
                    } else throw new Error(response.message || "Something went wrong");
                } catch (error) {
                    Swal.fire('ข้อผิดพลาด', error.message || 'ไม่สามารถแก้ไขข้อมูลได้', 'error');
                }
            },

            async confirmDeleteMember(member) {
                const result = await Swal.fire({
                    title: "ยืนยันการลบ",
                    html: `<p>ต้องการลบผู้ใช้ <span class="text-red-500 font-semibold">"${member.member_name || member.member_phone}"</span> ใช่หรือไม่?</p>`,
                    icon: "warning",
                    showConfirmButton: true,
                    confirmButtonText: "ยืนยันการลบ",
                    confirmButtonColor: "#d33",
                    showCancelButton: true,
                    cancelButtonText: "ยกเลิก"
                });

                if (result.isConfirmed) {
                    try {
                        const deleteRes = await fetch("/api/members/delete", {
                            method: "POST",
                            headers: { "Content-Type": "application/json" },
                            body: JSON.stringify({ member_ids: [member.member_id] }),
                        });
                        const delResult = await deleteRes.json();
                        if (delResult.success) {
                            Swal.fire({ icon: "success", title: "ลบสำเร็จ", timer: 2000, showConfirmButton: false });
                            this.fetchMembers();
                            this.fetchFaculty();
                        } else throw new Error(delResult.message);
                    } catch (error) {
                        Swal.fire("ผิดพลาด", error.message || "ลบรายชื่อไม่สำเร็จ", "error");
                    }
                }
            },

            openWasteDeposit(member) {
                window.open(`/${this.manager_role}/transactions/waste?member_id=${member.member_id}`, '_blank');
            },

            openRedeemReward(member) {
                window.open(`/${this.manager_role}/transactions/redeem_item?member_id=${member.member_id}`, '_blank');
            },

            openDonation(member) {
                window.open(`/${this.manager_role}/transactions/donation?member_id=${member.member_id}`, '_blank');
            }
        }
    }
</script>