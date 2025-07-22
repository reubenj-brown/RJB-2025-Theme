    <!-- Footer -->
    <footer class="site-footer" id="site-footer">
        <div class="footer-content">
            <div class="footer-logo">
                <img src="/wp-content/uploads/2025/06/Reuben-J-Brown-logo-favicon-white.png" alt="RJB Logo" id="footer-logo-img">
            </div>
            <div class="social-links" style="color: #808080">
                <p class="caption"><a href="mailto:reubenjbrown@protonmail.com">email</a> / <a href="https://www.instagram.com/reubenj.brown/">instagram</a> / <a href="https://www.linkedin.com/in/reuben-j-brown/">linkedin</a></p>
            </div>
            <div class="copyright caption">© Reuben J. Brown 2025</div>
        </div>
    </footer>

    <style>
    /* Footer Styles */
    .site-footer {
        position: sticky;
        bottom: 0;
        background: transparent;
        padding: 1vw 2vw;
        z-index: 100;
    }

    /* Gradient blur effect for footer - 3 points: 2px, 5px, 10px */
    .site-footer::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        backdrop-filter: blur(10px);
        mask: linear-gradient(to bottom, transparent 10px, black 30px, black 100%);
        -webkit-mask: linear-gradient(to bottom, transparent 10px, black 30px, black 100%);
        z-index: -1;
    }

    .site-footer::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        backdrop-filter: blur(2px);
        mask: linear-gradient(to bottom, black 0%, black 20px, transparent 30px);
        -webkit-mask: linear-gradient(to bottom, black 0%, black 20px, transparent 30px);
        z-index: -1;
    }

    /* Third pseudo-element for middle blur point */
    .site-footer .footer-content::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        backdrop-filter: blur(5px);
        mask: linear-gradient(to bottom, transparent 10px, black 20px, transparent 30px);
        -webkit-mask: linear-gradient(to bottom, transparent 10px, black 20px, transparent 30px);
        z-index: -1;
    }

    .footer-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
    }

    .social-links {
        display: flex;
        gap: 0;
        align-items: center;
    }

    .social-links a {
        text-decoration: none;
        transition: all 0.5s ease;
        color: #808080;
        font-size: 14px;
    }

    .social-links a:hover {
        color: #39e58f;
    }

    .copyright {
        color: #808080;
        font-size: 14px;
        transition: all 0.5s ease;
    }

    .footer-logo {
        width: 40px;
        height: 40px;
    }

    .footer-logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    /* Footer overlay styles for full-bleed section */
    .site-footer.over-full-bleed::before,
    .site-footer.over-full-bleed::after,
    .site-footer.over-full-bleed .footer-content::before {
        backdrop-filter: none !important;
        -webkit-mask: none !important;
        mask: none !important;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .site-footer {
            padding: 3vw 4vw 2vw 4vw;
        }

        .footer-content {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .social-links {
            gap: 0;
        }

        .footer-logo {
            width: 28px;
            height: 28px;
        }

        .copyright {
            display: none; /* Always hidden on mobile */
        }
    }
    </style>

    <script>
        // Set footer logo to black for story pages (no splash area)
        const footerLogo = document.getElementById('footer-logo-img');
        if (footerLogo && (document.body.classList.contains('single-story') || document.body.classList.contains('post-type-archive-story'))) {
            footerLogo.src = '/wp-content/uploads/2025/06/Reuben-J-Brown-logo-favicon-black.png';
        }

        // Header transparency and footer visibility for full-bleed sections (portfolio page only)
        function updateHeaderAndFooterForFullBleed() {
            const header = document.querySelector('.site-header');
            const footer = document.querySelector('.site-footer');
            const fullBleedSection = document.querySelector('.featured-story-full-bleed');
            
            if (!header || !footer || !fullBleedSection) {
                return;
            }
            
            const headerHeight = header.offsetHeight;
            const fullBleedRect = fullBleedSection.getBoundingClientRect();
            const footerRect = footer.getBoundingClientRect();
            
            // Check if header overlaps with full-bleed section
            if (fullBleedRect.top <= headerHeight && fullBleedRect.bottom >= 0) {
                header.classList.add('over-full-bleed');
            } else {
                header.classList.remove('over-full-bleed');
            }
            
            // Check if footer overlaps with full-bleed section
            const socialLinks = footer.querySelector('.social-links');
            const footerLogo = footer.querySelector('.footer-logo img');
            const copyright = footer.querySelector('.copyright');
            
            // Footer overlaps with hero section if hero section bottom is below footer top
            if (fullBleedRect.bottom > footerRect.top && fullBleedRect.top < footerRect.bottom) {
                // Footer is overlapping hero section - use white logo/text and disable blur
                footer.classList.add('over-full-bleed');
                if (footerLogo) footerLogo.src = '/wp-content/uploads/2025/06/Reuben-J-Brown-logo-favicon-white.png';
                if (copyright) copyright.style.color = 'white';
                if (socialLinks) socialLinks.style.display = 'none';
            } else {
                // Footer is not overlapping hero section - use black logo/gray text and enable blur
                footer.classList.remove('over-full-bleed');
                if (footerLogo) footerLogo.src = '/wp-content/uploads/2025/06/Reuben-J-Brown-logo-favicon-black.png';
                if (copyright) copyright.style.color = '#808080';
                if (socialLinks) socialLinks.style.display = 'block';
            }
        }

        // Only run full-bleed logic on portfolio pages
        if (document.body.classList.contains('page-template-page-portfolio')) {
            window.addEventListener('scroll', updateHeaderAndFooterForFullBleed);
            window.addEventListener('resize', updateHeaderAndFooterForFullBleed);
            updateHeaderAndFooterForFullBleed(); // Run on page load
        }
    </script>

    <?php wp_footer(); ?>
</body>
</html>