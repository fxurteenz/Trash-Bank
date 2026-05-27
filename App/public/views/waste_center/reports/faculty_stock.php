<?php
$faculty_id = $faculty_id ?? "null";
?>
<div x-data="FacultyStockReport()" x-init="initData()" class="max-w-7xl mx-auto bg-white p-8">
    <div class="header-container flex flex-col items-center justify-center mb-6 relative">

        <div class="absolute right-0 top-0 flex flex-col items-end gap-2">
            <button @click="window.print()"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded print:hidden">
                พิมพ์รายงาน
            </button>
            <p class="text-sm text-gray-600" x-text="`ข้อมูล ณ วันที่: ${new Date().toLocaleDateString('th-TH')}`"></p>
        </div>
    </div>
    <div class="header-container flex flex-col items-center justify-center mb-6 relative">
        <h2 class="text-2xl font-bold mb-2 mt-12" x-text="`รายงานขยะในคลัง คณะ${faculty_detail?.faculty_name}`"></h2>

    </div>

    <!-- Table -->
    <table class="min-w-full bg-white border border-gray-200">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 border text-center text-xs font-semibold text-gray-600 uppercase w-16">ลำดับ</th>
                <th class="px-4 py-2 border text-left text-xs font-semibold text-gray-600 uppercase">หมวดหมู่</th>
                <th class="px-4 py-2 border text-left text-xs font-semibold text-gray-600 uppercase">ประเภทขยะ</th>
                <th class="px-4 py-2 border text-right text-xs font-semibold text-gray-600 uppercase">น้ำหนักคงเหลือ
                    (กก.)</th>
            </tr>
        </thead>
        <tbody>
            <template x-for="(item, index) in stocks" :key="item.waste_type_id">
                <tr>
                    <td class="px-4 py-2 border text-center text-xs" x-text="index + 1"></td>
                    <td class="px-4 py-2 border text-xs" x-text="item.waste_category_name || '-'"></td>
                    <td class="px-4 py-2 border text-xs" x-text="item.waste_type_name || '-'"></td>
                    <td class="px-4 py-2 border text-right text-xs font-semibold text-gray-800"
                        x-text="Number(item.stock_weight || 0).toLocaleString(undefined, {minimumFractionDigits: 3, maximumFractionDigits: 3})">
                    </td>
                </tr>
            </template>
            <template x-if="stocks.length === 0">
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-gray-500 border">ไม่มีขยะในคลัง</td>
                </tr>
            </template>
        </tbody>
        <tfoot class="bg-gray-50" x-show="stocks.length > 0" x-cloak>
            <tr>
                <td colspan="3" class="px-4 py-2 border text-right text-sm font-bold text-gray-700">น้ำหนักรวม</td>
                <td class="px-4 py-2 border text-right text-sm font-bold text-gray-700"
                    x-text="Number(totalStockWeight).toLocaleString(undefined, {minimumFractionDigits: 3, maximumFractionDigits: 3})">
                </td>
            </tr>
        </tfoot>
    </table>
</div>

<script>
    function FacultyStockReport() {
        return {
            facultyId: <?= $faculty_id ?>,
            stocks: [],
            faculty_detail: [],
            get totalStockWeight() {
                return this.stocks.reduce((sum, item) => sum + (parseFloat(item.stock_weight) || 0), 0);
            },

            async initData() {
                if (!this.facultyId || this.facultyId === 'null') {
                    alert("ไม่พบรหัสคณะ");
                    return;
                }
                await this.fetchData();
                setTimeout(() => { window.print(); }, 800);
            },

            async fetchData() {
                try {
                    // ดึงข้อมูลรายการคลังขยะจาก API โดยตรง
                    const resStock = await fetch(`/api/faculty_stock/${this.facultyId}`);
                    const resultStock = await resStock.json();

                    if (resultStock.data) {
                        this.stocks = resultStock.data || [];
                        this.faculty_detail = resultStock.faculty_detail || [];
                    }
                } catch (err) {
                    console.error("Failed to load faculty stock for report:", err);
                    alert("ไม่สามารถโหลดข้อมูลรายงานได้");
                }
            }
        };
    }
</script>