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
            centerStock: [],
            isLoadingStock: false,
            isSubmitting: false,
            todayStats: { count: 0, money: 0 },

            // --- Computed ---
            get totalWeight() {
                return this.items.reduce((sum, item) => sum + parseFloat(item.weight || 0), 0);
            },

            get totalPrice() {
                return this.items.reduce((sum, item) => sum + parseFloat(item.price || 0), 0);
            },

            getItemSellWeight(wasteTypeId) {
                return this.items.filter(i => i.waste_type_id == wasteTypeId).reduce((sum, i) => sum + parseFloat(i.weight), 0);
            },

            getItemSellPrice(wasteTypeId) {
                return this.items.filter(i => i.waste_type_id == wasteTypeId).reduce((sum, i) => sum + parseFloat(i.price), 0);
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
            async confirmBuyer() {
                if (!this.buyerName.trim()) {
                    this.showNotification('กรุณาระบุชื่อผู้ซื้อ', 'error');
                    this.$refs.buyerInput.focus();
                    return;
                }
                this.buyerConfirmed = true;
                this.showNotification(`ผู้ซื้อ: ${this.buyerName}`, 'success');
                await this.loadCenterStock();
                setTimeout(() => {
                    if (this.$refs.wasteCodeInput) {
                        this.$refs.wasteCodeInput.focus();
                    }
                }, 100);
            },

            resetBuyer() {
                this.buyerConfirmed = false;
                this.items = [];
                this.clearItemForm();
                this.buyerName = '';
                this.focusBuyerInput();
            },

            // --- Data Loading ---
            async loadCenterStock() {
                this.isLoadingStock = true;
                try {
                    const response = await fetch('/api/center_stock');
                    const result = await response.json();
                    if (result.success) {
                        this.centerStock = result.data || [];
                        this.wasteTypes = this.centerStock.map(item => ({
                            waste_type_id: item.waste_type_id,
                            waste_type_name: item.waste_type_name,
                            waste_category_name: item.waste_category_name,
                            stock_weight: parseFloat(item.stock_weight || 0)
                        }));
                        this.filteredWasteTypes = this.wasteTypes;
                    }
                } catch (error) {
                    console.error('Error loading center stock:', error);
                    this.showNotification('ไม่สามารถโหลดข้อมูลคลังศูนย์ได้', 'error');
                } finally {
                    this.isLoadingStock = false;
                }
            },

            // --- Item Form Logic ---
            searchWasteType() {
                const search = this.itemForm.wasteCode.toLowerCase();
                this.filteredWasteTypes = this.wasteTypes.filter(type =>
                    type.waste_type_id.toString().padStart(3, '0').includes(search) ||
                    type.waste_type_id.toString().includes(search) ||
                    type.waste_type_name.toLowerCase().includes(search)
                );

                const exactMatch = this.wasteTypes.find(type =>
                    type.waste_type_id.toString().padStart(3, '0') === this.itemForm.wasteCode ||
                    type.waste_type_id.toString() === this.itemForm.wasteCode
                );
                this.selectedWasteType = exactMatch || null;
            },

            handleWasteCodeEnter() {
                if (!this.selectedWasteType && this.itemForm.wasteCode) {
                    const search = this.itemForm.wasteCode.toLowerCase().trim();
                    const match = this.wasteTypes.find(type =>
                        type.waste_type_id.toString().padStart(3, '0').includes(search) ||
                        type.waste_type_id.toString().includes(search) ||
                        type.waste_type_name.toLowerCase().includes(search)
                    );
                    if (match) {
                        this.selectedWasteType = match;
                        this.itemForm.wasteCode = match.waste_type_id.toString().padStart(3, '0');
                    }
                }

                if (this.selectedWasteType) {
                    this.$refs.weightInput.focus();
                } else {
                    this.showNotification('ไม่พบชนิดขยะที่ระบุ', 'warning');
                }
            },

            addItem() {
                if (!this.selectedWasteType && this.itemForm.wasteCode) {
                    const search = this.itemForm.wasteCode.toLowerCase().trim();
                    const match = this.wasteTypes.find(type =>
                        type.waste_type_id.toString().padStart(3, '0').includes(search) ||
                        type.waste_type_id.toString().includes(search) ||
                        type.waste_type_name.toLowerCase().includes(search)
                    );
                    if (match) {
                        this.selectedWasteType = match;
                        this.itemForm.wasteCode = match.waste_type_id.toString().padStart(3, '0');
                    }
                }

                // Validation
                if (!this.itemForm.wasteCode || !this.selectedWasteType) {
                    this.showNotification('กรุณาเลือกชนิดขยะ', 'error');
                    this.$refs.wasteCodeInput.focus();
                    return;
                }

                const rawWeight = String(this.itemForm.weight);
                const weightMatch = rawWeight.match(/^-?\d+(?:\.\d{0,3})?/);
                const weightVal = weightMatch ? parseFloat(weightMatch[0]) : 0;
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

                // ตรวจสอบน้ำหนักกับคลังศูนย์
                const stockItem = this.centerStock.find(s => s.waste_type_id == this.selectedWasteType.waste_type_id);
                const availableStock = stockItem ? parseFloat(stockItem.stock_weight) : 0;
                const alreadyAdded = this.items.filter(i => i.waste_type_id == this.selectedWasteType.waste_type_id).reduce((sum, i) => sum + parseFloat(i.weight), 0);

                if ((weightVal + alreadyAdded) > availableStock) {
                    this.showNotification(`คำเตือน: น้ำหนักขายรวม (${(weightVal + alreadyAdded).toFixed(3)} กก.) มากกว่าที่มีในคลัง (${availableStock.toFixed(3)} กก.)`, 'warning');
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

<div x-data="WasteSalePOSHandler()" x-init="init()" class="flex flex-col h-[calc(100vh-6rem)] gap-4">

    <div class="flex-none flex flex-col md:flex-row gap-4">
        <div class="md:w-1/3 flex flex-col justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 mb-2 flex items-center gap-3">
                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" />
                    </svg>
                    <span>ระบบจำหน่ายขยะ</span>
                </h1>
                <p class="text-slate-600 text-sm">จำหน่ายขยะ - ลงรายการและจ่ายแต้ม</p>
            </div>
        </div>

        <div class="md:w-2/3 bg-white rounded-xl shadow-md card-hover relative flex flex-col justify-center"
            x-bind:class="{ 'p-6': !buyerConfirmed, 'p-0': buyerConfirmed}">

            <div x-show="!buyerConfirmed" class="w-full">
                <h2 class="text-xl font-bold text-slate-900 mb-2">ระบุผู้ซื้อ</h2>
                <div class="relative mt-2">
                    <div class="flex gap-2">
                        <input x-ref="buyerInput" x-model="buyerName" @keydown.enter.prevent="confirmBuyer()"
                            type="text" placeholder="กรอกชื่อผู้รับซื้อ / บริษัท"
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition text-lg"
                            autocomplete="off">
                        <button @click="confirmBuyer()"
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold shadow-sm transition-all whitespace-nowrap">
                            ยืนยัน
                        </button>
                    </div>
                    <!-- <p class="text-xs text-slate-500 mt-2">
                        กด <kbd class="bg-slate-100 px-2 py-1 rounded">Enter</kbd> เพื่อยืนยัน
                    </p> -->
                </div>
            </div>

            <div x-show="buyerConfirmed" class="w-full h-full">
                <div
                    class="relative bg-gradient-to-br from-blue-50 to-blue-100 border-2 border-blue-300 rounded-lg p-4 h-full flex flex-col justify-center">
                    <button @click="resetBuyer()"
                        class="absolute top-2 right-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-full transition-colors p-1 z-10">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="m12 13.4l2.9 2.9q.275.275.7.275t.7-.275t.275-.7t-.275-.7L13.4 12l2.9-2.9q.275-.275.275-.7t-.275-.7t-.7-.275t-.7.275L12 10.6L9.1 7.7q-.275-.275-.7-.275t-.7.275t-.275.7t.275.7l2.9 2.9l-2.9 2.9q-.275.275-.275.7t.275.7t.7.275t.7-.275zm0 8.6q-2.075 0-3.9-.788t-3.175-2.137T2.788 15.9T2 12t.788-3.9t2.137-3.175T8.1 2.788T12 2t3.9.788t3.175 2.137T21.213 8.1T22 12t-.788 3.9t-2.137 3.175t-3.175 2.138T12 22m0-2q3.35 0 5.675-2.325T20 12t-2.325-5.675T12 4T6.325 6.325T4 12t2.325 5.675T12 20m0-8"
                                stroke-width="0.5" stroke="currentColor" />
                        </svg>
                    </button>

                    <div class="flex items-end justify-between pr-6">
                        <div>
                            <p class="text-xs font-semibold text-blue-700 uppercase tracking-wider mb-1">ผู้รับซื้อ</p>
                            <h2 class="text-2xl font-bold text-slate-900" x-text="buyerName"></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex-1 min-h-0 grid grid-cols-1 lg:grid-cols-3 gap-6 overflow-hidden pb-1">

        <div class="lg:col-span-2 flex flex-col gap-4 h-full overflow-hidden">

            <div x-show="buyerConfirmed" style="display: none;"
                class="flex-none bg-white rounded-xl shadow-md p-6 card-hover z-10">
                <h2 class="text-xl font-bold text-slate-900 mb-3">เพิ่มรายการขาย</h2>
                <div class="grid grid-cols-12 gap-3">
                    <div class="col-span-5">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">รหัสชนิดขยะ</label>
                        <input x-ref="wasteCodeInput" x-model="itemForm.wasteCode"
                            @keydown.tab.prevent="handleWasteCodeEnter()" @keydown.enter="handleWasteCodeEnter()"
                            @input="searchWasteType()" type="text" placeholder="พิมพ์รหัส/ชื่อ"
                            class="w-full px-4 py-2 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                            list="wasteTypeList" autocomplete="off">
                        <datalist id="wasteTypeList">
                            <template x-for="type in filteredWasteTypes" :key="type.waste_type_id">
                                <option :value="type.waste_type_id.toString().padStart(3, '0')"
                                    :label="`${type.waste_category_name} : ${type.waste_type_name} (คลัง: ${type.stock_weight.toFixed(3)} กก.)`">
                                </option>
                            </template>
                        </datalist>
                        <p class="text-[10px] text-blue-600 mt-1 font-medium truncate"
                            x-text="selectedWasteType ? `${selectedWasteType?.waste_category_name} : ${selectedWasteType?.waste_type_name} (มีในคลัง ${selectedWasteType?.stock_weight.toFixed(3)} กก.)`:  'ระบุรหัสชนิดขยะที่มีในคลัง'">
                        </p>
                    </div>

                    <div class="col-span-3">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">น้ำหนัก (กก.)</label>
                        <input x-ref="weightInput" x-model="itemForm.weight" @keydown.enter="$refs.priceInput.focus()"
                            @keydown.tab.prevent="$refs.priceInput.focus()" type="number" step="0.001"
                            placeholder="0.000"
                            class="w-full px-4 py-2 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                    </div>

                    <div class="col-span-4">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">ราคาขาย (บาท)</label>
                        <div class="flex gap-2">
                            <input x-ref="priceInput" x-model="itemForm.price" @keydown.enter="addItem()"
                                @keydown.tab.prevent="addItem()" type="number" step="0.01" placeholder="0.00"
                                class="w-full px-4 py-2 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                            <button @click="addItem()"
                                class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors whitespace-nowrap">
                                เพิ่ม
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="buyerConfirmed" style="display: none;" class="flex-1 min-h-0 flex flex-col">

                <!-- Center Stock Table -->
                <div class="bg-white rounded-xl shadow-xl p-6 flex flex-col min-h-full">
                    <h2 class="text-xl font-bold text-slate-900 mb-2 shrink-0 flex items-center justify-between">
                        <span>📦 คลังขยะศูนย์</span>
                        <span x-show="isLoadingStock" class="text-xs text-slate-500 animate-pulse">กำลังโหลด...</span>
                    </h2>
                    <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
                        <table x-show="centerStock.length > 0" class="w-full text-sm text-left text-slate-600">
                            <thead class="text-xs text-slate-700 uppercase bg-slate-100 sticky top-0 z-10 shadow-sm">
                                <tr>
                                    <th scope="col" class="px-4 py-3 rounded-tl-lg">รหัส</th>
                                    <th scope="col" class="px-4 py-3">ชนิดขยะ</th>
                                    <th scope="col" class="px-4 py-3 text-right">คงเหลือ (กก.)</th>
                                    <th scope="col" class="px-4 py-3 text-right text-blue-700">ขาย (กก.)</th>
                                    <th scope="col" class="px-4 py-3 text-right text-amber-700 rounded-tr-lg">ราคา (฿)
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(stock, index) in centerStock" :key="index">
                                    <tr class="border-b last:border-b-0 transition-colors"
                                        :class="getItemSellWeight(stock.waste_type_id) > 0 ? 'bg-blue-50 hover:bg-blue-100' : 'bg-white hover:bg-slate-50'">
                                        <td class="px-4 py-3 font-medium text-slate-900"
                                            x-text="stock.waste_type_id.toString().padStart(3, '0')"></td>
                                        <td class="px-4 py-3">
                                            <div class="font-semibold text-slate-800 truncate"
                                                x-text="stock.waste_type_name"></div>
                                            <div class="text-[10px] text-slate-500 truncate"
                                                x-text="stock.waste_category_name"></div>
                                        </td>
                                        <td class="px-4 py-3 text-right font-bold text-emerald-600"
                                            x-text="parseFloat(stock.stock_weight).toFixed(3)"></td>
                                        <td class="px-4 py-3 text-right font-bold text-blue-600"
                                            x-text="getItemSellWeight(stock.waste_type_id) > 0 ? parseFloat(getItemSellWeight(stock.waste_type_id)).toFixed(3) : '-'">
                                        </td>
                                        <td class="px-4 py-3 text-right font-bold text-amber-600"
                                            x-text="getItemSellPrice(stock.waste_type_id) > 0 ? parseFloat(getItemSellPrice(stock.waste_type_id)).toFixed(2) : '-'">
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                        <div x-show="!isLoadingStock && centerStock.length === 0"
                            class="h-full flex flex-col items-center justify-center text-slate-400 opacity-60">
                            <p class="text-sm">ไม่มีขยะในคลังศูนย์</p>
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="!buyerConfirmed"
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
                <p class="text-lg">กรุณาระบุผู้ซื้อก่อนทำรายการ</p>
            </div>
        </div>

        <div class="h-full rounded-xl shadow-md overflow-hidden">
            <div
                class="h-full bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white flex flex-col justify-between overflow-y-auto custom-scrollbar">

                <div>
                    <h3 class="text-xl font-bold mb-4 flex items-center gap-2 border-b border-blue-400 pb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M19 3h-4.18C14.25 1.44 12.53.64 11 1.2c-.86.3-1.5.96-1.82 1.8H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2m-7 0a1 1 0 0 1 1 1a1 1 0 0 1-1 1a1 1 0 0 1-1-1a1 1 0 0 1 1-1M7 7h10V5h2v14H5V5h2zm10 4H7V9h10zm-2 4H7v-2h8z"
                                stroke-width="0.5" stroke="currentColor" />
                        </svg>
                        สรุปรายการ
                    </h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-blue-100 text-lg">จำนวนรายการ</span>
                            <span class="text-2xl font-bold" x-text="items.length"></span>
                        </div>

                        <div class="bg-black/20 rounded-xl p-4 mt-4 backdrop-blur-sm max-h-48 overflow-y-auto custom-scrollbar"
                            x-show="items.length > 0">
                            <p class="text-blue-100 text-sm mb-2 font-semibold">รายการที่ขาย:</p>
                            <ul class="space-y-2 text-sm">
                                <template x-for="(item, index) in items" :key="index">
                                    <li
                                        class="flex justify-between items-center border-b border-white/10 py-1 last:border-0 last:pb-0">
                                        <span class="text-white truncate pr-2" x-text="item.waste_type_name"></span>
                                        <div class="text-right whitespace-nowrap flex items-center">
                                            <span class="text-blue-200 font-bold"
                                                x-text="parseFloat(item.weight).toFixed(3) + ' กก.'"></span>
                                            <span class="text-white/50 mx-1">|</span>
                                            <span class="text-amber-300 font-bold"
                                                x-text="parseFloat(item.price).toFixed(2) + ' ฿'"></span>
                                            <button @click="removeItem(index)"
                                                class="ml-2 p-1 text-red-300 hover:text-red-200 hover:bg-white/10 rounded transition-colors"
                                                title="ลบรายการ">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                    viewBox="0 0 24 24">
                                                    <path fill="currentColor"
                                                        d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </div>

                        <div class="h-px bg-blue-400 opacity-50"></div>

                        <div class="flex justify-between items-center">
                            <span class="text-blue-100 text-lg">น้ำหนักรวม</span>
                            <span class="text-2xl font-bold" x-text="totalWeight.toFixed(3) + ' กก.'"></span>
                        </div>
                        <div class="bg-black/20 rounded-xl p-4 mt-4 backdrop-blur-sm">
                            <p class="text-blue-100 text-sm mb-1">ยอดเงินรวมสุทธิ</p>
                            <p class="text-4xl font-bold tracking-tight text-white"
                                x-text="totalPrice.toFixed(2) + ' ฿'"></p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 space-y-3">
                    <button @click="submitTransaction()" :disabled="items.length === 0 || isSubmitting"
                        :class="items.length === 0 || isSubmitting ? 'bg-blue-800/50 cursor-not-allowed text-blue-200' : 'bg-white hover:bg-blue-50 text-blue-700 shadow-lg transform hover:-translate-y-0.5'"
                        class="w-full px-6 py-4 rounded-xl font-bold text-xl transition-all duration-200 flex items-center justify-center gap-2">
                        <span x-show="!isSubmitting">บันทึกการขาย</span>
                        <span x-show="isSubmitting" class="flex items-center gap-2">⏳ กำลังบันทึก...</span>
                    </button>
                    <button @click="cancelAll()" :disabled="items.length === 0"
                        class="w-full px-6 py-3 bg-red-500/20 hover:bg-red-500/30 text-white rounded-xl font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed border border-white/10">
                        ยกเลิกทั้งหมด
                    </button>
                    <p class="text-center text-blue-200 text-xs mt-4 opacity-70">กด <kbd
                            class="bg-blue-800/50 px-2 py-1 rounded text-white border border-blue-600/50">Ctrl+Enter</kbd>
                        เพื่อบันทึก</p>
                </div>
            </div>
        </div>
    </div>
    <div @keydown.ctrl.enter.window="submitTransaction()"></div>
</div>