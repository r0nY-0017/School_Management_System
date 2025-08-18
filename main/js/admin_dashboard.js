// Include SweetAlert2 in your HTML: <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

// Sidebar navigation
document.querySelectorAll('.sidebar-menu a').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const section = this.getAttribute('data-section');
        if (section) {
            document.querySelectorAll('.form-section').forEach(s => s.classList.remove('active'));
            const sectionElement = document.getElementById(section);
            if (sectionElement) {
                sectionElement.classList.add('active');
            } else {
                console.error(`Section not found: ${section}`);
            }
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
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to logout?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, logout!',
        cancelButtonText: 'No, cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'admin_login.php';
        }
    });
}















// Show add form
window.showAddForm = function(formId) {
    document.querySelectorAll('.form-section .card').forEach(card => card.style.display = 'none');
    const formElement = document.getElementById(formId);
    if (formElement) {
        formElement.style.display = 'block';
    } else {
        console.error(`Form not found: ${formId}`);
    }
};

// Back to list
window.backToList = function(section) {
    const validSections = ['students', 'teachers', 'parents'];
    if (!validSections.includes(section)) {
        console.error(`Invalid section: ${section}`);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: `Invalid section: ${section}`
        });
        return;
    }

    // Hide all cards in the section
    const cards = document.querySelectorAll(`#${section} .card`);
    if (cards.length === 0) {
        console.warn(`No cards found in section: ${section}`);
    }
    cards.forEach(card => {
        card.style.display = 'none';
    });

    // Use singular form for list ID
    const listId = section === 'students' ? 'student-list' : 
                   section === 'teachers' ? 'teacher-list' : 
                   'parent-list';
    const listElement = document.getElementById(listId);

    if (listElement) {
        listElement.style.display = 'block';
    } else {
        console.error(`List element not found: ${listId}`);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: `List element not found: ${listId}. Redirecting to main section.`
        });
        // Fallback: Show the main section
        document.querySelectorAll('.form-section').forEach(s => s.classList.remove('active'));
        document.getElementById(section).classList.add('active');
    }
};

// Fetch and display students
function loadStudents() {
    fetch('api/get_students.php')
        .then(response => {
            if (!response.ok) throw new Error('Failed to fetch students');
            return response.json();
        })
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
            console.log('Student list element:', document.getElementById('student-list'));
        })
        .catch(error => {
            console.error('Error loading students:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load students. Please try again.'
            });
        });
}

// Show student details
function showStudentDetails(id) {
    fetch(`api/get_student_details.php?id=${id}`)
        .then(response => {
            if (!response.ok) throw new Error('Failed to fetch student details');
            return response.json();
        })
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
        .catch(error => {
            console.error('Error loading student details:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load student details. Please try again.'
            });
        });
}

// Show update student form
function showUpdateStudentForm(id) {
    fetch(`api/get_student_details.php?id=${id}`)
        .then(response => {
            if (!response.ok) throw new Error('Failed to fetch student details');
            return response.json();
        })
        .then(data => {
            document.getElementById('updateStudentId').value = data.id;
            document.getElementById('updateStudentFullName').value = data.name;
            document.getElementById('updateStudentEmail').value = data.email;
            document.getElementById('updateStudentClass').value = data.class;
            document.getElementById('updateStudentRoll').value = data.roll;
            document.querySelectorAll('#students .card').forEach(card => card.style.display = 'none');
            document.getElementById('update-student').style.display = 'block';
        })
        .catch(error => {
            console.error('Error loading student details:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load student details for editing. Please try again.'
            });
        });
}

// Delete student
function deleteStudent(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to delete this student?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('api/delete_student.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id })
            })
            .then(response => {
                if (!response.ok) throw new Error('Failed to delete student');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Student deleted successfully!',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    loadStudents();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error deleting student: ' + data.error
                    });
                }
            })
            .catch(error => {
                console.error('Error deleting student:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while deleting the student.'
                });
            });
        }
    });
}

// Fetch and display teachers
function loadTeachers() {
    fetch('api/get_teachers.php')
        .then(response => {
            if (!response.ok) throw new Error('Failed to fetch teachers');
            return response.json();
        })
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
            console.log('Teacher list element:', document.getElementById('teacher-list'));
        })
        .catch(error => {
            console.error('Error loading teachers:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load teachers. Please try again.'
            });
        });
}

