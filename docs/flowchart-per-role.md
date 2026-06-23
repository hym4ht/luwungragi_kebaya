# Flowchart Per Role - Sistem Rental Kostum Luwungragi

## Cara Menggunakan di Draw.io:
1. Buka https://app.diagrams.net/
2. Pilih "Arrange" > "Insert" > "Advanced" > "Mermaid"
3. Copy-paste kode flowchart di bawah
4. Klik "Insert"

---

## 1. Flowchart - Customer Registration & Login

```mermaid
flowchart TD
    Start([Customer Access System]) --> HasToken{Has JWT Token?}
    
    HasToken -->|Yes| ValidateToken[Validate Token]
    HasToken -->|No| ShowLogin[Show Login Page]
    
    ValidateToken --> TokenValid{Token Valid?}
    TokenValid -->|Yes| CheckRole[Check User Role]
    TokenValid -->|No| ShowLogin
    
    ShowLogin --> HasAccount{Has Account?}
    HasAccount -->|No| ShowRegister[Show Register Form]
    HasAccount -->|Yes| EnterLogin[Enter Email & Password]
    
    ShowRegister --> FillRegister[Fill Registration Form]
    FillRegister --> SubmitRegister[Submit Registration]
    SubmitRegister --> ValidateReg{Valid Data?}
    ValidateReg -->|No| ShowRegError[Show Error] --> FillRegister
    ValidateReg -->|Yes| CreateUser[Create User Account]
    CreateUser --> RegSuccess[Registration Success]
    RegSuccess --> ShowLogin
    
    EnterLogin --> SubmitLogin[Submit Login]
    SubmitLogin --> ValidateCred{Valid Credentials?}
    ValidateCred -->|No| ShowLoginError[Show Error] --> EnterLogin
    ValidateCred -->|Yes| GenerateJWT[Generate JWT Token]
    GenerateJWT --> SetCookie[Set JWT Cookie]
    SetCookie --> CheckRole
    
    CheckRole --> IsCustomer{Role = Customer?}
    IsCustomer -->|Yes| CustomerDash[Customer Dashboard]
    IsCustomer -->|No| AccessDenied[Access Denied]
    
    CustomerDash --> End([End])
    AccessDenied --> End

    style Start fill:#90EE90
    style End fill:#FFB6C1
    style CustomerDash fill:#87CEEB
    style ShowRegError fill:#FFB6C1
    style ShowLoginError fill:#FFB6C1
    style AccessDenied fill:#FFB6C1
```

---

## 2. Flowchart - Customer Booking Process

```mermaid
flowchart TD
    Start([Customer Browse Catalog]) --> ViewCatalog[View Available Costumes]
    ViewCatalog --> SelectCostume[Select Costume]
    SelectCostume --> ViewDetails[View Costume Details]
    
    ViewDetails --> FillForm[Fill Booking Form]
    FillForm --> EnterDate[Enter Event Date]
    EnterDate --> EnterDuration[Enter Rental Duration 1-5 days]
    EnterDuration --> EnterQty[Enter Quantity]
    EnterQty --> SubmitBooking[Submit Booking]
    
    SubmitBooking --> ValidateDate{Event Date >= Today + 3?}
    ValidateDate -->|No| ErrorMinDate[Error: Minimum H-3] --> FillForm
    ValidateDate -->|Yes| ValidateDuration{Duration 1-5 days?}
    
    ValidateDuration -->|No| ErrorDuration[Error: Invalid Duration] --> FillForm
    ValidateDuration -->|Yes| CheckStock{Stock Available?}
    
    CheckStock -->|No| ErrorStock[Error: Stock Not Available] --> FillForm
    CheckStock -->|Yes| CalcSchedule[Calculate Schedule]
    
    CalcSchedule --> SetBookingDate[Booking Start = Event - 3 days]
    SetBookingDate --> SetPaymentDue[Payment Due = Event - 2 days]
    SetPaymentDue --> SetPickup[Pickup = Event - 1 day]
    SetPickup --> SetReturn[Return = Event + Duration]
    
    SetReturn --> CalcPrice[Calculate Total Price]
    CalcPrice --> CreateRental[Create Rental Record]
    CreateRental --> SetStatus[Status = Pending]
    SetStatus --> GenInvoice[Generate Invoice Number]
    GenInvoice --> CreateDetails[Create Rental Details]
    CreateDetails --> CreatePayment[Create Payment Record]
    CreatePayment --> SetPaymentPending[Payment Status = Pending]
    
    SetPaymentPending --> RedirectDetails[Redirect to Rental Details]
    RedirectDetails --> ShowSuccess[Show Success Message]
    ShowSuccess --> End([End])

    style Start fill:#90EE90
    style End fill:#FFB6C1
    style ErrorMinDate fill:#FFB6C1
    style ErrorDuration fill:#FFB6C1
    style ErrorStock fill:#FFB6C1
    style ShowSuccess fill:#87CEEB
```

