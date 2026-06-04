<div x-data="Branch()" x-init="fetchAllBranch()" class="h-[calc(100vh-6rem)] w-full">
    <div class="bg-white rounded-md shadow p-6 overflow-hidden h-full">

        <div class="flex justify-between mb-4">
            <div class="">
                <h1 class="text-2xl font-bold text-slate-900">จัดการหน่วยย่อย</h1>
                <p class="text-slate-600 font-light text-sm">เพิ่ม/แก้ไข/ลบข้อมูลหน่วยย่อย</p>
            </div>
            <div @click="openCreateDialog" :class="DialogShow && 'bg-emerald-300'"
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
                <span class="font-medium text-sm ">เพิ่มหน่วยย่อย</span>
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
                            ชื่อหน่วยย่อย
                        </th>
                        <th
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-b bg-gray-50">
                            แต้มคงเหลือ
                        </th>
                        <th
                            class="px-1 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-b bg-gray-50">
                            จำนวนเจ้าหน้าที่
                        </th>
                        <th
                            class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-b bg-gray-50">
                            จัดการ
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <template x-for="(faculty, index) in AllBranchData" :key="faculty.faculty_id">
                        <tr :class="editingFacultyId === faculty.faculty_id ? 'bg-amber-50' : 'hover:bg-gray-50'"
                            class="transition duration-200 cursor-pointer"
                            @click="if(editingFacultyId !== faculty.faculty_id) { window.location.href = `/admin/manage/branch/detail/${faculty.faculty_id}`; selectedFaculty = faculty; }">
                            <td class="py-2 px-2 whitespace-nowrap text-sm text-gray-900" x-text="index + 1"></td>
                            <td class="py-2 px-2 whitespace-nowrap text-sm text-gray-900">
                                <div x-show="editingFacultyId !== faculty.faculty_id">
                                    <span x-text="`${faculty.faculty_code || ' ? '} : ${faculty.faculty_name}`"></span>
                                </div>
                                <div x-show="editingFacultyId === faculty.faculty_id" class="flex gap-2" @click.stop
                                    x-cloak>
                                    <input type="text" x-model="editFacultyForm.faculty_code"
                                        @keydown.enter="saveEditFaculty()"
                                        class="w-16 border border-gray-300 rounded p-1 text-sm bg-white focus:ring-amber-500 focus:border-amber-500 outline-none"
                                        placeholder="รหัสย่อ">
                                    <input type="text" x-model="editFacultyForm.faculty_name"
                                        @keydown.enter="saveEditFaculty()"
                                        class="w-full border border-gray-300 rounded p-1 text-sm bg-white focus:ring-amber-500 focus:border-amber-500 outline-none"
                                        placeholder="ชื่อหน่วยย่อย">
                                </div>
                            </td>
                            <td class="py-2 px-2 text-center whitespace-nowrap text-sm text-gray-900"
                                x-text="`${faculty.faculty_point || '0'}`"></td>
                            <td class="py-2 px-1 text-center whitespace-nowrap text-sm text-gray-900"
                                x-text="`${faculty.total_member || '0'}`"></td>
                            <td class="px-2 py-2 whitespace-nowrap text-center text-sm" @click.stop>
                                <div x-show="editingFacultyId !== faculty.faculty_id"
                                    class="flex justify-center items-center gap-2">
                                    <button @click.stop="startEditFaculty(faculty)"
                                        class="bg-gradient-to-br from-amber-400 to-amber-500 p-2 text-white hover:bg-gradient-to-br hover:from-amber-500 hover:to-amber-600 hover:scale-105 cursor-pointer rounded-md"
                                        title="แก้ไข">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button @click.stop="SelectFaculty(faculty); confirmDeleteFaculty()"
                                        class="bg-gradient-to-br from-red-400 to-red-500 p-2 text-white hover:bg-gradient-to-br hover:from-red-500 hover:to-red-600 hover:scale-105 cursor-pointer rounded-md"
                                        title="ลบ">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                                <div x-show="editingFacultyId === faculty.faculty_id"
                                    class="flex justify-center items-center gap-2" x-cloak>
                                    <button @click.stop="saveEditFaculty()"
                                        class="bg-gradient-to-br from-emerald-500 to-emerald-600 p-2 text-white hover:scale-105 cursor-pointer rounded-md"
                                        title="บันทึก">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                    <button @click.stop="cancelEditFaculty()"
                                        class="bg-gray-400 p-2 text-white hover:bg-gray-500 hover:scale-105 cursor-pointer rounded-md"
                                        title="ยกเลิก">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <dialog x-show="DialogShow" x-ref="createDialog"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-3"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-3"
            @click.self="DialogShow = false" @close="DialogShow = false"
            class="fixed inset-0 mx-auto my-auto p-0 bg-transparent"
            x-init="$watch('DialogShow', value => {if (value) $refs.createDialog.showModal();else $refs.createDialog.close();})">

            <div class="bg-white p-6 rounded-lg shadow-xl w-80">
                <div class="flex justify-between ">
                    <h3 class="font-bold text-lg mb-3">เพิ่มข้อมูลหน่วยย่อยใหม่</h3>
                    <svg class="text-gray-500 hover:text-gray-900 hover:cursor-pointer transition duration-200 hover:scale-105"
                        @click="DialogShow = false" xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                        viewBox="0 0 32 32">
                        <path fill="currentColor"
                            d="M24.879 2.879A3 3 0 1 1 29.12 7.12l-8.79 8.79a.125.125 0 0 0 0 .177l8.79 8.79a3 3 0 1 1-4.242 4.243l-8.79-8.79a.125.125 0 0 0-.177 0l-8.79 8.79a3 3 0 1 1-4.243-4.242l8.79-8.79a.125.125 0 0 0 0-.177l-8.79-8.79A3 3 0 0 1 7.12 2.878l8.79 8.79a.125.125 0 0 0 .177 0z"
                            stroke-width="0.2" stroke="currentColor" />
                    </svg>
                </div>
                <div class="grid grid-1 space-y-2 text-sm">
                    <div class="space-y-1">
                        <div class="flex items-center justify-between text-sm">
                            <label for="create_fac_name">ชื่อหน่วยย่อย</label>
                            <input
                                class="border border-gray-300 rounded p-1 focus:ring-sky-300 focus:ring-3 focus:border-sky-200"
                                :class="FormErrors.faculty_name && 'border-red-500'" type="text"
                                id="create_fac_name" x-model="facultyForm.faculty_name">
                        </div>
                        <div x-show="FormErrors.faculty_name" class="text-red-500 text-xs text-right"
                            x-text="FormErrors.faculty_name" x-cloak></div>
                    </div>
                    <div class="space-y-1">
                        <div class="flex items-center justify-between text-sm">
                            <label for="create_fac_code">ชื่อย่อหน่วยย่อย</label>
                            <input
                                class="border border-gray-300 rounded p-1 focus:ring-sky-300 focus:ring-3 focus:border-sky-200"
                                type="text" id="create_fac_code" x-model="facultyForm.faculty_code">
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-right">
                    <button @click="submitFacultyForm"
                        class="px-3 py-1 text-white rounded cursor-pointer shadow bg-sky-400 hover:bg-sky-500">
                        ยืนยันเพิ่ม
                    </button>
                    <button @click="DialogShow = false"
                        class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 cursor-pointer">
                        ยกเลิก
                    </button>
                </div>
            </div>
        </dialog>
    </div>
