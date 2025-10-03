# 수익형 워드프레스 블로그 - Hueman Custom Theme

포트 8585로 실행되는 Docker 기반 수익형 워드프레스 블로그입니다. Jetpack 플러그인과 CDN이 활성화된 Hueman 스타일의 현대적인 디자인을 제공합니다.

## 🌟 주요 기능

- **현대적인 Hueman 디자인**: 첨부된 이미지 스타일의 매거진형 레이아웃
- **수익화 최적화**: 다양한 광고 위치와 AdSense 통합
- **SEO 친화적**: Yoast SEO와 구조화된 데이터 지원
- **성능 최적화**: CDN, 캐싱, 이미지 압축
- **반응형 디자인**: 모바일, 태블릿, 데스크톱 완벽 지원
- **소셜 미디어 통합**: 공유 버튼과 소셜 로그인

## 🚀 빠른 시작

### 1. Docker로 실행

```bash
# 리포지토리 클론
git clone <repository-url>
cd blog-wordpress

# Docker 컨테이너 실행
docker-compose up -d

# 브라우저에서 접속
http://localhost:8585
```

### 2. 초기 설정

1. **WordPress 설치**
   - 데이터베이스: `wordpress_blog`
   - 사용자명: `wordpress`
   - 비밀번호: `wordpress_password_2024`

2. **테마 활성화**
   - 외모 > 테마에서 "Hueman Custom Blog" 선택

3. **필수 플러그인 설치**
   - 도구 > Essential Plugins 메뉴에서 원클릭 설치

## 📁 프로젝트 구조

```
blog-wordpress/
├── docker-compose.yml          # Docker 설정
├── uploads.ini                 # PHP 업로드 설정
├── wp-content/
│   ├── themes/
│   │   └── hueman-custom/      # 커스텀 테마
│   │       ├── style.css       # 메인 스타일시트
│   │       ├── index.php       # 홈페이지 템플릿
│   │       ├── single.php      # 단일 포스트 템플릿
│   │       ├── header.php      # 헤더 템플릿
│   │       ├── footer.php      # 푸터 템플릿
│   │       ├── sidebar.php     # 사이드바 템플릿
│   │       ├── functions.php   # 테마 기능
│   │       └── js/
│   │           └── theme.js    # 커스텀 JavaScript
│   └── plugins/
│       └── hueman-essential-plugins.php  # 필수 플러그인 관리
└── README.md
```

## 🎨 테마 특징

### 디자인 요소

- **헤더**: 그라디언트 배경의 브랜딩 섹션
- **네비게이션**: 고정형 메인 메뉴
- **포스트 카드**: 호버 효과가 있는 현대적인 카드 레이아웃
- **사이드바**: 소셜 팔로우, 추천 포스트, 인기 글
- **푸터**: 깔끔한 정보 및 링크 섹션

### 반응형 브레이크포인트

- **데스크톱**: 1200px 이상
- **태블릿**: 968px - 1199px
- **모바일**: 767px 이하

## 💰 수익화 설정

### 1. Google AdSense

1. Google AdSense 계정 생성
2. 사이트 추가 및 승인 대기
3. 외모 > Theme Options에서 광고 코드 입력:
   - 헤더 광고 (728x90)
   - 사이드바 광고 (300x250)
   - 콘텐츠 하단 광고 (728x90)
   - 푸터 광고 (728x90)

### 2. 광고 위치

```css
/* 광고 위치들 */
.ad-space          /* 콘텐츠 영역 광고 */
.ad-space-sidebar  /* 사이드바 광고 */
```

### 3. SEO 최적화

- **Yoast SEO** 플러그인 활용
- **구조화된 데이터** 자동 생성
- **XML 사이트맵** 자동 제출
- **소셜 미디어 메타태그** 지원

## 🔧 플러그인 목록

### 필수 플러그인 (자동 설치)

