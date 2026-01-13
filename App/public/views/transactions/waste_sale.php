<div class="min-h-screen bg-gray-50 p-4">
    <div class="max-w-7xl mx-auto">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">ระบบขายขยะ (POS)</h1>
            <p class="text-gray-600 mt-2">บันทึกการขายขยะแบบเร็วด้วยแป้นพิมพ์</p>
        </div>

        <!-- Tab Navigation -->
        <div class="flex gap-2 mb-6 border-b border-gray-200">
            <button 
                @click="activeTab = 'batch'"
                :class="['px-6 py-3 font-medium transition', activeTab === 'batch' ? 'text-emerald-600 border-b-2 border-emerald-600' : 'text-gray-600 hover:text-gray-900']"
            >
                📦 บันทึกแบบรายการ (Batch)
            </button>
            <button 
                @click="activeTab = 'single'"
                :class="['px-6 py-3 font-medium transition', activeTab === 'single' ? 'text-emerald-600 border-b-2 border-emerald-600' : 'text-gray-600 hover:text-gray-900']"
            >
                💾 บันทึกแบบเดี่ยว
            </button>
            <button 
                @click="activeTab = 'history'"
                :class="['px-6 py-3 font-medium transition', activeTab === 'history' ? 'text-emerald-600 border-b-2 border-emerald-600' : 'text-gray-600 hover:text-gray-900']"
            >
                📋 ประวัติ
            </button>
        </div>

        <!-- Tab Content -->
        <div x-show="activeTab === 'batch'" x-data="WasteSalePOSHandler()" x-init="init()" class="space-y-6">
            <!-- Batch Entry Form -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Input Column -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow p-6 space-y-4">
                        <h2 class="text-xl font-bold text-gray-900">📝 บันทึกรายการ</h2>
                        
                        <!-- Buyer Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ชื่อผู้ซื้อ (ถ้ามี)</label>
                            <input 
                                x-model="buyerName"
                                type="text"
                                placeholder="ชื่อบริษัท/ผู้ซื้อ"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                            >
                        </div>

                        <!-- Date Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">วันที่ขาย</label>
                            <input 
                                x-model="saleDate"
                                type="date"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                            >
                        </div>

                        <!-- Waste Type and Weight Entry -->
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                            <h3 class="font-semibold text-gray-900 mb-4">🔍 ค้นหาประเภทขยะ</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">รหัสประเภทขยะ (ID) หรือชื่อ</label>
                                    <input 
                                        x-model="currentItemTypeCode"
                                        type="text"
                                        placeholder="พิมพ์รหัสหรือชื่อประเภทขยะ (เช่น 1 หรือ กระดาษ)"
                                        class="w-full px-4 py-2 border border-blue-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-lg font-semibold"
                                        @keyup.enter="() => { $dispatch('focus-weight'); }"
                                    >
                                    <p class="text-xs text-gray-500 mt-1">พิมพ์แล้วกด Tab หรือ Enter เพื่อไปยังช่องน้ำหนัก</p>
                                </div>

                                <!-- Matching Type Display -->
                                <template x-if="currentItemTypeCode && wasteTypes.length">
                                    <div>
                                        <template x-for="type in wasteTypes.filter(t => 
                                            t.waste_type_id.toString() === currentItemTypeCode.trim() || 
                                            t.waste_type_name.toLowerCase().includes(currentItemTypeCode.toLowerCase())
                                        ).slice(0, 3)" :key="type.waste_type_id">
                                            <div class="p-2 bg-white border border-emerald-200 rounded mb-2 cursor-pointer hover:bg-emerald-50"
                                                @click="currentItemTypeId = type.waste_type_id; currentItemTypeName = type.waste_type_name; currentItemTypeCode = ''; $dispatch('focus-weight');">
                                                <div class="font-medium text-emerald-600">ID: <span x-text="type.waste_type_id"></span> - <span x-text="type.waste_type_name"></span></div>
                                                <div class="text-sm text-gray-500">ราคา: <span x-text="type.waste_type_price"></span> บาท/กก.</div>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Weight Entry -->
                        <div class="bg-emerald-50 p-4 rounded-lg border border-emerald-200">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">น้ำหนัก (กก.)</label>
                                <input 
                                    @focus="$dispatch('focus-weight')"
                                    x-model="currentItemWeight"
                                    type="number"
                                    placeholder="0.00"
                                    step="0.01"
                                    min="0"
                                    class="w-full px-4 py-2 border border-emerald-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-lg font-bold"
                                    @keyup.enter="() => {
                                        if (currentItemTypeId && currentItemWeight) {
                                            addItem(currentItemTypeId, currentItemWeight, buyerName);
                                            currentItemTypeId = '';
                                            currentItemWeight = '';
                                            currentItemTypeCode = '';
                                            currentItemTypeName = '';
                                            document.querySelector('[x-ref=typeCodeInput]')?.focus();
                                        }
                                    }"
                                >
                                <p class="text-xs text-gray-500 mt-1">พิมพ์น้ำหนัก แล้วกด Enter เพื่อเพิ่มรายการ</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-2 pt-4">
                            <button 
                                @click="clearItems()"
                                class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg transition"
                            >
                                🗑️ เคลียร์ทั้งหมด
                            </button>
                            <button 
                                @click="removeLastItem()"
                                class="flex-1 px-4 py-2 bg-yellow-200 hover:bg-yellow-300 text-yellow-800 font-medium rounded-lg transition"
                            >
                                ↶ ยกเลิกสุดท้าย
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Summary Column -->
                <div class="lg:col-span-1">
                    <!-- Messages -->
                    <template x-if="errorMessage">
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                            <p class="text-red-700 font-semibold">❌ <span x-text="errorMessage"></span></p>
                        </div>
                    </template>

                    <template x-if="successMessage">
                        <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 mb-4">
                            <p class="text-emerald-700 font-semibold">✅ <span x-text="successMessage"></span></p>
                        </div>
                    </template>

                    <!-- Items List -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <h3 class="font-bold text-lg text-gray-900 mb-4">
                            📋 รายการ (<span x-text="items.length"></span>)
                        </h3>

                        <div class="space-y-2 max-h-96 overflow-y-auto">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="bg-gray-50 p-3 rounded border border-gray-200 flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="font-semibold text-gray-900" x-text="item.wasteTypeName"></div>
                                        <div class="text-sm text-gray-600">
                                            <span x-text="item.waste_sale_weight"></span> กก. @ <span x-text="item.wasteTypePrice"></span> บาท/กก.
                                        </div>
                                        <div class="text-sm font-bold text-emerald-600">
                                            💰 <span x-text="item.waste_sale_actual_price"></span> บาท
                                        </div>
                                    </div>
                                    <button 
                                        @click="removeItem(index)"
                                        class="text-red-500 hover:text-red-700 font-bold"
                                    >
                                        ✕
                                    </button>
                                </div>
                            </template>

                            <div x-show="items.length === 0" class="text-center text-gray-400 py-6">
                                ไม่มีรายการ
                            </div>
                        </div>

                        <!-- Summary -->
                        <div class="mt-4 pt-4 border-t border-gray-200 space-y-2">
                            <div class="flex justify-between text-gray-700">
                                <span>รวมน้ำหนัก:</span>
                                <span class="font-bold" x-text="getTotalWeight() + ' กก.'"></span>
                            </div>
                            <div class="flex justify-between text-emerald-600 text-lg font-bold">
                                <span>รวมเงิน:</span>
                                <span x-text="getTotalRevenue() + ' บาท'"></span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button 
                            @click="submitBatch()"
                            :disabled="items.length === 0 || isSubmitting"
                            class="w-full mt-4 px-4 py-3 bg-emerald-600 hover:bg-emerald-700 disabled:bg-gray-400 text-white font-bold rounded-lg transition text-lg"
                        >
                            <span x-show="!isSubmitting">✅ บันทึก <span x-text="items.length"></span> รายการ</span>
                            <span x-show="isSubmitting">⏳ กำลังบันทึก...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Single Entry Tab -->
        <div x-show="activeTab === 'single'" x-data="WasteSaleSingleFormHandler()" x-init="init()" class="max-w-2xl mx-auto">
            <div class="bg-white rounded-lg shadow p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">💾 บันทึกแบบเดี่ยว</h2>

                <!-- Messages -->
                <template x-if="errorMessage">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                        <p class="text-red-700 font-semibold" x-text="errorMessage"></p>
                    </div>
                </template>

                <template x-if="successMessage">
                    <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 mb-4">
                        <p class="text-emerald-700 font-semibold" x-text="successMessage"></p>
                    </div>
                </template>

                <div class="space-y-6">
                    <!-- Buyer Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ชื่อผู้ซื้อ (ถ้ามี)</label>
                        <input 
                            x-model="buyerName"
                            type="text"
                            placeholder="ชื่อบริษัท/ผู้ซื้อ"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-lg"
                        >
                    </div>

                    <!-- Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">วันที่ขาย</label>
                        <input 
                            x-model="saleDate"
                            type="date"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-lg"
                        >
                    </div>

                    <!-- Type Code -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">รหัสประเภทขยะ (ID) หรือชื่อ</label>
                        <input 
                            x-ref="typeCodeInput"
                            x-model="typeCode"
                            type="text"
                            placeholder="พิมพ์รหัสหรือชื่อประเภทขยะ"
                            class="w-full px-4 py-3 border-2 border-blue-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-xl font-bold"
                            autofocus
                        >
                    </div>

                    <!-- Weight -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">น้ำหนัก (กก.)</label>
                        <input 
                            x-model="weight"
                            type="number"
                            placeholder="0.00"
                            step="0.01"
                            min="0"
                            class="w-full px-4 py-3 border-2 border-emerald-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-xl font-bold"
                        >
                    </div>

                    <!-- Submit -->
                    <button 
                        @click="submitSale()"
                        :disabled="isSubmitting"
                        class="w-full px-4 py-4 bg-emerald-600 hover:bg-emerald-700 disabled:bg-gray-400 text-white font-bold rounded-lg transition text-xl"
                    >
                        <span x-show="!isSubmitting">✅ บันทึก</span>
                        <span x-show="isSubmitting">⏳ กำลังบันทึก...</span>
                    </button>
                </div>

                <div class="mt-8 p-4 bg-blue-50 rounded-lg border border-blue-200 text-sm text-gray-700">
                    <p class="font-semibold mb-2">💡 คำแนะนำการใช้:</p>
                    <ul class="space-y-1 text-xs">
                        <li>• กรอกชื่อผู้ซื้อ (ถ้ามี)</li>
                        <li>• เลือกวันที่ขาย</li>
                        <li>• พิมพ์รหัสหรือชื่อประเภทขยะ</li>
                        <li>• Tab ไปช่องน้ำหนัก และพิมพ์น้ำหนัก</li>
                        <li>• กด Enter หรือคลิกบันทึก</li>
                        <li>• ระบบจะโฟกัสกลับไปช่องรหัสสำหรับบันทึกรายการต่อไป</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- History Tab -->
        <div x-show="activeTab === 'history'" x-data="WasteSaleHistoryHandler()" x-init="init()">
            <!-- Filters -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">🔍 ตัวกรอง</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">วันที่</label>
                        <input 
                            x-model="filterDate"
                            type="date"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                        >
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ประเภทขยะ</label>
                        <select x-model="filterType" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white">
                            <option value="">ทั้งหมด</option>
                            <template x-for="type in wasteTypes" :key="type.waste_type_id">
                                <option :value="type.waste_type_id" x-text="type.waste_type_name"></option>
                            </template>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ชื่อผู้ซื้อ</label>
                        <input 
                            x-model="filterBuyer"
                            type="text"
                            placeholder="ค้นหาผู้ซื้อ"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                        >
                    </div>
                    
                    <div class="flex gap-2 items-end">
                        <button 
                            @click="applyFilters()"
                            class="flex-1 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg"
                        >
                            🔍 ค้นหา
                        </button>
                        <button 
                            @click="clearFilters()"
                            class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg"
                        >
                            🔄 ล้าง
                        </button>
                    </div>
                </div>
            </div>

            <!-- Summary Card -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="text-sm text-gray-600">รวมรายการ</div>
                    <div class="text-2xl font-bold text-blue-600" x-text="total"></div>
                </div>
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <div class="text-sm text-gray-600">รวมน้ำหนัก</div>
                    <div class="text-2xl font-bold text-purple-600" x-text="getTotalWeight() + ' กก.'"></div>
                </div>
                <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                    <div class="text-sm text-gray-600">รวมเงิน</div>
                    <div class="text-2xl font-bold text-emerald-600" x-text="getTotalRevenue() + ' ฿'"></div>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-900">วันที่</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-900">ประเภทขยะ</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-900">น้ำหนัก (กก.)</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-900">ราคา (บาท)</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-900">ผู้ซื้อ</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-900">การดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <template x-for="sale in filteredSales" :key="sale.waste_sale_id">
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-900" x-text="sale.waste_sale_date"></td>
                                <td class="px-4 py-3 text-gray-900" x-text="getWasteTypeName(sale.waste_sale_type_id)"></td>
                                <td class="px-4 py-3 text-right text-gray-900 font-mono" x-text="parseFloat(sale.waste_sale_weight).toFixed(3)"></td>
                                <td class="px-4 py-3 text-right text-emerald-600 font-bold" x-text="parseFloat(sale.waste_sale_actual_price || 0).toFixed(2)"></td>
                                <td class="px-4 py-3 text-gray-900" x-text="sale.waste_sale_buyer || '-'"></td>
                                <td class="px-4 py-3 text-center">
                                    <button 
                                        @click="deleteSale(sale.waste_sale_id)"
                                        class="text-red-500 hover:text-red-700 font-semibold"
                                    >
                                        ลบ
                                    </button>
                                </td>
                            </tr>
                        </template>

                        <tr x-show="sales.length === 0">
                            <td colspan="6" class="px-4 py-6 text-center text-gray-400">
                                ไม่พบรายการ
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div x-show="totalPages > 1" class="bg-gray-50 px-4 py-3 flex justify-between items-center border-t border-gray-200">
                    <button 
                        @click="prevPage()"
                        :disabled="page === 1"
                        class="px-3 py-1 bg-gray-200 hover:bg-gray-300 disabled:bg-gray-100 disabled:cursor-not-allowed rounded text-sm font-medium"
                    >
                        ← ก่อนหน้า
                    </button>
                    
                    <span class="text-sm text-gray-600">
                        หน้า <span x-text="page"></span> / <span x-text="totalPages"></span>
                    </span>
                    
                    <button 
                        @click="nextPage()"
                        :disabled="page === totalPages"
                        class="px-3 py-1 bg-gray-200 hover:bg-gray-300 disabled:bg-gray-100 disabled:cursor-not-allowed rounded text-sm font-medium"
                    >
                        ถัดไป →
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize Alpine data
    document.addEventListener('alpine:init', () => {
        Alpine.data('WasteSalePOSHandler', WasteSalePOSHandler);
        Alpine.data('WasteSaleSingleFormHandler', WasteSaleSingleFormHandler);
        Alpine.data('WasteSaleHistoryHandler', WasteSaleHistoryHandler);
    });
</script>

<style>
    /* Custom scrollbar for item lists */
    .max-h-96::-webkit-scrollbar {
        width: 6px;
    }
    
    .max-h-96::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }
    
    .max-h-96::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    
    .max-h-96::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
