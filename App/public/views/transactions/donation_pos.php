<style>
    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
        border-radius: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(156, 163, 175, 0.5);
        border-radius: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(107, 114, 128, 0.8);
    }
</style>

<script>
    function DonationPOSHandler() {
        return {
            // State สำหรับค้นหา
            currentDonor: null,
            searchQuery: '',
            searchResults: [],
            isSearching: false,
            showDropdown: false,
            selectedIndex: -1,

            donation: {
                item_name: '',
                item_amount: '',
                item_value: ''
            },
            items: [],
            todayDonations: 0,

            isSaving: false,

            async init() {
                const urlParams = new URLSearchParams(window.location.search);
                const memberId = urlParams.get('member_id');
                if (memberId) {
                    await this.fetchMemberById(memberId);
                } else {
                    this.$nextTick(() => {
                        if (this.$refs.searchInput) this.$refs.searchInput.focus();
                    });
                }
            },

            async fetchMemberById(id) {
                try {
                    const response = await fetch(`/api/members/profile/${id}`);
                    const result = await response.json();
                    if (result.success && result.data) {
                        this.selectDonor(result.data);
                    } else {
                        Swal.fire({ icon: 'error', title: 'ไม่พบข้อมูลสมาชิกจาก URL', timer: 1500, showConfirmButton: false });
                        this.$nextTick(() => { if (this.$refs.searchInput) this.$refs.searchInput.focus(); });
                    }
                } catch (error) {
                    console.error('Error fetching member:', error);
                    this.$nextTick(() => { if (this.$refs.searchInput) this.$refs.searchInput.focus(); });
                }
            },

            // --- Search Logic ---
            async searchDonor() {
                if (!this.searchQuery.trim()) {
                    this.searchResults = [];
                    this.showDropdown = false;
                    return;
                }
                this.isSearching = true;
                this.showDropdown = true;
                this.selectedIndex = -1;

                try {
                    const res = await fetch(`/api/members?search=${encodeURIComponent(this.searchQuery)}&limit=10`);
                    const data = await res.json();
                    this.searchResults = data.success ? data.data : [];
                } catch (err) {
                    console.error(err);
                    this.searchResults = [];
                } finally {
                    this.isSearching = false;
                }
            },

            selectDonor(member) {
                this.currentDonor = member;
                this.showDropdown = false;
                this.searchQuery = '';
                this.searchResults = [];
                this.selectedIndex = -1;
            },

            moveSelection(step) {
                if (!this.showDropdown || this.searchResults.length === 0) return;
                this.selectedIndex = (this.selectedIndex + step + this.searchResults.length) % this.searchResults.length;

                this.$nextTick(() => {
                    const list = this.$refs.resultList;
                    if (list) {
                        const activeItem = list.querySelectorAll('li')[this.selectedIndex];
                        if (activeItem) {
                            activeItem.scrollIntoView({ block: 'nearest' });
                        }
                    }
                });
            },

            handleEnterKey() {
                if (this.selectedIndex >= 0 && this.searchResults[this.selectedIndex]) {
                    this.selectDonor(this.searchResults[this.selectedIndex]);
                }
            },

            resetDonor() {
                this.currentDonor = null;
                this.searchQuery = '';
                this.searchResults = [];
                setTimeout(() => {
                    if (this.$refs.searchInput) this.$refs.searchInput.focus();
                }, 100);
            },

            // --- Donation Logic ---

            addItem() {
                const amount = parseInt(this.donation.item_amount);
                const value = parseFloat(this.donation.item_value);

                if (!this.donation.item_name.trim()) {
                    Swal.fire('แจ้งเตือน', 'กรุณาระบุชื่อสิ่งของ', 'warning');
                    if (this.$refs.itemNameInput) this.$refs.itemNameInput.focus();
                    return;
                }
                if (!amount || amount <= 0 || isNaN(amount)) {
                    Swal.fire('แจ้งเตือน', 'กรุณาระบุจำนวนให้ถูกต้อง (ขั้นต่ำ 1)', 'warning');
                    if (this.$refs.amountInput) this.$refs.amountInput.focus();
                    return;
                }
                if (isNaN(value) || value <= 0) {
                    Swal.fire('แจ้งเตือน', 'กรุณาระบุมูลค่าให้ถูกต้อง (ขั้นต่ำ > 0)', 'warning');
                    if (this.$refs.valueInput) this.$refs.valueInput.focus();
                    return;
                }

                this.items.push({
                    name: this.donation.item_name.trim(),
                    amount: amount,
                    value: value
                });

                this.donation.item_name = '';
                this.donation.item_amount = '';
                this.donation.item_value = '';

                if (this.$refs.itemNameInput) this.$refs.itemNameInput.focus();
            },

            removeItem(index) {
                this.items.splice(index, 1);
            },

            totalAmount() {
                return this.items.reduce((sum, item) => sum + item.amount, 0);
            },

            calculateTotal() {
                return this.items.reduce((sum, item) => sum + (item.amount * item.value), 0);
            },

            canSave() {
                return this.currentDonor &&
                    this.items.length > 0 &&
                    !this.isSaving;
            },

            async saveDonation() {
                if (!this.canSave()) return;

                this.isSaving = true;

                const payload = {
                    member_id: this.currentDonor.member_id,
                    items: this.items
                };

                try {
                    const res = await fetch('/api/donations', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload)
                    });

                    const result = await res.json();

                    if (result.status === 'success' || result.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'บันทึกสำเร็จ',
                            text: `รับเข้าคลัง ${this.items.length} รายการเรียบร้อย`,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        this.todayDonations += 1;
                        this.resetForm();
                    } else {
                        throw new Error(result.message || 'Unknown error');
                    }
                } catch (err) {
                    console.error('Save Error:', err);
                    Swal.fire('เกิดข้อผิดพลาด', err.message || 'ไม่สามารถบันทึกข้อมูลได้', 'error');
                } finally {
                    this.isSaving = false;
                }
            },

            cancelAll() {
                if (this.items.length === 0) return;
                Swal.fire({
                    title: 'ต้องการยกเลิกทั้งหมด?',
                    text: "รายการที่เพิ่มไว้จะถูกลบออกทั้งหมด",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'ใช่, ลบทั้งหมด',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.items = [];
                        this.donation = { item_name: '', item_amount: '', item_value: '' };
                    }
                });
            },

            resetForm() {
                this.currentDonor = null;
                this.searchQuery = '';
                this.donation = { item_name: '', item_amount: '', item_value: '' };
                this.items = [];
                this.resetDonor(); // Focus กลับไปช่องค้นหา
            }
        };
    }
