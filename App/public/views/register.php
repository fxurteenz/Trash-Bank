<div class="min-h-screen flex flex-col bg-gray-50 text-gray-800">

    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center gap-3">
                    <a href="/" class="h-full flex items-center justify-center">
                        <img src="assets/images/bru_gogreen_logo.png" alt="BRU Waste Bank"
                            class="h-[60%] hover:cursor-pointer">
                    </a>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <a href="/login"
                        class="bg-white border-2 border-green-600 text-green-600 hover:bg-green-50 hover:cursor-pointer hover:scale-105 active:scale-98 px-6 py-2 rounded-full font-semibold transition-all">
                        เข้าสู่ระบบ
                    </a>
                </div>

                <div class="md:hidden flex items-center">
                    <button @click="isMenuOpen = !isMenuOpen" class="text-gray-600">
                        <i x-show="!isMenuOpen" data-lucide="menu" class="w-6 h-6"></i>
                        <i x-show="isMenuOpen" x-cloak data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="isMenuOpen" x-collapse x-cloak
            class="md:hidden bg-white border-b border-gray-100 px-4 pt-2 pb-6 space-y-3 shadow-lg absolute w-full">
            <a href="/about"
                class="block w-full text-left px-4 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 rounded-lg">รู้จักโครงการ</a>
            <a href="/how-it-works"
                class="block w-full text-left px-4 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 rounded-lg">การทำงาน</a>
            <a href="/rewards"
                class="block w-full text-left px-4 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 rounded-lg">ของรางวัล</a>
            <a href="/leaderboard"
                class="block w-full text-left px-4 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 rounded-lg">อันดับคณะ</a>
            <div class="pt-2">
                <a href="/register"
                    class="block text-center w-full bg-white border-2 border-green-600 text-green-600 px-4 py-3 rounded-xl font-semibold">สมัครสมาชิก</a>
            </div>
        </div>
    </nav>

    <div x-data="registrationForm()" x-init="init()" class="flex-1 flex justify-center items-center px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-8 w-96 max-w-full">
            <h3 class="font-bold text-2xl mb-6 text-center text-gray-800">สมัครสมาชิก</h3>

            <form @submit.prevent="submitRegistration" class="space-y-4 text-sm">

                <div class="flex flex-col space-y-1">
                    <label for="phone" class="text-gray-700 font-medium">
                        เบอร์โทรศัพท์ <span class="text-red-500">*</span>
                    </label>
                    <input
                        class="border border-gray-300 rounded-md p-2 focus:ring-emerald-500 focus:ring-2 focus:border-emerald-400"
                        :class="{'border-red-500': errors.member_phone}" type="text" id="phone"
                        x-model="formData.member_phone" placeholder="หมายเลขโทรศัพท์ 10 หลัก">
                    <span x-show="errors.member_phone" class="text-red-500 text-xs" x-text="errors.member_phone"></span>
                </div>

                <div class="flex flex-col space-y-1">
                    <label for="password" class="text-gray-700 font-medium">
                        รหัสผ่าน <span class="text-red-500">*</span>
                    </label>
                    <input
                        class="border border-gray-300 rounded-md p-2 focus:ring-emerald-500 focus:ring-2 focus:border-emerald-400"
                        :class="{'border-red-500': errors.member_password}" type="password" id="password"
                        x-model="formData.member_password" placeholder="อย่างน้อย 8 ตัวอักษร">
                    <span x-show="errors.member_password" class="text-red-500 text-xs"
                        x-text="errors.member_password"></span>
                </div>

                <div class="flex flex-col space-y-1">
                    <label for="personal_id" class="text-gray-700 font-medium">รหัสประจำตัว</label>
                    <input
                        class="border border-gray-300 rounded-md p-2 focus:ring-sky-500 focus:ring-2 focus:border-sky-400"
                        type="text" id="personal_id" x-model="formData.member_personal_id"
                        placeholder="รหัสนักศึกษา/รหัสประจำตัวประชาชน">
                </div>

                <div class="flex flex-col space-y-1">
                    <label for="email" class="text-gray-700 font-medium">อีเมล</label>
                    <input
                        class="border border-gray-300 rounded-md p-2 focus:ring-sky-500 focus:ring-2 focus:border-sky-400"
                        type="email" id="email" x-model="formData.member_email" placeholder="example@email.com">
                </div>

                <div class="flex flex-col space-y-1">
                    <label for="name" class="text-gray-700 font-medium">ชื่อ-สกุล</label>
                    <input
                        class="border border-gray-300 rounded-md p-2 focus:ring-sky-500 focus:ring-2 focus:border-sky-400"
                        type="text" id="name" x-model="formData.member_name" placeholder="ชื่อที่ใช้แสดงในระบบ">
                </div>

                <div class="flex flex-col space-y-1">
                    <label for="faculty" class="text-gray-700 font-medium">คณะ</label>
                    <select id="faculty" x-model="formData.faculty_id" @change="fetchMajors()"
                        class="border border-gray-300 rounded-md p-2 focus:ring-emerald-500 focus:ring-2 focus:border-emerald-400 bg-white">
                        <option value="">เลือกคณะ</option>
                        <template x-for="faculty in faculties" :key="faculty.faculty_id">
                            <option :value="faculty.faculty_id" x-text="faculty.faculty_name"></option>
                        </template>
                    </select>
                </div>

                <div x-show="formData.faculty_id" class="flex flex-col space-y-1">
                    <label for="major" class="text-gray-700 font-medium">สาขา</label>
                    <select id="major" x-model="formData.major_id"
                        class="border border-gray-300 rounded-md p-2 focus:ring-emerald-500 focus:ring-2 focus:border-emerald-400 bg-white disabled:bg-gray-100"
                        :disabled="!formData.faculty_id || majors.length === 0">
                        <option value="" x-text="majors.length === 0 ? `ไม่มีสาขาที่เข้าร่วม` : `เลือกสาขา`">เลือกสาขา
                        </option>
                        <template x-for="major in majors" :key="major.major_id">
                            <option :value="major.major_id" x-text="major.major_name"></option>
                        </template>
                    </select>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full font-semibold text-white py-3 px-4 rounded-lg bg-emerald-600 hover:bg-emerald-700 cursor-pointer transition-all hover:scale-105 active:scale-98 shadow-md">
                        ยืนยันการสมัคร
                    </button>
                </div>
                <div class="text-center mt-4">
                    <p class="text-gray-600 text-sm">
                        มีบัญชีผู้ใช้แล้ว?
                        <a href="/login" class="text-emerald-600 hover:underline font-semibold">เข้าสู่ระบบเลย</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <footer class="bg-gray-900 text-gray-400 py-12 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-8 items-center">
                <div class="flex flex-col justify-center items-center">
                    <div class="flex items-center gap-3 mb-4">
                        <div>
                            <img class="h-10" src="assets/images/waste_bankFullLogo.png" alt="">
                            <!-- <h1 class="font-bold text-lg text-white">BRU Waste Bank</h1> -->
                        </div>
                        <div>
                            <img class="h-10" src="assets/images/bru_gogreen_logo.png" alt="">
                            <!-- <h1 class="font-bold text-lg text-white">BRU Waste Bank</h1> -->
                        </div>
                    </div>
                    <p class="text-sm">
                        โครงการธนาคารขยะ มหาวิทยาลัยราชภัฏบุรีรัมย์<br />
                        สร้างสังคมคาร์บอนต่ำอย่างยั่งยืน
                    </p>
                </div>
                <div class="text-center">
                    <p class="text-sm">
                        © <span x-text="new Date().getFullYear()"></span>
                        มหาวิทยาลัยราชภัฏบุรีรัมย์.<br />สงวนลิขสิทธิ์.
                    </p>
                </div>
                <div class="flex justify-center md:justify-end gap-4">
                    <button class="hover:text-white transition-all cursor-pointer">ติดต่อแอดมิน</button>
                    <button class="hover:text-white transition-all cursor-pointer">นโยบายความเป็นส่วนตัว</button>
                </div>
            </div>
        </div>
    </footer>
