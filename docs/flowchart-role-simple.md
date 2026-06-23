# Flowchart Masing-Masing Role - Sistem Rental Kostum

## Cara Menggunakan di Draw.io:
1. Buka https://app.diagrams.net/
2. Pilih "Arrange" > "Insert" > "Advanced" > "Mermaid"
3. Copy-paste kode di bawah
4. Klik "Insert"

---

## 1. FLOWCHART ROLE: CUSTOMER (PENYEWA)

```mermaid
flowchart TD
    Start([CUSTOMER START]) --> Login[Login ke Sistem]
    Login --> Dashboard[Customer Dashboard]
    
    Dashboard --> Action{Pilih Menu}
    
    Action -->|Browse Catalog| Browse[Lihat Katalog Kostum]
    Action -->|My Orders| Orders[Lihat Pesanan Saya]
    Action -->|Logout| Logout[Logout]
    
    Browse --> Select[Pilih Kostum]
    Select --> Details[Lihat Detail Kostum]
    Details --> Book[Isi Form Booking]
    
    Book --> InputDate[Input Event Date min H-3]
    InputDate --> InputDuration[Input Durasi 1-5 hari]
    InputDuration --> InputQty[Input Jumlah]
    InputQty --> Submit[Submit Booking]
    
    Submit --> ValidDate{Tanggal Valid?}
    ValidDate -->|Tidak| ErrorDate[Error: Min H-3] --> Book
    ValidDate -->|Ya| CheckStock{Stok Tersedia?}
    
    CheckStock -->|Tidak| ErrorStock[Error: Stok Habis] --> Browse
    CheckStock -->|Ya| CreateBooking[Buat Rental]
    
    CreateBooking --> GenInvoice[Generate Invoice]
    GenInvoice --> StatusPending[Status: Pending]
    StatusPending --> RedirectPayment[Ke Halaman Payment]
    
    RedirectPayment --> ViewPayment[Lihat Detail Payment]
    ViewPayment --> ClickPay[Klik Tombol Bayar]
    ClickPay --> OpenMidtrans[Buka Midtrans]
    
    OpenMidtrans --> ChoosePayment[Pilih Metode Bayar]
    ChoosePayment --> PaymentMethod{Metode?}
    
    PaymentMethod -->|Transfer| Transfer[Bank Transfer]
    PaymentMethod -->|E-Wallet| EWallet[GoPay/OVO/Dana]
    PaymentMethod -->|Kartu Kredit| CC[Credit Card]
    
    Transfer --> ProcessPayment[Proses Pembayaran]
    EWallet --> ProcessPayment
    CC --> ProcessPayment
    
    ProcessPayment --> PaymentResult{Hasil?}
    PaymentResult -->|Berhasil| PaymentSuccess[Payment Success]
    PaymentResult -->|Gagal| PaymentFailed[Payment Failed]
    
    PaymentSuccess --> NotifSuccess[Notifikasi Lunas]
    NotifSuccess --> InfoPickup[Info: Ambil H-1]
    InfoPickup --> Orders
    
    PaymentFailed --> RetryOption{Coba Lagi?}
    RetryOption -->|Ya| ChoosePayment
    RetryOption -->|Tidak| Orders
    
    Orders --> ViewOrders[Lihat Daftar Pesanan]
    ViewOrders --> SelectOrder[Pilih Pesanan]
    SelectOrder --> OrderDetail[Lihat Detail Pesanan]
    OrderDetail --> CheckStatus[Cek Status]
    
    CheckStatus --> StatusInfo{Status?}
    StatusInfo -->|Pending| InfoPending[Menunggu Pembayaran]
    StatusInfo -->|Active| InfoActive[Siap Diambil/Sedang Dipakai]
    StatusInfo -->|Completed| InfoCompleted[Selesai]
    StatusInfo -->|Cancelled| InfoCancelled[Dibatalkan]
    
    InfoPending --> Dashboard
    InfoActive --> Dashboard
    InfoCompleted --> Dashboard
    InfoCancelled --> Dashboard
    
    Logout --> End([END])

    style Start fill:#90EE90
    style End fill:#FFB6C1
    style PaymentSuccess fill:#A5D6A7
    style PaymentFailed fill:#EF9A9A
    style ErrorDate fill:#FFB6C1
    style ErrorStock fill:#FFB6C1
```

