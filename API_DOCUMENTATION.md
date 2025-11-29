# API Documentation - Rental App

## Base URL
```
http://localhost:8000/api
```

## Authentication
API ini menggunakan Laravel Sanctum untuk authentication. Setelah login, Anda akan menerima token yang harus disertakan di header setiap request yang memerlukan authentication.

**Header untuk authenticated requests:**
```
Authorization: Bearer {your-token}
Accept: application/json
```

---

## 🔐 Authentication Endpoints

### 1. Register
**POST** `/api/register`

**Body:**
```json
{
  "full_name": "Budi Santoso",
  "email": "budi@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "phone_number": "08123456789",
  "date_of_birth": "1990-05-15",
  "driver_license_number": "SIM-1234567890"
}
```

### 2. Login
**POST** `/api/login`

**Body:**
```json
{
  "email": "customer1@gmail.com",
  "password": "password123"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {...},
    "token": "1|xxxxx...",
    "token_type": "Bearer"
  }
}
```

### 3. Logout
**POST** `/api/logout`

**Headers:** `Authorization: Bearer {token}`

### 4. Get Profile Summary
**GET** `/api/me`

**Headers:** `Authorization: Bearer {token}`

---

## 👤 Profile Endpoints

### 1. Get Full Profile
**GET** `/api/profile`

**Headers:** `Authorization: Bearer {token}`

### 2. Update Basic Profile
**PUT** `/api/profile`

**Headers:** `Authorization: Bearer {token}`

**Body:**
```json
{
  "full_name": "Budi Santoso Updated",
  "phone_number": "08123456789",
  "date_of_birth": "1990-05-15",
  "driver_license_number": "SIM-1234567890"
}
```

### 3. Update Address
**PUT** `/api/profile/address`

**Headers:** `Authorization: Bearer {token}`

**Body:**
```json
{
  "address": "Jl. Sudirman No. 123",
  "city": "Jakarta",
  "province": "DKI Jakarta",
  "postal_code": "12345"
}
```

### 4. Update Emergency Contact
**PUT** `/api/profile/emergency-contact`

**Headers:** `Authorization: Bearer {token}`

**Body:**
```json
{
  "emergency_contact_name": "Ani Santoso",
  "emergency_contact_phone": "089234567890"
}
```

---

## 📍 Locations & Categories (Public)

### 1. List All Locations
**GET** `/api/locations`

### 2. Get Location Detail
**GET** `/api/locations/{id}`

### 3. List All Car Categories
**GET** `/api/car-categories`

### 4. Get Category Detail
**GET** `/api/car-categories/{id}`

---

## 🚗 Cars Endpoints

### 1. List Cars (Public)
**GET** `/api/cars`

**Query Parameters:**
- `location_id` (optional)
- `category_id` (optional)
- `seat_capacity_min` (optional)
- `seat_capacity_max` (optional)
- `price_min` (optional)
- `price_max` (optional)

**Example:**
```
GET /api/cars?location_id=1&category_id=1&seat_capacity_min=4
```

### 2. Get Car Detail (Public)
**GET** `/api/cars/{id}`

### 3. Get Recommended Cars (Authenticated)
**GET** `/api/cars/recommended`

**Headers:** `Authorization: Bearer {token}`

---

## 📝 Rentals Endpoints

### 1. Preview Rental (Estimate Price)
**POST** `/api/rentals/preview`

**Headers:** `Authorization: Bearer {token}`

**Body:**
```json
{
  "car_id": 1,
  "start_date": "2025-12-01",
  "end_date": "2025-12-05",
  "pickup_location_id": 1,
  "dropoff_location_id": 1,
  "driver_option": "self_drive",
  "promo_code": "PROMO10"
}
```

**driver_option values:** `self_drive` or `with_driver`

### 2. Create Rental (Booking)
**POST** `/api/rentals`

**Headers:** `Authorization: Bearer {token}`

**Body:**
```json
{
  "car_id": 1,
  "start_date": "2025-12-01",
  "end_date": "2025-12-05",
  "pickup_location_id": 1,
  "dropoff_location_id": 1,
  "driver_option": "self_drive",
  "special_requests": "Tolong mobil diisi bensin penuh.",
  "promo_code": "PROMO10"
}
```

### 3. List My Rentals
**GET** `/api/rentals`

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `status` (optional): `pending`, `confirmed`, `active`, `completed`, `cancelled`

**Example:**
```
GET /api/rentals?status=pending
```

### 4. Get Rental Detail
**GET** `/api/rentals/{uuid}`

**Headers:** `Authorization: Bearer {token}`

### 5. Cancel Rental
**POST** `/api/rentals/{uuid}/cancel`

