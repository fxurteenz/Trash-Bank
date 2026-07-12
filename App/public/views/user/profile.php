<div class="grid gap-0 pb-6" x-data="profileData()">
    <!-- Top Bar -->
    <div
        class="relative bg-white px-4 pt-[14px] pb-3 flex items-center justify-between sticky top-0 z-[100] border-b border-[#F0F1F3]">

        <a @click="window.history.back()" class="flex items-center gap-[2px] no-underline text-[14px] text-gray-400">
            <i data-lucide="circle-chevron-left" class="h-[14px]"></i>
            <span>ย้อนกลับ</span>
        </a>

        <img src="/assets/images/waste_bankFullLogo.png" class="absolute left-1/2 -translate-x-1/2 h-[20px]" alt="">

        <div class="flex items-center gap-[10px]">
            <a href="/logout" class="text-[17px] no-underline cursor-pointer" aria-label="ออกจากระบบ"
                title="ออกจากระบบ">
                <i data-lucide="log-out" class="w-[14px] h-[14px] text-gray-400"></i>
            </a>
        </div>
    </div>

    <!-- Header / Avatar -->
    <div class="flex flex-col items-center justify-center mt-6 mb-2">
        <div class="w-[80px] h-[80px] rounded-full bg-gradient-to-br from-[#1B8B4B] to-[#0D6B38] text-white text-[32px] font-[800] grid place-items-center shadow-[0_4px_12px_rgba(27,139,75,0.3)]"
            x-text="getFirstThaiChar(profile?.member_name) || '-'">
        </div>
        <div class="text-[18px] font-[800] text-gray-700 mt-3" x-text="profile?.member_name || 'กำลังโหลด...'"></div>
        <div class="text-[13px] text-[#1B8B4B] mt-1 bg-[#E8F5EE] px-3 py-1 rounded-full"
            x-text="profile?.role_name_th || 'สมาชิก'"></div>
    </div>

    <!-- Personal Info -->
    <div class="bg-white mt-[14px] mx-[14px] mb-0 rounded-2xl overflow-hidden shadow-[0_1px_6px_rgba(0,0,0,0.07)]">
        <div class="flex items-center justify-between pt-[14px] px-4 pb-[10px] border-b border-[#F0F1F3]">
            <div class="text-[14px] text-gray-800 font-bold">ข้อมูลบัญชี</div>

            <!-- Edit Actions -->
            <div x-show="!isEditing" class="flex items-center gap-2">
                <button @click="openChangePasswordDialog()"
                    class="text-[12px] text-gray-600 bg-gray-200 px-3 py-1 rounded-full cursor-pointer border-none outline-none hover:bg-gray-300 font-semibold">เปลี่ยนรหัสผ่าน</button>
                <button @click="startEdit()"
                    class="text-[12px] text-[#1B8B4B] bg-[#E8F5EE] px-3 py-1 rounded-full cursor-pointer border-none outline-none hover:bg-emerald-200 font-semibold">แก้ไข</button>
            </div>
            <div x-show="isEditing" class="flex gap-2" style="display: none;">
                <button @click="cancelEdit()"
                    class="text-[12px] text-gray-500 px-2 py-1 cursor-pointer border-none bg-transparent outline-none">ยกเลิก</button>
                <button @click="saveProfile()" :disabled="isSaving"
                    class="text-[12px] text-white bg-[#1B8B4B] px-3 py-1 rounded-full cursor-pointer border-none outline-none disabled:opacity-50">
                    <span x-text="isSaving ? 'กำลังบันทึก...' : 'บันทึก'"></span>
                </button>
            </div>
        </div>

        <div class="flex flex-col gap-1 py-[14px] px-4 border-b border-[#F0F1F3] last:border-b-0">
            <div class="text-[12px] text-[#9CA3AF]">ชื่อ - นามสกุล</div>
            <div x-show="!isEditing" class="text-[14px] text-gray-700" x-text="profile?.member_name || '-'">
            </div>
            <input x-show="isEditing" type="text" x-model="editData.member_name"
                class="text-[14px] text-gray-700 border border-gray-300 rounded px-2 py-1.5 outline-none focus:border-[#1B8B4B] w-full"
                :class="{'border-red-500': errors.member_name}" style="display: none;">
            <span x-show="isEditing && errors.member_name" class="text-red-500 text-xs mt-1" x-text="errors.member_name"
                style="display: none;"></span>
        </div>
        <div class="flex flex-col gap-1 py-[14px] px-4 border-b border-[#F0F1F3] last:border-b-0">
            <div class="text-[12px] text-[#9CA3AF]">เบอร์โทรศัพท์</div>
            <div x-show="!isEditing" class="text-[14px] text-gray-700" x-text="profile?.member_phone || '-'"></div>
            <input x-show="isEditing" type="tel" x-model="editData.member_phone"
                class="text-[14px] text-gray-700 border border-gray-300 rounded px-2 py-1.5 outline-none focus:border-[#1B8B4B] w-full"
                :class="{'border-red-500': errors.member_phone}" style="display: none;">
            <span x-show="isEditing && errors.member_phone" class="text-red-500 text-xs mt-1"
                x-text="errors.member_phone" style="display: none;"></span>
        </div>

        <?php if (((int) $user->role_id) === 1): ?>
            <div class="flex flex-col gap-1 py-[14px] px-4 border-b border-[#F0F1F3] last:border-b-0">
                <div class="text-[12px] text-[#9CA3AF]">รหัสประจำตัว / รหัสนักศึกษา</div>
                <div x-show="!isEditing" class="text-[14px] font- text-gray-700"
                    x-text="profile?.member_personal_id || ' - '"></div>
                <input x-show="isEditing" type="text" x-model="editData.member_personal_id"
                    class="text-[14px] text-gray-700 border border-gray-300 rounded px-2 py-1.5 outline-none focus:border-[#1B8B4B] w-full"
                    :class="{'border-red-500': errors.member_personal_id}" style="display: none;">
                <span x-show="isEditing && errors.member_personal_id" class="text-red-500 text-xs mt-1"
                    x-text="errors.member_personal_id" style="display: none;"></span>
            </div>
        <?php endif; ?>

        <div class="flex flex-col gap-1 py-[14px] px-4 border-b border-[#F0F1F3] last:border-b-0">
            <div class="text-[12px] text-[#9CA3AF]">อีเมล</div>
            <div x-show="!isEditing" class="text-[14px] text-gray-700" x-text="profile?.member_email || '-'"></div>
            <input x-show="isEditing" type="email" x-model="editData.member_email"
                class="text-[14px] text-gray-700 border border-gray-300 rounded px-2 py-1.5 outline-none focus:border-[#1B8B4B] w-full"
                :class="{'border-red-500': errors.member_email}" style="display: none;">
            <span x-show="isEditing && errors.member_email" class="text-red-500 text-xs mt-1"
                x-text="errors.member_email" style="display: none;"></span>
        </div>
    </div>

    <!-- Faculty Info -->
    <div class="bg-white mt-[14px] mx-[14px] mb-0 rounded-2xl overflow-hidden shadow-[0_1px_6px_rgba(0,0,0,0.07)]">
        <div class="pt-[14px] px-4 pb-[10px] border-b border-[#F0F1F3]">
            <div class="text-[14px] text-gray-800 font-bold">ข้อมูลสังกัด</div>
        </div>
        <div class="flex flex-col gap-1 py-[14px] px-4 border-b border-[#F0F1F3] last:border-b-0">
            <div class="text-[12px] text-[#9CA3AF]">คณะ / หน่วยงาน</div>
            <div x-show="!isEditing" class="text-[14px] text-gray-700" x-text="profile?.faculty_name || '-'"></div>
            <select x-show="isEditing" x-model="editData.faculty_id" @change="fetchMajors()"
                class="text-[14px] text-gray-700 border border-gray-300 rounded px-2 py-1.5 outline-none focus:border-[#1B8B4B] w-full bg-white"
                :class="{'border-red-500': errors.faculty_id}" style="display: none;">
                <option value="">-- เลือกคณะ --</option>
                <template x-for="faculty in faculties" :key="faculty.faculty_id">
                    <option :value="faculty.faculty_id" x-text="faculty.faculty_name"
                        :selected="parseInt(profile.faculty_id) === parseInt(faculty.faculty_id)"></option>
                </template>
            </select>
            <span x-show="isEditing && errors.faculty_id" class="text-red-500 text-xs mt-1" x-text="errors.faculty_id"
                style="display: none;"></span>
        </div>
        <div class="flex flex-col gap-1 py-[14px] px-4 border-b border-[#F0F1F3] last:border-b-0">
            <div class="text-[12px] text-[#9CA3AF]">สาขา / ภาควิชา</div>
            <div x-show="!isEditing" class="text-[14px] text-gray-700" x-text="profile?.major_name || '-'"></div>
            <select x-show="isEditing" x-model="editData.major_id"
                class="text-[14px] text-gray-700 border border-gray-300 rounded px-2 py-1.5 outline-none focus:border-[#1B8B4B] w-full bg-white disabled:bg-gray-100"
                :disabled="!editData.faculty_id || majors.length === 0" style="display: none;">
                <option value=""
                    x-text="!editData.faculty_id ? 'กรุณาเลือกคณะก่อน' : (majors.length > 0 ? '-- เลือกสาขา --' : 'ไม่มีสาขาในคณะนี้')">
                </option>
                <template x-for="major in majors" :key="major.major_id">
                    <option :value="major.major_id" x-text="major.major_name"
                        :selected="parseInt(profile.major_id) === parseInt(major.major_id)"></option>
                </template>
            </select>
        </div>
        <div class="flex flex-col gap-1 py-[14px] px-4 border-b border-[#F0F1F3] last:border-b-0">
            <div class="text-[12px] text-[#9CA3AF]">วันที่สมัครสมาชิก</div>
            <div class="text-[14px] text-gray-700" x-text="formatDate(profile?.created_at)"></div>
        </div>
    </div>

    <!-- Change Password Dialog -->
    <dialog x-show="changePasswordDialogShow" x-ref="changePasswordDialog"
        @click.self="changePasswordDialogShow = false" @close="changePasswordDialogShow = false"
        class="fixed inset-0 mx-auto my-auto p-0 bg-transparent" style="z-index: auto;"
        x-init="$watch('changePasswordDialogShow', value => {if (value) $refs.changePasswordDialog.showModal();else $refs.changePasswordDialog.close();})">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full min-w-xs max-w-sm relative z-10">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">เปลี่ยนรหัสผ่าน</h3>
                <button @click="changePasswordDialogShow = false"
                    class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
            <div class="space-y-4">
                <div>
                    <label for="current_password"
                        class="block text-sm font-medium text-gray-700">รหัสผ่านปัจจุบัน</label>
                    <input type="password" id="current_password" x-model="passwordData.old_password"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                        :class="{'border-red-500': passwordErrors.old_password}">
                    <span x-show="passwordErrors.old_password" class="text-red-500 text-xs mt-1"
                        x-text="passwordErrors.old_password"></span>
                </div>
                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700">รหัสผ่านใหม่</label>
                    <input type="password" id="new_password" x-model="passwordData.new_password"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                        :class="{'border-red-500': passwordErrors.new_password}">
                    <span x-show="passwordErrors.new_password" class="text-red-500 text-xs mt-1"
                        x-text="passwordErrors.new_password"></span>
                </div>
                <div>
                    <label for="confirm_password"
                        class="block text-sm font-medium text-gray-700">ยืนยันรหัสผ่านใหม่</label>
                    <input type="password" id="confirm_password" x-model="passwordData.confirm_password"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                        :class="{'border-red-500': passwordErrors.confirm_password}">
                    <span x-show="passwordErrors.confirm_password" class="text-red-500 text-xs mt-1"
                        x-text="passwordErrors.confirm_password"></span>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-2">
                <button @click="changePasswordDialogShow = false"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">ยกเลิก</button>
                <button @click="savePassword()" :disabled="isSavingPassword"
                    class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 disabled:opacity-50 disabled:bg-gray-200">
                    <span x-show="!isSavingPassword">บันทึก</span>
                    <span x-show="isSavingPassword">กำลังบันทึก...</span>
                </button>
            </div>
        </div>
    </dialog>
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
            faculties: [],
            majors: [],
            changePasswordDialogShow: false,
            isSavingPassword: false,
            passwordData: {
                old_password: '',
                new_password: '',
                confirm_password: ''
            },
            passwordErrors: {},

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
            async startEdit() {
                this.editData = {
                    member_name: this.profile.member_name || '',
                    member_phone: this.profile.member_phone || '',
                    member_personal_id: this.profile.member_personal_id || '',
                    member_email: this.profile.member_email || '',
                    faculty_id: this.profile.faculty_id || '',
                    major_id: this.profile.major_id || ''
                };
                this.errors = {};
                this.isEditing = true;
                await this.fetchFaculties();
                if (this.editData.faculty_id) {
                    await this.fetchMajors();
                    this.editData.major_id = this.profile.major_id || '';
                }
            },

            getFirstThaiChar(name) {
                if (!name || name.length === 0) return '?';
                const leadingVowels = ['เ', 'แ', 'โ', 'ใ', 'ไ'];
                if (leadingVowels.includes(name.charAt(0)) && name.length > 1) {
                    return name.charAt(1);
                }
                return name.charAt(0);
            },

            async fetchFaculties() {
                try {
                    const response = await fetch('/api/faculties');
                    const result = await response.json();
                    if (result.success) {
                        this.faculties = result.data;
                    }
                } catch (error) {
                    console.error('Could not fetch faculties:', error);
                }
            },

            async fetchMajors() {
                this.majors = [];
                if (!this.editData.faculty_id) return;
                try {
                    const response = await fetch(`/api/majors/faculty/${this.editData.faculty_id}`);
                    const result = await response.json();
                    if (result.success) {
                        this.majors = result.result;
                    }
                } catch (error) {
                    console.error('Could not fetch majors:', error);
                }
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
            ,
            openChangePasswordDialog() {
                this.passwordData = { old_password: '', new_password: '', confirm_password: '' };
                this.passwordErrors = {};
                this.changePasswordDialogShow = true;
            },
            async savePassword() {
                if (this.isSavingPassword) return;

                this.passwordErrors = {};
                let hasError = false;

                if (!this.passwordData.old_password) {
                    this.passwordErrors.old_password = 'กรุณากรอกรหัสผ่านปัจจุบัน';
                    hasError = true;
                }
                if (!this.passwordData.new_password) {
                    this.passwordErrors.new_password = 'กรุณากรอกรหัสผ่านใหม่';
                    hasError = true;
                } else if (this.passwordData.new_password.length < 6) {
                    this.passwordErrors.new_password = 'รหัสผ่านใหม่ต้องมีอย่างน้อย 6 ตัวอักษร';
                    hasError = true;
                }
                if (this.passwordData.new_password !== this.passwordData.confirm_password) {
                    this.passwordErrors.confirm_password = 'รหัสผ่านใหม่และการยืนยันไม่ตรงกัน';
                    hasError = true;
                }

                if (hasError) {
                    this.isSavingPassword = false;
                    return;
                }

                this.isSavingPassword = true;

                try {
                    const res = await fetch(`/api/members/change_password/${this.profile.member_id}`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            old_password: this.passwordData.old_password,
                            new_password: this.passwordData.new_password,
                            confirm_password: this.passwordData.confirm_password
                        })
                    });
                    const data = await res.json();

                    if (data.success) {
                        this.changePasswordDialogShow = false;
                        Swal.fire({
                            icon: 'success',
                            title: 'เปลี่ยนรหัสผ่านสำเร็จ',
                            timer: 2000,
                            showConfirmButton: false,
                        });
                    } else {
                        this.changePasswordDialogShow = false;
                        Swal.fire({
                            icon: 'error',
                            title: 'เปลี่ยนรหัสผ่านไม่สำเร็จ',
                            text: data.message || 'เกิดข้อผิดพลาดบางอย่าง',
                            showConfirmButton: false,
                            timer: 2000,
                        });
                        setTimeout(() => {
                            this.changePasswordDialogShow = true;
                        }, 2000);
                    }
                } catch (error) {
                    console.error('Failed to change password:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้',
                    });
                } finally {
                    this.isSavingPassword = false;
                }
            }
        }));
    });
</script>