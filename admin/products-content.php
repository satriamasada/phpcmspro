<?php
// admin/products-content.php
require_once __DIR__ . '/../includes/auth.php';
if (!isset($_SESSION['user_id'])) exit;
?>

<div class="grid-3" style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: start;">
    <!-- Products Table -->
    <div class="admin-card">
        <h2 style="margin-bottom: 2rem;">Digital Products</h2>
        <table id="productsTable" class="data-table datatable-server">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th width="100">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- Form Area -->
    <div class="admin-card" id="prodFormArea">
        <h2 id="formTitle" style="margin-bottom: 1.5rem;">Add New Product</h2>
        <form id="productForm" enctype="multipart/form-data">
            <input type="hidden" name="add_product" id="formAction" value="1">
            <input type="hidden" name="id" id="product_id" value="">
            <input type="hidden" name="existing_image" id="existing_image" value="">

            <div class="form-group">
                <label>Product Name</label>
                <input type="text" id="name" name="name" required placeholder="e.g. SoftCo CRM v2">
            </div>
            
            <div class="form-group">
                <label>Price (IDR)</label>
                <input type="number" id="price" name="price" required placeholder="e.g. 1500000">
            </div>

            <div class="form-group">
                <label>External / Purchase Link</label>
                <input type="url" id="external_link" name="external_link" placeholder="https://example.com/buy">
            </div>
            
            <div class="form-group">
                <label>Product Image</label>
                <div id="thumbPreview" style="margin-bottom:10px; display:none;">
                    <img src="" style="width:100px; height:70px; object-fit:cover; border-radius:5px; border:1px solid var(--border);">
                </div>
                <input type="file" name="product_image" accept="image/*" style="font-size:11px;">
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea id="description" name="description" rows="4" required placeholder="Outline product features..."></textarea>
            </div>
            
            <div style="display:flex; gap:10px; margin-top:2rem;">
                <button type="submit" class="btn-primary" style="flex:1;">Save Product</button>
                <button type="button" id="resetBtn" style="background:#64748b; color:white; border:none; padding:8px 15px; border-radius:5px; font-size:12px; cursor:pointer; display:none;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    if (typeof productsTable !== 'undefined') {
        productsTable.destroy();
    }

    var productsTable = $('#productsTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": { "url": "ajax/products-data.php", "type": "GET" },
        "columns": [
            { "data": "thumbnail" },
            { "data": "name" },
            { "data": "price" },
            { "data": "actions", "orderable": false }
        ]
    });

    $('#productForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        
        $.ajax({
            url: 'products.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(res) {
                Swal.fire('Success!', 'Product has been updated.', 'success');
                productsTable.ajax.reload();
                resetForm();
            },
            error: function() {
                Swal.fire('Error', 'Communication with server failed.', 'error');
            }
        });
    });

    function resetForm() {
        $('#formTitle').text('Add New Product');
        $('#formAction').attr('name', 'add_product').val('1');
        $('#product_id').val('');
        $('#productForm')[0].reset();
        $('#thumbPreview').hide();
        $('#resetBtn').hide();
    }

    $('#resetBtn').on('click', resetForm);

    function editProduct(id) {
        $.getJSON('products.php?fetch_id=' + id, function(data) {
            $('#formTitle').text('Edit Product');
            $('#formAction').attr('name', 'edit_product').val('1');
            $('#product_id').val(data.id);
            $('#name').val(data.name);
            $('#price').val(data.price);
            $('#external_link').val(data.external_link);
            $('#description').val(data.description);
            $('#existing_image').val(data.image_url);
            
            if(data.image_url) {
                var prefix = data.image_url.startsWith('uploads/') ? '../' : '';
                $('#thumbPreview').show().find('img').attr('src', prefix + data.image_url);
            } else {
                $('#thumbPreview').hide();
            }
            
            $('#resetBtn').show();
            $('html, body').animate({ scrollTop: $('#prodFormArea').offset().top - 50 }, 500);
        });
    }

    function deleteProduct(id) {
        confirmDelete('products.php?delete=' + id, function() {
            productsTable.ajax.reload();
        });
    }
</script>
