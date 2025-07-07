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

📂 API Documentation (Postman Collection)
🔗 [Postman Collection Link](https://documenter.getpostman.com/view/23176160/2sB34cp2vi)

Includes all endpoints for:

- Auth: Register, Login
- Tenants: Current tenant info
- Teams: List, create, set availability
- Time Slots: Generate available slots
- Bookings: List, create, cancel booking

📝 Notes on Multi-Tenancy
- ✅ Uses Spatie Laravel Multitenancy package
- ✅ Each user belongs to a tenant via a tenant_id foreign key
- ✅ Models are scoped automatically to the current tenant for strict data isolation
- ✅ Middleware SetTenantFromUser sets the current tenant context on each request dynamically

⏰ Notes on Time Slot Generation
- ✅ Time slots are not stored in the database
- ✅ Slots are generated dynamically based on:
  - Team weekly recurring availability
  - Requested date range
  - Excluding already booked slots
- ✅ Uses Carbon for accurate date and time manipulation
- ✅ Example: Generates 1-hour slots from 9:00 AM to 5:00 PM for each day in the requested range, skipping booked slots.

🎯 Key Technologies
- Laravel 12
- Spatie Laravel Multitenancy
- Sanctum (API Authentication)
- Nwidart Modules (Modular architecture)

🎉 Enjoy using Booking Tenant System!

For questions or contributions, feel free to create an issue or pull request.
