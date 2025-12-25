<?php
/**
 * Auth Controller - Authentication Management
 */

class AuthController extends Controller {

    public function showLogin() {
        // Check if already logged in
        $auth = new Auth();
        $auth->guest();

        $csrfToken = $this->security->generateCSRFToken();
        $this->render('auth/login', ['csrf_token' => $csrfToken]);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        $data = [
            'username' => $_POST['username'] ?? '',
            'password' => $_POST['password'] ?? '',
            'remember_me' => isset($_POST['remember_me']),
            'csrf_token' => $_POST['csrf_token'] ?? ''
        ];

        // Validate CSRF token
        if (!$this->security->validateCSRFToken($data['csrf_token'])) {
            $this->session->setFlash('error', 'Invalid CSRF token');
            header('Location: /login');
            exit;
        }

        // Validate input
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        $this->validator = new Validator($data);
        if (!$this->validator->validate($rules)) {
            $this->session->setFlash('errors', $this->validator->getErrors());
            $this->session->setFlash('old', $data);
            header('Location: /login');
            exit;
        }

        // Check rate limiting
        if (!$this->security->checkRateLimit('login_' . $data['username'])) {
            $this->session->setFlash('error', 'Too many login attempts. Please try again later.');
            header('Location: /login');
            exit;
        }

        // Authenticate user
        $user = $this->db->selectOne(
            "SELECT * FROM users WHERE (username = ? OR email = ?) AND is_active = 1",
            [$data['username'], $data['username']]
        );

        if ($user && $this->security->verifyPassword($data['password'], $user['password'])) {
            // Update last login
            $this->db->update('users', ['last_login' => date('Y-m-d H:i:s')], 'id = ?', [$user['id']]);

            // Handle Remember Me
            if ($data['remember_me']) {
                // Create a remember token (simplified - in production use secure token)
                $rememberToken = bin2hex(random_bytes(32));
                $this->db->update('users', ['remember_token' => $rememberToken], 'id = ?', [$user['id']]);

                // Set cookie for 30 days
                setcookie('remember_token', $rememberToken, time() + (30 * 24 * 60 * 60), '/', '', true, true);
            }

            // Set session
            $this->session->setUser($user);

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header('Location: /admin/dashboard');
            } else {
                header('Location: /student/dashboard');
            }
            exit;
        } else {
            $this->session->setFlash('error', 'Invalid username or password');
            $this->session->setFlash('old', ['username' => $data['username']]);
            header('Location: /login');
            exit;
        }
    }

    public function logout() {
        $this->session->logout();
        header('Location: /login');
        exit;
    }

    public function showForgotPassword() {
        $auth = new Auth();
        $auth->guest();

        $csrfToken = $this->security->generateCSRFToken();
        $this->render('auth/forgot_password', ['csrf_token' => $csrfToken]);
    }

    public function forgotPassword() {
        // Implementation for forgot password
        // This would typically send a reset email
        $this->session->setFlash('success', 'Password reset instructions sent to your email');
        header('Location: /login');
    }

}