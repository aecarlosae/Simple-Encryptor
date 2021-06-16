<?php
/**
 * Simple Encryptor Class
 * 
 * NOTICE
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 **/

namespace Aecarlosae\SimpleEncryptor;

class Encryptor {
    private static $instance = null;
    private $passphrase;
    private $cipher;
    private $options;
    private $iv;
    private $hashAlgorithm;

    /**
     * Constructor
     * 
     * @param string $passphrase The passphrase
     * @param string $cipher The cipher method. See openssl_get_cipher_methods()
     * @param string $options flags OPENSSL_RAW_DATA or OPENSSL_ZERO_PADDING
     * @param string $hashAlgorithm Selected hashing algorithm
     **/
    private function __construct($passphrase, $cipher, $options, $hashAlgorithm)
    {
        $this->hashAlgorithm = $hashAlgorithm;
        $this->passphrase = hash($this->hashAlgorithm, $passphrase);
        $this->cipher = $cipher;
        $this->options = $options;
        $this->iv = substr($this->passphrase, 0, openssl_cipher_iv_length($this->cipher));
    }

    /**
     * Create a new instance or get the current one
     *
     * @param string $passphrase The passphrase
     * @param string $cipher The cipher method. See openssl_get_cipher_methods()
     * @param string $options flags OPENSSL_RAW_DATA or OPENSSL_ZERO_PADDING
     * @param string $hashAlgorithm Selected hashing algorithm
     * 
     * @throws Aecarlosae\SimpleEncryptor\Exception\UnknownCipher if $cipher is not available
     * @return Encryptor
     **/
    public static function getInstance(
        $passphrase,
        $cipher = 'AES-256-CBC',
        $options = OPENSSL_RAW_DATA,
        $hashAlgorithm = 'sha256'
    )
    {
        if (!in_array(strtolower($cipher), openssl_get_cipher_methods())) {
            throw new Exception\UnknownCipher('The given cipher method is not available');
        }

        if (self::$instance == null) {
            self::$instance = new self($passphrase, $cipher, $options, $hashAlgorithm);
        }

        return self::$instance;
    }

    /**
     * Encrypt data
     *
     * @param string $data to encrypt
     * 
     * @return string Base64 encoded
     **/
    public function encrypt($data)
    {
        $output = openssl_encrypt($data, $this->cipher, $this->passphrase, $this->options, $this->iv);

        return base64_encode($output);
    }

    /**
     * Decrypt data
     *
     * @param string $data Base64 encoded to decrypt
     * 
     * @return string
     **/
    public function decrypt($data)
    {
        return openssl_decrypt(base64_decode($data), $this->cipher, $this->passphrase, $this->options, $this->iv);
    }
}
