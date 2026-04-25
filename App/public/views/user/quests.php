<style>
  /* KBank-style Quests */
  .kq-topbar {
    background: #fff;
    padding: 14px 16px 12px;
    display: flex; align-items: center; gap: 12px;
    position: sticky; top: 0; z-index: 100;
    border-bottom: 1px solid #F0F1F3;
  }
  .kq-topbar-title { font-size: 17px; font-weight: 800; color: #1A1A2E; flex: 1; }
  .kq-topbar-tag {
    background: #FEF3C7; color: #D97706;
    border-radius: 999px; padding: 4px 12px;
    font-size: 12px; font-weight: 800;
  }

  .kq-body { display: grid; gap: 0; }

  .kq-progress-card {
    margin: 14px 14px 0;
    background: linear-gradient(135deg, #1B8B4B, #0D6B38);
    border-radius: 16px; padding: 16px 18px;
    color: #fff;
    box-shadow: 0 4px 16px rgba(27,139,75,0.28);
  }
  .kq-prog-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
  .kq-prog-label { font-size: 13px; font-weight: 700; opacity: 0.9; }
  .kq-prog-count { font-size: 13px; font-weight: 800; }
  .kq-prog-track { background: rgba(255,255,255,0.25); border-radius: 999px; height: 8px; overflow: hidden; }
  .kq-prog-fill { height: 100%; background: #fff; border-radius: 999px; width: 40%; }
  .kq-prog-hint { font-size: 11px; opacity: 0.75; margin-top: 8px; }

  .kq-section {
    background: #fff;
    margin: 14px 14px 0;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 1px 6px rgba(0,0,0,0.07);
  }
  .kq-section-head {
    padding: 14px 16px 10px;
    border-bottom: 1px solid #F0F1F3;
    display: flex; align-items: center; justify-content: space-between;
  }
  .kq-section-title { font-size: 14px; font-weight: 800; color: #1A1A2E; }
  .kq-reset { font-size: 11px; color: #9CA3AF; }

  .kq-item {
    display: flex; align-items: center; gap: 14px;
    padding: 14px 16px;
    border-bottom: 1px solid #F0F1F3;
  }
  .kq-item:last-child { border-bottom: 0; }
  .kq-item-icon {
    width: 44px; height: 44px; border-radius: 12px;
    display: grid; place-items: center; font-size: 21px;
    flex-shrink: 0;
  }
  .kq-item-icon.green  { background: #E8F5EE; }
  .kq-item-icon.blue   { background: #E0F2FE; }
  .kq-item-icon.amber  { background: #FEF3C7; }
  .kq-item-icon.purple { background: #F3E8FF; }
  .kq-item-icon.rose   { background: #FEE2E2; }
  .kq-item-detail { flex: 1; min-width: 0; }
  .kq-item-name { font-size: 13px; font-weight: 700; color: #1A1A2E; }
  .kq-item-reward { font-size: 11px; color: #1B8B4B; font-weight: 700; margin-top: 3px; }
  .kq-accept-btn {
    background: linear-gradient(135deg, #1B8B4B, #0D6B38);
    color: #fff; border: 0; border-radius: 10px;
    padding: 7px 14px; font-size: 12px; font-weight: 800;
    cursor: pointer; flex-shrink: 0;
  }
</style>

<div class="kq-body">
  <!-- Top Bar -->
  <div class="kq-topbar">
    <a href="/user" style="font-size:22px;text-decoration:none;color:#1A1A2E;line-height:1;">‹</a>
    <div class="kq-topbar-title">ภารกิจประจำวัน</div>
    <div class="kq-topbar-tag">⏰ รีเซตใหม่ทุกวัน</div>
  </div>

  <!-- Progress Card -->
  <div class="kq-progress-card">
    <div class="kq-prog-row">
      <div class="kq-prog-label">ความคืบหน้าวันนี้</div>
      <div class="kq-prog-count">2/5 ภารกิจ</div>
    </div>
    <div class="kq-prog-track">
      <div class="kq-prog-fill"></div>
    </div>
    <div class="kq-prog-hint">ทำครบ 5 ภารกิจ รับโบนัส +100 แต้ม</div>
  </div>

  <!-- Quest List -->
  <div class="kq-section">
    <div class="kq-section-head">
      <div class="kq-section-title">ภารกิจที่รอทำ</div>
      <div class="kq-reset">รีเซต 00:00 น.</div>
    </div>
    <?php
      $quests = [
        ['icon'=>'♻️','cls'=>'green','name'=>'ฝากขยะรีไซเคิล 2 ครั้ง','reward'=>'+80 แต้มขยะ'],
        ['icon'=>'💚','cls'=>'blue','name'=>'ชวนเพื่อนร่วมกิจกรรม 1 คน','reward'=>'+60 แต้มความดี'],
        ['icon'=>'🌿','cls'=>'green','name'=>'ลดคาร์บอนสะสม 3 กก.','reward'=>'+30 เครดิตคาร์บอน'],
        ['icon'=>'🏷️','cls'=>'amber','name'=>'แยกประเภทขยะครบ 4 หมวด','reward'=>'+45 แต้มขยะ'],
        ['icon'=>'📸','cls'=>'purple','name'=>'ส่งหลักฐานกิจกรรมสีเขียว','reward'=>'+55 แต้มความดี'],
      ];
      foreach($quests as $q):
    ?>
    <div class="kq-item">
      <div class="kq-item-icon <?= $q['cls'] ?>"><?= $q['icon'] ?></div>
      <div class="kq-item-detail">
        <div class="kq-item-name"><?= $q['name'] ?></div>
        <div class="kq-item-reward">รางวัล: <?= $q['reward'] ?></div>
      </div>
      <button class="kq-accept-btn">รับ</button>
    </div>
    <?php endforeach; ?>
  </div>
</div>
