<div x-data="WasteSaleHistoryHandler()" x-init="init()" class="space-y-6">
  <div class="mb-8">
    <h1 class="text-4xl font-bold text-slate-900 mb-2">📊 ประวัติการขายขยะ</h1>
    <p class="text-slate-600 text-lg">ดึงจากตารางหลัก คลิกเพื่อดูรายละเอียด</p>
  </div>

  <div class="bg-white rounded-xl shadow-md p-6 card-hover">
    <h2 class="text-xl font-bold text-slate-900 mb-5">🔍 ตัวกรองข้อมูล</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
      <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">วันที่เริ่มต้น</label>
        <input x-model="filterStartDate" @change="applyFilters()" type="date" class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
      </div>
      <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">วันที่สิ้นสุด</label>
        <input x-model="filterEndDate" @change="applyFilters()" type="date" class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
      </div>
      <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">ประเภทขยะ</label>
        <select x-model="filterType" @change="applyFilters()" class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition bg-white">
          <option value="">-- ทั้งหมด --</option>
          <template x-for="type in wasteTypes" :key="type.waste_type_id">
            <option :value="type.waste_type_id" x-text="type.waste_type_name"></option>
          </template>
        </select>
      </div>
      <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">ชื่อผู้ซื้อ</label>
        <input x-model="filterBuyer" @keydown.enter="applyFilters()" type="text" placeholder="ค้นหาผู้ซื้อ" class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
      </div>
      <div class="flex gap-2">
        <button @click="applyFilters()" class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors">🔍 ค้นหา</button>
        <button @click="clearFilters()" class="flex-1 px-4 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg font-semibold transition-colors">🔄 ล้าง</button>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 card-hover">
      <p class="text-slate-600 text-sm font-medium mb-2">📦 รวมรายการ</p>
      <p class="text-4xl font-bold text-blue-600" x-text="sales.length"></p>
    </div>
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500 card-hover">
      <p class="text-slate-600 text-sm font-medium mb-2">⚖️ รวมน้ำหนัก</p>
      <p class="text-3xl font-bold text-purple-600">
        <span x-text="sales.reduce((s,x)=>s+Number(x.waste_sale_total_weight||0),0).toFixed(2)"></span>
        <span class="text-lg ml-2">กก.</span>
      </p>
    </div>
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-emerald-500 card-hover">
      <p class="text-slate-600 text-sm font-medium mb-2">💰 รวมเงิน</p>
      <p class="text-3xl font-bold text-emerald-600">
        <span x-text="sales.reduce((s,x)=>s+Number(x.waste_sale_total_price||0),0).toFixed(2)"></span>
        <span class="text-lg ml-2">฿</span>
      </p>
    </div>
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-orange-500 card-hover">
      <p class="text-slate-600 text-sm font-medium mb-2">📊 เฉลี่ย/ครั้ง</p>
      <p class="text-3xl font-bold text-orange-600" x-text="(sales.length>0?(sales.reduce((s,x)=>s+Number(x.waste_sale_total_price||0),0)/sales.length).toFixed(2):0) + ' ฿'"></p>
    </div>
  </div>

  <div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="p-6 border-b border-slate-200">
      <h2 class="text-xl font-bold text-slate-900">📋 รายการขาย (ตารางหลัก)</h2>
      <p class="text-sm text-slate-600 mt-1">พบ <span x-text="sales.length"></span> รายการ</p>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-slate-100 border-b-2 border-slate-300">
          <tr class="text-left text-sm font-bold text-slate-700">
            <th class="px-6 py-4 w-12">#</th>
            <th class="px-6 py-4">วันที่</th>
            <th class="px-6 py-4">ผู้ซื้อ</th>
            <th class="px-6 py-4 text-right">รวมน้ำหนัก (กก.)</th>
            <th class="px-6 py-4 text-right">รวมเงิน (฿)</th>
            <th class="px-6 py-4 text-center">ดำเนินการ</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          <template x-for="(sale, index) in sales" :key="sale.waste_sale_id">
            <tr class="hover:bg-slate-50 transition text-sm text-slate-700" @click="openDetail(sale.waste_sale_id)" style="cursor: pointer;">
              <td class="px-6 py-4 font-medium text-slate-400" x-text="index + 1"></td>
              <td class="px-6 py-4" x-text="new Date(sale.waste_sale_date).toLocaleDateString('th-TH')"></td>
              <td class="px-6 py-4 font-medium" x-text="sale.waste_sale_buyer || 'ไม่ระบุ'"></td>
              <td class="px-6 py-4 text-right font-medium" x-text="Number(sale.waste_sale_total_weight||0).toFixed(2)"></td>
              <td class="px-6 py-4 text-right font-bold text-emerald-600" x-text="Number(sale.waste_sale_total_price||0).toFixed(2)"></td>
              <td class="px-6 py-4 text-center">
                <button @click.stop="openDetail(sale.waste_sale_id)" class="text-blue-600 hover:text-blue-700 font-semibold mr-3">👁️ ดู</button>
                <button @click.stop="confirmDelete(sale.waste_sale_id)" class="text-red-600 hover:text-red-700 font-semibold">🗑️ ลบ</button>
              </td>
            </tr>
          </template>
          <template x-if="sales.length === 0">
            <tr>
              <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                <p class="text-lg font-medium">📭 ไม่มีข้อมูล</p>
                <p class="text-sm">ไม่พบรายการที่ตรงกับเงื่อนไขการค้นหา</p>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
  </div>

  <div class="flex justify-center gap-4 pb-8">
    <button @click="printReport()" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors flex items-center gap-2"><span>🖨️</span> พิมพ์รายงาน</button>
    <button @click="exportCSV()" class="px-8 py-4 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition-colors flex items-center gap-2"><span>📥</span> ส่งออก CSV</button>
  </div>

  <div x-show="showModal" class="fixed inset-0 bg-black/40 flex items-center justify-center p-4" style="display:none;">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl">
      <div class="flex items-center justify-between p-4 border-b">
        <h3 class="text-lg font-bold">รายละเอียดการขาย</h3>
        <button class="text-slate-500" @click="showModal=false">✖</button>
      </div>
      <div class="p-4 space-y-3" x-show="detail">
        <div class="text-sm text-slate-600">วันที่: <span x-text="new Date(detail.created_at).toLocaleDateString('th-TH')"></span></div>
        <div class="text-sm text-slate-600">ผู้ซื้อ: <span x-text="detail.waste_sale_buyer || '-' "></span></div>
        <div class="text-sm text-slate-600">รวม: <span x-text="Number(detail.waste_sale_total_weight||0).toFixed(2)+' กก. / '+Number(detail.waste_sale_total_price||0).toFixed(2)+' ฿'"></span></div>
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead class="bg-slate-100">
              <tr>
                <th class="px-3 py-2 text-left">ประเภท</th>
                <th class="px-3 py-2 text-right">น้ำหนัก (กก.)</th>
                <th class="px-3 py-2 text-right">ราคา/กก.</th>
                <th class="px-3 py-2 text-right">รวม</th>
              </tr>
            </thead>
            <tbody>
              <template x-for="item in detail.details || []" :key="item.waste_sale_detail_id">
                <tr class="border-b">
                  <td class="px-3 py-2" x-text="item.waste_type_name"></td>
                  <td class="px-3 py-2 text-right" x-text="Number(item.waste_sale_detail_weight||0).toFixed(2)"></td>
                  <td class="px-3 py-2 text-right" x-text="Number(item.waste_sale_detail_price||0).toFixed(2)"></td>
                  <td class="px-3 py-2 text-right" x-text="Number(item.waste_sale_detail_price||0).toFixed(2)"></td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>
      </div>
      <div class="p-4 border-t text-right">
        <button class="px-4 py-2 rounded bg-slate-200 hover:bg-slate-300" @click="showModal=false">ปิด</button>
      </div>
    </div>
  </div>
