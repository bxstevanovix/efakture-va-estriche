// global-sweetalert.js
function initSweetAlert(selector = '.swal-confirm') {
    document.querySelectorAll(selector).forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const title = this.dataset.title || "Are you sure?";
            const text = this.dataset.desc || "You won't be able to revert this!";
            const url = this.dataset.url;
            const id = this.dataset.id || null;
            const successMsg = this.dataset.success || "Action completed successfully!";
            const tableSelector = this.dataset.table || null; // <--- opcionalni data-table atribut

            if (!url) {
                console.error("No URL provided for SweetAlert button.");
                return;
            }

            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                cancelButtonColor: '#3085d6',
                confirmButtonText: this.dataset.confirmText || 'Yes',
                cancelButtonText: this.dataset.cancelText || 'No',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ id: id })
                    })
                    .then(res => res.json())
                    .then(data => {
                        Swal.fire('Erledigt!', data.message || successMsg, 'success');

                        // Ako postoji data-table atribut, osveži DataTable
                        if (tableSelector) {
                            const tableEl = document.querySelector(tableSelector);
                            if (tableEl && $.fn.DataTable.isDataTable(tableEl)) {
                                $(tableEl).DataTable().ajax.reload(null, false);
                            }
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire('Fehler!', 'Es ist ein Problem aufgetreten', 'error');
                    });
                }
            });
        });
    });
}