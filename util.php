<?php
class Util {
    const GO_BACK         = "98";
    const GO_TO_MAIN_MENU = "99";
    const HOST     = "localhost";
    const DBNAME   = "even";
    const USERNAME = "root";
    const PASSWORD = "";
    const USER_INITIAL_BALANCE = 50000;
    const TRANSACTION_FEE = 200;
    const AT_USERNAME = "sandbox";
    const AT_API_KEY  = "atsk_0777ca10bd8047303f61e49baa170df0eb7c259f0f32e11ec9b4bf5edc46a5f34a9c4af8";
    const SMS_SENDER  = "EventMint Show";

    private $pdo;

    public function __construct() {
        $dsn = "mysql:host=".self::HOST.";dbname=".self::DBNAME;
        $this->pdo = new PDO(
            $dsn,
            self::USERNAME,
            self::PASSWORD,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    /** @return PDO */
    public function getConnection() {
        return $this->pdo;
    }
    public static function formatAmount($amt) {
        return number_format($amt, 0) . " RWF";
    }

    public static function generateReference() {
        return uniqid('EVENTMINT-');
    }

     public static function sanitizeInput($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

}