---

## 2. FLOWCHART ROLE: ADMIN

```mermaid
flowchart TD
    Start([ADMIN START]) --> Login[Login Admin]
    Login --> ValidateAdmin{Role Admin?}
    ValidateAdmin -->|Tidak| Denied[Access Denied] --> End([END])
    ValidateAdmin -->|Ya| Dashboard[Admin Dashboard]
    
    Dashboard --> ViewStats[Lihat Statistik]
    ViewStats --> AdminMenu{Pilih Menu}
    
    AdminMenu -->|Kelola Kostum| Costumes[Menu Kostum]
    AdminMenu -->|Kelola Rental| Rentals[Menu Rental]
    AdminMenu -->|Kelola Customer| Customers[Menu Customer]
    AdminMenu -->|Logout| Logout[Logout]
    
    %% KELOLA KOSTUM
    Costumes --> CostumeList[Lihat Daftar Kostum]
    CostumeList --> CostumeAction{Pilih Aksi}
    
    CostumeAction -->|Tambah| AddCostume[Form Tambah Kostum]
    CostumeAction -->|Edit| EditCostume[Pilih & Edit Kostum]
    CostumeAction -->|Hapus| DeleteCostume[Pilih & Hapus Kostum]
    CostumeAction -->|Kembali| Dashboard
    
    AddCostume --> FillCostume[Isi Data Kostum]
    FillCostume --> UploadImages[Upload Gambar]
    UploadImages --> SaveCostume[Simpan Kostum]
    SaveCostume --> SuccessCostume[Berhasil Ditambahkan]
    SuccessCostume --> CostumeList
    
    EditCostume --> ModifyCostume[Ubah Data Kostum]
    ModifyCostume --> UpdateCostume[Update Kostum]
    UpdateCostume --> SuccessUpdate[Berhasil Diupdate]
    SuccessUpdate --> CostumeList
    
    DeleteCostume --> ConfirmDelete{Konfirmasi Hapus?}
    ConfirmDelete -->|Tidak| CostumeList
    ConfirmDelete -->|Ya| CheckActive{Ada Rental Aktif?}
    CheckActive -->|Ya| ErrorDelete[Error: Tidak Bisa Dihapus]
    CheckActive -->|Tidak| DeleteRecord[Hapus Kostum]
    ErrorDelete --> CostumeList
    DeleteRecord --> SuccessDelete[Berhasil Dihapus]
    SuccessDelete --> CostumeList
    
    %% KELOLA RENTAL
    Rentals --> RentalList[Lihat Daftar Rental]
    RentalList --> RentalAction{Pilih Aksi}
    
    RentalAction -->|Filter| FilterRental[Filter Status/Payment]
    RentalAction -->|Lihat Detail| ViewRental[Pilih Rental]
    RentalAction -->|Kembali| Dashboard
    
    FilterRental --> ApplyFilter[Terapkan Filter]
    ApplyFilter --> RentalList
    
    ViewRental --> RentalDetail[Lihat Detail Lengkap]
    RentalDetail --> DetailAction{Pilih Aksi}
    
    DetailAction -->|Update Status| UpdateStatus[Ubah Status Rental]
    DetailAction -->|Update Payment| UpdatePayment[Ubah Status Payment]
    DetailAction -->|Catat Return| RecordReturn[Catat Pengembalian]
    DetailAction -->|Kembali| RentalList
    
    UpdateStatus --> CurrentStatus{Status Saat Ini?}
    CurrentStatus -->|Pending| ToPendingActive[Ubah ke Active/Cancel]
    CurrentStatus -->|Active| ToActiveComplete[Ubah ke Completed/Cancel]
    CurrentStatus -->|Completed| NoChangeStatus[Tidak Bisa Diubah]
    
    ToPendingActive --> SaveStatus[Simpan Status]
    ToActiveComplete --> SaveStatus
    SaveStatus --> SuccessStatus[Status Berhasil Diubah]
    SuccessStatus --> RentalDetail
    NoChangeStatus --> RentalDetail
    
    UpdatePayment --> PaymentStatus{Status Payment?}
    PaymentStatus -->|Pending| ChangePayment[Ubah ke Settlement/Expire/Cancel]
    PaymentStatus -->|Lainnya| NoChangePayment[Tidak Bisa Diubah]
    
    ChangePayment --> SavePayment[Simpan Payment]
    SavePayment --> SuccessPayment[Payment Berhasil Diubah]
    SuccessPayment --> RentalDetail
    NoChangePayment --> RentalDetail
    
    RecordReturn --> InputReturn[Input Tanggal Kembali]
    InputReturn --> InputDamage[Input Biaya Kerusakan]
    InputDamage --> CalcLate{Terlambat?}
    
    CalcLate -->|Ya| CalcLateFee[Hitung Denda Rp 15rb/hari]
    CalcLate -->|Tidak| NoLateFee[Denda = 0]
    
    CalcLateFee --> SaveReturn[Simpan Data Return]
    NoLateFee --> SaveReturn
    SaveReturn --> CompleteRental[Status = Completed]
    CompleteRental --> SuccessReturn[Return Berhasil Dicatat]
    SuccessReturn --> RentalDetail
    
    %% KELOLA CUSTOMER
    Customers --> CustomerList[Lihat Daftar Customer]
    CustomerList --> SelectCustomer[Pilih Customer]
    SelectCustomer --> CustomerDetail[Lihat Detail Customer]
    CustomerDetail --> CustomerHistory[Lihat Riwayat Rental]
    CustomerHistory --> BackCustomer[Kembali]
    BackCustomer --> CustomerList
    CustomerList --> BackDashboard[Kembali ke Dashboard]
    BackDashboard --> Dashboard
    
    Logout --> End

    style Start fill:#90EE90
    style End fill:#FFB6C1
    style Denied fill:#EF9A9A
    style SuccessCostume fill:#A5D6A7
    style SuccessUpdate fill:#A5D6A7
    style SuccessDelete fill:#A5D6A7
    style SuccessStatus fill:#A5D6A7
    style SuccessPayment fill:#A5D6A7
    style SuccessReturn fill:#A5D6A7
    style ErrorDelete fill:#FFB6C1
```

