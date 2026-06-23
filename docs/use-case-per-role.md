# Use Case Diagram Per Role - Sistem Rental Kostum Luwungragi

## Cara Menggunakan di Draw.io:
1. Buka https://app.diagrams.net/
2. Pilih "Arrange" > "Insert" > "Advanced" > "PlantUML"
3. Copy-paste kode diagram di bawah
4. Klik "Insert"

---

## 1. Use Case Diagram - Customer Role

```plantuml
@startuml
left to right direction
skinparam packageStyle rectangle

actor Customer as C
actor "Midtrans System" as M

rectangle "Sistem Rental Kostum - Customer Features" {
  
  package "Authentication" {
    usecase "Register" as UC1
    usecase "Login" as UC2
    usecase "Logout" as UC3
  }
  
  package "Browse & Search" {
    usecase "Browse Catalog" as UC4
    usecase "View Costume Details" as UC5
    usecase "Search Costumes" as UC6
    usecase "Filter by Category" as UC7
  }
  
  package "Booking Management" {
    usecase "Create Rental Booking" as UC8
    usecase "Select Event Date" as UC9
    usecase "Choose Rental Duration" as UC10
    usecase "Check Stock Availability" as UC11
    usecase "Calculate Total Price" as UC12
  }
  
  package "Payment" {
    usecase "View Payment Details" as UC13
    usecase "Generate Midtrans Token" as UC14
    usecase "Pay via Midtrans" as UC15
    usecase "Sync Payment Status" as UC16
  }
  
  package "Order Tracking" {
    usecase "View My Orders" as UC17
    usecase "View Rental Details" as UC18
    usecase "Check Rental Status" as UC19
    usecase "Check Payment Status" as UC20
    usecase "View Return Information" as UC21
  }
}

' Customer connections
C --> UC1
C --> UC2
C --> UC3
C --> UC4
C --> UC6
C --> UC8
C --> UC13
C --> UC17
C --> UC18

' Include relationships
UC4 ..> UC5 : <<include>>
UC4 ..> UC7 : <<extend>>
UC6 ..> UC5 : <<include>>

UC8 ..> UC9 : <<include>>
UC8 ..> UC10 : <<include>>
UC8 ..> UC11 : <<include>>
UC8 ..> UC12 : <<include>>

UC13 ..> UC14 : <<include>>
UC14 ..> UC15 : <<include>>
UC13 ..> UC16 : <<extend>>

UC18 ..> UC19 : <<include>>
UC18 ..> UC20 : <<include>>
UC18 ..> UC21 : <<extend>>

' Midtrans interaction
UC15 ..> M : <<communicate>>

note right of UC8
  **Booking Rules:**
  • Event date: min H-3
  • Duration: 1-5 days
  • Check stock availability
  • Generate invoice
end note

note right of UC15
  **Payment Rules:**
  • Payment due: H-2 before event
  • Pickup: H-1 before event
  • Multiple payment methods
  • Real-time status update
end note

@enduml
```

---

## 2. Use Case Diagram - Admin Role

```plantuml
@startuml
left to right direction
skinparam packageStyle rectangle

actor Admin as A

rectangle "Sistem Rental Kostum - Admin Features" {
  
  package "Authentication" {
    usecase "Admin Login" as UC1
    usecase "Admin Logout" as UC2
  }
  
  package "Dashboard" {
    usecase "View Dashboard" as UC3
    usecase "View Statistics" as UC4
    usecase "View Recent Activities" as UC5
    usecase "View Revenue Summary" as UC6
  }
  
  package "Costume Management" {
    usecase "View Costume List" as UC7
    usecase "Create New Costume" as UC8
    usecase "Edit Costume" as UC9
    usecase "Delete Costume" as UC10
    usecase "Upload Costume Images" as UC11
    usecase "Manage Stock" as UC12
    usecase "Set Pricing" as UC13
  }
  
  package "Customer Management" {
    usecase "View Customer List" as UC14
    usecase "View Customer Details" as UC15
    usecase "View Customer History" as UC16
  }
  
  package "Rental Management" {
    usecase "View All Rentals" as UC17
    usecase "Filter Rentals" as UC18
    usecase "Search Rentals" as UC19
    usecase "View Rental Details" as UC20
    usecase "Update Rental Status" as UC21
    usecase "Activate Rental" as UC22
    usecase "Complete Rental" as UC23
    usecase "Cancel Rental" as UC24
  }
  
  package "Payment Management" {
    usecase "View Payment Details" as UC25
    usecase "Update Payment Status" as UC26
    usecase "Confirm Payment" as UC27
    usecase "Mark as Expired" as UC28
  }
  
  package "Return Management" {
    usecase "Record Return" as UC29
    usecase "Set Return Date" as UC30
    usecase "Calculate Late Fee" as UC31
    usecase "Add Damage Fee" as UC32
    usecase "Complete Return Process" as UC33
  }
}

' Admin connections
A --> UC1
A --> UC2
A --> UC3
A --> UC7
A --> UC14
A --> UC17

' Dashboard includes
UC3 ..> UC4 : <<include>>
UC3 ..> UC5 : <<include>>
UC3 ..> UC6 : <<include>>

' Costume management
UC7 ..> UC8 : <<extend>>
UC7 ..> UC9 : <<extend>>
UC7 ..> UC10 : <<extend>>
UC8 ..> UC11 : <<include>>
UC8 ..> UC12 : <<include>>
UC8 ..> UC13 : <<include>>
UC9 ..> UC11 : <<include>>
UC9 ..> UC12 : <<include>>

' Customer management
UC14 ..> UC15 : <<extend>>
UC15 ..> UC16 : <<include>>

' Rental management
UC17 ..> UC18 : <<extend>>
UC17 ..> UC19 : <<extend>>
UC17 ..> UC20 : <<extend>>
UC20 ..> UC21 : <<extend>>
UC21 ..> UC22 : <<extend>>
UC21 ..> UC23 : <<extend>>
UC21 ..> UC24 : <<extend>>

' Payment management
UC20 ..> UC25 : <<include>>
UC25 ..> UC26 : <<extend>>
UC26 ..> UC27 : <<extend>>
UC26 ..> UC28 : <<extend>>

' Return management
UC20 ..> UC29 : <<extend>>
UC29 ..> UC30 : <<include>>
UC29 ..> UC31 : <<include>>
UC29 ..> UC32 : <<extend>>
UC29 ..> UC33 : <<include>>

note right of UC21
  **Status Transitions:**
  • Pending → Active
  • Active → Completed
  • Any → Cancelled
end note

note right of UC29
  **Return Rules:**
  • Record actual return date
  • Late fee: Rp 15.000/day
  • Optional damage fee
  • Auto-complete rental
end note

@enduml
```

