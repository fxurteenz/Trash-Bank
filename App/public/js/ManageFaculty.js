function FacultyMajor() {
    return {
        selectedFaculty: null,
        selectedFacultyShow: false,
        AllFacultyData: [],

        // Dialog States
        createFacultyDialogShow: false,

        // Edit States
        isEditingFaculty: false,

        // Forms
        facultyForm: {
            faculty_id: null,
            faculty_name: null,
            faculty_code: null,
        },

        CloseFacultyDetail() {
            this.selectedFacultyShow = false;
            setTimeout(() => {
                this.selectedFaculty = null;
                // this.FacultyMajorData = [];
                this.selectedMajorIds = [];
            }, 200);
        },

        SelectFaculty(faculty) {
            if (this.selectedFaculty?.faculty_id === faculty.faculty_id) return;
            this.selectedFaculty = faculty;
            // this.selectedMajorIds = [];
            // this.fetchFacultyMajor(faculty);
            this.selectedFacultyShow = true;
        },

        async fetchAllFaculty() {
            try {
                const res = await fetch("/api/faculties");
                const result = await res.json();
                // console.log(result);

                if (result.success) {
                    this.AllFacultyData = result.data;
                }
            } catch (error) {
                console.error(error);
            }
        },

        // --- Faculty CRUD ---
        openCreateFacultyDialog() {
            this.isEditingFaculty = false;
            this.facultyForm = {
                faculty_id: null,
                faculty_name: null,
                faculty_code: null,
            };
            this.createFacultyDialogShow = true;
        },

        openEditFacultyDialog(faculty) {
            this.isEditingFaculty = true;
            this.facultyForm = { ...faculty };
            this.createFacultyDialogShow = true;
        },

        async submitFacultyForm() {
            this.createFacultyDialogShow = false;
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
                    title: "ผิดพลาด",
                    text: `${action}คณะไม่สำเร็จ`,
                });
                this.createFacultyDialogShow = true;
            }
        },

        confirmDeleteFaculty() {
            if (!this.selectedFaculty) return;
            Swal.fire({
                title: "ยืนยันการลบ?",
                text: `ต้องการลบคณะ "${this.selectedFaculty.faculty_name}" ใช่หรือไม่?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "ลบเลย",
                confirmButtonColor: "#d33",
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        // สมมติ API ลบ
                        const res = await fetch("/api/faculties/delete", {
                            method: "POST",
                            headers: { "Content-Type": "application/json" },
                            body: JSON.stringify({
                                faculty_ids: [this.selectedFaculty.faculty_id],
                            }),
                        });
                        const data = await res.json();
                        if (data.success) {
                            Swal.fire(
                                "ลบสำเร็จ",
                                "ข้อมูลถูกลบเรียบร้อยแล้ว",
                                "success"
                            );
                            this.CloseFacultyDetail();
                            this.fetchAllFaculty();
                        } else {
                            throw data;
                        }
                    } catch (error) {
                        Swal.fire(
                            "ข้อผิดพลาด",
                            "ไม่สามารถลบข้อมูลได้",
                            "error"
                        );
                    }
                }
            });
        },
    };
}
