<div class="space-y-4 w-full">

    <div class="w-full grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-5 gap-2">
        <div class="app-card p-6 col-span-2 md:col-span-3 xl:col-span-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">ผู้ใช้ทั้งหมด</p>
                    <p class="text-2xl font-bold text-emerald-700">500</p>
                </div>
                <div class="p-3 bg-emerald-100 rounded-full text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M5.85 17.1q1.275-.975 2.85-1.537T12 15t3.3.563t2.85 1.537q.875-1.025 1.363-2.325T20 12q0-3.325-2.337-5.663T12 4T6.337 6.338T4 12q0 1.475.488 2.775T5.85 17.1M12 13q-1.475 0-2.488-1.012T8.5 9.5t1.013-2.488T12 6t2.488 1.013T15.5 9.5t-1.012 2.488T12 13m0 9q-2.075 0-3.9-.788t-3.175-2.137T2.788 15.9T2 12t.788-3.9t2.137-3.175T8.1 2.788T12 2t3.9.788t3.175 2.137T21.213 8.1T22 12t-.788 3.9t-2.137 3.175t-3.175 2.138T12 22"
                            stroke-width="0.5" stroke="currentColor" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div x-data="UserTable()" x-init="initData()" class="app-card p-6 overflow-x-auto w-full">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <h2 class="text-xl font-bold">รายชื่อผู้ใช้งาน</h2>
            <div class="flex gap-2">

            </div>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
            <div>
                <label for="filter_faculty" class="block text-xs font-medium text-gray-700 mb-1">คณะ</label>
                <select id="filter_faculty" x-model="filters.faculty_id" @change="handleFacultyFilterChange()"
                    class="bg-white border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 hover:cursor-pointer block w-full p-1">
                    <option value="">ทุกคณะ</option>
                    <template x-for="fac in faculties" :key="fac.faculty_id">
                        <option :value="fac.faculty_id" x-text="fac.faculty_name"></option>
                    </template>
                </select>
            </div>

            <div>
                <label for="filter_major" class="block text-xs font-medium text-gray-700 mb-1">สาขา</label>
                <select id="filter_major" x-model="filters.major_id" @change="handleFilterChange()"
                    class="bg-white border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 hover:cursor-pointer block w-full p-1"
                    :disabled="!filters.faculty_id">
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

        <div class="flex justify-between mb-1 text-gray-700 text-xs font-regular">
            <div class="flex justify-end mb-1 text-xs space-x-2">
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
                    class="group flex items-center py-1 px-2 border-2 border-orange-500 rounded-lg space-x-1 cursor-pointer hover:bg-orange-300 transition-all duration-300 disabled:cursor-not-allowed disabled:opacity-50"
                    @click="deleteCheckedUser" :disabled="checkedMembers.member_ids.length === 0">
                    <div :class="[checkedMembers.member_ids.length === 0 ? 'opacity-100' : 'opacity-100 group-hover:rotate-90']"
                        class="duration-300 transition-transform">
                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 16 16"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 8h8m2.5 0a6.5 6.5 0 1 1-13 0a6.5 6.5 0 0 1 13 0Z" stroke-width="1.5" />
                        </svg>
                    </div>
                    <span class="select-none">ลบ</span>
                </button>
            </div>
        </div>

        <table class="w-full table-fixed border-collapse border border-emerald-300 text-sm">
            <thead class="bg-emerald-100 text-xs">
                <tr>
                    <th class="border border-emerald-300 px-4 py-2 w-1/18">เลือก</th>
                    <th class="border border-emerald-300 bg-emerald-200 px-4 py-2 w-2/18">รหัสประจำตัว</th>
                    <th class="border border-emerald-300 px-4 py-2 w-2/18 hidden lg:table-cell">เบอร์โทรศัพท์</th>
                    <th class="border border-emerald-300 px-4 py-2 w-2/18">ชื่อ</th>
                    <th class="border border-emerald-300 px-4 py-2 w-2/18 hidden xl:table-cell">คณะ</th>
                    <th class="border border-emerald-300 px-4 py-2 w-2/18 hidden xl:table-cell">สาขา</th>
                    <th class="border border-emerald-300 px-4 py-2 w-3/18 hidden xl:table-cell">อีเมล์</th>
                    <th class="border border-emerald-300 px-4 py-2 w-2/18">บทบาท</th>
                    <th class="border border-emerald-300 px-4 py-2 w-1/18">คะแนน</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="member in members" :key="member.member_id">
                    <tr class="hover:bg-emerald-50 cursor-pointer"
                        :class="editUserForm && editUserForm.member_id == member.member_id ? 'bg-emerald-100' : ''">
                        <td class="border border-gray-300 px-2 py-2 text-center" @click.stop>
                            <input type="checkbox" class="p-1" :id="member.member_id" :value="member.member_id"
                                x-model="checkedMembers.member_ids">
                        </td>
                        <td @click="selectingRow(member)"
                            class="border border-gray-300 bg-emerald-100 px-2 py-2 overflow-hidden text-ellipsis text-xs "
                            x-text="member.member_personal_id ?? 'ไม่ระบุ'"></td>
                        <td @click="selectingRow(member)"
                            class="border border-gray-300 px-2 py-2 overflow-hidden text-ellipsis text-xs hidden lg:table-cell"
                            x-text="member.member_phone ?? 'ไม่ระบุ'"></td>

                        <td @click="selectingRow(member)"
                            class="border border-gray-300 px-2 py-2 overflow-hidden text-ellipsis text-xs"
                            x-text="member.member_name ?? 'ไม่มีชื่อ'"></td>

                        <td @click="selectingRow(member)"
                            class="border border-gray-300 px-2 py-2 overflow-hidden text-ellipsis text-xs hidden xl:table-cell"
                            x-text="member.faculty_name ?? 'ไม่ระบุ'"></td>

                        <td @click="selectingRow(member)"
                            class="border border-gray-300 px-2 py-2 overflow-hidden text-ellipsis text-xs hidden xl:table-cell"
                            x-text="member.major_name ?? 'ไม่ระบุ'"></td>

                        <td @click="selectingRow(member)"
                            class="border border-gray-300 px-2 py-2 overflow-hidden text-ellipsis text-xs hidden xl:table-cell"
                            x-text="member.member_email ?? 'ไม่ระบุ'"></td>

                        <td @click="selectingRow(member)"
                            class="border border-gray-300 px-2 py-2 overflow-hidden text-ellipsis text-xs"
                            x-text="member.role_name_th || 'ไม่ระบุ'">
                        </td>

                        <td @click="selectingRow(member)" class="border border-gray-300 px-2 py-2 text-xs"
                            x-text="member.member_score ?? '0'"></td>
                    </tr>
                </template>
            </tbody>
        </table>

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
            <div class="app-card p-6 w-96 max-w-full">
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
                        <input
                            class="border border-gray-300 rounded p-1.5 focus:ring-emerald-300 focus:ring-3 focus:border-emerald-200"
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
                        <input
                            class="border border-gray-300 rounded p-1.5 focus:ring-emerald-300 focus:ring-3 focus:border-emerald-200"
                            :class="{'border-red-500': errors.create.member_password}" type="password"
                            id="create_acc_pass" x-model="createUserForm.member_password" placeholder="8 character">
                        <span x-show="errors.create.member_password" class="text-red-500 text-xs">
                            กรุณากรอกรหัสผ่าน
                        </span>
                    </div>

                    <div class="flex flex-col space-y-1">
                        <label for="create_acc_personal_id" class="text-gray-700 font-medium">รหัสประจำตัว</label>
                        <input
                            class="border border-gray-300 rounded p-1.5 focus:ring-sky-300 focus:ring-3 focus:border-sky-200"
                            :class="{'border-red-500': errors.create.member_personal_id}" type="text"
                            id="create_acc_personal_id" x-model="createUserForm.member_personal_id"
                            placeholder="รหัสนักศึกษา/รหัสประจำตัวปชช.">
                    </div>

                    <div class="flex flex-col space-y-1">
                        <label for="create_acc_mail">อีเมล์</label>
                        <input
                            class="border border-gray-300 rounded p-1.5 focus:ring-sky-300 focus:ring-3 focus:border-sky-200"
                            type="text" id="create_acc_mail" x-model="createUserForm.member_email"
                            placeholder="อีเมลล์">
                    </div>

                    <div class="flex flex-col space-y-1">
                        <label for="create_acc_name">ชื่อ</label>
                        <input
                            class="border border-gray-300 rounded p-1.5 focus:ring-sky-300 focus:ring-3 focus:border-sky-200"
                            type="text" id="create_acc_name" x-model="createUserForm.member_name"
                            placeholder="ชื่อที่ใช้แสดงผล">
                    </div>

                    <div class="flex flex-col space-y-1">
                        <label for="create_acc_role" class="text-gray-700 font-medium">
                            บทบาท <span class="text-red-500">*</span>
                        </label>
                        <select id="create_acc_role" x-model="createUserForm.role_id"
                            class="border border-gray-300 rounded p-1.5 focus:ring-sky-300 focus:ring-3 focus:border-sky-200 bg-white"
                            :class="{'border-red-500': errors.create.role_id}">
                            <option value="">เลือกบทบาท</option>
                            <option value="1">ผู้ดูแลระบบ</option>
                            <option value="2">ผู้ใช้งานทั่วไป</option>
                            <option value="3">เจ้าหน้าที่จุดฝาก</option>
                            <option value="4">เจ้าหน้าที่ศูนย์ใหญ่</option>
                        </select>
                        <span x-show="errors.create.role_id" class="text-red-500 text-xs">
                            กรุณาเลือกบทบาท
                        </span>
                    </div>

                    <div class="flex flex-col space-y-1">
                        <label for="create_acc_faculty" class="text-gray-700 font-medium">
                            คณะ
                        </label>
                        <select id="create_acc_faculty" x-model="createUserForm.faculty_id"
                            @change="fetchMajorsByFaculty(createUserForm.faculty_id, 'create')"
                            class="border border-gray-300 rounded p-1.5 focus:ring-emerald-300 focus:ring-3 focus:border-emerald-200 bg-white"
                            :class="{'border-red-500': errors.create.faculty_id}">
                            <option value="">เลือกคณะ</option>
                            <template x-for="fac in faculties" :key="fac.faculty_id">
                                <option :value="fac.faculty_id" x-text="fac.faculty_name"></option>
                            </template>
                        </select>
                    </div>

                    <div class="flex flex-col space-y-1">
                        <label for="create_acc_major" class="text-gray-700 font-medium">
                            สาขา
                        </label>
                        <select id="create_acc_major" x-model="createUserForm.major_id"
                            class="border border-gray-300 rounded p-1.5 focus:ring-emerald-300 focus:ring-3 focus:border-emerald-200 bg-white"
                            :disabled="!createUserForm.faculty_id">
                            <option value="">เลือกสาขา</option>
                            <template x-for="major in createMajors" :key="major.major_id">
                                <option :value="major.major_id" x-text="major.major_name"></option>
                            </template>
                        </select>
                    </div>

                </div>

                <div class="mt-4 text-right">
                    <button @click="submitCreate"
                        class="px-3 py-1 bg-sky-300 rounded hover:bg-sky-400 cursor-pointer">ยืนยัน</button>
                    <button @click="createUserDialogShow = false"
                        class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 cursor-pointer">ยกเลิก</button>
                </div>
            </div>
        </dialog>

        <dialog x-ref="editUserDialog" x-show="editUserDialogShow" @click.self="editUserDialogShow = false"
            @close="editUserDialogShow = false" class="fixed inset-0 mx-auto my-auto p-0 bg-transparent z-50"
            x-init="$watch('editUserDialogShow', value => {if (value) $refs.editUserDialog.showModal();else $refs.editUserDialog.close();})">
            <div class="app-card p-6 w-96 max-w-full">
                <h3 class="font-bold text-lg mb-3">แก้ไขข้อมูล</h3>

                <div class="grid grid-1 space-y-2 text-xs">

                    <div class="flex flex-col space-y-1">
                        <label for="edit_acc_tel">
                            เบอร์โทรศัพท์ <span class="text-red-500">*</span>
                        </label>
                        <input
                            class="border border-gray-300 rounded p-1.5 focus:ring-emerald-300 focus:ring-3 focus:border-emerald-200"
                            type="text" id="edit_acc_tel" x-model="editUserForm.member_phone"
                            placeholder="เบอร์โทรศัพท์">
                        <span x-show="errors.edit.member_phone" class="text-red-500 text-xs">
                            กรุณากรอกหมายเลขโทรศัพท์
                        </span>
                    </div>

                    <div class="flex flex-col space-y-1">
                        <label for="edit_acc_personal_id" class="text-gray-700 font-medium">รหัสประจำตัว</label>
                        <input
                            class="border border-gray-300 rounded p-1.5 focus:ring-sky-300 focus:ring-3 focus:border-sky-200"
                            :class="{'border-red-500': errors.edit.member_personal_id}" type="text"
                            id="edit_acc_personal_id" x-model="editUserForm.member_personal_id"
                            placeholder="รหัสนักศึกษา/รหัสประจำตัวปชช.">
                    </div>

                    <div class="flex flex-col space-y-1">
                        <label for="edit_acc_mail">อีเมล์</label>
                        <input
                            class="border border-gray-300 rounded p-1.5 focus:ring-emerald-300 focus:ring-3 focus:border-emerald-200"
                            type="text" id="edit_acc_mail" x-model="editUserForm.member_email" placeholder="อีเมล์">
                    </div>

                    <div class="flex flex-col space-y-1">
                        <label for="edit_acc_name">ชื่อ</label>
                        <input
                            class="border border-gray-300 rounded p-1.5 focus:ring-emerald-300 focus:ring-3 focus:border-emerald-200"
                            type="text" id="edit_acc_name" x-model="editUserForm.member_name"
                            placeholder="ชื่อที่ใช้แสดงผล">
                    </div>

                    <div class="flex flex-col space-y-1">
                        <label for="edit_acc_role" class="text-gray-700 font-medium">บทบาท <span
                                class="text-red-500">*</span></label>
                        <select id="edit_acc_role" x-model="editUserForm.role_id"
                            class="border border-gray-300 rounded p-1.5 focus:ring-emerald-300 focus:ring-3 focus:border-emerald-200 bg-white"
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
                        <label for="edit_acc_faculty" class="text-gray-700 font-medium">
                            คณะ
                        </label>
                        <select id="edit_acc_faculty" x-model="editUserForm.faculty_id"
                            @change="fetchMajorsByFaculty(editUserForm.faculty_id, 'edit')"
                            class="border border-gray-300 rounded p-1.5 focus:ring-sky-300 focus:ring-3 focus:border-sky-200 bg-white"
                            :class="{'border-red-500': errors.edit.faculty_id}">
                            <option value="">เลือกคณะ</option>
                            <template x-for="fac in faculties" :key="fac.faculty_id">
                                <option :value="fac.faculty_id" x-text="fac.faculty_name"></option>
                            </template>
                        </select>
                    </div>

                    <div class="flex flex-col space-y-1">
                        <label for="edit_acc_major" class="text-gray-700 font-medium">
                            สาขา
                        </label>
                        <select id="edit_acc_major" x-model="editUserForm.major_id"
                            class="border border-gray-300 rounded p-1.5 focus:ring-sky-300 focus:ring-3 focus:border-sky-200 bg-white"
                            :disabled="!editUserForm.faculty_id">
                            <option value="">เลือกสาขา</option>
                            <template x-for="major in editMajors" :key="major.major_id">
                                <option :value="major.major_id" x-text="major.major_name"></option>
                            </template>
                        </select>
                    </div>

                </div>

                <div class="mt-4 text-right space-x-2">
                    <button @click="submitEdit"
                        class="px-4 py-2 bg-emerald-500 text-white rounded-full hover:bg-emerald-600 cursor-pointer font-medium transition-colors">ยืนยัน</button>
                    <button @click="editUserDialogShow = false"
                        class="px-4 py-2 bg-gray-200 rounded-full hover:bg-gray-300 cursor-pointer transition-colors">ยกเลิก</button>
                </div>
            </div>
        </dialog>

    </div>
</div>