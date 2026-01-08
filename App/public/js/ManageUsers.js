function UserTable() {
    return {
        members: [],
        faculties: [], // Store Faculty list
        createMajors: [], // Store majors for create dialog
        editMajors: [], // Store majors for edit dialog
        filterMajors: [], // Store majors for filter dropdown
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
            faculty_id: "",
            major_id: "",
            role: "",
            search: "",
        },

        async initData() {
            await this.fetchMembers();
            await this.fetchFaculties();
        },

        async fetchMembers() {
            try {
                const params = new URLSearchParams();
                params.append("page", this.page);
                params.append("limit", this.limit);
                if (this.filters.faculty_id)
                    params.append("faculty_id", this.filters.faculty_id);
                if (this.filters.major_id)
                    params.append("major_id", this.filters.major_id);
                if (this.filters.role) params.append("role", this.filters.role);
                if (this.filters.search)
                    params.append("search", this.filters.search);

                const res = await fetch(`/api/members?${params.toString()}`);
                let result = await res.json();
                console.log(result.data);

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
        },

        async handleFacultyFilterChange() {
            await this.fetchMajorsByFaculty(this.filters.faculty_id, 'filter');
            this.filters.major_id = "";
            this.handleFilterChange();
        },

        resetFilters() {
            this.filters = {
                faculty_id: "",
                major_id: "",
                role: "",
                search: "",
            };
            this.filterMajors = [];
            this.page = 1;
            this.fetchMembers();
        },

        openCreateDialog() {
            this.createUserForm = {
                member_personal_id: "",
                member_name: "",
                member_email: "",
                member_password: "",
                faculty_id: "",
                major_id: "",
                role_id: "",
            };
            this.createMajors = [];
            this.errors.create = {};
            this.createUserDialogShow = true;
        },

        async selectingRow(user) {
            this.selectedUser = user;
            this.editUserForm = {
                member_personal_id: user.member_personal_id ?? "",
                member_phone: user.member_phone ?? null,
                member_name: user.member_name ?? null,
                member_email: user.member_email ?? null,
                faculty_id: user.faculty_id ?? "",
                major_id: user.major_id ?? "",
                role_id: user.role_id,
            };
            this.errors.edit = {};
            
            if (user.faculty_id) {
                await this.fetchMajorsByFaculty(user.faculty_id, 'edit');
            }

            console.log("Selected User:", user);
            console.log("Form Data:", this.editUserForm);

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
                        title: "ผิดพลาด",
                        text: "แก้ไขข้อมูลไม่สำเร็จ",
                        timer: 2000,
                        showConfirmButton: false,
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
                    title: "ผิดพลาด",
                    text: "เพิ่มรายชื่อไม่สำเร็จ",
                    timer: 2000,
                    showConfirmButton: false,
                });
                this.createUserDialogShow = true;
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
            });

            if (result.isConfirmed) {
                try {
                    const deleteRes = await fetch("/api/members/bulk-del", {
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
                        this.checkedMembers.member_ids = []; // Reset checked items
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
    };
}