---

## 3. FLOWCHART ROLE: OWNER

```mermaid
flowchart TD
    Start([OWNER START]) --> Login[Login Owner]
    Login --> ValidateOwner{Role Owner?}
    ValidateOwner -->|Tidak| Denied[Access Denied] --> End([END])
    ValidateOwner -->|Ya| Dashboard[Owner Dashboard]
    
    Dashboard --> LoadData[Load Data Statistik]
    LoadData --> ShowRevenue[Tampilkan Total Revenue]
    ShowRevenue --> ShowRentals[Tampilkan Total Rental]
    ShowRentals --> ShowCustomers[Tampilkan Total Customer]
    ShowCustomers --> ShowCharts[Tampilkan Grafik]
    
    ShowCharts --> OwnerMenu{Pilih Menu}
    
    OwnerMenu -->|Laporan Revenue| RevenueReport[Menu Laporan Revenue]
    OwnerMenu -->|Laporan Rental| RentalReport[Menu Laporan Rental]
    OwnerMenu -->|Laporan Customer| CustomerReport[Menu Laporan Customer]
    OwnerMenu -->|Analisis Kostum| CostumeAnalysis[Menu Analisis Kostum]
    OwnerMenu -->|Refresh Data| LoadData
    OwnerMenu -->|Logout| Logout[Logout]
    
    %% LAPORAN REVENUE
    RevenueReport --> SelectPeriod[Pilih Periode]
    SelectPeriod --> PeriodType{Tipe Periode?}
    
    PeriodType -->|Harian| DailyRevenue[Revenue Harian]
    PeriodType -->|Bulanan| MonthlyRevenue[Revenue Bulanan]
    PeriodType -->|Tahunan| YearlyRevenue[Revenue Tahunan]
    PeriodType -->|Custom| CustomPeriod[Pilih Range Tanggal]
    
    DailyRevenue --> ShowRevenueData[Tampilkan Data Revenue]
    MonthlyRevenue --> ShowRevenueData
    YearlyRevenue --> ShowRevenueData
    CustomPeriod --> ShowRevenueData
    
    ShowRevenueData --> RevenueChart[Tampilkan Chart Revenue]
    RevenueChart --> RevenueDetail[Detail Revenue]
    RevenueDetail --> ExportRevenue{Export Laporan?}
    
    ExportRevenue -->|Ya| SelectFormat[Pilih Format]
    ExportRevenue -->|Tidak| BackRevenue[Kembali]
    
    SelectFormat --> FormatType{Format?}
    FormatType -->|PDF| ExportPDF[Export ke PDF]
    FormatType -->|Excel| ExportExcel[Export ke Excel]
    FormatType -->|CSV| ExportCSV[Export ke CSV]
    
    ExportPDF --> Download[Download File]
    ExportExcel --> Download
    ExportCSV --> Download
    Download --> SuccessExport[Export Berhasil]
    SuccessExport --> BackRevenue
    BackRevenue --> Dashboard
    
    %% LAPORAN RENTAL
    RentalReport --> ShowRentalStats[Tampilkan Statistik Rental]
    ShowRentalStats --> RentalBreakdown[Breakdown Status]
    RentalBreakdown --> CountPending[Hitung Pending]
    CountPending --> CountActive[Hitung Active]
    CountActive --> CountCompleted[Hitung Completed]
    CountCompleted --> CountCancelled[Hitung Cancelled]
    
    CountCancelled --> RentalTrend[Tampilkan Trend Rental]
    RentalTrend --> RentalChart[Chart Rental per Bulan]
    RentalChart --> PopularTime[Waktu Populer]
    PopularTime --> ExportRental{Export?}
    
    ExportRental -->|Ya| SelectFormat
    ExportRental -->|Tidak| BackRental[Kembali]
    BackRental --> Dashboard
    
    %% LAPORAN CUSTOMER
    CustomerReport --> ShowCustomerStats[Tampilkan Statistik Customer]
    ShowCustomerStats --> TotalCustomers[Total Customer]
    TotalCustomers --> NewCustomers[Customer Baru]
    NewCustomers --> TopCustomers[Top 10 Customer]
    TopCustomers --> CustomerRetention[Retention Rate]
    
    CustomerRetention --> CustomerChart[Chart Customer Growth]
    CustomerChart --> CustomerSegment[Segmentasi Customer]
    CustomerSegment --> ExportCustomer{Export?}
    
    ExportCustomer -->|Ya| SelectFormat
    ExportCustomer -->|Tidak| BackCustomer[Kembali]
    BackCustomer --> Dashboard
    
    %% ANALISIS KOSTUM
    CostumeAnalysis --> ShowCostumePerf[Tampilkan Performa Kostum]
    ShowCostumePerf --> PopularCostumes[Kostum Terpopuler]
    PopularCostumes --> CostumeRevenue[Revenue per Kostum]
    CostumeRevenue --> UtilizationRate[Tingkat Utilisasi]
    UtilizationRate --> StockStatus[Status Stok]
    
    StockStatus --> CostumeChart[Chart Performa Kostum]
    CostumeChart --> Recommendations[Rekomendasi]
    Recommendations --> ShowReco[Tampilkan Rekomendasi]
    ShowReco --> RecoType{Tipe Rekomendasi}
    
    RecoType -->|Tambah Stok| RecoAddStock[Rekomendasi Tambah Stok]
    RecoType -->|Kurangi Stok| RecoReduceStock[Rekomendasi Kurangi Stok]
    RecoType -->|Kostum Baru| RecoNewCostume[Rekomendasi Kostum Baru]
    
    RecoAddStock --> BackAnalysis[Kembali]
    RecoReduceStock --> BackAnalysis
    RecoNewCostume --> BackAnalysis
    BackAnalysis --> Dashboard
    
    Logout --> End

    style Start fill:#90EE90
    style End fill:#FFB6C1
    style Denied fill:#EF9A9A
    style SuccessExport fill:#A5D6A7
    style Download fill:#81D4FA
```

