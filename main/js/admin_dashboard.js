// Sidebar navigation
document.querySelectorAll('.sidebar-menu a').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const section = this.getAttribute('data-section');
        if (section) {
            document.querySelectorAll('.form-section').forEach(s => s.classList.remove('active'));
            document.getElementById(section).classList.add('active');
            document.querySelectorAll('.sidebar-menu a').forEach(l => l.classList.remove('active'));
            this.classList.add('active');
            
            // Load respective data when section is clicked
            if (section === 'students') loadStudents();
            if (section === 'teachers') loadTeachers();
            if (section === 'parents') loadParents();
        }
    });
});

// Logout function
function logout() {
    if (confirm('Are you sure you want to logout?')) {
        window.location.href = 'admin_login.php';
    }
}

// Show add form
window.showAddForm = function(formId) {
    document.querySelectorAll('.form-section .card').forEach(card => card.style.display = 'none');
    document.getElementById(formId).style.display = 'block';
};

// Back to list
window.backToList = function(section) {
    document.querySelectorAll(`#${section} .card`).forEach(card => card.style.display = 'none');
    document.getElementById(`${section}-list`).style.display = 'block';
};

// Fetch and display students
function loadStudents() {
    fetch('api/get_students.php')
        .then(response => response.json())
        .then(data => {
            const studentList = document.getElementById('studentList');
            studentList.innerHTML = '';
            data.forEach(student => {
                studentList.innerHTML += `
                    <tr>
                        <td>${student.id}</td>
                        <td>${student.name}</td>
                        <td>${student.class}</td>
                        <td>${student.roll}</td>
                        <td>
                            <button class="btn btn-sm btn-info" onclick="showStudentDetails('${student.id}')">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-warning" onclick="showUpdateStudentForm('${student.id}')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteStudent('${student.id}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
        })
        .catch(error => console.error('Error loading students:', error));
}

// Show student details
function showStudentDetails(id) {
    fetch(`api/get_student_details.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('studentDetailsContent').innerHTML = `
                <div class="mb-3"><strong>ID:</strong> ${data.id}</div>
                <div class="mb-3"><strong>Name:</strong> ${data.name}</div>
                <div class="mb-3"><strong>Class:</strong> ${data.class}</div>
                <div class="mb-3"><strong>Roll:</strong> ${data.roll}</div>
                <div class="mb-3"><strong>Email:</strong> ${data.email}</div>
            `;
            document.querySelectorAll('#students .card').forEach(card => card.style.display = 'none');
            document.getElementById('student-details').style.display = 'block';
        })
        .catch(error => console.error('Error loading student details:', error));
}

// Show update student form
function showUpdateStudentForm(id) {
    fetch(`api/get_student_details.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('updateStudentId').value = data.id;
            document.getElementById('updateStudentFullName').value = data.name;
            document.getElementById('updateStudentEmail').value = data.email;
            document.getElementById('updateStudentClass').value = data.class;
            document.getElementById('updateStudentRoll').value = data.roll;
            document.querySelectorAll('#students .card').forEach(card => card.style.display = 'none');
            document.getElementById('update-student').style.display = 'block';
        })
        .catch(error => console.error('Error loading student details:', error));
}

// Delete student
function deleteStudent(id) {
    if (confirm('Are you sure you want to delete this student?')) {
        fetch('api/delete_student.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Student deleted successfully!');
                loadStudents();
            } else {
                alert('Error deleting student: ' + data.error);
            }
        })
        .catch(error => console.error('Error deleting student:', error));
    }
}

// Fetch and display teachers
function loadTeachers() {
    fetch('api/get_teachers.php')
        .then(response => response.json())
        .then(data => {
            const teacherList = document.getElementById('teacherList');
            teacherList.innerHTML = '';
            data.forEach(teacher => {
                teacherList.innerHTML += `
                    <tr>
                        <td>${teacher.id}</td>
                        <td>${teacher.name}</td>
                        <td>${teacher.subject}</td>
                        <td>
                            <button class="btn btn-sm btn-info" onclick="showTeacherDetails('${teacher.id}')">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-warning" onclick="showUpdateTeacherForm('${teacher.id}')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteTeacher('${teacher.id}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
        })
        .catch(error => console.error('Error loading teachers:', error));
}

// Show teacher details
function showTeacherDetails(id) {
    fetch(`api/get_teacher_details.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('teacherDetailsContent').innerHTML = `
                <div class="mb-3"><strong>ID:</strong> ${data.id}</div>
                <div class="mb-3"><strong>Name:</strong> ${data.name}</div>
                <div class="mb-3"><strong>Subject:</strong> ${data.subject}</div>
                <div class="mb-3"><strong>Email:</strong> ${data.email}</div>
            `;
            document.querySelectorAll('#teachers .card').forEach(card => card.style.display = 'none');
            document.getElementById('teacher-details').style.display = 'block';
        })
        .catch(error => console.error('Error loading teacher details:', error));
}

// Show update teacher form
function showUpdateTeacherForm(id) {
    fetch(`api/get_teacher_details.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('updateTeacherId').value = data.id;
            document.getElementById('updateTeacherFullName').value = data.name;
            document.getElementById('updateTeacherEmail').value = data.email;
            document.getElementById('updateTeacherSubject').value = data.subject;
            document.querySelectorAll('#teachers .card').forEach(card => card.style.display = 'none');
            document.getElementById('update-teacher').style.display = 'block';
        })
        .catch(error => console.error('Error loading teacher details:', error));
}

// Delete teacher
function deleteTeacher(id) {
    if (confirm('Are you sure you want to delete this teacher?')) {
        fetch('api/delete_teacher.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Teacher deleted successfully!');
                loadTeachers();
            } else {
                alert('Error deleting teacher: ' + data.error);
            }
        })
        .catch(error => console.error('Error deleting teacher:', error));
    }
}

