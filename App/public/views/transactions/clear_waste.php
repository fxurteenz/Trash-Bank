<div x-data="ClearWastePOSHandler()" x-init="init()" class="space-y-4 h-full">
    <div class="flex flex-col md:flex-row gap-4 h-[20%]">
        <div class="md:w-1/3">
            <h1 class="text-3xl font-bold text-slate-900 mb-2 flex items-center gap-3">
                <svg class="w-8 h-8"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M5 19V5zv-.112zm0 2q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h14q.825 0 1.413.588T21 5v7q0 .425-.288.713T20 13t-.712-.288T19 12V5H5v14h6q.425 0 .713.288T12 20t-.288.713T11 21zm12.35-1.825l3.525-3.55q.3-.3.713-.3t.712.3t.3.713t-.3.712l-4.25 4.25q-.3.3-.712.3t-.713-.3L14.5 19.175q-.275-.3-.275-.712t.3-.713t.7-.3t.7.3zM8 13q.425 0 .713-.288T9 12t-.288-.712T8 11t-.712.288T7 12t.288.713T8 13m0-4q.425 0 .713-.288T9 8t-.288-.712T8 7t-.712.288T7 8t.288.713T8 9m8 4q.425 0 .713-.288T17 12t-.288-.712T16 11h-4q-.425 0-.712.288T11 12t.288.713T12 13zm0-4q.425 0 .713-.288T17 8t-.288-.712T16 7h-4q-.425 0-.712.288T11 8t.288.713T12 9z"
                                stroke-width="0.5" stroke="currentColor" />
                        </svg>
                <span>ระบบเคลียร์ขยะ</span>
            </h1>
            <p class="text-slate-600 text-md">รับขยะของคณะ - ลงรายการและจ่ายแต้ม</p>
        </div>

        <div class="bg-white rounded-xl shadow-md card-hover relative md:w-2/3"
            x-bind:class="{ 'p-6': !currentFaculty, 'p-0': currentFaculty}">

            <h2 class="text-xl font-bold text-slate-900" x-show="!currentFaculty">
                <span>ระบุคณะ</span>
            </h2>

            <div x-show="!currentFaculty" class="relative mt-2" @click.away="showDropdown = false">
                <div class="flex gap-2">
                    <input x-ref="facultyInput" x-model="facultySearch" @input.debounce.300ms="searchFaculty()"
                        @focus="showDropdown = true" @keydown.enter.prevent="handleFacultyEnter()"
                        @keydown.escape="showDropdown = false" @keydown.arrow-down.prevent="moveSelection(1)"
                        @keydown.tab.prevent="moveSelection(1)" @keydown.arrow-up.prevent="moveSelection(-1)"
                        type="text" placeholder="พิมพ์ชื่อคณะ หรือ รหัสคณะ..."
                        class="flex-1 px-3 py-3 border-2 border-slate-300 rounded-lg focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition"
                        autocomplete="off">
                </div>

                <div x-show="showDropdown && (searchResults.length > 0 || isSearching)"
                    x-transition.opacity.duration.200ms
                    class="absolute top-full left-0 z-50 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-xl max-h-80 overflow-y-auto">
                    <ul x-show="!isSearching && searchResults.length > 0">
                        <template x-for="(faculty, index) in searchResults" :key="faculty.faculty_id">
                            <li @click="selectFaculty(faculty)" :id="'faculty-item-' + index"
                                :class="{ 'bg-amber-100 ring-1 ring-inset ring-amber-300': index === selectedIndex, 'hover:bg-amber-50': index !== selectedIndex }"
                                class="px-4 py-3 cursor-pointer border-b border-slate-100 last:border-0 transition-colors group">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-bold text-slate-800 group-hover:text-amber-700"
                                            x-text="faculty.faculty_name"></p>
                                        <p class="text-xs text-slate-500">รหัส: <span
                                                x-text="faculty.faculty_id"></span></p>
                                    </div>
                                </div>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>

            <div x-show="currentFaculty"
                class="bg-gradient-to-br from-amber-50 to-amber-100 border-2 border-amber-300 rounded-lg p-3 h-full flex flex-col justify-center">
                <div class="flex gap-4 text-sm text-slate-600 items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-amber-700 uppercase tracking-wider">คณะที่เลือก</p>
                        <p class="text-2xl font-bold text-slate-900" x-text="currentFaculty?.faculty_name"></p>
                        <p class="text-xs text-slate-500">รหัส: <span x-text="currentFaculty?.faculty_id"></span></p>
                    </div>
                    <button @click="resetFaculty()" class="text-xs text-red-600 hover:underline">เปลี่ยน</button>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sticky h-[80%]">
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-xl shadow-md p-6 card-hover z-10">
                <h2 class="text-2xl font-bold text-slate-900 mb-4">เพิ่มรายการเคลียร์ขยะ</h2>
                <div class="grid grid-cols-12 gap-3">
                    <div class="col-span-5">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">รหัสชนิดขยะ</label>
                        <input x-ref="wasteCodeInput" x-model="itemForm.wasteCode"
                            @keydown.tab.prevent="$refs.weightInput.focus()" @keydown.enter="handleWasteCodeEnter()"
                            @input="searchWasteType()" type="text" placeholder="พิมพ์รหัส/ชื่อ"
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition"
                            list="wasteTypeList" autocomplete="off">
                        <datalist id="wasteTypeList">
                            <template x-for="type in filteredWasteTypes" :key="type.waste_type_id">
                                <option :value="type.waste_type_id"
                                    :label="`${type.waste_category_name} : ${type.waste_type_name}`"></option>
                            </template>
                        </datalist>
                        <p class="text-xs text-amber-600 mt-2 font-medium"
                            x-text="selectedWasteType ? `${selectedWasteType?.waste_category_name} : ${selectedWasteType?.waste_type_name}`:  'ระบุรหัสชนิดขยะ'">
                        </p>
                    </div>
                    <div class="col-span-4">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">น้ำหนัก (กก.)</label>
                        <input x-ref="weightInput" x-model="itemForm.weight" @keydown.enter="addItem()"
                            @keydown.tab.prevent="addItem(); $refs.wasteCodeInput.focus()" type="number" step="0.01"
                            placeholder="0.00"
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition">
                    </div>
                    <div class="col-span-3 my-auto">
                        <button @click="addItem()"
                            class="w-full px-4 py-3 bg-amber-600 hover:bg-amber-700 text-white rounded-lg font-semibold transition-colors">
                            เพิ่ม
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 h-90 flex flex-col" x-init="$watch('items', value => {
                $nextTick(() => { const container = $refs.listContainer; container.scrollTop = container.scrollHeight; });
            })">
                <h2 class="text-2xl font-bold text-slate-900 mb-4 shrink-0">
                    รายการที่บันทึก <span class="text-amber-600" x-text="items.length"></span>
                </h2>
                <div x-ref="listContainer" class="space-y-1 flex-1 min-h-0 overflow-y-auto pr-2">
                    <template x-for="(item, index) in items" :key="index">
                        <div
                            class="flex items-center justify-between p-2 bg-slate-50 hover:bg-slate-100 rounded-lg transition group">
                            <div class="flex items-center gap-2">
                                <span class="text-lg text-slate-400 w-8 text-center" x-text="index + 1 +'.'"></span>
                                <div>
                                    <span class="font-light text-slate-900" x-text="item.waste_type_id"></span> :
                                    <span class="font-semibold text-slate-900" x-text="item.waste_type_name"></span>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="text-xl font-medium text-amber-600"
                                    x-text="item.weight.toFixed(2) + ' กก.'"></span>
                                <button @click="removeItem(index)"
                                    class="px-3 py-2 text-sm bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition-all">
                                    ลบ
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl shadow-lg p-6 text-white sticky top-8">
            <h3 class="text-lg font-bold mb-5 flex items-center gap-2">สรุปรายการเคลียร์</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-amber-100">จำนวนรายการ</span>
                    <span class="text-3xl font-bold" x-text="items.length"></span>
                </div>
                <div class="h-px bg-amber-400 opacity-50"></div>
                <div class="flex justify-between items-center">
                    <span class="text-amber-100">น้ำหนักรวม</span>
                    <span class="text-2xl font-bold" x-text="totalWeight.toFixed(2) + ' กก.'"></span>
                </div>
            </div>
            <div class="mt-6 space-y-3">
                <button @click="submitClearance()" :disabled="items.length === 0 || isSubmitting"
                    class="w-full px-6 py-4 rounded-lg font-bold text-lg transition-colors bg-white hover:bg-slate-50 text-amber-600 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!isSubmitting">ยืนยันการเคลียร์</span>
                    <span x-show="isSubmitting">กำลังบันทึก...</span>
                </button>
                <button @click="cancelAll()" :disabled="items.length === 0"
                    class="w-full px-6 py-3 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    ยกเลิกทั้งหมด
                </button>
            </div>
            <p class="text-center text-amber-100 text-xs mt-4">กด <kbd
                    class="bg-amber-700 px-2 py-1 rounded">Ctrl+Enter</kbd> เพื่อบันทึก</p>
        </div>
    </div>
    <div @keydown.ctrl.enter.window="submitClearance()"></div>
