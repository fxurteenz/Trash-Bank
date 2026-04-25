<?php
// ไฟล์นี้ใช้สำหรับเจ้าหน้าที่คณะจัดการสมาชิกเท่านั้น
$faculty_id = (int) $user->faculty_id ?? "null";
?>

<div x-data="UserTable()" x-init="initData()" class="space-y-4 w-full">
    <!-- Top Card -->
    <div id="role-counts-container" class="w-full grid grid-cols-2 md:grid-cols-4 xl:grid-cols-5 gap-2">
        <div @click="filterByRole('')"
            class="bg-white rounded-md shadow p-6 col-span-2 md:col-span-4 xl:col-span-1 cursor-pointer hover:scale-105 active:scale-95 duration-300 ease-out hover:shadow-md hover:shadow-emerald-500/50 transition-all"
            :class="filters.role === '' ? 'ring-2 ring-emerald-500' : ''">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">ผู้ใช้ทั้งหมด</p>
                    <p class="text-2xl font-bold text-emerald-700" x-text="roleCounts.total_members"></p>
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
        <div @click="filterByRole(roleCounts.roles[0]?.role_id)"
            class="bg-white rounded-md shadow p-6 col-span-1 cursor-pointer hover:scale-105 hover:shadow-md hover:shadow-emerald-500/50 active:scale-95 duration-300 ease-out transition-all"
            :class="filters.role === roleCounts.roles[0]?.role_id ? 'ring-2 ring-emerald-500' : ''">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600" x-text="roleCounts.roles[0]?.role_name_th"></p>
                    <p class="text-2xl font-bold text-emerald-700" x-text="roleCounts.roles[0]?.member_count">
                    </p>
                </div>
                <div class="p-3 bg-emerald-100 rounded-full text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 21v-2a4 4 0 0 1 4-4h2m10 1c0 4-2.5 6-3.5 6S15 20 15 16c1 0 2.5-.5 3.5-1.5c1 1 2.5 1.5 3.5 1.5M8 7a4 4 0 1 0 8 0a4 4 0 0 0-8 0" />
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
                    <p class="text-2xl font-bold text-emerald-700" x-text="roleCounts.roles[1]?.member_count">
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
                    <p class="text-2xl font-bold text-emerald-700" x-text="roleCounts.roles[2]?.member_count">
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
        <div @click="filterByRole(roleCounts.roles[3]?.role_id)"
            class="bg-white rounded-md shadow p-6 col-span-1 cursor-pointer hover:scale-105 hover:shadow-md hover:shadow-emerald-500/50 active:scale-95 duration-300 ease-out transition-all"
            :class="filters.role === roleCounts.roles[3]?.role_id ? 'ring-2 ring-emerald-500' : ''">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600" x-text="roleCounts.roles[3]?.role_name_th"></p>
                    <p class="text-2xl font-bold text-emerald-700" x-text="roleCounts.roles[3]?.member_count">
                    </p>
                </div>
                <div class="p-3 bg-emerald-100 rounded-full text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2">
                            <path
                                d="m13.163 2.168l8.021 5.828c.694.504.984 1.397.719 2.212l-3.064 9.43a1.98 1.98 0 0 1-1.881 1.367H7.042a1.98 1.98 0 0 1-1.881-1.367l-3.064-9.43a1.98 1.98 0 0 1 .719-2.212l8.021-5.828a1.98 1.98 0 0 1 2.326 0" />
                            <path
                                d="M12 13a3 3 0 1 0 0-6a3 3 0 0 0 0 6m-6 7.703V20a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v.707" />
                        </g>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-md shadow p-6 overflow-x-auto w-full">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">รายชื่อผู้ใช้งาน</h2>
            <div class="flex justify-between mb-1 text-gray-700 text-xs font-regular">
                <div class="flex justify-end text-xs space-x-2">
                    <div @click="openCreateDialog" :class="createUserDialogShow && 'bg-emerald-300'"
                        class="group cursor-pointer flex items-center py-2 px-4 border-2 border-emerald-500 rounded-full hover:bg-emerald-100 space-x-1 transition-colors font-medium text-emerald-700">
                        <button class="group-hover:rotate-90 duration-300" title="Add New">
                            <svg class="stroke-teal-500 fill-none group-active:stroke-teal-200 group-active:duration-0 duration-300"
                                viewBox="0 0 24 24" height="24" width="24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-width="1.5"
                                    d="M12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22Z">
                                </path>
                                <path stroke-width="1.5" d="M8 12H16"></path>
                                <path stroke-width="1.5" d="M12 16V8"></path>
                            </svg>
                        </button>
                        <span>เพิ่ม</span>
                    </div>

                    <button
                        class="group cursor-pointer flex items-center py-2 px-4 border-2 border-red-500 rounded-full hover:bg-red-100 space-x-1 hover:scale-105 font-medium text-red-700 disabled:opacity-50"
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
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 p-4 bg-gray-50 rounded-lg">
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
                    <option value="admin">Admin</option>
                    <option value="staff">Staff</option>
                    <option value="user">User</option>
                </select>
            </div>
            <div>
                <label for="filter_search" class="block text-xs font-medium text-gray-700 mb-1">ค้นหารายชื่อ</label>
                <input type="text" id="filter_search" x-model="filters.search"
                    @input.debounce.500ms="handleFilterChange()" placeholder="ชื่อ/รหัส/เบอร์โทร"
                    class="w-full text-xs border border-gray-300 rounded px-2 py-1.5 bg-white focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        <div class="flex items-center gap-2 mb-1">
            <button @click="resetFilters()"
                class="text-sm text-gray-500 hover:text-gray-700 underline">ล้างตัวกรอง</button>
        </div>

        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                            เลือก
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                            เบอร์โทรศัพท์
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                            ชื่อ
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b hidden lg:table-cell">
                            บทบาท
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                            แต้มขยะ
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <template x-for="member in members" :key="member.member_id">
                        <tr @click="window.open(`/staff/manage/members/detail/${member.member_id}`, '_blank')"
                            class="hover:bg-gray-50 cursor-pointer transition-colors"
                            :class="editUserForm && editUserForm.member_id == member.member_id ? 'bg-emerald-100' : ''">
                            <td class="px-2 py-2 text-center" @click.stop>
                                <input type="checkbox" class="p-1" :id="member.member_id" :value="member.member_id"
                                    x-model="checkedMembers.member_ids">
                            </td>
                            <td class="px-2 py-2 overflow-hidden text-ellipsis text-xs "
                                x-text="member.member_phone ?? 'ไม่ระบุ'"></td>

                            <td class="px-2 py-2 overflow-hidden text-ellipsis text-xs"
                                x-text="member.member_name ?? 'ไม่มีชื่อ'"></td>

                            <td class="px-2 py-2 overflow-hidden text-ellipsis text-xs hidden lg:table-cell"
                                x-text="member.role_name_th || 'ไม่ระบุ'">
                            </td>

                            <td class="px-2 py-2 text-xs text-end" x-text="member.member_waste_point ?? '0'"></td>
                            <td class="px-2 py-2 whitespace-nowrap text-center text-sm" @click.stop>
                                <div class="flex justify-center items-center gap-1">
                                    <button @click="showQuickMenu(member)"
                                        class="bg-blue-100 text-blue-700 hover:bg-blue-200 border border-blue-200 hover:cursor-pointer transition duration-200 px-2 py-1 rounded"
                                        title="ทำรายการ">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24" fill="currentColor">
                                            <path
                                                d="M12 8c1.1 0 2-0.9 2-2s-0.9-2-2-2-2 0.9-2 2 0.9 2 2 2z m0 2c-1.1 0-2 0.9-2 2s0.9 2 2 2 2-0.9 2-2-0.9-2-2-2z m0 6c-1.1 0-2 0.9-2 2s0.9 2 2 2 2-0.9 2-2-0.9-2-2-2z" />
                                        </svg>
                                    </button>

                                    <button @click.stop="selectingRow(member)"
                                        class="bg-amber-100 text-amber-700 hover:bg-amber-200 border border-amber-200 hover:cursor-pointer transition duration-200 px-2 py-1 rounded flex"
                                        title="แก้ไข">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>

                                    <button @click.stop="confirmDeleteUser(member)"
                                        class="bg-red-100 hover:bg-red-200 border border-red-200 hover:cursor-pointer text-red-700 cursor-pointer transition duration-200 px-2 py-1 rounded flex"
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

        <div class="flex items-center justify-between mt-4 text-xs">
            <button class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50" :disabled="page <= 1"
                @click="page--; fetchMembers()">
                ก่อนหน้า
            </button>
            <div class="flex items-center space-x-2">
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
                        <select id="create_acc_role" x-model="createUserForm.role_id" disabled
                            class="border border-gray-300 rounded p-1.5 bg-gray-100 text-gray-500 cursor-not-allowed">
                            <option value="2">ผู้ใช้งานทั่วไป</option>
                        </select>
                    </div>

                    <div class="flex flex-col space-y-1">
                        <label for="create_acc_major" class="text-gray-700 font-medium">
                            สาขา
                        </label>
                        <select id="create_acc_major" x-model="createUserForm.major_id"
                            class="border border-gray-300 rounded p-1.5 focus:ring-emerald-500 focus:ring-3 bg-white"
                            :disabled="!facultyId">
                            <option value="">เลือกสาขา</option>
                            <template x-for="major in createMajors" :key="major.major_id">
                                <option :value="major.major_id" x-text="major.major_name"></option>
                            </template>
                        </select>
                    </div>

                </div>

                <div class="mt-4 text-right">
                    <button @click="createUserDialogShow = false"
                        class="px-3 py-1 border-2 border-gray-200 rounded hover:border-gray-300 cursor-pointer text-gray-400 hover:scale-105">ยกเลิก</button>
                    <button @click="submitCreate"
                        class="px-3 py-1 bg-emerald-500 rounded hover:bg-emerald-700 cursor-pointer text-white font-semibold hover:scale-105">เพิ่มผู้ใช้งาน</button>
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
                        <input class="border border-gray-300 rounded p-1.5 focus:ring-emerald-500 focus:ring-3"
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
                        <input class="border border-gray-300 rounded p-1.5 focus:ring-emerald-500 focus:ring-3"
                            type="text" id="edit_acc_mail" x-model="editUserForm.member_email" placeholder="อีเมล์">
                    </div>

                    <div class="flex flex-col space-y-1">
                        <label for="edit_acc_name">ชื่อ</label>
                        <input class="border border-gray-300 rounded p-1.5 focus:ring-emerald-500 focus:ring-3"
                            type="text" id="edit_acc_name" x-model="editUserForm.member_name"
                            placeholder="ชื่อที่ใช้แสดงผล">
                    </div>

                    <div class="flex flex-col space-y-1">
                        <label for="edit_acc_role" class="text-gray-700 font-medium">บทบาท <span
                                class="text-red-500">*</span></label>
                        <select id="edit_acc_role" x-model="editUserForm.role_id"
                            class="border border-gray-300 rounded p-1.5 focus:ring-emerald-500 focus:ring-3 bg-white"
                            :class="{'border-red-500': errors.edit.role_id}">
                            <option value="">เลือกบทบาท</option>
                            <option value="1">ผู้ดูแลระบบ</option>
                            <option value="2">ผู้ใช้งานทั่วไป</option>
                            <option value="3">เจ้าหน้าที่จุดฝาก</option>
                            <option value="4">เจ้าหน้าที่ศูนย์ใหญ่</option>
                        </select>
                        <span x-show="errors.edit.role_id" class="text-red-500 text-xs">กรุณาเลือกบทบาท</span>
                    </div>

                    <div class="flex flex-col space-y-1">
                        <label for="edit_acc_major" class="text-gray-700 font-medium">
                            สาขา
                        </label>
                        <select id="edit_acc_major" x-model="editUserForm.major_id"
                            class="border border-gray-300 rounded p-1.5 focus:ring-emerald-500 focus:ring-3 bg-white"
                            :disabled="!facultyId">
                            <option value="">เลือกสาขา</option>
                            <template x-for="major in editMajors" :key="major.major_id">
                                <option :value="major.major_id" x-text="major.major_name"></option>
                            </template>
                        </select>
                    </div>

                </div>

                <div class="mt-4 text-right space-x-2">
                    <button @click="editUserDialogShow = false"
                        class="px-3 py-1 border-2 border-gray-200 rounded hover:border-gray-300 cursor-pointer text-gray-400 hover:scale-105">ยกเลิก</button>
                    <button @click="submitEdit"
                        class="px-3 py-1 bg-emerald-500 rounded hover:bg-emerald-700 cursor-pointer text-white font-semibold hover:scale-105">ยืนยันความเปลี่ยนแปลง</button>
                </div>
            </div>
        </dialog>

        <dialog x-ref="quickMenuDialog" x-show="quickMenuShow" @click.self="quickMenuShow = false"
            @close="quickMenuShow = false" class="fixed inset-0 mx-auto my-auto p-0 bg-transparent z-50"
            x-init="$watch('quickMenuShow', value => {if (value) $refs.quickMenuDialog.showModal();else $refs.quickMenuDialog.close();})">
            <div class="bg-white rounded-lg shadow-lg p-6 w-80 max-w-full">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-lg">🎯 เมนูด่วน</h3>
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
                        <p><b>แต้ม:</b> <span x-text="selectedMemberForMenu.member_waste_point || '0'"></span></p>
                    </div>
                </template>

                <div class="space-y-2">
                    <button @click="openWasteDeposit(selectedMemberForMenu)"
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path d="M7 2H3v20h4V2zm8 0h-4v20h4V2zm8 0h-4v20h4V2z" />
                        </svg>
                        📦 ทำรายการฝากของ
                    </button>

                    <button @click="openDonationExchange(selectedMemberForMenu)"
                        class="w-full bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 px-4 rounded-lg transition flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path
                                d="M7 16h2v2H7zm4 0h2v2h-2zm4 0h2v2h-2zm-8-4h2v2H7zm4 0h2v2h-2zm4 0h2v2h-2zM7 8h2v2H7zm4 0h2v2h-2zm4 0h2v2h-2z" />
                        </svg>
                        💰 แลกของบริจาค
                    </button>

                    <button @click="openRedeemReward(selectedMemberForMenu)"
                        class="w-full bg-pink-500 hover:bg-pink-600 text-white font-bold py-2 px-4 rounded-lg transition flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path
                                d="M12 2L15.09 8.26H22L17.45 12.74L19.54 19L12 15.27L4.46 19L6.55 12.74L2 8.26H8.91L12 2Z" />
                        </svg>
                        🎁 แลกของรางวัล
                    </button>

                    <button @click="openDonation(selectedMemberForMenu)"
                        class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z" />
                        </svg>
                        🎀 บริจาคสิ่งของ
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
            facultyId: <?php echo $faculty_id; ?>,
            roleCounts: { total_members: 0, roles: [] },
            members: [],
            faculties: [],
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
                faculty_id: "",
                major_id: "",
                role_id: "",
            },

            createUserForm: {
                member_personal_id: "",
                member_phone: "",
                member_name: "",
                member_email: "",
                member_password: "",
                faculty_id: "",
                major_id: "",
                role_id: "",
            },

            filters: {
                major_id: "",
                role: "",
                search: "",
            },

            quickMenuShow: false,
            selectedMemberForMenu: null,

            async initData() {
                await this.fetchFaculties();
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

                    const res = await fetch(`/api/members?${params.toString()}`);
                    let result = await res.json();

                    this.members = result.data;
                    this.totalPages = Math.ceil(result.total / this.limit);
                } catch (err) {
                    console.error("โหลดข้อมูลผู้ใช้ล้มเหลว", err);
                }
            },

            async fetchFaculties() {
                try {
                    const res = await fetch("/api/faculties");
                    const result = await res.json();
                    if (result.success || result.data) {
                        this.faculties = result.data;
                    } else {
                        throw result;
                    }
                } catch (err) {
                    console.error("โหลดข้อมูลคณะล้มเหลว", err);
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

            resetFilters() {
                this.filters = {
                    major_id: "",
                    role: "",
                    search: "",
                };
                this.filterMajors = [];
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
                    role_id: "2", // <--- ล็อกค่า Role เป็น 2 
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
                    text: `ต้องการลบผู้ใช้ "${member.member_name}" ใช่หรือไม่?`,
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
                Swal.fire({
                    title: '📦 ทำรายการฝากของ',
                    html: `
                    <div class="text-left text-sm">
                        <p><b>สมาชิก:</b> ${member.member_name || 'ไม่ระบุชื่อ'}</p>
                        <p><b>เบอร์:</b> ${member.member_phone || 'ไม่ระบุ'}</p>
                        <p class="mt-3 text-gray-600">ไปยังหน้าฝากของสำหรับสมาชิกนี้</p>
                    </div>
                `,
                    showConfirmButton: true,
                    confirmButtonText: 'ไปที่หน้าฝาก',
                    showCancelButton: true,
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `/waste_center/transactions/waste_deposit_pos?member_id=${member.member_id}`;
                    }
                });
            },

            openDonationExchange(member) {
                this.quickMenuShow = false;
                Swal.fire({
                    title: '💰 แลกของบริจาค',
                    html: `
                    <div class="text-left text-sm">
                        <p><b>สมาชิก:</b> ${member.member_name || 'ไม่ระบุชื่อ'}</p>
                        <p><b>แต้มปัจจุบัน:</b> ${member.member_waste_point || 0}</p>
                        <p class="mt-3 text-gray-600">อัตรา: 1 บาท = 10 แต้ม</p>
                    </div>
                `,
                    showConfirmButton: true,
                    confirmButtonText: 'ไปแลก',
                    showCancelButton: true,
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `/waste_center/transactions/donation_exchange?member_id=${member.member_id}`;
                    }
                });
            },

            openRedeemReward(member) {
                this.quickMenuShow = false;
                Swal.fire({
                    title: '🎁 แลกของรางวัล',
                    html: `
                    <div class="text-left text-sm">
                        <p><b>สมาชิก:</b> ${member.member_name || 'ไม่ระบุชื่อ'}</p>
                        <p><b>แต้มปัจจุบัน:</b> ${member.member_waste_point || 0}</p>
                        <p class="mt-3 text-gray-600">เลือกของรางวัลตามแต้มที่มี</p>
                    </div>
                `,
                    showConfirmButton: true,
                    confirmButtonText: 'ไปแลก',
                    showCancelButton: true,
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `/waste_center/transactions/redeem_reward_pos?member_id=${member.member_id}`;
                    }
                });
            },

            openDonation(member) {
                this.quickMenuShow = false;
                Swal.fire({
                    title: '🎀 บริจาคสิ่งของ',
                    html: `
                    <div class="text-left text-sm">
                        <p><b>ผู้บริจาค:</b> ${member.member_name || 'ไม่ระบุชื่อ'}</p>
                        <p><b>เบอร์:</b> ${member.member_phone || 'ไม่ระบุ'}</p>
                        <p class="mt-3 text-gray-600">บันทึกการบริจาคของสิ่งประเมินค่า</p>
                    </div>
                `,
                    showConfirmButton: true,
                    confirmButtonText: 'บริจาค',
                    showCancelButton: true,
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `/waste_center/transactions/donation_pos?member_id=${member.member_id}`;
                    }
                });
            },
        };
    }

</script>