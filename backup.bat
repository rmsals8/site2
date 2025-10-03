@echo off
echo ================================
echo 블로그 백업 스크립트
echo ================================
echo.

set backup_date=%date:~0,4%%date:~5,2%%date:~8,2%_%time:~0,2%%time:~3,2%%time:~6,2%
set backup_date=%backup_date: =0%

echo 백업 날짜: %backup_date%
echo.

echo 백업 폴더 생성...
mkdir backups\%backup_date% 2>nul

echo 데이터베이스 백업...
docker exec wordpress-mysql mysqldump -u root -proot_password_2024 wordpress_blog > backups\%backup_date%\database.sql

echo wp-content 폴더 백업...
xcopy wp-content backups\%backup_date%\wp-content\ /E /I /H /Y

echo 설정 파일 백업...
copy docker-compose.yml backups\%backup_date%\
copy uploads.ini backups\%backup_date%\

echo.
echo ================================
echo 백업이 완료되었습니다!
echo 위치: backups\%backup_date%\
echo ================================

pause
