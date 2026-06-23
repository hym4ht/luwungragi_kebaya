# Diagram untuk Draw.io - Sistem Rental Kostum Luwungragi

## Cara Menggunakan:
1. Buka https://app.diagrams.net/ (draw.io)
2. Pilih "Arrange" > "Insert" > "Advanced" > "Mermaid" atau "PlantUML"
3. Copy-paste kode diagram di bawah ini
4. Atau gunakan plugin Mermaid/PlantUML di draw.io

---

## 1. Use Case Diagram (PlantUML)

```plantuml
@startuml
left to right direction
skinparam packageStyle rectangle

actor Customer as C
actor Admin as A
actor Owner as O
actor "Midtrans System" as M

rectangle "Sistem Rental Kostum Luwungragi" {
  
  package "Customer Features" {
    usecase "Register & Login" as UC1
    usecase "Browse Catalog" as UC2
    usecase "View Costume Details" as UC3
    usecase "Create Rental Booking" as UC4
    usecase "Check Availability" as UC5
    usecase "Make Payment" as UC6
    usecase "Generate Midtrans Token" as UC7
    usecase "View My Orders" as UC8
    usecase "View Rental Details" as UC9
    usecase "Sync Payment Status" as UC10
  }
  
  package "Admin Features" {
    usecase "Admin Login" as UC11
    usecase "View Dashboard" as UC12
    usecase "Manage Costumes" as UC13
    usecase "Create Costume" as UC14
    usecase "Edit Costume" as UC15
    usecase "Delete Costume" as UC16
    usecase "Manage Customers" as UC17
    usecase "Manage Rentals" as UC18
    usecase "Update Rental Status" as UC19
    usecase "Update Payment Status" as UC20
    usecase "Record Return" as UC21
    usecase "Calculate Damage Fee" as UC22
  }
  
  package "Owner Features" {
    usecase "Owner Login" as UC23
    usecase "View Owner Dashboard" as UC24
    usecase "View Reports" as UC25
  }
  
  package "System Features" {
    usecase "Process Payment" as UC26
    usecase "Send Webhook" as UC27
    usecase "Update Transaction Status" as UC28
  }
}

C --> UC1
C --> UC2
C --> UC4
C --> UC6
C --> UC8
C --> UC9
C --> UC10

UC2 ..> UC3 : <<include>>
UC4 ..> UC5 : <<include>>
UC6 ..> UC7 : <<include>>

A --> UC11
A --> UC12
A --> UC13
A --> UC17
A --> UC18

UC13 ..> UC14 : <<extend>>
UC13 ..> UC15 : <<extend>>
UC13 ..> UC16 : <<extend>>

UC18 ..> UC19 : <<extend>>
UC18 ..> UC20 : <<extend>>
UC18 ..> UC21 : <<extend>>
UC21 ..> UC22 : <<include>>

O --> UC23
O --> UC24
O --> UC25

M --> UC26
M --> UC27
M --> UC28

UC6 ..> UC26 : <<communicate>>
UC27 ..> UC20 : <<trigger>>

note right of UC4
  Business Rules:
  - Booking: H-3 before event
  - Payment Due: H-2 before event
  - Pickup: H-1 before event
  - Return: H+1 after event
  - Duration: 1-5 days
  - Late Fee: Rp 15.000/day
end note

@enduml
```

---

## 2. Sequence Diagram - Customer Booking & Payment (PlantUML)

