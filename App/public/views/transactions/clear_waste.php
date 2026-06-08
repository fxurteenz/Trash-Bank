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
            items: [],
            isLoadingStock: false,
            isSubmitting: false,
            todayStats: { count: 0, weight: 0 },
            itemForm: { wasteCode: '', weight: '' },
            selectedStockItem: null,

            // --- Computed ---
            get totalWeight() {
                return this.items.reduce((sum, item) => sum + parseFloat(item.clearance_weight || 0), 0);
            },
            get activeItemsCount() {
                return this.items.filter(i => parseFloat(i.clearance_weight) > 0).length;
            },
            get activeItemsList() {
                return this.items.filter(i => parseFloat(i.clearance_weight) > 0);
            },
            get totalPoints() {
                return this.items.reduce((sum, item) => {
                    const weight = parseFloat(item.clearance_weight) || 0;
                    const price = parseFloat(item.waste_type_price) || 0;
                    if (weight > 0) {
                        return sum + Math.floor((price * weight) * 6);
                    }
                    return sum;
                }, 0);
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
                await this.loadFaculties();
                this.loadTodayStats();

                const urlParams = new URLSearchParams(window.location.search);
                const facultyId = urlParams.get('faculty_id');

                if (facultyId) {
                    const faculty = this.allFaculties.find(f => f.faculty_id.toString() === facultyId);
                    if (faculty) {
                        await this.selectFaculty(faculty);
                    } else {
                        this.showNotification('ไม่พบข้อมูลคณะจาก URL', 'error');
                        this.focusFacultyInput();
                    }
                } else {
                    this.focusFacultyInput();
                }
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
                    const response = await fetch('/api/faculties?show_branch=true');
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

            async selectFaculty(faculty) {
                this.currentFaculty = faculty;
                this.facultySearch = '';
                this.searchResults = [];
                this.showDropdown = false;
                this.showNotification('เลือกคณะ: ' + faculty.faculty_name, 'success');

                await this.loadFacultyStock(faculty.faculty_id);

                setTimeout(() => {
                    if (this.$refs.wasteCodeInput) {
                        this.$refs.wasteCodeInput.focus();
                    }
                }, 100);
            },

            resetFaculty() {
                this.currentFaculty = null;
                this.items = [];
                this.clearItemForm();
                this.focusFacultyInput();
            },

            // --- Fetch Stock ---
            async loadFacultyStock(facultyId) {
                this.isLoadingStock = true;
                this.items = [];
                try {
                    const response = await fetch(`/api/faculty_stock/${facultyId}`);
                    const result = await response.json();
                    if (result.success) {
                        this.items = (result.data || []).map(item => ({
                            ...item,
                            clearance_weight: '' // สร้างฟิลด์เปล่าไว้รับค่า input
                        }));
                    }
                } catch (error) {
                    console.error('Error loading stock:', error);
                    this.showNotification('ไม่สามารถโหลดข้อมูลคลังขยะของคณะได้', 'error');
                } finally {
                    this.isLoadingStock = false;
                }
            },

            setMaxWeight(index) {
                this.items[index].clearance_weight = this.items[index].stock_weight;
            },

            clearWeight(index) {
                this.items[index].clearance_weight = '';
            },

            setAllMaxWeight() {
                this.items.forEach(item => {
                    item.clearance_weight = item.stock_weight;
                });
                this.showNotification('ระบุยอดเคลียร์ทั้งหมดที่มีในคลังแล้ว', 'success');
            },

            searchStockItem() {
                const search = this.itemForm.wasteCode.toLowerCase();
                const exactMatch = this.items.find(type =>
                    type.waste_type_id.toString().padStart(3, '0') === search ||
                    type.waste_type_id.toString() === search
                );
                this.selectedStockItem = exactMatch || null;
            },

            addClearanceItem() {
                const rawWeight = String(this.itemForm.weight);
                const weightMatch = rawWeight.match(/^-?\d+(?:\.\d{0,3})?/);
                const weightToAdd = weightMatch ? parseFloat(weightMatch[0]) : 0;
                if (!weightToAdd || isNaN(weightToAdd) || weightToAdd <= 0) {
                    this.showNotification('กรุณากรอกน้ำหนักให้ถูกต้อง (มากกว่า 0)', 'error');
                    this.$refs.weightInput.focus();
                    return;
                }

                // เลือกรหัสขยะอัตโนมัติจากคำค้นหา หากยังไม่ได้เลือกจาก Dropdown
                if (!this.selectedStockItem && this.itemForm.wasteCode) {
                    const search = this.itemForm.wasteCode.toLowerCase().trim();
                    const match = this.items.find(type =>
                        type.waste_type_id.toString().padStart(3, '0').includes(search) ||
                        type.waste_type_id.toString().includes(search) ||
                        type.waste_type_name.toLowerCase().includes(search)
                    );
                    if (match) {
                        this.selectedStockItem = match;
                        this.itemForm.wasteCode = match.waste_type_id.toString().padStart(3, '0');
                    }
                }

                if (!this.itemForm.wasteCode || !this.selectedStockItem) {
                    this.showNotification('กรุณาเลือกรหัสชนิดขยะที่มีในคลัง', 'error');
                    this.$refs.wasteCodeInput.focus();
                    return;
                }

                const itemIndex = this.items.findIndex(i => i.waste_type_id === this.selectedStockItem.waste_type_id);
                if (itemIndex === -1) return;

                const stockWeight = parseFloat(this.items[itemIndex].stock_weight);
                const currentClearance = parseFloat(this.items[itemIndex].clearance_weight || 0);
                const newClearance = parseFloat((currentClearance + weightToAdd).toFixed(3));

                if (newClearance > stockWeight) {
                    this.showNotification(`คำเตือน: น้ำหนักเคลียร์ (${newClearance} กก.) มากกว่าที่มีในคลัง (${stockWeight} กก.)`, 'warning');
                }

                this.items[itemIndex].clearance_weight = newClearance;
                this.showNotification(`เพิ่มยอดเคลียร์ ${weightToAdd} กก. แล้ว`, 'success');

                this.clearItemForm();
                this.$refs.weightInput.focus();
            },

            clearItemForm() {
                this.itemForm.wasteCode = '';
                this.itemForm.weight = '';
                this.selectedStockItem = null;
            },

            cancelAll() {
                const activeItems = this.items.filter(i => parseFloat(i.clearance_weight) > 0);
                if (activeItems.length === 0) return;
                Swal.fire({
                    title: 'ยกเลิกทั้งหมด?',
                    text: "ค่าที่กรอกไว้จะถูกเคลียร์ทิ้ง",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'ล้างค่า',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.items.forEach(item => item.clearance_weight = '');
                    }
                });
            },

            // --- Submission Logic (The core requirement) ---
            async submitClearance() {
                const activeItems = this.items.filter(i => parseFloat(i.clearance_weight) > 0);
                if (activeItems.length === 0) {
                    this.showNotification('กรุณาระบุน้ำหนักในรายการที่ต้องการเคลียร์', 'warning');
                    return;
                }
                if (!this.currentFaculty) {
                    this.showNotification('กรุณาเลือกคณะ', 'warning');
                    return;
                }

                // Confirm Dialog
                const confirm = await Swal.fire({
                    title: 'ยืนยันการเคลียร์ยอด?',
                    html: `คณะ: <b>${this.currentFaculty.faculty_name}</b><br>จำนวน: <b>${activeItems.length} รายการ</b>`,
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
                        items: activeItems.map(item => ({
                            waste_category_id: item.waste_category_id,
                            waste_type_id: item.waste_type_id,
                            clearance_weight: parseFloat(item.clearance_weight)
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
                        this.clearItemForm();
                        this.resetFaculty();
                        await this.loadFaculties(); // ดึงข้อมูลคณะและแต้มล่าสุดใหม่หลังจากบันทึกเสร็จ
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

    [x-cloak] {
        display: none !important;
    }
</style>

<div x-data="ClearWastePOSHandler()" x-init="init()" class="flex flex-col h-[calc(100vh-6rem)] gap-4">

    <div class="flex-none flex flex-col md:flex-row gap-4">
        <div class="md:w-1/3 flex flex-col justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 mb-2 flex items-center gap-3">
                    <svg class="w-8 h-8 text-amber-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M5 19V5zv-.112zm0 2q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h14q.825 0 1.413.588T21 5v7q0 .425-.288.713T20 13t-.712-.288T19 12V5H5v14h6q.425 0 .713.288T12 20t-.288.713T11 21zm12.35-1.825l3.525-3.55q.3-.3.713-.3t.712.3t.3.713t-.3.712l-4.25 4.25q-.3.3-.712.3t-.713-.3L14.5 19.175q-.275-.3-.275-.712t.3-.713t.7-.3t.7.3zM8 13q.425 0 .713-.288T9 12t-.288-.712T8 11t-.712.288T7 12t.288.713T8 13m0-4q.425 0 .713-.288T9 8t-.288-.712T8 7t-.712.288T7 8t.288.713T8 9m8 4q.425 0 .713-.288T17 12t-.288-.712T16 11h-4q-.425 0-.712.288T11 12t.288.713T12 13zm0-4q.425 0 .713-.288T17 8t-.288-.712T16 7h-4q-.425 0-.712.288T11 8t.288.713T12 9z"
                            stroke-width="0.5" stroke="currentColor" />
                    </svg>
                    <span>ระบบเคลียร์ขยะ</span>
                </h1>
                <p class="text-slate-600 text-sm">รับขยะของคณะ - ลงรายการและจ่ายแต้ม</p>
            </div>

            <!-- <div class="bg-gradient-to-r from-amber-500 to-amber-600 rounded-xl shadow-lg p-4 text-white">
                <div class="flex items-center justify-between">
                    <p class="text-amber-100 text-xs font-medium uppercase tracking-wider mb-1">ยอดเคลียร์วันนี้</p>
                    <h2 class="text-3xl font-bold flex items-center gap-2">
                            <span x-text="todayStats.weight.toFixed(3)">0</span>
                        <span class="text-sm font-normal text-amber-100 mt-2">กก.</span>
                    </h2>
                </div>
            </div> -->
        </div>

        <div class="md:w-2/3 bg-white rounded-xl shadow-md card-hover relative flex flex-col justify-center"
            x-bind:class="{ 'p-6': !currentFaculty, 'p-0': currentFaculty}">

            <div x-show="!currentFaculty" class="w-full">
                <h2 class="text-xl font-bold text-slate-900 mb-2">ระบุคณะ</h2>
                <div class="relative" @click.away="showDropdown = false">
                    <div class="flex gap-2">
                        <input x-ref="facultyInput" x-model="facultySearch" @input.debounce.300ms="searchFaculty()"
                            @focus="showDropdown = true" @keydown.enter.prevent="handleFacultyEnter()"
                            @keydown.escape="showDropdown = false" @keydown.arrow-down.prevent="moveSelection(1)"
                            @keydown.tab.prevent="moveSelection(1)" @keydown.arrow-up.prevent="moveSelection(-1)"
                            type="text" placeholder="พิมพ์ชื่อคณะ หรือ รหัสคณะ..."
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition text-lg"
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
            </div>

            <div x-show="currentFaculty" class="w-full h-full">
                <div
                    class="relative bg-gradient-to-br from-amber-50 to-amber-100 border-2 border-amber-300 rounded-lg p-4 h-full flex flex-col justify-center">

                    <button @click="resetFaculty()"
                        class="absolute top-2 right-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-full transition-colors p-1 z-10">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="m12 13.4l2.9 2.9q.275.275.7.275t.7-.275t.275-.7t-.275-.7L13.4 12l2.9-2.9q.275-.275.275-.7t-.275-.7t-.7-.275t-.7.275L12 10.6L9.1 7.7q-.275-.275-.7-.275t-.7.275t-.275.7t.275.7l2.9 2.9l-2.9 2.9q-.275.275-.275.7t.275.7t.7.275t.7-.275zm0 8.6q-2.075 0-3.9-.788t-3.175-2.137T2.788 15.9T2 12t.788-3.9t2.137-3.175T8.1 2.788T12 2t3.9.788t3.175 2.137T21.213 8.1T22 12t-.788 3.9t-2.137 3.175t-3.175 2.138T12 22m0-2q3.35 0 5.675-2.325T20 12t-2.325-5.675T12 4T6.325 6.325T4 12t2.325 5.675T12 20m0-8"
                                stroke-width="0.5" stroke="currentColor" />
                        </svg>
                    </button>

                    <div class="flex items-end justify-between pr-6">
                        <div>
                            <p class="text-xs font-bold text-amber-700 uppercase tracking-wider mb-1">คณะที่เลือก</p>
                            <h2 class="text-2xl font-bold text-slate-900 mb-1" x-text="currentFaculty?.faculty_name">
                            </h2>
                            <p class="text-xs text-slate-500">รหัส: <span x-text="currentFaculty?.faculty_id"></span>
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold text-amber-700 uppercase tracking-wider mb-1">แต้มคณะคงเหลือ</p>
                            <p class="text-3xl font-bold text-slate-800"
                                x-text="Number(currentFaculty?.faculty_point || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})">
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex-1 min-h-0 grid grid-cols-1 lg:grid-cols-3 gap-6 overflow-hidden pb-1">

        <div class="lg:col-span-2 flex flex-col gap-4 h-full overflow-hidden">

            <div x-show="currentFaculty" class="flex-none bg-white rounded-xl shadow-md p-6 card-hover z-10"
                style="display: none;">
                <h2 class="text-xl font-bold text-slate-900 mb-3">ระบุขยะที่ต้องการเคลียร์</h2>
                <div class="grid grid-cols-12 gap-3">
                    <div class="col-span-5">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">รหัสชนิดขยะ</label>
                        <input x-ref="wasteCodeInput" x-model="itemForm.wasteCode"
                            @keydown.tab.prevent="$refs.weightInput.focus()" @keydown.enter="$refs.weightInput.focus()"
                            @input="searchStockItem()" type="text" placeholder="พิมพ์รหัสขยะที่มีในคลัง"
                            class="w-full px-4 py-2 border-2 border-slate-300 rounded-lg focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition"
                            list="stockItemList" autocomplete="off">
                        <datalist id="stockItemList">
                            <template x-for="item in items" :key="item.waste_type_id">
                                <option :value="item.waste_type_id.toString().padStart(3, '0')"
                                    :label="`${item.waste_category_name} : ${item.waste_type_name} (คลัง: ${Number(item.stock_weight).toFixed(3)} กก.)`">
                                </option>
                            </template>
                        </datalist>
                        <p class="text-[10px] text-amber-600 mt-1 font-medium truncate"
                            x-text="selectedStockItem ? `${selectedStockItem.waste_category_name} : ${selectedStockItem.waste_type_name} (มีในคลัง ${Number(selectedStockItem.stock_weight).toFixed(3)} กก.)`:  'ระบุรหัสชนิดขยะเพื่อตรวจสอบสต็อก'">
                        </p>
                    </div>
                    <div class="col-span-4">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">น้ำหนักเคลียร์ (กก.)</label>
                        <input x-ref="weightInput" x-model="itemForm.weight" @keydown.enter="addClearanceItem()"
                            @keydown.tab.prevent="addClearanceItem()" type="number" step="0.001" placeholder="0.000"
                            class="w-full px-4 py-2 border-2 border-slate-300 rounded-lg focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition">
                    </div>

                    <div class="col-span-3 flex items-center">
                        <button @click="addClearanceItem()"
                            class="w-full px-4 py-2 mb-[2px] bg-amber-600 hover:bg-amber-700 text-white rounded-lg font-semibold transition-colors h-[42px]">
                            เพิ่มยอดเคลียร์
                        </button>
                    </div>
                </div>
            </div>

            <div x-show="currentFaculty" class="flex-1 min-h-0 bg-white rounded-xl shadow-xl p-6 flex flex-col"
                style="display: none;">

                <div class="mb-2 shrink-0 flex items-center justify-between">
                    <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                        <span>รายการขยะในคลัง</span>

                    </h2>
                    <button x-show="items.length > 0" @click="setAllMaxWeight()"
                        class="text-xs px-3 py-1.5 bg-sky-100 text-sky-700 rounded-md hover:bg-sky-200 transition-colors font-medium shadow-sm cursor-pointer">
                        ทั้งหมด
                    </button>
                </div>

                <div x-ref="listContainer" class="flex-1 min-h-0 overflow-y-auto pr-2 custom-scrollbar">
                    <div x-show="isLoadingStock" class="p-8 text-center text-slate-500">
                        <span class="inline-block animate-spin mr-2">⏳</span> กำลังโหลดข้อมูลคลังขยะ...
                    </div>

                    <table x-show="!isLoadingStock && items.length > 0" class="w-full text-sm text-left text-slate-600">
                        <thead class="text-xs text-slate-700 uppercase bg-slate-100 sticky top-0 z-10 shadow-sm">
                            <tr>
                                <th scope="col" class="px-4 py-3 rounded-tl-lg">รหัสขยะ</th>
                                <th scope="col" class="px-4 py-3">ชนิดขยะ</th>
                                <th scope="col" class="px-4 py-3 text-right">ในคลัง (กก.)</th>
                                <th scope="col" class="px-4 py-3 text-right">ยอดเคลียร์ (กก.)</th>
                                <th scope="col" class="px-4 py-3 text-center rounded-tr-lg w-16">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(item, index) in items" :key="index">
                                <tr class="border-b last:border-b-0 transition-colors group"
                                    :class="parseFloat(item.clearance_weight) > 0 ? 'bg-amber-50 hover:bg-amber-100' : 'bg-white hover:bg-slate-50'">
                                    <td class="px-4 py-3 font-medium text-slate-900"
                                        x-text="item.waste_type_id.toString().padStart(3, '0')"></td>
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-slate-800"
                                            x-text="`${item.waste_category_name} : ${item.waste_type_name}`"></div>
                                        <!-- <div class="text-xs text-slate-500" x-text="item.waste_category_name"></div> -->
                                    </td>
                                    <td class="px-4 py-3 text-right font-bold text-amber-700"
                                        x-text="Number(item.stock_weight).toFixed(3)"></td>
                                    <td class="px-4 py-3 text-right font-bold text-emerald-600"
                                        x-text="parseFloat(item.clearance_weight) > 0 ? Number(item.clearance_weight).toFixed(3) : '-'">
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button x-show="parseFloat(item.clearance_weight) > 0"
                                            @click="clearWeight(index)"
                                            class="p-1.5 text-red-500 hover:bg-red-100 rounded-md transition-colors hover:bg-red-50 cursor-pointer"
                                            title="ลบยอดเคลียร์">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                viewBox="0 0 24 24">
                                                <path fill="currentColor"
                                                    d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>

                    <div x-show="!isLoadingStock && items.length === 0"
                        class="h-full flex flex-col items-center justify-center text-slate-400 opacity-60">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-2" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z" />
                        </svg>
                        <p class="text-sm">ไม่มีขยะค้างอยู่ในคลังของคณะนี้</p>
                    </div>
                </div>
            </div>

            <div x-show="!currentFaculty"
                class="flex-1 min-h-0 flex flex-col items-center justify-center bg-slate-50 rounded-xl border-2 border-dashed border-slate-300 text-slate-400">
                <svg class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M19.3 14.9c.4-.7.7-1.5.7-2.4C20 10 18 8 15.5 8S11 10 11 12.5s2 4.5 4.5 4.5c.9 0 1.7-.3 2.4-.7l2.9 2.9l1.4-1.4zm-3.8.1c-1.4 0-2.5-1.1-2.5-2.5s1.1-2.5 2.5-2.5s2.5 1.1 2.5 2.5s-1.1 2.5-2.5 2.5m-.8 3.9c-.4.4-.8.7-1.2 1.1L12 21.3L10.5 20C5.4 15.4 2 12.3 2 8.5C2 5.4 4.4 3 7.5 3c1.7 0 3.4.8 4.5 2.1C13.1 3.8 14.8 3 16.5 3C19.6 3 22 5.4 22 8.5c0 .7-.1 1.3-.3 2c-.9-2.6-3.3-4.5-6.2-4.5C11.9 6 9 8.9 9 12.5c0 3.3 2.5 6 5.7 6.4"
                        stroke-width="0.5" stroke="currentColor" />
                </svg></span>
                <p class="text-lg">กรุณาเลือกคณะก่อนทำรายการ</p>
            </div>
        </div>

        <div class="h-full rounded-xl shadow-md">
            <div
                class="h-full bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl p-6 text-white flex flex-col justify-between overflow-y-auto custom-scrollbar">

                <div>
                    <h3 class="text-xl font-bold mb-4 flex items-center gap-2 border-b border-amber-400 pb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M19 3h-4.18C14.25 1.44 12.53.64 11 1.2c-.86.3-1.5.96-1.82 1.8H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2m-7 0a1 1 0 0 1 1 1a1 1 0 0 1-1 1a1 1 0 0 1-1-1a1 1 0 0 1 1-1M7 7h10V5h2v14H5V5h2zm10 4H7V9h10zm-2 4H7v-2h8z"
                                stroke-width="0.5" stroke="currentColor" />
                        </svg>
                        สรุปรายการ
                    </h3>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-amber-100 text-lg">จำนวนรายการ</span>
                            <span class="text-2xl font-bold" x-text="activeItemsCount"></span>
                        </div>
                        <div class="h-px bg-amber-400 opacity-50"></div>
                        <div class="flex justify-between items-center">
                            <span class="text-amber-100 text-lg">น้ำหนักรวม</span>
                            <div class="text-right">
                                <span class="text-2xl font-bold" x-text="totalWeight.toFixed(3)"></span>
                                <span class="text-sm text-amber-200">กก.</span>
                            </div>
                        </div>

                        <div class="bg-black/20 rounded-xl p-4 mt-4 backdrop-blur-sm">
                            <p class="text-amber-100 text-sm mb-1">คะแนนที่จะได้รับ</p>
                            <p class="text-3xl font-bold tracking-tight text-white"
                                x-text="totalPoints.toLocaleString()"></p>
                        </div>

                    </div>
                </div>

                <div class="space-y-3">
                    <button @click="submitClearance()" :disabled="activeItemsCount === 0 || isSubmitting"
                        :class="activeItemsCount === 0 || isSubmitting ? 'bg-amber-800/50 cursor-not-allowed text-amber-200' : 'bg-white hover:bg-amber-50 text-amber-700 shadow-lg transform hover:-translate-y-0.5'"
                        class="w-full px-6 py-4 rounded-xl font-bold text-xl transition-all duration-200 flex items-center justify-center gap-2">
                        <span x-show="!isSubmitting">ยืนยันการเคลียร์</span>
                        <span x-show="isSubmitting" class="flex items-center gap-2">⏳ กำลังบันทึก...</span>
                    </button>
                    <button @click="cancelAll()" :disabled="activeItemsCount === 0"
                        class="w-full px-6 py-3 bg-red-500/20 hover:bg-red-500/30 text-white rounded-xl font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed border border-white/10">
                        ยกเลิกทั้งหมด
                    </button>
                    <p class="text-center text-amber-200 text-xs mt-2 opacity-70">กด <kbd
                            class="bg-amber-800/50 px-2 py-1 rounded text-white border border-amber-600/50">Ctrl+Enter</kbd>
                        เพื่อบันทึก</p>
                </div>
            </div>
        </div>
    </div>
    <div @keydown.ctrl.enter.window="submitClearance()"></div>
</div>