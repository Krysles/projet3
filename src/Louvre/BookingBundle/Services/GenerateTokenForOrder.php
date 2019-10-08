<?php

namespace Louvre\BookingBundle\Services;

class GenerateTokenForOrder
{
    public function generateToken() {
        $token = bin2hex(openssl_random_pseudo_bytes(32));
        return $token;
    }
}