```plantuml
@startuml
actor Customer as C
participant "System" as S
participant "AvailabilityService" as AS
participant "RentalWorkflowService" as RWS
participant "MidtransService" as MS
participant "Midtrans API" as MA
database "Database" as DB

== Browse Catalog ==
C -> S: Browse Catalog
S -> AS: Get Available Costumes
AS -> DB: Query Costumes & Check Stock
DB --> AS: Costume List
AS --> S: Available Catalog
S --> C: Display Catalog

== Create Booking ==
C -> S: Submit Booking\n(costume_id, event_date, rental_days, quantity)
S -> S: Validate Event Date\n(>= today + 3 days)
S -> AS: Check Stock Availability
AS -> DB: Check Available Stock
DB --> AS: Stock Status
AS --> S: Stock Available

S -> RWS: Create Booking
RWS -> DB: Create Rental (status: pending)
RWS -> DB: Create Rental Details
RWS -> DB: Create Payment (status: pending)
DB --> RWS: Rental Created
RWS --> S: Rental Object
S --> C: Redirect to Rental Details

== Payment Process ==
C -> S: Click Pay Button
S -> MS: Generate Snap Token
MS -> DB: Get Rental Details
DB --> MS: Rental Data
MS -> MA: Create Transaction
MA --> MS: Snap Token
MS --> S: Snap Token
S --> C: Return Token (AJAX)

C -> MA: Open Payment Page
MA --> C: Display Payment Options
C -> MA: Complete Payment
MA -> MA: Process Payment

== Webhook Notification ==
MA -> S: Send Webhook (transaction_status)
S -> MS: Handle Webhook
MS -> MS: Verify Signature
MS -> DB: Update Payment Status (settlement)
DB --> MS: Payment Updated
MS --> S: Success Response
S --> MA: HTTP 200 OK

== Sync Status ==
C -> S: Sync Payment Status
S -> MS: Check Transaction Status
MS -> MA: Get Transaction Status
MA --> MS: Current Status
MS -> DB: Update Payment Status
DB --> S: Updated Status
S --> C: Display Current Status

@enduml
```

---

## 3. Activity Diagram - Customer Booking Process (PlantUML)

```plantuml
@startuml
start

:Customer Browse Catalog;
:Select Costume;
:View Costume Details;
:Fill Booking Form;
note right
  - Event Date
  - Rental Duration (1-5 days)
  - Quantity
end note

:Submit Booking;

if (Event Date >= Today + 3 days?) then (yes)
  if (Stock Available?) then (yes)
    :Calculate Schedule;
    note right
      - Booking Start: Event Date - 3 days
      - Payment Due: Event Date - 2 days
      - Pickup: Event Date - 1 day
      - Return Due: Event Date + rental_days
    end note
    
    :Create Rental Record;
    note right
      Status: Pending
      Generate Invoice Number
      Calculate Total Price
    end note
    
    :Create Rental Details;
    :Create Payment Record;
    note right
      Status: Pending
      Type: Midtrans
    end note
    
    :Redirect to Rental Details Page;
    stop
  else (no)
    :Show Error: Stock Not Available;
    :Back to Form;
    stop
  endif
else (no)
  :Show Error: Minimum H-3 Before Event;
  :Back to Form;
  stop
endif

@enduml
```

---

## 4. Activity Diagram - Admin Manage Rentals (PlantUML)

```plantuml
@startuml
start

:Admin Access Rentals Page;
:View All Rentals List;

repeat
  :Select Action;
  
  if (Action Type?) then (Filter)
    :Apply Filters;
    note right
      - By Status
      - By Payment Status
      - By Search
    end note
    :Display Filtered Results;
    
  elseif (View Details)
    :Display Complete Rental Info;
    note right
      - Customer Info
      - Costume Details
      - Payment Status
      - Rental Status
      - Return Info
    end note
    
  elseif (Update Status)
    if (Current Status?) then (Pending)
      :Change to Active/Cancelled;
    elseif (Active)
      :Change to Completed/Cancelled;
    else (Completed)
      :No Change Allowed;
    endif
    :Update Status in Database;
    :Show Success Message;
    
  elseif (Update Payment)
    if (Payment Status = Pending?) then (yes)
      :Change to Settlement/Expire/Cancel;
      :Update Payment Status;
      :Show Success Message;
    else (no)
      :No Change Allowed;
    endif
    
  else (Record Return)
    :Enter Return Information;
    note right
      - Returned Date
      - Damage Fee (optional)
    end note
    
    :Calculate Late Fee;
    if (Returned Late?) then (yes)
      :Late Fee = Days Late × Rp 15.000;
    else (no)
      :Late Fee = 0;
    endif
    
    :Create Return Record;
    :Update Rental Status to Completed;
    :Show Success Message;
  endif

repeat while (Continue?) is (yes)
->no;

:Logout;
stop

@enduml
```

---

## 5. State Diagram - Rental Status Lifecycle (PlantUML)

