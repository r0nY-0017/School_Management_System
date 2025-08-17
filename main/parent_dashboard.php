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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <header class="dashboard-header">
            <div class="header-left">
                <h1>Parent Portal</h1>
                <p>Welcome back, <?php echo htmlspecialchars($parent['name']); ?>!</p>
            </div>
            <div class="header-right">
                <div class="user-profile">
                    <div class="avatar">
                        <?php echo strtoupper(substr($parent['name'], 0, 1)); ?>
                    </div>
                    <div class="user-info">
                        <h3><?php echo htmlspecialchars($parent['name']); ?></h3>
                        <span>Parent of <?php echo $student ? htmlspecialchars($student['name']) : 'Student'; ?></span>
                    </div>
                </div>
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </header>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card attendance">
                <div class="icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3>94%</h3>
                <p>Child's Attendance</p>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 94%"></div>
                </div>
            </div>
            
            <div class="stat-card grades">
                <div class="icon">
                    <i class="fas fa-star"></i>
                </div>
                <h3>A-</h3>
                <p>Overall Grade</p>
            </div>
            
            <div class="stat-card subjects">
                <div class="icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <h3>6</h3>
                <p>Subjects Enrolled</p>
            </div>
            
            <div class="stat-card assignments">
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3>2</h3>
                <p>Pending Assignments</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="dashboard-content">
            <div class="content-left">
                <!-- Child's Information -->
                <?php if ($student): ?>
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2><i class="fas fa-user-graduate"></i> Child's Information</h2>
                    </div>
                    <div class="card-body">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div>
                                <strong>Name:</strong> <?php echo htmlspecialchars($student['name']); ?>
                            </div>
                            <div>
                                <strong>Student ID:</strong> <?php echo htmlspecialchars($student['id']); ?>
                            </div>
                            <div>
                                <strong>Class:</strong> <?php echo htmlspecialchars($student['class']); ?>
                            </div>
                            <div>
                                <strong>Roll Number:</strong> <?php echo htmlspecialchars($student['roll']); ?>
                            </div>
                            <div>
                                <strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Recent Grades -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2><i class="fas fa-chart-line"></i> Recent Grades</h2>
                    </div>
                    <div class="card-body">
                        <div class="assignment-item">
                            <div class="assignment-info">
                                <h4>Mathematics Quiz</h4>
                                <p>Chapter 5 Assessment</p>
                            </div>
                            <span class="assignment-status" style="background: #d4edda; color: #155724;">A</span>
                        </div>
                        <div class="assignment-item">
                            <div class="assignment-info">
                                <h4>English Essay</h4>
                                <p>Creative Writing Assignment</p>
                            </div>
                            <span class="assignment-status" style="background: #d4edda; color: #155724;">A-</span>
                        </div>
                        <div class="assignment-item">
                            <div class="assignment-info">
                                <h4>Physics Lab Report</h4>
                                <p>Experiment Analysis</p>
                            </div>
                            <span class="assignment-status" style="background: #fff3cd; color: #856404;">B+</span>
                        </div>
                        <div class="assignment-item">
                            <div class="assignment-info">
                                <h4>History Project</h4>
                                <p>World War II Research</p>
                            </div>
                            <span class="assignment-status" style="background: #d4edda; color: #155724;">A</span>
                        </div>
                    </div>
                </div>

                <!-- Attendance Record -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2><i class="fas fa-calendar-alt"></i> Attendance Record</h2>
                    </div>
                    <div class="card-body">
                        <div style="margin-bottom: 20px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                <span>This Month</span>
                                <span>94% (23/24 days)</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 94%"></div>
                            </div>
                        </div>
                        <div style="margin-bottom: 20px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                <span>Last Month</span>
                                <span>96% (25/26 days)</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 96%"></div>
                            </div>
                        </div>
                        <div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                <span>Semester Total</span>
                                <span>95% (142/150 days)</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 95%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-right">
                <!-- Quick Actions -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2><i class="fas fa-bolt"></i> Quick Actions</h2>
                    </div>
                    <div class="card-body">
                        <div class="quick-actions">
                            <a href="#" class="action-btn" onclick="showTeacherRequest()">
                                <i class="fas fa-chalkboard-teacher"></i> Request Teacher
                            </a>
                            <a href="#" class="action-btn secondary" onclick="showProfileSettings()">
                                <i class="fas fa-user-edit"></i> Manage Profile
                            </a>
                            <a href="#" class="action-btn" onclick="showStudentSettings()">
                                <i class="fas fa-user-graduate"></i> Student Info
                            </a>
                            <a href="#" class="action-btn secondary">
                                <i class="fas fa-download"></i> Download Report
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Teacher Messages -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2><i class="fas fa-comments"></i> Teacher Messages</h2>
                    </div>
                    <div class="card-body">
                        <div class="announcement-item">
                            <h4>Mathematics Teacher</h4>
                            <p>Your child is showing excellent progress in algebra. Keep up the good work!</p>
                            <div class="announcement-date">2 days ago</div>
                        </div>
                        <div class="announcement-item">
                            <h4>English Teacher</h4>
                            <p>Please ensure homework is submitted on time. Late submissions affect grades.</p>
                            <div class="announcement-date">5 days ago</div>
                        </div>
                        <div class="announcement-item">
                            <h4>Class Teacher</h4>
                            <p>Parent-teacher meeting scheduled for next week. Please confirm attendance.</p>
                            <div class="announcement-date">1 week ago</div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Events -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2><i class="fas fa-calendar-plus"></i> Upcoming Events</h2>
                    </div>
                    <div class="card-body">
                        <div class="announcement-item">
                            <h4>Parent-Teacher Conference</h4>
                            <p>Individual meetings with all subject teachers</p>
                            <div class="announcement-date">December 22, 2024</div>
                        </div>
                        <div class="announcement-item">
                            <h4>Final Exams</h4>
                            <p>Semester final examinations begin</p>
                            <div class="announcement-date">January 5, 2025</div>
                        </div>
                        <div class="announcement-item">
                            <h4>Science Fair</h4>
                            <p>Annual science project exhibition</p>
                            <div class="announcement-date">January 15, 2025</div>
                        </div>
                    </div>
                </div>

                <!-- Fee Information -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2><i class="fas fa-money-bill"></i> Fee Information</h2>
                    </div>
                    <div class="card-body">
                        <div style="margin-bottom: 15px;">
                            <div style="display: flex; justify-content: space-between;">
                                <span><strong>Total Fee:</strong></span>
                                <span>$2,500</span>
                            </div>
                        </div>
                        <div style="margin-bottom: 15px;">
                            <div style="display: flex; justify-content: space-between;">
                                <span><strong>Paid:</strong></span>
                                <span style="color: #28a745;">$2,000</span>
                            </div>
                        </div>
                        <div style="margin-bottom: 15px;">
                            <div style="display: flex; justify-content: space-between;">
                                <span><strong>Remaining:</strong></span>
                                <span style="color: #dc3545;">$500</span>
                            </div>
                        </div>
                        <div style="margin-top: 15px;">
                            <a href="#" class="action-btn" style="width: 100%; text-align: center;">
                                <i class="fas fa-credit-card"></i> Pay Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Teacher Request Modal -->
    <div id="teacherRequestModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 10px; width: 90%; max-width: 700px; max-height: 80%; overflow-y: auto;">
            <h2><i class="fas fa-chalkboard-teacher"></i> Request Teacher Assignment</h2>
            
            <!-- Teacher Request Form -->
            <form id="teacherRequestForm" style="margin-bottom: 30px;">
                <div style="margin-bottom: 15px;">
                    <label>Select Teacher:</label>
                    <select id="teacherSelect" style="width: 100%; padding: 8px; margin-top: 5px;" required>
                        <option value="">Choose a teacher...</option>
                    </select>
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Subject:</label>
                    <input type="text" id="requestSubject" style="width: 100%; padding: 8px; margin-top: 5px;" placeholder="Enter subject" required>
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Message to Admin:</label>
                    <textarea id="requestMessage" style="width: 100%; padding: 8px; margin-top: 5px; min-height: 80px;" placeholder="Explain why you want this teacher for your child..."></textarea>
                </div>
                <button type="submit" style="background: var(--primary-color); color: white; padding: 10px 20px; border: none; border-radius: 5px;">
                    <i class="fas fa-paper-plane"></i> Submit Request
                </button>
            </form>
            
            <!-- My Requests -->
            <div>
                <h3>My Teacher Requests</h3>
                <div id="myRequestsList"></div>
            </div>
            
            <button onclick="closeTeacherRequest()" style="background: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 5px; float: right;">
                <i class="fas fa-times"></i> Close
            </button>
        </div>
    </div>

    <!-- Profile Settings Modal -->
    <div id="profileModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 10px; width: 90%; max-width: 500px;">
            <h2><i class="fas fa-user-edit"></i> Profile Settings</h2>
            
            <!-- Update Profile Form -->
            <form id="updateProfileForm" style="margin-bottom: 20px;">
                <div style="margin-bottom: 15px;">
                    <label>Name:</label>
                    <input type="text" id="profileName" style="width: 100%; padding: 8px; margin-top: 5px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Email:</label>
                    <input type="email" id="profileEmail" style="width: 100%; padding: 8px; margin-top: 5px;">
                </div>
                <button type="submit" style="background: var(--primary-color); color: white; padding: 10px 20px; border: none; border-radius: 5px;">
                    <i class="fas fa-save"></i> Update Profile
                </button>
            </form>
            
            <!-- Change Password Form -->
            <form id="changePasswordForm">
                <h3>Change Password</h3>
                <div style="margin-bottom: 15px;">
                    <label>Current Password:</label>
                    <input type="password" id="currentPassword" style="width: 100%; padding: 8px; margin-top: 5px;" required>
                </div>
                <div style="margin-bottom: 15px;">
                    <label>New Password:</label>
                    <input type="password" id="newPassword" style="width: 100%; padding: 8px; margin-top: 5px;" required>
                </div>
                <button type="submit" style="background: var(--warning-color); color: black; padding: 10px 20px; border: none; border-radius: 5px;">
                    <i class="fas fa-key"></i> Change Password
                </button>
            </form>
            
            <button onclick="closeProfileSettings()" style="background: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 5px; float: right; margin-top: 20px;">
                <i class="fas fa-times"></i> Close
            </button>
        </div>
    </div>

    <!-- Student Settings Modal -->
    <div id="studentModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 10px; width: 90%; max-width: 500px;">
            <h2><i class="fas fa-user-graduate"></i> Student Information</h2>
            
            <form id="updateStudentForm">
                <div style="margin-bottom: 15px;">
                    <label>Student Name:</label>
                    <input type="text" id="studentName" style="width: 100%; padding: 8px; margin-top: 5px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Class:</label>
                    <input type="text" id="studentClass" style="width: 100%; padding: 8px; margin-top: 5px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Roll Number:</label>
                    <input type="text" id="studentRoll" style="width: 100%; padding: 8px; margin-top: 5px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Email:</label>
                    <input type="email" id="studentEmail" style="width: 100%; padding: 8px; margin-top: 5px;">
                </div>
                <button type="submit" style="background: var(--primary-color); color: white; padding: 10px 20px; border: none; border-radius: 5px;">
                    <i class="fas fa-save"></i> Update Student Info
                </button>
            </form>
            
            <button onclick="closeStudentSettings()" style="background: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 5px; float: right; margin-top: 20px;">
                <i class="fas fa-times"></i> Close
            </button>
        </div>
    </div>

    <script>
        // Prevent back button navigation
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            history.go(1);
        };
        
        // Disable keyboard shortcuts that might navigate away
        document.addEventListener('keydown', function(e) {
            // Disable F5, Ctrl+R (refresh)
            if (e.key === 'F5' || (e.ctrlKey && e.key === 'r')) {
                e.preventDefault();
                return false;
            }
            // Disable Alt+Left (back), Alt+Right (forward)
            if (e.altKey && (e.key === 'ArrowLeft' || e.key === 'ArrowRight')) {
                e.preventDefault();
                return false;
            }
            // Disable Backspace (back) when not in input field
            if (e.key === 'Backspace' && !['INPUT', 'TEXTAREA'].includes(e.target.tagName)) {
                e.preventDefault();
                return false;
            }
        });

        // Add some interactivity
        document.addEventListener('DOMContentLoaded', function() {
            // Animate progress bars
            const progressBars = document.querySelectorAll('.progress-fill');
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.width = width;
                }, 500);
            });

            // Add hover effects to cards
            const cards = document.querySelectorAll('.stat-card, .dashboard-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Add form handlers
            document.getElementById('teacherRequestForm').addEventListener('submit', handleTeacherRequest);
            document.getElementById('updateProfileForm').addEventListener('submit', handleUpdateProfile);
            document.getElementById('changePasswordForm').addEventListener('submit', handleChangePassword);
            document.getElementById('updateStudentForm').addEventListener('submit', handleUpdateStudent);
        });
        
        // Teacher Request Functions
        function showTeacherRequest() {
            document.getElementById('teacherRequestModal').style.display = 'block';
            loadTeachersList();
            loadMyRequests();
        }
        
        function closeTeacherRequest() {
            document.getElementById('teacherRequestModal').style.display = 'none';
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
                }
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
                        html = '<p>No teacher requests yet.</p>';
                    } else {
                        data.requests.forEach(req => {
                            let statusColor = req.status === 'approved' ? '#28a745' : 
                                            req.status === 'rejected' ? '#dc3545' : '#ffc107';
                            html += `<div style="border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 5px;">
                                <h4>${req.teacher_name} - ${req.subject}</h4>
                                <p><strong>Status:</strong> <span style="color: ${statusColor}; font-weight: bold;">${req.status.toUpperCase()}</span></p>
                                <p><strong>Request Date:</strong> ${new Date(req.created_at).toLocaleDateString()}</p>
                                <p><strong>Message:</strong> ${req.message || 'No message'}</p>
                            </div>`;
                        });
                    }
                    document.getElementById('myRequestsList').innerHTML = html;
                }
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
                    alert('Teacher request submitted successfully!');
                    document.getElementById('teacherRequestForm').reset();
                    loadMyRequests();
                } else {
                    alert('Error: ' + data.error);
                }
            });
        }
        
        // Profile Management Functions
        function showProfileSettings() {
            document.getElementById('profileModal').style.display = 'block';
            loadParentProfile();
        }
        
        function closeProfileSettings() {
            document.getElementById('profileModal').style.display = 'none';
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
                }
            });
        }
        
        function handleUpdateProfile(e) {
            e.preventDefault();
            const formData = new FormData();
            formData.append('action', 'update_profile');
            formData.append('name', document.getElementById('profileName').value);
            formData.append('email', document.getElementById('profileEmail').value);
            
            fetch('api/parent_profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Profile updated successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.error);
                }
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
                    alert('Password changed successfully!');
                    document.getElementById('changePasswordForm').reset();
                } else {
                    alert('Error: ' + data.error);
                }
            });
        }
        
        // Student Management Functions
        function showStudentSettings() {
            document.getElementById('studentModal').style.display = 'block';
            loadStudentInfo();
        }
        
        function closeStudentSettings() {
            document.getElementById('studentModal').style.display = 'none';
        }
        
        function loadStudentInfo() {
            fetch('api/parent_profile.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=get_profile'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.parent.student_name) {
                    document.getElementById('studentName').value = data.parent.student_name;
                    document.getElementById('studentClass').value = data.parent.class;
                    document.getElementById('studentRoll').value = data.parent.roll;
                    document.getElementById('studentEmail').value = data.parent.email;
                }
            });
        }
        
        function handleUpdateStudent(e) {
            e.preventDefault();
            const formData = new FormData();
            formData.append('action', 'update_student_info');
            formData.append('student_name', document.getElementById('studentName').value);
            formData.append('student_class', document.getElementById('studentClass').value);
            formData.append('student_roll', document.getElementById('studentRoll').value);
            formData.append('student_email', document.getElementById('studentEmail').value);
            
            fetch('api/parent_profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Student information updated successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.error);
                }
            });
        }
    </script>
</body>
</html>
