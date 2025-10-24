<?php

/**
 * PHP Email Form
 * A simple PHP class for sending emails
 * Based on BootstrapMade template
 */

class PHP_Email_Form {
    public $ajax = false;
    public $to;
    public $from_name;
    public $from_email;
    public $subject;
    public $smtp = null;
    private $messages = array();

    public function add_message($message, $label = '', $length = 0) {
        $this->messages[] = array(
            'message' => $message,
            'label' => $label,
            'length' => $length
        );
    }

    public function send() {
        // Validate required fields
        if (empty($this->to) || empty($this->from_email) || empty($this->subject)) {
            return $this->ajax ? json_encode(array('error' => 'Missing required fields')) : 'Missing required fields';
        }

        // Build email content
        $headers = "From: " . $this->from_name . " <" . $this->from_email . ">\r\n";
        $headers .= "Reply-To: " . $this->from_email . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $body = "<html><body>";
        $body .= "<h2>" . htmlspecialchars($this->subject) . "</h2>";
        foreach ($this->messages as $msg) {
            $body .= "<p><strong>" . htmlspecialchars($msg['label']) . ":</strong> " . nl2br(htmlspecialchars($msg['message'])) . "</p>";
        }
        $body .= "</body></html>";

        // Send email
        if ($this->smtp) {
            // Use SMTP if configured
            return $this->send_smtp($headers, $body);
        } else {
            // Use PHP mail function
            if (mail($this->to, $this->subject, $body, $headers)) {
                return $this->ajax ? 'OK' : 'Message sent successfully';
            } else {
                return $this->ajax ? json_encode(array('error' => 'Failed to send message')) : 'Failed to send message';
            }
        }
    }

    private function send_smtp($headers, $body) {
        // Basic SMTP implementation (requires PHPMailer or similar, but keeping simple)
        // For now, fall back to mail() as SMTP config is not fully implemented
        if (mail($this->to, $this->subject, $body, $headers)) {
            return $this->ajax ? json_encode(array('success' => 'Message sent successfully')) : 'Message sent successfully';
        } else {
            return $this->ajax ? json_encode(array('error' => 'Failed to send message')) : 'Failed to send message';
        }
    }
}

?>
