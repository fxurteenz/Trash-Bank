<div x-data="DonationPOSHandler()" x-init="init()" class="space-y-6">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 mb-2">🎁 ระบบรับของบริจาค</h1>
        <p class="text-slate-600 text-lg">บันทึกการรับบริจาคสิ่งของเข้าคลัง</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white rounded-xl shadow-md p-6 card-hover relative z-20">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-slate-900">
                        <span x-show="!currentDonor">🔍 ค้นหาผู้บริจาค</span>
                        <span x-show="currentDonor" class="text-purple-600">✅ ยืนยันผู้บริจาค</span>
                    </h2>
                    <button x-show="currentDonor" @click="resetDonor()"
                        class="px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                        🔄 เปลี่ยน
                    </button>
                </div>

                <div x-show="!currentDonor" class="space-y-3 relative" @click.away="showDropdown = false">
                    <label class="block text-sm font-semibold text-slate-700">เบอร์โทร / รหัสประจำตัว</label>
                    <div class="relative">
                        <input x-ref="searchInput" x-model="searchQuery" @input.debounce.300ms="searchDonor()"
                            @focus="showDropdown = true" @keydown.enter.prevent="handleEnterKey()"
                            @keydown.escape="showDropdown = false" @keydown.arrow-down.prevent="moveSelection(1)"
                            @keydown.arrow-up.prevent="moveSelection(-1)" type="text"
                            placeholder="ป้อนเบอร์โทรหรือรหัสประจำตัว"
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition"
                            autocomplete="off">

                        <div x-show="showDropdown && (searchResults.length > 0 || isSearching)"
                            class="absolute top-full left-0 z-50 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-xl max-h-60 overflow-y-auto"
                            style="display: none;">
                            <div x-show="isSearching" class="p-4 text-center text-slate-500">
                                <span class="inline-block animate-spin mr-2">⏳</span> กำลังค้นหา...
                            </div>
                            <ul x-ref="resultList" x-show="!isSearching && searchResults.length > 0">
                                <template x-for="(result, index) in searchResults" :key="result.member_id">
                                    <li @click="selectDonor(result)"
                                        :class="{ 'bg-purple-100': index === selectedIndex }"
                                        class="px-4 py-3 cursor-pointer hover:bg-purple-50 border-b border-slate-100 last:border-0">
                                        <div class="flex justify-between">
                                            <div>
                                                <p class="font-bold" x-text="result.member_name"></p>
                                                <p class="text-xs text-slate-500" x-text="result.faculty_name"></p>
                                            </div>
                                            <span class="text-sm font-mono" x-text="result.member_phone"></span>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                            <div x-show="!isSearching && searchResults.length === 0 && searchQuery.length > 0"
                                class="p-4 text-center text-slate-500">❌ ไม่พบข้อมูล</div>
                        </div>
                    </div>
                </div>

                <div x-show="currentDonor"
                    class="bg-gradient-to-br from-purple-50 to-purple-100 border-2 border-purple-300 rounded-lg p-5">
                    <div class="space-y-2">
                        <p class="text-xs font-semibold text-purple-700 uppercase">สมาชิกผู้บริจาค</p>
                        <p class="text-2xl font-bold" x-text="currentDonor?.member_name"></p>
                        <p class="text-sm text-slate-600"
                            x-text="`เบอร์โทร: ${currentDonor?.member_phone || '-'} | คณะ: ${currentDonor?.faculty_name || '-'}`">
                        </p>
                    </div>
                </div>
            </div>

            <div x-show="currentDonor" class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-2xl font-bold text-slate-900 mb-4">📝 รายละเอียดของที่รับ</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">ชื่อสิ่งของ * (inventory
                            key)</label>
                        <input type="text" x-model="donation.item_name" placeholder="เช่น ขวดแก้ว, กระดาษลัง, เสื้อยืด"
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">จำนวน *</label>
                        <input type="number" x-model.number="donation.item_qty" min="1" placeholder="ระบุจำนวน"
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">มูลค่าต่อชิ้น/หน่วย (บาท)
                            *</label>
                        <input type="number" x-model.number="donation.item_value" min="0" step="0.01" placeholder="0.00"
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
                        <p class="text-xs text-slate-500 mt-1">ใช้คำนวณมูลค่ารวมในสต็อก</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">รายละเอียดเพิ่มเติม /
                            หมายเหตุ</label>
                        <textarea x-model="donation.description" rows="2" placeholder="สภาพของ, แหล่งที่มา (ถ้ามี)"
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-md p-6 sticky top-6">
                <h2 class="text-2xl font-bold text-slate-900 mb-4">📋 สรุปรายการ</h2>

                <div class="space-y-4">
                    <div class="p-4 bg-purple-50 rounded-lg">
                        <p class="text-sm text-slate-600 mb-1">ผู้บริจาค</p>
                        <p class="text-lg font-bold text-slate-900"
                            x-text="currentDonor ? currentDonor.member_name : '-'"></p>
                    </div>

                    <div class="p-4 bg-slate-50 rounded-lg border border-slate-100">
                        <div class="flex justify-between mb-2">
                            <span class="text-slate-600">รายการ:</span>
                            <span class="font-semibold text-right" x-text="donation.item_name || '-'"></span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-slate-600">จำนวน:</span>
                            <span class="font-semibold"
                                x-text="donation.item_qty ? `${donation.item_qty} หน่วย` : '-'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600">ราคา/หน่วย:</span>
                            <span class="font-semibold"
                                x-text="donation.item_value ? `฿${parseFloat(donation.item_value).toFixed(2)}` : '-'"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3 bg-blue-50 rounded-lg text-center">
                            <p class="text-xs text-slate-600 mb-1">มูลค่ารวม (บาท)</p>
                            <p class="text-lg font-bold text-blue-600" x-text="`฿${calculateTotal().toFixed(2)}`"></p>
                        </div>
                        <div class="p-3 bg-green-50 rounded-lg text-center">
                            <p class="text-xs text-slate-600 mb-1">แต้มความดีที่ได้</p>
                            <p class="text-lg font-bold text-green-600" x-text="Math.floor(calculateTotal())"></p>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-200 space-y-3">
                        <button @click="saveDonation()" :disabled="!canSave()"
                            :class="canSave() ? 'bg-purple-600 hover:bg-purple-700 shadow-lg transform hover:-translate-y-1' : 'bg-slate-300 cursor-not-allowed'"
                            class="w-full py-3 text-white font-bold rounded-lg transition-all duration-200 flex justify-center items-center gap-2">
                            <span x-show="isSaving" class="animate-spin">⏳</span>
                            <span>💾 บันทึกรับของเข้าคลัง</span>
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

            // --- Search Logic (เหมือนเดิม) ---
            async searchDonor() {
                if (!this.searchQuery.trim()) {
                    this.searchResults = [];
                    this.showDropdown = false;
                    return;
                }
                this.isSearching = true;
                this.showDropdown = true;
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
                setTimeout(() => this.$refs.searchInput.focus(), 100);
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