<?php
// admin/portfolio-content.php
require_once __DIR__ . '/../includes/auth.php';
if (!isset($_SESSION['user_id'])) exit;
?>

<div class="grid-3" style="display: grid; grid-template-columns: 2.5fr 1fr; gap: 2rem; align-items: start;">
    <!-- Portfolio Table -->
    <div class="admin-card">
        <h2 style="margin-bottom: 2rem;">Projects & Works</h2>
        <table id="portfolioTable" class="data-table datatable-server">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Project Title</th>
                    <th>Category</th>
                    <th width="100">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- Form Area -->
    <div class="admin-card" id="portFormArea">
        <h2 id="formTitle" style="margin-bottom: 1.5rem;">Add New Project</h2>
        <form id="portfolioForm" enctype="multipart/form-data">
            <input type="hidden" name="add_portfolio" id="formAction" value="1">
            <input type="hidden" name="id" id="project_id" value="">
            <input type="hidden" name="existing_image" id="existing_image" value="">

            <div class="form-group">
                <label>Project Title</label>
                <input type="text" id="title" name="title" required placeholder="e.g. ERP Cloud Pro">
            </div>
            
            <div class="form-group">
                <label>Category</label>
                <select id="category" name="category" required style="width:100%; padding:12px; border:1px solid var(--border); border-radius:8px;">
                    <option value="Web App">Web App</option>
                    <option value="Mobile App">Mobile App</option>
                    <option value="Enterprise Solution">Enterprise Solution</option>
                    <option value="UI/UX Design">UI/UX Design</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Project Thumbnail</label>
                <div id="thumbPreview" style="margin-bottom:10px; display:none;">
                    <img src="" style="width:100%; height:120px; object-fit:cover; border-radius:8px; border:1px solid var(--border);">
                </div>
                <input type="file" name="portfolio_image" accept="image/*" style="font-size:11px;">
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea id="description" name="description" rows="4" required placeholder="Describe the project objective..."></textarea>
            </div>
            
            <div style="display:flex; gap:10px; margin-top:2rem;">
                <button type="submit" class="btn-primary" style="flex:1;">Save Project</button>
                <button type="button" id="resetBtn" style="background:#64748b; color:white; border:none; padding:8px 15px; border-radius:5px; font-size:12px; cursor:pointer; display:none;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    if (typeof portfolioTable !== 'undefined') {
        portfolioTable.destroy();
    }

    var portfolioTable = $('#portfolioTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": { "url": "ajax/portfolio-data.php", "type": "GET" },
        "columns": [
            { "data": "image" },
            { "data": "title" },
            { "data": "category" },
            { "data": "actions", "orderable": false }
        ]
    });

    $('#portfolioForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        
        $.ajax({
            url: 'portfolio.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(res) {
                Swal.fire('Success!', 'Project has been updated.', 'success');
                portfolioTable.ajax.reload();
                resetForm();
            },
            error: function() {
                Swal.fire('Error', 'Communication with server failed.', 'error');
            }
        });
    });

    function resetForm() {
        $('#formTitle').text('Add New Project');
        $('#formAction').attr('name', 'add_portfolio').val('1');
        $('#project_id').val('');
        $('#portfolioForm')[0].reset();
        $('#thumbPreview').hide();
        $('#resetBtn').hide();
    }

    $('#resetBtn').on('click', resetForm);

    function editPortfolio(id) {
        $.getJSON('portfolio.php?fetch_id=' + id, function(data) {
            $('#formTitle').text('Edit Project');
            $('#formAction').attr('name', 'edit_portfolio').val('1');
            $('#project_id').val(data.id);
            $('#title').val(data.title);
            $('#category').val(data.category);
            $('#description').val(data.description);
            $('#existing_image').val(data.image_url);
            
            if(data.image_url) {
                var prefix = data.image_url.startsWith('uploads/') ? '../' : '';
                $('#thumbPreview').show().find('img').attr('src', prefix + data.image_url);
            } else {
                $('#thumbPreview').hide();
            }
            
            $('#resetBtn').show();
            $('html, body').animate({ scrollTop: $('#portFormArea').offset().top - 50 }, 500);
        });
    }

    function deletePortfolio(id) {
        confirmDelete('portfolio.php?delete=' + id, function() {
            portfolioTable.ajax.reload();
        });
    }
</script>