</div>

<script>
    function Branch() {
        return {
            selectedFaculty: null,
            AllBranchData: [],

            // Dialog States
            DialogShow: false,

            // Edit States
            editingFacultyId: null,
            editFacultyForm: {
                faculty_id: null,
                faculty_name: null,
                faculty_code: null,
            },

            // Forms
            facultyForm: {
                faculty_id: null,
                faculty_name: null,
                faculty_code: null,
            },
            FormErrors: {},

            CloseFacultyDetail() {
                setTimeout(() => {
                    this.selectedFaculty = null;
                    this.selectedMajorIds = [];
                }, 200);
            },

            SelectFaculty(faculty) {
                if (this.selectedFaculty?.faculty_id === faculty.faculty_id) return;
                this.selectedFaculty = faculty;
            },

            async fetchAllBranch() {
                try {
                    const res = await fetch(`/api/faculties?only_branch=true`);
                    const result = await res.json();

                    if (result.success) {
                        this.AllBranchData = result.data;
                    }
                } catch (error) {
                    console.error(error);
                }
            },

            // --- Faculty CRUD ---
            validateForm() {
                this.FormErrors = {};
                const { faculty_name, faculty_code } = this.facultyForm;
                if (!faculty_name || faculty_name.trim() === '') {
                    this.FormErrors.faculty_name = 'กรุณากรอกชื่อหน่วยย่อย';
                }
                return Object.keys(this.FormErrors).length === 0;
            },

            openCreateDialog() {
                this.facultyForm = {
                    faculty_id: null,
                    faculty_name: null,
                    faculty_code: null,
                };
                this.FormErrors = {};
                this.DialogShow = true;
            },

            startEditFaculty(faculty) {
                this.editingFacultyId = faculty.faculty_id;
                this.editFacultyForm = { ...faculty };
            },

            cancelEditFaculty() {
                this.editingFacultyId = null;
            },

            async submitFacultyForm() {
                if (!this.validateForm()) {
                    return;
                }
                this.DialogShow = false;
                const action = "เพิ่ม";
                const url = "/api/branches";

                try {
                    const confirmed = await Swal.fire({
                        title: `${action}หน่วยย่อย`,
                        text: "คุณตรวจสอบข้อมูลและแน่ใจแล้วใช่ไหม ?",
                        icon: "info",
                        showConfirmButton: true,
                        confirmButtonText: "ยืนยัน",
                        confirmButtonColor: "#ff8f4eff",
                        showCancelButton: true,
                        cancelButtonText: "ยกเลิก",
                        didOpen: () => {
                            Swal.getConfirmButton().focus();
                        }
                    });

                    if (confirmed.isConfirmed) {
                        const res = await fetch(url, {
                            method: "POST",
                            headers: { "Content-Type": "application/json" },
                            body: JSON.stringify(this.facultyForm),
                        });
                        const result = await res.json();

                        if (result.success) {
                            Swal.fire({
                                icon: "success",
                                title: "สำเร็จ",
                                text: `${action}หน่วยย่อยเรียบร้อยแล้ว`,
                                timer: 1500,
                                showConfirmButton: false,
                            });
                            this.fetchAllBranch();
                            this.facultyForm = {
                                faculty_id: null,
                                faculty_name: null,
                                faculty_code: null,
                            };
                        } else {
                            throw result;
                        }
                    }
                } catch (error) {
                    console.error(error);
                    await Swal.fire({
                        icon: "error",
                        title: "ไม่สำเร็จ",
                        html: `<p>ไม่สามารถ${action}หน่วยย่อยได้</p><p></p> ${error.message}</p><br><hr><p class='text-xs'>หากพบปัญหาในการใช้งาน สามารถติดต่อศูนย์ฯด้วยตนเอง เพื่อดำเนินการแก้ไข</p>`,
                        timer: 5000,
                        showConfirmButton: true,
                        confirmButtonColor: '#009966',
                        confirmButtonText: "ปิด"
                    });
                    this.DialogShow = true;
                }
            },

            async saveEditFaculty() {
                if (!this.editFacultyForm.faculty_name || this.editFacultyForm.faculty_name.trim() === '') {
                    Swal.fire('แจ้งเตือน', 'กรุณากรอกชื่อหน่วยย่อย', 'warning');
                    return;
                }

                try {
                    const payload = {
                        faculty_name: this.editFacultyForm.faculty_name,
                        faculty_code: this.editFacultyForm.faculty_code,
                    };
                    const res = await fetch(`/api/faculties/update/${this.editingFacultyId}`, {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify(payload),
                    });
                    const result = await res.json();

                    if (result.success) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'แก้ไขข้อมูลเรียบร้อย',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        this.editingFacultyId = null;
                        this.fetchAllBranch();
                    } else {
                        throw result;
                    }
                } catch (error) {
                    console.error(error);
                    Swal.fire({
                        icon: "error",
                        title: "ไม่สำเร็จ",
                        html: `<p>ไม่สามารถแก้ไขหน่วยย่อยได้</p><p></p> ${error.message}</p><br><hr><p class='text-xs'>หากพบปัญหาในการใช้งาน สามารถติดต่อศูนย์ฯด้วยตนเอง เพื่อดำเนินการแก้ไข</p>`,
                        timer: 5000,
                        showConfirmButton: true,
                        confirmButtonColor: '#009966',
                        confirmButtonText: "ปิด"
                    });
                }
            },

            confirmDeleteFaculty() {
                if (!this.selectedFaculty) return;
                Swal.fire({
                    title: "ยืนยันการลบ?",
                    html: `<p>ต้องการลบหน่วยย่อย "${this.selectedFaculty.faculty_name}" ใช่หรือไม่?</p><br><hr><p class='text-xs text-red-500 mt-1'>กระบวนการเสี่ยงต่อความผิดพลาดของข้อมูล, กรุณาดำเนินการด้วยความระมัดระวัง</p>`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "ลบเลย",
                    confirmButtonColor: "#d33",
                    didOpen: () => {
                        Swal.getConfirmButton().focus();
                    }
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const res = await fetch("/api/faculties/delete", {
                                method: "POST",
                                headers: { "Content-Type": "application/json" },
                                body: JSON.stringify({
                                    faculty_ids: [this.selectedFaculty.faculty_id],
                                }),
                            });
                            const data = await res.json();
                            if (data.success) {
                                Swal.fire({
                                    icon: "success",
                                    title: "ลบสำเร็จ",
                                    timer: 2000,
                                    showConfirmButton: false,
                                });
                                this.CloseFacultyDetail();
                                this.fetchAllBranch();

                            } else {
                                throw data;
                            }
                        } catch (error) {
                            Swal.fire({
                                icon: "error",
                                title: "ไม่สำเร็จ",
                                html: "<p>" + (error.message || "ไม่สามารถลบได้") + "</p><br><hr><p class='text-xs'>หากพบปัญหาในการใช้งาน สามารถติดต่อศูนย์ฯด้วยตนเอง เพื่อดำเนินการแก้ไข</p>",
                                timer: 5000,
                                showConfirmButton: true,
                                confirmButtonColor: '#009966',
                                confirmButtonText: "ปิด"
                            });
                        }
                    }
                });
            },
        };
    }
</script>