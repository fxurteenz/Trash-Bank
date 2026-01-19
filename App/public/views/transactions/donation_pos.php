<div x-data="DonationPOSHandler()" x-init="init()" class="space-y-6">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 mb-2">🎁 ระบบรับของบริจาค</h1>
        <p class="text-slate-600 text-lg">บันทึกการรับบริจาคสิ่งของ - ใช้เบอร์โทรหรือรหัสประจำตัว</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- ส่วนเลือกผู้บริจาค -->
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

                <!-- Input เบอร์โทรหรือรหัสประจำตัว -->
                <div x-show="!currentDonor" class="space-y-3 relative" @click.away="showDropdown = false">
                    <label class="block text-sm font-semibold text-slate-700">เบอร์โทร / รหัสประจำตัว</label>
                    <div class="relative">
                        <input x-ref="searchInput" x-model="searchQuery"
                            @input.debounce.200ms="searchDonor()" @focus="showDropdown = true"
                            @keydown.enter.prevent="handleEnterKey()" @keydown.escape="showDropdown = false"
                            @keydown.arrow-down.prevent="moveSelection(1)"
                            @keydown.arrow-up.prevent="moveSelection(-1)" type="text"
                            placeholder="ป้อนเบอร์โทรหรือรหัสประจำตัว"
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition"
                            autocomplete="off">

                        <div x-show="showDropdown && (searchResults.length > 0 || isSearching)"
                            x-transition.opacity.duration.200ms
                            class="absolute top-full left-0 z-50 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-xl max-h-80 overflow-y-auto">
                            <div x-show="isSearching" class="p-4 text-center text-slate-500">
                                <span class="inline-block animate-spin mr-2">⏳</span> กำลังค้นหา...
                            </div>

                            <ul x-show="!isSearching && searchResults.length > 0">
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
                                class="p-4 text-center text-slate-500">
                                ❌ ไม่พบข้อมูล
                            </div>
                        </div>
                    </div>
                </div>

                <!-- แสดงผู้บริจาค -->
                <div x-show="currentDonor"
                    class="bg-gradient-to-br from-purple-50 to-purple-100 border-2 border-purple-300 rounded-lg p-5">
                    <div class="space-y-2">
                        <p class="text-xs font-semibold text-purple-700 uppercase">สมาชิกผู้บริจาค</p>
                        <p class="text-2xl font-bold" x-text="currentDonor?.member_name"></p>
                        <p class="text-sm text-slate-600" x-text="`เบอร์โทร: ${currentDonor?.member_phone} | คณะ: ${currentDonor?.faculty_name}`"></p>
                    </div>
                </div>
            </div>

            <!-- ส่วนกรอกรายละเอียดบริจาค -->
            <div x-show="currentDonor" class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-2xl font-bold text-slate-900 mb-4">📝 รายละเอียดการบริจาค</h2>

                <div class="space-y-4">
                    <!-- ชื่อสิ่งของ -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">ชื่อสิ่งของ *</label>
                        <input type="text" x-model="donation.name"
                            placeholder="เช่น ขวดแก้ว, กระดาษลัง"
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
                    </div>

                    <!-- จำนวน -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">จำนวน *</label>
                        <input type="number" x-model.number="donation.quantity" min="1"
                            placeholder="เช่น 50"
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
                    </div>

                    <!-- มูลค่า = แต้มความดี -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">มูลค่า (บาท) = แต้มความดี *</label>
                        <input type="number" x-model.number="donation.value" min="0" step="0.01"
                            placeholder="0.00"
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
                        <p class="text-xs text-purple-600 mt-1">แต้มความดี = มูลค่า (บาท)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- สรุปรายการ -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-md p-6 sticky top-6">
                <h2 class="text-2xl font-bold text-slate-900 mb-4">📋 สรุปรายการ</h2>

                <div class="space-y-4">
                    <div class="p-4 bg-purple-50 rounded-lg">
                        <p class="text-sm text-slate-600 mb-1">ผู้บริจาค</p>
                        <p class="text-lg font-bold" x-text="currentDonor?.member_name || '-'"></p>
                    </div>

                    <div class="p-4 bg-slate-50 rounded-lg">
                        <p class="text-sm text-slate-600 mb-1">ชื่อสิ่งของ</p>
                        <p class="font-semibold" x-text="donation.name || '-'"></p>
                    </div>

                    <div class="p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-slate-600 mb-1">จำนวน</p>
                        <p class="text-lg font-bold text-blue-600" x-text="`${donation.quantity || 0}`"></p>
                    </div>

                    <div class="p-4 bg-purple-50 rounded-lg">
                        <p class="text-sm text-slate-600 mb-1">มูลค่า / แต้มความดี</p>
                        <p class="text-lg font-bold text-purple-600">
                            <span x-text="`${(donation.value || 0).toFixed(2)} บาท / ${(donation.value || 0).toFixed(2)} แต้ม`"></span>
                        </p>
                    </div>

                    <div class="pt-4 border-t border-slate-200 space-y-3">
                        <button @click="saveDonation()" :disabled="!canSave()" 
                            :class="canSave() ? 'bg-purple-500 hover:bg-purple-600' : 'bg-slate-300 cursor-not-allowed'"
                            class="w-full py-3 text-white font-bold rounded-lg transition-colors">
                            💾 บันทึกการบริจาค
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
                </div>
            </div>
        </div>

        <!-- สรุปรายการ -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-md p-6 sticky top-6">
                <h2 class="text-2xl font-bold text-slate-900 mb-4">📋 สรุปรายการ</h2>

                <div class="space-y-4">
                    <div class="p-4 bg-purple-50 rounded-lg">
                        <p class="text-sm text-slate-600 mb-1">ผู้บริจาค</p>
                        <p class="text-lg font-bold text-slate-900" x-text="currentDonor ? currentDonor.name : '-'"></p>
                    </div>

                    <div class="p-4 bg-slate-50 rounded-lg">
                        <p class="text-sm text-slate-600 mb-1">รายละเอียด</p>
                        <p class="text-sm text-slate-900" x-text="donation.description || '-'"></p>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3 bg-blue-50 rounded-lg text-center">
                            <p class="text-xs text-slate-600 mb-1">มูลค่า</p>
                            <p class="text-lg font-bold text-blue-600" x-text="`฿${(donation.estimatedValue || 0).toFixed(2)}`"></p>
                        </div>
                        <div class="p-3 bg-purple-50 rounded-lg text-center">
                            <p class="text-xs text-slate-600 mb-1">แต้มความดี</p>
                            <p class="text-lg font-bold text-purple-600" x-text="donation.goodnessPoint || 0"></p>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-200 space-y-3">
                        <button @click="saveDonation()" :disabled="!canSave()" 
                            :class="canSave() ? 'bg-purple-500 hover:bg-purple-600' : 'bg-slate-300 cursor-not-allowed'"
                            class="w-full py-3 text-white font-bold rounded-lg transition-colors">
                            💾 บันทึกการบริจาค
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
        currentDonor: null,
        searchQuery: '',
        searchResults: [],
        isSearching: false,
        showDropdown: false,
        selectedIndex: -1,
        donation: {
            name: '',
            quantity: '',
            value: ''
        },

        async init() {
            this.$nextTick(() => {
                if (this.$refs.searchInput) {
                    this.$refs.searchInput.focus();
                }
            });
        },

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
                if (data.success) {
                    this.searchResults = data.data || [];
                    this.selectedIndex = -1;
                } else {
                    this.searchResults = [];
                }
            } catch (err) {
                console.error('Failed to search donor', err);
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
            this.selectedIndex += step;
            if (this.selectedIndex < 0) {
                this.selectedIndex = this.searchResults.length - 1;
            } else if (this.selectedIndex >= this.searchResults.length) {
                this.selectedIndex = 0;
            }
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
            this.$nextTick(() => {
                if (this.$refs.searchInput) {
                    this.$refs.searchInput.focus();
                }
            });
        },

        canSave() {
            return this.currentDonor && this.donation.name.trim() !== '' && this.donation.quantity > 0 && this.donation.value >= 0;
        },

        async saveDonation() {
            if (!this.canSave()) {
                Swal.fire('แจ้งเตือน', 'กรุณากรอกข้อมูลให้ครบถ้วน', 'warning');
                return;
            }

            try {
                const data = {
                    member_id: this.currentDonor.member_id,
                    donation_description: this.donation.name,
                    donation_quantity: this.donation.quantity,
                    donation_estimated_value: parseFloat(this.donation.value) || 0,
                    donation_goodness_point: parseFloat(this.donation.value) || 0
                };

                const res = await fetch('/api/donations', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                const result = await res.json();

                if (result.success) {
                    Swal.fire('สำเร็จ', 'บันทึกการบริจาคเรียบร้อยแล้ว', 'success');
                    this.resetForm();
                } else {
                    Swal.fire('เกิดข้อผิดพลาด', result.message || 'ไม่สามารถบันทึกได้', 'error');
                }
            } catch (err) {
                console.error('Error saving donation:', err);
                Swal.fire('เกิดข้อผิดพลาด', 'ไม่สามารถบันทึกข้อมูล', 'error');
            }
        },

        resetForm() {
            this.currentDonor = null;
            this.searchQuery = '';
            this.searchResults = [];
            this.donation = {
                name: '',
                quantity: '',
                value: ''
            };
            this.$nextTick(() => {
                if (this.$refs.searchInput) {
                    this.$refs.searchInput.focus();
                }
            });
        }
    };
}
</script>
