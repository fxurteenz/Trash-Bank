<div x-data="DonationPOSHandler()" x-init="init()" class="flex flex-col h-[calc(100vh-6rem)] gap-4">

    <div class="flex-none flex flex-col md:flex-row gap-4">
        <div class="md:w-1/3 flex flex-col justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 mb-2 flex items-center gap-3">
                    <span class="text-4xl">🎁</span>
                    <span>ระบบรับของบริจาค</span>
                </h1>
                <p class="text-slate-600 text-sm">บันทึกการรับบริจาค - ใช้เบอร์โทรหรือรหัสประจำตัว</p>
            </div>

            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-4 text-white">
                <div class="flex items-center justify-between">
                    <p class="text-purple-100 text-xs font-medium uppercase tracking-wider mb-1">ยอดบริจาควันนี้</p>
                    <h2 class="text-3xl font-bold flex items-center gap-2">
                        0
                        <span class="text-sm font-normal text-purple-100 mt-2">รายการ</span>
                    </h2>
                </div>
            </div>
        </div>

        <div class="md:w-2/3 bg-white rounded-xl shadow-md card-hover relative flex flex-col justify-center transition-all duration-300"
            x-bind:class="{ 'p-6': !currentDonor, 'p-0': currentDonor}">

            <div x-show="!currentDonor" class="w-full">
                <h2 class="text-xl font-bold text-slate-900 mb-2">ค้นหาผู้บริจาค</h2>
                <div class="relative" @click.away="showDropdown = false">
                    <div class="flex gap-2">
                        <input x-ref="searchInput" x-model="searchQuery" @input.debounce.300ms="searchDonor()"
                            @focus="showDropdown = true" @keydown.enter.prevent="handleEnterKey()"
                            @keydown.escape="showDropdown = false" @keydown.arrow-down.prevent="moveSelection(1)"
                            @keydown.arrow-up.prevent="moveSelection(-1)" type="text"
                            placeholder="ป้อนเบอร์โทรหรือรหัสประจำตัว"
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition text-lg"
                            autocomplete="off">
                    </div>

                    <div x-show="showDropdown && (searchResults.length > 0 || isSearching)"
                        class="absolute top-full left-0 z-50 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-xl max-h-80 overflow-y-auto">

                        <div x-show="isSearching" class="p-4 text-center text-slate-500">
                            <span class="inline-block animate-spin mr-2">⏳</span> กำลังค้นหา...
                        </div>

                        <ul x-ref="resultList" x-show="!isSearching && searchResults.length > 0">
                            <template x-for="(result, index) in searchResults" :key="result.member_id">
                                <li @click="selectDonor(result)"
                                    :class="{ 'bg-purple-100 ring-1 ring-inset ring-purple-300': index === selectedIndex, 'hover:bg-purple-50': index !== selectedIndex }"
                                    class="px-4 py-3 cursor-pointer border-b border-slate-100 last:border-0 transition-colors group">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="font-bold text-slate-800 group-hover:text-purple-700"
                                                x-text="result.member_name"></p>
                                            <p class="text-xs text-slate-500">คณะ: <span
                                                    x-text="result.faculty_name"></span></p>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xs font-mono bg-slate-100 px-2 py-1 rounded text-slate-600"
                                                x-text="result.member_phone"></span>
                                        </div>
                                    </div>
                                </li>
                            </template>
                        </ul>
                        <div x-show="!isSearching && searchResults.length === 0 && searchQuery.length > 0"
                            class="p-4 text-center text-slate-500">❌ ไม่พบข้อมูล</div>
                    </div>
                </div>
            </div>

            <div x-show="currentDonor" class="w-full h-full">
                <div class="relative bg-gradient-to-br from-purple-50 to-purple-100 border-2 border-purple-300 rounded-lg p-4 h-full flex flex-col justify-center">
                    
                    <button @click="resetDonor()"
                        class="absolute top-2 right-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-full transition-colors p-1 z-10">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                            <path fill="currentColor" d="m12 13.4l2.9 2.9q.275.275.7.275t.7-.275t.275-.7t-.275-.7L13.4 12l2.9-2.9q.275-.275.275-.7t-.275-.7t-.7-.275t-.7.275L12 10.6L9.1 7.7q-.275-.275-.7-.275t-.7.275t-.275.7t.275.7l2.9 2.9l-2.9 2.9q-.275.275-.275.7t.275.7t.7.275t.7-.275zm0 8.6q-2.075 0-3.9-.788t-3.175-2.137T2.788 15.9T2 12t.788-3.9t2.137-3.175T8.1 2.788T12 2t3.9.788t3.175 2.137T21.213 8.1T22 12t-.788 3.9t-2.137 3.175t-3.175 2.138T12 22m0-2q3.35 0 5.675-2.325T20 12t-2.325-5.675T12 4T6.325 6.325T4 12t2.325 5.675T12 20m0-8" stroke-width="0.5" stroke="currentColor" />
                        </svg>
                    </button>

                    <div class="flex items-end justify-between pr-6">
                        <div>
                            <p class="text-xs font-bold text-purple-700 uppercase tracking-wider mb-1">ผู้บริจาค</p>
                            <h2 class="text-2xl font-bold text-slate-900 mb-1" x-text="currentDonor?.member_name"></h2>
                            <div class="flex gap-3 text-sm text-slate-600">
                                <span>เบอร์โทร: <span class="font-semibold" x-text="currentDonor?.member_phone"></span></span>
                                <span class="text-slate-300">|</span>
                                <span>คณะ: <span class="font-semibold" x-text="currentDonor?.faculty_name"></span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex-1 min-h-0 grid grid-cols-1 lg:grid-cols-3 gap-6 pb-1">
        
        <div class="lg:col-span-2 flex flex-col gap-4 h-full">
            <div x-show="currentDonor" class="flex-1 min-h-0 bg-white rounded-xl shadow-md p-6 card-hover overflow-y-auto custom-scrollbar">
                <h2 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <span>📝 รายละเอียดของที่รับ</span>
                </h2>

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">ชื่อสิ่งของ * (inventory key)</label>
                        <input type="text" x-model="donation.item_name" placeholder="เช่น ขวดแก้ว, กระดาษลัง, เสื้อยืด"
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">จำนวน *</label>
                            <input type="number" x-model.number="donation.item_qty" min="1" placeholder="ระบุจำนวน"
                                class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">มูลค่าต่อชิ้น/หน่วย (บาท) *</label>
                            <input type="number" x-model.number="donation.item_value" min="0" step="0.01"
                                placeholder="0.00"
                                class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
                            <p class="text-xs text-slate-500 mt-1">ใช้คำนวณมูลค่ารวมในสต็อก</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">รายละเอียดเพิ่มเติม / หมายเหตุ</label>
                        <textarea x-model="donation.description" rows="3" placeholder="สภาพของ, แหล่งที่มา (ถ้ามี)"
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition"></textarea>
                    </div>
                </div>
            </div>

            <div x-show="!currentDonor"
                class="flex-1 min-h-0 flex flex-col items-center justify-center bg-slate-50 rounded-xl border-2 border-dashed border-slate-300 text-slate-400">
                <span class="text-5xl mb-3">🔍</span>
                <p class="text-lg">กรุณาค้นหาและเลือกผู้บริจาคก่อนเริ่มทำรายการ</p>
            </div>
        </div>

        <div class="h-full rounded-xl shadow-md">
            <div class="h-full bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white flex flex-col justify-between overflow-y-auto custom-scrollbar">
                
                <div>
                    <h3 class="text-xl font-bold mb-4 flex items-center gap-2 border-b border-purple-400 pb-2">
                        📋 สรุปรายการ
                    </h3>

                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-purple-100">รายการ</span>
                            <span class="text-lg font-bold truncate max-w-[150px]" x-text="donation.item_name || '-'"></span>
                        </div>
                        <div class="h-px bg-purple-400 opacity-50"></div>

                        <div class="flex justify-between items-center">
                            <span class="text-purple-100">จำนวน</span>
                            <span class="text-xl font-bold" x-text="donation.item_qty ? `${donation.item_qty} หน่วย` : '-'"></span>
                        </div>
                        <div class="h-px bg-purple-400 opacity-50"></div>

                        <div class="flex justify-between items-center">
                            <span class="text-purple-100">ราคา/หน่วย</span>
                            <span class="text-xl font-bold" x-text="donation.item_value ? `฿${parseFloat(donation.item_value).toFixed(2)}` : '-'"></span>
                        </div>

                        <div class="bg-purple-700/50 backdrop-blur-sm rounded-xl p-4 mt-4">
                            <p class="text-purple-100 text-sm mb-1">มูลค่ารวม / แต้มความดี</p>
                            <p class="text-3xl font-bold" x-text="`฿${calculateTotal().toFixed(2)}`"></p>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <button @click="saveDonation()" :disabled="!canSave()"
                        :class="canSave() ? 'bg-white hover:bg-slate-50 text-purple-600' : 'bg-purple-800/50 opacity-50 cursor-not-allowed text-purple-200'"
                        class="w-full px-6 py-4 rounded-xl font-bold text-lg transition-all flex justify-center items-center gap-2 shadow-lg">
                        <span x-show="isSaving" class="animate-spin text-lg">⏳</span>
                        <span>บันทึกรับของเข้าคลัง</span>
                    </button>

                    <button @click="resetForm()"
                        class="w-full px-6 py-3 bg-red-500/20 hover:bg-red-500/30 text-white rounded-xl font-semibold transition-colors border border-white/10">
                        เริ่มใหม่
                    </button>
                    <p class="text-center text-purple-200 text-xs mt-2 opacity-70">ตรวจสอบความถูกต้องก่อนบันทึก</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function DonationPOSHandler() {
        return {
            // State สำหรับค้นหา
            currentDonor: null,
            searchQuery: '',
            searchResults: [],
            isSearching: false,
            showDropdown: false,
            selectedIndex: -1,

            donation: {
                item_name: '',
                item_qty: '',
                item_value: '',
                description: ''
            },

            isSaving: false,

            async init() {
                this.$nextTick(() => {
                    if (this.$refs.searchInput) this.$refs.searchInput.focus();
                });
            },

            // --- Search Logic ---
            async searchDonor() {
                if (!this.searchQuery.trim()) {
                    this.searchResults = [];
                    this.showDropdown = false;
                    return;
                }
                this.isSearching = true;
                this.showDropdown = true;
                this.selectedIndex = -1;

                try {
                    const res = await fetch(`/api/members?search=${encodeURIComponent(this.searchQuery)}&limit=10`);
                    const data = await res.json();
                    this.searchResults = data.success ? data.data : [];
                } catch (err) {
                    console.error(err);
                    this.searchResults = [];
                } finally {
                    this.isSearching = false;
                }
            },

            selectDonor(member) {
                this.currentDonor = member;
                this.showDropdown = false;
                this.searchQuery = '';
                this.searchResults = [];
                this.selectedIndex = -1;
            },

            moveSelection(step) {
                if (!this.showDropdown || this.searchResults.length === 0) return;
                this.selectedIndex = (this.selectedIndex + step + this.searchResults.length) % this.searchResults.length;

                this.$nextTick(() => {
                    const list = this.$refs.resultList;
                    if (list) {
                        const activeItem = list.querySelectorAll('li')[this.selectedIndex];
                        if (activeItem) {
                            activeItem.scrollIntoView({ block: 'nearest' });
                        }
                    }
                });
            },

            handleEnterKey() {
                if (this.selectedIndex >= 0 && this.searchResults[this.selectedIndex]) {
                    this.selectDonor(this.searchResults[this.selectedIndex]);
                }
            },

            resetDonor() {
                this.currentDonor = null;
                this.searchQuery = '';
                this.searchResults = [];
                setTimeout(() => {
                    if (this.$refs.searchInput) this.$refs.searchInput.focus();
                }, 100);
            },

            // --- Donation Logic ---

            calculateTotal() {
                const qty = parseFloat(this.donation.item_qty) || 0;
                const price = parseFloat(this.donation.item_value) || 0;
                return qty * price;
            },

            canSave() {
                return this.currentDonor &&
                    this.donation.item_name.trim() !== '' &&
                    this.donation.item_qty > 0 &&
                    this.donation.item_value !== '' &&
                    !this.isSaving;
            },

            async saveDonation() {
                if (!this.canSave()) return;

                this.isSaving = true;

                const payload = {
                    member_id: this.currentDonor.member_id,
                    donation_item_name: this.donation.item_name,
                    donation_item_qty: parseInt(this.donation.item_qty),
                    donation_item_value: parseFloat(this.donation.item_value), // ราคาต่อหน่วย
                    donation_description: this.donation.description
                };

                try {
                    const res = await fetch('/api/donations', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload)
                    });

                    const result = await res.json();

                    if (result.status === 'success' || result.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'บันทึกสำเร็จ',
                            text: `รับ ${payload.donation_item_name} เข้าคลังเรียบร้อย`,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        this.resetForm();
                    } else {
                        throw new Error(result.message || 'Unknown error');
                    }
                } catch (err) {
                    console.error('Save Error:', err);
                    Swal.fire('เกิดข้อผิดพลาด', err.message || 'ไม่สามารถบันทึกข้อมูลได้', 'error');
                } finally {
                    this.isSaving = false;
                }
            },

            resetForm() {
                this.currentDonor = null;
                this.searchQuery = '';
                this.donation = { item_name: '', item_qty: '', item_value: '', description: '' };
                this.resetDonor(); // Focus กลับไปช่องค้นหา
            }
        };
    }
</script>

<style>
    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(156, 163, 175, 0.5);
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(107, 114, 128, 0.8);
    }
</style>