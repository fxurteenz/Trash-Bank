<style>
  /* KBank-style Equipment */
  .ke-topbar {
    background: #fff;
    padding: 14px 16px 12px;
    display: flex; align-items: center; gap: 12px;
    position: sticky; top: 0; z-index: 100;
    border-bottom: 1px solid #F0F1F3;
  }
  .ke-topbar-title { font-size: 17px; font-weight: 800; color: #1A1A2E; flex: 1; }

  .ke-body { display: grid; gap: 0; }

  .ke-info {
    margin: 14px 14px 0;
    background: #fff;
    border-radius: 16px;
    padding: 16px;
    box-shadow: 0 1px 6px rgba(0,0,0,0.07);
    display: flex; align-items: center; gap: 14px;
  }
  .ke-info-icon { font-size: 32px; width: 52px; height: 52px; background: #E8F5EE; border-radius: 14px; display: grid; place-items: center; flex-shrink: 0; }
  .ke-info-title { font-size: 14px; font-weight: 800; color: #1A1A2E; }
  .ke-info-desc { font-size: 12px; color: #9CA3AF; margin-top: 3px; }

  .ke-section {
    background: #fff;
    margin: 14px 14px 0;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 1px 6px rgba(0,0,0,0.07);
  }
  .ke-section-head {
    padding: 14px 16px 10px;
    border-bottom: 1px solid #F0F1F3;
  }
  .ke-section-title { font-size: 14px; font-weight: 800; color: #1A1A2E; }

  .ke-item {
    display: flex; align-items: center; gap: 14px;
    padding: 14px 16px;
    border-bottom: 1px solid #F0F1F3;
  }
  .ke-item:last-child { border-bottom: 0; }
  .ke-item-icon {
    width: 44px; height: 44px; border-radius: 12px;
    background: #F4F5F7;
    display: grid; place-items: center; font-size: 22px;
    flex-shrink: 0;
  }
  .ke-item-detail { flex: 1; }
  .ke-item-name { font-size: 14px; font-weight: 700; color: #1A1A2E; }
  .ke-item-desc { font-size: 12px; color: #9CA3AF; margin-top: 2px; }
  .ke-lv-badge {
    background: #E8F5EE; color: #1B8B4B;
    border-radius: 8px; padding: 3px 10px;
    font-size: 12px; font-weight: 800;
    flex-shrink: 0;
  }
</style>

<div class="ke-body">
  <!-- Top Bar -->
  <div class="ke-topbar">
    <a href="/user" style="font-size:22px;text-decoration:none;color:#1A1A2E;line-height:1;">‹</a>
    <div class="ke-topbar-title">อุปกรณ์สมาชิก</div>
  </div>

  <!-- Info Banner -->
  <div class="ke-info">
    <div class="ke-info-icon">🎒</div>
    <div>
      <div class="ke-info-title">อุปกรณ์ของฉัน</div>
      <div class="ke-info-desc">อุปกรณ์ช่วยแยกขยะและเพิ่มประสิทธิภาพการฝาก</div>
    </div>
  </div>

  <!-- Items List -->
  <div class="ke-section">
    <div class="ke-section-head">
      <div class="ke-section-title">อุปกรณ์ที่มี</div>
    </div>
    <?php
      $items = [
        ['icon'=>'🧤','name'=>'ถุงมือแยกขยะ','desc'=>'ป้องกันมือขณะแยกขยะ','lv'=>'Lv.2'],
        ['icon'=>'🧺','name'=>'ตะกร้ารีไซเคิล','desc'=>'แยกประเภทขยะได้ 3 ช่อง','lv'=>'Lv.3'],
        ['icon'=>'⚖️','name'=>'เครื่องชั่งพกพา','desc'=>'ชั่งน้ำหนักขยะก่อนนำส่ง','lv'=>'Lv.1'],
        ['icon'=>'🏷️','name'=>'ชุดป้ายแยกประเภท','desc'=>'ป้ายแบ่งหมวดขยะครบชุด','lv'=>'Lv.2'],
        ['icon'=>'🧴','name'=>'สเปรย์ทำความสะอาด','desc'=>'ทำความสะอาดก่อนรีไซเคิล','lv'=>'Lv.1'],
        ['icon'=>'📦','name'=>'กล่องเก็บวัสดุ','desc'=>'เก็บวัสดุรีไซเคิลได้ 20 กก.','lv'=>'Lv.4'],
      ];
      foreach($items as $item):
    ?>
    <div class="ke-item">
      <div class="ke-item-icon"><?= $item['icon'] ?></div>
      <div class="ke-item-detail">
        <div class="ke-item-name"><?= $item['name'] ?></div>
        <div class="ke-item-desc"><?= $item['desc'] ?></div>
      </div>
      <div class="ke-lv-badge"><?= $item['lv'] ?></div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
