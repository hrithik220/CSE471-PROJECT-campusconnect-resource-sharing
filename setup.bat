@echo off
echo ========================================
echo   CampusShare - Hrithik Module 1 & 2
echo   CSE471 Group 11
echo ========================================
echo.
echo [1/6] Installing dependencies...
composer install
if %errorlevel% neq 0 ( echo ERROR: composer install failed! & pause & exit /b 1 )

echo.
echo [2/6] Creating .env file...
copy .env.example .env

echo.
echo [3/6] Generating app key...
php artisan key:generate

echo.
echo [4/6] Running migrations...
php artisan migrate

echo.
echo [5/6] Seeding demo data...
php artisan db:seed

echo.
echo [6/6] Creating storage link...
php artisan storage:link

echo.
echo ========================================
echo   DONE! Starting server...
echo   Open: http://127.0.0.1:8000
echo ========================================
echo.
echo Demo accounts:
echo   fahim@campus.com / password
echo   hrithik@campus.com / password
echo   admin@campus.com / password
echo.
echo NOTE: For Google Maps to work,
echo edit .env and set GOOGLE_MAPS_API_KEY
echo.
php artisan serve
pause
