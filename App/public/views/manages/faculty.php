<div x-data="FacultyMajor()" x-init="fetchAllFaculty(), fetchAllFacultiesForSelect()"
    class="flex flex-col lg:flex-row gap-4 h-[calc(100vh-6rem)] w-full">

    <div class="bg-white rounded-md shadow p-6 lg:basis-2/3 flex flex-col overflow-hidden h-full">

        <div class="flex justify-between mb-4">
            <div class="">
                <h1 class="text-2xl font-bold text-slate-900">จัดการคณะ</h1>
                <p class="text-slate-600 font-light text-sm">เพิ่ม/แก้ไข/ลบข้อมูลคณะ</p>
            </div>
            <div @click="openCreateFacultyDialog" :class="facultyDialogShow && !isEditingFaculty && 'bg-emerald-300'"
                class="group cursor-pointer flex items-center py-2 px-2 border-2 border-emerald-500 rounded-full hover:bg-emerald-100 space-x-1 w-fit transition-colors font-medium text-emerald-700">
                <button class="group-hover:rotate-90 duration-300 focus:outline-none" title="Add New">
                    <svg class="stroke-emerald-600 fill-none group-active:stroke-emerald-300 group-active:duration-0 duration-300"
                        viewBox="0 0 24 24" height="24" width="24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-width="1.5"
                            d="M12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22Z">
                        </path>
                        <path stroke-width="1.5" d="M8 12H16"></path>
                        <path stroke-width="1.5" d="M12 16V8"></path>
                    </svg>
                </button>
                <span class="font-medium text-sm ">เพิ่มคณะ</span>
            </div>
        </div>

        <div class="mt-4 flex-1 overflow-auto relative">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                <thead class="bg-gray-50 sticky top-0 z-0 shadow-sm">
                    <tr>
                        <th
                            class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b bg-gray-50">
                            #
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b bg-gray-50">
                            ชื่อคณะ
                        </th>
                        <th
                            class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase tracking-wider border-b bg-gray-50">
                            จำนวนสาขา
                        </th>
                        <th
                            class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-b bg-gray-50">
                            จัดการ
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <template x-for="(faculty, index) in AllFacultyData" :key="faculty.faculty_id">
                        <tr class="hover:bg-gray-50 transition duration-200 cursor-pointer"
                            @click="filterMajorsByFaculty(faculty);selectedFaculty = faculty;"
                            :class="selectedFilterFacultyId === faculty.faculty_id ? 'bg-sky-100 ring-1 ring-sky-300' : (selectedFaculty?.faculty_id === faculty.faculty_id ? 'bg-sky-50' : '')">
                            <td class="py-2 px-2 whitespace-nowrap text-sm text-gray-900" x-text="index + 1"></td>
                            <td class="py-2 px-2 whitespace-nowrap text-sm text-gray-900"
                                x-text="`${faculty.faculty_code || ' ? '} : ${faculty.faculty_name}`"></td>
                            <td class="py-2 px-2 text-end whitespace-nowrap text-sm text-gray-900"
                                x-text="`${faculty.major_count_total || '0'}`"></td>
                            <td class="px-2 py-2 whitespace-nowrap text-center text-sm flex justify-center items-center gap-2"
                                @click.stop>
                                <button @click="openFacultyMajorModal(faculty)"
                                    class=" bg-sky-200 hover:bg-sky-300 border border-1 border-sky-400 text-sky-700 hover:cursor-pointer transition duration-200 px-3 py-1 rounded-full flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24">
                                        <g fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="3" />
                                            <path
                                                d="M20.188 10.934c.388.472.582.707.582 1.066s-.194.594-.582 1.066C18.768 14.79 15.636 18 12 18s-6.768-3.21-8.188-4.934c-.388-.472-.582-.707-.582-1.066s.194-.594.582-1.066C5.232 9.21 8.364 6 12 6s6.768 3.21 8.188 4.934Z" />
                                        </g>
                                    </svg> <span>&nbsp;ดู</span>
                                </button>
                                <button @click="openEditFacultyDialog(faculty)"
                                    class=" bg-amber-100 text-amber-700 hover:bg-amber-200 border border-amber-200 hover:cursor-pointer transition duration-200 px-3 py-1 rounded-full flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg> <span>&nbsp;แก้ไข</span>
                                </button>
                                <button @click="SelectFaculty(faculty); confirmDeleteFaculty(faculty)"
                                    class="bg-red-100 hover:bg-red-200 border border-red-200 hover:cursor-pointer text-red-700 cursor-pointer transition duration-200 px-3 py-1 rounded-full flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg> <span>&nbsp;ลบ</span>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <dialog x-show="facultyMajorModalShow" x-ref="facultyMajorModal"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-3"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-3"
            @click.self="facultyMajorModalShow = false" @close="facultyMajorModalShow = false"
            class="fixed inset-0 mx-auto my-auto p-0 bg-transparent"
            x-init="$watch('facultyMajorModalShow', value => {if (value) $refs.facultyMajorModal.showModal();else $refs.facultyMajorModal.close();})">

            <div class="bg-white p-6 rounded-lg shadow-xl w-[600px] max-h-[80vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-sky-700"
                        x-text="`สาขาในคณะ${selectedFaculty?.faculty_name || ''}`"></h3>
                    <button @click="facultyMajorModalShow = false"
                        class="text-gray-500 hover:text-gray-900 hover:cursor-pointer transition duration-200 hover:scale-105">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="space-y-2">
                    <template x-if="facultyMajorList.length === 0">
                        <div class="text-center py-8 text-gray-500">
                            <p>ยังไม่มีสาขาในคณะนี้</p>
                        </div>
                    </template>

                    <template x-for="major in facultyMajorList" :key="major.major_id">
                        <div class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-200">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="font-semibold text-gray-900" x-text="major.major_name"></h4>
                                    <p class="text-sm text-gray-500">รหัสสาขา: <span x-text="major.major_id"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="mt-4 text-right">
                    <button @click="facultyMajorModalShow = false"
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 cursor-pointer">
                        ปิด
                    </button>
                </div>
            </div>
        </dialog>

        <dialog x-show="facultyDialogShow" x-ref="createFacultyDialog"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-3"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-3"
            @click.self="facultyDialogShow = false" @close="facultyDialogShow = false"
            class="fixed inset-0 mx-auto my-auto p-0 bg-transparent"
            x-init="$watch('facultyDialogShow', value => {if (value) $refs.createFacultyDialog.showModal();else $refs.createFacultyDialog.close();})">

            <div class="bg-white p-6 rounded-lg shadow-xl w-80">
                <div class="flex justify-between ">
                    <h3 class="font-bold text-lg mb-3" x-text="isEditingFaculty ? 'แก้ไขคณะ' : 'เพิ่มข้อมูลคณะใหม่'">
                    </h3>
                    <svg class="text-gray-500 hover:text-gray-900 hover:cursor-pointer transition duration-200 hover:scale-105"
                        @click="facultyDialogShow = false" xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                        viewBox="0 0 32 32">
                        <path fill="currentColor"
                            d="M24.879 2.879A3 3 0 1 1 29.12 7.12l-8.79 8.79a.125.125 0 0 0 0 .177l8.79 8.79a3 3 0 1 1-4.242 4.243l-8.79-8.79a.125.125 0 0 0-.177 0l-8.79 8.79a3 3 0 1 1-4.243-4.242l8.79-8.79a.125.125 0 0 0 0-.177l-8.79-8.79A3 3 0 0 1 7.12 2.878l8.79 8.79a.125.125 0 0 0 .177 0z"
                            stroke-width="0.2" stroke="currentColor" />
                    </svg>
                </div>
                <div class="grid grid-1 space-y-2 text-sm">
                    <div class="space-y-1">
                        <div class="flex items-center justify-between text-sm">
                            <label for="create_fac_name">ชื่อคณะ</label>
                            <input
                                class="border border-gray-300 rounded p-1 focus:ring-sky-300 focus:ring-3 focus:border-sky-200"
                                :class="facultyFormErrors.faculty_name && 'border-red-500'" type="text"
                                id="create_fac_name" x-model="facultyForm.faculty_name">
                        </div>
                        <div x-show="facultyFormErrors.faculty_name" class="text-red-500 text-xs text-right"
                            x-text="facultyFormErrors.faculty_name" x-cloak></div>
                    </div>
                    <div class="space-y-1">
                        <div class="flex items-center justify-between text-sm">
                            <label for="create_fac_code">ชื่อย่อคณะ</label>
                            <input
                                class="border border-gray-300 rounded p-1 focus:ring-sky-300 focus:ring-3 focus:border-sky-200"
                                type="text" id="create_fac_code" x-model="facultyForm.faculty_code">
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-right">
                    <button @click="submitFacultyForm" class="px-3 py-1 text-white rounded cursor-pointer shadow"
                        :class="isEditingFaculty ? 'bg-amber-500 hover:bg-amber-600' : 'bg-sky-400 hover:bg-sky-500'"
                        x-text="isEditingFaculty ? 'บันทึกแก้ไข' : 'ยืนยันเพิ่ม'">
                    </button>
                    <button @click="facultyDialogShow = false"
                        class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 cursor-pointer">
                        ยกเลิก
                    </button>
                </div>
            </div>
        </dialog>
    </div>

    <div class="bg-white rounded-lg shadow p-6 lg:basis-1/3 flex flex-col overflow-hidden h-full">

        <div class="flex justify-between mb-4">
            <div class="">
                <h1 class="text-2xl font-bold text-slate-900">จัดการสาขา</h1>
                <p class="text-slate-600 font-light text-sm"
                    x-text="`ภายในคณะ ${selectedFaculty.faculty_name || 'ยังไม่เลือกคณะ'}`">
                </p>
            </div>
            <div class="flex gap-2">
                <div @click="openCreateMajorDialog" :class="majorDialogShow && !isEditingMajor && 'bg-emerald-300'"
                    class="group cursor-pointer flex items-center py-2 px-3 border-2 border-emerald-500 rounded-full hover:bg-emerald-300 space-x-1 w-fit transition-colors font-medium text-emerald-700">
                    <button class="group-hover:rotate-90 duration-300 focus:outline-none" title="Add New">
                        <svg class="stroke-emerald-500 fill-none group-active:stroke-emerald-200 group-active:duration-0 duration-300"
                            viewBox="0 0 24 24" height="24" width="24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-width="1.5"
                                d="M12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22Z">
                            </path>
                            <path stroke-width="1.5" d="M8 12H16"></path>
                            <path stroke-width="1.5" d="M12 16V8"></path>
                        </svg>
                    </button>
                    <span class="font-medium text-sm ">เพิ่มสาขา</span>
                </div>
            </div>
        </div>

        <div class="mt-2 text-lg text-gray-700 flex flex-col flex-1 border-t border-gray-100 py-2 overflow-hidden">

            <div class="flex items-center justify-between shrink-0 mb-3">
                <h3 class="font-semibold text-gray-800 text-base" x-text="'รายการสาขา'"></h3>
            </div>

            <div class="grid grid-cols-3 lg:grid-cols-1 gap-2 flex-1 overflow-y-auto pr-2 pb-2 content-start">
                <template x-if="!selectedFilterFacultyId">
                    <div class="flex items-center justify-center h-full text-gray-500 text-sm mt-10">
                        <p>กรุณาเลือกคณะเพื่อดูรายการสาขา</p>
                    </div>
                </template>
                <template x-if="selectedFilterFacultyId && AllMajorData.length === 0">
                    <div class="flex items-center justify-center h-full text-gray-500 text-sm mt-10">
                        <p>ไม่พบข้อมูลสาขาในคณะนี้</p>
                    </div>
                </template>
                <template x-if="selectedFilterFacultyId">
                    <template x-for="major in AllMajorData" :key="major.major_id">
                        <div class="grid grid-cols-2 px-4 py-3 border border-gray-100 shadow-xs rounded transition duration-200 hover:shadow-md"
                            :class="selectedMajor?.major_id === major.major_id ? 'ring-2 ring-purple-400 bg-purple-50' : 'bg-white'">
                            <div>
                                <div class="font-semibold text-base">
                                    <span class="text-truncated" x-text="major.major_name"></span>
                                    <span x-show="major.major_code" class="text-xs text-gray-500"
                                        x-text="`(${major.major_code})`"></span>
                                </div>
                            </div>
                            <div class="flex gap-1 justify-end items-end">
                                <button @click="SelectMajor(major);openEditMajorDialog(selectedMajor)"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 scale-90"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    class="bg-amber-100 text-amber-700 p-2 rounded-lg hover:bg-amber-200 border border-amber-200 font-medium text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>

                                <button @click="SelectMajor(major);confirmDeleteMajor()"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 scale-90"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    class="bg-red-100 text-red-700 p-2 rounded-lg hover:bg-red-200 border border-red-200 font-medium text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </template>
            </div>
        </div>

        <dialog x-show="majorDialogShow" x-ref="createMajorDialog" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-3" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-3" @click.self="majorDialogShow = false"
            @close="majorDialogShow = false" class="fixed inset-0 mx-auto my-auto p-0 bg-transparent"
            x-init="$watch('majorDialogShow', value => {if (value) $refs.createMajorDialog.showModal();else $refs.createMajorDialog.close();})">

            <div class="bg-white p-6 rounded-lg shadow-xl w-96">
                <div class="flex justify-between ">
                    <h3 class="font-bold text-lg mb-3" x-text="isEditingMajor ? 'แก้ไขสาขา' : 'เพิ่มสาขาใหม่'">
                    </h3>
                    <svg class="text-gray-500 hover:text-gray-900 hover:cursor-pointer transition duration-200 hover:scale-105"
                        @click="majorDialogShow = false" xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                        viewBox="0 0 32 32">
                        <path fill="currentColor"
                            d="M24.879 2.879A3 3 0 1 1 29.12 7.12l-8.79 8.79a.125.125 0 0 0 0 .177l8.79 8.79a3 3 0 1 1-4.242 4.243l-8.79-8.79a.125.125 0 0 0-.177 0l-8.79 8.79a3 3 0 1 1-4.243-4.242l8.79-8.79a.125.125 0 0 0 0-.177l-8.79-8.79A3 3 0 0 1 7.12 2.878l8.79 8.79a.125.125 0 0 0 .177 0z"
                            stroke-width="0.2" stroke="currentColor" />
                    </svg>
                </div>
                <div class="grid grid-1 space-y-3 text-sm">
                    <div class="flex flex-col space-y-1">
                        <label for="create_major_name" class="font-medium">ชื่อสาขา (ภาษาไทย)</label>
                        <input
                            class="border border-gray-300 rounded p-2 focus:ring-purple-300 focus:ring-2 focus:border-purple-200 focus:outline-none"
                            :class="majorFormErrors.major_name && 'border-red-500 ring-red-200'" type="text"
                            id="create_major_name" x-model="majorForm.major_name" placeholder="ระบุชื่อสาขา (ภาษาไทย)">
                        <div x-show="majorFormErrors.major_name" class="text-red-500 text-xs"
                            x-text="majorFormErrors.major_name" x-cloak></div>
                    </div>
                    <div class="flex flex-col space-y-1">
                        <label for="create_major_name_en" class="font-medium">ชื่อสาขา (ภาษาอังกฤษ)</label>
                        <input
                            class="border border-gray-300 rounded p-2 focus:ring-purple-300 focus:ring-2 focus:border-purple-200 focus:outline-none"
                            type="text" id="create_major_name_en" x-model="majorForm.major_name_en"
                            placeholder="ระบุชื่อสาขา (ภาษาอังกฤษ)">
                    </div>
                    <div class="flex flex-col space-y-1">
                        <label for="create_major_code" class="font-medium">อักษรย่อสาขา</label>
                        <input
                            class="border border-gray-300 rounded p-2 focus:ring-purple-300 focus:ring-2 focus:border-purple-200 focus:outline-none"
                            type="text" id="create_major_code" x-model="majorForm.major_code" placeholder="เช่น IT, CS">
                    </div>
                    <div class="flex flex-col space-y-1">
                        <label for="create_major_faculty" class="font-medium">เลือกคณะ</label>
                        <select
                            class="border border-gray-300 rounded p-2 focus:ring-purple-300 focus:ring-2 focus:border-purple-200 focus:outline-none"
                            :class="majorFormErrors.faculty_id && 'border-red-500 ring-red-200'"
                            id="create_major_faculty" x-model="majorForm.faculty_id">
                            <option value="">-- เลือกคณะ --</option>
                            <template x-for="faculty in facultyListForSelect" :key="faculty.faculty_id">
                                <option :value="faculty.faculty_id" x-text="faculty.faculty_name"></option>
                            </template>
                        </select>
                        <div x-show="majorFormErrors.faculty_id" class="text-red-500 text-xs"
                            x-text="majorFormErrors.faculty_id" x-cloak></div>
                    </div>
                </div>

                <div class="mt-4 text-right space-x-2">
                    <button @click="submitMajorForm" class="px-4 py-2 text-white rounded cursor-pointer shadow"
                        :class="isEditingMajor ? 'bg-amber-500 hover:bg-amber-600' : 'bg-purple-500 hover:bg-purple-600'"
                        x-text="isEditingMajor ? 'บันทึกแก้ไข' : 'ยืนยันเพิ่ม'">
                    </button>
                    <button @click="majorDialogShow = false"
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 cursor-pointer">
                        ยกเลิก
                    </button>
                </div>
            </div>
        </dialog>
    </div>
</div>