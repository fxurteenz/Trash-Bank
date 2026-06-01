<div class="flex flex-col w-full space-y-6" x-data="RewardCategoryDetail()" x-init="initData()">
    <div class="flex justify-between items-center mb-4">
        <div class="flex items-center gap-4">
            <button @click="window.history.back()"
                class="text-gray-500 hover:text-gray-700 transition cursor-pointer hover:scale-105 active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </button>
            <h1 class="text-2xl font-bold text-slate-900">ของรางวัลหมวดหมู่ : <span class="text-emerald-600"
                    x-text="categoryName"></span></h1>
        </div>
    </div>
    <!-- Main Content -->
    <div class="bg-white shadow-sm rounded-lg p-6 flex flex-col min-h-[calc(100vh-14rem)]">
        <!-- Breadcrumb & Header -->
        <div class="flex flex-col mb-4">
            <!-- <div class="flex items-center space-x-3 text-sm text-gray-500 mb-2">
                <a href="/admin/manage/reward_category" class="hover:text-emerald-600 transition">หมวดหมู่รางวัล</a>
                <span>/</span>
                <span class="text-gray-800 font-medium" x-text="categoryName">กำลังโหลด...</span>
            </div> -->
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800">รายการของรางวัล</h2>
                <button @click="window.location.href='/admin/manage/reward'"
                    class="px-4 py-2 bg-emerald-50 text-emerald-600 rounded-full hover:bg-emerald-100 transition-colors font-medium">
                    จัดการของรางวัลทั้งหมด
                </button>
            </div>
        </div>

        <!-- Bulk Actions Toolbar -->
        <div
            class="flex justify-between items-center mb-4 min-h-[44px] bg-gray-50 px-4 py-2 rounded-lg border border-gray-200">
            <div class="flex items-center w-full">
                <div x-show="selectedItems.length > 0" x-transition x-cloak
                    class="flex flex-wrap items-center gap-3 w-full">
                    <span
                        class="text-sm font-medium text-gray-700 bg-white px-3 py-1.5 rounded-md border border-gray-200 shadow-sm">
                        เลือก <span class="text-emerald-600 font-bold" x-text="selectedItems.length"></span> รายการ
                    </span>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-600">ย้ายไป:</span>
                        <select x-model="bulkMoveCategoryId"
                            class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none bg-white min-w-[200px]">
                            <option value="none" disabled>-- เลือกหมวดหมู่ปลายทาง --</option>
                            <template x-for="cat in categories" :key="cat.donation_item_category_id">
                                <option :value="cat.donation_item_category_id" x-text="cat.donation_item_category_name"
                                    x-show="cat.donation_item_category_id != categoryId"></option>
                            </template>
                        </select>
                        <button @click="moveSelectedItems()" :disabled="bulkMoveCategoryId === 'none'"
                            class="px-4 py-1.5 bg-emerald-500 text-white text-sm font-medium rounded-lg hover:bg-emerald-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed shadow-sm">
                            ยืนยัน
                        </button>
                    </div>
                </div>
                <div x-show="selectedItems.length === 0" class="text-sm text-gray-500 italic">
                    ติ๊กเลือกที่ช่องสี่เหลี่ยมด้านหน้าเพื่อย้ายหมวดหมู่ของรางวัล
                </div>
            </div>
        </div>

        <div class="overflow-x-auto border border-gray-200 rounded-lg">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-center w-12 border-b">
                            <input type="checkbox" @change="toggleAll" :checked="isAllSelected"
                                class="w-4 h-4 text-emerald-600 bg-gray-100 border-gray-300 rounded focus:ring-emerald-500 cursor-pointer">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 tracking-wider border-b w-24">
                            ลำดับ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 tracking-wider border-b w-32">
                            รูปภาพ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 tracking-wider border-b">
                            ชื่อรางวัล</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 tracking-wider border-b">
                            คะแนนที่ใช้แลก</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 tracking-wider border-b">
                            จำนวนคงเหลือ</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 tracking-wider border-b">
                            สถานะ</th>
                        <th
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 tracking-wider border-b w-24">
                            จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <template x-for="(item, index) in items" :key="item.donation_item_id">
                        <tr :class="editingItemId === item.donation_item_id ? 'bg-emerald-50' : 'hover:bg-gray-50'"
                            class="transition duration-200">
                            <td class="py-4 px-4 text-center">
                                <input type="checkbox" :value="item.donation_item_id" x-model="selectedItems"
                                    class="w-4 h-4 text-emerald-600 bg-gray-100 border-gray-300 rounded focus:ring-emerald-500 cursor-pointer">
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap text-sm text-gray-500" x-text="index + 1"></td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div x-show="editingItemId !== item.donation_item_id">
                                    <template x-if="item.donation_item_image">
                                        <img :src="'/assets/images/donation_items/' + item.donation_item_image"
                                            class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                                    </template>
                                    <template x-if="!item.donation_item_image">
                                        <div
                                            class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    </template>
                                </div>
                                <div x-show="editingItemId === item.donation_item_id" x-cloak
                                    class="relative group cursor-pointer w-16 h-16"
                                    @click="$el.querySelector('input[type=file]').click()">
                                    <template x-if="editImagePreview">
                                        <img :src="editImagePreview"
                                            class="w-16 h-16 object-cover rounded-lg border border-emerald-400">
                                    </template>
                                    <template x-if="!editImagePreview && item.donation_item_image">
                                        <img :src="'/assets/images/donation_items/' + item.donation_item_image"
                                            class="w-16 h-16 object-cover rounded-lg opacity-70 group-hover:opacity-50 transition-opacity border border-gray-200">
                                    </template>
                                    <template x-if="!editImagePreview && !item.donation_item_image">
                                        <div
                                            class="w-16 h-16 bg-emerald-50 rounded-lg flex items-center justify-center group-hover:bg-emerald-100 transition-colors border border-dashed border-emerald-300">
                                            <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                        </div>
                                    </template>
                                    <div
                                        class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <svg class="w-6 h-6 text-white drop-shadow-md" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 16a4 4 0 014-4h.01M16 12a4 4 0 014 4m-4-4l-4-4m0 0l-4 4m4-4v12" />
                                        </svg>
                                    </div>
                                    <input type="file" @change="handleInlineImageUpload($event)" accept="image/*"
                                        class="hidden">
                                </div>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap text-sm font-medium text-gray-900">
                                <template x-if="editingItemId !== item.donation_item_id">
                                    <span x-text="item.donation_item_name"></span>
                                </template>
                                <template x-if="editingItemId === item.donation_item_id">
                                    <input type="text" x-model="editForm.donation_item_name"
                                        class="w-full border border-gray-300 rounded p-1 text-sm bg-white focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                                </template>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap text-sm text-center font-semibold"
                                :class="item.donation_item_redeem_point ? 'text-amber-600' : ''">
                                <template x-if="editingItemId !== item.donation_item_id">
                                    <span x-text="item.donation_item_redeem_point || '-'"></span>
                                </template>
                                <template x-if="editingItemId === item.donation_item_id">
                                    <input type="number" x-model="editForm.donation_item_redeem_point"
                                        class="w-24 text-center border border-gray-300 rounded p-1 text-sm bg-white focus:ring-emerald-500 focus:border-emerald-500 outline-none mx-auto">
                                </template>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap text-sm text-center font-medium"
                                :class="item.donation_item_amount > 0 ? 'text-emerald-600' : 'text-red-500'"
                                x-text="item.donation_item_amount">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <template x-if="editingItemId !== item.donation_item_id">
                                    <span @click.stop="toggleStatus(item)"
                                        :class="item.donation_item_available == 1 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'"
                                        class="px-3 py-1 text-xs rounded-full font-medium cursor-pointer hover:opacity-80 transition-opacity"
                                        title="คลิกเพื่อสลับสถานะ"
                                        x-text="item.donation_item_available == 1 ? 'เปิดแลก' : 'ไม่เปิดแลก'"></span>
                                </template>
                                <template x-if="editingItemId === item.donation_item_id" x-cloak>
                                    <select x-model="editForm.donation_item_available"
                                        class="w-24 border border-gray-300 rounded p-1 text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                                        <option value="1">เปิดแลก</option>
                                        <option value="0">ไม่เปิดแลก</option>
                                    </select>
                                </template>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                <template x-if="editingItemId !== item.donation_item_id">
                                    <button @click="startEdit(item)"
                                        class="text-emerald-600 hover:text-emerald-900 hover:bg-emerald-100 p-2 rounded-full transition"
                                        title="แก้ไข">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                </template>
                                <template x-if="editingItemId === item.donation_item_id">
                                    <div class="flex justify-center space-x-2">
                                        <button @click="saveEdit()"
                                            class="bg-emerald-500 text-white p-2 rounded-md hover:bg-emerald-600 transition"
                                            title="บันทึก"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg></button>
                                        <button @click="cancelEdit()"
                                            class="bg-gray-400 text-white p-2 rounded-md hover:bg-gray-500 transition"
                                            title="ยกเลิก"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg></button>
                                    </div>
                                </template>
                            </td>
                        </tr>
                    </template>
                    <template x-if="items.length === 0 && !loading">
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                ไม่มีรายการของรางวัลในหมวดหมู่นี้</td>
                        </tr>
                    </template>
                    <template x-if="loading">
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">กำลังโหลดข้อมูล...</td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function RewardCategoryDetail() {
        return {
            categoryId: <?= $cid ?? 'null' ?>,
            categoryName: '',
            items: [],
            categories: [],
            loading: true,

            editingItemId: null,
            editImageFile: null,
            editImagePreview: null,
            selectedItems: [],
            bulkMoveCategoryId: 'none',

            editForm: {
                donation_item_id: null,
                donation_item_name: '',
                donation_item_redeem_point: '',
                donation_item_category_id: '',
                donation_item_available: 1
            },

            get isAllSelected() {
                return this.items.length > 0 && this.selectedItems.length === this.items.length;
            },

            toggleAll(e) {
                if (e.target.checked) {
                    this.selectedItems = this.items.map(i => i.donation_item_id);
                } else {
                    this.selectedItems = [];
                }
            },

            async initData() {
                if (!this.categoryId) return;
                await this.fetchCategoryName(); // โหลดรายชื่อหมวดหมู่ทั้งหมดเตรียมไว้
                await this.fetchItems();
            },

            async fetchItems() {
                this.loading = true;
                this.selectedItems = []; // รีเซ็ตการเลือกเมื่อดึงข้อมูลใหม่
                try {
                    const res = await fetch(`/api/donations/items/category/${this.categoryId}`);
                    const json = await res.json();

                    if (json.success) {
                        this.items = json.data || [];
                    }
                } catch (error) {
                    console.error('Error fetching items:', error);
                } finally {
                    this.loading = false;
                }
            },

            async fetchCategoryName() {
                try {
                    const res = await fetch('/api/donations/items/category');
                    const json = await res.json();
                    if (json.success && json.data) {
                        this.categories = json.data;
                        const cat = json.data.find(c => c.donation_item_category_id == this.categoryId);
                        if (cat) this.categoryName = cat.donation_item_category_name;
                    }
                } catch (error) {
                    console.error('Error fetching category info:', error);
                }
            },

            async moveSelectedItems() {
                if (this.bulkMoveCategoryId === 'none') return;

                try {
                    const result = await Swal.fire({
                        title: 'ยืนยันการย้าย?',
                        text: `คุณต้องการย้ายของรางวัล ${this.selectedItems.length} รายการ ใช่หรือไม่?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'ยืนยัน',
                        cancelButtonText: 'ยกเลิก',
                        confirmButtonColor: '#10b981'
                    });

                    if (result.isConfirmed) {
                        Swal.fire({ title: 'กำลังย้ายข้อมูล...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                        const promises = this.selectedItems.map(id => {
                            const formData = new FormData();
                            formData.append('donation_item_category_id', this.bulkMoveCategoryId);
                            return fetch(`/api/donations/items/update/${id}`, {
                                method: 'POST',
                                body: formData
                            });
                        });

                        await Promise.all(promises); // ยิง Request ย้ายข้อมูลพร้อมกัน

                        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'ย้ายข้อมูลสำเร็จ', showConfirmButton: false, timer: 1500 });
                        this.selectedItems = [];
                        this.bulkMoveCategoryId = 'none';
                        this.fetchItems(); // อัปเดตรายการใหม่
                    }
                } catch (error) {
                    console.error(error);
                    Swal.fire('ข้อผิดพลาด', 'ไม่สามารถย้ายข้อมูลได้', 'error');
                }
            },

            startEdit(item) {
                this.editingItemId = item.donation_item_id;
                this.editImageFile = null;
                this.editImagePreview = null;
                this.editForm = {
                    donation_item_id: item.donation_item_id,
                    donation_item_name: item.donation_item_name,
                    donation_item_redeem_point: item.donation_item_redeem_point,
                    donation_item_category_id: item.donation_item_category_id,
                    donation_item_available: item.donation_item_available
                };
            },

            cancelEdit() {
                this.editingItemId = null;
                this.editImageFile = null;
                this.editImagePreview = null;
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

            async toggleStatus(item) {
                try {
                    const newStatus = item.donation_item_available == 1 ? 0 : 1;

                    // ฝั่ง Backend บังคับว่าถ้าเปิดแลก ต้องมีคะแนนที่ใช้แลก
                    if (newStatus === 1 && (item.donation_item_redeem_point === null || item.donation_item_redeem_point === '')) {
                        Swal.fire('คำเตือน', 'ไม่สามารถเปิดแลกได้เนื่องจากยังไม่ได้ระบุแต้มที่ใช้แลก กรุณากดแก้ไขก่อน', 'warning');
                        return;
                    }

                    const res = await fetch(`/api/donations/items/activate`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ donation_item_ids: [item.donation_item_id] })
                    });

                    const json = await res.json();

                    if (json.success) {
                        item.donation_item_available = newStatus; // สลับสถานะในหน้าจอ
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

            async saveEdit() {
                try {
                    if (!this.editForm.donation_item_name || this.editForm.donation_item_redeem_point === '') {
                        Swal.fire('คำเตือน', 'กรุณากรอกข้อมูลที่จำเป็นให้ครบถ้วน', 'warning');
                        return;
                    }

                    const formData = new FormData();
                    formData.append('donation_item_name', this.editForm.donation_item_name);
                    formData.append('donation_item_redeem_point', this.editForm.donation_item_redeem_point);
                    formData.append('donation_item_category_id', this.editForm.donation_item_category_id);
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
                        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'แก้ไขข้อมูลสำเร็จ', showConfirmButton: false, timer: 1500 });
                        this.editingItemId = null;
                        this.fetchItems();
                    } else {
                        throw new Error(json.message || 'บันทึกข้อมูลไม่สำเร็จ');
                    }
                } catch (error) {
                    console.error('Error saving edit:', error);
                    Swal.fire('ข้อผิดพลาด', error.message, 'error');
                }
            }
        }
    }
</script>