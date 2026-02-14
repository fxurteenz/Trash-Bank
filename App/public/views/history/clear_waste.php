<script>
    function ClearWasteHistoryHandler() {
        return {
            clearances: [],
            loading: false,

            // ใช้ Getter ในการคำนวณค่ารวมจาก JSON
            get totalWeight() {
                return this.clearances.reduce((sum, i) => sum + parseFloat(i.waste_clearance_total_weight || 0), 0).toFixed(2);
            },
            get totalPoints() {
                return this.clearances.reduce((sum, i) => sum + parseInt(i.waste_clearance_total_point || 0), 0);
            },

            async init() {
                await this.applyFilters();
            },

            formatDateTime(dateStr) {
                const date = new Date(dateStr);
                return date.toLocaleDateString('th-TH') + ' ' + date.toLocaleTimeString('th-TH', { hour: '2-digit', minute: '2-digit' });
            },

            async applyFilters() {
                this.loading = true;
                try {
                    // ปรับให้ตรงตามโครงสร้าง API ของคุณ
                    const res = await fetch('/api/clearances');
                    const json = await res.json();

                    if (json.success) {
                        // แก้ไข: เข้าถึงข้อมูลผ่าน json.data ตามโครงสร้างที่ให้มา
                        this.clearances = json.data || [];
                    }
                } catch (err) {
                    console.error('Fetch error:', err);
                } finally {
                    this.loading = false;
                }
            },

            async openDetail(item) {
                // สำหรับ Modal รายละเอียด ต้องเช็คอีกทีว่า API Detail ใช้ Field อะไร
                console.log("Viewing ID:", item.waste_clearance_id);
            }
        }
    }
</script>

<div x-data="ClearWasteHistoryHandler()" x-init="init()" class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-emerald-500">
            <p class="text-slate-600 text-sm font-medium mb-2">📦 รวมรายการ</p>
            <p class="text-4xl font-bold text-emerald-600" x-text="clearances.length"></p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-teal-500">
            <p class="text-slate-600 text-sm font-medium mb-2">⚖️ น้ำหนักรวมทั้งหมด</p>
            <p class="text-3xl font-bold text-teal-600" x-text="totalWeight + ' กก.'"></p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-cyan-500">
            <p class="text-slate-600 text-sm font-medium mb-2">🏆 แต้มสะสมรวม</p>
            <p class="text-3xl font-bold text-cyan-600" x-text="totalPoints + ' แต้ม'"></p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-orange-500">
            <p class="text-slate-600 text-sm font-medium mb-2">👤 ผู้บันทึกหลัก</p>
            <p class="text-2xl font-bold text-orange-600" x-text="clearances[0]?.creator_name || '-'"></p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-100 border-b-2 border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-bold text-slate-700">เลขที่รายการ</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-slate-700">วันที่-เวลา</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-slate-700">คณะ</th>
                        <th class="px-6 py-4 text-right text-sm font-bold text-slate-700">น้ำหนัก (กก.)</th>
                        <th class="px-6 py-4 text-right text-sm font-bold text-slate-700">แต้ม</th>
                        <th class="px-6 py-4 text-center text-sm font-bold text-slate-700">ผู้บันทึก</th>
                        <!-- <th class="px-6 py-4 text-center text-sm font-bold text-slate-700">ดำเนินการ</th> -->
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <template x-for="item in clearances" :key="item.waste_clearance_id">
                        <tr class="hover:bg-emerald-50 transition cursor-pointer">
                            <td class="px-6 py-4 text-sm font-mono text-slate-600" x-text="item.waste_clearance_id">
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600" x-text="formatDateTime(item.created_at)"></td>
                            <td class="px-6 py-4 text-sm text-slate-900 font-medium" x-text="item.faculty_name"></td>
                            <td class="px-6 py-4 text-sm text-right font-bold text-emerald-600"
                                x-text="parseFloat(item.waste_clearance_total_weight).toFixed(2)"></td>
                            <td class="px-6 py-4 text-sm text-right text-cyan-600 font-bold"
                                x-text="item.waste_clearance_total_point"></td>
                            <td class="px-6 py-4 text-sm text-center text-slate-700" x-text="item.creator_name"></td>
                            <!-- <td class="px-6 py-4 text-center">
                                <button @click="openDetail(item)"
                                    class="text-emerald-600 hover:underline font-medium">ดูรายละเอียด</button>
                            </td> -->
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>