// Fetch and display parents
function loadParents() {
    fetch('api/get_parents.php')
        .then(response => response.json())
        .then(data => {
            const parentList = document.getElementById('parentList');
            parentList.innerHTML = '';
            data.forEach(parent => {
                parentList.innerHTML += `
                    <tr>
                        <td>${parent.id}</td>
                        <td>${parent.name}</td>
                        <td>${parent.student_id}</td>
                        <td>
                            <button class="btn btn-sm btn-info" onclick="showParentDetails('${parent.id}')">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-warning" onclick="showUpdateParentForm('${parent.id}')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteParent('${parent.id}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
        })
        .catch(error => console.error('Error loading parents:', error));
}

// Show parent details
function showParentDetails(id) {
    fetch(`api/get_parent_details.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('parentDetailsContent').innerHTML = `
                <div class="mb-3"><strong>ID:</strong> ${data.id}</div>
                <div class="mb-3"><strong>Name:</strong> ${data.name}</div>
                <div class="mb-3"><strong>Linked Student ID:</strong> ${data.student_id}</div>
                <div class="mb-3"><strong>Email:</strong> ${data.email}</div>
            `;
            document.querySelectorAll('#parents .card').forEach(card => card.style.display = 'none');
            document.getElementById('parent-details').style.display = 'block';
        })
        .catch(error => console.error('Error loading parent details:', error));
}

// Show update parent form
function showUpdateParentForm(id) {
    fetch(`api/get_parent_details.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('updateParentId').value = data.id;
            document.getElementById('updateParentFullName').value = data.name;
            document.getElementById('updateParentEmail').value = data.email;
            document.getElementById('updateParentStudentId').value = data.student_id;
            document.querySelectorAll('#parents .card').forEach(card => card.style.display = 'none');
            document.getElementById('update-parent').style.display = 'block';
        })
        .catch(error => console.error('Error loading parent details:', error));
}

// Delete parent
function deleteParent(id) {
    if (confirm('Are you sure you want to delete this parent?')) {
        fetch('api/delete_parent.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Parent deleted successfully!');
                loadParents();
            } else {
                alert('Error deleting parent: ' + data.error);
            }
        })
        .catch(error => console.error('Error deleting parent:', error));
    }
}

// Handle add student form submission
document.getElementById('addStudentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const data = {
        role: 'student',
        id: document.getElementById('studentId').value,
        name: document.getElementById('studentFullName').value,
        email: document.getElementById('studentEmail').value,
        password: document.getElementById('studentPassword').value,
        class: document.getElementById('studentClass').value,
        roll: document.getElementById('studentRoll').value
    };

    fetch('api/add_user.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Student added successfully!');
            window.backToList('students');
            loadStudents();
        } else {
            alert('Error adding student: ' + data.error);
        }
    })
    .catch(error => console.error('Error adding student:', error));
});

// Handle update student form submission
document.getElementById('updateStudentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const data = {
        id: document.getElementById('updateStudentId').value,
        name: document.getElementById('updateStudentFullName').value,
        email: document.getElementById('updateStudentEmail').value,
        password: document.getElementById('updateStudentPassword').value,
        class: document.getElementById('updateStudentClass').value,
        roll: document.getElementById('updateStudentRoll').value
    };

    fetch('api/update_student.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Student updated successfully!');
            window.backToList('students');
            loadStudents();
        } else {
            alert('Error updating student: ' + data.error);
        }
    })
    .catch(error => console.error('Error updating student:', error));
});

// Handle add teacher form submission
document.getElementById('addTeacherForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const data = {
        role: 'teacher',
        id: document.getElementById('teacherId').value,
        name: document.getElementById('teacherFullName').value,
        email: document.getElementById('teacherEmail').value,
        password: document.getElementById('teacherPassword').value,
        subject: document.getElementById('teacherSubject').value
    };

    fetch('api/add_user.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Teacher added successfully!');
            window.backToList('teachers');
            loadTeachers();
        } else {
            alert('Error adding teacher: ' + data.error);
        }
    })
    .catch(error => console.error('Error adding teacher:', error));
});

// Handle update teacher form submission
document.getElementById('updateTeacherForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const data = {
        id: document.getElementById('updateTeacherId').value,
        name: document.getElementById('updateTeacherFullName').value,
        email: document.getElementById('updateTeacherEmail').value,
        password: document.getElementById('updateTeacherPassword').value,
        subject: document.getElementById('updateTeacherSubject').value
    };

    fetch('api/update_teacher.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Teacher updated successfully!');
            window.backToList('teachers');
            loadTeachers();
        } else {
            alert('Error updating teacher: ' + data.error);
        }
    })
    .catch(error => console.error('Error updating teacher:', error));
});

// Handle add parent form submission
document.getElementById('addParentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const data = {
        role: 'parent',
        id: document.getElementById('parentId').value,
        name: document.getElementById('parentFullName').value,
        email: document.getElementById('parentEmail').value,
        password: document.getElementById('parentPassword').value,
        student_id: document.getElementById('parentStudentId').value
    };

    fetch('api/add_user.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Parent added successfully!');
            window.backToList('parents');
            loadParents();
        } else {
            alert('Error adding parent: ' + data.error);
        }
    })
    .catch(error => console.error('Error adding parent:', error));
});

// Handle update parent form submission
document.getElementById('updateParentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const data = {
        id: document.getElementById('updateParentId').value,
        name: document.getElementById('updateParentFullName').value,
        email: document.getElementById('updateParentEmail').value,
        password: document.getElementById('updateParentPassword').value,
        student_id: document.getElementById('updateParentStudentId').value
    };

    fetch('api/update_parent.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Parent updated successfully!');
            window.backToList('parents');
            loadParents();
        } else {
            alert('Error updating parent: ' + data.error);
        }
    })
    .catch(error => console.error('Error updating parent:', error));
});

// Initialize charts
const ctxLine = document.getElementById('lineChart').getContext('2d');
const lineChart = new Chart(ctxLine, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'Student Performance',
            data: [65, 59, 80, 81, 56, 55],
            fill: false,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
        }]
    }
});

const ctxPie = document.getElementById('classPieChart').getContext('2d');
const pieChart = new Chart(ctxPie, {
    type: 'pie',
    data: {
        labels: ['Class 6', 'Class 7', 'Class 8', 'Class 9', 'Class 10'],
        datasets: [{
            data: [120, 150, 140, 160, 165],
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']
        }]
    }
});

// Initialize calendar
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: '300px',
        events: [
            { title: 'Parent-Teacher Meeting', start: '2025-08-05' },
            { title: 'Annual Sports Day', start: '2025-08-15' },
            { title: 'Exam Week', start: '2025-08-20', end: '2025-08-25' }
        ]
    });
    calendar.render();
});

// Load initial data
loadStudents();
loadTeachers();
loadParents();