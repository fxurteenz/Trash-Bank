<style>
  /* ─── KBank-style Dashboard ─── */
  .kd-topbar {
    background: #fff;
    padding: 14px 16px 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    z-index: 100;
    border-bottom: 1px solid #F0F1F3;
  }
  .kd-topbar-left { display: flex; align-items: center; gap: 10px; }
  .kd-avatar {
    width: 38px; height: 38px;
    border-radius: 999px;
    background: linear-gradient(135deg, #1B8B4B, #0D6B38);
    color: #fff; font-size: 16px; font-weight: 800;
    display: grid; place-items: center;
    flex-shrink: 0;
  }
  .kd-greet-sub { font-size: 11px; color: #9CA3AF; }
  .kd-greet-name { font-size: 14px; font-weight: 800; color: #1A1A2E; margin-top: 1px; }
  .kd-topbar-right { display: flex; align-items: center; gap: 10px; }
  .kd-icon-btn {
    width: 36px; height: 36px; border-radius: 999px;
    background: #F4F5F7;
    display: grid; place-items: center; font-size: 17px;
    text-decoration: none; border: none; cursor: pointer;
  }

  .kd-body { display: grid; gap: 0; }

  /* Account Card */
  .kd-account-card {
    background: linear-gradient(140deg, #1B8B4B 0%, #0D6B38 60%, #0A5A2F 100%);
    margin: 14px 14px 0;
    border-radius: 18px;
    padding: 20px 20px 16px;
    color: #fff;
    position: relative;
    overflow: hidden;
    box-shadow: 0 6px 24px rgba(27,139,75,0.35);
  }
  .kd-account-card::before {
    content: '';
    position: absolute;
    width: 180px; height: 180px;
    border-radius: 999px;
    top: -60px; right: -50px;
    background: rgba(255,255,255,0.08);
  }
  .kd-account-card::after {
    content: '';
    position: absolute;
    width: 100px; height: 100px;
    border-radius: 999px;
    bottom: -30px; left: 30px;
    background: rgba(255,255,255,0.06);
  }
  .kd-acc-label { font-size: 11px; opacity: 0.8; letter-spacing: 0.5px; text-transform: uppercase; }
  .kd-acc-balance { font-size: 38px; font-weight: 900; line-height: 1.1; margin: 4px 0 2px; letter-spacing: -1px; }
  .kd-acc-unit { font-size: 14px; opacity: 0.85; font-weight: 600; }
  .kd-acc-row { display: flex; align-items: center; justify-content: space-between; margin-top: 16px; padding-top: 14px; border-top: 1px solid rgba(255,255,255,0.18); }
  .kd-acc-sub { text-align: center; }
  .kd-acc-sub .sub-val { font-size: 17px; font-weight: 800; }
  .kd-acc-sub .sub-lbl { font-size: 10px; opacity: 0.75; margin-top: 2px; }
  .kd-acc-divider { width: 1px; height: 34px; background: rgba(255,255,255,0.22); }
  .kd-acc-badge { background: rgba(255,255,255,0.15); border-radius: 8px; padding: 3px 8px; font-size: 11px; font-weight: 700; }

  /* Quick Actions */
  .kd-actions {
    background: #fff;
    margin: 14px 14px 0;
    border-radius: 16px;
    padding: 16px;
    box-shadow: 0 1px 6px rgba(0,0,0,0.07);
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 4px;
  }
  .kd-action {
    display: flex; flex-direction: column; align-items: center; gap: 7px;
    text-decoration: none;
    padding: 6px 4px;
    border-radius: 12px;
    transition: background 0.15s;
  }
  .kd-action:active { background: #F4F5F7; }
  .kd-action-icon {
    width: 46px; height: 46px;
    border-radius: 14px;
    display: grid; place-items: center;
    font-size: 21px;
  }
  .kd-action-icon.green { background: #E8F5EE; }
  .kd-action-icon.blue  { background: #E0F2FE; }
  .kd-action-icon.amber { background: #FEF3C7; }
  .kd-action-icon.purple{ background: #F3E8FF; }
  .kd-action-label { font-size: 11px; font-weight: 700; color: #374151; text-align: center; }

  /* Section headers */
  .kd-section {
    background: #fff;
    margin: 14px 14px 0;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 1px 6px rgba(0,0,0,0.07);
  }
  .kd-section-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 16px 10px;
  }
  .kd-section-title { font-size: 14px; font-weight: 800; color: #1A1A2E; }
  .kd-section-more { font-size: 12px; color: #1B8B4B; font-weight: 700; text-decoration: none; }

  /* Stats row */
  .kd-stats {
    display: grid; grid-template-columns: repeat(3,1fr);
    border-top: 1px solid #F0F1F3;
  }
  .kd-stat {
    padding: 12px 8px; text-align: center;
    border-right: 1px solid #F0F1F3;
  }
  .kd-stat:last-child { border-right: 0; }
  .kd-stat-icon { font-size: 18px; }
  .kd-stat-val { font-size: 17px; font-weight: 900; color: #1A1A2E; margin-top: 3px; }
  .kd-stat-lbl { font-size: 10px; color: #9CA3AF; margin-top: 2px; }

  /* Waste bars */
  .kd-bar-list { padding: 0 16px 14px; }
  .kd-bar-row { display: flex; align-items: center; gap: 10px; padding: 7px 0; }
  .kd-bar-dot { width: 8px; height: 8px; border-radius: 999px; flex-shrink: 0; }
  .kd-bar-name { font-size: 13px; color: #374151; font-weight: 600; flex: 1; }
  .kd-bar-track { flex: 2; background: #F0F1F3; border-radius: 999px; height: 6px; overflow: hidden; }
  .kd-bar-fill { height: 100%; border-radius: 999px; }
  .kd-bar-kg { font-size: 12px; color: #6B7280; font-weight: 700; min-width: 52px; text-align: right; }

  /* Transaction list */
  .kd-txn {
    display: flex; align-items: center; gap: 12px;
    padding: 13px 16px;
    border-bottom: 1px solid #F0F1F3;
  }
  .kd-txn:last-child { border-bottom: 0; }
  .kd-txn-icon {
    width: 40px; height: 40px; border-radius: 12px;
    display: grid; place-items: center; font-size: 18px;
    flex-shrink: 0;
  }
  .kd-txn-icon.recycle { background: #E8F5EE; }
  .kd-txn-icon.redeem  { background: #FEF3C7; }
  .kd-txn-icon.carbon  { background: #E0F2FE; }
  .kd-txn-detail { flex: 1; min-width: 0; }
  .kd-txn-name { font-size: 13px; font-weight: 700; color: #1A1A2E; }
  .kd-txn-time { font-size: 11px; color: #9CA3AF; margin-top: 2px; }
  .kd-txn-amount { font-size: 14px; font-weight: 800; }
  .kd-txn-amount.plus  { color: #1B8B4B; }
  .kd-txn-amount.minus { color: #EF4444; }
</style>

<!-- Top Bar -->
<div class="kd-body">
  <div class="kd-topbar">
    <div class="kd-topbar-left">
      <div class="kd-avatar">ส</div>
      <div>
        <div class="kd-greet-sub">สวัสดี,</div>
        <div class="kd-greet-name">สมชาย ใจดี</div>
      </div>
    </div>
    <div class="kd-topbar-right">
      <button class="kd-icon-btn" aria-label="แจ้งเตือน">🔔</button>
    </div>
  </div>

  <!-- Account Card -->
  <div class="kd-account-card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
      <div class="kd-acc-label">แต้มขยะสะสม</div>
      <span class="kd-acc-badge">♻️ สมาชิก</span>
    </div>
    <div class="kd-acc-balance">1,280</div>
    <div class="kd-acc-unit">แต้ม</div>
    <div class="kd-acc-row">
      <div class="kd-acc-sub">
        <div class="sub-val">845</div>
        <div class="sub-lbl">แต้มความดี</div>
      </div>
      <div class="kd-acc-divider"></div>
      <div class="kd-acc-sub">
        <div class="sub-val">2,430</div>
        <div class="sub-lbl">เครดิตสะสม (บ.)</div>
      </div>
      <div class="kd-acc-divider"></div>
      <div class="kd-acc-sub">
        <div class="sub-val">132.4</div>
        <div class="sub-lbl">ขยะรวม (กก.)</div>
      </div>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="kd-actions">
    <a class="kd-action" href="/user/shop">
      <div class="kd-action-icon green">🛍️</div>
      <div class="kd-action-label">แลกรางวัล</div>
    </a>
    <a class="kd-action" href="#">
      <div class="kd-action-icon blue">♻️</div>
      <div class="kd-action-label">ฝากขยะ</div>
    </a>
    <a class="kd-action" href="/user/quests">
      <div class="kd-action-icon amber">✅</div>
      <div class="kd-action-label">ภารกิจ</div>
    </a>
    <a class="kd-action" href="/user/collection">
      <div class="kd-action-icon purple">🏅</div>
      <div class="kd-action-label">รางวัล</div>
    </a>
  </div>

  <!-- Stats -->
  <div class="kd-section">
    <div class="kd-section-head">
      <div class="kd-section-title">สถิติของฉัน</div>
    </div>
    <div class="kd-stats">
      <div class="kd-stat">
        <div class="kd-stat-icon">🌿</div>
        <div class="kd-stat-val">84.6</div>
        <div class="kd-stat-lbl">คาร์บอน (กก.)</div>
      </div>
      <div class="kd-stat">
        <div class="kd-stat-icon">⚖️</div>
        <div class="kd-stat-val">132.4</div>
        <div class="kd-stat-lbl">ขยะฝาก (กก.)</div>
      </div>
      <div class="kd-stat">
        <div class="kd-stat-icon">🎯</div>
        <div class="kd-stat-val">Lv.5</div>
        <div class="kd-stat-lbl">ระดับ</div>
      </div>
    </div>
  </div>

  <!-- Waste Breakdown -->
  <div class="kd-section">
    <div class="kd-section-head">
      <div class="kd-section-title">สัดส่วนขยะที่ฝาก</div>
      <a href="#" class="kd-section-more">ดูทั้งหมด ›</a>
    </div>
    <div class="kd-bar-list">
      <div class="kd-bar-row">
        <div class="kd-bar-dot" style="background:#1B8B4B"></div>
        <div class="kd-bar-name">พลาสติก</div>
        <div class="kd-bar-track"><div class="kd-bar-fill" style="width:72%;background:#1B8B4B"></div></div>
        <div class="kd-bar-kg">51.2 กก.</div>
      </div>
      <div class="kd-bar-row">
        <div class="kd-bar-dot" style="background:#0EA5E9"></div>
        <div class="kd-bar-name">กระดาษ</div>
        <div class="kd-bar-track"><div class="kd-bar-fill" style="width:54%;background:#0EA5E9"></div></div>
        <div class="kd-bar-kg">38.7 กก.</div>
      </div>
      <div class="kd-bar-row">
        <div class="kd-bar-dot" style="background:#F59E0B"></div>
        <div class="kd-bar-name">โลหะ</div>
        <div class="kd-bar-track"><div class="kd-bar-fill" style="width:33%;background:#F59E0B"></div></div>
        <div class="kd-bar-kg">24.1 กก.</div>
      </div>
      <div class="kd-bar-row">
        <div class="kd-bar-dot" style="background:#8B5CF6"></div>
        <div class="kd-bar-name">แก้ว</div>
        <div class="kd-bar-track"><div class="kd-bar-fill" style="width:26%;background:#8B5CF6"></div></div>
        <div class="kd-bar-kg">18.4 กก.</div>
      </div>
    </div>
  </div>

  <!-- Recent Transactions -->
  <div class="kd-section" style="margin-bottom:0">
    <div class="kd-section-head">
      <div class="kd-section-title">รายการล่าสุด</div>
      <a href="#" class="kd-section-more">ดูทั้งหมด ›</a>
    </div>
    <div class="kd-txn">
      <div class="kd-txn-icon recycle">♻️</div>
      <div class="kd-txn-detail">
        <div class="kd-txn-name">ฝากขยะพลาสติก</div>
        <div class="kd-txn-time">วันนี้ 10:42 น.</div>
      </div>
      <div class="kd-txn-amount plus">+120 แต้ม</div>
    </div>
    <div class="kd-txn">
      <div class="kd-txn-icon redeem">🎁</div>
      <div class="kd-txn-detail">
        <div class="kd-txn-name">แลกของรางวัล</div>
        <div class="kd-txn-time">เมื่อวาน 14:15 น.</div>
      </div>
      <div class="kd-txn-amount minus">-80 แต้ม</div>
    </div>
    <div class="kd-txn">
      <div class="kd-txn-icon carbon">🌱</div>
      <div class="kd-txn-detail">
        <div class="kd-txn-name">สะสมเครดิตคาร์บอน</div>
        <div class="kd-txn-time">เมื่อวาน 09:10 น.</div>
      </div>
      <div class="kd-txn-amount plus">+35 แต้ม</div>
    </div>
  </div>

</div>
