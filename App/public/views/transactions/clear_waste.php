<div class="grid grid-cols-1 gap-4 mb-6">
    <div class="mb-4">
        <h2 class="text-4xl font-bold">🗑️ เคลียร์ยอดฝากขยะ</h2>
        <h2 class="text-lg font-light text-gray-500">บันทึกการเคลียร์ยอด - ป้อนข้อมูลเหมือนการขายออก</h2>
    </div>

    <!-- Clear Waste POS Form -->
    <div x-data="ClearWastePOSHandler()" x-init="init()" class="space-y-6">
        <!-- Main Form -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">📝 บันทึกการเคลียร์ยอด</h2>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end mb-4">
                <!-- Waste Type Input -->
                <div class="md:col-span-3 relative" @click.away="showTypeDropdown = false">
                    <label class="block text-sm font-medium text-gray-700 mb-1">รหัสประเภทขยะ</label>
                    <input x-ref="typeInput" x-model="typeSearch" @input.debounce.200ms="searchWasteType()"
                        @focus="showTypeDropdown = true" @keydown.enter.prevent="selectTypeEnter()"
                        @keydown.escape="showTypeDropdown = false" @keydown.arrow-down.prevent="moveTypeSelection(1)"
                        @keydown.arrow-up.prevent="moveTypeSelection(-1)"
                        type="text" placeholder="ป้อนรหัสหรือชื่อ"
                        class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
                        autocomplete="off" />
                    
                    <!-- Type dropdown -->
                    <div x-show="showTypeDropdown && (typeResults.length > 0 || isSearchingType)"
                        class="absolute top-full left-0 z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                        <div x-show="isSearchingType" class="p-2 text-center text-sm text-gray-500">🔎 กำลังค้นหา...</div>
                        <ul>
                            <template x-for="(type, index) in typeResults" :key="type.waste_type_id">
                                <li @click="selectWasteType(type)"
                                    :class="{ 'bg-emerald-100': index === selectedTypeIndex }"
                                    class="px-3 py-2 cursor-pointer hover:bg-emerald-50">
                                    <p class="font-semibold text-sm" x-text="`${type.waste_type_id} - ${type.waste_type_name}`"></p>
                                    <p class="text-xs text-gray-600" x-text="`${type.waste_category_name} | คลังนี้: ${type.stock_weight.toFixed(2)}kg`"></p>
                                </li>
                            </template>
                        </ul>
                        <div x-show="!isSearchingType && typeResults.length === 0 && typeSearch.length > 0"
                            class="p-2 text-center text-sm text-gray-500">ไม่พบข้อมูล</div>
                    </div>
                </div>

                <!-- Weight Input -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">น้ำหนัก (kg)</label>
                    <input x-model.number="weight" @keydown.enter="addToCart()" type="number" step="0.01"
                        placeholder="0.00" 
                        class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition" />
                </div>

                <!-- Add Button -->
                <div class="md:col-span-2">
                    <button @click="addToCart()" 
                        class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-4 rounded-lg transition">
                        ➕ เพิ่มรายการ
                    </button>
                </div>

                <!-- Clear Cart Button -->
                <div class="md:col-span-3">
                    <button @click="clearCart()" x-show="cart.length > 0"
                        class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-4 rounded-lg transition">
                        🗑️ ล้างรายการ
                    </button>
                </div>
            </div>

            <!-- Status Message -->
            <div x-show="selectedType" class="bg-emerald-50 border border-emerald-200 rounded-lg p-3">
                <p class="text-sm text-emerald-800">
                    <strong>ชนิด:</strong> <span x-text="selectedType?.waste_type_name"></span>
                    | <strong>คลังนี้:</strong> <span x-text="selectedType?.stock_weight?.toFixed(2)"></span>kg
                </p>
            </div>
        </div>

        <!-- Cart Items -->
        <div class="bg-white rounded-lg shadow-sm p-6" x-show="cart.length > 0">
            <h3 class="text-xl font-bold text-slate-900 mb-4">📦 รายการเคลียร์ยอด</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 border-b border-gray-300">
                        <tr>
                            <th class="px-4 py-2 text-left">ประเภท</th>
                            <th class="px-4 py-2 text-left">หมวดหมู่</th>
                            <th class="px-4 py-2 text-right">น้ำหนัก (kg)</th>
                            <th class="px-4 py-2 text-right">แต้ม/kg</th>
                            <th class="px-4 py-2 text-right">รวมแต้ม</th>
                            <th class="px-4 py-2 text-center">ลบ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template x-for="(item, idx) in cart" :key="idx">
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3" x-text="item.waste_type_name"></td>
                                <td class="px-4 py-3 text-sm text-gray-600" x-text="item.waste_category_name"></td>
                                <td class="px-4 py-3 text-right font-mono" x-text="item.weight.toFixed(2)"></td>
                                <td class="px-4 py-3 text-right" x-text="item.waste_type_point_per_kg"></td>
                                <td class="px-4 py-3 text-right font-bold text-emerald-600" x-text="(item.weight * item.waste_type_point_per_kg).toFixed(2)"></td>
                                <td class="px-4 py-3 text-center">
                                    <button @click="removeFromCart(idx)" class="text-red-500 hover:text-red-700">
                                        ✕
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <tfoot class="bg-gray-50 border-t-2 border-gray-300">
                        <tr>
                            <td colspan="2" class="px-4 py-3 font-bold text-right">รวมทั้งสิ้น</td>
                            <td class="px-4 py-3 text-right font-bold" x-text="totalWeight.toFixed(2)"></td>
                            <td class="px-4 py-3"></td>
                            <td class="px-4 py-3 text-right font-bold text-emerald-600 text-lg" x-text="totalPoints.toFixed(2)"></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Confirm Button -->
            <div class="mt-6 flex gap-4">
                <button @click="submitClearance()"
                    class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-lg transition">
                    ✅ ยืนยันการเคลียร์ยอด
                </button>
            </div>
        </div>

        <!-- Empty State -->
        <div x-show="cart.length === 0" class="bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 p-12 text-center">
            <p class="text-gray-600 text-lg">ยังไม่มีรายการ กรุณาป้อนข้อมูลด้านบน</p>
        </div>
    </div>
