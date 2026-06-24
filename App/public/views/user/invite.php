<div class="grid gap-0 pb-6">
    <!-- Top Bar -->
    <div
        class="bg-white px-4 pt-[14px] pb-3 flex items-center justify-between sticky top-0 z-[100] border-b border-[#F0F1F3]">
        <div class="flex items-center gap-4">
            <a href="/user"
                class="text-[22px] no-underline text-[#1A1A2E] leading-none w-[36px] h-[36px] rounded-full bg-[#F4F5F7] flex items-center justify-center">‹</a>
        </div>
        <div class="text-[17px] font-[800] text-[#1A1A2E]">ชวนเพื่อน</div>
        <div class="w-[36px]"></div> 
    </div>

    <div class="mt-6 mx-[14px] flex flex-col items-center">
        <div class="bg-white rounded-[16px] w-full p-6 shadow-[0_4px_20px_rgba(0,0,0,0.08)] flex flex-col items-center">
            <!-- Avatar -->
            <div class="w-[80px] h-[80px] rounded-full bg-gradient-to-br from-[#1B8B4B] to-[#0D6B38] text-white text-[32px] font-[800] grid place-items-center shadow-[0_4px_12px_rgba(27,139,75,0.3)] mb-4">
                <?php echo htmlspecialchars(mb_strtoupper(mb_substr($user->member_name, 0, 1, 'UTF-8'), 'UTF-8')); ?>
            </div>
            <div class="text-[18px] font-[800] text-[#1A1A2E]"><?php echo htmlspecialchars($user->member_name); ?></div>
            <div class="text-[13px] text-[#1B8B4B] font-[700] mt-1 bg-[#E8F5EE] px-3 py-1 rounded-full mb-6">
                สมาชิก
            </div>

            <!-- พื้นที่แสดง QR Code -->
            <div class="w-full flex flex-col items-center justify-center bg-[#F4F5F7] p-4 rounded-xl">
                <div id="qrcode" class="w-full bg-white p-2 rounded-lg"></div>
            </div>

            <div class="text-[12px] text-[#9CA3AF] mt-5 text-center">
                ให้เพื่อนสแกน QR Code นี้เพื่อสมัครสมาชิก<br>แล้วรับแต้มสังคม!
            </div>
        </div>
    </div>
</div>

<!-- โหลดไลบรารี qrcode.js -->
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inviterId = <?php echo (int) $user->member_id; ?>;
        const registerUrl = `https://gogreen.bru.ac.th/register?recruiter=${inviterId}`;

        const qrcodeContainer = document.getElementById('qrcode');

        if (inviterId && qrcodeContainer) {
            new QRCode(qrcodeContainer, {
                text: registerUrl,
                width: 400,
                height: 400,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        } else {
            qrcodeContainer.innerHTML = '<p class="text-center text-red-500">ไม่สามารถสร้าง QR Code ได้</p>';
        }
    });
</script>