---

## 3. Use Case Diagram - Owner Role

```plantuml
@startuml
left to right direction
skinparam packageStyle rectangle

actor Owner as O

rectangle "Sistem Rental Kostum - Owner Features" {
  
  package "Authentication" {
    usecase "Owner Login" as UC1
    usecase "Owner Logout" as UC2
  }
  
  package "Dashboard" {
    usecase "View Owner Dashboard" as UC3
    usecase "View Revenue Statistics" as UC4
    usecase "View Rental Statistics" as UC5
    usecase "View Customer Statistics" as UC6
    usecase "View Performance Metrics" as UC7
  }
  
  package "Revenue Reports" {
    usecase "View Revenue Reports" as UC8
    usecase "Filter by Date Range" as UC9
    usecase "View Daily Revenue" as UC10
    usecase "View Monthly Revenue" as UC11
    usecase "View Yearly Revenue" as UC12
    usecase "View Revenue by Costume" as UC13
    usecase "Export Revenue Report" as UC14
  }
  
  package "Rental Reports" {
    usecase "View Rental Reports" as UC15
    usecase "View Total Rentals" as UC16
    usecase "View Active Rentals" as UC17
    usecase "View Completed Rentals" as UC18
    usecase "View Cancelled Rentals" as UC19
    usecase "View Rental Trends" as UC20
    usecase "Export Rental Report" as UC21
  }
  
  package "Customer Reports" {
    usecase "View Customer Reports" as UC22
    usecase "View Total Customers" as UC23
    usecase "View New Customers" as UC24
    usecase "View Top Customers" as UC25
    usecase "View Customer Retention" as UC26
    usecase "Export Customer Report" as UC27
  }
  
  package "Costume Performance" {
    usecase "View Costume Analytics" as UC28
    usecase "View Popular Costumes" as UC29
    usecase "View Costume Utilization" as UC30
    usecase "View Stock Status" as UC31
    usecase "View Revenue per Costume" as UC32
  }
  
  package "Financial Analysis" {
    usecase "View Profit Analysis" as UC33
    usecase "View Payment Status Summary" as UC34
    usecase "View Late Fee Collection" as UC35
    usecase "View Damage Fee Collection" as UC36
  }
}

' Owner connections
O --> UC1
O --> UC2
O --> UC3
O --> UC8
O --> UC15
O --> UC22
O --> UC28
O --> UC33

' Dashboard includes
UC3 ..> UC4 : <<include>>
UC3 ..> UC5 : <<include>>
UC3 ..> UC6 : <<include>>
UC3 ..> UC7 : <<include>>

' Revenue reports
UC8 ..> UC9 : <<extend>>
UC8 ..> UC10 : <<extend>>
UC8 ..> UC11 : <<extend>>
UC8 ..> UC12 : <<extend>>
UC8 ..> UC13 : <<extend>>
UC8 ..> UC14 : <<extend>>

' Rental reports
UC15 ..> UC16 : <<include>>
UC15 ..> UC17 : <<include>>
UC15 ..> UC18 : <<include>>
UC15 ..> UC19 : <<include>>
UC15 ..> UC20 : <<extend>>
UC15 ..> UC21 : <<extend>>

' Customer reports
UC22 ..> UC23 : <<include>>
UC22 ..> UC24 : <<include>>
UC22 ..> UC25 : <<extend>>
UC22 ..> UC26 : <<extend>>
UC22 ..> UC27 : <<extend>>

' Costume analytics
UC28 ..> UC29 : <<include>>
UC28 ..> UC30 : <<include>>
UC28 ..> UC31 : <<include>>
UC28 ..> UC32 : <<include>>

' Financial analysis
UC33 ..> UC34 : <<include>>
UC33 ..> UC35 : <<include>>
UC33 ..> UC36 : <<include>>

note right of UC8
  **Revenue Insights:**
  • Total revenue
  • Revenue trends
  • Revenue by period
  • Revenue by costume
  • Export to PDF/Excel
end note

note right of UC28
  **Performance Metrics:**
  • Most rented costumes
  • Utilization rate
  • Stock turnover
  • Revenue per costume
end note

@enduml
```

