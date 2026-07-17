/**
 * main.js — AI-Solutions Global JavaScript
 * Handles: mobile nav toggle, scroll effects, form validation helpers,
 *          smooth scroll, back-to-top, fade-in-on-scroll
 */

document.addEventListener('DOMContentLoaded', () => {

    // ── 1. Mobile Navigation Toggle ──────────────────────────
    const toggle  = document.getElementById('navToggle');
    const navMenu = document.getElementById('navMenu');

    if (toggle && navMenu) {
        toggle.addEventListener('click', () => {
            const isOpen = navMenu.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', isOpen);

            // Animate hamburger → X
            const bars = toggle.querySelectorAll('.hamburger');
            if (isOpen) {
                bars[0].style.transform = 'translateY(7px) rotate(45deg)';
                bars[1].style.opacity   = '0';
                bars[2].style.transform = 'translateY(-7px) rotate(-45deg)';
            } else {
                bars.forEach(b => { b.style.transform = ''; b.style.opacity = ''; });
            }
        });

        // Close menu when a link is clicked
        navMenu.querySelectorAll('.nav__link').forEach(link => {
            link.addEventListener('click', () => {
                navMenu.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
                toggle.querySelectorAll('.hamburger').forEach(b => {
                    b.style.transform = ''; b.style.opacity = '';
                });
            });
        });

        // Close on outside click
        document.addEventListener('click', (e) => {
            if (!toggle.contains(e.target) && !navMenu.contains(e.target)) {
                navMenu.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // ── 2. Sticky Header Shadow ───────────────────────────────
    const header = document.getElementById('site-header');
    if (header) {
        window.addEventListener('scroll', () => {
            header.classList.toggle('scrolled', window.scrollY > 10);
        }, { passive: true });
    }

    // ── 3. Fade-in on Scroll (Intersection Observer) ─────────
    const fadeEls = document.querySelectorAll(
        '.card, .blog-card, .event-card, .testimonial-card, .case-card, .kpi-card, .gallery-item'
    );

    if ('IntersectionObserver' in window && fadeEls.length) {
        const io = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in-up');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });

        fadeEls.forEach(el => {
            el.style.opacity = '0';
            io.observe(el);
        });
    }

    // ── 4. Back-to-Top Button (auto-injected) ────────────────
    const btt = document.createElement('button');
    btt.id        = 'backToTop';
    btt.innerHTML = '<i class="fa fa-arrow-up"></i>';
    btt.setAttribute('aria-label', 'Back to top');
    btt.style.cssText = `
        position:fixed; bottom:1.5rem; right:1.5rem; z-index:999;
        background:var(--blue); color:#fff; border:none; border-radius:50%;
        width:44px; height:44px; cursor:pointer; font-size:1rem;
        box-shadow:0 4px 12px rgba(0,0,0,0.2);
        display:flex; align-items:center; justify-content:center;
        opacity:0; transition:opacity 0.3s, transform 0.3s;
        transform:translateY(20px); pointer-events:none;
    `;
    document.body.appendChild(btt);

    window.addEventListener('scroll', () => {
        const show = window.scrollY > 400;
        btt.style.opacity       = show ? '1' : '0';
        btt.style.transform     = show ? 'translateY(0)' : 'translateY(20px)';
        btt.style.pointerEvents = show ? 'auto' : 'none';
    }, { passive: true });

    btt.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

    // ── 5. Client-side Form Validation Helper ────────────────
    // Attach to any form with data-validate="true"
    document.querySelectorAll('form[data-validate="true"]').forEach(form => {
        form.addEventListener('submit', (e) => {
            let valid = true;

            // Clear previous errors
            form.querySelectorAll('.form-error').forEach(el => el.textContent = '');
            form.querySelectorAll('.form-control').forEach(el => {
                el.classList.remove('is-invalid', 'is-valid');
            });

            // Validate required fields
            form.querySelectorAll('[required]').forEach(field => {
                const val = field.value.trim();
                const err = document.getElementById(field.id + '-error');

                if (!val) {
                    field.classList.add('is-invalid');
                    if (err) err.textContent = 'This field is required.';
                    valid = false;
                } else {
                    // Email format check
                    if (field.type === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
                        field.classList.add('is-invalid');
                        if (err) err.textContent = 'Please enter a valid email address.';
                        valid = false;
                    } else {
                        field.classList.add('is-valid');
                    }
                }
            });

            if (!valid) {
                e.preventDefault();
                // Scroll to first error
                const first = form.querySelector('.is-invalid');
                if (first) first.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    });

    // ── 6. Auto-dismiss alerts after 5 seconds ───────────────
    document.querySelectorAll('.alert').forEach(alert => {
        if (alert.dataset.autoDismiss !== 'false') {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }, 5000);
        }
    });

    // ── 7. KPI card counter animation ────────────────────────
    document.querySelectorAll('.kpi-card__value[data-count]').forEach(el => {
        const target = parseInt(el.dataset.count, 10);
        if (isNaN(target)) return;
        let current = 0;
        const step  = Math.ceil(target / 40);
        const timer = setInterval(() => {
            current = Math.min(current + step, target);
            el.textContent = current;
            if (current >= target) clearInterval(timer);
        }, 40);
    });

    // ── Broken image fallback ────────────────────────────────
    document.querySelectorAll('img[src]').forEach(img => {
        img.addEventListener('error', function () {
            this.onerror = null;
            this.style.cssText = 'object-fit:none;background:var(--blue-light);filter:none;';
            this.src = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100%25' height='100%25' viewBox='0 0 200 120'%3E%3Crect fill='%23EEF2FF' width='200' height='120'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='Inter,sans-serif' font-size='13' fill='%234F46E5'%3EImage unavailable%3C/text%3E%3C/svg%3E";
        });
    });

});
