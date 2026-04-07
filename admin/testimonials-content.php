<?php
// admin/testimonials-content.php
require_once __DIR__ . '/../includes/auth.php';
authorize('Super Admin', 'Admin');
?>

<div class="grid-3" style="display: grid; grid-template-columns: 2fr 1.2fr; gap: 2rem; align-items: start;">
    <!-- Table Area -->
    <div class="admin-card">
        <h2 style="margin-bottom: 2rem;">Client Feedback</h2>
        <table id="testimonialsTable" class="data-table datatable-server">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Testimonial</th>
                    <th>Rating</th>
                    <th width="100">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- Form Area -->
    <div class="admin-card" id="testimonialFormArea">
        <h2 id="formTitle" style="margin-bottom: 1.5rem;">New Feedback</h2>
        <form id="testimonialForm" enctype="multipart/form-data">
            <input type="hidden" name="add_testimonial" id="formAction" value="1">
            <input type="hidden" name="id" id="item_id" value="">
            <input type="hidden" name="existing_image" id="existing_image" value="">

            <div class="form-group" style="margin-bottom:1rem;">
                <label>Client Name</label>
                <input type="text" id="client_name" name="client_name" required placeholder="Ex: John Doe">
            </div>

            <div class="form-group" style="margin-bottom:1rem;">
                <label>Company / Role</label>
                <input type="text" id="client_company" name="client_company" placeholder="Ex: CEO at TechCo">
            </div>

            <div class="form-group" style="margin-bottom:1rem;">
                <label>Avatar / Photo</label>
                <div style="background:#f8fafc; padding:1rem; border-radius:10px; border:1px dashed var(--border); text-align:center;">
                    <img id="imgPreview" src="https://via.placeholder.com/100x100" style="width:60px; height:60px; border-radius:50%; object-fit:cover; margin-bottom:1rem; display:none;">
                    <input type="file" id="imageInput" name="image" accept="image/*" onchange="previewImage(this)">
                </div>
            </div>

            <div class="form-group" style="margin-bottom:1rem;">
                <label>Star Rating</label>
                <select name="rating" id="rating">
                    <option value="5">5 - Excellent</option>
                    <option value="4">4 - Very Good</option>
                    <option value="3">3 - Good</option>
                    <option value="2">2 - Fair</option>
                    <option value="1">1 - Poor</option>
                </select>
            </div>

            <div class="form-group">
                <label>Testimonial Text</label>
                <textarea id="content" name="content" rows="4" required placeholder="Write the feedback here..."></textarea>
            </div>
            
            <div style="display:flex; gap:10px; margin-top:2rem;">
                <button type="submit" class="btn-primary" style="flex:1;">Save Feedback</button>
                <button type="button" id="resetBtn" style="background:#64748b; color:white; border:none; padding:8px 15px; border-radius:5px; font-size:12px; cursor:pointer; display:none;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    if (typeof testimonialsTable !== 'undefined') {
        testimonialsTable.destroy();
    }

    var testimonialsTable = $('#testimonialsTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": { "url": "ajax/testimonials-data.php", "type": "GET" },
        "columns": [
            { "data": "client" },
            { "data": "feedback" },
            { "data": "rating" },
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

    $('#testimonialForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: 'testimonials.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(res) {
                Swal.fire('Success!', 'Testimonial saved.', 'success');
                testimonialsTable.ajax.reload();
                resetForm();
            }
        });
    });

    function resetForm() {
        $('#formTitle').text('New Feedback');
        $('#formAction').attr('name', 'add_testimonial').val('1');
        $('#item_id').val('');
        $('#existing_image').val('');
        $('#testimonialForm')[0].reset();
        $('#imgPreview').hide();
        $('#resetBtn').hide();
    }

    $('#resetBtn').on('click', resetForm);

    function editItem(id) {
        $.getJSON('testimonials.php?fetch_id=' + id, function(data) {
            $('#formTitle').text('Update Feedback');
            $('#formAction').attr('name', 'edit_testimonial').val('1');
            $('#item_id').val(data.id);
            $('#client_name').val(data.client_name);
            $('#client_company').val(data.client_company);
            $('#content').val(data.content);
            $('#rating').val(data.rating);
            $('#existing_image').val(data.client_image);
            
            if (data.client_image) {
                $('#imgPreview').attr('src', '../' + data.client_image).show();
            }
            
            $('#resetBtn').show();
            $('html, body').animate({ scrollTop: $('#testimonialFormArea').offset().top - 50 }, 500);
        });
    }

    function deleteItem(id) {
        confirmDelete('testimonials.php?delete=' + id, function() {
            testimonialsTable.ajax.reload();
        });
    }
</script>
