<div x-data="{ isMenuOpen: false }" class="min-h-screen flex flex-col bg-gray-50 text-gray-800">

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
                    <a href="/register"
                        class="bg-white border-2 border-green-600 text-green-600 hover:bg-green-50 cursor-pointer hover:scale-105 active:scale-98 px-6 py-2 rounded-full font-semibold transition-all">
                        สมัครสมาชิก
                    </a>
                </div>
            </div>
        </div>

    </nav>

    <div class="flex-1 flex justify-center items-center px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-8 w-96 max-w-full">

            <h3 class="font-bold text-2xl mb-6 text-center text-gray-800">เข้าสู่ระบบ</h3>

            <div class="space-y-4 text-sm">
                <div class="flex flex-col space-y-1">
                    <label for="identifier" class="text-gray-700 font-medium">ผู้ใช้งาน</label>
                    <input type="text" name="identifier" id="identifier"
                        class="border border-gray-300 rounded-md p-2.5 focus:ring-emerald-500 focus:ring-2 focus:border-emerald-400 outline-none transition-all"
                        placeholder="เบอร์โทรศัพท์, อีเมล หรือ รหัสประจำตัว">
                </div>

                <div class="flex flex-col space-y-1">
                    <div class="flex justify-between items-center">
                        <label for="password" class="text-gray-700 font-medium">รหัสผ่าน</label>
                        <a href="#" onclick="event.preventDefault(); showForgotPasswordDialog();"
                            class="text-xs text-emerald-600 hover:underline">ลืมรหัสผ่าน?</a>
                    </div>
                    <input type="password" name="password" id="password"
                        class="border border-gray-300 rounded-md p-2.5 focus:ring-emerald-500 focus:ring-2 focus:border-emerald-400 outline-none transition-all"
                        placeholder="รหัสผ่านของคุณ">
                </div>

                <div class="pt-4">
                    <button
                        class="w-full font-semibold text-white py-3 px-4 rounded-lg bg-emerald-600 hover:bg-emerald-700 cursor-pointer transition-all shadow-md transform hover:scale-105 active:scale-98"
                        onClick="OnSubmit()">
                        เข้าสู่ระบบ
                    </button>
                </div>

                <div class="text-center mt-6 pt-6 border-t border-gray-100">
                    <p class="text-gray-600 text-sm">
                        ยังไม่มีบัญชีผู้ใช้?
                        <a href="/register" class="text-emerald-600 hover:underline font-semibold">สมัครสมาชิกเลย</a>
                    </p>
                </div>
            </div>
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

<script type="text/javascript">
    async function OnSubmit(e) {
        if (e) e.preventDefault(); // ถ้าเรียกจาก <form onSubmit={...}>

        console.log("submit");

        const identifierInput = document.getElementById("identifier");
        const passwordInput = document.getElementById("password");

        if (!identifierInput || !passwordInput) {
            console.error("ไม่พบช่องกรอกข้อมูลหรือรหัสผ่าน");
            return;
        }

        const identifier = identifierInput.value.trim();
        const password = passwordInput.value;

        if (!identifier || !password) {
            console.error("กรุณากรอกข้อมูลและรหัสผ่านให้ครบถ้วน");
            return;
        }

        try {
            const response = await fetch("/login", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ identifier, password }),
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.message || `HTTP ${response.status}`);
            }

            const result = await response.json();
            console.log("Login success:", result);
            if (result.success) {
                await Swal.fire({
                    icon: "success",
                    title: "เข้าสู่ระบบสำเร็จ",
                    text: "กำลังพาท่านเข้าสู่ระบบ...",
                    timer: 1500,
                    showConfirmButton: false,
                });
                window.location.reload();
            } else {
                throw new Error(result.message || result, 500);
            }
        } catch (error) {
            console.error("Login failed:", error);
            Swal.fire({
                icon: "error",
                title: "เข้าสู่ระบบไม่สำเร็จ",
                text: error.message || "ข้อมูลหรือรหัสผ่านไม่ถูกต้อง",
            });
        }
    }

    function showForgotPasswordDialog() {
        Swal.fire({
            icon: 'info',
            title: 'ลืมรหัสผ่าน',
            text: 'กรุณาติดต่อศูนย์ธนาคารขยะเพื่อรีเซ็ทรหัสผ่าน ขออภัยในความไม่สะดวก',
            confirmButtonText: 'ตกลง',
            confirmButtonColor: '#059669',
        });
    }

</script>