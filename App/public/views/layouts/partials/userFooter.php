<style>
  /* Reserve space on pages that include this footer (safe area aware) */
  .has-footer { padding-bottom: calc(98px + env(safe-area-inset-bottom, 0px)); }

  .game-footer {
    position: fixed;
    left: 0; right: 0; bottom: 0;
    background: rgba(8, 63, 38, 0.88);
    backdrop-filter: blur(12px);
    box-shadow: 0 -8px 24px rgba(0,0,0,0.28);
    padding: 10px 12px calc(10px + env(safe-area-inset-bottom, 0px));
    z-index: 1000;
  }
  .footer-inner {
    max-width: 460px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 8px;
  }
  .foot-item {
    background: rgba(255,255,255,0.09);
    border-radius: 14px;
    padding: 8px 6px;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    color: #d9ffea; text-decoration: none; font-weight: 700;
    border: 1px solid rgba(255,255,255,0.1);
    transition: transform .14s ease, background .2s ease, border-color .2s ease;
    min-height: 60px;
  }
  .foot-item:hover { transform: translateY(-1px); }
  .foot-item.active {
    background: linear-gradient(135deg, #20c06f 0%, #0f9d58 100%);
    border-color: rgba(255,255,255,0.3);
    color: #ffffff;
  }
  .foot-icon { font-size: 20px; line-height: 1; }
  .foot-label { font-size: 11px; margin-top: 5px; letter-spacing: .2px; }
  @media (min-width: 1024px){ .foot-label{ font-size: 12px;} .foot-icon{ font-size: 21px;} }
</style>

<?php
  // Helper to mark active
  if (!function_exists('userFootActive')) {
    function userFootActive($key, $active){ return $key === ($active ?? '') ? 'active' : ''; }
  }
?>

<nav class="game-footer">
  <div class="footer-inner">
    <a class="foot-item <?= userFootActive('shop', $activeTab ?? '') ?>" href="/user/shop" aria-label="Shop">
      <div class="foot-icon">🛍️</div>
      <div class="foot-label">ชอป</div>
    </a>
    <a class="foot-item <?= userFootActive('equipment', $activeTab ?? '') ?>" href="/user/equipment" aria-label="Equipment">
      <div class="foot-icon">🎒</div>
      <div class="foot-label">กระเป๋า</div>
    </a>
    <a class="foot-item <?= userFootActive('dashboard', $activeTab ?? '') ?>" href="/user" aria-label="Home">
      <div class="foot-icon">🏦</div>
      <div class="foot-label">บัญชี</div>
    </a>
    <a class="foot-item <?= userFootActive('collection', $activeTab ?? '') ?>" href="/user/collection" aria-label="Collection">
      <div class="foot-icon">🎖️</div>
      <div class="foot-label">รางวัล</div>
    </a>
    <a class="foot-item <?= userFootActive('quests', $activeTab ?? '') ?>" href="/user/quests" aria-label="Quests">
      <div class="foot-icon">✅</div>
      <div class="foot-label">ภารกิจ</div>
    </a>
  </div>
  
</nav>
