<?php
namespace app\components\niloosoft\Niloosoft;

use Exception;
use Yii;

/**
 * Holds application configuration. (PHP 7.4 compatible)
 * Values are loaded via the constructor and accessed via getter methods.
 */
class NlsOptions
{
    // Properties are private
    private string $restApiUrl;
    private string $consumerKey;
    private string $supplierId;
    private string $domain;
    private string $user;
    private string $password;
    private string $cvWebmail;
    private string $ccMail;
    private string $contactMail;
    private string $mailFrom;
    private string $mailFromName;
    private int $pageSize;
    private int $cacheTimeSec;
    private string $headerScript;
    private string $bodyScript;
    private string $authData;

    private string $filePath;

    /**
     * Load configuration data.
     */
    public function __construct(array $configData)
    {
        // Initialization remains the same, assigning to private properties
        $this->restApiUrl = (string) ($configData['REST_API_URL'] ?? '');
        $this->consumerKey = (string) ($configData['CONSUMER_KEY'] ?? '');
        $this->supplierId = (string) ($configData['SUPPLIER_ID'] ?? '');
        $this->domain = (string) ($configData['DOMAIN'] ?? '');
        $this->user = (string) ($configData['USER'] ?? '');
        $this->password = (string) ($configData['PASSWORD'] ?? '');

        $this->cvWebmail = (string) ($configData['CV_WEBMAIL'] ?? '');
        $this->ccMail = (string) ($configData['CC_MAIL'] ?? '');
        $this->contactMail = (string) ($configData['CONTACT_MAIL'] ?? '');
        $this->mailFrom = (string) ($configData['MAIL_FROM'] ?? '');
        $this->mailFromName = (string) ($configData['MAIL_FROM_NAME'] ?? '');

        $this->pageSize = (int) ($configData['PAGE_SIZE'] ?? 10);
        $this->cacheTimeSec = (int) ($configData['CACHE_TIME_SEC'] ?? 3600);

        $this->headerScript = (string) ($configData['HEADER_SCRIPT'] ?? '');
        $this->bodyScript = (string) ($configData['BODY_SCRIPT'] ?? '');

        $this->authData = (string) ($configData['AUTH_DATA'] ?? '');

        $this->filePath = (string) ($configData['FILE_PATH'] ?? '');
    }

    /**
     * Returns the current configuration as an associative array.
     * Useful for saving the configuration state.
     * Keys should match the expected keys in the config file.
     */
    public function toArray(): array
    {
        return [
            'REST_API_URL' => $this->getRestApiUrl(),
            'CONSUMER_KEY' => $this->getConsumerKey(),
            'SUPPLIER_ID' => $this->getSupplierId(),
            'DOMAIN' => $this->getDomain(),
            'USER' => $this->getuser(),
            'PASSWORD' => $this->getPassword(),

            'CV_WEBMAIL' => $this->getCvWebmail(),
            'CC_MAIL' => $this->getCcMail(),
            'CONTACT_MAIL' => $this->getContactMail(),
            'MAIL_FROM' => $this->getMailFrom(),
            'MAIL_FROM_NAME' => $this->getMailFromName(),

            'PAGE_SIZE' => $this->getPageSize(),
            'CACHE_TIME_SEC' => $this->getCacheTimeSec(),
            'HEADER_SCRIPT' => $this->getHeaderScript(),
            'BODY_SCRIPT' => $this->getBodyScript(),

            'AUTH_DATA' => $this->getAuthData(),
        ];
    }

    /**
     * Creates an AppConfig instance by loading data from a JSON file.
     *
     * @param string $filePath The path to the configuration file.
     * @return NlsOptions
     * @throws Exception If the file cannot be read or decoded.
     */
    public static function loadFromFile(string $filePath): NlsOptions
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            // Decide how to handle: throw error, return default config, create file?
            // Throwing an error is often safest during bootstrap.
            throw new Exception("Configuration file not found or not readable: " . $filePath);
        }

        $jsonContent = file_get_contents($filePath);
        if ($jsonContent === false) {
            throw new Exception("Failed to read configuration file: " . $filePath);
        }

        $configData = json_decode($jsonContent, true); // Decode as associative array

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Failed to decode configuration JSON: " . json_last_error_msg());
        }

        // Handle case where file is empty or JSON is just 'null' etc.
        if (!is_array($configData)) {
            $configData = []; // Treat as empty config
        }

        $configData['FILE_PATH'] = $filePath;

        return new self($configData); // Create instance using the loaded data
    }

    // --- Method for Saving ---

    /**
     * Saves the current configuration state to a JSON file.
     *
     * @param string $filePath The path to the configuration file.
     * @return bool True on success, false on failure.
     */
    public function saveToFile(string $saveFilePath = null): bool
    {
        $filePath = $saveFilePath ?? $this->filePath;
        if (!file_exists($filePath) || !is_readable($filePath)) {
            // Decide how to handle: throw error, return default config, create file?
            // Throwing an error is often safest during bootstrap.
            throw new Exception("Configuration file not found or not readable: " . $filePath);
        }

        $configData = $this->toArray();

        $jsonContent = json_encode(
            $configData,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );

        if ($jsonContent === false) {
            // Log error: json_last_error_msg()
            return false;
        }

        $result = file_put_contents($filePath, $jsonContent);

        return $result !== false;
    }


    // --- Public Getter Methods ---

    public function getRestApiUrl($default = null): string
    {
        return $this->restApiUrl ?? $default;
    }

    public function getConsumerKey($default = null): string
    {
        return $this->consumerKey ?? $default;
    }

    public function getSupplierId($default = null): string
    {
        return $this->supplierId ?? $default;
    }

    public function getDomain($default = null): string
    {
        return $this->domain ?? $default;
    }

    public function getUser($default = null): string
    {
        return $this->user ?? $default;
    }

    public function getPassword($default = null): string
    {
        //  ?? $defaultConsider security implications if you provide a direct password getter
        return $this->password ?? $default;
    }

    public function getCvWebmail($default = null): string
    {
        return $this->cvWebmail ?? $default;
    }

    public function getCcMail($default = null): string
    {
        return $this->ccMail ?? $default;
    }

    public function getContactMail($default = null): string
    {
        return $this->contactMail ?? $default;
    }

    public function getMailFrom($default = null): string
    {
        return $this->mailFrom ?? $default;
    }

    public function getMailFromName($default = null): string
    {
        return $this->mailFromName ?? $default;
    }

    public function getPageSize($default = 20): int
    {
        return $this->pageSize ?? $default;
    }

    public function getCacheTimeSec($default = 20): int
    {
        return $this->cacheTimeSec ?? $default;
    }

    public function getHeaderScript($default = null): string
    {
        return $this->headerScript ?? $default;
    }

    public function getBodyScript($default = null): string
    {
        return $this->bodyScript ?? $default;
    }

    public function getAuthData($default = null): string
    {
        return $this->authData ?? $default;
    }

    public function setAuthData($data): bool
    {
        try {
            if (!is_string($data))
                return false;
            $this->authData = $data;
            $this->saveToFile();
            return true;
        } catch (\Throwable $th) {
            Yii::error($th);
            return false;
        }
    }
}