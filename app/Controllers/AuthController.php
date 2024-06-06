<?php

class AuthController {
    private $jwtService;
    private $db;

    public function __construct() {
        $this->jwtService = new JwtService();
        $this->db = (new Database())->getPdo();
    }

    public function login($request) {
        $data = json_decode($request->getBody(), true);
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        if (empty($email) || empty($password)) {
            return json_encode(['error' => 'Email and password are required']);
        }

        // Fetch user from database
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password'])) {
            return json_encode(['error' => 'Invalid email or password']);
        }

        // Generate JWT token
        $token = $this->jwtService->generateToken(['user_id' => $user['id']]);
        return json_encode(['token' => $token]);
    }

    public function register($request) {
        $data = json_decode($request->getBody(), true);
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        if (empty($email) || empty($password)) {
            return json_encode(['error' => 'Email and password are required']);
        }

        // Check if user already exists
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            return json_encode(['error' => 'User already exists']);
        }

        // Create new user
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        if ($stmt->execute()) {
            return json_encode(['message' => 'User registered successfully']);
        } else {
            return json_encode(['error' => 'Registration failed']);
        }
    }
}
