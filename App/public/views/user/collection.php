<style>
  /* KBank-style Collection */
  .kc-topbar {
    background: #fff;
    padding: 14px 16px 12px;
    display: flex; align-items: center; gap: 12px;
    position: sticky; top: 0; z-index: 100;
    border-bottom: 1px solid #F0F1F3;
  }
  .kc-topbar-title { font-size: 17px; font-weight: 800; color: #1A1A2E; flex: 1; }
  .kc-topbar-count {
    background: #E8F5EE; border-radius: 999px;
    padding: 4px 12px; font-size: 12px; font-weight: 800; color: #1B8B4B;
  }

  .kc-body { display: grid; gap: 0; }

  .kc-summary {
    margin: 14px 14px 0;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 1px 6px rgba(0,0,0,0.07);
    display: grid; grid-template-columns: repeat(3,1fr);
    overflow: hidden;
  }
  .kc-sum-item { padding: 14px 8px; text-align: center; border-right: 1px solid #F0F1F3; }
  .kc-sum-item:last-child { border-right: 0; }
  .kc-sum-icon { font-size: 20px; }
  .kc-sum-val { font-size: 18px; font-weight: 900; color: #1A1A2E; margin-top: 4px; }
  .kc-sum-lbl { font-size: 10px; color: #9CA3AF; margin-top: 2px; }

  .kc-section {
    background: #fff;
    margin: 14px 14px 0;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 1px 6px rgba(0,0,0,0.07);
  }
  .kc-section-head {
    padding: 14px 16px 10px;
    border-bottom: 1px solid #F0F1F3;
  }
  .kc-section-title { font-size: 14px; font-weight: 800; color: #1A1A2E; }

  .kc-badge-row {
    display: flex; align-items: center; gap: 14px;
    padding: 14px 16px;
    border-bottom: 1px solid #F0F1F3;
  }
  .kc-badge-row:last-child { border-bottom: 0; }
  .kc-badge-icon {
    width: 48px; height: 48px; border-radius: 14px;
    display: grid; place-items: center; font-size: 24px;
    flex-shrink: 0;
  }
  .kc-badge-icon.gold   { background: #FEF9C3; }
  .kc-badge-icon.silver { background: #F1F5F9; }
  .kc-badge-icon.bronze { background: #FEF3C7; }
  .kc-badge-icon.green  { background: #E8F5EE; }
  .kc-badge-icon.purple { background: #F3E8FF; }
  .kc-badge-icon.blue   { background: #E0F2FE; }

  .kc-badge-detail { flex: 1; }
  .kc-badge-name { font-size: 14px; font-weight: 700; color: #1A1A2E; }
  .kc-badge-cond { font-size: 12px; color: #9CA3AF; margin-top: 2px; }
  .kc-badge-earned {
    background: #E8F5EE; color: #1B8B4B;
    border-radius: 8px; padding: 3px 10px;
    font-size: 11px; font-weight: 700;
    flex-shrink: 0;
  }
</style>

<div class="kc-body">
  <!-- Top Bar -->
  <div class="kc-topbar">
    <a href="/user" style="font-size:22px;text-decoration:none;color:#1A1A2E;line-height:1;">‹</a>
    <div class="kc-topbar-title">เหรียญและใบประกาศ</div>
    <div class="kc-topbar-count">🏅 6 รางวัล</div>
  </div>

  <!-- Summary -->
  <div class="kc-summary">
    <div class="kc-sum-item">
      <div class="kc-sum-icon">🏆</div>
      <div class="kc-sum-val">6</div>
      <div class="kc-sum-lbl">เหรียญทั้งหมด</div>
    </div>
    <div class="kc-sum-item">
      <div class="kc-sum-icon">🌟</div>
      <div class="kc-sum-val">3</div>
      <div class="kc-sum-lbl">ระดับทอง</div>
    </div>
    <div class="kc-sum-item">
      <div class="kc-sum-icon">📅</div>
      <div class="kc-sum-val">21 เม.ย.</div>
      <div class="kc-sum-lbl">ล่าสุด</div>
    </div>
  </div>

  <!-- Badge List -->
  <div class="kc-section">
    <div class="kc-section-head">
      <div class="kc-section-title">รางวัลที่ได้รับ</div>
    </div>
    <?php
      $badges = [
        ['icon'=>'🥉','cls'=>'bronze','name'=>'นักแยกขยะเริ่มต้น','cond'=>'ครบ 10 กก.'],
        ['icon'=>'🥈','cls'=>'silver','name'=>'นักฝากต่อเนื่อง','cond'=>'ฝาก 7 วันติด'],
        ['icon'=>'🥇','cls'=>'gold','name'=>'ฮีโร่รีไซเคิล','cond'=>'ครบ 100 กก.'],
        ['icon'=>'🏅','cls'=>'green','name'=>'พลเมืองสีเขียว','cond'=>'แต้มความดี 500'],
        ['icon'=>'🎖️','cls'=>'purple','name'=>'ช่วยโลกขั้นสูง','cond'=>'Carbon 50 กก.'],
        ['icon'=>'🏆','cls'=>'blue','name'=>'สมาชิกต้นแบบ','cond'=>'อันดับ Top 10'],
      ];
      foreach($badges as $b):
    ?>
    <div class="kc-badge-row">
      <div class="kc-badge-icon <?= $b['cls'] ?>"><?= $b['icon'] ?></div>
      <div class="kc-badge-detail">
        <div class="kc-badge-name"><?= $b['name'] ?></div>
        <div class="kc-badge-cond"><?= $b['cond'] ?></div>
      </div>
      <div class="kc-badge-earned">ได้รับแล้ว</div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
