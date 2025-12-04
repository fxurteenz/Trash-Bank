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
            // console.log(this.selectedUser);
        },
        async submitEdit() {
            try {
                const res = await fetch(`/api/users/${this.selectedUser.account_id}`,
                    {
                        method: 'POST',
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify(this.editUserForm)
                    })
                const response = await res.json();
                if (response.success) {
                    this.loadUsers();
                    this.selectedUser = null;
                    this.editUserForm = {
                        account_name: '',
                        account_email: '',
                        faculty_id: '',
                        major_id: '',
                    };
                }
                console.log(response);
            } catch (error) {
                console.error(error);
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
                    this.loadUsers();
                    this.createUserForm = {
                        account_name: '',
                        account_email: '',
                        faculty_id: '',
                        major_id: '',
                    };
                }
                console.log(response);
            } catch (error) {
                console.error(error);
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
                    }

                } catch (error) {
                    console.error(error);
                }
            }
        },
    }
}