```plantuml
@startuml
[*] --> Pending : Customer Creates Booking

Pending --> Active : Admin Activates\n(After Payment Confirmed)
Pending --> Cancelled : Admin/Customer Cancels
Pending --> Expired : Payment Timeout

Active --> Completed : Admin Records Return
Active --> Cancelled : Admin Cancels

Completed --> [*]
Cancelled --> [*]
Expired --> [*]

note right of Pending
  Customer makes payment
  Payment due: H-2 before event
end note

note right of Active
  Customer picks up: H-1
  Customer uses costume
  Customer returns: H+1
end note

note right of Completed
  Return recorded
  Late fee calculated if applicable
  (Rp 15.000/day)
end note

@enduml
```

---

## 6. State Diagram - Payment Status Lifecycle (PlantUML)

```plantuml
@startuml
[*] --> Pending : Payment Created

Pending --> Settlement : Payment Success\n(via Midtrans)
Pending --> Expire : Payment Timeout
Pending --> Cancel : Payment Cancelled

Settlement --> [*]
Expire --> [*]
Cancel --> [*]

note right of Pending
  Waiting for customer payment
  Midtrans Snap Token generated
end note

note right of Settlement
  Payment confirmed (Lunas)
  Webhook received from Midtrans
  Rental can be activated
end note

note right of Expire
  Payment timeout (Kedaluwarsa)
  Customer didn't complete payment
end note

note right of Cancel
  Payment cancelled (Dibatalkan)
  By customer or system
end note

@enduml
```

---

## 7. Class Diagram - Main Entities (PlantUML)

```plantuml
@startuml
class User {
  +id: int
  +name: string
  +email: string
  +password: string
  +role: UserRole
  --
  +hasRole(roles): bool
}

enum UserRole {
  ADMIN
  CUSTOMER
  OWNER
}

class Costume {
  +id: int
  +name: string
  +description: text
  +price_per_day: decimal
  +stock: int
  +size: string
  +category: string
  +main_image: string
  +gallery_images: json
}

class Rental {
  +id: int
  +user_id: int
  +invoice_number: string
  +event_date: date
  +rental_date: date
  +return_date: date
  +total_price: decimal
  +status: RentalStatus
  --
  +BOOKING_BUFFER_DAYS: 3
  +PAYMENT_BUFFER_DAYS: 2
  +PICKUP_BUFFER_DAYS: 1
  +MIN_RENTAL_DAYS: 1
  +MAX_RENTAL_DAYS: 5
  +LATE_FEE_PER_DAY: 15000
}

enum RentalStatus {
  PENDING
  ACTIVE
  COMPLETED
  CANCELLED
}

class RentalDetail {
  +id: int
  +rental_id: int
  +costume_id: int
  +quantity: int
  +price_per_day: decimal
  +subtotal: decimal
}

class Payment {
  +id: int
  +rental_id: int
  +amount: decimal
  +status: PaymentStatus
  +type: string
  +midtrans_order_id: string
  +midtrans_snap_token: string
  +paid_at: timestamp
}

enum PaymentStatus {
  PENDING
  SETTLEMENT
  EXPIRE
  CANCEL
}

class RentalReturn {
  +id: int
  +rental_id: int
  +returned_date: date
  +damage_fee: decimal
  +late_fee: decimal
  +notes: text
}

User "1" -- "0..*" Rental : creates
User -- UserRole

Rental "1" -- "1..*" RentalDetail : contains
Rental "1" -- "1" Payment : has
Rental "1" -- "0..1" RentalReturn : has
Rental -- RentalStatus

RentalDetail "*" -- "1" Costume : references

Payment -- PaymentStatus

@enduml
```

---

## 8. Component Diagram - System Architecture (PlantUML)

