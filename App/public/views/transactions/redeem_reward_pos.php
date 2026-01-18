<div x-data="RedeemRewardPOSHandler()" x-init="init()" class="space-y-6 relative">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 mb-2">🎁 ระบบแลกรางวัล</h1>
        <p class="text-slate-600 text-lg">ให้สมาชิกแลกแต้มเป็นของรางวัล</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- ส่วนกรอกสมาชิก -->
            <div class="bg-white rounded-xl shadow-md p-6 card-hover relative z-20">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-slate-900">
                        <span x-show="!currentMember">🔍 ค้นหาสมาชิก</span>
                        <span x-show="currentMember" class="text-emerald-600">✅ ยืนยันสมาชิก</span>
                    </h2>
                    <button x-show="currentMember" @click="resetMember()"
                        class="px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                        🔄 เปลี่ยน
                    </button>
                </div>

                <!-- dropdown เลือกสมาชิก -->
                <div x-show="!currentMember" class="space-y-3 relative" @click.away="showDropdown = false">
                    <label class="block text-sm font-semibold text-slate-700">เบอร์โทร หรือ ชื่อสมาชิก</label>

                    <div class="relative">
                        <input x-ref="memberInput" x-model="memberSearch"
                            @input.debounce.300ms="searchMember()" @focus="showDropdown = true"
                            @keydown.enter.prevent="handleEnterKey()" @keydown.escape="showDropdown = false"
                            @keydown.arrow-down.prevent="moveSelection(1)"
                            @keydown.arrow-up.prevent="moveSelection(-1)" type="text"
                            placeholder="กรอกเบอร์โทร หรือ ชื่อสมาชิก"
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
                            autocomplete="off">

                        <div x-show="showDropdown && (searchResults.length > 0 || isSearching)"
                            x-transition.opacity.duration.200ms
                            class="absolute top-full left-0 z-50 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-xl max-h-80 overflow-y-auto">
                            <div x-show="isSearching" class="p-4 text-center text-slate-500">
                                <span class="inline-block animate-spin mr-2">⏳</span> กำลังค้นหา...
                            </div>

                            <ul x-show="!isSearching && searchResults.length > 0">
                                <template x-for="(member, index) in searchResults" :key="member.member_id">
                                    <li @click="selectMember(member)" :id="'member-item-' + index"
                                        :class="{ 'bg-emerald-100 ring-1 ring-inset ring-emerald-300': index === selectedIndex, 'hover:bg-emerald-50': index !== selectedIndex }"
                                        class="px-4 py-3 cursor-pointer border-b border-slate-100 last:border-0 transition-colors group">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="font-bold text-slate-800 group-hover:text-emerald-700"
                                                    x-text="member.member_name || 'ไม่ระบุชื่อ'"></p>
                                                <p class="text-xs text-slate-500">
                                                    <span x-text="member.faculty_name"></span>
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-xs font-mono bg-slate-100 px-2 py-1 rounded text-slate-600"
                                                    x-text="member.member_phone"></span>
                                                <div class="text-xs text-emerald-600 mt-1 font-semibold">แต้ม: <span
                                                        x-text="member.member_waste_point"></span></div>
                                            </div>
                                        </div>
                                    </li>
                                </template>
                            </ul>

                            <div x-show="!isSearching && searchResults.length === 0 && memberSearch.length > 0"
                                class="p-4 text-center text-slate-500">
                                ❌ ไม่พบข้อมูลสมาชิก
                            </div>
                        </div>
                    </div>

                    <p class="text-xs text-slate-500">
                        💡 ใช้ปุ่ม <kbd class="bg-slate-100 px-2 py-1 rounded">⬇️</kbd><kbd
                            class="bg-slate-100 px-2 py-1 rounded">⬆️</kbd> เพื่อเลือก และ<kbd
                            class="bg-slate-100 px-2 py-1 rounded">Enter</kbd> ยืนยัน
                    </p>
                </div>

                <!-- แสดงข้อมูลสมาชิก -->
                <div x-show="currentMember"
                    class="bg-gradient-to-br from-emerald-50 to-emerald-100 border-2 border-emerald-300 rounded-lg p-5">
                    <div class="space-y-2">
                        <p class="text-xs font-semibold text-emerald-700 uppercase tracking-wider">สมาชิก</p>
                        <p class="text-2xl font-bold text-slate-900"
                            x-text="currentMember?.member_name || 'ไม่ระบุชื่อ'"></p>
                        <div class="flex gap-4 text-sm text-slate-600">
                            <span>เบอร์โทร : <span class="font-semibold" x-text="currentMember?.member_phone"></span></span>
                            <span>คณะ : <span class="font-semibold" x-text="currentMember?.faculty_name"></span></span>
                        </div>
                        <div class="mt-3 p-3 bg-white rounded-lg">
                            <p class="text-sm text-slate-600">แต้มคงเหลือ</p>
                            <p class="text-3xl font-bold text-emerald-600" x-text="currentMember?.member_waste_point || 0"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ส่วนเลือกของรางวัล -->
            <div x-show="currentMember" class="bg-white rounded-xl shadow-md p-6 card-hover">
                <h2 class="text-2xl font-bold text-slate-900 mb-4">🎁 เลือกของรางวัล</h2>

                <div class="mb-4">
                    <input type="text" x-model="rewardSearch" @input.debounce.300ms="filterRewards()"
                        placeholder="ค้นหาของรางวัล..."
                        class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-96 overflow-y-auto">
                    <template x-for="reward in filteredRewards" :key="reward.reward_id">
                        <div @click="selectReward(reward)"
                            :class="selectedReward?.reward_id === reward.reward_id ? 'ring-2 ring-emerald-500 bg-emerald-50' : 'hover:shadow-lg'"
                            class="border-2 border-slate-200 rounded-lg p-4 cursor-pointer transition-all">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 w-16 h-16 bg-slate-200 rounded-lg overflow-hidden">
                                    <img :src="reward.reward_image ? `/assets/images/rewards/${reward.reward_image}` : '/assets/images/rewards/default.png'"
                                        :alt="reward.reward_name"
                                        class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-bold text-slate-900 truncate" x-text="reward.reward_name"></h3>
                                    <p class="text-sm text-slate-600 line-clamp-2" x-text="reward.reward_description || 'ไม่มีรายละเอียด'"></p>
                                    <div class="mt-2 flex items-center justify-between">
                                        <span class="text-sm font-bold text-emerald-600" x-text="`${reward.reward_point_required} แต้ม`"></span>
                                        <span class="text-xs text-slate-500" x-text="`คงเหลือ: ${reward.reward_stock}`"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div x-show="filteredRewards.length === 0" class="col-span-2 text-center py-8 text-slate-500">
                        ไม่พบของรางวัล
                    </div>
                </div>

                <!-- จำนวนที่แลก -->
                <div x-show="selectedReward" class="mt-6 p-4 bg-emerald-50 rounded-lg">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">จำนวนที่ต้องการแลก</label>
                    <div class="flex items-center gap-4">
                        <button @click="decreaseQty()"
                            class="px-4 py-2 bg-slate-200 hover:bg-slate-300 rounded-lg font-bold transition">
                            −
                        </button>
                        <input type="number" x-model.number="redeemQty" min="1" :max="maxQty()"
                            class="flex-1 text-center px-4 py-2 border-2 border-slate-300 rounded-lg focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition">
                        <button @click="increaseQty()"
                            class="px-4 py-2 bg-slate-200 hover:bg-slate-300 rounded-lg font-bold transition">
                            +
                        </button>
                    </div>
                    <p class="text-xs text-slate-600 mt-2">
                        ต้องใช้แต้ม: <span class="font-bold text-emerald-600" x-text="totalPoints()"></span> แต้ม
                    </p>
                </div>
            </div>
        </div>

        <!-- สรุปรายการ -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-md p-6 sticky top-6">
                <h2 class="text-2xl font-bold text-slate-900 mb-4">📋 สรุปรายการ</h2>

                <div class="space-y-4">
                    <div class="p-4 bg-emerald-50 rounded-lg">
                        <p class="text-sm text-slate-600 mb-1">สมาชิก</p>
                        <p class="text-lg font-bold text-slate-900" x-text="currentMember ? currentMember.member_name : '-'"></p>
                        <p class="text-sm text-emerald-600 mt-1">แต้มคงเหลือ: <span class="font-bold" x-text="currentMember?.member_waste_point || 0"></span></p>
                    </div>

                    <div class="p-4 bg-slate-50 rounded-lg">
                        <p class="text-sm text-slate-600 mb-1">ของรางวัล</p>
                        <p class="text-sm text-slate-900 font-bold" x-text="selectedReward ? selectedReward.reward_name : '-'"></p>
                        <p class="text-xs text-slate-600 mt-1" x-show="selectedReward">จำนวน: <span x-text="redeemQty"></span> ชิ้น</p>
                    </div>

                    <div class="p-4 bg-red-50 rounded-lg border border-red-200">
                        <p class="text-sm text-slate-600 mb-1">แต้มที่ใช้</p>
                        <p class="text-2xl font-bold text-red-600" x-text="totalPoints()"></p>
                    </div>

                    <div class="p-4 bg-emerald-50 rounded-lg border border-emerald-200">
                        <p class="text-sm text-slate-600 mb-1">แต้มคงเหลือหลังแลก</p>
                        <p class="text-2xl font-bold text-emerald-600" x-text="remainingPoints()"></p>
                    </div>

                    <div class="pt-4 border-t border-slate-200 space-y-3">
                        <button @click="saveRedemption()" :disabled="!canSave()"
                            :class="canSave() ? 'bg-emerald-500 hover:bg-emerald-600' : 'bg-slate-300 cursor-not-allowed'"
                            class="w-full py-3 text-white font-bold rounded-lg transition-colors">
                            💾 ยืนยันการแลกรางวัล
                        </button>

                        <button @click="resetForm()"
                            class="w-full py-3 border-2 border-slate-300 text-slate-700 font-bold rounded-lg hover:bg-slate-50 transition-colors">
                            🔄 เริ่มใหม่
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function RedeemRewardPOSHandler() {
    return {
        currentMember: null,
        memberSearch: '',
        searchResults: [],
        isSearching: false,
        showDropdown: false,
        selectedIndex: -1,
        rewards: [],
        filteredRewards: [],
        rewardSearch: '',
        selectedReward: null,
        redeemQty: 1,

        async init() {
            await this.loadRewards();
        },

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

        async searchMember() {
            if (this.memberSearch.length < 2) {
                this.searchResults = [];
                return;
            }

            this.isSearching = true;
            try {
                const response = await fetch(`/api/members?search=${encodeURIComponent(this.memberSearch)}&limit=10`);
                const result = await response.json();
                if (result.success) {
                    this.searchResults = result.data;
                    this.selectedIndex = -1;
                }
            } catch (error) {
                console.error('Error searching member:', error);
            } finally {
                this.isSearching = false;
            }
        },

        selectMember(member) {
            this.currentMember = member;
            this.showDropdown = false;
            this.memberSearch = '';
        },

        selectReward(reward) {
            this.selectedReward = reward;
            this.redeemQty = 1;
        },

        moveSelection(direction) {
            if (this.searchResults.length === 0) return;

            this.selectedIndex += direction;
            if (this.selectedIndex < 0) this.selectedIndex = this.searchResults.length - 1;
            if (this.selectedIndex >= this.searchResults.length) this.selectedIndex = 0;

            const element = document.getElementById(`member-item-${this.selectedIndex}`);
            if (element) {
                element.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
            }
        },

        handleEnterKey() {
            if (this.selectedIndex >= 0 && this.searchResults[this.selectedIndex]) {
                this.selectMember(this.searchResults[this.selectedIndex]);
            }
        },

        resetMember() {
            this.currentMember = null;
            this.memberSearch = '';
            this.searchResults = [];
            this.selectedReward = null;
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
            return Math.min(maxByPoints, maxByStock);
        },

        totalPoints() {
            if (!this.selectedReward) return 0;
            return this.selectedReward.reward_point_required * this.redeemQty;
        },

        remainingPoints() {
            if (!this.currentMember) return 0;
            return this.currentMember.member_waste_point - this.totalPoints();
        },

        canSave() {
            return this.currentMember &&
                this.selectedReward &&
                this.redeemQty > 0 &&
                this.totalPoints() <= this.currentMember.member_waste_point &&
                this.redeemQty <= this.selectedReward.reward_stock;
        },

        async saveRedemption() {
            if (!this.canSave()) return;

            const confirmMsg = `ยืนยันการแลกรางวัล?\n\nสมาชิก: ${this.currentMember.member_name}\nของรางวัล: ${this.selectedReward.reward_name}\nจำนวน: ${this.redeemQty}\nใช้แต้ม: ${this.totalPoints()}`;

            if (!confirm(confirmMsg)) return;

            try {
                const data = {
                    member_id: this.currentMember.member_id,
                    reward_id: this.selectedReward.reward_id,
                    member_reward_qty: this.redeemQty,
                    member_reward_point_used: this.totalPoints(),
                    member_reward_status: 'pending'
                };

                const response = await fetch('/api/member_rewards', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    alert('✅ บันทึกการแลกรางวัลสำเร็จ!\n\nสมาชิกสามารถมารับของรางวัลได้');
                    this.resetForm();
                } else {
                    alert('❌ เกิดข้อผิดพลาด: ' + result.message);
                }
            } catch (error) {
                console.error('Error saving redemption:', error);
                alert('❌ เกิดข้อผิดพลาดในการบันทึกข้อมูล');
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
            this.loadRewards();
        }
    };
}
</script>
