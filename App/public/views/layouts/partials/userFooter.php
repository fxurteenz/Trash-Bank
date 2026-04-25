<style>
  /* KBank-style bottom nav */
  .has-footer { padding-bottom: calc(64px + env(safe-area-inset-bottom, 0px)); }

  .kbank-footer {
    position: fixed;
    left: 0; right: 0; bottom: 0;
    background: #fff;
    border-top: 1px solid #F0F1F3;
    box-shadow: 0 -2px 12px rgba(0,0,0,0.08);
    padding: 6px 8px calc(6px + env(safe-area-inset-bottom, 0px));
    z-index: 1000;
  }
  .kfooter-inner {
    max-width: 460px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(5, 1fr);
  }
  .kfoot-item {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 3px; text-decoration: none;
    padding: 6px 4px;
    border-radius: 10px;
    transition: background 0.12s;
    color: #9CA3AF;
    min-height: 52px;
  }
  .kfoot-item.active { color: #1B8B4B; }
  .kfoot-icon {
    width: 28px; height: 28px;
    display: grid; place-items: center;
    font-size: 20px;
    position: relative;
  }
  .kfoot-icon.active-dot::after {
    content: '';
    position: absolute;
    bottom: -2px; left: 50%; transform: translateX(-50%);
    width: 4px; height: 4px; border-radius: 999px;
    background: #1B8B4B;
  }
  .kfoot-label {
    font-size: 10px; font-weight: 700;
    letter-spacing: 0.1px;
  }
</style>

<?php
  if (!function_exists('userFootActive')) {
    function userFootActive($key, $active){ return $key === ($active ?? '') ? 'active' : ''; }
  }
?>

<nav class="kbank-footer">
  <div class="kfooter-inner">
    <a class="kfoot-item <?= userFootActive('shop', $activeTab ?? '') ?>" href="/user/shop" aria-label="Shop">
      <div class="kfoot-icon <?= ($activeTab??'')==='shop' ? 'active-dot' : '' ?>">🛍️</div>
      <div class="kfoot-label">แลกรางวัล</div>
    </a>
    <a class="kfoot-item <?= userFootActive('equipment', $activeTab ?? '') ?>" href="/user/equipment" aria-label="Equipment">
      <div class="kfoot-icon <?= ($activeTab??'')==='equipment' ? 'active-dot' : '' ?>">🎒</div>
      <div class="kfoot-label">อุปกรณ์</div>
    </a>
    <a class="kfoot-item <?= userFootActive('dashboard', $activeTab ?? '') ?>" href="/user" aria-label="Home">
      <div class="kfoot-icon <?= ($activeTab??'')==='dashboard' ? 'active-dot' : '' ?>">🏦</div>
      <div class="kfoot-label">บัญชี</div>
    </a>
    <a class="kfoot-item <?= userFootActive('collection', $activeTab ?? '') ?>" href="/user/collection" aria-label="Collection">
      <div class="kfoot-icon <?= ($activeTab??'')==='collection' ? 'active-dot' : '' ?>">🏅</div>
      <div class="kfoot-label">รางวัล</div>
    </a>
    <a class="kfoot-item <?= userFootActive('quests', $activeTab ?? '') ?>" href="/user/quests" aria-label="Quests">
      <div class="kfoot-icon <?= ($activeTab??'')==='quests' ? 'active-dot' : '' ?>">✅</div>
      <div class="kfoot-label">ภารกิจ</div>
    </a>
  </div>
</nav>