---

## 3. Flowchart - Customer Payment Process

```mermaid
flowchart TD
    Start([Customer View Rental Details]) --> CheckPayment{Payment Status?}
    
    CheckPayment -->|Settlement| ShowSuccess[Show Payment Success]
    CheckPayment -->|Expire/Cancel| ShowFailed[Show Payment Failed]
    CheckPayment -->|Pending| ShowPayButton[Show Pay Button]
    
    ShowSuccess --> End([End])
    ShowFailed --> End
    
    ShowPayButton --> ClickPay[Customer Click Pay]
    ClickPay --> RequestToken[Request Midtrans Token]
    RequestToken --> GetRentalData[Get Rental Details]
    GetRentalData --> PrepareTransaction[Prepare Transaction Data]
    PrepareTransaction --> CallMidtrans[Call Midtrans API]
    CallMidtrans --> ReceiveToken[Receive Snap Token]
    
    ReceiveToken --> OpenMidtrans[Open Midtrans Payment Page]
    OpenMidtrans --> ChooseMethod[Choose Payment Method]
    ChooseMethod --> SelectMethod{Payment Method}
    
    SelectMethod -->|Bank Transfer| BankTransfer[Bank Transfer]
    SelectMethod -->|E-Wallet| EWallet[GoPay/OVO/Dana]
    SelectMethod -->|Credit Card| CreditCard[Credit Card]
    SelectMethod -->|Others| OtherMethod[Other Methods]
    
    BankTransfer --> CompletePayment[Complete Payment]
    EWallet --> CompletePayment
    CreditCard --> CompletePayment
    OtherMethod --> CompletePayment
    
    CompletePayment --> MidtransProcess[Midtrans Process Payment]
    MidtransProcess --> PaymentResult{Payment Result?}
    
    PaymentResult -->|Success| SendWebhook[Midtrans Send Webhook]
    PaymentResult -->|Failed| PaymentFailed[Payment Failed]
    PaymentResult -->|Pending| PaymentPending[Payment Pending]
    
    SendWebhook --> VerifySignature{Valid Signature?}
    VerifySignature -->|No| RejectWebhook[Reject HTTP 403]
    VerifySignature -->|Yes| UpdateStatus[Update Payment Status]
    
    UpdateStatus --> SetSettlement[Status = Settlement]
    SetSettlement --> NotifyCustomer[Notify Customer]
    NotifyCustomer --> ShowSuccessMsg[Show Success Message]
    ShowSuccessMsg --> End
    
    PaymentFailed --> CanRetry{Retry Payment?}
    CanRetry -->|Yes| ChooseMethod
    CanRetry -->|No| End
    
    PaymentPending --> CanSync{Sync Status?}
    CanSync -->|Yes| SyncStatus[Check Midtrans API]
    CanSync -->|No| End
    SyncStatus --> UpdateFromAPI[Update Status from API]
    UpdateFromAPI --> End
    
    RejectWebhook --> End

    style Start fill:#90EE90
    style End fill:#FFB6C1
    style ShowSuccess fill:#87CEEB
    style ShowFailed fill:#FFB6C1
    style ShowSuccessMsg fill:#87CEEB
    style RejectWebhook fill:#FFB6C1
```

---

## 4. Flowchart - Admin Login & Dashboard

