<!-- Top Bar -->
<div class="grid gap-0" x-data="dashboardData()">
    <div
        class="bg-white px-4 pt-[14px] pb-3 flex items-center justify-between sticky top-0 z-[100] border-b border-[#F0F1F3]">
        <a href="/user/profile" class="flex items-center gap-[10px] no-underline">
            <div class="w-[38px] h-[38px] rounded-full bg-gradient-to-br from-[#1B8B4B] to-[#0D6B38] text-white text-[16px] font-[800] grid place-items-center shrink-0"
                x-text="profile?.member_name ? profile.member_name.charAt(0) : ''">
            </div>
            <div>
                <div class="text-[11px] text-[#9CA3AF]">สวัสดี,</div>
                <div class="text-[14px] font-[800] text-[#1A1A2E] mt-[1px]"
                    x-text="profile?.member_name || 'กำลังโหลด...'"></div>
            </div>
        </a>
        <div class="flex items-center gap-[10px]">
            <a href="/logout"
                class="w-[36px] h-[36px] rounded-full bg-[#F4F5F7] grid place-items-center text-[17px] no-underline border-none cursor-pointer"
                aria-label="ออกจากระบบ" title="ออกจากระบบ">
                <svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M5 21q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h7v2H5v14h7v2zm11-4l-1.375-1.45l2.55-2.55H9v-2h8.175l-2.55-2.55L16 7l5 5z"
                        stroke-width="0.5" stroke="currentColor" />
                </svg>
            </a>
        </div>
    </div>

    <!-- Account Card -->
    <div
        class="bg-gradient-to-br from-[#1B8B4B] via-[#0D6B38] to-[#0A5A2F] mx-[14px] mt-[14px] rounded-[18px] p-[20px_20px_16px] text-white relative overflow-hidden shadow-[0_6px_24px_rgba(27,139,75,0.35)]">
        <div class="absolute w-[180px] h-[180px] rounded-full -top-[60px] -right-[50px] bg-white/10"></div>
        <div class="absolute w-[100px] h-[100px] rounded-full -bottom-[30px] left-[30px] bg-white/5"></div>

        <div class="relative z-10">
            <div class="flex items-center justify-between mb-[6px]">
                <div class="text-[11px] opacity-80 tracking-[0.5px] uppercase">แต้มขยะสะสม</div>
            </div>
            <div class="text-[38px] font-[900] leading-[1.1] mt-1 mb-0.5 tracking-[-1px]"
                x-text="Number(profile?.member_waste_point || 0).toLocaleString()">...</div>
            <div class="text-[14px] opacity-85 font-[600]">แต้ม</div>
            <div class="flex items-center justify-between mt-4 pt-[14px] border-t border-white/20">
                <div class="text-center">
                    <div class="text-[17px] font-[800]"
                        x-text="Number(profile?.member_goodness_point || 0).toLocaleString()">...</div>
                    <div class="text-[10px] opacity-75 mt-[2px]">แต้มความดี</div>
                </div>
                <div class="w-[1px] h-[34px] bg-white/20"></div>
                <div class="text-center">
                    <div class="text-[17px] font-[800]"
                        x-text="totals.weight.toLocaleString(undefined, {minimumFractionDigits: 1, maximumFractionDigits: 1})">
                        ...</div>
                    <div class="text-[10px] opacity-75 mt-[2px]">ขยะรวม (กก.)</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <!-- <div
        class="bg-white mx-[14px] mt-[14px] rounded-[16px] p-4 shadow-[0_1px_6px_rgba(0,0,0,0.07)] grid grid-cols-4 gap-1">
        <a class="flex flex-col items-center gap-[7px] no-underline p-[6px_4px] rounded-[12px] transition-colors active:bg-[#F4F5F7]"
            href="/user/shop">
            <div class="w-[46px] h-[46px] rounded-[14px] grid place-items-center text-[21px] bg-[#E8F5EE]">🛍️</div>
            <div class="text-[11px] font-[700] text-[#374151] text-center">แลกรางวัล</div>
        </a>
        <a class="flex flex-col items-center gap-[7px] no-underline p-[6px_4px] rounded-[12px] transition-colors active:bg-[#F4F5F7]"
            href="#">
            <div class="w-[46px] h-[46px] rounded-[14px] grid place-items-center text-[21px] bg-[#E0F2FE]">♻️</div>
            <div class="text-[11px] font-[700] text-[#374151] text-center">ฝากขยะ</div>
        </a>
        <a class="flex flex-col items-center gap-[7px] no-underline p-[6px_4px] rounded-[12px] transition-colors active:bg-[#F4F5F7]"
            href="/user/quests">
            <div class="w-[46px] h-[46px] rounded-[14px] grid place-items-center text-[21px] bg-[#FEF3C7]">✅</div>
            <div class="text-[11px] font-[700] text-[#374151] text-center">ภารกิจ</div>
        </a>
        <a class="flex flex-col items-center gap-[7px] no-underline p-[6px_4px] rounded-[12px] transition-colors active:bg-[#F4F5F7]"
            href="/user/collection">
            <div class="w-[46px] h-[46px] rounded-[14px] grid place-items-center text-[21px] bg-[#F3E8FF]">🏅</div>
            <div class="text-[11px] font-[700] text-[#374151] text-center">รางวัล</div>
        </a>
    </div> -->

    <!-- Stats -->
    <div class="bg-white mx-[14px] mt-[14px] rounded-[16px] overflow-hidden shadow-[0_1px_6px_rgba(0,0,0,0.07)]">
        <div class="flex items-center justify-between px-[16px] pt-[14px] pb-[10px]">
            <div class="text-[14px] font-[800] text-[#1A1A2E]">สถิติของฉัน</div>
        </div>
        <div class="grid grid-cols-3 border-t border-[#F0F1F3]">
            <div class="p-[12px_8px] text-center border-r border-[#F0F1F3] last:border-r-0">
                <div class="flex justify-center items-center text-emerald-600"><svg xmlns="http://www.w3.org/2000/svg"
                        width="32" height="32" viewBox="0 0 16 16">
                        <path fill="none" stroke="currentColor"
                            d="M9 12.5s2.678.322 4.208-1.208S14.5 6.5 14.5 6.5s-3.11-.447-4.5 1c-.435.453-1 1-1 2.5zm0 0s-3.5.5-5.912-1.912S1.5 3.5 1.5 3.5s3.652-.348 6.059 2.059C8.526 6.526 9 8 9 9.706zm2.5-2.5L9 12M5 8l3.782 3.972"
                            stroke-width="1.2" />
                    </svg></div>
                <div class="text-[17px] font-[900] text-[#1A1A2E] mt-[3px]"
                    x-text="totals.carbon.toLocaleString(undefined, {minimumFractionDigits: 1, maximumFractionDigits: 1})">
                </div>
                <div class="text-[10px] text-[#9CA3AF] mt-[2px]">คาร์บอน (กก.)</div>
            </div>
            <div class="p-[12px_8px] text-center border-r border-[#F0F1F3] last:border-r-0">
                <div class="flex justify-center items-center text-sky-600"><svg xmlns="http://www.w3.org/2000/svg"
                        width="32" height="32" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M5.998 2c0 .513.49 1 1 1h10c.513 0 1-.49 1-1h2a3 3 0 0 1-3 3h-4l.001 2.062A8 8 0 0 1 19.998 15v6a1 1 0 0 1-1 1h-14a1 1 0 0 1-1-1v-6a8 8 0 0 1 7-7.938V5h-4c-1.66 0-3-1.34-3-3zm6 7c-3.238 0-6 2.76-6 6v5h12v-5c0-3.238-2.762-6-6-6m0 2c.742 0 1.437.202 2.032.554l-2.74 2.739a1 1 0 0 0 1.32 1.497l.095-.083l2.74-2.739A4 4 0 1 1 11.998 11"
                            stroke-width="0.2" stroke="currentColor" />
                    </svg></div>
                <div class="text-[17px] font-[900] text-[#1A1A2E] mt-[3px]"
                    x-text="totals.weight.toLocaleString(undefined, {minimumFractionDigits: 1, maximumFractionDigits: 1})">
                    ...</div>
                <div class="text-[10px] text-[#9CA3AF] mt-[2px]">ขยะฝาก (กก.)</div>
            </div>
            <div class="p-[12px_8px] text-center border-r border-[#F0F1F3] last:border-r-0">
                <div class="flex justify-center items-center text-amber-500"><svg xmlns="http://www.w3.org/2000/svg"
                        width="30" height="30" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M20.172 6.75h-1.861l-4.566 4.564a1.874 1.874 0 1 1-1.06-1.06l4.565-4.565V3.828a.94.94 0 0 1 .275-.664l1.73-1.73a.25.25 0 0 1 .25-.063c.089.026.155.1.173.191l.46 2.301l2.3.46c.09.018.164.084.19.173a.25.25 0 0 1-.062.249l-1.731 1.73a.94.94 0 0 1-.663.275"
                            stroke-width="0.5" stroke="currentColor" />
                        <path fill="currentColor"
                            d="M2.625 12A9.375 9.375 0 0 0 12 21.375A9.375 9.375 0 0 0 21.375 12c0-.898-.126-1.766-.361-2.587A.75.75 0 0 1 22.455 9c.274.954.42 1.96.42 3c0 6.006-4.869 10.875-10.875 10.875S1.125 18.006 1.125 12S5.994 1.125 12 1.125c1.015-.001 2.024.14 3 .419a.75.75 0 1 1-.413 1.442A9.4 9.4 0 0 0 12 2.625A9.375 9.375 0 0 0 2.625 12"
                            stroke-width="0.5" stroke="currentColor" />
                        <path fill="currentColor"
                            d="M7.125 12a4.874 4.874 0 1 0 9.717-.569a.748.748 0 0 1 1.047-.798c.251.112.42.351.442.625a6.373 6.373 0 0 1-10.836 5.253a6.376 6.376 0 0 1 5.236-10.844a.75.75 0 1 1-.17 1.49A4.876 4.876 0 0 0 7.125 12"
                            stroke-width="0.5" stroke="currentColor" />
                    </svg></div>
                <div class="text-[17px] font-[900] text-[#1A1A2E] mt-[3px]"
                    x-text="'Lv.' + (Math.floor(totals.weight / 50) + 1)">...</div>
                <div class="text-[10px] text-[#9CA3AF] mt-[2px]">ระดับ</div>
            </div>
        </div>
    </div>

    <!-- Waste Breakdown -->
    <div class="bg-white mx-[14px] mt-[14px] rounded-[16px] overflow-hidden shadow-[0_1px_6px_rgba(0,0,0,0.07)]">
        <div class="flex items-center justify-between px-[16px] pt-[14px] pb-[10px]">
            <div class="text-[14px] font-[800] text-[#1A1A2E]">สัดส่วนขยะที่ฝาก</div>
            <a href="#" class="text-[12px] text-[#1B8B4B] font-[700] no-underline">ดูทั้งหมด ›</a>
        </div>
        <div class="px-[16px] pb-[14px]">
            <template x-if="isLoading">
                <div class="text-center py-4 text-[#9CA3AF] text-[13px]">กำลังโหลดข้อมูล...</div>
            </template>
            <template x-if="!isLoading && wasteBreakdown.length === 0">
                <div class="text-center py-4 text-[#9CA3AF] text-[13px]">ยังไม่มีข้อมูลสัดส่วนขยะ</div>
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
    <div class="bg-white mx-[14px] mt-[14px] mb-0 rounded-[16px] overflow-hidden shadow-[0_1px_6px_rgba(0,0,0,0.07)]">
        <div class="flex items-center justify-between px-[16px] pt-[14px] pb-[10px]">
            <div class="text-[14px] font-[800] text-[#1A1A2E]">รายการล่าสุด</div>
            <a href="#" class="text-[12px] text-[#1B8B4B] font-[700] no-underline">ดูทั้งหมด ›</a>
        </div>
        <div class="pb-2">
            <template x-if="isLoading">
                <div class="text-center py-4 text-[#9CA3AF] text-[13px]">กำลังโหลดข้อมูล...</div>
            </template>
            <template x-if="!isLoading && recentTransactions.length === 0">
                <div class="text-center py-4 text-[#9CA3AF] text-[13px]">ยังไม่มีรายการล่าสุด</div>
            </template>
            <template x-for="(tx, index) in recentTransactions" :key="index">
                <div class="flex items-center gap-[12px] py-[13px] px-[16px] border-b border-[#F0F1F3] last:border-b-0">
                    <div class="w-[40px] h-[40px] rounded-[12px] grid place-items-center shrink-0" :class="tx.bgColor"
                        x-html="tx.icon"></div>
                    <div class="flex-1 min-w-0">
                        <div class="text-[13px] font-[700] text-[#1A1A2E]" x-text="tx.title"></div>
                        <div class="text-[11px] text-[#9CA3AF] mt-[2px]" x-text="tx.dateStr"></div>
                    </div>
                    <div class="text-[14px] font-[800]" :class="tx.pointColor" x-text="tx.pointText"></div>
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

            async init() {
                try {
                    const memberId = <?php echo $user->member_id; ?>

                    if (!memberId) {
                        window.location.href = '/login';
                        return;
                    }

                    const depositIconSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.5"><path stroke-linejoin="round" d="M12 22c-.818 0-1.6-.325-3.163-.974C4.946 19.41 3 18.602 3 17.243V7.745M12 22c.818 0 1.6-.325 3.163-.974C19.054 19.41 21 18.602 21 17.243V7.745M12 22v-9.831M3 7.745c0 .603.802.985 2.405 1.747l2.92 1.39C10.13 11.74 11.03 12.17 12 12.17M3 7.745c0-.604.802-.986 2.405-1.748L7.5 5M21 7.745c0 .603-.802.985-2.405 1.747l-2.92 1.39C13.87 11.74 12.97 12.17 12 12.17m9-4.424c0-.604-.802-.986-2.405-1.748L16.5 5M6 13.152l2 .983" /><path d="M12.004 2v7m0 0c.263.004.522-.18.714-.405L14 7.062M12.004 9c-.254-.003-.511-.186-.714-.405L10 7.062" /></g></svg>`;
                    const donationIconSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M4 21h9.62a4 4 0 0 0 3.037-1.397l5.102-5.952a1 1 0 0 0-.442-1.6l-1.968-.656a3.04 3.04 0 0 0-2.823.503l-3.185 2.547l-.617-1.235A3.98 3.98 0 0 0 9.146 11H4c-1.103 0-2 .897-2 2v6c0 1.103.897 2 2 2m0-8h5.146c.763 0 1.448.423 1.789 1.105l.447.895H7v2h6.014a1 1 0 0 0 .442-.11l.003-.001l.004-.002h.003l.002-.001h.004l.001-.001c.009.003.003-.001.003-.001c.01 0 .002-.001.002-.001h.001l.002-.001l.003-.001l.002-.001l.002-.001l.003-.001l.002-.001c.003 0 .001-.001.002-.001l.003-.002l.002-.001l.002-.001l.003-.001l.002-.001h.001l.002-.001h.001l.002-.001l.002-.001c.009-.001.003-.001.003-.001l.002-.001a1 1 0 0 0 .11-.078l4.146-3.317c.262-.208.623-.273.94-.167l.557.186l-4.133 4.823a2.03 2.03 0 0 1-1.52.688H4zM16 2h-.017c-.163.002-1.006.039-1.983.705c-.951-.648-1.774-.7-1.968-.704L12.002 2h-.004c-.801 0-1.555.313-2.119.878C9.313 3.445 9 4.198 9 5s.313 1.555.861 2.104l3.414 3.586a1.006 1.006 0 0 0 1.45-.001l3.396-3.568C18.688 6.555 19 5.802 19 5s-.313-1.555-.878-2.121A2.98 2.98 0 0 0 16.002 2zm1 3c0 .267-.104.518-.311.725L14 8.55l-2.707-2.843C11.104 5.518 11 5.267 11 5s.104-.518.294-.708A.98.98 0 0 1 11.979 4c.025.001.502.032 1.067.485q.121.098.247.222l.707.707l.707-.707q.126-.124.247-.222c.529-.425.976-.478 1.052-.484a1 1 0 0 1 .701.292c.189.189.293.44.293.707" stroke-width="0.5" stroke="currentColor" /></svg>`;
                    const redeemIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 48 48"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"><path d="M3 27.564S9 25 14 25c3.527 0 7.4 1.418 9.994 2.587c1.99.896 3.135 1.462 3.59 3.597c.317 1.483-.748 3.955-2.26 4.077l-1.065.086m0 0c-2.442 0-6.222-.724-6.222-.724m6.222.724c3.387 0 12.303-1.609 15.463-2.2c.754-.14 1.535-.243 2.252.027c.902.341 2.112 1.164 2.69 3.15c.452 1.552-.633 3.045-2.14 3.628C38.104 41.662 28.887 45 24.259 45C12 45 3 41.923 3 41.923M36 9c0-2-1.02-6-4.588-6c-4.077 0-4.265 5-1.808 6M36 9c0-2 1.02-6 4.588-6c4.077 0 4.265 5 1.807 6" /><path d="M44.88 19.905c-.11 1.617-1.354 2.814-2.971 2.922A89 89 0 0 1 36 23a89 89 0 0 1-5.909-.173c-1.617-.108-2.86-1.305-2.971-2.922c-.068-.99-.12-2.28-.12-3.905s.052-2.915.12-3.905c.11-1.617 1.354-2.814 2.971-2.922A89 89 0 0 1 36 9c2.581 0 4.528.081 5.909.173c1.617.108 2.86 1.305 2.971 2.922c.068.99.12 2.28.12 3.905s-.052 2.915-.12 3.905M36 9.045v14" /></g></svg>`

                    // 1. Fetch Profile Data (สำหรับข้อมูลส่วนตัว, แต้ม และรายการล่าสุด)
                    const profileRes = await fetch(`/api/members/profile/${memberId}`);
                    const profileData = await profileRes.json();

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