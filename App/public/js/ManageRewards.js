function ManageRewards() {
    return {
        rewards: [],
        totalRewards: 0,
        dialogShow: false,
        isEditing: false,
        page: 1,
        limit: 10,
        totalPages: 1,
        imagePreview: null,
        imageFile: null,

        filters: {
            search: "",
            active: ""
        },

        form: {
            reward_id: null,
            reward_name: "",
            reward_description: "",
            reward_point_required: 0,
            reward_stock: 0,
            reward_active: 1
        },

        async initData() {
            await this.fetchRewards();
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

        async fetchRewards() {
            try {
                const params = new URLSearchParams();
                params.append("page", this.page);
                params.append("limit", this.limit);
                if (this.filters.search) params.append("search", this.filters.search);
                if (this.filters.active !== "") params.append("active", this.filters.active);

                const res = await fetch(`/api/rewards?${params.toString()}`);
                const result = await res.json();

                if (result.success) {
                    this.rewards = result.data;
                    this.totalRewards = result.total;
                    this.totalPages = Math.ceil(result.total / this.limit);
                }
            } catch (err) {
                console.error("โหลดข้อมูลรางวัลล้มเหลว", err);
                Swal.fire({
                    icon: "error",
                    title: "เกิดข้อผิดพลาด",
                    text: "ไม่สามารถโหลดข้อมูลรางวัลได้"
                });
            }
        },

        openCreateDialog() {
            this.isEditing = false;
            this.imagePreview = null;
            this.imageFile = null;
            this.form = {
                reward_id: null,
                reward_name: "",
                reward_description: "",
                reward_point_required: 0,
                reward_stock: 0,
                reward_active: 1
            };
            this.dialogShow = true;
        },

        openEditDialog(reward) {
            this.isEditing = true;
            this.imagePreview = null;
            this.imageFile = null;
            this.form = {
                reward_id: reward.reward_id,
                reward_name: reward.reward_name,
                reward_description: reward.reward_description || "",
                reward_point_required: reward.reward_point_required,
                reward_stock: reward.reward_stock,
                reward_active: reward.reward_active,
                reward_image: reward.reward_image
            };
            this.dialogShow = true;
        },

        async submitForm() {
            // Validation
            if (!this.form.reward_name || !this.form.reward_point_required || this.form.reward_stock === null) {
                Swal.fire({
                    icon: "warning",
                    title: "กรุณากรอกข้อมูลให้ครบถ้วน",
                    text: "ชื่อรางวัล คะแนน และจำนวนคงเหลือเป็นข้อมูลที่จำเป็น"
                });
                return;
            }

            try {
                const url = this.isEditing 
                    ? `/api/rewards/update/${this.form.reward_id}`
                    : `/api/rewards`;

                const formData = new FormData();
                formData.append('reward_name', this.form.reward_name);
                formData.append('reward_description', this.form.reward_description || '');
                formData.append('reward_point_required', this.form.reward_point_required);
                formData.append('reward_stock', this.form.reward_stock);
                formData.append('reward_active', this.form.reward_active);
                
                if (this.imageFile) {
                    formData.append('reward_image', this.imageFile);
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
                        text: this.isEditing ? "แก้ไขรางวัลเรียบร้อย" : "เพิ่มรางวัลเรียบร้อย",
                        timer: 1500,
                        showConfirmButton: false
                    });
                    this.dialogShow = false;
                    await this.fetchRewards();
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

        async confirmDelete(rewardId) {
            const result = await Swal.fire({
                icon: "warning",
                title: "ยืนยันการลบ",
                text: "คุณแน่ใจหรือไม่ที่จะลบรางวัลนี้?",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "ลบ",
                cancelButtonText: "ยกเลิก"
            });

            if (result.isConfirmed) {
                await this.deleteReward(rewardId);
            }
        },

        async deleteReward(rewardId) {
            try {
                const res = await fetch("/api/rewards/delete", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ reward_id: rewardId })
                });

                const result = await res.json();

                if (result.success) {
                    Swal.fire({
                        icon: "success",
                        title: "ลบเรียบร้อย",
                        timer: 1500,
                        showConfirmButton: false
                    });
                    await this.fetchRewards();
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
