# Database Setup - Rental App

## Migration Files Created

Berikut adalah daftar migration yang telah dibuat:

1. **2025_11_27_044048_create_users_table.php** - Tabel users dengan role customer, admin, dan super_admin
2. **2025_11_27_044131_create_customer_profiles_table.php** - Tabel customer profiles dengan informasi detail customer
3. **2025_11_27_044145_create_car_categories_table.php** - Tabel kategori mobil
4. **2025_11_27_044152_create_locations_table.php** - Tabel lokasi rental
5. **2025_11_27_044154_create_cars_table.php** - Tabel mobil dengan detail lengkap
6. **2025_11_27_044202_create_rentals_table.php** - Tabel rental/booking
7. **2025_11_27_044204_create_payments_table.php** - Tabel pembayaran
8. **2025_11_27_044206_create_maintenance_table.php** - Tabel maintenance mobil
9. **2025_11_27_044208_create_promotions_table.php** - Tabel promo dan diskon
10. **2025_11_27_044210_create_notifications_table.php** - Tabel notifikasi
11. **2025_11_27_044311_add_managed_location_id_to_users_table.php** - Foreign key untuk admin yang manage lokasi tertentu

## Models Created

Semua model telah dibuat dengan relationship yang lengkap:

1. **User** - Model utama untuk user (customer, admin, super_admin)
2. **CustomerProfile** - Profile lengkap customer
3. **CarCategory** - Kategori mobil
4. **Location** - Lokasi rental
5. **Car** - Data mobil
6. **Rental** - Data rental/booking
7. **Payment** - Data pembayaran
8. **Maintenance** - Data maintenance mobil
9. **Promotion** - Data promo dan diskon
10. **Notification** - Notifikasi untuk user

## Relationships

### User Model
- `hasOne` CustomerProfile
- `hasMany` Rentals (as customer)
- `hasMany` Maintenances (as admin)
- `hasMany` Promotions (as creator)
- `hasMany` Notifications
- `belongsTo` Location (managed_location for admin)

### CustomerProfile Model
- `belongsTo` User

### CarCategory Model
- `hasMany` Cars

### Location Model
- `hasMany` Cars
- `hasMany` Rentals (as pickup_location)
- `hasMany` Rentals (as dropoff_location)
- `hasMany` Users (admins who manage this location)

### Car Model
- `belongsTo` CarCategory
- `belongsTo` Location
- `hasMany` Rentals
- `hasMany` Maintenances

### Rental Model
- `belongsTo` User (customer)
- `belongsTo` Car
- `belongsTo` Location (pickup_location)
- `belongsTo` Location (dropoff_location)
- `hasMany` Payments

### Payment Model
- `belongsTo` Rental

### Maintenance Model
- `belongsTo` Car
- `belongsTo` User (admin)

### Promotion Model
- `belongsTo` User (creator)

### Notification Model
- `belongsTo` User

## Cara Menjalankan Migration

```bash
# Jalankan semua migration
php artisan migrate

# Jika ingin rollback
php artisan migrate:rollback

# Jika ingin reset dan migrate ulang
php artisan migrate:fresh
```

## Catatan Penting

1. **UUID Fields**: Tabel `users`, `cars`, dan `rentals` memiliki field UUID untuk keamanan dan identifier eksternal
2. **Enum Fields**: Banyak menggunakan enum untuk validasi data (user_type, status, payment_method, dll)
3. **JSON Fields**: 
   - `locations.opening_hours` untuk jam operasional
   - `cars.features` untuk fitur mobil
   - `cars.images` untuk multiple gambar mobil
4. **Decimal Precision**: 
   - Amount/price fields menggunakan decimal(12,2)
   - Discount menggunakan decimal(5,2) atau decimal(10,2)
   - Coordinates menggunakan decimal(10,8) dan decimal(11,8)
5. **Foreign Keys**: Semua foreign key sudah diset dengan constraint dan onDelete behavior yang sesuai
6. **Timestamps**: Semua tabel menggunakan timestamps (created_at, updated_at)

## Database Diagram

Struktur database ini mendukung sistem rental mobil dengan fitur:
- Multi-role user (customer, admin, super_admin)
- Multiple locations untuk pickup dan dropoff
- Car categories dan pricing yang fleksibel
- Payment tracking yang lengkap
- Maintenance tracking
- Promotion/discount system
- Notification system
