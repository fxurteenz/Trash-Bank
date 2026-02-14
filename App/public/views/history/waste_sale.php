<script>
    function WasteSaleHistoryHandler() {
        return {
            sales: [],
            wasteTypes: [],
            filterStartDate: '',
            filterEndDate: '',
            filterType: '',
            filterBuyer: '',

            async init() {
                const today = new Date();
                const monthAgo = new Date(today.getTime() - (30 * 24 * 60 * 60 * 1000));

                this.filterStartDate = monthAgo.toISOString().split('T')[0];
                this.filterEndDate = today.toISOString().split('T')[0];

                await this.loadWasteTypes();
                await this.applyFilters();
            },

            async loadWasteTypes() {
                try {
                    const response = await fetch('/api/waste_types');
                    const result = await response.json();
                    if (result.success) {
                        this.wasteTypes = result.result || [];
                    }
                } catch (error) {
                    console.error('Error loading waste types:', error);
                }
            },

            async applyFilters() {
                try {
                    let url = '/api/waste_sales?';
                    const params = [];

                    if (this.filterStartDate) params.push(`start_date=${this.filterStartDate}`);
                    if (this.filterEndDate) params.push(`end_date=${this.filterEndDate}`);
                    if (this.filterType) params.push(`type_id=${this.filterType}`);
                    if (this.filterBuyer) params.push(`buyer=${encodeURIComponent(this.filterBuyer)}`);

                    url += params.join('&');

                    const response = await fetch(url);
                    const result = await response.json();

                    if (result.success) {
                        this.sales = result.result?.data || [];
                    }
                } catch (error) {
                    console.error('Error applying filters:', error);
                    this.sales = [];
                }
            },

            clearFilters() {
                this.filterStartDate = '';
                this.filterEndDate = '';
                this.filterType = '';
                this.filterBuyer = '';
                this.applyFilters();
            },

            getTotalWeight() {
                return this.sales.reduce((sum, sale) => sum + parseFloat(sale.waste_sale_weight || 0), 0);
            },

            getTotalRevenue() {
                return this.sales.reduce((sum, sale) => sum + parseFloat(sale.waste_sale_actual_price || 0), 0);
            },

            showDetail(sale) {
                alert(`รายละเอียด: ${sale.waste_type_name}\nน้ำหนัก: ${sale.waste_sale_weight} กก.\nมูลค่า: ${sale.waste_sale_actual_price} ฿`);
            },

            async deleteRecord(saleId) {
                if (confirm('คุณแน่ใจหรือว่าต้องการลบรายการนี้?')) {
                    try {
                        const response = await fetch(`/api/waste_sales/delete/${saleId}`, { method: 'POST' });
                        if (response.ok) {
                            alert('ลบรายการสำเร็จ');
                            this.applyFilters();
                        }
                    } catch (error) {
                        alert('เกิดข้อผิดพลาดในการลบ: ' + error.message);
                    }
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
                const startDate = new Date(this.filterStartDate).toLocaleDateString('th-TH');
                const endDate = new Date(this.filterEndDate).toLocaleDateString('th-TH');

                const rows = this.sales.map((sale, i) => `
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">${i + 1}</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">${new Date(sale.waste_sale_date).toLocaleDateString('th-TH')}</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">${sale.waste_sale_buyer || 'ไม่ระบุ'}</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">${sale.waste_type_name}</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">${sale.waste_sale_weight.toFixed(2)}</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">${sale.waste_type_price.toFixed(2)}</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right; font-weight: bold;">${sale.waste_sale_actual_price.toFixed(2)}</td>
                </tr>
            `).join('');

                return `
                <html>
                <head>
                    <meta charset="utf-8">
                    <title>รายงานประวัติการขายขยะ</title>
                    <style>
                        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 20px; }
                        h2 { text-align: center; color: #10b981; }
                        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                        th { background-color: #d1fae5; font-weight: bold; padding: 10px; text-align: left; }
                        .summary { margin-top: 20px; font-size: 14px; }
                        .summary-item { margin: 5px 0; }
                    </style>
                </head>
                <body>
                    <h2>รายงานประวัติการขายขยะ</h2>
                    <p><strong>ช่วงวันที่:</strong> ${startDate} ถึง ${endDate}</p>
                    <p><strong>ผู้ค้นหา:</strong> ${this.filterBuyer ? this.filterBuyer : 'ทั้งหมด'}</p>
                    
                    <table>
                        <thead>
                            <tr style="background-color: #d1fae5;">
                                <th style="width: 5%;">ลำดับ</th>
                                <th>วันที่</th>
                                <th>ผู้ซื้อ</th>
                                <th>ประเภท</th>
                                <th style="text-align: right;">น้ำหนัก (กก.)</th>
                                <th style="text-align: right;">ราคา/กก. (฿)</th>
                                <th style="text-align: right;">รวม (฿)</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${rows}
                        </tbody>
                    </table>

                    <div class="summary">
                        <div class="summary-item"><strong>จำนวนรายการ:</strong> ${this.sales.length}</div>
                        <div class="summary-item"><strong>รวมน้ำหนัก:</strong> ${this.getTotalWeight().toFixed(2)} กก.</div>
                        <div class="summary-item"><strong>รวมเงิน:</strong> ${this.getTotalRevenue().toFixed(2)} ฿</div>
                        <div class="summary-item"><strong>เฉลี่ยต่อครั้ง:</strong> ${(this.sales.length > 0 ? (this.getTotalRevenue() / this.sales.length).toFixed(2) : 0)} ฿</div>
                    </div>
                </body>
                </html>
            `;
            },

            exportCSV() {
                const header = 'ลำดับ,วันที่,ผู้ซื้อ,ประเภท,น้ำหนัก,ราคา/กก.,รวม\n';
                const rows = this.sales.map((sale, i) =>
                    `${i + 1},"${new Date(sale.waste_sale_date).toLocaleDateString('th-TH')}","${sale.waste_sale_buyer || 'ไม่ระบุ'}","${sale.waste_type_name}",${sale.waste_sale_weight.toFixed(2)},${sale.waste_type_price.toFixed(2)},${sale.waste_sale_actual_price.toFixed(2)}`
                ).join('\n');
                const summary = `\n\nสรุป\nจำนวนรายการ,${this.sales.length}\nรวมน้ำหนัก,${this.getTotalWeight().toFixed(2)} กก.\nรวมเงิน,${this.getTotalRevenue().toFixed(2)} ฿`;

                const csv = header + rows + summary;
                const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
                const link = document.createElement('a');
                const startDate = new Date(this.filterStartDate).toLocaleDateString('th-TH');
                const endDate = new Date(this.filterEndDate).toLocaleDateString('th-TH');
                link.setAttribute('href', URL.createObjectURL(blob));
                link.setAttribute('download', `waste_sale_history_${startDate}_${endDate}.csv`);
                link.click();
            }
        };
    }
</script>

<div x-data="WasteSaleHistoryHandler()" x-init="init()" class="space-y-6">
    <!-- Page Intro -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 mb-2">📊 ประวัติการขายขยะ</h1>
        <p class="text-slate-600 text-lg">ดูสรุปและรายงานการขายขยะทั้งหมด</p>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-xl shadow-md p-6 card-hover">
        <h2 class="text-xl font-bold text-slate-900 mb-5">🔍 ตัวกรองข้อมูล</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">วันที่เริ่มต้น</label>
                <input x-model="filterStartDate" @change="applyFilters()"
                    type="date"
                    class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">วันที่สิ้นสุด</label>
                <input x-model="filterEndDate" @change="applyFilters()"
                    type="date"
                    class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">ประเภทขยะ</label>
                <select x-model="filterType" @change="applyFilters()"
                    class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition bg-white">
                    <option value="">-- ทั้งหมด --</option>
                    <template x-for="type in wasteTypes" :key="type.waste_type_id">
                        <option :value="type.waste_type_id" x-text="type.waste_type_name"></option>
                    </template>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">ชื่อผู้ซื้อ</label>
                <input x-model="filterBuyer" @keydown.enter="applyFilters()"
                    type="text" placeholder="ค้นหาผู้ซื้อ"
                    class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
            </div>

            <div class="flex gap-2">
                <button @click="applyFilters()"
                    class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors">
                    🔍 ค้นหา
                </button>
                <button @click="clearFilters()"
                    class="flex-1 px-4 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg font-semibold transition-colors">
                    🔄 ล้าง
                </button>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 card-hover">
            <p class="text-slate-600 text-sm font-medium mb-2">📦 รวมรายการ</p>
            <p class="text-4xl font-bold text-blue-600" x-text="sales.length"></p>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500 card-hover">
            <p class="text-slate-600 text-sm font-medium mb-2">⚖️ รวมน้ำหนัก</p>
            <p class="text-3xl font-bold text-purple-600">
                <span x-text="getTotalWeight().toFixed(2)"></span>
                <span class="text-lg ml-2">กก.</span>
            </p>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-emerald-500 card-hover">
            <p class="text-slate-600 text-sm font-medium mb-2">💰 รวมเงิน</p>
            <p class="text-3xl font-bold text-emerald-600">
                <span x-text="getTotalRevenue().toFixed(2)"></span>
                <span class="text-lg ml-2">฿</span>
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-orange-500 card-hover">
            <p class="text-slate-600 text-sm font-medium mb-2">📊 เฉลี่ย/ครั้ง</p>
            <p class="text-3xl font-bold text-orange-600" x-text="(sales.length > 0 ? (getTotalRevenue() / sales.length).toFixed(2) : 0) + ' ฿'"></p>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-900">📋 รายละเอียดการขาย</h2>
            <p class="text-sm text-slate-600 mt-1">พบ <span x-text="sales.length"></span> รายการ</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-100 border-b-2 border-slate-300">
                    <tr class="text-left text-sm font-bold text-slate-700">
                        <th class="px-6 py-4 w-12">#</th>
                        <th class="px-6 py-4">วันที่</th>
                        <th class="px-6 py-4">ผู้ซื้อ</th>
                        <th class="px-6 py-4">ประเภทขยะ</th>
                        <th class="px-6 py-4 text-right">น้ำหนัก (กก.)</th>
                        <th class="px-6 py-4 text-right">ราคา/กก. (฿)</th>
                        <th class="px-6 py-4 text-right">รวม (฿)</th>
                        <th class="px-6 py-4 text-center">ดำเนินการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <template x-for="(sale, index) in sales" :key="sale.waste_sale_id">
                        <tr class="hover:bg-slate-50 transition text-sm text-slate-700" @click="showDetail(sale)" style="cursor: pointer;">
                            <td class="px-6 py-4 font-medium text-slate-400" x-text="index + 1"></td>
                            <td class="px-6 py-4">
                                <span x-text="new Date(sale.waste_sale_date).toLocaleDateString('th-TH')"></span>
                            </td>
                            <td class="px-6 py-4 font-medium" x-text="sale.waste_sale_buyer || 'ไม่ระบุ'"></td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold" x-text="sale.waste_type_name"></span>
                            </td>
                            <td class="px-6 py-4 text-right font-medium" x-text="sale.waste_sale_weight.toFixed(2)"></td>
                            <td class="px-6 py-4 text-right" x-text="sale.waste_type_price.toFixed(2)"></td>
                            <td class="px-6 py-4 text-right font-bold text-emerald-600" x-text="sale.waste_sale_actual_price.toFixed(2)"></td>
                            <td class="px-6 py-4 text-center">
                                <button @click.stop="deleteRecord(sale.waste_sale_id)" class="text-red-600 hover:text-red-700 font-semibold">
                                    🗑️ ลบ
                                </button>
                            </td>
                        </tr>
                    </template>
                    <template x-if="sales.length === 0">
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-slate-500">
                                <p class="text-lg font-medium">📭 ไม่มีข้อมูล</p>
                                <p class="text-sm">ไม่พบรายการที่ตรงกับเงื่อนไขการค้นหา</p>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Export Actions -->
    <div class="flex justify-center gap-4 pb-8">
        <button @click="printReport()"
            class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors flex items-center gap-2">
            <span>🖨️</span> พิมพ์รายงาน
        </button>
        <button @click="exportCSV()"
            class="px-8 py-4 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition-colors flex items-center gap-2">
            <span>📥</span> ส่งออก CSV
        </button>
    </div>
</div>


