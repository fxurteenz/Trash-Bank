<div x-data="WasteSalePOSHandler()" x-init="init()" class="space-y-6">
    <!-- Page Intro -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 mb-2">💰 ระบบขายขยะแบบ POS</h1>
        <p class="text-slate-600 text-lg">บันทึกการนำขยะออกไปขาย - ใช้คีย์บอร์ดเพียงอย่างเดียว</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Input Area -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Sale Info -->
            <div class="bg-white rounded-xl shadow-md p-6 card-hover">
                <h2 class="text-2xl font-bold text-slate-900 mb-5">📅 ข้อมูลการขาย</h2>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">วันที่ขาย</label>
                        <div class="flex gap-2">
                            <input x-ref="saleDateInput" x-model="saleDate"
                                @keydown.tab.prevent="$refs.buyerInput.focus()"
                                type="date"
                                class="flex-1 px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                            <button @click="saleDate = today"
                                class="px-4 py-3 text-sm font-medium bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg transition-colors">
                                วันนี้
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">ชื่อผู้ซื้อ</label>
                        <input x-ref="buyerInput" x-model="buyerName"
                            @keydown.tab.prevent="$refs.wasteCodeInput.focus()"
                            type="text" placeholder="บริษัท / ผู้ซื้อ"
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                    </div>
                </div>
            </div>

            <!-- Item Entry -->
            <div class="bg-white rounded-xl shadow-md p-6 card-hover">
                <h2 class="text-2xl font-bold text-slate-900 mb-5">📝 เพิ่มรายการขยะ</h2>
                
                <div class="grid grid-cols-12 gap-3 items-end mb-4">
                    <!-- Waste Type -->
                    <div class="col-span-5">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">รหัสชนิดขยะ</label>
                        <input x-ref="wasteCodeInput" x-model="itemForm.wasteCode"
                            @keydown.tab.prevent="$refs.weightInput.focus()"
                            @keydown.enter="handleWasteCodeEnter()" @input="searchWasteType()"
                            type="text" placeholder="พิมพ์รหัส/ชื่อ"
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                            list="wasteTypeList" autocomplete="off">
                        <datalist id="wasteTypeList">
                            <template x-for="type in filteredWasteTypes" :key="type.waste_type_id">
                                <option :value="type.waste_type_id" :label="`${type.waste_type_id} - ${type.waste_type_name}`"></option>
                            </template>
                        </datalist>
                        <p x-show="selectedWasteType" class="text-xs text-emerald-600 mt-2 font-medium" x-text="selectedWasteType?.waste_type_name"></p>
                    </div>

                    <!-- Weight -->
                    <div class="col-span-3">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">น้ำหนัก (กก.)</label>
                        <input x-ref="weightInput" x-model="itemForm.weight"
                            @keydown.enter="addItem()" @keydown.tab.prevent="addItem(); $refs.wasteCodeInput.focus()"
                            type="number" step="0.01" min="0.01" placeholder="0.00"
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                    </div>

                    <!-- Add Button -->
                    <div class="col-span-4">
                        <button @click="addItem()"
                            class="w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors">
                            ➕ เพิ่ม
                        </button>
                    </div>
                </div>

                <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-800">
                    💡 กรอกรหัส → <kbd class="bg-white px-2 py-1 rounded border">Tab</kbd> → 
                    น้ำหนัก → <kbd class="bg-white px-2 py-1 rounded border">Enter</kbd> เพื่อเพิ่ม
                </div>
            </div>

            <!-- Items List -->
            <div x-show="items.length > 0" class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-2xl font-bold text-slate-900 mb-4">🛒 รายการที่ขาย <span class="text-blue-600" x-text="items.length"></span></h2>
                
                <div class="space-y-2 max-h-96 overflow-y-auto">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex items-center justify-between p-4 bg-slate-50 hover:bg-slate-100 rounded-lg transition group">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <span class="text-lg font-bold text-slate-400 w-8 text-center" x-text="index + 1"></span>
                                    <div class="flex-1">
                                        <p class="font-semibold text-slate-900" x-text="item.waste_type_name"></p>
                                        <p class="text-sm text-slate-600">
                                            น้ำหนัก: <span x-text="item.weight.toFixed(2)"></span> กก. | 
                                            ราคา: <span class="font-semibold text-emerald-600" x-text="(item.weight * item.waste_type_price).toFixed(2)"></span> ฿
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <button @click="removeItem(index)"
                                class="ml-4 px-3 py-2 text-sm bg-red-100 hover:bg-red-200 text-red-700 rounded-lg opacity-0 group-hover:opacity-100 transition-all">
                                ❌ ลบ
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Summary Sidebar -->
        <div class="space-y-6">
            <!-- Summary Card -->
            <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-lg p-6 text-white sticky top-8">
                <h3 class="text-lg font-bold mb-5 flex items-center gap-2">
                    <span class="text-2xl">📊</span> สรุป
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
                        <p class="text-emerald-100 text-sm mb-1">รวมเงินทั้งหมด</p>
                        <p class="text-4xl font-bold" x-text="totalRevenue.toFixed(2) + ' ฿'"></p>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-6 space-y-3">
                    <button @click="submitSale()"
                        :disabled="items.length === 0 || isSubmitting"
                        :class="items.length === 0 || isSubmitting ? 'bg-emerald-700 opacity-50 cursor-not-allowed' : 'bg-white hover:bg-slate-50 text-emerald-600'"
                        class="w-full px-6 py-4 rounded-lg font-bold text-lg transition-colors">
                        <span x-show="!isSubmitting">✅ บันทึกการขาย</span>
                        <span x-show="isSubmitting">⏳ กำลังบันทึก...</span>
                    </button>
                    
                    <button @click="cancelAll()" :disabled="items.length === 0"
                        class="w-full px-6 py-3 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        ❌ ยกเลิกทั้งหมด
                    </button>
                </div>

                <p class="text-center text-emerald-100 text-xs mt-4">กด <kbd class="bg-emerald-600 px-2 py-1 rounded">Ctrl+Enter</kbd> เพื่อบันทึก</p>
            </div>

            <!-- Daily Stats -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">📈 สถิติวันนี้</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-600">ครั้งที่ขาย</span>
                        <span class="font-bold text-slate-900" x-text="todayStats.count"></span>
                    </div>
                    <div class="h-px bg-slate-200"></div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-600">น้ำหนักรวม</span>
                        <span class="font-bold text-slate-900" x-text="todayStats.weight.toFixed(2) + ' กก.'"></span>
                    </div>
                    <div class="h-px bg-slate-200"></div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-600">เงินรวม</span>
                        <span class="font-bold text-emerald-600" x-text="todayStats.revenue.toFixed(2) + ' ฿'"></span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-3">⚡ ลัดชั้น</h3>
                <div class="space-y-2">
                    <button @click="printReport()"
                        class="w-full px-4 py-2 text-sm bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg font-medium transition-colors">
                        🖨️ พิมพ์รายงาน
                    </button>
                    <button @click="exportCSV()"
                        class="w-full px-4 py-2 text-sm bg-green-100 hover:bg-green-200 text-green-700 rounded-lg font-medium transition-colors">
                        📥 ส่งออก CSV
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Keyboard Shortcuts -->
    <div @keydown.ctrl.enter.window="submitSale()"></div>
