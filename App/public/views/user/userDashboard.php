<!-- Top Bar -->
<div class="grid gap-0" x-data="dashboardData()">
    <div
        class="relative bg-white px-4 pt-[14px] pb-3 flex items-center justify-between sticky top-0 z-[100] border-b border-[#F0F1F3]">

        <a href="/" class="flex items-center gap-[2px] no-underline text-[14px] text-gray-400">
            <i data-lucide="circle-chevron-left" class="h-[14px]"></i>
            <span>หน้าหลัก</span>
        </a>

        <img src="/assets/images/waste_bankFullLogo.png" class="absolute left-1/2 -translate-x-1/2 h-[20px]" alt="">

        <div class="flex items-center gap-[10px]">
            <a href="/logout" class="text-[17px] no-underline cursor-pointer" aria-label="ออกจากระบบ"
                title="ออกจากระบบ">
                <i data-lucide="log-out" class="w-[14px] h-[14px] text-gray-400"></i>
            </a>
        </div>
    </div>

    <!-- Account Card -->
    <div class="relative mx-[14px] mt-[14px] aspect-[16/9] rounded-2xl shadow-2xl overflow-hidden text-gray-900 bg-gradient-to-br from-sky-600 to-sky-700 z-0"
        style="background-image: url('/assets/images/card2.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <!-- <div
        class="relative mx-[14px] mt-[14px] aspect-[16/9] rounded-2xl shadow-2xl overflow-hidden text-gray-900 bg-gradient-to-r from-emerald-600 via-emerald-500 via-emerald-550 via-40% via-emerald-500 to-emerald-600 z-0"> -->

        <!-- แผ่นกระจกใส (Glassmorphism Overlay) ตรงกลางบัตร -->
        <div
            class="absolute inset-[15px] sm:inset-[20px] backdrop-blur border border-gray-100 border-1 rounded-2xl p-4 sm:p-5 flex flex-col justify-between ">

            <div class="flex flex-col h-full">

                <!-- ด้านซ้าย: ข้อมูลสมาชิก และ หมายเลขบัตร -->
                <div class="flex-1 flex flex-col justify-between">

                    <div>
                        <div class="text-sm font-bold text-gray-800">
                            <span x-text="profile?.member_name || '...'"></span>
                        </div>

                    </div>

                    <div
                        class="w-12 h-9 sm:w-14 sm:h-10 rounded-md bg-gradient-to-br from-[#fbe396] to-[#d6aa32] border border-yellow-600/50 shadow-inner relative overflow-hidden flex flex-wrap opacity-90">
                        <div class="absolute w-full h-[1px] bg-yellow-700/40 top-[30%]"></div>
                        <div class="absolute w-full h-[1px] bg-yellow-700/40 bottom-[30%]"></div>
                        <div class="absolute w-[1px] h-full bg-yellow-700/40 left-[30%]"></div>
                        <div class="absolute w-[1px] h-full bg-yellow-700/40 right-[30%]"></div>
                    </div>

                    <!-- หมายเลขบัตร และ วันหมดอายุ -->
                    <div class="flex flex-row items-end justify-between w-full">
                        <div class=" text-xl font-bold tracking-widest text-gray-900 drop-shadow-sm "
                            x-text="profile?.member_personal_id || 'xxx-xxxxxx-xxx'">
                        </div>
                        <div class="flex items-center gap-1 text-[10px] sm:text-xs font-bold leading-tight">

                        </div>
                    </div>
                </div>

                <!-- ด้านขวา: แสดงแต้มต่างๆ (เน้นแต้มขยะ) -->
                <div class="w-full flex justify-between">

                    <!-- กล่องแต้มขยะสะสม (เน้นพิเศษ) -->
                    <div class=" p-2 rounded-xl flex flex-row items-center justify-between">

                        <div class="flex flex-col text-right text-gray-900">
                            <div class="text-xs font-bold">แต้มขยะคงเหลือ</div>
                            <div class="text-lg font-black leading-none tracking-tight py-1"
                                x-text="Number(profile?.member_waste_point || 0).toLocaleString()">0000</div>
                        </div>
                    </div>

                    <!-- กล่องแต้มความดี -->
                    <div class="p-2 rounded-xl flex flex-row items-center justify-between">
                        <div class="text-right">
                            <div class="text-xs font-extrabold text-gray-700">แต้มความดี</div>
                            <div class="flex items-baseline justify-end gap-1">
                                <span class="text-lg font-black text-gray-900"
                                    x-text="Number(profile?.member_goodness_point || 0).toLocaleString()">310</span>
                            </div>
                        </div>
                    </div>

                    <!-- กล่องแต้มมิตรภาพ (สังคม) -->
                    <div class="p-2 px-3 rounded-xl flex flex-row items-center justify-between">
                        <div class="text-right">
                            <div class="text-[11px] font-extrabold text-gray-700">แต้มมิตรภาพ</div>
                            <div class="flex items-baseline justify-end gap-1">
                                <span class="text-lg sm:text-xl font-black text-gray-900"
                                    x-text="Number(profile?.member_social_point || 0).toLocaleString()">0</span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>

    <!-- Stats -->
    <div class="bg-white mx-[14px] mt-[14px] rounded-2xl overflow-hidden shadow-[0_1px_6px_rgba(0,0,0,0.07)] z-0">
        <div class="flex items-center justify-between px-[16px] pt-[14px] pb-[10px]">
            <div class="text-[14px] font-bold text-gray-800" x-text="showTotalPoints ? 'แต้มสะสม' : 'แต้มคงเหลือ'">
                แต้มคงเหลือ</div>
            <button @click="showTotalPoints = !showTotalPoints"
                class="w-8 h-8 rounded-full grid place-items-center text-gray-400 hover:bg-gray-100 active:bg-gray-200 transition-colors"
                title="สลับมุมมอง">
                <i data-lucide="arrow-left-right" class="h-4"></i>
            </button>
        </div>
        <div class="grid grid-cols-5 border-t border-[#F0F1F3]">
            <!-- แต้มรักษ์โลก -->
            <div class="p-[12px_8px] text-center border-r border-[#F0F1F3] last:border-r-0">
                <div class="flex justify-center items-center">
                    <img src="assets/images/green_coin.png" class="h-10" alt="เหรียญแต้มรักษ์โลก">
                </div>
                <div class="text-[17px] font-[900] text-emerald-600 mt-[3px]"
                    x-text="`${parseInt(showTotalPoints ? (profile?.total_co2e) : (profile?.total_co2e )).toLocaleString()}`">
                </div>
                <div class="text-[10px] text-gray-400 mt-[2px]">แต้มรักษ์โลก</div>
            </div>
            <!-- แต้มความดี -->
            <div class="p-[12px_8px] text-center border-r border-[#F0F1F3] last:border-r-0">
                <div class="flex justify-center items-center">
                    <img src="assets/images/yellow_coin.png" class="h-10" alt="เหรียญแต้มความดี">
                </div>
                <div class="text-[17px] font-[900] text-yellow-500 mt-[3px]"
                    x-text="parseInt(showTotalPoints ? (profile?.member_total_goodness_point || 0) : (profile?.member_goodness_point || 0)).toLocaleString()">
                </div>
                <div class="text-[10px] text-gray-400 mt-[2px]">แต้มความดี</div>
            </div>
            <!-- แต้มมิตรภาพ -->
            <div class="p-[12px_8px] text-center border-r border-[#F0F1F3] last:border-r-0">
                <div class="flex justify-center items-center">
                    <img src="assets/images/red_coin.png" class="h-10" alt="เหรียญแต้มมิตรภาพ">
                </div>
                <div class="text-[17px] font-[900] text-red-700 mt-[3px]"
                    x-text="parseInt(showTotalPoints ? (profile?.member_total_social_point || 0) : (profile?.member_social_point || 0)).toLocaleString()">
                </div>
                <div class="text-[10px] text-gray-400 mt-[2px]">มิตรภาพ</div>
            </div>
            <!-- แต้มกิจกรรม -->
            <div class="p-[12px_8px] text-center border-r border-[#F0F1F3] last:border-r-0">
                <div class="flex justify-center items-center">
                    <img src="assets/images/purple_coin.png" class="h-10" alt="เหรียญแต้มกิจกรรม">
                </div>
                <div class="text-[17px] font-[900] text-purple-800 mt-[3px]"
                    x-text="parseFloat(showTotalPoints ? (profile?.member_point_event_sum || 0) : (profile?.member_point_event || 0)).toLocaleString()">
                </div>
                <div class="text-[10px] text-gray-400 mt-[2px]">แต้มกิจกรรม</div>
            </div>
            <!-- แต้มอื่นๆ -->
            <div class="p-[12px_8px] text-center border-r border-[#F0F1F3] last:border-r-0">
                <div class="flex justify-center items-center">
                    <img src="assets/images/pink_coin.png" class="h-10" alt="เหรียญจิตอาสา">
                </div>
                <div class="text-[17px] font-[900] text-pink-600 mt-[3px]" x-text="parseFloat(0).toLocaleString()">
                </div>
                <div class="text-[10px] text-gray-400 mt-[2px]">แต้มจิตอาสา</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white mx-[14px] mt-[14px] rounded-2xl overflow-hidden shadow-[0_1px_6px_rgba(0,0,0,0.07)] z-0">
        <div class="flex items-center justify-between px-[16px] pt-[14px] pb-[10px]">
            <div class="text-[14px] font-bold text-gray-800">เมนู</div>
        </div>
        <div class="grid grid-cols-6 border-t border-[#F0F1F3] p-2">
            <a class="flex flex-col items-center gap-[7px] no-underline p-[4px_2px] rounded-[12px] transition-colors active:bg-gray-200"
                href="/user/invite">
                <div
                    class="w-[46px] h-[46px] rounded-[14px] grid place-items-center text-[21px] bg-gray-100 text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                        <path d="M0 0h24v24H0z" fill="none" />
                        <path fill="currentColor"
                            d="M5 5h2v2H5zM1 1h10v10H1zm2 2v6h6V3zm2 14h2v2H5zm-4-4h10v10H1zm2 2v6h6v-6zm10-2h4v2h2v-2h4v2h-4v2h4v6h-4v-2h-4v2h-2v-2h2v-2h-2zm8 8v-2h-2v2zm-2-4h-2v-2h-2v4h4zM17 2v3h-3v2h3v3h2V7h3V5h-3V2Z" />
                    </svg>

                </div>
                <div class="text-[11px] font-regular text-gray-400 text-center">เชิญเพื่อน</div>
            </a>

            <a class="flex flex-col items-center gap-[7px] no-underline p-[4px_2px] rounded-[12px] transition-colors active:bg-gray-200"
                href="/user/barcode">
                <div
                    class="w-[46px] h-[46px] rounded-[14px] grid place-items-center text-[21px] bg-gray-100 text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                        <path d="M0 0h24v24H0z" fill="none" />
                        <g fill="none" stroke="currentColor" stroke-linecap="round">
                            <path stroke-width="2"
                                d="M2.992 4.983v13.934m6.97-13.934v13.934m5.976-13.934v13.934m2.987-13.934v13.934" />
                            <path d="M5.48 4.483v14.934M7.47 4.483v14.934M21.413 4.483v14.934M13.446 4.483v14.934" />
                        </g>
                    </svg>

                </div>
                <div class="text-[11px] font-regular text-gray-400 text-center">บาร์โค้ด</div>
            </a>

            <a class="flex flex-col items-center gap-[7px] no-underline p-[4px_2px] rounded-[12px] transition-colors">
                <div class=" w-[46px] h-[46px] rounded-[14px] grid place-items-center text-[21px] border border-gray-200 border-dashed border-2
                text-gray-600">
                </div>
                <div class="text-[11px] font-regular text-gray-300 text-center"></div>
            </a>

            <a class="flex flex-col items-center gap-[7px] no-underline p-[4px_2px] rounded-[12px] transition-colors">
                <div class=" w-[46px] h-[46px] rounded-[14px] grid place-items-center text-[21px] border border-gray-200 border-dashed border-2
                text-gray-600">
                </div>
                <div class="text-[11px] font-regular text-gray-300 text-center"></div>
            </a>
            <a class="flex flex-col items-center gap-[7px] no-underline p-[4px_2px] rounded-[12px] transition-colors">
                <div class=" w-[46px] h-[46px] rounded-[14px] grid place-items-center text-[21px] border border-gray-200 border-dashed border-2
                text-gray-600">
                </div>
                <div class="text-[11px] font-regular text-gray-300 text-center"></div>
            </a>
            <a class="flex flex-col items-center gap-[7px] no-underline p-[4px_2px] rounded-[12px] transition-colors">
                <div class=" w-[46px] h-[46px] rounded-[14px] grid place-items-center text-[21px] border border-gray-200 border-dashed border-2
                text-gray-600">
                </div>
                <div class="text-[11px] font-regular text-gray-300 text-center"></div>
            </a>
        </div>
    </div>

    <!-- Waste Breakdown -->
    <div class="bg-white mx-[14px] mt-[14px] rounded-2xl overflow-hidden shadow-[0_1px_6px_rgba(0,0,0,0.07)] z-0">
        <div class="flex items-center justify-between px-[16px] pt-[14px] pb-[10px]">
            <div class="text-[14px] font-bold text-gray-800">สัดส่วนขยะที่ฝาก</div>
            <a href="#" class="text-[12px] text-[#1B8B4B] font-[700] no-underline">ดูทั้งหมด ›</a>
        </div>
        <div class="px-[16px] pb-[14px]">
            <template x-if="isLoading">
                <div class="text-center py-4 text-gray-400 text-[13px]">กำลังโหลดข้อมูล...</div>
            </template>
            <template x-if="!isLoading && wasteBreakdown.length === 0">
                <div class="text-center py-4 text-gray-400 text-[13px]">ยังไม่มีข้อมูลสัดส่วนขยะ</div>
            </template>
            <template x-for="(item, index) in wasteBreakdown" :key="index">
                <div class="flex items-center gap-[10px] py-[7px]">
                    <div class="w-[8px] h-[8px] rounded-full shrink-0" :style="`background:${item.color}`"></div>
                    <div class="text-[13px] text-[#374151] font-[600] flex-1" x-text="item.name"></div>
                    <div class="flex-[2] bg-[#F0F1F3] rounded-full h-[6px] overflow-hidden">
                        <div class="h-full rounded-full" :style="`width:${item.percentage}%;background:${item.color}`">
                        </div>
                    </div>
                    <div class="text-[12px] text-[#6B7280] font-[700] min-w-[52px] text-right"
                        x-text="`${item.weight.toLocaleString(undefined, {minimumFractionDigits: 1, maximumFractionDigits: 1})} กก.`">
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white mx-[14px] mt-[14px] mb-0 rounded-2xl overflow-hidden shadow-[0_1px_6px_rgba(0,0,0,0.07)] z-0">
        <div class="flex items-center justify-between px-[16px] pt-[14px] pb-[10px]">
            <div class="text-[14px] font-bold text-gray-800">รายการล่าสุด</div>
            <a href="#" class="text-[12px] text-[#1B8B4B] font-[700] no-underline">ดูทั้งหมด ›</a>
        </div>
        <div class="pb-2">
            <template x-if="isLoading">
                <div class="text-center py-4 text-gray-400 text-[13px]">กำลังโหลดข้อมูล...</div>
            </template>
            <template x-if="!isLoading && recentTransactions.length === 0">
                <div class="text-center py-4 text-gray-400 text-[13px]">ยังไม่มีรายการล่าสุด</div>
            </template>
            <template x-for="(tx, index) in recentTransactions" :key="index">
                <div class="flex items-center gap-[12px] py-[13px] px-[16px] border-b border-[#F0F1F3] last:border-b-0">
                    <div class="w-[40px] h-[40px] rounded-[12px] grid place-items-center shrink-0" :class="tx.bgColor"
                        x-html="tx.icon"></div>
                    <div class="flex-1 min-w-0">
                        <div class="text-[13px] font-[700] text-gray-800" x-text="tx.title"></div>
                        <div class="text-[11px] text-gray-400 mt-[2px]" x-text="tx.dateStr"></div>
                    </div>
                    <div class="text-[14px] font-bold" :class="tx.pointColor" x-text="tx.pointText"></div>
                </div>
            </template>
        </div>
    </div>

