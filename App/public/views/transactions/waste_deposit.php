<div x-data="WasteDepositPOSHandler()" x-init="init()" class="space-y-6 relative">
    <div class="mb-8 flex flex-col md:flex-row gap-6">
        <div class="w-1/3">
            <h1 class="text-4xl font-bold text-slate-900 mb-2">ระบบฝากขยะ</h1>
            <p class="text-slate-600 text-lg">บันทึกการฝากขยะ - ค้นหาสมาชิกและลงรายการ</p>
        </div>


        <!-- ส่วนกรอกผู้ฝาก -->
        <div class="bg-white rounded-xl shadow-md p-6 card-hover relative w-2/3">
            <!-- ฟอร์ม -->
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold text-slate-900">
                    <span x-show="!currentMember">ค้นหาสมาชิก</span>
                    <span x-show="currentMember" class="text-emerald-600">ยืนยันสมาชิก</span>
                </h2>
                <button x-show="currentMember" @click="resetMember()"
                    class="px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                    เปลี่ยน
                </button>
            </div>
            <!-- dropdown เลือกสมาชิก -->
            <div x-show="!currentMember" class="space-y-3 relative" @click.away="showDropdown = false">
                <label class="block text-sm font-semibold text-slate-700">เบอร์โทร หรือ ชื่อสมาชิก</label>

                <div class="relative">
                    <div class="flex gap-2">
                        <input x-ref="memberInput" x-model="memberSearch" @input.debounce.300ms="searchMember(false)"
                            @focus="showDropdown = true" @keydown.enter.prevent="handleEnterKey()"
                            @keydown.escape="showDropdown = false" @keydown.arrow-down.prevent="moveSelection(1)"
                            @keydown.tab.prevent="moveSelection(1)" @keydown.arrow-up.prevent="moveSelection(-1)"
                            type="text" placeholder="กรอกเบอร์โทร หรือ ชื่อสมาชิก"
                            class="flex-1 px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
                            autocomplete="off">
                    </div>

                    <div x-show="showDropdown && (searchResults.length > 0 || isSearching)" x-ref="dropdownList"
                        x-transition.opacity.duration.200ms
                        class="absolute top-full left-0 z-50 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-xl max-h-80 overflow-y-auto">

                        <div x-show="isSearching" class="p-4 text-center text-slate-500">
                            <span class="inline-block animate-spin mr-2"></span> กำลังค้นหา...
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
                                                <span x-text="member.role_name_th"></span> •
                                                <span x-text="member.faculty_name"></span>
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <span
                                                class="text-xs font-mono bg-slate-100 px-2 py-1 rounded text-slate-600"
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
                            ไม่พบข้อมูลสมาชิก
                        </div>
                    </div>
                </div>

                <p class="text-xs text-slate-500">
                    ใช้ปุ่ม <kbd class="bg-slate-100 px-2 py-1 rounded">Arrow Down</kbd><kbd
                        class="bg-slate-100 px-2 py-1 rounded">Arrow Up</kbd> เพื่อเลือก และ<kbd
                        class="bg-slate-100 px-2 py-1 rounded">Enter</kbd> ยืนยัน
                </p>
            </div>
            <!-- แสดงข้อมูลสมาชิก -->
            <div x-show="currentMember"
                class="bg-gradient-to-br from-emerald-50 to-emerald-100 border-2 border-emerald-300 rounded-lg p-5">
                <div class="space-y-2">
                    <p class="text-xs font-semibold text-emerald-700 uppercase tracking-wider">สมาชิก</p>
                    <p class="text-2xl font-bold text-slate-900" x-text="currentMember?.member_name || 'ไม่ระบุชื่อ'">
                    </p>
                    <div class="flex gap-4 text-sm text-slate-600">
                        <span>
                            เบอร์โทร : <span class="font-semibold" x-text="currentMember?.member_phone"></span>
                        </span>
                        <span>
                            คณะ : <span class="font-semibold" x-text="currentMember?.faculty_name"></span>
                        </span>
                        <span>
                            แต้มสะสม : <span class="font-bold text-emerald-600"
                                x-text="currentMember?.member_waste_point"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">

            <!-- ฟอร์มกรอกข้อมูลรายการที่ฝาก -->
            <div x-show="currentMember" class="bg-white rounded-xl shadow-md p-6 card-hover z-10">

                <h2 class="text-2xl font-bold text-slate-900 mb-5">เพิ่มรายการขยะ</h2>
                <div class="grid grid-cols-12 gap-3">

                    <div class="col-span-5">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">รหัสชนิดขยะ</label>
                        <input x-ref="wasteCodeInput" x-model="itemForm.wasteCode"
                            @keydown.tab.prevent="$refs.weightInput.focus()" @keydown.enter="handleWasteCodeEnter()"
                            @input="searchWasteType()" type="text" placeholder="พิมพ์รหัส/ชื่อ"
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                            list="wasteTypeList" autocomplete="off">
                        <datalist id="wasteTypeList">
                            <template x-for="type in filteredWasteTypes" :key="type.waste_type_id">
                                <option :value="type.waste_type_id"
                                    :label="`${type.waste_category_name} : ${type.waste_type_name}`"></option>
                            </template>
                        </datalist>
                        <p class="text-xs text-emerald-600 mt-2 font-medium"
                            x-text="selectedWasteType ? `${selectedWasteType?.waste_category_name} : ${selectedWasteType?.waste_type_name}`:  'ระบุรหัสชนิดขยะ'">
                        </p>
                    </div>

                    <div class="col-span-4">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">น้ำหนัก (กก.)</label>
                        <input x-ref="weightInput" x-model="itemForm.weight" @keydown.enter="addItem()"
                            @keydown.tab.prevent="addItem(); $refs.wasteCodeInput.focus()" type="number" step="0.01"
                            placeholder="0.00"
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                        <p class="text-xs text-slate-500 mt-2 font-medium">ใส่ค่าลบ (เช่น -2) เพื่อลดน้ำหนัก</p>
                    </div>

                    <div class="col-span-3 my-auto">
                        <button @click="addItem()"
                            class="w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors">
                            เพิ่ม
                        </button>
                    </div>
                </div>
            </div>
            <!-- รายการที่ฝาก -->
            <div x-show="items.length > 0" class="bg-white rounded-xl shadow-md p-6 z-0">
                <h2 class="text-2xl font-bold text-slate-900 mb-4">รายการที่บันทึก <span class="text-blue-600"
                        x-text="items.length"></span></h2>
                <div class="space-y-2 max-h-96 overflow-y-auto">
                    <template x-for="(item, index) in items" :key="index">
                        <div
                            class="flex items-center justify-between p-4 bg-slate-50 hover:bg-slate-100 rounded-lg transition group">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <span class="text-lg font-bold text-slate-400 w-8 text-center"
                                        x-text="index + 1"></span>
                                    <div>
                                        <p>
                                            <span class="font-light text-slate-900" x-text="item.waste_type_id"></span>
                                            :
                                            <span class="font-semibold text-slate-900"
                                                x-text="item.waste_type_name"></span>
                                        </p>
                                        <p class="text-sm text-slate-600"
                                            x-text="'น้ำหนัก: ' + item.weight.toFixed(2) + ' กก.'"></p>
                                    </div>
                                </div>
                            </div>
                                                            <button @click="removeItem(index)"
                                                            class="ml-4 px-3 py-2 text-sm bg-red-100 hover:bg-red-200 text-red-700 rounded-lg opacity-0 group-hover:opacity-100 transition-all">
                                                            ลบ
                                                        </button>                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div
                class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-lg p-6 text-white sticky top-8">
                <h3 class="text-lg font-bold mb-5 flex items-center gap-2">
                    สรุป
                </h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-emerald-100">จำนวนรายการ</span>
                        <span class="text-3xl font-bold" x-text="items.length"></span>
                    </div>
                    <div class="h-px bg-emerald-400 opacity-50"></div>
                    <div class="flex justify-between items-center">
                        <span class="text-emerald-100">น้ำหนักรวม</span>
                        <span class="text-2xl font-bold" x-text="totalWeight.toFixed(2) + ' กก.'"></span>
                    </div>
                    <div class="h-px bg-emerald-400 opacity-50"></div>
                    <div class="bg-emerald-600 rounded-lg p-4 mt-4">
                        <p class="text-emerald-100 text-sm mb-1">คะแนนที่จะได้รับ</p>
                        <p class="text-4xl font-bold" x-text="totalPoints.toFixed(0)"></p>
                    </div>
                </div>
                <div class="mt-6 space-y-3">
                    <button @click="submitTransaction()" :disabled="items.length === 0 || isSubmitting"
                        :class="items.length === 0 || isSubmitting ? 'bg-emerald-700 opacity-50 cursor-not-allowed' : 'bg-white hover:bg-slate-50 text-emerald-600'"
                        class="w-full px-6 py-4 rounded-lg font-bold text-lg transition-colors">
                        <span x-show="!isSubmitting">บันทึกทั้งหมด</span>
                        <span x-show="isSubmitting">กำลังบันทึก...</span>
                    </button>
                    <button @click="cancelAll()" :disabled="items.length === 0"
                        class="w-full px-6 py-3 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        ยกเลิกทั้งหมด
                    </button>
                </div>
                <p class="text-center text-emerald-100 text-xs mt-4">กด <kbd
                        class="bg-emerald-600 px-2 py-1 rounded">Ctrl+Enter</kbd> เพื่อบันทึก</p>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">สถิติวันนี้</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-600">ทำรายการ</span>
                        <span class="font-bold text-slate-900" x-text="todayStats.count + ' ครั้ง'"></span>
                    </div>
                    <div class="h-px bg-slate-200"></div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-600">น้ำหนักรวม</span>
                        <span class="font-bold text-slate-900" x-text="todayStats.weight.toFixed(2) + ' กก.'"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div @keydown.ctrl.enter.window="submitTransaction()"></div>
