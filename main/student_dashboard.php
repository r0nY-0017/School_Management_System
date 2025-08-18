<?php
session_start();

// Prevent caching to avoid back button issues
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Check if user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: student_login.php");
    exit();
}

require_once 'config/db_connect.php';

// Get student details
$student_id = $_SESSION['user_id'];
$sql = "SELECT * FROM students WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $student_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$student = mysqli_fetch_assoc($result);

if (!$student) {
    session_destroy();
    header("Location: student_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - <?php echo htmlspecialchars($student['name']); ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/student_dashboard.css">
</head>
<body>
    <div class="container-fluid bg-light min-vh-100">
        <!-- Header -->
        <header class="bg-white shadow-sm py-3 mb-4">
            <div class="container d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">Student Portal</h1>
                    <p class="text-muted">Welcome back, <?php echo htmlspecialchars($student['name']); ?>!</p>
                </div>
                <div class="d-flex align-items-center">
                    <div class="d-flex align-items-center me-3">
                        <div class="avatar rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px; font-size: 18px;">
                            <?php echo strtoupper(substr($student['name'], 0, 1)); ?>
                        </div>
                        <div>
                            <h5 class="mb-0"><?php echo htmlspecialchars($student['name']); ?></h5>
                            <small class="text-muted">Class <?php echo htmlspecialchars($student['class']); ?> • Roll: <?php echo htmlspecialchars($student['roll']); ?></small>
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
                            <h3>92%</h3>
                            <p class="card-text">Attendance Rate</p>
                            <div class="progress">
                                <div class="progress-bar bg-primary" style="width: 92%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-chart-line fa-2x text-success mb-2"></i>
                            <h3>A-</h3>
                            <p class="card-text">Current Grade</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-book fa-2x text-info mb-2"></i>
                            <h3>6</h3>
                            <p class="card-text">Active Subjects</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-tasks fa-2x text-warning mb-2"></i>
                            <h3>3</h3>
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
                    <!-- Today's Schedule -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-light">
                            <h5><i class="fas fa-clock me-2"></i>Today's Schedule</h5>
                        </div>
                        <div class="card-body bg-light">
                            <div class="d-flex mb-3">
                                <div class="me-3 text-muted">09:00</div>
                                <div>
                                    <h6>Mathematics</h6>
                                    <p class="mb-0 text-muted">Room 101 • Algebra & Geometry</p>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="me-3 text-muted">10:30</div>
                                <div>
                                    <h6>English Literature</h6>
                                    <p class="mb-0 text-muted">Room 205 • Poetry Analysis</p>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="me-3 text-muted">12:00</div>
                                <div>
                                    <h6>Physics</h6>
                                    <p class="mb-0 text-muted">Lab 301 • Practical Session</p>
                                </div>
                            </div>
                            <div class="d-flex">
                                <div class="me-3 text-muted">14:00</div>
                                <div>
                                    <h6>Computer Science</h6>
                                    <p class="mb-0 text-muted">Computer Lab • Programming Basics</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Assignments -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h5><i class="fas fa-clipboard-list me-2"></i>Recent Assignments</h5>
                        </div>
                        <div class="card-body bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6>Mathematics Quiz</h6>
                                    <p class="mb-0 text-muted">Due: Tomorrow, 11:59 PM</p>
                                </div>
                                <span class="badge bg-warning text-dark">Pending</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6>English Essay</h6>
                                    <p class="mb-0 text-muted">Due: Dec 20, 2024</p>
                                </div>
                                <span class="badge bg-success">Submitted</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6>Physics Lab Report</h6>
                                    <p class="mb-0 text-muted">Due: Dec 15, 2024</p>
                                </div>
                                <span class="badge bg-danger">Overdue</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6>History Project</h6>
                                    <p class="mb-0 text-muted">Due: Dec 25, 2024</p>
                                </div>
                                <span class="badge bg-warning text-dark">Pending</span>
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
                                <button class="btn btn-primary" onclick="showProfileSettings()"><i class="fas fa-user-edit me-2"></i>My Profile</button>
                                <button class="btn btn-outline-primary"><i class="fas fa-book-open me-2"></i>View Grades</button>
                                <button class="btn btn-primary"><i class="fas fa-calendar me-2"></i>Full Schedule</button>
                                <button class="btn btn-outline-primary"><i class="fas fa-download me-2"></i>Assignments</button>
                            </div>
                        </div>
                    </div>

                    <!-- Announcements -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-light">
                            <h5><i class="fas fa-bullhorn me-2"></i>Announcements</h5>
                        </div>
                        <div class="card-body bg-light">
                            <div class="mb-3">
                                <h6>Winter Break Notice</h6>
                                <p class="mb-0 text-muted">Classes will be suspended from December 24th to January 2nd for winter break.</p>
                                <small class="text-muted">December 18, 2024</small>
                            </div>
                            <div class="mb-3">
                                <h6>Exam Schedule Released</h6>
                                <p class="mb-0 text-muted">Final examination schedule for the current semester has been published.</p>
                                <small class="text-muted">December 15, 2024</small>
                            </div>
                            <div>
                                <h6>Library Hours Extended</h6>
                                <p class="mb-0 text-muted">Library will remain open until 10 PM on weekdays during exam period.</p>
                                <small class="text-muted">December 12, 2024</small>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Progress -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h5><i class="fas fa-chart-pie me-2"></i>Academic Progress</h5>
                        </div>
                        <div class="card-body bg-light">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Mathematics</span>
                                    <span>88%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-primary" style="width: 88%"></div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>English</span>
                                    <span>92%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-primary" style="width: 92%"></div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Physics</span>
                                    <span>85%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-primary" style="width: 85%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Computer Science</span>
                                    <span>95%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-primary" style="width: 95%"></div>
                                </div>
                            </div>
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
                            <label class="form-label">Student ID</label>
                            <input type="text" class="form-control" id="profileId" value="<?php echo htmlspecialchars($student['id']); ?>" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" id="profileName" value="<?php echo htmlspecialchars($student['name']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="profileEmail" value="<?php echo htmlspecialchars($student['email']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Class</label>
                            <input type="text" class="form-control" id="profileClass" value="<?php echo htmlspecialchars($student['class']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Roll Number</label>
                            <input type="text" class="form-control" id="profileRoll" value="<?php echo htmlspecialchars($student['roll']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" id="profilePhone" value="<?php echo htmlspecialchars($student['phone'] ?? ''); ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" id="profileAddress" rows="3"><?php echo htmlspecialchars($student['address'] ?? ''); ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="profileDob" value="<?php echo htmlspecialchars($student['dob'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Gender</label>
                            <select class="form-select" id="profileGender">
                                <option value="Male" <?php echo $student['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo $student['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                                <option value="Other" <?php echo $student['gender'] === 'Other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Guardian Name</label>
                            <input type="text" class="form-control" id="profileGuardianName" value="<?php echo htmlspecialchars($student['guardian_name'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Guardian Phone</label>
                            <input type="text" class="form-control" id="profileGuardianPhone" value="<?php echo htmlspecialchars($student['guardian_phone'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Admission Date</label>
                            <input type="date" class="form-control" id="profileAdmissionDate" value="<?php echo htmlspecialchars($student['admission_date'] ?? ''); ?>" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <input type="text" class="form-control" id="profileStatus" value="<?php echo htmlspecialchars($student['status']); ?>" disabled>
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

        // Modal Functions
        function showProfileSettings() {
            new bootstrap.Modal(document.getElementById('profileModal')).show();
            loadStudentProfile();
        }

        function closeProfileSettings() {
            bootstrap.Modal.getInstance(document.getElementById('profileModal')).hide();
        }

        // Load Student Profile
        function loadStudentProfile() {
            fetch('api/student_profile.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=get_profile'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('profileName').value = data.student.name;
                    document.getElementById('profileEmail').value = data.student.email;
                    document.getElementById('profileClass').value = data.student.class;
                    document.getElementById('profileRoll').value = data.student.roll;
                    document.getElementById('profilePhone').value = data.student.phone || '';
                    document.getElementById('profileAddress').value = data.student.address || '';
                    document.getElementById('profileDob').value = data.student.dob || '';
                    document.getElementById('profileGender').value = data.student.gender || '';
                    document.getElementById('profileGuardianName').value = data.student.guardian_name || '';
                    document.getElementById('profileGuardianPhone').value = data.student.guardian_phone || '';
                    document.getElementById('profileAdmissionDate').value = data.student.admission_date || '';
                    document.getElementById('profileStatus').value = data.student.status || 'Active';
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

        // Handle Update Profile
        document.getElementById('updateProfileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData();
            formData.append('action', 'update_profile');
            formData.append('name', document.getElementById('profileName').value);
            formData.append('email', document.getElementById('profileEmail').value);
            formData.append('class', document.getElementById('profileClass').value);
            formData.append('roll', document.getElementById('profileRoll').value);
            formData.append('phone', document.getElementById('profilePhone').value);
            formData.append('address', document.getElementById('profileAddress').value);
            formData.append('dob', document.getElementById('profileDob').value);
            formData.append('gender', document.getElementById('profileGender').value);
            formData.append('guardian_name', document.getElementById('profileGuardianName').value);
            formData.append('guardian_phone', document.getElementById('profileGuardianPhone').value);

            fetch('api/student_profile.php', {
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
        });

        // Handle Change Password
        document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData();
            formData.append('action', 'change_password');
            formData.append('current_password', document.getElementById('currentPassword').value);
            formData.append('new_password', document.getElementById('newPassword').value);

            fetch('api/student_profile.php', {
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
        });
    </script>
</body>
</html>