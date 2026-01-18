<div x-data="DonationPOSHandler()" x-init="init()" class="space-y-6 relative">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 mb-2">🎁 ระบบรับของบริจาค</h1>
        <p class="text-slate-600 text-lg">บันทึกการรับบริจาคสิ่งของ - ค้นหาสมาชิก/คณะและลงรายการ</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- ส่วนกรอกผู้บริจาค -->
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

                <!-- เลือกประเภทผู้บริจาค -->
                <div x-show="!currentDonor" class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">ประเภทผู้บริจาค</label>
                    <div class="flex gap-4">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" x-model="donorType" value="member" class="mr-2">
                            <span>สมาชิก</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" x-model="donorType" value="faculty" class="mr-2">
                            <span>คณะ</span>
                        </label>
                    </div>
                </div>

                <!-- dropdown เลือกสมาชิก -->
                <div x-show="!currentDonor && donorType === 'member'" class="space-y-3 relative" @click.away="showDropdown = false">
                    <label class="block text-sm font-semibold text-slate-700">เบอร์โทร หรือ ชื่อสมาชิก</label>
                    <div class="relative">
                        <input x-ref="memberInput" x-model="memberSearch"
                            @input.debounce.300ms="searchMember()" @focus="showDropdown = true"
                            @keydown.enter.prevent="handleEnterKey()" @keydown.escape="showDropdown = false"
                            @keydown.arrow-down.prevent="moveSelection(1)"
                            @keydown.arrow-up.prevent="moveSelection(-1)" type="text"
                            placeholder="กรอกเบอร์โทร หรือ ชื่อสมาชิก"
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition"
                            autocomplete="off">

                        <div x-show="showDropdown && (searchResults.length > 0 || isSearching)"
                            x-transition.opacity.duration.200ms
                            class="absolute top-full left-0 z-50 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-xl max-h-80 overflow-y-auto">
                            <div x-show="isSearching" class="p-4 text-center text-slate-500">
                                <span class="inline-block animate-spin mr-2">⏳</span> กำลังค้นหา...
                            </div>

                            <ul x-show="!isSearching && searchResults.length > 0">
                                <template x-for="(member, index) in searchResults" :key="member.member_id">
                                    <li @click="selectMember(member)" 
                                        :class="{ 'bg-purple-100 ring-1 ring-inset ring-purple-300': index === selectedIndex, 'hover:bg-purple-50': index !== selectedIndex }"
                                        class="px-4 py-3 cursor-pointer border-b border-slate-100 last:border-0 transition-colors">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="font-bold text-slate-800" x-text="member.member_name || 'ไม่ระบุชื่อ'"></p>
                                                <p class="text-xs text-slate-500">
                                                    <span x-text="member.faculty_name"></span>
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-xs font-mono bg-slate-100 px-2 py-1 rounded text-slate-600" x-text="member.member_phone"></span>
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
                </div>

                <!-- dropdown เลือกคณะ -->
                <div x-show="!currentDonor && donorType === 'faculty'" class="space-y-3">
                    <label class="block text-sm font-semibold text-slate-700">เลือกคณะ</label>
                    <select x-model="selectedFacultyId" @change="selectFaculty()"
                        class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
                        <option value="">-- เลือกคณะ --</option>
                        <template x-for="faculty in faculties" :key="faculty.faculty_id">
                            <option :value="faculty.faculty_id" x-text="faculty.faculty_name"></option>
                        </template>
                    </select>
                </div>

                <!-- แสดงข้อมูลผู้บริจาค -->
                <div x-show="currentDonor"
                    class="bg-gradient-to-br from-purple-50 to-purple-100 border-2 border-purple-300 rounded-lg p-5">
                    <div class="space-y-2">
                        <p class="text-xs font-semibold text-purple-700 uppercase tracking-wider" x-text="donorType === 'member' ? 'สมาชิกผู้บริจาค' : 'คณะผู้บริจาค'"></p>
                        <p class="text-2xl font-bold text-slate-900" x-text="currentDonor?.name || 'ไม่ระบุชื่อ'"></p>
                        <div class="flex gap-4 text-sm text-slate-600" x-show="donorType === 'member'">
                            <span>เบอร์โทร : <span class="font-semibold" x-text="currentDonor?.phone"></span></span>
                            <span>คณะ : <span class="font-semibold" x-text="currentDonor?.faculty"></span></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ส่วนกรอกรายละเอียดการบริจาค -->
            <div x-show="currentDonor" class="bg-white rounded-xl shadow-md p-6 card-hover">
                <h2 class="text-2xl font-bold text-slate-900 mb-4">📝 รายละเอียดการบริจาค</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">รายละเอียดสิ่งของที่บริจาค *</label>
                        <textarea x-model="donation.description" rows="3"
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition"
                            placeholder="เช่น ขวดแก้ว 50 ใบ, กระดาษลัง 10 กก."></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">มูลค่าโดยประมาณ (บาท)</label>
                            <input type="number" x-model="donation.estimatedValue" step="0.01" min="0"
                                class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition"
                                placeholder="0.00">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">แต้มความดี (Goodness Point)</label>
                            <input type="number" x-model="donation.goodnessPoint" min="0"
                                class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition"
                                placeholder="0">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">เหตุผลในการบริจาค</label>
                        <input type="text" x-model="donation.reason"
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition"
                            placeholder="เช่น บริจาคเพื่อสิ่งแวดล้อม, ช่วยเหลือสังคม">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">วันที่บริจาค</label>
                        <input type="date" x-model="donation.date"
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
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
        donorType: 'member',
        currentDonor: null,
        memberSearch: '',
        searchResults: [],
        isSearching: false,
        showDropdown: false,
        selectedIndex: -1,
        selectedFacultyId: '',
        faculties: [],
        donation: {
            description: '',
            estimatedValue: null,
            goodnessPoint: null,
            reason: '',
            date: new Date().toISOString().split('T')[0]
        },

        async init() {
            await this.loadFaculties();
        },

        async loadFaculties() {
            try {
                const response = await fetch('/api/faculties');
                const result = await response.json();
                if (result.success) {
                    this.faculties = result.data;
                }
            } catch (error) {
                console.error('Error loading faculties:', error);
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
            this.currentDonor = {
                type: 'member',
                id: member.member_id,
                name: member.member_name,
                phone: member.member_phone,
                faculty: member.faculty_name,
                facultyId: member.faculty_id
            };
            this.showDropdown = false;
            this.memberSearch = '';
        },

        selectFaculty() {
            const faculty = this.faculties.find(f => f.faculty_id == this.selectedFacultyId);
            if (faculty) {
                this.currentDonor = {
                    type: 'faculty',
                    id: faculty.faculty_id,
                    name: faculty.faculty_name
                };
            }
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

        resetDonor() {
            this.currentDonor = null;
            this.memberSearch = '';
            this.selectedFacultyId = '';
            this.searchResults = [];
        },

        canSave() {
            return this.currentDonor && this.donation.description.trim() !== '';
        },

        async saveDonation() {
            if (!this.canSave()) return;

            try {
                const data = {
                    member_id: this.donorType === 'member' ? this.currentDonor.id : null,
                    faculty_id: this.donorType === 'member' ? this.currentDonor.facultyId : this.currentDonor.id,
                    donation_description: this.donation.description,
                    donation_estimated_value: this.donation.estimatedValue || null,
                    donation_goodness_point: this.donation.goodnessPoint || null,
                    donation_reason: this.donation.reason || null,
                    donation_date: this.donation.date
                };

                const response = await fetch('/api/donations', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    alert('✅ บันทึกการบริจาคสำเร็จ!');
                    this.resetForm();
                } else {
                    alert('❌ เกิดข้อผิดพลาด: ' + result.message);
                }
            } catch (error) {
                console.error('Error saving donation:', error);
                alert('❌ เกิดข้อผิดพลาดในการบันทึกข้อมูล');
            }
        },

        resetForm() {
            this.currentDonor = null;
            this.memberSearch = '';
            this.selectedFacultyId = '';
            this.searchResults = [];
            this.donation = {
                description: '',
                estimatedValue: null,
                goodnessPoint: null,
                reason: '',
                date: new Date().toISOString().split('T')[0]
            };
            this.donorType = 'member';
        }
    };
}
</script>
