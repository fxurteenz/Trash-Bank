<div x-data="WasteSalePOSHandler()" x-init="init()" class="space-y-6 relative">
    <div class="mb-8 flex flex-col md:flex-row gap-6">
        <div class="w-1/3">
            <h1 class="text-4xl font-bold text-slate-900 mb-2">ระบบจำหน่ายขยะ</h1>
            <p class="text-slate-600 text-lg">บันทึกการขายขยะ - ระบุผู้ซื้อและลงรายการ</p>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 card-hover relative w-2/3">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold text-slate-900">
                    <span x-show="!buyerConfirmed">ระบุผู้ซื้อ</span>
                    <span x-show="buyerConfirmed" class="text-blue-600">ยืนยันผู้ซื้อ</span>
                </h2>
                <button x-show="buyerConfirmed" @click="resetBuyer()"
                    class="px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                    เปลี่ยน
                </button>
            </div>

            <div x-show="!buyerConfirmed" class="space-y-3 relative">
                <label class="block text-sm font-semibold text-slate-700">ชื่อผู้รับซื้อ / บริษัท</label>
                <div class="flex gap-2">
                    <input x-ref="buyerInput" x-model="buyerName" @keydown.enter.prevent="confirmBuyer()" type="text"
                        placeholder="กรอกชื่อผู้รับซื้อ..."
                        class="flex-1 px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                        autocomplete="off">
                    <button @click="confirmBuyer()"
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold shadow-sm transition-all">
                        ยืนยัน
                    </button>
                </div>
                <p class="text-xs text-slate-500">
                    กด <kbd class="bg-slate-100 px-2 py-1 rounded">Enter</kbd> เพื่อยืนยัน
                </p>
            </div>

            <div x-show="buyerConfirmed"
                class="bg-gradient-to-br from-blue-50 to-blue-100 border-2 border-blue-300 rounded-lg p-5">
                <div class="space-y-2">
                    <p class="text-xs font-semibold text-blue-700 uppercase tracking-wider">ผู้รับซื้อ</p>
                    <div class="flex items-center gap-3">
                        <div class="bg-white p-2 rounded-full shadow-sm text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-slate-900" x-text="buyerName"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">

            <div x-show="buyerConfirmed" class="bg-white rounded-xl shadow-md p-6 card-hover z-10">
                <h2 class="text-2xl font-bold text-slate-900 mb-5">เพิ่มรายการขาย</h2>
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
                        <p class="text-xs text-blue-600 mt-2 font-medium"
                            x-text="selectedWasteType ? `${selectedWasteType?.waste_category_name} : ${selectedWasteType?.waste_type_name}`:  'ระบุรหัสชนิดขยะ'">
                        </p>
                    </div>

                    <div class="col-span-3">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">น้ำหนัก (กก.)</label>
                        <input x-ref="weightInput" x-model="itemForm.weight" @keydown.enter="$refs.priceInput.focus()"
                            @keydown.tab.prevent="$refs.priceInput.focus()" type="number" step="0.01" placeholder="0.00"
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                    </div>

                    <div class="col-span-4">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">ราคาขาย (บาท)</label>
                        <div class="flex gap-2">
                            <input x-ref="priceInput" x-model="itemForm.price" @keydown.enter="addItem()"
                                @keydown.tab.prevent="addItem()" type="number" step="0.01" placeholder="0.00"
                                class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                            <button @click="addItem()"
                                class="px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors">
                                เพิ่ม
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <div x-show="items.length > 0" class="bg-white rounded-xl shadow-md p-6 z-0">
                <h2 class="text-2xl font-bold text-slate-900 mb-4">รายการที่จะขาย <span class="text-blue-600"
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
                                        <p class="text-sm text-slate-600">
                                            <span
                                                x-text="'น้ำหนัก: ' + parseFloat(item.weight).toFixed(2) + ' กก.'"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right mr-4">
                                <p class="text-lg font-bold text-blue-600"
                                    x-text="parseFloat(item.price).toFixed(2) + ' ฿'"></p>
                            </div>
                            <button @click="removeItem(index)"
                                class="ml-2 px-3 py-2 text-sm bg-red-100 hover:bg-red-200 text-red-700 rounded-lg opacity-0 group-hover:opacity-100 transition-all">
                                ลบ
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white sticky top-8">
                <h3 class="text-lg font-bold mb-5 flex items-center gap-2">
                    สรุปการขาย
                </h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-blue-100">จำนวนรายการ</span>
                        <span class="text-3xl font-bold" x-text="items.length"></span>
                    </div>
                    <div class="h-px bg-blue-400 opacity-50"></div>
                    <div class="flex justify-between items-center">
                        <span class="text-blue-100">น้ำหนักรวม</span>
                        <span class="text-2xl font-bold" x-text="totalWeight.toFixed(2) + ' กก.'"></span>
                    </div>
                    <div class="h-px bg-blue-400 opacity-50"></div>
                    <div class="bg-blue-700 rounded-lg p-4 mt-4">
                        <p class="text-blue-100 text-sm mb-1">ยอดเงินรวมสุทธิ</p>
                        <p class="text-4xl font-bold" x-text="totalPrice.toFixed(2) + ' ฿'"></p>
                    </div>
                </div>
                <div class="mt-6 space-y-3">
                    <button @click="submitTransaction()" :disabled="items.length === 0 || isSubmitting"
                        :class="items.length === 0 || isSubmitting ? 'bg-blue-800 opacity-50 cursor-not-allowed' : 'bg-white hover:bg-slate-50 text-blue-600'"
                        class="w-full px-6 py-4 rounded-lg font-bold text-lg transition-colors">
                        <span x-show="!isSubmitting">บันทึกการขาย</span>
                        <span x-show="isSubmitting">กำลังบันทึก...</span>
                    </button>
                    <button @click="cancelAll()" :disabled="items.length === 0"
                        class="w-full px-6 py-3 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        ยกเลิกทั้งหมด
                    </button>
                </div>
                <p class="text-center text-blue-100 text-xs mt-4">กด <kbd
                        class="bg-blue-700 px-2 py-1 rounded">Ctrl+Enter</kbd> เพื่อบันทึก</p>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">สถิติวันนี้</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-600">ทำรายการขาย</span>
                        <span class="font-bold text-slate-900" x-text="todayStats.count + ' ครั้ง'"></span>
                    </div>
                    <div class="h-px bg-slate-200"></div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-600">ยอดเงินรวม</span>
                        <span class="font-bold text-slate-900" x-text="todayStats.money.toFixed(2) + ' ฿'"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div @keydown.ctrl.enter.window="submitTransaction()"></div>