</script>

<div x-data="DonationPOSHandler()" x-init="init()" class="flex flex-col h-[calc(100vh-6rem)] gap-4">

    <div class="flex-none flex flex-col md:flex-row gap-4">
        <div class="md:w-1/3 flex flex-col justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 mb-2 flex items-center gap-3">
                    <svg class="w-8 h-8 text-purple-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M4 21h9.62a4 4 0 0 0 3.037-1.397l5.102-5.952a1 1 0 0 0-.442-1.6l-1.968-.656a3.04 3.04 0 0 0-2.823.503l-3.185 2.547l-.617-1.235A3.98 3.98 0 0 0 9.146 11H4c-1.103 0-2 .897-2 2v6c0 1.103.897 2 2 2m0-8h5.146c.763 0 1.448.423 1.789 1.105l.447.895H7v2h6.014a1 1 0 0 0 .442-.11l.003-.001l.004-.002h.003l.002-.001h.004l.001-.001c.009.003.003-.001.003-.001c.01 0 .002-.001.002-.001h.001l.002-.001l.003-.001l.002-.001l.002-.001l.003-.001l.002-.001c.003 0 .001-.001.002-.001l.003-.002l.002-.001l.002-.001l.003-.001l.002-.001h.001l.002-.001h.001l.002-.001l.002-.001c.009-.001.003-.001.003-.001l.002-.001a1 1 0 0 0 .11-.078l4.146-3.317c.262-.208.623-.273.94-.167l.557.186l-4.133 4.823a2.03 2.03 0 0 1-1.52.688H4zM16 2h-.017c-.163.002-1.006.039-1.983.705c-.951-.648-1.774-.7-1.968-.704L12.002 2h-.004c-.801 0-1.555.313-2.119.878C9.313 3.445 9 4.198 9 5s.313 1.555.861 2.104l3.414 3.586a1.006 1.006 0 0 0 1.45-.001l3.396-3.568C18.688 6.555 19 5.802 19 5s-.313-1.555-.878-2.121A2.98 2.98 0 0 0 16.002 2zm1 3c0 .267-.104.518-.311.725L14 8.55l-2.707-2.843C11.104 5.518 11 5.267 11 5s.104-.518.294-.708A.98.98 0 0 1 11.979 4c.025.001.502.032 1.067.485q.121.098.247.222l.707.707l.707-.707q.126-.124.247-.222c.529-.425.976-.478 1.052-.484a1 1 0 0 1 .701.292c.189.189.293.44.293.707"
                            stroke-width="0.5" stroke="currentColor" />
                    </svg>
                    <span>ระบบรับของบริจาค</span>
                </h1>
                <p class="text-slate-600 text-sm">บันทึกการรับบริจาค - ใช้เบอร์โทรหรือรหัสประจำตัว</p>
            </div>

            <!-- <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-4 text-white">
                <div class="flex items-center justify-between">
                    <p class="text-purple-100 text-xs font-medium uppercase tracking-wider mb-1">ยอดบริจาควันนี้</p>
                    <h2 class="text-3xl font-bold flex items-center gap-2">
                        <span x-text="todayDonations">0</span>
                        <span class="text-sm font-normal text-purple-100 mt-2">ครั้ง</span>
                    </h2>
                </div>
            </div> -->
        </div>

        <div class="md:w-2/3 bg-white rounded-xl shadow-md card-hover relative flex flex-col justify-center transition-all duration-300"
            x-bind:class="{ 'p-6': !currentDonor, 'p-0': currentDonor}">

            <div x-show="!currentDonor" class="w-full">
                <h2 class="text-xl font-bold text-slate-900 mb-2">ค้นหาสมาชิก</h2>
                <div class="relative" @click.away="showDropdown = false">
                    <div class="flex gap-2">
                        <input x-ref="searchInput" x-model="searchQuery" @input.debounce.300ms="searchDonor()"
                            @focus="showDropdown = true" @keydown.enter.prevent="handleEnterKey()"
                            @keydown.escape="showDropdown = false" @keydown.arrow-down.prevent="moveSelection(1)"
                            @keydown.arrow-up.prevent="moveSelection(-1)" type="text"
                            placeholder="ป้อนเบอร์โทรหรือรหัสประจำตัว"
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition text-lg"
                            autocomplete="off">
                    </div>

                    <div x-show="showDropdown && (searchResults.length > 0 || isSearching)"
                        class="absolute top-full left-0 z-50 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-xl max-h-80 overflow-y-auto">

                        <div x-show="isSearching" class="p-4 text-center text-slate-500">
                            <span class="inline-block animate-spin mr-2">⏳</span> กำลังค้นหา...
                        </div>

                        <ul x-ref="resultList" x-show="!isSearching && searchResults.length > 0">
                            <template x-for="(result, index) in searchResults" :key="result.member_id">
                                <li @click="selectDonor(result)"
                                    :class="{ 'bg-purple-100 ring-1 ring-inset ring-purple-300': index === selectedIndex, 'hover:bg-purple-50': index !== selectedIndex }"
                                    class="px-4 py-3 cursor-pointer border-b border-slate-100 last:border-0 transition-colors group">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="font-bold text-slate-800 group-hover:text-purple-700"
                                                x-text="result.member_name"></p>
                                            <p class="text-xs text-slate-500">คณะ: <span
                                                    x-text="result.faculty_name"></span></p>
                                        </div>
                                        <div class="text-right">
                                            <span
                                                class="text-xs font-mono bg-slate-100 px-2 py-1 rounded text-slate-600"
                                                x-text="result.member_phone"></span>
                                        </div>
                                    </div>
                                </li>
                            </template>
                        </ul>
                        <div x-show="!isSearching && searchResults.length === 0 && searchQuery.length > 0"
                            class="p-4 text-center text-slate-500">❌ ไม่พบข้อมูล</div>
                    </div>
                </div>
            </div>

            <div x-show="currentDonor" class="w-full h-full">
                <div
                    class="relative bg-gradient-to-br from-purple-50 to-purple-100 border-2 border-purple-300 rounded-lg p-4 h-full flex flex-col justify-center">

                    <button @click="resetDonor()"
                        class="absolute top-2 right-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-full transition-colors p-1 z-10">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="m12 13.4l2.9 2.9q.275.275.7.275t.7-.275t.275-.7t-.275-.7L13.4 12l2.9-2.9q.275-.275.275-.7t-.275-.7t-.7-.275t-.7.275L12 10.6L9.1 7.7q-.275-.275-.7-.275t-.7.275t-.275.7t.275.7l2.9 2.9l-2.9 2.9q-.275.275-.275.7t.275.7t.7.275t.7-.275zm0 8.6q-2.075 0-3.9-.788t-3.175-2.137T2.788 15.9T2 12t.788-3.9t2.137-3.175T8.1 2.788T12 2t3.9.788t3.175 2.137T21.213 8.1T22 12t-.788 3.9t-2.137 3.175t-3.175 2.138T12 22m0-2q3.35 0 5.675-2.325T20 12t-2.325-5.675T12 4T6.325 6.325T4 12t2.325 5.675T12 20m0-8"
                                stroke-width="0.5" stroke="currentColor" />
                        </svg>
                    </button>

                    <div class="flex items-end justify-between pr-6">
                        <div>
                            <p class="text-xs font-bold text-purple-700 uppercase tracking-wider mb-1">ผู้บริจาค</p>
                            <h2 class="text-2xl font-bold text-slate-900 mb-1" x-text="currentDonor?.member_name"></h2>
                            <div class="flex gap-3 text-sm text-slate-600">
                                <span>เบอร์โทร: <span class="font-semibold"
                                        x-text="currentDonor?.member_phone"></span></span>
                                <span class="text-slate-300">|</span>
                                <span>คณะ: <span class="font-semibold"
                                        x-text="currentDonor?.faculty_name"></span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex-1 min-h-0 grid grid-cols-1 lg:grid-cols-3 gap-6 pb-1">

        <div class="lg:col-span-2 flex flex-col gap-4 h-full">
            <div x-show="currentDonor" style="display: none;"
                class="flex-none bg-white rounded-xl shadow-md p-6 card-hover z-10">
                <h2 class="text-xl font-bold text-slate-900 mb-3">📝 เพิ่มรายการสิ่งของ</h2>
                <div class="grid grid-cols-12 gap-3">
                    <div class="col-span-5">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">ชื่อสิ่งของ *</label>
                        <input x-ref="itemNameInput" x-model="donation.item_name"
                            @keydown.enter="$refs.amountInput.focus()" type="text"
                            placeholder="เช่น ขวดแก้ว, กระดาษลัง, เสื้อยืด"
                            class="w-full px-4 py-2 border-2 border-slate-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
                    </div>
                    <div class="col-span-3">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">จำนวน *</label>
                        <input x-ref="amountInput" x-model.number="donation.item_amount"
                            @keydown.enter="$refs.valueInput.focus()" type="number" min="1" placeholder="1"
                            class="w-full px-4 py-2 border-2 border-slate-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
                    </div>
                    <div class="col-span-4">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">มูลค่า/ชิ้น (฿) *</label>
                        <div class="flex gap-2">
                            <input x-ref="valueInput" x-model.number="donation.item_value" @keydown.enter="addItem()"
                                type="number" min="0" step="0.01" placeholder="0.00"
                                class="w-full px-4 py-2 border-2 border-slate-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
                            <button @click="addItem()"
                                class="px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-semibold transition-colors whitespace-nowrap">
                                เพิ่ม
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="currentDonor" style="display: none;"
                class="flex-1 min-h-0 bg-white rounded-xl shadow-xl p-6 flex flex-col"
                x-init="$watch('items', value => { $nextTick(() => { const container = $refs.listContainer; if (container) container.scrollTop = container.scrollHeight; }); })">

                <h2 class="text-xl font-bold text-slate-900 mb-2 shrink-0 flex items-center justify-between">
                    <span>รายการที่รับบริจาค</span>
                    <span class="bg-purple-100 text-purple-700 text-sm px-2 py-1 rounded-md"
                        x-text="items.length + ' รายการ'"></span>
                </h2>

                <div x-ref="listContainer" class="flex-1 min-h-0 overflow-y-auto pr-2 custom-scrollbar">
                    <table x-show="items.length > 0" class="w-full text-sm text-left text-slate-600">
                        <thead class="text-xs text-slate-700 uppercase bg-slate-100 sticky top-0 z-10 shadow-sm">
                            <tr>
                                <th scope="col" class="px-4 py-3 rounded-tl-lg text-center w-12">#</th>
                                <th scope="col" class="px-4 py-3">ชื่อสิ่งของ</th>
                                <th scope="col" class="px-4 py-3 text-right">จำนวน</th>
                                <th scope="col" class="px-4 py-3 text-right">มูลค่า/ชิ้น (฿)</th>
                                <th scope="col" class="px-4 py-3 text-right">รวม (฿)</th>
                                <th scope="col" class="px-4 py-3 text-center rounded-tr-lg w-16">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(item, index) in items" :key="index">
                                <tr class="border-b last:border-b-0 transition-colors bg-white hover:bg-slate-50 group">
                                    <td class="px-4 py-3 text-slate-500 text-center font-medium" x-text="index + 1">
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-slate-800" x-text="item.name"></td>
                                    <td class="px-4 py-3 text-right font-bold text-slate-700" x-text="item.amount"></td>
                                    <td class="px-4 py-3 text-right text-slate-600"
                                        x-text="parseFloat(item.value).toFixed(2)"></td>
                                    <td class="px-4 py-3 text-right font-bold text-purple-600"
                                        x-text="(item.amount * item.value).toFixed(2)"></td>
                                    <td class="px-4 py-3 text-center">
                                        <button @click="removeItem(index)"
                                            class="p-1.5 text-red-500 hover:bg-red-100 rounded-md transition-colors hover:text-red-600 cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                viewBox="0 0 24 24">
                                                <path fill="currentColor"
                                                    d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>

                    <div x-show="items.length === 0"
                        class="h-full flex flex-col items-center justify-center text-slate-400 opacity-60">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-2" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z" />
                        </svg>
                        <p class="text-sm">เพิ่มรายการจากฟอร์มด้านบน</p>
                    </div>
                </div>
            </div>

            <div x-show="!currentDonor"
                class="flex-1 min-h-0 flex flex-col items-center justify-center bg-slate-50 rounded-xl border-2 border-dashed border-slate-300 text-slate-400">
                <span class="text-5xl mb-3">
                    <svg class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24">
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2">
                            <path d="M21 12a9 9 0 1 0-9 9M9 10h.01M15 10h.01" />
                            <path d="M9.5 15c.658.672 1.56 1 2.5 1m3 2a3 3 0 1 0 6 0a3 3 0 1 0-6 0m5.2 2.2L22 22" />
                        </g>
                    </svg>
                </span>
                <p class="text-lg">กรุณาค้นหาและเลือกสมาชิกก่อนเริ่มทำรายการ</p>
            </div>
        </div>

        <div class="h-full rounded-xl shadow-md">
            <div
                class="h-full bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white flex flex-col justify-between overflow-y-auto custom-scrollbar">

                <div>
                    <h3 class="text-xl font-bold mb-4 flex items-center gap-2 border-b border-purple-400 pb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M19 3h-4.18C14.25 1.44 12.53.64 11 1.2c-.86.3-1.5.96-1.82 1.8H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2m-7 0a1 1 0 0 1 1 1a1 1 0 0 1-1 1a1 1 0 0 1-1-1a1 1 0 0 1 1-1M7 7h10V5h2v14H5V5h2zm10 4H7V9h10zm-2 4H7v-2h8z"
                                stroke-width="0.5" stroke="currentColor" />
                        </svg>
                        สรุปรายการ
                    </h3>

                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-purple-100 text-lg">จำนวนสิ่งของรวม</span>
                            <div class="text-right">
                                <span class="text-2xl font-bold" x-text="totalAmount()"></span>
                                <span class="text-sm text-purple-200">ชิ้น</span>
                            </div>
                        </div>

                        <div class="bg-purple-700/50 backdrop-blur-sm rounded-xl p-4 mt-4 space-y-2">
                            <div class="flex justify-between items-center text-purple-100">
                                <span class="text-sm">มูลค่ารวม</span>
                                <span class="text-xl font-bold text-white"
                                    x-text="`฿${calculateTotal().toFixed(2)}`"></span>
                            </div>
                            <div class="flex justify-between items-center text-purple-100">
                                <span class="text-sm">แต้มความดีที่จะได้รับ</span>
                                <span class="text-3xl font-bold text-amber-300"
                                    x-text="`${(calculateTotal() * 10).toLocaleString()}`"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <button @click="saveDonation()" :disabled="!canSave()"
                        :class="!canSave() ? 'bg-purple-800/50 cursor-not-allowed text-purple-200' : 'bg-white hover:bg-purple-50 text-purple-700 shadow-lg transform hover:-translate-y-0.5'"
                        class="w-full px-6 py-4 rounded-xl font-bold text-xl transition-all duration-200 flex items-center justify-center gap-2">
                        <span x-show="!isSaving">บันทึกทั้งหมดเข้าคลัง</span>
                        <span x-show="isSaving" class="flex items-center gap-2">⏳ กำลังบันทึก...</span>
                    </button>

                    <button @click="cancelAll()" :disabled="items.length === 0"
                        class="w-full px-6 py-3 bg-red-500/20 hover:bg-red-500/30 text-white rounded-xl font-semibold transition-colors border border-white/10 disabled:opacity-50 disabled:cursor-not-allowed">
                        ยกเลิกทั้งหมด
                    </button>
                    <p class="text-center text-purple-200 text-xs mt-2 opacity-70">กด <kbd
                            class="bg-purple-800/50 px-2 py-1 rounded text-white border border-purple-600/50">Ctrl+Enter</kbd>
                        เพื่อบันทึก</p>
                </div>
            </div>
        </div>
    </div>
    <div @keydown.ctrl.enter.window="saveDonation()"></div>
</div>