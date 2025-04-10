<?php

namespace app\components\niloosoft\niloosoft\api;
use yii;

/**
 * Handles the security with Niloosoft Services 
 */
if (!class_exists('NlsSecurity')) {

    class NlsSecurity
    {
        private const ACTION_LOGIN = '/Login';

        private $auth;
        private $domain;
        private $userName;
        private $password;
        private $applicationId;
        private $fetcher;
        private $options;

        public function __construct($fetcher, $options)
        {
            try {
                $this->fetcher = $fetcher;
                $this->options = $options;
                if (!$this->options || !$this->options instanceof NlsOptions)
                    throw new \InvalidArgumentException('Missing Options');

                $this->auth = $this->options->getAuthData();

                if (!$this->isAuth()) {
                    $this->domain = $this->options->getDomain();
                    $this->userName = $this->options->getUser();
                    $this->password = $this->options->getPassword();
                    $this->applicationId = $this->options->getConsumerKey();

                    $this->authenticate();
                }
            } catch (\Throwable $th) {
                Yii::error($th->getMessage());
                $this->auth = null;
            }
        }

        private function getLoginData()
        {
            if (!$this->domain || !$this->userName || !$this->password)
                throw new \InvalidArgumentException('Missing Credentials');

            return [
                'userName' => "$this->domain\\$this->userName",
                'password' => $this->password,
            ];
        }

        /**
         * Authenticate the user against the service and gets an Auth object
         * @return object | false auth with user data and expiration time
         * @throws \exception \app\modules\niloos\models\Exception
         */
        private function authenticate()
        {
            // 1. return true if the user is authenticated
            if ($this->isAuth())
                return $this->auth;

            try {
                if (!$this->fetcher)
                    throw new \Exception('Security fetcher was not set');
                // 3. use Login 
                $this->fetcher->setHeaders($this->getHeaders());
                $this->auth = $this->fetcher->POST(self::ACTION_LOGIN, $this->getLoginData());
                $this->options->setAuthData($this->auth);
                return $this->auth;
            } catch (\Throwable $th) {
                $this->auth = false;
                return false;
            }
        }

        /**
         * @return array the headers for the API request. 
         * This method set the headers depending on the isAuth state.
         */
        public function getHeaders()
        {
            $headers = [
                'Content-Type: application/json',
                'TransactionCode: ' . NlsHelper::newGuid(),
                'NiloosoftCred1: ' . ($this->auth ? $this->auth->usernameToken : "$this->domain\\$this->userName"),
                'NiloosoftCred2: ' . ($this->auth ? $this->auth->passwordToken : "$this->password"),
            ];
            if (!$this->isAuth()) {
                $headers[] = 'NiloosoftCred0: ' . $this->applicationId;
            }

            return $headers;
        }

        /**
         * Checks if the app is authenticated and the auth is valid (not expired)
         * if not valid Authenticates
         * 
         * @return bool true if authenticated
         */
        public function isAuth()
        {
            if (!$this->auth)
                return false;
            try {
                if (property_exists($this->auth, 'faultcode') || !property_exists($this->auth, 'usernameToken')) {
                    throw new \InvalidArgumentException('Authentication Object Error');
                }

                // Check if expired    
                $expTime = \DateTime::createFromFormat("d/m/Y H:i:s", explode("^^^^", $this->auth->usernameToken)[1]);
                if ($expTime->getTimestamp() < time())
                    throw new \Exception('Authentication Object Error');
            } catch (\Exception $e) {
                $this->auth = false;
                return false;
            }
            return true;
        }

        /**
         * @var boolean $forceNew if true get new authentication tokens from the service.
         * otherwise try to retrieve the saved token from options.
         * If not Auth uses the authentication method to retrieve new token.
         */
        public function getAuth($forceNew = false)
        {
            if (!$forceNew && $this->isAuth())
                return $this->auth;

            $res = $this->authenticate();

            return $res;
        }
    }
}