// Show teacher details
function showTeacherDetails(id) {
    fetch(`api/get_teacher_details.php?id=${id}`)
        .then(response => {
            if (!response.ok) throw new Error('Failed to fetch teacher details');
            return response.json();
        })
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
        .catch(error => {
            console.error('Error loading teacher details:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load teacher details. Please try again.'
            });
        });
}

// Show update teacher form
function showUpdateTeacherForm(id) {
    fetch(`api/get_teacher_details.php?id=${id}`)
        .then(response => {
            if (!response.ok) throw new Error('Failed to fetch teacher details');
            return response.json();
        })
        .then(data => {
            document.getElementById('updateTeacherId').value = data.id;
            document.getElementById('updateTeacherFullName').value = data.name;
            document.getElementById('updateTeacherEmail').value = data.email;
            document.getElementById('updateTeacherSubject').value = data.subject;
            document.querySelectorAll('#teachers .card').forEach(card => card.style.display = 'none');
            document.getElementById('update-teacher').style.display = 'block';
        })
        .catch(error => {
            console.error('Error loading teacher details:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load teacher details for editing. Please try again.'
            });
        });
}

// Delete teacher
function deleteTeacher(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to delete this teacher?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('api/delete_teacher.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id })
            })
            .then(response => {
                if (!response.ok) throw new Error('Failed to delete teacher');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Teacher deleted successfully!',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    loadTeachers();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error deleting teacher: ' + data.error
                    });
                }
            })
            .catch(error => {
                console.error('Error deleting teacher:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while deleting the teacher.'
                });
            });
        }
    });
}

// Fetch and display parents
function loadParents() {
    fetch('api/get_parents.php')
        .then(response => {
            if (!response.ok) throw new Error('Failed to fetch parents');
            return response.json();
        })
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
            console.log('Parent list element:', document.getElementById('parent-list'));
        })
        .catch(error => {
            console.error('Error loading parents:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load parents. Please try again.'
            });
        });
}

// Show parent details
function showParentDetails(id) {
    fetch(`api/get_parent_details.php?id=${id}`)
        .then(response => {
            if (!response.ok) throw new Error('Failed to fetch parent details');
            return response.json();
        })
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
        .catch(error => {
            console.error('Error loading parent details:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load parent details. Please try again.'
            });
        });
}

// Show update parent form
function showUpdateParentForm(id) {
    fetch(`api/get_parent_details.php?id=${id}`)
        .then(response => {
            if (!response.ok) throw new Error('Failed to fetch parent details');
            return response.json();
        })
        .then(data => {
            document.getElementById('updateParentId').value = data.id;
            document.getElementById('updateParentFullName').value = data.name;
            document.getElementById('updateParentEmail').value = data.email;
            document.getElementById('updateParentStudentId').value = data.student_id;
            document.querySelectorAll('#parents .card').forEach(card => card.style.display = 'none');
            document.getElementById('update-parent').style.display = 'block';
        })
        .catch(error => {
            console.error('Error loading parent details:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load parent details for editing. Please try again.'
            });
        });
}

// Delete parent
function deleteParent(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to delete this parent?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('api/delete_parent.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id })
            })
            .then(response => {
                if (!response.ok) throw new Error('Failed to delete parent');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Parent deleted successfully!',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    loadParents();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error deleting parent: ' + data.error
                    });
                }
            })
            .catch(error => {
                console.error('Error deleting parent:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while deleting the parent.'
                });
            });
        }
    });
}

