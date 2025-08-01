const lineCtx = document.getElementById("lineChart").getContext("2d");
new Chart(lineCtx, {
    type: "line",
    data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
        datasets: [{
            label: "Academic Performance",
            data: [72, 75, 78, 82, 80, 85, 88],
            borderColor: "#3498db",
            backgroundColor: "rgba(52, 152, 219, 0.1)",
            fill: true,
            tension: 0.3,
            borderWidth: 2
        }],
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
            tooltip: {
                mode: 'index',
                intersect: false,
            }
        },
        scales: {
            y: {
                beginAtZero: false,
                min: 60,
                max: 100
            }
        }
    }
});

const pieCtx = document.getElementById("classPieChart").getContext("2d");
new Chart(pieCtx, {
    type: "doughnut",
    data: {
        labels: ["Class 6", "Class 7", "Class 8", "Class 9", "Class 10"],
        datasets: [{
            data: [150, 140, 155, 160, 130],
            backgroundColor: ["#3498db", "#2ecc71", "#f1c40f", "#e74c3c", "#9b59b6"],
            borderWidth: 0
        }],
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    font: {
                        size: 12
                    }
                }
            }
        },
        cutout: '60%'
    }
});


document.addEventListener("DOMContentLoaded", function () {
    // Calendar
    const calendarEl = document.getElementById("calendar");
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: "dayGridMonth",
        height: '210px',
        headerToolbar: false,
        events: [
            { title: "Parent-Teacher Meeting", date: "2025-08-10", color: '#3498db' },
            { title: "Mid-Term Exams Start", date: "2025-08-15", color: '#e74c3c' },
            { title: "Science Fair", date: "2025-08-20", color: '#2ecc71' },
            { title: "Sports Day", date: "2025-08-25", color: '#f1c40f' }
        ]
    });
    calendar.render();

    // Navigation
    document.querySelectorAll('.sidebar-menu a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const section = this.getAttribute('data-section');
            if (section) {
                document.querySelectorAll('.form-section').forEach(sec => {
                    sec.classList.remove('active');
                });
                document.getElementById(section).classList.add('active');
                
                document.querySelectorAll('.sidebar-menu a').forEach(a => {
                    a.classList.remove('active');
                });
                this.classList.add('active');
                
                // Update calendar size when showing dashboard
                if (section === 'dashboard') {
                    setTimeout(() => {
                        calendar.updateSize();
                    }, 100);
                }
            }
        });
    });
});
    
    // Profile image upload preview
    document.getElementById('profileImage').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('profileImagePreview').src = event.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
    

    // Form submissions
    document.getElementById('profileForm').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Profile updated successfully!');
    });
    
    document.getElementById('addStudentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Student added successfully!');
        this.reset();
    });
    
    document.getElementById('addTeacherForm').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Teacher added successfully!');
        this.reset();
    });
    
    document.getElementById('addParentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Parent added successfully!');
        this.reset();
    });

    // Logout Function
    function logout() {
        if (confirm('Are you sure you want to logout?')) {
            sessionStorage.removeItem('user_id'); // Mimic clearing session
            sessionStorage.removeItem('role');    // Mimic clearing role
            window.location.href = 'admin_login.php'; // Redirect to admin login page
        }
    }