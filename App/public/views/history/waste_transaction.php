<div x-data="DepositHistory()" x-init="init()" class="space-y-6">
  <div class="mb-8">
    <h1 class="text-4xl font-bold text-slate-900 mb-2">🗂️ ประวัติการฝากขยะ</h1>
    <p class="text-slate-600 text-lg">ดึงจากตารางหลัก คลิกเพื่อดูรายละเอียด</p>
  </div>

  <div class="bg-white rounded-xl shadow-md p-6 card-hover">
    <h2 class="text-xl font-bold text-slate-900 mb-5">🔍 ตัวกรองข้อมูล</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
      <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">วันที่เริ่มต้น</label>
        <input x-model="start" @change="apply()" type="date" class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500">
      </div>
      <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">วันที่สิ้นสุด</label>
        <input x-model="end" @change="apply()" type="date" class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500">
      </div>
      <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">ค้นหาผู้ฝาก</label>
        <input x-model="memberSearch" @keydown.enter="apply()" type="text" placeholder="ชื่อ/รหัส/เบอร์/อีเมล" class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500">
      </div>
      <div class="flex gap-2">
        <button @click="apply()" class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold">ค้นหา</button>
        <button @click="clear()" class="flex-1 px-4 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg font-semibold">ล้าง</button>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500"><p class="text-slate-600 text-sm mb-2">📦 รวมรายการ</p><p class="text-4xl font-bold text-blue-600" x-text="rows.length"></p></div>
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500"><p class="text-slate-600 text-sm mb-2">⚖️ รวมน้ำหนัก</p><p class="text-3xl font-bold text-purple-600"><span x-text="rows.reduce((s,x)=>s+Number(x.waste_transaction_total_weight||0),0).toFixed(2)"></span> <span class="text-lg">กก.</span></p></div>
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-emerald-500"><p class="text-slate-600 text-sm mb-2">⭐ รวมคะแนน</p><p class="text-3xl font-bold text-emerald-600" x-text="rows.reduce((s,x)=>s+Number(x.waste_transaction_total_point||0),0).toFixed(0)"></p></div>
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-orange-500"><p class="text-slate-600 text-sm mb-2">📊 เฉลี่ย/ครั้ง (กก.)</p><p class="text-3xl font-bold text-orange-600" x-text="(rows.length>0?(rows.reduce((s,x)=>s+Number(x.waste_transaction_total_weight||0),0)/rows.length).toFixed(2):0)"></p></div>
  </div>

  <div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="p-6 border-b border-slate-200">
      <h2 class="text-xl font-bold text-slate-900">📋 รายการฝาก (ตารางหลัก)</h2>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-slate-100 border-b-2 border-slate-300">
          <tr class="text-left text-sm font-bold text-slate-700">
            <th class="px-6 py-4 w-12">#</th>
            <th class="px-6 py-4">วันที่</th>
            <th class="px-6 py-4">ผู้ฝาก</th>
            <th class="px-6 py-4">เจ้าหน้าที่</th>
            <th class="px-6 py-4">คณะ</th>
            <th class="px-6 py-4 text-right">รวมน้ำหนัก</th>
            <th class="px-6 py-4 text-right">รวมคะแนน</th>
            <th class="px-6 py-4 text-center">ดำเนินการ</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          <template x-for="(r,i) in rows" :key="r.waste_transaction_id">
            <tr class="hover:bg-slate-50 transition text-sm text-slate-700" @click="openDetail(r.waste_transaction_id)" style="cursor:pointer;">
              <td class="px-6 py-4" x-text="i+1"></td>
              <td class="px-6 py-4" x-text="new Date(r.created_at).toLocaleDateString('th-TH')"></td>
              <td class="px-6 py-4" x-text="r.member_name||'-'"></td>
              <td class="px-6 py-4" x-text="r.staff_name||'-'"></td>
              <td class="px-6 py-4" x-text="r.faculty_name||'-'"></td>
              <td class="px-6 py-4 text-right" x-text="Number(r.waste_transaction_total_weight||0).toFixed(2)"></td>
              <td class="px-6 py-4 text-right" x-text="Number(r.waste_transaction_total_point||0).toFixed(0)"></td>
              <td class="px-6 py-4 text-center">
                <button @click.stop="openDetail(r.waste_transaction_id)" class="text-blue-600 hover:text-blue-700 font-semibold mr-3">👁️ ดู</button>
                <button @click.stop="confirmDelete()" class="text-red-600 hover:text-red-700 font-semibold">🗑️ ลบ</button>
              </td>
            </tr>
          </template>
          <template x-if="rows.length===0"><tr><td colspan="8" class="px-6 py-8 text-center text-slate-500">ไม่มีข้อมูล</td></tr></template>
        </tbody>
      </table>
    </div>
  </div>

  <div x-show="showModal" class="fixed inset-0 bg-black/40 flex items-center justify-center p-4" style="display:none;">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl">
      <div class="flex items-center justify-between p-4 border-b"><h3 class="text-lg font-bold">รายละเอียดการฝาก</h3><button class="text-slate-500" @click="showModal=false">✖</button></div>
      <div class="p-4 space-y-3" x-show="detail">
        <div class="text-sm text-slate-600">วันที่: <span x-text="new Date(detail.transaction.created_at).toLocaleDateString('th-TH')"></span></div>
        <div class="text-sm text-slate-600">ผู้ฝาก: <span x-text="detail.transaction.member_name"></span></div>
        <div class="text-sm text-slate-600">รวม: <span x-text="Number(detail.transaction.waste_transaction_total_weight||0).toFixed(2)+' กก. / '+Number(detail.transaction.waste_transaction_total_point||0).toFixed(0)+' คะแนน'"></span></div>
        <div class="overflow-x-auto">
          <table class="w-full text-sm"><thead class="bg-slate-100"><tr><th class="px-3 py-2 text-left">หมวด/ชนิด</th><th class="px-3 py-2 text-right">น้ำหนัก</th><th class="px-3 py-2 text-right">คะแนน</th></tr></thead><tbody>
            <template x-for="it in detail.detail" :key="it.waste_transaction_detail_id">
              <tr class="border-b"><td class="px-3 py-2" x-text="`${it.waste_category_name} - ${it.waste_type_name}`"></td><td class="px-3 py-2 text-right" x-text="Number(it.waste_transaction_detail_weight||0).toFixed(2)"></td><td class="px-3 py-2 text-right" x-text="Number(it.waste_transaction_detail_point||0).toFixed(0)"></td></tr>
            </template>
          </tbody></table>
        </div>
      </div>
      <div class="p-4 border-t text-right"><button class="px-4 py-2 rounded bg-slate-200 hover:bg-slate-300" @click="showModal=false">ปิด</button></div>
    </div>
  </div>
</div>

<script>
function DepositHistory(){
  return {
    rows:[], start:'', end:'', memberSearch:'', showModal:false, detail:null,
    async init(){ const t=new Date(); const m=new Date(t.getTime()-30*24*60*60*1000); this.start=m.toISOString().split('T')[0]; this.end=t.toISOString().split('T')[0]; await this.apply(); },
    async apply(){ const p=[]; p.push('scope=header'); if(this.start)p.push(`start_date=${this.start}`); if(this.end)p.push(`end_date=${this.end}`); if(this.memberSearch)p.push(`member_search=${encodeURIComponent(this.memberSearch)}`); const r=await fetch('/api/waste_transactions?'+p.join('&')); const j=await r.json(); this.rows=j.result?.data||[]; },
    clear(){ this.start=''; this.end=''; this.memberSearch=''; this.apply(); },
    async openDetail(id){ const r=await fetch('/api/waste_transactions/'+id); const j=await r.json(); if(j.success){ this.detail=j.result; this.showModal=true; } },
    confirmDelete(){ if(confirm('ยืนยันลบรายการนี้หรือไม่? (ยังไม่เชื่อม API)')){} }
  }
}
</script>
