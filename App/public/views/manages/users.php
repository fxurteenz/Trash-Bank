<?php
// ไฟล์นี้ใช้สำหรับเจ้าหน้าที่คณะจัดการสมาชิกเท่านั้น
$faculty_id = (int) $user->faculty_id ?? "null";
?>

<div x-data="UserTable()" x-init="initData()" class="space-y-4 w-full">
    <!-- Top Card -->
    <div id="role-counts-container" class="w-full grid grid-cols-3 gap-2">
        <div @click="filterByRole('')"
            class="bg-white rounded-md shadow p-6 col-span-2 md:col-span-4 xl:col-span-1 cursor-pointer hover:scale-105 active:scale-95 duration-300 ease-out hover:shadow-md hover:shadow-emerald-500/50 transition-all"
            :class="filters.role === '' ? 'ring-2 ring-emerald-500' : ''">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">ผู้ใช้ทั้งหมด</p>
                    <p class="text-2xl font-bold text-emerald-700"
                        x-text="Number(roleCounts.total_members || 0).toLocaleString()"></p>
                </div>
                <div class="p-3 bg-emerald-100 rounded-full text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0-4 0m-2 8v-1a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v1M15 5a2 2 0 1 0 4 0a2 2 0 0 0-4 0m2 5h2a2 2 0 0 1 2 2v1M5 5a2 2 0 1 0 4 0a2 2 0 0 0-4 0m-2 8v-1a2 2 0 0 1 2-2h2" />
                    </svg>
                </div>
            </div>
        </div>
        <div @click="filterByRole(roleCounts.roles[1]?.role_id)"
            class="bg-white rounded-md shadow p-6 col-span-1 cursor-pointer hover:scale-105 hover:shadow-md hover:shadow-emerald-500/50 active:scale-95 duration-300 ease-out transition-all"
            :class="filters.role === roleCounts.roles[1]?.role_id ? 'ring-2 ring-emerald-500' : ''">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600" x-text="roleCounts.roles[1]?.role_name_th"></p>
                    <p class="text-2xl font-bold text-emerald-700"
                        x-text="Number(roleCounts.roles[1]?.member_count || 0).toLocaleString()">
                    </p>
                </div>
                <div class="p-3 bg-emerald-100 rounded-full text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0-8 0M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2" />
                    </svg>
                </div>
            </div>
        </div>
        <div @click="filterByRole(roleCounts.roles[2]?.role_id)"
            class="bg-white rounded-md shadow p-6 col-span-1 cursor-pointer hover:scale-105 hover:shadow-md hover:shadow-emerald-500/50 active:scale-95 duration-300 ease-out transition-all"
            :class="filters.role === roleCounts.roles[2]?.role_id ? 'ring-2 ring-emerald-500' : ''">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600" x-text="roleCounts.roles[2]?.role_name_th"></p>
                    <p class="text-2xl font-bold text-emerald-700"
                        x-text="Number(roleCounts.roles[2]?.member_count || 0).toLocaleString()">
                    </p>
                </div>
                <div class="p-3 bg-emerald-100 rounded-full text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0-8 0M6 21v-2a4 4 0 0 1 4-4h.5m7.5 7l3.35-3.284a2.143 2.143 0 0 0 .005-3.071a2.24 2.24 0 0 0-3.129-.006l-.224.22l-.223-.22a2.24 2.24 0 0 0-3.128-.006a2.143 2.143 0 0 0-.006 3.071z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-md shadow p-6 overflow-x-auto w-full">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">รายชื่อผู้ใช้งาน</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-4 mb-4 p-4 bg-gray-50 rounded-lg">
            <div class="items-center flex gap-2 justify-center">
                <button @click="openCreateDialog" :class="createUserDialogShow && 'bg-emerald-300'"
                    class="group cursor-pointer flex items-center justify-between py-1 px-2 border-2 border-emerald-500 rounded-full hover:bg-emerald-100 space-x-1 hover:scale-105 font-medium text-emerald-700">
                    <div class="group-hover:rotate-90 duration-300" title="Add New">
                        <svg class="stroke-teal-500 fill-none group-active:duration-0 duration-300" viewBox="0 0 24 24"
                            height="24" width="24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-width="1.5"
                                d="M12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22Z">
                            </path>
                            <path stroke-width="1.5" d="M8 12H16"></path>
                            <path stroke-width="1.5" d="M12 16V8"></path>
                        </svg>
                    </div>
                    <span>เพิ่ม</span>
                </button>
                <button
                    class="group cursor-pointer disabled:cursor-not-allowed flex items-center justify-between py-1 px-2 border-2 border-red-500 rounded-full hover:bg-red-100 space-x-1 hover:scale-105 font-medium text-red-700 disabled:opacity-50"
                    @click="deleteCheckedUser" :disabled="checkedMembers.member_ids.length === 0">
                    <div :class="[checkedMembers.member_ids.length === 0 ? 'opacity-100' : 'opacity-100 group-hover:rotate-90']"
                        class="duration-300 transition-transform">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 16 16"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 8h8m2.5 0a6.5 6.5 0 1 1-13 0a6.5 6.5 0 0 1 13 0Z" stroke-width="1.5" />
                        </svg>
                    </div>
                    <span class="select-none">ลบ</span>
                </button>
            </div>
            <div>
                <label for="filter_search" class="block text-xs font-medium text-gray-700 mb-1">ค้นหารายชื่อ</label>
                <input type="text" id="filter_search" x-model="filters.search"
                    @input.debounce.500ms="handleFilterChange()" placeholder="ชื่อ/รหัส/เบอร์โทร"
                    class="w-full text-xs border border-gray-300 rounded px-2 py-1.5 bg-white focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="filter_major" class="block text-xs font-medium text-gray-700 mb-1">สาขา</label>
                <select id="filter_major" x-model="filters.major_id" @change="handleFilterChange()"
                    class="bg-white border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 hover:cursor-pointer block w-full p-1"
                    :disabled="!facultyId">
                    <option value="">ทุกสาขา</option>
                    <template x-for="major in filterMajors" :key="major.major_id">
                        <option :value="major.major_id" x-text="major.major_name"></option>
                    </template>
                </select>
            </div>
            <div>
                <label for="filter_role" class="block text-xs font-medium text-gray-700 mb-1">บทบาท</label>
                <select id="filter_role" x-model="filters.role" @change="handleFilterChange()"
                    class="bg-white border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 hover:cursor-pointer block w-full p-1">
                    <option value="">ทุกบทบาท</option>
                    <template x-for="role in roleCounts.roles" :key="role.role_id">
                        <option :value="role.role_id" x-text="role.role_name_th"></option>
                    </template>
                </select>
            </div>
            <div class="flex justify-center items-center">
                <button @click="resetFilters()"
                    class="text-sm text-gray-500 hover:scale-105 cursor-pointer bg-sky-400 px-4 py-2 rounded text-white font-semibold duration-200">ล้างตัวกรอง</button>
            </div>
        </div>

        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                            ลำดับ
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                            เลือก
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                            เบอร์โทรศัพท์
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b cursor-pointer hover:bg-gray-200 transition select-none"
                            @click="sortMembers('name')">
                            ชื่อ <span x-show="filters.sort_by === 'name'"
                                x-text="filters.order === 'ASC' ? '↑' : '↓'"></span>
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b cursor-pointer hover:bg-gray-200 transition select-none">
                            ทางลัด
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b hidden lg:table-cell">
                            บทบาท
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b cursor-pointer hover:bg-gray-200 transition select-none"
                            @click="sortMembers('waste_point')">
                            แต้มขยะ <span x-show="filters.sort_by === 'waste_point'"
                                x-text="filters.order === 'ASC' ? '↑' : '↓'"></span>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b cursor-pointer hover:bg-gray-200 transition select-none"
                            @click="sortMembers('goodness_point')">
                            แต้มความดี <span x-show="filters.sort_by === 'goodness_point'"
                                x-text="filters.order === 'ASC' ? '↑' : '↓'"></span>
                        </th>
                        <th
                            class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                            <span class="font-normal " x-text="`หน้า ${page} จาก ${totalPages}`">
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <template x-for="(member, index) in members" :key="member.member_id">
                        <tr @click="window.open(`/${manager_role}/manage/members/detail/${member.member_id}`, '_blank')"
                            class="hover:bg-gray-100 cursor-pointer transition-all"
                            :class="editUserForm && editUserForm.member_id == member.member_id ? 'bg-emerald-100' : ''">
                            <td class="px-2 py-2 text-center text-xs text-gray-500"
                                x-text="(page - 1) * limit + index + 1"></td>
                            <td class="px-2 py-2 text-center" @click.stop>
                                <input type="checkbox" class="p-1" :id="member.member_id" :value="member.member_id"
                                    x-model="checkedMembers.member_ids">
                            </td>
                            <td class="px-2 py-2 overflow-hidden text-ellipsis text-xs"
                                x-text="member.member_phone ?? 'ไม่ระบุ'"></td>

                            <td class="px-2 py-2 overflow-hidden text-ellipsis text-xs">
                                <div class="flex items-center gap-1">
                                    <svg x-show="member.role_id == 3" class="text-emerald-600" title="เจ้าหน้าที่คณะ"
                                        xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                        <path fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2"
                                            d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0-8 0M6 21v-2a4 4 0 0 1 4-4h.5m7.5 7l3.35-3.284a2.143 2.143 0 0 0 .005-3.071a2.24 2.24 0 0 0-3.129-.006l-.224.22l-.223-.22a2.24 2.24 0 0 0-3.128-.006a2.143 2.143 0 0 0-.006 3.071z" />
                                    </svg>
                                    <span x-text="member.member_name ?? 'ไม่มีชื่อ'"></span>
                                </div>
                            </td>
                            <td class="px-2 py-2 overflow-hidden text-ellipsis text-xs">

                                <!-- Quick Action Menu Button -->
                                <button @click="openWasteDeposit(member)"
                                    class="bg-gradient-to-br from-emerald-500 to-emerald-600 p-2 text-white hover:bg-gradient-to-br hover:from-emerald-600 hover:to-emerald-700 hover:scale-105 cursor-pointer rounded-md"
                                    title="ฝากขยะ">
                                    <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" width="28"
                                        height="28" viewBox="0 0 24 24">
                                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.5">
                                            <path stroke-linejoin="round"
                                                d="M12 22c-.818 0-1.6-.325-3.163-.974C4.946 19.41 3 18.602 3 17.243V7.745M12 22c.818 0 1.6-.325 3.163-.974C19.054 19.41 21 18.602 21 17.243V7.745M12 22v-9.831M3 7.745c0 .603.802.985 2.405 1.747l2.92 1.39C10.13 11.74 11.03 12.17 12 12.17M3 7.745c0-.604.802-.986 2.405-1.748L7.5 5M21 7.745c0 .603-.802.985-2.405 1.747l-2.92 1.39C13.87 11.74 12.97 12.17 12 12.17m9-4.424c0-.604-.802-.986-2.405-1.748L16.5 5M6 13.152l2 .983" />
                                            <path
                                                d="M12.004 2v7m0 0c.263.004.522-.18.714-.405L14 7.062M12.004 9c-.254-.003-.511-.186-.714-.405L10 7.062" />
                                        </g>
                                    </svg>
                                </button>
                                <button @click="openRedeem(member)"
                                    class="bg-gradient-to-br from-yellow-500 to-yellow-600 p-2 text-white hover:bg-gradient-to-br hover:from-yellow-600 hover:to-yellow-700 hover:scale-105 cursor-pointer rounded-md"
                                    title="แลกของรางวัล">
                                    <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24">
                                        <path fill="currentColor"
                                            d="M11.3 8.3L9.2 6.2q-.3-.3-.3-.7t.3-.7l2.1-2.1q.3-.3.7-.3t.7.3l2.1 2.1q.3.3.3.7t-.3.7l-2.1 2.1q-.3.3-.7.3t-.7-.3M2 20q-.425 0-.712-.288T1 19v-3q0-.85.588-1.425T3 14h3.275q.5 0 .95.25t.725.675q.725.975 1.788 1.525T12 17q1.225 0 2.288-.55t1.762-1.525q.325-.425.763-.675t.912-.25H21q.85 0 1.425.575T23 16v3q0 .425-.288.713T22 20h-5q-.425 0-.712-.288T16 19v-1.275q-.875.625-1.888.95T12 19q-1.075 0-2.1-.337T8 17.7V19q0 .425-.288.713T7 20zm2-7q-1.25 0-2.125-.875T1 10q0-1.275.875-2.137T4 7q1.275 0 2.138.863T7 10q0 1.25-.862 2.125T4 13m16 0q-1.25 0-2.125-.875T17 10q0-1.275.875-2.137T20 7q1.275 0 2.138.863T23 10q0 1.25-.862 2.125T20 13"
                                            stroke-width="0.5" stroke="currentColor" />
                                    </svg>
                                </button>
                                <button @click="openDonation(member)"
                                    class="bg-gradient-to-br from-purple-400 to-purple-500 p-2 text-white hover:bg-gradient-to-br hover:from-purple-500 hover:to-purple-600 hover:scale-105 cursor-pointer rounded-md"
                                    title="บริจาคสิ่งของ">
                                    <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24">
                                        <path fill="currentColor"
                                            d="M4 21h9.62a4 4 0 0 0 3.037-1.397l5.102-5.952a1 1 0 0 0-.442-1.6l-1.968-.656a3.04 3.04 0 0 0-2.823.503l-3.185 2.547l-.617-1.235A3.98 3.98 0 0 0 9.146 11H4c-1.103 0-2 .897-2 2v6c0 1.103.897 2 2 2m0-8h5.146c.763 0 1.448.423 1.789 1.105l.447.895H7v2h6.014a1 1 0 0 0 .442-.11l.003-.001l.004-.002h.003l.002-.001h.004l.001-.001c.009.003.003-.001.003-.001c.01 0 .002-.001.002-.001h.001l.002-.001l.003-.001l.002-.001l.002-.001l.003-.001l.002-.001c.003 0 .001-.001.002-.001l.003-.002l.002-.001l.002-.001l.003-.001l.002-.001h.001l.002-.001h.001l.002-.001l.002-.001c.009-.001.003-.001.003-.001l.002-.001a1 1 0 0 0 .11-.078l4.146-3.317c.262-.208.623-.273.94-.167l.557.186l-4.133 4.823a2.03 2.03 0 0 1-1.52.688H4zM16 2h-.017c-.163.002-1.006.039-1.983.705c-.951-.648-1.774-.7-1.968-.704L12.002 2h-.004c-.801 0-1.555.313-2.119.878C9.313 3.445 9 4.198 9 5s.313 1.555.861 2.104l3.414 3.586a1.006 1.006 0 0 0 1.45-.001l3.396-3.568C18.688 6.555 19 5.802 19 5s-.313-1.555-.878-2.121A2.98 2.98 0 0 0 16.002 2zm1 3c0 .267-.104.518-.311.725L14 8.55l-2.707-2.843C11.104 5.518 11 5.267 11 5s.104-.518.294-.708A.98.98 0 0 1 11.979 4c.025.001.502.032 1.067.485q.121.098.247.222l.707.707l.707-.707q.126-.124.247-.222c.529-.425.976-.478 1.052-.484a1 1 0 0 1 .701.292c.189.189.293.44.293.707"
                                            stroke-width="0.5" stroke="currentColor" />
                                    </svg>
                                </button>
                            </td>
                            <td class="px-2 py-2 overflow-hidden text-ellipsis text-xs hidden lg:table-cell"
                                x-text="member.role_name_th || 'ไม่ระบุ'">
                            </td>
                            <td class="px-2 py-2 text-xs text-end"
                                x-text="Number(parseInt(member.member_waste_point) || 0).toLocaleString()"></td>
                            <td class="px-2 py-2 text-xs text-end"
                                x-text="Number(parseInt(member.member_goodness_point) || 0).toLocaleString()"></td>
                            <td class="px-2 py-2 whitespace-nowrap text-center text-sm" @click.stop>
                                <div class="flex justify-center items-center gap-1">
                                    <!-- Edit Button -->
                                    <button @click.stop="selectingRow(member)"
                                        class="bg-gradient-to-br from-amber-400 to-amber-500 p-2 text-white hover:bg-gradient-to-br hover:from-amber-500 hover:to-amber-600 hover:scale-105 cursor-pointer rounded-md"
                                        title="แก้ไข">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <!-- Delete Button -->
                                    <button @click.stop="confirmDeleteUser(member)"
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
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500 text-sm">
                                ไม่มีข้อมูลผู้ใช้งาน
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div class="flex flex-col md:flex-row items-center justify-between mt-4 text-xs gap-4">
            <div class="flex items-center gap-2">
                <span class="text-gray-600">แสดง</span>
                <select x-model="limit" @change="handleFilterChange()"
                    class="px-2 py-1 bg-gray-100 border border-gray-300 rounded outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="text-gray-600">รายการ</span>
            </div>

            <div class="flex items-center space-x-2">
                <button class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50"
                    :disabled="page <= 1" @click="page--; fetchMembers()">
                    ก่อนหน้า
                </button>
                <div class="flex items-center space-x-1">
                    <template x-for="p in totalPages">
                        <button class="px-2 py-1 rounded"
                            :class="p === page ? 'bg-emerald-500 text-white' : 'bg-gray-200 hover:bg-gray-300'"
                            @click="page = p; fetchMembers()" x-text="p"></button>
                    </template>
                </div>
                <button class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50"
                    :disabled="page >= totalPages" @click="page++; fetchMembers()">
                    ถัดไป
                </button>
            </div>
        </div>

        <dialog x-show="createUserDialogShow" x-ref="createUserDialog" @click.self="createUserDialogShow = false"
            @close="createUserDialogShow = false" class="fixed inset-0 mx-auto my-auto p-0 bg-transparent z-50"
            x-init="$watch('createUserDialogShow', value => {if (value) $refs.createUserDialog.showModal();else $refs.createUserDialog.close();})">
            <div class="bg-white rounded-md shadow p-6 w-96 max-w-full">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-bold text-lg">เพิ่มผู้ใช้งานใหม่</h3>
                    <svg class="cursor-pointer hover:scale-110 transition-transform"
                        @click="createUserDialogShow = false" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                        viewBox="0 0 32 32">
                        <path fill="currentColor"
                            d="M24.879 2.879A3 3 0 1 1 29.12 7.12l-8.79 8.79a.125.125 0 0 0 0 .177l8.79 8.79a3 3 0 1 1-4.242 4.243l-8.79-8.79a.125.125 0 0 0-.177 0l-8.79 8.79a3 3 0 1 1-4.243-4.242l8.79-8.79a.125.125 0 0 0 0-.177l-8.79-8.79A3 3 0 0 1 7.12 2.878l8.79 8.79a.125.125 0 0 0 .177 0z"
                            stroke-width="0.5" stroke="currentColor" />
                    </svg>
                </div>

                <div class="grid grid-1 space-y-2 text-xs">

                    <div class="flex flex-col space-y-1">
                        <label for="create_acc_tel">
                            เบอร์โทรศัพท์ <span class="text-red-500">*</span>
                        </label>
                        <input class="border border-gray-300 rounded p-1.5 focus:ring-emerald-500 focus:ring-3"
                            type="text" id="create_acc_tel" x-model="createUserForm.member_phone"
                            placeholder="หมายเลขโทรศัพท์">
                        <span x-show="errors.create.member_phone" class="text-red-500 text-xs">
                            กรุณากรอกหมายเลขโทรศัพท์
                        </span>
                    </div>

                    <div class="flex flex-col space-y-1">
                        <label for="create_acc_pass" class="text-gray-700 font-medium">
                            รหัสผ่าน <span class="text-red-500">*</span>
                        </label>
                        <input class="border border-gray-300 rounded p-1.5 focus:ring-emerald-500 focus:ring-3"
                            :class="{'border-red-500': errors.create.member_password}" type="password"
                            id="create_acc_pass" x-model="createUserForm.member_password" placeholder="8 character">
                        <span x-show="errors.create.member_password" class="text-red-500 text-xs">
                            กรุณากรอกรหัสผ่าน
                        </span>
                    </div>

                    <div class="flex flex-col space-y-1">
                        <label for="create_acc_personal_id" class="text-gray-700 font-medium">รหัสประจำตัว</label>
                        <input class="border border-gray-300 rounded p-1.5 focus:ring-emerald-500 focus:ring-3"
                            :class="{'border-red-500': errors.create.member_personal_id}" type="text"
                            id="create_acc_personal_id" x-model="createUserForm.member_personal_id"
                            placeholder="รหัสนักศึกษา/รหัสประจำตัวปชช.">
                    </div>

                    <div class="flex flex-col space-y-1">
                        <label for="create_acc_mail">อีเมล์</label>
                        <input class="border border-gray-300 rounded p-1.5 focus:ring-emerald-500 focus:ring-3"
                            type="text" id="create_acc_mail" x-model="createUserForm.member_email"
                            placeholder="อีเมลล์">
                    </div>

                    <div class="flex flex-col space-y-1">
                        <label for="create_acc_name">ชื่อ</label>
                        <input class="border border-gray-300 rounded p-1.5 focus:ring-emerald-500 focus:ring-3"
                            type="text" id="create_acc_name" x-model="createUserForm.member_name"
                            placeholder="ชื่อที่ใช้แสดงผล">
                    </div>

                    <div class="flex flex-col space-y-1">
                        <label for="create_acc_role" class="text-gray-700 font-medium">
                            บทบาท <span class="text-red-500">*</span>
                        </label>
                        <select id="create_acc_role" x-model="createUserForm.role_id"
                            class="border border-gray-300 rounded p-1.5 focus:ring-emerald-500 focus:ring-3 bg-white"
                            :class="{'border-red-500': errors.create.role_id}">
                            <option value="">เลือกบทบาท</option>
                            <?php
                            if ((int) $user->role_id == 1) {
                                echo '<option value="1">ผู้ดูแลระบบ</option>';
                            }
                            ?>
                            <option value="2">นักศึกษา</option>
                            <option value="5">อาจารย์/ศาสตราจารย์</option>
                            <option value="6">บุคลากร</option>
                            <option value="3">เจ้าหน้าที่จุดฝาก</option>
                        </select>
                        <span x-show="errors.create.role_id" class="text-red-500 text-xs">
                            กรุณาเลือกบทบาท
                        </span>
                    </div>

                    <div class="flex flex-col space-y-1">
                        <label for="create_acc_major" class="text-gray-700 font-medium">
                            สาขา
                        </label>
                        <select id="create_acc_major" x-model="createUserForm.major_id"
                            class="border border-gray-300 rounded p-1.5 focus:ring-emerald-300 focus:ring-3 bg-white disabled:bg-gray-100"
                            :disabled="!createUserForm.faculty_id">
                            <option value="">เลือกสาขา</option>
                            <template x-for="major in createMajors" :key="major.major_id">
                                <option :value="major.major_id" x-text="major.major_name"></option>
                            </template>
                        </select>
                    </div>

                </div>

                <div class="mt-4 text-right">
                    <button @click="createUserDialogShow = false"
                        class="px-3 py-1 border-2 border-gray-200 rounded hover:border-gray-300 cursor-pointer text-gray-400 hover:scale-105 transition duration-100">ยกเลิก</button>
                    <button @click="submitCreate"
                        class="px-3 py-1 bg-emerald-500 rounded hover:bg-emerald-700 cursor-pointer text-white font-semibold hover:scale-105 transition duration-100">เพิ่มผู้ใช้งาน</button>
                </div>
            </div>
        </dialog>

        <dialog x-ref="editUserDialog" x-show="editUserDialogShow" @click.self="editUserDialogShow = false"
            @close="editUserDialogShow = false" class="fixed inset-0 mx-auto my-auto p-0 bg-transparent z-50"
            x-init="$watch('editUserDialogShow', value => {if (value) $refs.editUserDialog.showModal();else $refs.editUserDialog.close();})">
            <div class="bg-white rounded-md shadow p-6 w-96 max-w-full">
                <h3 class="font-bold text-lg mb-3">แก้ไขข้อมูล</h3>

                <div class="grid grid-1 space-y-2 text-xs">

                    <div class="flex flex-col space-y-1">
                        <label for="edit_acc_tel">
                            เบอร์โทรศัพท์ <span class="text-red-500">*</span>
                        </label>
                        <input class="border border-gray-300 rounded p-1.5 focus:ring-emerald-300 focus:ring-3"
                            type="text" id="edit_acc_tel" x-model="editUserForm.member_phone"
                            placeholder="เบอร์โทรศัพท์">
                        <span x-show="errors.edit.member_phone" class="text-red-500 text-xs">
                            กรุณากรอกหมายเลขโทรศัพท์
                        </span>
                    </div>

                    <div class="flex flex-col space-y-1">
                        <label for="edit_acc_personal_id" class="text-gray-700 font-medium">รหัสประจำตัว</label>
                        <input class="border border-gray-300 rounded p-1.5 focus:ring-emerald-500 focus:ring-3"
                            :class="{'border-red-500': errors.edit.member_personal_id}" type="text"
                            id="edit_acc_personal_id" x-model="editUserForm.member_personal_id"
                            placeholder="รหัสนักศึกษา/รหัสประจำตัวปชช.">
                    </div>

                    <div class="flex flex-col space-y-1">
                        <label for="edit_acc_mail">อีเมล์</label>
                        <input class="border border-gray-300 rounded p-1.5 focus:ring-emerald-300 focus:ring-3"
                            type="text" id="edit_acc_mail" x-model="editUserForm.member_email" placeholder="อีเมล์">
                    </div>

                    <div class="flex flex-col space-y-1">
                        <label for="edit_acc_name">ชื่อ</label>
                        <input class="border border-gray-300 rounded p-1.5 focus:ring-emerald-300 focus:ring-3"
                            type="text" id="edit_acc_name" x-model="editUserForm.member_name"
                            placeholder="ชื่อที่ใช้แสดงผล">
                    </div>

                    <div class="flex flex-col space-y-1">
                        <label for="edit_acc_role" class="text-gray-700 font-medium">บทบาท <span
                                class="text-red-500">*</span></label>
                        <select id="edit_acc_role" x-model="editUserForm.role_id"
                            class="border border-gray-300 rounded p-1.5 focus:ring-emerald-300 focus:ring-3 bg-white"
                            :class="{'border-red-500': errors.edit.role_id}">
                            <option value="">เลือกบทบาท</option>
                            <?php
                            if ((int) $user->role_id == 1) {
                                echo '<option value="1">ผู้ดูแลระบบ</option>';
                            }
                            ?>
                            <option value="2">นักศึกษา</option>
                            <option value="5">อาจารย์/ศาสตราจารย์</option>
                            <option value="6">บุคลากร</option>
                            <option value="3">เจ้าหน้าที่จุดฝาก</option>
                        </select>
                        <span x-show="errors.edit.role_id" class="text-red-500 text-xs">กรุณาเลือกบทบาท</span>
                    </div>

                    <div class="flex flex-col space-y-1">
                        <label for="edit_acc_major" class="text-gray-700 font-medium">
                            สาขา
                        </label>
                        <select id="edit_acc_major" x-model="editUserForm.major_id"
                            class="border border-gray-300 rounded p-1.5 focus:ring-emerald-500 focus:ring-3 bg-white disabled:bg-gray-100"
                            :disabled="!editUserForm.faculty_id">
                            <option value="">เลือกสาขา</option>
                            <template x-for="major in editMajors" :key="major.major_id">
                                <option :value="major.major_id" x-text="major.major_name"></option>
                            </template>
                        </select>
                    </div>

                </div>

                <div class="mt-4 text-right space-x-2">
                    <button @click="editUserDialogShow = false"
                        class="px-3 py-1 border-2 border-gray-200 rounded hover:border-gray-300 cursor-pointer text-gray-400 hover:scale-105 transition duration-100">ยกเลิก</button>
                    <button @click="submitEdit"
                        class="px-3 py-1 bg-emerald-500 rounded hover:bg-emerald-700 cursor-pointer text-white font-semibold hover:scale-105 transition duration-100">ยืนยัน</button>
                </div>
            </div>
        </dialog>

        <!-- Quick Menu Dialog -->
        <dialog x-ref="quickMenuDialog" x-show="quickMenuShow" @click.self="quickMenuShow = false"
            @close="quickMenuShow = false" class="fixed inset-0 mx-auto my-auto p-0 bg-transparent z-50"
            x-init="$watch('quickMenuShow', value => {if (value) $refs.quickMenuDialog.showModal();else $refs.quickMenuDialog.close();})">
            <div class="bg-white rounded-lg shadow-lg p-6 w-80 max-w-full">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-lg">เมนูด่วน</h3>
                    <button @click="quickMenuShow = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <template x-if="selectedMemberForMenu">
                    <div class="bg-blue-50 rounded-lg p-3 mb-4 text-sm">
                        <p><b>สมาชิก:</b> <span x-text="selectedMemberForMenu.member_name || 'ไม่ระบุชื่อ'"></span></p>
                        <p><b>เบอร์:</b> <span x-text="selectedMemberForMenu.member_phone || 'ไม่ระบุ'"></span></p>
                        <p><b>แต้ม:</b> <span
                                x-text="Number(parseInt(selectedMemberForMenu.member_waste_point) || 0).toLocaleString()"></span>
                        </p>
                    </div>
                </template>

                <div class="space-y-2">
                    <button @click="openWasteDeposit(selectedMemberForMenu)"
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path d="M7 2H3v20h4V2zm8 0h-4v20h4V2zm8 0h-4v20h4V2z" />
                        </svg>
                        ฝากขยะ
                    </button>

                    <button @click="openRedeemReward(selectedMemberForMenu)"
                        class="w-full bg-pink-500 hover:bg-pink-600 text-white font-bold py-2 px-4 rounded-lg transition flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path
                                d="M12 2L15.09 8.26H22L17.45 12.74L19.54 19L12 15.27L4.46 19L6.55 12.74L2 8.26H8.91L12 2Z" />
                        </svg>
                        แลกของรางวัล
                    </button>

                    <button @click="openDonation(selectedMemberForMenu)"
                        class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z" />
                        </svg>
                        บริจาคสิ่งของ
                    </button>
                </div>

                <div class="mt-4">
                    <button @click="quickMenuShow = false"
                        class="w-full bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg transition">
                        ปิด
                    </button>
                </div>
            </div>
        </dialog>

    </div>
