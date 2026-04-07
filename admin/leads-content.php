<?php
// admin/leads-content.php
require_once __DIR__ . '/../includes/auth.php';
if (!isset($_SESSION['user_id'])) exit;
?>

<div class="admin-card">
    <h2 style="margin-bottom: 2rem;">Incoming Messages</h2>
    <table id="leadsTable" class="data-table datatable-server">
        <thead>
            <tr>
                <th>Date</th>
                <th>Sender</th>
                <th>Subject</th>
                <th>Status</th>
                <th width="100">Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script>
    if (typeof leadsTable !== 'undefined') {
        leadsTable.destroy();
    }

    var leadsTable = $('#leadsTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "ajax/leads-data.php",
            "type": "GET"
        },
        "columns": [
            { "data": "date" },
            { "data": "sender" },
            { "data": "subject" },
            { "data": "status" },
            { "data": "actions", "orderable": false }
        ],
        "order": [[0, "desc"]]
    });

    function viewLead(id) {
        // First mark as read
        $.get('leads.php?read=' + id, function() {
            // Then fetch all leads and find this one's message (for demo, we'll just use a generic message or re-fetch)
            // Ideally we re-fetch single lead data
            Swal.fire({
                title: 'Message Details',
                html: '<div style="text-align:left; color:#64748b;">Loading message...</div>',
                showCloseButton: true,
                confirmButtonText: 'Done'
            });

            // Fetch message
            $.getJSON('ajax/lead-detail.php?id=' + id, function(data) {
                Swal.update({
                    html: '<div style="text-align:left; color:#1e293b; line-height:1.6; padding:10px;">' +
                          '<p><strong>From:</strong> ' + data.name + ' (' + data.email + ')</p>' +
                          '<p><strong>Subject:</strong> ' + data.subject + '</p>' +
                          '<hr style="border:0.5px solid #e2e8f0; margin:15px 0;">' +
                          '<div style="background:#f8fafc; padding:15px; border-radius:10px; border:1px solid #e2e8f0;">' + data.message + '</div>' +
                          '</div>'
                });
                leadsTable.ajax.reload(null, false);
            });
        });
    }
</script>
