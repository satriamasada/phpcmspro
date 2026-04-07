<?php
// admin/inquiries-content.php
require_once __DIR__ . '/../includes/auth.php';
if (!isset($_SESSION['user_id'])) exit;
?>

<div class="admin-card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem;">
        <h2>Project Proposals</h2>
        <p style="font-size:0.85rem; color:var(--text-muted);">Incoming project leads and service inquiries.</p>
    </div>
    <table id="inquiriesTable" class="data-table datatable-server">
        <thead>
            <tr>
                <th>Date</th>
                <th>Client</th>
                <th>Interest Item</th>
                <th>Source</th>
                <th>Status</th>
                <th width="120">Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script>
    if (typeof inquiriesTable !== 'undefined') {
        inquiriesTable.destroy();
    }

    var inquiriesTable = $('#inquiriesTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": { "url": "ajax/inquiries-data.php", "type": "GET" },
        "columns": [
            { "data": "date" },
            { "data": "client" },
            { "data": "item" },
            { "data": "source" },
            { "data": "status" },
            { "data": "actions", "orderable": false }
        ],
        "order": [[0, "desc"]]
    });

    function viewInquiry(id) {
        Swal.fire({
            title: 'Proposal Detail',
            html: '<div style="text-align:left; color:#64748b;">Loading details...</div>',
            showCloseButton: true,
            confirmButtonText: 'Close'
        });

        $.getJSON('ajax/inquiry-detail.php?id=' + id, function(data) {
            Swal.update({
                html: '<div style="text-align:left; color:#1e293b; line-height:1.6; padding:10px;">' +
                      '<p><strong>Customer:</strong> ' + data.customer_name + '</p>' +
                      '<p><strong>Contact:</strong> ' + data.customer_email + ' (' + data.customer_phone + ')</p>' +
                      '<p><strong>Interest:</strong> ' + (data.interest_title || 'General Service') + ' (' + data.inquiry_type + ')</p>' +
                      '<hr style="border:0.5px solid #e2e8f0; margin:15px 0;">' +
                      '<div style="background:#f8fafc; padding:15px; border-radius:10px; border:1px solid #e2e8f0; font-size:0.95rem;">' + data.message + '</div>' +
                      '<div style="margin-top:2rem; display:flex; gap:10px;">' +
                        '<button class="btn-primary" onclick="updateStatus(' + id + ', \'responded\')" style="background:#10b981; font-size:11px;">Responded</button>' +
                        '<button class="btn-primary" onclick="updateStatus(' + id + ', \'deal\')" style="background:#6366f1; font-size:11px;">Deal / Win</button>' +
                        '<button class="btn-primary" onclick="updateStatus(' + id + ', \'rejected\')" style="background:#f43f5e; font-size:11px;">Reject</button>' +
                      '</div>' +
                      '</div>'
            });
        });
    }

    function updateStatus(id, status) {
        $.getJSON('inquiries.php?status=' + status + '&id=' + id, function(res) {
            Swal.fire('Updated!', 'Proposal status changed to ' + status, 'success');
            inquiriesTable.ajax.reload(null, false);
        });
    }

    function deleteInquiry(id) {
        confirmDelete('inquiries.php?delete=' + id, function() {
            inquiriesTable.ajax.reload();
        });
    }
</script>