```mermaid
flowchart TD
    Start([Admin Access System]) --> HasToken{Has JWT Token?}
    
    HasToken -->|Yes| ValidateToken[Validate Token]
    HasToken -->|No| ShowLogin[Show Admin Login]
    
    ValidateToken --> TokenValid{Token Valid?}
    TokenValid -->|Yes| CheckRole[Check User Role]
    TokenValid -->|No| ShowLogin
    
    ShowLogin --> EnterCred[Enter Email & Password]
    EnterCred --> SubmitLogin[Submit Login]
    SubmitLogin --> ValidateCred{Valid Credentials?}
    ValidateCred -->|No| ShowError[Show Error] --> EnterCred
    ValidateCred -->|Yes| GenerateJWT[Generate JWT Token]
    GenerateJWT --> SetCookie[Set JWT Cookie]
    SetCookie --> CheckRole
    
    CheckRole --> IsAdmin{Role = Admin?}
    IsAdmin -->|No| AccessDenied[Access Denied] --> End([End])
    IsAdmin -->|Yes| LoadDashboard[Load Dashboard Data]
    
    LoadDashboard --> GetStats[Get Statistics]
    GetStats --> CountRentals[Count Total Rentals]
    CountRentals --> CountActive[Count Active Rentals]
    CountActive --> CountCompleted[Count Completed Rentals]
    CountCompleted --> CalcRevenue[Calculate Total Revenue]
    CalcRevenue --> CountCustomers[Count Total Customers]
    CountCustomers --> GetRecent[Get Recent Activities]
    
    GetRecent --> DisplayDashboard[Display Admin Dashboard]
    DisplayDashboard --> AdminAction{Admin Action?}
    
    AdminAction -->|Manage Costumes| GoCostumes[Go to Costumes]
    AdminAction -->|Manage Rentals| GoRentals[Go to Rentals]
    AdminAction -->|Manage Customers| GoCustomers[Go to Customers]
    AdminAction -->|View Reports| GoReports[Go to Reports]
    AdminAction -->|Logout| Logout[Logout]
    
    GoCostumes --> End
    GoRentals --> End
    GoCustomers --> End
    GoReports --> End
    Logout --> End

    style Start fill:#90EE90
    style End fill:#FFB6C1
    style DisplayDashboard fill:#87CEEB
    style ShowError fill:#FFB6C1
    style AccessDenied fill:#FFB6C1
```

---

## 5. Flowchart - Admin Manage Costumes

```mermaid
flowchart TD
    Start([Admin Access Costumes]) --> LoadList[Load Costume List]
    LoadList --> DisplayList[Display All Costumes]
    DisplayList --> AdminAction{Select Action}
    
    AdminAction -->|Create| ShowCreateForm[Show Create Form]
    AdminAction -->|Edit| SelectCostume[Select Costume]
    AdminAction -->|Delete| SelectDelete[Select Costume to Delete]
    AdminAction -->|Back| End([End])
    
    ShowCreateForm --> FillCreate[Fill Costume Data]
    FillCreate --> EnterName[Enter Name]
    EnterName --> EnterDesc[Enter Description]
    EnterDesc --> EnterPrice[Enter Price per Day]
    EnterPrice --> EnterStock[Enter Stock]
    EnterStock --> EnterSize[Enter Size]
    EnterSize --> EnterCategory[Enter Category]
    EnterCategory --> UploadMain[Upload Main Image]
    UploadMain --> UploadGallery[Upload Gallery Images]
    UploadGallery --> SubmitCreate[Submit Create]
    
    SubmitCreate --> ValidateCreate{Valid Data?}
    ValidateCreate -->|No| ShowCreateError[Show Errors] --> FillCreate
    ValidateCreate -->|Yes| SaveImages[Save Images to Storage]
    SaveImages --> CreateRecord[Create Costume Record]
    CreateRecord --> ShowCreateSuccess[Show Success]
    ShowCreateSuccess --> DisplayList
    
    SelectCostume --> LoadCostume[Load Costume Data]
    LoadCostume --> ShowEditForm[Show Edit Form]
    ShowEditForm --> ModifyData[Modify Costume Data]
    ModifyData --> SubmitEdit[Submit Update]
    
    SubmitEdit --> ValidateEdit{Valid Data?}
    ValidateEdit -->|No| ShowEditError[Show Errors] --> ModifyData
    ValidateEdit -->|Yes| ImagesChanged{Images Changed?}
    
    ImagesChanged -->|Yes| UploadNewImages[Upload New Images]
    ImagesChanged -->|No| KeepImages[Keep Existing Images]
    
    UploadNewImages --> DeleteOldImages[Delete Old Images]
    DeleteOldImages --> UpdateRecord[Update Costume Record]
    KeepImages --> UpdateRecord
    
    UpdateRecord --> ShowEditSuccess[Show Success]
    ShowEditSuccess --> DisplayList
    
    SelectDelete --> ShowConfirm[Show Confirmation Dialog]
    ShowConfirm --> ConfirmDelete{Confirm Delete?}
    ConfirmDelete -->|No| DisplayList
    ConfirmDelete -->|Yes| CheckActiveRentals{Has Active Rentals?}
    
    CheckActiveRentals -->|Yes| ShowDeleteError[Error: Cannot Delete]
    CheckActiveRentals -->|No| DeleteImages[Delete Costume Images]
    
    ShowDeleteError --> DisplayList
    DeleteImages --> DeleteRecord[Delete Costume Record]
    DeleteRecord --> ShowDeleteSuccess[Show Success]
    ShowDeleteSuccess --> DisplayList

    style Start fill:#90EE90
    style End fill:#FFB6C1
    style ShowCreateSuccess fill:#87CEEB
    style ShowEditSuccess fill:#87CEEB
    style ShowDeleteSuccess fill:#87CEEB
    style ShowCreateError fill:#FFB6C1
    style ShowEditError fill:#FFB6C1
    style ShowDeleteError fill:#FFB6C1
```

