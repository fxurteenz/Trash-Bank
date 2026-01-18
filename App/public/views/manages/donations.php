<div class="space-y-6" x-data="ManageDonations()" x-init="initData()">
    
    <!-- Header Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">รายการบริจาคทั้งหมด</p>
                    <p class="text-2xl font-bold text-emerald-700" x-text="totalDonations"></p>
                </div>
                <div class="p-3 bg-emerald-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2m0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8m3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">มูลค่ารวม</p>
                    <p class="text-2xl font-bold text-blue-700" x-text="`฿ ${getTotalValue().toLocaleString('th-TH', {minimumFractionDigits: 2})}`"></p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">จำนวนหน่วยงาน</p>
                    <p class="text-2xl font-bold text-purple-700" x-text="getUniqueDonors()"></p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">รายการบริจาค</h2>
            <button @click="openCreateDialog" 
                class="flex items-center px-4 py-2 bg-emerald-500 text-white rounded-full hover:bg-emerald-600 transition-colors font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                เพิ่มการบริจาค
            </button>
        </div>

        <!-- Filters -->
        <div class="mb-4 flex gap-4">
            <input type="text" x-model="filters.search" @input.debounce.500ms="fetchDonations()" 
                placeholder="ค้นหาชื่อสิ่งของหรือหน่วยงาน..." 
                class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <select x-model="filters.category" @change="fetchDonations()" 
                class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                <option value="">หมวดหมู่ทั้งหมด</option>
                <option value="วัสดุสิ้นเปลือง">วัสดุสิ้นเปลือง</option>
                <option value="อุปกรณ์">อุปกรณ์</option>
                <option value="วัสดุก่อสร้าง">วัสดุก่อสร้าง</option>
                <option value="เฟอร์นิเจอร์">เฟอร์นิเจอร์</option>
                <option value="เครื่องหนังสือพิมพ์">เครื่องหนังสือพิมพ์</option>
                <option value="อื่นๆ">อื่นๆ</option>
            </select>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-emerald-100 rounded-lg">
                <thead class="bg-emerald-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">รูปภาพ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">วันที่</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">หน่วยงาน</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">สิ่งของ</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">จำนวน</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">หมวดหมู่</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">มูลค่า</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-emerald-700 uppercase tracking-wider border-b border-emerald-200">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <template x-for="(donation, index) in filteredDonations" :key="donation.donation_id || index">
                        <tr class="hover:bg-emerald-50 transition duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="index + 1"></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <template x-if="donation.donation_image">
                                    <img :src="donation.donation_image" class="w-16 h-16 object-cover rounded-lg">
                                </template>
                                <template x-if="!donation.donation_image">
                                    <div class="w-16 h-16 bg-emerald-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </template>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="formatDate(donation.donation_date)"></td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900" x-text="donation.donor_name"></td>
                            <td class="px-6 py-4 text-sm text-gray-600" x-text="donation.item_name"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-900" x-text="`${donation.quantity} ${donation.unit}`"></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 text-xs rounded-full font-medium bg-blue-100 text-blue-800" 
                                    x-text="donation.category || 'ไม่ระบุ'"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                                <span x-show="donation.estimated_value" x-text="`฿ ${parseFloat(donation.estimated_value).toLocaleString('th-TH', {minimumFractionDigits: 2})}`"></span>
                                <span x-show="!donation.estimated_value" class="text-gray-400">-</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm space-x-2">
                                <button @click="openEditDialog(donation)" class="text-emerald-600 hover:text-emerald-900 hover:bg-emerald-100 p-2 rounded-full transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button @click="confirmDelete(donation.donation_id || index)" class="text-red-600 hover:text-red-900 hover:bg-red-100 p-2 rounded-full transition">
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

        <template x-if="donations.length === 0">
            <div class="text-center py-12">
                <p class="text-gray-500">ไม่มีข้อมูลการบริจาค</p>
            </div>
        </template>

        <!-- Pagination -->
        <div class="flex items-center justify-between mt-4">
            <button class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50" 
                :disabled="page <= 1" @click="page--; fetchDonations()">
                ก่อนหน้า
            </button>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-600">หน้า <span x-text="page"></span> จาก <span x-text="totalPages"></span></span>
            </div>
            <button class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50" 
                :disabled="page >= totalPages" @click="page++; fetchDonations()">
                ถัดไป
            </button>
        </div>
    </div>

    <!-- Create/Edit Dialog -->
    <dialog x-show="dialogShow" x-ref="donationDialog" @click.self="dialogShow = false" @close="dialogShow = false"
        class="fixed inset-0 mx-auto my-auto p-0 bg-transparent z-50"
        x-init="$watch('dialogShow', value => {if (value) $refs.donationDialog.showModal();else $refs.donationDialog.close();})">
        <div class="bg-white shadow-sm rounded-lg p-6 w-96 max-w-full">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg" x-text="isEditing ? 'แก้ไขการบริจาค' : 'เพิ่มการบริจาคใหม่'"></h3>
                <button @click="dialogShow = false" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">หน่วยงาน <span class="text-red-500">*</span></label>
                    <input type="text" x-model="form.donor_name" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="ชื่อหน่วยงานที่บริจาค">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">วันที่ <span class="text-red-500">*</span></label>
                    <input type="date" x-model="form.donation_date" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อสิ่งของ <span class="text-red-500">*</span></label>
                    <input type="text" x-model="form.item_name" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="เช่น กล่องพลาสติก, กระเป๋า">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">จำนวน <span class="text-red-500">*</span></label>
                        <input type="number" x-model="form.quantity" min="1"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                            placeholder="0">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">หน่วยวัด</label>
                        <select x-model="form.unit"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                            <option value="ชิ้น">ชิ้น</option>
                            <option value="กิโลกรัม">กิโลกรัม</option>
                            <option value="กระสอบ">กระสอบ</option>
                            <option value="กล่อง">กล่อง</option>
                            <option value="ลัง">ลัง</option>
                            <option value="เมตร">เมตร</option>
                            <option value="อื่นๆ">อื่นๆ</option>
                        </select>
                    </div>
                </div>

                <div x-show="form.unit === 'อื่นๆ'" x-transition>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ระบุหน่วยวัดอื่นๆ</label>
                    <input type="text" x-model="form.customUnit" placeholder="เช่น หีบ, ตัว, ชั้น"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">หมวดหมู่</label>
                    <select x-model="form.category"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                        <option value="">-- เลือกหมวดหมู่ --</option>
                        <option value="วัสดุสิ้นเปลือง">วัสดุสิ้นเปลือง</option>
                        <option value="อุปกรณ์">อุปกรณ์</option>
                        <option value="วัสดุก่อสร้าง">วัสดุก่อสร้าง</option>
                        <option value="เฟอร์นิเจอร์">เฟอร์นิเจอร์</option>
                        <option value="เครื่องหนังสือพิมพ์">เครื่องหนังสือพิมพ์</option>
                        <option value="อื่นๆ">อื่นๆ</option>
                    </select>
                </div>

                <div x-show="form.category === 'อื่นๆ'" x-transition>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ระบุหมวดหมู่อื่นๆ</label>
                    <input type="text" x-model="form.customCategory" placeholder="กรุณาระบุหมวดหมู่"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">มูลค่า (บาท)</label>
                    <input type="number" x-model="form.estimated_value" step="0.01" min="0"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="0.00">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">หมายเหตุ</label>
                    <textarea x-model="form.notes" rows="3"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="เพิ่มรายละเอียดเพิ่มเติม..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">รูปภาพ</label>
                    <input type="file" @change="handleImageUpload($event)" accept="image/*"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    <template x-if="imagePreview">
                        <div class="mt-2">
                            <img :src="imagePreview" class="w-32 h-32 object-cover rounded-lg border border-gray-300">
                        </div>
                    </template>
                    <template x-if="form.donation_image && !imagePreview">
                        <div class="mt-2">
                            <img :src="form.donation_image" class="w-32 h-32 object-cover rounded-lg border border-gray-300">
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
                    <span x-text="isEditing ? 'บันทึก' : 'เพิ่ม'"></span>
                </button>
            </div>
        </div>
    </dialog>

