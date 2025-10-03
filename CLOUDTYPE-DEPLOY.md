# CloudType 배포 가이드

## 중요! CloudType에서 배포하는 방법

CloudType에 배포할 때는 반드시 **docker-compose.cloudtype.yml** 파일을 사용해야 합니다.

### CloudType 배포 설정

1. **CloudType 프로젝트 설정**
   - 저장소: 이 GitHub 저장소 연결
   - 빌드 설정: Docker Compose
   - Compose 파일: `docker-compose.cloudtype.yml` 선택
   - 포트: `8080`

2. **환경변수 (자동 설정됨)**
   ```
   WORDPRESS_DB_HOST=db:3306
   WORDPRESS_DB_USER=wordpress
   WORDPRESS_DB_PASSWORD=wordpress_password_2024
   WORDPRESS_DB_NAME=wordpress_blog
   ```

3. **배포 후 접속**
   - CloudType에서 제공하는 URL로 접속
   - 예: https://port-0-site-xxx.cloudtype.app

### 파일 설명

- `docker-compose.yml` - 로컬 개발용 (포트 8585)
- `docker-compose.cloudtype.yml` - CloudType 배포용 (포트 8080)
- `Dockerfile` - WordPress 이미지 빌드
- `docker-entrypoint.sh` - 데이터베이스 연결 및 설정 스크립트

### 로컬 개발 (Windows)

로컬에서 테스트할 때는:

```bash
# 시작
start-blog.bat

# 종료
stop-blog.bat
```

### 문제 해결

**"Error establishing a database connection" 오류가 발생하면:**

1. CloudType에서 `docker-compose.cloudtype.yml` 파일을 사용하고 있는지 확인
2. 두 서비스(wordpress, db)가 모두 실행 중인지 확인
3. 로그에서 데이터베이스 연결 대기 메시지 확인:
   ```
   Waiting for database at db...
   Database is ready!
   ```

**데이터베이스가 준비되지 않았다면:**
- CloudType의 컨테이너 로그를 확인
- MySQL 서비스가 정상적으로 시작되었는지 확인
- healthcheck가 통과되었는지 확인

### 주의사항

- 비밀번호는 실제 운영 환경에서 반드시 변경하세요
- wp-content/uploads 폴더의 데이터는 볼륨에 저장됩니다
- 데이터베이스 데이터도 볼륨에 영구 저장됩니다