---

## 6. Flowchart - Admin Manage Rentals

```mermaid
flowchart TD
    Start([Admin Access Rentals]) --> LoadRentals[Load All Rentals]
    LoadRentals --> DisplayList[Display Rentals List]
    DisplayList --> AdminAction{Select Action}
    
    AdminAction -->|Filter| ApplyFilter[Apply Filters]
    AdminAction -->|Search| SearchRental[Search by Invoice/Customer]
    AdminAction -->|View Details| SelectRental[Select Rental]
    AdminAction -->|Back| End([End])
    
    ApplyFilter --> FilterBy{Filter By?}
    FilterBy -->|Status| FilterStatus[Filter by Rental Status]
    FilterBy -->|Payment| FilterPayment[Filter by Payment Status]
    FilterBy -->|Date| FilterDate[Filter by Date Range]
    
    FilterStatus --> DisplayFiltered[Display Filtered Results]
    FilterPayment --> DisplayFiltered
    FilterDate --> DisplayFiltered
    DisplayFiltered --> AdminAction
    
    SearchRental --> EnterSearch[Enter Search Term]
    EnterSearch --> ExecuteSearch[Execute Search]
    ExecuteSearch --> DisplayFiltered
    
    SelectRental --> LoadDetails[Load Rental Details]
    LoadDetails --> ShowDetails[Show Complete Info]
    ShowDetails --> DetailAction{Select Action}
    
    DetailAction -->|Update Status| UpdateStatus[Update Rental Status]
    DetailAction -->|Update Payment| UpdatePayment[Update Payment Status]
    DetailAction -->|Record Return| RecordReturn[Record Return]
    DetailAction -->|Back| DisplayList
    
    UpdateStatus --> CurrentStatus{Current Status?}
    CurrentStatus -->|Pending| ChangePending[Change to Active/Cancelled]
    CurrentStatus -->|Active| ChangeActive[Change to Completed/Cancelled]
    CurrentStatus -->|Completed| NoChange[No Change Allowed]
    
    ChangePending --> SelectNewStatus[Select New Status]
    ChangeActive --> SelectNewStatus
    SelectNewStatus --> SaveStatus[Save Status to DB]
    SaveStatus --> ShowStatusSuccess[Show Success]
    ShowStatusSuccess --> ShowDetails
    NoChange --> ShowDetails
    
    UpdatePayment --> PaymentStatus{Payment Status?}
    PaymentStatus -->|Pending| ChangePayment[Change to Settlement/Expire/Cancel]
    PaymentStatus -->|Others| NoPaymentChange[No Change Allowed]
    
    ChangePayment --> SelectPaymentStatus[Select New Payment Status]
    SelectPaymentStatus --> SavePayment[Save Payment Status]
    SavePayment --> ShowPaymentSuccess[Show Success]
    ShowPaymentSuccess --> ShowDetails
    NoPaymentChange --> ShowDetails
    
    RecordReturn --> EnterReturnDate[Enter Return Date]
    EnterReturnDate --> EnterDamageFee[Enter Damage Fee Optional]
    EnterDamageFee --> CalcLateFee[Calculate Late Fee]
    CalcLateFee --> CheckLate{Returned Late?}
    
    CheckLate -->|Yes| CalcDays[Calculate Days Late]
    CheckLate -->|No| SetZeroFee[Late Fee = 0]
    
    CalcDays --> CalcAmount[Late Fee = Days × Rp 15000]
    CalcAmount --> CreateReturn[Create Return Record]
    SetZeroFee --> CreateReturn
    
    CreateReturn --> UpdateRentalComplete[Update Rental Status = Completed]
    UpdateRentalComplete --> ShowReturnSuccess[Show Success]
    ShowReturnSuccess --> ShowDetails

    style Start fill:#90EE90
    style End fill:#FFB6C1
    style ShowStatusSuccess fill:#87CEEB
    style ShowPaymentSuccess fill:#87CEEB
    style ShowReturnSuccess fill:#87CEEB
```