</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('dashboardData', () => ({
            isLoading: true,
            profile: {},
            totals: { weight: 0, carbon: 0, credit: 0 },
            recentTransactions: [],
            wasteBreakdown: [],
            showTotalPoints: false,

            async init() {
                try {
                    const memberId = <?php echo (int) $user->member_id; ?>

                    if (!memberId) {
                        window.location.href = '/login';
                        return;
                    }

                    const depositIconSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.5"><path stroke-linejoin="round" d="M12 22c-.818 0-1.6-.325-3.163-.974C4.946 19.41 3 18.602 3 17.243V7.745M12 22c.818 0 1.6-.325 3.163-.974C19.054 19.41 21 18.602 21 17.243V7.745M12 22v-9.831M3 7.745c0 .603.802.985 2.405 1.747l2.92 1.39C10.13 11.74 11.03 12.17 12 12.17M3 7.745c0-.604.802-.986 2.405-1.748L7.5 5M21 7.745c0 .603-.802.985-2.405 1.747l-2.92 1.39C13.87 11.74 12.97 12.17 12 12.17m9-4.424c0-.604-.802-.986-2.405-1.748L16.5 5M6 13.152l2 .983" /><path d="M12.004 2v7m0 0c.263.004.522-.18.714-.405L14 7.062M12.004 9c-.254-.003-.511-.186-.714-.405L10 7.062" /></g></svg>`;
                    const donationIconSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M4 21h9.62a4 4 0 0 0 3.037-1.397l5.102-5.952a1 1 0 0 0-.442-1.6l-1.968-.656a3.04 3.04 0 0 0-2.823.503l-3.185 2.547l-.617-1.235A3.98 3.98 0 0 0 9.146 11H4c-1.103 0-2 .897-2 2v6c0 1.103.897 2 2 2m0-8h5.146c.763 0 1.448.423 1.789 1.105l.447.895H7v2h6.014a1 1 0 0 0 .442-.11l.003-.001l.004-.002h.003l.002-.001h.004l.001-.001c.009.003.003-.001.003-.001c.01 0 .002-.001.002-.001h.001l.002-.001l.003-.001l.002-.001l.002-.001l.003-.001l.002-.001c.003 0 .001-.001.002-.001l.003-.002l.002-.001l.002-.001l.003-.001l.002-.001h.001l.002-.001h.001l.002-.001l.002-.001c.009-.001.003-.001.003-.001l.002-.001a1 1 0 0 0 .11-.078l4.146-3.317c.262-.208.623-.273.94-.167l.557.186l-4.133 4.823a2.03 2.03 0 0 1-1.52.688H4zM16 2h-.017c-.163.002-1.006.039-1.983.705c-.951-.648-1.774-.7-1.968-.704L12.002 2h-.004c-.801 0-1.555.313-2.119.878C9.313 3.445 9 4.198 9 5s.313 1.555.861 2.104l3.414 3.586a1.006 1.006 0 0 0 1.45-.001l3.396-3.568C18.688 6.555 19 5.802 19 5s-.313-1.555-.878-2.121A2.98 2.98 0 0 0 16.002 2zm1 3c0 .267-.104.518-.311.725L14 8.55l-2.707-2.843C11.104 5.518 11 5.267 11 5s.104-.518.294-.708A.98.98 0 0 1 11.979 4c.025.001.502.032 1.067.485q.121.098.247.222l.707.707l.707-.707q.126-.124.247-.222c.529-.425.976-.478 1.052-.484a1 1 0 0 1 .701.292c.189.189.293.44.293.707" stroke-width="0.5" stroke="currentColor" /></svg>`;
                    const redeemIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 48 48"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"><path d="M3 27.564S9 25 14 25c3.527 0 7.4 1.418 9.994 2.587c1.99.896 3.135 1.462 3.59 3.597c.317 1.483-.748 3.955-2.26 4.077l-1.065.086m0 0c-2.442 0-6.222-.724-6.222-.724m6.222.724c3.387 0 12.303-1.609 15.463-2.2c.754-.14 1.535-.243 2.252.027c.902.341 2.112 1.164 2.69 3.15c.452 1.552-.633 3.045-2.14 3.628C38.104 41.662 28.887 45 24.259 45C12 45 3 41.923 3 41.923M36 9c0-2-1.02-6-4.588-6c-4.077 0-4.265 5-1.808 6M36 9c0-2 1.02-6 4.588-6c4.077 0 4.265 5 1.807 6" /><path d="M44.88 19.905c-.11 1.617-1.354 2.814-2.971 2.922A89 89 0 0 1 36 23a89 89 0 0 1-5.909-.173c-1.617-.108-2.86-1.305-2.971-2.922c-.068-.99-.12-2.28-.12-3.905s.052-2.915.12-3.905c.11-1.617 1.354-2.814 2.971-2.922A89 89 0 0 1 36 9c2.581 0 4.528.081 5.909.173c1.617.108 2.86 1.305 2.971 2.922c.068.99.12 2.28.12 3.905s-.052 2.915-.12 3.905M36 9.045v14" /></g></svg>`
                    const inviteIconSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M5 5h2v2H5zM1 1h10v10H1zm2 2v6h6V3zm2 14h2v2H5zm-4-4h10v10H1zm2 2v6h6v-6zm10-2h4v2h2v-2h4v2h-4v2h4v6h-4v-2h-4v2h-2v-2h2v-2h-2zm8 8v-2h-2v2zm-2-4h-2v-2h-2v4h4zM17 2v3h-3v2h3v3h2V7h3V5h-3V2Z" /></svg>`;

                    // 1. Fetch Profile Data (สำหรับข้อมูลส่วนตัว, แต้ม และรายการล่าสุด)
                    const profileRes = await fetch(`/api/members/profile/${memberId}`);
                    const profileData = await profileRes.json();
                    console.log(profileData);

                    if (profileData.success && profileData.data) {
                        this.profile = profileData.data;
                        const transactions = this.profile.waste_transactions || [];

                        transactions.forEach(tx => {
                            this.totals.weight += parseFloat(tx.waste_transaction_total_weight || 0);
                            this.totals.carbon += parseFloat(tx.waste_transaction_total_co2e || 0);
                            this.totals.credit += parseFloat(tx.waste_transaction_total_point || 0);
                        });

                        let recentItems = [...transactions];
                        if (this.profile.donations) {
                            recentItems = [...recentItems, ...this.profile.donations];
                        }
                        if (this.profile.member_items) {
                            recentItems = [...recentItems, ...this.profile.member_items];
                        }
                        if (this.profile.member_invites) {
                            recentItems = [...recentItems, ...this.profile.member_invites];
                        }
                        recentItems.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

                        this.recentTransactions = recentItems.slice(0, 3).map(item => {
                            const date = new Date(item.created_at);
                            const dateStr = `${date.getDate().toString().padStart(2, '0')}/${(date.getMonth() + 1).toString().padStart(2, '0')}/${date.getFullYear() + 543} ${date.getHours().toString().padStart(2, '0')}:${date.getMinutes().toString().padStart(2, '0')} น.`;

                            if (item.waste_transaction_id) {
                                return {
                                    icon: depositIconSvg,
                                    bgColor: 'bg-[#E8F5EE] text-[#1B8B4B]',
                                    title: 'ฝากขยะ', dateStr,
                                    pointText: `+${Number(item.waste_transaction_total_point || 0).toLocaleString()} แต้ม`,
                                    pointColor: 'text-[#1B8B4B]'
                                };
                            } else if (item.donation_id) {
                                return {
                                    icon: donationIconSvg,
                                    bgColor: 'bg-[#FEF3C7] text-[#D97706]',
                                    title: 'บริจาคสิ่งของ', dateStr,
                                    pointText: `+${Number(item.donation_total_goodness_point || 0).toLocaleString()} แต้มความดี`,
                                    pointColor: 'text-[#1B8B4B]'
                                };
                            } else if (item.member_item_id) {
                                return {
                                    icon: redeemIcon,
                                    bgColor: 'bg-[#FEF3C7] text-[#D97706]',
                                    title: item.donation_item_name ? `แลกของรางวัล (${item.donation_item_name})` : 'แลกของรางวัล', dateStr,
                                    pointText: `-${Number(item.member_item_point_used || 0).toLocaleString()} แต้ม`,
                                    pointColor: 'text-amber-500'
                                };
                            } else if (item.invite_record_id) {
                                return {
                                    icon: inviteIconSvg,
                                    bgColor: 'bg-sky-100 text-sky-600',
                                    title: `ชวนเพื่อน: ${item.invitee_name}`, dateStr,
                                    pointText: `+1 แต้มสังคม`,
                                    pointColor: 'text-sky-600'
                                };
                            }
                            return {};
                        });
                    }

                    // 2. Fetch Stats Data (สำหรับวาดกราฟสัดส่วนหมวดหมู่ขยะ)
                    const statsRes = await fetch(`/api/statistics/member/${memberId}`);
                    const statsData = await statsRes.json();

                    if (statsData.success && statsData.data) {
                        const breakdown = statsData.data.category_breakdown || [];
                        const colors = ['#1B8B4B', '#0EA5E9', '#F59E0B', '#8B5CF6', '#EC4899'];
                        const totalWeight = breakdown.reduce((sum, item) => sum + parseFloat(item.category_total_weight || 0), 0);

                        this.wasteBreakdown = breakdown.map((item, index) => {
                            const weight = parseFloat(item.category_total_weight || 0);
                            return {
                                name: item.waste_category_name || 'ไม่ระบุ',
                                weight: weight,
                                percentage: totalWeight > 0 ? (weight / totalWeight) * 100 : 0,
                                color: colors[index % colors.length]
                            };
                        });
                    }
                } catch (error) {
                    console.error('Failed to load dashboard data:', error);
                } finally {
                    this.isLoading = false;
                }
            }
        }));
    });
</script>