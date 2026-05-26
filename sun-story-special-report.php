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
    echo '<link rel="stylesheet" href="' . $base . '/story-templates/sun-story-special-report.css?v=' . $v . '">' . "\n";
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
    $external_url     = get_field('external_url');
?>

<!-- Hero -->
<section class="sun-sr-hero">
    <div class="sun-sr-hero-inner">
        <?php if ($special_report) : ?>
            <p class="sun-sr-kicker"><?php echo esc_html($special_report); ?></p>
        <?php endif; ?>

        <?php if ($long_headline) : ?>
            <h1 class="sun-sr-headline"><?php echo esc_html($long_headline); ?></h1>
        <?php endif; ?>

        <?php if (has_post_thumbnail()) : ?>
            <div class="sun-sr-hero-image">
                <?php the_post_thumbnail('full'); ?>
            </div>
        <?php endif; ?>

        <?php if (has_excerpt()) : ?>
            <div class="sun-sr-excerpt"><?php the_excerpt(); ?></div>
        <?php endif; ?>

        <hr class="sun-sr-divider">

        <div class="sun-sr-index">
            <div class="sun-sr-index-left">
                <?php if ($specific_date) : ?>
                    <span><?php echo esc_html($specific_date); ?></span>
                    <span class="sun-sr-dot">⋅</span>
                <?php endif; ?>
                <?php if ($reading_time) : ?>
                    <span><?php echo esc_html($reading_time); ?></span>
                    <span class="sun-sr-dot">⋅</span>
                <?php endif; ?>
                <a href="#" class="sun-sr-share">Share ↗</a>
            </div>
            <div class="sun-sr-index-right">
                <?php if ($medium) : ?>
                    <?php echo esc_html($medium); ?> for
                <?php endif; ?>
                <?php if ($publication && $external_url) : ?>
                    <a href="<?php echo esc_url($external_url); ?>" target="_blank" rel="noopener"><?php echo esc_html($publication); ?> →</a>
                <?php elseif ($publication) : ?>
                    <?php echo esc_html($publication); ?> →
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Body -->
<section class="sun-sr-body">
    <div class="story-single-container">
        <div class="story-content-wrapper">
            <article class="story-content">
                <div class="story-content-inner">
                    <?php the_content(); ?>
                </div>
            </article>
        </div>
    </div>
</section>

<!-- Bottom -->
<section class="sun-sr-bottom">
    <div class="sun-sr-bottom-inner">
        <hr class="sun-sr-divider">
        <p class="sun-sr-bottom-kicker">More in <a href="/stories/cracking-the-sun">Cracking the Sun↗</a></p>
        <div class="sun-sr-bottom-stories">
            <?php if (have_rows('more_in_special_report')) :
                while (have_rows('more_in_special_report')) : the_row();
                    $h = get_sub_field('headline');
                    $u = get_sub_field('url');
                    if (!$h) continue;
            ?>
                <a href="<?php echo esc_url($u); ?>" class="sr-more-stories"><?php echo esc_html($h); ?></a>
            <?php endwhile; endif; ?>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const share = document.querySelector('.sun-sr-share');
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

    const storyNav = document.createElement('a');
    storyNav.href = '/';
    storyNav.className = 'story-header-nav';
    storyNav.textContent = '← Home';
    header.appendChild(storyNav);

    const contactButton = document.createElement('a');
    contactButton.href = '/#contact';
    contactButton.className = 'story-header-contact';
    contactButton.textContent = 'contact →';
    header.appendChild(contactButton);
});
</script>

<?php endwhile; endif; ?>

<?php get_footer('branded'); ?>
