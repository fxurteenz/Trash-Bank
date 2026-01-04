<div class="flex justify-center items-center min-h-screen">
    <div class="bg-white p-4 rounded-md shadow-md w-80 text-sm">
        <h1 class="text-center text-xl font-bold">เข้าสู่ระบบ</h1>
        <div class="space-y-2">
            <div class="flex flex-col">
                <label for="identifier">ผู้ใช้งาน</label>
                <input type="text" name="identifier" id="identifier" class="p-2 rounded-md border-gray-200 border-1"
                    placeholder="เบอร์โทรศัพท์ อีเมล์ หรือ รหัสประจำตัว">
            </div>

            <div class="flex flex-col">
                <label for="password">รหัสผ่าน</label>
                <input type="password" name="password" id="password" class="p-2 rounded-md border-gray-200 border-1"
                    placeholder="รหัสผ่าน">
            </div>

            <button class="w-full font-semibold text-white py-2 px-4 rounded-md bg-orange-400 cursor-pointer"
                onClick="OnSubmit()">
                เข้าสู่ระบบ
            </button>
        </div>
    </div>
</div>