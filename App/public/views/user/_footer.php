<style>
  .game-footer {
    position: fixed;
    left: 0; right: 0; bottom: 0;
    background: linear-gradient(180deg, #2c3e50 0%, #1f2a44 100%);
    box-shadow: 0 -8px 24px rgba(0,0,0,0.35);
    padding: 10px 12px 16px;
    z-index: 1000;
  }
  .footer-inner {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 10px;
  }
  .foot-item {
    background: linear-gradient(180deg, #445a7b, #2a3a57);
    border-radius: 16px;
    padding: 10px 8px;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    color: #e6f0ff; text-decoration: none; font-weight: 700;
    border: 2px solid rgba(255,255,255,0.08);
    box-shadow: 0 6px 0 #1b253a, 0 10px 18px rgba(0,0,0,0.25);
    transition: transform .12s ease, box-shadow .12s ease, background .2s ease;
    min-height: 66px;
  }
  .foot-item:hover { transform: translateY(-2px); }
  .foot-item:active { transform: translateY(2px); box-shadow: 0 2px 0 #1b253a; }
  .foot-item.active { background: linear-gradient(180deg, #5b7bd6, #3d55a8); border-color: #89a6ff; }
  .foot-icon { font-size: 22px; line-height: 1; }
  .foot-label { font-size: 12px; margin-top: 6px; letter-spacing: .2px; }
  @media (min-width: 1024px){ .foot-label{ font-size: 13px;} .foot-icon{ font-size: 24px;} }
  @media (max-width: 420px){ .foot-label{ font-size: 11px;} .foot-item{ min-height: 60px; } }
</style>

<?php
  // Helper to mark active
  function footActive($key, $active){ return $key === ($active ?? '') ? 'active' : ''; }
?>

<nav class="game-footer">
  <div class="footer-inner">
    <a class="foot-item <?= footActive('shop', $activeTab ?? '') ?>" href="/user/shop" aria-label="Shop">
      <div class="foot-icon">🏪</div>
      <div class="foot-label">ร้านค้า</div>
    </a>
    <a class="foot-item <?= footActive('equipment', $activeTab ?? '') ?>" href="/user/equipment" aria-label="Equipment">
      <div class="foot-icon">🛡️</div>
      <div class="foot-label">อุปกรณ์</div>
    </a>
    <a class="foot-item <?= footActive('dashboard', $activeTab ?? '') ?>" href="/user" aria-label="Home">
      <div class="foot-icon">🏆</div>
      <div class="foot-label">แดชบอร์ด</div>
    </a>
    <a class="foot-item <?= footActive('collection', $activeTab ?? '') ?>" href="/user/collection" aria-label="Collection">
      <div class="foot-icon">🥇</div>
      <div class="foot-label">ของสะสม</div>
    </a>
    <a class="foot-item <?= footActive('quests', $activeTab ?? '') ?>" href="/user/quests" aria-label="Quests">
      <div class="foot-icon">📜</div>
      <div class="foot-label">ภารกิจ</div>
    </a>
  </div>
</nav>
