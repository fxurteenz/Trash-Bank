<div x-data="WasteDepositPOSHandler()" x-init="init()" class="space-y-6">
    <!-- Page Intro -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 mb-2">🏪 ระบบฝากขยะแบบ POS</h1>
        <p class="text-slate-600 text-lg">บันทึกการฝากขยะอย่างรวดเร็ว - ใช้เพียงแป้นพิมพ์เท่านั้น</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Input Area -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Member Selection -->
            <div class="bg-white rounded-xl shadow-md p-6 card-hover">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-slate-900">
                        <span x-show="!currentMember">🔍 ค้นหาสมาชิก</span>
                        <span x-show="currentMember" class="text-emerald-600">✅ ยืนยันสมาชิก</span>
                    </h2>
                    <button x-show="currentMember" @click="resetMember()" 
                        class="px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                        🔄 เปลี่ยน
                    </button>
                </div>

                <div x-show="!currentMember" class="space-y-3">
                    <label class="block text-sm font-semibold text-slate-700">รหัสนักศึกษา / รหัสสมาชิก</label>
                    <div class="flex gap-2">
                        <input x-ref="memberInput" x-model="memberSearch" @keydown.enter="searchMember()"
                            type="text" placeholder="กรอกรหัสแล้วกด Enter"
                            class="flex-1 px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition">
                        <button @click="searchMember()"
                            class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium transition-colors">
                            ค้นหา
                        </button>
                    </div>
                    <p class="text-xs text-slate-500">💡 กด <kbd class="bg-slate-100 px-2 py-1 rounded">Enter</kbd> เพื่อค้นหา</p>
                </div>

                <div x-show="currentMember" class="bg-gradient-to-br from-emerald-50 to-emerald-100 border-2 border-emerald-300 rounded-lg p-5">
                    <div class="space-y-2">
                        <p class="text-xs font-semibold text-emerald-700 uppercase tracking-wider">สมาชิก</p>
                        <p class="text-2xl font-bold text-slate-900" x-text="currentMember?.member_name"></p>
                        <div class="flex gap-4 text-sm text-slate-600">
                            <span>ID: <span class="font-semibold" x-text="currentMember?.member_studentId"></span></span>
                            <span>แต้ม: <span class="font-bold text-emerald-600" x-text="currentMember?.member_point"></span></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Item Entry Form -->
            <div x-show="currentMember" class="bg-white rounded-xl shadow-md p-6 card-hover">
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
                    <div class="col-span-4">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">น้ำหนัก (กก.)</label>
                        <input x-ref="weightInput" x-model="itemForm.weight"
                            @keydown.enter="addItem()" @keydown.tab.prevent="addItem(); $refs.wasteCodeInput.focus()"
                            type="number" step="0.01" min="0.01" placeholder="0.00"
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                    </div>

                    <!-- Add Button -->
                    <div class="col-span-3">
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
                <h2 class="text-2xl font-bold text-slate-900 mb-4">🛒 รายการที่บันทึก <span class="text-blue-600" x-text="items.length"></span></h2>
                
                <div class="space-y-2 max-h-96 overflow-y-auto">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex items-center justify-between p-4 bg-slate-50 hover:bg-slate-100 rounded-lg transition group">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <span class="text-lg font-bold text-slate-400 w-8 text-center" x-text="index + 1"></span>
                                    <div>
                                        <p class="font-semibold text-slate-900" x-text="item.waste_type_name"></p>
                                        <p class="text-sm text-slate-600" x-text="'น้ำหนัก: ' + item.weight.toFixed(2) + ' กก.'"></p>
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
                        <p class="text-emerald-100 text-sm mb-1">คะแนนที่จะได้รับ</p>
                        <p class="text-4xl font-bold" x-text="totalPoints.toFixed(0)"></p>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-6 space-y-3">
                    <button @click="submitTransaction()"
                        :disabled="items.length === 0 || isSubmitting"
                        :class="items.length === 0 || isSubmitting ? 'bg-emerald-700 opacity-50 cursor-not-allowed' : 'bg-white hover:bg-slate-50 text-emerald-600'"
                        class="w-full px-6 py-4 rounded-lg font-bold text-lg transition-colors">
                        <span x-show="!isSubmitting">✅ บันทึกทั้งหมด</span>
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
                        <span class="text-slate-600">ทำรายการ</span>
                        <span class="font-bold text-slate-900" x-text="todayStats.count + ' ครั้ง'"></span>
                    </div>
                    <div class="h-px bg-slate-200"></div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-600">น้ำหนักรวม</span>
                        <span class="font-bold text-slate-900" x-text="todayStats.weight.toFixed(2) + ' กก.'"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Keyboard Shortcuts -->
    <div @keydown.ctrl.enter.window="submitTransaction()"></div>
</div>

