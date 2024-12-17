# ELIV Domain API Client

ELIV의 도메인 리셀러 API를 PHP에서 쉽게 사용할 수 있게 해주는 클라이언트 라이브러리입니다.

## 요구사항

- PHP 8.0 이상
- cURL extension

## 사용 방법

```php
require_once 'ELIV_DomainAPI.php';

$Api = new ELIV_DomainAPI();

// 도메인 등록 가능 여부 확인
$Availability = $Api->CheckDomainAvailability('example.jp');

// 도메인 등록
$Result = $Api->CreateDomain(
    'example.jp',
    1,
    ['ATHENA.NS.CLOUDFLARE.COM', 'HUXLEY.NS.CLOUDFLARE.COM'],
    ['registrant' => '101f02b9', 'administrative' => '101f02b9']
);
```

## 제공 기능

### 도메인 관리
- 도메인 등록 가능 여부 확인
- 도메인 등록
- 도메인 정보 조회
- 도메인 삭제
- 도메인 연장
- 도메인 연장 기록 조회
- 도메인 연장 취소

### 네임서버 관리
- 네임서버 업데이트
- 네임서버 조회

### 연락처 관리
- 연락처 생성
- 연락처 삭제
- 도메인 연락처 업데이트
- 도메인 연락처 조회

## 에러 처리

API 요청 중 발생하는 모든 cURL 에러는 Exception으로 처리됩니다. try-catch 구문을 사용하여 에러를 처리하는 것을 권장합니다:

```php
try {
    $Result = $Api->CheckDomainAvailability('example.jp');
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

## 지원 및 문의

API 사용에 관한 문의사항이 있으시면 아래 연락처로 문의해 주시기 바랍니다:

- Email: team@eliv.kr
- 기술지원: https://support.eliv.kr
