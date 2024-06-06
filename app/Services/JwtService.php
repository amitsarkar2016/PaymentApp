<?php

use \Firebase\JWT\JWT;

class JwtService {
    private $secret;

    public function __construct() {
        $this->secret = getenv('JWT_SECRET');
    }

    public function generateToken($data) {
        $payload = [
            'iss' => "http://knightcoder.in",
            'iat' => time(),
            'exp' => time() + 60*60, // Token expires in 1 hour
            'data' => $data
        ];

        return JWT::encode($payload, $this->secret);
    }

    public function decodeToken($token) {
        return JWT::decode($token, $this->secret, ['HS256']);
    }
}
