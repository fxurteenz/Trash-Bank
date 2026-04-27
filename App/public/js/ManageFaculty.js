function FacultyMajor() {
    return {
        selectedFaculty: null,
        AllFacultyData: [],

        // Dialog States
        facultyDialogShow: false,

        // Edit States
        isEditingFaculty: false,

        // Forms
        facultyForm: {
            faculty_id: null,
            faculty_name: null,
            faculty_code: null,
        },
        facultyFormErrors: {},

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

        async fetchAllFaculty() {
            try {
                const res = await fetch(`/api/faculties`);
                const result = await res.json();

                if (result.success) {
                    this.AllFacultyData = result.data;
                }
            } catch (error) {
                console.error(error);
            }
        },

        // --- Faculty CRUD ---
        validateFacultyForm() {
            this.facultyFormErrors = {};
            const { faculty_name, faculty_code } = this.facultyForm;
            if (!faculty_name || faculty_name.trim() === '') {
                this.facultyFormErrors.faculty_name = 'กรุณากรอกชื่อคณะ';
            }
            return Object.keys(this.facultyFormErrors).length === 0;
        },

        openCreateFacultyDialog() {
            this.isEditingFaculty = false;
            this.facultyForm = {
                faculty_id: null,
                faculty_name: null,
                faculty_code: null,
            };
            this.facultyFormErrors = {};
            this.facultyDialogShow = true;
        },

        openEditFacultyDialog(faculty) {
            this.isEditingFaculty = true;
            this.facultyForm = { faculty_id: faculty.faculty_id, faculty_name: faculty.faculty_name, faculty_code: faculty.faculty_code };
            this.facultyFormErrors = {};
            this.facultyDialogShow = true;

        },

        async submitFacultyForm() {
            if (!this.validateFacultyForm()) {
                return;
            }
            this.facultyDialogShow = false;
            const action = this.isEditingFaculty ? "แก้ไข" : "เพิ่ม";
            const url = this.isEditingFaculty
                ? `/api/faculties/update/${this.facultyForm.faculty_id}`
                : "/api/faculties";

            try {
                const confirmed = await Swal.fire({
                    title: `${action}คณะ`,
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
                            text: `${action}คณะเรียบร้อยแล้ว`,
                            timer: 1500,
                            showConfirmButton: false,
                        });
                        this.fetchAllFaculty();
                        if (
                            this.isEditingFaculty &&
                            this.selectedFaculty &&
                            this.selectedFaculty.faculty_id ===
                            this.facultyForm.faculty_id
                        ) {
                            this.selectedFaculty = { ...this.facultyForm };
                        }
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
                    html: `<p>ไม่สามารถ${action}คณะได้</p><p></p> ${error.message}</p><br><hr><p class='text-xs'>หากพบปัญหาในการใช้งาน สามารถติดต่อศูนย์ฯด้วยตนเอง เพื่อดำเนินการแก้ไข</p>`,
                    timer: 5000,
                    showConfirmButton: true,
                    confirmButtonColor: '#009966',
                    confirmButtonText: "ปิด"
                });
                this.facultyDialogShow = true;
            }
        },

        confirmDeleteFaculty() {
            if (!this.selectedFaculty) return;
            Swal.fire({
                title: "ยืนยันการลบ?",
                html: `<p>ต้องการลบคณะ "${this.selectedFaculty.faculty_name}" ใช่หรือไม่?</p><br><hr><p class='text-xs text-red-500 mt-1'>กระบวนการเสี่ยงต่อความผิดพลาดของข้อมูล, กรุณาดำเนินการด้วยความระมัดระวัง</p>`,
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
                            this.fetchAllFaculty();

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