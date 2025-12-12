<div>
  <h1 style="font-size:28px;font-weight:800;margin:10px 0">🛡️ อุปกรณ์</h1>
  <p style="opacity:.9;margin-bottom:20px">จัดการอุปกรณ์และอัปเกรดความสามารถ</p>
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(120px,1fr));gap:12px">
    <?php $icons=['🗡️','🪖','👕','👟','💍','🎯','🧪','🧿','🧤','🧢','🛡️','🧷']; foreach($icons as $i=>$ic): ?>
    <div style="background:#fff;border-radius:16px;padding:16px;color:#333;text-align:center;box-shadow:0 10px 24px rgba(0,0,0,.2)">
      <div style="font-size:42px;"><?= $ic ?></div>
      <div style="font-weight:800;margin-top:8px">ไอเทม #<?= $i+1 ?></div>
    </div>
    <?php endforeach; ?>
  </div>
<?php $activeTab='equipment'; ?>
