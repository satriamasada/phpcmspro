<?php
// admin/orders-content.php
require_once __DIR__ . '/../includes/auth.php';
authorize('Super Admin');
?>

<div class="admin-card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem;">
        <h2>Customer Orders</h2>
        <p style="font-size:0.85rem; color:var(--text-muted);">Manage payments and licenses.</p>
    </div>
    <table id="ordersTable" class="data-table datatable-server">
        <thead>
            <tr>
                <th>Order Date</th>
                <th>Customer</th>
                <th>Product</th>
                <th>Amount</th>
                <th>Payment</th>
                <th>Status</th>
                <th width="120">Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script>
    if (typeof ordersTable !== 'undefined') {
        ordersTable.destroy();
    }

    var ordersTable = $('#ordersTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": { "url": "ajax/orders-data.php", "type": "GET" },
        "columns": [
            { "data": "date" },
            { "data": "customer" },
            { "data": "product" },
            { "data": "amount" },
            { "data": "payment" },
            { "data": "status" },
            { "data": "actions", "orderable": false }
        ],
        "order": [[0, "desc"]]
    });

    function updateStatus(id, status) {
        var action = status === 'paid' ? 'Mark as Paid' : 'Cancel Order';
        var color = status === 'paid' ? '#10b981' : '#f43f5e';
        
        Swal.fire({
            title: action + '?',
            text: "Change order status to " + status + "?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: color,
            confirmButtonText: 'Yes, change it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.getJSON('orders.php?status=' + status + '&id=' + id, function(res) {
                    Swal.fire('Updated!', 'Order status has been changed.', 'success');
                    ordersTable.ajax.reload(null, false);
                });
            }
        });
    }

    function deleteOrder(id) {
        confirmDelete('orders.php?delete=' + id, function() {
            ordersTable.ajax.reload();
        });
    }
</script>
