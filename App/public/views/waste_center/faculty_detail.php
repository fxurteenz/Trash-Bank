<?php
// Faculty Detail Page
// Shows: Faculty info, waste items in storage, points distribution, total weight
?>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <a href="/waste_center/manage/faculties" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
                ← กลับ
            </a>
            <h1 class="text-3xl font-bold text-gray-900">รายละเอียดคณะ</h1>
        </div>

        <!-- Faculty Info Card -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-sm text-gray-600">ชื่อคณะ</p>
                    <p class="text-xl font-semibold text-gray-900"><?php echo htmlspecialchars($faculty['faculty_name'] ?? '-') ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">รหัสคณะ</p>
                    <p class="text-lg text-gray-900"><?php echo htmlspecialchars($faculty['faculty_code'] ?? '-') ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">ที่อยู่</p>
                    <p class="text-gray-900"><?php echo htmlspecialchars($faculty['faculty_address'] ?? '-') ?></p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Waste Items & Weight -->
            <div class="lg:col-span-2">
                <!-- Waste Items in Storage -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">ปริมาณขยะในคลัง</h2>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b-2 border-gray-300">
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">ชนิดขยะ</th>
                                    <th class="text-center py-3 px-4 font-semibold text-gray-700">น้ำหนัก (กก.)</th>
                                    <th class="text-center py-3 px-4 font-semibold text-gray-700">แต้มต่อกก.</th>
                                    <th class="text-center py-3 px-4 font-semibold text-gray-700">รวมแต้ม</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($wasteItems)): ?>
                                    <?php 
                                    $totalWeight = 0;
                                    foreach ($wasteItems as $item): 
                                        $totalWeight += $item['stock_weight'];
                                        $itemPoints = $item['stock_weight'] * $item['waste_type_point_per_kg'];
                                    ?>
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="py-3 px-4 text-gray-900">
                                                <?php echo htmlspecialchars($item['waste_type_name']) ?>
                                            </td>
                                            <td class="py-3 px-4 text-center font-semibold text-gray-900">
                                                <?php echo number_format($item['stock_weight'], 2) ?>
                                            </td>
                                            <td class="py-3 px-4 text-center text-gray-700">
                                                <?php echo number_format($item['waste_type_point_per_kg'], 2) ?>
                                            </td>
                                            <td class="py-3 px-4 text-center font-semibold text-blue-600">
                                                <?php echo number_format($itemPoints, 0) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr class="bg-gray-100 font-bold">
                                        <td class="py-3 px-4 text-gray-900">รวมทั้งสิ้น</td>
                                        <td class="py-3 px-4 text-center text-gray-900">
                                            <?php echo number_format($totalWeight, 2) ?> กก.
                                        </td>
                                        <td class="py-3 px-4"></td>
                                        <td class="py-3 px-4 text-center text-blue-700">
                                            <?php 
                                            $totalPoints = 0;
                                            foreach ($wasteItems as $item) {
                                                $totalPoints += $item['stock_weight'] * $item['waste_type_point_per_kg'];
                                            }
                                            echo number_format($totalPoints, 0);
                                            ?>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="py-6 px-4 text-center text-gray-500">
                                            ไม่มีข้อมูลขยะในคลัง
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Column: Points Stats -->
            <div>
                <!-- Points Summary -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">สรุปแต้ม</h2>
                    
                    <div class="space-y-6">
                        <!-- Current Points -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-5 border-l-4 border-blue-600">
                            <p class="text-sm text-gray-600 mb-2">แต้มปัจจุบัน</p>
                            <p class="text-3xl font-bold text-blue-700">
                                <?php echo number_format($facultyStats['current_points'] ?? 0, 0) ?>
                            </p>
                            <p class="text-xs text-gray-500 mt-2">จากขยะในคลัง</p>
                        </div>

                        <!-- Points Given to Users -->
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-5 border-l-4 border-green-600">
                            <p class="text-sm text-gray-600 mb-2">แต้มที่มอบให้ผู้ใช้</p>
                            <p class="text-3xl font-bold text-green-700">
                                <?php echo number_format($facultyStats['given_points'] ?? 0, 0) ?>
                            </p>
                            <p class="text-xs text-gray-500 mt-2">รวมทั้งสิ้น</p>
                        </div>

                        <!-- Total Points Ever -->
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-5 border-l-4 border-purple-600">
                            <p class="text-sm text-gray-600 mb-2">แต้มสะสม</p>
                            <p class="text-3xl font-bold text-purple-700">
                                <?php echo number_format(($facultyStats['current_points'] ?? 0) + ($facultyStats['given_points'] ?? 0), 0) ?>
                            </p>
                            <p class="text-xs text-gray-500 mt-2">ปัจจุบัน + มอบให้</p>
                        </div>

                        <!-- Total Weight -->
                        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-5 border-l-4 border-orange-600">
                            <p class="text-sm text-gray-600 mb-2">น้ำหนักขยะรวม</p>
                            <p class="text-3xl font-bold text-orange-700">
                                <?php 
                                $totalWgt = 0;
                                foreach ($wasteItems as $item) {
                                    $totalWgt += $item['stock_weight'];
                                }
                                echo number_format($totalWgt, 2);
                                ?>
                            </p>
                            <p class="text-xs text-gray-500 mt-2">กิโลกรัม</p>
                        </div>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border-l-4 border-blue-600 rounded-lg p-4">
                    <p class="text-sm text-gray-700">
                        <strong>หมายเหตุ:</strong> แต้มปัจจุบันคำนวณจากปริมาณขยะในคลังคูณกับแต้มต่อกิโลกรัมของแต่ละชนิด
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
