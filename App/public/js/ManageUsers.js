function UserTable() {
    return {
        users: [],
        selectedUser: null,
        checkedUser: {account_ids: []},
        page: 1,
        limit: 5,
        totalPages: 1,
        editUserForm: {
            account_name: '',
            account_email: '',
            faculty_id: '',
            major_id: ''
        },
        createUserForm: {
            account_name: '',
            account_email: '',
            account_password:'',
            faculty_id: '',
            major_id: ''
        },
        filterRoleDropdown: false,
        createUserDialogShow: false,
        editUserDialogShow:false,
        async loadUsers() {
            try {
                this.offset = (this.page - 1) * this.limit;
                const res = await fetch(`/api/users?page=${this.page}&limit=${this.limit}`);
                let data = await res.json();
                this.users = data.result;
                this.totalPages = Math.ceil(data.total / this.limit);
                // console.log(this.users);
            } catch (err) {
                console.error("โหลดข้อมูลล้มเหลว", err);
            }
        },
        selectingRow(user) {
            this.selectedUser = user;
            this.editUserForm = {
                account_name: user.account_name ?? '',
                account_email: user.account_email ?? '',
                faculty_id: user.faculty_id ?? '',
                major_id: user.major_id ?? '',
            };
            this.editUserDialogShow = true;
            // console.log(this.selectedUser);
        },
        async submitEdit() {
            this.editUserDialogShow = false;
            const result = await Swal.fire({
                title: 'แก้ไขข้อมูล',
                text: 'คุณตรวจสอบข้อมูลและแน่ใจแล้วใช่ไหม ?',
                icon:'info',
                theme: 'material-ui',
                showConfirmButton: true,
                confirmButtonText: "ยืนยัน",
                confirmButtonColor: "#ff8f4eff",
                showCancelButton: true,
                cancelButtonText: "ยกเลิก",
                cancelButtonColor:"#8a8a8aff"
            })

            if (result.isConfirmed) {
                try {
                    this.editUserDialogShow = false;
                    const res = await fetch(`/api/users/update/${this.selectedUser.account_id}`,
                        {
                            method: 'POST',
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify(this.editUserForm)
                        })
                    const response = await res.json();
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'แก้ไขสำเร็จ',
                            text: `แก้ไขข้อมูลเรียบร้อยแล้ว`,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        this.selectedUser = null;
                        this.loadUsers();
                        this.editUserForm = {
                            account_name: '',
                            account_email: '',
                            faculty_id: '',
                            major_id: '',
                        };
                    } else {
                        throw "something wrong !";
                    }
                    console.log(response);
                } catch (error) {
                    const errAlert = await Swal.fire({
                            icon: 'error',
                            title: 'ผิดพลาด',
                            text: 'ลบรายชื่อไม่สำเร็จ กรุณาลองใหม่อีกครั้ง',
                            timer: 2000,
                            showConfirmButton: false
                    });
                    console.log(errAlert);
                    this.editUserDialogShow = true;
                    console.error(error);
                }
            }
        },
        async submitCreate() {
            try {
                const res = await fetch(`/api/users`,
                    {
                        method: 'POST',
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify(this.createUserForm)
                    })
                const response = await res.json();
                if (response.success) {
                    this.createUserDialogShow = false;
                    this.createUserForm = {
                        account_name: '',
                        account_email: '',
                        faculty_id: '',
                        major_id: '',
                    };
                    Swal.fire({
                            icon: 'success',
                            title: 'เพิ่มสำเร็จ',
                            text: `เพ่ิมผู้ใช้งานเรียบร้อย`,
                            timer: 2000,
                            showConfirmButton: false
                        });

                    this.loadUsers();
                    
                } else {
                    throw "some thing wrong !";
                }
            } catch (error) {
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'ผิดพลาด',
                    text: 'เพิ่มรายชื่อไม่สำเร็จ กรุณาลองใหม่อีกครั้ง',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        },
        async deleteCheckedUser() {
            const result = await Swal.fire({
                title: 'ลบข้อมูล',
                text: 'คุณตรวจสอบข้อมูลและแน่ใจแล้วใช่ไหม ?',
                icon:'warning',
                theme: 'material-ui',
                showConfirmButton: true,
                confirmButtonText: "ยืนยันการลบ",
                confirmButtonColor: "#ff8f4eff",
                showCancelButton: true,
                cancelButtonText: "ยกเลิก",
                cancelButtonColor:"#8a8a8aff"
            })

            if (result.isConfirmed) {
                console.log("confirmed");
                console.log(JSON.stringify(this.checkedUser));
             
                try {
                    const deleteRes = await fetch('/api/users/bulk-del', {
                            method: 'POST',
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify(this.checkedUser)
                        })
                    const delResult = await deleteRes.json();
                    console.log(delResult);
                    
                    if (delResult.success){
                        Swal.fire({
                            icon: 'success',
                            title: 'ลบสำเร็จ',
                            text: `ลบข้อมูล ${this.checkedUser.length} รายการเรียบร้อย`,
                            timer: 2000,
                            showConfirmButton: false
                        });

                        this.checkedUser.account_ids = [];
                        this.loadUsers();
                    } else {
                        throw "some thing wrong !";
                    }

                } catch (error) {
                    console.error(error);
                    Swal.fire({
                            icon: 'error',
                            title: 'ผิดพลาด',
                            text: 'ลบรายชื่อไม่สำเร็จ กรุณาลองใหม่อีกครั้ง',
                            timer: 2000,
                            showConfirmButton: false
                        });
                }
            }
        },
    }
}