</div>

<script>
function WasteSalePOSHandler() {
    return {
        saleDate: new Date().toISOString().split('T')[0],
        today: new Date().toISOString().split('T')[0],
        buyerName: '',
        itemForm: { wasteCode: '', weight: '', price: 0 },
        wasteTypes: [],
        filteredWasteTypes: [],
        selectedWasteType: null,
        items: [],
        isSubmitting: false,
        todayStats: { count: 0, weight: 0, revenue: 0 },

        get totalWeight() {
            return this.items.reduce((sum, item) => sum + parseFloat(item.weight || 0), 0);
        },

        get totalRevenue() {
            return this.items.reduce((sum, item) => {
                const price = parseFloat(item.waste_type_price || item.price || 0);
                const weight = parseFloat(item.weight || 0);
                return sum + (price * weight);
            }, 0);
        },

        async init() {
            await this.loadWasteTypes();
            await this.loadTodayStats();
            this.$nextTick(() => {
                if (this.$refs.saleDateInput) {
                    this.$refs.saleDateInput.focus();
                }
            });
        },

        async loadWasteTypes() {
            try {
                const response = await fetch('/api/waste_types');
                const result = await response.json();
                if (result.success) {
                    this.wasteTypes = result.result || [];
                    this.filteredWasteTypes = this.wasteTypes;
                }
            } catch (error) {
                console.error('Error loading waste types:', error);
                this.showValidation('❌ ไม่สามารถโหลดข้อมูลชนิดขยะได้', 'error');
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
            } else {
                this.showValidation('❌ ไม่พบชนิดขยะ กรุณาลองใหม่', 'error');
            }
        },

        addItem() {
            if (!this.itemForm.wasteCode || !this.selectedWasteType) {
                this.showValidation('❌ กรุณาเลือกชนิดขยะ', 'error');
                this.$refs.wasteCodeInput.focus();
                return;
            }

            if (!this.itemForm.weight || parseFloat(this.itemForm.weight) <= 0) {
                this.showValidation('❌ กรุณากรอกน้ำหนัก', 'error');
                this.$refs.weightInput.focus();
                return;
            }

            this.items.push({
                waste_type_id: this.selectedWasteType.waste_type_id,
                waste_type_name: this.selectedWasteType.waste_type_name,
                waste_type_price: parseFloat(this.selectedWasteType.waste_type_price),
                weight: parseFloat(this.itemForm.weight)
            });

            this.showValidation('✅ เพิ่มรายการแล้ว', 'success');
            this.clearItemForm();
            this.$refs.wasteCodeInput.focus();
        },

        removeItem(index) {
            this.items.splice(index, 1);
            this.showValidation('🗑️ ลบรายการแล้ว', 'info');
        },

        clearItemForm() {
            this.itemForm.wasteCode = '';
            this.itemForm.weight = '';
            this.itemForm.price = 0;
            this.selectedWasteType = null;
            this.filteredWasteTypes = this.wasteTypes;
        },

        async submitSale() {
            if (!this.saleDate) {
                this.showValidation('❌ กรุณาเลือกวันที่ขาย', 'error');
                return;
            }

            if (this.items.length === 0) {
                this.showValidation('❌ ไม่มีรายการที่จะบันทึก', 'error');
                return;
            }

            this.isSubmitting = true;

            try {
                const payload = {
                    sales: this.items.map(item => ({
                        waste_sale_type_id: item.waste_type_id,
                        waste_sale_weight: item.weight,
                        waste_sale_actual_price: item.weight * item.waste_type_price,
                        waste_sale_buyer: this.buyerName || 'ไม่ระบุ',
                        waste_sale_date: this.saleDate
                    }))
                };

                const response = await fetch('/api/waste_sales/batch', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (result.success) {
                    this.showValidation(`✅ บันทึก ${this.items.length} รายการสำเร็จ!`, 'success');
                    await this.loadTodayStats();
                    this.items = [];
                    this.clearItemForm();
                    this.buyerName = '';
                    this.saleDate = this.today;
                    this.$refs.saleDateInput.focus();
                } else {
                    this.showValidation('⚠️ บันทึกไม่สำเร็จ: ' + (result.message || 'ข้อผิดพลาดไม่ทราบ'), 'error');
                }
            } catch (error) {
                console.error('Error submitting:', error);
                this.showValidation('❌ เกิดข้อผิดพลาดในการบันทึก', 'error');
            } finally {
                this.isSubmitting = false;
            }
        },

        cancelAll() {
            if (this.items.length === 0) return;
            if (confirm('ต้องการยกเลิกรายการทั้งหมดใช่หรือไม่?')) {
                this.items = [];
                this.clearItemForm();
                this.showValidation('❌ ยกเลิกทั้งหมดแล้ว', 'info');
            }
        },

        async loadTodayStats() {
            try {
                const today = new Date().toISOString().split('T')[0];
                const response = await fetch(`/api/waste_sales?date=${today}`);
                const result = await response.json();

                if (result.success) {
                    const data = result.result?.data || [];
                    this.todayStats.count = data.length;
                    this.todayStats.weight = data.reduce((sum, s) => sum + parseFloat(s.waste_sale_weight || 0), 0);
                    this.todayStats.revenue = data.reduce((sum, s) => sum + parseFloat(s.waste_sale_actual_price || 0), 0);
                }
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        },

        printReport() {
            const html = this.generateReportHTML();
            const printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write(html);
            printWindow.document.close();
            printWindow.print();
        },

        generateReportHTML() {
            const date = new Date(this.saleDate).toLocaleDateString('th-TH');
            const items = this.items.map((item, i) => `
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;">${i + 1}</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">${item.waste_type_name}</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">${item.weight.toFixed(2)}</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">${item.waste_type_price.toFixed(2)}</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">${(item.weight * item.waste_type_price).toFixed(2)}</td>
                </tr>
            `).join('');

            return `
                <html>
                <head>
                    <meta charset="utf-8">
                    <title>รายงานการขายขยะ</title>
                    <style>
                        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 20px; }
                        h2 { text-align: center; }
                        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                        .summary { margin-top: 20px; }
                    </style>
                </head>
                <body>
                    <h2>รายงานการขายขยะ</h2>
                    <p><strong>วันที่:</strong> ${date}</p>
                    <p><strong>ผู้ซื้อ:</strong> ${this.buyerName || 'ไม่ระบุ'}</p>
                    <table>
                        <thead>
                            <tr style="background-color: #f2f2f2;">
                                <th style="border: 1px solid #ddd; padding: 8px;">ลำดับ</th>
                                <th style="border: 1px solid #ddd; padding: 8px;">ชนิดขยะ</th>
                                <th style="border: 1px solid #ddd; padding: 8px; text-align: right;">น้ำหนัก (กก.)</th>
                                <th style="border: 1px solid #ddd; padding: 8px; text-align: right;">ราคา/กก. (฿)</th>
                                <th style="border: 1px solid #ddd; padding: 8px; text-align: right;">รวม (฿)</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${items}
                        </tbody>
                    </table>
                    <div class="summary">
                        <p><strong>รวมน้ำหนัก:</strong> ${this.totalWeight.toFixed(2)} กก.</p>
                        <p><strong>รวมเงิน:</strong> ${this.totalRevenue.toFixed(2)} ฿</p>
                    </div>
                </body>
                </html>
            `;
        },

        exportCSV() {
            const header = 'ลำดับ,ชนิดขยะ,น้ำหนัก,ราคา/กก.,รวมเงิน\n';
            const rows = this.items.map((item, i) => 
                `${i + 1},"${item.waste_type_name}",${item.weight.toFixed(2)},${item.waste_type_price.toFixed(2)},${(item.weight * item.waste_type_price).toFixed(2)}`
            ).join('\n');
            const summary = `\n\nรวมน้ำหนัก,${this.totalWeight.toFixed(2)} กก.\nรวมเงิน,${this.totalRevenue.toFixed(2)} ฿`;

            const csv = header + rows + summary;
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const date = new Date(this.saleDate).toLocaleDateString('th-TH');
            link.setAttribute('href', URL.createObjectURL(blob));
            link.setAttribute('download', `waste_sale_${date}.csv`);
            link.click();
        },

        showValidation(message, type = 'info') {
            console.log(message);
        }
    };
}
</script>
