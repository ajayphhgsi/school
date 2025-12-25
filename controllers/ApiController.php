<?php
/**
 * API Controller - RESTful API Endpoints
 */

class ApiController extends Controller {

    public function __construct() {
        parent::__construct();
        // API endpoints might need different authentication
        // For now, we'll keep basic auth
    }

    // Authentication endpoints
    public function login() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['username']) || !isset($data['password'])) {
            $this->json(['success' => false, 'message' => 'Username and password are required'], 400);
        }

        $user = $this->db->selectOne(
            "SELECT * FROM users WHERE (username = ? OR email = ?) AND is_active = 1",
            [$data['username'], $data['username']]
        );

        if ($user && $this->security->verifyPassword($data['password'], $user['password'])) {
            // Generate API token (simplified - in production use JWT)
            $token = bin2hex(random_bytes(32));

            // Store token (simplified - in production use proper token storage)
            $_SESSION['api_token'] = $token;
            $_SESSION['api_user'] = $user['id'];

            $this->json([
                'success' => true,
                'message' => 'Login successful',
                'token' => $token,
                'user' => [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name']
                ]
            ]);
        } else {
            $this->json(['success' => false, 'message' => 'Invalid credentials'], 401);
        }
    }

    // Student data endpoints
    public function getStudents() {
        $this->checkApiAuth();

        $page = $_GET['page'] ?? 1;
        $limit = $_GET['limit'] ?? 20;
        $offset = ($page - 1) * $limit;

        $students = $this->db->select("
            SELECT s.*, c.class_name, c.section
            FROM students s
            LEFT JOIN classes c ON s.class_id = c.id
            WHERE s.is_active = 1
            ORDER BY s.first_name, s.last_name
            LIMIT ? OFFSET ?
        ", [$limit, $offset]);

        $total = $this->db->selectOne("SELECT COUNT(*) as count FROM students WHERE is_active = 1")['count'];

        $this->json([
            'success' => true,
            'data' => $students,
            'pagination' => [
                'page' => (int)$page,
                'limit' => (int)$limit,
                'total' => (int)$total,
                'pages' => ceil($total / $limit)
            ]
        ]);
    }

    public function getStudent($id) {
        $this->checkApiAuth();

        $student = $this->db->selectOne("
            SELECT s.*, c.class_name, c.section
            FROM students s
            LEFT JOIN classes c ON s.class_id = c.id
            WHERE s.id = ? AND s.is_active = 1
        ", [$id]);

        if (!$student) {
            $this->json(['success' => false, 'message' => 'Student not found'], 404);
        }

        $this->json(['success' => true, 'data' => $student]);
    }

    // Fee management endpoints
    public function getFees() {
        $this->checkApiAuth();

        $studentId = $_GET['student_id'] ?? null;

        $query = "SELECT f.*, s.first_name, s.last_name, s.scholar_number FROM fees f LEFT JOIN students s ON f.student_id = s.id";
        $params = [];

        if ($studentId) {
            $query .= " WHERE f.student_id = ?";
            $params[] = $studentId;
        }

        $query .= " ORDER BY f.created_at DESC";

        $fees = $this->db->select($query, $params);
        $this->json(['success' => true, 'data' => $fees]);
    }

    public function getStudentFees($studentId) {
        $this->checkApiAuth();

        $fees = $this->db->select("
            SELECT f.*, fp.amount_paid, fp.payment_date, fp.payment_mode
            FROM fees f
            LEFT JOIN fee_payments fp ON f.id = fp.fee_id
            WHERE f.student_id = ?
            ORDER BY f.due_date DESC
        ", [$studentId]);

        $this->json(['success' => true, 'data' => $fees]);
    }

    // Exam endpoints
    public function getExams() {
        $this->checkApiAuth();

        $exams = $this->db->select("
            SELECT e.*, c.class_name, c.section
            FROM exams e
            LEFT JOIN classes c ON e.class_id = c.id
            WHERE e.is_active = 1
            ORDER BY e.start_date DESC
        ");

        $this->json(['success' => true, 'data' => $exams]);
    }

    public function getExamResults($examId) {
        $this->checkApiAuth();

        $results = $this->db->select("
            SELECT er.*, s.first_name, s.last_name, s.scholar_number, sub.subject_name
            FROM exam_results er
            LEFT JOIN students s ON er.student_id = s.id
            LEFT JOIN subjects sub ON er.subject_id = sub.id
            WHERE er.exam_id = ?
            ORDER BY s.first_name, s.last_name, sub.subject_name
        ", [$examId]);

        $this->json(['success' => true, 'data' => $results]);
    }

    // Attendance endpoints
    public function getAttendance() {
        $this->checkApiAuth();

        $studentId = $_GET['student_id'] ?? null;
        $classId = $_GET['class_id'] ?? null;
        $date = $_GET['date'] ?? null;

        $query = "SELECT a.*, s.first_name, s.last_name, s.scholar_number, c.class_name, c.section FROM attendance a LEFT JOIN students s ON a.student_id = s.id LEFT JOIN classes c ON a.class_id = c.id WHERE 1=1";
        $params = [];

        if ($studentId) {
            $query .= " AND a.student_id = ?";
            $params[] = $studentId;
        }

        if ($classId) {
            $query .= " AND a.class_id = ?";
            $params[] = $classId;
        }

        if ($date) {
            $query .= " AND a.attendance_date = ?";
            $params[] = $date;
        }

        $query .= " ORDER BY a.attendance_date DESC, s.first_name, s.last_name";

        $attendance = $this->db->select($query, $params);
        $this->json(['success' => true, 'data' => $attendance]);
    }

    // Reports endpoint
    public function getReports() {
        $this->checkApiAuth();

        $type = $_GET['type'] ?? 'students';

        switch ($type) {
            case 'students':
                $data = $this->db->select("SELECT COUNT(*) as total_students FROM students WHERE is_active = 1");
                break;
            case 'attendance':
                $data = $this->db->selectOne("
                    SELECT
                        COUNT(*) as total_records,
                        SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_count,
                        SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent_count,
                        ROUND(AVG(CASE WHEN status = 'present' THEN 1 ELSE 0 END) * 100, 2) as attendance_rate
                    FROM attendance
                ");
                break;
            case 'fees':
                $data = $this->db->selectOne("
                    SELECT
                        SUM(amount) as total_fees,
                        SUM(amount_paid) as total_paid,
                        SUM(amount) - SUM(amount_paid) as total_pending
                    FROM fees f
                    LEFT JOIN fee_payments fp ON f.id = fp.fee_id
                ");
                break;
            default:
                $data = ['message' => 'Invalid report type'];
        }

        $this->json(['success' => true, 'data' => $data]);
    }

    // Utility methods
    private function checkApiAuth() {
        $headers = getallheaders();
        $token = $headers['Authorization'] ?? $headers['X-API-Token'] ?? $_GET['token'] ?? null;

        if (!$token) {
            $this->json(['success' => false, 'message' => 'API token required'], 401);
        }

        // Simplified token validation (in production, validate against database)
        if (!isset($_SESSION['api_token']) || $_SESSION['api_token'] !== $token) {
            $this->json(['success' => false, 'message' => 'Invalid API token'], 401);
        }
    }

    private function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}