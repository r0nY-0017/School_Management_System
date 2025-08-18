<?php
session_start();

// Prevent caching to avoid back button issues
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Check if user is logged in and is a parent
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'parent') {
    header("Location: parent_login.php");
    exit();
}

require_once 'config/db_connect.php';

// Get parent details
$parent_id = $_SESSION['user_id'];
$sql = "SELECT * FROM parents WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $parent_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$parent = mysqli_fetch_assoc($result);

if (!$parent) {
    session_destroy();
    header("Location: parent_login.php");
    exit();
}

// Get student details
$student_sql = "SELECT * FROM students WHERE id = ?";
$student_stmt = mysqli_prepare($conn, $student_sql);
mysqli_stmt_bind_param($student_stmt, "s", $parent['student_id']);
mysqli_stmt_execute($student_stmt);
$student_result = mysqli_stmt_get_result($student_stmt);
$student = mysqli_fetch_assoc($student_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Dashboard - <?php echo htmlspecialchars($parent['name']); ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/parent_dashboard.css">
</head>
<body>
    <div class="container-fluid bg-light min-vh-100">
        <!-- Header -->
        <header class="bg-white shadow-sm py-3 mb-4">
            <div class="container d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">Parent Portal</h1>
                    <p class="text-muted">Welcome back, <?php echo htmlspecialchars($parent['name']); ?>!</p>
                </div>
                <div class="d-flex align-items-center">
                    <div class="d-flex align-items-center me-3">
                        <div class="avatar rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px; font-size: 18px;">
                            <?php echo strtoupper(substr($parent['name'], 0, 1)); ?>
                        </div>
                        <div>
                            <h5 class="mb-0"><?php echo htmlspecialchars($parent['name']); ?></h5>
                            <small class="text-muted">Parent of <?php echo $student ? htmlspecialchars($student['name']) : 'Student'; ?></small>
                        </div>
                    </div>
                    <a href="logout.php" class="btn btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
        </header>

        <!-- Stats Cards -->
        <div class="container mb-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-check fa-2x text-primary mb-2"></i>
                            <h3>94%</h3>
                            <p class="card-text">Child's Attendance</p>
                            <div class="progress">
                                <div class="progress-bar bg-primary" style="width: 94%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-star fa-2x text-success mb-2"></i>
                            <h3>A-</h3>
                            <p class="card-text">Overall Grade</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-book-open fa-2x text-info mb-2"></i>
                            <h3>6</h3>
                            <p class="card-text">Subjects Enrolled</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-exclamation-triangle fa-2x text-warning mb-2"></i>
                            <h3>2</h3>
                            <p class="card-text">Pending Assignments</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="container">
            <div class="row g-3">
                <div class="col-lg-8">
                    <!-- Child's Information -->
                    <?php if ($student): ?>
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-light">
                            <h5><i class="fas fa-user-graduate me-2"></i>Child's Information</h5>
                        </div>
                        <div class="card-body bg-light">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <strong>Name:</strong> <?php echo htmlspecialchars($student['name']); ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Student ID:</strong> <?php echo htmlspecialchars($student['id']); ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Class:</strong> <?php echo htmlspecialchars($student['class']); ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Roll Number:</strong> <?php echo htmlspecialchars($student['roll']); ?>
                                </div>
                                <div class="col-md-6">
                                    <strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Recent Grades -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-light">
                            <h5><i class="fas fa-chart-line me-2"></i>Recent Grades</h5>
                        </div>
                        <div class="card-body bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6>Mathematics Quiz</h6>
                                    <p class="mb-0 text-muted">Chapter 5 Assessment</p>
                                </div>
                                <span class="badge bg-success">A</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6>English Essay</h6>
                                    <p class="mb-0 text-muted">Creative Writing Assignment</p>
                                </div>
                                <span class="badge bg-success">A-</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6>Physics Lab Report</h6>
                                    <p class="mb-0 text-muted">Experiment Analysis</p>
                                </div>
                                <span class="badge bg-warning text-dark">B+</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6>History Project</h6>
                                    <p class="mb-0 text-muted">World War II Research</p>
                                </div>
                                <span class="badge bg-success">A</span>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Record -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h5><i class="fas fa-calendar-alt me-2"></i>Attendance Record</h5>
                        </div>
                        <div class="card-body bg-light">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>This Month</span>
                                    <span>94% (23/24 days)</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-primary" style="width: 94%"></div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Last Month</span>
                                    <span>96% (25/26 days)</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-primary" style="width: 96%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Semester Total</span>
                                    <span>95% (142/150 days)</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-primary" style="width: 95%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Quick Actions -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-white">
                            <h5><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary" onclick="showTeacherRequest()"><i class="fas fa-chalkboard-teacher me-2"></i>Request Teacher</button>
                                <button class="btn btn-outline-primary" onclick="showProfileSettings()"><i class="fas fa-user-edit me-2"></i>Manage Profile</button>
                                <button class="btn btn-primary" onclick="showStudentSettings()"><i class="fas fa-user-graduate me-2"></i>Student Info</button>
                                <button class="btn btn-outline-primary"><i class="fas fa-download me-2"></i>Download Report</button>
                            </div>
                        </div>
                    </div>

                    <!-- Teacher Messages -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-light">
                            <h5><i class="fas fa-comments me-2"></i>Teacher Messages</h5>
                        </div>
                        <div class="card-body bg-light">
                            <div class="mb-3">
                                <h6>Mathematics Teacher</h6>
                                <p class="mb-0 text-muted">Your child is showing excellent progress in algebra. Keep up the good work!</p>
                                <small class="text-muted">2 days ago</small>
                            </div>
                            <div class="mb-3">
                                <h6>English Teacher</h6>
                                <p class="mb-0 text-muted">Please ensure homework is submitted on time. Late submissions affect grades.</p>
                                <small class="text-muted">5 days ago</small>
                            </div>
                            <div>
                                <h6>Class Teacher</h6>
                                <p class="mb-0 text-muted">Parent-teacher meeting scheduled for next week. Please confirm attendance.</p>
                                <small class="text-muted">1 week ago</small>
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming Events -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-light">
                            <h5><i class="fas fa-calendar-plus me-2"></i>Upcoming Events</h5>
                        </div>
                        <div class="card-body bg-light">
                            <div class="mb-3">
                                <h6>Parent-Teacher Conference</h6>
                                <p class="mb-0 text-muted">Individual meetings with all subject teachers</p>
                                <small class="text-muted">December 22, 2024</small>
                            </div>
                            <div class="mb-3">
                                <h6>Final Exams</h6>
                                <p class="mb-0 text-muted">Semester final examinations begin</p>
                                <small class="text-muted">January 5, 2025</small>
                            </div>
                            <div>
                                <h6>Science Fair</h6>
                                <p class="mb-0 text-muted">Annual science project exhibition</p>
                                <small class="text-muted">January 15, 2025</small>
                            </div>
                        </div>
                    </div>

                    <!-- Fee Information -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h5><i class="fas fa-money-bill me-2"></i>Fee Information</h5>
                        </div>
                        <div class="card-body bg-light">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <strong>Total Fee:</strong>
                                    <span>$2,500</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <strong>Paid:</strong>
                                    <span class="text-success">$2,000</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <strong>Remaining:</strong>
                                    <span class="text-danger">$500</span>
                                </div>
                            </div>
                            <button class="btn btn-primary w-100"><i class="fas fa-credit-card me-2"></i>Pay Now</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white shadow-sm py-3 mt-4">
            <div class="container text-center">
                <p class="mb-0 text-muted">&copy; <?php echo date('Y'); ?> Your School Name. All rights reserved.</p>
                <p class="mb-0 text-muted">
                    <a href="https://yourschoolwebsite.com" class="text-primary text-decoration-none">Visit Our Website</a>
                </p>
            </div>
        </footer>
    </div>

    <!-- Teacher Request Modal -->
    <div class="modal fade" id="teacherRequestModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-chalkboard-teacher me-2"></i>Request Teacher Assignment</h5>
                    <button type="button" class="btn-close" onclick="closeTeacherRequest()"></button>
                </div>
                <div class="modal-body">
                    <form id="teacherRequestForm" class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Select Teacher</label>
                            <select id="teacherSelect" class="form-select" required>
                                <option value="">Choose a teacher...</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Subject</label>
                            <input type="text" id="requestSubject" class="form-control" placeholder="Enter subject" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Message to Admin</label>
                            <textarea id="requestMessage" class="form-control" rows="4" placeholder="Explain why you want this teacher for your child..."></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane me-2"></i>Submit Request</button>
                        </div>
                    </form>
                    <hr class="my-4">
                    <h5>My Teacher Requests</h5>
                    <div id="myRequestsList"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="closeTeacherRequest()"><i class="fas fa-times me-2"></i>Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Settings Modal -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user-edit me-2"></i>Profile Settings</h5>
                    <button type="button" class="btn-close" onclick="closeProfileSettings()"></button>
                </div>
                <div class="modal-body">
                    <form id="updateProfileForm" class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Parent ID</label>
                            <input type="text" class="form-control" id="profileId" value="<?php echo htmlspecialchars($parent['id']); ?>" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" id="profileName" value="<?php echo htmlspecialchars($parent['name']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="profileEmail" value="<?php echo htmlspecialchars($parent['email']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" id="profilePhone" value="<?php echo htmlspecialchars($parent['phone'] ?? ''); ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" id="profileAddress" rows="3"><?php echo htmlspecialchars($parent['address'] ?? ''); ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Occupation</label>
                            <input type="text" class="form-control" id="profileOccupation" value="<?php echo htmlspecialchars($parent['occupation'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Relation</label>
                            <input type="text" class="form-control" id="profileRelation" value="<?php echo htmlspecialchars($parent['relation']); ?>" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Student ID</label>
                            <input type="text" class="form-control" id="profileStudentId" value="<?php echo htmlspecialchars($parent['student_id']); ?>" disabled>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Update Profile</button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <form id="changePasswordForm" class="row g-3">
                        <h6>Change Password</h6>
                        <div class="col-md-6">
                            <label class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="currentPassword" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control" id="newPassword" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-warning"><i class="fas fa-key me-2"></i>Change Password</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="closeProfileSettings()"><i class="fas fa-times me-2"></i>Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Settings Modal -->
    <div class="modal fade" id="studentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user-graduate me-2"></i>Student Information</h5>
                    <button type="button" class="btn-close" onclick="closeStudentSettings()"></button>
                </div>
                <div class="modal-body">
                    <form id="updateStudentForm" class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Student ID</label>
                            <input type="text" class="form-control" id="studentId" value="<?php echo htmlspecialchars($student['id']); ?>" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Student Name</label>
                            <input type="text" class="form-control" id="studentName" value="<?php echo htmlspecialchars($student['name']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="studentEmail" value="<?php echo htmlspecialchars($student['email']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Class</label>
                            <input type="text" class="form-control" id="studentClass" value="<?php echo htmlspecialchars($student['class']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Roll Number</label>
                            <input type="text" class="form-control" id="studentRoll" value="<?php echo htmlspecialchars($student['roll']); ?>" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Update Student Info</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="closeStudentSettings()"><i class="fas fa-times me-2"></i>Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Prevent back button navigation
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            history.go(1);
        };

        // Disable keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.key === 'F5' || (e.ctrlKey && e.key === 'r')) {
                e.preventDefault();
                return false;
            }
            if (e.altKey && (e.key === 'ArrowLeft' || e.key === 'ArrowRight')) {
                e.preventDefault();
                return false;
            }
            if (e.key === 'Backspace' && !['INPUT', 'TEXTAREA'].includes(e.target.tagName)) {
                e.preventDefault();
                return false;
            }
        });

        // Teacher Request Functions
        function showTeacherRequest() {
            new bootstrap.Modal(document.getElementById('teacherRequestModal')).show();
            loadTeachersList();
            loadMyRequests();
        }

        function closeTeacherRequest() {
            bootstrap.Modal.getInstance(document.getElementById('teacherRequestModal')).hide();
        }

        function loadTeachersList() {
            fetch('api/parent_profile.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=get_all_teachers'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const select = document.getElementById('teacherSelect');
                    select.innerHTML = '<option value="">Choose a teacher...</option>';
                    data.teachers.forEach(teacher => {
                        select.innerHTML += `<option value="${teacher.id}">${teacher.name} (${teacher.subject})</option>`;
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error loading teachers: ' + data.error,
                        confirmButtonColor: '#007bff'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error: ' + error.message,
                    confirmButtonColor: '#007bff'
                });
            });
        }

        function loadMyRequests() {
            fetch('api/parent_teacher_requests.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=get_my_requests'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let html = '';
                    if (data.requests.length === 0) {
                        html = '<p class="text-muted">No teacher requests yet.</p>';
                    } else {
                        data.requests.forEach(req => {
                            let statusColor = req.status === 'approved' ? 'bg-success' : 
                                            req.status === 'rejected' ? 'bg-danger' : 'bg-warning text-dark';
                            html += `<div class="card mb-2">
                                <div class="card-body">
                                    <h6>${req.teacher_name} - ${req.subject}</h6>
                                    <p class="mb-1"><strong>Status:</strong> <span class="badge ${statusColor}">${req.status.toUpperCase()}</span></p>
                                    <p class="mb-1"><strong>Request Date:</strong> ${new Date(req.created_at).toLocaleDateString()}</p>
                                    <p class="mb-0"><strong>Message:</strong> ${req.message || 'No message'}</p>
                                </div>
                            </div>`;
                        });
                    }
                    document.getElementById('myRequestsList').innerHTML = html;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error loading requests: ' + data.error,
                        confirmButtonColor: '#007bff'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error: ' + error.message,
                    confirmButtonColor: '#007bff'
                });
            });
        }

        function handleTeacherRequest(e) {
            e.preventDefault();
            const formData = new FormData();
            formData.append('action', 'submit_request');
            formData.append('teacher_id', document.getElementById('teacherSelect').value);
            formData.append('subject', document.getElementById('requestSubject').value);
            formData.append('message', document.getElementById('requestMessage').value);

            fetch('api/parent_teacher_requests.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Teacher request submitted successfully!',
                        confirmButtonColor: '#007bff'
                    }).then(() => {
                        document.getElementById('teacherRequestForm').reset();
                        loadMyRequests();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error: ' + data.error,
                        confirmButtonColor: '#007bff'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error: ' + error.message,
                    confirmButtonColor: '#007bff'
                });
            });
        }

        // Profile Management Functions
        function showProfileSettings() {
            new bootstrap.Modal(document.getElementById('profileModal')).show();
            loadParentProfile();
        }

        function closeProfileSettings() {
            bootstrap.Modal.getInstance(document.getElementById('profileModal')).hide();
        }

        function loadParentProfile() {
            fetch('api/parent_profile.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=get_profile'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('profileName').value = data.parent.name;
                    document.getElementById('profileEmail').value = data.parent.email;
                    document.getElementById('profilePhone').value = data.parent.phone || '';
                    document.getElementById('profileAddress').value = data.parent.address || '';
                    document.getElementById('profileOccupation').value = data.parent.occupation || '';
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error loading profile: ' + data.error,
                        confirmButtonColor: '#007bff'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error: ' + error.message,
                    confirmButtonColor: '#007bff'
                });
            });
        }

        function handleUpdateProfile(e) {
            e.preventDefault();
            const formData = new FormData();
            formData.append('action', 'update_profile');
            formData.append('name', document.getElementById('profileName').value);
            formData.append('email', document.getElementById('profileEmail').value);
            formData.append('phone', document.getElementById('profilePhone').value);
            formData.append('address', document.getElementById('profileAddress').value);
            formData.append('occupation', document.getElementById('profileOccupation').value);

            fetch('api/parent_profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Profile updated successfully!',
                        confirmButtonColor: '#007bff'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error: ' + data.error,
                        confirmButtonColor: '#007bff'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error: ' + error.message,
                    confirmButtonColor: '#007bff'
                });
            });
        }

        function handleChangePassword(e) {
            e.preventDefault();
            const formData = new FormData();
            formData.append('action', 'change_password');
            formData.append('current_password', document.getElementById('currentPassword').value);
            formData.append('new_password', document.getElementById('newPassword').value);

            fetch('api/parent_profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Password changed successfully!',
                        confirmButtonColor: '#007bff'
                    }).then(() => {
                        document.getElementById('changePasswordForm').reset();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error: ' + data.error,
                        confirmButtonColor: '#007bff'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error: ' + error.message,
                    confirmButtonColor: '#007bff'
                });
            });
        }

        // Student Management Functions
        function showStudentSettings() {
            new bootstrap.Modal(document.getElementById('studentModal')).show();
            loadStudentInfo();
        }

        function closeStudentSettings() {
            bootstrap.Modal.getInstance(document.getElementById('studentModal')).hide();
        }

        function loadStudentInfo() {
            fetch('api/parent_profile.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=get_student_info'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('studentName').value = data.student.name;
                    document.getElementById('studentEmail').value = data.student.email;
                    document.getElementById('studentClass').value = data.student.class;
                    document.getElementById('studentRoll').value = data.student.roll;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error loading student info: ' + data.error,
                        confirmButtonColor: '#007bff'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error: ' + error.message,
                    confirmButtonColor: '#007bff'
                });
            });
        }

        function handleUpdateStudent(e) {
            e.preventDefault();
            const formData = new FormData();
            formData.append('action', 'update_student_info');
            formData.append('student_id', '<?php echo htmlspecialchars($parent['student_id']); ?>');
            formData.append('name', document.getElementById('studentName').value);
            formData.append('email', document.getElementById('studentEmail').value);
            formData.append('class', document.getElementById('studentClass').value);
            formData.append('roll', document.getElementById('studentRoll').value);

            fetch('api/parent_profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Student information updated successfully!',
                        confirmButtonColor: '#007bff'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error: ' + data.error,
                        confirmButtonColor: '#007bff'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error: ' + error.message,
                    confirmButtonColor: '#007bff'
                });
            });
        }
    </script>
</body>
</html>