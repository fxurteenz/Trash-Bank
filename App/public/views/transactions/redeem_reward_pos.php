<div x-data="RedeemRewardPOSHandler()" x-init="init()" class="flex flex-col h-[calc(100vh-6rem)] gap-4">

    <div class="flex-none flex flex-col md:flex-row gap-4">
        <div class="md:w-1/3 flex flex-col justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 mb-2 flex items-center gap-3">
                    <span class="text-4xl">🎁</span>
                    <span>ระบบแลกรางวัล</span>
                </h1>
                <p class="text-slate-600 text-sm">ให้สมาชิกแลกแต้มเป็นของรางวัล</p>
            </div>

        </div>

        <div class="md:w-2/3 bg-white rounded-xl shadow-md card-hover relative flex flex-col justify-center transition-all duration-300"
            x-bind:class="{ 'p-6': !currentMember, 'p-0': currentMember}">

            <div x-show="!currentMember" class="w-full">
                <h2 class="text-xl font-bold text-slate-900 mb-2">ค้นหาสมาชิก</h2>
                <div class="relative" @click.away="showDropdown = false">
                    <div class="flex gap-2">
                        <input x-ref="memberInput" x-model="memberSearch" @input.debounce.300ms="searchMember()"
                            @focus="showDropdown = true" @keydown.enter.prevent="handleEnterKey()"
                            @keydown.escape="showDropdown = false" @keydown.arrow-down.prevent="moveSelection(1)"
                            @keydown.arrow-up.prevent="moveSelection(-1)" type="text"
                            placeholder="กรอกเบอร์โทร หรือ ชื่อสมาชิก..."
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-rose-500 focus:ring-2 focus:ring-rose-200 transition text-lg"
                            autocomplete="off">
                    </div>

                    <div x-show="showDropdown && (searchResults.length > 0 || isSearching)"
                        class="absolute top-full left-0 z-50 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-xl max-h-80 overflow-y-auto">
                        <div x-show="isSearching" class="p-4 text-center text-slate-500">
                            <span class="inline-block animate-spin mr-2">⏳</span> กำลังค้นหา...
                        </div>

                        <ul x-show="!isSearching && searchResults.length > 0">
                            <template x-for="(member, index) in searchResults" :key="member.member_id">
                                <li @click="selectMember(member)" :id="'member-item-' + index"
                                    :class="{ 'bg-rose-100 ring-1 ring-inset ring-rose-300': index === selectedIndex, 'hover:bg-rose-50': index !== selectedIndex }"
                                    class="px-4 py-3 cursor-pointer border-b border-slate-100 last:border-0 transition-colors group">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="font-bold text-slate-800 group-hover:text-rose-700"
                                                x-text="member.member_name || 'ไม่ระบุชื่อ'"></p>
                                            <p class="text-xs text-slate-500">
                                                <span x-text="member.faculty_name"></span>
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <span
                                                class="text-xs font-mono bg-slate-100 px-2 py-1 rounded text-slate-600"
                                                x-text="member.member_phone"></span>
                                            <div class="text-xs text-rose-600 mt-1 font-semibold">แต้ม: <span
                                                    x-text="member.member_waste_point"></span></div>
                                        </div>
                                    </div>
                                </li>
                            </template>
                        </ul>
                        <div x-show="!isSearching && searchResults.length === 0 && memberSearch.length > 0"
                            class="p-4 text-center text-slate-500">❌ ไม่พบข้อมูล</div>
                    </div>
                </div>
            </div>

            <div x-show="currentMember" class="w-full h-full">
                <div
                    class="relative bg-gradient-to-br from-rose-50 to-rose-100 border-2 border-rose-300 rounded-lg p-4 h-full flex flex-col justify-center">

                    <button @click="resetMember()"
                        class="absolute top-2 right-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-full transition-colors p-1 z-10">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="m12 13.4l2.9 2.9q.275.275.7.275t.7-.275t.275-.7t-.275-.7L13.4 12l2.9-2.9q.275-.275.275-.7t-.275-.7t-.7-.275t-.7.275L12 10.6L9.1 7.7q-.275-.275-.7-.275t-.7.275t-.275.7t.275.7l2.9 2.9l-2.9 2.9q-.275.275-.275.7t.275.7t.7.275t.7-.275zm0 8.6q-2.075 0-3.9-.788t-3.175-2.137T2.788 15.9T2 12t.788-3.9t2.137-3.175T8.1 2.788T12 2t3.9.788t3.175 2.137T21.213 8.1T22 12t-.788 3.9t-2.137 3.175t-3.175 2.138T12 22m0-2q3.35 0 5.675-2.325T20 12t-2.325-5.675T12 4T6.325 6.325T4 12t2.325 5.675T12 20m0-8"
                                stroke-width="0.5" stroke="currentColor" />
                        </svg>
                    </button>

                    <div class="flex items-end justify-between pr-6">
                        <div>
                            <p class="text-xs font-bold text-rose-700 uppercase tracking-wider mb-1">สมาชิกผู้แลก</p>
                            <h2 class="text-2xl font-bold text-slate-900 mb-1" x-text="currentMember?.member_name"></h2>
                            <div class="flex gap-3 text-sm text-slate-600">
                                <span><span x-text="currentMember?.member_phone"></span></span>
                                <span class="text-slate-300">|</span>
                                <span x-text="currentMember?.faculty_name"></span>
                            </div>
                        </div>
                        <div class="text-center bg-white/60 p-2 px-3 rounded-lg shadow-sm border border-rose-100">
                            <p class="text-[10px] text-slate-500 mb-0">แต้มคงเหลือ</p>
                            <span class="text-2xl font-bold text-rose-600"
                                x-text="currentMember?.member_waste_point || 0"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex-1 min-h-0 grid grid-cols-1 lg:grid-cols-3 gap-6 pb-1">

        <div class="lg:col-span-2 flex flex-col gap-4 h-full">
            <div class="flex-1 min-h-0 bg-white rounded-xl shadow-md p-6 card-hover flex flex-col">
                <div class="flex items-center justify-between mb-4 shrink-0">
                    <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                        <span>🛍️ เลือกของรางวัล</span>
                    </h2>
                    <input type="text" x-model="rewardSearch" @input.debounce.300ms="filterRewards()"
                        placeholder="🔍 ค้นหาของรางวัล..."
                        class="px-4 py-2 border-2 border-slate-200 rounded-lg focus:border-rose-500 focus:ring-2 focus:ring-rose-200 transition text-sm w-64">
                </div>

                <div class="flex-1 min-h-0 overflow-y-auto custom-scrollbar pr-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <template x-for="reward in filteredRewards" :key="reward.reward_id">
                            <div @click="selectReward(reward)"
                                :class="selectedReward?.reward_id === reward.reward_id ? 'ring-2 ring-rose-500 bg-rose-50' : 'hover:shadow-md border-slate-200 bg-white'"
                                class="border-2 rounded-xl p-3 cursor-pointer transition-all flex items-start gap-3 h-24">
                                <div
                                    class="flex-shrink-0 w-16 h-16 bg-slate-100 rounded-lg overflow-hidden border border-slate-100">
                                    <img :src="reward.reward_image ? `/assets/images/rewards/${reward.reward_image}` : '/assets/images/rewards/default.png'"
                                        :alt="reward.reward_name" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0 flex flex-col justify-between h-full">
                                    <div>
                                        <h3 class="font-bold text-slate-800 truncate text-sm"
                                            x-text="reward.reward_name"></h3>
                                        <p class="text-xs text-slate-500 line-clamp-1"
                                            x-text="reward.reward_description || '-'"></p>
                                    </div>
                                    <div class="flex items-end justify-between">
                                        <div class="bg-rose-100 text-rose-700 text-xs font-bold px-2 py-1 rounded">
                                            <span x-text="reward.reward_point_required"></span> แต้ม
                                        </div>
                                        <span class="text-[10px] text-slate-400">คงเหลือ: <span
                                                x-text="reward.reward_stock"></span></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div x-show="filteredRewards.length === 0"
                        class="h-full flex flex-col items-center justify-center text-slate-400 opacity-60">
                        <span class="text-4xl mb-2">📦</span>
                        <p class="text-sm">ไม่พบของรางวัล</p>
                    </div>
                </div>
            </div>


        </div>

        <div class="h-full rounded-xl shadow-md relative">
            <div x-show="!selectedReward"
                class="absolute inset-0 flex flex-col items-center justify-center text-slate-400 z-10 bg-slate-50 rounded-xl border-2 border-dashed border-slate-300 m-6">
                <span class="text-4xl mb-3">👈</span>
                <p class="text-sm">เลือกของรางวัลจากรายการด้านซ้าย</p>
            </div>

            <div
                class="h-full bg-gradient-to-br from-rose-500 to-rose-600 rounded-xl p-6 text-white flex flex-col justify-between overflow-y-auto custom-scrollbar">

                <div>
                    <h3 class="text-xl font-bold mb-4 flex items-center gap-2 border-b border-rose-400 pb-2">
                        📋 สรุปรายการ
                    </h3>

                    <div class="space-y-4" x-show="selectedReward">
                        <div>
                            <span class="text-rose-100 text-sm">ของรางวัล</span>
                            <p class="text-xl font-bold truncate" x-text="selectedReward?.reward_name"></p>
                        </div>

                        <div>
                            <span class="text-rose-100 text-sm">จำนวนที่แลก</span>
                            <div class="flex items-center gap-3 mt-1">
                                <button @click="decreaseQty()"
                                    class="w-8 h-8 rounded-lg bg-white/20 hover:bg-white/30 text-white font-bold flex items-center justify-center transition">-</button>
                                <span class="text-2xl font-bold w-12 text-center" x-text="redeemQty"></span>
                                <button @click="increaseQty()"
                                    class="w-8 h-8 rounded-lg bg-white/20 hover:bg-white/30 text-white font-bold flex items-center justify-center transition">+</button>
                            </div>
                        </div>

                        <div class="h-px bg-rose-400 opacity-50 my-2"></div>

                        <div class="flex justify-between items-center">
                            <span class="text-rose-100">ใช้แต้มรวม</span>
                            <span class="text-2xl font-bold" x-text="totalPoints()"></span>
                        </div>

                        <div class="bg-black/20 rounded-xl p-4 mt-2 backdrop-blur-sm">
                            <div class="flex justify-between items-center text-sm mb-1 text-rose-200">
                                <span>แต้มคงเหลือเดิม</span>
                                <span x-text="currentMember?.member_waste_point || 0"></span>
                            </div>
                            <div class="flex justify-between items-center font-bold text-white">
                                <span>คงเหลือหลังแลก</span>
                                <span x-text="remainingPoints()"
                                    :class="remainingPoints() < 0 ? 'text-red-300' : 'text-emerald-300'"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 space-y-3">
                    <button @click="saveRedemption()" :disabled="!canSave() || isSubmitting"
                        :class="!canSave() || isSubmitting ? 'bg-rose-800/50 cursor-not-allowed text-rose-200' : 'bg-white hover:bg-rose-50 text-rose-700 shadow-lg transform hover:-translate-y-0.5'"
                        class="w-full px-6 py-4 rounded-xl font-bold text-xl transition-all duration-200 flex items-center justify-center gap-2">
                        <span x-show="!isSubmitting">ยืนยันการแลก</span>
                        <span x-show="isSubmitting" class="flex items-center gap-2">⏳ กำลังบันทึก...</span>
                    </button>

                    <button @click="resetForm()"
                        class="w-full px-6 py-3 bg-red-500/20 hover:bg-red-500/30 text-white rounded-xl font-semibold transition-colors border border-white/10">
                        เริ่มใหม่
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function RedeemRewardPOSHandler() {
        return {
            // --- Member State ---
            currentMember: null,
            memberSearch: '',
            searchResults: [],
            isSearching: false,
            showDropdown: false,
            selectedIndex: -1,

            // --- Reward State ---
            rewards: [],
            filteredRewards: [],
            rewardSearch: '',
            selectedReward: null,
            redeemQty: 1,
            isSubmitting: false,

            // --- Computed Helper ---
            totalPoints() {
                if (!this.selectedReward) return 0;
                return this.selectedReward.reward_point_required * this.redeemQty;
            },

            remainingPoints() {
                if (!this.currentMember) return 0;
                return this.currentMember.member_waste_point - this.totalPoints();
            },

            // --- Init ---
            async init() {
                await this.loadRewards();
                this.$nextTick(() => { if (this.$refs.memberInput) this.$refs.memberInput.focus(); });
            },

            // --- Load Data ---
            async loadRewards() {
                try {
                    const response = await fetch('/api/rewards?limit=1000');
                    const result = await response.json();
                    if (result.success) {
                        this.rewards = result.data.filter(r => r.reward_stock > 0);
                        this.filteredRewards = [...this.rewards];
                    }
                } catch (error) {
                    console.error('Error loading rewards:', error);
                }
            },

            filterRewards() {
                if (!this.rewardSearch) {
                    this.filteredRewards = [...this.rewards];
                } else {
                    const search = this.rewardSearch.toLowerCase();
                    this.filteredRewards = this.rewards.filter(r =>
                        r.reward_name.toLowerCase().includes(search) ||
                        (r.reward_description && r.reward_description.toLowerCase().includes(search))
                    );
                }
            },

            // --- Member Logic ---
            async searchMember() {
                if (this.memberSearch.length < 2) {
                    this.searchResults = [];
                    return;
                }
                this.isSearching = true;
                this.showDropdown = true;
                try {
                    const response = await fetch(`/api/members?search=${encodeURIComponent(this.memberSearch)}&limit=10`);
                    const result = await response.json();
                    if (result.success) {
                        this.searchResults = result.data;
                        this.selectedIndex = -1;
                    } else {
                        this.searchResults = [];
                    }
                } catch (error) {
                    console.error('Error searching member:', error);
                    this.searchResults = [];
                } finally {
                    this.isSearching = false;
                }
            },

            selectMember(member) {
                this.currentMember = member;
                this.showDropdown = false;
                this.memberSearch = '';
                this.searchResults = [];
                this.selectedReward = null;
                this.redeemQty = 1;
            },

            resetMember() {
                this.currentMember = null;
                this.memberSearch = '';
                this.searchResults = [];
                this.selectedReward = null;
                this.redeemQty = 1;
                this.$nextTick(() => { if (this.$refs.memberInput) this.$refs.memberInput.focus(); });
            },

            moveSelection(direction) {
                if (!this.showDropdown || this.searchResults.length === 0) return;
                this.selectedIndex = (this.selectedIndex + direction + this.searchResults.length) % this.searchResults.length;
                this.$nextTick(() => {
                    const el = document.getElementById('member-item-' + this.selectedIndex);
                    if (el) el.scrollIntoView({ block: 'nearest' });
                });
            },

            handleEnterKey() {
                if (this.selectedIndex >= 0 && this.searchResults[this.selectedIndex]) {
                    this.selectMember(this.searchResults[this.selectedIndex]);
                }
            },

            // --- Reward Selection Logic ---
            selectReward(reward) {
                if (!this.currentMember) {
                    Swal.fire({ icon: 'warning', title: 'กรุณาเลือกสมาชิกก่อน', timer: 1500, showConfirmButton: false });
                    this.$refs.memberInput.focus();
                    return;
                }
                this.selectedReward = reward;
                this.redeemQty = 1;
            },

            increaseQty() {
                if (this.redeemQty < this.maxQty()) {
                    this.redeemQty++;
                }
            },

            decreaseQty() {
                if (this.redeemQty > 1) {
                    this.redeemQty--;
                }
            },

            maxQty() {
                if (!this.selectedReward || !this.currentMember) return 1;
                const maxByPoints = Math.floor(this.currentMember.member_waste_point / this.selectedReward.reward_point_required);
                const maxByStock = this.selectedReward.reward_stock;
                return Math.max(1, Math.min(maxByPoints, maxByStock)); // Ensure at least 1 if valid, but logic usually handles 0 elsewhere
            },

            // --- Transaction ---
            canSave() {
                return this.currentMember &&
                    this.selectedReward &&
                    this.redeemQty > 0 &&
                    this.remainingPoints() >= 0 &&
                    this.redeemQty <= this.selectedReward.reward_stock &&
                    !this.isSubmitting;
            },

            async saveRedemption() {
                if (!this.canSave()) return;

                const confirmMsg = `ยืนยันแลก: ${this.selectedReward.reward_name} x${this.redeemQty}`;
                const result = await Swal.fire({
                    title: 'ยืนยันการแลกรางวัล?',
                    text: confirmMsg,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'ยืนยัน',
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonColor: '#e11d48' // rose-600
                });

                if (!result.isConfirmed) return;

                this.isSubmitting = true;

                try {
                    const data = {
                        member_id: this.currentMember.member_id,
                        reward_id: this.selectedReward.reward_id,
                        member_reward_qty: this.redeemQty,
                        member_reward_point_used: this.totalPoints(),
                        member_reward_status: 'pending' // Or 'completed' based on your logic
                    };

                    const response = await fetch('/api/member_rewards', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(data)
                    });

                    const resData = await response.json();

                    if (resData.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'แลกรางวัลสำเร็จ!',
                            text: 'บันทึกรายการเรียบร้อยแล้ว',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        this.resetForm();
                    } else {
                        throw new Error(resData.message || 'Unknown error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire('เกิดข้อผิดพลาด', error.message || 'ไม่สามารถบันทึกได้', 'error');
                } finally {
                    this.isSubmitting = false;
                }
            },

            resetForm() {
                this.currentMember = null;
                this.memberSearch = '';
                this.searchResults = [];
                this.selectedReward = null;
                this.redeemQty = 1;
                this.rewardSearch = '';
                this.filteredRewards = [...this.rewards];
                this.loadRewards(); // Reload stock
                this.$nextTick(() => { if (this.$refs.memberInput) this.$refs.memberInput.focus(); });
            },

        };
    }
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(156, 163, 175, 0.5);
        border-radius: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(107, 114, 128, 0.8);
    }

    [x-cloak] {
        display: none !important;
    }
</style>