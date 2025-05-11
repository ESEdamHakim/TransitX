<?php
class Mailer {
    private $smtp_host;
    private $smtp_port;
    private $username;
    private $password;
    private $from_email;
    private $from_name;
    private $debug = false;
    private $socket;

    public function __construct($host, $port, $username, $password, $from_email, $from_name = '') {
        $this->smtp_host = $host;
        $this->smtp_port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->from_email = $from_email;
        $this->from_name = $from_name;
    }

    private function connect() {
        // Create SSL context
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]);

        // Connect to SMTP server with SSL context
        $errno = 0;
        $errstr = '';
        $this->socket = stream_socket_client(
            "tcp://{$this->smtp_host}:{$this->smtp_port}",
            $errno,
            $errstr,
            30,
            STREAM_CLIENT_CONNECT,
            $context
        );
        
        if (!$this->socket) {
            throw new Exception("Could not connect to SMTP server: $errstr ($errno)");
        }
        
        // Set timeout
        stream_set_timeout($this->socket, 30);
        
        // Get initial response
        $this->getResponse();
    }

    public function send($to, $subject, $message) {
        try {
            $this->connect();
            
            // Send EHLO
            $this->sendCommand("EHLO " . gethostname());
            
            // Request STARTTLS
            $this->sendCommand("STARTTLS");
            
            // Create SSL context for TLS
            $crypto_method = STREAM_CRYPTO_METHOD_TLS_CLIENT;
            if (defined('STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT')) {
                $crypto_method |= STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT;
                $crypto_method |= STREAM_CRYPTO_METHOD_TLSv1_1_CLIENT;
            }

            // Enable TLS with proper context
            if (!@stream_socket_enable_crypto($this->socket, true, $crypto_method)) {
                throw new Exception("TLS negotiation failed: " . error_get_last()['message']);
            }
            
            // After TLS, send EHLO again
            $this->sendCommand("EHLO " . gethostname());
            
            // Auth
            $this->sendCommand("AUTH LOGIN");
            $this->sendCommand(base64_encode($this->username));
            $this->sendCommand(base64_encode($this->password));
            
            // Send From
            $this->sendCommand("MAIL FROM:<{$this->from_email}>");
            
            // Send To
            $this->sendCommand("RCPT TO:<$to>");
            
            // Send Data
            $this->sendCommand("DATA");
            
            // Construct headers
            $headers = [];
            $headers[] = "MIME-Version: 1.0";
            $headers[] = "Content-type: text/html; charset=utf-8";
            $headers[] = "From: " . ($this->from_name ? "{$this->from_name} <{$this->from_email}>" : $this->from_email);
            $headers[] = "To: $to";
            $headers[] = "Subject: $subject";
            $headers[] = "Date: " . date("r");
            $headers[] = "X-Mailer: PHP/" . phpversion();
            
            $email = implode("\r\n", $headers) . "\r\n\r\n" . $message . "\r\n.";
            $this->sendCommand($email);
            
            // Close connection
            $this->sendCommand("QUIT");
            
            return true;
        } catch (Exception $e) {
            if ($this->debug) {
                echo "ERROR: " . $e->getMessage() . "\n";
            }
            throw $e;
        }
    }
    
    private function sendCommand($command) {
        if ($this->debug) {
            echo "SEND: $command\n";
        }
        fwrite($this->socket, $command . "\r\n");
        return $this->getResponse();
    }
    
    private function getResponse() {
        $response = '';
        while ($line = fgets($this->socket, 515)) {
            $response .= $line;
            if (substr($line, 3, 1) == ' ') {
                break;
            }
        }
        
        if ($this->debug) {
            echo "RECV: $response";
        }
        
        if (!$response) {
            throw new Exception("No response from SMTP server");
        }
        
        $code = substr($response, 0, 3);
        if ($code[0] !== '2' && $code[0] !== '3') {
            throw new Exception("SMTP Error: $response");
        }
        
        return $response;
    }
    
    public function setDebug($debug) {
        $this->debug = $debug;
    }
    
    public function __destruct() {
        if ($this->socket) {
            fclose($this->socket);
        }
    }
}
