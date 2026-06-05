<script>
    function registrationForm() {
        return {
            isMenuOpen: false,
            step: 1,
            member_type: '', // เก็บประเภทของสมาชิก ('student' หรือ 'staff')
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

            nextStep() {
                if (this.step === 1) {
                    if (!this.member_type) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'กรุณาเลือกประเภทสมาชิก',
                            text: 'โปรดระบุว่าคุณเป็นนักศึกษาหรือบุคลากร',
                            confirmButtonColor: '#059669'
                        });
                        return;
                    }
                    this.step++;
                } else if (this.step === 2) {
                    if (this.validateStep2()) {
                        this.step++;
                    }
                }
            },

            prevStep() {
                if (this.step > 1) {
                    this.step--;
                }
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

            getSubmitButtonText() {
                const hasName = this.formData.member_name && this.formData.member_name.trim() !== '';

                const hasRequired = (this.member_type === 'student' || this.member_type === 'teacher')
                    ? (this.formData.faculty_id && this.formData.faculty_id.toString().trim() !== '')
                    : true;

                if (!hasName || !hasRequired) {
                    return 'กรุณากรอกข้อมูลให้ครบถ้วน';
                }
                return 'ยืนยันการสมัคร';
            },

            validateStep2() {
                this.errors = {};
                if (!this.formData.member_phone) {
                    this.errors.member_phone = 'กรุณากรอกเบอร์โทรศัพท์';
                } else if (!/^\d{10}$/.test(this.formData.member_phone)) {
                    this.errors.member_phone = 'เบอร์โทรศัพท์ต้องเป็นตัวเลข 10 หลัก';
                }
                if (!this.formData.member_password) {
                    this.errors.member_password = 'กรุณากรอกรหัสผ่าน';
                } else if (this.formData.member_password.length < 8) {
                    this.errors.member_password = 'รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร';
                }
                return Object.keys(this.errors).length === 0;
            },

            async submitRegistration() {
                this.errors = {};

                if (!this.formData.member_name || this.formData.member_name.trim() === '') {
                    this.errors.member_name = 'กรุณากรอกชื่อ-สกุล';
                } else if (!/^[a-zA-Zก-๏\s]+$/u.test(this.formData.member_name)) {
                    this.errors.member_name = 'ชื่อ-นามสกุลต้องเป็นตัวอักษรเท่านั้น';
                }

                if ((this.member_type === 'student' || this.member_type === 'teacher') && (!this.formData.faculty_id || this.formData.faculty_id.toString().trim() === '')) {
                    this.errors.faculty_id = 'กรุณาเลือกคณะ';
                }

                if (this.formData.member_email && !/^\S+@\S+\.\S+$/.test(this.formData.member_email)) {
                    this.errors.member_email = 'รูปแบบอีเมลไม่ถูกต้อง';
                }

                if (this.member_type === 'student' && this.formData.member_personal_id && !/^\d{12}$/.test(this.formData.member_personal_id)) {
                    this.errors.member_personal_id = 'รหัสนักศึกษาต้องเป็นตัวเลข 12 หลัก';
                }

                if (Object.keys(this.errors).length > 0) {
                    return;
                }

                // หากผู้ใช้เป็นบุคลากร ให้เคลียร์ค่าของนักศึกษาที่อาจค้างอยู่เพื่อความชัวร์ (Optional)
                if (this.member_type === 'staff') {
                    this.formData.member_personal_id = '';
                    this.formData.faculty_id = '';
                    this.formData.major_id = '';
                }

                const payload = { ...this.formData, member_type: this.member_type };

                try {
                    const response = await fetch('/register', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload)
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
                        html: "<p class='mb-2'>" + error.message + "</p><hr><p class='mt-1 text-xs font-light'>หากพบปัญหาในการใช้งาน สามารถติดต่อศูนย์ฯด้วยตนเอง เพื่อดำเนินการแก้ไข</p>",
                        confirmButtonColor: '#009966',
                        confirmButtonText: 'ลองใหม่'
                    });
                }
            }
        }
    }
    document.addEventListener('alpine:init', () => {
        Alpine.data('registrationForm', registrationForm);
    });