---

## 7. Flowchart - Owner Dashboard & Reports

```mermaid
flowchart TD
    Start([Owner Access System]) --> ValidateLogin[Validate JWT Token]
    ValidateLogin --> CheckRole{Role = Owner?}
    CheckRole -->|No| AccessDenied[Access Denied] --> End([End])
    CheckRole -->|Yes| LoadDashboard[Load Owner Dashboard]
    
    LoadDashboard --> GetRevenue[Get Revenue Statistics]
    GetRevenue --> CalcTotal[Calculate Total Revenue]
    CalcTotal --> CalcMonthly[Calculate Monthly Revenue]
    CalcMonthly --> CalcYearly[Calculate Yearly Revenue]
    
    CalcYearly --> GetRentals[Get Rental Statistics]
    GetRentals --> CountTotal[Count Total Rentals]
    CountTotal --> CountActive[Count Active Rentals]
    CountActive --> CountCompleted[Count Completed Rentals]
    CountCompleted --> CountCancelled[Count Cancelled Rentals]
    
    CountCancelled --> GetCustomers[Get Customer Statistics]
    GetCustomers --> CountCustomers[Count Total Customers]
    CountCustomers --> CountNew[Count New Customers]
    CountNew --> GetTopCustomers[Get Top Customers]
    
    GetTopCustomers --> GetCostumes[Get Costume Performance]
    GetCostumes --> GetPopular[Get Popular Costumes]
    GetPopular --> CalcUtilization[Calculate Utilization Rate]
    CalcUtilization --> GetRevenueByCostume[Get Revenue per Costume]
    
    GetRevenueByCostume --> DisplayDashboard[Display Dashboard]
    DisplayDashboard --> ShowCharts[Show Charts & Graphs]
    ShowCharts --> OwnerAction{Select Action}
    
    OwnerAction -->|View Revenue Report| RevenueReport[Open Revenue Report]
    OwnerAction -->|View Rental Report| RentalReport[Open Rental Report]
    OwnerAction -->|View Customer Report| CustomerReport[Open Customer Report]
    OwnerAction -->|View Analytics| Analytics[Open Analytics]
    OwnerAction -->|Refresh| LoadDashboard
    OwnerAction -->|Logout| Logout[Logout]
    
    RevenueReport --> SelectPeriod[Select Period]
    SelectPeriod --> PeriodType{Period Type?}
    PeriodType -->|Daily| DailyRevenue[Show Daily Revenue]
    PeriodType -->|Monthly| MonthlyRevenue[Show Monthly Revenue]
    PeriodType -->|Yearly| YearlyRevenue[Show Yearly Revenue]
    PeriodType -->|Custom| CustomRange[Select Date Range]
    
    DailyRevenue --> ShowRevenueData[Display Revenue Data]
    MonthlyRevenue --> ShowRevenueData
    YearlyRevenue --> ShowRevenueData
    CustomRange --> ShowRevenueData
    
    ShowRevenueData --> ExportOption{Export Report?}
    ExportOption -->|Yes| SelectFormat[Select Format]
    ExportOption -->|No| OwnerAction
    
    SelectFormat --> FormatType{Format Type?}
    FormatType -->|PDF| ExportPDF[Export to PDF]
    FormatType -->|Excel| ExportExcel[Export to Excel]
    FormatType -->|CSV| ExportCSV[Export to CSV]
    
    ExportPDF --> DownloadFile[Download File]
    ExportExcel --> DownloadFile
    ExportCSV --> DownloadFile
    DownloadFile --> OwnerAction
    
    RentalReport --> ShowRentalData[Display Rental Statistics]
    ShowRentalData --> ShowTrends[Show Rental Trends]
    ShowTrends --> OwnerAction
    
    CustomerReport --> ShowCustomerData[Display Customer Statistics]
    ShowCustomerData --> ShowRetention[Show Customer Retention]
    ShowRetention --> OwnerAction
    
    Analytics --> ShowAnalytics[Display Performance Analytics]
    ShowAnalytics --> ShowMetrics[Show Key Metrics]
    ShowMetrics --> OwnerAction
    
    Logout --> End

    style Start fill:#90EE90
    style End fill:#FFB6C1
    style DisplayDashboard fill:#87CEEB
    style AccessDenied fill:#FFB6C1
    style DownloadFile fill:#87CEEB
```

