<div class="grid gap-0 pb-6" x-data="barcodeData()">
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

    <div class="mt-6 mx-[14px] flex flex-col items-center">
        <template x-if="isLoading">
            <div class="text-center py-4 text-[#9CA3AF] text-[13px]">กำลังโหลดข้อมูล...</div>
        </template>

        <!-- แสดงบาร์โค้ดเมื่อมีเบอร์โทรศัพท์ -->
        <template x-if="!isLoading && profile.member_phone">
            <div
                class="bg-white rounded-[16px] w-full p-6 shadow-[0_4px_20px_rgba(0,0,0,0.08)] flex flex-col items-center">
                <!-- Avatar -->
                <div class="w-[80px] h-[80px] rounded-full bg-gradient-to-br from-[#1B8B4B] to-[#0D6B38] text-white text-[32px] font-[800] grid place-items-center shadow-[0_4px_12px_rgba(27,139,75,0.3)] mb-4"
                    x-text="profile?.member_name ? profile.member_name.charAt(0) : ''">
                </div>
                <div class="text-[18px] font-[800] text-[#1A1A2E]" x-text="profile.member_name"></div>
                <div class="text-[13px] text-[#1B8B4B] font-[700] mt-1 bg-[#E8F5EE] px-3 py-1 rounded-full mb-6"
                    x-text="profile.role_name_th || 'สมาชิก'"></div>

                <!-- พื้นที่แสดงบาร์โค้ด -->
                <div class="w-full flex flex-col items-center justify-center bg-[#F4F5F7] p-4 rounded-xl">
                    <svg id="barcode"></svg>
                    <!-- แสดงเบอร์โทรด้านล่างบาร์โค้ดให้เว้นระยะอ่านง่าย -->
                    <div class="text-[18px] font-[800] text-[#1A1A2E] mt-2 tracking-widest"
                        x-text="profile.member_phone.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3')">
                    </div>
                </div>

                <div class="text-[12px] text-[#9CA3AF] mt-5 text-center">
                    โปรดแสดงบาร์โค้ดนี้แก่เจ้าหน้าที่เมื่อทำการฝากขยะ</div>
            </div>
        </template>

        <!-- กรณีไม่มีเบอร์โทรศัพท์ -->
        <template x-if="!isLoading && !profile.member_phone">
            <div
                class="bg-white rounded-[16px] w-full p-6 shadow-[0_4px_20px_rgba(0,0,0,0.08)] flex flex-col items-center text-center">
                <div class="text-red-500 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10s10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                    </svg>
                </div>
                <div class="text-[16px] font-[700] text-[#1A1A2E]">ไม่พบข้อมูลเบอร์โทรศัพท์</div>
                <div class="text-[13px] text-[#9CA3AF] mt-2">กรุณาเพิ่มเบอร์โทรศัพท์ในหน้าโปรไฟล์เพื่อใช้งานบาร์โค้ด
                </div>
                <a href="/user/profile"
                    class="mt-5 text-[13px] text-white font-[700] bg-[#1B8B4B] px-5 py-2.5 rounded-full no-underline shadow-md">ไปแก้ไขหน้าโปรไฟล์</a>
            </div>
        </template>
    </div>
</div>

<!-- โหลดไลบรารี JsBarcode -->
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('barcodeData', () => ({
            isLoading: true,
            profile: {},

            async init() {
                try {
                    const memberId = <?php echo (int) ($user->member_id ?? 0); ?>;
                    // if (!memberId) {
                    //     window.location.href = '/login';
                    //     return;
                    // }

                    const res = await fetch(`/api/members/profile/${memberId}`);
                    const data = await res.json();

                    if (data.success && data.data) {
                        this.profile = data.data;
                        if (this.profile.member_phone) {
                            // ใช้ $nextTick เพื่อให้ Alpine.js เรนเดอร์ DOM ก่อนวาดบาร์โค้ด
                            this.$nextTick(() => {
                                JsBarcode("#barcode", this.profile.member_phone, {
                                    format: "CODE128", // รูปแบบบาร์โค้ดมาตรฐาน
                                    lineColor: "#1A1A2E",
                                    width: 2.5,
                                    height: 80,
                                    displayValue: false, // ปิดตัวเลขของ JsBarcode เพราะเราเอามาจัด format สวยๆ เองด้านล่าง
                                    margin: 0,
                                    background: "transparent"
                                });
                            });
                        }
                    }
                } catch (error) {
                    console.error('Failed to load profile data:', error);
                } finally {
                    this.isLoading = false;
                }
            }
        }));
    });
</script>