---

## 4. FLOWCHART SISTEM: MIDTRANS WEBHOOK

```mermaid
flowchart TD
    Start([MIDTRANS WEBHOOK]) --> Receive[Terima POST Request]
    Receive --> Extract[Extract Data]
    Extract --> GetOrder[Get order_id]
    GetOrder --> GetStatus[Get transaction_status]
    GetStatus --> GetSignature[Get signature_key]
    
    GetSignature --> Verify[Verify Signature]
    Verify --> CalcHash[Hitung Hash]
    CalcHash --> Compare{Signature Valid?}
    
    Compare -->|Tidak| LogWarning[Log Security Warning]
    Compare -->|Ya| FindPayment[Cari Payment]
    
    LogWarning --> Return403[HTTP 403 Forbidden]
    Return403 --> End([END])
    
    FindPayment --> Found{Payment Ditemukan?}
    Found -->|Tidak| LogError[Log Error]
    Found -->|Ya| MapStatus[Map Status]
    
    LogError --> Return404[HTTP 404 Not Found]
    Return404 --> End
    
    MapStatus --> StatusType{Transaction Status?}
    StatusType -->|capture/settlement| SetSettlement[Status = Settlement]
    StatusType -->|pending| SetPending[Status = Pending]
    StatusType -->|deny/expire| SetExpire[Status = Expire]
    StatusType -->|cancel| SetCancel[Status = Cancel]
    
    SetSettlement --> UpdateDB[Update Database]
    SetPending --> UpdateDB
    SetExpire --> UpdateDB
    SetCancel --> UpdateDB
    
    UpdateDB --> LogUpdate[Log Transaction]
    LogUpdate --> CheckSettlement{Status = Settlement?}
    
    CheckSettlement -->|Ya| Notify[Kirim Notifikasi Customer]
    CheckSettlement -->|Tidak| LogOnly[Log Saja]
    
    Notify --> UpdateRental[Update Rental Status]
    UpdateRental --> Return200[HTTP 200 OK]
    LogOnly --> Return200
    
    Return200 --> End

    style Start fill:#90EE90
    style End fill:#FFB6C1
    style Return200 fill:#A5D6A7
    style Return403 fill:#EF9A9A
    style Return404 fill:#EF9A9A
```

