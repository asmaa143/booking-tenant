# 🏢 Booking Tenant System

🔗 **Multi-tenant booking system** with team availability management, dynamic time slot generation, and booking APIs.

---

## 🚀 **Setup Instructions**

### ✅ **1. Clone the repository**

```bash
git clone https://github.com/asmaa143/booking-tenant.git
cd booking-tenant
composer install
cp .env.example .env
php artisan key:generate
php artisan modules:migrate-seed-all
php artisan migrate
```
📂 Postman Collection
https://documenter.getpostman.com/view/23176160/2sB34cp2vi

📝 Notes on Multi-Tenancy
✅ Uses Spatie Laravel Multitenancy package.
✅ Each user belongs to a tenant via tenant_id foreign key.
✅ All models are scoped automatically to the current tenant to ensure strict data isolation.
✅ Middleware SetTenantFromUser sets the current tenant context on each request.

⏰ Notes on Time Slot Generation
✅ Time slots are not stored in the database.
✅ Slots are generated dynamically on-the-fly based on:

Team weekly availability

Requested date range

Excluding already booked slots

✅ Uses Carbon for precise date and time manipulation.
✅ Example: generating 1-hour slots from 9:00 AM to 5:00 PM for each day in range, skipping conflicts.


🎉 Enjoy using Booking Tenant System!