---

## 8. Flowchart - Midtrans Webhook Processing

```mermaid
flowchart TD
    Start([Midtrans Send Webhook]) --> ReceivePost[Receive POST Request]
    ReceivePost --> ExtractData[Extract Webhook Data]
    ExtractData --> GetOrderID[Get order_id]
    GetOrderID --> GetStatus[Get transaction_status]
    GetStatus --> GetSignature[Get signature_key]
    
    GetSignature --> VerifySignature[Verify Signature]
    VerifySignature --> CalcHash[Calculate Hash]
    CalcHash --> CompareHash{Signature Valid?}
    
    CompareHash -->|No| LogSecurity[Log Security Warning]
    CompareHash -->|Yes| FindPayment[Find Payment by order_id]
    
    LogSecurity --> Return403[Return HTTP 403 Forbidden]
    Return403 --> End([End])
    
    FindPayment --> PaymentFound{Payment Found?}
    PaymentFound -->|No| LogError[Log Error: Payment Not Found]
    PaymentFound -->|Yes| MapStatus[Map Transaction Status]
    
    LogError --> Return404[Return HTTP 404 Not Found]
    Return404 --> End
    
    MapStatus --> StatusType{Transaction Status?}
    StatusType -->|capture/settlement| SetSettlement[Status = Settlement]
    StatusType -->|pending| SetPending[Status = Pending]
    StatusType -->|deny| SetCancel[Status = Cancel]
    StatusType -->|expire| SetExpire[Status = Expire]
    StatusType -->|cancel| SetCancel
    
    SetSettlement --> UpdateDB[Update Payment in DB]
    SetPending --> UpdateDB
    SetCancel --> UpdateDB
    SetExpire --> UpdateDB
    
    UpdateDB --> LogTransaction[Log Transaction Update]
    LogTransaction --> CheckSettlement{Status = Settlement?}
    
    CheckSettlement -->|Yes| NotifyCustomer[Send Notification to Customer]
    CheckSettlement -->|No| LogOnly[Log Status Change]
    
    NotifyCustomer --> UpdateRental[Update Rental if Needed]
    UpdateRental --> Return200[Return HTTP 200 OK]
    LogOnly --> Return200
    
    Return200 --> End

    style Start fill:#90EE90
    style End fill:#FFB6C1
    style Return200 fill:#87CEEB
    style Return403 fill:#FFB6C1
    style Return404 fill:#FFB6C1
    style LogSecurity fill:#FFB6C1
```

---

## 9. Flowchart - Stock Availability Check

