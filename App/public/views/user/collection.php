<div>
  <h1 style="font-size:28px;font-weight:800;margin:10px 0">🏆 ของสะสม</h1>
  <p style="opacity:.9;margin-bottom:20px">สะสมตรา/รางวัลจากกิจกรรมต่าง ๆ</p>
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:14px">
    <?php for($i=1;$i<=18;$i++): ?>
    <div style="background:#fff;border-radius:16px;padding:16px;color:#333;text-align:center;box-shadow:0 10px 24px rgba(0,0,0,.2)">
      <div style="font-size:42px">🥇</div>
      <div style="font-weight:800;margin-top:8px">เหรียญที่ <?= $i ?></div>
    </div>
    <?php endfor; ?>
  </div>
<?php $activeTab='collection'; ?>
