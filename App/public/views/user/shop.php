<style>
  .up-page { display:grid; gap:12px; }
  .up-head { background:linear-gradient(135deg,#0e8b53,#0a6f43); color:#fff; border-radius:18px; padding:14px; box-shadow:0 10px 24px rgba(0,0,0,.16); }
  .up-head h1 { font-size:22px; font-weight:900; }
  .up-head p { opacity:.9; font-size:12px; margin-top:2px; }
  .up-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:10px; }
  .up-card { background:#fff; border-radius:14px; padding:12px; box-shadow:0 8px 20px rgba(0,0,0,.14); }
  .up-icon { font-size:28px; }
  .up-title { margin-top:8px; color:#1b3b2d; font-size:14px; font-weight:800; }
  .up-desc { margin-top:4px; color:#6b7f76; font-size:12px; min-height:30px; }
  .up-btn { width:100%; margin-top:10px; border:0; border-radius:10px; padding:8px 10px; color:#fff; font-weight:800; font-size:12px; background:linear-gradient(135deg,#22c55e,#16a34a); }
</style>

<div class="up-page">
  <div class="up-head">
    <h1>ร้านแลกแต้ม</h1>
    <p>แลกเครดิตหรือสิทธิพิเศษด้วยแต้มขยะและแต้มความดี</p>
  </div>

  <div class="up-grid">
    <?php
      $items = [
        ['icon'=>'🧴','name'=>'น้ำยาล้างจานรักษ์โลก','desc'=>'ใช้แต้มขยะ 220 แต้ม','price'=>'แลก 220 แต้ม'],
        ['icon'=>'🛍️','name'=>'ถุงผ้าลดโลกร้อน','desc'=>'ใช้แต้มความดี 150 แต้ม','price'=>'แลก 150 แต้ม'],
        ['icon'=>'🌱','name'=>'กล้าไม้พื้นถิ่น','desc'=>'ช่วยเพิ่มพื้นที่สีเขียว','price'=>'แลก 180 แต้ม'],
        ['icon'=>'☕','name'=>'ส่วนลดร้านกาแฟ','desc'=>'คูปองส่วนลด 30 บาท','price'=>'แลก 120 แต้ม'],
        ['icon'=>'🎟️','name'=>'บัตรกิจกรรมสีเขียว','desc'=>'เข้างานเวิร์กชอปฟรี','price'=>'แลก 280 แต้ม'],
        ['icon'=>'🎁','name'=>'กล่องของขวัญรีไซเคิล','desc'=>'ของรางวัลประจำเดือน','price'=>'แลก 340 แต้ม'],
      ];
      foreach($items as $item):
    ?>
      <article class="up-card">
        <div class="up-icon"><?= $item['icon'] ?></div>
        <div class="up-title"><?= $item['name'] ?></div>
        <div class="up-desc"><?= $item['desc'] ?></div>
        <button class="up-btn"><?= $item['price'] ?></button>
      </article>
    <?php endforeach; ?>
  </div>
</div>
