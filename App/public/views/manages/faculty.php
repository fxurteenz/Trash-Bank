<div class="space-y-4 w-full">
    <div class="bg-white rounded-lg shadow p-6 overflow-x-auto w-full">
    </div>

    <div x-data="FacultyMajor()" x-init="fetchAllFaculty()" class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-2">
            รายชื่อคณะ
        </h2>

        <div class="flex justify-between">

            <div class="flex gap-2">
                <div @click="openCreateFacultyDialog"
                    :class="createFacultyDialogShow && !isEditingFaculty && 'bg-teal-300'"
                    class="group cursor-pointer flex items-center py-1 px-2 border-2 border-teal-500 rounded-lg hover:bg-teal-300 space-x-1 w-fit transition-colors">
                    <button class="group-hover:rotate-90 duration-300 focus:outline-none" title="Add New">
                        <svg class="stroke-teal-500 fill-none group-active:stroke-teal-200 group-active:duration-0 duration-300"
                            viewBox="0 0 24 24" height="24" width="24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-width="1.5"
                                d="M12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22Z">
                            </path>
                            <path stroke-width="1.5" d="M8 12H16"></path>
                            <path stroke-width="1.5" d="M12 16V8"></path>
                        </svg>
                    </button>
                    <span class="font-medium">เพิ่ม</span>
                </div>

                <button x-show="selectedFaculty" @click="openEditFacultyDialog(selectedFaculty)"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="flex items-center space-x-1 bg-amber-100 text-amber-700 px-3 py-1 rounded-lg hover:bg-amber-200 border border-amber-200 font-medium text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>แก้ไข</span>
                </button>

                <button x-show="selectedFaculty" @click="confirmDeleteFaculty"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="flex items-center space-x-1 bg-red-100 text-red-700 px-3 py-1 rounded-lg hover:bg-red-200 border border-red-200 font-medium text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>ลบ</span>
                </button>
            </div>

        </div>

        <span class="text-xs font-light">รายชื่อคณะจากข้อมูลที่มีในระบบ</span>

        <div class="mt-2 text-lg text-gray-700 space-y-4 border-t border-gray-100 py-2">
            <h3 class="font-semibold">ดูข้อมูลคณะ</h3>
            <div class="grid grid-cols-2 md:grid-cols-3  gap-2">
                <template x-for="faculty in AllFacultyData" :key="faculty.faculty_id">
                    <div class="px-4 py-3 border border-gray-100 shadow-xs rounded transition duration-200 text-center hover:shadow-md hover:bg-sky-50 cursor-pointer"
                        :class="selectedFaculty?.faculty_id === faculty.faculty_id ? 'ring-2 ring-sky-400 bg-sky-50' : 'bg-white'"
                        @click="SelectFaculty(faculty)">
                        <span x-text="faculty.faculty_name"></span>
                    </div>
                </template>
            </div>

            <div x-show="selectedFacultyShow" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-3" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-3"
                class="overflow-x-auto pt-2 border border-gray-100 rounded">

                <div class="flex justify-between items-start px-2">
                    <h3 class="text-xl font-bold text-blue-700 text-shadow-xs"
                        x-text="`คณะ ${selectedFaculty ? selectedFaculty.faculty_name: 'กรุณาเลือกคณะ'}`"></h3>
                    <button @click="CloseFacultyDetail()"
                        class="text-gray-500 hover:text-gray-900 hover:cursor-pointer transition duration-200 hover:scale-105">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                <div class="p-4 rounded space-y-2">

                    <div class="flex justify-between items-center">
                        <h4 class="font-semibold text-gray-500">รายละเอียด</h4>

                    </div>

                 
                </div>
            </div>
        </div>

        <dialog x-show="createFacultyDialogShow" x-ref="createFacultyDialog"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-3"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-3"
            @click.self="createFacultyDialogShow = false" @close="createFacultyDialogShow = false"
            class="fixed inset-0 mx-auto my-auto p-0 bg-transparent"
            x-init="$watch('createFacultyDialogShow', value => {if (value) $refs.createFacultyDialog.showModal();else $refs.createFacultyDialog.close();})">

            <div class="bg-white p-6 rounded-lg shadow-xl w-80">
                <div class="flex justify-between ">
                    <h3 class="font-bold text-lg mb-3" x-text="isEditingFaculty ? 'แก้ไขคณะ' : 'เพิ่มข้อมูลคณะใหม่'">
                    </h3>
                    <svg class="text-gray-500 hover:text-gray-900 hover:cursor-pointer transition duration-200 hover:scale-105"
                        @click="createFacultyDialogShow = false" xmlns="http://www.w3.org/2000/svg" width="10"
                        height="10" viewBox="0 0 32 32">
                        <path fill="currentColor"
                            d="M24.879 2.879A3 3 0 1 1 29.12 7.12l-8.79 8.79a.125.125 0 0 0 0 .177l8.79 8.79a3 3 0 1 1-4.242 4.243l-8.79-8.79a.125.125 0 0 0-.177 0l-8.79 8.79a3 3 0 1 1-4.243-4.242l8.79-8.79a.125.125 0 0 0 0-.177l-8.79-8.79A3 3 0 0 1 7.12 2.878l8.79 8.79a.125.125 0 0 0 .177 0z"
                            stroke-width="0.2" stroke="currentColor" />
                    </svg>
                </div>
                <div class="grid grid-1 space-y-2 text-sm">
                    <div class="flex items-center justify-between text-sm">
                        <label for="create_fac_name">ชื่อคณะ</label>
                        <input
                            class="border border-gray-300 rounded p-1 focus:ring-sky-300 focus:ring-3 focus:border-sky-200"
                            type="text" id="create_fac_name" x-model="facultyForm.faculty_name">
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <label for="create_fac_code">ชื่อย่อคณะ</label>
                        <input
                            class="border border-gray-300 rounded p-1 focus:ring-sky-300 focus:ring-3 focus:border-sky-200"
                            type="text" id="create_fac_code" x-model="facultyForm.faculty_code">
                    </div>
                </div>

                <div class="mt-4 text-right">
                    <button @click="submitFacultyForm" class="px-3 py-1 text-white rounded cursor-pointer shadow"
                        :class="isEditingFaculty ? 'bg-amber-500 hover:bg-amber-600' : 'bg-sky-400 hover:bg-sky-500'"
                        x-text="isEditingFaculty ? 'บันทึกแก้ไข' : 'ยืนยันเพิ่ม'">
                    </button>
                    <button @click="createFacultyDialogShow = false"
                        class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 cursor-pointer">
                        ยกเลิก
                    </button>
                </div>
            </div>
        </dialog>

    </div>

</div>

<script>

</script>