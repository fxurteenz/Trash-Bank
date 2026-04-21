<style>
  .uq-page { display:grid; gap:12px; }
  .uq-head { background:linear-gradient(135deg,#0e8b53,#0a6f43); color:#fff; border-radius:18px; padding:14px; box-shadow:0 10px 24px rgba(0,0,0,.16); }
  .uq-head h1 { font-size:22px; font-weight:900; }
  .uq-head p { opacity:.9; font-size:12px; margin-top:2px; }
  .uq-list { display:grid; gap:10px; }
  .uq-item { background:#fff; border-radius:14px; padding:12px; box-shadow:0 8px 20px rgba(0,0,0,.14); display:flex; align-items:center; gap:10px; }
  .uq-icon { width:40px; height:40px; border-radius:12px; background:#e9f9f0; display:grid; place-items:center; font-size:20px; flex-shrink:0; }
  .uq-detail { flex:1; }
  .uq-name { color:#1b3b2d; font-size:14px; font-weight:800; }
  .uq-reward { color:#6b7f76; font-size:12px; margin-top:2px; }
  .uq-btn { border:0; border-radius:10px; padding:8px 10px; color:#fff; font-weight:800; font-size:12px; background:linear-gradient(135deg,#22c55e,#16a34a); }
</style>

<div class="uq-page">
  <div class="uq-head">
    <h1>ภารกิจประจำวัน</h1>
    <p>ทำภารกิจเพื่อรับแต้มขยะ แต้มความดี และเครดิตเพิ่ม</p>
  </div>

  <div class="uq-list">
    <?php $quests=[
      ['icon'=>'♻️','name'=>'ฝากขยะรีไซเคิล 2 ครั้ง','reward'=>'+80 แต้มขยะ'],
      ['icon'=>'💚','name'=>'ชวนเพื่อนร่วมกิจกรรม 1 คน','reward'=>'+60 แต้มความดี'],
      ['icon'=>'🌿','name'=>'ลดคาร์บอนสะสม 3 กก.','reward'=>'+30 เครดิตคาร์บอน'],
      ['icon'=>'🏷️','name'=>'แยกประเภทขยะครบ 4 หมวด','reward'=>'+45 แต้มขยะ'],
      ['icon'=>'📸','name'=>'ส่งหลักฐานกิจกรรมสีเขียว','reward'=>'+55 แต้มความดี'],
    ]; foreach($quests as $q): ?>
      <article class="uq-item">
        <div class="uq-icon"><?= $q['icon'] ?></div>
        <div class="uq-detail">
          <div class="uq-name"><?= $q['name'] ?></div>
          <div class="uq-reward">รางวัล: <?= $q['reward'] ?></div>
        </div>
        <button class="uq-btn">รับงาน</button>
      </article>
    <?php endforeach; ?>
  </div>
</div>
