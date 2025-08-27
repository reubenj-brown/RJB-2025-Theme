    <!-- Footer -->
    <footer class="site-footer" id="site-footer">
        <div class="footer-content">
            <div class="footer-logo">
                <img src="/wp-content/uploads/2025/06/Reuben-J-Brown-logo-favicon-black.png" alt="RJB Logo" id="footer-logo-img">
            </div>
            <!-- Contact button for responsive display -->
            <div class="footer-contact-button">
                <a href="<?php echo home_url('/#contact'); ?>" class="footer-contact-pill">contact ↓</a>
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
        z-index: 1001; /* Ensure footer sits above contact section gradient */
    }

    /* Smoother gradient blur effect for footer - 3 points with softer transitions */
    .site-footer::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        backdrop-filter: blur(8px);
        mask: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.7) 30%, black 60%, black 100%);
        -webkit-mask: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.7) 30%, black 60%, black 100%);
        z-index: -1;
    }

    .site-footer::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        backdrop-filter: blur(3px);
        mask: linear-gradient(to bottom, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.8) 20%, rgba(0,0,0,0.3) 70%, transparent 100%);
        -webkit-mask: linear-gradient(to bottom, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.8) 20%, rgba(0,0,0,0.3) 70%, transparent 100%);
        z-index: -1;
    }

    /* Third pseudo-element for middle blur point with softer blending */
    .site-footer .footer-content::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        backdrop-filter: blur(5px);
        mask: linear-gradient(to bottom, transparent 20%, rgba(0,0,0,0.5) 40%, rgba(0,0,0,0.6) 60%, transparent 80%);
        -webkit-mask: linear-gradient(to bottom, transparent 20%, rgba(0,0,0,0.5) 40%, rgba(0,0,0,0.6) 60%, transparent 80%);
        z-index: -1;
    }

    .footer-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
    }

    /* Footer contact button - hidden by default, shown on tablet/mobile */
    .footer-contact-button {
        display: none;
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
    }

    .footer-contact-pill {
        display: inline-block;
        padding: 8px 20px;
        background: white;
        border: 1px solid var(--highlight-color);
        border-radius: 25px;
        text-decoration: none;
        font-family: var(--primary-font) !important;
        font-size: 16px;
        font-weight: 500;
        color: #000;
        transition: all 0.3s ease;
    }

    .footer-contact-pill:hover {
        background: var(--highlight-color);
        color: white;
    }

    .copyright {
        color: #808080;
        font-size: 16px;
        font-family: var(--primary-font);
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

    .site-footer.over-full-bleed .copyright {
        color: white !important;
    }

    .site-footer.over-full-bleed .footer-contact-pill {
        background: rgba(255, 255, 255, 0.2) !important;
        color: white !important;
        border-color: white !important;
    }

    .site-footer.over-full-bleed .footer-contact-pill:hover {
        background: white !important;
        color: #000 !important;
    }

    /* Tablet Responsive - Show contact button in center */
    @media (max-width: 1200px) {
        .footer-contact-button {
            display: block;
        }
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .site-footer {
            padding: 3vw 4vw 2vw 4vw;
        }

        .footer-content {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }

        /* Move contact button to right on mobile */
        .footer-contact-button {
            position: static;
            transform: none;
            left: auto;
        }

        .footer-logo {
            width: 28px;
            height: 28px;
        }

        .copyright {
            display: none; /* Always hidden on mobile */
        }
        
        .footer-contact-pill {
            font-size: 14px; /* Smaller font size on mobile */
        }
    }
    </style>

    <script>
        // Set footer logo to black for story pages (no splash area)
        const footerLogo = document.getElementById('footer-logo-img');
        if (footerLogo && (document.body.classList.contains('single-story') || document.body.classList.contains('post-type-archive-story'))) {
            footerLogo.src = '/wp-content/uploads/2025/06/Reuben-J-Brown-logo-favicon-black.png';
        }
    </script>

    <?php wp_footer(); ?>
</body>
</html>