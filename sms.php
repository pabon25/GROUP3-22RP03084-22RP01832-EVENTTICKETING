<?php
require 'vendor/autoload.php';
require_once 'Util.php';

use AfricasTalking\SDK\AfricasTalking;

class Sms {
    protected $AT;

    public function __construct() {
        $this->AT = new AfricasTalking(
            Util::AT_USERNAME,
            Util::AT_API_KEY
        );
    }

    /**
     * Send an SMS via Africa's Talking
     * @param string $message
     * @param string $to      E.164 phone number
     */
    public function sendSMS($message, $to) {
        return $this->AT->sms()->send([
            'to'      => $to,
            'message' => $message,
            'from'    => Util::SMS_SENDER
        ]);
    }
}
