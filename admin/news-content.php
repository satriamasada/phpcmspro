<?php
// admin/news-content.php
require_once __DIR__ . '/../includes/auth.php';
if (!isset($_SESSION['user_id'])) exit;
?>

<!-- Quill.js (Modern & Reliable Publisher) -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<div class="grid-3" style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: start;">
    <!-- List Area -->
    <div class="admin-card">
        <h2 style="margin-bottom:1.5rem;">News Management</h2>
        <table id="newsTable" class="data-table datatable-server">
            <thead>
                <tr>
                    <th>Featured</th>
                    <th>Article Title</th>
                    <th>Status</th>
                    <th width="100">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- Form Area -->
    <div class="admin-card" id="newsFormArea">
        <h2 id="formTitle" style="margin-bottom: 1.5rem;">Create Post</h2>
        <form id="newsForm" enctype="multipart/form-data">
            <input type="hidden" name="add_news" id="formAction" value="1">
            <input type="hidden" name="id" id="article_id" value="">
            <input type="hidden" name="existing_thumbnail" id="existing_thumbnail" value="">

            <div class="form-group">
                <label>Article Headline</label>
                <input type="text" id="title" name="title" required placeholder="Main title...">
            </div>
            
            <div class="form-group">
                <label>Thumbnail / Image</label>
                <div id="thumbPreview" style="margin-bottom:10px; display:none;">
                    <img src="" style="width:100px; height:65px; object-fit:cover; border-radius:5px; border:1px solid var(--border);">
                </div>
                <input type="file" name="thumbnail" accept="image/*" style="font-size:11px;">
            </div>

            <div class="form-group">
                <label>Content Editor</label>
                <div id="editor" style="height: 300px; border-radius: 6px; background:#fff;"></div>
                <textarea name="content" id="hidden_content" style="display:none;"></textarea>
            </div>
            
            <div class="form-group" style="display:flex; align-items:center; gap:10px; margin-top:1.5rem;">
                <input type="checkbox" id="is_published" name="is_published" checked style="width:auto;">
                <label for="is_published" style="margin:0; font-size:12px;">Publish directly</label>
            </div>
            
            <div style="display:flex; gap:10px; margin-top:2rem;">
                <button type="submit" class="btn-primary" style="flex:1;">Save Post</button>
                <button type="button" id="resetBtn" style="background:#64748b; color:white; border:none; padding:8px 15px; border-radius:5px; font-size:12px; cursor:pointer; display:none;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Quill UI Config
    var quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Tell your story...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link', 'blockquote'],
                ['clean']
            ]
        }
    });

    if (typeof newsTable !== 'undefined') {
        newsTable.destroy();
    }

    var newsTable = $('#newsTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": { "url": "ajax/news-data.php", "type": "GET" },
        "columns": [
            { "data": "thumbnail" },
            { "data": "title" },
            { "data": "status" },
            { "data": "actions", "orderable": false }
        ]
    });

    $('#newsForm').on('submit', function(e) {
        e.preventDefault();
        $('#hidden_content').val(quill.root.innerHTML);
        
        var formData = new FormData(this);
        $.ajax({
            url: 'news.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(res) {
                Swal.fire({
                    title: 'Success!',
                    text: 'Post has been saved correctly.',
                    icon: 'success'
                });
                newsTable.ajax.reload();
                resetForm();
            },
            error: function() {
                Swal.fire('Error', 'Communication error with server.', 'error');
            }
        });
    });

    function resetForm() {
        $('#formTitle').text('Create Post');
        $('#formAction').attr('name', 'add_news').val('1');
        $('#article_id').val('');
        $('#newsForm')[0].reset();
        $('#thumbPreview').hide();
        $('#resetBtn').hide();
        quill.root.innerHTML = '';
    }

    $('#resetBtn').on('click', resetForm);

    function editNews(id) {
        $.getJSON('news.php?fetch_id=' + id, function(data) {
            $('#formTitle').text('Update Post');
            $('#formAction').attr('name', 'edit_news').val('1');
            $('#article_id').val(data.id);
            $('#title').val(data.title);
            $('#is_published').prop('checked', data.is_published == 1);
            $('#existing_thumbnail').val(data.featured_image);
            
            if(data.featured_image) {
                var prefix = data.featured_image.startsWith('uploads/') ? '../' : '';
                $('#thumbPreview').show().find('img').attr('src', prefix + data.featured_image);
            } else {
                $('#thumbPreview').hide();
            }
            
            quill.root.innerHTML = data.content;
            $('#resetBtn').show();
            $('html, body').animate({ scrollTop: $('#newsFormArea').offset().top - 50 }, 500);
        });
    }

    function deleteNews(id) {
        confirmDelete('news.php?delete=' + id, function() {
            newsTable.ajax.reload();
        });
    }
</script>