</div>

<script>
function WasteSaleHistoryHandler(){
  return {
    sales: [], wasteTypes: [], filterStartDate: '', filterEndDate: '', filterType: '', filterBuyer: '', showModal:false, detail:null,
    async init(){
      const today=new Date(); const monthAgo=new Date(today.getTime()-30*24*60*60*1000);
      this.filterStartDate=monthAgo.toISOString().split('T')[0];
      this.filterEndDate=today.toISOString().split('T')[0];
      await this.loadWasteTypes();
      await this.applyFilters();
    },
    async loadWasteTypes(){
      try{ const r=await fetch('/api/waste_types'); const j=await r.json(); this.wasteTypes=j.data||j.result||[]; }catch(e){console.error(e)}
    },
    async applyFilters(){
      try{
        const p=[]; if(this.filterStartDate)p.push(`start_date=${this.filterStartDate}`); if(this.filterEndDate)p.push(`end_date=${this.filterEndDate}`); if(this.filterType)p.push(`type_id=${this.filterType}`); if(this.filterBuyer)p.push(`buyer=${encodeURIComponent(this.filterBuyer)}`);
        const r=await fetch('/api/waste_sales?'+p.join('&')); const j=await r.json(); this.sales=j.result?.data||j.data?.data||[];
      }catch(e){ console.error(e); this.sales=[]; }
    },
    clearFilters(){ this.filterStartDate=''; this.filterEndDate=''; this.filterType=''; this.filterBuyer=''; this.applyFilters(); },
    async openDetail(id){ try{ const r=await fetch(`/api/waste_sales/${id}`); const j=await r.json(); if(j.success){ this.detail=j.result||j.data; this.showModal=true; } }catch(e){console.error(e)} },
    confirmDelete(){ if(confirm('ยืนยันลบรายการนี้หรือไม่? (ยังไม่เชื่อม API)')){} },
    printReport(){ const w=window.open('','','height=600,width=800'); w.document.write(this.generateReportHTML()); w.document.close(); w.print(); },
    generateReportHTML(){ const s=new Date(this.filterStartDate).toLocaleDateString('th-TH'); const e=new Date(this.filterEndDate).toLocaleDateString('th-TH'); const rows=this.sales.map((x,i)=>`<tr><td style="border:1px solid #ddd;padding:8px;text-align:center;">${i+1}</td><td style="border:1px solid #ddd;padding:8px;">${new Date(x.waste_sale_date).toLocaleDateString('th-TH')}</td><td style="border:1px solid #ddd;padding:8px;">${x.waste_sale_buyer||'ไม่ระบุ'}</td><td style="border:1px solid #ddd;padding:8px;text-align:right;">${Number(x.waste_sale_total_weight||0).toFixed(2)}</td><td style="border:1px solid #ddd;padding:8px;text-align:right;font-weight:bold;">${Number(x.waste_sale_total_price||0).toFixed(2)}</td></tr>`).join(''); return `<html><head><meta charset="utf-8"><title>รายงานประวัติการขายขยะ</title><style>body{font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;padding:20px}table{width:100%;border-collapse:collapse}th{background:#d1fae5;padding:10px;text-align:left}</style></head><body><h2 style="text-align:center;color:#10b981">รายงานประวัติการขายขยะ</h2><p><strong>ช่วงวันที่:</strong> ${s} ถึง ${e}</p><table><thead><tr><th style="width:5%">ลำดับ</th><th>วันที่</th><th>ผู้ซื้อ</th><th style="text-align:right">รวมน้ำหนัก (กก.)</th><th style="text-align:right">รวมเงิน (฿)</th></tr></thead><tbody>${rows}</tbody></table></body></html>`; },
    exportCSV(){ const header='ลำดับ,วันที่,ผู้ซื้อ,รวมน้ำหนัก,รวมเงิน\n'; const rows=this.sales.map((x,i)=>`${i+1},"${new Date(x.waste_sale_date).toLocaleDateString('th-TH')}","${x.waste_sale_buyer||'ไม่ระบุ'}",${Number(x.waste_sale_total_weight||0).toFixed(2)},${Number(x.waste_sale_total_price||0).toFixed(2)}`).join('\n'); const blob=new Blob([header+rows],{type:'text/csv;charset=utf-8;'}); const a=document.createElement('a'); a.href=URL.createObjectURL(blob); a.download='waste_sale_history.csv'; a.click(); }
  }
}
</script>
