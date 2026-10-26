**English** · [Français](README.fr.md)

# MondoBarry Portfolio — WordPress theme

Dark one-page WordPress theme for developer or sysadmin portfolios.
All content is set from **Appearance → Customize**, without touching the code.

![Preview](screenshot.png)

Live demo: [mondobarry.com](https://www.mondobarry.com/en/)

## Features

- **Hero** with an animated terminal (`whoami`, skills, status) and configurable sector pills
- **Sections** Skills, Projects, Experience (timeline), Infrastructure, Contact, Music — each one can be turned on or off
- **Custom post types** `Projects` and `Experiences` with meta boxes (stack, URL, live / in-progress status, featured); falls back to the Customizer settings when empty
- **Typography**: 10 Google Fonts + 6 monospace fonts, loaded dynamically according to the choice
- **Colours** through CSS variables injected from the Customizer
- **AJAX contact form**: WordPress nonce, sanitisation, anti-spam honeypot, sending through `wp_mail()`
- **Optional tracking**: GA4, Google Tag Manager, AdSense, Facebook Pixel (only injected when an ID is set)
- `<head>` clean-up (generator, RSD, WLW, shortlink)

## Installation

1. Copy the folder to `wp-content/themes/mondobarry-theme`
2. Activate the theme in **Appearance → Themes**
3. Customise it in **Appearance → Customize → 🎨 MondoBarry — Réglages**

Requirements: WordPress 6.0+, PHP 8.0+.

## Structure

```
functions.php            Setup, enqueue, dynamic CSS, Customizer, AJAX, CPT
customizer-content.php   Editable section content
index.php                One-page layout
singular.php / 404.php   Posts, pages, error
assets/css/main.css      Styles
assets/js/main.js        Scroll reveal, mobile menu, AJAX form
```

## Author

**Mamadou Alimou Barry** — WordPress developer since 2014 · [mondobarry.com/en](https://www.mondobarry.com/en/)

## License

GPL v2 or later — see [LICENSE](LICENSE).
