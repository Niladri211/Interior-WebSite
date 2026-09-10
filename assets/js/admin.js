/**
 * Raman Group Admin Dashboard JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // --- Mobile Sidebar Toggle ---
    const sidebarToggle = document.getElementById('sidebarToggle');
    const adminSidebar = document.querySelector('.admin-sidebar');

    if (sidebarToggle && adminSidebar) {
        sidebarToggle.addEventListener('click', function() {
            adminSidebar.classList.toggle('show');
        });
    }

    // --- Delete Confirmation ---
    const deleteBtns = document.querySelectorAll('.btn-delete-confirm');
    deleteBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            const confirmMsg = this.getAttribute('data-confirm') || 'Are you sure you want to delete this item? This action cannot be undone.';
            if (!confirm(confirmMsg)) {
                e.preventDefault();
            }
        });
    });

    // --- Image Preview on Upload Input ---
    const imageInputs = document.querySelectorAll('.image-upload-preview');
    imageInputs.forEach(input => {
        input.addEventListener('change', function() {
            const previewTargetId = this.getAttribute('data-preview');
            const previewContainer = document.getElementById(previewTargetId);
            
            if (previewContainer && this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewContainer.src = e.target.result;
                    previewContainer.style.display = 'block';
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    });

    // --- Quick Admin Table Filter Search ---
    const tableSearch = document.getElementById('adminTableSearch');
    if (tableSearch) {
        tableSearch.addEventListener('keyup', function() {
            const query = this.value.toLowerCase();
            const rows = document.querySelectorAll('.admin-table tbody tr');

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                if (text.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

});