```plantuml
@startuml
package "Frontend Layer" {
  [Blade Templates]
  [JavaScript/Alpine.js]
  [CSS/Tailwind]
}

package "Application Layer" {
  [Controllers]
  [Middleware]
  [Requests/Validation]
}

package "Business Logic Layer" {
  [AvailabilityService]
  [RentalWorkflowService]
  [MidtransService]
  [ReportService]
  [JwtService]
}

package "Data Layer" {
  [Models]
  [Database]
}

package "External Services" {
  [Midtrans API]
}

[Blade Templates] --> [Controllers]
[JavaScript/Alpine.js] --> [Controllers]

[Controllers] --> [Middleware]
[Controllers] --> [Business Logic Layer]

[AvailabilityService] --> [Models]
[RentalWorkflowService] --> [Models]
[MidtransService] --> [Models]
[MidtransService] --> [Midtrans API]
[ReportService] --> [Models]
[JwtService] --> [Models]

[Models] --> [Database]

note right of [Middleware]
  - JWT Authentication
  - Role-based Access Control
  - CSRF Protection
end note

note right of [Business Logic Layer]
  Core business logic:
  - Stock availability checking
  - Rental workflow management
  - Payment processing
  - Report generation
end note

@enduml
```

---

## 9. Deployment Diagram (PlantUML)

```plantuml
@startuml
node "Client Browser" {
  [Web Browser]
}

node "Web Server" {
  [Apache/Nginx]
  [PHP-FPM]
}

node "Application Server" {
  [Laravel Application]
  [JWT Service]
  [Business Services]
}

database "MySQL Database" {
  [Users]
  [Costumes]
  [Rentals]
  [Payments]
}

cloud "External Services" {
  [Midtrans Payment Gateway]
}

node "File Storage" {
  [Costume Images]
  [Documents]
}

[Web Browser] --> [Apache/Nginx] : HTTPS
[Apache/Nginx] --> [PHP-FPM]
[PHP-FPM] --> [Laravel Application]
[Laravel Application] --> [MySQL Database]
[Laravel Application] --> [File Storage]
[Laravel Application] --> [Midtrans Payment Gateway] : API Calls
[Midtrans Payment Gateway] --> [Laravel Application] : Webhooks

@enduml
```

---

## 10. ERD - Entity Relationship Diagram (Mermaid)

```mermaid
erDiagram
    USERS ||--o{ RENTALS : creates
    RENTALS ||--|{ RENTAL_DETAILS : contains
    RENTALS ||--|| PAYMENTS : has
    RENTALS ||--o| RENTAL_RETURNS : has
    COSTUMES ||--o{ RENTAL_DETAILS : referenced_in
    
    USERS {
        int id PK
        string name
        string email UK
        string password
        enum role
        timestamp created_at
    }
    
    COSTUMES {
        int id PK
        string name
        text description
        decimal price_per_day
        int stock
        string size
        string category
        string main_image
        json gallery_images
        timestamp created_at
    }
    
    RENTALS {
        int id PK
        int user_id FK
        string invoice_number UK
        date event_date
        date rental_date
        date return_date
        decimal total_price
        enum status
        timestamp created_at
    }
    
    RENTAL_DETAILS {
        int id PK
        int rental_id FK
        int costume_id FK
        int quantity
        decimal price_per_day
        decimal subtotal
    }
    
    PAYMENTS {
        int id PK
        int rental_id FK
        decimal amount
        enum status
        string type
        string midtrans_order_id UK
        string midtrans_snap_token
        timestamp paid_at
        timestamp created_at
    }
    
    RENTAL_RETURNS {
        int id PK
        int rental_id FK
        date returned_date
        decimal damage_fee
        decimal late_fee
        text notes
        timestamp created_at
    }
```

---

## Cara Import ke Draw.io:

### Untuk PlantUML:
1. Buka draw.io
2. Klik menu "Arrange" → "Insert" → "Advanced" → "PlantUML..."
3. Paste kode PlantUML
4. Klik "Insert"

### Untuk Mermaid:
1. Buka draw.io
2. Klik menu "Arrange" → "Insert" → "Advanced" → "Mermaid..."
3. Paste kode Mermaid
4. Klik "Insert"

### Alternatif (Manual Import):
1. Buka https://plantuml.com/plantuml (untuk PlantUML)
2. Atau https://mermaid.live (untuk Mermaid)
3. Generate diagram
4. Download sebagai PNG/SVG
5. Import ke draw.io

### Tips:
- Gunakan PlantUML untuk diagram yang lebih kompleks
- Gunakan Mermaid untuk diagram yang lebih sederhana
- Sesuaikan styling setelah import ke draw.io
- Simpan dalam format .drawio untuk editing lebih lanjut