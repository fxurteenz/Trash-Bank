<style>
  .ue-page { display:grid; gap:12px; }
  .ue-head { background:linear-gradient(135deg,#0e8b53,#0a6f43); color:#fff; border-radius:18px; padding:14px; box-shadow:0 10px 24px rgba(0,0,0,.16); }
  .ue-head h1 { font-size:22px; font-weight:900; }
  .ue-head p { opacity:.9; font-size:12px; margin-top:2px; }
  .ue-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:10px; }
  .ue-card { background:#fff; border-radius:14px; padding:12px 8px; text-align:center; box-shadow:0 8px 20px rgba(0,0,0,.14); }
  .ue-icon { font-size:30px; }
  .ue-name { margin-top:8px; color:#1b3b2d; font-size:12px; font-weight:800; }
  .ue-level { margin-top:3px; color:#6b7f76; font-size:11px; }
</style>

<div class="ue-page">
  <div class="ue-head">
    <h1>อุปกรณ์สมาชิก</h1>
    <p>อุปกรณ์ช่วยแยกขยะและเพิ่มประสิทธิภาพการฝาก</p>
  </div>

  <div class="ue-grid">
    <?php
      $items = [
        ['icon'=>'🧤','name'=>'ถุงมือแยกขยะ','lv'=>'Lv.2'],
        ['icon'=>'🧺','name'=>'ตะกร้ารีไซเคิล','lv'=>'Lv.3'],
        ['icon'=>'⚖️','name'=>'เครื่องชั่งพกพา','lv'=>'Lv.1'],
        ['icon'=>'🏷️','name'=>'ชุดป้ายแยกประเภท','lv'=>'Lv.2'],
        ['icon'=>'🧴','name'=>'สเปรย์ทำความสะอาด','lv'=>'Lv.1'],
        ['icon'=>'📦','name'=>'กล่องเก็บวัสดุ','lv'=>'Lv.4'],
      ];
      foreach($items as $item):
    ?>
      <article class="ue-card">
        <div class="ue-icon"><?= $item['icon'] ?></div>
        <div class="ue-name"><?= $item['name'] ?></div>
        <div class="ue-level"><?= $item['lv'] ?></div>
      </article>
    <?php endforeach; ?>
  </div>
</div>
