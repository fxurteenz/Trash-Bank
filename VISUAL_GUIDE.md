# 🎬 Visual Guide - ระบบขายขยะ (POS)

## 🎯 System Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                      WASTE SALE SYSTEM                       │
│                     (POS-Style Interface)                    │
└─────────────────────────────────────────────────────────────┘

┌──────────────────┐  ┌──────────────────┐  ┌──────────────────┐
│  Batch Entry     │  │  Single Entry    │  │  History View    │
│   (POS Tab 1)    │  │   (POS Tab 2)    │  │   (POS Tab 3)    │
│                  │  │                  │  │                  │
│ • Multiple items │  │ • One at a time  │  │ • Advanced filter│
│ • Quick totals   │  │ • Auto-reset     │  │ • Pagination     │
│ • Batch submit   │  │ • Continuous     │  │ • Summary stats  │
│ • Item list      │  │ • Tab navigation │  │ • Delete records │
└──────────────────┘  └──────────────────┘  └──────────────────┘
         │                     │                      │
         └─────────────────────┴──────────────────────┘
                       │
         ┌─────────────▼──────────────┐
         │  WasteSalePOS.mjs (JS)    │
         │  • Alpine.js handlers     │
         │  • API calls              │
         │  • Form logic             │
         └─────────────┬──────────────┘
                       │
     ┌─────────────────┼─────────────────┐
     │                 │                 │
     ▼                 ▼                 ▼
┌─────────────────────────────────────────────────────┐
│         WasteSaleController.php (API)               │
│  - GET /api/waste_sales                             │
│  - POST /api/waste_sales (single)                   │
│  - POST /api/waste_sales/batch                      │
│  - POST /api/waste_sales/update/[id]                │
│  - POST /api/waste_sales/delete/[id]                │
│  - GET /api/waste_sales/summary                     │
└────────────────┬────────────────────────────────────┘
                 │
                 ▼
        ┌─────────────────────────┐
        │  WasteSaleModel.php    │
        │  - CRUD Operations     │
        │  - Batch Processing    │
        │  - Aggregation Queries │
        └────────────┬────────────┘
                     │
                     ▼
        ┌────────────────────────┐
        │   Database (MySQL)     │
        │  • waste_sale table    │
        │  • Indexed columns     │
        │  • FK to waste_type    │
        └────────────────────────┘
```

---

## 📊 Data Flow - Batch Entry

```
User Input
    │
    │  [Buyer Name] [Date] [Type ID] [Weight]
    │
    ▼
┌─────────────────────────────────────┐
│  WasteSalePOSHandler()              │
│  • Validate input                   │
│  • Lookup waste type                │
│  • Calculate price                  │
│  • Add to items array               │
└─────────────────────────────────────┘
    │
    │ Display on UI:
    │ ✅ Item added
    │ 💰 Subtotal updated
    │ 📋 Item list refreshed
    │
    ▼ (After all items entered)
┌─────────────────────────────────────┐
│  submitBatch()                      │
│  • Prepare JSON payload             │
│  • Send POST /api/waste_sales/batch │
└─────────────────────────────────────┘
    │
    ▼
┌─────────────────────────────────────┐
│  WasteSaleController::CreateBatch() │
│  • Validate all data                │
│  • Begin transaction                │
│  • Insert each sale                 │
│  • Commit transaction               │
└─────────────────────────────────────┘
    │
    ▼
┌─────────────────────────────────────┐
│  WasteSaleModel::CreateBatchSales() │
│  • Execute inserts                  │
│  • Return created sales             │
└─────────────────────────────────────┘
    │
    ▼
Database: INSERT into waste_sale table
    │
    ▼
Success Response with created IDs
    │
    ▼
UI Updates:
    ✅ Success message
    🔄 Clear form
    📊 Update totals
    🎯 Ready for next batch
