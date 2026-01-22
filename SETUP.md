# Setup Guide - Tumbal Perang Kingdom Game

## 📋 Requirements

- PHP >= 8.1
- Composer
- MySQL >= 5.7 atau MariaDB
- Node.js & NPM (untuk asset compilation)

## 🚀 Installation Steps

### 1. Clone Repository

```bash
git clone https://github.com/pakcol/FinalProject_UAS.git
cd FinalProject_UAS
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wfp_final
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Database Setup

```bash
# Create database
mysql -u root -p
CREATE DATABASE wfp_final;
exit;

# Run migrations
php artisan migrate

# Optional: Seed initial data
php artisan db:seed
```

### 5. Build Assets

```bash
npm run build
# atau untuk development
npm run dev
```

### 6. Create Admin User

```bash
php artisan tinker
```

Dalam tinker:

```php
$user = App\Models\User::find(1); // atau user ID yang ingin dijadikan admin
$user->is_admin = true;
$user->save();
```

### 7. Storage Link

```bash
php artisan storage:link
```

## ⚙️ Running the Application

### Development Mode

```bash
# Terminal 1: Start Laravel server
php artisan serve

# Terminal 2: Run scheduler (for auto gold & troop generation)
php artisan schedule:work

# Terminal 3 (Optional): Watch assets
npm run dev
```

Akses aplikasi di: `http://localhost:8000`

### Production Mode

**Setup Cron Job** (Linux/Mac):

```bash
crontab -e
```

Tambahkan line:

```
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

**Windows Task Scheduler**:

- Buat task baru yang menjalankan:
  ```
  php artisan schedule:run
  ```
- Set untuk run every minute

## 🎮 First Time Setup

1. **Register Account**
   - Buka `http://localhost:8000/register`
   - Isi data dan pilih tribe
   - Otomatis dapat main building level 1

2. **Build Economy**
   - Build Gold Mine untuk income
   - Build Barracks untuk troop production
   - Build Walls untuk defense

3. **Auto Resources**
   - Gold: +5 per menit (base) + (10 × jumlah mines)
   - Troops: +5 per menit × jumlah barracks
   - Pastikan scheduler berjalan!

4. **Admin Panel**
   - Login sebagai admin
   - Akses `/admin/dashboard`
   - Manage buildings, tribes, users

## 🔧 Manual Commands

Jika scheduler belum jalan, generate manual:

```bash
# Generate gold untuk semua kingdoms
php artisan game:generate-gold

# Generate troops untuk semua kingdoms
php artisan game:generate-troops
```

## 🐛 Troubleshooting

### Scheduler Tidak Jalan

```bash
# Test command manual
php artisan game:generate-gold
php artisan game:generate-troops

# Lihat scheduled tasks
php artisan schedule:list

# Run scheduler manually (development)
php artisan schedule:work
```

### Migration Error

```bash
# Reset database
php artisan migrate:fresh

# Atau rollback and re-migrate
php artisan migrate:rollback
php artisan migrate
```

### Permission Issues (Linux)

```bash
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

## 📚 Game Mechanics

### Resources
- **Gold**: Currency untuk build/upgrade
- **Troops**: Army untuk attack/defense

### Buildings
- **Main Building**: Foundation, dapat di-upgrade
- **Barracks**: Produksi troops (+5/min per barracks)
- **Gold Mine**: Produksi gold (+10/min per mine)
- **Defense Walls**: Bonus defense

### Battle System
- Hanya bisa serang kingdom yang punya barracks DAN mine
- **Win**: Rampas 90% gold defender
- **Lose**: Semua troops attacker mati
- Defender troops: calculated based on power difference

### Tribe Attributes
- **Marksman**: High range attack, low defense
- **Tank**: High defense, low attack
- **Mage**: High magic attack, low magic defense
- **Warrior**: High melee attack, balanced

## 🌐 Deployment (Production)

See [DEPLOYMENT.md](DEPLOYMENT.md) for detailed deployment instructions.

## 📞 Support

Jika ada masalah, create issue di GitHub repository.
