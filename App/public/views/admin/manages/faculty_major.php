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

            <div class="flex justify-end text-sm space-x-2">
                <div class="flex items-center px-2 py-1 text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M6.532 4.75h6.936c.457 0 .854 0 1.165.03c.307.028.685.095.993.348c.397.326.621.814.624 1.322c.002.39-.172.726-.34.992c-.168.27-.411.59-.695.964l-.031.04l-.01.013l-2.555 3.369c-.252.332-.315.42-.359.51a1.2 1.2 0 0 0-.099.297c-.02.1-.023.212-.023.634v4.243c0 .208 0 .412-.014.578c-.015.164-.052.427-.224.663c-.21.287-.537.473-.9.495c-.302.019-.547-.103-.69-.183c-.144-.08-.309-.195-.476-.31l-.989-.683l-.048-.033c-.191-.131-.403-.276-.562-.477a1.7 1.7 0 0 1-.303-.585c-.071-.244-.07-.5-.07-.738v-2.97c0-.422-.004-.534-.023-.634a1.2 1.2 0 0 0-.1-.297c-.043-.09-.106-.178-.358-.51L4.825 8.459l-.01-.012l-.03-.04c-.284-.375-.527-.695-.696-.965c-.167-.266-.34-.602-.339-.992a1.72 1.72 0 0 1 .624-1.322c.308-.253.686-.32.993-.349c.311-.029.707-.029 1.165-.029m.397 4l1.647 2.17l.035.047c.201.264.361.475.478.715q.154.317.222.665c.051.261.05.527.05.864v2.968c0 .158.001.247.005.314l.006.062a.2.2 0 0 0 .036.073l.041.034c.05.04.12.088.248.176l.941.65V13.21c0-.337 0-.603.051-.864q.068-.347.222-.665c.117-.24.277-.45.478-.715l.035-.046l1.646-2.17zm7.28-1.5c.195-.26.334-.45.43-.604c.08-.126.104-.188.11-.207a.22.22 0 0 0-.057-.134a1 1 0 0 0-.2-.032c-.232-.022-.556-.023-1.06-.023H6.568c-.504 0-.828 0-1.06.023a1 1 0 0 0-.2.032a.22.22 0 0 0-.057.134c.006.019.03.081.11.207c.096.155.235.344.43.604zm1.541 3.25a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 0 1.5h-3a.75.75 0 0 1-.75-.75m-1.5 2.5a.75.75 0 0 1 .75-.75h4.5a.75.75 0 0 1 0 1.5H15a.75.75 0 0 1-.75-.75m-.5 2.5a.75.75 0 0 1 .75-.75h5a.75.75 0 0 1 0 1.5h-5a.75.75 0 0 1-.75-.75m0 2.5a.75.75 0 0 1 .75-.75H17a.75.75 0 0 1 0 1.5h-2.5a.75.75 0 0 1-.75-.75"
                            stroke-width="0.2" stroke="currentColor" />
                    </svg>
                    ตัวกรอง
                </div>

                <div class="">
                    <label class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white"
                        for="default-search">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
                                class="w-4 h-4 text-gray-500 dark:text-gray-400">
                                <path d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" stroke-width="2"
                                    stroke-linejoin="round" stroke-linecap="round" stroke="currentColor"></path>
                            </svg>
                        </div>
                        <input required="" placeholder="Search"
                            class="block w-full py-1 ps-10 pe-10 text-sm text-gray-900 border border-gray-300 rounded bg-gray-50 focus:ring-blue-500 outline-none focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            id="default-search" type="text" />
                        <button
                            class="absolute end-0 bottom-1/2 translate-y-1/2 py-2.5 px-2 text-sm font-medium text-white bg-blue-700 rounded-r-md border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
                                class="w-4 h-2">
                                <path d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" stroke-width="2"
                                    stroke-linejoin="round" stroke-linecap="round" stroke="currentColor"></path>
                            </svg>
                        </button>
                    </div>
                </div>
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
                        <h4 class="font-semibold text-gray-500">สาขาที่พบ</h4>

                        <div class="flex items-center space-x-2 text-xs">
                            <button x-show="selectedMajorIds.length > 0" @click="deleteSelectedMajors"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 scale-90"
                                x-transition:enter-end="opacity-100 scale-100"
                                class="flex items-center space-x-1 bg-red-100 text-red-700 px-2 py-1 rounded-lg hover:bg-red-200 border border-red-200 font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span x-text="`ลบ (${selectedMajorIds.length})`"></span>
                            </button>

                            <div @click="openCreateMajorDialog"
                                :class="createMajorDialogShow && !isEditingMajor && 'bg-teal-300'"
                                class="group cursor-pointer flex items-center py-1 px-2 border-2 border-teal-500 rounded-lg hover:bg-teal-300 space-x-1 transition-colors">
                                <button class="group-hover:rotate-90 duration-300 focus:outline-none" title="Add New">
                                    <svg class="stroke-teal-500 fill-none group-active:stroke-teal-200 group-active:duration-0 duration-300"
                                        viewBox="0 0 24 24" height="18" width="18" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-width="1.5"
                                            d="M12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22Z">
                                        </path>
                                        <path stroke-width="1.5" d="M8 12H16"></path>
                                        <path stroke-width="1.5" d="M12 16V8"></path>
                                    </svg>
                                </button>
                                <span>เพิ่ม</span>
                            </div>

                            <div class="flex justify-end text-sm space-x-2">
                                <div class="flex items-center px-2 py-1 text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                        <path fill="currentColor"
                                            d="M6.532 4.75h6.936c.457 0 .854 0 1.165.03c.307.028.685.095.993.348c.397.326.621.814.624 1.322c.002.39-.172.726-.34.992c-.168.27-.411.59-.695.964l-.031.04l-.01.013l-2.555 3.369c-.252.332-.315.42-.359.51a1.2 1.2 0 0 0-.099.297c-.02.1-.023.212-.023.634v4.243c0 .208 0 .412-.014.578c-.015.164-.052.427-.224.663c-.21.287-.537.473-.9.495c-.302.019-.547-.103-.69-.183c-.144-.08-.309-.195-.476-.31l-.989-.683l-.048-.033c-.191-.131-.403-.276-.562-.477a1.7 1.7 0 0 1-.303-.585c-.071-.244-.07-.5-.07-.738v-2.97c0-.422-.004-.534-.023-.634a1.2 1.2 0 0 0-.1-.297c-.043-.09-.106-.178-.358-.51L4.825 8.459l-.01-.012l-.03-.04c-.284-.375-.527-.695-.696-.965c-.167-.266-.34-.602-.339-.992a1.72 1.72 0 0 1 .624-1.322c.308-.253.686-.32.993-.349c.311-.029.707-.029 1.165-.029m.397 4l1.647 2.17l.035.047c.201.264.361.475.478.715q.154.317.222.665c.051.261.05.527.05.864v2.968c0 .158.001.247.005.314l.006.062a.2.2 0 0 0 .036.073l.041.034c.05.04.12.088.248.176l.941.65V13.21c0-.337 0-.603.051-.864q.068-.347.222-.665c.117-.24.277-.45.478-.715l.035-.046l1.646-2.17zm7.28-1.5c.195-.26.334-.45.43-.604c.08-.126.104-.188.11-.207a.22.22 0 0 0-.057-.134a1 1 0 0 0-.2-.032c-.232-.022-.556-.023-1.06-.023H6.568c-.504 0-.828 0-1.06.023a1 1 0 0 0-.2.032a.22.22 0 0 0-.057.134c.006.019.03.081.11.207c.096.155.235.344.43.604zm1.541 3.25a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 0 1.5h-3a.75.75 0 0 1-.75-.75m-1.5 2.5a.75.75 0 0 1 .75-.75h4.5a.75.75 0 0 1 0 1.5H15a.75.75 0 0 1-.75-.75m-.5 2.5a.75.75 0 0 1 .75-.75h5a.75.75 0 0 1 0 1.5h-5a.75.75 0 0 1-.75-.75m0 2.5a.75.75 0 0 1 .75-.75H17a.75.75 0 0 1 0 1.5h-2.5a.75.75 0 0 1-.75-.75"
                                            stroke-width="0.2" stroke="currentColor" />
                                    </svg>
                                    ตัวกรอง
                                </div>
                                <div class="">
                                    <label class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white"
                                        for="default-search">Search</label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                            <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"
                                                aria-hidden="true" class="w-4 h-4 text-gray-500 dark:text-gray-400">
                                                <path d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" stroke-width="2"
                                                    stroke-linejoin="round" stroke-linecap="round"
                                                    stroke="currentColor"></path>
                                            </svg>
                                        </div>
                                        <input required="" placeholder="Search"
                                            class="block w-full py-1 ps-10 pe-10 text-sm text-gray-900 border border-gray-300 rounded bg-gray-50 focus:ring-blue-500 outline-none focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            id="default-search" type="text" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-container">
                        <template x-if="FacultyMajorDataLoading">
                            <table class="w-full table-auto border-collapse border border-gray-300 text-sm">
                                <thead class="bg-gray-200 text-xs">
                                    <tr>
                                        <th class="border border-gray-300 px-2 py-1 lg:px-4 lg:py-2 w-2/12 lg:w-2/12">
                                            เลือก</th>
                                        <th class="border border-gray-300 px-2 py-1 lg:px-4 lg:py-2 w-4/12  lg:w-4/12">
                                            ชื่อสาขา</th>
                                        <th class="border border-gray-300 px-2 py-1 lg:px-4 lg:py-2 w-2/12 lg:w-2/12">
                                            ผู้ใช้งาน</th>
                                        <th class="border border-gray-300 px-2 py-1 lg:px-4 lg:py-2 w-2/12 lg:w-2/12">
                                            เจ้าหน้าที่</th>
                                        <th class="border border-gray-300 px-2 py-1 lg:px-4 lg:py-2 w-2/12 lg:w-2/10">
                                            รวม</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="i in 5" :key="i">
                                        <tr>
                                            <td class="border border-gray-300 py-1 px-1.5 lg:px-2 lg:py-2">
                                                <div class="h-3 bg-gray-300 rounded mx-auto animate-pulse w-4"></div>
                                            </td>
                                            <td class="border border-gray-300 py-1 px-1.5 lg:px-2 lg:py-2">
                                                <div class="h-3 bg-gray-300 rounded animate-pulse w-10/12"></div>
                                            </td>
                                            <td class="border border-gray-300 py-1 px-1.5 lg:px-2 lg:py-2">
                                                <div class="h-3 bg-gray-300 rounded mx-auto animate-pulse w-3/4"></div>
                                            </td>
                                            <td class="border border-gray-300 py-1 px-1.5 lg:px-2 lg:py-2">
                                                <div class="h-3 bg-gray-300 rounded mx-auto animate-pulse w-3/4"></div>
                                            </td>
                                            <td class="border border-gray-300 py-1 px-1.5 lg:px-2 lg:py-2">
                                                <div class="h-3 bg-gray-300 rounded mx-auto animate-pulse w-3/4"></div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </template>

                        <div x-show="!FacultyMajorDataLoading">
                            <table class="w-full table-auto border-collapse border border-gray-300 text-sm">
                                <thead class="bg-gray-200 text-xs">
                                    <tr>
                                        <th class="border border-gray-300 px-2 py-1 lg:px-4 lg:py-2 w-10 text-center">
                                            <input type="checkbox" @change="toggleAllMajors"
                                                :checked="isAllMajorsSelected && FacultyMajorData.length > 0"
                                                class="p-1 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                        </th>
                                        <th class="border border-gray-300 px-2 py-1 lg:px-4 lg:py-2 w-4/12  lg:w-4/12">
                                            ชื่อสาขา
                                        </th>
                                        <th class="border border-gray-300 px-2 py-1 lg:px-4 lg:py-2 w-2/12 lg:w-2/12">
                                            ผู้ใช้งาน
                                        </th>
                                        <th class="border border-gray-300 px-2 py-1 lg:px-4 lg:py-2 w-2/12 lg:w-2/12">
                                            เจ้าหน้าที่
                                        </th>
                                        <th class="border border-gray-300 px-2 py-1 lg:px-4 lg:py-2 w-2/12 lg:w-2/10">
                                            รวม
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="major in FacultyMajorData" :key="major.major_id">
                                        <tr class="hover:bg-amber-50 cursor-pointer group"
                                            @click="openEditMajorDialog(major)">

                                            <td class="border border-gray-300 py-1 px-1.5 lg:px-2 lg:py-2 text-center"
                                                @click.stop>
                                                <input type="checkbox" :value="major.major_id"
                                                    x-model="selectedMajorIds"
                                                    class="p-1 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                            </td>

                                            <td class="border border-gray-300 py-1 px-1.5 lg:px-2 lg:py-2 overflow-hidden text-ellipsis group-hover:text-amber-700 font-medium"
                                                x-text="major.major_name"></td>
                                            <td class="border border-gray-300 py-1 px-1.5 lg:px-2 lg:py-2 overflow-hidden text-ellipsis text-center"
                                                x-text="major.count_user"></td>
                                            <td class="border border-gray-300 py-1 px-1.5 lg:px-2 lg:py-2 overflow-hidden text-ellipsis text-center"
                                                x-text="major.count_staff"></td>
                                            <td class="border border-gray-300 py-1 px-1.5 lg:px-2 lg:py-2 overflow-hidden text-ellipsis text-center"
                                                x-text="major.total_all"></td>
                                        </tr>
                                    </template>
                                    <tr x-show="FacultyMajorData?.length == 0">
                                        <td class="border border-gray-300 py-1 px-1.5 lg:px-2 lg:py-2 overflow-hidden text-ellipsis text-center"
                                            colspan="5">
                                            ไม่พบข้อมูลสาขา</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
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

        <dialog x-show="createMajorDialogShow" x-ref="createMajorDialog"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-3"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-3"
            @click.self="createMajorDialogShow = false" @close="createMajorDialogShow = false"
            class="fixed inset-0 mx-auto my-auto p-0 bg-transparent"
            x-init="$watch('createMajorDialogShow', value => {if (value) $refs.createMajorDialog.showModal();else $refs.createMajorDialog.close();})">

            <div class="bg-white p-6 rounded-lg shadow-xl w-100">
                <div class="flex justify-between ">
                    <h3 class="font-bold text-lg mb-3" x-text="isEditingMajor ? 'แก้ไขสาขา' : 'เพิ่มข้อมูลสาขาใหม่'">
                    </h3>
                    <svg class="text-gray-500 hover:text-gray-900 hover:cursor-pointer transition duration-200 hover:scale-105"
                        @click="createMajorDialogShow = false" xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                        viewBox="0 0 32 32">
                        <path fill="currentColor"
                            d="M24.879 2.879A3 3 0 1 1 29.12 7.12l-8.79 8.79a.125.125 0 0 0 0 .177l8.79 8.79a3 3 0 1 1-4.242 4.243l-8.79-8.79a.125.125 0 0 0-.177 0l-8.79 8.79a3 3 0 1 1-4.243-4.242l8.79-8.79a.125.125 0 0 0 0-.177l-8.79-8.79A3 3 0 0 1 7.12 2.878l8.79 8.79a.125.125 0 0 0 .177 0z"
                            stroke-width="0.2" stroke="currentColor" />
                    </svg>
                </div>
                <div class="grid grid-1 space-y-2 text-sm">
                    <template x-if="selectedFaculty">
                        <div class="flex items-center justify-between text-sm">
                            <label for="create_maj_fac_name">สังกัดคณะ</label>
                            <input
                                class="border border-gray-300 bg-gray-100 rounded p-1 focus:ring-sky-300 focus:ring-3 focus:border-sky-200"
                                type="text" id="create_maj_fac_name" x-model="selectedFaculty.faculty_name" disabled>
                        </div>
                    </template>
                    <div class="flex items-center justify-between text-sm">
                        <label for="create_maj_name">ชื่อสาขา</label>
                        <input
                            class="border border-gray-300 rounded p-1 focus:ring-sky-300 focus:ring-3 focus:border-sky-200"
                            type="text" id="create_maj_name" x-model="majorForm.major_name">
                    </div>

                </div>

                <div class=" mt-4 text-right">
                    <button @click="submitMajorForm" class="px-3 py-1 text-white rounded cursor-pointer shadow"
                        :class="isEditingMajor ? 'bg-amber-500 hover:bg-amber-600' : 'bg-sky-400 hover:bg-sky-500'"
                        x-text="isEditingMajor ? 'บันทึกแก้ไข' : 'ยืนยันเพิ่ม'">
                    </button>
                    <button @click="createMajorDialogShow = false"
                        class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 cursor-pointer">
                        ยกเลิก
                    </button>
                </div>
            </div>
        </dialog>
    </div>

