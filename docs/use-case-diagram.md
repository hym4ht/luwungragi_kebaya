# Use Case Diagram - Sistem Rental Kostum Luwungragi

## Actors
1. **Customer (Penyewa)** - Pengguna yang menyewa kostum
2. **Admin** - Mengelola sistem, kostum, dan transaksi
3. **Owner** - Melihat laporan dan dashboard bisnis
4. **Midtrans System** - Sistem pembayaran eksternal

## Use Cases

### Customer Use Cases
```
Customer
├── Register & Login
├── Browse Catalog
│   └── View Costume Details
├── Create Rental Booking
│   ├── Select Costume
│   ├── Choose Event Date
│   ├── Set Rental Duration (1-5 days)
│   └── Check Availability
├── Make Payment
│   ├── Generate Midtrans Token
│   ├── Pay via Midtrans
│   └── Sync Payment Status
├── View My Orders
└── View Rental Details
    ├── Check Payment Status
    ├── Check Rental Status
    └── View Return Information
```

### Admin Use Cases
```
Admin
├── Login
├── View Dashboard
│   ├── View Statistics
│   └── View Recent Activities
├── Manage Costumes
│   ├── Create Costume
│   ├── Edit Costume
│   ├── Delete Costume
│   └── View Costume List
├── Manage Customers
│   └── View Customer List
├── Manage Rentals
│   ├── View All Rentals
│   ├── Filter Rentals (by status, payment)
│   ├── View Rental Details
│   ├── Update Rental Status
│   │   ├── Pending → Active
│   │   ├── Active → Completed
│   │   └── Any → Cancelled
│   ├── Update Payment Status
│   │   ├── Pending → Settlement
│   │   ├── Pending → Expire
│   │   └── Pending → Cancel
│   └── Record Return
│       ├── Set Return Date
│       └── Calculate Damage Fee
└── View Reports
```

### Owner Use Cases
```
Owner
├── Login
├── View Dashboard
│   ├── View Revenue Statistics
│   ├── View Rental Statistics
│   └── View Performance Metrics
└── View Reports
    ├── Revenue Reports
    ├── Rental Reports
    └── Customer Reports
```

### System Use Cases
```
Midtrans System
├── Process Payment
├── Send Webhook Notification
└── Update Transaction Status
```

## Business Rules

### Rental Rules
- **Booking Buffer**: Minimal booking H-3 sebelum event
- **Payment Due**: Maksimal H-2 sebelum event
- **Pickup**: H-1 sebelum event (offline)
- **Return**: H+1 setelah event selesai
- **Rental Duration**: 1-5 hari
- **Late Fee**: Rp 15.000 per hari

### Status Flow
**Rental Status:**
- Pending → Active → Completed
- Any Status → Cancelled

**Payment Status:**
- Pending → Settlement (Lunas)
- Pending → Expire (Kedaluwarsa)
- Pending → Cancel (Dibatalkan)

## Relationships
- Customer **creates** Rental Booking
- Customer **makes** Payment via Midtrans
- Admin **manages** Costumes
- Admin **manages** Rentals
- Admin **updates** Payment Status
- Admin **records** Returns
- Owner **views** Reports
- Midtrans **processes** Payment
- Midtrans **notifies** System via Webhook
```