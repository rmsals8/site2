@echo off
echo ================================
echo 수익형 워드프레스 블로그 시작
echo ================================
echo.

echo Docker 컨테이너를 시작합니다...
docker-compose up -d

echo.
echo 컨테이너 상태를 확인합니다...
docker-compose ps

echo.
echo ================================
echo 블로그가 시작되었습니다!
echo.
echo 접속 URL:
echo   - 블로그: http://localhost:8585
echo   - 관리자: http://localhost:8585/wp-admin
echo   - phpMyAdmin: http://localhost:8586
echo.
echo 기본 계정 정보:
echo   - 데이터베이스: wordpress_blog
echo   - 사용자명: wordpress
echo   - 비밀번호: wordpress_password_2024
echo.
echo WordPress 설치를 완료한 후:
echo 1. 테마를 'Hueman Custom Blog'로 변경
echo 2. 도구 > Essential Plugins에서 플러그인 설치
echo 3. 외모 > Theme Options에서 광고 설정
echo ================================

pause
