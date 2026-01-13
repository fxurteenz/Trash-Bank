<div x-data="WasteDepositHandler()" x-init="init()" class="space-y-6">
    <!-- Page Intro -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 mb-2">♻️ ฝากขยะ</h1>
        <p class="text-slate-600 text-lg">บันทึกการฝากขยะ - ใช้คีย์บอร์ดเพียงอย่างเดียว</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Input Area -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Depositor Info -->
            <div class="bg-white rounded-xl shadow-md p-6 card-hover">
                <h2 class="text-2xl font-bold text-slate-900 mb-5">👤 ข้อมูลผู้ฝาก</h2>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">ผู้ฝาก (เบอร์โทร/รหัส)</label>
                    <input x-ref="depositorInput" x-model="depositor"
                        @keydown.tab.prevent="$refs.categorySelect.focus()"
                        type="text" placeholder="กรอกเบอร์โทรศัพท์หรือรหัสประจำตัว"
                        class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                    <p x-show="memberInfo" class="text-sm text-emerald-600 mt-2 font-medium" x-text="memberInfo"></p>
                </div>
            </div>

            <!-- Item Entry -->
            <div class="bg-white rounded-xl shadow-md p-6 card-hover">
                <h2 class="text-2xl font-bold text-slate-900 mb-5">📝 เพิ่มรายการขยะ</h2>
                
                <div class="grid grid-cols-12 gap-3 items-end mb-4">
                    <!-- Category -->
                    <div class="col-span-4">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">หมวดหมู่</label>
                        <select x-ref="categorySelect" x-model="itemForm.category_id" @change="loadWasteTypes()"
                            @keydown.tab.prevent="$refs.typeSelect.focus()"
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition bg-white">
                            <option value="">-- เลือก --</option>
                            <template x-for="cat in categories" :key="cat.waste_category_id">
                                <option :value="cat.waste_category_id" x-text="cat.waste_category_name"></option>
                            </template>
                        </select>
                    </div>

                    <!-- Type -->
                    <div class="col-span-4">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">ประเภท</label>
                        <select x-ref="typeSelect" x-model="itemForm.type_id" :disabled="!itemForm.category_id"
                            @keydown.tab.prevent="$refs.weightInput.focus()"
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition bg-white disabled:bg-slate-100 disabled:cursor-not-allowed">
                            <option value="">-- เลือก --</option>
                            <template x-for="type in wasteTypes" :key="type.waste_type_id">
                                <option :value="type.waste_type_id" x-text="type.waste_type_name"></option>
                            </template>
                        </select>
                    </div>

                    <!-- Weight -->
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">น้ำหนัก (กก.)</label>
                        <input x-ref="weightInput" x-model="itemForm.weight"
                            @keydown.enter="addItem()" @keydown.tab.prevent="addItem(); $refs.categorySelect.focus()"
                            type="number" step="0.01" min="0.01" placeholder="0.00"
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                    </div>

                    <!-- Add Button -->
                    <div class="col-span-2">
                        <button @click="addItem()"
                            class="w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors">
                            ➕ เพิ่ม
                        </button>
                    </div>
                </div>

                <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-800">
                    💡 หมวด → <kbd class="bg-white px-2 py-1 rounded border">Tab</kbd> → 
                    ประเภท → <kbd class="bg-white px-2 py-1 rounded border">Tab</kbd> → 
                    น้ำหนัก → <kbd class="bg-white px-2 py-1 rounded border">Enter</kbd> เพื่อเพิ่ม
                </div>
            </div>

            <!-- Items List -->
            <div x-show="items.length > 0" class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-2xl font-bold text-slate-900 mb-4">📦 รายการที่ฝาก <span class="text-blue-600" x-text="items.length"></span></h2>
                
                <div class="space-y-2 max-h-96 overflow-y-auto">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex items-center justify-between p-4 bg-slate-50 hover:bg-slate-100 rounded-lg transition group">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <span class="text-lg font-bold text-slate-400 w-8 text-center" x-text="index + 1"></span>
                                    <div class="flex-1">
                                        <p class="font-semibold text-slate-900" x-text="item.type_name"></p>
                                        <p class="text-sm text-slate-600" x-text="item.weight.toFixed(2) + ' กก.'"></p>
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
                        <p class="text-emerald-100 text-sm mb-1">คะแนนที่จะได้</p>
                        <p class="text-4xl font-bold" x-text="totalPoints.toFixed(0)"></p>
                    </div>
                </div>

                <!-- Submit Button -->
                <button @click="submitDeposit()"
                    :disabled="items.length === 0 || !depositor || isSubmitting"
                    :class="items.length === 0 || !depositor || isSubmitting ? 'bg-emerald-700 opacity-50 cursor-not-allowed' : 'bg-white hover:bg-slate-50 text-emerald-600'"
                    class="w-full px-6 py-4 rounded-lg font-bold text-lg transition-colors mt-6">
                    <span x-show="!isSubmitting">✅ บันทึกการฝาก</span>
                    <span x-show="isSubmitting">⏳ กำลังบันทึก...</span>
                </button>

                <button @click="cancelAll()" :disabled="items.length === 0"
                    class="w-full px-6 py-3 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed mt-3">
                    ❌ ยกเลิกทั้งหมด
                </button>

                <p class="text-center text-emerald-100 text-xs mt-4">กด <kbd class="bg-emerald-600 px-2 py-1 rounded">Ctrl+Enter</kbd> เพื่อบันทึก</p>
            </div>

            <!-- Daily Stats -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">📈 สถิติวันนี้</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-600">ครั้งที่ฝาก</span>
                        <span class="font-bold text-slate-900" x-text="todayStats.count"></span>
                    </div>
                    <div class="h-px bg-slate-200"></div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-600">น้ำหนักรวม</span>
                        <span class="font-bold text-slate-900" x-text="todayStats.weight.toFixed(2) + ' กก.'"></span>
                    </div>
                    <div class="h-px bg-slate-200"></div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-600">คะแนนรวม</span>
                        <span class="font-bold text-emerald-600" x-text="todayStats.points.toFixed(0)"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Keyboard Shortcuts -->
    <div @keydown.ctrl.enter.window="submitDeposit()"></div>
