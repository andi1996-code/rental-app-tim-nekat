# 🚗 Rental App - Tim A

> Aplikasi Rental Mobil berbasis Laravel dengan fitur lengkap untuk customer, admin, dan super admin.

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat&logo=mysql)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

---

## 📋 Deskripsi Project

Rental App adalah sistem manajemen rental mobil modern yang memungkinkan:
- **Customer** untuk menyewa mobil dengan mudah
- **Admin** untuk mengelola mobil dan lokasi tertentu
- **Super Admin** untuk manajemen sistem secara keseluruhan

### ✨ Fitur Utama

#### 👤 Customer Features
- 📝 Registrasi dan login dengan verifikasi email
- 🚙 Browse dan cari mobil berdasarkan kategori, lokasi, harga
- 📅 Booking mobil dengan pilihan pickup & dropoff location berbeda
- 💳 Multiple payment methods (Credit Card, Bank Transfer, E-Wallet, Cash)
- 🎟️ Gunakan promo code untuk diskon
- 📊 Membership levels (Regular, Silver, Gold) dengan benefits
- 📱 Notifikasi real-time untuk booking dan payment
- ⭐ Rating dan review system
- 🚦 Driver option (Self Drive / With Driver)

#### 🔧 Admin Features
- 🏢 Manage mobil di lokasi tertentu
- 🔍 Monitor rental dan pembayaran
- 🛠️ Track maintenance mobil
- 📈 View reports dan statistics
- 👥 Manage customer profiles
- 💬 Communication dengan customer

#### 👑 Super Admin Features
- 🌐 Manage semua lokasi rental
- 👮 Manage admin dan assign ke lokasi
- 📊 View comprehensive analytics
- 🎯 Buat dan manage promo codes
- ⚙️ System configuration
- 📨 Broadcast notifications

---

## 🗄️ Database Schema

Project ini menggunakan **11 tabel utama** dengan relationships yang kompleks:

| Tabel | Deskripsi |
|-------|-----------|
| `users` | Multi-role users (customer, admin, super_admin) dengan UUID |
| `customer_profiles` | Extended profile untuk customer dengan membership tracking |
| `car_categories` | Kategori mobil (Economy, SUV, MPV, Sedan, Luxury) |
| `locations` | Multiple locations dengan GPS coordinates |
| `cars` | Data mobil lengkap dengan features, images, dan status |
| `rentals` | Booking data dengan pickup/dropoff locations berbeda |
| `payments` | Payment tracking dengan multiple methods |
| `maintenance` | Maintenance tracking untuk mobil |
| `promotions` | Promo codes dengan usage limit |
| `notifications` | User notifications system |

📖 **Detail lengkap:** Lihat [DATABASE_SETUP.md](DATABASE_SETUP.md) dan [MIGRATION_SUMMARY.md](MIGRATION_SUMMARY.md)

---

## 🛠️ Tech Stack

- **Backend:** Laravel 11.x
- **Database:** MySQL 8.0
- **Authentication:** Laravel Sanctum (API Token)
- **Queue:** Laravel Queue for background jobs
- **Storage:** Local/S3 for images
- **Cache:** Redis (optional)

---

## 🚀 Installation

### Prerequisites
- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Node.js & NPM (untuk asset compilation)

### Setup Steps

1. **Clone Repository**
```bash
git clone https://github.com/andi1996-code/rental-app-tim-nekat.git
cd rental-app-tim-nekat
```

2. **Install Dependencies**
```bash
composer install
npm install
```

3. **Environment Configuration**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure Database**
Edit file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_rental_mobil
DB_USERNAME=root
DB_PASSWORD=
```

5. **Run Migrations**
```bash
php artisan migrate
```

6. **Seed Database (Optional)**
```bash
php artisan db:seed --class=LocationSeeder
php artisan db:seed --class=CarCategorySeeder
```

7. **Generate Storage Link**
```bash
php artisan storage:link
```

8. **Compile Assets**
```bash
npm run dev
# atau untuk production
npm run build
```

9. **Run Development Server**
```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

---

## 📁 Project Structure

```
rental-app-tim-a/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── API/          # API Controllers
│   ├── Models/               # Eloquent Models (9 models)
│   └── Policies/             # Authorization Policies
├── database/
│   ├── migrations/           # 11 Migration files
│   └── seeders/              # Database Seeders
├── routes/
│   ├── api.php              # API Routes
│   └── web.php              # Web Routes
├── resources/
│   ├── views/               # Blade Templates
│   └── js/                  # Frontend Assets
├── storage/
│   └── app/
│       └── public/          # Uploaded Files
├── DATABASE_SETUP.md        # Database Documentation
└── MIGRATION_SUMMARY.md     # Migration Summary
```

---

## 🔑 API Endpoints (Coming Soon)

### Authentication
- `POST /api/register` - Register customer baru
- `POST /api/login` - Login user
- `POST /api/logout` - Logout user

### Cars
- `GET /api/cars` - List semua mobil
- `GET /api/cars/{id}` - Detail mobil
- `GET /api/categories` - List kategori mobil

### Rentals
- `POST /api/rentals` - Create booking baru
- `GET /api/rentals` - List rental user
- `GET /api/rentals/{id}` - Detail rental

### Payments
- `POST /api/payments` - Process payment
- `GET /api/payments/{id}` - Payment status

---

## 🎯 Roadmap

- [x] Database Schema & Models
- [x] Migration & Seeding
- [ ] API Authentication (Sanctum)
- [ ] API Controllers
- [ ] API Routes
- [ ] Validation & Error Handling
- [ ] Payment Gateway Integration
- [ ] File Upload (Images)
- [ ] Email Notifications
- [ ] Admin Dashboard
- [ ] Customer Frontend
- [ ] Testing (Unit & Feature)
- [ ] API Documentation (Swagger)
- [ ] Deployment

---

## 👥 Team

**Tim A - Rental App Development**

- Developer: [Your Name]
- Project Manager: [PM Name]
- Designer: [Designer Name]

---

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 🤝 Contributing

Contributions, issues, and feature requests are welcome!

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📞 Support

Jika ada pertanyaan atau butuh bantuan:
- 📧 Email: -
- 💬 Issues: [GitHub Issues](https://github.com/andi1996-code/rental-app-tim-nekat/issues)

---

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The PHP Framework
- [Tailwind CSS](https://tailwindcss.com) - For styling
- [MySQL](https://mysql.com) - Database

---

<p align="center">Made with ❤️ by Tim A</p>
