<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 *
 *  @author    Radu Vasile Catalin
 *  @copyright 2020-2020 Any Media Development
 *  @license   AFL
 */

//adapted for presta 1.6 and 1.7
class Csrf
{
    private $cookie;
    public function __construct()
    {
        $this->cookie = Context::getContext()->cookie;
    }
    //Link: https://www.wikihow.com/Prevent-Cross-Site-Request-Forgery-(CSRF)-Attacks-in-PHP
    public function getTokenId()
    {
        if ($this->cookie->__isset('token_id')) {
            return $this->cookie->__get('token_id');
        } else {
            $token_id = $this->random(10);
            $this->cookie->__set('token_id', $token_id);
            return $token_id;
        }
    }

    public function getToken()
    {
        if ($this->cookie->__isset('token_value')) {
            return $this->cookie->__get('token_value');
        } else {
            $token = hash('sha256', $this->random(500));
            $this->cookie->__set('token_value', $token);
            return $token;
        }
    }

    public function checkValid($ajax = false)
    {
        if ($ajax && Tools::getValue('sendsms_security') == $this->getToken()) {
            //sendsms_security predefined value in ajax
            return true;
        }
        if (Tools::getIsset($this->getTokenId()) && Tools::getValue($this->getTokenId()) == $this->getToken()) {
            return true;
        }
        return false;
    }

    //No need for this
    // public function form_names($names, $regenerate)
    // {
    //     $value = array();
    //     foreach ($names as $n) {
    //         if ($regenerate == true) {
    //             unset($_SESSION[$n]);
    //         }
    //         $s = isset($_SESSION[$n]) ? $_SESSION[$n] : $this->random(10);
    //         $_SESSION[$n] = $s;
    //         $values[$n] = $s;
    //     }
    //     return $values;
    // }

    public function random($len)
    {
        if (function_exists('openssl_random_pseudo_bytes')) {
            $byteLen = (int)(($len / 2) + 1);
            $return = Tools::substr(bin2hex(openssl_random_pseudo_bytes($byteLen)), 0, $len);
        } elseif (@is_readable('/dev/urandom')) {
            $f = fopen('/dev/urandom', 'r');
            $urandom = fread($f, $len);
            fclose($f);
            $return = '';
        }

        if (empty($return)) {
            for ($i = 0; $i < $len; ++$i) {
                if (!isset($urandom)) {
                    if ($i % 2 == 0) {
                        mt_srand(time() % 2147 * 1000000 + (float)microtime() * 1000000);
                    }
                    $rand = 48 + mt_rand() % 64;
                } else {
                    $rand = 48 + ord($urandom[$i]) % 64;
                }

                if ($rand > 57) {
                    $rand += 7;
                }
                if ($rand > 90) {
                    $rand += 6;
                }
                if ($rand == 123) {
                    $rand = 52;
                }
                if ($rand == 124) {
                    $rand = 53;
                }
                $return .= chr($rand);
            }
        }

        return $return;
    }
}
