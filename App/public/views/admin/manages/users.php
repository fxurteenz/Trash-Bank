<div class="space-y-2 w-full">
    
    <div class="bg-white rounded-lg shadow p-6 overflow-x-auto w-full">
        <table class="w-50 md:w-full table-auto border-collapse border border-gray-300 text-xs">
            <thead class="bg-gray-200 text-sm">
                <tr>
                    <th class="border border-gray-300 px-4 py-2">คณะ</th>
                    <th class="border border-gray-300 px-4 py-2">สาขาวิชา</th>
                    <th class="border border-gray-300 px-4 py-2">อีเมลล์</th>
                    <th class="border border-gray-300 px-4 py-2">บทบาท</th>
                    <th class="border border-gray-300 px-4 py-2">ชื่อ</th>
                    <th class="border border-gray-300 px-4 py-2">คะแนน</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($AllUsersData as $index => $user): ?>
                    <tr id="<?= $user["account_id"] ?>">
                        <?php foreach ($user as $key => $value): ?>
                            <?php if ($key !== "account_password" && $key !== "account_id"): ?>
                                <td class="border border-gray-300 px-4 py-2 text-ellipse">
                                    <?= !empty($value) ? htmlspecialchars($value) : "ไม่มีข้อมูล"; ?>
                                </td>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="bg-white rounded-lg shadow p-6 overflow-x-auto w-full">
    </div>
</div>