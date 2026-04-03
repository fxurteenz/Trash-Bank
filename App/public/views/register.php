<!DOCTYPE html>
<html lang="th">

<head>
    <title><?= $title ?? 'สมัครสมาชิก' ?></title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- <link href="/assets/output.css" rel="stylesheet"> -->
    <script defer src="/js/alpine.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap');

        .noto-sans-thai {
            font-family: "Noto Sans Thai", sans-serif;
        }

        .open-sans {
            font-family: "Open Sans", sans-serif;
        }
    </style>
</head>

<body class="min-h-screen noto-sans-thai m-0 p-0 bg-gray-100">

    <div x-data="registrationForm()" x-init="init()" class="flex justify-center items-center min-h-screen">
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
                        class="w-full font-semibold text-white py-3 px-4 rounded-lg bg-emerald-600 hover:bg-emerald-700 cursor-pointer transition-colors shadow-md">
                        ยืนยันการสมัคร
                    </button>
                </div>
                <div class="text-center mt-4">
                    <a href="/login" class="text-sm text-emerald-600 hover:underline">มีบัญชีอยู่แล้ว? เข้าสู่ระบบ</a>
                </div>
            </form>
        </div>
    </div>

    <script src="/js/swal.min.js"></script>
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
</body>

</html>