---

## 5. FLOWCHART SISTEM: CEK KETERSEDIAAN STOK

```mermaid
flowchart TD
    Start([CEK STOK]) --> Input[Input Data]
    Input --> GetCostume[Get Costume ID]
    GetCostume --> GetEvent[Get Event Date]
    GetEvent --> GetDuration[Get Duration]
    GetDuration --> GetQty[Get Quantity]
    
    GetQty --> CalcRange[Hitung Range Tanggal]
    CalcRange --> StartDate[Start = Event - 3 hari]
    StartDate --> EndDate[End = Event + Duration]
    
    EndDate --> QueryRentals[Query Rental Aktif]
    QueryRentals --> FilterDate[Filter Overlap Tanggal]
    FilterDate --> GetDetails[Get Rental Details]
    
    GetDetails --> SumReserved[Jumlahkan Qty Reserved]
    SumReserved --> GetTotal[Get Total Stok Kostum]
    GetTotal --> CalcAvailable[Available = Total - Reserved]
    
    CalcAvailable --> Compare{Available >= Requested?}
    Compare -->|Ya| StockOK[Stok Tersedia]
    Compare -->|Tidak| StockNot[Stok Tidak Cukup]
    
    StockOK --> ReturnSuccess[Return Success]
    StockNot --> ReturnError[Return Error]
    
    ReturnSuccess --> AllowBooking[Izinkan Booking]
    ReturnError --> RejectBooking[Tolak Booking]
    
    AllowBooking --> End([END])
    RejectBooking --> End

    style Start fill:#90EE90
    style End fill:#FFB6C1
    style StockOK fill:#A5D6A7
    style StockNot fill:#EF9A9A
    style AllowBooking fill:#A5D6A7
    style RejectBooking fill:#EF9A9A
```

