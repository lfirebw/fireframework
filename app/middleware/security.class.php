<?php

/**
 * Class middleware with all programming about security system for bw
 */
class security
{
    const SS_KEY = "fireframework123";
    const SS_METHOD = 'aes-256-ctr';

	static public function checkkey($k){
		try{
			return true;
		}catch(Exception $e){
			return false;
		}
    }
    /**
     * Get private internal key of the class
     * @return string
     */
    public static function getInternalKey(){
        return self::SS_KEY;
    }
    /**
     * Encrypts (but does not authenticate) a message
     * 
     * @param string $message - plaintext message
     * @param string $key - encryption key (raw binary expected)
     * @param boolean $encode - set to TRUE to return a base64-encoded 
     * @return string (raw binary)
     */

    public static function encrypt($message, $key = false, $encode = false)
    {
        $key = $key == false ? self::SS_KEY : $key;
        $nonceSize = openssl_cipher_iv_length(self::SS_METHOD);
        $nonce = openssl_random_pseudo_bytes($nonceSize);

        $ciphertext = openssl_encrypt(
            $message,
            self::SS_METHOD,
            $key,
            OPENSSL_RAW_DATA,
            $nonce
        );

        // Now let's pack the IV and the ciphertext together
        // Naively, we can just concatenate
        if ($encode) {
            return base64_encode($nonce.$ciphertext);
        }
        return $nonce.$ciphertext;
    }

    /** 
    * @param string $message - ciphertext message
    * @param string $key - encryption key (raw binary expected)
    * @param boolean $encoded - are we expecting an encoded string?
    * @return string
    */
   public static function decrypt($message, $key = false, $encoded = false)
   {
       $key = $key == false ? self::SS_KEY : $key;
       if ($encoded) {
           $message = base64_decode($message, true);
           if ($message === false) {
               throw new Exception('Encryption failure');
           }
       }

       $nonceSize = openssl_cipher_iv_length(self::SS_METHOD);
       $nonce = mb_substr($message, 0, $nonceSize, '8bit');
       $ciphertext = mb_substr($message, $nonceSize, null, '8bit');

       $plaintext = openssl_decrypt(
           $ciphertext,
           self::SS_METHOD,
           $key,
           OPENSSL_RAW_DATA,
           $nonce
       );

       return $plaintext;
   }
}

?>