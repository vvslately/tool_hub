<?php
declare(strict_types=1);

// ✅ Start Session
session_start();

// ✅ เตรียมอ่าน raw input เพื่อดูว่าเป็น type อะไร
$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput, true);
$requestType = $input['type'] ?? null;

// ✅ ตรวจ Referer และ Session เฉพาะเมื่อไม่ใช่ check-ip
$refererHost = parse_url($_SERVER['HTTP_REFERER'] ?? '', PHP_URL_HOST);
$serverHost = $_SERVER['HTTP_HOST'] ?? '';

if (
    $requestType !== 'check-ip' && (
        !isset($_SESSION['allow_api']) || $_SESSION['allow_api'] !== true ||
        $refererHost !== $serverHost
    )
) {
    http_response_code(403);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'status' => false,
        'message' => 'NOT FOUND'
    ]);
    exit;
}

// ✅ ApiHandler Class
class ApiHandler
{
    private const EMAIL_VALIDATION_KEY_ENDPOINT = 'https://gmailver.com/php/key.php';
    private const EMAIL_VALIDATION_CHECK_ENDPOINT = 'https://gmailver.com/php/check1.php';
    private const IP_INFORMATION_ENDPOINT_PREFIX = 'https://ipwho.is/';
    private const DEFAULT_REQUEST_TIMEOUT_SECONDS = 30;

    public function handleRequest(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendJsonResponse(false, 'Invalid request method. Only POST is accepted.', null, 405);
            return;
        }

        $input = $this->getJsonInput();
        if ($input === null) return;

        $requestType = $input['type'] ?? (isset($input['mail']) && is_array($input['mail']) ? 'check-email' : null);

        if ($requestType === null) {
            $this->sendJsonResponse(false, 'Missing request type parameter.', ['received_input' => $input], 400);
            return;
        }

