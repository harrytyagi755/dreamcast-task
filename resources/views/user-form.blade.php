<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Form</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        .error {
            color: red;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">User Form</h2>
        <form id="userForm" enctype="multipart/form-data" novalidate>
            @csrf
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name">
                <small class="error" id="nameError"></small>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email">
                <small class="error" id="emailError"></small>
            </div>

            <div class="form-group">
                <label for="phone">Phone (Indian format):</label>
                <input type="text" class="form-control" id="phone" name="phone">
                <small class="error" id="phoneError"></small>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description"></textarea>
                <small class="error" id="descriptionError"></small>
            </div>

            <div class="form-group">
                <label for="role_id">Role:</label>
                <select class="form-control" id="role_id" name="role_id">
                    <option value="" disabled selected>Select a role</option>
                </select>
                <small class="error" id="roleError"></small>
            </div>

            <div class="form-group">
                <label for="profile_image">Profile Image:</label>
                <input type="file" class="form-control-file" id="profile_image" name="profile_image">
                <small class="error" id="profileImageError"></small>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <h3 class="mt-5">Users</h3>
        <table id="userTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Description</th>
                    <th>Role</th>
                    <th>Profile Image</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <script>
        $(document).ready(function () {
            // Load roles
            $.ajax({
                url: '/api/roles',
                method: 'GET',
                success: function (data) {
                    console.log('Roles data:', data); // Debugging line
                    let roleOptions = '';
                    if (Array.isArray(data)) {
                        $.each(data, function (key, role) {
                            roleOptions += `<option value="${role.id}">${role.name}</option>`;
                        });
                        $('#role_id').html(roleOptions);
                    } else {
                        console.error('Roles data is not an array.');
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    console.error('Error fetching roles:', textStatus, errorThrown);
                }
            });

            // Client-side validation function
            function validateForm() {
                let valid = true;
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                const phoneRegex = /^[6-9]\d{9}$/;

                $('.error').text('');
                if ($('#name').val() === '') {
                    $('#nameError').text('Name is required.');
                    valid = false;
                }

                if (!emailRegex.test($('#email').val())) {
                    $('#emailError').text('Please enter a valid email address.');
                    valid = false;
                }

                if (!phoneRegex.test($('#phone').val())) {
                    $('#phoneError').text('Please enter a valid 10-digit Indian phone number.');
                    valid = false;
                }

                if ($('#role_id').val() === null || $('#role_id').val() === '') {
                    $('#roleError').text('Please select a role.');
                    valid = false;
                }

                return valid;
            }

            // Submit form with validation
            $('#userForm').on('submit', function (e) {
                e.preventDefault();

                if (!validateForm()) {
                    return;
                }

                var formData = new FormData(this);

                $.ajax({
                    url: '/api/users',
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                       // alert('User added successfully!');
                        loadUsers();
                    },
                    error: function (xhr) {
                        alert('Error: ' + JSON.stringify(xhr.responseJSON.errors));
                    }
                });
            });

            // Load users
            function loadUsers() {
                $.ajax({
                    url: '/api/users',
                    method: 'GET',
                    success: function (data) {
                        console.log('Users data:', data);
                        $('#userTable tbody').empty();
                        $.each(data, function (key, user) {
                            $('#userTable tbody').append(`
                                <tr>
                                    <td>${user.name}</td>
                                    <td>${user.email}</td>
                                    <td>${user.phone}</td>
                                    <td>${user.description}</td>
                                    <td>${user.role.name}</td>
                                    <td><img src="/storage/${user.profile_image}" width="50"></td>
                                </tr>
                            `);
                        });
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        console.error('Error fetching users:', textStatus, errorThrown);
                    }
                });
            }

            loadUsers();
        });
    </script>
</body>
</html>
