<div x-data="RedeemItemPOSHandler()" x-init="init()" class="flex flex-col h-[calc(100vh-6rem)] gap-4">

    <div class="flex-none flex flex-col md:flex-row gap-4">
        <div class="md:w-1/3 flex flex-col justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 mb-2 flex items-center gap-3">
                    <span class="text-4xl">🎁</span>
                    <span>ระบบแลกของบริจาค</span>
                </h1>
                <p class="text-slate-600 text-sm">บันทึกการแลกของบริจาคจากสมาชิก</p>
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
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition text-lg"
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
                                    :class="{ 'bg-purple-100 ring-1 ring-inset ring-purple-300': index === selectedIndex, 'hover:bg-purple-50': index !== selectedIndex }"
                                    class="px-4 py-3 cursor-pointer border-b border-slate-100 last:border-0 transition-colors group">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="font-bold text-slate-800 group-hover:text-purple-700"
                                                x-text="member.member_name || 'ไม่ระบุชื่อ'"></p>
                                            <p class="text-xs text-slate-500">
                                                <span x-text="member.faculty_name"></span>
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <span
                                                class="text-xs font-mono bg-slate-100 px-2 py-1 rounded text-slate-600"
                                                x-text="member.member_phone"></span>
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
                    class="relative bg-gradient-to-br from-purple-50 to-purple-100 border-2 border-purple-300 rounded-lg p-4 h-full flex flex-col justify-center">

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
                            <p class="text-xs font-bold text-purple-700 uppercase tracking-wider mb-1">สมาชิก</p>
                            <h2 class="text-2xl font-bold text-slate-900 mb-1" x-text="currentMember?.member_name"></h2>
                            <div class="flex gap-3 text-sm text-slate-600">
                                <span><span x-text="currentMember?.member_phone"></span></span>
                                <span class="text-slate-300">|</span>
                                <span x-text="currentMember?.faculty_name"></span>
                            </div>
                        </div>
                        <div class="text-right">
                             <p class="text-xs font-bold text-purple-700 uppercase tracking-wider mb-1">แต้มขยะ</p>
                            <p class="text-3xl font-bold text-slate-800" x-text="currentMember?.member_waste_point || 0"></p>
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
                        <span>📦 เลือกของบริจาค</span>
                    </h2>
                    <input type="text" x-model="itemSearch" @input.debounce.300ms="filterItems()"
                        placeholder="🔍 ค้นหาของบริจาค..."
                        class="px-4 py-2 border-2 border-slate-200 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition text-sm w-64">
                </div>

                <div class="flex-1 min-h-0 overflow-y-auto custom-scrollbar pr-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <template x-for="item in filteredItems" :key="item.donation_item_id">
                            <div @click="selectItem(item)"
                                :class="selectedItem?.donation_item_id === item.donation_item_id ? 'ring-2 ring-purple-500 bg-purple-50' : 'hover:shadow-md border-slate-200 bg-white'"
                                class="border-2 rounded-xl p-3 cursor-pointer transition-all flex items-start gap-3 h-24">
                                <div
                                    class="flex-shrink-0 w-16 h-16 bg-slate-100 rounded-lg overflow-hidden border border-slate-100">
                                    <img src="/assets/images/rewards/default.png"
                                        :alt="item.donation_item_name" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0 flex flex-col justify-between h-full">
                                    <div>
                                        <h3 class="font-bold text-slate-800 truncate text-sm"
                                            x-text="item.donation_item_name"></h3>
                                        <p class="text-xs text-slate-500">
                                            คงเหลือ: <span x-text="item.donation_item_amount"></span> ชิ้น
                                        </p>
                                    </div>
                                    <div class="flex items-end justify-between">
                                        <div class="bg-purple-100 text-purple-700 text-xs font-bold px-2 py-1 rounded">
                                            <span x-text="item.donation_item_price * 10"></span> แต้ม/ชิ้น
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div x-show="filteredItems.length === 0"
                        class="h-full flex flex-col items-center justify-center text-slate-400 opacity-60">
                        <span class="text-4xl mb-2">📦</span>
                        <p class="text-sm">ไม่พบรายการ</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="h-full rounded-xl shadow-md relative">
            <div x-show="!selectedItem"
                class="absolute inset-0 flex flex-col items-center justify-center text-slate-400 z-10 bg-slate-50 rounded-xl border-2 border-dashed border-slate-300 m-6">
                <span class="text-4xl mb-3">👈</span>
                <p class="text-sm">เลือกของจากรายการด้านซ้าย</p>
            </div>

            <div
                class="h-full bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white flex flex-col justify-between overflow-y-auto custom-scrollbar">

                <div>
                    <h3 class="text-xl font-bold mb-4 flex items-center gap-2 border-b border-purple-400 pb-2">
                        📋 สรุปรายการ
                    </h3>

                    <div class="space-y-4" x-show="selectedItem">
                        <div>
                            <span class="text-purple-100 text-sm">รายการ</span>
                            <p class="text-xl font-bold truncate" x-text="selectedItem?.donation_item_name"></p>
                        </div>

                        <div>
                            <span class="text-purple-100 text-sm">จำนวน</span>
                            <div class="flex items-center gap-3 mt-1">
                                <button @click="decreaseQty()"
                                    class="w-8 h-8 rounded-lg bg-white/20 hover:bg-white/30 text-white font-bold flex items-center justify-center transition">-</button>
                                <span class="text-2xl font-bold w-12 text-center" x-text="redeemQty"></span>
                                <button @click="increaseQty()"
                                    class="w-8 h-8 rounded-lg bg-white/20 hover:bg-white/30 text-white font-bold flex items-center justify-center transition">+</button>
                            </div>
                        </div>

                        <div class="h-px bg-purple-400 opacity-50 my-2"></div>

                        <div class="bg-black/20 rounded-xl p-4 mt-2 backdrop-blur-sm space-y-2">
                            <div class="flex justify-between items-center text-sm text-purple-200">
                                <span>แต้มที่ต้องใช้</span>
                                <span x-text="totalPoints()"></span>
                            </div>
                            <div class="flex justify-between items-center text-sm text-purple-200">
                                <span>แต้มที่มี</span>
                                <span x-text="currentMember?.member_waste_point || 0"></span>
                            </div>
                            <div class="flex justify-between items-center font-bold text-white text-lg">
                                <span>แต้มคงเหลือ</span>
                                <span x-text="remainingPoints()"></span>
                            </div>
                        </div>
                         <div x-show="!hasEnoughPoints()" class="text-yellow-300 bg-black/30 text-center text-xs p-2 mt-2 rounded-lg">
                            ⚠️ แต้มไม่เพียงพอ
                        </div>
                    </div>
                </div>

                <div class="mt-6 space-y-3">
                    <button @click="saveRedemption()" :disabled="!canSave() || isSubmitting"
                        :class="!canSave() || isSubmitting ? 'bg-purple-800/50 cursor-not-allowed text-purple-200' : 'bg-white hover:bg-purple-50 text-purple-700 shadow-lg transform hover:-translate-y-0.5'"
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
    function RedeemItemPOSHandler() {
        return {
            // --- Member State ---
            currentMember: null,
            memberSearch: '',
            searchResults: [],
            isSearching: false,
            showDropdown: false,
            selectedIndex: -1,

            // --- Item State ---
            items: [],
            filteredItems: [],
            itemSearch: '',
            selectedItem: null,
            redeemQty: 1,
            isSubmitting: false,

            // --- Computed Helpers ---
            totalPoints() {
                if (!this.selectedItem) return 0;
                return this.selectedItem.donation_item_price * 10 * this.redeemQty;
            },
            remainingPoints() {
                if (!this.currentMember) return 0;
                return (this.currentMember.member_waste_point || 0) - this.totalPoints();
            },
            hasEnoughPoints() {
                return this.remainingPoints() >= 0;
            },

            // --- Init ---
            async init() {
                await this.loadItems();
                this.$nextTick(() => { if (this.$refs.memberInput) this.$refs.memberInput.focus(); });
            },

            // --- Load Data ---
            async loadItems() {
                try {
                    const response = await fetch('/api/donations/items?limit=1000');
                    const result = await response.json();
                    if (result.success) {
                        this.items = result.data.filter(item => item.donation_item_amount > 0);
                        this.filteredItems = [...this.items];
                    }
                } catch (error) {
                    console.error('Error loading items:', error);
                }
            },

            filterItems() {
                if (!this.itemSearch) {
                    this.filteredItems = [...this.items];
                } else {
                    const search = this.itemSearch.toLowerCase();
                    this.filteredItems = this.items.filter(i =>
                        i.donation_item_name.toLowerCase().includes(search)
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
                        // Assuming the API returns member_waste_point
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
                this.selectedItem = null;
                this.redeemQty = 1;
            },

            resetMember() {
                this.currentMember = null;
                this.memberSearch = '';
                this.searchResults = [];
                this.selectedItem = null;
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

            // --- Item Selection Logic ---
            selectItem(item) {
                if (!this.currentMember) {
                    Swal.fire({ icon: 'warning', title: 'กรุณาเลือกสมาชิกก่อน', timer: 1500, showConfirmButton: false });
                    this.$refs.memberInput.focus();
                    return;
                }
                this.selectedItem = item;
                this.redeemQty = 1;
            },

            increaseQty() {
                if (this.redeemQty < this.selectedItem.donation_item_amount) {
                    this.redeemQty++;
                }
            },

            decreaseQty() {
                if (this.redeemQty > 1) {
                    this.redeemQty--;
                }
            },

            // --- Transaction ---
            canSave() {
                return this.currentMember &&
                    this.selectedItem &&
                    this.redeemQty > 0 &&
                    this.hasEnoughPoints() &&
                    !this.isSubmitting;
            },

            async saveRedemption() {
                if (!this.canSave()) return;

                const confirmMsg = `ยืนยันการแลก: ${this.selectedItem.donation_item_name} x${this.redeemQty}`;
                const result = await Swal.fire({
                    title: 'ยืนยันการแลกของ?',
                    text: confirmMsg,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'ยืนยัน',
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonColor: '#9333ea' // purple-600
                });

                if (!result.isConfirmed) return;

                this.isSubmitting = true;

                try {
                    const data = {
                        member_id: this.currentMember.member_id,
                        donation_item_id: this.selectedItem.donation_item_id,
                        member_item_qty: this.redeemQty,
                    };

                    const response = await fetch('/api/member_items/redeem', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(data)
                    });

                    const resData = await response.json();

                    if (resData.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'แลกของสำเร็จ!',
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
                this.selectedItem = null;
                this.redeemQty = 1;
                this.itemSearch = '';
                this.filteredItems = [...this.items];
                this.loadItems();
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