</div>

<script>
    function ClearWastePOSHandler() {
        return {
            // --- Faculty Search State ---
            facultySearch: '',
            allFaculties: [],
            searchResults: [],
            currentFaculty: null,
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

            // --- Cookie Helpers (Reused) ---
            setCookie(name, value) {
                const date = new Date();
                date.setHours(23, 59, 59, 999);
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
                await Promise.all([this.loadWasteTypes(), this.loadFaculties()]);
                this.loadTodayStats();
                this.focusFacultyInput();
            },

            focusFacultyInput() {
                this.$nextTick(() => {
                    if (this.$refs.facultyInput) {
                        this.$refs.facultyInput.focus();
                    }
                });
            },

            // --- Faculty Logic ---
            async loadFaculties() {
                try {
                    const response = await fetch('/api/faculties');
                    const result = await response.json();
                    if (result.success || Array.isArray(result.data)) {
                        this.allFaculties = result.data || [];
                    }
                } catch (error) {
                    console.error('Error loading faculties:', error);
                    this.showNotification('ไม่สามารถโหลดข้อมูลคณะได้', 'error');
                }
            },

            searchFaculty() {
                if (!this.facultySearch.trim()) {
                    this.searchResults = [];
                    this.showDropdown = false;
                    this.selectedIndex = -1;
                    return;
                }

                this.isSearching = true;
                this.showDropdown = true;
                this.selectedIndex = -1;

                // Client-side filtering
                const search = this.facultySearch.toLowerCase();
                setTimeout(() => {
                    this.searchResults = this.allFaculties.filter(f =>
                        f.faculty_name.toLowerCase().includes(search) ||
                        f.faculty_id.toString().includes(search)
                    );
                    this.isSearching = false;
                }, 100); // Simulate tiny delay/Debounce
            },

            handleFacultyEnter() {
                if (this.selectedIndex >= 0 && this.searchResults[this.selectedIndex]) {
                    this.selectFaculty(this.searchResults[this.selectedIndex]);
                } else if (this.searchResults.length === 1) {
                    this.selectFaculty(this.searchResults[0]);
                }
            },

            moveSelection(step) {
                if (!this.showDropdown || this.searchResults.length === 0) return;
                this.selectedIndex += step;
                if (this.selectedIndex >= this.searchResults.length) this.selectedIndex = 0;
                else if (this.selectedIndex < 0) this.selectedIndex = this.searchResults.length - 1;

                this.$nextTick(() => {
                    const el = document.getElementById('faculty-item-' + this.selectedIndex);
                    if (el) el.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
                });
            },

            selectFaculty(faculty) {
                this.currentFaculty = faculty;
                this.facultySearch = '';
                this.searchResults = [];
                this.showDropdown = false;
                this.showNotification('เลือกคณะ: ' + faculty.faculty_name, 'success');
                this.$nextTick(() => {
                    if (this.$refs.wasteCodeInput) this.$refs.wasteCodeInput.focus();
                });
            },

            resetFaculty() {
                this.currentFaculty = null;
                this.items = [];
                this.clearItemForm();
                this.focusFacultyInput();
            },

            // --- Waste Type Logic ---
            async loadWasteTypes() {
                try {
                    // ใช้ endpoint เดียวกับที่ใช้ load ปกติ
                    const response = await fetch('/api/waste_types');
                    const result = await response.json();
                    if (result.success) {
                        this.wasteTypes = result.data || [];
                        this.filteredWasteTypes = this.wasteTypes;
                    }
                } catch (error) {
                    console.error('Error loading waste types:', error);
                }
            },

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

            // --- Cart Logic ---
            addItem() {
                if (!this.itemForm.wasteCode || !this.selectedWasteType) {
                    this.showNotification('กรุณาเลือกชนิดขยะ', 'error');
                    this.$refs.wasteCodeInput.focus();
                    return;
                }

                const weightToAdd = parseFloat(this.itemForm.weight);
                if (!weightToAdd || weightToAdd <= 0) {
                    this.showNotification('กรุณากรอกน้ำหนักที่ถูกต้อง (> 0)', 'error');
                    this.$refs.weightInput.focus();
                    return;
                }

                // Check duplicate item
                const existingIndex = this.items.findIndex(item =>
                    item.waste_type_id === this.selectedWasteType.waste_type_id
                );

                if (existingIndex !== -1) {
                    // Add to existing
                    this.items[existingIndex].weight += weightToAdd;
                    this.showNotification(`อัปเดตน้ำหนักเป็น ${this.items[existingIndex].weight.toFixed(2)} กก.`, 'success');
                } else {
                    // Add new
                    this.items.push({
                        waste_category_id: this.selectedWasteType.waste_category_id,
                        waste_category_name: this.selectedWasteType.waste_category_name, // for display
                        waste_type_id: this.selectedWasteType.waste_type_id,
                        waste_type_name: this.selectedWasteType.waste_type_name, // for display
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

            cancelAll() {
                if (this.items.length === 0) return;
                Swal.fire({
                    title: 'ยกเลิกทั้งหมด?',
                    text: "รายการทั้งหมดจะถูกลบ",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'ลบทั้งหมด',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.items = [];
                        this.clearItemForm();
                    }
                });
            },

            // --- Submission Logic (The core requirement) ---
            async submitClearance() {
                if (this.items.length === 0) {
                    this.showNotification('ไม่มีรายการที่จะบันทึก', 'warning');
                    return;
                }
                if (!this.currentFaculty) {
                    this.showNotification('กรุณาเลือกคณะ', 'warning');
                    return;
                }

                // Confirm Dialog
                const confirm = await Swal.fire({
                    title: 'ยืนยันการเคลียร์ยอด?',
                    html: `คณะ: <b>${this.currentFaculty.faculty_name}</b><br>จำนวน: <b>${this.items.length} รายการ</b>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'ยืนยัน',
                    cancelButtonText: 'ยกเลิก'
                });

                if (!confirm.isConfirmed) return;

                this.isSubmitting = true;

                try {
                    // ** Construct payload strictly as requested **
                    const payload = {
                        faculty_id: this.currentFaculty.faculty_id,
                        items: this.items.map(item => ({
                            waste_category_id: item.waste_category_id,
                            waste_type_id: item.waste_type_id,
                            clearance_weight: parseFloat(item.weight)
                        }))
                    };

                    // Use appropriate endpoint
                    const response = await fetch('/api/clearances', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload)
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'บันทึกสำเร็จ!',
                            timer: 2000,
                            showConfirmButton: false,
                        });

                        // Update stats
                        const today = new Date().toISOString().split('T')[0];
                        this.todayStats.count += 1;
                        this.todayStats.weight += this.totalWeight;
                        this.setCookie('clearanceStats', JSON.stringify({ date: today, count: this.todayStats.count, weight: this.todayStats.weight }));

                        this.items = [];
                        this.resetFaculty();
                    } else {
                        throw new Error(result.message || 'บันทึกไม่สำเร็จ');
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

            loadTodayStats() {
                const statsCookie = this.getCookie('clearanceStats');
                const today = new Date().toISOString().split('T')[0];

                if (statsCookie) {
                    try {
                        const stats = JSON.parse(statsCookie);
                        if (stats.date === today) {
                            this.todayStats.count = stats.count;
                            this.todayStats.weight = stats.weight;
                            return;
                        }
                    } catch (e) { }
                }
                this.todayStats.count = 0;
                this.todayStats.weight = 0;
                this.setCookie('clearanceStats', JSON.stringify({ date: today, count: 0, weight: 0 }));
            },

            showNotification(message, type = 'info') {
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                toast.fire({ icon: type, title: message });
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