</div>

<script>
    function UserTable() {
        return {
            manager_role: "<?= $user->role_name ?>",
            facultyId: <?php echo $faculty_id; ?>,
            roleCounts: { total_members: 0, roles: [] },
            members: [],
            createMajors: [],
            editMajors: [],
            filterMajors: [],
            checkedMembers: { member_ids: [] },
            selectedUser: null,
            createUserDialogShow: false,
            editUserDialogShow: false,
            page: 1,
            limit: 10,
            totalPages: 1,

            errors: {
                create: {},
                edit: {},
            },

            editUserForm: {
                member_personal_id: "",
                member_phone: "",
                member_name: "",
                member_email: "",
                faculty_id: this.facultyId,
                major_id: "",
                role_id: "",
            },

            createUserForm: {
                member_personal_id: "",
                member_phone: "",
                member_name: "",
                member_email: "",
                member_password: "",
                faculty_id: this.facultyId,
                major_id: "",
                role_id: "",
            },

            filters: {
                major_id: "",
                role: "",
                search: "",
                sort_by: "",
                order: "DESC",
            },

            quickMenuShow: false,
            selectedMemberForMenu: null,

            async initData() {
                if (this.facultyId) {
                    await this.fetchMajorsByFaculty(this.facultyId, 'filter');
                }
                await this.fetchMembers();
                await this.fetchRoleCounts();
            },

            async fetchRoleCounts() {
                try {
                    const params = new URLSearchParams();
                    if (this.facultyId)
                        params.append("faculty", this.facultyId);
                    if (this.filters.major_id)
                        params.append("major", this.filters.major_id);

                    const res = await fetch(`/api/members/count?${params.toString()}`);
                    let result = await res.json();
                    if (result.success) {
                        this.roleCounts = result.data;
                    }

                } catch (e) {
                    console.error(e);
                }
            },

            async fetchMembers() {
                try {
                    const params = new URLSearchParams();
                    params.append("page", this.page);
                    params.append("limit", this.limit);
                    if (this.facultyId)
                        params.append("faculty", this.facultyId);
                    if (this.filters.major_id)
                        params.append("major_id", this.filters.major_id);
                    if (this.filters.role) params.append("role", this.filters.role);
                    if (this.filters.search)
                        params.append("search", this.filters.search);
                    if (this.filters.sort_by) {
                        params.append("sort_by", this.filters.sort_by);
                        params.append("order", this.filters.order);
                    }

                    const res = await fetch(`/api/members?${params.toString()}`);
                    let result = await res.json();

                    this.members = result.data;
                    this.totalPages = Math.ceil(result.total / this.limit);
                } catch (err) {
                    console.error("โหลดข้อมูลผู้ใช้ล้มเหลว", err);
                }
            },

            async fetchMajorsByFaculty(facultyId, formType) {
                if (!facultyId) {
                    if (formType === 'create') {
                        this.createMajors = [];
                        this.createUserForm.major_id = "";
                    } else if (formType === 'edit') {
                        this.editMajors = [];
                        this.editUserForm.major_id = "";
                    } else if (formType === 'filter') {
                        this.filterMajors = [];
                        this.filters.major_id = "";
                    }
                    return;
                }

                try {
                    const res = await fetch(`/api/majors/faculty/${facultyId}`);
                    const result = await res.json();
                    if (result.success) {
                        if (formType === 'create') {
                            this.createMajors = result.result;
                        } else if (formType === 'edit') {
                            this.editMajors = result.result;
                        } else if (formType === 'filter') {
                            this.filterMajors = result.result;
                        }
                    } else {
                        if (formType === 'create') {
                            this.createMajors = [];
                        } else if (formType === 'edit') {
                            this.editMajors = [];
                        } else if (formType === 'filter') {
                            this.filterMajors = [];
                        }
                    }
                } catch (err) {
                    console.error("โหลดข้อมูลสาขาล้มเหลว", err);
                    if (formType === 'create') {
                        this.createMajors = [];
                    } else if (formType === 'edit') {
                        this.editMajors = [];
                    } else if (formType === 'filter') {
                        this.filterMajors = [];
                    }
                }
            },

            handleFilterChange() {
                this.page = 1;
                this.fetchMembers();
                this.fetchRoleCounts();
            },

            filterByRole(roleId) {
                this.filters.role = roleId || "";
                this.handleFilterChange();
            },

            sortMembers(column) {
                if (this.filters.sort_by === column) {
                    this.filters.order = this.filters.order === 'ASC' ? 'DESC' : 'ASC';
                } else {
                    this.filters.sort_by = column;
                    this.filters.order = 'DESC';
                }
                this.page = 1;
                this.fetchMembers();
            },

            resetFilters() {
                this.filters = {
                    major_id: "",
                    role: "",
                    search: "",
                    sort_by: "",
                    order: "DESC",
                };
                this.page = 1;
                this.fetchMembers();
            },


            async openCreateDialog() {
                this.createUserForm = {
                    member_personal_id: "",
                    member_name: "",
                    member_email: "",
                    member_password: "",
                    faculty_id: this.facultyId,
                    major_id: "",
                    role_id: "",
                };
                this.errors.create = {};

                if (this.facultyId) {
                    await this.fetchMajorsByFaculty(this.facultyId, 'create');
                } else {
                    this.createMajors = [];
                }

                this.createUserDialogShow = true;
            },

            async selectingRow(user) {
                this.selectedUser = user;
                this.editUserForm = {
                    member_personal_id: user.member_personal_id ?? "",
                    member_phone: user.member_phone ?? null,
                    member_name: user.member_name ?? null,
                    member_email: user.member_email ?? null,
                    faculty_id: this.facultyId,
                    major_id: user.major_id ?? "",
                    role_id: parseInt(user.role_id),
                };
                this.errors.edit = {};

                if (this.facultyId) {
                    await this.fetchMajorsByFaculty(this.facultyId, 'edit');
                } else {
                    this.editMajors = [];
                }

                this.editUserDialogShow = true;
            },

            validateForm(formType) {
                let isValid = true;
                const errors = {};
                const form =
                    formType === "create" ? this.createUserForm : this.editUserForm;

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

            async submitEdit() {
                this.editUserDialogShow = false;
                if (!this.validateForm("edit")) {
                    await Swal.fire({
                        icon: "warning",
                        title: "ข้อมูลไม่ครบถ้วน",
                        text: "กรุณากรอกข้อมูลในช่องที่มีเครื่องหมายดอกจัน (*) ให้ครบ",
                        confirmButtonColor: "#ff8f4eff",
                    });
                    this.editUserDialogShow = true;
                    return;
                }
                const result = await Swal.fire({
                    title: "แก้ไขข้อมูล",
                    text: "คุณตรวจสอบข้อมูลและแน่ใจแล้วใช่ไหม ?",
                    icon: "info",
                    showConfirmButton: true,
                    confirmButtonText: "ยืนยัน",
                    showCancelButton: true,
                    cancelButtonText: "ยกเลิก",
                    didOpen: () => {
                        Swal.getConfirmButton().focus();
                    },
                });

                if (result.isConfirmed) {
                    try {
                        const res = await fetch(
                            `/api/members/update/${this.selectedUser.member_id}`,
                            {
                                method: "POST",
                                headers: { "Content-Type": "application/json" },
                                body: JSON.stringify(this.editUserForm),
                            }
                        );
                        const response = await res.json();

                        if (response.success) {
                            Swal.fire({
                                icon: "success",
                                title: "แก้ไขสำเร็จ",
                                timer: 2000,
                                showConfirmButton: false,
                            });
                            this.selectedUser = null;
                            this.fetchMembers();
                        } else {
                            throw new Error(
                                response.message || "Something went wrong"
                            );
                        }
                    } catch (error) {
                        await Swal.fire({
                            icon: "error",
                            title: "ไม่สำเร็จ",
                            html: "<p>" + (error.message || "ไม่สามารถแก้ไขข้อมูลได้") + "</p><br><hr><p class='text-xs'>หากพบปัญหาในการใช้งาน สามารถติดต่อศูนย์ฯด้วยตนเอง เพื่อดำเนินการแก้ไข</p>",
                            timer: 5000,
                            showConfirmButton: true,
                            confirmButtonColor: '#009966',
                            confirmButtonText: "ปิด"
                        });
                        console.error(error);
                        this.editUserDialogShow = true;
                    }
                }
            },

            async submitCreate() {
                this.createUserDialogShow = false;
                if (!this.validateForm("create")) {
                    await Swal.fire({
                        icon: "warning",
                        title: "ข้อมูลไม่ครบถ้วน",
                        text: "กรุณากรอกข้อมูลในช่องที่มีเครื่องหมายดอกจัน (*) ให้ครบ",
                        confirmButtonColor: "#ff8f4eff",
                    });
                    this.createUserDialogShow = true;
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
                        Swal.fire({
                            icon: "success",
                            title: "เพิ่มสำเร็จ",
                            text: `เพิ่มผู้ใช้งานเรียบร้อย`,
                            timer: 2000,
                            showConfirmButton: false,
                        });
                        this.fetchMembers();
                    } else {
                        throw new Error(response.message || "Something went wrong");
                    }
                } catch (error) {
                    console.error(error);
                    await Swal.fire({
                        icon: "error",
                        title: "ไม่สำเร็จ",
                        html: "<p>" + (error.message || "ไม่สามารถเพิ่มผู้ใช้งานได้") + "</p><br><hr><p class='text-xs'>หากพบปัญหาในการใช้งาน สามารถติดต่อศูนย์ฯด้วยตนเอง เพื่อดำเนินการแก้ไข</p>",
                        timer: 5000,
                        showConfirmButton: true,
                        confirmButtonColor: '#009966',
                        confirmButtonText: "ปิด"
                    });
                    this.createUserDialogShow = true;
                }
            },

            async confirmDeleteUser(member) {
                if (!member) return;

                const result = await Swal.fire({
                    title: "ยืนยันการลบ",
                    html: `<p>ต้องการลบผู้ใช้ <span class="text-red-500 font-semibold">"${member.member_name || member.member_phone}"</span> ใช่หรือไม่?</p>`,
                    icon: "warning",
                    showConfirmButton: true,
                    confirmButtonText: "ยืนยันการลบ",
                    confirmButtonColor: "#d33",
                    showCancelButton: true,
                    cancelButtonText: "ยกเลิก",
                    didOpen: () => {
                        Swal.getConfirmButton().focus();
                    },
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
                            Swal.fire({
                                icon: "success",
                                title: "ลบสำเร็จ",
                                timer: 2000,
                                showConfirmButton: false,
                            });
                            this.fetchMembers();
                        } else {
                            throw new Error(
                                delResult.message || "Something went wrong"
                            );
                        }
                    } catch (error) {
                        console.error(error);
                        Swal.fire({
                            icon: "error",
                            title: "ผิดพลาด",
                            text: "ลบรายชื่อไม่สำเร็จ",
                            timer: 2000,
                            showConfirmButton: false,
                        });
                    }
                }
            },

            async deleteCheckedUser() {
                if (this.checkedMembers.member_ids.length === 0) return;

                const result = await Swal.fire({
                    title: "ลบข้อมูล",
                    text: `ต้องการลบผู้ใช้ ${this.checkedMembers.member_ids.length} รายการ ใช่หรือไม่?`,
                    icon: "warning",
                    showConfirmButton: true,
                    confirmButtonText: "ยืนยันการลบ",
                    confirmButtonColor: "#d33",
                    showCancelButton: true,
                    cancelButtonText: "ยกเลิก",
                    didOpen: () => {
                        Swal.getConfirmButton().focus();
                    },
                });

                if (result.isConfirmed) {
                    try {
                        const deleteRes = await fetch("/api/members/delete", {
                            method: "POST",
                            headers: { "Content-Type": "application/json" },
                            body: JSON.stringify(this.checkedMembers),
                        });
                        const delResult = await deleteRes.json();

                        if (delResult.success) {
                            Swal.fire({
                                icon: "success",
                                title: "ลบสำเร็จ",
                                timer: 2000,
                                showConfirmButton: false,
                            });
                            this.checkedMembers.member_ids = [];
                            this.fetchMembers();
                        } else {
                            throw new Error(
                                delResult.message || "Something went wrong"
                            );
                        }
                    } catch (error) {
                        console.error(error);
                        Swal.fire({
                            icon: "error",
                            title: "ผิดพลาด",
                            text: "ลบรายชื่อไม่สำเร็จ",
                            timer: 2000,
                            showConfirmButton: false,
                        });
                    }
                }
            },

            showQuickMenu(member) {
                this.selectedMemberForMenu = member;
                this.quickMenuShow = true;
            },

            openWasteDeposit(member) {
                this.quickMenuShow = false;
                window.open(`/${this.manager_role}/transactions/waste?member_id=${member.member_id}`, '_blank');
            },

            openRedeemReward(member) {
                this.quickMenuShow = false;
                window.open(`/${this.manager_role}/transactions/redeem_item?member_id=${member.member_id}`, '_blank');
            },

            openDonation(member) {
                this.quickMenuShow = false;
                window.open(`/${this.manager_role}/transactions/donation?member_id=${member.member_id}`, '_blank');
            },
        };
    }

</script>