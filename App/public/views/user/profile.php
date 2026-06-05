<div class="grid gap-0 pb-6" x-data="profileData()">
    <!-- Top Bar -->
    <div
        class="bg-white px-4 pt-[14px] pb-3 flex items-center justify-between sticky top-0 z-[100] border-b border-[#F0F1F3]">
        <div class="flex items-center gap-4">
            <a href="/user"
                class="text-[22px] no-underline text-[#1A1A2E] leading-none w-[36px] h-[36px] rounded-full bg-[#F4F5F7] flex items-center justify-center">‹</a>
        </div>

        <div class="flex items-center gap-[10px]">
            <a href="/logout"
                class="w-[36px] h-[36px] rounded-full bg-[#F4F5F7] grid place-items-center text-[17px] no-underline border-none cursor-pointer"
                aria-label="ออกจากระบบ" title="ออกจากระบบ">
                <svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M5 21q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h7v2H5v14h7v2zm11-4l-1.375-1.45l2.55-2.55H9v-2h8.175l-2.55-2.55L16 7l5 5z"
                        stroke-width="0.5" stroke="currentColor" />
                </svg>
            </a>
        </div>
    </div>

    <!-- Header / Avatar -->
    <div class="flex flex-col items-center justify-center mt-6 mb-2">
        <div class="w-[80px] h-[80px] rounded-full bg-gradient-to-br from-[#1B8B4B] to-[#0D6B38] text-white text-[32px] font-[800] grid place-items-center shadow-[0_4px_12px_rgba(27,139,75,0.3)]"
            x-text="profile?.member_name ? profile.member_name.charAt(0) : ''">
        </div>
        <div class="text-[18px] font-[800] text-[#1A1A2E] mt-3" x-text="profile?.member_name || 'กำลังโหลด...'"></div>
        <div class="text-[13px] text-[#1B8B4B] font-[700] mt-1 bg-[#E8F5EE] px-3 py-1 rounded-full"
            x-text="profile?.role_name_th || 'สมาชิก'"></div>
    </div>

    <!-- Personal Info -->
    <div class="bg-white mt-[14px] mx-[14px] mb-0 rounded-2xl overflow-hidden shadow-[0_1px_6px_rgba(0,0,0,0.07)]">
        <div class="flex items-center justify-between pt-[14px] px-4 pb-[10px] border-b border-[#F0F1F3]">
            <div class="text-[14px] font-[800] text-[#1A1A2E]">ข้อมูลบัญชี</div>

            <!-- Edit Actions -->
            <button x-show="!isEditing" @click="startEdit()"
                class="text-[12px] text-[#1B8B4B] font-[700] bg-[#E8F5EE] px-3 py-1 rounded-full cursor-pointer border-none outline-none">แก้ไข</button>
            <div x-show="isEditing" class="flex gap-2" style="display: none;">
                <button @click="cancelEdit()"
                    class="text-[12px] text-gray-500 font-[700] px-2 py-1 cursor-pointer border-none bg-transparent outline-none">ยกเลิก</button>
                <button @click="saveProfile()" :disabled="isSaving"
                    class="text-[12px] text-white font-[700] bg-[#1B8B4B] px-3 py-1 rounded-full cursor-pointer border-none outline-none disabled:opacity-50">
                    <span x-text="isSaving ? 'กำลังบันทึก...' : 'บันทึก'"></span>
                </button>
            </div>
        </div>

        <div class="flex flex-col gap-1 py-[14px] px-4 border-b border-[#F0F1F3] last:border-b-0">
            <div class="text-[12px] text-[#9CA3AF] font-[600]">ชื่อ - นามสกุล</div>
            <div x-show="!isEditing" class="text-[14px] font-[700] text-[#1A1A2E]" x-text="profile?.member_name || '-'">
            </div>
            <input x-show="isEditing" type="text" x-model="editData.member_name"
                class="text-[14px] font-[700] text-[#1A1A2E] border border-gray-300 rounded px-2 py-1.5 outline-none focus:border-[#1B8B4B] w-full"
                :class="{'border-red-500': errors.member_name}" style="display: none;">
            <span x-show="isEditing && errors.member_name" class="text-red-500 text-xs mt-1" x-text="errors.member_name"
                style="display: none;"></span>
        </div>
        <div class="flex flex-col gap-1 py-[14px] px-4 border-b border-[#F0F1F3] last:border-b-0">
            <div class="text-[12px] text-[#9CA3AF] font-[600]">เบอร์โทรศัพท์</div>
            <div x-show="!isEditing" class="text-[14px] font-[700] text-[#1A1A2E]"
                x-text="profile?.member_phone || '-'"></div>
            <input x-show="isEditing" type="tel" x-model="editData.member_phone"
                class="text-[14px] font-[700] text-[#1A1A2E] border border-gray-300 rounded px-2 py-1.5 outline-none focus:border-[#1B8B4B] w-full"
                :class="{'border-red-500': errors.member_phone}" style="display: none;">
            <span x-show="isEditing && errors.member_phone" class="text-red-500 text-xs mt-1"
                x-text="errors.member_phone" style="display: none;"></span>
        </div>

        <?php if (((int) $user->role_id) === 1): ?>
            <div class="flex flex-col gap-1 py-[14px] px-4 border-b border-[#F0F1F3] last:border-b-0">
                <div class="text-[12px] text-[#9CA3AF] font-">รหัสประจำตัว / รหัสนักศึกษา</div>
                <div x-show="!isEditing" class="text-[14px] font- text-[#1A1A2E]"
                    x-text="profile?.member_personal_id || ' - '"></div>
                <input x-show="isEditing" type="text" x-model="editData.member_personal_id"
                    class="text-[14px] font- text-[#1A1A2E] border border-gray-300 rounded px-2 py-1.5 outline-none focus:border-[#1B8B4B] w-full"
                    :class="{'border-red-500': errors.member_personal_id}" style="display: none;">
                <span x-show="isEditing && errors.member_personal_id" class="text-red-500 text-xs mt-1"
                    x-text="errors.member_personal_id" style="display: none;"></span>
            </div>
        <?php endif; ?>

        <div class="flex flex-col gap-1 py-[14px] px-4 border-b border-[#F0F1F3] last:border-b-0">
            <div class="text-[12px] text-[#9CA3AF] font-[600]">อีเมล</div>
            <div x-show="!isEditing" class="text-[14px] font-[700] text-[#1A1A2E]"
                x-text="profile?.member_email || '-'"></div>
            <input x-show="isEditing" type="email" x-model="editData.member_email"
                class="text-[14px] font-[700] text-[#1A1A2E] border border-gray-300 rounded px-2 py-1.5 outline-none focus:border-[#1B8B4B] w-full"
                :class="{'border-red-500': errors.member_email}" style="display: none;">
            <span x-show="isEditing && errors.member_email" class="text-red-500 text-xs mt-1"
                x-text="errors.member_email" style="display: none;"></span>
        </div>
    </div>

    <!-- Faculty Info -->
    <div class="bg-white mt-[14px] mx-[14px] mb-0 rounded-2xl overflow-hidden shadow-[0_1px_6px_rgba(0,0,0,0.07)]">
        <div class="pt-[14px] px-4 pb-[10px] border-b border-[#F0F1F3]">
            <div class="text-[14px] font-[800] text-[#1A1A2E]">ข้อมูลสังกัด</div>
        </div>
        <div class="flex flex-col gap-1 py-[14px] px-4 border-b border-[#F0F1F3] last:border-b-0">
            <div class="text-[12px] text-[#9CA3AF] font-[600]">คณะ / หน่วยงาน</div>
            <div class="text-[14px] font-[700] text-[#1A1A2E]" x-text="profile?.faculty_name || '-'"></div>
        </div>
        <div class="flex flex-col gap-1 py-[14px] px-4 border-b border-[#F0F1F3] last:border-b-0">
            <div class="text-[12px] text-[#9CA3AF] font-[600]">สาขา / ภาควิชา</div>
            <div class="text-[14px] font-[700] text-[#1A1A2E]" x-text="profile?.major_name || '-'"></div>
        </div>
        <div class="flex flex-col gap-1 py-[14px] px-4 border-b border-[#F0F1F3] last:border-b-0">
            <div class="text-[12px] text-[#9CA3AF] font-[600]">วันที่สมัครสมาชิก</div>
            <div class="text-[14px] font-[700] text-[#1A1A2E]" x-text="formatDate(profile?.created_at)"></div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('profileData', () => ({
            isLoading: true,
            isEditing: false,
            isSaving: false,
            profile: {},
            editData: {},
            errors: {},

            async init() {
                try {
                    const memberId = <?php echo (int) $user->member_id ?? 'null'; ?>;
                    if (!memberId) {
                        window.location.href = '/login';
                        return;
                    }

                    const res = await fetch(`/api/members/profile/${memberId}`);
                    const data = await res.json();

                    if (data.success && data.data) {
                        this.profile = data.data;
                    }
                } catch (error) {
                    console.error('Failed to load profile data:', error);
                } finally {
                    this.isLoading = false;
                }
            },
            startEdit() {
                this.editData = {
                    member_name: this.profile.member_name || '',
                    member_phone: this.profile.member_phone || '',
                    member_personal_id: this.profile.member_personal_id || '',
                    member_email: this.profile.member_email || ''
                };
                this.errors = {};
                this.isEditing = true;
            },
            cancelEdit() {
                this.isEditing = false;
                this.errors = {};
            },
            async saveProfile() {
                if (this.isSaving) return;

                this.errors = {};

                if (this.editData.member_name && !/^[a-zA-Zก-๏\s]+$/u.test(this.editData.member_name)) {
                    this.errors.member_name = 'ชื่อ-นามสกุลต้องเป็นตัวอักษรเท่านั้น';
                }

                if (this.editData.member_phone && !/^\d{10}$/.test(this.editData.member_phone)) {
                    this.errors.member_phone = 'เบอร์โทรศัพท์ต้องเป็นตัวเลข 10 หลัก';
                }

                if (this.editData.member_email && this.editData.member_email.length > 0 && !/^\S+@\S+\.\S+$/.test(this.editData.member_email)) {
                    this.errors.member_email = 'รูปแบบอีเมลไม่ถูกต้อง';
                }

                if (this.profile.role_id === 1 && this.editData.member_personal_id && !/^\d{12}$/.test(this.editData.member_personal_id)) {
                    this.errors.member_personal_id = 'รหัสนักศึกษาต้องเป็นตัวเลข 12 หลัก';
                }

                if (Object.keys(this.errors).length > 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'ข้อมูลไม่ถูกต้อง',
                        text: 'กรุณาตรวจสอบข้อมูลที่กรอกอีกครั้ง',
                        confirmButtonColor: '#1B8B4B'
                    });
                    return;
                }

                try {
                    this.isSaving = true;
                    const res = await fetch(`/api/members/update/profile/${this.profile.member_id}`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(this.editData)
                    });
                    const data = await res.json();

                    if (data.success) {
                        this.isEditing = false;

                        await this.init(); // บังคับให้ดึงข้อมูลใหม่ทั้งหมดจากเซิร์ฟเวอร์

                        Swal.fire({
                            icon: 'success',
                            title: 'บันทึกข้อมูลสำเร็จ',
                            timer: 1500,
                            showConfirmButton: false,
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'บันทึกไม่สำเร็จ',
                            text: data.message || 'เกิดข้อผิดพลาดในการบันทึกข้อมูล',
                        });
                    }
                } catch (error) {
                    console.error('Failed to save profile data:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์',
                    });
                } finally {
                    this.isSaving = false;

                }
            },
            formatDate(dateString) {
                if (!dateString) return '-';
                const date = new Date(dateString);
                return `${date.getDate().toString().padStart(2, '0')}/${(date.getMonth() + 1).toString().padStart(2, '0')}/${date.getFullYear() + 543}`;
            }
        }));
    });
</script>