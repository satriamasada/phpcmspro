        </div> <!-- End content-area -->
    </main> <!-- End main-wrapper -->

    <!-- Common Scripts -->
    <script>
        function confirmDelete(url, callback) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0066ff',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(response) {
                            try {
                                var res = typeof response === 'object' ? response : JSON.parse(response);
                                if (res.status === 'success') {
                                    Swal.fire('Deleted!', 'Data has been removed.', 'success');
                                    if(callback) callback();
                                } else {
                                    Swal.fire('Error', res.message || 'Operation failed', 'error');
                                }
                            } catch(e) {
                                // Default success if not JSON
                                Swal.fire('Deleted!', 'Data has been removed.', 'success');
                                if(callback) callback();
                            }
                        }
                    });
                }
            });
        }
    </script>
</body>
</html>
