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
            selectedItems: [],
            isSubmitting: false,
            directItemForm: { code: '', qty: 1 },
            selectedDirectItem: null,

            // --- Computed Helpers ---
            totalPoints() {
                return this.selectedItems.reduce((sum, item) => sum + (item.donation_item_redeem_point * item.qty), 0);
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

                const urlParams = new URLSearchParams(window.location.search);
                const memberId = urlParams.get('member_id');
                if (memberId) {
                    await this.fetchMemberById(memberId);
                } else {
                    this.$nextTick(() => { if (this.$refs.memberInput) this.$refs.memberInput.focus(); });
                }
            },

            // --- Load Data ---
            async loadItems() {
                try {
                    const response = await fetch('/api/donations/items/available');
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

            async fetchMemberById(id) {
                try {
                    const response = await fetch(`/api/members/profile/${id}`);
                    const result = await response.json();
                    if (result.success && result.data) {
                        this.selectMember(result.data);
                    } else {
                        Swal.fire({ icon: 'error', title: 'ไม่พบข้อมูลสมาชิกจาก URL', timer: 1500, showConfirmButton: false });
                        this.$nextTick(() => { if (this.$refs.memberInput) this.$refs.memberInput.focus(); });
                    }
                } catch (error) {
                    console.error('Error fetching member:', error);
                    this.$nextTick(() => { if (this.$refs.memberInput) this.$refs.memberInput.focus(); });
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
                this.selectedItems = [];
            },

            resetMember() {
                this.currentMember = null;
                this.memberSearch = '';
                this.searchResults = [];
                this.selectedItems = [];
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

                const existing = this.selectedItems.find(i => i.donation_item_id === item.donation_item_id);
                if (existing) {
                    if (existing.qty < item.donation_item_amount) {
                        existing.qty++;
                    } else {
                        Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: 'สต็อกของรางวัลชิ้นนี้ไม่เพียงพอ', showConfirmButton: false, timer: 1500 });
                    }
                } else {
                    if (item.donation_item_amount > 0) {
                        this.selectedItems.push({
                            ...item,
                            qty: 1
                        });
                    }
                }
            },

            increaseQty(index) {
                if (this.selectedItems[index].qty < this.selectedItems[index].donation_item_amount) {
                    this.selectedItems[index].qty++;
                }
            },

            decreaseQty(index) {
                if (this.selectedItems[index].qty > 1) {
                    this.selectedItems[index].qty--;
                }
            },

            removeItem(index) {
                this.selectedItems.splice(index, 1);
            },

            removeItemById(id) {
                const index = this.selectedItems.findIndex(i => i.donation_item_id === id);
                if (index !== -1) {
                    this.selectedItems.splice(index, 1);
                }
            },

            searchDirectItem() {
                const search = this.directItemForm.code.toLowerCase();
                const exactMatch = this.items.find(type =>
                    type.donation_item_id.toString().padStart(3, '0') === search ||
                    type.donation_item_id.toString() === search
                );
                this.selectedDirectItem = exactMatch || null;
            },

            handleItemCodeEnter() {
                if (!this.selectedDirectItem && this.directItemForm.code) {
                    const search = this.directItemForm.code.toLowerCase().trim();
                    const match = this.items.find(type =>
                        type.donation_item_id.toString().padStart(3, '0').includes(search) ||
                        type.donation_item_id.toString().includes(search) ||
                        type.donation_item_name.toLowerCase().includes(search)
                    );
                    if (match) {
                        this.selectedDirectItem = match;
                        this.directItemForm.code = match.donation_item_id.toString().padStart(3, '0');
                    }
                }

                if (this.selectedDirectItem) {
                    this.$refs.qtyInput.focus();
                } else {
                    Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: 'ไม่พบของบริจาคที่ระบุ', showConfirmButton: false, timer: 1500 });
                }
            },

            addDirectItem() {
                if (!this.currentMember) {
                    Swal.fire({ icon: 'warning', title: 'กรุณาเลือกสมาชิกก่อน', timer: 1500, showConfirmButton: false });
                    this.$refs.memberInput.focus();
                    return;
                }

                if (!this.selectedDirectItem && this.directItemForm.code) {
                    const search = this.directItemForm.code.toLowerCase().trim();
                    const match = this.items.find(type =>
                        type.donation_item_id.toString().padStart(3, '0').includes(search) ||
                        type.donation_item_id.toString().includes(search) ||
                        type.donation_item_name.toLowerCase().includes(search)
                    );
                    if (match) {
                        this.selectedDirectItem = match;
                        this.directItemForm.code = match.donation_item_id.toString().padStart(3, '0');
                    }
                }

                if (!this.directItemForm.code || !this.selectedDirectItem) {
                    Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'กรุณาเลือกรหัสของบริจาค', showConfirmButton: false, timer: 1500 });
                    this.$refs.itemCodeInput.focus();
                    return;
                }

                const qtyToAdd = parseInt(this.directItemForm.qty);
                if (!qtyToAdd || isNaN(qtyToAdd) || qtyToAdd <= 0) {
                    Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'กรุณากรอกจำนวนให้ถูกต้อง', showConfirmButton: false, timer: 1500 });
                    this.$refs.qtyInput.focus();
                    return;
                }

                const existing = this.selectedItems.find(i => i.donation_item_id === this.selectedDirectItem.donation_item_id);
                if (existing) {
                    if (existing.qty + qtyToAdd <= this.selectedDirectItem.donation_item_amount) {
                        existing.qty += qtyToAdd;
                        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'เพิ่มจำนวนแล้ว', showConfirmButton: false, timer: 1500 });
                    } else {
                        Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: 'สต็อกของรางวัลชิ้นนี้ไม่เพียงพอ', showConfirmButton: false, timer: 1500 });
                    }
                } else {
                    if (qtyToAdd <= this.selectedDirectItem.donation_item_amount) {
                        this.selectedItems.push({
                            ...this.selectedDirectItem,
                            qty: qtyToAdd
                        });
                        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'เพิ่มรายการใหม่แล้ว', showConfirmButton: false, timer: 1500 });
                    } else {
                        Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: 'สต็อกของรางวัลชิ้นนี้ไม่เพียงพอ', showConfirmButton: false, timer: 1500 });
                    }
                }

                this.clearDirectItemForm();
                this.$refs.itemCodeInput.focus();
            },

            clearDirectItemForm() {
                this.directItemForm.code = '';
                this.directItemForm.qty = 1;
                this.selectedDirectItem = null;
            },

            // --- Transaction ---
            canSave() {
                return this.currentMember &&
                    this.selectedItems.length > 0 &&
                    this.hasEnoughPoints() &&
                    !this.isSubmitting;
            },

            async saveRedemption() {
                if (!this.canSave()) return;

                const totalQty = this.selectedItems.reduce((sum, item) => sum + item.qty, 0);
                const confirmMsg = `ยืนยันการแลกของรางวัล ${this.selectedItems.length} รายการ (รวม ${totalQty} ชิ้น) ใช่หรือไม่?`;
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
                        items: this.selectedItems.map(item => ({
                            donation_item_id: item.donation_item_id,
                            member_item_qty: item.qty
                        }))
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
                this.selectedItems = [];
                this.itemSearch = '';
                this.filteredItems = [...this.items];
                this.clearDirectItemForm();
                this.loadItems();
                this.$nextTick(() => { if (this.$refs.memberInput) this.$refs.memberInput.focus(); });
            },

        };
    }
