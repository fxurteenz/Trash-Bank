<?php
$member_id = $member_id ?? "null";
?>

<div x-data="userDetail()">
    <div class="w-full grid grid-cols-1 xl:grid-cols-2 2xl:grid-cols-3 gap-4">
        <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8 2xl:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">ข้อมูลส่วนตัว</h3>
                    <span class="text-base font-normal text-gray-500">รายละเอียดข้อมูลส่วนตัวของผู้ใช้</span>
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
                            <p class="text-sm text-gray-500">คณะ</p>
                            <p class="text-base font-semibold" x-text="profile.faculty_name"></p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500">สาขา</p>
                            <p class="text-base font-semibold" x-text="profile.major_name"></p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500">แต้มขยะ</p>
                            <p class="text-base font-semibold" x-text="profile.member_waste_point"></p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500">แต้มความดี</p>
                            <p class="text-base font-semibold" x-text="profile.member_goodness_point"></p>
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
                                    x-text="'+' + transaction.waste_transaction_total_point + ' แต้ม'">
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
                                        x-text="'จำนวน: ' + donation.donation_item_qty"></p>
                                    <p class="text-sm text-gray-500 truncate" x-text="donation.created_at"></p>
                                </div>
                                <div class="inline-flex items-center text-base font-semibold text-purple-700"
                                    x-text="'+' + donation.donation_goodness_point + ' แต้ม'">
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
</div>

<script>
    function userDetail() {
        return {
            member_id: <?php echo $member_id; ?>,
            profile: {},
            waste_transactions: [],
            donations: [],
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
            }
        }
    }
</script>