</div>

<script>
    function WasteSalePOSHandler() {
        return {
            // --- Buyer State ---
            buyerName: '',
            buyerConfirmed: false,

            // --- Transaction State ---
            itemForm: { wasteCode: '', weight: '', price: '' },
            wasteTypes: [],
            filteredWasteTypes: [],
            selectedWasteType: null,
            items: [],
            isSubmitting: false,
            todayStats: { count: 0, money: 0 },

            // --- Computed ---
            get totalWeight() {
                return this.items.reduce((sum, item) => sum + parseFloat(item.weight || 0), 0);
            },

            get totalPrice() {
                return this.items.reduce((sum, item) => sum + parseFloat(item.price || 0), 0);
            },

            // --- Cookie Helpers ---
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
                await this.loadWasteTypes();
                this.loadTodayStats();
                this.focusBuyerInput();
            },

            focusBuyerInput() {
                this.$nextTick(() => {
                    if (this.$refs.buyerInput) {
                        this.$refs.buyerInput.focus();
                    }
                });
            },

            // --- Buyer Logic ---
            confirmBuyer() {
                if (!this.buyerName.trim()) {
                    this.showNotification('กรุณาระบุชื่อผู้ซื้อ', 'error');
                    this.$refs.buyerInput.focus();
                    return;
                }
                this.buyerConfirmed = true;
                this.showNotification(`ผู้ซื้อ: ${this.buyerName}`, 'success');
                this.$nextTick(() => {
                    if (this.$refs.wasteCodeInput) {
                        this.$refs.wasteCodeInput.focus();
                    }
                });
            },

            resetBuyer() {
                this.buyerConfirmed = false;
                this.items = [];
                this.clearItemForm();
                this.buyerName = '';
                this.focusBuyerInput();
            },

            // --- Data Loading ---
            async loadWasteTypes() {
                try {
                    const response = await fetch('/api/waste_types'); // หรือ /api/center_stock ถ้าต้องการดึงเฉพาะที่มี แต่โจทย์บอกไม่ต้องเช็คคลัง
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
                // Validation
                if (!this.itemForm.wasteCode || !this.selectedWasteType) {
                    this.showNotification('กรุณาเลือกชนิดขยะ', 'error');
                    this.$refs.wasteCodeInput.focus();
                    return;
                }

                const weightVal = parseFloat(this.itemForm.weight);
                const priceVal = parseFloat(this.itemForm.price);

                if (!weightVal || weightVal <= 0) {
                    this.showNotification('กรุณากรอกน้ำหนักให้ถูกต้อง', 'error');
                    this.$refs.weightInput.focus();
                    return;
                }

                if (isNaN(priceVal) || priceVal < 0) {
                    this.showNotification('กรุณากรอกราคาให้ถูกต้อง', 'error');
                    this.$refs.priceInput.focus();
                    return;
                }

                // Add Item
                this.items.push({
                    waste_category_id: this.selectedWasteType.waste_category_id,
                    waste_type_id: this.selectedWasteType.waste_type_id,
                    waste_type_name: this.selectedWasteType.waste_type_name,
                    weight: weightVal,
                    price: priceVal
                });

                this.showNotification('เพิ่มรายการแล้ว', 'success');
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
                this.itemForm.price = '';
                this.selectedWasteType = null;
                this.filteredWasteTypes = this.wasteTypes;
            },

            // --- Submission Logic ---
            async submitTransaction() {
                if (this.items.length === 0) {
                    this.showNotification('ไม่มีรายการที่จะบันทึก', 'warning');
                    return;
                }
                if (!this.buyerConfirmed) {
                    this.showNotification('กรุณายืนยันผู้ซื้อ', 'warning');
                    return;
                }

                this.isSubmitting = true;
                try {
                    // Construct Payload as requested
                    const payload = {
                        waste_sale_buyer: this.buyerName,
                        items: this.items.map(item => ({
                            waste_type_id: item.waste_type_id,
                            waste_sale_detail_weight: parseFloat(item.weight),
                            waste_sale_detail_price: parseFloat(item.price)
                        }))
                    };

                    const response = await fetch('/api/waste_sales', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload)
                    });

                    const result = await response.json();

                    if (response.ok && result.success) { // เช็ค property ตาม response จริงของ backend คุณ
                        Swal.fire({
                            icon: 'success',
                            title: 'บันทึกการขายสำเร็จ!',
                            text: `บันทึก ${this.items.length} รายการเรียบร้อย`,
                            timer: 2000,
                            showConfirmButton: false,
                        });

                        // Update stats
                        const today = new Date().toISOString().split('T')[0];
                        this.todayStats.count += 1;
                        this.todayStats.money += this.totalPrice;
                        this.setCookie('saleStats', JSON.stringify({ date: today, count: this.todayStats.count, money: this.todayStats.money }));

                        this.items = [];
                        this.resetBuyer();
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

            cancelAll() {
                if (this.items.length === 0) return;
                Swal.fire({
                    title: 'ต้องการยกเลิกทั้งหมด?',
                    text: "รายการทั้งหมดจะถูกลบ",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'ใช่, ลบทั้งหมด',
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
                const statsCookie = this.getCookie('saleStats');
                const today = new Date().toISOString().split('T')[0];

                if (statsCookie) {
                    try {
                        const stats = JSON.parse(statsCookie);
                        if (stats.date === today) {
                            this.todayStats.count = stats.count;
                            this.todayStats.money = stats.money;
                            return;
                        }
                    } catch (e) { console.error(e); }
                }
                this.todayStats.count = 0;
                this.todayStats.money = 0;
                this.setCookie('saleStats', JSON.stringify({ date: today, count: 0, money: 0 }));
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