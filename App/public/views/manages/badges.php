<div class="space-y-6" x-data="ManageBadges()" x-init="initData()">
    
    <!-- Header Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="app-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">เหรียญตราทั้งหมด</p>
                    <p class="text-2xl font-bold text-emerald-700" x-text="totalBadges"></p>
                </div>
                <div class="p-3 bg-emerald-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="app-card p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">รายการเหรียญตรา</h2>
            <button @click="openCreateDialog" 
                class="flex items-center px-4 py-2 bg-emerald-500 text-white rounded-full hover:bg-emerald-600 transition-colors font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                เพิ่มเหรียญตรา
            </button>
        </div>

        <!-- Filters -->
        <div class="mb-4 flex gap-4">
            <input type="text" x-model="filters.search" @input.debounce.500ms="fetchBadges()" 
                placeholder="ค้นหาชื่อเหรียญตรา..." 
                class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <select x-model="filters.badge_type" @change="fetchBadges()" 
                class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                <option value="">ประเภททั้งหมด</option>
                <option value="achievement">ความสำเร็จ</option>
                <option value="milestone">เป้าหมาย</option>
                <option value="special">พิเศษ</option>
            </select>
        </div>

        <!-- Grid Layout -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <template x-for="badge in badges" :key="badge.badge_id">
                <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="flex-shrink-0">
                            <template x-if="badge.badge_image">
                                <img :src="'/assets/images/badges/' + badge.badge_image" class="w-20 h-20 object-cover rounded-full border-2 border-amber-400">
                            </template>
                            <template x-if="!badge.badge_image">
                                <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center">
                                    <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                    </svg>
                                </div>
                            </template>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg font-semibold text-gray-900" x-text="badge.badge_name"></h3>
                                <span :class="{
                                    'bg-emerald-100 text-emerald-800': badge.badge_type === 'achievement',
                                    'bg-emerald-100 text-emerald-800': badge.badge_type === 'milestone',
                                    'bg-emerald-100 text-emerald-800': badge.badge_type === 'special'
                                }" class="px-3 py-1 text-xs rounded-full font-medium" x-text="getBadgeTypeText(badge.badge_type)"></span>
                            </div>
                            <p class="text-sm text-gray-500 mt-1" x-text="badge.badge_description"></p>
                        </div>
                    </div>

                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                            <span class="text-gray-600">เงื่อนไข: <span class="font-medium" x-text="badge.badge_condition"></span></span>
                        </div>
                        <div class="flex items-center text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-gray-600">คะแนนโบนัส: <span class="font-medium text-amber-600" x-text="badge.badge_bonus_score"></span></span>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-2 pt-4 border-t border-gray-200">
                        <button @click="openEditDialog(badge)" 
                            class="px-3 py-1 text-sm text-amber-600 hover:bg-amber-50 rounded-lg transition-colors">
                            แก้ไข
                        </button>
                        <button @click="confirmDelete(badge.badge_id)" 
                            class="px-3 py-1 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                            ลบ
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between mt-6">
            <button class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50" 
                :disabled="page <= 1" @click="page--; fetchBadges()">
                ก่อนหน้า
            </button>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-600">หน้า <span x-text="page"></span> จาก <span x-text="totalPages"></span></span>
            </div>
            <button class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50" 
                :disabled="page >= totalPages" @click="page++; fetchBadges()">
                ถัดไป
            </button>
        </div>
    </div>

    <!-- Create/Edit Dialog -->
    <dialog x-show="dialogShow" x-ref="badgeDialog" @click.self="dialogShow = false" @close="dialogShow = false"
        class="fixed inset-0 mx-auto my-auto p-0 bg-transparent z-50"
        x-init="$watch('dialogShow', value => {if (value) $refs.badgeDialog.showModal();else $refs.badgeDialog.close();})">
        <div class="bg-white p-6 rounded-lg shadow-xl w-96 max-w-full">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg" x-text="isEditing ? 'แก้ไขเหรียญตรา' : 'เพิ่มเหรียญตราใหม่'"></h3>
                <button @click="dialogShow = false" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อเหรียญตรา <span class="text-red-500">*</span></label>
                    <input type="text" x-model="form.badge_name" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                        placeholder="ชื่อเหรียญตรา">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">คำอธิบาย</label>
                    <textarea x-model="form.badge_description" rows="3"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                        placeholder="คำอธิบายเหรียญตรา"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ประเภท <span class="text-red-500">*</span></label>
                    <select x-model="form.badge_type"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                        <option value="">เลือกประเภท</option>
                        <option value="achievement">ความสำเร็จ</option>
                        <option value="milestone">เป้าหมาย</option>
                        <option value="special">พิเศษ</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">เงื่อนไข <span class="text-red-500">*</span></label>
                    <input type="text" x-model="form.badge_condition"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                        placeholder="เช่น ฝากขยะ 10 ครั้ง">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">คะแนนโบนัส</label>
                    <input type="number" x-model="form.badge_bonus_score" min="0"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                        placeholder="คะแนนที่ได้รับเมื่อปลดล็อค">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">รูปภาพ</label>
                    <input type="file" @change="handleImageUpload($event, 'badge')" accept="image/*"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    <template x-if="imagePreview">
                        <div class="mt-2">
                            <img :src="imagePreview" class="w-32 h-32 object-cover rounded-lg border border-gray-300">
                        </div>
                    </template>
                    <template x-if="form.badge_image && !imagePreview">
                        <div class="mt-2">
                            <img :src="'/assets/images/badges/' + form.badge_image" class="w-32 h-32 object-cover rounded-lg border border-gray-300">
                        </div>
                    </template>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-2">
                <button @click="dialogShow = false" 
                    class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                    ยกเลิก
                </button>
                <button @click="submitForm" 
                    class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600">
                    <span x-text="isEditing ? 'บันทึก' : 'เพิ่ม'"></span>
                </button>
            </div>
        </div>
    </dialog>

</div>