</div>

<script>
    function FacultyMajor() {
        return {
            selectedFaculty: null,
            selectedFacultyShow: false,
            AllFacultyData: [],
            FacultyMajorDataLoading: false,
            FacultyMajorData: [],

            // Dialog States
            createFacultyDialogShow: false,
            createMajorDialogShow: false,

            // Edit States
            isEditingFaculty: false,
            isEditingMajor: false,

            // Selection States (For Majors)
            selectedMajorIds: [],

            // Forms
            facultyForm: {
                faculty_id: null,
                faculty_name: null
            },
            majorForm: {
                major_id: null,
                major_name: null
            },

            CloseFacultyDetail() {
                this.selectedFacultyShow = false;
                setTimeout(() => {
                    this.selectedFaculty = null;
                    this.FacultyMajorData = [];
                    this.selectedMajorIds = [];
                }, 200);
            },

            SelectFaculty(faculty) {
                if (this.selectedFaculty?.faculty_id === faculty.faculty_id) return;
                this.selectedFaculty = faculty;
                this.selectedMajorIds = [];
                this.fetchFacultyMajor(faculty);
                this.selectedFacultyShow = true;
            },

            async fetchAllFaculty() {
                try {
                    const res = await fetch("/api/faculties");
                    const result = await res.json();
                    if (result.result) {
                        this.AllFacultyData = result.result;
                    }
                } catch (error) {
                    console.error(error);
                }
            },

            async fetchFacultyMajor(faculty) {
                this.FacultyMajorDataLoading = true;
                this.FacultyMajorData = [];
                try {
                    const res = await fetch(`/api/majors/faculty/${faculty.faculty_id}`);
                    const result = await res.json();
                    if (result.result) {
                        this.FacultyMajorData = result.result;
                    }
                } catch (error) {
                    console.error(error);
                } finally {
                    this.FacultyMajorDataLoading = false;
                }
            },

            // --- Faculty CRUD ---
            openCreateFacultyDialog() {
                this.isEditingFaculty = false;
                this.facultyForm = { faculty_id: null, faculty_name: null };
                this.createFacultyDialogShow = true;
            },

            openEditFacultyDialog(faculty) {
                this.isEditingFaculty = true;
                this.facultyForm = { ...faculty };
                this.createFacultyDialogShow = true;
            },

            async submitFacultyForm() {
                this.createFacultyDialogShow = false;
                const action = this.isEditingFaculty ? 'แก้ไข' : 'เพิ่ม';
                const url = this.isEditingFaculty ? `/api/faculties/update/${this.facultyForm.faculty_id}` : '/api/faculties';

                try {
                    const confirmed = await Swal.fire({
                        title: `${action}คณะ`,
                        text: 'คุณตรวจสอบข้อมูลและแน่ใจแล้วใช่ไหม ?',
                        icon: 'info',
                        showConfirmButton: true,
                        confirmButtonText: "ยืนยัน",
                        confirmButtonColor: "#ff8f4eff",
                        showCancelButton: true,
                        cancelButtonText: "ยกเลิก"
                    });

                    if (confirmed.isConfirmed) {
                        const res = await fetch(url, {
                            method: 'POST',
                            headers: { "Content-Type": "application/json" },
                            body: JSON.stringify(this.facultyForm)
                        });
                        const result = await res.json();

                        if (result.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'สำเร็จ',
                                text: `${action}คณะเรียบร้อยแล้ว`,
                                timer: 1500,
                                showConfirmButton: false
                            });
                            this.fetchAllFaculty();
                            if (this.isEditingFaculty && this.selectedFaculty && this.selectedFaculty.faculty_id === this.facultyForm.faculty_id) {
                                this.selectedFaculty = { ...this.facultyForm };
                            }
                            this.facultyForm = { faculty_id: null, faculty_name: null };
                        } else {
                            throw result;
                        }
                    }
                } catch (error) {
                    console.error(error);
                    await Swal.fire({ icon: 'error', title: 'ผิดพลาด', text: `${action}คณะไม่สำเร็จ` });
                    this.createFacultyDialogShow = true;
                }
            },

            confirmDeleteFaculty() {
                if (!this.selectedFaculty) return;
                Swal.fire({
                    title: 'ยืนยันการลบ?',
                    text: `ต้องการลบคณะ "${this.selectedFaculty.faculty_name}" ใช่หรือไม่?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'ลบเลย',
                    confirmButtonColor: '#d33'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            // สมมติ API ลบ
                            const res = await fetch('/api/faculties/delete', {
                                method: 'POST',
                                headers: { "Content-Type": "application/json" },
                                body: JSON.stringify({ faculty_ids: [this.selectedFaculty.faculty_id] })
                            });
                            const data = await res.json();
                            if (data.success) {
                                Swal.fire('ลบสำเร็จ', 'ข้อมูลถูกลบเรียบร้อยแล้ว', 'success');
                                this.CloseFacultyDetail();
                                this.fetchAllFaculty();
                            } else {
                                throw data;
                            }
                        } catch (error) {
                            Swal.fire('ข้อผิดพลาด', 'ไม่สามารถลบข้อมูลได้', 'error');
                        }
                    }
                });
            },

            // --- Major CRUD ---
            openCreateMajorDialog() {
                this.isEditingMajor = false;
                this.majorForm = { major_id: null, major_name: null };
                this.createMajorDialogShow = true;
            },

            openEditMajorDialog(major) {
                this.isEditingMajor = true;
                this.majorForm = { ...major };
                this.createMajorDialogShow = true;
            },

            async submitMajorForm() {
                this.createMajorDialogShow = false;
                const action = this.isEditingMajor ? 'แก้ไข' : 'เพิ่ม';
                const url = this.isEditingMajor ? `/api/majors/update/${this.majorForm.major_id}` : '/api/majors';

                // Payload - if create need faculty_id, if update only name
                const payload = {
                    major_name: this.majorForm.major_name
                };
                if (!this.isEditingMajor) {
                    payload.faculty_id = this.selectedFaculty.faculty_id;
                }

                try {
                    const confirmed = await Swal.fire({
                        title: `${action}สาขา`,
                        text: 'คุณตรวจสอบข้อมูลและแน่ใจแล้วใช่ไหม ?',
                        icon: 'info',
                        showConfirmButton: true,
                        confirmButtonText: "ยืนยัน",
                        confirmButtonColor: "#ff8f4eff",
                        showCancelButton: true,
                        cancelButtonText: "ยกเลิก"
                    });

                    if (confirmed.isConfirmed) {
                        const res = await fetch(url, {
                            method: 'POST',
                            headers: { "Content-Type": "application/json" },
                            body: JSON.stringify(payload)
                        });
                        const result = await res.json();

                        if (result.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'สำเร็จ',
                                text: `${action}สาขาเรียบร้อยแล้ว`,
                                timer: 1500,
                                showConfirmButton: false
                            });
                            this.fetchFacultyMajor(this.selectedFaculty);
                            this.majorForm = { major_id: null, major_name: null };
                        } else {
                            throw result;
                        }
                    }
                } catch (error) {
                    console.error(error);
                    Swal.fire({ icon: 'error', title: 'ผิดพลาด', text: `${action}สาขาไม่สำเร็จ` });
                    this.createMajorDialogShow = true;
                }
            },

            // --- Major Selection Logic ---
            get isAllMajorsSelected() {
                return this.FacultyMajorData.length > 0 && this.selectedMajorIds.length === this.FacultyMajorData.length;
            },

            toggleAllMajors() {
                if (this.isAllMajorsSelected) {
                    this.selectedMajorIds = [];
                } else {
                    this.selectedMajorIds = this.FacultyMajorData.map(m => m.major_id);
                }
            },

            deleteSelectedMajors() {
                Swal.fire({
                    title: 'ยืนยันการลบ?',
                    text: `คุณต้องการลบ ${this.selectedMajorIds.length} รายการที่เลือกใช่หรือไม่?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'ลบเลย',
                    confirmButtonColor: '#d33'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const res = await fetch('/api/majors/delete', {
                                method: 'POST',
                                headers: { "Content-Type": "application/json" },
                                body: JSON.stringify({ major_ids: this.selectedMajorIds })
                            });
                            const data = await res.json();
                            if (data.success) {
                                Swal.fire('ลบสำเร็จ', 'ข้อมูลถูกลบเรียบร้อยแล้ว', 'success');
                                this.selectedMajorIds = [];
                                this.fetchFacultyMajor(this.selectedFaculty);
                            } else {
                                throw data;
                            }
                        } catch (error) {
                            Swal.fire('ข้อผิดพลาด', 'ไม่สามารถลบข้อมูลได้', 'error');
                        }
                    }
                });
            }
        }
    }
</script>