```

---

## ⌨️ Keyboard Navigation Flow

```
Batch Entry Flow:
┌──────────────────────────────────────────────────────┐
│ TYPE: [Buyer Name] ─────────────────────┐            │
│                                         Enter         │
│ TAB ─────────────────────────────────────►           │
│                                                       │
│ SELECT: [Date Picker]                                │
│                                         Enter         │
│ TAB ─────────────────────────────────────►           │
│                                                       │
│ TYPE: [Type ID/Code]  ◄─────────────────────────┐    │
│ ┌──────────────────────┐                        │    │
│ │ ID 8: พลาสติก        │ (Lookup results)       │    │
│ │ ID 9: ขวด PET        │                        │    │
│ │ ID 4: กระดาษลัง      │                        │    │
│ └──────────────────────┘                        │    │
│                        Click or Tab             │    │
│ TAB ─────────────────────────────────────►     │    │
│                                                 │    │
│ TYPE: [Weight] 5.5                              │    │
│                                                 │    │
│ Enter ───────────────────────────────►  [✅ ADD]    │
│                                          item│       │
│ Back to Type ID ◄────────────────────────────┘      │
│ (Repeat for next item)                              │
│                                                      │
│ After all items: CLICK "✅ SUBMIT ALL"              │
└──────────────────────────────────────────────────────┘
```

---

## 🎨 UI Layout - POS Interface

```
╔═══════════════════════════════════════════════════════════════════╗
║  ☰  TRASH-BANK > Waste Center > Waste Sale (POS)        [ ≡ ]   ║
╠═══════════════════════════════════════════════════════════════════╣
║                                                                    ║
║  ┌─────────────────────────────────────────────────────────────┐ ║
║  │ 📦 Batch │ 💾 Single │ 📋 History                          │ ║
║  ├─────────────────────────────────────────────────────────────┤ ║
║  │                                                               │ ║
║  │ ┌──────────────────────────────────┐  ┌─────────────────┐  │ ║
║  │ │ 📝 FORM ENTRY          SUMMARY   │  │ 📋 Items (3)    │  │ ║
║  │ │ ┌─ Buyer Name ────────────────┐ │  │ ┌───────────────┐ │ ║
║  │ │ │ บริษัท XYZ               │ │  │ │ ✓ Plastic 5.5  │ │ ║
║  │ │ └──────────────────────────── │ │  │ │   @ 2฿/kg     │ │ ║
║  │ │ ┌─ Sale Date ──────────────────┐│  │ │   = 11.00฿    │ │ ║
║  │ │ │ 2026-01-13               │ │  │ │ ┌─────────────┐ │ │ ║
║  │ │ └──────────────────────────── │ │  │ │ ✓ PET 3.2   │ │ │ ║
║  │ │                                │ │  │ │   @ 4฿/kg   │ │ │ ║
║  │ │ ┌─ Waste Type ─────────────────┐│  │ │   = 12.80฿  │ │ │ ║
║  │ │ │ 8  [Type here]              │ │  │ │ ┌─────────┐ │ │ ║
║  │ │ │ Results: (1 match)           │ │  │ │ ✓ Paper  │ │ │ ║
║  │ │ │ > ID 8: Plastic Mixed       │ │  │ │   2.0 kg │ │ │ ║
║  │ │ └──────────────────────────── │ │  │ │   = 4฿   │ │ │ ║
║  │ │ ┌─ Weight (kg) ────────────────┐│  │ │ └─────────┘ │ │ │ ║
║  │ │ │ 5.5                          │ │  │ │ ┌─────────┐ │ │ ║
║  │ │ │ [Enter to add]               │ │  │ │ Total:    │ │ │ ║
║  │ │ └──────────────────────────── │ │  │ │ Weight:   │ │ │ ║
║  │ │ ┌─────────────────────────────┐ │  │ │ 10.7 kg   │ │ │ ║
║  │ │ │[Clear All] [Undo Last]     │ │  │ │ Revenue:  │ │ │ ║
║  │ │ └─────────────────────────────┘ │  │ │ 27.80฿    │ │ │ ║
║  │ │                                   │  │ │ ┌────────┐ │ │ ║
║  │ │                                   │  │ │[✅ Save]│ │ │ ║
║  │ │                                   │  │ │└────────┘ │ │ │ ║
║  │ └──────────────────────────────────┘  │ └────────────┘ │ ║
║  │                                                        │ ║
║  └──────────────────────────────────────────────────────────┘ ║
║                                                                 ║
╚═════════════════════════════════════════════════════════════════╝
```

---

## 📑 History View Layout

```
╔══════════════════════════════════════════════════════════════════╗
║  📋 Waste Sale History                                  [ ≡ ]   ║
╠══════════════════════════════════════════════════════════════════╣
║                                                                   ║
║  🔍 FILTERS                                                      ║
║  ┌──────────────────────────────────────────────────────────┐   ║
║  │ 📅 Date: [2026-01-13] │ 🏷️ Type: [All ▼] │ 👤 Buyer: [__]   ║
║  │ [🔍 Search]  [🔄 Clear]                                  │   ║
║  └──────────────────────────────────────────────────────────┘   ║
║                                                                   ║
║  📊 SUMMARY                                                      ║
║  ┌──────────────────────────────────────────────────────────┐   ║
║  │ 📦 Items: 150  │ ⚖️ Weight: 1,250 kg │ 💰 Revenue: 5,000฿   ║
║  └──────────────────────────────────────────────────────────┘   ║
║                                                                   ║
║  📋 RECORDS                                                      ║
║  ┌──────────────────────────────────────────────────────────┐   ║
║  │ Date    │ Type         │ Weight  │ Price  │ Buyer    │    ║
║  ├──────────────────────────────────────────────────────────┤   ║
║  │ 2026-01 │ Plastic      │ 5.500   │ 11.00  │ Co. ABC  │ 🗑️  ║
║  │ 2026-01 │ PET Bottle   │ 3.200   │ 12.80  │ Co. ABC  │ 🗑️  ║
║  │ 2026-01 │ Paper Card   │ 2.000   │ 4.00   │ Co. XYZ  │ 🗑️  ║
║  │ 2026-01 │ Glass Bottle │ 1.500   │ 1.50   │ Co. XYZ  │ 🗑️  ║
║  │ ...                                                      │   ║
║  └──────────────────────────────────────────────────────────┘   ║
║                                                                   ║
║  Pagination: [◀ Previous] Page 1/8 [Next ▶]                     ║
║              Items per page: [20 ▼]                             ║
║                                                                   ║
╚══════════════════════════════════════════════════════════════════╝
```

---

## 🔄 API Response Example - Batch Create

```json
Request:
POST /api/waste_sales/batch
{
  "sales": [
    {
      "waste_sale_type_id": 8,
      "waste_sale_weight": "5.5",
      "waste_sale_actual_price": "11.00",
      "waste_sale_buyer": "Company ABC",
      "waste_sale_date": "2026-01-13"
    },
    {
      "waste_sale_type_id": 9,
      "waste_sale_weight": "3.2",
      "waste_sale_actual_price": "12.80",
      "waste_sale_buyer": "Company ABC",
      "waste_sale_date": "2026-01-13"
    }
  ]
}

