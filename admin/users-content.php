<?php
// admin/users-content.php
require_once __DIR__ . '/../includes/auth.php';
authorize('Super Admin');
?>

<div class="grid-3" style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: start;">
    <!-- Table Area -->
    <div class="admin-card">
        <h2 style="margin-bottom: 2rem;">Authorized Users</h2>
        <table id="usersTable" class="data-table datatable-server">
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th width="100">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- Form Area -->
    <div class="admin-card" id="userFormArea">
        <h2 id="formTitle" style="margin-bottom: 1.5rem;">Create New User</h2>
        <form id="userForm">
            <input type="hidden" name="add_user" id="formAction" value="1">
            <input type="hidden" name="id" id="user_id" value="">

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" id="full_name" name="full_name" required placeholder="John Doe">
            </div>
            
            <div class="form-group">
                <label>Username</label>
                <input type="text" id="username" name="username" required placeholder="superadmin">
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" id="email" name="email" required placeholder="admin@softco.tech">
            </div>

            <div class="form-group">
                <label>User Role</label>
                <select id="role_id" name="role_id" required style="width:100%; padding:12px; border:1px solid var(--border); border-radius:8px;">
                    <option value="">-- Assign Role --</option>
                    <?php 
                        global $pdo;
                        $role_list = $pdo->query("SELECT * FROM roles")->fetchAll();
                        foreach ($role_list as $role): 
                    ?>
                        <option value="<?= $role['id'] ?>"><?= htmlspecialchars($role['role_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" id="password" name="password" placeholder="Min. 8 characters" style="font-family: 'Inter', sans-serif;">
                <p id="pwHint" style="font-size:10px; color:var(--text-muted); margin-top:5px; display:none;">* Leave blank to keep current password.</p>
            </div>
            
            <div style="display:flex; gap:10px; margin-top:2rem;">
                <button type="submit" class="btn-primary" style="flex:1;">Save Account</button>
                <button type="button" id="resetBtn" style="background:#64748b; color:white; border:none; padding:8px 15px; border-radius:5px; font-size:12px; cursor:pointer; display:none;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    if (typeof usersTable !== 'undefined') {
        usersTable.destroy();
    }

    var usersTable = $('#usersTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": { "url": "ajax/users-data.php", "type": "GET" },
        "columns": [
            { "data": "full_name" },
            { "data": "username" },
            { "data": "email" },
            { "data": "role" },
            { "data": "actions", "orderable": false }
        ]
    });

    $('#userForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'users.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                if (res.status === 'success') {
                    Swal.fire({ title: 'Success!', text: 'User has been saved.', icon: 'success' });
                    usersTable.ajax.reload();
                    resetForm();
                } else {
                    Swal.fire('Error', res.message || 'Operation failed', 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Communication error with server.', 'error');
            }
        });
    });

    function resetForm() {
        $('#formTitle').text('Create New User');
        $('#formAction').attr('name', 'add_user').val('1');
        $('#user_id').val('');
        $('#userForm')[0].reset();
        $('#password').attr('placeholder', 'Min. 8 characters');
        $('#pwHint').hide();
        $('#resetBtn').hide();
    }

    $('#resetBtn').on('click', resetForm);

    function editUser(id) {
        $.getJSON('users.php?fetch_id=' + id, function(data) {
            $('#formTitle').text('Modify User');
            $('#formAction').attr('name', 'edit_user').val('1');
            $('#user_id').val(data.id);
            $('#full_name').val(data.full_name);
            $('#username').val(data.username);
            $('#email').val(data.email);
            $('#role_id').val(data.role_id);
            
            $('#password').attr('placeholder', 'Change Password (optional)');
            $('#pwHint').show();
            $('#resetBtn').show();
            $('html, body').animate({ scrollTop: $('#userFormArea').offset().top - 50 }, 500);
        });
    }

    function deleteUser(id) {
        confirmDelete('users.php?delete=' + id, function() {
            usersTable.ajax.reload();
        });
    }
</script>
