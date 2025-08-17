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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <header class="dashboard-header">
            <div class="header-left">
                <h1>Student Portal</h1>
                <p>Welcome back, <?php echo htmlspecialchars($student['name']); ?>!</p>
            </div>
            <div class="header-right">
                <div class="user-profile">
                    <div class="avatar">
                        <?php echo strtoupper(substr($student['name'], 0, 1)); ?>
                    </div>
                    <div class="user-info">
                        <h3><?php echo htmlspecialchars($student['name']); ?></h3>
                        <span>Class <?php echo htmlspecialchars($student['class']); ?> • Roll: <?php echo htmlspecialchars($student['roll']); ?></span>
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
                <h3>92%</h3>
                <p>Attendance Rate</p>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 92%"></div>
                </div>
            </div>
            
            <div class="stat-card grades">
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>A-</h3>
                <p>Current Grade</p>
            </div>
            
            <div class="stat-card subjects">
                <div class="icon">
                    <i class="fas fa-book"></i>
                </div>
                <h3>6</h3>
                <p>Active Subjects</p>
            </div>
            
            <div class="stat-card assignments">
                <div class="icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <h3>3</h3>
                <p>Pending Assignments</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="dashboard-content">
            <div class="content-left">
                <!-- Today's Schedule -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2><i class="fas fa-clock"></i> Today's Schedule</h2>
                    </div>
                    <div class="card-body">
                        <div class="schedule-item">
                            <div class="schedule-time">09:00</div>
                            <div class="schedule-details">
                                <h4>Mathematics</h4>
                                <p>Room 101 • Algebra & Geometry</p>
                            </div>
                        </div>
                        <div class="schedule-item">
                            <div class="schedule-time">10:30</div>
                            <div class="schedule-details">
                                <h4>English Literature</h4>
                                <p>Room 205 • Poetry Analysis</p>
                            </div>
                        </div>
                        <div class="schedule-item">
                            <div class="schedule-time">12:00</div>
                            <div class="schedule-details">
                                <h4>Physics</h4>
                                <p>Lab 301 • Practical Session</p>
                            </div>
                        </div>
                        <div class="schedule-item">
                            <div class="schedule-time">14:00</div>
                            <div class="schedule-details">
                                <h4>Computer Science</h4>
                                <p>Computer Lab • Programming Basics</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Assignments -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2><i class="fas fa-clipboard-list"></i> Recent Assignments</h2>
                    </div>
                    <div class="card-body">
                        <div class="assignment-item">
                            <div class="assignment-info">
                                <h4>Mathematics Quiz</h4>
                                <p>Due: Tomorrow, 11:59 PM</p>
                            </div>
                            <span class="assignment-status status-pending">Pending</span>
                        </div>
                        <div class="assignment-item">
                            <div class="assignment-info">
                                <h4>English Essay</h4>
                                <p>Due: Dec 20, 2024</p>
                            </div>
                            <span class="assignment-status status-submitted">Submitted</span>
                        </div>
                        <div class="assignment-item">
                            <div class="assignment-info">
                                <h4>Physics Lab Report</h4>
                                <p>Due: Dec 15, 2024</p>
                            </div>
                            <span class="assignment-status status-overdue">Overdue</span>
                        </div>
                        <div class="assignment-item">
                            <div class="assignment-info">
                                <h4>History Project</h4>
                                <p>Due: Dec 25, 2024</p>
                            </div>
                            <span class="assignment-status status-pending">Pending</span>
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
                            <a href="#" class="action-btn" onclick="showProfileSettings()">
                                <i class="fas fa-user-edit"></i> My Profile
                            </a>
                            <a href="#" class="action-btn secondary">
                                <i class="fas fa-book-open"></i> View Grades
                            </a>
                            <a href="#" class="action-btn">
                                <i class="fas fa-calendar"></i> Full Schedule
                            </a>
                            <a href="#" class="action-btn secondary">
                                <i class="fas fa-download"></i> Assignments
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Announcements -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2><i class="fas fa-bullhorn"></i> Announcements</h2>
                    </div>
                    <div class="card-body">
                        <div class="announcement-item">
                            <h4>Winter Break Notice</h4>
                            <p>Classes will be suspended from December 24th to January 2nd for winter break.</p>
                            <div class="announcement-date">December 18, 2024</div>
                        </div>
                        <div class="announcement-item">
                            <h4>Exam Schedule Released</h4>
                            <p>Final examination schedule for the current semester has been published. Check your student portal.</p>
                            <div class="announcement-date">December 15, 2024</div>
                        </div>
                        <div class="announcement-item">
                            <h4>Library Hours Extended</h4>
                            <p>Library will now remain open until 10 PM on weekdays during exam preparation period.</p>
                            <div class="announcement-date">December 12, 2024</div>
                        </div>
                    </div>
                </div>

                <!-- Academic Progress -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2><i class="fas fa-chart-pie"></i> Academic Progress</h2>
                    </div>
                    <div class="card-body">
                        <div style="margin-bottom: 20px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                <span>Mathematics</span>
                                <span>88%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 88%"></div>
                            </div>
                        </div>
                        <div style="margin-bottom: 20px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                <span>English</span>
                                <span>92%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 92%"></div>
                            </div>
                        </div>
                        <div style="margin-bottom: 20px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                <span>Physics</span>
                                <span>85%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 85%"></div>
                            </div>
                        </div>
                        <div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                <span>Computer Science</span>
                                <span>95%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 95%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                    <label>Class:</label>
                    <input type="text" id="profileClass" style="width: 100%; padding: 8px; margin-top: 5px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Roll Number:</label>
                    <input type="text" id="profileRoll" style="width: 100%; padding: 8px; margin-top: 5px;">
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
            
            // Add form handlers
            document.getElementById('updateProfileForm').addEventListener('submit', handleUpdateProfile);
            document.getElementById('changePasswordForm').addEventListener('submit', handleChangePassword);
        });
        
        // Profile Management Functions
        function showProfileSettings() {
            document.getElementById('profileModal').style.display = 'block';
            loadStudentProfile();
        }
        
        function closeProfileSettings() {
            document.getElementById('profileModal').style.display = 'none';
        }
        
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
                    document.getElementById('profileClass').value = data.student.class;
                    document.getElementById('profileRoll').value = data.student.roll;
                    document.getElementById('profileEmail').value = data.student.email;
                }
            });
        }
        
        function handleUpdateProfile(e) {
            e.preventDefault();
            const formData = new FormData();
            formData.append('action', 'update_profile');
            formData.append('name', document.getElementById('profileName').value);
            formData.append('class', document.getElementById('profileClass').value);
            formData.append('roll', document.getElementById('profileRoll').value);
            formData.append('email', document.getElementById('profileEmail').value);
            
            fetch('api/student_profile.php', {
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
            
            fetch('api/student_profile.php', {
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
