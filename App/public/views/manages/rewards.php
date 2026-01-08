<div class="space-y-6" x-data="ManageRewards()" x-init="initData()">
    
    <!-- Header Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="app-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">รางวัลทั้งหมด</p>
                    <p class="text-2xl font-bold text-emerald-700" x-text="totalRewards"></p>
                </div>
                <div class="p-3 bg-emerald-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="app-card p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">รายการของรางวัล</h2>
            <button @click="openCreateDialog" 
                class="flex items-center px-4 py-2 bg-emerald-500 text-white rounded-full hover:bg-emerald-600 transition-colors font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                เพิ่มรางวัล
            </button>
        </div>

        <!-- Filters -->
        <div class="mb-4 flex gap-4">
            <input type="text" x-model="filters.search" @input.debounce.500ms="fetchRewards()" 
                placeholder="ค้นหาชื่อรางวัล..." 
                class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <select x-model="filters.active" @change="fetchRewards()" 
                class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                <option value="">สถานะทั้งหมด</option>
                <option value="1">ใช้งาน</option>
                <option value="0">ไม่ใช้งาน</option>
            </select>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-emerald-100 rounded-lg">
                <thead class="bg-emerald-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">รูปภาพ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">ชื่อรางวัล</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">คะแนนที่ใช้</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">จำนวนคงเหลือ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">สถานะ</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <template x-for="(reward, index) in rewards" :key="reward.reward_id">
                        <tr class="hover:bg-emerald-50 transition duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="index + 1"></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <template x-if="reward.reward_image">
                                    <img :src="'/assets/images/rewards/' + reward.reward_image" class="w-16 h-16 object-cover rounded-lg">
                                </template>
                                <template x-if="!reward.reward_image">
                                    <div class="w-16 h-16 bg-emerald-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </template>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="font-medium text-gray-900" x-text="reward.reward_name"></div>
                                <div class="text-gray-500 text-xs" x-text="reward.reward_description"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="reward.reward_point_required"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="reward.reward_stock"></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="reward.reward_active == 1 ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800'" 
                                    class="px-3 py-1 text-xs rounded-full font-medium" x-text="reward.reward_active == 1 ? 'ใช้งาน' : 'ไม่ใช้งาน'"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm space-x-2">
                                <button @click="openEditDialog(reward)" class="text-emerald-600 hover:text-emerald-900 hover:bg-emerald-100 p-2 rounded-full transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button @click="confirmDelete(reward.reward_id)" class="text-red-600 hover:text-red-900 hover:bg-red-100 p-2 rounded-full transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between mt-4">
            <button class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50" 
                :disabled="page <= 1" @click="page--; fetchRewards()">
                ก่อนหน้า
            </button>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-600">หน้า <span x-text="page"></span> จาก <span x-text="totalPages"></span></span>
            </div>
            <button class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50" 
                :disabled="page >= totalPages" @click="page++; fetchRewards()">
                ถัดไป
            </button>
        </div>
    </div>

    <!-- Create/Edit Dialog -->
    <dialog x-show="dialogShow" x-ref="rewardDialog" @click.self="dialogShow = false" @close="dialogShow = false"
        class="fixed inset-0 mx-auto my-auto p-0 bg-transparent z-50"
        x-init="$watch('dialogShow', value => {if (value) $refs.rewardDialog.showModal();else $refs.rewardDialog.close();})">
        <div class="app-card p-6 w-96 max-w-full">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg" x-text="isEditing ? 'แก้ไขรางวัล' : 'เพิ่มรางวัลใหม่'"></h3>
                <button @click="dialogShow = false" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อรางวัล <span class="text-red-500">*</span></label>
                    <input type="text" x-model="form.reward_name" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="ชื่อรางวัล">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">คำอธิบาย</label>
                    <textarea x-model="form.reward_description" rows="3"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="คำอธิบายรางวัล"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">คะแนนที่ใช้ <span class="text-red-500">*</span></label>
                    <input type="number" x-model="form.reward_point_required" min="0"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="จำนวนคะแนน">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">จำนวนคงเหลือ <span class="text-red-500">*</span></label>
                    <input type="number" x-model="form.reward_stock" min="0"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="จำนวนของรางวัล">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">รูปภาพ</label>
                    <input type="file" @change="handleImageUpload($event, 'reward')" accept="image/*"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    <template x-if="imagePreview">
                        <div class="mt-2">
                            <img :src="imagePreview" class="w-32 h-32 object-cover rounded-lg border border-gray-300">
                        </div>
                    </template>
                    <template x-if="form.reward_image && !imagePreview">
                        <div class="mt-2">
                            <img :src="'/assets/images/rewards/' + form.reward_image" class="w-32 h-32 object-cover rounded-lg border border-gray-300">
                        </div>
                    </template>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">สถานะ</label>
                    <select x-model="form.reward_active"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                        <option value="1">ใช้งาน</option>
                        <option value="0">ไม่ใช้งาน</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-2">
                <button @click="dialogShow = false" 
                    class="px-4 py-2 bg-gray-200 rounded-full hover:bg-gray-300 font-medium transition-colors">
                    ยกเลิก
                </button>
                <button @click="submitForm" 
                    class="px-4 py-2 bg-emerald-500 text-white rounded-full hover:bg-emerald-600 font-medium transition-colors">
                    <span x-text="isEditing ? 'บันทึก' : 'เพิ่ม'"></span>
                </button>
            </div>
        </div>
    </dialog>

</div>
