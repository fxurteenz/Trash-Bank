<div x-data="WasteCategories()" x-init="initData()" class="max-w-7xl mx-auto bg-white p-8">
    <div class="header-container flex flex-col items-center justify-center mb-6 relative">
        <h2 class="text-2xl font-bold mb-2">รายงานรายการหมวดหมู่ขยะ</h2>

        <div class="absolute right-0 top-0 flex flex-col items-end gap-2">
            <button @click="window.print()"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded print:hidden">
                พิมพ์รายงาน
            </button>
            <p class="text-sm text-gray-600" x-text="`ข้อมูล ณ วันที่: ${new Date().toLocaleDateString('th-TH')}`"></p>
        </div>
    </div>
    <div class="mb-4">
        <p class="text-sm font-semibold text-gray-700" x-text="`จำนวนหมวดหมู่รวม: ${categories.length} หมวดหมู่`"></p>
    </div>

    <table class="min-w-full bg-white border border-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 border text-center text-xs font-semibold text-gray-600 uppercase">
                    ลำดับ</th>
                <th class="px-4 py-2 border text-left text-xs font-semibold text-gray-600 uppercase">
                    ชื่อหมวดหมู่</th>
                <th class="px-4 py-2 border text-right text-xs font-semibold text-gray-600 uppercase">
                    จำนวนประเภท</th>
            </tr>
        </thead>
        <tbody>
            <template x-for="(category, index) in categories" :key="category.waste_category_id">
                <tr>
                    <td class="px-4 py-2 border text-center text-xs w-18" x-text="index + 1"></td>
                    <td class="px-4 py-2 border text-left text-xs" x-text="category.waste_category_name"></td>
                    <td class="px-4 py-2 border text-right text-xs w-32"
                        x-text="Number(parseInt(category.waste_type_count) || 0).toLocaleString()"></td>
                </tr>
            </template>
            <template x-if="categories.length > 0">
                <tr class="bg-gray-50">
                    <td :colspan="2" class="px-4 py-8 border text-right text-xs text-gray-600">
                        จำนวนประเภทขยะทั้งหมดของทุกหมวดหมู่
                    </td>
                    <td :colspan="1" class="px-4 py-8 border text-right text-xs font-semibold text-gray-600"
                        x-text="allWasteTypesCount.toLocaleString()"></td>
                </tr>
            </template>
            <template x-if="categories.length === 0 && isLoadingCategories">
                <tr>
                    <td :colspan="3" class="px-4 py-8 text-center text-gray-500 border">กำลังโหลด...</td>
                </tr>
            </template>
            <template x-if="categories.length === 0 && !isLoadingCategories">
                <tr>
                    <td :colspan="3" class="px-4 py-8 text-center text-gray-500 border">ไม่มีข้อมูลหมวดหมู่ของรางวัล
                    </td>
                </tr>
            </template>
        </tbody>
    </table>
</div>

<script>
    function WasteCategories() {
        return {
            // Data States
            categories: [],
            wasteTypes: [],
            isLoadingCategories: false,
            wasteTypesLoading: false,
            allWasteTypesCount: 0,

            initData() {
                this.fetchCategories();
            },

            // --- Categories Logic ---
            async fetchCategories(page = 1) {

                this.isLoadingCategories = true;
                try {
                    const response = await fetch(`/api/waste_categories`);
                    const result = await response.json();
                    if (result.success) {
                        this.categories = result.data || [];
                        this.allWasteTypesCount = result.data.reduce((sum, category) => {
                            return sum + Number(parseInt(category.waste_type_count) || 0);
                        }, 0)

                        setTimeout(() => {
                            window.print();
                        }, 500);
                    }
                } catch (error) {
                    console.error('Error fetching categories:', error);
                    await Swal.fire('ข้อผิดพลาด', error.message, 'error');
                } finally {
                    this.isLoadingCategories = false;
                }
            },
        }
    }
</script>