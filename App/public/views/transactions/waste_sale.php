<div x-data="WasteSaleHandler()" x-init="init()" class="space-y-6 relative">
    <!-- Page Intro -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 mb-2">ระบบจำหน่ายขยะ (POS)</h1>
        <p class="text-slate-600 text-lg">สร้างรายการขายตรงจากคลังศูนย์ใหญ่</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Input Area -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Buyer Info -->
            <div class="bg-white rounded-xl shadow-md p-6 card-hover relative z-20">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-slate-900">
                        <span x-show="!buyerConfirmed">👤 ระบุผู้ซื้อ</span>
                        <span x-show="buyerConfirmed" class="text-emerald-600">✅ ยืนยันผู้ซื้อ</span>
                    </h2>
                    <button x-show="buyerConfirmed" @click="resetBuyer()"
                        class="px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                        🔄 เปลี่ยน
                    </button>
                </div>
                <!-- Buyer Input -->
                <div x-show="!buyerConfirmed" class="flex items-end gap-3">
                    <div class="flex-grow">
                        <label for="buyer_name" class="block text-sm font-semibold text-slate-700 mb-2">ชื่อผู้ซื้อ</label>
                        <input id="buyer_name" x-ref="buyerInput" x-model="buyerName" @keydown.enter.prevent="$refs.confirmBuyerBtn.focus()" type="text" placeholder="ระบุชื่อบริษัท หรือบุคคล" class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                    </div>
                    <button x-ref="confirmBuyerBtn" @click="confirmBuyer()" class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors">
                        ยืนยันชื่อ
                    </button>
                </div>
                <!-- Buyer Display -->
                <div x-show="buyerConfirmed" class="bg-gradient-to-br from-blue-50 to-blue-100 border-2 border-blue-300 rounded-lg p-5">
                    <p class="text-2xl font-bold text-slate-900" x-text="buyerName"></p>
                </div>
            </div>

            <!-- Item Entry -->
            <div x-show="buyerConfirmed" class="bg-white rounded-xl shadow-md p-6 card-hover">
                <h2 class="text-2xl font-bold text-slate-900 mb-5">📝 เพิ่มรายการขยะ</h2>
                
                <div class="grid grid-cols-12 gap-3 items-start mb-4">
                    <!-- Waste Type Search -->
                    <div class="col-span-12 md:col-span-4">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">ค้นหาชนิดขยะ</label>
                        <input x-ref="wasteCodeInput" x-model="itemForm.wasteCode" @input.debounce.300ms="searchWasteType()" @keydown.enter.prevent="$refs.weightInput.focus()" type="text" placeholder="พิมพ์รหัส/ชื่อ" class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" list="wasteTypeList" autocomplete="off">
                        <datalist id="wasteTypeList">
                            <template x-for="type in filteredStock" :key="type.waste_type_id">
                                <option :value="type.waste_type_id" :label="`${type.waste_type_id} - ${type.waste_type_name}`"></option>
                            </template>
                        </datalist>
                        <p x-show="selectedStockItem" class="text-xs text-emerald-600 mt-2 font-medium">
                            ในคลัง: <span x-text="selectedStockItem?.stock_weight || 0"></span> กก.
                        </p>
                    </div>

                    <!-- Weight Input -->
                    <div class="col-span-6 md:col-span-3">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">น้ำหนัก (กก.)</label>
                        <input x-ref="weightInput" x-model.number="itemForm.weight" @keydown.enter.prevent="$refs.priceInput.focus()" type="number" step="0.01" min="0.01" placeholder="0.00" class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                    </div>

                    <!-- Price Input -->
                    <div class="col-span-6 md:col-span-3">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">ราคาขาย (บาท)</label>
                        <input x-ref="priceInput" x-model.number="itemForm.price" @keydown.enter.prevent="addItem()" type="number" step="0.01" min="0" placeholder="0.00" class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                    </div>

                    <!-- Add Button -->
                    <div class="col-span-12 md:col-span-2 flex items-end">
                        <button @click="addItem()" class="w-full mt-2 md:mt-0 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors">
                            ➕ เพิ่ม
                        </button>
                    </div>
                </div>
            </div>

            <!-- Items List -->
            <div x-show="items.length > 0" class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-2xl font-bold text-slate-900 mb-4">🛒 รายการที่จะขาย <span class="text-blue-600" x-text="items.length"></span></h2>
                <div class="space-y-2 max-h-96 overflow-y-auto">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex items-center justify-between p-4 bg-slate-50 hover:bg-slate-100 rounded-lg transition group">
                            <div class="flex items-center gap-3 flex-1">
                                <span class="text-lg font-bold text-slate-400 w-8 text-center" x-text="index + 1"></span>
                                <div class="flex-1">
                                    <p class="font-semibold text-slate-900" x-text="item.waste_type_name"></p>
                                    <p class="text-sm text-slate-600">
                                        น้ำหนัก: <span x-text="item.weight.toFixed(2)"></span> กก. | 
                                        ราคา: <span class="font-semibold text-emerald-600" x-text="item.price.toFixed(2)"></span> ฿
                                    </p>
                                </div>
                            </div>
                            <button @click="removeItem(index)" class="ml-4 px-3 py-2 text-sm bg-red-100 hover:bg-red-200 text-red-700 rounded-lg opacity-0 group-hover:opacity-100 transition-all">
                                ❌ ลบ
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Summary Sidebar -->
        <div class="space-y-6">
            <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-lg p-6 text-white sticky top-8">
                <h3 class="text-lg font-bold mb-5 flex items-center gap-2">📊 สรุป</h3>
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
                        <p class="text-emerald-100 text-sm mb-1">ราคารวมทั้งหมด</p>
                        <p class="text-4xl font-bold" x-text="totalPrice.toFixed(2) + ' ฿'"></p>
                    </div>
                </div>

                <div class="mt-6">
                     <label for="sale_note" class="block text-sm font-semibold text-emerald-100 mb-2">หมายเหตุ (ถ้ามี)</label>
                     <textarea id="sale_note" x-model="saleNote" rows="2" class="w-full px-3 py-2 bg-emerald-700 text-white rounded-lg focus:ring-2 focus:ring-white/50 transition" placeholder="..."></textarea>
                </div>

                <div class="mt-6 space-y-3">
                    <button @click="submitSale()" :disabled="items.length === 0 || !buyerConfirmed || isSubmitting" class="w-full px-6 py-4 rounded-lg font-bold text-lg transition-colors bg-white hover:bg-slate-50 text-emerald-600 disabled:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!isSubmitting">✅ บันทึกการขาย</span>
                        <span x-show="isSubmitting">⏳ กำลังบันทึก...</span>
                    </button>
                    <button @click="cancelAll()" :disabled="items.length === 0" class="w-full text-center text-red-200 hover:text-white transition">ยกเลิกทั้งหมด</button>
                </div>
                <p class="text-center text-emerald-100 text-xs mt-4">กด <kbd class="bg-emerald-600 px-2 py-1 rounded">Ctrl+Enter</kbd> เพื่อบันทึก</p>
            </div>
        </div>
    </div>
    <div @keydown.ctrl.enter.window.prevent="submitSale()"></div>
