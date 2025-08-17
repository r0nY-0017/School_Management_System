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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <header class="dashboard-header">
            <div class="header-left">
                <h1>Teacher Portal</h1>
                <p>Welcome back, <?php echo htmlspecialchars($teacher['name']); ?>!</p>
            </div>
            <div class="header-right">
                <div class="user-profile">
                    <div class="avatar">
                        <?php echo strtoupper(substr($teacher['name'], 0, 1)); ?>
                    </div>
                    <div class="user-info">
                        <h3><?php echo htmlspecialchars($teacher['name']); ?></h3>
                        <span><?php echo htmlspecialchars($teacher['subject']); ?> Teacher</span>
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
                    <i class="fas fa-users"></i>
                </div>
                <h3><?php echo $student_count; ?></h3>
                <p>Total Students</p>
            </div>
            
            <div class="stat-card grades">
                <div class="icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <h3>12</h3>
                <p>Assignments to Grade</p>
            </div>
            
            <div class="stat-card subjects">
                <div class="icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <h3>5</h3>
                <p>Classes Today</p>
            </div>
            
            <div class="stat-card assignments">
                <div class="icon">
                    <i class="fas fa-percentage"></i>
                </div>
                <h3>89%</h3>
                <p>Average Class Performance</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="dashboard-content">
            <div class="content-left">
                <!-- Today's Classes -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2><i class="fas fa-calendar-alt"></i> Today's Classes</h2>
                    </div>
                    <div class="card-body">
                        <div class="schedule-item">
                            <div class="schedule-time">09:00</div>
                            <div class="schedule-details">
                                <h4><?php echo htmlspecialchars($teacher['subject']); ?> - Class 9A</h4>
                                <p>Room 101 • 25 Students</p>
                            </div>
                        </div>
                        <div class="schedule-item">
                            <div class="schedule-time">10:30</div>
                            <div class="schedule-details">
                                <h4><?php echo htmlspecialchars($teacher['subject']); ?> - Class 9B</h4>
                                <p>Room 205 • 28 Students</p>
                            </div>
                        </div>
                        <div class="schedule-item">
                            <div class="schedule-time">12:00</div>
                            <div class="schedule-details">
                                <h4><?php echo htmlspecialchars($teacher['subject']); ?> - Class 10A</h4>
                                <p>Lab 301 • 22 Students</p>
                            </div>
                        </div>
                        <div class="schedule-item">
                            <div class="schedule-time">14:00</div>
                            <div class="schedule-details">
                                <h4>Faculty Meeting</h4>
                                <p>Conference Room • All Teachers</p>
                            </div>
                        </div>
                        <div class="schedule-item">
                            <div class="schedule-time">15:30</div>
                            <div class="schedule-details">
                                <h4><?php echo htmlspecialchars($teacher['subject']); ?> - Class 10B</h4>
                                <p>Room 102 • 24 Students</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Assignments to Grade -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2><i class="fas fa-edit"></i> Assignments to Grade</h2>
                    </div>
                    <div class="card-body">
                        <div class="assignment-item">
                            <div class="assignment-info">
                                <h4>Chapter 5 Quiz - Class 9A</h4>
                                <p>Submitted: 25/25 students • Due for grading</p>
                            </div>
                            <span class="assignment-status status-pending">Grade Now</span>
                        </div>
                        <div class="assignment-item">
                            <div class="assignment-info">
                                <h4>Mid-term Project - Class 10A</h4>
                                <p>Submitted: 22/22 students • Priority</p>
                            </div>
                            <span class="assignment-status status-overdue">Urgent</span>
                        </div>
                        <div class="assignment-item">
                            <div class="assignment-info">
                                <h4>Homework Assignment - Class 9B</h4>
                                <p>Submitted: 26/28 students • 2 pending</p>
                            </div>
                            <span class="assignment-status status-pending">In Progress</span>
                        </div>
                        <div class="assignment-item">
                            <div class="assignment-info">
                                <h4>Lab Report - Class 10B</h4>
                                <p>Submitted: 20/24 students • Due tomorrow</p>
                            </div>
                            <span class="assignment-status status-pending">Review</span>
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
                            <a href="#" class="action-btn" onclick="showStudentManagement()">
                                <i class="fas fa-user-plus"></i> Manage Students
                            </a>
                            <a href="#" class="action-btn secondary" onclick="showProfileSettings()">
                                <i class="fas fa-user-edit"></i> My Profile
                            </a>
                            <a href="#" class="action-btn">
                                <i class="fas fa-plus"></i> Create Assignment
                            </a>
                            <a href="#" class="action-btn secondary">
                                <i class="fas fa-clipboard-list"></i> Take Attendance
                            </a>
                            <a href="#" class="action-btn">
                                <i class="fas fa-chart-bar"></i> View Reports
                            </a>
                            <a href="#" class="action-btn secondary">
                                <i class="fas fa-calendar-plus"></i> Schedule Exam
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2><i class="fas fa-history"></i> Recent Activity</h2>
                    </div>
                    <div class="card-body">
                        <div class="announcement-item">
                            <h4>Assignment Graded</h4>
                            <p>Completed grading for Class 9A Quiz - Average score: 87%</p>
                            <div class="announcement-date">2 hours ago</div>
                        </div>
                        <div class="announcement-item">
                            <h4>Attendance Recorded</h4>
                            <p>Morning attendance recorded for Class 10B - 23/24 present</p>
                            <div class="announcement-date">4 hours ago</div>
                        </div>
                        <div class="announcement-item">
                            <h4>New Assignment Created</h4>
                            <p>Created "Chapter 6 Practice Problems" for Class 9B</p>
                            <div class="announcement-date">Yesterday</div>
                        </div>
                    </div>
                </div>

                <!-- Class Performance -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2><i class="fas fa-chart-line"></i> Class Performance</h2>
                    </div>
                    <div class="card-body">
                        <div style="margin-bottom: 20px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                <span>Class 9A</span>
                                <span>92%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 92%"></div>
                            </div>
                        </div>
                        <div style="margin-bottom: 20px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                <span>Class 9B</span>
                                <span>88%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 88%"></div>
                            </div>
                        </div>
                        <div style="margin-bottom: 20px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                <span>Class 10A</span>
                                <span>95%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 95%"></div>
                            </div>
                        </div>
                        <div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                <span>Class 10B</span>
                                <span>90%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 90%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Events -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2><i class="fas fa-bell"></i> Upcoming Events</h2>
                    </div>
                    <div class="card-body">
                        <div class="announcement-item">
                            <h4>Parent-Teacher Conference</h4>
                            <p>Schedule meetings with parents for semester progress review</p>
                            <div class="announcement-date">December 22, 2024</div>
                        </div>
                        <div class="announcement-item">
                            <h4>Final Exam Preparation</h4>
                            <p>Submit final exam questions and grading rubrics</p>
                            <div class="announcement-date">December 20, 2024</div>
                        </div>
                        <div class="announcement-item">
                            <h4>Faculty Development Workshop</h4>
                            <p>Digital Teaching Tools and Methods</p>
                            <div class="announcement-date">December 25, 2024</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Management Modal -->
    <div id="studentManagementModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 10px; width: 90%; max-width: 800px; max-height: 80%; overflow-y: auto;">
            <h2><i class="fas fa-users"></i> Student Management</h2>
            
            <!-- Add Student Form -->
            <div class="management-section">
                <h3>Add New Student</h3>
                <form id="addStudentForm" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 30px;">
                    <input type="text" id="newStudentId" placeholder="Student ID" required>
                    <input type="text" id="newStudentName" placeholder="Student Name" required>
                    <input type="text" id="newStudentClass" placeholder="Class" required>
                    <input type="text" id="newStudentRoll" placeholder="Roll Number" required>
                    <input type="email" id="newStudentEmail" placeholder="Email" required>
                    <input type="password" id="newStudentPassword" placeholder="Password" required>
                    <button type="submit" style="grid-column: span 2; background: var(--primary-color); color: white; padding: 10px; border: none; border-radius: 5px;">
                        <i class="fas fa-plus"></i> Add Student
                    </button>
                </form>
            </div>
            
            <!-- Students List -->
            <div class="management-section">
                <h3>Current Students</h3>
                <div id="studentsList" style="max-height: 300px; overflow-y: auto;"></div>
            </div>
            
            <button onclick="closeStudentManagement()" style="background: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 5px; float: right;">
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
                    <label>Subject:</label>
                    <input type="text" id="profileSubject" style="width: 100%; padding: 8px; margin-top: 5px;">
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

            // Load teacher profile data
            loadTeacherProfile();
            
            // Add form handlers
            document.getElementById('addStudentForm').addEventListener('submit', handleAddStudent);
            document.getElementById('updateProfileForm').addEventListener('submit', handleUpdateProfile);
            document.getElementById('changePasswordForm').addEventListener('submit', handleChangePassword);
        });
        
        // Student Management Functions
        function showStudentManagement() {
            document.getElementById('studentManagementModal').style.display = 'block';
            loadStudentsList();
        }
        
        function closeStudentManagement() {
            document.getElementById('studentManagementModal').style.display = 'none';
        }
        
        function loadStudentsList() {
            fetch('api/teacher_manage_students.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=get_students'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let html = '<table style="width: 100%; border-collapse: collapse;">';
                    html += '<tr style="background: #f8f9fa;"><th style="padding: 10px; border: 1px solid #ddd;">ID</th><th style="padding: 10px; border: 1px solid #ddd;">Name</th><th style="padding: 10px; border: 1px solid #ddd;">Class</th><th style="padding: 10px; border: 1px solid #ddd;">Roll</th><th style="padding: 10px; border: 1px solid #ddd;">Actions</th></tr>';
                    data.students.forEach(student => {
                        html += `<tr>
                            <td style="padding: 8px; border: 1px solid #ddd;">${student.id}</td>
                            <td style="padding: 8px; border: 1px solid #ddd;">${student.name}</td>
                            <td style="padding: 8px; border: 1px solid #ddd;">${student.class}</td>
                            <td style="padding: 8px; border: 1px solid #ddd;">${student.roll}</td>
                            <td style="padding: 8px; border: 1px solid #ddd;">
                                <button onclick="deleteStudent('${student.id}')" style="background: #dc3545; color: white; padding: 5px 10px; border: none; border-radius: 3px;">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>`;
                    });
                    html += '</table>';
                    document.getElementById('studentsList').innerHTML = html;
                }
            });
        }
        
        function handleAddStudent(e) {
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
                    alert('Student added successfully!');
                    document.getElementById('addStudentForm').reset();
                    loadStudentsList();
                } else {
                    alert('Error: ' + data.error);
                }
            });
        }
        
        function deleteStudent(studentId) {
            if (confirm('Are you sure you want to delete this student?')) {
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
                        alert('Student deleted successfully!');
                        loadStudentsList();
                    } else {
                        alert('Error: ' + data.error);
                    }
                });
            }
        }
        
        // Profile Management Functions
        function showProfileSettings() {
            document.getElementById('profileModal').style.display = 'block';
            loadTeacherProfile();
        }
        
        function closeProfileSettings() {
            document.getElementById('profileModal').style.display = 'none';
        }
        
        function loadTeacherProfile() {
            fetch('api/teacher_profile.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=get_profile'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('profileName').value = data.teacher.name;
                    document.getElementById('profileSubject').value = data.teacher.subject;
                    document.getElementById('profileEmail').value = data.teacher.email;
                }
            });
        }
        
        function handleUpdateProfile(e) {
            e.preventDefault();
            const formData = new FormData();
            formData.append('action', 'update_profile');
            formData.append('name', document.getElementById('profileName').value);
            formData.append('subject', document.getElementById('profileSubject').value);
            formData.append('email', document.getElementById('profileEmail').value);
            
            fetch('api/teacher_profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Profile updated successfully!');
                    location.reload(); // Refresh to show updated info
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
            
            fetch('api/teacher_profile.php', {
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
    </script>
</body>
</html>
