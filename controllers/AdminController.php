<?php
/**
 * Admin Controller - Admin Panel Management
 */

class AdminController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function dashboard() {
        // Get dashboard statistics
        $stats = [
            'total_students' => $this->db->selectOne("SELECT COUNT(*) as count FROM students WHERE is_active = 1")['count'],
            'total_classes' => $this->db->selectOne("SELECT COUNT(*) as count FROM classes WHERE is_active = 1")['count'],
            'total_events' => $this->db->selectOne("SELECT COUNT(*) as count FROM events WHERE is_active = 1")['count'],
            'total_gallery' => $this->db->selectOne("SELECT COUNT(*) as count FROM gallery WHERE is_active = 1")['count'],
            'recent_students' => $this->db->select("SELECT * FROM students ORDER BY created_at DESC LIMIT 5"),
            'upcoming_events' => $this->db->select("SELECT * FROM events WHERE event_date >= date('now') AND is_active = 1 ORDER BY event_date LIMIT 5")
        ];

        $this->render('admin/dashboard', ['stats' => $stats]);
    }

    public function students() {
        $students = $this->db->select("SELECT s.*, c.class_name, c.section FROM students s LEFT JOIN classes c ON s.class_id = c.id ORDER BY s.created_at DESC");
        $this->render('admin/students/index', ['students' => $students]);
    }

    public function createStudent() {
        $classes = $this->db->select("SELECT * FROM classes WHERE is_active = 1 ORDER BY class_name");
        $csrfToken = $this->csrfToken();
        $this->render('admin/students/create', ['classes' => $classes, 'csrf_token' => $csrfToken]);
    }

    public function storeStudent() {
        $data = [
            'scholar_number' => $_POST['scholar_number'] ?? '',
            'admission_number' => $_POST['admission_number'] ?? '',
            'admission_date' => $_POST['admission_date'] ?? '',
            'first_name' => $_POST['first_name'] ?? '',
            'middle_name' => $_POST['middle_name'] ?? '',
            'last_name' => $_POST['last_name'] ?? '',
            'date_of_birth' => $_POST['date_of_birth'] ?? '',
            'gender' => $_POST['gender'] ?? '',
            'caste_category' => $_POST['caste_category'] ?? '',
            'nationality' => $_POST['nationality'] ?? 'Indian',
            'religion' => $_POST['religion'] ?? '',
            'blood_group' => $_POST['blood_group'] ?? '',
            'village' => $_POST['village'] ?? '',
            'address' => $_POST['address'] ?? '',
            'permanent_address' => $_POST['permanent_address'] ?? '',
            'mobile' => $_POST['mobile'] ?? '',
            'email' => $_POST['email'] ?? '',
            'aadhar_number' => $_POST['aadhar_number'] ?? '',
            'samagra_number' => $_POST['samagra_number'] ?? '',
            'apaar_id' => $_POST['apaar_id'] ?? '',
            'pan_number' => $_POST['pan_number'] ?? '',
            'previous_school' => $_POST['previous_school'] ?? '',
            'medical_conditions' => $_POST['medical_conditions'] ?? '',
            'father_name' => $_POST['father_name'] ?? '',
            'mother_name' => $_POST['mother_name'] ?? '',
            'guardian_name' => $_POST['guardian_name'] ?? '',
            'guardian_contact' => $_POST['guardian_contact'] ?? '',
            'class_id' => $_POST['class_id'] ?? '',
            'csrf_token' => $_POST['csrf_token'] ?? ''
        ];

        if (!$this->checkCsrfToken($data['csrf_token'])) {
            $this->session->setFlash('error', 'Invalid CSRF token');
            $this->redirect('/admin/students/create');
        }

        $rules = [
            'scholar_number' => 'required|unique:students,scholar_number',
            'admission_number' => 'required',
            'first_name' => 'required|min:2|max:50',
            'last_name' => 'required|min:2|max:50',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'mobile' => 'required|regex:/^[0-9]{10,15}$/',
            'class_id' => 'required|numeric'
        ];

        if (!$this->validate($data, $rules)) {
            $this->session->setFlash('errors', $this->getValidationErrors());
            $this->session->setFlash('old', $data);
            $this->redirect('/admin/students/create');
        }

        // Handle file upload for photo
        $photoPath = '';
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = UPLOADS_PATH . 'students/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $fileName = uniqid() . '_' . basename($_FILES['photo']['name']);
            $targetFile = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
                $photoPath = 'students/' . $fileName;
            }
        }

        $studentData = $data;
        unset($studentData['csrf_token']);
        if ($photoPath) {
            $studentData['photo'] = $photoPath;
        }

        $studentId = $this->db->insert('students', $studentData);

        if ($studentId) {
            $this->session->setFlash('success', 'Student registered successfully');
            $this->redirect('/admin/students');
        } else {
            $this->session->setFlash('error', 'Failed to register student');
            $this->redirect('/admin/students/create');
        }
    }

    public function editStudent($id) {
        $student = $this->db->selectOne("SELECT * FROM students WHERE id = ?", [$id]);
        if (!$student) {
            $this->session->setFlash('error', 'Student not found');
            $this->redirect('/admin/students');
        }

        $classes = $this->db->select("SELECT * FROM classes WHERE is_active = 1 ORDER BY class_name");
        $csrfToken = $this->csrfToken();
        $this->render('admin/students/edit', [
            'student' => $student,
            'classes' => $classes,
            'csrf_token' => $csrfToken
        ]);
    }

    public function updateStudent($id) {
        $student = $this->db->selectOne("SELECT * FROM students WHERE id = ?", [$id]);
        if (!$student) {
            $this->session->setFlash('error', 'Student not found');
            $this->redirect('/admin/students');
        }

        // Similar to store, but for update
        $data = [
            'scholar_number' => $_POST['scholar_number'] ?? '',
            'admission_number' => $_POST['admission_number'] ?? '',
            'admission_date' => $_POST['admission_date'] ?? '',
            'first_name' => $_POST['first_name'] ?? '',
            'middle_name' => $_POST['middle_name'] ?? '',
            'last_name' => $_POST['last_name'] ?? '',
            'date_of_birth' => $_POST['date_of_birth'] ?? '',
            'gender' => $_POST['gender'] ?? '',
            'caste_category' => $_POST['caste_category'] ?? '',
            'nationality' => $_POST['nationality'] ?? 'Indian',
            'religion' => $_POST['religion'] ?? '',
            'blood_group' => $_POST['blood_group'] ?? '',
            'village' => $_POST['village'] ?? '',
            'address' => $_POST['address'] ?? '',
            'permanent_address' => $_POST['permanent_address'] ?? '',
            'mobile' => $_POST['mobile'] ?? '',
            'email' => $_POST['email'] ?? '',
            'aadhar_number' => $_POST['aadhar_number'] ?? '',
            'samagra_number' => $_POST['samagra_number'] ?? '',
            'apaar_id' => $_POST['apaar_id'] ?? '',
            'pan_number' => $_POST['pan_number'] ?? '',
            'previous_school' => $_POST['previous_school'] ?? '',
            'medical_conditions' => $_POST['medical_conditions'] ?? '',
            'father_name' => $_POST['father_name'] ?? '',
            'mother_name' => $_POST['mother_name'] ?? '',
            'guardian_name' => $_POST['guardian_name'] ?? '',
            'guardian_contact' => $_POST['guardian_contact'] ?? '',
            'class_id' => $_POST['class_id'] ?? '',
            'csrf_token' => $_POST['csrf_token'] ?? ''
        ];

        if (!$this->checkCsrfToken($data['csrf_token'])) {
            $this->session->setFlash('error', 'Invalid CSRF token');
            $this->redirect('/admin/students/edit/' . $id);
        }

        $rules = [
            'scholar_number' => 'required',
            'admission_number' => 'required',
            'first_name' => 'required|min:2|max:50',
            'last_name' => 'required|min:2|max:50',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'mobile' => 'required|regex:/^[0-9]{10,15}$/',
            'class_id' => 'required|numeric'
        ];

        if (!$this->validate($data, $rules)) {
            $this->session->setFlash('errors', $this->getValidationErrors());
            $this->session->setFlash('old', $data);
            $this->redirect('/admin/students/edit/' . $id);
        }

        // Handle file upload for photo
        $photoPath = $student['photo'];
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = UPLOADS_PATH . 'students/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $fileName = uniqid() . '_' . basename($_FILES['photo']['name']);
            $targetFile = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
                // Delete old photo if exists
                if ($student['photo'] && file_exists(UPLOADS_PATH . $student['photo'])) {
                    unlink(UPLOADS_PATH . $student['photo']);
                }
                $photoPath = 'students/' . $fileName;
            }
        }

        $studentData = $data;
        unset($studentData['csrf_token']);
        $studentData['photo'] = $photoPath;

        $updated = $this->db->update('students', $studentData, 'id = ?', [$id]);

        if ($updated) {
            $this->session->setFlash('success', 'Student updated successfully');
            $this->redirect('/admin/students');
        } else {
            $this->session->setFlash('error', 'Failed to update student');
            $this->redirect('/admin/students/edit/' . $id);
        }
    }

    public function viewStudent($id) {
        $student = $this->db->selectOne("
            SELECT s.*, c.class_name, c.section
            FROM students s
            LEFT JOIN classes c ON s.class_id = c.id
            WHERE s.id = ?
        ", [$id]);

        if (!$student) {
            $this->session->setFlash('error', 'Student not found');
            $this->redirect('/admin/students');
        }

        $this->render('admin/students/view', ['student' => $student]);
    }

    public function deleteStudent($id) {
        $student = $this->db->selectOne("SELECT * FROM students WHERE id = ?", [$id]);
        if (!$student) {
            $this->session->setFlash('error', 'Student not found');
            $this->redirect('/admin/students');
        }

        // Delete photo if exists
        if ($student['photo'] && file_exists(UPLOADS_PATH . $student['photo'])) {
            unlink(UPLOADS_PATH . $student['photo']);
        }

        $deleted = $this->db->delete('students', 'id = ?', [$id]);

        if ($deleted) {
            $this->session->setFlash('success', 'Student deleted successfully');
        } else {
            $this->session->setFlash('error', 'Failed to delete student');
        }

        $this->redirect('/admin/students');
    }

    public function classes() {
        $classes = $this->db->select("SELECT c.*, COUNT(s.id) as student_count FROM classes c LEFT JOIN students s ON c.id = s.class_id GROUP BY c.id ORDER BY c.class_name");
        $this->render('admin/classes/index', ['classes' => $classes]);
    }

    public function promoteStudents() {
        // Get all classes ordered by grade
        $classes = $this->db->select("SELECT * FROM classes WHERE is_active = 1 ORDER BY class_name");

        // Group classes by grade level for promotion logic
        $gradeGroups = [];
        foreach ($classes as $class) {
            // Extract grade from class name (e.g., "Grade 1", "Class 1", etc.)
            preg_match('/(\d+)/', $class['class_name'], $matches);
            $grade = $matches[1] ?? 0;
            $gradeGroups[$grade][] = $class;
        }

        ksort($gradeGroups); // Sort by grade

        $this->render('admin/classes/promote', [
            'classes' => $classes,
            'grade_groups' => $gradeGroups
        ]);
    }

    public function processPromotion() {
        $data = $_POST;
        $csrfToken = $data['csrf_token'] ?? '';

        if (!$this->checkCsrfToken($csrfToken)) {
            $this->session->setFlash('error', 'Invalid CSRF token');
            $this->redirect('/admin/classes/promote');
        }

        $fromClassId = $data['from_class_id'] ?? '';
        $toClassId = $data['to_class_id'] ?? '';
        $academicYear = $data['academic_year'] ?? date('Y') . '-' . (date('Y') + 1);

        if (!$fromClassId || !$toClassId) {
            $this->session->setFlash('error', 'Please select both source and target classes');
            $this->redirect('/admin/classes/promote');
        }

        // Get students from source class
        $students = $this->db->select("SELECT * FROM students WHERE class_id = ? AND is_active = 1", [$fromClassId]);

        if (empty($students)) {
            $this->session->setFlash('error', 'No students found in the selected class');
            $this->redirect('/admin/classes/promote');
        }

        // Start transaction
        $this->db->beginTransaction();

        try {
            $promotedCount = 0;

            foreach ($students as $student) {
                // Check if student meets promotion criteria (simplified - in production, check exam results)
                $canPromote = $this->checkPromotionCriteria($student['id'], $fromClassId, $academicYear);

                if ($canPromote) {
                    // Update student class
                    $this->db->update('students', [
                        'class_id' => $toClassId,
                        'updated_at' => date('Y-m-d H:i:s')
                    ], 'id = ?', [$student['id']]);

                    // Log promotion
                    $this->db->insert('audit_logs', [
                        'user_id' => $_SESSION['user']['id'] ?? 1,
                        'action' => 'student_promotion',
                        'table_name' => 'students',
                        'record_id' => $student['id'],
                        'old_values' => json_encode(['class_id' => $fromClassId]),
                        'new_values' => json_encode(['class_id' => $toClassId]),
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                    $promotedCount++;
                }
            }

            $this->db->commit();

            $this->session->setFlash('success', "Promotion completed! {$promotedCount} out of " . count($students) . " students promoted successfully.");
            $this->redirect('/admin/classes');

        } catch (Exception $e) {
            $this->db->rollback();
            $this->session->setFlash('error', 'Failed to process promotion: ' . $e->getMessage());
            $this->redirect('/admin/classes/promote');
        }
    }

    private function checkPromotionCriteria($studentId, $classId, $academicYear) {
        // Simplified promotion criteria - in production, check exam results, attendance, etc.
        // For now, we'll promote all students (can be enhanced based on requirements)

        // Check attendance rate (should be above 75%)
        $attendanceStats = $this->db->selectOne("
            SELECT
                COUNT(*) as total_days,
                SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_days
            FROM attendance
            WHERE student_id = ? AND class_id = ?
        ", [$studentId, $classId]);

        if ($attendanceStats['total_days'] > 0) {
            $attendanceRate = ($attendanceStats['present_days'] / $attendanceStats['total_days']) * 100;
            if ($attendanceRate < 75) {
                return false; // Attendance too low
            }
        }

        // Check exam results (should pass all subjects)
        $examResults = $this->db->select("
            SELECT er.*, e.exam_type
            FROM exam_results er
            LEFT JOIN exams e ON er.exam_id = e.id
            WHERE er.student_id = ? AND e.class_id = ? AND e.exam_type = 'final'
            ORDER BY er.created_at DESC
            LIMIT 10
        ", [$studentId, $classId]);

        if (!empty($examResults)) {
            foreach ($examResults as $result) {
                if ($result['grade'] === 'F') {
                    return false; // Failed subject
                }
            }
        }

        return true; // Can be promoted
    }

    public function attendance() {
        $classes = $this->db->select("SELECT * FROM classes WHERE is_active = 1 ORDER BY class_name");
        $csrfToken = $this->csrfToken();
        $this->render('admin/attendance/index', ['classes' => $classes, 'csrf_token' => $csrfToken]);
    }

    public function attendanceData() {
        $classId = $_GET['class_id'] ?? '';
        $date = $_GET['date'] ?? '';

        if (!$classId || !$date) {
            $this->json(['error' => 'Class ID and date are required'], 400);
        }

        // Get students in the class
        $students = $this->db->select("
            SELECT s.*, c.class_name, c.section
            FROM students s
            LEFT JOIN classes c ON s.class_id = c.id
            WHERE s.class_id = ? AND s.is_active = 1
            ORDER BY s.first_name, s.last_name
        ", [$classId]);

        // Get existing attendance for the date
        $attendance = $this->db->select("
            SELECT * FROM attendance
            WHERE class_id = ? AND attendance_date = ?
        ", [$classId, $date]);

        $this->json([
            'students' => $students,
            'attendance' => $attendance
        ]);
    }

    public function saveAttendance() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['class_id']) || !isset($data['date']) || !isset($data['attendance'])) {
            $this->json(['success' => false, 'message' => 'Invalid data'], 400);
        }

        $classId = $data['class_id'];
        $date = $data['date'];
        $attendanceData = $data['attendance'];

        // Start transaction
        $this->db->beginTransaction();

        try {
            // Delete existing attendance for this class and date
            $this->db->delete('attendance', 'class_id = ? AND attendance_date = ?', [$classId, $date]);

            // Insert new attendance records
            foreach ($attendanceData as $record) {
                $this->db->insert('attendance', [
                    'student_id' => $record['student_id'],
                    'class_id' => $classId,
                    'attendance_date' => $date,
                    'status' => $record['status'],
                    'marked_by' => $_SESSION['user']['id'] ?? 1,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }

            $this->db->commit();
            $this->json(['success' => true, 'message' => 'Attendance saved successfully']);

        } catch (Exception $e) {
            $this->db->rollback();
            $this->json(['success' => false, 'message' => 'Failed to save attendance: ' . $e->getMessage()], 500);
        }
    }

    public function exportAttendance() {
        $classId = $_GET['class_id'] ?? '';
        $date = $_GET['date'] ?? '';

        if (!$classId || !$date) {
            die('Class ID and date are required');
        }

        // Get class info
        $class = $this->db->selectOne("SELECT * FROM classes WHERE id = ?", [$classId]);
        if (!$class) {
            die('Class not found');
        }

        // Get attendance data
        $attendance = $this->db->select("
            SELECT s.scholar_number, s.first_name, s.last_name, a.status
            FROM students s
            LEFT JOIN attendance a ON s.id = a.student_id AND a.attendance_date = ?
            WHERE s.class_id = ? AND s.is_active = 1
            ORDER BY s.first_name, s.last_name
        ", [$date, $classId]);

        // Generate CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="attendance_' . $class['class_name'] . '_' . $date . '.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Scholar Number', 'First Name', 'Last Name', 'Status']);

        foreach ($attendance as $record) {
            fputcsv($output, [
                $record['scholar_number'],
                $record['first_name'],
                $record['last_name'],
                $record['status'] ?: 'Not Marked'
            ]);
        }

        fclose($output);
        exit;
    }

    public function exams() {
        $exams = $this->db->select("SELECT e.*, c.class_name FROM exams e LEFT JOIN classes c ON e.class_id = c.id ORDER BY e.created_at DESC");
        $this->render('admin/exams/index', ['exams' => $exams]);
    }

    public function admitCards() {
        $exams = $this->db->select("SELECT e.*, c.class_name FROM exams e LEFT JOIN classes c ON e.class_id = c.id WHERE e.is_active = 1 ORDER BY e.start_date DESC");
        $csrfToken = $this->csrfToken();
        $this->render('admin/exams/admit-cards', ['exams' => $exams, 'csrf_token' => $csrfToken]);
    }

    public function getExamStudents($examId) {
        // Get exam details
        $exam = $this->db->selectOne("SELECT * FROM exams WHERE id = ?", [$examId]);
        if (!$exam) {
            $this->json(['error' => 'Exam not found'], 404);
        }

        // Get students for this exam's class
        $students = $this->db->select("
            SELECT s.*, c.class_name, c.section
            FROM students s
            LEFT JOIN classes c ON s.class_id = c.id
            WHERE s.class_id = ? AND s.is_active = 1
            ORDER BY s.first_name, s.last_name
        ", [$exam['class_id']]);

        $this->json(['students' => $students]);
    }

    public function generateAdmitCards() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['exam_id']) || !isset($data['students'])) {
            $this->json(['success' => false, 'message' => 'Invalid data'], 400);
        }

        $examId = $data['exam_id'];
        $studentIds = $data['students'];
        $includePhotos = $data['include_photos'] ?? true;
        $includeSignatures = $data['include_signatures'] ?? true;
        $cardsPerPage = $data['cards_per_page'] ?? 4;

        // Get exam details
        $exam = $this->db->selectOne("
            SELECT e.*, c.class_name, c.section
            FROM exams e
            LEFT JOIN classes c ON e.class_id = c.id
            WHERE e.id = ?
        ", [$examId]);

        if (!$exam) {
            $this->json(['success' => false, 'message' => 'Exam not found'], 404);
        }

        // Get students
        $students = $this->db->select("
            SELECT s.*, c.class_name, c.section
            FROM students s
            LEFT JOIN classes c ON s.class_id = c.id
            WHERE s.id IN (" . str_repeat('?,', count($studentIds) - 1) . "?) AND s.is_active = 1
            ORDER BY s.first_name, s.last_name
        ", $studentIds);

        // Generate HTML for admit cards
        $html = $this->generateAdmitCardsHTML($exam, $students, $includePhotos, $includeSignatures, $cardsPerPage);

        // For now, return HTML. In production, this would generate PDF
        // Save HTML to temporary file and return URL
        $filename = 'admit_cards_' . $examId . '_' . time() . '.html';
        $filepath = BASE_PATH . 'temp/' . $filename;

        // Ensure temp directory exists
        if (!is_dir(dirname($filepath))) {
            mkdir(dirname($filepath), 0755, true);
        }

        file_put_contents($filepath, $html);

        $this->json([
            'success' => true,
            'message' => 'Admit cards generated successfully',
            'html_url' => '/temp/' . $filename,
            'pdf_url' => '/temp/' . $filename // In production, this would be PDF URL
        ]);
    }

    public function generateAdmitCard() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['exam_id']) || !isset($data['student_id'])) {
            $this->json(['success' => false, 'message' => 'Invalid data'], 400);
        }

        $examId = $data['exam_id'];
        $studentId = $data['student_id'];
        $includePhotos = $data['include_photos'] ?? true;
        $includeSignatures = $data['include_signatures'] ?? true;

        // Get exam details
        $exam = $this->db->selectOne("
            SELECT e.*, c.class_name, c.section
            FROM exams e
            LEFT JOIN classes c ON e.class_id = c.id
            WHERE e.id = ?
        ", [$examId]);

        if (!$exam) {
            $this->json(['success' => false, 'message' => 'Exam not found'], 404);
        }

        // Get student
        $student = $this->db->selectOne("
            SELECT s.*, c.class_name, c.section
            FROM students s
            LEFT JOIN classes c ON s.class_id = c.id
            WHERE s.id = ? AND s.is_active = 1
        ", [$studentId]);

        if (!$student) {
            $this->json(['success' => false, 'message' => 'Student not found'], 404);
        }

        // Generate HTML for single admit card
        $html = $this->generateAdmitCardsHTML($exam, [$student], $includePhotos, $includeSignatures, 1);

        // Save HTML to temporary file
        $filename = 'admit_card_' . $examId . '_' . $studentId . '_' . time() . '.html';
        $filepath = BASE_PATH . 'temp/' . $filename;

        if (!is_dir(dirname($filepath))) {
            mkdir(dirname($filepath), 0755, true);
        }

        file_put_contents($filepath, $html);

        $this->json([
            'success' => true,
            'message' => 'Admit card generated successfully',
            'html_url' => '/temp/' . $filename,
            'pdf_url' => '/temp/' . $filename
        ]);
    }

    public function certificates() {
        $classes = $this->db->select("SELECT * FROM classes WHERE is_active = 1 ORDER BY class_name");
        $csrfToken = $this->csrfToken();
        $this->render('admin/certificates/index', ['classes' => $classes, 'csrf_token' => $csrfToken]);
    }

    public function getCertificateStudents() {
        $classId = $_GET['class_id'] ?? '';

        $query = "SELECT s.*, c.class_name, c.section FROM students s LEFT JOIN classes c ON s.class_id = c.id WHERE s.is_active = 1";
        $params = [];

        if (!empty($classId)) {
            $query .= " AND s.class_id = ?";
            $params[] = $classId;
        }

        $query .= " ORDER BY s.first_name, s.last_name";

        $students = $this->db->select($query, $params);
        $this->json(['students' => $students]);
    }

    public function generateCertificate() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['student_id']) || !isset($data['certificate_type'])) {
            $this->json(['success' => false, 'message' => 'Invalid data'], 400);
        }

        $studentId = $data['student_id'];
        $certificateType = $data['certificate_type'];

        // Get student details
        $student = $this->db->selectOne("
            SELECT s.*, c.class_name, c.section
            FROM students s
            LEFT JOIN classes c ON s.class_id = c.id
            WHERE s.id = ? AND s.is_active = 1
        ", [$studentId]);

        if (!$student) {
            $this->json(['success' => false, 'message' => 'Student not found'], 404);
        }

        if ($certificateType === 'transfer') {
            $html = $this->generateTransferCertificateHTML($student, $data);
        } else {
            $this->json(['success' => false, 'message' => 'Certificate type not supported'], 400);
        }

        // Save HTML to temporary file
        $filename = 'certificate_' . $certificateType . '_' . $studentId . '_' . time() . '.html';
        $filepath = BASE_PATH . 'temp/' . $filename;

        if (!is_dir(dirname($filepath))) {
            mkdir(dirname($filepath), 0755, true);
        }

        file_put_contents($filepath, $html);

        $this->json([
            'success' => true,
            'message' => 'Certificate generated successfully',
            'certificate_url' => '/temp/' . $filename
        ]);
    }

    private function generateTransferCertificateHTML($student, $data) {
        $schoolName = $this->db->selectOne("SELECT setting_value FROM settings WHERE setting_key = 'school_name'")['setting_value'] ?? 'School Management System';
        $schoolAddress = $this->db->selectOne("SELECT setting_value FROM settings WHERE setting_key = 'school_address'")['setting_value'] ?? '';

        $certNo = 'TC-' . date('Y') . '-' . str_pad($student['id'], 4, '0', STR_PAD_LEFT);
        $issueDate = date('d/m/Y', strtotime($data['issue_date']));
        $admissionDate = $student['admission_date'] ? date('d/m/Y', strtotime($student['admission_date'])) : 'N/A';

        // Get academic record (simplified - in real implementation, this would pull from exam results)
        $academicRecord = "Class: {$student['class_name']} {$student['section']} - Academic Year: " . date('Y');

        $reasonText = '';
        switch ($data['transfer_reason']) {
            case 'parent_transfer': $reasonText = 'Parent Transfer'; break;
            case 'better_opportunity': $reasonText = 'Better Educational Opportunity'; break;
            case 'family_moved': $reasonText = 'Family Moved'; break;
            case 'personal': $reasonText = 'Personal Reasons'; break;
            default: $reasonText = 'Other'; break;
        }

        $conduct = ucfirst($data['conduct'] ?? 'good');

        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Transfer Certificate - ' . htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) . '</title>
            <style>
                body { font-family: "Times New Roman", serif; margin: 40px; line-height: 1.6; }
                .certificate { border: 3px solid #000; padding: 40px; max-width: 800px; margin: 0 auto; }
                .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 20px; margin-bottom: 30px; }
                .school-name { font-size: 24px; font-weight: bold; margin-bottom: 10px; }
                .certificate-title { font-size: 20px; font-weight: bold; margin: 20px 0; }
                .content { margin: 20px 0; }
                .field { margin: 10px 0; }
                .label { font-weight: bold; display: inline-block; width: 200px; }
                .signatures { margin-top: 60px; clear: both; }
                .signature-box { width: 30%; float: left; text-align: center; margin-right: 3%; border-top: 1px solid #000; padding-top: 20px; min-height: 80px; }
                .signature-box:last-child { margin-right: 0; }
                .cert-no { position: absolute; top: 40px; right: 40px; font-weight: bold; }
                @media print { body { margin: 20px; } .certificate { border: 2px solid #000; } }
            </style>
        </head>
        <body>
            <div class="cert-no">Certificate No: ' . $certNo . '</div>

            <div class="certificate">
                <div class="header">
                    <div class="school-name">' . htmlspecialchars($schoolName) . '</div>
                    <div>' . htmlspecialchars($schoolAddress) . '</div>
                    <div class="certificate-title">TRANSFER CERTIFICATE</div>
                </div>

                <div class="content">
                    <div class="field">
                        <span class="label">This is to certify that</span>
                        ' . htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) . '
                    </div>

                    <div class="field">
                        <span class="label">Scholar Number:</span>
                        ' . htmlspecialchars($student['scholar_number']) . '
                    </div>

                    <div class="field">
                        <span class="label">Date of Admission:</span>
                        ' . $admissionDate . '
                    </div>

                    <div class="field">
                        <span class="label">Class:</span>
                        ' . htmlspecialchars($student['class_name'] . ' ' . $student['section']) . '
                    </div>

                    <div class="field">
                        <span class="label">Academic Record:</span>
                        ' . htmlspecialchars($academicRecord) . '
                    </div>

                    <div class="field">
                        <span class="label">Conduct:</span>
                        ' . $conduct . '
                    </div>

                    <div class="field">
                        <span class="label">Reason for Leaving:</span>
                        ' . $reasonText . '
                    </div>

                    <div class="field">
                        <span class="label">Date of Issue:</span>
                        ' . $issueDate . '
                    </div>
                    ';

        if (!empty($data['remarks'])) {
            $html .= '
                    <div class="field">
                        <span class="label">Remarks:</span>
                        ' . htmlspecialchars($data['remarks']) . '
                    </div>
                    ';
        }

        $html .= '
                </div>

                <div class="signatures">
                    <div class="signature-box">
                        <small>Class Teacher</small>
                    </div>
                    <div class="signature-box">
                        <small>Principal</small>
                    </div>
                    <div class="signature-box">
                        <small>School Seal</small>
                    </div>
                </div>
            </div>
        </body>
        </html>
        ';

        return $html;
    }

    public function marksheets() {
        $exams = $this->db->select("SELECT e.*, c.class_name FROM exams e LEFT JOIN classes c ON e.class_id = c.id WHERE e.is_active = 1 ORDER BY e.start_date DESC");
        $csrfToken = $this->csrfToken();
        $this->render('admin/exams/marksheets', ['exams' => $exams, 'csrf_token' => $csrfToken]);
    }

    public function getExamResultsStudents($examId) {
        // Get students who have results for this exam
        $students = $this->db->select("
            SELECT DISTINCT s.*, c.class_name, c.section
            FROM students s
            LEFT JOIN classes c ON s.class_id = c.id
            INNER JOIN exam_results er ON s.id = er.student_id
            WHERE er.exam_id = ? AND s.is_active = 1
            ORDER BY s.first_name, s.last_name
        ", [$examId]);

        $this->json(['students' => $students]);
    }

    public function generateMarksheets() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['exam_id']) || !isset($data['students'])) {
            $this->json(['success' => false, 'message' => 'Invalid data'], 400);
        }

        $examId = $data['exam_id'];
        $studentIds = $data['students'];
        $includePhotos = $data['include_photos'] ?? true;
        $includeGrades = $data['include_grades'] ?? true;
        $includeRankings = $data['include_rankings'] ?? true;
        $marksheetsPerPage = $data['marksheets_per_page'] ?? 2;

        // Get exam details
        $exam = $this->db->selectOne("
            SELECT e.*, c.class_name, c.section
            FROM exams e
            LEFT JOIN classes c ON e.class_id = c.id
            WHERE e.id = ?
        ", [$examId]);

        if (!$exam) {
            $this->json(['success' => false, 'message' => 'Exam not found'], 404);
        }

        // Get students with their results
        $students = [];
        foreach ($studentIds as $studentId) {
            $student = $this->db->selectOne("
                SELECT s.*, c.class_name, c.section
                FROM students s
                LEFT JOIN classes c ON s.class_id = c.id
                WHERE s.id = ? AND s.is_active = 1
            ", [$studentId]);

            if ($student) {
                // Get exam results for this student
                $results = $this->db->select("
                    SELECT er.*, sub.subject_name, sub.subject_code
                    FROM exam_results er
                    LEFT JOIN subjects sub ON er.subject_id = sub.id
                    WHERE er.exam_id = ? AND er.student_id = ?
                    ORDER BY sub.subject_name
                ", [$examId, $studentId]);

                $student['results'] = $results;
                $students[] = $student;
            }
        }

        // Calculate rankings if requested
        if ($includeRankings) {
            $this->calculateStudentRankings($students, $examId);
        }

        // Generate HTML for marksheets
        $html = $this->generateMarksheetsHTML($exam, $students, $includePhotos, $includeGrades, $includeRankings, $marksheetsPerPage);

        // Save HTML to temporary file
        $filename = 'marksheets_' . $examId . '_' . time() . '.html';
        $filepath = BASE_PATH . 'temp/' . $filename;

        if (!is_dir(dirname($filepath))) {
            mkdir(dirname($filepath), 0755, true);
        }

        file_put_contents($filepath, $html);

        $this->json([
            'success' => true,
            'message' => 'Marksheets generated successfully',
            'marksheet_url' => '/temp/' . $filename
        ]);
    }

    public function generateMarksheet() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['exam_id']) || !isset($data['student_id'])) {
            $this->json(['success' => false, 'message' => 'Invalid data'], 400);
        }

        $examId = $data['exam_id'];
        $studentId = $data['student_id'];
        $includePhotos = $data['include_photos'] ?? true;
        $includeGrades = $data['include_grades'] ?? true;
        $includeRankings = $data['include_rankings'] ?? true;

        // Get exam details
        $exam = $this->db->selectOne("
            SELECT e.*, c.class_name, c.section
            FROM exams e
            LEFT JOIN classes c ON e.class_id = c.id
            WHERE e.id = ?
        ", [$examId]);

        if (!$exam) {
            $this->json(['success' => false, 'message' => 'Exam not found'], 404);
        }

        // Get student with results
        $student = $this->db->selectOne("
            SELECT s.*, c.class_name, c.section
            FROM students s
            LEFT JOIN classes c ON s.class_id = c.id
            WHERE s.id = ? AND s.is_active = 1
        ", [$studentId]);

        if (!$student) {
            $this->json(['success' => false, 'message' => 'Student not found'], 404);
        }

        // Get exam results for this student
        $results = $this->db->select("
            SELECT er.*, sub.subject_name, sub.subject_code
            FROM exam_results er
            LEFT JOIN subjects sub ON er.subject_id = sub.id
            WHERE er.exam_id = ? AND er.student_id = ?
            ORDER BY sub.subject_name
        ", [$examId, $studentId]);

        $student['results'] = $results;

        // Calculate ranking if requested
        if ($includeRankings) {
            $allStudents = $this->db->select("
                SELECT DISTINCT s.id
                FROM students s
                INNER JOIN exam_results er ON s.id = er.student_id
                WHERE er.exam_id = ? AND s.class_id = ? AND s.is_active = 1
            ", [$examId, $student['class_id']]);

            $studentIds = array_column($allStudents, 'id');
            $students = [];
            foreach ($studentIds as $sid) {
                $s = $this->db->selectOne("SELECT * FROM students WHERE id = ?", [$sid]);
                $s['results'] = $this->db->select("
                    SELECT er.*, sub.subject_name
                    FROM exam_results er
                    LEFT JOIN subjects sub ON er.subject_id = sub.id
                    WHERE er.exam_id = ? AND er.student_id = ?
                ", [$examId, $sid]);
                $students[] = $s;
            }

            $this->calculateStudentRankings($students, $examId);
            // Find this student's rank
            foreach ($students as $s) {
                if ($s['id'] == $studentId) {
                    $student['rank'] = $s['rank'];
                    break;
                }
            }
        }

        // Generate HTML for single marksheet
        $html = $this->generateMarksheetsHTML($exam, [$student], $includePhotos, $includeGrades, $includeRankings, 1);

        // Save HTML to temporary file
        $filename = 'marksheet_' . $examId . '_' . $studentId . '_' . time() . '.html';
        $filepath = BASE_PATH . 'temp/' . $filename;

        if (!is_dir(dirname($filepath))) {
            mkdir(dirname($filepath), 0755, true);
        }

        file_put_contents($filepath, $html);

        $this->json([
            'success' => true,
            'message' => 'Marksheet generated successfully',
            'marksheet_url' => '/temp/' . $filename
        ]);
    }

    public function createExam() {
        $classes = $this->db->select("SELECT * FROM classes WHERE is_active = 1 ORDER BY class_name");
        $subjects = $this->db->select("SELECT * FROM subjects WHERE is_active = 1 ORDER BY subject_name");
        $csrfToken = $this->csrfToken();
        $this->render('admin/exams/create', ['classes' => $classes, 'subjects' => $subjects, 'csrf_token' => $csrfToken]);
    }

    public function storeExam() {
        $data = $_POST;
        $csrfToken = $data['csrf_token'] ?? '';

        if (!$this->checkCsrfToken($csrfToken)) {
            $this->session->setFlash('error', 'Invalid CSRF token');
            $this->redirect('/admin/exams/create');
        }

        // Validate required fields
        $required = ['exam_name', 'exam_type', 'class_id', 'start_date', 'end_date'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $this->session->setFlash('error', ucfirst(str_replace('_', ' ', $field)) . ' is required');
                $this->session->setFlash('old', $data);
                $this->redirect('/admin/exams/create');
            }
        }

        // Validate subjects
        if (empty($data['subjects']) || !is_array($data['subjects'])) {
            $this->session->setFlash('error', 'At least one subject must be added to the exam');
            $this->session->setFlash('old', $data);
            $this->redirect('/admin/exams/create');
        }

        // Start transaction
        $this->db->beginTransaction();

        try {
            // Create exam
            $examData = [
                'exam_name' => trim($data['exam_name']),
                'exam_type' => $data['exam_type'],
                'class_id' => $data['class_id'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'academic_year' => $data['academic_year'] ?? date('Y') . '-' . (date('Y') + 1),
                'is_active' => isset($data['is_active']) ? 1 : 0
            ];

            $examId = $this->db->insert('exams', $examData);

            // Create exam subjects
            foreach ($data['subjects'] as $subjectData) {
                if (!empty($subjectData['subject_id'])) {
                    $this->db->insert('exam_subjects', [
                        'exam_id' => $examId,
                        'subject_id' => $subjectData['subject_id'],
                        'exam_date' => $subjectData['exam_date'],
                        'start_time' => $subjectData['start_time'],
                        'end_time' => $subjectData['end_time'],
                        'max_marks' => $subjectData['max_marks']
                    ]);
                }
            }

            $this->db->commit();

            $this->session->setFlash('success', 'Exam created successfully with ' . count($data['subjects']) . ' subjects');
            $this->redirect('/admin/exams');

        } catch (Exception $e) {
            $this->db->rollback();
            $this->session->setFlash('error', 'Failed to create exam: ' . $e->getMessage());
            $this->session->setFlash('old', $data);
            $this->redirect('/admin/exams/create');
        }
    }

    public function enterResults($examId) {
        // Get exam details
        $exam = $this->db->selectOne("
            SELECT e.*, c.class_name, c.section
            FROM exams e
            LEFT JOIN classes c ON e.class_id = c.id
            WHERE e.id = ?
        ", [$examId]);

        if (!$exam) {
            $this->session->setFlash('error', 'Exam not found');
            $this->redirect('/admin/exams');
        }

        // Get exam subjects
        $examSubjects = $this->db->select("
            SELECT es.*, s.subject_name, s.subject_code
            FROM exam_subjects es
            LEFT JOIN subjects s ON es.subject_id = s.id
            WHERE es.exam_id = ?
            ORDER BY es.exam_date, es.start_time
        ", [$examId]);

        // Get students in the class
        $students = $this->db->select("
            SELECT s.*, c.class_name, c.section
            FROM students s
            LEFT JOIN classes c ON s.class_id = c.id
            WHERE s.class_id = ? AND s.is_active = 1
            ORDER BY s.first_name, s.last_name
        ", [$exam['class_id']]);

        $csrfToken = $this->csrfToken();
        $this->render('admin/exams/results', [
            'exam' => $exam,
            'exam_subjects' => $examSubjects,
            'students' => $students,
            'csrf_token' => $csrfToken
        ]);
    }

    public function saveResults() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['exam_id']) || !isset($data['results'])) {
            $this->json(['success' => false, 'message' => 'Invalid data'], 400);
        }

        $examId = $data['exam_id'];
        $results = $data['results'];

        // Start transaction
        $this->db->beginTransaction();

        try {
            // Delete existing results for this exam
            $this->db->delete('exam_results', 'exam_id = ?', [$examId]);

            // Insert new results
            foreach ($results as $result) {
                if (isset($result['marks_obtained']) && is_numeric($result['marks_obtained'])) {
                    // Calculate grade
                    $percentage = ($result['marks_obtained'] / $result['max_marks']) * 100;
                    $grade = $this->calculateGrade($result['marks_obtained'], $result['max_marks']);

                    $this->db->insert('exam_results', [
                        'exam_id' => $examId,
                        'student_id' => $result['student_id'],
                        'subject_id' => $result['subject_id'],
                        'marks_obtained' => $result['marks_obtained'],
                        'max_marks' => $result['max_marks'],
                        'grade' => $grade,
                        'percentage' => round($percentage, 2)
                    ]);
                }
            }

            $this->db->commit();
            $this->json(['success' => true, 'message' => 'Results saved successfully']);

        } catch (Exception $e) {
            $this->db->rollback();
            $this->json(['success' => false, 'message' => 'Failed to save results: ' . $e->getMessage()], 500);
        }
    }

    public function getExistingResults($examId) {
        $results = $this->db->select("
            SELECT er.*, es.max_marks
            FROM exam_results er
            LEFT JOIN exam_subjects es ON er.subject_id = es.subject_id AND es.exam_id = er.exam_id
            WHERE er.exam_id = ?
        ", [$examId]);

        $this->json(['results' => $results]);
    }

    private function calculateStudentRankings(&$students, $examId) {
        // Calculate total marks for each student
        foreach ($students as &$student) {
            $totalMarks = 0;
            $maxMarks = 0;
            foreach ($student['results'] as $result) {
                $totalMarks += $result['marks_obtained'];
                $maxMarks += $result['max_marks'];
            }
            $student['total_marks'] = $totalMarks;
            $student['max_marks'] = $maxMarks;
            $student['percentage'] = $maxMarks > 0 ? round(($totalMarks / $maxMarks) * 100, 2) : 0;
        }

        // Sort by total marks descending
        usort($students, function($a, $b) {
            return $b['total_marks'] <=> $a['total_marks'];
        });

        // Assign ranks
        $rank = 1;
        $prevMarks = null;
        foreach ($students as &$student) {
            if ($prevMarks !== null && $student['total_marks'] < $prevMarks) {
                $rank++;
            }
            $student['rank'] = $rank;
            $prevMarks = $student['total_marks'];
        }
    }

    private function generateMarksheetsHTML($exam, $students, $includePhotos, $includeGrades, $includeRankings, $marksheetsPerPage) {
        $schoolName = $this->db->selectOne("SELECT setting_value FROM settings WHERE setting_key = 'school_name'")['setting_value'] ?? 'School Management System';
        $schoolAddress = $this->db->selectOne("SELECT setting_value FROM settings WHERE setting_key = 'school_address'")['setting_value'] ?? '';

        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Marksheets - ' . htmlspecialchars($exam['exam_name']) . '</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
                .marksheet { border: 2px solid #000; padding: 20px; margin: 10px; width: ' . (100/$marksheetsPerPage - 2) . '%; float: left; box-sizing: border-box; page-break-inside: avoid; }
                .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
                .school-name { font-size: 18px; font-weight: bold; }
                .exam-title { font-size: 16px; font-weight: bold; margin: 10px 0; }
                .student-info { margin: 10px 0; }
                .marks-table { width: 100%; border-collapse: collapse; margin: 15px 0; }
                .marks-table th, .marks-table td { border: 1px solid #000; padding: 8px; text-align: center; }
                .marks-table th { background-color: #f0f0f0; font-weight: bold; }
                .signatures { margin-top: 30px; clear: both; }
                .signature-box { width: 30%; float: left; text-align: center; border-top: 1px solid #000; padding-top: 20px; min-height: 60px; }
                .signature-box:last-child { margin-right: 0; }
                .photo { float: right; width: 60px; height: 80px; border: 1px solid #000; margin-left: 20px; }
                .grade-badge { display: inline-block; padding: 2px 6px; border-radius: 3px; font-size: 0.8rem; font-weight: bold; }
                .grade-A { background-color: #d4edda; color: #155724; }
                .grade-B { background-color: #fff3cd; color: #856404; }
                .grade-C { background-color: #ffeaa7; color: #d68910; }
                .grade-F { background-color: #f8d7da; color: #721c24; }
                @media print { body { margin: 10px; } .marksheet { margin: 5px; } }
            </style>
        </head>
        <body>
        ';

        foreach ($students as $index => $student) {
            if ($index % $marksheetsPerPage === 0 && $index > 0) {
                $html .= '<div style="page-break-before: always;"></div>';
            }

            $html .= '
            <div class="marksheet">
                <div class="header">
                    <div class="school-name">' . htmlspecialchars($schoolName) . '</div>
                    <div>' . htmlspecialchars($schoolAddress) . '</div>
                    <div class="exam-title">MARKSHEET - ' . htmlspecialchars($exam['exam_name']) . '</div>
                </div>

                <div style="overflow: hidden;">
            ';

            if ($includePhotos && !empty($student['photo'])) {
                $html .= '<img src="' . BASE_PATH . 'uploads/' . $student['photo'] . '" class="photo" alt="Photo">';
            }

            $html .= '
                <div class="student-info">
                    <strong>Student Name:</strong> ' . htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) . '<br>
                    <strong>Scholar Number:</strong> ' . htmlspecialchars($student['scholar_number']) . '<br>
                    <strong>Class:</strong> ' . htmlspecialchars($student['class_name'] . ' ' . $student['section']) . '<br>
                    <strong>Roll Number:</strong> ' . htmlspecialchars($student['roll_number'] ?? 'N/A') . '<br>
                    <strong>Exam Date:</strong> ' . date('M d, Y', strtotime($exam['start_date'])) . ' - ' . date('M d, Y', strtotime($exam['end_date'])) . '<br>
                </div>

                <table class="marks-table">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Max Marks</th>
                            <th>Marks Obtained</th>
                            <th>Grade</th>
                        </tr>
                    </thead>
                    <tbody>
            ';

            $totalMarks = 0;
            $maxTotalMarks = 0;

            foreach ($student['results'] as $result) {
                $marks = $result['marks_obtained'];
                $maxMarks = $result['max_marks'];
                $totalMarks += $marks;
                $maxTotalMarks += $maxMarks;

                $grade = $this->calculateGrade($marks, $maxMarks);
                $gradeClass = 'grade-' . $grade;

                $html .= '
                        <tr>
                            <td>' . htmlspecialchars($result['subject_name']) . '</td>
                            <td>' . $maxMarks . '</td>
                            <td>' . $marks . '</td>
                            <td><span class="grade-badge ' . $gradeClass . '">' . $grade . '</span></td>
                        </tr>
                ';
            }

            $percentage = $maxTotalMarks > 0 ? round(($totalMarks / $maxTotalMarks) * 100, 2) : 0;
            $overallGrade = $this->calculateGrade($totalMarks, $maxTotalMarks);

            $html .= '
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2">Total Marks</th>
                            <th>' . $totalMarks . '/' . $maxTotalMarks . '</th>
                            <th><span class="grade-badge grade-' . $overallGrade . '">' . $overallGrade . '</span></th>
                        </tr>
                        <tr>
                            <th colspan="2">Percentage</th>
                            <th colspan="2">' . $percentage . '%</th>
                        </tr>
            ';

            if ($includeRankings && isset($student['rank'])) {
                $html .= '
                        <tr>
                            <th colspan="2">Class Rank</th>
                            <th colspan="2">' . $student['rank'] . '</th>
                        </tr>
                ';
            }

            $html .= '
                    </tfoot>
                </table>
            ';

            $html .= '
                <div class="signatures">
                    <div class="signature-box">
                        <small>Class Teacher</small>
                    </div>
                    <div class="signature-box">
                        <small>Exam Controller</small>
                    </div>
                    <div class="signature-box">
                        <small>Principal</small>
                    </div>
                </div>
            ';

            $html .= '
                </div>
            </div>
            ';
        }

        $html .= '
        </body>
        </html>
        ';

        return $html;
    }

    private function calculateGrade($marks, $maxMarks) {
        if ($maxMarks == 0) return 'N/A';

        $percentage = ($marks / $maxMarks) * 100;

        if ($percentage >= 90) return 'A';
        if ($percentage >= 80) return 'B';
        if ($percentage >= 70) return 'C';
        if ($percentage >= 60) return 'D';
        return 'F';
    }

    private function generateAdmitCardsHTML($exam, $students, $includePhotos, $includeSignatures, $cardsPerPage) {
        $schoolName = $this->db->selectOne("SELECT setting_value FROM settings WHERE setting_key = 'school_name'")['setting_value'] ?? 'School Management System';
        $schoolAddress = $this->db->selectOne("SELECT setting_value FROM settings WHERE setting_key = 'school_address'")['setting_value'] ?? '';

        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Admit Cards - ' . htmlspecialchars($exam['exam_name']) . '</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
                .admit-card { border: 2px solid #000; padding: 20px; margin: 10px; width: ' . (100/$cardsPerPage - 2) . '%; float: left; box-sizing: border-box; page-break-inside: avoid; }
                .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
                .school-name { font-size: 18px; font-weight: bold; }
                .exam-title { font-size: 16px; margin: 10px 0; }
                .student-info { margin: 10px 0; }
                .subject-schedule { margin: 15px 0; }
                .signatures { margin-top: 30px; clear: both; }
                .signature-box { border-top: 1px solid #000; width: 30%; float: left; text-align: center; padding-top: 30px; margin-right: 3%; min-height: 60px; }
                .signature-box:last-child { margin-right: 0; }
                .photo { float: right; width: 80px; height: 100px; border: 1px solid #000; margin-left: 20px; }
                @media print { body { margin: 0; } .admit-card { margin: 5px; } }
            </style>
        </head>
        <body>
        ';

        foreach ($students as $index => $student) {
            if ($index % $cardsPerPage === 0 && $index > 0) {
                $html .= '<div style="page-break-before: always;"></div>';
            }

            $html .= '
            <div class="admit-card">
                <div class="header">
                    <div class="school-name">' . htmlspecialchars($schoolName) . '</div>
                    <div>' . htmlspecialchars($schoolAddress) . '</div>
                    <div class="exam-title">Admit Card - ' . htmlspecialchars($exam['exam_name']) . '</div>
                </div>

                <div style="overflow: hidden;">
            ';

            if ($includePhotos && !empty($student['photo'])) {
                $html .= '<img src="' . BASE_PATH . 'uploads/' . $student['photo'] . '" class="photo" alt="Photo">';
            }

            $html .= '
                <div class="student-info">
                    <strong>Student Name:</strong> ' . htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) . '<br>
                    <strong>Scholar Number:</strong> ' . htmlspecialchars($student['scholar_number']) . '<br>
                    <strong>Class:</strong> ' . htmlspecialchars($student['class_name'] . ' ' . $student['section']) . '<br>
                    <strong>Roll Number:</strong> ' . htmlspecialchars($student['roll_number'] ?? 'N/A') . '<br>
                    <strong>Exam Date:</strong> ' . date('M d, Y', strtotime($exam['start_date'])) . ' - ' . date('M d, Y', strtotime($exam['end_date'])) . '<br>
                </div>

                <div class="subject-schedule">
                    <strong>Subject Schedule:</strong><br>
                    <small>Please check the exam schedule for detailed timings</small>
                </div>
            ';

            if ($includeSignatures) {
                $html .= '
                <div class="signatures">
                    <div class="signature-box">
                        <small>Principal</small>
                    </div>
                    <div class="signature-box">
                        <small>Exam Controller</small>
                    </div>
                    <div class="signature-box">
                        <small>School Seal</small>
                    </div>
                </div>
                ';
            }

            $html .= '
                </div>
            </div>
            ';
        }

        $html .= '
        </body>
        </html>
        ';

        return $html;
    }

    public function fees() {
        $fees = $this->db->select("SELECT f.*, s.first_name, s.last_name, s.scholar_number FROM fees f LEFT JOIN students s ON f.student_id = s.id ORDER BY f.created_at DESC");

        // Calculate statistics
        $stats = [
            'total_collected' => $this->db->selectOne("SELECT SUM(amount_paid) as total FROM fee_payments WHERE strftime('%Y-%m', payment_date) = strftime('%Y-%m', 'now')")['total'] ?? 0,
            'total_pending' => $this->db->selectOne("SELECT SUM(amount) as total FROM fees WHERE is_paid = 0")['total'] ?? 0,
            'monthly_target' => 10000, // This could be configurable
            'overdue_amount' => $this->db->selectOne("SELECT SUM(amount) as total FROM fees WHERE is_paid = 0 AND due_date < date('now')")['total'] ?? 0
        ];

        $this->render('admin/fees/index', ['fees' => $fees, 'stats' => $stats]);
    }

    public function createFee() {
        $classes = $this->db->select("SELECT * FROM classes WHERE is_active = 1 ORDER BY class_name");
        $csrfToken = $this->csrfToken();
        $this->render('admin/fees/create', ['classes' => $classes, 'csrf_token' => $csrfToken]);
    }

    public function getStudentsForFees() {
        $classId = $_GET['class_id'] ?? '';
        $village = $_GET['village'] ?? '';

        if (!$classId) {
            $this->json(['error' => 'Class ID is required'], 400);
        }

        $query = "SELECT s.*, c.class_name, c.section FROM students s LEFT JOIN classes c ON s.class_id = c.id WHERE s.class_id = ? AND s.is_active = 1";
        $params = [$classId];

        if (!empty($village)) {
            $query .= " AND s.village LIKE ?";
            $params[] = '%' . $village . '%';
        }

        $query .= " ORDER BY s.first_name, s.last_name";

        $students = $this->db->select($query, $params);
        $this->json(['students' => $students]);
    }

    public function storeFee() {
        $data = $_POST;
        $csrfToken = $data['csrf_token'] ?? '';

        if (!$this->checkCsrfToken($csrfToken)) {
            $this->session->setFlash('error', 'Invalid CSRF token');
            $this->redirect('/admin/fees/create');
        }

        // Validate required fields
        $required = ['student_id', 'fee_type', 'total_fee', 'net_amount', 'receipt_number', 'payment_mode', 'payment_date'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $this->session->setFlash('error', ucfirst(str_replace('_', ' ', $field)) . ' is required');
                $this->session->setFlash('old', $data);
                $this->redirect('/admin/fees/create');
            }
        }

        // Validate student exists
        $student = $this->db->selectOne("SELECT * FROM students WHERE id = ?", [$data['student_id']]);
        if (!$student) {
            $this->session->setFlash('error', 'Selected student not found');
            $this->redirect('/admin/fees/create');
        }

        // Start transaction
        $this->db->beginTransaction();

        try {
            // Create fee record
            $feeData = [
                'student_id' => $data['student_id'],
                'fee_type' => $data['fee_type'],
                'amount' => $data['total_fee'],
                'due_date' => date('Y-m-d', strtotime('+30 days')), // Default 30 days
                'academic_year' => date('Y'), // Current year
                'is_paid' => 1
            ];

            $feeId = $this->db->insert('fees', $feeData);

            // Create payment record
            $paymentData = [
                'fee_id' => $feeId,
                'amount_paid' => $data['net_amount'],
                'payment_date' => $data['payment_date'],
                'payment_mode' => $data['payment_mode'],
                'transaction_id' => $data['transaction_id'] ?? null,
                'cheque_number' => ($data['payment_mode'] === 'cheque') ? $data['transaction_id'] : null,
                'remarks' => $data['remarks'] ?? '',
                'collected_by' => $_SESSION['user']['id'] ?? 1
            ];

            $this->db->insert('fee_payments', $paymentData);

            $this->db->commit();

            // Generate receipt (this would be implemented with PDF generation)
            $this->session->setFlash('success', 'Fee payment recorded successfully. Receipt generated.');

            // Redirect to receipt view or back to fees
            $this->redirect('/admin/fees');

        } catch (Exception $e) {
            $this->db->rollback();
            $this->session->setFlash('error', 'Failed to record fee payment: ' . $e->getMessage());
            $this->redirect('/admin/fees/create');
        }
    }

    public function expenses() {
        $expenses = $this->db->select("SELECT * FROM expenses ORDER BY created_at DESC");
        $this->render('admin/expenses/index', ['expenses' => $expenses]);
    }

    public function events() {
        $events = $this->db->select("SELECT * FROM events ORDER BY created_at DESC");
        $this->render('admin/events/index', ['events' => $events]);
    }

    public function gallery() {
        $gallery = $this->db->select("SELECT * FROM gallery ORDER BY created_at DESC");
        $this->render('admin/gallery/index', ['gallery' => $gallery]);
    }

    public function reports() {
        $this->render('admin/reports/index');
    }

    public function settings() {
        $settings = $this->db->select("SELECT * FROM settings ORDER BY setting_key");
        $this->render('admin/settings/index', ['settings' => $settings]);
    }

    public function homepage() {
        // Get homepage content by sections
        $carousel = $this->db->select("SELECT * FROM homepage_content WHERE section = 'carousel' AND is_active = 1 ORDER BY sort_order");
        $about = $this->db->selectOne("SELECT * FROM homepage_content WHERE section = 'about' AND is_active = 1 LIMIT 1");
        $courses = $this->db->select("SELECT * FROM homepage_content WHERE section = 'courses' AND is_active = 1 ORDER BY sort_order");
        $events = $this->db->select("SELECT * FROM events WHERE is_active = 1 ORDER BY event_date DESC LIMIT 5");
        $gallery = $this->db->select("SELECT * FROM gallery WHERE is_active = 1 ORDER BY created_at DESC LIMIT 8");
        $testimonials = $this->db->select("SELECT * FROM homepage_content WHERE section = 'testimonials' AND is_active = 1 ORDER BY sort_order");

        // Get contact info from settings
        $contact = [
            'address' => $this->db->selectOne("SELECT setting_value FROM settings WHERE setting_key = 'school_address'")['setting_value'] ?? '',
            'phone' => $this->db->selectOne("SELECT setting_value FROM settings WHERE setting_key = 'school_phone'")['setting_value'] ?? '',
            'email' => $this->db->selectOne("SELECT setting_value FROM settings WHERE setting_key = 'school_email'")['setting_value'] ?? ''
        ];

        $this->render('admin/homepage/index', [
            'carousel' => $carousel,
            'about' => $about,
            'courses' => $courses,
            'events' => $events,
            'gallery' => $gallery,
            'testimonials' => $testimonials,
            'contact' => $contact
        ]);
    }

    public function homepageCarousel() {
        $carousel = $this->db->select("SELECT * FROM homepage_content WHERE section = 'carousel' ORDER BY sort_order");
        $csrfToken = $this->csrfToken();
        $this->render('admin/homepage/carousel', ['carousel' => $carousel, 'csrf_token' => $csrfToken]);
    }

    public function saveHomepageCarousel() {
        $data = $_POST;
        $csrfToken = $data['csrf_token'] ?? '';

        if (!$this->checkCsrfToken($csrfToken)) {
            $this->session->setFlash('error', 'Invalid CSRF token');
            $this->redirect('/admin/homepage/carousel');
        }

        // Handle carousel updates
        if (isset($data['carousel'])) {
            foreach ($data['carousel'] as $id => $item) {
                $this->db->update('homepage_content', [
                    'title' => $item['title'] ?? '',
                    'content' => $item['content'] ?? '',
                    'link' => $item['link'] ?? '',
                    'sort_order' => $item['sort_order'] ?? 0,
                    'is_active' => isset($item['is_active']) ? 1 : 0
                ], 'id = ?', [$id]);
            }
        }

        // Handle new carousel item
        if (!empty($data['new_title'])) {
            $imagePath = '';
            if (isset($_FILES['new_image']) && $_FILES['new_image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = UPLOADS_PATH . 'homepage/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $fileName = uniqid() . '_' . basename($_FILES['new_image']['name']);
                $targetFile = $uploadDir . $fileName;
                if (move_uploaded_file($_FILES['new_image']['tmp_name'], $targetFile)) {
                    $imagePath = 'homepage/' . $fileName;
                }
            }

            if ($imagePath) {
                $this->db->insert('homepage_content', [
                    'section' => 'carousel',
                    'title' => $data['new_title'],
                    'content' => $data['new_content'] ?? '',
                    'image_path' => $imagePath,
                    'link' => $data['new_link'] ?? '',
                    'sort_order' => $data['new_sort_order'] ?? 0,
                    'is_active' => 1
                ]);
            }
        }

        $this->session->setFlash('success', 'Carousel updated successfully');
        $this->redirect('/admin/homepage/carousel');
    }

    public function homepageAbout() {
        $about = $this->db->selectOne("SELECT * FROM homepage_content WHERE section = 'about' LIMIT 1");
        $csrfToken = $this->csrfToken();
        $this->render('admin/homepage/about', ['about' => $about, 'csrf_token' => $csrfToken]);
    }

    public function saveHomepageAbout() {
        $data = $_POST;
        $csrfToken = $data['csrf_token'] ?? '';

        if (!$this->checkCsrfToken($csrfToken)) {
            $this->session->setFlash('error', 'Invalid CSRF token');
            $this->redirect('/admin/homepage/about');
        }

        $imagePath = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = UPLOADS_PATH . 'homepage/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
            $targetFile = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $imagePath = 'homepage/' . $fileName;
            }
        }

        $about = $this->db->selectOne("SELECT * FROM homepage_content WHERE section = 'about' LIMIT 1");

        if ($about) {
            // Update existing
            $updateData = [
                'title' => $data['title'] ?? '',
                'content' => $data['content'] ?? '',
                'is_active' => isset($data['is_active']) ? 1 : 0
            ];
            if ($imagePath) {
                $updateData['image_path'] = $imagePath;
            }
            $this->db->update('homepage_content', $updateData, 'id = ?', [$about['id']]);
        } else {
            // Create new
            $this->db->insert('homepage_content', [
                'section' => 'about',
                'title' => $data['title'] ?? '',
                'content' => $data['content'] ?? '',
                'image_path' => $imagePath,
                'is_active' => isset($data['is_active']) ? 1 : 0
            ]);
        }

        $this->session->setFlash('success', 'About section updated successfully');
        $this->redirect('/admin/homepage/about');
    }
}