</div>

    <div x-data="clearanceHistory()" x-init="init()" @clearance-updated.window="fetchClearances()"
        class="bg-white rounded-lg shadow-sm p-6 space-y-4">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold">จัดการรายการเคลียร์ยอด</h2>
            <h2 class="text-lg font-light text-gray-500
            <button @click=" fetchClearances()" class="text-sm text-emerald-600 hover:underline">รีเฟรช</button>
        </div>

        <!-- Filters -->
        <div class="bg-gray-50 p-4 rounded border border-gray-100">
            <div class="w-full flex flex-row-reverse gap-4 items-end">
                <button @click="resetFilters()"
                    class="text-sm text-gray-500 hover:text-gray-700 hover:underline">ล้างตัวกรอง</button>

            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">คณะ</label>
                    <select x-model="filters.faculty" @change="fetchClearances()"
                        class="w-full text-xs bg-white px-4 py-1.5 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                        <option value="">ทั้งหมด</option>
                        <template x-for="f in faculties" :key="f.faculty_id">
                            <option :value="f.faculty_id" x-text="f.faculty_name"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">จากวันที่</label>
                    <div class="relative w-full text-gray-700">
                        <input type="date" x-model="filters.stat_date" @change="fetchClearances()"
                            class="w-full text-sm px-2 py-1.5 bg-white border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition appearance-none">

                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M7 11h2v2H7zm14-6v14c0 1.11-.89 2-2 2H5a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2h1V1h2v2h8V1h2v2h1a2 2 0 0 1 2 2M5 7h14V5H5zm14 12V9H5v10zm-4-6v-2h2v2zm-4 0v-2h2v2zm-4 2h2v2H7zm8 2v-2h2v2zm-4 0v-2h2v2z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">ถึงวันที่</label>
                   <div class="relative w-full text-gray-700">
                        <input x-ref="startDateInput" type="date" x-model="filters.end_date" @change="fetchClearances()"
                            class="w-full text-sm px-2 py-1.5 bg-white border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition appearance-none">

                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M7 11h2v2H7zm14-6v14c0 1.11-.89 2-2 2H5a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2h1V1h2v2h8V1h2v2h1a2 2 0 0 1 2 2M5 7h14V5H5zm14 12V9H5v10zm-4-6v-2h2v2zm-4 0v-2h2v2zm-4 2h2v2H7zm8 2v-2h2v2zm-4 0v-2h2v2z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-gray-700 border-b border-sky-300 bg-gradient-to-b from-sky-50 to-sky-100">
                    <tr>
                        <th class="py-2 px-4">วันที่ทำรายการ</th>
                        <th class="py-2 px-4">คณะ</th>
                        <th class="py-2 px-4">ช่วงเวลา</th>
                        <th class="py-2 px-4 text-center">สถานะ</th>
                        <th class="py-2 px-4 text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <template x-if="loading">
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-500">
                                <div class="flex justify-center items-center">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900"></div>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-if="!loading && items.length === 0">
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-500">ไม่พบข้อมูล</td>
                        </tr>
                    </template>
                    <template x-for="item in items" :key="item.waste_clearance_id">
                        <tr class="hover:bg-gray-50 hover:cursor-pointer">
                            <td class="py-3 px-4" x-text="formatDate(item.created_at)" @click="openDetail(item)"></td>
                            <td class="py-3 px-4" x-text="item.faculty_name || item.faculty_id || '-'"
                                @click="openDetail(item)"></td>
                            <td class="py-3 px-4" @click="openDetail(item)">
                                <span x-text="formatDate(item.waste_clearance_period_start)"></span> -
                                <span x-text="formatDate(item.waste_clearance_period_end)"></span>
                            </td>
                            <td class="py-3 px-4 text-center" @click="openDetail(item)">
                                <span x-show="item.waste_clearance_status === 'รอการยืนยัน'"
                                    class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">รอการยืนยัน</span>
                                <span x-show="item.waste_clearance_status === 'ยืนยันแล้ว'"
                                    class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">ยืนยันแล้ว</span>
                            </td>
                            <td class="py-3 px-4 text-center flex justify-center gap-2">
                                <button x-show="item.waste_clearance_status === 'รอการยืนยัน'"
                                    class="text-yellow-500 text-xl hover:text-yellow-700 hover:cursor-pointer"
                                    @click="openManagePage(item.waste_clearance_id)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                        <path fill="currentColor"
                                            d="M14.5 11c.28 0 .5.22.5.5V13H9v-1.5c0-.28.22-.5.5-.5zm5.5 2.55V10h-2v3.06c.69.08 1.36.25 2 .49M21 9H3V3h18zm-2-4H5v2h14zM8.85 19H6v-9H4v11h5.78c-.24-.39-.46-.81-.64-1.25zM17 18c-.56 0-1 .44-1 1s.44 1 1 1s1-.44 1-1s-.44-1-1-1m6 1c-.94 2.34-3.27 4-6 4s-5.06-1.66-6-4c.94-2.34 3.27-4 6-4s5.06 1.66 6 4m-3.5 0a2.5 2.5 0 0 0-5 0a2.5 2.5 0 0 0 5 0" />
                                    </svg>
                                </button>
                                <button x-show="item.waste_clearance_status === 'รอการยืนยัน'"
                                    class="text-red-500 text-xl hover:text-red-700 hover:cursor-pointer"
                                    @click="cancleClearance(item.waste_clearance_id)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                        <path fill="currentColor"
                                            d="M14.5 11c.28 0 .5.22.5.5V13H9v-1.5c0-.28.22-.5.5-.5zm4 1c.5 0 1 .07 1.5.18V10h-2v2.03c.17-.03.33-.03.5-.03M6 19v-9H4v11h8.5c-.26-.62-.41-1.3-.47-2zM21 9H3V3h18zm-2-4H5v2h14zm4 13.5c0 2.5-2 4.5-4.5 4.5S14 21 14 18.5s2-4.5 4.5-4.5s4.5 2 4.5 4.5m-3 2.58L15.92 17c-.27.42-.42.94-.42 1.5c0 1.66 1.34 3 3 3c.56 0 1.08-.15 1.5-.42m1.5-2.58c0-1.66-1.34-3-3-3c-.56 0-1.08.15-1.5.42L21.08 20c.27-.42.42-.94.42-1.5" />
                                    </svg>
                                </button>
                                <span x-show="item.waste_clearance_status === 'ยืนยันแล้ว'"
                                    class="text-green-600 flex justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M20 6 9 17l-5-5" />
                                    </svg>
                                </span>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Detail Dialog -->
        <dialog x-show="detailDialogShow" x-ref="detailDialog" @click.self="detailDialogShow = false"
            @close="detailDialogShow = false"
            class="fixed inset-0 mx-auto my-auto p-0 bg-transparent z-50 backdrop:bg-gray-500/50"
            x-init="$watch('detailDialogShow', value => {if (value) $refs.detailDialog.showModal();else $refs.detailDialog.close();})">
            <div class="bg-white p-6 rounded-lg shadow-xl max-w-4xl max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">รายละเอียดการเคลียร์ยอด</h3>
                        <template x-if="detailItem">
                            <p class="text-sm text-gray-600">
                                รอบ: <span x-text="formatDate(detailItem.waste_clearance_period_start)"></span> - <span
                                    x-text="formatDate(detailItem.waste_clearance_period_end)"></span>
                            </p>
                        </template>
                    </div>
                    <button @click="detailDialogShow = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                <div class="space-y-4" x-show="detailItem">
                    <div
                        class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm bg-gray-50 p-3 rounded border border-gray-100">
                        <div>
                            <span class="block text-gray-500 text-xs">วันที่ทำรายการ</span>
                            <span class="font-medium" x-text="formatDate(detailItem.created_at)"></span>
                        </div>
                        <div>
                            <span class="block text-gray-500 text-xs">คณะ</span>
                            <span class="font-medium" x-text="detailItem.faculty_name || '-'"></span>
                        </div>
                        <div>
                            <span class="block text-gray-500 text-xs">สถานะ</span>
                            <span class="font-medium" x-text="detailItem.waste_clearance_status"></span>
                        </div>
                    </div>

                    <div class="overflow-x-auto border rounded-lg">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-100 text-gray-700">
                                <tr>
                                    <th class="py-2 px-3">หมวดหมู่</th>
                                    <th class="py-2 px-3">ประเภท</th>
                                    <th class="py-2 px-3 text-right">น้ำหนัก (ระบบ)</th>
                                    <th class="py-2 px-3 text-right">น้ำหนัก (จริง)</th>
                                    <th class="py-2 px-3 text-center">สถานะ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <template x-if="detailLoading">
                                    <tr>
                                        <td colspan="5" class="text-center py-4">กำลังโหลด...</td>
                                    </tr>
                                </template>
                                <template x-if="!detailLoading && detailList.length === 0">
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-gray-500">ไม่มีรายการ</td>
                                    </tr>
                                </template>
                                <template x-for="d in detailList" :key="d.clearance_detail_id">
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-2 px-3" x-text="d.waste_category_name"></td>
                                        <td class="py-2 px-3" x-text="d.waste_type_name"></td>
                                        <td class="py-2 px-3 text-right" x-text="d.clearance_detail_transaction_weight">
                                        </td>
                                        <td class="py-2 px-3 text-right"
                                            x-text="d.clearance_detail_clearance_weight || '-'"></td>
                                        <td class="py-2 px-3 text-center"
                                            x-text="d.clearance_detail_success == 1 ? 'สำเร็จ' : 'รอ/ไม่สำเร็จ'"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </dialog>

    </div>
