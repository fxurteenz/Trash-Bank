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

            // --- Init ---
            async init() {
                await this.loadWasteTypes();
                this.focusMemberInput();
            },

            get formatPoints() {
                if (this.currentMember && this.currentMember.member_waste_point) {
                    return this.currentMember.member_waste_point + ' แต้ม';
                }
                return 0;
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
                    setTimeout(() => {
                        this.isSearching = false;
                    }, 150);
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

            showNotification(message, type = 'info') {
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1500,
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

<div x-data="WasteDepositPOSHandler()" x-init="init()" class="flex flex-col h-[calc(100vh-6rem)] gap-4">

    <div class="flex-none flex flex-col md:flex-row gap-4">
        <div class="md:w-1/3 flex flex-col justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 mb-2 flex items-center gap-3">
                    <svg class="w-8 h-8 text-emerald-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="m12 18l4-4l-1.4-1.4l-1.6 1.6V10h-2v4.2l-1.6-1.6L8 14zM5 8v11h14V8zm0 13q-.825 0-1.412-.587T3 19V6.525q0-.35.113-.675t.337-.6L4.7 3.725q.275-.35.687-.538T6.25 3h11.5q.45 0 .863.188t.687.537l1.25 1.525q.225.275.338.6t.112.675V19q0 .825-.587 1.413T19 21zm.4-15h13.2l-.85-1H6.25zm6.6 7.5"
                            stroke-width="0.5" stroke="currentColor" />
                    </svg>
                    <span>ระบบฝากขยะ</span>
                </h1>
                <p class="text-slate-600 text-sm">บันทึกการฝากขยะ - ค้นหาสมาชิกและลงรายการ</p>
            </div>
            <!-- <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl shadow-lg p-4 text-white">
                <div class="flex items-center justify-between">
                    <p class="text-emerald-100 text-xs font-medium uppercase tracking-wider mb-1">แต้มสะสมของคณะ</p>
                    <h2 class="text-3xl font-bold flex items-center gap-2">
                        9,999
                        <span class="text-sm font-normal text-emerald-100 mt-2">แต้ม</span>
                    </h2>
                </div>
            </div> -->
        </div>

        <div class="md:w-2/3 bg-white rounded-xl shadow-md card-hover relative flex flex-col justify-center transition-all duration-300"
            x-bind:class="{ 'p-6': !currentMember, 'p-0': currentMember}">
            <div x-show="!currentMember" class="w-full">
                <h2 class="text-xl font-bold text-slate-900 mb-2">ค้นหาสมาชิก</h2>
                <div class="relative" @click.away="showDropdown = false">
                    <div class="relative">
                        <input x-ref="memberInput" x-model="memberSearch" @input.debounce.300ms="searchMember(false)"
                            @focus="showDropdown = true" @keydown.enter.prevent="handleEnterKey()"
                            @keydown.escape="showDropdown = false" @keydown.arrow-down.prevent="moveSelection(1)"
                            @keydown.tab.prevent="moveSelection(1)" @keydown.arrow-up.prevent="moveSelection(-1)"
                            type="text" placeholder="กรอกเบอร์โทร หรือ ชื่อสมาชิก..."
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition text-lg"
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
                                    :class="{ 'bg-emerald-100 ring-1 ring-inset ring-emerald-300': index === selectedIndex, 'hover:bg-emerald-50': index !== selectedIndex }"
                                    class="px-4 py-3 cursor-pointer border-b border-slate-100 last:border-0 transition-colors group">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="font-bold text-slate-800 group-hover:text-emerald-700"
                                                x-text="member.member_name"></p>
                                            <p class="text-xs text-slate-500"><span x-text="member.faculty_name"></span>
                                            </p>
                                        </div>
                                        <span class="text-xs font-mono bg-slate-100 px-2 py-1 rounded text-slate-600"
                                            x-text="member.member_phone"></span>
                                    </div>
                                </li>
                            </template>
                        </ul>
                        <div x-show="!isSearching && searchResults.length === 0 && memberSearch.length > 0"
                            class="p-4 text-center text-slate-500">ไม่พบข้อมูล</div>
                    </div>
                </div>
                <!-- <p class="text-xs text-slate-500 mt-2">
                    กด <kbd class="bg-slate-100 px-1 rounded border">Enter</kbd>
                    เพื่อเลือก
                </p> -->
            </div>

            <div x-show="currentMember" class="w-full h-full">
                <div
                    class="relative bg-gradient-to-br from-emerald-50 to-emerald-100 border-2 border-emerald-300 rounded-lg p-4 h-full flex flex-col justify-center">
                    <button @click="resetMember()"
                        class="absolute top-2 right-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-full transition-colors p-1 z-10">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="m325.297 256l134.148-134.148c19.136-19.136 19.136-50.161 0-69.297c-19.137-19.136-50.16-19.136-69.297 0L256 186.703L121.852 52.555c-19.136-19.136-50.161-19.136-69.297 0s-19.136 50.161 0 69.297L186.703 256L52.555 390.148c-19.136 19.136-19.136 50.161 0 69.297c9.568 9.567 22.108 14.352 34.648 14.352s25.081-4.784 34.648-14.352L256 325.297l134.148 134.148c9.568 9.567 22.108 14.352 34.648 14.352s25.08-4.784 34.648-14.352c19.136-19.136 19.136-50.161 0-69.297z"
                                stroke-width="13" stroke="currentColor" />
                        </svg>
                    </button>
                    <div class="flex items-end justify-between pr-6">
                        <div>
                            <p class="text-xs font-bold text-emerald-700 uppercase tracking-wider mb-1">สมาชิกผู้ฝาก</p>
                            <h2 class="text-2xl font-bold text-slate-900 mb-1"
                                x-text="currentMember?.member_name || '-'"></h2>
                            <div class="flex gap-3 text-sm text-slate-600">
                                <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                        </path>
                                    </svg><span x-text="currentMember?.member_phone"></span></span>
                                <span class="text-slate-300">|</span>
                                <span x-text="currentMember?.faculty_name"></span>
                            </div>
                        </div>
                        <div class="text-center bg-white/60 p-2 px-3 rounded-lg shadow-sm border border-emerald-100">
                            <p class="text-[10px] text-slate-500 mb-0">แต้มสะสม</p>
                            <span class="text-2xl font-bold text-emerald-600" x-text="formatPoints"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex-1 min-h-0 grid grid-cols-1 lg:grid-cols-3 gap-6 overflow-hidden pb-2 px-2">

        <div class="lg:col-span-2 flex flex-col gap-4 h-full overflow-hidden">

            <div class="flex-none bg-white rounded-xl shadow-md p-6 card-hover z-10">
                <h2 class="text-xl font-bold text-slate-900 mb-3">เพิ่มรายการขยะ</h2>
                <div class="grid grid-cols-12 gap-3">
                    <div class="col-span-5">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">รหัสชนิดขยะ</label>
                        <input x-ref="wasteCodeInput" x-model="itemForm.wasteCode"
                            @keydown.tab.prevent="$refs.weightInput.focus()" @keydown.enter="handleWasteCodeEnter()"
                            @input="searchWasteType()" type="text" placeholder="พิมพ์รหัส/ชื่อ"
                            class="w-full px-4 py-2 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                            list="wasteTypeList" autocomplete="off">
                        <datalist id="wasteTypeList">
                            <template x-for="type in filteredWasteTypes" :key="type.waste_type_id">
                                <option :value="type.waste_type_id"
                                    :label="`${type.waste_category_name} : ${type.waste_type_name}`"></option>
                            </template>
                        </datalist>
                        <p class="text-[10px] text-emerald-600 mt-1 font-medium truncate"
                            x-text="selectedWasteType ? `${selectedWasteType?.waste_category_name} : ${selectedWasteType?.waste_type_name}`:  'ระบุรหัสชนิดขยะ'">
                        </p>
                    </div>
                    <div class="col-span-4">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">น้ำหนัก (กก.)</label>
                        <input x-ref="weightInput" x-model="itemForm.weight" @keydown.enter="addItem()"
                            @keydown.tab.prevent="addItem(); $refs.wasteCodeInput.focus()" type="number" step="0.01"
                            placeholder="0.00"
                            class="w-full px-4 py-2 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                    </div>
                    <div class="col-span-3 flex items-center">
                        <button @click="addItem()"
                            class="w-full px-4 py-2 mb-[2px] bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors h-[42px]">
                            เพิ่ม
                        </button>
                    </div>
                </div>
            </div>

            <div x-show="currentMember" class="flex-1 min-h-0 bg-white rounded-xl shadow-xl p-6 flex flex-col"
                x-init="$watch('items', value => { $nextTick(() => { const container = $refs.listContainer; container.scrollTop = container.scrollHeight; }); })">

                <h2 class="text-xl font-bold text-slate-900 mb-2 shrink-0 flex items-center justify-between">
                    <span>รายการที่บันทึก</span>
                    <span class="bg-blue-100 text-blue-700 text-sm px-2 py-1 rounded-md"
                        x-text="items.length + ' รายการ'"></span>
                </h2>

                <div x-ref="listContainer" class="flex-1 min-h-0 overflow-y-auto pr-2 space-y-2 custom-scrollbar">
                    <template x-for="(item, index) in items" :key="index">
                        <div
                            class="flex items-center justify-between p-3 bg-slate-50 hover:bg-slate-100 rounded-lg transition group border border-slate-100">
                            <div class="flex items-center gap-3">
                                <span
                                    class="flex items-center justify-center w-6 h-6 rounded-full bg-slate-200 text-xs font-bold text-slate-500"
                                    x-text="index + 1"></span>
                                <div>
                                    <p class="font-semibold text-slate-900 text-sm" x-text="item.waste_type_name"></p>
                                    <p class="text-xs text-slate-500" x-text="item.waste_type_id"></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="text-lg font-bold text-slate-700"
                                    x-text="item.weight.toFixed(2) + ' กก.'"></span>
                                <button @click="removeItem(index)"
                                    class="p-1.5 text-red-500 hover:bg-red-100 rounded-md transition-colors opacity-0 group-hover:opacity-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24">
                                        <path fill="currentColor"
                                            d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>

                    <div x-show="items.length === 0"
                        class="h-full flex flex-col items-center justify-center text-slate-400 opacity-60">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-2" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z" />
                        </svg>
                        <p class="text-sm">เพิ่มรายการขยะจากฟอร์มด้านบน</p>
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

        <div class="h-full rounded-xl shadow-md overflow-hidden">
            <div
                class="h-full bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl p-6 text-white flex flex-col justify-between overflow-y-auto custom-scrollbar">
                <div>
                    <h3 class="text-xl font-bold mb-4 flex items-center gap-2 border-b border-emerald-400 pb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M19 3h-4.18C14.25 1.44 12.53.64 11 1.2c-.86.3-1.5.96-1.82 1.8H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2m-7 0a1 1 0 0 1 1 1a1 1 0 0 1-1 1a1 1 0 0 1-1-1a1 1 0 0 1 1-1M7 7h10V5h2v14H5V5h2zm10 4H7V9h10zm-2 4H7v-2h8z"
                                stroke-width="0.5" stroke="currentColor" />
                        </svg>
                        สรุปรายการ
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-emerald-100 text-lg">จำนวนรายการ</span>
                            <span class="text-2xl font-bold" x-text="items.length"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-emerald-100 text-lg">น้ำหนักรวม</span>
                            <div class="text-right">
                                <span class="text-2xl font-bold" x-text="totalWeight.toFixed(2)"></span>
                                <span class="text-sm text-emerald-200">กก.</span>
                            </div>
                        </div>
                        <div class="bg-black/20 rounded-xl p-4 mt-4 backdrop-blur-sm">
                            <p class="text-emerald-100 text-sm mb-1">คะแนนที่จะได้รับ</p>
                            <p class="text-3xl font-bold tracking-tight text-white" x-text="totalPoints.toFixed(0)"></p>
                        </div>
                    </div>
                </div>
                <div class="space-y-3">
                    <button @click="submitTransaction()" :disabled="items.length === 0 || isSubmitting"
                        :class="items.length === 0 || isSubmitting ? 'bg-emerald-800/50 cursor-not-allowed text-emerald-200' : 'bg-white hover:bg-emerald-50 text-emerald-700 shadow-lg transform hover:-translate-y-0.5'"
                        class="w-full px-6 py-4 rounded-xl font-bold text-xl transition-all duration-200 flex items-center justify-center gap-2">
                        <span x-show="!isSubmitting">บันทึกทั้งหมด</span>
                        <span x-show="isSubmitting" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            กำลังบันทึก...
                        </span>
                    </button>
                    <button @click="cancelAll()" :disabled="items.length === 0"
                        class="w-full px-6 py-3 bg-red-500/20 hover:bg-red-500/30 text-white rounded-xl font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed border border-white/10">
                        ยกเลิกทั้งหมด
                    </button>
                    <p class="text-center text-emerald-200 text-xs mt-2 opacity-70">
                        กด <kbd
                            class="bg-emerald-800/50 px-2 py-1 rounded text-white border border-emerald-600/50">Ctrl+Enter</kbd>
                        เพื่อบันทึก
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div @keydown.ctrl.enter.window="submitTransaction()"></div>
</div>

<style>
    /* Custom Scrollbar สำหรับรายการ */
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



<style>
    kbd {
        font-family: monospace;
        font-size: 0.85em;
    }

    [x-cloak] {
        display: none !important;
    }
</style>