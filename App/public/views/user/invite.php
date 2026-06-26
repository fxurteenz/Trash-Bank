<div class="grid gap-0 pb-20" x-data="invitePageData()">
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
            <div class="w-[80px] h-[80px] rounded-full bg-gradient-to-br from-[#1B8B4B] to-[#0D6B38] text-white text-[32px] font-[800] grid place-items-center shadow-[0_4px_12px_rgba(27,139,75,0.3)] mb-4"
                x-text="user.name.charAt(0)">
            </div>
            <div class="text-[18px] font-[800] text-[#1A1A2E]" x-text="user.name"></div>
            <div class="text-[13px] text-[#1B8B4B] font-[700] mt-1 bg-[#E8F5EE] px-3 py-1 rounded-full mb-6">
                <span x-text="user.role"></span>
            </div>

            <!-- พื้นที่แสดง QR Code -->
            <div class="w-full flex flex-col items-center justify-center bg-[#F4F5F7] p-4 rounded-xl">
                <div id="qrcode" class="w-full bg-white p-2 rounded-lg"></div>
            </div>

            <div class="text-[12px] text-[#9CA3AF] mt-5 text-center">
                ให้เพื่อนสแกน QR Code นี้เพื่อสมัครสมาชิก<br>แล้วรับแต้มสังคม!
            </div>

            <div class="mt-4 w-full">
                <button @click="copyLink()" :class="copyButton.class"
                    class="w-full text-white font-bold py-3 px-4 rounded-lg transition-colors duration-200 ease-in-out flex items-center justify-center gap-2 cursor-pointer">
                    <span id="copyIcon"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                            viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 10a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-8a2 2 0 0 1-2-2zM4 14a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2" />
                        </svg></span>
                    <span x-text="copyButton.text"></span>
                </button>
            </div>
        </div>

        <!-- Invitation History -->
        <div
            class="bg-white mx-[14px] mt-[14px] mb-0 rounded-[16px] overflow-hidden shadow-[0_1px_6px_rgba(0,0,0,0.07)] w-full">
            <div class="flex items-center justify-between px-[16px] pt-[14px] pb-[10px]">
                <div class="text-[14px] font-[800] text-[#1A1A2E]">ประวัติการชวนเพื่อน</div>
                <div class="text-[12px] text-[#1B8B4B] font-[700]" x-text="`ทั้งหมด ${invitations.length} คน`"></div>
            </div>
            <div class="pb-2">
                <template x-if="isLoading">
                    <div class="text-center py-4 text-[#9CA3AF] text-[13px]">กำลังโหลดข้อมูล...</div>
                </template>
                <template x-if="!isLoading && invitations.length === 0">
                    <div class="text-center py-4 text-[#9CA3AF] text-[13px]">ยังไม่มีประวัติการชวนเพื่อน</div>
                </template>
                <template x-for="(invite, index) in invitations" :key="index">
                    <div
                        class="flex items-center gap-[12px] py-[13px] px-[16px] border-b border-[#F0F1F3] last:border-b-0">
                        <div class="w-[40px] h-[40px] rounded-full grid place-items-center shrink-0 bg-gray-100 text-gray-600 text-lg font-bold"
                            x-text="invite.invitee_name.charAt(0)">
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-[13px] font-[700] text-[#1A1A2E]" x-text="invite.invitee_name"></div>
                            <div class="text-[11px] text-[#9CA3AF] mt-[2px]" x-text="formatDate(invite.created_at)"></div>
                        </div>
                        <div class="text-[14px] font-[800] text-[#1B8B4B]">+1 แต้มสังคม</div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('invitePageData', () => ({
            user: {
                id: <?php echo (int) $user->member_id; ?>,
                name: '<?php echo htmlspecialchars($user->member_name, ENT_QUOTES); ?>',
                role: 'สมาชิก'
            },
            registerUrl: '',
            isLoading: true,
            invitations: [],
            copyButton: {
                text: 'คัดลอกลิงค์',
                class: 'bg-emerald-600 hover:bg-emerald-700'
            },

            init() {
                this.registerUrl = `https://gogreen.bru.ac.th/register?recruiter=${this.user.id}`;
                this.generateQrCode();
                this.fetchInvitations();
            },

            generateQrCode() {
                const qrcodeContainer = document.getElementById('qrcode');
                if (this.user.id && qrcodeContainer) {
                    new QRCode(qrcodeContainer, {
                        text: this.registerUrl,
                        width: 400,
                        height: 400,
                        colorDark: "#000000",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.H
                    });
                } else {
                    qrcodeContainer.innerHTML = '<p class="text-center text-red-500">ไม่สามารถสร้าง QR Code ได้</p>';
                }
            },

            async fetchInvitations() {
                this.isLoading = true;
                try {
                    const response = await fetch(`/api/members/invitations/${this.user.id}`);
                    const result = await response.json();
                    if (result.success) {
                        this.invitations = result.data;
                    }
                } catch (error) {
                    console.error('Error fetching invitations:', error);
                } finally {
                    this.isLoading = false;
                }
            },

            copyLink() {
                navigator.clipboard.writeText(this.registerUrl).then(() => {
                    const originalText = this.copyButton.text;
                    const originalClass = this.copyButton.class;

                    this.copyButton.text = 'คัดลอกแล้ว!';
                    this.copyButton.class = 'bg-green-700';

                    setTimeout(() => {
                        this.copyButton.text = originalText;
                        this.copyButton.class = originalClass;
                    }, 2000);
                });
            },

            formatDate(dateString) {
                if (!dateString) return '-';
                const date = new Date(dateString);
                const day = date.getDate().toString().padStart(2, '0');
                const month = (date.getMonth() + 1).toString().padStart(2, '0');
                return `${day}/${month}/${date.getFullYear() + 543} ${date.getHours().toString().padStart(2, '0')}:${date.getMinutes().toString().padStart(2, '0')} น.`;
            }
        }));
    })
</script>