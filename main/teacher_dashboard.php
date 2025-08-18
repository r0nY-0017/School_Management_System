<?php
session_start();

// Prevent caching to avoid back button issues
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Check if user is logged in and is a teacher
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: teacher_login.php");
    exit();
}

require_once 'config/db_connect.php';

// Get teacher details
$teacher_id = $_SESSION['user_id'];
$sql = "SELECT * FROM teachers WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $teacher_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$teacher = mysqli_fetch_assoc($result);

if (!$teacher) {
    session_destroy();
    header("Location: teacher_login.php");
    exit();
}

// Get student count for teacher's subject
$sql_students = "SELECT COUNT(*) as total_students FROM students";
$result_students = mysqli_query($conn, $sql_students);
$student_count = mysqli_fetch_assoc($result_students)['total_students'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - <?php echo htmlspecialchars($teacher['name']); ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <div class="container-fluid bg-light min-vh-100">
        <!-- Header -->
        <header class="bg-white shadow-sm py-3 mb-4">
            <div class="container d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">Teacher Portal</h1>
                    <p class="text-muted">Welcome back, <?php echo htmlspecialchars($teacher['name']); ?>!</p>
                </div>
                <div class="d-flex align-items-center">
                    <div class="d-flex align-items-center me-3">
                        <!-- <img src="Uploads/<?php echo htmlspecialchars($teacher['profile_pic']); ?>" alt="Profile Pic" class="rounded-circle me-2" width="40" height="40" onerror="this.src='Uploads/default.jpg'"> -->
                        <div>
                            <h5 class="mb-0"><?php echo htmlspecialchars($teacher['name']); ?></h5>
                            <small class="text-muted"><?php echo htmlspecialchars($teacher['subject']); ?> Teacher</small>
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
                            <i class="fas fa-users fa-2x text-primary mb-2"></i>
                            <h3><?php echo $student_count; ?></h3>
                            <p class="card-text">Total Students</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-clipboard-check fa-2x text-success mb-2"></i>
                            <h3>12</h3>
                            <p class="card-text">Assignments to Grade</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-chalkboard-teacher fa-2x text-info mb-2"></i>
                            <h3>5</h3>
                            <p class="card-text">Classes Today</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-percentage fa-2x text-warning mb-2"></i>
                            <h3>89%</h3>
                            <p class="card-text">Average Class Performance</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="container">
            <div class="row g-3">
                <div class="col-lg-8">
                    <!-- Today's Classes -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-light">
                            <h5><i class="fas fa-calendar-alt me-2"></i>Today's Classes</h5>
                        </div>
                        <div class="card-body bg-light">
                            <div class="d-flex mb-3">
                                <div class="me-3 text-muted">09:00</div>
                                <div>
                                    <h6><?php echo htmlspecialchars($teacher['subject']); ?> - Class 9A</h6>
                                    <p class="mb-0 text-muted">Room 101 • 25 Students</p>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="me-3 text-muted">10:30</div>
                                <div>
                                    <h6><?php echo htmlspecialchars($teacher['subject']); ?> - Class 9B</h6>
                                    <p class="mb-0 text-muted">Room 205 • 28 Students</p>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="me-3 text-muted">12:00</div>
                                <div>
                                    <h6><?php echo htmlspecialchars($teacher['subject']); ?> - Class 10A</h6>
                                    <p class="mb-0 text-muted">Lab 301 • 22 Students</p>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="me-3 text-muted">14:00</div>
                                <div>
                                    <h6>Faculty Meeting</h6>
                                    <p class="mb-0 text-muted">Conference Room • All Teachers</p>
                                </div>
                            </div>
                            <div class="d-flex">
                                <div class="me-3 text-muted">15:30</div>
                                <div>
                                    <h6><?php echo htmlspecialchars($teacher['subject']); ?> - Class 10B</h6>
                                    <p class="mb-0 text-muted">Room 102 • 24 Students</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Assignments to Grade -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h5><i class="fas fa-edit me-2"></i>Assignments to Grade</h5>
                        </div>
                        <div class="card-body bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6>Chapter 5 Quiz - Class 9A</h6>
                                    <p class="mb-0 text-muted">Submitted: 25/25 students • Due for grading</p>
                                </div>
                                <span class="badge bg-warning text-dark">Grade Now</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6>Mid-term Project - Class 10A</h6>
                                    <p class="mb-0 text-muted">Submitted: 22/22 students • Priority</p>
                                </div>
                                <span class="badge bg-danger">Urgent</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6>Homework Assignment - Class 9B</h6>
                                    <p class="mb-0 text-muted">Submitted: 26/28 students • 2 pending</p>
                                </div>
                                <span class="badge bg-info">In Progress</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6>Lab Report - Class 10B</h6>
                                    <p class="mb-0 text-muted">Submitted: 20/24 students • Due tomorrow</p>
                                </div>
                                <span class="badge bg-secondary">Review</span>
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
                                <button class="btn btn-primary" onclick="showStudentManagement()"><i class="fas fa-user-plus me-2"></i>Manage Students</button>
                                <button class="btn btn-outline-primary" onclick="showProfileSettings()"><i class="fas fa-user-edit me-2"></i>My Profile</button>
                                <button class="btn btn-primary"><i class="fas fa-plus me-2"></i>Create Assignment</button>
                                <button class="btn btn-outline-primary"><i class="fas fa-clipboard-list me-2"></i>Take Attendance</button>
                                <button class="btn btn-primary"><i class="fas fa-chart-bar me-2"></i>View Reports</button>
                                <button class="btn btn-outline-primary"><i class="fas fa-calendar-plus me-2"></i>Schedule Exam</button>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-light">
                            <h5><i class="fas fa-history me-2"></i>Recent Activity</h5>
                        </div>
                        <div class="card-body bg-light">
                            <div class="mb-3">
                                <h6>Assignment Graded</h6>
                                <p class="mb-0 text-muted">Completed grading for Class 9A Quiz - Average score: 87%</p>
                                <small class="text-muted">2 hours ago</small>
                            </div>
                            <div class="mb-3">
                                <h6>Attendance Recorded</h6>
                                <p class="mb-0 text-muted">Morning attendance recorded for Class 10B - 23/24 present</p>
                                <small class="text-muted">4 hours ago</small>
                            </div>
                            <div>
                                <h6>New Assignment Created</h6>
                                <p class="mb-0 text-muted">Created "Chapter 6 Practice Problems" for Class 9B</p>
                                <small class="text-muted">Yesterday</small>
                            </div>
                        </div>
                    </div>

                    <!-- Class Performance -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-light">
                            <h5><i class="fas fa-chart-line me-2"></i>Class Performance</h5>
                        </div>
                        <div class="card-body bg-light">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Class 9A</span>
                                    <span>92%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-primary" style="width: 92%"></div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Class 9B</span>
                                    <span>88%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-primary" style="width: 88%"></div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Class 10A</span>
                                    <span>95%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-primary" style="width: 95%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Class 10B</span>
                                    <span>90%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-primary" style="width: 90%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming Events -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h5><i class="fas fa-bell me-2"></i>Upcoming Events</h5>
                        </div>
                        <div class="card-body bg-light">
                            <div class="mb-3">
                                <h6>Parent-Teacher Conference</h6>
                                <p class="mb-0 text-muted">Schedule meetings with parents for semester progress review</p>
                                <small class="text-muted">December 22, 2024</small>
                            </div>
                            <div class="mb-3">
                                <h6>Final Exam Preparation</h6>
                                <p class="mb-0 text-muted">Submit final exam questions and grading rubrics</p>
                                <small class="text-muted">December 20, 2024</small>
                            </div>
                            <div>
                                <h6>Faculty Development Workshop</h6>
                                <p class="mb-0 text-muted">Digital Teaching Tools and Methods</p>
                                <small class="text-muted">December 25, 2024</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white shadow-sm py-3 mt-4">
            <div class="container text-center">
                <p class="mb-0 text-muted">&copy; <?php echo date('Y'); ?> Daffodil School. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <!-- Student Management Modal -->
    <div class="modal fade" id="studentManagementModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-users me-2"></i>Student Management</h5>
                    <button type="button" class="btn-close" onclick="closeStudentManagement()"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <h6>Add New Student</h6>
                        <form id="addStudentForm" class="row g-3">
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="newStudentId" placeholder="Student ID" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="newStudentName" placeholder="Student Name" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="newStudentClass" placeholder="Class" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="newStudentRoll" placeholder="Roll Number" required>
                            </div>
                            <div class="col-md-6">
                                <input type="email" class="form-control" id="newStudentEmail" placeholder="Email" required>
                            </div>
                            <div class="col-md-6">
                                <input type="password" class="form-control" id="newStudentPassword" placeholder="Password" required>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Add Student</button>
                            </div>
                        </form>
                    </div>
                    <div>
                        <h6>Current Students</h6>
                        <div id="studentsList" class="table-responsive"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="closeStudentManagement()"><i class="fas fa-times me-2"></i>Close</button>
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
                            <label class="form-label">Teacher ID</label>
                            <input type="text" class="form-control" id="profileId" value="<?php echo htmlspecialchars($teacher['id']); ?>" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" id="profileName" value="<?php echo htmlspecialchars($teacher['name']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="profileEmail" value="<?php echo htmlspecialchars($teacher['email']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Subject</label>
                            <input type="text" class="form-control" id="profileSubject" value="<?php echo htmlspecialchars($teacher['subject']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" id="profilePhone" value="<?php echo htmlspecialchars($teacher['phone'] ?? ''); ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" id="profileAddress" rows="3"><?php echo htmlspecialchars($teacher['address'] ?? ''); ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Gender</label>
                            <select class="form-select" id="profileGender">
                                <option value="Male" <?php echo $teacher['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo $teacher['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                                <option value="Other" <?php echo $teacher['gender'] === 'Other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="profileDob" value="<?php echo htmlspecialchars($teacher['date_of_birth'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Joining Date</label>
                            <input type="date" class="form-control" id="profileJoiningDate" value="<?php echo htmlspecialchars($teacher['joining_date'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Qualification</label>
                            <input type="text" class="form-control" id="profileQualification" value="<?php echo htmlspecialchars($teacher['qualification'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Experience</label>
                            <input type="text" class="form-control" id="profileExperience" value="<?php echo htmlspecialchars($teacher['experience'] ?? ''); ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Bio</label>
                            <textarea class="form-control" id="profileBio" rows="4"><?php echo htmlspecialchars($teacher['bio'] ?? ''); ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="profileStatus">
                                <option value="Active" <?php echo $teacher['status'] === 'Active' ? 'selected' : ''; ?>>Active</option>
                                <option value="Inactive" <?php echo $teacher['status'] === 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
                                <option value="On Leave" <?php echo $teacher['status'] === 'On Leave' ? 'selected' : ''; ?>>On Leave</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Login</label>
                            <input type="text" class="form-control" id="profileLastLogin" value="<?php echo htmlspecialchars($teacher['last_login'] ?? ''); ?>" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Created At</label>
                            <input type="text" class="form-control" id="profileCreatedAt" value="<?php echo htmlspecialchars($teacher['created_at']); ?>" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Updated At</label>
                            <input type="text" class="form-control" id="profileUpdatedAt" value="<?php echo htmlspecialchars($teacher['updated_at']); ?>" disabled>
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
    function showStudentManagement() {
        new bootstrap.Modal(document.getElementById('studentManagementModal')).show();
        loadStudentsList();
    }

    function closeStudentManagement() {
        bootstrap.Modal.getInstance(document.getElementById('studentManagementModal')).hide();
    }

    function showProfileSettings() {
        new bootstrap.Modal(document.getElementById('profileModal')).show();
    }

    function closeProfileSettings() {
        bootstrap.Modal.getInstance(document.getElementById('profileModal')).hide();
    }

    // Handle Update Profile
    document.getElementById('updateProfileForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData();
        formData.append('action', 'update_profile');
        formData.append('name', document.getElementById('profileName').value);
        formData.append('email', document.getElementById('profileEmail').value);
        formData.append('subject', document.getElementById('profileSubject').value);
        formData.append('phone', document.getElementById('profilePhone').value);
        formData.append('address', document.getElementById('profileAddress').value);
        formData.append('gender', document.getElementById('profileGender').value);
        formData.append('date_of_birth', document.getElementById('profileDob').value);
        formData.append('joining_date', document.getElementById('profileJoiningDate').value);
        formData.append('qualification', document.getElementById('profileQualification').value);
        formData.append('experience', document.getElementById('profileExperience').value);
        formData.append('bio', document.getElementById('profileBio').value);
        formData.append('status', document.getElementById('profileStatus').value);

        fetch('api/teacher_profile.php', {
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

        fetch('api/teacher_profile.php', {
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

    // Student Management Functions
    function loadStudentsList() {
        fetch('api/teacher_manage_students.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'action=get_students'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let html = '<table class="table table-striped table-bordered">';
                html += '<thead><tr><th>ID</th><th>Name</th><th>Class</th><th>Roll</th><th>Actions</th></tr></thead><tbody>';
                data.students.forEach(student => {
                    html += `<tr>
                        <td>${student.id}</td>
                        <td>${student.name}</td>
                        <td>${student.class}</td>
                        <td>${student.roll}</td>
                        <td>
                            <button onclick="deleteStudent('${student.id}')" class="btn btn-sm btn-danger"><i class="fas fa-trash me-2"></i>Delete</button>
                        </td>
                    </tr>`;
                });
                html += '</tbody></table>';
                document.getElementById('studentsList').innerHTML = html;
            } else {
                document.getElementById('studentsList').innerHTML = '<p class="text-danger">Error loading students: ' + data.error + '</p>';
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error loading students: ' + error.message,
                confirmButtonColor: '#007bff'
            });
        });
    }

    document.getElementById('addStudentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData();
        formData.append('action', 'add_student');
        formData.append('student_id', document.getElementById('newStudentId').value);
        formData.append('name', document.getElementById('newStudentName').value);
        formData.append('class', document.getElementById('newStudentClass').value);
        formData.append('roll', document.getElementById('newStudentRoll').value);
        formData.append('email', document.getElementById('newStudentEmail').value);
        formData.append('password', document.getElementById('newStudentPassword').value);

        fetch('api/teacher_manage_students.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Student added successfully!',
                    confirmButtonColor: '#007bff'
                }).then(() => {
                    document.getElementById('addStudentForm').reset();
                    loadStudentsList();
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

    function deleteStudent(studentId) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to delete this student?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#dc3545',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('action', 'delete_student');
                formData.append('student_id', studentId);

                fetch('api/teacher_manage_students.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted',
                            text: 'Student deleted successfully!',
                            confirmButtonColor: '#007bff'
                        }).then(() => {
                            loadStudentsList();
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
        });
    }
</script>
</body>
</html>