@echo off
REM Quick Setup Script for FinalProject_UAS (Windows)
REM This script will reset local repo to match remote and run migrations

echo ========================================
echo Starting FinalProject_UAS Setup...
echo ========================================
echo.

REM Step 1: Backup uncommitted changes
echo [1/7] Checking for uncommitted changes...
git status --short > temp_status.txt
for /f %%i in ("temp_status.txt") do set size=%%~zi
if %size% gtr 0 (
    echo Stashing local changes...
    git stash save "Auto-backup before setup %date% %time%"
)
del temp_status.txt

REM Step 2: Fetch latest from remote
echo.
echo [2/7] Fetching latest changes from GitHub...
git fetch fork main

REM Step 3: Reset to remote
echo.
echo [3/7] Resetting local to match remote...
git reset --hard fork/main

echo.
echo ✓ Git sync completed!

REM Step 4: Install dependencies
echo.
echo [4/7] Installing Composer dependencies...
composer install --no-interaction

echo.
echo ✓ Dependencies installed!

REM Step 5: Run migrations
echo.
echo [5/7] Running migrations...
php artisan migrate:fresh --seed --force

echo.
echo ✓ Database migrated!

REM Step 6: Clear caches
echo.
echo [6/7] Clearing caches...
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo.
echo ✓ Caches cleared!

REM Step 7: Show commands list
echo.
echo [7/7] Registering artisan commands...
php artisan list | findstr "game:"

echo.
echo ========================================
echo Setup completed successfully!
echo ========================================
echo.
echo Next Steps:
echo.
echo 1. Start scheduler (in separate terminal):
echo    php artisan schedule:work
echo.
echo 2. Start development server:
echo    php artisan serve
echo.
echo 3. Test auto-generation commands:
echo    php artisan game:generate-gold
echo    php artisan game:produce-troops
echo.
echo 4. Access the game:
echo    http://localhost:8000
echo.
echo 5. Admin panel:
echo    http://localhost:8000/admin/login
echo    Email: admin@admin.com
echo    Pass:  admin123
echo.
echo ========================================
pause
