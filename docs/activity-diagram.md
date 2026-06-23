# Activity Diagram - Sistem Rental Kostum Luwungragi

## 1. Customer Rental Booking Process

```
[START]
    |
    v
[Customer Browse Catalog]
    |
    v
[Select Costume]
    |
    v
[View Costume Details]
    |
    v
[Fill Booking Form]
    ├── Enter Event Date (min: today + 3 days)
    ├── Select Rental Duration (1-5 days)
    └── Enter Quantity
    |
    v
[Submit Booking]
    |
    v
<Validate Event Date>
    ├── [NO: Event Date < Today + 3 days] → [Show Error] → [Back to Form]
    └── [YES: Valid Date]
        |
        v
    <Check Stock Availability>
        ├── [NO: Stock Not Available] → [Show Error] → [Back to Form]
        └── [YES: Stock Available]
            |
            v
        [Calculate Schedule]
            ├── Booking Start Date = Event Date - 3 days
            ├── Payment Due Date = Event Date - 2 days
            ├── Pickup Date = Event Date - 1 day
            ├── Usage End Date = Event Date + (rental_days - 1)
            └── Return Due Date = Usage End Date + 1 day
            |
            v
        [Create Rental Record]
            ├── Status: Pending
            ├── Generate Invoice Number
            └── Calculate Total Price
            |
            v
        [Create Rental Details]
            ├── Link Costume
            ├── Set Quantity
            └── Set Price per Day
            |
            v
        [Create Payment Record]
            ├── Status: Pending
            ├── Amount: Total Price
            └── Type: Midtrans
            |
            v
        [Redirect to Rental Details Page]
            |
            v
        [END]
```

## 2. Customer Payment Process

```
[START: Customer on Rental Details Page]
    |
    v
<Check Payment Status>
    ├── [Settlement] → [Show Payment Success] → [END]
    ├── [Expire/Cancel] → [Show Payment Failed] → [END]
    └── [Pending]
        |
        v
    [Click Pay Button]
        |
        v
    [Request Midtrans Snap Token]
        |
        v
    [System Generate Token]
        ├── Get Rental Details
        ├── Prepare Transaction Data
        └── Call Midtrans API
        |
        v
    [Receive Snap Token]
        |
        v
    [Open Midtrans Payment Page]
        |
        v
    [Customer Choose Payment Method]
        ├── Bank Transfer
        ├── E-Wallet (GoPay, OVO, etc)
        ├── Credit Card
        └── Others
        |
        v
    [Customer Complete Payment]
        |
        v
    [Midtrans Process Payment]
        |
        v
    <Payment Result>
        ├── [SUCCESS]
        │   |
        │   v
        │   [Midtrans Send Webhook]
        │   |
        │   v
        │   [System Update Payment Status: Settlement]
        │   |
        │   v
        │   [Customer See Success Message]
        │   |
        │   v
        │   [END]
        │
        └── [FAILED/PENDING]
            |
            v
            [Customer Can Retry or Sync Status]
            |
            v
            <Customer Action>
                ├── [Retry Payment] → [Back to Payment Method Selection]
                ├── [Sync Status] → [Check Midtrans API] → [Update Status]
                └── [Cancel] → [END]
```

## 3. Admin Manage Rental Process

```
[START: Admin Access Rentals Page]
    |
    v
[View All Rentals List]
    |
    v
<Admin Action>
    |
    ├── [Filter Rentals]
    │   ├── By Status (pending/active/completed/cancelled)
    │   ├── By Payment Status
    │   └── By Search (invoice/customer name)
    │   |
    │   v
    │   [Display Filtered Results] → [Back to Admin Action]
    │
    ├── [View Rental Details]
    │   |
    │   v
    │   [Display Complete Rental Info]
    │       ├── Customer Info
    │       ├── Costume Details
    │       ├── Payment Status
    │       ├── Rental Status
    │       └── Return Info (if exists)
    │   |
    │   v
    │   [Back to Admin Action]
    │
    ├── [Update Rental Status]
    │   |
    │   v
    │   <Current Status>
    │       ├── [Pending] → [Change to Active/Cancelled]
    │       ├── [Active] → [Change to Completed/Cancelled]
    │       └── [Completed] → [No Change Allowed]
    │   |
    │   v
    │   [Update Status in Database]
    │   |
    │   v
    │   [Show Success Message] → [Back to Admin Action]
    │
    ├── [Update Payment Status]
    │   |
    │   v
    │   <Current Payment Status>
    │       ├── [Pending] → [Change to Settlement/Expire/Cancel]
    │       └── [Settlement/Expire/Cancel] → [No Change Allowed]
    │   |
    │   v
    │   [Update Payment Status in Database]
    │   |
    │   v
    │   [Show Success Message] → [Back to Admin Action]
    │
    └── [Record Return]
        |
        v
        [Enter Return Information]
            ├── Returned Date
            └── Damage Fee (optional)
        |
        v
        [Calculate Late Fee]
            |
            v
        <Check Return Date>
            ├── [On Time or Early] → Late Fee = 0
            └── [Late]
                |
                v
                [Calculate Days Late]
                |
                v
                [Late Fee = Days Late × Rp 15.000]
        |
        v
        [Create Return Record]
            ├── Returned Date
            ├── Damage Fee
            └── Late Fee
        |
        v
        [Update Rental Status to Completed]
        |
        v
        [Show Success Message] → [Back to Admin Action]
    |
    v
[Logout or Continue]
    |
    v
[END]
```

## 4. Admin Manage Costumes Process

