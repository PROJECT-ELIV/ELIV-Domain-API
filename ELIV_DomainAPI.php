<?php

class ELIV_DomainAPI
{
    private string $Endpoint = "https://partner-api.eliv.kr";
    private string $API_Version = "v1";
    private string $Token = "PARTNER_ID:PARTNER_SECRET";
    private array $Headers;

    public function __construct()
    {
        $this->Headers = [
            'Authorization: ' . $this->Token,
            'Content-Type: application/json'
        ];
    }

    /**
     * 도메인 등록 가능 여부 확인
     */
    public function CheckDomainAvailability(string $DomainName): array
    {
        return $this->SendRequest('GET', "/Domain/Check?DomainName={$DomainName}");
    }

    /**
     * 도메인 등록
     */
    public function CreateDomain(string $DomainName, int $Period, array $Nameservers, array $Contacts): array
    {
        $Payload = [
            'DomainName' => $DomainName,
            'Period' => (string)$Period,
            'Nameservers' => $Nameservers,
            'Contacts' => $Contacts
        ];
        
        return $this->SendRequest('POST', '/Domain/Create', $Payload);
    }

    /**
     * 도메인 정보 조회
     */
    public function GetDomainInfo(string $DomainName): array
    {
        return $this->SendRequest('GET', "/Domain/{$DomainName}");
    }

    /**
     * 도메인 삭제
     */
    public function DeleteDomain(string $DomainName, bool $Refund = false): array
    {
        return $this->SendRequest('DELETE', "/Domain/{$DomainName}" . ($Refund ? '?refund=true' : ''));
    }

    /**
     * 도메인 연장
     */
    public function RenewDomain(string $DomainName, int $Period): array
    {
        return $this->SendRequest('POST', "/Domain/{$DomainName}/Renew", ['Period' => $Period]);
    }

    /**
     * 도메인 연장 기록 조회
     */
    public function GetRenewalHistory(string $DomainName): array
    {
        return $this->SendRequest('GET', "/Domain/{$DomainName}/Renew");
    }

    /**
     * 도메인 연장 취소
     */
    public function CancelRenewal(string $DomainName, string $RenewUid, bool $Refund = false): array
    {
        $Payload = [
            'RenewUID' => $RenewUid,
            'Refund' => $Refund
        ];
        
        return $this->SendRequest('POST', "/Domain/{$DomainName}/Renew/Cancel", $Payload);
    }

    /**
     * 도메인 네임서버 업데이트
     */
    public function UpdateNameservers(string $DomainName, array $Nameservers): array
    {
        return $this->SendRequest('PUT', "/Domain/{$DomainName}/Nameservers", ['Nameservers' => $Nameservers]);
    }

    /**
     * 도메인 네임서버 조회
     */
    public function GetNameservers(string $DomainName): array
    {
        return $this->SendRequest('GET', "/Domain/{$DomainName}/Nameservers");
    }

    /**
     * 도메인 연락처 업데이트
     */
    public function UpdateContacts(string $DomainName, array $Contacts): array
    {
        return $this->SendRequest('PUT', "/Domain/{$DomainName}/Contacts", ['Contacts' => $Contacts]);
    }

    /**
     * 도메인 연락처 조회
     */
    public function GetContacts(string $DomainName): array
    {
        return $this->SendRequest('GET', "/Domain/{$DomainName}/Contacts");
    }

    /**
     * 연락처 생성
     */
    public function CreateContact(array $ContactData): array
    {
        return $this->SendRequest('POST', '/Contact/Create', $ContactData);
    }

    /**
     * 연락처 삭제
     */
    public function DeleteContact(string $ContactId): array
    {
        return $this->SendRequest('DELETE', "/Contact/{$ContactId}");
    }

    /**
     * HTTP 요청 전송 처리
     */
    private function SendRequest(string $Method, string $Endpoint, array $Payload = null): array
    {
        $Curl = curl_init();
        
        $Url = $this->Endpoint . '/' . $this->API_Version . $Endpoint;
        
        $Options = [
            CURLOPT_URL => $Url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $Method,
            CURLOPT_HTTPHEADER => $this->Headers,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ];

        if ($Payload !== null) {
            $Options[CURLOPT_POSTFIELDS] = json_encode($Payload);
        }

        curl_setopt_array($Curl, $Options);

        $Response = curl_exec($Curl);
        $Error = curl_error($Curl);

        curl_close($Curl);

        if ($Error) {
            throw new \Exception('cURL Error: ' . $Error);
        }

        return json_decode($Response, true) ?? [];
    }
}
