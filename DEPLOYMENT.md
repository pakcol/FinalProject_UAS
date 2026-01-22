# Deployment Guide - Tumbal Perang

## 🚀 Deployment Options

### Option 1: Railway (Recommended - Free)

#### Step 1: Prepare Repository

1. Pastikan semua changes sudah di-push ke GitHub
2. Pastikan `.env.example` sudah ada

#### Step 2: Deploy to Railway

1. Buka [railway.app](https://railway.app)
2. Login dengan GitHub
3. Click "New Project" → "Deploy from GitHub repo"
4. Pilih repository: `pakcol/FinalProject_UAS`
5. Railway akan auto-detect Laravel

#### Step 3: Add Database

1. Click "New" → "Database" → "Add MySQL"
2. Railway akan provision MySQL instance
3. Copy connection details

#### Step 4: Set Environment Variables

```env
APP_NAME="Tumbal Perang"
APP_ENV=production
APP_KEY=base64:... (generate dengan php artisan key:generate)
APP_DEBUG=false
APP_URL=https://your-app.railway.app

DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}

SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

#### Step 5: Deploy Commands

Di Railway, set build & start commands:

**Build Command**:
```bash
composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

**Start Command**:
```bash
php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
```

#### Step 6: Setup Scheduler

Railway tidak support cron secara native. Gunakan salah satu:

**A. External Cron Service** (Recommended)

1. Buat endpoint untuk trigger scheduler:

```php
// routes/web.php
Route::get('/cron/run', function() {
    Artisan::call('schedule:run');
    return 'Scheduler executed';
})->middleware('throttle:1,1'); // Max 1 request per minute
```

2. Gunakan service seperti [cron-job.org](https://cron-job.org):
   - Create job yang hit: `https://your-app.railway.app/cron/run`
   - Set interval: Every 1 minute

**B. Laravel Queue Worker** (Alternative)

Ubah scheduler jadi queue jobs, deploy worker terpisah.

---

### Option 2: Heroku

#### Step 1: Install Heroku CLI

```bash
curl https://cli-assets.heroku.com/install.sh | sh
```

#### Step 2: Login & Create App

```bash
heroku login
heroku create tumbal-perang
```

#### Step 3: Add MySQL

```bash
heroku addons:create jawsdb:kitefin
```

#### Step 4: Set Environment

```bash
heroku config:set APP_KEY=$(php artisan key:generate --show)
heroku config:set APP_ENV=production
heroku config:set APP_DEBUG=false
```

#### Step 5: Add Procfile

Create `Procfile`:

```
web: vendor/bin/heroku-php-apache2 public/
worker: php artisan queue:work
```

#### Step 6: Deploy

```bash
git push heroku main
heroku run php artisan migrate --force
```

#### Step 7: Setup Scheduler

```bash
heroku addons:create scheduler:standard
heroku addons:open scheduler
```

Add job:
```
php artisan schedule:run
```
Frequency: Every 10 minutes

---

### Option 3: Shared Hosting (cPanel)

#### Step 1: Upload Files

1. Zip project (exclude `node_modules`, `.git`)
2. Upload via cPanel File Manager ke `/home/username/`
3. Extract

#### Step 2: Move Public Folder

```bash
mv public public_old
mv public_html public_html_old
ln -s /home/username/project/public /home/username/public_html
```

#### Step 3: Setup Database

1. Buat database di cPanel → MySQL
2. Update `.env`

#### Step 4: Composer Install

```bash
cd /home/username/project
composer install --no-dev
```

#### Step 5: Setup Cron Job

cPanel → Cron Jobs:

```
* * * * * cd /home/username/project && php artisan schedule:run >> /dev/null 2>&1
```

---

### Option 4: VPS (DigitalOcean/Linode)

#### Step 1: Server Setup

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install LAMP
sudo apt install apache2 mysql-server php8.1 php8.1-{cli,fpm,mysql,xml,mbstring,curl,zip,gd} -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

#### Step 2: Clone & Install

```bash
cd /var/www
sudo git clone https://github.com/pakcol/FinalProject_UAS.git tumbal-perang
cd tumbal-perang
sudo composer install --no-dev
```

#### Step 3: Apache Config

```bash
sudo nano /etc/apache2/sites-available/tumbal-perang.conf
```

```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /var/www/tumbal-perang/public

    <Directory /var/www/tumbal-perang/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

```bash
sudo a2ensite tumbal-perang
sudo a2enmod rewrite
sudo systemctl reload apache2
```

#### Step 4: Database

```bash
sudo mysql
CREATE DATABASE tumbal_perang;
CREATE USER 'tumbal'@'localhost' IDENTIFIED BY 'password';
GRANT ALL ON tumbal_perang.* TO 'tumbal'@'localhost';
exit;
```

#### Step 5: Permissions

```bash
sudo chown -R www-data:www-data /var/www/tumbal-perang
sudo chmod -R 775 /var/www/tumbal-perang/storage
sudo chmod -R 775 /var/www/tumbal-perang/bootstrap/cache
```

#### Step 6: Cron Job

```bash
sudo crontab -e -u www-data
```

```
* * * * * cd /var/www/tumbal-perang && php artisan schedule:run >> /dev/null 2>&1
```

#### Step 7: SSL (Optional)

```bash
sudo apt install certbot python3-certbot-apache -y
sudo certbot --apache -d your-domain.com
```

---

## 🔒 Security Checklist

- [ ] `APP_DEBUG=false` di production
- [ ] Generate new `APP_KEY`
- [ ] Remove `.env.example` sensitive data
- [ ] Setup firewall (UFW/CloudFlare)
- [ ] Use HTTPS (SSL certificate)
- [ ] Limit database access
- [ ] Regular backups
- [ ] Update dependencies regularly

## 📊 Monitoring

### Check Scheduler Running

```bash
# Check logs
tail -f storage/logs/laravel.log | grep "generation completed"

# Manual test
php artisan game:generate-gold
php artisan game:generate-troops
```

### Performance Optimization

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

## 🆘 Troubleshooting

### 500 Error

```bash
php artisan cache:clear
php artisan config:clear
chmod -R 775 storage bootstrap/cache
```

### Scheduler Not Running

```bash
# Check if cron is active
service cron status

# Check cron logs
grep CRON /var/log/syslog
```

### Database Connection Failed

```bash
# Test connection
php artisan tinker
>>> DB::connection()->getPdo();
```

## 🎉 Post-Deployment

1. ✅ Register admin account
2. ✅ Set user as admin via tinker
3. ✅ Test scheduler: wait 1 minute, check gold/troops increased
4. ✅ Test building purchase
5. ✅ Test battle system
6. ✅ Monitor logs for errors

---

**Need help?** Open issue di GitHub!