<script>
function WasteDepositPOSHandler() {
    return {
        memberSearch: '',
        currentMember: null,
        itemForm: { wasteCode: '', weight: '' },
        wasteTypes: [],
        filteredWasteTypes: [],
        selectedWasteType: null,
        items: [],
        isSubmitting: false,
        todayStats: { count: 0, weight: 0 },

        get totalWeight() {
            return this.items.reduce((sum, item) => sum + parseFloat(item.weight || 0), 0);
        },

        get totalPoints() {
            return this.items.reduce((sum, item) => {
                const price = parseFloat(item.waste_type_price || 0);
                const weight = parseFloat(item.weight || 0);
                return sum + (price * weight);
            }, 0);
        },

        async init() {
            await this.loadWasteTypes();
            await this.loadTodayStats();
            this.focusMemberInput();
        },

        focusMemberInput() {
            this.$nextTick(() => {
                if (this.$refs.memberInput) {
                    this.$refs.memberInput.focus();
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
                this.showNotification('ไม่สามารถโหลดข้อมูลชนิดขยะได้', 'error');
            }
        },

        async searchMember() {
            if (!this.memberSearch.trim()) {
                this.showNotification('กรุณากรอกรหัสสมาชิก', 'warning');
                return;
            }

            try {
                const response = await fetch(`/api/members?search=${encodeURIComponent(this.memberSearch)}`);
                const result = await response.json();

                if (result.success && result.result?.data?.length > 0) {
                    this.currentMember = result.result.data[0];
                    this.memberSearch = '';
                    this.showNotification('✅ พบสมาชิก: ' + this.currentMember.member_name, 'success');
                    
                    this.$nextTick(() => {
                        if (this.$refs.wasteCodeInput) {
                            this.$refs.wasteCodeInput.focus();
                        }
                    });
                } else {
                    this.showNotification('❌ ไม่พบสมาชิก กรุณาลองใหม่', 'error');
                }
            } catch (error) {
                console.error('Error searching member:', error);
                this.showNotification('เกิดข้อผิดพลาดในการค้นหา', 'error');
            }
        },

        resetMember() {
            this.currentMember = null;
            this.items = [];
            this.clearItemForm();
            this.focusMemberInput();
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
            }
        },

        addItem() {
            if (!this.itemForm.wasteCode || !this.selectedWasteType) {
                this.showNotification('❌ กรุณาเลือกชนิดขยะ', 'error');
                this.$refs.wasteCodeInput.focus();
                return;
            }

            if (!this.itemForm.weight || parseFloat(this.itemForm.weight) <= 0) {
                this.showNotification('❌ กรุณากรอกน้ำหนัก', 'error');
                this.$refs.weightInput.focus();
                return;
            }

            this.items.push({
                waste_type_id: this.selectedWasteType.waste_type_id,
                waste_type_name: this.selectedWasteType.waste_type_name,
                waste_type_price: this.selectedWasteType.waste_type_price,
                weight: parseFloat(this.itemForm.weight)
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
            this.itemForm.wasteCode = '';
            this.itemForm.weight = '';
            this.selectedWasteType = null;
            this.filteredWasteTypes = this.wasteTypes;
        },

        async submitTransaction() {
            if (this.items.length === 0) {
                this.showNotification('❌ ไม่มีรายการที่จะบันทึก', 'warning');
                return;
            }

            if (!this.currentMember) {
                this.showNotification('❌ กรุณาเลือกสมาชิก', 'warning');
                return;
            }

            this.isSubmitting = true;

            try {
                const promises = this.items.map(item => 
                    fetch('/api/waste_transactions', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            member_id: this.currentMember.member_id,
                            waste_type_id: item.waste_type_id,
                            waste_transaction_weight: item.weight
                        })
                    })
                );

                const results = await Promise.all(promises);
                const allSuccess = results.every(r => r.ok);

                if (allSuccess) {
                    this.showNotification(`✅ บันทึก ${this.items.length} รายการสำเร็จ!`, 'success');
                    await this.loadTodayStats();
                    this.items = [];
                    this.resetMember();
                } else {
                    this.showNotification('⚠️ บางรายการบันทึกไม่สำเร็จ', 'error');
                }
            } catch (error) {
                console.error('Error submitting:', error);
                this.showNotification('❌ เกิดข้อผิดพลาดในการบันทึก', 'error');
            } finally {
                this.isSubmitting = false;
            }
        },

        cancelAll() {
            if (confirm('ต้องการยกเลิกรายการทั้งหมดใช่หรือไม่?')) {
                this.items = [];
                this.clearItemForm();
                this.showNotification('❌ ยกเลิกทั้งหมดแล้ว', 'info');
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
                    this.todayStats.weight = data.reduce((sum, t) => sum + parseFloat(t.waste_transaction_weight || 0), 0);
                }
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        },

        showNotification(message, type = 'info') {
            const emoji = { success: '✅', error: '❌', warning: '⚠️', info: 'ℹ️' };
            console.log(`${emoji[type]} ${message}`);
        }
    };
}
</script>

<style>
kbd {
    font-family: monospace;
    font-size: 0.85em;
}
</style>