</div>

<script>
    function ClearWastePOSHandler() {
        return {
            // Waste Type Search
            typeSearch: '',
            typeResults: [],
            selectedType: null,
            showTypeDropdown: false,
            isSearchingType: false,
            selectedTypeIndex: -1,
            weight: '',
            
            // Cart
            cart: [],

            init() {
                this.$nextTick(() => {
                    if (this.$refs.typeInput) {
                        this.$refs.typeInput.focus();
                    }
                });
            },

            async searchWasteType() {
                if (!this.typeSearch.trim()) {
                    this.typeResults = [];
                    this.showTypeDropdown = false;
                    return;
                }

                this.isSearchingType = true;
                this.showTypeDropdown = true;

                try {
                    const res = await fetch(`/api/center_stock?search=${encodeURIComponent(this.typeSearch)}&limit=20`);
                    const data = await res.json();
                    if (data.success) {
                        this.typeResults = data.data || [];
                        this.selectedTypeIndex = -1;
                    } else {
                        this.typeResults = [];
                    }
                } catch (err) {
                    console.error('Failed to search waste types', err);
                    this.typeResults = [];
                } finally {
                    this.isSearchingType = false;
                }
            },

            moveTypeSelection(step) {
                if (!this.showTypeDropdown || this.typeResults.length === 0) return;
                this.selectedTypeIndex += step;
                if (this.selectedTypeIndex < 0) {
                    this.selectedTypeIndex = this.typeResults.length - 1;
                } else if (this.selectedTypeIndex >= this.typeResults.length) {
                    this.selectedTypeIndex = 0;
                }
            },

            selectTypeEnter() {
                if (this.selectedTypeIndex >= 0 && this.typeResults[this.selectedTypeIndex]) {
                    this.selectWasteType(this.typeResults[this.selectedTypeIndex]);
                }
            },

            selectWasteType(type) {
                this.selectedType = type;
                this.typeSearch = '';
                this.typeResults = [];
                this.showTypeDropdown = false;
                this.selectedTypeIndex = -1;
                this.$nextTick(() => {
                    document.querySelector('[placeholder="0.00"]')?.focus();
                });
            },

            addToCart() {
                if (!this.selectedType || !this.weight || parseFloat(this.weight) <= 0) {
                    Swal.fire('แจ้งเตือน', 'กรุณาเลือกประเภทขยะและป้อนน้ำหนัก', 'warning');
                    return;
                }

                if (parseFloat(this.weight) > parseFloat(this.selectedType.stock_weight)) {
                    Swal.fire('แจ้งเตือน', `น้ำหนักเกินคลังนี้ (คลังมี: ${this.selectedType.stock_weight.toFixed(2)}kg)`, 'warning');
                    return;
                }

                this.cart.push({
                    waste_type_id: this.selectedType.waste_type_id,
                    waste_type_name: this.selectedType.waste_type_name,
                    waste_category_name: this.selectedType.waste_category_name,
                    weight: parseFloat(this.weight),
                    waste_type_point_per_kg: this.selectedType.waste_type_point_per_kg || 0,
                    stock_weight: this.selectedType.stock_weight
                });

                this.selectedType = null;
                this.weight = '';
                this.typeSearch = '';
                this.$nextTick(() => {
                    if (this.$refs.typeInput) {
                        this.$refs.typeInput.focus();
                    }
                });
            },

            removeFromCart(index) {
                this.cart.splice(index, 1);
            },

            clearCart() {
                Swal.fire({
                    title: 'ยืนยันการล้างรายการ?',
                    text: "คุณแน่ใจหรือไม่ว่าต้องการลบรายการทั้งหมด?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'ใช่, ลบเลย',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.cart = [];
                        this.$nextTick(() => {
                            if (this.$refs.typeInput) {
                                this.$refs.typeInput.focus();
                            }
                        });
                    }
                });
            },

            get totalWeight() {
                return this.cart.reduce((sum, item) => sum + item.weight, 0);
            },

            get totalPoints() {
                return this.cart.reduce((sum, item) => sum + (item.weight * item.waste_type_point_per_kg), 0);
            },

            async submitClearance() {
                if (this.cart.length === 0) {
                    Swal.fire('แจ้งเตือน', 'ยังไม่มีรายการในตะกร้า', 'warning');
                    return;
                }

                const confirmation = await Swal.fire({
                    title: 'ยืนยันการเคลียร์ยอด?',
                    html: `
                        <div class="text-left text-sm">
                            <p><b>จำนวนรายการ:</b> ${this.cart.length}</p>
                            <p><b>น้ำหนักรวม:</b> ${this.totalWeight.toFixed(2)}kg</p>
                            <p><b>แต้มรวม:</b> ${this.totalPoints.toFixed(2)}</p>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'ใช่, ยืนยัน',
                    cancelButtonText: 'ยกเลิก'
                });

                if (confirmation.isConfirmed) {
                    await this.sendClearance();
                }
            },

            async sendClearance() {
                try {
                    const payload = {
                        clearance_items: this.cart.map(item => ({
                            waste_type_id: item.waste_type_id,
                            weight: item.weight
                        }))
                    };

                    const res = await fetch('/api/clearances/direct', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });

                    const data = await res.json();

                    if (res.ok) {
                        Swal.fire('สำเร็จ', data.message || 'ดำเนินการเรียบร้อย', 'success');
                        this.cart = [];
                        this.selectedType = null;
                        this.weight = '';
                        this.typeSearch = '';
                        this.$nextTick(() => {
                            if (this.$refs.typeInput) {
                                this.$refs.typeInput.focus();
                            }
                        });
                    } else {
                        throw new Error(data.message || 'เกิดข้อผิดพลาด');
                    }
                } catch (err) {
                    console.error(err);
                    Swal.fire('เกิดข้อผิดพลาด', err.message || 'ไม่สามารถส่งคำขอได้', 'error');
                }
            }
        }
    }

    function clearanceHistory() {
        return {
            // TODO: can't get 
            userRole: <?php echo json_encode($user["user_data"]->role_name ?? null) ?>,
            items: [],
            faculties: [],
            filters: {
                faculty: '',
                start_date: '',
                end_date: ''
            },
            detailDialogShow: false,
            detailItem: [],
            detailList: [],
            detailLoading: false,
            loading: false,
            async init() {
                await Promise.all([this.fetchFaculties(), this.fetchClearances()]);
            },
            async fetchFaculties() {
                try {
                    const res = await fetch('/api/faculties');
                    const data = await res.json();
                    this.faculties = data.data || [];
                } catch (err) {
                    console.error('Failed to load faculties', err);
                }
            },
            async fetchClearances() {
                this.loading = true;
                try {
                    const params = new URLSearchParams();
                    if (this.filters.faculty) params.append('faculty', this.filters.faculty);
                    if (this.filters.start_date) params.append('start_date', this.filters.start_date);
                    if (this.filters.end_date) params.append('end_date', this.filters.end_date);

                    const res = await fetch(`/api/clearances?${params.toString()}`);
                    const data = await res.json();

                    if (data.success) {
                        this.items = data.data || [];
                    }
                } catch (err) {
                    console.error(err);
                } finally {
                    this.loading = false;
                }
            },
            async openDetail(item) {
                this.detailItem = item;
                this.detailDialogShow = true;
                this.detailList = [];
                this.detailLoading = true;
                try {
                    const res = await fetch(`/api/clearances/${item.waste_clearance_id}`);
                    const result = await res.json();
                    if (result.success) {
                        this.detailList = result.data.detail || [];
                    }
                } catch (err) {
                    console.error(err);
                } finally {
                    this.detailLoading = false;
                }
            },
            openManagePage(wcid) {
                console.log(this.userRole);

                if (this.userRole === 'admin') {
                    window.location.href = `/admin/transactions/clear_waste/manage/${wcid}`;
                }
                if (this.userRole === 'center') {
                    window.location.href = `/waste_center/transactions/clear_waste/manage/${wcid}`;
                }
            },
            async cancleClearance(wcid) {
                Swal.fire({
                    title: 'ยืนยันการยกเลิก?',
                    text: "คุณแน่ใจหรือไม่ว่าต้องการยกเลิกรายการนี้?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'ใช่, ยกเลิก',
                    cancelButtonText: 'ไม่'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const res = await fetch(`/api/clearances/${wcid}`, {
                                method: 'DELETE'
                            });
                            const data = await res.json();
                            if (res.ok) {
                                Swal.fire('สำเร็จ', data.message || 'ยกเลิกรายการเรียบร้อย', 'success');
                                this.fetchClearances();
                            } else {
                                throw new Error(data.message || 'เกิดข้อผิดพลาด');
                            }
                        } catch (err) {
                            Swal.fire('เกิดข้อผิดพลาด', err.message || 'ไม่สามารถยกเลิกรายการได้', 'error');
                        }
                    }
                });
            },
            resetFilters() {
                this.filters = {
                    faculty: '',
                    start_date: '',
                    end_date: ''
                };
                this.fetchClearances();
            },
            formatDate(d) {
                if (!d) return '-';
                return new Date(d).toLocaleDateString('th-TH', {
                    day: 'numeric',
                    month: 'short',
                    year: '2-digit'
                });
            }
        }
    }
</script>