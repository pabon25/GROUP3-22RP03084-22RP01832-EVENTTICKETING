<?php
require_once 'Util.php';
require_once 'Sms.php';

class Momo {
    protected $pdo;

    public function __construct() {
        $this->pdo = (new Util())->getConnection();
    }

    public function checkBalance($phone) {
        $stmt = $this->pdo->prepare(
            "SELECT balance FROM users WHERE phone_number = ?"
        );
        $stmt->execute([$phone]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['balance'] : 0;
    }

    public function sendMoney($from, $to, $amount) {
        try {
            $fee = Util::TRANSACTION_FEE;
            $total = $amount + $fee;

            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare(
                "UPDATE users 
                 SET balance = balance - ? 
                 WHERE phone_number = ? AND balance >= ?"
            );
            $stmt->execute([$total, $from, $total]);
            if ($stmt->rowCount() === 0) {
                throw new Exception("Insufficient balance");
            }

            $stmt = $this->pdo->prepare(
                "UPDATE users 
                 SET balance = balance + ? 
                 WHERE phone_number = ?"
            );
            $stmt->execute([$amount, $to]);

            $reference = Util::generateReference();
            $stmt = $this->pdo->prepare(
                "INSERT INTO transactions
                 (reference, user_phone, amount, type, status, fee)
                 VALUES (?, ?, ?, 'send', 'completed', ?)"
            );
            $stmt->execute([$reference, $from, $amount, $fee]);

            $this->pdo->commit();
            return ['status' => 'SUCCESS', 'reference' => $reference];

        } catch (Exception $e) {
            $this->pdo->rollBack();
            return ['status' => 'FAILED', 'message' => $e->getMessage()];
        }
    }
}
