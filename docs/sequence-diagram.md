# Sequence Diagram - Sistem Rental Kostum Luwungragi

## 1. Customer Registration & Login Flow

```
Customer -> System: Access Register Page
System -> Customer: Display Registration Form
Customer -> System: Submit Registration (name, email, password)
System -> Database: Create User (role: customer)
Database -> System: User Created
System -> Customer: Redirect to Login

Customer -> System: Submit Login (email, password)
System -> JwtService: Validate Credentials
JwtService -> Database: Check User
Database -> JwtService: User Data
JwtService -> System: Generate JWT Token
System -> Customer: Set JWT Cookie & Redirect to Dashboard
```

## 2. Browse Catalog & Create Rental Booking Flow

```
Customer -> System: Browse Catalog
System -> AvailabilityService: Get Available Costumes
AvailabilityService -> Database: Query Costumes & Check Stock
Database -> AvailabilityService: Costume List with Availability
AvailabilityService -> System: Available Catalog
System -> Customer: Display Catalog

Customer -> System: View Costume Details
System -> Database: Get Costume Info
Database -> System: Costume Details
System -> Customer: Display Details & Booking Form

Customer -> System: Submit Booking (costume_id, event_date, rental_days, quantity)
System -> System: Validate (event_date >= now + 3 days)
System -> AvailabilityService: Check Stock Availability
AvailabilityService -> Database: Check Available Stock for Date Range
Database -> AvailabilityService: Stock Status
AvailabilityService -> System: Stock Available/Unavailable

alt Stock Available
    System -> RentalWorkflowService: Create Booking
    RentalWorkflowService -> Database: Create Rental (status: pending)
    RentalWorkflowService -> Database: Create Rental Details
    RentalWorkflowService -> Database: Create Payment (status: pending)
    Database -> RentalWorkflowService: Rental Created
    RentalWorkflowService -> System: Rental Object
    System -> Customer: Redirect to Rental Details Page
else Stock Unavailable
    System -> Customer: Error - Stock Not Available
end
```

## 3. Payment Flow (Midtrans Integration)

```
Customer -> System: View Rental Details
System -> Database: Get Rental & Payment Info
Database -> System: Rental Data (status: pending, payment: pending)
System -> Customer: Display Payment Button

Customer -> System: Click Pay Button
System -> MidtransService: Generate Snap Token (rental_id)
MidtransService -> Database: Get Rental & Payment Details
Database -> MidtransService: Rental Data
MidtransService -> Midtrans API: Create Transaction
Midtrans API -> MidtransService: Snap Token
MidtransService -> System: Snap Token
System -> Customer: Return Snap Token (AJAX)

Customer -> Midtrans: Open Payment Page (Snap Token)
Midtrans -> Customer: Display Payment Options
Customer -> Midtrans: Complete Payment
Midtrans -> Midtrans: Process Payment

Midtrans -> System: Send Webhook (transaction_status)
System -> MidtransService: Handle Webhook
MidtransService -> Database: Update Payment Status
alt Payment Success (settlement)
    Database -> MidtransService: Payment Updated (settlement)
    MidtransService -> System: Success Response
else Payment Failed/Expire
    Database -> MidtransService: Payment Updated (expire/cancel)
    MidtransService -> System: Failed Response
end

Customer -> System: Sync Payment Status (manual check)
System -> MidtransService: Check Transaction Status
MidtransService -> Midtrans API: Get Transaction Status
Midtrans API -> MidtransService: Current Status
MidtransService -> Database: Update Payment Status
Database -> System: Updated Status
System -> Customer: Display Current Status
```

## 4. Admin Manage Rentals Flow

