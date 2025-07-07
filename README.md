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
