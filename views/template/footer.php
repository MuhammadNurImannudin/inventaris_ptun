<?php
/**
 * Footer Template - PTUN Banjarmasin
 * 
 * LOKASI FILE: views/template/footer.php (REPLACE)
 */
?>

    </div> <!-- End main-content -->
</div> <!-- End page-content-wrapper -->

<!-- Footer -->
<footer class="bg-white border-top py-4 mt-5">
    <div class="container-fluid px-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0 text-muted">
                    © <?= date('Y') ?> Pengadilan Tata Usaha Negara Banjarmasin. 
                    <br><small>Sistem Inventaris v<?= defined('APP_VERSION') ? APP_VERSION : '1.0.0' ?></small>
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="mb-0 text-muted">
                    <i class="bi bi-person-badge me-1"></i>
                    Dikembangkan untuk PKL PTUN Banjarmasin
                </p>
            </div>
        </div>
    </div>
</footer>

<!-- Toast Notification Container -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11;">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-success text-white">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong class="me-auto">Berhasil</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-danger text-white">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong class="me-auto">Error</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
</div>

<!-- Bootstrap JavaScript Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JavaScript -->
<script>
// Sidebar Toggle Functionality - FIXED VERSION
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar-wrapper');
    const pageContent = document.getElementById('page-content-wrapper');
    
    if (sidebarToggle && sidebar && pageContent) {
        sidebarToggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Toggle classes for responsive behavior
            sidebar.classList.toggle('toggled');
            pageContent.classList.toggle('toggled');
            
            // For mobile - show/hide sidebar
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('show');
            }
        });
    }

    // Auto-hide sidebar on mobile when clicking outside
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768) {
            if (sidebar && !sidebar.contains(e.target) && 
                sidebarToggle && !sidebarToggle.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        }
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        if (sidebar && pageContent) {
            if (window.innerWidth > 768) {
                sidebar.classList.remove('show');
                // Reset desktop layout
                if (!sidebar.classList.contains('toggled')) {
                    pageContent.style.marginLeft = '280px';
                } else {
                    pageContent.style.marginLeft = '0';
                }
            } else {
                // Mobile layout
                pageContent.style.marginLeft = '0';
            }
        }
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Auto-hide toasts after 5 seconds
    var toasts = document.querySelectorAll('.toast');
    toasts.forEach(function(toast) {
        var bsToast = new bootstrap.Toast(toast, {delay: 5000});
        bsToast.show();
        
        // Auto hide after delay
        setTimeout(function() {
            bsToast.hide();
        }, 5000);
    });

    // Confirm delete actions
    const deleteButtons = document.querySelectorAll('[data-confirm-delete]');
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            const message = this.getAttribute('data-confirm-delete') || 'Apakah Anda yakin ingin menghapus data ini?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });

    // Add loading animation to buttons
    const submitButtons = document.querySelectorAll('button[type="submit"]');
    submitButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="bi bi-arrow-repeat spin me-2"></i>Loading...';
            this.disabled = true;
            
            // Re-enable after 3 seconds (fallback)
            setTimeout(() => {
                this.innerHTML = originalText;
                this.disabled = false;
            }, 3000);
        });
    });
});

// Utility Functions
function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}

function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.top = '20px';
    alertDiv.style.right = '20px';
    alertDiv.style.zIndex = '9999';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Print function for reports
function printReport() {
    const printContent = document.querySelector('.printable-area');
    if (printContent) {
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Laporan PTUN Banjarmasin</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    @media print {
                        .no-print { display: none !important; }
                        body { font-size: 12px; }
                        .card { border: 1px solid #dee2e6 !important; }
                        .page-break { page-break-before: always; }
                    }
                    .header-print {
                        text-align: center;
                        border-bottom: 2px solid #000;
                        padding-bottom: 20px;
                        margin-bottom: 30px;
                    }
                    .logo-print {
                        width: 80px;
                        height: 80px;
                        margin: 0 auto 10px;
                    }
                </style>
            </head>
            <body>
                <div class="header-print">
                    <h3>PENGADILAN TATA USAHA NEGARA BANJARMASIN</h3>
                    <p>Jl. Brig Jend. Hasan Basri No.3, Pangeran, Kec. Banjarmasin Utara</p>
                    <p>Banjarmasin, Kalimantan Selatan 70124</p>
                    <p>Telp: (0511) 3354420 | Email: ptun.banjarmasin@go.id</p>
                </div>
                ${printContent.innerHTML}
                <div class="mt-4 text-end">
                    <p>Dicetak pada: ${new Date().toLocaleDateString('id-ID', { 
                        weekday: 'long', 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric' 
                    })}</p>
                    <br><br>
                    <div class="text-center">
                        <p>Mengetahui,<br><br><br><br>______________________<br>
                        Administrator Sistem</p>
                    </div>
                </div>
            </body>
            </html>
        `);
        printWindow.document.close();
        
        // Wait for content to load then print
        printWindow.onload = function() {
            printWindow.print();
            printWindow.close();
        };
    } else {
        showAlert('Konten untuk print tidak ditemukan', 'warning');
    }
}

// Export to Excel (simple CSV)
function exportToCSV(tableId, filename = 'data_ptun') {
    const table = document.getElementById(tableId);
    if (!table) {
        showAlert('Tabel tidak ditemukan', 'error');
        return;
    }
    
    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = [];
        const cols = rows[i].querySelectorAll('td, th');
        
        for (let j = 0; j < cols.length; j++) {
            // Clean up text content
            let cellText = cols[j].innerText.replace(/,/g, ';').replace(/\n/g, ' ').trim();
            row.push(`"${cellText}"`);
        }
        if (row.length > 0) {
            csv.push(row.join(','));
        }
    }
    
    const csvContent = csv.join('\n');
    const BOM = '\uFEFF'; // UTF-8 BOM for Excel
    const blob = new Blob([BOM + csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = window.URL.createObjectURL(blob);
    
    const a = document.createElement('a');
    a.setAttribute('hidden', '');
    a.setAttribute('href', url);
    a.setAttribute('download', filename + '_' + new Date().toISOString().slice(0, 10) + '.csv');
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    
    showAlert('File berhasil didownload', 'success');
}

// Image preview function
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById(previewId);
            if (preview) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Add spinning animation for icons
const style = document.createElement('style');
style.textContent = `
    .spin {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: var(--ptun-primary);
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: var(--ptun-secondary);
    }
`;
document.head.appendChild(style);

// Console log for debugging
console.log('PTUN Banjarmasin Inventory System - JavaScript Loaded Successfully');
console.log('Base URL:', '<?= defined("BASE_URL") ? BASE_URL : "http://localhost/inventaris-ptun/" ?>');
</script>

</body>
</html>

    // Enhanced form validation
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    // Auto-format number inputs
    const numberInputs = document.querySelectorAll('input[type="number"]');
    numberInputs.forEach(function(input) {
        input.addEventListener('input', function() {
            if (this.value < 0) this.value = 0;
        });
    });

    // Initialize smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }