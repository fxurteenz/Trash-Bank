<div x-data="userDetail()">
    <div class="w-full grid grid-cols-3 gap-2 mb-4">
        <div
            class="bg-gradient-to-br from-sky-500 to-sky-600 rounded-xl shadow-lg p-4 text-white hover:scale-105 active:scale-95 duration-300 ease-out transition-all">
            <div class="flex items-center justify-between">
                <div class="space-y-2">
                    <h3 class="text-2xl text-white font-semibold">แต้มขยะ</h3>
                    <div class="w-12 h-12  bg-white rounded-full flex items-center justify-center text-sky-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24">
                            <g fill="none" stroke="currentColor" stroke-width="1.5">
                                <path
                                    d="M12 16c-5.76 0-6.78-5.74-6.96-10.294c-.051-1.266-.076-1.9.4-2.485c.475-.586 1.044-.682 2.183-.874A26.4 26.4 0 0 1 12 2c1.784 0 3.253.157 4.377.347c1.139.192 1.708.288 2.184.874s.45 1.219.4 2.485C18.781 10.26 17.761 16 12.001 16Z" />
                                <path stroke-linecap="round" d="M12 16v3" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.5 22h-7l.34-1.696a1 1 0 0 1 .98-.804h4.36a1 1 0 0 1 .98.804z" />
                                <path
                                    d="m19 5l.949.316c.99.33 1.485.495 1.768.888S22 7.12 22 8.162v.073c0 .86 0 1.291-.207 1.643s-.584.561-1.336.98L17.5 12.5M5 5l-.949.316c-.99.33-1.485.495-1.768.888S2 7.12 2 8.162v.073c0 .86 0 1.291.207 1.643s.584.561 1.336.98L6.5 12.5m4.646-6.477C11.526 5.34 11.716 5 12 5s.474.34.854 1.023l.098.176c.108.194.162.29.246.354c.085.064.19.088.4.135l.19.044c.738.167 1.107.25 1.195.532s-.164.577-.667 1.165l-.13.152c-.143.167-.215.25-.247.354s-.021.215 0 .438l.02.203c.076.785.114 1.178-.115 1.352c-.23.174-.576.015-1.267-.303l-.178-.082c-.197-.09-.295-.135-.399-.135s-.202.045-.399.135l-.178.082c-.691.319-1.037.477-1.267.303s-.191-.567-.115-1.352l.02-.203c.021-.223.032-.334 0-.438s-.104-.187-.247-.354l-.13-.152c-.503-.588-.755-.882-.667-1.165c.088-.282.457-.365 1.195-.532l.19-.044c.21-.047.315-.07.4-.135c.084-.064.138-.16.246-.354z" />
                                <path stroke-linecap="round" d="M18 22H6" />
                            </g>
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-bold" x-text="Number(profile.member_waste_point || 0).toLocaleString()">
                    </h3>
                    <h3 class="text-sm text-white">แต้ม</h3>
                </div>
            </div>
        </div>
        <div
            class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-4 text-white hover:scale-105 active:scale-95 duration-300 ease-out transition-all">
            <div class="flex items-center justify-between">
                <div class=" space-y-2">
                    <h3 class="text-2xl text-white font-semibold">แต้มความดี</h3>
                    <div class="w-12 h-12  bg-white rounded-full flex items-center justify-center text-yellow-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="m10.542 8.608l1.1-2.23a.678.678 0 0 1 1.216 0l1.1 2.23l2.461.357c.556.08.778.764.376 1.157l-1.78 1.735l.42 2.45a.678.678 0 0 1-.984.716l-2.201-1.157l-2.2 1.157a.678.678 0 0 1-.985-.715l.42-2.45l-1.78-1.736a.678.678 0 0 1 .376-1.157zm1.058.92a.68.68 0 0 1-.51.37l-1.454.212l1.052 1.025a.68.68 0 0 1 .195.6l-.249 1.448l1.3-.684a.68.68 0 0 1 .632 0l1.3.684l-.249-1.448a.68.68 0 0 1 .195-.6l1.052-1.025l-1.453-.212a.68.68 0 0 1-.51-.37L12.25 8.21zM6.5 2A2.5 2.5 0 0 0 4 4.5v15A2.5 2.5 0 0 0 6.5 22h13.25a.75.75 0 0 0 0-1.5H6.5a1 1 0 0 1-1-1h14.25a.75.75 0 0 0 .75-.75V4.5A2.5 2.5 0 0 0 18 2zM19 18H5.5V4.5a1 1 0 0 1 1-1H18a1 1 0 0 1 1 1z"
                                stroke-width="0.5" stroke="currentColor" />
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-bold" x-text="Number(profile.member_goodness_point || 0).toLocaleString()">
                    </h3>
                    <h3 class="text-sm text-white">แต้ม</h3>
                </div>
            </div>
        </div>
        <div
            class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-lg p-4 text-white hover:scale-105 active:scale-95 duration-300 ease-out transition-all">
            <div class="flex items-center justify-between">
                <div class="space-y-2">
                    <h3 class="text-2xl text-white font-semibold">แต้มรักษ์โลก</h3>
                    <div class="w-12 h-12  bg-white rounded-full flex items-center justify-center text-emerald-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 20 20">
                            <path fill="currentColor"
                                d="M10.254 17.996a8 8 0 1 1 7.742-7.742a5.5 5.5 0 0 0-1.008-.66A7 7 0 0 0 16.54 7.5h-2.733q.118.736.165 1.525a5.5 5.5 0 0 0-.992.188a15 15 0 0 0-.186-1.713H7.206A15 15 0 0 0 7 10c0 .883.073 1.725.206 2.5h2.169a5.5 5.5 0 0 0-.284 1H7.419c.153.59.342 1.126.56 1.592c.48 1.028 1.041 1.614 1.577 1.821q.287.586.698 1.083m1.768-13.088C11.407 3.59 10.657 3 10 3s-1.407.59-2.022 1.908A9.3 9.3 0 0 0 7.42 6.5h5.162a9.3 9.3 0 0 0-.56-1.592M6.389 6.5c.176-.743.407-1.422.683-2.015c.186-.399.401-.773.642-1.103A7.02 7.02 0 0 0 3.936 6.5zM6 10c0-.87.067-1.712.193-2.5H3.46A7 7 0 0 0 3 10c0 .88.163 1.724.46 2.5h2.733A16 16 0 0 1 6 10m1.072 5.515a10.5 10.5 0 0 1-.683-2.015H3.936a7.02 7.02 0 0 0 3.778 3.118a6.6 6.6 0 0 1-.642-1.103M16.064 6.5a7.02 7.02 0 0 0-3.778-3.118c.241.33.456.704.642 1.103c.276.593.507 1.272.683 2.015zM14.5 19a4.5 4.5 0 1 0 0-9a4.5 4.5 0 0 0 0 9m.954-5.608h1.544c.485 0 .687.647.295.944l-1.25.947l.477 1.532c.15.48-.378.88-.77.583l-1.25-.947l-1.25.947c-.392.297-.92-.103-.77-.583l.477-1.532l-1.25-.947c-.392-.297-.19-.944.294-.944h1.546l.477-1.531a.494.494 0 0 1 .952 0z"
                                stroke-width="0.5" stroke="currentColor" />
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-bold" x-text="Number(profile.member_goodness_point || 0).toLocaleString()">
                    </h3>
                    <h3 class="text-sm text-white">แต้ม</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="w-full grid grid-cols-1 gap-4">
        <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8 2xl:col-span-2">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">ข้อมูลส่วนตัว</h3>
                    <span class="text-base font-normal text-gray-500">รายละเอียดข้อมูลส่วนตัวของผู้ใช้</span>
                </div>
                <div class="flex items-center space-x-1 py-2 px-4 bg-gray-100 rounded-md">
                    <button @click="openEditDialog()"
                        class="flex items-center gap-1 bg-gradient-to-br from-amber-400 to-amber-500 px-3 py-2 text-white hover:bg-gradient-to-br hover:from-amber-500 hover:to-amber-600 hover:scale-105 cursor-pointer rounded-md shadow-sm"
                        title="แก้ไขข้อมูล">
                        <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span class="text-sm font-medium">แก้ไข</span>
                    </button>
                    <button @click="confirmDeleteUser()"
                        class="flex items-center gap-1 bg-gradient-to-br from-red-400 to-red-500 px-3 py-2 text-white hover:bg-gradient-to-br hover:from-red-500 hover:to-red-600 hover:scale-105 cursor-pointer rounded-md shadow-sm"
                        title="ลบผู้ใช้งาน">
                        <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span class="text-sm font-medium">ลบ</span>
                    </button>
                    <div class="w-px h-6 bg-gray-300 mx-2"></div>
                    <button @click="openWasteDeposit(profile)"
                        class="bg-gradient-to-br from-emerald-500 to-emerald-600 p-2 text-white hover:bg-gradient-to-br hover:from-emerald-600 hover:to-emerald-700 hover:scale-105 cursor-pointer rounded-md flex gap-1">
                        <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                            viewBox="0 0 24 24">
                            <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.5">
                                <path stroke-linejoin="round"
                                    d="M12 22c-.818 0-1.6-.325-3.163-.974C4.946 19.41 3 18.602 3 17.243V7.745M12 22c.818 0 1.6-.325 3.163-.974C19.054 19.41 21 18.602 21 17.243V7.745M12 22v-9.831M3 7.745c0 .603.802.985 2.405 1.747l2.92 1.39C10.13 11.74 11.03 12.17 12 12.17M3 7.745c0-.604.802-.986 2.405-1.748L7.5 5M21 7.745c0 .603-.802.985-2.405 1.747l-2.92 1.39C13.87 11.74 12.97 12.17 12 12.17m9-4.424c0-.604-.802-.986-2.405-1.748L16.5 5M6 13.152l2 .983" />
                                <path
                                    d="M12.004 2v7m0 0c.263.004.522-.18.714-.405L14 7.062M12.004 9c-.254-.003-.511-.186-.714-.405L10 7.062" />
                            </g>
                        </svg>
                        <span class="text-sm font-medium">ฝาก</span>
                    </button>
                    <button @click="openRedeem(profile)"
                        class="bg-gradient-to-br from-yellow-500 to-yellow-600 p-2 text-white hover:bg-gradient-to-br hover:from-yellow-600 hover:to-yellow-700 hover:scale-105 cursor-pointer rounded-md flex gap-1">
                        <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M11.3 8.3L9.2 6.2q-.3-.3-.3-.7t.3-.7l2.1-2.1q.3-.3.7-.3t.7.3l2.1 2.1q.3.3.3.7t-.3.7l-2.1 2.1q-.3.3-.7.3t-.7-.3M2 20q-.425 0-.712-.288T1 19v-3q0-.85.588-1.425T3 14h3.275q.5 0 .95.25t.725.675q.725.975 1.788 1.525T12 17q1.225 0 2.288-.55t1.762-1.525q.325-.425.763-.675t.912-.25H21q.85 0 1.425.575T23 16v3q0 .425-.288.713T22 20h-5q-.425 0-.712-.288T16 19v-1.275q-.875.625-1.888.95T12 19q-1.075 0-2.1-.337T8 17.7V19q0 .425-.288.713T7 20zm2-7q-1.25 0-2.125-.875T1 10q0-1.275.875-2.137T4 7q1.275 0 2.138.863T7 10q0 1.25-.862 2.125T4 13m16 0q-1.25 0-2.125-.875T17 10q0-1.275.875-2.137T20 7q1.275 0 2.138.863T23 10q0 1.25-.862 2.125T20 13"
                                stroke-width="0.5" stroke="currentColor" />
                        </svg>
                        <span class="text-sm font-medium">แลก</span>
                    </button>
                    <button @click="openDonation(profile)"
                        class="bg-gradient-to-br from-purple-500 to-purple-600 p-2 text-white hover:bg-gradient-to-br hover:from-purple-600 hover:to-purple-700 hover:scale-105 cursor-pointer rounded-md flex gap-1">
                        <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M4 21h9.62a4 4 0 0 0 3.037-1.397l5.102-5.952a1 1 0 0 0-.442-1.6l-1.968-.656a3.04 3.04 0 0 0-2.823.503l-3.185 2.547l-.617-1.235A3.98 3.98 0 0 0 9.146 11H4c-1.103 0-2 .897-2 2v6c0 1.103.897 2 2 2m0-8h5.146c.763 0 1.448.423 1.789 1.105l.447.895H7v2h6.014a1 1 0 0 0 .442-.11l.003-.001l.004-.002h.003l.002-.001h.004l.001-.001c.009.003.003-.001.003-.001c.01 0 .002-.001.002-.001h.001l.002-.001l.003-.001l.002-.001l.002-.001l.003-.001l.002-.001c.003 0 .001-.001.002-.001l.003-.002l.002-.001l.002-.001l.003-.001l.002-.001h.001l.002-.001h.001l.002-.001l.002-.001c.009-.001.003-.001.003-.001l.002-.001a1 1 0 0 0 .11-.078l4.146-3.317c.262-.208.623-.273.94-.167l.557.186l-4.133 4.823a2.03 2.03 0 0 1-1.52.688H4zM16 2h-.017c-.163.002-1.006.039-1.983.705c-.951-.648-1.774-.7-1.968-.704L12.002 2h-.004c-.801 0-1.555.313-2.119.878C9.313 3.445 9 4.198 9 5s.313 1.555.861 2.104l3.414 3.586a1.006 1.006 0 0 0 1.45-.001l3.396-3.568C18.688 6.555 19 5.802 19 5s-.313-1.555-.878-2.121A2.98 2.98 0 0 0 16.002 2zm1 3c0 .267-.104.518-.311.725L14 8.55l-2.707-2.843C11.104 5.518 11 5.267 11 5s.104-.518.294-.708A.98.98 0 0 1 11.979 4c.025.001.502.032 1.067.485q.121.098.247.222l.707.707l.707-.707q.126-.124.247-.222c.529-.425.976-.478 1.052-.484a1 1 0 0 1 .701.292c.189.189.293.44.293.707"
                                stroke-width="0.5" stroke="currentColor" />
                        </svg>
                        <span class="text-sm font-medium">รับ</span>
                    </button>
                </div>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-full">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500">ชื่อ</p>
                            <p class="text-base font-semibold" x-text="profile.member_name"></p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500">เบอร์โทรศัพท์</p>
                            <p class="text-base font-semibold" x-text="profile.member_phone"></p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500">สาขา</p>
                            <p class="text-base font-semibold" x-text="profile.major_name"></p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500">คณะ</p>
                            <p class="text-base font-semibold" x-text="profile.faculty_name"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 2xl:grid-cols-2 xl:gap-4 my-4">
        <div class="bg-white shadow rounded-lg mb-4 p-4 sm:p-6 h-full">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold leading-none text-gray-900">ประวัติการฝากขยะ</h3>
            </div>
            <div class="flow-root">
                <ul role="list" class="divide-y divide-gray-200">
                    <template x-for="transaction in waste_transactions" :key="transaction.waste_transaction_id">
                        <li class="py-3 sm:py-4">
                            <div class="flex items-center space-x-4">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate"
                                        x-text="'รหัส: ' + transaction.waste_transaction_id"></p>
                                    <p class="text-sm text-gray-500 truncate" x-text="transaction.created_at"></p>
                                </div>
                                <div class="inline-flex items-center text-base font-semibold text-emerald-700"
                                    x-text="'+' + Number(transaction.waste_transaction_total_point || 0).toLocaleString() + ' แต้ม'">
                                </div>
                            </div>
                        </li>
                    </template>
                    <template x-if="waste_transactions.length === 0">
                        <li class="py-3 sm:py-4 text-center text-gray-500">
                            ไม่มีข้อมูล
                        </li>
                    </template>
                </ul>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-xl font-bold leading-none text-gray-900">ประวัติการบริจาค</h3>
            </div>
            <div class="flow-root">
                <ul role="list" class="divide-y divide-gray-200">
                    <template x-for="donation in donations" :key="donation.donation_id">
                        <li class="py-3 sm:py-4">
                            <div class="flex items-center space-x-4">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate"
                                        x-text="donation.donation_item_name"></p>
                                    <p class="text-sm text-gray-500 truncate"
                                        x-text="'จำนวน: ' + Number(donation.donation_item_qty || 0).toLocaleString()">
                                    </p>
                                    <p class="text-sm text-gray-500 truncate" x-text="donation.created_at"></p>
                                </div>
                                <div class="inline-flex items-center text-base font-semibold text-purple-700"
                                    x-text="'+' + Number(donation.donation_goodness_point || 0).toLocaleString() + ' แต้ม'">
                                </div>
                            </div>
                        </li>
                    </template>
                    <template x-if="donations.length === 0">
                        <li class="py-3 sm:py-4 text-center text-gray-500">
                            ไม่มีข้อมูล
                        </li>
                    </template>
                </ul>
            </div>
        </div>
    </div>

    <!-- Edit Member Modal -->
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30"
        @click.self="editUserDialogShow = false" x-show="editUserDialogShow" style="display: none;">
        <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md border border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 mb-4">แก้ไขข้อมูลผู้ใช้งาน</h3>
            <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">เบอร์โทรศัพท์ <span
                            class="text-red-500">*</span></label>
                    <input type="text" x-model="editUserForm.member_phone"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                    <span x-show="errors.member_phone" class="text-red-500 text-xs">กรุณากรอกหมายเลขโทรศัพท์</span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">รหัสประจำตัว</label>
                    <input type="text" x-model="editUserForm.member_personal_id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">อีเมล</label>
                    <input type="email" x-model="editUserForm.member_email"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อที่ใช้แสดงผล</label>
                    <input type="text" x-model="editUserForm.member_name"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">บทบาท <span
                            class="text-red-500">*</span></label>
                    <select x-model="editUserForm.role_id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none bg-white">
                        <option value="">เลือกบทบาท</option>
                        <?php if ((int) $user->role_id == 1): ?>
                            <option value="1">ผู้ดูแลระบบ</option>
                        <?php endif; ?>
                        <option value="2">ผู้ใช้งานทั่วไป</option>
                        <option value="3">เจ้าหน้าที่จุดฝาก/เจ้าหน้าที่คณะ</option>
                    </select>
                    <span x-show="errors.role_id" class="text-red-500 text-xs">กรุณาเลือกบทบาท</span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">คณะ</label>
                    <select x-model="editUserForm.faculty_id" @change="fetchMajors(editUserForm.faculty_id)"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none bg-white">
                        <option value="">เลือกคณะ</option>
                        <template x-for="faculty in faculties" :key="faculty.faculty_id">
                            <option :value="faculty.faculty_id" x-text="faculty.faculty_name"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">สาขา</label>
                    <select x-model="editUserForm.major_id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none bg-white disabled:bg-gray-100"
                        :disabled="!editUserForm.faculty_id">
                        <option value="">เลือกสาขา</option>
                        <template x-for="major in majors" :key="major.major_id">
                            <option :value="major.major_id" x-text="major.major_name"></option>
                        </template>
                    </select>
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button @click="editUserDialogShow = false"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">ยกเลิก</button>
                <button @click="submitEditUser"
                    class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition">บันทึก</button>
            </div>
        </div>
    </div>
</div>

<script>
    function userDetail() {
        return {
            member_id: <?php echo $member_id; ?>,
            manager_role: "<?= $user->role_name ?>",
            profile: {},
            waste_transactions: [],
            donations: [],

            editUserDialogShow: false,
            editUserForm: {
                member_personal_id: "",
                member_phone: "",
                member_name: "",
                member_email: "",
                faculty_id: "",
                major_id: "",
                role_id: "",
            },
            errors: {},
            faculties: [],
            majors: [],

            init() {
                this.fetchProfile();
            },
            async fetchProfile() {
                try {
                    const response = await fetch(`/api/members/profile/${this.member_id}`);
                    // if (!response.ok) {
                    //     console.error(response);
                    //     throw new Error('Network response was not ok');
                    // }
                    const result = await response.json();
                    if (result.success) {
                        this.profile = result.data;
                        this.waste_transactions = result.data.waste_transactions;
                        this.donations = result.data.donations;
                    } else {
                        throw new Error(result.message);
                    }
                } catch (error) {
                    console.error('There has been a problem with your fetch operation:', error);
                    swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: error.message,
                    });
                }
            },
            async fetchFaculties() {
                try {
                    const res = await fetch('/api/faculties');
                    const json = await res.json();
                    if (json.success) {
                        this.faculties = json.data || json.result || [];
                    }
                } catch (e) { console.error(e); }
            },
            async fetchMajors(facultyId) {
                if (!facultyId) {
                    this.majors = [];
                    this.editUserForm.major_id = "";
                    return;
                }
                try {
                    const res = await fetch(`/api/majors/faculty/${facultyId}`);
                    const json = await res.json();
                    if (json.success) {
                        this.majors = json.result || json.data || [];
                    } else {
                        this.majors = [];
                    }
                } catch (e) {
                    console.error(e);
                    this.majors = [];
                }
            },
            async openEditDialog() {
                await this.fetchFaculties();
                this.editUserForm = {
                    member_personal_id: this.profile.member_personal_id ?? "",
                    member_phone: this.profile.member_phone ?? "",
                    member_name: this.profile.member_name ?? "",
                    member_email: this.profile.member_email ?? "",
                    faculty_id: this.profile.faculty_id ?? "",
                    major_id: this.profile.major_id ?? "",
                    role_id: parseInt(this.profile.role_id) || "",
                };
                if (this.editUserForm.faculty_id) {
                    await this.fetchMajors(this.editUserForm.faculty_id);
                    this.editUserForm.major_id = this.profile.major_id ?? "";
                }
                this.errors = {};
                this.editUserDialogShow = true;
            },
            validateForm() {
                let isValid = true;
                this.errors = {};
                if (!this.editUserForm.member_phone) {
                    this.errors.member_phone = true;
                    isValid = false;
                }
                if (!this.editUserForm.role_id) {
                    this.errors.role_id = true;
                    isValid = false;
                }
                return isValid;
            },
            async submitEditUser() {
                if (!this.validateForm()) {
                    Swal.fire('แจ้งเตือน', 'กรุณากรอกข้อมูลในช่องที่มีเครื่องหมายดอกจัน (*) ให้ครบ', 'warning');
                    return;
                }
                try {
                    const res = await fetch(`/api/members/update/${this.member_id}`, {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify(this.editUserForm),
                    });
                    const response = await res.json();
                    if (response.success) {
                        Swal.fire({ icon: "success", title: "แก้ไขสำเร็จ", timer: 2000, showConfirmButton: false });
                        this.editUserDialogShow = false;
                        this.fetchProfile();
                    } else {
                        throw new Error(response.message || "Something went wrong");
                    }
                } catch (error) {
                    Swal.fire('ข้อผิดพลาด', error.message || 'ไม่สามารถแก้ไขข้อมูลได้', 'error');
                }
            },
            async confirmDeleteUser() {
                const result = await Swal.fire({
                    title: "ยืนยันการลบ",
                    html: `<p>ต้องการลบผู้ใช้ <span class="text-red-500 font-semibold">"${this.profile.member_name || this.profile.member_phone}"</span> ใช่หรือไม่?</p>`,
                    icon: "warning",
                    showConfirmButton: true,
                    confirmButtonText: "ยืนยันการลบ",
                    confirmButtonColor: "#d33",
                    showCancelButton: true,
                    cancelButtonText: "ยกเลิก"
                });

                if (result.isConfirmed) {
                    try {
                        const res = await fetch("/api/members/delete", {
                            method: "POST",
                            headers: { "Content-Type": "application/json" },
                            body: JSON.stringify({ member_ids: [this.member_id] }),
                        });
                        const data = await res.json();
                        if (data.success) {
                            await Swal.fire({ icon: "success", title: "ลบสำเร็จ", timer: 2000, showConfirmButton: false });
                            window.history.back();
                        } else {
                            throw new Error(data.message || "ลบไม่สำเร็จ");
                        }
                    } catch (error) {
                        Swal.fire("ผิดพลาด", error.message, "error");
                    }
                }
            },
            openWasteDeposit(member) {
                window.open(`/${this.manager_role}/transactions/waste?member_id=${member.member_id}`, '_blank');
            },
            openRedeem(member) {
                window.open(`/${this.manager_role}/transactions/redeem_item?member_id=${member.member_id}`, '_blank');
            },
            openDonation(member) {
                window.open(`/${this.manager_role}/transactions/donation?member_id=${member.member_id}`, '_blank');
            },

        }
    }
</script>