</div>

<script>
    function WasteDepositPOSHandler() {
        return {
            // --- Member Search & Dropdown State ---
            memberSearch: '',
            currentMember: null,
            searchResults: [],
            showDropdown: false,
            isSearching: false,
            selectedIndex: -1,

            // --- Transaction State ---
            itemForm: { wasteCode: '', weight: '' },
            wasteTypes: [],
            filteredWasteTypes: [],
            selectedWasteType: null,
            items: [],
            isSubmitting: false,
            todayStats: { count: 0, weight: 0 },

            // --- Computed ---
            get totalWeight() {
                return this.items.reduce((sum, item) => sum + parseFloat(item.weight || 0), 0);
            },

            get totalPoints() {
                return this.items.reduce((sum, item) => {
                    const price = parseFloat(item.waste_type_price || 0);
                    const weight = parseFloat(item.weight || 0);
                    return sum + (price * weight) / 2 * 10;
                }, 0);
            },

            // --- Cookie Helpers ---
            setCookie(name, value) {
                const date = new Date();
                date.setHours(23, 59, 59, 999); // Set to end of day
                let expires = "; expires=" + date.toUTCString();
                document.cookie = name + "=" + (value || "") + expires + "; path=/";
            },
            getCookie(name) {
                const nameEQ = name + "=";
                const ca = document.cookie.split(';');
                for (let i = 0; i < ca.length; i++) {
                    let c = ca[i];
                    while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
                }
                return null;
            },

            // --- Init ---
            async init() {
                await this.loadWasteTypes();
                this.loadTodayStats();
                this.focusMemberInput();
            },

            focusMemberInput() {
                this.$nextTick(() => {
                    if (this.$refs.memberInput) {
                        this.$refs.memberInput.focus();
                    }
                });
            },

            // --- Member Logic ---
            async searchMember(force = false) {
                if (!this.memberSearch.trim()) {
                    this.searchResults = [];
                    this.showDropdown = false;
                    this.selectedIndex = -1;
                    return;
                }

                this.isSearching = true;
                this.showDropdown = true;
                this.selectedIndex = -1;

                try {
                    const response = await fetch(`/api/members?page=1&limit=10&role=2&search=${encodeURIComponent(this.memberSearch)}`);
                    const result = await response.json();

                    if (result.success) {
                        this.searchResults = result.data || [];

                        if (force && this.searchResults.length > 0) {
                            this.selectedIndex = 0;
                        } else if (force && this.searchResults.length === 0) {
                            this.showNotification('ไม่พบสมาชิก', 'error');
                        }
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

            handleEnterKey() {
                if (this.selectedIndex >= 0 && this.searchResults[this.selectedIndex]) {
                    this.selectMember(this.searchResults[this.selectedIndex]);
                } else if (this.memberSearch.trim()) {
                    this.searchMember(true);
                }
            },

            // --- แก้ไขฟังก์ชัน moveSelection ให้วน Loop ได้ ---
            moveSelection(step) {
                if (!this.showDropdown || this.searchResults.length === 0) return;

                // 1. เปลี่ยนตำแหน่ง
                this.selectedIndex += step;

                // 2. ตรวจสอบเงื่อนไขวน Loop
                if (this.selectedIndex >= this.searchResults.length) {
                    // ถ้าเกินรายการสุดท้าย -> วนกลับไปรายการแรก
                    this.selectedIndex = 0;
                } else if (this.selectedIndex < 0) {
                    // ถ้าถอยหลังเลยรายการแรก -> วนไปรายการสุดท้าย
                    this.selectedIndex = this.searchResults.length - 1;
                }

                // 3. Scroll ให้รายการที่เลือกโชว์ขึ้นมา
                this.$nextTick(() => {
                    const el = document.getElementById('member-item-' + this.selectedIndex);
                    if (el) {
                        el.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
                    }
                });
            },

            selectMember(member) {
                this.currentMember = member;
                this.memberSearch = '';
                this.searchResults = [];
                this.showDropdown = false;
                this.selectedIndex = -1;

                this.showNotification('เลือกสมาชิก: ' + (member.member_name || 'ไม่ระบุชื่อ'), 'success');

                this.$nextTick(() => {
                    if (this.$refs.wasteCodeInput) {
                        this.$refs.wasteCodeInput.focus();
                    }
                });
            },

            resetMember() {
                this.currentMember = null;
                this.items = [];
                this.clearItemForm();
                this.focusMemberInput();
                this.searchResults = [];
                this.showDropdown = false;
                this.selectedIndex = -1;
            },

            // --- Data Loading ---
            async loadWasteTypes() {
                try {
                    const response = await fetch('/api/waste_types');
                    const result = await response.json();
                    if (result.success) {
                        this.wasteTypes = result.data || [];
                        this.filteredWasteTypes = this.wasteTypes;
                    }
                } catch (error) {
                    console.error('Error loading waste types:', error);
                    this.showNotification('ไม่สามารถโหลดข้อมูลชนิดขยะได้', 'error');
                }
            },

            // --- Item Form Logic ---
            searchWasteType() {
                const search = this.itemForm.wasteCode.toLowerCase();
                this.filteredWasteTypes = this.wasteTypes.filter(type =>
                    type.waste_type_id.toString().includes(search) ||
                    type.waste_type_name.toLowerCase().includes(search)
                );

                const exactMatch = this.wasteTypes.find(type =>
                    type.waste_type_id.toString() === this.itemForm.wasteCode
                );
                this.selectedWasteType = exactMatch || null;
            },

            handleWasteCodeEnter() {
                if (this.selectedWasteType) {
                    this.$refs.weightInput.focus();
                }
            },

            addItem() {
                if (!this.itemForm.wasteCode || !this.selectedWasteType) {
                    this.showNotification('กรุณาเลือกชนิดขยะ', 'error');
                    this.$refs.wasteCodeInput.focus();
                    return;
                }

                // (เรายอมให้กรอกค่าติดลบได้ แต่ห้ามเป็นค่าว่างหรือ 0 เฉยๆ ในตอนแรกถ้าไม่มีรายการ)
                const weightToAdd = parseFloat(this.itemForm.weight);
                if (!weightToAdd || isNaN(weightToAdd)) {
                    this.showNotification('กรุณากรอกน้ำหนัก', 'error');
                    this.$refs.weightInput.focus();
                    return;
                }

                const existingIndex = this.items.findIndex(item =>
                    item.waste_type_id === this.selectedWasteType.waste_type_id
                );

                if (existingIndex !== -1) {
                    const currentWeight = parseFloat(this.items[existingIndex].weight);
                    const newWeight = currentWeight + weightToAdd;

                    if (newWeight <= 0) {
                        // ถ้าผลลัพธ์ <= 0 ให้ลบรายการออก
                        this.items.splice(existingIndex, 1);
                        this.showNotification(`ลบรายการ ${this.selectedWasteType.waste_type_name} ออกแล้ว (น้ำหนักเหลือ ${newWeight})`, 'warning');
                    } else {
                        // ถ้ายังมีค่าบวก ให้อัปเดตค่าใหม่
                        this.items[existingIndex].weight = newWeight;
                        const action = weightToAdd > 0 ? 'เพิ่ม' : 'ลด';
                        this.showNotification(`${action}น้ำหนักเป็น ${newWeight.toFixed(2)} กก.`, 'success');
                    }

                } else {
                    // --- กรณี B: รายการใหม่ ---
                    if (weightToAdd <= 0) {
                        // ห้ามเพิ่มรายการใหม่ด้วยค่าติดลบหรือ 0
                        this.showNotification('รายการใหม่ต้องมีน้ำหนักมากกว่า 0', 'error');
                        this.$refs.weightInput.focus();
                        return;
                    }

                    this.items.push({
                        waste_category_id: this.selectedWasteType.waste_category_id,
                        waste_type_id: this.selectedWasteType.waste_type_id,
                        waste_type_name: this.selectedWasteType.waste_type_name,
                        waste_type_price: this.selectedWasteType.waste_type_price,
                        weight: weightToAdd
                    });
                    this.showNotification('เพิ่มรายการใหม่แล้ว', 'success');
                }
                this.clearItemForm();
                this.$refs.wasteCodeInput.focus();
            },

            removeItem(index) {
                this.items.splice(index, 1);
                this.showNotification('ลบรายการแล้ว', 'info');
            },

            clearItemForm() {
                this.itemForm.wasteCode = '';
                this.itemForm.weight = '';
                this.selectedWasteType = null;
                this.filteredWasteTypes = this.wasteTypes;
            },

            // --- Submission Logic ---
            async submitTransaction() {
                if (this.items.length === 0) {
                    this.showNotification('ไม่มีรายการที่จะบันทึก', 'warning');
                    return;
                }
                if (!this.currentMember) {
                    this.showNotification('กรุณาเลือกสมาชิก', 'warning');
                    return;
                }

                this.isSubmitting = true;
                try {
                    const payload = {
                        depositor_member: this.currentMember.member_id,
                        items: this.items.map(item => ({
                            waste_category_id: item.waste_category_id,
                            waste_type_id: item.waste_type_id,
                            deposit_weight: item.weight
                        }))
                    };

                    const response = await fetch('/api/waste_transactions', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload)
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'บันทึกสำเร็จ!',
                            text: `บันทึก ${result.result.items_count || this.items.length} รายการเรียบร้อย`,
                            timer: 2000,
                            showConfirmButton: false,
                        });

                        // Update stats in cookie
                        const today = new Date().toISOString().split('T')[0];
                        const newWeight = this.totalWeight;
                        this.todayStats.count += 1;
                        this.todayStats.weight += newWeight;
                        this.setCookie('todayStats', JSON.stringify({ date: today, count: this.todayStats.count, weight: this.todayStats.weight }));

                        this.items = [];
                        this.resetMember();
                    } else {
                        throw new Error(result.message || 'บางรายการบันทึกไม่สำเร็จ');
                    }
                } catch (error) {
                    console.error('Error submitting:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด!',
                        text: error.message,
                    });
                } finally {
                    this.isSubmitting = false;
                }
            },

            cancelAll() {
                if (this.items.length === 0) return;
                Swal.fire({
                    title: 'ต้องการยกเลิกทั้งหมด?',
                    text: "รายการทั้งหมดจะถูกลบและไม่สามารถกู้คืนได้",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'ใช่, ลบทั้งหมด!',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.items = [];
                        this.clearItemForm();
                        this.showNotification('ยกเลิกทั้งหมดแล้ว', 'success');
                    }
                })
            },

            loadTodayStats() {
                const statsCookie = this.getCookie('todayStats');
                const today = new Date().toISOString().split('T')[0];

                if (statsCookie) {
                    try {
                        const stats = JSON.parse(statsCookie);
                        if (stats.date === today) {
                            this.todayStats.count = stats.count;
                            this.todayStats.weight = stats.weight;
                            return;
                        }
                    } catch (e) {
                        console.error('Error parsing todayStats cookie:', e);
                    }
                }

                // If no cookie or cookie is from another day, reset stats
                this.todayStats.count = 0;
                this.todayStats.weight = 0;
                this.setCookie('todayStats', JSON.stringify({ date: today, count: 0, weight: 0 }));
            },

            showNotification(message, type = 'info') {
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });

                toast.fire({
                    icon: type,
                    title: message
                });
            }
        };
    }
</script>

<style>
    kbd {
        font-family: monospace;
        font-size: 0.85em;
    }

    [x-cloak] {
        display: none !important;
    }
</style>