</div>

<script>
    function registrationForm() {
        return {
            formData: {
                member_phone: '',
                member_password: '',
                member_personal_id: '',
                member_email: '',
                member_name: '',
                faculty_id: '',
                major_id: '',
            },
            faculties: [],
            majors: [],
            errors: {},

            init() {
                this.fetchFaculties();
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
                this.formData.major_id = '';
                if (!this.formData.faculty_id) {
                    return;
                }
                try {
                    const response = await fetch(`/api/majors/faculty/${this.formData.faculty_id}`);
                    const result = await response.json();
                    if (result.success) {
                        this.majors = result.result;
                    }
                } catch (error) {
                    console.error('Could not fetch majors:', error);
                }
            },

            validateForm() {
                this.errors = {};
                if (!this.formData.member_phone) {
                    this.errors.member_phone = 'กรุณากรอกเบอร์โทรศัพท์';
                }
                if (!this.formData.member_password) {
                    this.errors.member_password = 'กรุณากรอกรหัสผ่าน';
                } else if (this.formData.member_password.length < 8) {
                    this.errors.member_password = 'รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร';
                }
                return Object.keys(this.errors).length === 0;
            },

            async submitRegistration() {
                if (!this.validateForm()) {
                    return;
                }

                try {
                    const response = await fetch('/register', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(this.formData)
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        await Swal.fire({
                            icon: 'success',
                            title: 'สมัครสมาชิกสำเร็จ!',
                            text: 'กำลังนำท่านไปยังหน้าเข้าสู่ระบบ',
                            timer: 2000,
                            showConfirmButton: false,
                        });
                        window.location.href = '/login';
                    } else {
                        throw new Error(result.message || 'เกิดข้อผิดพลาดในการสมัครสมาชิก');
                    }
                } catch (error) {
                    console.error('Registration failed:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'สมัครสมาชิกไม่สำเร็จ',
                        text: error.message,
                    });
                }
            }
        }
    }
    document.addEventListener('alpine:init', () => {
        Alpine.data('registrationForm', registrationForm);
    });
</script>