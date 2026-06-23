<?php
/**
 * Template Name: Sun Story — Special Report
 * Template Post Type: story
 *
 * Special Report story template (Cracking the Sun series).
 * Structure: Hero (gradient) → Body (cr-page) → Bottom (gradient).
 */

show_admin_bar(false);

add_action('wp_enqueue_scripts', function() {
    wp_dequeue_style('astra-theme-css');
    wp_deregister_style('astra-theme-css');
}, 100);

add_action('wp_head', function() {
    $v = wp_get_theme()->get('Version');
    $base = get_stylesheet_directory_uri();
    echo '<link rel="stylesheet" href="' . $base . '/story-templates/story-templates.css?v=' . $v . '">' . "\n";
    echo '<link rel="stylesheet" href="' . $base . '/story-templates/japan-story-special-report.css?v=' . $v . '">' . "\n";
    echo '<script src="' . $base . '/story-templates/story-templates.js?v=' . $v . '"></script>' . "\n";
}, 999);

get_header('branded'); ?>

<?php if (have_posts()) : while (have_posts()) : the_post();
    $special_report   = get_field('special_report');
    $long_headline    = get_field('long_headline');
    $specific_date    = get_field('specific_publish_date');
    $reading_time     = get_field('reading_time');
    $publication      = get_field('publication');
    $medium           = get_field('medium');
    $external_url              = get_field('external_url');
    $publication_external_url  = get_field('publication_external_url');
    $photo_credit     = get_field('photo_credit');
?>

<!-- Hero (story-hero-full-bleed class triggers the header-branded.php JS that
     toggles .over-full-bleed on .site-header, giving white nav text here) -->
<section class="sr-hero story-hero-full-bleed">
    <div class="sr-hero-inner">
        <?php if ($special_report) : ?>
            <p class="sr-kicker"><?php echo esc_html($special_report); ?></p>
        <?php endif; ?>

        <?php if ($long_headline) : ?>
            <p class="sr-headline"><?php echo esc_html($long_headline); ?></p>
        <?php endif; ?>

        <?php if (has_post_thumbnail()) : ?>
            <div class="sr-hero-image">
                <?php the_post_thumbnail('full'); ?>
            </div>
            <?php if ($photo_credit) : ?>
                <p class="story-image-credit sr-image-credit"><?php echo esc_html($photo_credit); ?></p>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (has_excerpt()) : ?>
            <div class="sr-excerpt"><?php the_excerpt(); ?></div>
        <?php endif; ?>

        <hr class="sr-divider">

        <div class="sr-index">
            <div class="sr-index-left">
                <?php if ($specific_date) : ?>
                    <span><?php echo esc_html($specific_date); ?></span>
                    <span class="sr-dot">⋅</span>
                <?php endif; ?>
                <?php if ($reading_time) : ?>
                    <span><?php echo esc_html($reading_time); ?></span>
                    <span class="sr-dot">⋅</span>
                <?php endif; ?>
                <a href="#" class="sr-share">Share ↑</a>
            </div>
            <div class="sr-index-right">
                <?php if ($medium) : ?>
                    <?php echo esc_html($medium); ?> for
                <?php endif; ?>
                <?php if ($publication && $publication_external_url) : ?>
                    <a href="<?php echo esc_url($publication_external_url); ?>" target="_blank" rel="noopener"><em><?php echo esc_html($publication); ?></em> →</a>
                <?php elseif ($publication) : ?>
                    <em><?php echo esc_html($publication); ?></em> →
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Body -->
<section class="sr-body">
    <div class="story-single-container">
        <div class="story-content-wrapper">
            <article class="story-content">
                <div class="story-content-inner">
                    <?php the_content(); ?>
                </div>
            </article>
        </div>
    </div>
    
    <hr class="sr-divider-bottom">
    
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const share = document.querySelector('.sr-share');
    if (!share) return;
    const originalText = share.textContent;

    share.addEventListener('click', async function(e) {
        e.preventDefault();
        const url = window.location.href;
        const title = document.title;

        if (navigator.share) {
            try {
                await navigator.share({ title, url });
            } catch (err) {
                // user cancelled or share failed silently
            }
            return;
        }

        if (navigator.clipboard && navigator.clipboard.writeText) {
            try {
                await navigator.clipboard.writeText(url);
                share.textContent = 'Link copied to clipboard!';
                setTimeout(function() { share.textContent = originalText; }, 4000);
            } catch (err) {
                // clipboard write failed; leave label alone
            }
        }
    });
});

// Replace header nav for this story template, matching other single-story templates
document.addEventListener('DOMContentLoaded', function() {
    const header = document.querySelector('.site-header');
    if (!header) return;
    const mainNav = header.querySelector('.main-nav');
    const contactPill = header.querySelector('.contact-pill');
    const siteTitle = header.querySelector('.site-title-name');

    if (mainNav) mainNav.remove();
    if (contactPill) contactPill.remove();

    if (siteTitle) {
        siteTitle.href = '#';
        siteTitle.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    const storyNavDesktop = document.createElement('a');
    storyNavDesktop.href = '/';
    storyNavDesktop.className = 'story-header-nav story-header-nav-desktop';
    storyNavDesktop.textContent = '← Home';
    header.appendChild(storyNavDesktop);

    const storyNavMobile = document.createElement('a');
    storyNavMobile.href = '/';
    storyNavMobile.className = 'story-header-nav story-header-nav-mobile';
    storyNavMobile.textContent = 'Home →';
    header.appendChild(storyNavMobile);

    const contactButton = document.createElement('a');
    contactButton.href = '/#contact';
    contactButton.className = 'story-header-contact';
    contactButton.textContent = 'contact →';
    header.appendChild(contactButton);
});

// Toggle .over-full-bleed on the footer while it overlaps the dark Bottom
// section, so the logo and copyright go white and the blur layers turn off.
document.addEventListener('DOMContentLoaded', function() {
    const footer = document.querySelector('.site-footer');
    const bottom = document.querySelector('.sr-bottom');
    if (!footer || !bottom) return;

    const logo = document.getElementById('footer-logo-img');
    const copyright = footer.querySelector('.copyright');
    const LOGO_WHITE = '/wp-content/uploads/2025/06/Reuben-J-Brown-logo-favicon-white.png';
    const LOGO_BLACK = '/wp-content/uploads/2025/06/Reuben-J-Brown-logo-favicon-black.png';

    function updateFooter() {
        const fRect = footer.getBoundingClientRect();
        const bRect = bottom.getBoundingClientRect();
        const overlaps = bRect.bottom > fRect.top && bRect.top < fRect.bottom;

        if (overlaps) {
            footer.classList.add('over-full-bleed');
            if (logo) logo.src = LOGO_WHITE;
            if (copyright) copyright.style.color = 'white';
        } else {
            footer.classList.remove('over-full-bleed');
            if (logo) logo.src = LOGO_BLACK;
            if (copyright) copyright.style.color = '';
        }
    }

    updateFooter();
    window.addEventListener('scroll', updateFooter, { passive: true });
    window.addEventListener('resize', updateFooter);
});
</script>

<?php endwhile; endif; ?>

<?php get_footer('branded'); ?>