</div>

<script>
function ManageDonations() {
    return {
        donations: [],
        form: {
            donation_id: null,
            donor_name: '',
            donation_date: new Date().toISOString().split('T')[0],
            item_name: '',
            quantity: 1,
            unit: 'ชิ้น',
            customUnit: '',
            category: '',
            customCategory: '',
            estimated_value: 0,
            notes: '',
            donation_image: null
        },
        filters: {
            search: '',
            category: ''
        },
        dialogShow: false,
        isEditing: false,
        page: 1,
        totalPages: 1,
        totalDonations: 0,
        imagePreview: null,

        initData() {
            this.loadDonations();
        },

        loadDonations() {
            const saved = localStorage.getItem('donationList');
            if (saved) {
                this.donations = JSON.parse(saved);
                this.totalDonations = this.donations.length;
                this.totalPages = Math.ceil(this.donations.length / 10) || 1;
            } else {
                this.donations = [];
                this.totalDonations = 0;
                this.totalPages = 1;
            }
        },

        fetchDonations() {
            this.loadDonations();
        },

        openCreateDialog() {
            this.isEditing = false;
            this.imagePreview = null;
            this.form = {
                donation_id: null,
                donor_name: '',
                donation_date: new Date().toISOString().split('T')[0],
                item_name: '',
                quantity: 1,
                unit: 'ชิ้น',
                customUnit: '',
                category: '',
                customCategory: '',
                estimated_value: 0,
                notes: '',
                donation_image: null
            };
            this.dialogShow = true;
        },

        openEditDialog(donation) {
            this.isEditing = true;
            this.imagePreview = null;
            this.form = { ...donation };
            this.dialogShow = true;
        },

        submitForm() {
            if (!this.form.donor_name || !this.form.item_name || !this.form.quantity) {
                alert('กรุณากรอกข้อมูลที่จำเป็น');
                return;
            }

            // ถ้าเลือก "อื่นๆ" ให้ใช้ customCategory แทน
            if (this.form.category === 'อื่นๆ' && this.form.customCategory) {
                this.form.category = this.form.customCategory;
            }

            // ถ้าเลือก "อื่นๆ" ให้ใช้ customUnit แทน
            if (this.form.unit === 'อื่นๆ' && this.form.customUnit) {
                this.form.unit = this.form.customUnit;
            }

            if (this.isEditing) {
                const index = this.donations.findIndex(d => d.donation_id === this.form.donation_id);
                if (index !== -1) {
                    this.donations[index] = { ...this.form };
                }
            } else {
                this.form.donation_id = 'donation_' + Date.now();
                this.donations.push({ ...this.form });
            }

            this.saveDonations();
            this.dialogShow = false;
            this.fetchDonations();
        },

        confirmDelete(id) {
            if (confirm('ยืนยันการลบรายการนี้?')) {
                this.donations = this.donations.filter(d => d.donation_id !== id && d !== id);
                this.saveDonations();
                this.fetchDonations();
            }
        },

        saveDonations() {
            localStorage.setItem('donationList', JSON.stringify(this.donations));
        },

        handleImageUpload(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.form.donation_image = e.target.result;
                    this.imagePreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('th-TH', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
            });
        },

        getTotalValue() {
            return this.donations.reduce((sum, d) => sum + (parseFloat(d.estimated_value) || 0), 0);
        },

        getUniqueDonors() {
            return [...new Set(this.donations.map(d => d.donor_name))].length;
        },

        get filteredDonations() {
            return this.donations.filter(donation => {
                const matchesSearch = this.filters.search === '' || 
                    donation.donor_name.toLowerCase().includes(this.filters.search.toLowerCase()) ||
                    donation.item_name.toLowerCase().includes(this.filters.search.toLowerCase());
                
                const matchesCategory = this.filters.category === '' || 
                    donation.category === this.filters.category;
                
                return matchesSearch && matchesCategory;
            }).slice((this.page - 1) * 10, this.page * 10);
        }
    }
}
</script>
