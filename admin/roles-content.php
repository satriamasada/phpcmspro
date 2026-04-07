<?php
// admin/roles-content.php
require_once __DIR__ . '/../includes/auth.php';
authorize('Super Admin');

$modules = [
    'sections' => 'Landing Page Sections',
    'services' => 'Service Management',
    'portfolio' => 'Portfolio Projects',
    'products' => 'Product Catalog',
    'news' => 'News & Blog Articles',
    'orders' => 'Product Orders',
    'inquiries' => 'Project Proposals',
    'leads' => 'Contact Leads',
    'users' => 'User Management',
    'roles' => 'Roles & Permissions'
];
?>

<div class="grid-3" style="display: grid; grid-template-columns: 2fr 1.2fr; gap: 2rem; align-items: start;">
    <!-- Table Area -->
    <div class="admin-card">
        <h2 style="margin-bottom: 2rem;">Access Level Groups</h2>
        <table id="rolesTable" class="data-table datatable-server">
            <thead>
                <tr>
                    <th>Role Name</th>
                    <th>Permissions</th>
                    <th width="100">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- Form Area -->
    <div class="admin-card" id="roleFormArea">
        <h2 id="formTitle" style="margin-bottom: 1.5rem;">Define New Role</h2>
        <form id="roleForm">
            <input type="hidden" name="add_role" id="formAction" value="1">
            <input type="hidden" name="id" id="role_id" value="">

            <div class="form-group" style="margin-bottom:2rem;">
                <label>Role Identity Name</label>
                <input type="text" id="role_name" name="role_name" required placeholder="Ex: Project Manager">
            </div>

            <div class="form-group">
                <label style="display:flex; justify-content:space-between; align-items:center;">
                    Module Permissions
                    <span style="font-size:10px; color:var(--primary); cursor:pointer;" onclick="$('.perm-check').prop('checked', true)">Select All</span>
                </label>
                <div style="background:#f8fafc; padding:1.5rem; border-radius:12px; border:1px solid var(--border); margin-top:1rem; max-height:400px; overflow-y:auto;">
                    <?php foreach ($modules as $key => $label): ?>
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:0.75rem;">
                        <input type="checkbox" name="perms[<?= $key ?>]" value="true" class="perm-check" id="p_<?= $key ?>" style="width:auto;">
                        <label for="p_<?= $key ?>" style="margin:0; font-weight:600; font-size:12px;"><?= $label ?></label>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div style="display:flex; gap:10px; margin-top:2rem;">
                <button type="submit" class="btn-primary" style="flex:1;">Save Access Group</button>
                <button type="button" id="resetBtn" style="background:#64748b; color:white; border:none; padding:8px 15px; border-radius:5px; font-size:12px; cursor:pointer; display:none;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    if (typeof rolesTable !== 'undefined') {
        rolesTable.destroy();
    }

    var rolesTable = $('#rolesTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": { "url": "ajax/roles-data.php", "type": "GET" },
        "columns": [
            { "data": "role_name" },
            { "data": "perms" },
            { "data": "actions", "orderable": false }
        ]
    });

    $('#roleForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'roles.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                if (res.status === 'success') {
                    Swal.fire({ title: 'Success!', text: 'Role permissions updated.', icon: 'success' });
                    rolesTable.ajax.reload();
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
        $('#formTitle').text('Define New Role');
        $('#formAction').attr('name', 'add_role').val('1');
        $('#role_id').val('');
        $('#roleForm')[0].reset();
        $('.perm-check').prop('checked', false);
        $('#resetBtn').hide();
    }

    $('#resetBtn').on('click', resetForm);

    function editRole(id) {
        $.getJSON('roles.php?fetch_id=' + id, function(data) {
            $('#formTitle').text('Modify Role');
            $('#formAction').attr('name', 'edit_role').val('1');
            $('#role_id').val(data.id);
            $('#role_name').val(data.role_name);
            
            // Set Checkboxes
            $('.perm-check').prop('checked', false);
            if (data.permissions) {
                $.each(data.permissions, function(key, val) {
                    $('#p_' + key).prop('checked', val == true || val == "true");
                });
            }
            
            $('#resetBtn').show();
            $('html, body').animate({ scrollTop: $('#roleFormArea').offset().top - 50 }, 500);
        });
    }

    function deleteRole(id) {
        confirmDelete('roles.php?delete=' + id, function() {
            rolesTable.ajax.reload();
        });
    }
</script>