        switch ($requestType) {
            case 'check-email':
                $this->handleEmailCheckRequest($input);
                break;
            case 'check-ip':
                $this->handlePublicIpCheckRequest();
                break;
            default:
                $this->sendJsonResponse(false, 'Unknown API request type.', ['request_type' => $requestType], 400);
        }
    }

    private function getJsonInput(): ?array
    {
        $rawInput = file_get_contents('php://input');
        if ($rawInput === false || $rawInput === '') {
            $this->sendJsonResponse(false, 'No input data received.', null, 400);
            return null;
        }

        $input = json_decode($rawInput, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->sendJsonResponse(false, 'Invalid JSON payload: ' . json_last_error_msg(), ['raw_input' => $rawInput], 400);
            return null;
        }

        return $input;
    }

    private function handleEmailCheckRequest(array $input): void
    {
        if (!isset($input['mail']) || !is_array($input['mail']) || empty($input['mail'])) {
            $this->sendJsonResponse(false, 'Invalid or empty email list provided in "mail" parameter.', ['received_input' => $input], 400);
            return;
        }

        $validEmails = array_filter($input['mail'], fn($email) => filter_var($email, FILTER_VALIDATE_EMAIL));

        if (empty($validEmails)) {
            $this->sendJsonResponse(false, 'No valid email addresses found.', ['original_emails' => $input['mail']], 400);
            return;
        }

        $keyApiResponse = $this->executeApiPostRequest(self::EMAIL_VALIDATION_KEY_ENDPOINT, ['mail' => $validEmails]);
        if (!$this->isValidApiResponse($keyApiResponse, 'message')) {
            $this->sendJsonResponse(false, 'Failed to obtain a license key.', $keyApiResponse, 502);
            return;
        }

        $licenseKey = trim((string)$keyApiResponse['message']);

        $emailCheckApiResponse = $this->executeApiPostRequest(self::EMAIL_VALIDATION_CHECK_ENDPOINT, [
            'mail' => $validEmails,
            'key' => $licenseKey,
            'fastCheck' => false
        ]);

        $formattedResults = [];

        if (isset($emailCheckApiResponse['data']) && is_array($emailCheckApiResponse['data'])) {
            foreach ($emailCheckApiResponse['data'] as $item) {
                if (isset($item['email'], $item['status'])) {
                    $status = strtolower((string)$item['status']);
                    if (in_array($status, ['unknown', 'unregistered'], true)) {
                        $status = 'not_exit';
                    }
                    $formattedResults[] = [
                        'email' => (string)$item['email'],
                        'status' => $status
                    ];
                }
            }
            $this->sendJsonResponse(true, 'Email check completed successfully.', $formattedResults);
        } else {
            $fallbackResults = array_map(fn($email) => ['email' => $email, 'status' => 'unknown'], $validEmails);
            $this->sendJsonResponse(false, 'No data returned or invalid response.', $fallbackResults, 502);
        }
    }

    private function handlePublicIpCheckRequest(): void
    {
        $clientIp = $this->determineUserIpAddress();
        if ($clientIp === 'UNKNOWN') {
            $this->sendJsonResponse(false, 'Unable to determine client IP address.', null, 500);
            return;
        }

        $ipInfoUrl = self::IP_INFORMATION_ENDPOINT_PREFIX . urlencode($clientIp) . '?output=json';
        $ipInfoResponse = $this->executeApiGetRequest($ipInfoUrl);

        $data = [
            'ip' => $ipInfoResponse['ip'] ?? $clientIp,
            'country' => $ipInfoResponse['country'] ?? 'N/A',
            'region' => $ipInfoResponse['region'] ?? 'N/A',
            'city' => $ipInfoResponse['city'] ?? 'N/A',
            'latitude' => $ipInfoResponse['latitude'] ?? 'N/A',
            'longitude' => $ipInfoResponse['longitude'] ?? 'N/A',
            'timezone' => is_array($ipInfoResponse['timezone'] ?? null)
                ? ($ipInfoResponse['timezone']['id'] ?? 'N/A')
                : ($ipInfoResponse['timezone'] ?? 'N/A'),
            'isp' => $ipInfoResponse['connection']['isp'] ?? 'N/A',
            'organization' => $ipInfoResponse['connection']['org'] ?? 'N/A',
            'userAgent' => $_SERVER['HTTP_USER_AGENT'] ?? 'N/A'
        ];

        $success = isset($ipInfoResponse['success']) && $ipInfoResponse['success'] === true;

        $this->sendJsonResponse($success, $success ? 'Public IP information fetched successfully.' : 'Partial IP info available.', $data, $success ? 200 : 206);
    }

    private function determineUserIpAddress(): string
    {
        $ipSources = [
            $_SERVER['HTTP_CLIENT_IP'] ?? null,
            $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null,
            $_SERVER['REMOTE_ADDR'] ?? null
        ];

        foreach ($ipSources as $source) {
            if ($source === null) continue;
            $ips = explode(',', $source);
            $potentialIp = trim($ips[0]);
            if (filter_var($potentialIp, FILTER_VALIDATE_IP)) {
                return $potentialIp;
            }
        }

        return 'UNKNOWN';
    }

    private function isValidApiResponse($response, string $expectedKey = 'data'): bool
    {
        return is_array($response) && isset($response[$expectedKey]);
    }

    private function executeApiPostRequest(string $url, array $data): ?array
    {
        $ch = curl_init($url);
        if ($ch === false) return ['curl_error' => true, 'message' => 'cURL initialization failed'];

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_TIMEOUT => self::DEFAULT_REQUEST_TIMEOUT_SECONDS,
            CURLOPT_FAILONERROR => true
        ]);

        $responseJson = curl_exec($ch);
        $curlErrorNo = curl_errno($ch);
        $curlErrorMessage = curl_error($ch);
        curl_close($ch);

        if ($curlErrorNo !== 0 || $responseJson === false || $responseJson === '') {
            return ['curl_error' => true, 'message' => $curlErrorMessage ?: 'Empty response'];
        }

        $decoded = json_decode($responseJson, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['curl_error' => true, 'message' => 'Invalid JSON: ' . json_last_error_msg(), 'raw' => $responseJson];
        }

        return $decoded;
    }

    private function executeApiGetRequest(string $url): ?array
    {
        $ch = curl_init($url);
        if ($ch === false) return ['curl_error' => true, 'message' => 'cURL initialization failed'];

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => self::DEFAULT_REQUEST_TIMEOUT_SECONDS,
            CURLOPT_FAILONERROR => true
        ]);

        $responseJson = curl_exec($ch);
        $curlErrorNo = curl_errno($ch);
        $curlErrorMessage = curl_error($ch);
        curl_close($ch);

        if ($curlErrorNo !== 0 || $responseJson === false || $responseJson === '') {
            return ['curl_error' => true, 'message' => $curlErrorMessage ?: 'Empty response'];
        }

        $decoded = json_decode($responseJson, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['curl_error' => true, 'message' => 'Invalid JSON: ' . json_last_error_msg(), 'raw' => $responseJson];
        }

        return $decoded;
    }

    private function sendJsonResponse(bool $status, string $message, $data = [], int $httpStatusCode = 200): void
    {
        http_response_code($httpStatusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => $status,
            'message' => $message,
            'data' => $data ?? []
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
}

// ✅ เรียกใช้งาน
$apiHandler = new ApiHandler();
$apiHandler->handleRequest();
