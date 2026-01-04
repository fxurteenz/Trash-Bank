<div>
  <h1 style="font-size:28px;font-weight:800;margin:10px 0">📜 ภารกิจ</h1>
  <p style="opacity:.9;margin-bottom:20px">ทำภารกิจประจำวันเพื่อรับรางวัล</p>
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:14px">
    <?php $quests=[
      ['icon'=>'♻️','name'=>'รีไซเคิลขยะ 2 ครั้ง','reward'=>'+50 แต้ม'],
      ['icon'=>'🌱','name'=>'ลดคาร์บอน 5 กก.','reward'=>'+40 แต้ม'],
      ['icon'=>'💰','name'=>'สะสมรายได้ 100 บาท','reward'=>'+30 แต้ม'],
      ['icon'=>'🎁','name'=>'รับของรางวัล 1 ครั้ง','reward'=>'+20 แต้ม'],
    ]; foreach($quests as $q): ?>
    <div style="background:#fff;border-radius:16px;padding:18px;color:#333;display:flex;gap:12px;align-items:center;box-shadow:0 10px 24px rgba(0,0,0,.2)">
      <div style="font-size:36px;"><?= $q['icon'] ?></div>
      <div style="flex:1">
        <div style="font-weight:800;"><?= $q['name'] ?></div>
        <div style="opacity:.8">รางวัล: <?= $q['reward'] ?></div>
      </div>
      <button style="background:linear-gradient(90deg,#56ab2f,#a8e063);color:#fff;border:none;padding:10px 14px;border-radius:12px;font-weight:800;cursor:pointer">ทำเลย</button>
    </div>
    <?php endforeach; ?>
  </div>
<?php $activeTab='quests'; ?>