```
[START: Admin Access Costumes Page]
    |
    v
[View All Costumes List]
    |
    v
<Admin Action>
    |
    ├── [Create New Costume]
    │   |
    │   v
    │   [Fill Costume Form]
    │       ├── Name
    │       ├── Description
    │       ├── Price per Day
    │       ├── Stock
    │       ├── Size
    │       ├── Category
    │       ├── Main Image
    │       └── Gallery Images
    │   |
    │   v
    │   [Validate Input]
    │   |
    │   v
    │   <Validation Result>
    │       ├── [INVALID] → [Show Errors] → [Back to Form]
    │       └── [VALID]
    │           |
    │           v
    │           [Upload Images]
    │           |
    │           v
    │           [Save Costume to Database]
    │           |
    │           v
    │           [Show Success Message] → [Back to Admin Action]
    │
    ├── [Edit Costume]
    │   |
    │   v
    │   [Load Costume Data]
    │   |
    │   v
    │   [Display Edit Form with Current Data]
    │   |
    │   v
    │   [Admin Modify Data]
    │   |
    │   v
    │   [Validate Input]
    │   |
    │   v
    │   <Validation Result>
    │       ├── [INVALID] → [Show Errors] → [Back to Form]
    │       └── [VALID]
    │           |
    │           v
    │           <Images Changed?>
    │               ├── [YES] → [Upload New Images] → [Delete Old Images]
    │               └── [NO] → [Keep Existing Images]
    │           |
    │           v
    │           [Update Costume in Database]
    │           |
    │           v
    │           [Show Success Message] → [Back to Admin Action]
    │
    └── [Delete Costume]
        |
        v
        [Show Confirmation Dialog]
        |
        v
        <Confirm Delete?>
            ├── [NO] → [Cancel] → [Back to Admin Action]
            └── [YES]
                |
                v
                <Check Active Rentals>
                    ├── [HAS ACTIVE RENTALS] → [Show Error: Cannot Delete] → [Back to Admin Action]
                    └── [NO ACTIVE RENTALS]
                        |
                        v
                        [Delete Costume Images]
                        |
                        v
                        [Delete Costume from Database]
                        |
                        v
                        [Show Success Message] → [Back to Admin Action]
    |
    v
[Logout or Continue]
    |
    v
[END]
```

## 5. Owner View Reports Process

```
[START: Owner Login]
    |
    v
[Access Dashboard]
    |
    v
[Load Dashboard Statistics]
    ├── Total Revenue
    ├── Total Rentals
    ├── Active Rentals
    ├── Completed Rentals
    ├── Total Customers
    └── Popular Costumes
    |
    v
[Display Dashboard with Charts]
    ├── Revenue Chart (by month)
    ├── Rental Status Distribution
    └── Top Costumes
    |
    v
<Owner Action>
    |
    ├── [View Detailed Reports]
    │   |
    │   v
    │   [Access Reports Page]
    │   |
    │   v
    │   [Select Report Type]
    │       ├── Revenue Report
    │       ├── Rental Report
    │       └── Customer Report
    │   |
    │   v
    │   [Apply Filters]
    │       ├── Date Range
    │       ├── Status
    │       └── Category
    │   |
    │   v
    │   [Generate Report]
    │   |
    │   v
    │   [Display Report Data]
    │       ├── Tables
    │       ├── Charts
    │       └── Summary Statistics
    │   |
    │   v
    │   <Export Report?>
    │       ├── [YES] → [Export to PDF/Excel] → [Download File]
    │       └── [NO] → [Continue Viewing]
    │   |
    │   v
    │   [Back to Owner Action]
    │
    └── [Refresh Dashboard]
        |
        v
        [Reload Latest Data] → [Back to Owner Action]
    |
    v
[Logout]
    |
    v
[END]
```

## 6. Midtrans Webhook Processing

```
[START: Midtrans Send Webhook]
    |
    v
[System Receive POST Request]
    |
    v
[Extract Webhook Data]
    ├── order_id
    ├── transaction_status
    ├── signature_key
    └── other transaction details
    |
    v
[Verify Signature]
    |
    v
<Signature Valid?>
    ├── [NO: Invalid Signature]
    │   |
    │   v
    │   [Log Security Warning]
    │   |
    │   v
    │   [Return HTTP 403 Forbidden]
    │   |
    │   v
    │   [END]
    │
    └── [YES: Valid Signature]
        |
        v
        [Find Payment by order_id]
        |
        v
        <Payment Found?>
            ├── [NO: Payment Not Found]
            │   |
            │   v
            │   [Log Error]
            │   |
            │   v
            │   [Return HTTP 404 Not Found]
            │   |
            │   v
            │   [END]
            │
            └── [YES: Payment Found]
                |
                v
                [Map Transaction Status]
                    ├── capture/settlement → settlement
                    ├── pending → pending
                    ├── deny/expire/cancel → expire/cancel
                    └── others → keep current
                |
                v
                [Update Payment Status in Database]
                |
                v
                [Log Transaction Update]
                |
                v
                <Payment Status = Settlement?>
                    ├── [YES]
                    │   |
                    │   v
                    │   [Send Notification to Customer]
                    │   |
                    │   v
                    │   [Update Rental Status if Needed]
                    │
                    └── [NO: Other Status]
                        |
                        v
                        [Log Status Change Only]
                |
                v
                [Return HTTP 200 OK]
                |
                v
                [END]
```

## Key Decision Points

### Booking Validation
- Event date must be >= today + 3 days
- Rental duration must be 1-5 days
- Stock must be available for the entire rental period

### Payment Validation
- Payment must be completed before pickup date (event_date - 1 day)
- Payment due date is event_date - 2 days

### Return Validation
- Return must be recorded with actual return date
- Late fee calculated if returned after due date (Rp 15.000/day)
- Damage fee can be added by admin

### Status Transitions
- Rental: pending → active → completed (or cancelled anytime)
- Payment: pending → settlement/expire/cancel (one-way only)