</script>
<div x-data="registrationForm()" x-init="init()" class="min-h-screen flex flex-col bg-gray-50 text-gray-800">

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

    <div class="flex-1 flex justify-center items-center px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-8 w-96 max-w-full">
            <h3 class="font-bold text-2xl mb-6 text-center text-gray-800">สมัครสมาชิก</h3>

            <div class="relative mb-8">
                <!-- <div class="overflow-hidden mb-2 h-2 text-xs flex rounded bg-gray-200">
                    <div :style="'width: ' + ((step - 1) / 2 * 100) + '%'"
                        class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-emerald-500 transition-all duration-300">
                    </div>
                </div> -->
                <div class="flex justify-between text-xs text-gray-500 font-light gap-2">
                    <div :class="{'text-emerald-600': step >= 1}"
                        class="flex-1/3 flex flex-col items-center justify-center text-center">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M12 22q-2.075 0-3.9-.788t-3.175-2.137T2.788 15.9T2 12t.788-3.9t2.137-3.175T8.1 2.788T12 2t3.9.788t3.175 2.137T21.213 8.1T22 12t-.788 3.9t-2.137 3.175t-3.175 2.138T12 22m0-2q3.35 0 5.675-2.325T20 12t-2.325-5.675T12 4T6.325 6.325T4 12t2.325 5.675T12 20m-.5-11v7q0 .425.288.713T12.5 17t.713-.288T13.5 16V8q0-.425-.288-.712T12.5 7h-2q-.425 0-.712.288T9.5 8t.288.713T10.5 9z"
                                    stroke-width="0.5" stroke="currentColor" />
                            </svg>
                        </div>
                        <span>
                            ประเภทสมาชิก
                        </span>
                        <div class="w-full h-2 text-xs flex rounded bg-gray-200 mb-1">
                            <div :style="'width: 100%'"
                                class="shadow-none flex flex-col text-center rounded whitespace-nowrap text-white justify-center bg-emerald-500 transition-all duration-300">
                            </div>
                        </div>

                    </div>
                    <div :class="{'text-emerald-600': step >= 2}"
                        class="flex-1/3 flex flex-col items-center justify-center text-center">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M12 22q-2.075 0-3.9-.788t-3.175-2.137T2.788 15.9T2 12t.788-3.9t2.137-3.175T8.1 2.788T12 2t3.9.788t3.175 2.137T21.213 8.1T22 12t-.788 3.9t-2.137 3.175t-3.175 2.138T12 22m0-2q3.35 0 5.675-2.325T20 12t-2.325-5.675T12 4T6.325 6.325T4 12t2.325 5.675T12 20m2-3q.425 0 .713-.288T15 16t-.288-.712T14 15h-3v-2h2q.825 0 1.413-.587T15 11V9q0-.825-.587-1.412T13 7h-3q-.425 0-.712.288T9 8t.288.713T10 9h3v2h-2q-.825 0-1.412.588T9 13v3q0 .425.288.713T10 17z"
                                    stroke-width="0.5" stroke="currentColor" />
                            </svg>
                        </div>
                        <span>
                            เบอร์โทรศัพท์
                        </span>
                        <div class="w-full h-2 text-xs flex rounded bg-gray-200 mb-1">
                            <div :style="'width: ' + ((step - 2) / 1 * 100) + '%'"
                                class="shadow-none flex flex-col text-center whitespace-nowrap rounded justify-center bg-emerald-500 transition-all duration-300">
                            </div>
                        </div>

                    </div>
                    <div :class="{'text-emerald-600': step >= 3}"
                        class="flex-1/3 flex flex-col items-center justify-center text-center">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M12 22q-2.075 0-3.9-.788t-3.175-2.137T2.788 15.9T2 12t.788-3.9t2.137-3.175T8.1 2.788T12 2t3.9.788t3.175 2.137T21.213 8.1T22 12t-.788 3.9t-2.137 3.175t-3.175 2.138T12 22m0-2q3.35 0 5.675-2.325T20 12t-2.325-5.675T12 4T6.325 6.325T4 12t2.325 5.675T12 20m-2-3h3q.825 0 1.413-.587T15 15v-1.5q0-.65-.425-1.075T13.5 12q.65 0 1.075-.425T15 10.5V9q0-.825-.587-1.412T13 7h-3q-.425 0-.712.288T9 8t.288.713T10 9h3v2h-1q-.425 0-.712.288T11 12t.288.713T12 13h1v2h-3q-.425 0-.712.288T9 16t.288.713T10 17"
                                    stroke-width="0.5" stroke="currentColor" />
                            </svg>
                        </div>
                        <span>
                            รายละเอียดเพิ่มเติม
                        </span>
                        <div class="w-full h-2 text-xs flex rounded bg-gray-200 mb-1">
                            <div :style="'width: ' + ((step - 3) / 1 * 100) + '%'"
                                class="shadow-none flex flex-col text-center whitespace-nowrap rounded justify-center bg-emerald-500 transition-all duration-300">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submitRegistration" class="space-y-4 text-sm">

                <div x-show="step === 1">
                    <div class="flex text-center justify-center mb-3">
                        <label class="text-gray-700 font-medium text-center text-lg">เลือกประเภทสมาชิก&nbsp;</label>
                        <label class="text-emerald-700 font-semibold text-center text-lg">คุณเป็น</label>
                    </div>

                    <div class="space-y-3">
                        <label
                            class="flex items-center justify-center gap-3 p-4 border rounded-lg cursor-pointer transition-all"
                            :class="member_type === 'student' ? 'border-emerald-500 bg-emerald-50' : 'border-gray-300 hover:bg-gray-50'">
                            <input type="radio" x-model="member_type" value="student" class="hidden">
                            <i data-lucide="graduation-cap"></i>
                            <div class="font-semibold text-lg"
                                :class="member_type === 'student' ? 'text-emerald-700' : 'text-gray-600'">
                                นักศึกษา
                            </div>
                        </label>
                        <label
                            class="flex items-center justify-center gap-3 p-4 border rounded-lg cursor-pointer transition-all"
                            :class="member_type === 'teacher' ? 'border-emerald-500 bg-emerald-50' : 'border-gray-300 hover:bg-gray-50'">
                            <input type="radio" x-model="member_type" value="teacher" class="hidden">
                            <i data-lucide="id-card-lanyard"></i>
                            <div class="font-semibold text-lg"
                                :class="member_type === 'staff' ? 'text-emerald-700' : 'text-gray-600'">
                                อาจารย์
                            </div>
                        </label>
                        <label
                            class="flex items-center justify-center gap-3 p-4 border rounded-lg cursor-pointer transition-all"
                            :class="member_type === 'staff' ? 'border-emerald-500 bg-emerald-50' : 'border-gray-300 hover:bg-gray-50'">
                            <input type="radio" x-model="member_type" value="staff" class="hidden">
                            <i data-lucide="id-card-lanyard"></i>
                            <div class="font-semibold text-lg"
                                :class="member_type === 'staff' ? 'text-emerald-700' : 'text-gray-600'">
                                บุคลากร
                            </div>
                        </label>
                    </div>

                    <div class="pt-6">
                        <button type="button" @click="nextStep"
                            class="w-full font-semibold text-white bg-emerald-600 hover:bg-emerald-700 py-3 px-4 rounded-lg hover:scale-102 cursor-pointer transition-all shadow-md">
                            ถัดไป
                        </button>
                    </div>
                </div>

                <div x-show="step === 2" style="display: none;">
                    <div class="space-y-4">
                        <div class="flex flex-col space-y-1">
                            <label for="phone" class="text-gray-700 font-medium text-lg">
                                เบอร์โทรศัพท์ <span class="text-red-500">*</span>
                            </label>
                            <input class="border border-gray-300 rounded-md p-2 focus:ring-emerald-500 focus:ring-2"
                                :class="{'border-red-500': errors.member_phone}" type="text" id="phone"
                                x-model="formData.member_phone" placeholder="หมายเลขโทรศัพท์ 10 หลัก">
                            <span x-show="errors.member_phone" class="text-red-500 text-xs"
                                x-text="errors.member_phone"></span>
                        </div>

                        <div class="flex flex-col space-y-1">
                            <label for="password" class="text-gray-700 font-medium text-lg">
                                รหัสผ่าน <span class="text-red-500">*</span>
                            </label>
                            <input class="border border-gray-300 rounded-md p-2 focus:ring-emerald-500 focus:ring-2"
                                :class="{'border-red-500': errors.member_password}" type="password" id="password"
                                x-model="formData.member_password" placeholder="อย่างน้อย 8 ตัวอักษร">
                            <span x-show="errors.member_password" class="text-red-500 text-xs"
                                x-text="errors.member_password"></span>
                        </div>
                    </div>

                    <div class="pt-6 flex gap-3">
                        <button type="button" @click="prevStep"
                            class="w-1/3 font-semibold border border-2 border-gray-500 text-gray-500 hover:scale-102 py-3 px-4 rounded-lg cursor-pointer transition-all">
                            กลับ
                        </button>
                        <button type="button" @click="nextStep"
                            class="w-2/3 font-semibold text-white bg-emerald-600 hover:bg-emerald-700 py-3 px-4 rounded-lg hover:scale-102 cursor-pointer transition-all shadow-md">
                            ถัดไป
                        </button>
                    </div>
                </div>

                <div x-show="step === 3" style="display: none;">
                    <div class="space-y-4">
                        <div class="flex flex-col space-y-1">
                            <label for="name" class="text-gray-700 font-medium text-lg">ชื่อ-สกุล <span
                                    class="text-red-500">*</span></label>
                            <input
                                class="border border-gray-300 rounded-md p-2 focus:ring-sky-500 focus:ring-2 focus:border-sky-400"
                                :class="{'border-red-500': errors.member_name}" type="text" id="name"
                                x-model="formData.member_name" placeholder="ชื่อที่ใช้แสดงในระบบ">
                            <span x-show="errors.member_name" class="text-red-500 text-xs"
                                x-text="errors.member_name"></span>
                        </div>

                        <div class="flex flex-col space-y-1">
                            <label for="email" class="text-gray-700 font-medium text-lg">อีเมล</label>
                            <input
                                class="border border-gray-300 rounded-md p-2 focus:ring-sky-500 focus:ring-2 focus:border-sky-400"
                                :class="{'border-red-500': errors.member_email}" type="email" id="email"
                                x-model="formData.member_email" placeholder="example@email.com">
                            <span x-show="errors.member_email" class="text-red-500 text-xs" x-text="errors.member_email"
                                style="display: none;"></span>
                        </div>

                        <template x-if="member_type === 'student' || member_type === 'teacher'">
                            <div class="space-y-4">
                                <template x-if="member_type === 'student'">
                                    <div class="flex flex-col space-y-1">
                                        <label for="personal_id"
                                            class="text-gray-700 font-medium text-lg">รหัสประจำตัวนักศึกษา</label>
                                        <input :class="{'border-red-500': errors.member_personal_id}"
                                            class="border border-gray-300 rounded-md p-2 focus:ring-sky-500 focus:ring-2 focus:border-sky-400"
                                            type="text" id="personal_id" x-model="formData.member_personal_id"
                                            placeholder="รหัสนักศึกษา">
                                        <span x-show="errors.member_personal_id" class="text-red-500 text-xs"
                                            x-text="errors.member_personal_id" style="display: none;"></span>
                                    </div>
                                </template>

                                <div class="flex flex-col space-y-1">
                                    <label for="faculty" class="text-gray-700 font-medium text-lg">คณะ <span
                                            class="text-red-500">*</span></label>
                                    <select id="faculty" x-model="formData.faculty_id" @change="fetchMajors()"
                                        class="border border-gray-300 rounded-md p-2 focus:ring-emerald-500 focus:ring-2 focus:border-emerald-400 bg-white"
                                        :class="{'border-red-500': errors.faculty_id}">
                                        <option value="">เลือกคณะ</option>
                                        <template x-for="faculty in faculties" :key="faculty.faculty_id">
                                            <option :value="faculty.faculty_id" x-text="faculty.faculty_name"></option>
                                        </template>
                                    </select>
                                    <span x-show="errors.faculty_id" class="text-red-500 text-xs"
                                        x-text="errors.faculty_id"></span>
                                </div>

                                <div x-show="formData.faculty_id" class="flex flex-col space-y-1">
                                    <label for="major" class="text-gray-700 font-medium text-lg">สาขา</label>
                                    <select id="major" x-model="formData.major_id"
                                        class="border border-gray-300 rounded-md p-2 focus:ring-emerald-500 focus:ring-2 focus:border-emerald-400 bg-white disabled:bg-gray-100"
                                        :disabled="!formData.faculty_id || majors.length === 0">
                                        <option value=""
                                            x-text="majors.length === 0 ? `ไม่มีสาขาที่เข้าร่วม` : `เลือกสาขา`">
                                            เลือกสาขา
                                        </option>
                                        <template x-for="major in majors" :key="major.major_id">
                                            <option :value="major.major_id" x-text="major.major_name"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>
                        </template>

                    </div>

                    <div class="pt-6 flex gap-3">
                        <button type="button" @click="prevStep"
                            class="w-1/3 font-semibold border border-2 border-gray-500 text-gray-500 hover:scale-102 py-3 px-4 rounded-lg cursor-pointer transition-all">
                            กลับ
                        </button>
                        <button type="submit" class="w-2/3 font-semibold py-3 px-4 rounded-lg text-white" :class="{
                                'bg-gray-400 cursor-not-allowed': getSubmitButtonText() === 'กรุณากรอกข้อมูลให้ครบถ้วน',
                                'bg-emerald-600 hover:bg-emerald-700 cursor-pointer transition-all hover:scale-102': getSubmitButtonText() === 'ยืนยันการสมัคร'
                            }" x-text="getSubmitButtonText()">
                        </button>
                    </div>
                    <div class="w-full text-end">
                        <span class="text-xs text-gray-400 ">ข้อมูลที่ไม่มี * สามารถกรอกภายหลังได้</span>
                    </div>

                </div>

                <div x-show="step === 1" class="text-center mt-4">
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
                        </div>
                        <div>
                            <img class="h-10" src="assets/images/bru_gogreen_logo.png" alt="">
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