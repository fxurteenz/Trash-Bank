<style>
  /* KBank-style Shop */
  .ks-topbar {
    background: #fff;
    padding: 14px 16px 12px;
    display: flex;
    align-items: center;
    gap: 12px;
    position: sticky; top: 0; z-index: 100;
    border-bottom: 1px solid #F0F1F3;
  }
  .ks-topbar-title { font-size: 17px; font-weight: 800; color: #1A1A2E; flex: 1; }
  .ks-topbar-pts {
    background: #E8F5EE; border-radius: 999px;
    padding: 4px 12px; font-size: 12px; font-weight: 800; color: #1B8B4B;
  }

  .ks-body { display: grid; gap: 0; }

  .ks-banner {
    margin: 14px 14px 0;
    background: linear-gradient(135deg, #1B8B4B 0%, #0D6B38 100%);
    border-radius: 16px; padding: 16px 18px;
    color: #fff;
    display: flex; align-items: center; justify-content: space-between;
    box-shadow: 0 4px 16px rgba(27,139,75,0.28);
  }
  .ks-banner-left .lbl { font-size: 11px; opacity: 0.8; }
  .ks-banner-left .val { font-size: 30px; font-weight: 900; line-height: 1.1; }
  .ks-banner-left .unit { font-size: 12px; opacity: 0.85; margin-top: 2px; }
  .ks-banner-icon { font-size: 44px; opacity: 0.8; }

  .ks-section {
    background: #fff;
    margin: 14px 14px 0;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 1px 6px rgba(0,0,0,0.07);
  }
  .ks-section-head {
    padding: 14px 16px 10px;
    display: flex; align-items: center; justify-content: space-between;
    border-bottom: 1px solid #F0F1F3;
  }
  .ks-section-title { font-size: 14px; font-weight: 800; color: #1A1A2E; }

  .ks-item {
    display: flex; align-items: center; gap: 14px;
    padding: 14px 16px;
    border-bottom: 1px solid #F0F1F3;
  }
  .ks-item:last-child { border-bottom: 0; }
  .ks-item-icon {
    width: 46px; height: 46px; border-radius: 14px;
    background: #E8F5EE;
    display: grid; place-items: center; font-size: 22px;
    flex-shrink: 0;
  }
  .ks-item-detail { flex: 1; min-width: 0; }
  .ks-item-name { font-size: 14px; font-weight: 700; color: #1A1A2E; }
  .ks-item-desc { font-size: 12px; color: #9CA3AF; margin-top: 2px; }
  .ks-item-right { text-align: right; flex-shrink: 0; }
  .ks-item-pts { font-size: 14px; font-weight: 800; color: #1B8B4B; }
  .ks-item-unit { font-size: 10px; color: #9CA3AF; margin-top: 1px; }
  .ks-redeem-btn {
    margin-top: 6px;
    background: linear-gradient(135deg, #1B8B4B, #0D6B38);
    color: #fff; border: 0; border-radius: 8px;
    padding: 5px 12px; font-size: 11px; font-weight: 800;
    cursor: pointer; white-space: nowrap;
  }
</style>

<div class="ks-body">
  <!-- Top Bar -->
  <div class="ks-topbar">
    <a href="/user" style="font-size:22px;text-decoration:none;color:#1A1A2E;line-height:1;">‹</a>
    <div class="ks-topbar-title">ร้านแลกแต้ม</div>
    <div class="ks-topbar-pts">🏆 1,280 แต้ม</div>
  </div>

  <!-- Balance Banner -->
  <div class="ks-banner">
    <div class="ks-banner-left">
      <div class="lbl">แต้มขยะคงเหลือ</div>
      <div class="val">1,280</div>
      <div class="unit">แต้ม</div>
    </div>
    <div class="ks-banner-icon">🛍️</div>
  </div>

  <!-- Items List -->
  <div class="ks-section">
    <div class="ks-section-head">
      <div class="ks-section-title">รายการของรางวัล</div>
    </div>
    <?php
      $items = [
        ['icon'=>'🧴','name'=>'น้ำยาล้างจานรักษ์โลก','desc'=>'ใช้แต้มขยะ','pts'=>220],
        ['icon'=>'🛍️','name'=>'ถุงผ้าลดโลกร้อน','desc'=>'ใช้แต้มความดี','pts'=>150],
        ['icon'=>'🌱','name'=>'กล้าไม้พื้นถิ่น','desc'=>'ช่วยเพิ่มพื้นที่สีเขียว','pts'=>180],
        ['icon'=>'☕','name'=>'ส่วนลดร้านกาแฟ','desc'=>'คูปองส่วนลด 30 บาท','pts'=>120],
        ['icon'=>'🎟️','name'=>'บัตรกิจกรรมสีเขียว','desc'=>'เข้างานเวิร์กชอปฟรี','pts'=>280],
        ['icon'=>'🎁','name'=>'กล่องของขวัญรีไซเคิล','desc'=>'ของรางวัลประจำเดือน','pts'=>340],
      ];
      foreach($items as $item):
    ?>
    <div class="ks-item">
      <div class="ks-item-icon"><?= $item['icon'] ?></div>
      <div class="ks-item-detail">
        <div class="ks-item-name"><?= $item['name'] ?></div>
        <div class="ks-item-desc"><?= $item['desc'] ?></div>
      </div>
      <div class="ks-item-right">
        <div class="ks-item-pts"><?= $item['pts'] ?></div>
        <div class="ks-item-unit">แต้ม</div>
        <button class="ks-redeem-btn">แลก</button>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
