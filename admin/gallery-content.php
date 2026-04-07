<?php
// admin/gallery-content.php
require_once __DIR__ . '/../includes/auth.php';
authorize('Super Admin', 'Admin');
?>

<div class="grid-3" style="display: grid; grid-template-columns: 2fr 1.2fr; gap: 2rem; align-items: start;">
    <!-- Table Area -->
    <div class="admin-card">
        <h2 style="margin-bottom: 2rem;">Showcase Gallery</h2>
        <table id="galleryTable" class="data-table datatable-server">
            <thead>
                <tr>
                    <th>Thumbnail</th>
                    <th>Title & Category</th>
                    <th width="100">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- Form Area -->
    <div class="admin-card" id="galleryFormArea">
        <h2 id="formTitle" style="margin-bottom: 1.5rem;">Add New Highlight</h2>
        <form id="galleryForm" enctype="multipart/form-data">
            <input type="hidden" name="add_gallery" id="formAction" value="1">
            <input type="hidden" name="id" id="item_id" value="">
            <input type="hidden" name="existing_image" id="existing_image" value="">

            <div class="form-group" style="margin-bottom:1.5rem;">
                <label>Highlight Title</label>
                <input type="text" id="title" name="title" required placeholder="Ex: Tech Summit 2024">
            </div>

            <div class="form-group" style="margin-bottom:1.5rem;">
                <label>Category / Event</label>
                <input type="text" id="category" name="category" placeholder="Ex: Corporate Event">
            </div>

            <div class="form-group" style="margin-bottom:1.5rem;">
                <label>Highlight Description</label>
                <textarea id="description" name="description" rows="4" placeholder="Describe the event, tech hub, or office activity..."></textarea>
            </div>

            <div class="form-group">
                <label>Showcase Photo</label>
                <div style="background:#f8fafc; padding:1rem; border-radius:10px; border:1px dashed var(--border); text-align:center;">
                    <img id="imgPreview" src="https://via.placeholder.com/300x200?text=Upload+Photo" style="max-width:100%; height:150px; object-fit:cover; border-radius:8px; margin-bottom:1rem; display:none;">
                    <input type="file" id="imageInput" name="image" accept="image/*" onchange="previewImage(this)">
                </div>
            </div>
            
            <div style="display:flex; gap:10px; margin-top:2rem;">
                <button type="submit" class="btn-primary" style="flex:1;">Save to Gallery</button>
                <button type="button" id="resetBtn" style="background:#64748b; color:white; border:none; padding:8px 15px; border-radius:5px; font-size:12px; cursor:pointer; display:none;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    if (typeof galleryTable !== 'undefined') {
        galleryTable.destroy();
    }

    var galleryTable = $('#galleryTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": { "url": "ajax/gallery-data.php", "type": "GET" },
        "columns": [
            { "data": "thumbnail" },
            { "data": "details" },
            { "data": "actions", "orderable": false }
        ]
    });

    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imgPreview').attr('src', e.target.result).show();
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $('#galleryForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: 'gallery.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(res) {
                Swal.fire('Success!', 'Gallery highlight saved.', 'success');
                galleryTable.ajax.reload();
                resetForm();
            }
        });
    });

    function resetForm() {
        $('#formTitle').text('Add New Highlight');
        $('#formAction').attr('name', 'add_gallery').val('1');
        $('#item_id').val('');
        $('#existing_image').val('');
        $('#galleryForm')[0].reset();
        $('#imgPreview').hide();
        $('#resetBtn').hide();
    }

    $('#resetBtn').on('click', resetForm);

    function editItem(id) {
        $.getJSON('gallery.php?fetch_id=' + id, function(data) {
            $('#formTitle').text('Update Highlight');
            $('#formAction').attr('name', 'edit_gallery').val('1');
            $('#item_id').val(data.id);
            $('#title').val(data.title);
            $('#category').val(data.category);
            $('#description').val(data.description);
            $('#existing_image').val(data.image_path);
            
            if (data.image_path) {
                $('#imgPreview').attr('src', '../' + data.image_path).show();
            }
            
            $('#resetBtn').show();
            $('html, body').animate({ scrollTop: $('#galleryFormArea').offset().top - 50 }, 500);
        });
    }

    function deleteItem(id) {
        confirmDelete('gallery.php?delete=' + id, function() {
            galleryTable.ajax.reload();
        });
    }
</script>
