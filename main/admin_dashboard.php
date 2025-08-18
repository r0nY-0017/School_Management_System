<?php
session_start();
require_once 'config/db_connect.php';
include 'api/get_admin_details.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>School Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" />
    <link rel="stylesheet" href="css/admin_dashboard.css">
</head>
<body>

    <!-- Side Bar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h4><i class="fas fa-graduation-cap mr-2"></i> Daffodil School</h4>
        </div>

        <div class="profile-section">
            <div class="profile-img-container">
                <img src="images/admin.jpg" class="profile-img" alt="Profile">
                <div class="profile-upload-btn" title="Change Photo">
                    <i class="fas fa-camera"></i>
                </div>
            </div>
            <div class="profile-name">Md. Mehedi Hasan</div>
            <div class="profile-role">Super Administrator</div>
        </div>

        <div class="sidebar-menu">
            <a href="#" class="active" data-section="dashboard"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a>
            <a href="#" data-section="profile"><i class="fas fa-user"></i> <span>Profile</span></a>
            <a href="#" data-section="students"><i class="fas fa-user-graduate"></i> <span>Students</span></a>
            <a href="#" data-section="teachers"><i class="fas fa-chalkboard-teacher"></i> <span>Teachers</span></a>
            <a href="#" data-section="parents"><i class="fas fa-user-friends"></i> <span>Parents</span></a>
            <a href="#" onclick="logout()"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
        </div>
    </div>

    <div class="main-content">
        <!-- Dashboard Section -->
        <div id="dashboard" class="form-section active">
            <div class="header">
                <h2>Welcome back, Admin!</h2>
                <div class="header-actions">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search...">
                    </div>
                    <div class="notification-bell">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </div>
                    <div class="user-menu">
                        <img src="images/admin.jpg" alt="User">
                        <span>Md. Mehedi Hasan</span>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="row">
                <div class="col-md-4">
                    <div class="card stat-card">
                        <div class="card-body">
                            <i class="fas fa-user-graduate"></i>
                            <h3>735</h3>
                            <p>Total Students</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card stat-card">
                        <div class="card-body">
                            <i class="fas fa-chalkboard-teacher"></i>
                            <h3>15</h3>
                            <p>Total Teachers</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card stat-card">
                        <div class="card-body">
                            <i class="fas fa-clipboard-check"></i>
                            <h3>85%</h3>
                            <p>Today's Attendance</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Calendar -->
            <div class="row mt-4">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5>Performance Overview</h5>
                            <div class="card-actions">
                                <i class="fas fa-ellipsis-v"></i>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="lineChart" height="250"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Class Distribution</h5>
                            <div class="card-actions">
                                <i class="fas fa-ellipsis-v"></i>
                            </div>
                        </div>
                        <div class="card-body class-distribution">
                            <canvas id="classPieChart" height="250"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Second Row -->
            <div class="row mt-4">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Upcoming Events</h5>
                            <div class="card-actions">
                                <i class="fas fa-ellipsis-v"></i>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Recent Activities</h5>
                            <div class="card-actions">
                                <i class="fas fa-ellipsis-v"></i>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="activity-item">
                                <div class="activity-badge">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-text">New student registered - Sunzil Khandakar (Class 9)</div>
                                    <div class="activity-time">40 minutes ago</div>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-badge">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-text">Fee payment received from Rohim Ali</div>
                                    <div class="activity-time">1 hour ago</div>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-badge">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-text">PTM scheduled for August 5</div>
                                    <div class="activity-time">3 hours ago</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information Section -->
            <div class="row mt-4 info-section">
                <div class="col-md-6">
                    <div class="card info-card">
                        <h5><i class="fas fa-info-circle mr-2"></i> Daffodil School Information</h5>
                        <div class="info-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <strong>Address:</strong> Daffodil School, Dhaka, Bangladesh
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-phone"></i>
                            <div>
                                <strong>Phone:</strong> (123) 456-7890
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <strong>Email:</strong> daffodil.info@gmail.com
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-globe"></i>
                            <div>
                                <strong>Website:</strong> www.daffodilschool.com
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card info-card">
                        <h5><i class="fas fa-chart-line mr-2"></i> Quick Stats</h5>
                        <div class="info-item">
                            <i class="fas fa-users"></i>
                            <div>
                                <strong>Staff Members:</strong> 71
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-book-open"></i>
                            <div>
                                <strong>Courses Offered:</strong> 25
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-building"></i>
                            <div>
                                <strong>Classrooms:</strong> 20
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-bus"></i>
                            <div>
                                <strong>Transport Routes:</strong> 7
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>






        <!-- Profile Section -->
        <div id="profile" class="form-section">
            <div class="card form-container">
                <div class="card-header">
                    <h5><i class="fas fa-user mr-2"></i> Admin Profile</h5>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($success); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form id="profileForm" enctype="multipart/form-data" method="POST">
                        <div class="text-center mb-4">
                            <img id="profileImagePreview" src="<?php echo $admin_data['profile_image'] ? htmlspecialchars($admin_data['profile_image']) : 'https://ui-avatars.com/api/?name=' . urlencode($admin_data['name'] ?? 'Admin User') . '&background=0d6efd&color=fff'; ?>" class="form-img-preview" alt="Profile">
                            <input type="file" id="profileImage" name="profileImage" accept="image/*" style="display: none;">
                            <button type="button" class="btn btn-sm btn-primary mt-2" onclick="document.getElementById('profileImage').click()">
                                <i class="fas fa-camera mr-2"></i>Change Photo
                            </button>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="adminId" class="form-label">Admin ID</label>
                                    <input type="text" class="form-control" id="adminId" value="<?php echo htmlspecialchars($admin_data['admin_id'] ?? 'Unknown'); ?>" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="adminName" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="adminName" name="adminName" value="<?php echo htmlspecialchars($admin_data['name'] ?? ''); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="adminEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="adminEmail" name="adminEmail" value="<?php echo htmlspecialchars($admin_data['email'] ?? ''); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="adminPhone" class="form-label">Phone</label>
                                    <input type="tel" class="form-control" id="adminPhone" name="adminPhone" value="<?php echo htmlspecialchars($admin_data['phone'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="adminEmergencyContact" class="form-label">Emergency Contact Number</label>
                                    <input type="tel" class="form-control" id="adminEmergencyContact" name="adminEmergencyContact" value="<?php echo htmlspecialchars($admin_data['emergency_contact'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="adminNationalId" class="form-label">National ID</label>
                                    <input type="text" class="form-control" id="adminNationalId" name="adminNationalId" value="<?php echo htmlspecialchars($admin_data['national_id'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="adminDob" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" id="adminDob" name="adminDob" value="<?php echo htmlspecialchars($admin_data['dob'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="adminGender" class="form-label">Gender</label>
                                    <select class="form-control" id="adminGender" name="adminGender">
                                        <option value="">Select Gender</option>
                                        <option value="Male" <?php echo ($admin_data['gender'] ?? '') === 'Male' ? 'selected' : ''; ?>>Male</option>
                                        <option value="Female" <?php echo ($admin_data['gender'] ?? '') === 'Female' ? 'selected' : ''; ?>>Female</option>
                                        <option value="Other" <?php echo ($admin_data['gender'] ?? '') === 'Other' ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="adminJoinDate" class="form-label">Join Date</label>
                                    <input type="date" class="form-control" id="adminJoinDate" name="adminJoinDate" value="<?php echo htmlspecialchars($admin_data['join_date'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="adminBloodGroup" class="form-label">Blood Group</label>
                                    <select class="form-control" id="adminBloodGroup" name="adminBloodGroup">
                                        <option value="">Select Blood Group</option>
                                        <option value="A+" <?php echo ($admin_data['blood_group'] ?? '') === 'A+' ? 'selected' : ''; ?>>A+</option>
                                        <option value="A-" <?php echo ($admin_data['blood_group'] ?? '') === 'A-' ? 'selected' : ''; ?>>A-</option>
                                        <option value="B+" <?php echo ($admin_data['blood_group'] ?? '') === 'B+' ? 'selected' : ''; ?>>B+</option>
                                        <option value="B-" <?php echo ($admin_data['blood_group'] ?? '') === 'B-' ? 'selected' : ''; ?>>B-</option>
                                        <option value="AB+" <?php echo ($admin_data['blood_group'] ?? '') === 'AB+' ? 'selected' : ''; ?>>AB+</option>
                                        <option value="AB-" <?php echo ($admin_data['blood_group'] ?? '') === 'AB-' ? 'selected' : ''; ?>>AB-</option>
                                        <option value="O+" <?php echo ($admin_data['blood_group'] ?? '') === 'O+' ? 'selected' : ''; ?>>O+</option>
                                        <option value="O-" <?php echo ($admin_data['blood_group'] ?? '') === 'O-' ? 'selected' : ''; ?>>O-</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="adminQualification" class="form-label">Qualification</label>
                                    <input type="text" class="form-control" id="adminQualification" name="adminQualification" value="<?php echo htmlspecialchars($admin_data['qualification'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="adminRole" class="form-label">Role</label>
                                    <input type="text" class="form-control" id="adminRole" value="Super Administrator" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="adminAddress" class="form-label">Address</label>
                            <textarea class="form-control" id="adminAddress" name="adminAddress" rows="3"><?php echo htmlspecialchars($admin_data['address'] ?? ''); ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="adminPassword" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="adminPassword" name="adminPassword" placeholder="Enter new password">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="adminConfirmPassword" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="adminConfirmPassword" name="adminConfirmPassword" placeholder="Confirm new password">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save mr-2"></i>Update Profile
                        </button>
                    </form>
                </div>
            </div>
        </div>












        <!-- Students Section -->
        <div id="students" class="form-section">
            <div class="card" id="student-list">
                <div class="card-header">
                    <h5><i class="fas fa-user-graduate mr-2"></i> Students</h5>
                    <button class="btn btn-primary btn-sm" onclick="window.showAddForm('add-student')">
                        <i class="fas fa-plus-circle mr-2"></i>Add Student
                    </button>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Class</th>
                                <th>Roll</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="studentList">
                            <!-- Student list will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="student-details" class="card form-container mt-4" style="display: none;">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle mr-2"></i> Student Details</h5>
                    <button class="btn btn-sm btn-secondary" onclick="window.backToList('students')">
                        <i class="fas fa-arrow-left mr-2"></i>Back to List
                    </button>
                </div>
                <div class="card-body" id="studentDetailsContent">
                    <!-- Student details will be populated by JavaScript -->
                </div>
            </div>
            <div id="add-student" class="card form-container mt-4" style="display: none;">
                <div class="card-header">
                    <h5><i class="fas fa-user-graduate mr-2"></i> Add New Student</h5>
                    <button class="btn btn-sm btn-secondary" onclick="window.backToList('students')">
                        <i class="fas fa-arrow-left mr-2"></i>Back to List
                    </button>
                </div>
                <div class="card-body">
                    <form id="addStudentForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="studentFullName" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="studentFullName" placeholder="Enter full name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="studentId" class="form-label">Student ID</label>
                                    <input type="text" class="form-control" id="studentId" placeholder="Enter student ID" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="studentEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="studentEmail" placeholder="Enter email" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="studentPassword" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="studentPassword" placeholder="Enter password" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <label for="studentClass" class="form-label">Class</label>
                            <select class="form-control" id="studentClass" required>
                                <option value="">Select Class</option>
                                <option>6</option>
                                <option>7</option>
                                <option>8</option>
                                <option>9</option>
                                <option>10</option>
                            </select>
                        </div>
                        <div class="form-group mb-4">
                            <label for="studentRoll" class="form-label">Roll Number</label>
                            <input type="text" class="form-control" id="studentRoll" placeholder="Enter roll number" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-plus-circle mr-2"></i>Add Student
                        </button>
                    </form>
                </div>
            </div>
            <div id="update-student" class="card form-container mt-4" style="display: none;">
                <div class="card-header">
                    <h5><i class="fas fa-user-graduate mr-2"></i> Update Student</h5>
                    <button class="btn btn-sm btn-secondary" onclick="window.backToList('students')">
                        <i class="fas fa-arrow-left mr-2"></i>Back to List
                    </button>
                </div>
                <div class="card-body">
                    <form id="updateStudentForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="updateStudentFullName" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="updateStudentFullName" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="updateStudentId" class="form-label">Student ID</label>
                                    <input type="text" class="form-control" id="updateStudentId" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="updateStudentEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="updateStudentEmail" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="updateStudentPassword" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="updateStudentPassword" placeholder="Enter new password">
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <label for="updateStudentClass" class="form-label">Class</label>
                            <select class="form-control" id="updateStudentClass" required>
                                <option value="">Select Class</option>
                                <option>Class 6</option>
                                <option>Class 7</option>
                                <option>Class 8</option>
                                <option>Class 9</option>
                                <option>Class 10</option>
                            </select>
                        </div>
                        <div class="form-group mb-4">
                            <label for="updateStudentRoll" class="form-label">Roll Number</label>
                            <input type="text" class="form-control" id="updateStudentRoll" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save mr-2"></i>Update Student
                        </button>
                    </form>
                </div>
            </div>
        </div>


        

        <!-- Teachers Section -->
        <div id="teachers" class="form-section">
            <div class="card" id="teacher-list">
                <div class="card-header">
                    <h5><i class="fas fa-chalkboard-teacher mr-2"></i> Teachers</h5>
                    <button class="btn btn-primary btn-sm" onclick="window.showAddForm('add-teacher')">
                        <i class="fas fa-plus-circle mr-2"></i>Add Teacher
                    </button>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Subject</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="teacherList">
                            <!-- Teacher list will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="teacher-details" class="card form-container mt-4" style="display: none;">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle mr-2"></i> Teacher Details</h5>
                    <button class="btn btn-sm btn-secondary" onclick="window.backToList('teachers')">
                        <i class="fas fa-arrow-left mr-2"></i>Back to List
                    </button>
                </div>
                <div class="card-body" id="teacherDetailsContent">
                    <!-- Teacher details will be populated by JavaScript -->
                </div>
            </div>
            <div id="add-teacher" class="card form-container mt-4" style="display: none;">
                <div class="card-header">
                    <h5><i class="fas fa-chalkboard-teacher mr-2"></i> Add New Teacher</h5>
                    <button class="btn btn-sm btn-secondary" onclick="window.backToList('teachers')">
                        <i class="fas fa-arrow-left mr-2"></i>Back to List
                    </button>
                </div>
                <div class="card-body">
                    <form id="addTeacherForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="teacherFullName" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="teacherFullName" placeholder="Enter full name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="teacherId" class="form-label">Teacher ID</label>
                                    <input type="text" class="form-control" id="teacherId" placeholder="Enter teacher ID" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="teacherEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="teacherEmail" placeholder="Enter email" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="teacherPassword" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="teacherPassword" placeholder="Enter password" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <label for="teacherSubject" class="form-label">Primary Subject</label>
                            <input type="text" class="form-control" id="teacherSubject" placeholder="Enter primary subject" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-plus-circle mr-2"></i>Add Teacher
                        </button>
                    </form>
                </div>
            </div>
            <div id="update-teacher" class="card form-container mt-4" style="display: none;">
                <div class="card-header">
                    <h5><i class="fas fa-chalkboard-teacher mr-2"></i> Update Teacher</h5>
                    <button class="btn btn-sm btn-secondary" onclick="window.backToList('teachers')">
                        <i class="fas fa-arrow-left mr-2"></i>Back to List
                    </button>
                </div>
                <div class="card-body">
                    <form id="updateTeacherForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="updateTeacherFullName" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="updateTeacherFullName" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="updateTeacherId" class="form-label">Teacher ID</label>
                                    <input type="text" class="form-control" id="updateTeacherId" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="updateTeacherEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="updateTeacherEmail" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="updateTeacherPassword" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="updateTeacherPassword" placeholder="Enter new password">
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <label for="updateTeacherSubject" class="form-label">Primary Subject</label>
                            <input type="text" class="form-control" id="updateTeacherSubject" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save mr-2"></i>Update Teacher
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Parents Section -->
        <div id="parents" class="form-section">
            <div class="card" id="parent-list">
                <div class="card-header">
                    <h5><i class="fas fa-user-friends mr-2"></i> Parents</h5>
                    <button class="btn btn-primary btn-sm" onclick="window.showAddForm('add-parent')">
                        <i class="fas fa-plus-circle mr-2"></i>Add Parent
                    </button>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Linked Student</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="parentList">
                            <!-- Parent list will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="parent-details" class="card form-container mt-4" style="display: none;">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle mr-2"></i> Parent Details</h5>
                    <button class="btn btn-sm btn-secondary" onclick="window.backToList('parents')">
                        <i class="fas fa-arrow-left mr-2"></i>Back to List
                    </button>
                </div>
                <div class="card-body" id="parentDetailsContent">
                    <!-- Parent details will be populated by JavaScript -->
                </div>
            </div>
            <div id="add-parent" class="card form-container mt-4" style="display: none;">
                <div class="card-header">
                    <h5><i class="fas fa-user-friends mr-2"></i> Add New Parent</h5>
                    <button class="btn btn-sm btn-secondary" onclick="window.backToList('parents')">
                        <i class="fas fa-arrow-left mr-2"></i>Back to List
                    </button>
                </div>
                <div class="card-body">
                    <form id="addParentForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="parentFullName" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="parentFullName" placeholder="Enter full name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="parentId" class="form-label">Parent ID</label>
                                    <input type="text" class="form-control" id="parentId" placeholder="Enter parent ID" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="parentEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="parentEmail" placeholder="Enter email" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="parentPassword" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="parentPassword" placeholder="Enter password" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <label for="parentStudentId" class="form-label">Student ID</label>
                            <input type="text" class="form-control" id="parentStudentId" placeholder="Enter student ID to link with" required>
                            <small class="text-muted">Enter the ID of the student this parent is associated with</small>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-plus-circle mr-2"></i>Add Parent
                        </button>
                    </form>
                </div>
            </div>
            <div id="update-parent" class="card form-container mt-4" style="display: none;">
                <div class="card-header">
                    <h5><i class="fas fa-user-friends mr-2"></i> Update Parent</h5>
                    <button class="btn btn-sm btn-secondary" onclick="window.backToList('parents')">
                        <i class="fas fa-arrow-left mr-2"></i>Back to List
                    </button>
                </div>
                <div class="card-body">
                    <form id="updateParentForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="updateParentFullName" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="updateParentFullName" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="updateParentId" class="form-label">Parent ID</label>
                                    <input type="text" class="form-control" id="updateParentId" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="updateParentEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="updateParentEmail" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="updateParentPassword" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="updateParentPassword" placeholder="Enter new password">
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <label for="updateParentStudentId" class="form-label">Student ID</label>
                            <input type="text" class="form-control" id="updateParentStudentId" required>
                            <small class="text-muted">Enter the ID of the student this parent is associated with</small>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save mr-2"></i>Update Parent
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="js/admin_dashboard.js"></script>
</body>
</html>