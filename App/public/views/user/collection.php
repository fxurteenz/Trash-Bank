<style>
  .uc-page { display:grid; gap:12px; }
  .uc-head { background:linear-gradient(135deg,#0e8b53,#0a6f43); color:#fff; border-radius:18px; padding:14px; box-shadow:0 10px 24px rgba(0,0,0,.16); }
  .uc-head h1 { font-size:22px; font-weight:900; }
  .uc-head p { opacity:.9; font-size:12px; margin-top:2px; }
  .uc-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:10px; }
  .uc-card { background:#fff; border-radius:14px; padding:12px; box-shadow:0 8px 20px rgba(0,0,0,.14); text-align:center; }
  .uc-icon { font-size:36px; }
  .uc-name { margin-top:8px; color:#1b3b2d; font-size:13px; font-weight:800; }
  .uc-meta { margin-top:2px; color:#6b7f76; font-size:11px; }
</style>

<div class="uc-page">
  <div class="uc-head">
    <h1>เหรียญและใบประกาศ</h1>
    <p>ประวัติความสำเร็จจากการฝากขยะและกิจกรรมเพื่อสังคม</p>
  </div>

  <div class="uc-grid">
    <?php
      $badges = [
        ['icon'=>'🥉','name'=>'นักแยกขยะเริ่มต้น','meta'=>'ครบ 10 กก.'],
        ['icon'=>'🥈','name'=>'นักฝากต่อเนื่อง','meta'=>'ฝาก 7 วันติด'],
        ['icon'=>'🥇','name'=>'ฮีโร่รีไซเคิล','meta'=>'ครบ 100 กก.'],
        ['icon'=>'🏅','name'=>'พลเมืองสีเขียว','meta'=>'แต้มความดี 500'],
        ['icon'=>'🎖️','name'=>'ช่วยโลกขั้นสูง','meta'=>'Carbon 50 กก.'],
        ['icon'=>'🏆','name'=>'สมาชิกต้นแบบ','meta'=>'อันดับ Top 10'],
      ];
      foreach($badges as $badge):
    ?>
      <article class="uc-card">
        <div class="uc-icon"><?= $badge['icon'] ?></div>
        <div class="uc-name"><?= $badge['name'] ?></div>
        <div class="uc-meta"><?= $badge['meta'] ?></div>
      </article>
    <?php endforeach; ?>
  </div>
</div>
