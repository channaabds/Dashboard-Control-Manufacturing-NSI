@echo off
set projectPath=E:\magang-batch5\dashboard
set logFile=%projectPath%\server.log

cd %projectPath%

rem Cek apakah server sedang berjalan
netstat -ano | findstr :8080
if %errorlevel%==0 (
    echo Server Laravel sedang berjalan.
) else (
    echo Server Laravel berhenti, memulai ulang...
    start /b php artisan serve --host=192.168.10.75 --port=8080 > %logFile% 2>&1
)
