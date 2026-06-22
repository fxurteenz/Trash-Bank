<div class="flex flex-col w-full space-y-6" x-data="ManageRewards()" x-init="initData()">
    <div class="flex items-center gap-4 mb-6">
        <button @click="window.history.back()"
            class="text-gray-500 hover:text-gray-700 transition cursor-pointer hover:scale-105 active:scale-95">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </button>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">ของรางวัลทั้งหมด
            </h1>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 shrink-0">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">รางวัลทั้งหมด</p>
                    <p class="text-2xl font-bold text-emerald-700" x-text="total"></p>
                </div>
                <div class="p-3 bg-emerald-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">ยังไม่จัดหมวดหมู่</p>
                    <p class="text-2xl font-bold text-emerald-700" x-text="uncatTotal"></p>
                </div>
                <div class="p-3 bg-emerald-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">จัดหมวดหมู่แล้ว</p>
                    <p class="text-2xl font-bold text-emerald-700" x-text="total - uncatTotal"></p>
                </div>
                <div class="p-3 bg-emerald-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-sm rounded-lg p-6 flex flex-col min-h-[calc(100vh-6rem)]">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">รายการของรางวัล</h2>
            <button @click="openCreateDialog"
                class="flex items-center px-4 py-2 bg-emerald-500 text-white rounded-full hover:bg-emerald-600 transition-colors font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                เพิ่มรางวัล
            </button>
        </div>

        <div class="mb-4 flex gap-4 shrink-0">
            <input type="text" x-model="filters.search" @input.debounce.500ms="fetchRewards()"
                placeholder="ค้นหาชื่อรางวัล..."
                class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
        </div>

        <div class="flex flex-col flex-1 min-h-0">
            <div class="overflow-auto flex-1 border border-gray-200 rounded-lg">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-50 sticky top-0 shadow-sm">
                        <tr>
                            <th class="px-2 py-3 text-left text-xs font-medium uppercase tracking-wider border-b">#
                            </th>
                            <th class="px-2 py-3 text-left text-xs font-medium uppercase tracking-wider border-b">
                                รูปภาพ
                            </th>
                            <th class="px-2 py-3 text-left text-xs font-medium uppercase tracking-wider border-b">
                                ชื่อรางวัล
                            </th>
                            <th class="px-2 py-3 text-left text-xs font-medium uppercase tracking-wider border-b">
                                หมวดหมู่
                            </th>
                            <th class="px-2 py-3 text-left text-xs font-medium uppercase tracking-wider border-b">
                                คะแนนที่ใช้</th>
                            <th class="px-2 py-3 text-left text-xs font-medium uppercase tracking-wider border-b">
                                คะแนนหลังลด(โปรโมชั่น)</th>
                            <th class="px-2 py-3 text-left text-xs font-medium uppercase tracking-wider border-b">
                                จำนวนคงเหลือ</th>
                            <th class="px-2 py-3 text-left text-xs font-medium uppercase tracking-wider border-b">
                                สถานะ
                            </th>
                            <th class="px-2 py-3 text-center text-xs font-medium uppercase tracking-wider border-b">
                                จัดการ
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <template x-for="(reward, index) in rewards" :key="reward.donation_item_id">
                            <tr :class="{
                                        'bg-emerald-50': editingRewardId === reward.donation_item_id,
                                        'bg-rose-50 hover:bg-rose-100': editingRewardId !== reward.donation_item_id && reward.donation_item_category_id == 1,
                                        'hover:bg-gray-50': editingRewardId !== reward.donation_item_id && reward.donation_item_category_id != 1
                                    }" class="transition duration-200">
                                <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-900"
                                    x-text="((page - 1) * limit) + index + 1"></td>
                                <td class="px-2 py-4 whitespace-nowrap">
                                    <div x-show="editingRewardId !== reward.donation_item_id">
                                        <template x-if="reward.donation_item_image">
                                            <img :src="'/assets/images/donation_items/' + reward.donation_item_image"
                                                class="w-16 h-16 object-cover rounded-lg">
                                        </template>
                                        <template x-if="!reward.donation_item_image">
                                            <div
                                                class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        </template>
                                    </div>
                                    <div x-show="editingRewardId === reward.donation_item_id" x-cloak
                                        class="relative group cursor-pointer w-16 h-16"
                                        @click="$el.querySelector('input[type=file]').click()">
                                        <template x-if="editImagePreview">
                                            <img :src="editImagePreview"
                                                class="w-16 h-16 object-cover rounded-lg border border-emerald-400">
                                        </template>
                                        <template x-if="!editImagePreview && reward.donation_item_image">
                                            <img :src="'/assets/images/donation_items/' + reward.donation_item_image"
                                                class="w-16 h-16 object-cover rounded-lg opacity-70 group-hover:opacity-50 transition-opacity">
                                        </template>
                                        <template x-if="!editImagePreview && !reward.donation_item_image">
                                            <div
                                                class="w-16 h-16 bg-emerald-50 rounded-lg flex items-center justify-center group-hover:bg-emerald-100 transition-colors border border-dashed border-emerald-300">
                                                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                            </div>
                                        </template>
                                        <div
                                            class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg class="w-6 h-6 text-white drop-shadow-md" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 16a4 4 0 014-4h.01M16 12a4 4 0 014 4m-4-4l-4-4m0 0l-4 4m4-4v12" />
                                            </svg>
                                        </div>
                                        <input type="file" @change="handleInlineImageUpload($event)" accept="image/*"
                                            class="hidden">
                                    </div>
                                </td>
                                <td class="px-2 py-4 text-sm">
                                    <div x-show="editingRewardId !== reward.donation_item_id"
                                        class="font-medium text-gray-900" x-text="reward.donation_item_name"></div>
                                    <div x-show="editingRewardId === reward.donation_item_id" x-cloak>
                                        <input type="text" x-model="editForm.donation_item_name"
                                            class="w-full border border-gray-300 rounded p-1 text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                                    </div>
                                </td>
                                <td class="px-2 py-4 text-sm">
                                    <div x-show="editingRewardId !== reward.donation_item_id" class="text-gray-600">
                                        <span x-text="getCategoryName(reward.donation_item_category_id)"></span>
                                    </div>
                                    <div x-show="editingRewardId === reward.donation_item_id" x-cloak>
                                        <select x-model="editForm.donation_item_category_id"
                                            class="w-full border border-gray-300 rounded p-1 text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                                            <option value="1">-- ไม่จัดหมวดหมู่ --</option>
                                            <template x-for="cat in categories" :key="cat.donation_item_category_id">
                                                <option :value="cat.donation_item_category_id"
                                                    x-text="cat.donation_item_category_name"></option>
                                            </template>
                                        </select>
                                    </div>
                                </td>
                                <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div x-show="editingRewardId !== reward.donation_item_id"
                                        x-text="reward.donation_item_redeem_point || '-'"></div>
                                    <div x-show="editingRewardId === reward.donation_item_id" x-cloak>
                                        <input type="number" min="0" x-model="editForm.donation_item_redeem_point"
                                            class="w-20 border border-gray-300 rounded p-1 text-sm focus:ring-2 focus:ring-emerald-500 outline-none"
                                            placeholder="แต้ม">
                                    </div>
                                </td>
                                <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div x-show="editingRewardId !== reward.donation_item_id"
                                        x-text="reward.donation_item_discount_point || '0'"></div>
                                    <div x-show="editingRewardId === reward.donation_item_id" x-cloak>
                                        <input type="number" min="0" x-model="editForm.donation_item_discount_point"
                                            class="w-20 border border-gray-300 rounded p-1 text-sm focus:ring-2 focus:ring-emerald-500 outline-none"
                                            placeholder="คะแนนหลังลด">
                                    </div>
                                </td>
                                <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div x-show="editingRewardId !== reward.donation_item_id"
                                        x-text="reward.donation_item_amount"></div>
                                    <div x-show="editingRewardId === reward.donation_item_id" x-cloak>
                                        <input type="number" x-model="editForm.donation_item_amount" min="0"
                                            class="w-16 border border-gray-300 rounded p-1 text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                                    </div>
                                </td>
                                <td class="px-2 py-4 whitespace-nowrap">
                                    <template x-if="editingRewardId !== reward.donation_item_id">
                                        <span @click.stop="toggleStatus(reward)"
                                            :class="reward.donation_item_available == 1 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'"
                                            class="px-3 py-1 text-xs rounded-full font-medium cursor-pointer hover:opacity-80 transition-opacity"
                                            title="คลิกเพื่อสลับสถานะ"
                                            x-text="reward.donation_item_available == 1 ? 'เปิดแลก' : 'ไม่เปิดแลก'"></span>
                                    </template>
                                    <template x-if="editingRewardId === reward.donation_item_id" x-cloak>
                                        <select x-model="editForm.donation_item_available"
                                            class="w-24 border border-gray-300 rounded p-1 text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                                            <option value="1">เปิดแลก</option>
                                            <option value="0">ไม่เปิดแลก</option>
                                        </select>
                                    </template>
                                </td>
                                <td class="px-2 py-4 whitespace-nowrap text-center text-sm space-x-2">
                                    <div x-show="editingRewardId !== reward.donation_item_id"
                                        class="flex justify-center space-x-2">
                                        <button @click.stop="startEdit(reward)"
                                            class="text-emerald-600 hover:text-emerald-900 hover:bg-emerald-100 p-2 rounded-full transition"
                                            title="แก้ไข">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button @click.stop="confirmDelete(reward.donation_item_id)"
                                            class="text-red-600 hover:text-red-900 hover:bg-red-100 p-2 rounded-full transition"
                                            title="ลบ">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div x-show="editingRewardId === reward.donation_item_id"
                                        class="flex justify-center space-x-2" x-cloak>
                                        <button @click.stop="saveEdit()"
                                            class="bg-emerald-500 text-white p-2 rounded-md hover:bg-emerald-600 transition"
                                            title="บันทึก">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                        <button @click.stop="cancelEdit()"
                                            class="bg-gray-400 text-white p-2 rounded-md hover:bg-gray-500 transition"
                                            title="ยกเลิก">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template x-if="rewards.length === 0">
                            <tr>
                                <td colspan="9" class="px-2 py-4 text-center text-gray-500">ไม่มีข้อมูล</td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div class="flex items-center justify-between mt-4 shrink-0">
                <button class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50"
                    :disabled="page <= 1" @click="page--; fetchRewardsList()">
                    ก่อนหน้า
                </button>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-600">หน้า <span x-text="page"></span> จาก <span
                            x-text="totalPages"></span></span>
                </div>
                <button class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50"
                    :disabled="page >= totalPages" @click="page++; fetchRewardsList()">
                    ถัดไป
                </button>
            </div>
        </div>

    </div>

    <div x-show="dialogShow" x-cloak @click.self="dialogShow = false"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-white shadow-sm rounded-lg p-6 w-96 max-w-full relative">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg">เพิ่มรางวัลใหม่</h3>
                <button @click="dialogShow = false" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อของรางวัล <span
                            class="text-red-500">*</span></label>
                    <input type="text" x-model="form.donation_item_name"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="ชื่อรางวัล">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">หมวดหมู่</label>
                    <select x-model="form.donation_item_category_id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                        <option value="1">-- ไม่จัดหมวดหมู่ --</option>
                        <template x-for="cat in categories" :key="cat.donation_item_category_id">
                            <option :value="cat.donation_item_category_id" x-text="cat.donation_item_category_name">
                            </option>
                        </template>
                    </select>
                </div>

                <div class="flex gap-2">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            แต้มที่ใช้แลก
                            <span class="text-red-500">*</span></label>
                        <input type="number" min="0" x-model="form.donation_item_redeem_point"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                            placeholder="แต้มที่ใช้แลก">
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            ส่วนลด (คะแนน)</label>
                        <input type="number" min="0" x-model="form.donation_item_discount_point"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                            placeholder="ส่วนลด">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">จำนวน <span
                            class="text-red-500">*</span></label>
                    <input type="number" x-model="form.donation_item_amount" min="0"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="จำนวนของรางวัล">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">สถานะ</label>
                    <select x-model="form.donation_item_available"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                        <option value="1">เปิดแลก</option>
                        <option value="0" selected>ไม่เปิดแลก</option>
                    </select>
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
                    <template x-if="form.donation_item_image && !imagePreview">
                        <div class="mt-2">
                            <img :src="'/assets/images/donation_items/' + form.donation_item_image"
                                class="w-32 h-32 object-cover rounded-lg border border-gray-300">
                        </div>
                    </template>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-2">
                <button @click="dialogShow = false"
                    class="px-4 py-2 bg-gray-200 rounded-full hover:bg-gray-300 font-medium transition-colors">
                    ยกเลิก
                </button>
                <button @click="submitForm"
                    class="px-4 py-2 bg-emerald-500 text-white rounded-full hover:bg-emerald-600 font-medium transition-colors">
                    <span>เพิ่ม</span>
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    function ManageRewards() {
        return {
            categories: [],
            rewards: [],
            total: 0,
            uncatTotal: 0,
            page: 1,
            totalPages: 1,
            limit: 10,
            filters: {
                search: ''
            },
            dialogShow: false,
            editingRewardId: null,
            form: {
                donation_item_id: null,
                donation_item_name: '',
                donation_item_category_id: '1',
                donation_item_redeem_point: '',
                donation_item_discount_point: 0,
                donation_item_amount: 0,
                donation_item_available: 0,
                donation_item_image: null
            },
            editForm: {
                donation_item_id: null,
                donation_item_name: '',
                donation_item_category_id: '1',
                donation_item_redeem_point: '',
                donation_item_discount_point: 0,
                donation_item_amount: 0,
                donation_item_available: 1,
            },
            imageFile: null,
            imagePreview: null,
            editImageFile: null,
            editImagePreview: null,

            async initData() {
                await this.fetchCategories();
                await this.fetchRewards();
            },

            async fetchRewards() {
                this.page = 1;
                await this.refreshData();
            },

            async refreshData() {
                await Promise.all([
                    this.fetchRewardsList(),
                    this.fetchUncatTotal()
                ]);
            },

            async fetchCategories() {
                try {
                    const res = await fetch('/api/donations/items/category');
                    const json = await res.json();
                    if (json.success) {
                        this.categories = json.data || [];
                    }
                } catch (error) {
                    console.error('Error fetching categories:', error);
                }
            },

            async fetchRewardsList() {
                try {
                    const params = new URLSearchParams({
                        page: this.page,
                        limit: this.limit
                    });
                    if (this.filters.search) params.append('search', this.filters.search);

                    const res = await fetch(`/api/donations/items?${params.toString()}`);
                    const json = await res.json();

                    if (json.success) {
                        this.rewards = json.data || [];
                        this.total = json.total || 0;
                        this.totalPages = Math.ceil(this.total / this.limit) || 1;
                    }
                } catch (error) {
                    console.error('Error fetching rewards:', error);
                    Swal.fire('ข้อผิดพลาด', 'ไม่สามารถโหลดข้อมูลของรางวัลได้', 'error');
                }
            },

            async fetchUncatTotal() {
                try {
                    const params = new URLSearchParams({
                        limit: 1
                    });
                    if (this.filters.search) params.append('search', this.filters.search);

                    const res = await fetch(`/api/donations/items/uncategorised?${params.toString()}`);
                    const json = await res.json();

                    if (json.success) {
                        this.uncatTotal = json.total || 0;
                    }
                } catch (error) {
                    console.error('Error fetching uncategorised total:', error);
                }
            },

            getCategoryName(id) {
                if (!id) return '-';
                const cat = this.categories.find(c => c.donation_item_category_id == id);
                return cat ? cat.donation_item_category_name : '-';
            },

            openCreateDialog() {
                this.form = {
                    donation_item_id: null,
                    donation_item_name: '',
                    donation_item_category_id: '1',
                    donation_item_redeem_point: null,
                    donation_item_discount_point: 0,
                    donation_item_amount: 0,
                    donation_item_available: 0,
                    donation_item_image: null
                };
                this.imageFile = null;
                this.imagePreview = null;
                this.dialogShow = true;
            },

            startEdit(reward) {
                this.editingRewardId = reward.donation_item_id;
                this.editForm = {
                    donation_item_id: reward.donation_item_id,
                    donation_item_name: reward.donation_item_name,
                    donation_item_category_id: reward.donation_item_category_id || '1',
                    donation_item_redeem_point: reward.donation_item_redeem_point,
                    donation_item_discount_point: reward.donation_item_discount_point || 0,
                    donation_item_amount: reward.donation_item_amount,
                    donation_item_available: reward.donation_item_available,
                };
                this.editImageFile = null;
                this.editImagePreview = null;
            },

            cancelEdit() {
                this.editingRewardId = null;
                this.editImageFile = null;
                this.editImagePreview = null;
            },

            handleImageUpload(event, type) {
                const file = event.target.files[0];
                if (file) {
                    this.imageFile = file;
                    this.imagePreview = URL.createObjectURL(file);
                } else {
                    this.imageFile = null;
                    this.imagePreview = null;
                }
            },

            handleInlineImageUpload(event) {
                const file = event.target.files[0];
                if (file) {
                    this.editImageFile = file;
                    this.editImagePreview = URL.createObjectURL(file);
                } else {
                    this.editImageFile = null;
                    this.editImagePreview = null;
                }
            },

            async toggleStatus(reward) {
                try {
                    const newStatus = reward.donation_item_available == 1 ? 0 : 1;

                    // ฝั่ง Backend บังคับว่าถ้าเปิดแลก ต้องมีคะแนนที่ใช้แลก
                    if (newStatus === 1 && (reward.donation_item_redeem_point === null || reward.donation_item_redeem_point === '')) {
                        Swal.fire('คำเตือน', 'ไม่สามารถเปิดแลกได้เนื่องจากยังไม่ได้ระบุแต้มที่ใช้แลก กรุณากดแก้ไขก่อน', 'warning');
                        return;
                    }

                    const res = await fetch(`/api/donations/items/activate`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ donation_item_ids: [reward.donation_item_id] })
                    });
                    const json = await res.json();

                    if (json.success) {
                        reward.donation_item_available = newStatus; // สลับสถานะในหน้าจอ
                        Swal.fire({
                            toast: true, position: 'top-end', icon: 'success',
                            title: 'สลับสถานะสำเร็จ', showConfirmButton: false, timer: 1500
                        });
                    } else {
                        throw new Error(json.message || 'สลับสถานะไม่สำเร็จ');
                    }
                } catch (error) {
                    console.error('Error toggling status:', error);
                    Swal.fire('ข้อผิดพลาด', error.message, 'error');
                }
            },

            async submitForm() {
                try {
                    if (!this.form.donation_item_name || this.form.donation_item_redeem_point === '' || this.form.donation_item_amount === '') {
                        Swal.fire('คำเตือน', 'กรุณากรอกข้อมูลที่จำเป็นให้ครบถ้วน', 'warning');
                        return;
                    }

                    const formData = new FormData();
                    formData.append('donation_item_name', this.form.donation_item_name);
                    formData.append('donation_item_redeem_point', this.form.donation_item_redeem_point);
                    formData.append('donation_item_discount_point', this.form.donation_item_discount_point || 0);
                    formData.append('donation_item_amount', this.form.donation_item_amount);
                    if (this.form.donation_item_category_id) {
                        formData.append('donation_item_category_id', this.form.donation_item_category_id);
                    }
                    formData.append('donation_item_available', this.form.donation_item_available);

                    if (this.imageFile) {
                        formData.append('donation_item_image', this.imageFile);
                    }

                    const res = await fetch('/api/donations/items', {
                        method: 'POST',
                        body: formData
                    });

                    const json = await res.json();

                    if (json.success) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'เพิ่มข้อมูลสำเร็จ',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        this.dialogShow = false;
                        this.refreshData();
                    } else {
                        throw new Error(json.message || 'บันทึกข้อมูลไม่สำเร็จ');
                    }
                } catch (error) {
                    console.error('Error submitting form:', error);
                    Swal.fire('ข้อผิดพลาด', error.message, 'error');
                }
            },

            async saveEdit() {
                try {
                    if (!this.editForm.donation_item_name || this.editForm.donation_item_redeem_point === '' || this.editForm.donation_item_amount === '') {
                        Swal.fire('คำเตือน', 'กรุณากรอกข้อมูลที่จำเป็นให้ครบถ้วน', 'warning');
                        return;
                    }

                    const formData = new FormData();
                    formData.append('donation_item_name', this.editForm.donation_item_name);
                    formData.append('donation_item_redeem_point', this.editForm.donation_item_redeem_point);
                    formData.append('donation_item_discount_point', this.editForm.donation_item_discount_point || 0);
                    formData.append('donation_item_amount', this.editForm.donation_item_amount);
                    if (this.editForm.donation_item_category_id) {
                        formData.append('donation_item_category_id', this.editForm.donation_item_category_id);
                    } else {
                        formData.append('donation_item_category_id', '');
                    }
                    formData.append('donation_item_available', this.editForm.donation_item_available);

                    if (this.editImageFile) {
                        formData.append('donation_item_image', this.editImageFile);
                    }

                    const res = await fetch(`/api/donations/items/update/${this.editForm.donation_item_id}`, {
                        method: 'POST',
                        body: formData
                    });

                    const json = await res.json();

                    if (json.success) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'แก้ไขข้อมูลสำเร็จ',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        this.editingRewardId = null;
                        this.refreshData();
                    } else {
                        throw new Error(json.message || 'บันทึกข้อมูลไม่สำเร็จ');
                    }
                } catch (error) {
                    console.error('Error saving edit:', error);
                    Swal.fire('ข้อผิดพลาด', error.message, 'error');
                }
            },

            async confirmDelete(id) {
                const result = await Swal.fire({
                    title: 'ยืนยันการลบ?',
                    text: "คุณจะไม่สามารถกู้คืนข้อมูลนี้ได้!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'ใช่, ลบเลย!',
                    cancelButtonText: 'ยกเลิก'
                });

                if (result.isConfirmed) {
                    try {
                        const res = await fetch('/api/donations/delete', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ donation_item_ids: [id] })
                        });

                        const json = await res.json();

                        if (json.success) {
                            Swal.fire('ลบแล้ว!', 'ข้อมูลถูกลบเรียบร้อย', 'success');
                            this.fetchRewards();
                        } else {
                            throw new Error(json.message || 'ลบข้อมูลไม่สำเร็จ');
                        }
                    } catch (error) {
                        console.error('Error deleting reward:', error);
                        Swal.fire('ข้อผิดพลาด', error.message, 'error');
                    }
                }
            },
        }
    }
</script>