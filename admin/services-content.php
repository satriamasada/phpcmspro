<?php
// admin/services-content.php
require_once __DIR__ . '/../includes/auth.php';
if (!isset($_SESSION['user_id'])) exit;
?>

<div class="grid-3" style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: start;">
    <!-- Table Area -->
    <div class="admin-card">
        <h2 style="margin-bottom: 2rem;">Current Services</h2>
        <table id="servicesTable" class="data-table datatable-server">
            <thead>
                <tr>
                    <th>Icon</th>
                    <th>Service Title</th>
                    <th>Short Bio</th>
                    <th width="100">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- Form Area -->
    <div class="admin-card" id="servFormArea">
        <h2 id="formTitle" style="margin-bottom: 1.5rem;">Add New Service</h2>
        <form id="serviceForm">
            <input type="hidden" name="add_service" id="formAction" value="1">
            <input type="hidden" name="id" id="service_id" value="">

            <div class="form-group">
                <label>Service Title</label>
                <input type="text" id="title" name="title" required placeholder="e.g. AI Integration">
            </div>
            
            <div class="form-group">
                <label>Icon Class (FontAwesome)</label>
                <div style="display:flex; gap:10px; align-items:center;">
                    <i id="iconPreview" class="fas fa-question-circle" style="font-size:1.5rem; color:var(--primary);"></i>
                    <input type="text" id="icon" name="icon" required placeholder="fas fa-robot" style="flex:1;">
                </div>
                <small style="color:var(--text-muted); font-size:10px;">Visit fontawesome.com for icon classes.</small>
            </div>

            <div class="form-group">
                <label>Full Description</label>
                <textarea id="description" name="description" rows="5" required placeholder="Describe what you offer..."></textarea>
            </div>
            
            <div style="display:flex; gap:10px; margin-top:2rem;">
                <button type="submit" class="btn-primary" style="flex:1;">Save Service</button>
                <button type="button" id="resetBtn" style="background:#64748b; color:white; border:none; padding:8px 15px; border-radius:5px; font-size:12px; cursor:pointer; display:none;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    if (typeof servicesTable !== 'undefined') {
        servicesTable.destroy();
    }

    var servicesTable = $('#servicesTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": { "url": "ajax/services-data.php", "type": "GET" },
        "columns": [
            { "data": "icon" },
            { "data": "title" },
            { "data": "desc_short" },
            { "data": "actions", "orderable": false }
        ]
    });

    $('#icon').on('input', function() {
        $('#iconPreview').attr('class', $(this).val() || 'fas fa-question-circle');
    });

    $('#serviceForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'services.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                Swal.fire('Success!', 'Service has been saved.', 'success');
                servicesTable.ajax.reload();
                resetForm();
            },
            error: function() {
                Swal.fire('Error', 'Communication error with server.', 'error');
            }
        });
    });

    function resetForm() {
        $('#formTitle').text('Add New Service');
        $('#formAction').attr('name', 'add_service').val('1');
        $('#service_id').val('');
        $('#serviceForm')[0].reset();
        $('#iconPreview').attr('class', 'fas fa-question-circle');
        $('#resetBtn').hide();
    }

    $('#resetBtn').on('click', resetForm);

    function editService(id) {
        $.getJSON('services.php?fetch_id=' + id, function(data) {
            $('#formTitle').text('Update Service');
            $('#formAction').attr('name', 'edit_service').val('1');
            $('#service_id').val(data.id);
            $('#title').val(data.title);
            $('#icon').val(data.icon);
            $('#iconPreview').attr('class', data.icon);
            $('#description').val(data.description);
            
            $('#resetBtn').show();
            $('html, body').animate({ scrollTop: $('#servFormArea').offset().top - 50 }, 500);
        });
    }

    function deleteService(id) {
        confirmDelete('services.php?delete=' + id, function() {
            servicesTable.ajax.reload();
        });
    }
</script>
