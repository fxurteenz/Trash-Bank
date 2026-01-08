function FacultyMajor() {
    return {
        selectedFaculty: null,
        selectedFacultyShow: false,
        AllFacultyData: [],

        // Major States
        selectedMajor: null,
        selectedMajorShow: false,
        AllMajorData: [],
        
        // Faculty Major Modal
        facultyMajorModalShow: false,
        facultyMajorList: [],

        // Dialog States
        createFacultyDialogShow: false,
        createMajorDialogShow: false,

        // Edit States
        isEditingFaculty: false,
        isEditingMajor: false,

        // Forms
        facultyForm: {
            faculty_id: null,
            faculty_name: null,
            faculty_code: null,
        },
        majorForm: {
            major_id: null,
            major_name: null,
            faculty_id: null,
        },

        CloseFacultyDetail() {
            this.selectedFacultyShow = false;
            setTimeout(() => {
                this.selectedFaculty = null;
                this.selectedMajorIds = [];
            }, 200);
        },

        CloseMajorDetail() {
            this.selectedMajorShow = false;
            setTimeout(() => {
                this.selectedMajor = null;
            }, 200);
        },

        SelectFaculty(faculty) {
            if (this.selectedFaculty?.faculty_id === faculty.faculty_id) return;
            this.selectedFaculty = faculty;
            this.selectedFacultyShow = true;
        },

        async openFacultyMajorModal(faculty) {
            this.selectedFaculty = faculty;
            try {
                const res = await fetch(`/api/majors/faculty/${faculty.faculty_id}`);
                const result = await res.json();
                
                if (result.success) {
                    this.facultyMajorList = result.result;
                } else {
                    this.facultyMajorList = [];
                }
            } catch (error) {
                console.error(error);
                this.facultyMajorList = [];
            }
            this.facultyMajorModalShow = true;
        },

        SelectMajor(major) {
            if (this.selectedMajor?.major_id === major.major_id) return;
            this.selectedMajor = major;
            this.selectedMajorShow = true;
        },

        async fetchAllFaculty() {
            try {
                const res = await fetch("/api/faculties");
                const result = await res.json();

                if (result.success) {
                    this.AllFacultyData = result.data;
                }
            } catch (error) {
                console.error(error);
            }
        },

        async fetchAllMajors() {
            try {
                const res = await fetch("/api/majors");
                const result = await res.json();

                if (result.success) {
                    this.AllMajorData = result.result;
                }
            } catch (error) {
                console.error(error);
            }
        },

        // --- Major CRUD ---
        openCreateMajorDialog() {
            this.isEditingMajor = false;
            this.majorForm = {
                major_id: null,
                major_name: null,
                faculty_id: null,
            };
            this.createMajorDialogShow = true;
        },

        openEditMajorDialog(major) {
            this.isEditingMajor = true;
            this.majorForm = { ...major };
            this.createMajorDialogShow = true;
        },

        async submitMajorForm() {
            this.createMajorDialogShow = false;
            const action = this.isEditingMajor ? "แก้ไข" : "เพิ่ม";
            const url = this.isEditingMajor
                ? `/api/majors/update/${this.majorForm.major_id}`
                : "/api/majors";

            try {
                const confirmed = await Swal.fire({
                    title: `${action}สาขา`,
                    text: "คุณตรวจสอบข้อมูลและแน่ใจแล้วใช่ไหม ?",
                    icon: "info",
                    showConfirmButton: true,
                    confirmButtonText: "ยืนยัน",
                    confirmButtonColor: "#8b5cf6",
                    showCancelButton: true,
                    cancelButtonText: "ยกเลิก",
                });

                if (confirmed.isConfirmed) {
                    const res = await fetch(url, {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify(this.majorForm),
                    });
                    const result = await res.json();

                    if (result.success) {
                        Swal.fire({
                            icon: "success",
                            title: "สำเร็จ",
                            text: `${action}สาขาเรียบร้อยแล้ว`,
                            timer: 1500,
                            showConfirmButton: false,
                        });
                        this.fetchAllMajors();
                        if (
                            this.isEditingMajor &&
                            this.selectedMajor &&
                            this.selectedMajor.major_id === this.majorForm.major_id
                        ) {
                            this.selectedMajor = { ...this.majorForm };
                        }
                        this.majorForm = {
                            major_id: null,
                            major_name: null,
                            faculty_id: null,
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
                    text: `${action}สาขาไม่สำเร็จ`,
                });
                this.createMajorDialogShow = true;
            }
        },

        confirmDeleteMajor() {
            if (!this.selectedMajor) return;
            Swal.fire({
                title: "ยืนยันการลบ?",
                text: `ต้องการลบสาขา "${this.selectedMajor.major_name}" ใช่หรือไม่?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "ลบเลย",
                confirmButtonColor: "#d33",
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const res = await fetch("/api/majors/delete", {
                            method: "POST",
                            headers: { "Content-Type": "application/json" },
                            body: JSON.stringify({
                                major_ids: [this.selectedMajor.major_id],
                            }),
                        });
                        const data = await res.json();
                        if (data.success) {
                            Swal.fire(
                                "ลบสำเร็จ",
                                "ข้อมูลถูกลบเรียบร้อยแล้ว",
                                "success"
                            );
                            this.CloseMajorDetail();
                            this.fetchAllMajors();
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
