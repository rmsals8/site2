# CloudType Dockerfile 배포 가이드

## 중요! CloudType에서 Dockerfile로 배포하기

CloudType에서는 **Dockerfile만 사용**하여 배포하고, 데이터베이스는 CloudType의 별도 서비스를 사용합니다.

## CloudType 배포 설정

### 1단계: 데이터베이스 서비스 생성 (이미 완료됨)

CloudType에서 MySQL 데이터베이스 서비스를 생성하면 다음과 같은 정보를 받습니다:
- Host: svc.sel4.cloudtype.app:30333
- User: rmsals
- Password: 1q2w3e
- Database: blog1

### 2단계: WordPress 프로젝트 설정

CloudType 대시보드에서:

1. **프로젝트 생성**
   - 저장소: 이 GitHub 저장소 연결
   - 빌드 설정: Dockerfile
   - 포트: 8080

2. **환경변수 설정** (중요!)
   
   CloudType의 환경변수 섹션에 다음을 추가:

   ```
   WORDPRESS_DB_HOST=svc.sel4.cloudtype.app:30333
   WORDPRESS_DB_USER=rmsals
   WORDPRESS_DB_PASSWORD=1q2w3e
   WORDPRESS_DB_NAME=blog1
   ```

   추가 설정 (선택사항):
   ```
   WORDPRESS_CONFIG_EXTRA=define('WP_MEMORY_LIMIT', '256M'); define('WP_MAX_MEMORY_LIMIT', '512M'); define('FS_METHOD', 'direct');
   ```

3. **배포 시작**

### 3단계: 배포 확인

배포 로그에서 다음 메시지를 확인:

```
=== WordPress Docker Entrypoint ===
Waiting for database at svc.sel4.cloudtype.app:30333...
Database is ready!
Creating wp-config.php from environment variables...
Database config: Host=svc.sel4.cloudtype.app:30333, Name=blog1, User=rmsals
wp-config.php created successfully!
Setting permissions...
=== Starting Apache ===
```

## 로컬 개발 환경

로컬에서는 docker-compose.yml을 사용합니다:

```bash
# 시작
docker-compose up -d

# 또는 배치 파일 사용
start-blog.bat

# 종료
stop-blog.bat
```

## 파일 구조

```
blog-wordpress/
├── Dockerfile                      # CloudType 배포용 (포트 8080)
├── docker-entrypoint.sh           # 데이터베이스 연결 및 설정 스크립트
├── docker-compose.yml             # 로컬 개발용 (포트 8585)
├── docker-compose.cloudtype.yml   # 사용하지 않음 (참고용)
├── wp-content/                    # WordPress 테마, 플러그인, 업로드 파일
└── uploads.ini                    # PHP 업로드 제한 설정
```

## 중요한 포인트

### docker-entrypoint.sh의 역할

1. **데이터베이스 연결 대기**: MySQL이 준비될 때까지 최대 60초 기다립니다
2. **wp-config.php 자동 생성**: 환경변수를 사용해서 WordPress 설정 파일을 만듭니다
3. **보안 키 생성**: WordPress API에서 자동으로 보안 키를 받아옵니다
4. **권한 설정**: 파일과 폴더 권한을 올바르게 설정합니다

### Dockerfile의 역할

1. **WordPress 베이스 이미지 사용**: 최신 WordPress를 기반으로 합니다
2. **필수 패키지 설치**: mysql-client를 포함한 필요한 도구들을 설치합니다
3. **포트 변경**: Apache를 80번 포트에서 8080번 포트로 변경합니다 (CloudType 요구사항)
4. **wp-content 복사**: 기존 테마, 플러그인, 업로드 파일을 이미지에 포함합니다
5. **엔트리포인트 설정**: 컨테이너 시작 시 docker-entrypoint.sh를 실행합니다

## 문제 해결

### "Error establishing a database connection" 오류

**원인**: 환경변수가 설정되지 않았거나 데이터베이스 정보가 틀렸습니다.

**해결 방법**:
1. CloudType 대시보드에서 환경변수가 올바르게 설정되었는지 확인
2. 데이터베이스 서비스가 실행 중인지 확인
3. 데이터베이스 호스트, 포트, 사용자명, 비밀번호가 정확한지 확인

### 로그 확인 방법

CloudType 대시보드에서 컨테이너 로그를 확인:
- "Waiting for database" 메시지가 반복되면 데이터베이스 연결 정보가 틀렸거나 데이터베이스가 실행되지 않은 것
- "Database is ready!" 메시지가 나타나면 정상
- "wp-config.php created successfully!" 메시지가 나타나면 설정 완료

## 환경변수 상세 설명

- `WORDPRESS_DB_HOST`: 데이터베이스 주소와 포트 (예: svc.sel4.cloudtype.app:30333)
- `WORDPRESS_DB_USER`: 데이터베이스 사용자명 (예: rmsals)
- `WORDPRESS_DB_PASSWORD`: 데이터베이스 비밀번호 (예: 1q2w3e)
- `WORDPRESS_DB_NAME`: 데이터베이스 이름 (예: blog1)
- `WORDPRESS_CONFIG_EXTRA`: 추가 WordPress 설정 (선택사항)

## 주의사항

1. **비밀번호 보안**: 실제 운영 환경에서는 더 강력한 비밀번호를 사용하세요
2. **wp-content 백업**: 중요한 테마, 플러그인, 업로드 파일은 정기적으로 백업하세요
3. **데이터베이스 백업**: CloudType의 데이터베이스 백업 기능을 활성화하세요
4. **업로드 제한**: uploads.ini에서 업로드 파일 크기를 조정할 수 있습니다
