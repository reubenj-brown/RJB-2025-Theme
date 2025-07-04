# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a two-part WordPress portfolio system for Reuben J. Brown, a multimedia journalist. The system consists of:

1. **RJB-2025-Theme** (this repository): A WordPress child theme built on Astra
2. **RJB-2025-portfolio-plugin**: A custom WordPress plugin providing shortcodes for portfolio sections

Both repositories are cloned locally:
- Theme: `/Users/reubenj.brown/RJB-2025-Theme/`
- Plugin: `/Users/reubenj.brown/RJB-2025-portfolio-plugin/`

## Architecture

### Theme Structure (This Repository)
- **Base Theme**: Child theme of Astra WordPress theme
- **Primary Template**: `page-portfolio.php` - Custom full-page portfolio template that bypasses most WordPress theme elements
- **Test Template**: `test-page.php` - Simplified template for testing shortcodes and sections
- **Custom Styling**: Inline CSS in templates with custom font loading and responsive design

### Plugin Structure (Companion Repository)
- **Main Plugin File**: `reuben-portfolio-sections.php` - Defines shortcodes and portfolio functionality
- **Assets Directory**: Contains modular CSS files for each section
- **Templates Directory**: PHP template files for rendering portfolio sections
- **Modular Design**: Each section has its own CSS and PHP template file

## File Structure

### Theme Files (This Repository)
```
RJB-2025-Theme/
├── fonts/                          # Custom font files
│   ├── InnovatorGrotesk-Regular.woff2
│   ├── InnovatorGrotesk-RegularItalic.woff2
│   ├── InnovatorGrotesk-SemiBold.woff2
│   └── InnovatorGrotesk-SemiBoldItalic.woff2
├── functions.php                    # WordPress theme functions
├── page-portfolio.php               # Main portfolio template
├── style.css                        # Theme stylesheet header
├── test-page.php                    # Test template
└── README.md                        # Basic project description
```

### Plugin Files (Companion Repository)
```
RJB-2025-portfolio-plugin/
├── assets/                          # Modular CSS files
│   ├── base-sections.css            # Shared styles for all sections
│   ├── about-section.css            # About section specific styles
│   ├── writing-section.css          # Writing section specific styles
│   ├── strategy-section.css         # Strategy section specific styles
│   └── cv-section.css               # CV section specific styles
├── templates/                       # PHP template files
│   ├── about-section.php            # About section HTML structure
│   ├── writing-section.php          # Writing section HTML structure
│   ├── strategy-section.php         # Strategy section HTML structure
│   └── cv-section.php               # CV section HTML structure
├── reuben-portfolio-sections.php    # Main plugin file with shortcode definitions
└── README.md                        # Plugin description
```

## Development Commands

### WordPress Development Setup
1. **Local WordPress Installation**: Set up a local WordPress environment
2. **Install Astra Theme**: Install and activate the Astra parent theme
3. **Install Child Theme**: Copy theme files to WordPress themes directory and activate
4. **Install Plugin**: Copy plugin files to WordPress plugins directory and activate
5. **Create Pages**: Create pages using the "Portfolio Page" or "Test Page" templates

### Version Control
Both repositories use Git:
```bash
# Theme repository
cd /Users/reubenj.brown/RJB-2025-Theme
git status
git add .
git commit -m "commit message"

# Plugin repository  
cd /Users/reubenj.brown/RJB-2025-portfolio-plugin
git status
git add .
git commit -m "commit message"
```

## Shortcodes System

The plugin provides these shortcodes for dynamic content:
- `[reuben_about]` - About section with photo and bio
- `[reuben_writing]` - Writing portfolio section
- `[reuben_photography]` - Photography portfolio section
- `[reuben_strategy]` - Strategy section
- `[reuben_cv]` - CV/Resume section

### Shortcode Implementation
- Each shortcode renders via `templates/*.php` files
- Styling is handled by corresponding `assets/*.css` files
- Base styles are shared via `assets/base-sections.css`
- CSS is conditionally loaded only on portfolio pages

## Key Features

### Fonts
- **Innovator Grotesk**: Local font files stored in theme `/fonts/` directory
- **Legitima**: Adobe Fonts (loaded via typekit.net)

### Design System
CSS custom properties defined in `:root`:
- `--highlight-color: #39e58f`
- `--primary-font: 'Innovator Grotesk', [fallbacks]`
- `--serif-font: 'Legitima', [fallbacks]`

### Portfolio Template Features
- Full-screen image carousel background with 5 rotating images
- Fixed transparent header that transforms on scroll
- Smooth scroll navigation between sections
- Responsive design with mobile-specific optimizations
- Footer with social links and logo that adapts to scroll position

## Template Usage

### Portfolio Page Template
- Creates a full-screen, single-page portfolio layout
- Bypasses most WordPress theme elements
- Uses hardcoded image URLs for carousel background
- Includes custom JavaScript for carousel and scroll behavior
- Renders shortcodes in main content area

### Test Page Template
- Simplified template for testing shortcodes
- Maintains WordPress structure but with custom styling
- Useful for development and testing individual sections

## External Dependencies

- **Astra Theme**: Parent theme (must be installed)
- **Adobe Fonts**: Legitima font family
- **Images**: Carousel and content images hosted on `skyblue-mongoose-220265.hostingersite.com`

## Development Workflow

### Making Changes
1. **Theme Changes**: Edit files in `/Users/reubenj.brown/RJB-2025-Theme/`
2. **Content Changes**: Edit template files in `/Users/reubenj.brown/RJB-2025-portfolio-plugin/templates/`
3. **Styling Changes**: Edit CSS files in `/Users/reubenj.brown/RJB-2025-portfolio-plugin/assets/`
4. **Functionality Changes**: Edit plugin main file `reuben-portfolio-sections.php`

### Testing
- Use the Test Page template for isolated section testing
- Use the Portfolio Page template for full site testing
- CSS changes are conditionally loaded only on portfolio pages

## Customization Notes

### Theme Customization
- Font files should be updated in `/fonts/` directory and corresponding `@font-face` declarations
- Color scheme can be modified via CSS custom properties
- Image carousel backgrounds are hardcoded in `page-portfolio.php` lines 182-186
- Social links and footer content are hardcoded in templates

### Plugin Customization
- Add new sections by creating new template and CSS files
- Register new shortcodes in the main plugin file
- All sections follow the same pattern: shortcode → template → CSS
- Base styles are shared across all sections

## WordPress Integration

The system integrates with WordPress through:
- Standard WordPress template hierarchy
- WordPress enqueue system for styles and scripts
- Custom post templates via Template Name headers
- WordPress shortcode system for dynamic content sections
- Child theme architecture extending Astra parent theme
- Plugin architecture for modular content management