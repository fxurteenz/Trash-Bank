function UserTable() {
    return {
        users: [],
        faculties: [], // Store Faculty list
        majors: [], // Store Major list (dynamic)
        filterMajors: [], // Store Major list for filter
        checkedUser: { account_ids: [] },
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
            account_personal_id: "",
            account_tel: "",
            account_name: "",
            account_email: "",
            faculty_id: "",
            major_id: "",
            account_role: "",
        },
        createUserForm: {
            account_personal_id: "",
            account_tel: "",
            account_name: "",
            account_email: "",
            account_password: "",
            faculty_id: "",
            major_id: "",
            account_role: "",
        },
        filters: {
            faculty_id: "",
            major_id: "",
            role: "",
            search: "",
        },

        async initData() {
            await this.fetchUsers();
            await this.fetchFaculties();
        },

        async fetchUsers() {
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

                const res = await fetch(`/api/users?${params.toString()}`);
                let data = await res.json();
                this.users = data.result;
                this.totalPages = Math.ceil(data.total / this.limit);
            } catch (err) {
                console.error("โหลดข้อมูลผู้ใช้ล้มเหลว", err);
            }
        },

        async fetchFaculties() {
            try {
                const res = await fetch("/api/faculties");
                const data = await res.json();
                if (data.success || data.result) {
                    this.faculties = data.result;
                }
            } catch (err) {
                console.error("โหลดข้อมูลคณะล้มเหลว", err);
            }
        },

        async fetchMajors(facultyId) {
            this.majors = []; // Clear old majors first
            if (!facultyId) return;

            try {
                const res = await fetch(`/api/majors/faculty/${facultyId}`);
                const data = await res.json();
                if (data.success || data.result) {
                    this.majors = data.result;
                }
            } catch (err) {
                console.error("โหลดข้อมูลสาขาล้มเหลว", err);
            }
        },

        async fetchFilterMajors(facultyId) {
            this.filterMajors = [];
            this.filters.major_id = "";
            this.page = 1;

            if (!facultyId) {
                this.fetchUsers();
                ``;
                return;
            }

            try {
                const res = await fetch(`/api/majors/faculty/${facultyId}`);
                const data = await res.json();
                if (data.success || data.result) {
                    this.filterMajors = data.result;
                }
                this.fetchUsers();
            } catch (err) {
                console.error("โหลดข้อมูลสาขาล้มเหลว", err);
            }
        },

        handleFilterChange() {
            this.page = 1;
            this.fetchUsers();
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
            this.fetchUsers();
        },

        openCreateDialog() {
            this.createUserForm = {
                account_personal_id: "", // เพิ่ม field นี้ตอน reset
                account_name: "",
                account_email: "",
                account_password: "",
                faculty_id: "",
                major_id: "",
                account_role: "",
            };
            this.majors = [];
            this.errors.create = {};
            this.createUserDialogShow = true;
        },

        async selectingRow(user) {
            this.selectedUser = user;
            this.editUserForm = {
                account_personal_id: user.account_personal_id ?? "",
                account_tel: user.account_tel ?? null,
                account_name: user.account_name ?? null,
                account_email: user.account_email ?? null,
                faculty_id: user.faculty_id ?? "",
                account_role: user.account_role == "user" ? 1 : user.account_role == "faculty_staff" ?  2 : user.account_role == "operater" ? 3 : 4,
                major_id: "",
            };
            this.errors.edit = {};
            if (user.faculty_id) {
                await this.fetchMajors(user.faculty_id);
                this.editUserForm.major_id = user.major_id ?? "";
            } else {
                this.majors = [];
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

            if (!form.account_personal_id) {
                errors.account_personal_id = true;
                isValid = false;
            }
            if (!form.faculty_id) {
                errors.faculty_id = true;
                isValid = false;
            }
            if (!form.account_role) {
                errors.account_role = true;
                isValid = false;
            }
            if (formType === "create" && !form.account_password) {
                errors.account_password = true;
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
                        `/api/users/update/${this.selectedUser.account_id}`,
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
                        this.fetchUsers();
                    } else {
                        throw new Error(
                            response.message || "Something went wrong"
                        );
                    }
                } catch (error) {
                    Swal.fire({
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
                const res = await fetch(`/api/users`, {
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
                    this.fetchUsers();
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
            if (this.checkedUser.account_ids.length === 0) return;

            const result = await Swal.fire({
                title: "ลบข้อมูล",
                text: `ต้องการลบผู้ใช้ ${this.checkedUser.account_ids.length} รายการ ใช่หรือไม่?`,
                icon: "warning",
                showConfirmButton: true,
                confirmButtonText: "ยืนยันการลบ",
                confirmButtonColor: "#d33",
                showCancelButton: true,
                cancelButtonText: "ยกเลิก",
            });

            if (result.isConfirmed) {
                try {
                    const deleteRes = await fetch("/api/users/bulk-del", {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify(this.checkedUser),
                    });
                    const delResult = await deleteRes.json();

                    if (delResult.success) {
                        Swal.fire({
                            icon: "success",
                            title: "ลบสำเร็จ",
                            timer: 2000,
                            showConfirmButton: false,
                        });
                        this.checkedUser.account_ids = []; // Reset checked items
                        this.fetchUsers();
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
