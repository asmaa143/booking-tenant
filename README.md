# 🏢 Booking Tenant System

🔗 A multi-tenant booking system providing:

- ✅ Team availability management
- ✅ Dynamic time slot generation  
- ✅ Full booking APIs

## 🚀 Setup Instructions

### 1️⃣ Clone the repository and setup environment

```bash
git clone https://github.com/asmaa143/booking-tenant.git
cd booking-tenant
composer install
cp .env.example .env
php artisan key:generate
php artisan modules:migrate-seed-all
php artisan migrate
```

### 2️⃣ Running Tests

```bash


# Run specific booking tests
php artisan test:bookings
```

#### 📋 Test Command Explanation

The `php artisan test:bookings` command is a custom Artisan command that:
- Runs only the tests related to the Booking module
## 🧪 Test Coverage Details

### 🔧 BookingServiceTest (Unit Tests)
Testing the core booking business logic:

- **✅ test_create_booking_successful**
  - Creates tenant, user, and team with availability
  - Generates valid time slots
  - Successfully creates booking with proper data validation
  - Verifies booking is stored in database

- **❌ test_create_booking_conflict_throws_exception**
  - Tests conflict detection when time slots overlap
  - Creates existing booking first
  - Attempts to create conflicting booking
  - Expects exception with message "Slot is already booked."

### 🌐 BookingControllerTest (Feature Tests)
Testing the API endpoints and HTTP responses:

- **📋 test_user_can_list_bookings**
  - Creates authenticated user with booking
  - Tests GET `/api/v1/bookings` endpoint
  - Verifies 200 status and success message
  - Ensures proper booking retrieval

- **✨ test_user_can_create_booking**
  - Sets up tenant, user, and team with availability
  - Gets valid time slot from availability
  - Tests POST `/api/v1/bookings` endpoint
  - Verifies booking creation and database persistence

- **🚫 test_user_cannot_create_conflicting_booking**
  - Creates existing booking in time slot
  - Attempts to book same slot again
  - Expects 409 Conflict status
  - Verifies proper error message handling

- **🗑️ test_user_can_cancel_booking**
  - Creates booking for authenticated user
  - Tests DELETE `/api/v1/bookings/{id}` endpoint
  - Verifies 200 status and success message
  - Ensures booking is removed from database

### 🔍 Key Testing Features

- **🏢 Multi-tenant isolation:** Each test creates isolated tenant data
- **🔐 Authentication:** All API tests use authenticated users
- **📅 Time slot validation:** Tests ensure proper availability checking
- **🔄 Database transactions:** Uses RefreshDatabase for clean test state
- **🧩 Helper traits:** Shared test utilities for common setup tasks

## 📂 API Documentation

🔗 **Postman Collection:** [https://documenter.getpostman.com/view/23176160/2sB34cp2vi](https://documenter.getpostman.com/view/23176160/2sB34cp2vi)

### 📋 Available Endpoints:

- **Auth:**
  - User registration
  - User login
  - Token management

- **Tenants:**
  - Get current tenant information
  - Tenant-specific operations

- **Teams:**
  - List all teams
  - Create new teams
  - Set team availability
  - Manage team members

- **Bookings:**
  - Create new bookings
  - View booking details
  - Cancel bookings
  - Check availability