---

## 4. Use Case Diagram - System Integration (Midtrans)

```plantuml
@startuml
left to right direction
skinparam packageStyle rectangle

actor "Midtrans System" as M
actor "System Admin" as SA

rectangle "Payment Integration - Midtrans" {
  
  package "Transaction Processing" {
    usecase "Create Transaction" as UC1
    usecase "Generate Snap Token" as UC2
    usecase "Process Payment" as UC3
    usecase "Validate Payment" as UC4
  }
  
  package "Webhook Handling" {
    usecase "Send Webhook Notification" as UC5
    usecase "Verify Signature" as UC6
    usecase "Update Transaction Status" as UC7
    usecase "Log Transaction" as UC8
  }
  
  package "Status Management" {
    usecase "Check Transaction Status" as UC9
    usecase "Sync Payment Status" as UC10
    usecase "Handle Settlement" as UC11
    usecase "Handle Expiration" as UC12
    usecase "Handle Cancellation" as UC13
  }
  
  package "Notification" {
    usecase "Send Payment Confirmation" as UC14
    usecase "Send Payment Reminder" as UC15
    usecase "Send Expiration Notice" as UC16
  }
}

' Midtrans connections
M --> UC1
M --> UC3
M --> UC5
M --> UC9

' System Admin monitoring
SA --> UC9
SA --> UC10

' Transaction flow
UC1 ..> UC2 : <<include>>
UC3 ..> UC4 : <<include>>

' Webhook flow
UC5 ..> UC6 : <<include>>
UC6 ..> UC7 : <<include>>
UC7 ..> UC8 : <<include>>

' Status handling
UC7 ..> UC11 : <<extend>>
UC7 ..> UC12 : <<extend>>
UC7 ..> UC13 : <<extend>>

' Notifications
UC11 ..> UC14 : <<include>>
UC12 ..> UC16 : <<include>>

note right of UC5
  **Webhook Events:**
  • transaction.success
  • transaction.pending
  • transaction.expire
  • transaction.cancel
end note

note right of UC6
  **Security:**
  • Signature verification
  • Server key validation
  • IP whitelist check
  • Request logging
end note

@enduml
```

---

## 5. Use Case Diagram - Complete System Overview

```plantuml
@startuml
left to right direction
skinparam packageStyle rectangle

actor Customer as C
actor Admin as A
actor Owner as O
actor "Midtrans" as M

rectangle "Sistem Rental Kostum Luwungragi" {
  
  package "Customer Module" {
    usecase "Browse & Book" as UC1
    usecase "Make Payment" as UC2
    usecase "Track Orders" as UC3
  }
  
  package "Admin Module" {
    usecase "Manage Costumes" as UC4
    usecase "Manage Rentals" as UC5
    usecase "Manage Payments" as UC6
    usecase "Process Returns" as UC7
  }
  
  package "Owner Module" {
    usecase "View Reports" as UC8
    usecase "Analyze Performance" as UC9
  }
  
  package "Payment Module" {
    usecase "Process Transactions" as UC10
    usecase "Handle Webhooks" as UC11
  }
}

' Actor connections
C --> UC1
C --> UC2
C --> UC3

A --> UC4
A --> UC5
A --> UC6
A --> UC7

O --> UC8
O --> UC9

M --> UC10
M --> UC11

' Module interactions
UC2 ..> UC10 : <<use>>
UC11 ..> UC6 : <<update>>
UC5 ..> UC8 : <<provide data>>
UC6 ..> UC8 : <<provide data>>
UC7 ..> UC8 : <<provide data>>

note bottom of "Sistem Rental Kostum Luwungragi"
  **System Rules:**
  • Booking: H-3 before event
  • Payment: H-2 before event
  • Pickup: H-1 before event
  • Return: H+1 after event
  • Duration: 1-5 days
  • Late Fee: Rp 15.000/day
end note

@enduml
```

---

## Summary

### Customer (Penyewa)
- **Main Focus**: Browse, book, pay, track orders
- **Key Features**: 17 use cases
- **Integration**: Midtrans payment

### Admin
- **Main Focus**: Manage costumes, rentals, payments, returns
- **Key Features**: 33 use cases
- **Responsibilities**: Full operational control

### Owner
- **Main Focus**: View reports and analytics
- **Key Features**: 36 use cases
- **Insights**: Revenue, rentals, customers, performance

### System Integration
- **Midtrans**: Payment processing and webhooks
- **Security**: Signature verification
- **Real-time**: Status synchronization