Response (201):
{
  "success": true,
  "result": {
    "success": true,
    "created_count": 2,
    "sales": [
      {
        "waste_sale_id": 101,
        "waste_sale_type_id": 8,
        "waste_sale_weight": "5.500",
        "waste_sale_actual_price": "11.00",
        "waste_sale_buyer": "Company ABC",
        "waste_sale_date": "2026-01-13",
        "waste_type_name": "Plastic Mixed Color",
        "created_at": "2026-01-13 10:30:45"
      },
      {
        "waste_sale_id": 102,
        "waste_sale_type_id": 9,
        "waste_sale_weight": "3.200",
        "waste_sale_actual_price": "12.80",
        "waste_sale_buyer": "Company ABC",
        "waste_sale_date": "2026-01-13",
        "waste_type_name": "PET Bottle",
        "created_at": "2026-01-13 10:30:46"
      }
    ]
  },
  "message": "Batch waste sales created successfully"
}
```

---

## 🎯 User Journey Map

```
┌─────────────────────────────────────────────────────────────┐
│               USER JOURNEY - WASTE SALE                      │
└─────────────────────────────────────────────────────────────┘

1. LOGIN
   └─► Admin/Staff logs into system
       └─► Has waste to sell

2. NAVIGATE
   └─► Goes to /waste_center/transactions/waste_sale
       └─► Sees POS interface

3. CHOOSE METHOD
   ├─► Option A: BATCH (for multiple items)
   │   ├─► Enter buyer name
   │   ├─► Select date
   │   ├─► Add type 1 → weight 1 → Enter
   │   ├─► Add type 2 → weight 2 → Enter
   │   ├─► Add type 3 → weight 3 → Enter
   │   ├─► Review totals on right side
   │   └─► Click "Save 3 Items"
   │
   └─► Option B: SINGLE (for one item at a time)
       ├─► Enter buyer name
       ├─► Select date
       ├─► Enter type code
       ├─► Tab to weight field
       ├─► Enter weight
       ├─► Press Enter
       ├─► Form resets
       └─► System ready for next item

4. SUBMIT CONFIRMATION
   └─► See success message with count
       └─► Items saved to database

5. VERIFY (Optional)
   └─► Go to History tab
       ├─► Filter by date
       ├─► See all created items
       ├─► Verify amounts
       └─► Can delete if needed

6. CONTINUE
   └─► Ready for next batch
```

---

## 📊 Database Relationships

```
┌────────────────────┐        ┌──────────────────┐
│  waste_type        │        │  waste_sale      │
├────────────────────┤        ├──────────────────┤
│ PK waste_type_id   │◄────┐  │ PK waste_sale_id │
│    waste_type_name │     │  │ FK waste_sale_id │
│    waste_type_price│     └──┤ waste_sale_weight│
│    waste_category  │        │ waste_sale_price │
│                    │        │ waste_sale_buyer │
│                    │        │ waste_sale_date  │
└────────────────────┘        │ created_at       │
                              └──────────────────┘
```

---

## ✅ Success Criteria

```
✅ Data Entry Speed
   • < 5 seconds per item in batch mode
   • < 3 seconds per item in single mode
   • No mouse lifting required

✅ Data Accuracy
   • All required fields validated
   • Auto-calculated totals correct
   • No data loss during submission

✅ User Experience
   • Clear visual feedback
   • Helpful error messages
   • Keyboard shortcuts work
   • Responsive on all screen sizes

✅ System Performance
   • Database queries < 100ms
   • Batch submission < 500ms
   • History loading < 1s

✅ Security
   • Only authenticated users can access
   • Data is properly stored
   • No SQL injection possible
```

---

**Visual Guide Version:** 1.0  
**Last Updated:** January 13, 2026  
**Status:** ✅ Complete