```mermaid
flowchart TD
    Start([Check Stock Availability]) --> GetCostume[Get Costume ID]
    GetCostume --> GetDateRange[Get Date Range]
    GetDateRange --> CalcStart[Calculate Start Date]
    CalcStart --> CalcEnd[Calculate End Date]
    
    CalcEnd --> StartDate[Start = Event Date - 3 days]
    StartDate --> EndDate[End = Event Date + Duration]
    
    EndDate --> QueryRentals[Query Active Rentals]
    QueryRentals --> FilterOverlap[Filter Overlapping Dates]
    FilterOverlap --> GetRentalDetails[Get Rental Details]
    
    GetRentalDetails --> CalcReserved[Calculate Reserved Quantity]
    CalcReserved --> SumQuantities[Sum All Quantities]
    SumQuantities --> GetTotalStock[Get Total Stock]
    
    GetTotalStock --> CalcAvailable[Available = Total - Reserved]
    CalcAvailable --> GetRequested[Get Requested Quantity]
    
    GetRequested --> CheckAvailable{Available >= Requested?}
    CheckAvailable -->|Yes| AllowBooking[Allow Booking]
    CheckAvailable -->|No| RejectBooking[Reject Booking]
    
    AllowBooking --> ReturnSuccess[Return Success]
    RejectBooking --> ReturnError[Return Error Message]
    
    ReturnSuccess --> End([End])
    ReturnError --> End

    style Start fill:#90EE90
    style End fill:#FFB6C1
    style AllowBooking fill:#87CEEB
    style RejectBooking fill:#FFB6C1
```

---

## 10. Flowchart - Rental Status Lifecycle

```mermaid
flowchart TD
    Start([Rental Created]) --> StatusPending[Status: PENDING]
    
    StatusPending --> WaitPayment[Wait for Payment]
    WaitPayment --> PaymentCheck{Payment Status?}
    
    PaymentCheck -->|Settlement| AdminActivate[Admin Activates Rental]
    PaymentCheck -->|Expire| AutoCancel[Auto Cancel]
    PaymentCheck -->|Cancel| AutoCancel
    PaymentCheck -->|Pending| CheckDue{Past Due Date?}
    
    CheckDue -->|Yes| AutoExpire[Auto Expire]
    CheckDue -->|No| WaitPayment
    
    AdminActivate --> StatusActive[Status: ACTIVE]
    AutoCancel --> StatusCancelled[Status: CANCELLED]
    AutoExpire --> StatusCancelled
    
    StatusActive --> CustomerPickup[Customer Picks Up H-1]
    CustomerPickup --> CustomerUse[Customer Uses Costume]
    CustomerUse --> CustomerReturn[Customer Returns H+1]
    
    CustomerReturn --> AdminRecord[Admin Records Return]
    AdminRecord --> CheckReturnDate{Return On Time?}
    
    CheckReturnDate -->|Yes| NoLateFee[Late Fee = 0]
    CheckReturnDate -->|No| CalcLateFee[Calculate Late Fee]
    
    CalcLateFee --> LateFeeAmount[Late Fee = Days × Rp 15000]
    LateFeeAmount --> CheckDamage{Has Damage?}
    NoLateFee --> CheckDamage
    
    CheckDamage -->|Yes| AddDamageFee[Add Damage Fee]
    CheckDamage -->|No| NoDamageFee[Damage Fee = 0]
    
    AddDamageFee --> CreateReturn[Create Return Record]
    NoDamageFee --> CreateReturn
    
    CreateReturn --> StatusCompleted[Status: COMPLETED]
    
    StatusActive --> ManualCancel{Admin Cancel?}
    ManualCancel -->|Yes| StatusCancelled
    
    StatusPending --> ManualCancel2{Admin Cancel?}
    ManualCancel2 -->|Yes| StatusCancelled
    
    StatusCompleted --> End([End])
    StatusCancelled --> End

    style Start fill:#90EE90
    style End fill:#FFB6C1
    style StatusPending fill:#FFF59D
    style StatusActive fill:#81D4FA
    style StatusCompleted fill:#A5D6A7
    style StatusCancelled fill:#EF9A9A
```

---

## Summary Flowchart

### Customer Flows (3 flowcharts):
1. Registration & Login
2. Booking Process
3. Payment Process

### Admin Flows (3 flowcharts):
4. Login & Dashboard
5. Manage Costumes
6. Manage Rentals

### Owner Flows (1 flowchart):
7. Dashboard & Reports

### System Flows (3 flowcharts):
8. Midtrans Webhook
9. Stock Availability Check
10. Rental Status Lifecycle

**Total: 10 Flowcharts** yang siap di-paste ke draw.io menggunakan format Mermaid!