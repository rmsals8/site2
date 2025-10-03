@echo off
echo ================================
echo 수익형 워드프레스 블로그 중지
echo ================================
echo.

echo Docker 컨테이너를 중지합니다...
docker-compose down

echo.
echo 블로그가 중지되었습니다.
echo 데이터는 보존됩니다.
echo.
echo 다시 시작하려면 start-blog.bat를 실행하세요.

pause
