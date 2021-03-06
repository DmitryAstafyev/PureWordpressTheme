<?php
namespace Pure\Components\webSocketServer\Common{
    class Encoding{
        public function encode($message, $messageType='text') {
            switch ($messageType) {
                case 'continuous':
                    $b1 = 0;
                    break;
                case 'text':
                    $b1 = 1;
                    break;
                case 'binary':
                    $b1 = 2;
                    break;
                case 'close':
                    $b1 = 8;
                    break;
                case 'ping':
                    $b1 = 9;
                    break;
                case 'pong':
                    $b1 = 10;
                    break;
            }
            $message        = base64_encode($message);
            $b1             += 128;
            $length         = strlen($message);
            $lengthField    = "";
            if ($length < 126) {
                $b2 = $length;
            } elseif ($length <= 65536) {
                $b2 = 126;
                $hexLength = dechex($length);
                if (strlen($hexLength)%2 == 1) {
                    $hexLength = '0' . $hexLength;
                }
                $n = strlen($hexLength) - 2;
                for ($i = $n; $i >= 0; $i=$i-2) {
                    $lengthField = chr(hexdec(substr($hexLength, $i, 2))) . $lengthField;
                }
                while (strlen($lengthField) < 2) {
                    $lengthField = chr(0) . $lengthField;
                }
            } else {
                $b2 = 127;
                $hexLength = dechex($length);
                if (strlen($hexLength)%2 == 1) {
                    $hexLength = '0' . $hexLength;
                }
                $n = strlen($hexLength) - 2;
                for ($i = $n; $i >= 0; $i=$i-2) {
                    $lengthField = chr(hexdec(substr($hexLength, $i, 2))) . $lengthField;
                }
                while (strlen($lengthField) < 8) {
                    $lengthField = chr(0) . $lengthField;
                }
            }
            return chr($b1).chr($b2).$lengthField.$message;
        }
        public function unmask($payload) {
            $length = ord($payload[1]) & 127;
            if($length == 126) {
                $masks  = substr($payload, 4, 4);
                $data   = substr($payload, 8);
            }
            elseif($length == 127) {
                $masks  = substr($payload, 10, 4);
                $data   = substr($payload, 14);
            }
            else {
                $masks  = substr($payload, 2, 4);
                $data   = substr($payload, 6);
            }
            $text = '';
            for ($i = 0; $i < strlen($data); ++$i) {
                $text .= $data[$i] ^ $masks[$i%4];
            }
            return $text;
        }
    }
}
?>