1. **Jetpack**: CDN, 통계, 보안
2. **Yoast SEO**: SEO 최적화
3. **WP Super Cache**: 캐싱 시스템
4. **Advanced Ads**: 광고 관리
5. **Contact Form 7**: 연락처 폼
6. **Smush**: 이미지 압축

### Jetpack 활성화 기능

- Photon CDN (이미지 최적화)
- Site Stats (방문자 통계)
- Social Sharing (소셜 공유)
- Related Posts (관련 글)
- Infinite Scroll (무한 스크롤)
- Contact Form (연락처 폼)
- XML Sitemap (사이트맵)

## 🚀 성능 최적화

### CDN 설정

1. **Jetpack Photon**: 이미지 CDN 자동 활성화
2. **외부 CDN**: Cloudflare 등 추가 설정 가능

### 캐싱 전략

```php
// WP Super Cache 권장 설정
- 캐싱: 활성화
- 압축: Gzip 활성화
- 브라우저 캐싱: 활성화
```

### 이미지 최적화

- **Smush 플러그인**: 자동 이미지 압축
- **Lazy Loading**: 스크롤 시 이미지 로딩
- **WebP 형식**: 브라우저 지원 시 자동 변환

## 🔐 보안 설정

### 기본 보안 조치

- **보안 헤더** 자동 적용
- **브루트 포스 보호** (Jetpack)
- **스팸 보호** (Akismet)
- **SSL/HTTPS** 지원

### 추천 보안 플러그인

- Wordfence Security
- Sucuri Security
- iThemes Security

## 📊 분석 및 추적

### Google Analytics

```php
// functions.php에서 설정
// 외모 > Theme Options에서 GA 코드 입력
```

### 포스트 조회수 추적

- 자동 조회수 카운팅
- 인기 글 자동 정렬
- 실시간 통계 (Jetpack)

## 🛠️ 커스터마이징

### 색상 변경

```css
/* style.css에서 주요 색상 */
:root {
    --primary-color: #3498db;
    --secondary-color: #2c3e50;
    --accent-color: #e74c3c;
}
```

### 레이아웃 수정

- `index.php`: 홈페이지 레이아웃
- `single.php`: 포스트 상세 페이지
- `sidebar.php`: 사이드바 위젯

### 기능 추가

```php
// functions.php에서 새로운 기능 추가
function custom_feature() {
    // 커스텀 기능 구현
}
add_action('init', 'custom_feature');
```

## 📱 모바일 최적화

### PWA 지원

- Service Worker 준비됨
- 오프라인 캐싱 지원
- 앱 설치 배너

### 터치 친화적 UI

- 큰 터치 타겟
- 스와이프 제스처
- 빠른 로딩

## 🌐 다국어 지원

### 준비된 기능

- WPML 호환성
- 번역 준비된 문자열
- RTL 레이아웃 지원

## 🔄 업데이트 및 유지보수

### 정기 업데이트

```bash
# Docker 이미지 업데이트
docker-compose pull
docker-compose up -d

# WordPress 플러그인 업데이트
# 관리자 패널에서 진행
```

### 백업 전략

- **데이터베이스**: 매일 자동 백업
- **파일**: 주간 전체 백업
- **UpdraftPlus** 플러그인 권장

## 🆘 문제 해결

### 자주 발생하는 문제

1. **포트 충돌**: docker-compose.yml에서 포트 변경
2. **권한 문제**: wp-content 폴더 권한 확인
3. **플러그인 충돌**: 하나씩 비활성화하여 확인

### 로그 확인

```bash
# Docker 로그
docker-compose logs -f wordpress

# WordPress 로그
tail -f wp-content/debug.log
```

## 📞 지원 및 문의

- **이슈 리포팅**: GitHub Issues
- **문서**: 이 README 파일
- **커뮤니티**: WordPress 한국 커뮤니티

## 📄 라이선스

이 프로젝트는 MIT 라이선스를 따릅니다. 자유롭게 사용, 수정, 배포하실 수 있습니다.

---

**즐거운 블로깅 되세요! 🎉**
