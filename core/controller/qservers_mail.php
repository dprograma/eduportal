<?php

require_once "Mail.php";
require_once('Mail/mime.php'); 

class QserversMail {
    public $message;
    public $subject;
    public $from;
    public $to;
    public $text;

    public function __construct($message, $subject, $from, $to, $text = "") {
        $this->message = $message;
        $this->subject = $subject;
        $this->from = $from;
        $this->to = $to;
        $this->text = $text;
    }

    public function sendMail() {
        $headers = array('From' => $this->from, 'To' => $this->to, 'Subject' => $this->subject);
        $crlf = "\n";

        $mime = new Mail_mime($crlf);
        $mime->setTXTBody($this->text);
        $mime->setHTMLBody($this->message);

        $body = $mime->get();
        $headers = $mime->headers($headers);

        $host = "localhost"; 
        $username = "ketuojoken@gmail.com"; 
        $password = "oyuisxthlkunofrk"; 

        $smtp = Mail::factory('smtp', array(
            'host' => $host,
            'auth' => true,
            'username' => $username,
            'password' => $password
        ));

        $mail = $smtp->send($this->to, $headers, $body);

        if (PEAR::isError($mail)) {
            echo ("<p>" . $mail->getMessage() . "</p>");
        } else {
            echo ("<p>Message successfully sent!</p>");
        }
    }

}
