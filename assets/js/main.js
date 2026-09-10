/**
 * Raman Group Main Client JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    

    // --- Back to Top Button ---
    const scrollTopBtn = document.getElementById('scrollTopBtn');
    if (scrollTopBtn) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 400) {
                scrollTopBtn.classList.add('visible');
            } else {
                scrollTopBtn.classList.remove('visible');
            }
        });

        scrollTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // --- Statistics Counters ---
    const statNumbers = document.querySelectorAll('.stat-number');
    if (statNumbers.length > 0) {
        let animated = false;

        function runCounters() {
            const statSection = document.querySelector('.stats-section');
            if (!statSection) return;

            const rect = statSection.getBoundingClientRect();
            if (rect.top <= window.innerHeight && rect.bottom >= 0 && !animated) {
                animated = true;
                statNumbers.forEach(counter => {
                    const targetText = counter.getAttribute('data-target') || counter.innerText;
                    const target = parseInt(targetText.replace(/\D/g, '')) || 100;
                    const suffix = targetText.replace(/[0-9]/g, '');
                    let count = 0;
                    const speed = Math.ceil(target / 50);

                    const updateCount = () => {
                        count += speed;
                        if (count < target) {
                            counter.innerText = count + suffix;
                            setTimeout(updateCount, 30);
                        } else {
                            counter.innerText = target + suffix;
                        }
                    };
                    updateCount();
                });
            }
        }

        window.addEventListener('scroll', runCounters);
        runCounters();
    }

    // --- Project / Gallery Filter Tabs ---
    const filterBtns = document.querySelectorAll('.filter-btn');
    const filterItems = document.querySelectorAll('.filter-item');

    if (filterBtns.length > 0 && filterItems.length > 0) {
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const filterValue = this.getAttribute('data-filter');

                filterItems.forEach(item => {
                    const category = item.getAttribute('data-category');
                    if (filterValue === 'all' || category === filterValue) {
                        item.style.display = 'block';
                        setTimeout(() => {
                            item.style.opacity = '1';
                            item.style.transform = 'scale(1)';
                        }, 50);
                    } else {
                        item.style.opacity = '0';
                        item.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            item.style.display = 'none';
                        }, 300);
                    }
                });
            });
        });
    }

    // --- AJAX Form Handler for Enquiries & Contact ---
    const ajaxForms = document.querySelectorAll('.ajax-form');
    ajaxForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const isAjax = form.dataset.ajax === 'true';
            if (!isAjax) return;

            e.preventDefault();
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn ? submitBtn.innerHTML : 'Submit';
            const alertBox = form.querySelector('.form-alert') || document.getElementById('formAlert');

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Processing...';
            }

            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }

                if (alertBox) {
                    alertBox.innerHTML = `
                        <div class="alert alert-${data.success ? 'success' : 'danger'} alert-dismissible fade show shadow-sm">
                            <i class="fas ${data.success ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2"></i>
                            ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                }

                if (data.success) {
                    form.reset();
                    if (data.redirect) {
                        setTimeout(() => window.location.href = data.redirect, 1500);
                    }
                }
            })
            .catch(err => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
                if (alertBox) {
                    alertBox.innerHTML = `
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-triangle me-2"></i> An unexpected network error occurred. Please try again.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                }
            });
        });
    // --- Global Image Load Error Interceptor ---
    document.addEventListener('error', function(e) {
        if (e.target && e.target.tagName && e.target.tagName.toLowerCase() === 'img') {
            const img = e.target;
            if (img.dataset.fallbackTried) return;
            img.dataset.fallbackTried = 'true';
            
            const altText = encodeURIComponent(img.getAttribute('alt') || 'Raman Group Project');
            const svgFallback = "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='800' height='500' viewBox='0 0 800 500'><defs><linearGradient id='bg' x1='0%25' y1='0%25' x2='100%25' y2='100%25'><stop offset='0%25' stop-color='%230b1220'/><stop offset='100%25' stop-color='%23161f33'/></linearGradient></defs><rect width='800' height='500' fill='url(%23bg)'/><rect x='20' y='20' width='760' height='460' rx='12' fill='none' stroke='%23d4af37' stroke-width='1.5' stroke-opacity='0.3'/><text x='400' y='240' font-family='Outfit, sans-serif' font-size='22' font-weight='800' fill='%23ffffff' text-anchor='middle'>" + altText + "</text><text x='400' y='280' font-family='Outfit, sans-serif' font-size='13' font-weight='800' fill='%23d4af37' text-anchor='middle' letter-spacing='2'>RAMAN GROUP SHOWCASE</text></svg>";
            img.src = svgFallback;
        }
    }, true);

});

