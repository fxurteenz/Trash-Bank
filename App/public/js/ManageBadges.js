function ManageBadges() {
    return {
        badges: [],
        totalBadges: 0,
        dialogShow: false,
        isEditing: false,
        page: 1,
        limit: 9,
        totalPages: 1,
        imagePreview: null,
        imageFile: null,

        filters: {
            search: "",
            badge_type: ""
        },

        form: {
            badge_id: null,
            badge_name: "",
            badge_description: "",
            badge_type: "",
            badge_condition: "",
            badge_bonus_score: 0,
            badge_image: null
        },

        async initData() {
            await this.fetchBadges();
        },

        handleImageUpload(event, type) {
            const file = event.target.files[0];
            if (file) {
                this.imageFile = file;
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        async fetchBadges() {
            try {
                const params = new URLSearchParams();
                params.append("page", this.page);
                params.append("limit", this.limit);
                if (this.filters.search) params.append("search", this.filters.search);
                if (this.filters.badge_type) params.append("badge_type", this.filters.badge_type);

                const res = await fetch(`/api/badges?${params.toString()}`);
                const result = await res.json();

                if (result.success) {
                    this.badges = result.data;
                    this.totalBadges = result.total;
                    this.totalPages = Math.ceil(result.total / this.limit);
                }
            } catch (err) {
                console.error("โหลดข้อมูลเหรียญตราล้มเหลว", err);
                Swal.fire({
                    icon: "error",
                    title: "เกิดข้อผิดพลาด",
                    text: "ไม่สามารถโหลดข้อมูลเหรียญตราได้"
                });
            }
        },

        getBadgeTypeText(type) {
            const types = {
                'achievement': 'ความสำเร็จ',
                'milestone': 'เป้าหมาย',
                'special': 'พิเศษ'
            };
            return types[type] || type;
        },

        openCreateDialog() {
            this.isEditing = false;
            this.imagePreview = null;
            this.imageFile = null;
            this.form = {
                badge_id: null,
                badge_name: "",
                badge_description: "",
                badge_type: "",
                badge_condition: "",
                badge_bonus_score: 0,
                badge_image: null
            };
            this.dialogShow = true;
        },

        openEditDialog(badge) {
            this.isEditing = true;
            this.imagePreview = null;
            this.imageFile = null;
            this.form = {
                badge_id: badge.badge_id,
                badge_name: badge.badge_name,
                badge_description: badge.badge_description || "",
                badge_type: badge.badge_type,
                badge_condition: badge.badge_condition || "",
                badge_bonus_score: badge.badge_bonus_score || 0,
                badge_image: badge.badge_image
            };
            this.dialogShow = true;
        },

        async submitForm() {
            // Validation
            if (!this.form.badge_name || !this.form.badge_type || !this.form.badge_condition) {
                Swal.fire({
                    icon: "warning",
                    title: "กรุณากรอกข้อมูลให้ครบถ้วน",
                    text: "ชื่อเหรียญตรา ประเภท และเงื่อนไขเป็นข้อมูลที่จำเป็น"
                });
                return;
            }

            try {
                const url = this.isEditing 
                    ? `/api/badges/update/${this.form.badge_id}`
                    : `/api/badges`;

                const formData = new FormData();
                formData.append('badge_name', this.form.badge_name);
                formData.append('badge_description', this.form.badge_description || '');
                formData.append('badge_type', this.form.badge_type);
                formData.append('badge_condition', this.form.badge_condition);
                formData.append('badge_bonus_score', this.form.badge_bonus_score || 0);
                
                if (this.imageFile) {
                    formData.append('badge_image', this.imageFile);
                }

                const res = await fetch(url, {
                    method: "POST",
                    body: formData
                });

                const result = await res.json();

                if (result.success) {
                    Swal.fire({
                        icon: "success",
                        title: "สำเร็จ",
                        text: this.isEditing ? "แก้ไขเหรียญตราเรียบร้อย" : "เพิ่มเหรียญตราเรียบร้อย",
                        timer: 1500,
                        showConfirmButton: false
                    });
                    this.dialogShow = false;
                    await this.fetchBadges();
                } else {
                    throw new Error(result.message || "เกิดข้อผิดพลาด");
                }
            } catch (err) {
                console.error("บันทึกข้อมูลล้มเหลว", err);
                Swal.fire({
                    icon: "error",
                    title: "เกิดข้อผิดพลาด",
                    text: err.message || "ไม่สามารถบันทึกข้อมูลได้"
                });
            }
        },

        async confirmDelete(badgeId) {
            const result = await Swal.fire({
                icon: "warning",
                title: "ยืนยันการลบ",
                text: "คุณแน่ใจหรือไม่ที่จะลบเหรียญตรานี้?",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "ลบ",
                cancelButtonText: "ยกเลิก"
            });

            if (result.isConfirmed) {
                await this.deleteBadge(badgeId);
            }
        },

        async deleteBadge(badgeId) {
            try {
                const res = await fetch("/api/badges/delete", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ badge_id: badgeId })
                });

                const result = await res.json();

                if (result.success) {
                    Swal.fire({
                        icon: "success",
                        title: "ลบเรียบร้อย",
                        timer: 1500,
                        showConfirmButton: false
                    });
                    await this.fetchBadges();
                } else {
                    throw new Error(result.message || "เกิดข้อผิดพลาด");
                }
            } catch (err) {
                console.error("ลบข้อมูลล้มเหลว", err);
                Swal.fire({
                    icon: "error",
                    title: "เกิดข้อผิดพลาด",
                    text: err.message || "ไม่สามารถลบข้อมูลได้"
                });
            }
        }
    };
}