---

## 6. FLOWCHART SISTEM: LIFECYCLE STATUS RENTAL

```mermaid
flowchart TD
    Start([RENTAL DIBUAT]) --> Pending[Status: PENDING]
    
    Pending --> WaitPayment[Tunggu Pembayaran]
    WaitPayment --> CheckPayment{Status Payment?}
    
    CheckPayment -->|Settlement| AdminCheck[Admin Cek Payment]
    CheckPayment -->|Expire| AutoCancel[Auto Cancel]
    CheckPayment -->|Cancel| AutoCancel
    CheckPayment -->|Pending| CheckDue{Lewat Deadline?}
    
    CheckDue -->|Ya| AutoExpire[Auto Expire]
    CheckDue -->|Tidak| WaitPayment
    
    AdminCheck --> AdminActivate[Admin Aktivasi]
    AdminActivate --> Active[Status: ACTIVE]
    
    AutoCancel --> Cancelled[Status: CANCELLED]
    AutoExpire --> Cancelled
    
    Active --> Pickup[Customer Ambil H-1]
    Pickup --> Use[Customer Pakai Kostum]
    Use --> Return[Customer Kembalikan H+1]
    
    Return --> AdminRecord[Admin Catat Return]
    AdminRecord --> CheckDate{Tepat Waktu?}
    
    CheckDate -->|Ya| NoFee[Denda = 0]
    CheckDate -->|Tidak| CalcFee[Hitung Denda]
    
    CalcFee --> LateFee[Denda = Hari × Rp 15rb]
    LateFee --> CheckDamage{Ada Kerusakan?}
    NoFee --> CheckDamage
    
    CheckDamage -->|Ya| AddDamage[Tambah Biaya Kerusakan]
    CheckDamage -->|Tidak| NoDamage[Tidak Ada Biaya]
    
    AddDamage --> SaveReturn[Simpan Data Return]
    NoDamage --> SaveReturn
    
    SaveReturn --> Completed[Status: COMPLETED]
    
    Pending --> ManualCancel{Admin Cancel?}
    ManualCancel -->|Ya| Cancelled
    
    Active --> ManualCancel2{Admin Cancel?}
    ManualCancel2 -->|Ya| Cancelled
    
    Completed --> End([END])
    Cancelled --> End

    style Start fill:#90EE90
    style End fill:#FFB6C1
    style Pending fill:#FFF59D
    style Active fill:#81D4FA
    style Completed fill:#A5D6A7
    style Cancelled fill:#EF9A9A
```

---

## RINGKASAN

### Flowchart yang Tersedia:

1. **Customer (Penyewa)** - Flow lengkap dari login, booking, payment, sampai tracking order
2. **Admin** - Flow kelola kostum, rental, payment, return, dan customer
3. **Owner** - Flow dashboard, laporan revenue/rental/customer, analisis, dan export
4. **Midtrans Webhook** - Flow processing webhook dari Midtrans
5. **Cek Stok** - Flow algoritma pengecekan ketersediaan stok
6. **Lifecycle Rental** - Flow perubahan status rental dari pending sampai completed

### Cara Pakai:
- Copy kode Mermaid
- Paste ke draw.io (Insert > Advanced > Mermaid)
- Atau gunakan di https://mermaid.live untuk preview
- Sesuaikan styling sesuai kebutuhan

### Warna:
- 🟢 Hijau = Start
- 🔴 Merah = End / Error
- 🔵 Biru = Success / Info
- 🟡 Kuning = Pending / Warning