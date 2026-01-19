<div x-data="DonationExchangeHandler()" x-init="init()" class="space-y-6">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 mb-2">🎁 ระบบแลกของบริจาค</h1>
        <p class="text-slate-600 text-lg">แลกของบริจาคด้วยแต้ม (1 บาท = 10 แต้ม)</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- ส่วนค้นหาสมาชิก -->
            <div class="bg-white rounded-xl shadow-md p-6 card-hover relative z-20">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-slate-900">
                        <span x-show="!currentMember">🔍 ค้นหาสมาชิก</span>
                        <span x-show="currentMember" class="text-blue-600">✅ ยืนยันสมาชิก</span>
                    </h2>
                    <button x-show="currentMember" @click="resetMember()"
                        class="px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                        🔄 เปลี่ยน
                    </button>
                </div>

                <!-- Input ค้นหาสมาชิก -->
                <div x-show="!currentMember" class="space-y-3 relative" @click.away="showDropdown = false">
                    <label class="block text-sm font-semibold text-slate-700">เบอร์โทร / รหัสประจำตัว</label>
                    <div class="relative">
                        <input x-ref="memberInput" x-model="memberSearch"
                            @input.debounce.200ms="searchMember()" @focus="showDropdown = true"
                            @keydown.enter.prevent="handleEnterKey()" @keydown.escape="showDropdown = false"
                            @keydown.arrow-down.prevent="moveSelection(1)"
                            @keydown.arrow-up.prevent="moveSelection(-1)" type="text"
                            placeholder="ป้อนเบอร์โทรหรือรหัสประจำตัว"
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                            autocomplete="off">

                        <div x-show="showDropdown && (searchResults.length > 0 || isSearching)"
                            class="absolute top-full left-0 z-50 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-xl max-h-80 overflow-y-auto">
                            <div x-show="isSearching" class="p-4 text-center text-slate-500">
                                <span class="inline-block animate-spin mr-2">⏳</span> กำลังค้นหา...
                            </div>

                            <ul x-show="!isSearching && searchResults.length > 0">
                                <template x-for="(member, index) in searchResults" :key="member.member_id">
                                    <li @click="selectMember(member)"
                                        :class="{ 'bg-blue-100': index === selectedIndex }"
                                        class="px-4 py-3 cursor-pointer hover:bg-blue-50 border-b border-slate-100 last:border-0">
                                        <div class="flex justify-between">
                                            <div>
                                                <p class="font-bold" x-text="member.member_name"></p>
                                                <p class="text-xs text-slate-500" x-text="member.faculty_name"></p>
                                            </div>
                                            <span class="text-sm font-mono" x-text="member.member_phone"></span>
                                        </div>
                                    </li>
                                </template>
                            </ul>

                            <div x-show="!isSearching && searchResults.length === 0 && memberSearch.length > 0"
                                class="p-4 text-center text-slate-500">
                                ❌ ไม่พบข้อมูล
                            </div>
                        </div>
                    </div>
                </div>

                <!-- แสดงข้อมูลสมาชิก -->
                <div x-show="currentMember"
                    class="bg-gradient-to-br from-blue-50 to-blue-100 border-2 border-blue-300 rounded-lg p-5">
                    <div class="space-y-2">
                        <p class="text-xs font-semibold text-blue-700 uppercase">สมาชิกผู้แลก</p>
                        <p class="text-2xl font-bold" x-text="currentMember?.member_name"></p>
                        <div class="grid grid-cols-3 gap-3 mt-4 text-center">
                            <div class="bg-white rounded p-3">
                                <p class="text-xs text-slate-600">แต้มคนเก็บ</p>
                                <p class="text-xl font-bold text-blue-600" x-text="currentMember?.member_point || 0"></p>
                            </div>
                            <div class="bg-white rounded p-3">
                                <p class="text-xs text-slate-600">มูลค่า (บาท)</p>
                                <p class="text-xl font-bold text-green-600" x-text="`฿${(currentMember?.member_point / 10).toFixed(2)}`"></p>
                            </div>
                            <div class="bg-white rounded p-3">
                                <p class="text-xs text-slate-600">อัตราแลก</p>
                                <p class="text-xl font-bold text-purple-600">10:1</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ส่วนกรอกจำนวนแลก -->
            <div x-show="currentMember" class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-2xl font-bold text-slate-900 mb-4">📝 ป้อนจำนวนแลก</h2>

                <div class="space-y-4">
                    <!-- ป้อนแต้มที่ต้องการแลก -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">จำนวนแต้มที่ต้องการแลก *</label>
                        <div class="relative">
                            <input type="number" x-model.number="exchangePoints" min="10" step="10"
                                placeholder="10, 20, 30 ... (ต้องเป็นพหุคูณของ 10)"
                                class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                        </div>
                        <p class="text-xs text-blue-600 mt-1">📌 แต้มต้องเป็นพหุคูณของ 10 (1 บาท = 10 แต้ม)</p>
                    </div>

                    <!-- แสดงการแลก -->
                    <div x-show="exchangePoints > 0" class="bg-blue-50 border-2 border-blue-200 rounded-lg p-4 space-y-3">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-slate-600">แต้มที่แลก</p>
                                <p class="text-2xl font-bold text-blue-600" x-text="`${exchangePoints}`"></p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-600">มูลค่า (บาท)</p>
                                <p class="text-2xl font-bold text-green-600" x-text="`฿${(exchangePoints / 10).toFixed(2)}`"></p>
                            </div>
                        </div>
                        
                        <div class="pt-3 border-t border-blue-200">
                            <p class="text-xs text-slate-600">แต้มคงเหลือหลังแลก</p>
                            <p class="text-lg font-bold" x-text="`${(currentMember?.member_point - exchangePoints) || 0}`"></p>
                        </div>

                        <div x-show="(currentMember?.member_point - exchangePoints) < 0" class="bg-red-100 border border-red-300 rounded p-2">
                            <p class="text-sm text-red-700 font-semibold">⚠️ แต้มไม่เพียงพอ!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- สรุปการแลก -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-md p-6 sticky top-6">
                <h2 class="text-2xl font-bold text-slate-900 mb-4">📋 สรุปการแลก</h2>

                <div class="space-y-4">
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-slate-600 mb-1">สมาชิก</p>
                        <p class="text-lg font-bold" x-text="currentMember?.member_name || '-'"></p>
                    </div>

                    <div class="p-4 bg-slate-50 rounded-lg">
                        <p class="text-sm text-slate-600 mb-1">เบอร์โทร</p>
                        <p class="font-semibold" x-text="currentMember?.member_phone || '-'"></p>
                    </div>

                    <div class="p-4 bg-green-50 rounded-lg">
                        <p class="text-sm text-slate-600 mb-1">แต้มที่แลก</p>
                        <p class="text-2xl font-bold text-green-600" x-text="`${exchangePoints || 0}`"></p>
                    </div>

                    <div class="p-4 bg-purple-50 rounded-lg">
                        <p class="text-sm text-slate-600 mb-1">มูลค่า (บาท)</p>
                        <p class="text-2xl font-bold text-purple-600" x-text="`฿${((exchangePoints || 0) / 10).toFixed(2)}`"></p>
                    </div>

                    <div class="pt-4 border-t border-slate-200 space-y-3">
                        <button @click="submitExchange()" :disabled="!canExchange()" 
                            :class="canExchange() ? 'bg-blue-500 hover:bg-blue-600' : 'bg-slate-300 cursor-not-allowed'"
                            class="w-full py-3 text-white font-bold rounded-lg transition-colors">
                            ✅ ยืนยันการแลก
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
function DonationExchangeHandler() {
    return {
        currentMember: null,
        memberSearch: '',
        searchResults: [],
        isSearching: false,
        showDropdown: false,
        selectedIndex: -1,
        exchangePoints: 0,

        async init() {
            this.$nextTick(() => {
                if (this.$refs.memberInput) {
                    this.$refs.memberInput.focus();
                }
            });
        },

        async searchMember() {
            if (!this.memberSearch.trim()) {
                this.searchResults = [];
                this.showDropdown = false;
                return;
            }

            this.isSearching = true;
            this.showDropdown = true;

            try {
                const res = await fetch(`/api/members?search=${encodeURIComponent(this.memberSearch)}&limit=10`);
                const data = await res.json();
                if (data.success) {
                    this.searchResults = data.data || [];
                    this.selectedIndex = -1;
                } else {
                    this.searchResults = [];
                }
            } catch (err) {
                console.error('Failed to search member', err);
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
                this.selectMember(this.searchResults[this.selectedIndex]);
            }
        },

        resetMember() {
            this.currentMember = null;
            this.memberSearch = '';
            this.searchResults = [];
            this.exchangePoints = 0;
            this.$nextTick(() => {
                if (this.$refs.memberInput) {
                    this.$refs.memberInput.focus();
                }
            });
        },

        canExchange() {
            return this.currentMember && 
                   this.exchangePoints > 0 && 
                   this.exchangePoints % 10 === 0 && 
                   this.exchangePoints <= (this.currentMember?.member_point || 0);
        },

        async submitExchange() {
            if (!this.canExchange()) {
                Swal.fire('แจ้งเตือน', 'กรุณาตรวจสอบข้อมูล', 'warning');
                return;
            }

            const confirmation = await Swal.fire({
                title: 'ยืนยันการแลก?',
                html: `
                    <div class="text-left text-sm">
                        <p><b>สมาชิก:</b> ${this.currentMember.member_name}</p>
                        <p><b>แต้มที่แลก:</b> ${this.exchangePoints}</p>
                        <p><b>มูลค่า:</b> ฿${(this.exchangePoints / 10).toFixed(2)}</p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'ใช่, ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            });

            if (confirmation.isConfirmed) {
                await this.submitToAPI();
            }
        },

        async submitToAPI() {
            try {
                const data = {
                    member_id: this.currentMember.member_id,
                    exchange_points: this.exchangePoints,
                    exchange_value: this.exchangePoints / 10
                };

                const res = await fetch('/api/member_rewards', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                const result = await res.json();

                if (result.success) {
                    Swal.fire('สำเร็จ', 'บันทึกการแลกเรียบร้อยแล้ว', 'success');
                    this.resetForm();
                } else {
                    Swal.fire('เกิดข้อผิดพลาด', result.message || 'ไม่สามารถบันทึกได้', 'error');
                }
            } catch (err) {
                console.error('Error submitting exchange:', err);
                Swal.fire('เกิดข้อผิดพลาด', 'ไม่สามารถบันทึกข้อมูล', 'error');
            }
        },

        resetForm() {
            this.currentMember = null;
            this.memberSearch = '';
            this.searchResults = [];
            this.exchangePoints = 0;
            this.$nextTick(() => {
                if (this.$refs.memberInput) {
                    this.$refs.memberInput.focus();
                }
            });
        }
    };
}
</script>
