<div>
  <h1 style="font-size:28px;font-weight:800;margin:10px 0">🛍️ ร้านค้า</h1>
  <p style="opacity:.9;margin-bottom:20px">ซื้อของ เพิ่มความสามารถ และรับข้อเสนอพิเศษได้ที่นี่</p>
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px">
    <?php for($i=1;$i<=8;$i++): ?>
    <div style="background:#fff;border-radius:16px;padding:16px;color:#333;box-shadow:0 10px 24px rgba(0,0,0,.2)">
      <div style="font-size:40px">💎</div>
      <div style="font-weight:800;margin:8px 0">แพ็คเพชร #<?= $i ?></div>
      <div style="opacity:.8;margin-bottom:12px">สุดคุ้ม เพิ่มพลังอย่างไว</div>
      <button style="background:linear-gradient(90deg,#26de81,#20bf6b);color:#fff;border:none;padding:10px 14px;border-radius:12px;font-weight:800;cursor:pointer">ซื้อ 79฿</button>
    </div>
    <?php endfor; ?>
  </div>
<?php $activeTab='shop'; ?>
