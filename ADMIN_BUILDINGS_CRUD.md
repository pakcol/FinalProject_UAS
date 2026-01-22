# Admin Buildings CRUD Feature

## Overview
Fitur CRUD (Create, Read, Update, Delete) lengkap untuk manajemen building oleh admin. Memungkinkan admin untuk menambah, mengedit, menghapus, dan mengaktifkan/menonaktifkan building dalam game.

## Features Implemented

### ✅ Backend
- **Migration**: Menambahkan kolom `is_admin` pada tabel `users`
- **Middleware**: `AdminMiddleware` untuk proteksi akses admin
- **Controller**: `AdminBuildingController` dengan full CRUD operations
- **Model Updates**: 
  - `User` model dengan field `is_admin`
  - `Building` model dengan field `is_active`

### ✅ Frontend Views
- **Admin Layout**: Template layout dengan sidebar navigation
- **Dashboard**: Statistik total buildings, users, kingdoms
- **Buildings Index**: Tabel dengan pagination, search, dan filter
- **Buildings Create**: Form untuk menambah building baru
- **Buildings Edit**: Form untuk edit building existing
- **Toggle Status**: Aktifkan/nonaktifkan building dengan satu klik

### ✅ Routes
```php
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::resource('buildings', AdminBuildingController::class);
    Route::patch('buildings/{building}/toggle', [AdminBuildingController::class, 'toggleActive']);
});
```

## Installation & Setup

### 1. Run Migration
```bash
php artisan migrate
```

Migration akan menambahkan kolom `is_admin` (boolean, default false) pada tabel `users`.

### 2. Set Admin User
Gunakan Laravel Tinker untuk set user pertama sebagai admin:

```bash
php artisan tinker
```

Dalam tinker:
```php
$user = App\Models\User::find(1); // Ganti dengan ID user yang ingin dijadikan admin
$user->is_admin = true;
$user->save();
exit;
```

Atau via SQL:
```sql
UPDATE users SET is_admin = 1 WHERE id = 1;
```

### 3. Clear Cache (Optional)
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

## Usage Guide

### Accessing Admin Panel
1. Login sebagai user dengan `is_admin = true`
2. Akses URL: `http://your-domain/admin/dashboard`
3. Navigate ke menu "Buildings" di sidebar

### Managing Buildings

#### Create New Building
1. Klik tombol "Add New Building"
2. Isi form:
   - **Name**: Nama building (required)
   - **Type**: Pilih tipe (main/barracks/mine/walls/other)
   - **Description**: Deskripsi building (required)
   - **Gold Cost**: Biaya pembuatan building
   - **Level**: Level default building
   - **Gold Production**: Produksi emas per menit
   - **Troop Production**: Produksi pasukan per menit
   - **Defense Bonus**: Bonus pertahanan
   - **Active**: Checkbox untuk set status aktif
3. Klik "Create Building"

#### Edit Building
1. Pada tabel buildings, klik icon edit (pencil) di kolom Actions
2. Ubah data yang diperlukan
3. Klik "Update Building"

#### Toggle Active/Inactive
1. Klik tombol status (Active/Inactive) pada kolom Status
2. Status akan berubah secara langsung
3. Building inactive tidak akan muncul untuk player

#### Delete Building
1. Klik icon delete (trash) di kolom Actions
2. Confirm deletion
3. Building akan terhapus permanen dari database

## File Structure

```
FinalProject_UAS/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── AdminBuildingController.php
│   │   └── Middleware/
│   │       └── AdminMiddleware.php
│   └── Models/
│       ├── Building.php (updated)
│       └── User.php (updated)
├── database/
│   └── migrations/
│       └── 2026_01_22_093600_add_is_admin_to_users_table.php
├── resources/
│   └── views/
│       └── admin/
│           ├── layouts/
│           │   └── app.blade.php
│           ├── buildings/
│           │   ├── index.blade.php
│           │   ├── create.blade.php
│           │   └── edit.blade.php
│           └── dashboard.blade.php
└── routes/
    └── web.php (updated)
```

## Security Features

1. **Middleware Protection**: Semua route admin dilindungi dengan middleware `admin`
2. **Authentication Check**: Memastikan user sudah login
3. **Authorization Check**: Memastikan user memiliki `is_admin = true`
4. **CSRF Protection**: Semua form menggunakan `@csrf` token
5. **Input Validation**: Validasi server-side untuk semua input

## Validation Rules

```php
[
    'name' => 'required|string|max:255',
    'type' => 'required|string|max:255',
    'description' => 'required|string',
    'gold_cost' => 'required|integer|min:0',
    'level' => 'required|integer|min:1',
    'gold_production' => 'required|integer|min:0',
    'troop_production' => 'required|integer|min:0',
    'defense_bonus' => 'required|integer|min:0',
    'is_active' => 'boolean',
]
```

## UI/UX Features

- **Responsive Design**: Bootstrap 5 responsive layout
- **Icons**: Font Awesome icons untuk visual yang lebih baik
- **Alert Messages**: Success/error messages dengan auto-dismiss
- **Confirmation Dialog**: Konfirmasi sebelum delete
- **Pagination**: Automatic pagination untuk large dataset
- **Color Coding**: 
  - Primary untuk create/view
  - Warning untuk edit
  - Danger untuk delete
  - Success untuk active status
  - Secondary untuk inactive status

## Troubleshooting

### Issue: 403 Unauthorized
**Solution**: Pastikan user sudah set sebagai admin
```bash
php artisan tinker
>>> $user = User::find(YOUR_USER_ID);
>>> $user->is_admin = true;
>>> $user->save();
```

### Issue: Route not found
**Solution**: Clear route cache
```bash
php artisan route:clear
php artisan route:cache
```

### Issue: View not found
**Solution**: Pastikan semua view files sudah dibuat di path yang benar

### Issue: Class AdminMiddleware not found
**Solution**: 
1. Pastikan file `app/Http/Middleware/AdminMiddleware.php` exists
2. Check registration di `app/Http/Kernel.php`
3. Run `composer dump-autoload`

## Future Enhancements

- [ ] Bulk operations (delete multiple buildings)
- [ ] Import/Export buildings via CSV
- [ ] Building images upload
- [ ] Advanced search and filter
- [ ] Audit log untuk tracking changes
- [ ] Role-based access control (RBAC)
- [ ] Building categories management

## Credits

Developed for **Final Project Web Framework Programming - Universitas Surabaya**

---

**Last Updated**: January 22, 2026
**Version**: 1.0.0
