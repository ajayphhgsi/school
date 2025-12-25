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
            'mobile' => 'required|numeric|min:10|max:15',
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
            'mobile' => 'required|numeric|min:10|max:15',
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

    public function attendance() {
        $classes = $this->db->select("SELECT * FROM classes WHERE is_active = 1 ORDER BY class_name");
        $this->render('admin/attendance/index', ['classes' => $classes]);
    }

    public function exams() {
        $exams = $this->db->select("SELECT e.*, c.class_name FROM exams e LEFT JOIN classes c ON e.class_id = c.id ORDER BY e.created_at DESC");
        $this->render('admin/exams/index', ['exams' => $exams]);
    }

    public function fees() {
        $fees = $this->db->select("SELECT f.*, s.first_name, s.last_name, s.scholar_number FROM fees f LEFT JOIN students s ON f.student_id = s.id ORDER BY f.created_at DESC");
        $this->render('admin/fees/index', ['fees' => $fees]);
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
}