</script>

<div x-data="RedeemItemPOSHandler()" x-init="init()" class="flex flex-col h-[calc(100vh-6rem)] gap-4">

    <div class="flex-none flex flex-col md:flex-row gap-4">
        <div class="md:w-1/3 flex flex-col justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 mb-2 flex items-center gap-3">
                    <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M11.3 8.3L9.2 6.2q-.3-.3-.3-.7t.3-.7l2.1-2.1q.3-.3.7-.3t.7.3l2.1 2.1q.3.3.3.7t-.3.7l-2.1 2.1q-.3.3-.7.3t-.7-.3M2 20q-.425 0-.712-.288T1 19v-3q0-.85.588-1.425T3 14h3.275q.5 0 .95.25t.725.675q.725.975 1.788 1.525T12 17q1.225 0 2.288-.55t1.762-1.525q.325-.425.763-.675t.912-.25H21q.85 0 1.425.575T23 16v3q0 .425-.288.713T22 20h-5q-.425 0-.712-.288T16 19v-1.275q-.875.625-1.888.95T12 19q-1.075 0-2.1-.337T8 17.7V19q0 .425-.288.713T7 20zm2-7q-1.25 0-2.125-.875T1 10q0-1.275.875-2.137T4 7q1.275 0 2.138.863T7 10q0 1.25-.862 2.125T4 13m16 0q-1.25 0-2.125-.875T17 10q0-1.275.875-2.137T20 7q1.275 0 2.138.863T23 10q0 1.25-.862 2.125T20 13"
                            stroke-width="0.5" stroke="currentColor" />
                    </svg>
                    <span>ระบบแลกของ</span>
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
                            <p class="text-3xl font-bold text-slate-800"
                                x-text="currentMember?.member_waste_point || 0"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex-1 min-h-0 grid grid-cols-1 lg:grid-cols-3 gap-6 pb-1">

        <div class="lg:col-span-2 flex flex-col gap-4 h-full overflow-hidden">

            <div x-show="currentMember" style="display: none;"
                class="flex-none bg-white rounded-xl shadow-md p-6 card-hover z-10">
                <h2 class="text-xl font-bold text-slate-900 mb-3">ระบุของบริจาค</h2>
                <div class="grid grid-cols-12 gap-3">
                    <div class="col-span-6">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">รหัส/ชื่อของบริจาค</label>
                        <input x-ref="itemCodeInput" x-model="directItemForm.code"
                            @keydown.tab.prevent="handleItemCodeEnter()" @keydown.enter="handleItemCodeEnter()"
                            @input="searchDirectItem()" type="text" placeholder="พิมพ์รหัส/ชื่อ"
                            class="w-full px-4 py-2 border-2 border-slate-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition"
                            list="itemTypeList" autocomplete="off">
                        <datalist id="itemTypeList">
                            <template x-for="type in items" :key="type.donation_item_id">
                                <option :value="type.donation_item_id.toString().padStart(3, '0')"
                                    :label="`${type.donation_item_name} (คงเหลือ: ${type.donation_item_amount} ชิ้น)`">
                                </option>
                            </template>
                        </datalist>
                        <p class="text-[10px] text-purple-600 mt-1 font-medium truncate"
                            x-text="selectedDirectItem ? `${selectedDirectItem?.donation_item_name} (คงเหลือ: ${selectedDirectItem?.donation_item_amount} ชิ้น)`:  'ระบุรหัสของบริจาคเพื่อตรวจสอบสต็อก'">
                        </p>
                    </div>
                    <div class="col-span-3">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">จำนวน (ชิ้น)</label>
                        <input x-ref="qtyInput" x-model.number="directItemForm.qty" @keydown.enter="addDirectItem()"
                            @keydown.tab.prevent="addDirectItem(); $refs.itemCodeInput.focus()" type="number" step="1"
                            min="1" placeholder="1"
                            class="w-full px-4 py-2 border-2 border-slate-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
                    </div>
                    <div class="col-span-3 flex items-center">
                        <button @click="addDirectItem()"
                            class="w-full px-4 py-2 mb-[2px] bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-semibold transition-colors h-[42px]">
                            เพิ่ม
                        </button>
                    </div>
                </div>
            </div>

            <div x-show="currentMember" style="display: none;"
                class="flex-1 min-h-0 bg-white rounded-xl shadow-md p-6 card-hover flex flex-col">
                <div class="flex items-center justify-between mb-4 shrink-0">
                    <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="1.5"
                                d="m12 12l8.073-4.625M12 12v9.25M12 12L7.963 9.688m12.11-2.313a3.17 3.17 0 0 0-1.165-1.156L16.25 4.696m3.823 2.679c.275.472.427 1.015.427 1.58v6.09a3.15 3.15 0 0 1-1.592 2.736l-5.316 3.046A3.2 3.2 0 0 1 12 21.25M3.926 7.375a3.14 3.14 0 0 0-.426 1.58v6.09c0 1.13.607 2.172 1.592 2.736l5.316 3.046A3.2 3.2 0 0 0 12 21.25M3.926 7.375a3.17 3.17 0 0 1 1.166-1.156l5.316-3.046a3.2 3.2 0 0 1 3.184 0l2.658 1.523M3.926 7.375l4.037 2.313m0 0l8.287-4.992" />
                        </svg>
                        <span>เลือกของบริจาค</span>
                    </h2>
                    <input type="text" x-model="itemSearch" @input.debounce.300ms="filterItems()"
                        placeholder=" ค้นหาของบริจาค..."
                        class="px-4 py-2 border-2 border-slate-200 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition text-sm w-64">
                </div>

                <div class="flex-1 min-h-0 overflow-y-auto custom-scrollbar pr-2">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <template x-for="item in filteredItems" :key="item.donation_item_id">
                            <div :class="selectedItems.find(i => i.donation_item_id === item.donation_item_id) ? 'ring-2 ring-purple-500 bg-purple-50' : 'hover:shadow-md border-slate-200 bg-white'"
                                class="border-2 rounded-xl overflow-hidden transition-all flex flex-col h-full">
                                <div class="w-full h-32 bg-slate-100 border-b border-slate-100 shrink-0">
                                    <template x-if="item.donation_item_image">
                                        <img :src="'/assets/images/donation_items/' + item.donation_item_image"
                                            class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!item.donation_item_image">
                                        <img src="/assets/images/donation_items/placeholder.png"
                                            class="w-full h-full object-cover opacity-50">
                                    </template>
                                </div>
                                <div class="p-3 flex flex-col flex-1 gap-2">
                                    <div>
                                        <h3 class="font-bold text-slate-800 line-clamp-2 text-sm leading-tight"
                                            x-text="item.donation_item_name"></h3>
                                        <p class="text-xs text-slate-500 mt-1">
                                            คงเหลือ: <span x-text="item.donation_item_amount"></span> ชิ้น
                                        </p>
                                    </div>
                                    <div
                                        class="flex items-center justify-between mt-auto pt-2 border-t border-slate-100">
                                        <div class="bg-purple-100 text-purple-700 text-xs font-bold px-2 py-1 rounded">
                                            <span x-text="item.donation_item_redeem_point"></span> แต้ม
                                        </div>
                                        <button
                                            x-show="!selectedItems.find(i => i.donation_item_id === item.donation_item_id)"
                                            @click.stop="selectItem(item)"
                                            class="px-3 py-1 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded shadow-sm transition-colors">
                                            เพิ่ม
                                        </button>
                                        <button
                                            x-show="selectedItems.find(i => i.donation_item_id === item.donation_item_id)"
                                            @click.stop="removeItemById(item.donation_item_id)" x-cloak
                                            class="px-3 py-1 bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold rounded shadow-sm transition-colors">
                                            นำออก
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div x-show="filteredItems.length === 0"
                        class="h-full flex flex-col items-center justify-center text-slate-400 opacity-60">
                        <span class="text-4xl mb-2"><svg class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="1.5"
                                    d="m12 12l8.073-4.625M12 12v9.25M12 12L7.963 9.688m12.11-2.313a3.17 3.17 0 0 0-1.165-1.156L16.25 4.696m3.823 2.679c.275.472.427 1.015.427 1.58v6.09a3.15 3.15 0 0 1-1.592 2.736l-5.316 3.046A3.2 3.2 0 0 1 12 21.25M3.926 7.375a3.14 3.14 0 0 0-.426 1.58v6.09c0 1.13.607 2.172 1.592 2.736l5.316 3.046A3.2 3.2 0 0 0 12 21.25M3.926 7.375a3.17 3.17 0 0 1 1.166-1.156l5.316-3.046a3.2 3.2 0 0 1 3.184 0l2.658 1.523M3.926 7.375l4.037 2.313m0 0l8.287-4.992" />
                            </svg></span>
                        <p class="text-sm">ไม่พบรายการ</p>
                    </div>
                </div>
            </div>

            <div x-show="!currentMember"
                class="flex-1 min-h-0 flex flex-col items-center justify-center bg-slate-50 rounded-xl border-2 border-dashed border-slate-300 text-slate-400">
                <span class="text-5xl mb-3"><svg class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" viewBox="0 0 24 24">
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2">
                            <path d="M21 12a9 9 0 1 0-9 9M9 10h.01M15 10h.01" />
                            <path d="M9.5 15c.658.672 1.56 1 2.5 1m3 2a3 3 0 1 0 6 0a3 3 0 1 0-6 0m5.2 2.2L22 22" />
                        </g>
                    </svg>
                </span>
                <p class="text-lg">กรุณาเลือกสมาชิกก่อนทำรายการ</p>
            </div>
        </div>

        <div class="h-full rounded-xl shadow-md relative">

            <div
                class="h-full bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white flex flex-col justify-between overflow-y-auto custom-scrollbar">

                <div>
                    <h3 class="text-xl font-bold mb-4 flex items-center gap-2 border-b border-amber-400 pb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M19 3h-4.18C14.25 1.44 12.53.64 11 1.2c-.86.3-1.5.96-1.82 1.8H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2m-7 0a1 1 0 0 1 1 1a1 1 0 0 1-1 1a1 1 0 0 1-1-1a1 1 0 0 1 1-1M7 7h10V5h2v14H5V5h2zm10 4H7V9h10zm-2 4H7v-2h8z"
                                stroke-width="0.5" stroke="currentColor" />
                        </svg>
                        สรุปรายการ
                    </h3>

                    <div class="space-y-4" x-show="selectedItems.length > 0">
                        <div
                            class="bg-black/20 rounded-xl p-4 mt-2 backdrop-blur-sm max-h-60 overflow-y-auto custom-scrollbar">
                            <ul class="space-y-3 text-sm">
                                <template x-for="(item, index) in selectedItems" :key="index">
                                    <li
                                        class="flex flex-col gap-2 border-b border-white/10 pb-3 last:border-0 last:pb-0">
                                        <div class="flex justify-between items-start">
                                            <span class="text-white font-semibold pr-2 line-clamp-2"
                                                x-text="item.donation_item_name"></span>
                                            <button @click="removeItem(index)"
                                                class="text-rose-300 hover:text-rose-100 transition-colors shrink-0">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center gap-2">
                                                <button @click="decreaseQty(index)"
                                                    class="w-7 h-7 rounded bg-white/20 hover:bg-white/30 text-white font-bold flex items-center justify-center transition">-</button>
                                                <span class="text-white font-bold w-8 text-center"
                                                    x-text="item.qty"></span>
                                                <button @click="increaseQty(index)"
                                                    class="w-7 h-7 rounded bg-white/20 hover:bg-white/30 text-white font-bold flex items-center justify-center transition">+</button>
                                            </div>
                                            <span class="text-purple-200 font-bold"
                                                x-text="(item.donation_item_redeem_point * item.qty) + ' แต้ม'"></span>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </div>

                        <div class="h-px bg-purple-400 opacity-50 my-2"></div>

                        <div class="bg-black/20 rounded-xl p-4 mt-2 backdrop-blur-sm space-y-2"
                            x-show="selectedItems.length > 0">
                            <div class="flex justify-between items-center text-sm text-purple-200">
                                <span>แต้มที่ต้องใช้รวม</span>
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
                        <div x-show="!hasEnoughPoints()"
                            class="text-yellow-300 bg-black/30 text-center text-xs p-2 mt-2 rounded-lg">
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