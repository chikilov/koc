<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Aes256Util {

    static public $defaultKey = 'abcdefghijklmnopqrstuvwxyz123456';
//32bytes
    static public function enc( $string, $key = NULL ) {
        $key = $key == NULL ? self::$defaultKey : $key;
        if( function_exists('openssl_encrypt') ) {
            return base64_encode(openssl_encrypt($string, "aes-256-cbc", $key, true, str_repeat(chr(0), 16)));
        } else {
            $padSize = 16 - (strlen ($string) % 16) ;
            $value = $string.str_repeat (chr ($padSize), $padSize) ;
            $output = mcrypt_encrypt (MCRYPT_RIJNDAEL_128, $key, $value, MCRYPT_MODE_CBC, str_repeat(chr(0),16)) ;
            return base64_encode ($output) ;
        }
    }
    static public function dec( $encrypted_string, $key = NULL ) {
        $key = $key == NULL ? self::$defaultKey : $key;
        if( function_exists( 'openssl_decrypt') ) {
            return openssl_decrypt(base64_decode($encrypted_string), "aes-256-cbc", $key, true, str_repeat(chr(0), 16));
        } else {
            $value = base64_decode ( $encrypted_string ) ;
            $output = mcrypt_decrypt ( MCRYPT_RIJNDAEL_128, $key, $value, MCRYPT_MODE_CBC, str_repeat(chr(0),16) ) ;
            $valueLen = strlen ( $output ) ;
            if ( $valueLen % 16 > 0 ) {
                $output = "";
            }
            $padSize = ord ($output{$valueLen - 1}) ;
            if ( ($padSize < 1) or ($padSize > 16) ) {
                $output = "";
// Check padding.
            }
            for ($i = 0; $i < $padSize; $i++) {
                if ( ord ($output{$valueLen - $i - 1}) != $padSize ) {
                    $output = "";
                }
            }
            $output = substr ($output, 0, $valueLen - $padSize) ;
            return $output;
        }
    }
}

?>
