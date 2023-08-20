<?php
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
class RsaCrypt
{
    protected $private, $public;

    final public function __construct()
    {
        if (
            function_exists('openssl_get_publickey') === false ||
            function_exists('openssl_public_encrypt') === false ||
            function_exists('openssl_get_privatekey') === false ||
            function_exists('openssl_private_decrypt') === false
        ) {
            throw new RuntimeException('Not all the functions of openssl.');
        }
    }

    final public function setPublicKey($key)
    {
        if (is_null($key) || empty($key) || file_exists($key) === false) {
            throw new RuntimeException('Wrong key.');
        }

        $this->public = $key;

        return true;
    }

    final public function getPublicKey()
    {
        return is_null($this->public) ? false : $this->public;
    }

    final public function setPrivateKey($key)
    {
        if (is_null($key) || empty($key) || file_exists($key) === false) {
            throw new RuntimeException('Wrong key.');
        }

        $this->private = $key;

        return true;
    }

    final public function getPrivateKey()
    {
        return is_null($this->private) ? false : $this->private;
    }

    final public function encryptWithPublicKey($data)
    {
        if (is_null($data) || empty($data) || is_string($data) === false) {
            throw new RuntimeException('Needless to encrypt.');
        } elseif (is_null($this->public) || empty($this->public)) {
            throw new RuntimeException('You need to set the public key.');
        }

        $key = @file_get_contents($this->public);
        if ($key) {
            $key = openssl_get_publickey($key);
            openssl_public_encrypt($data, $encrypted, $key);

            return chunk_split(base64_encode($encrypted));
        }

        return false;
    }

    final public function decryptWithPrivateKey($data)
    {
        if (is_null($data) || empty($data) || is_string($data) === false) {
            throw new RuntimeException('Needless to encrypt.');
        } elseif (is_null($this->private) || empty($this->private)) {
            throw new RuntimeException('You need to set the private key.');
        }

        $key = @file_get_contents($this->private);
        if ($key) {
            $key = openssl_get_privatekey($key);
            openssl_private_decrypt(base64_decode($data), $result, $key);

            return $result;
        }
    }

    final public function encryptWithPrivateKey($data)
    {
        if (is_null($data) || empty($data) || is_string($data) === false) {
            throw new RuntimeException('Needless to encrypt.');
        } elseif (is_null($this->private) || empty($this->private)) {
            throw new RuntimeException('You need to set the private key.');
        }

        $key = @file_get_contents($this->private);
        if ($key) {
            $key = openssl_get_privatekey($key);
            openssl_private_encrypt($data, $encrypted, $key);

            return chunk_split(base64_encode($encrypted));
        }

        return false;
    }

    final public function decryptWithPublicKey($data)
    {
        if (is_null($data) || empty($data) || is_string($data) === false) {
            throw new RuntimeException('Needless to encrypt.');
        } elseif (is_null($this->public) || empty($this->public)) {
            throw new RuntimeException('You need to set the public key.');
        }

        $key = @file_get_contents($this->public);
        if ($key) {
            $key = openssl_get_publickey($key);
            openssl_public_decrypt(base64_decode($data), $result, $key);

            return $result;
        }
    }
}
