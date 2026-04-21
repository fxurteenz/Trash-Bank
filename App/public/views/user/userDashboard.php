<style>
  :root {
    --bank-green: #0e8b53;
    --bank-green-dark: #0a6f43;
    --mint: #d8ffe8;
    --text-strong: #173227;
    --text-soft: #5b7368;
    --card-shadow: 0 10px 24px rgba(0, 0, 0, 0.16);
    --radius-lg: 20px;
    --radius-md: 14px;
  }

  .ub-page { display: grid; gap: 12px; }

  .ub-hero {
    background: linear-gradient(135deg, var(--bank-green) 0%, var(--bank-green-dark) 100%);
    border-radius: var(--radius-lg);
    padding: 16px;
    color: #fff;
    box-shadow: var(--card-shadow);
    position: relative;
    overflow: hidden;
  }

  .ub-hero::after {
    content: "";
    position: absolute;
    width: 130px;
    height: 130px;
    border-radius: 999px;
    top: -40px;
    right: -34px;
    background: rgba(255, 255, 255, 0.12);
  }

  .ub-user { display: flex; align-items: center; gap: 10px; }
  .ub-avatar {
    width: 46px;
    height: 46px;
    border-radius: 999px;
    background: rgba(255,255,255,0.2);
    display: grid;
    place-items: center;
    font-size: 20px;
    font-weight: 900;
  }

  .ub-user h2 { font-size: 18px; font-weight: 800; }
  .ub-user p { font-size: 12px; opacity: 0.86; }

  .ub-points {
    margin-top: 12px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
  }

  .ub-point {
    border-radius: 16px;
    padding: 12px;
    color: #fff;
    box-shadow: inset 0 0 0 1px rgba(255,255,255,0.18);
  }

  .ub-point.waste { background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); }
  .ub-point.good { background: linear-gradient(135deg, #38bdf8 0%, #0ea5e9 100%); }

  .ub-point .label { font-size: 11px; opacity: 0.92; font-weight: 700; }
  .ub-point .value { font-size: 30px; line-height: 1; margin: 6px 0 2px; font-weight: 900; }
  .ub-point .unit { font-size: 12px; opacity: 0.92; }

  .ub-metrics {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
  }

  .ub-metric {
    background: #fff;
    border-radius: var(--radius-md);
    padding: 12px 8px;
    text-align: center;
    box-shadow: var(--card-shadow);
    border-top: 4px solid #34d399;
  }

  .ub-metric.weight { border-top-color: #22c55e; }
  .ub-metric.carbon { border-top-color: #06b6d4; }
  .ub-metric.credit { border-top-color: #f59e0b; }

  .ub-metric .icon { font-size: 20px; }
  .ub-metric .value { color: var(--text-strong); font-size: 20px; font-weight: 900; margin-top: 4px; }
  .ub-metric .label { color: var(--text-soft); font-size: 11px; margin-top: 2px; }

  .ub-card {
    background: #fff;
    border-radius: var(--radius-lg);
    padding: 14px;
    box-shadow: var(--card-shadow);
  }

  .ub-title { color: var(--text-strong); font-size: 15px; font-weight: 800; margin-bottom: 10px; }

  .ub-row { display: grid; grid-template-columns: 1fr auto; gap: 8px; align-items: center; margin: 8px 0; }
  .ub-row .name { color: #375347; font-size: 13px; font-weight: 700; }
  .ub-row .kg { color: #1f3f33; font-size: 13px; font-weight: 800; }

  .ub-bar-wrap { grid-column: 1 / 3; background: #edf5f0; border-radius: 999px; height: 8px; overflow: hidden; }
  .ub-bar { height: 100%; border-radius: 999px; }

  .ub-activity { display: flex; align-items: center; gap: 10px; padding: 10px 0; border-bottom: 1px solid #eef3f0; }
  .ub-activity:last-child { border-bottom: 0; }

  .ub-activity .badge {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    display: grid;
    place-items: center;
    font-size: 16px;
    background: #eaf9f0;
  }

  .ub-activity .detail { flex: 1; }
  .ub-activity .name { color: #234437; font-size: 13px; font-weight: 700; }
  .ub-activity .time { color: #82968d; font-size: 11px; }
  .ub-activity .point { font-size: 13px; font-weight: 900; color: #0f9d58; }

  @media (max-width: 390px) {
    .ub-point .value { font-size: 26px; }
    .ub-metric .value { font-size: 17px; }
  }
</style>

<div class="ub-page">
  <section class="ub-hero">
    <div class="ub-user">
      <div class="ub-avatar">ส</div>
      <div>
        <h2>สมชาย ใจดี</h2>
        <p>บัญชีสมาชิกธนาคารขยะ</p>
      </div>
    </div>

    <div class="ub-points">
      <article class="ub-point waste">
        <div class="label">แต้มขยะ</div>
        <div class="value">1,280</div>
        <div class="unit">แต้ม</div>
      </article>
      <article class="ub-point good">
        <div class="label">แต้มความดี</div>
        <div class="value">845</div>
        <div class="unit">แต้ม</div>
      </article>
    </div>
  </section>

  <section class="ub-metrics">
    <article class="ub-metric carbon">
      <div class="icon">🌿</div>
      <div class="value">84.6</div>
      <div class="label">เครดิตคาร์บอน (กก.)</div>
    </article>
    <article class="ub-metric weight">
      <div class="icon">⚖️</div>
      <div class="value">132.4</div>
      <div class="label">ปริมาณขยะที่ฝาก (กก.)</div>
    </article>
    <article class="ub-metric credit">
      <div class="icon">💰</div>
      <div class="value">2,430</div>
      <div class="label">เครดิตสะสม (บาท)</div>
    </article>
  </section>

  <section class="ub-card">
    <h3 class="ub-title">สัดส่วนขยะที่ฝาก</h3>

    <div class="ub-row">
      <div class="name">พลาสติก</div>
      <div class="kg">51.2 กก.</div>
      <div class="ub-bar-wrap"><div class="ub-bar" style="width:72%;background:#22c55e"></div></div>
    </div>

    <div class="ub-row">
      <div class="name">กระดาษ</div>
      <div class="kg">38.7 กก.</div>
      <div class="ub-bar-wrap"><div class="ub-bar" style="width:54%;background:#06b6d4"></div></div>
    </div>

    <div class="ub-row">
      <div class="name">โลหะ</div>
      <div class="kg">24.1 กก.</div>
      <div class="ub-bar-wrap"><div class="ub-bar" style="width:33%;background:#0ea5e9"></div></div>
    </div>

    <div class="ub-row">
      <div class="name">แก้ว</div>
      <div class="kg">18.4 กก.</div>
      <div class="ub-bar-wrap"><div class="ub-bar" style="width:26%;background:#10b981"></div></div>
    </div>
  </section>

  <section class="ub-card">
    <h3 class="ub-title">รายการล่าสุด</h3>

    <div class="ub-activity">
      <div class="badge">♻️</div>
      <div class="detail">
        <div class="name">ฝากขยะพลาสติก</div>
        <div class="time">วันนี้ 10:42</div>
      </div>
      <div class="point">+120</div>
    </div>

    <div class="ub-activity">
      <div class="badge">🎁</div>
      <div class="detail">
        <div class="name">แลกของรางวัล</div>
        <div class="time">เมื่อวาน 14:15</div>
      </div>
      <div class="point" style="color:#ef4444;">-80</div>
    </div>

    <div class="ub-activity">
      <div class="badge">🌱</div>
      <div class="detail">
        <div class="name">สะสมเครดิตคาร์บอน</div>
        <div class="time">เมื่อวาน 09:10</div>
      </div>
      <div class="point">+35</div>
    </div>
  </section>
</div>