**Headers:** `Authorization: Bearer {token}`

---

## 💳 Payments Endpoints

### 1. Create Payment
**POST** `/api/payments`

**Headers:** `Authorization: Bearer {token}`

**Body:**
```json
{
  "rental_id": 1,
  "payment_method": "ewallet"
}
```

**payment_method values:** `credit_card`, `debit_card`, `bank_transfer`, `ewallet`, `cash`

### 2. Get Payment Detail
**GET** `/api/payments/{id}`

**Headers:** `Authorization: Bearer {token}`

---

## 🎁 Promotions Endpoints

### 1. List Active Promotions (Public)
**GET** `/api/promotions/active`

### 2. Validate Promotion Code (Public)
**POST** `/api/promotions/validate`

**Body:**
```json
{
  "code": "PROMO10",
  "rental_amount": 1500000,
  "rental_days": 3
}
```

---

## 🔔 Notifications Endpoints

### 1. List My Notifications
**GET** `/api/notifications`

**Headers:** `Authorization: Bearer {token}`

### 2. Get Unread Count
**GET** `/api/notifications/unread-count`

**Headers:** `Authorization: Bearer {token}`

### 3. Mark Notification as Read
**POST** `/api/notifications/{id}/read`

**Headers:** `Authorization: Bearer {token}`

### 4. Mark All as Read
**POST** `/api/notifications/read-all`

**Headers:** `Authorization: Bearer {token}`

---

## 📊 Dashboard Endpoints

### 1. Customer Dashboard (Main)
**GET** `/api/dashboard`

**Headers:** `Authorization: Bearer {token}`

Returns: Active rental, upcoming rentals, stats, recommended cars, and active promotions

### 2. Dashboard Stats
**GET** `/api/dashboard/stats`

**Headers:** `Authorization: Bearer {token}`

### 3. Active Rental Detail
**GET** `/api/dashboard/active-rental`

**Headers:** `Authorization: Bearer {token}`

### 4. Upcoming Rentals
**GET** `/api/dashboard/upcoming-rentals`

**Headers:** `Authorization: Bearer {token}`

### 5. Recommended Cars for Dashboard
**GET** `/api/dashboard/recommended-cars?limit=10`

**Headers:** `Authorization: Bearer {token}`

### 6. Recent Transactions
**GET** `/api/dashboard/recent-transactions?limit=5`

**Headers:** `Authorization: Bearer {token}`

### 7. Active Promos for Dashboard
**GET** `/api/dashboard/promos`

**Headers:** `Authorization: Bearer {token}`

### 8. Membership Progress
**GET** `/api/dashboard/membership`

**Headers:** `Authorization: Bearer {token}`

### 9. Quick Actions
**GET** `/api/dashboard/quick-actions`

**Headers:** `Authorization: Bearer {token}`

### 10. Rental History Summary
**GET** `/api/dashboard/rental-history?period=30`

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `period` (optional, default: 30): Number of days to look back

### 11. Spending Chart
**GET** `/api/dashboard/spending-chart?months=6`

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `months` (optional, default: 6): Number of months to display

### 12. Popular Cars Nearby
**GET** `/api/dashboard/popular-cars?location_id=1&limit=5`

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `location_id` (optional): Location ID
- `limit` (optional, default: 5): Number of cars to return

---

## 🧪 Testing

### Test Credentials (Seeder)
```
Email: customer1@gmail.com
Password: password123
```

### Quick Start Testing dengan cURL

#### 1. Login
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "customer1@gmail.com",
    "password": "password123"
  }'
```

#### 2. Get Profile (replace {token} dengan token dari login)
```bash
curl -X GET http://localhost:8000/api/profile \
  -H "Accept: application/json" \
  -H "Authorization: Bearer {token}"
```

#### 3. List Cars
```bash
curl -X GET "http://localhost:8000/api/cars" \
  -H "Accept: application/json"
```

---

## 📝 Notes

1. Semua endpoint mengembalikan JSON response
2. Success response selalu memiliki structure:
   ```json
   {
     "success": true,
     "data": {...}
   }
   ```
3. Error response structure:
   ```json
   {
     "success": false,
     "message": "Error message",
     "errors": {...}
   }
   ```
4. Pagination menggunakan Laravel default pagination
5. Timestamps (created_at, updated_at) otomatis ditambahkan ke semua response

---

## 🚀 Running the API

1. Setup database dan jalankan migration:
```bash
php artisan migrate:fresh --seed
```

2. Jalankan seeder customer:
```bash
php artisan db:seed --class=CustomerSeeder
```

3. Start development server:
```bash
php artisan serve
```

API akan berjalan di: `http://localhost:8000`