// Handle add student form submission
document.getElementById('addStudentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
    submitBtn.disabled = true;

    const data = {
        role: 'student',
        id: document.getElementById('studentId').value,
        name: document.getElementById('studentFullName').value,
        email: document.getElementById('studentEmail').value,
        password: document.getElementById('studentPassword').value,
        class: document.getElementById('studentClass').value,
        roll: document.getElementById('studentRoll').value
    };

    // Validate email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(data.email)) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Email',
            text: 'Please enter a valid email address.'
        });
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        return;
    }

    fetch('api/add_user.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) throw new Error('Failed to add student');
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Student added successfully!',
                timer: 2000,
                showConfirmButton: false
            });
            window.backToList('students');
            loadStudents();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error adding student: ' + data.error
            });
        }
    })
    .catch(error => {
        console.error('Error adding student:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while adding the student.'
        });
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Handle update student form submission
document.getElementById('updateStudentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
    submitBtn.disabled = true;

    const data = {
        id: document.getElementById('updateStudentId').value,
        name: document.getElementById('updateStudentFullName').value,
        email: document.getElementById('updateStudentEmail').value,
        password: document.getElementById('updateStudentPassword').value,
        class: document.getElementById('updateStudentClass').value,
        roll: document.getElementById('updateStudentRoll').value
    };

    // Validate email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(data.email)) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Email',
            text: 'Please enter a valid email address.'
        });
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        return;
    }

    fetch('api/update_student.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) throw new Error('Failed to update student');
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Student updated successfully!',
                timer: 2000,
                showConfirmButton: false
            });
            window.backToList('students');
            loadStudents();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error updating student: ' + data.error
            });
        }
    })
    .catch(error => {
        console.error('Error updating student:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while updating the student.'
        });
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Handle add teacher form submission
document.getElementById('addTeacherForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
    submitBtn.disabled = true;

    const data = {
        role: 'teacher',
        id: document.getElementById('teacherId').value,
        name: document.getElementById('teacherFullName').value,
        email: document.getElementById('teacherEmail').value,
        password: document.getElementById('teacherPassword').value,
        subject: document.getElementById('teacherSubject').value
    };

    // Validate email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(data.email)) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Email',
            text: 'Please enter a valid email address.'
        });
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        return;
    }

    fetch('api/add_user.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) throw new Error('Failed to add teacher');
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Teacher added successfully!',
                timer: 2000,
                showConfirmButton: false
            });
            window.backToList('teachers');
            loadTeachers();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error adding teacher: ' + data.error
            });
        }
    })
    .catch(error => {
        console.error('Error adding teacher:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while adding the teacher.'
        });
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Handle update teacher form submission
document.getElementById('updateTeacherForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
    submitBtn.disabled = true;

    const data = {
        id: document.getElementById('updateTeacherId').value,
        name: document.getElementById('updateTeacherFullName').value,
        email: document.getElementById('updateTeacherEmail').value,
        password: document.getElementById('updateTeacherPassword').value,
        subject: document.getElementById('updateTeacherSubject').value
    };

    // Validate email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(data.email)) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Email',
            text: 'Please enter a valid email address.'
        });
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        return;
    }

    fetch('api/update_teacher.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) throw new Error('Failed to update teacher');
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Teacher updated successfully!',
                timer: 2000,
                showConfirmButton: false
            });
            window.backToList('teachers');
            loadTeachers();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error updating teacher: ' + data.error
            });
        }
    })
    .catch(error => {
        console.error('Error updating teacher:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while updating the teacher.'
        });
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Handle add parent form submission
document.getElementById('addParentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
    submitBtn.disabled = true;

    const data = {
        role: 'parent',
        id: document.getElementById('parentId').value,
        name: document.getElementById('parentFullName').value,
        email: document.getElementById('parentEmail').value,
        password: document.getElementById('parentPassword').value,
        student_id: document.getElementById('parentStudentId').value
    };

    // Validate email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(data.email)) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Email',
            text: 'Please enter a valid email address.'
        });
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        return;
    }

    fetch('api/add_user.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) throw new Error('Failed to add parent');
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Parent added successfully!',
                timer: 2000,
                showConfirmButton: false
            });
            window.backToList('parents');
            loadParents();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error adding parent: ' + data.error
            });
        }
    })
    .catch(error => {
        console.error('Error adding parent:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while adding the parent.'
        });
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Handle update parent form submission
document.getElementById('updateParentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
    submitBtn.disabled = true;

    const data = {
        id: document.getElementById('updateParentId').value,
        name: document.getElementById('updateParentFullName').value,
        email: document.getElementById('updateParentEmail').value,
        password: document.getElementById('updateParentPassword').value,
        student_id: document.getElementById('updateParentStudentId').value
    };

    // Validate email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(data.email)) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Email',
            text: 'Please enter a valid email address.'
        });
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        return;
    }

    fetch('api/update_parent.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) throw new Error('Failed to update parent');
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Parent updated successfully!',
                timer: 2000,
                showConfirmButton: false
            });
            window.backToList('parents');
            loadParents();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error updating parent: ' + data.error
            });
        }
    })
    .catch(error => {
        console.error('Error updating parent:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while updating the parent.'
        });
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
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