</div>

<script>
function WasteDepositHandler() {
    return {
        depositor: '',
        memberInfo: '',
        itemForm: { category_id: '', type_id: '', weight: '' },
        categories: [],
        wasteTypes: [],
        items: [],
        isSubmitting: false,
        todayStats: { count: 0, weight: 0, points: 0 },

        get totalWeight() {
            return this.items.reduce((sum, item) => sum + parseFloat(item.weight || 0), 0);
        },

        get totalPoints() {
            return this.items.reduce((sum, item) => sum + (parseFloat(item.weight || 0) * 10), 0);
        },

        async init() {
            await this.loadCategories();
            await this.loadTodayStats();
            this.$nextTick(() => {
                if (this.$refs.depositorInput) {
                    this.$refs.depositorInput.focus();
                }
            });
        },

        async loadCategories() {
            try {
                const response = await fetch('/api/waste_categories');
                const result = await response.json();
                if (result.success) {
                    this.categories = result.result || [];
                }
            } catch (error) {
                console.error('Error loading categories:', error);
            }
        },

        async loadWasteTypes() {
            if (!this.itemForm.category_id) {
                this.wasteTypes = [];
                return;
            }
            try {
                const response = await fetch(`/api/waste_types?category=${this.itemForm.category_id}`);
                const result = await response.json();
                if (result.success) {
                    this.wasteTypes = result.result || [];
                }
            } catch (error) {
                console.error('Error loading waste types:', error);
                this.wasteTypes = [];
            }
        },

        addItem() {
            if (!this.itemForm.category_id) {
                alert('❌ กรุณาเลือกหมวดหมู่');
                this.$refs.categorySelect.focus();
                return;
            }
            if (!this.itemForm.type_id) {
                alert('❌ กรุณาเลือกประเภท');
                this.$refs.typeSelect.focus();
                return;
            }
            if (!this.itemForm.weight || parseFloat(this.itemForm.weight) <= 0) {
                alert('❌ กรุณากรอกน้ำหนัก');
                this.$refs.weightInput.focus();
                return;
            }

            const wasteType = this.wasteTypes.find(t => t.waste_type_id == this.itemForm.type_id);
            if (!wasteType) {
                alert('❌ ไม่พบข้อมูลประเภทขยะ');
                return;
            }

            this.items.push({
                type_id: this.itemForm.type_id,
                type_name: wasteType.waste_type_name,
                weight: parseFloat(this.itemForm.weight)
            });

            this.clearItemForm();
            this.$refs.categorySelect.focus();
        },

        removeItem(index) {
            this.items.splice(index, 1);
        },

        clearItemForm() {
            this.itemForm.category_id = '';
            this.itemForm.type_id = '';
            this.itemForm.weight = '';
            this.wasteTypes = [];
        },

        async submitDeposit() {
            if (!this.depositor) {
                alert('❌ กรุณากรอกข้อมูลผู้ฝาก');
                this.$refs.depositorInput.focus();
                return;
            }
            if (this.items.length === 0) {
                alert('❌ ไม่มีรายการที่จะบันทึก');
                return;
            }

            this.isSubmitting = true;

            try {
                const payload = {
                    depositor_member: this.depositor,
                    items: this.items.map(item => ({
                        waste_type_id: item.type_id,
                        deposit_weight: item.weight
                    }))
                };

                const response = await fetch('/api/waste_transactions', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (result.success) {
                    alert(`✅ บันทึก ${this.items.length} รายการสำเร็จ!`);
                    await this.loadTodayStats();
                    this.items = [];
                    this.clearItemForm();
                    this.depositor = '';
                    this.memberInfo = '';
                    this.$refs.depositorInput.focus();
                } else {
                    alert('⚠️ บันทึกไม่สำเร็จ: ' + (result.message || 'ข้อผิดพลาดไม่ทราบ'));
                }
            } catch (error) {
                console.error('Error submitting:', error);
                alert('❌ เกิดข้อผิดพลาดในการบันทึก');
            } finally {
                this.isSubmitting = false;
            }
        },

        cancelAll() {
            if (this.items.length === 0) return;
            if (confirm('ต้องการยกเลิกรายการทั้งหมดใช่หรือไม่?')) {
                this.items = [];
                this.clearItemForm();
            }
        },

        async loadTodayStats() {
            try {
                const today = new Date().toISOString().split('T')[0];
                const response = await fetch(`/api/waste_transactions?date=${today}`);
                const result = await response.json();

                if (result.success) {
                    const data = result.result?.data || [];
                    this.todayStats.count = data.length;
                    this.todayStats.weight = data.reduce((sum, t) => sum + parseFloat(t.deposit_weight || 0), 0);
                    this.todayStats.points = this.todayStats.weight * 10;
                }
            } catch (error) {
                console.error('Error loading today stats:', error);
            }
        }
    };
}
</script>