```
Admin -> System: Access Rentals Page
System -> Database: Get All Rentals (with filters)
Database -> System: Rental List
System -> Admin: Display Rentals Table

Admin -> System: View Rental Details
System -> Database: Get Rental (with user, details, payment, return)
Database -> System: Complete Rental Data
System -> Admin: Display Rental Details Modal

Admin -> System: Update Rental Status (pending → active)
System -> Database: Update Rental Status
Database -> System: Status Updated
System -> Admin: Success Message

Admin -> System: Update Payment Status (pending → settlement)
System -> RentalWorkflowService: Update Payment Status
RentalWorkflowService -> Database: Update Payment
Database -> RentalWorkflowService: Payment Updated
RentalWorkflowService -> System: Success
System -> Admin: Success Message

Admin -> System: Record Return (returned_date, damage_fee)
System -> RentalWorkflowService: Record Return
RentalWorkflowService -> Database: Create Return Record
RentalWorkflowService -> Database: Update Rental Status (completed)
alt Late Return
    RentalWorkflowService -> RentalWorkflowService: Calculate Late Fee
    RentalWorkflowService -> Database: Add Late Fee to Return
end
Database -> RentalWorkflowService: Return Recorded
RentalWorkflowService -> System: Success
System -> Admin: Success Message
```

## 5. Admin Manage Costumes Flow

```
Admin -> System: Access Costumes Page
System -> Database: Get All Costumes
Database -> System: Costume List
System -> Admin: Display Costumes Table

Admin -> System: Create New Costume
System -> Admin: Display Create Form
Admin -> System: Submit Costume Data (name, price, stock, images, etc)
System -> Database: Create Costume
Database -> System: Costume Created
System -> Admin: Success & Redirect

Admin -> System: Edit Costume
System -> Database: Get Costume Details
Database -> System: Costume Data
System -> Admin: Display Edit Form
Admin -> System: Submit Updated Data
System -> Database: Update Costume
Database -> System: Costume Updated
System -> Admin: Success Message

Admin -> System: Delete Costume
System -> Database: Delete Costume
Database -> System: Costume Deleted
System -> Admin: Success Message
```

## 6. Owner View Reports Flow

```
Owner -> System: Access Dashboard
System -> ReportService: Get Dashboard Statistics
ReportService -> Database: Query Revenue Data
ReportService -> Database: Query Rental Statistics
ReportService -> Database: Query Customer Data
Database -> ReportService: Aggregated Data
ReportService -> System: Statistics Object
System -> Owner: Display Dashboard with Charts

Owner -> System: Access Reports Page
System -> ReportService: Get Detailed Reports
ReportService -> Database: Query Report Data (with filters)
Database -> ReportService: Report Data
ReportService -> System: Report Object
System -> Owner: Display Reports
```

## 7. Midtrans Webhook Flow

```
Midtrans -> System: POST /payment/webhook/midtrans
System -> MidtransController: Handle Webhook
MidtransController -> MidtransService: Verify Signature
MidtransService -> MidtransService: Validate Signature Key
alt Valid Signature
    MidtransService -> Database: Find Payment by order_id
    Database -> MidtransService: Payment Record
    MidtransService -> MidtransService: Map Transaction Status
    MidtransService -> Database: Update Payment Status
    Database -> MidtransService: Payment Updated
    MidtransService -> MidtransController: Success
    MidtransController -> Midtrans: HTTP 200 OK
else Invalid Signature
    MidtransService -> MidtransController: Invalid Signature
    MidtransController -> Midtrans: HTTP 403 Forbidden
end
```

## Key Timing Rules

### Rental Timeline
```
Day 0: Customer creates booking (event_date must be >= now + 3 days)
Day X-3: Booking Start Date (earliest booking time)
Day X-2: Payment Due Date (latest payment time)
Day X-1: Pickup Date (customer picks up costume offline)
Day X: Event Date (usage start)
Day X+N: Usage End Date (N = rental_days - 1)
Day X+N+1: Return Due Date (customer must return)
Day X+N+2+: Late return (Rp 15.000/day penalty)
```

### Status Transitions
```
Rental Status:
- Created: pending
- After payment & pickup: active
- After return: completed
- Anytime: cancelled

Payment Status:
- Created: pending
- After successful payment: settlement
- After timeout: expire
- After cancellation: cancel
```