</div>

<script>
function WasteSaleHandler() {
    return {
        buyerName: '',
        saleNote: '',
        buyerConfirmed: false,
        itemForm: { wasteCode: '', weight: null, price: null },
        centerStock: [],
        filteredStock: [],
        selectedStockItem: null,
        items: [],
        isSubmitting: false,

        get totalWeight() {
            return this.items.reduce((sum, item) => sum + parseFloat(item.weight || 0), 0);
        },

        get totalPrice() {
            return this.items.reduce((sum, item) => sum + parseFloat(item.price || 0), 0);
        },

        init() {
            this.$nextTick(() => this.$refs.buyerInput.focus());
        },
        
        confirmBuyer() {
            if (!this.buyerName.trim()) {
                this.showNotification('❌ กรุณากรอกชื่อผู้ซื้อ', 'error');
                this.$refs.buyerInput.focus();
                return;
            }
            this.buyerConfirmed = true;
            this.loadCenterStock();
            this.$nextTick(() => this.$refs.wasteCodeInput.focus());
        },

        resetBuyer() {
            this.buyerConfirmed = false;
            this.buyerName = '';
            this.items = [];
            this.clearItemForm();
            this.$nextTick(() => this.$refs.buyerInput.focus());
        },

        async loadCenterStock() {
            try {
                const response = await fetch('/api/center_stock');
                const result = await response.json();
                if (result.status === 'success') {
                    this.centerStock = result.data || [];
                    this.filteredStock = this.centerStock;
                } else {
                     this.showNotification('❌ ไม่สามารถโหลดข้อมูลคลังขยะได้', 'error');
                }
            } catch (error) {
                console.error('Error loading center stock:', error);
                this.showNotification('❌ ไม่สามารถโหลดข้อมูลคลังขยะได้', 'error');
            }
        },

        searchWasteType() {
            const search = this.itemForm.wasteCode.toLowerCase();
            this.filteredStock = this.centerStock.filter(item => 
                item.waste_type_id.toString().includes(search) ||
                item.waste_type_name.toLowerCase().includes(search)
            );
            this.selectedStockItem = this.centerStock.find(item => item.waste_type_id.toString() === this.itemForm.wasteCode) || null;
        },

        addItem() {
            if (!this.selectedStockItem) {
                this.showNotification('❌ กรุณาเลือกชนิดขยะที่มีในคลัง', 'error');
                return;
            }
            if (!this.itemForm.weight || this.itemForm.weight <= 0) {
                this.showNotification('❌ กรุณากรอกน้ำหนักให้ถูกต้อง', 'error');
                return;
            }
            if (this.itemForm.price === null || this.itemForm.price < 0) {
                this.showNotification('❌ กรุณากรอกราคาขายให้ถูกต้อง', 'error');
                return;
            }

            const currentWeightInCart = this.items
                .filter(i => i.waste_type_id === this.selectedStockItem.waste_type_id)
                .reduce((sum, i) => sum + i.weight, 0);
            
            const availableStock = this.selectedStockItem.stock_weight - currentWeightInCart;

            if (this.itemForm.weight > availableStock) {
                this.showNotification(`⚠️ น้ำหนักเกินคลัง (คงเหลือ ${availableStock.toFixed(2)} กก.)`, 'warning');
                return;
            }

            this.items.push({
                waste_type_id: this.selectedStockItem.waste_type_id,
                waste_type_name: this.selectedStockItem.waste_type_name,
                weight: this.itemForm.weight,
                price: this.itemForm.price,
            });

            this.showNotification('✅ เพิ่มรายการแล้ว', 'success');
            this.clearItemForm();
            this.$refs.wasteCodeInput.focus();
        },

        removeItem(index) {
            this.items.splice(index, 1);
            this.showNotification('🗑️ ลบรายการแล้ว', 'info');
        },

        clearItemForm() {
            this.itemForm = { wasteCode: '', weight: null, price: null };
            this.selectedStockItem = null;
            this.filteredStock = this.centerStock;
        },

        async submitSale() {
            if (!this.buyerConfirmed || this.items.length === 0) {
                this.showNotification('❌ กรุณากรอกข้อมูลให้ครบถ้วน', 'warning');
                return;
            }
            this.isSubmitting = true;
            try {
                const payload = {
                    waste_sale_buyer: this.buyerName,
                    waste_sale_note: this.saleNote,
                    items: this.items.map(item => ({
                        waste_type_id: item.waste_type_id,
                        waste_sale_detail_weight: item.weight,
                        waste_sale_detail_price: item.price
                    }))
                };

                const response = await fetch('/api/waste_sales', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                
                const result = await response.json();

                if (response.ok) {
                    this.showNotification(`✅ บันทึกการขายสำเร็จ! ID: ${result.waste_sale_id}`, 'success');
                    this.resetForm();
                } else {
                    throw new Error(result.message || 'เกิดข้อผิดพลาด');
                }
            } catch (error) {
                console.error('Error submitting sale:', error);
                this.showNotification(`❌ เกิดข้อผิดพลาด: ${error.message}`, 'error');
            } finally {
                this.isSubmitting = false;
            }
        },

        resetForm() {
            this.items = [];
            this.saleNote = '';
            this.clearItemForm();
            this.resetBuyer();
        },
        
        cancelAll() {
            if (this.items.length > 0 && confirm('ต้องการยกเลิกรายการทั้งหมดใช่หรือไม่?')) {
                this.items = [];
                this.clearItemForm();
                this.showNotification('❌ ยกเลิกทั้งหมดแล้ว', 'info');
            }
        },
        
        showNotification(message, type = 'info') {
            // Placeholder for a real notification system
            console.log(`[${type}] ${message}`);
            alert(message);
        }
    };
}
</script>