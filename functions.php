<?php
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'MB_VERSION', '2.2.0' );
define( 'MB_URI', get_template_directory_uri() );
define( 'MB_DIR', get_template_directory() );

// Contenu éditables — sections complètes
require_once MB_DIR . '/customizer-content.php';

/* ═══════════════════════════════════════════════
   1. SETUP
═══════════════════════════════════════════════ */
function mondobarry_setup() {
    // Internationalisation
    load_theme_textdomain( 'mondobarry', MB_DIR . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [
        'search-form', 'comment-form', 'comment-list',
        'gallery', 'caption', 'style', 'script',
    ]);
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'custom-logo', [
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    add_theme_support( 'custom-background', [
        'default-color' => '07090F',
    ]);

    // Menus
    register_nav_menus([
        'primary' => __( 'Menu principal', 'mondobarry' ),
    ]);
}
add_action( 'after_setup_theme', 'mondobarry_setup' );

/* ═══════════════════════════════════════════════
   2. ENQUEUE — polices chargées dynamiquement
      selon les réglages Customizer (conforme WP)
═══════════════════════════════════════════════ */
function mondobarry_enqueue() {
    $f_d   = get_theme_mod( 'mb_font_display', 'Geist' );
    $f_b   = get_theme_mod( 'mb_font_body',    'Geist' );
    $f_m   = get_theme_mod( 'mb_font_mono',    'Geist Mono' );

    // Construire la liste des familles à charger
    $families = [];

    // Geist et Geist Mono ont leur propre entrée Google Fonts
    $geist_needed      = in_array( 'Geist',      [ $f_d, $f_b ] );
    $geist_mono_needed = in_array( 'Geist Mono', [ $f_d, $f_b, $f_m ] );

    if ( $geist_needed )      $families[] = 'Geist:wght@100..900';
    if ( $geist_mono_needed ) $families[] = 'Geist+Mono:wght@100..900';

    // Autres polices (non-Geist)
    $other_fonts = array_unique( array_filter( [ $f_d, $f_b, $f_m ], function($f) {
        return ! in_array( $f, [ 'Geist', 'Geist Mono' ] );
    }));
    foreach ( $other_fonts as $font ) {
        $families[] = urlencode( $font ) . ':wght@300;400;500;600;700;800';
    }

    if ( ! empty( $families ) ) {
        $fonts_url = 'https://fonts.googleapis.com/css2?family='
            . implode( '&family=', $families )
            . '&display=swap';
        wp_enqueue_style( 'mondobarry-fonts', $fonts_url, [], null );
    }

    // CSS principal (sans @import — les fonts sont enqueued séparément)
    wp_enqueue_style(
        'mondobarry-main',
        MB_URI . '/assets/css/main.css',
        [ 'mondobarry-fonts' ],
        MB_VERSION
    );

    // JS principal
    wp_enqueue_script(
        'mondobarry-js',
        MB_URI . '/assets/js/main.js',
        [],
        MB_VERSION,
        true
    );

    // Passer variables JS
    wp_localize_script( 'mondobarry-js', 'mbAjax', [
        'url'   => admin_url( 'admin-ajax.php' ),
        'nonce' => wp_create_nonce( 'mb_contact_nonce' ),
    ]);
}
add_action( 'wp_enqueue_scripts', 'mondobarry_enqueue' );

/* ═══════════════════════════════════════════════
   3. CSS DYNAMIQUE — CSS vars depuis Customizer
      Appliqué APRÈS main.css pour surcharger
═══════════════════════════════════════════════ */
function mondobarry_dynamic_css() {
    $accent = get_theme_mod( 'mb_color_accent', '#3B82F6' );
    $bg     = get_theme_mod( 'mb_color_bg',     '#07090F' );
    $bg2    = get_theme_mod( 'mb_color_bg2',    '#0C101A' );
    $card   = get_theme_mod( 'mb_color_card',   '#0F1523' );
    $text   = get_theme_mod( 'mb_color_text',   '#E2E8F0' );
    $muted  = get_theme_mod( 'mb_color_muted',  '#94A3B8' );
    $green  = get_theme_mod( 'mb_color_green',  '#10B981' );
    $f_d    = get_theme_mod( 'mb_font_display', 'Geist' );
    $f_b    = get_theme_mod( 'mb_font_body',    'Geist' );
    $f_m    = get_theme_mod( 'mb_font_mono',    'Geist Mono' );
    $f_size = (int) get_theme_mod( 'mb_font_size', '16' );

    // CSS vars injectées — surchargent celles de main.css
    $css = "
    :root {
        --accent:  {$accent};
        --accent2: " . mondobarry_lighten( $accent ) . ";
        --bg:      {$bg};
        --bg2:     {$bg2};
        --card:    {$card};
        --card-h:  " . mondobarry_lighten( $card, 5 ) . ";
        --text:    {$text};
        --muted2:  {$muted};
        --green:   {$green};
        --font-d:  '{$f_d}', sans-serif;
        --font-b:  '{$f_b}', sans-serif;
        --font-m:  '{$f_m}', monospace;
    }
    html { font-size: {$f_size}px; }

    /* Forcer l'application des polices sur tous les éléments typographiques */
    h1, h2, h3, h4, h5, h6,
    .hero-title, .section-title, .contact-title,
    .skill-card h3, .project-card h3, .timeline-content h3,
    .infra-card h4, .nav-logo, .footer-logo,
    .section-tag, .project-tag, .timeline-date { font-family: var(--font-d); }

    body, p, a, span, li, input, textarea, button,
    .hero-sub, .section-desc, .skill-card p,
    .project-card p, .timeline-content p,
    .contact-desc, .footer-tagline, .footer-copy { font-family: var(--font-b); }

    .hero-mono, .terminal-body, .t-prompt, .t-out,
    .t-title, .skill-tags span, .project-stack span,
    .timeline-tags span, .infra-card p,
    .badge-dot ~ *, .hero-badge,
    code, pre { font-family: var(--font-m); }
    ";

    wp_add_inline_style( 'mondobarry-main', $css );
}
add_action( 'wp_enqueue_scripts', 'mondobarry_dynamic_css', 20 );

/* ─ Utilitaires couleur ─ */
function mondobarry_lighten( $hex, $amount = 30 ) {
    $hex = ltrim( $hex, '#' );
    if ( strlen( $hex ) === 3 ) {
        $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
    }
    return sprintf( '#%02x%02x%02x',
        min( 255, hexdec( substr($hex,0,2) ) + $amount ),
        min( 255, hexdec( substr($hex,2,2) ) + $amount ),
        min( 255, hexdec( substr($hex,4,2) ) + $amount )
    );
}

/* ═══════════════════════════════════════════════
   4. TRACKING — Facebook Pixel, GA, AdSense
      Injecté dans <head> uniquement si renseigné
═══════════════════════════════════════════════ */
function mondobarry_tracking_head() {

    // ── Google Analytics (GA4) ──
    $ga_id = sanitize_text_field( get_theme_mod( 'mb_ga_id', '' ) );
    if ( $ga_id ) : ?>
<!-- Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $ga_id ); ?>"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', '<?php echo esc_js( $ga_id ); ?>');
</script>
    <?php endif;

    // ── Google AdSense ──
    $adsense_id = sanitize_text_field( get_theme_mod( 'mb_adsense_id', '' ) );
    if ( $adsense_id ) : ?>
<!-- Google AdSense -->
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=<?php echo esc_attr( $adsense_id ); ?>" crossorigin="anonymous"></script>
    <?php endif;

    // ── Facebook Pixel ──
    $fb_pixel = sanitize_text_field( get_theme_mod( 'mb_fb_pixel', '' ) );
    if ( $fb_pixel ) : ?>
<!-- Facebook Pixel -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '<?php echo esc_js( $fb_pixel ); ?>');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=<?php echo esc_attr( $fb_pixel ); ?>&ev=PageView&noscript=1"/></noscript>
    <?php endif;

    // ── Google Tag Manager ──
    $gtm_id = sanitize_text_field( get_theme_mod( 'mb_gtm_id', '' ) );
    if ( $gtm_id ) : ?>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','<?php echo esc_js( $gtm_id ); ?>');</script>
    <?php endif;
}
add_action( 'wp_head', 'mondobarry_tracking_head', 1 );

// GTM noscript — dans le body
function mondobarry_tracking_body() {
    $gtm_id = sanitize_text_field( get_theme_mod( 'mb_gtm_id', '' ) );
    if ( $gtm_id ) {
        echo '<noscript><iframe src="https://www.googletagmanager.com/ns.html?id='
            . esc_attr( $gtm_id )
            . '" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>' . "\n";
    }
}
add_action( 'wp_body_open', 'mondobarry_tracking_body' );

/* ═══════════════════════════════════════════════
   5. CUSTOMIZER
═══════════════════════════════════════════════ */
function mondobarry_customizer( $wp_customize ) {

    $wp_customize->add_panel( 'mb_panel', [
        'title'    => '🎨 MondoBarry — Réglages',
        'priority' => 10,
    ]);

    /* ── POLICES ── */
    $wp_customize->add_section( 'mb_fonts', [
        'title' => '🔤 Polices',
        'panel' => 'mb_panel',
    ]);

    $google_fonts = [
        'Geist'             => 'Geist (Vercel — recommandé)',
        'Inter'             => 'Inter',
        'Space Grotesk'     => 'Space Grotesk',
        'Outfit'            => 'Outfit',
        'Plus Jakarta Sans' => 'Plus Jakarta Sans',
        'Syne'              => 'Syne',
        'DM Sans'           => 'DM Sans',
        'Manrope'           => 'Manrope',
        'Raleway'           => 'Raleway',
        'Poppins'           => 'Poppins',
    ];
    $mono_fonts = [
        'Geist Mono'      => 'Geist Mono (recommandé)',
        'DM Mono'         => 'DM Mono',
        'JetBrains Mono'  => 'JetBrains Mono',
        'Fira Code'       => 'Fira Code',
        'Source Code Pro' => 'Source Code Pro',
        'IBM Plex Mono'   => 'IBM Plex Mono',
    ];

    foreach ([
        'mb_font_display' => ['Police des titres (h1, h2…)',   'Geist',      $google_fonts],
        'mb_font_body'    => ['Police du corps de texte',       'Geist',      $google_fonts],
        'mb_font_mono'    => ['Police monospace (terminal, tags)', 'Geist Mono', $mono_fonts],
    ] as $id => [$label, $default, $choices]) {
        $wp_customize->add_setting( $id, [
            'default'           => $default,
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh', // refresh pour appliquer côté serveur
        ]);
        $wp_customize->add_control( $id, [
            'label'   => $label,
            'section' => 'mb_fonts',
            'type'    => 'select',
            'choices' => $choices,
        ]);
    }

    $wp_customize->add_setting( 'mb_font_size', [
        'default'           => '16',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control( 'mb_font_size', [
        'label'       => 'Taille de base (px)',
        'description' => 'Taille par défaut du texte (14–20px)',
        'section'     => 'mb_fonts',
        'type'        => 'range',
        'input_attrs' => [ 'min' => 14, 'max' => 20, 'step' => 1 ],
    ]);

    /* ── COULEURS ── */
    $wp_customize->add_section( 'mb_colors', [
        'title' => '🎨 Couleurs',
        'panel' => 'mb_panel',
    ]);

    foreach ([
        'mb_color_accent' => ['Couleur accent (bleu)',      '#3B82F6'],
        'mb_color_bg'     => ['Fond principal',             '#07090F'],
        'mb_color_bg2'    => ['Fond secondaire (sections)', '#0C101A'],
        'mb_color_card'   => ['Fond des cartes',            '#0F1523'],
        'mb_color_text'   => ['Couleur du texte',           '#E2E8F0'],
        'mb_color_muted'  => ['Texte secondaire',           '#94A3B8'],
        'mb_color_green'  => ['Couleur Live / Online',      '#10B981'],
    ] as $id => [$label, $default]) {
        $wp_customize->add_setting( $id, [
            'default'           => $default,
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        ]);
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $id, [
            'label'   => $label,
            'section' => 'mb_colors',
        ]));
    }

    /* ── HERO ── */
    $wp_customize->add_section( 'mb_hero', [
        'title' => '🚀 Section Hero',
        'panel' => 'mb_panel',
    ]);

    // Photo de profil
    $wp_customize->add_setting( 'mb_hero_photo', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'mb_hero_photo', [
        'label'       => 'Photo de profil',
        'description' => 'Médaillon circulaire au-dessus du titre',
        'section'     => 'mb_hero',
    ]));

    foreach ([
        'mb_hero_badge'    => ['Badge disponibilité',          'Disponible — Remote & sur site'],
        'mb_hero_title1'   => ['Titre — ligne 1',              'Chef de Projet'],
        'mb_hero_title2'   => ['Titre — ligne 2 (en bleu)',    'Digital &'],
        'mb_hero_title3'   => ['Titre — ligne 3',              'Développeur Web'],
        'mb_hero_subtitle' => ['Sous-titre (nom + ville)',     'Prénom Nom · Ville'],
        'mb_hero_stack'    => ['Stack technique (monospace)',  'WordPress · Angular · IA · DevOps · Admin Système'],
        'mb_hero_btn1'     => ['Bouton principal — texte',     'Voir mes projets'],
        'mb_hero_btn2'     => ['Bouton secondaire — texte',    'Me contacter'],
    ] as $id => [$label, $default]) {
        $wp_customize->add_setting( $id, [
            'default'           => $default,
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ]);
        $wp_customize->add_control( $id, [
            'label'   => $label,
            'section' => 'mb_hero',
            'type'    => 'text',
        ]);
    }

    // Pastilles de secteurs, terminal animé, texte de contact
    foreach ([
        'mb_hero_sectors'   => ['Pastilles secteurs (séparées par des virgules)', 'Web, Mobile, DevOps, IA', 'text'],
        'mb_terminal_name'  => ['Terminal — sortie de whoami',                    'Prénom Nom',              'text'],
        'mb_terminal_lines' => ['Terminal — compétences (une par ligne)',         "WordPress · PHP · JavaScript\nDocker · Linux\nCloudflare · Tailscale", 'textarea'],
        'mb_terminal_status'=> ['Terminal — statut',                              'Open to work — remote & on-site', 'text'],
    ] as $id => [$label, $default, $type]) {
        $wp_customize->add_setting( $id, [
            'default'           => $default,
            'sanitize_callback' => $type === 'textarea' ? 'sanitize_textarea_field' : 'sanitize_text_field',
            'transport'         => 'refresh',
        ]);
        $wp_customize->add_control( $id, [
            'label'   => $label,
            'section' => 'mb_hero',
            'type'    => $type,
        ]);
    }

    /* ── LIENS SOCIAUX ── */
    $wp_customize->add_section( 'mb_social', [
        'title' => '🔗 Liens sociaux',
        'panel' => 'mb_panel',
    ]);

    foreach ([
        'mb_social_email'    => ['Adresse email',  ''],
        'mb_social_linkedin' => ['URL LinkedIn',   ''],
        'mb_social_github'   => ['URL GitHub',     ''],
        'mb_social_site'     => ['URL site perso', ''],
    ] as $id => [$label, $default]) {
        $wp_customize->add_setting( $id, [
            'default'           => $default,
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ]);
        $wp_customize->add_control( $id, [
            'label'   => $label,
            'section' => 'mb_social',
            'type'    => 'text',
        ]);
    }

    /* ── SECTIONS VISIBLES ── */
    $wp_customize->add_section( 'mb_sections', [
        'title' => '👁 Sections visibles',
        'panel' => 'mb_panel',
    ]);

    foreach ([
        'mb_show_competences' => 'Afficher section Compétences',
        'mb_show_projets'     => 'Afficher section Projets',
        'mb_show_experience'  => 'Afficher section Expérience',
        'mb_show_infra'       => 'Afficher section Infrastructure',
        'mb_show_contact'     => 'Afficher section Contact',
    ] as $id => $label) {
        $wp_customize->add_setting( $id, [
            'default'           => true,
            'sanitize_callback' => 'mondobarry_sanitize_checkbox',
            'transport'         => 'refresh',
        ]);
        $wp_customize->add_control( $id, [
            'label'   => $label,
            'section' => 'mb_sections',
            'type'    => 'checkbox',
        ]);
    }

    /* ── FORMULAIRE DE CONTACT ── */
    $wp_customize->add_section( 'mb_contact', [
        'title' => '📬 Formulaire de contact',
        'panel' => 'mb_panel',
    ]);

    $wp_customize->add_setting( 'mb_contact_form', [
        'default'           => true,
        'sanitize_callback' => 'mondobarry_sanitize_checkbox',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control( 'mb_contact_form', [
        'label'       => 'Afficher le formulaire de contact',
        'description' => 'Formulaire email avec protection nonce WordPress',
        'section'     => 'mb_contact',
        'type'        => 'checkbox',
    ]);

    $wp_customize->add_setting( 'mb_contact_desc', [
        'default'           => 'Disponible pour des missions en remote ou sur site.',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control( 'mb_contact_desc', [
        'label'   => 'Texte d\'introduction du contact',
        'section' => 'mb_contact',
        'type'    => 'textarea',
    ]);

    foreach ([
        'mb_contact_email'   => ['Email de réception',       '', 'sanitize_email'],
        'mb_contact_title'   => ['Titre du formulaire',      'Envoyer un message',    'sanitize_text_field'],
        'mb_contact_success' => ['Message de confirmation',  'Merci ! Message envoyé.','sanitize_text_field'],
    ] as $id => [$label, $default, $sanitize]) {
        $wp_customize->add_setting( $id, [
            'default'           => $default,
            'sanitize_callback' => $sanitize,
            'transport'         => 'refresh',
        ]);
        $wp_customize->add_control( $id, [
            'label'   => $label,
            'section' => 'mb_contact',
            'type'    => $id === 'mb_contact_email' ? 'email' : 'text',
        ]);
    }

    /* ── TRACKING & ANALYTICS ── */
    $wp_customize->add_section( 'mb_tracking', [
        'title'       => '📊 Tracking & Analytics',
        'panel'       => 'mb_panel',
        'description' => 'Laissez vide les champs non utilisés. Les scripts ne sont injectés que si l\'ID est renseigné.',
    ]);

    foreach ([
        'mb_ga_id'      => [
            'label'       => 'Google Analytics 4 — Measurement ID',
            'description' => 'Format : G-XXXXXXXXXX',
            'default'     => '',
        ],
        'mb_gtm_id'     => [
            'label'       => 'Google Tag Manager — Container ID',
            'description' => 'Format : GTM-XXXXXXX (remplace GA si utilisé ensemble)',
            'default'     => '',
        ],
        'mb_adsense_id' => [
            'label'       => 'Google AdSense — Publisher ID',
            'description' => 'Format : ca-pub-XXXXXXXXXXXXXXXX',
            'default'     => '',
        ],
        'mb_fb_pixel'   => [
            'label'       => 'Facebook Pixel — Pixel ID',
            'description' => 'Format : numérique (ex: 1234567890)',
            'default'     => '',
        ],
    ] as $id => $args) {
        $wp_customize->add_setting( $id, [
            'default'           => $args['default'],
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ]);
        $wp_customize->add_control( $id, [
            'label'       => $args['label'],
            'description' => $args['description'],
            'section'     => 'mb_tracking',
            'type'        => 'text',
        ]);
    }
}
add_action( 'customize_register', 'mondobarry_customizer' );

/* ═══════════════════════════════════════════════
   6. SANITIZE CHECKBOX
═══════════════════════════════════════════════ */
function mondobarry_sanitize_checkbox( $val ) {
    return (bool) $val;
}

/* ═══════════════════════════════════════════════
   7. HELPER — raccourci pour index.php
═══════════════════════════════════════════════ */
function mb_get( $key, $default = '' ) {
    return get_theme_mod( $key, $default );
}

/* ═══════════════════════════════════════════════
   8. FORMULAIRE CONTACT — AJAX
═══════════════════════════════════════════════ */
function mondobarry_contact_handler() {
    check_ajax_referer( 'mb_contact_nonce', 'nonce' );

    $name    = sanitize_text_field(     $_POST['mb_name']    ?? '' );
    $email   = sanitize_email(          $_POST['mb_email']   ?? '' );
    $subject = sanitize_text_field(     $_POST['mb_subject'] ?? '' );
    $message = sanitize_textarea_field( $_POST['mb_message'] ?? '' );

    if ( empty($name) || empty($email) || empty($message) || ! is_email($email) ) {
        wp_send_json_error( 'Veuillez remplir tous les champs obligatoires.' );
        return;
    }

    // Honeypot anti-spam
    if ( ! empty( $_POST['mb_website'] ) ) {
        wp_send_json_error( 'Erreur.' );
        return;
    }

    $to      = get_theme_mod( 'mb_contact_email' ) ?: get_option( 'admin_email' );
    $subject = $subject ?: 'Message depuis ' . wp_parse_url( home_url(), PHP_URL_HOST ) . ' — ' . $name;
    $body    = "Nom : {$name}\nEmail : {$email}\n\n{$message}";
    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        "Reply-To: {$name} <{$email}>",
    ];

    if ( wp_mail( $to, $subject, $body, $headers ) ) {
        $success_msg = get_theme_mod( 'mb_contact_success', 'Merci ! Votre message a bien été envoyé.' );
        wp_send_json_success( $success_msg );
    } else {
        wp_send_json_error( "Erreur lors de l'envoi. Contactez-moi directement par email." );
    }
}
add_action( 'wp_ajax_mb_contact',        'mondobarry_contact_handler' );
add_action( 'wp_ajax_nopriv_mb_contact', 'mondobarry_contact_handler' );

/* ═══════════════════════════════════════════════
   9. CUSTOM POST TYPES
═══════════════════════════════════════════════ */
function mondobarry_register_cpt() {

    /* ── CPT : Projets ── */
    register_post_type( 'mb_projet', [
        'labels' => [
            'name'               => 'Projets',
            'singular_name'      => 'Projet',
            'add_new'            => 'Ajouter un projet',
            'add_new_item'       => 'Ajouter un nouveau projet',
            'edit_item'          => 'Modifier le projet',
            'new_item'           => 'Nouveau projet',
            'view_item'          => 'Voir le projet',
            'search_items'       => 'Rechercher un projet',
            'not_found'          => 'Aucun projet trouvé',
            'not_found_in_trash' => 'Aucun projet dans la corbeille',
            'menu_name'          => '💼 Projets',
        ],
        'public'        => false,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'menu_position' => 20,
        'menu_icon'     => 'dashicons-portfolio',
        'supports'      => [ 'title', 'editor', 'thumbnail', 'custom-fields', 'page-attributes' ],
        'show_in_rest'  => true, // support Gutenberg
        'rewrite'       => false,
    ]);

    /* ── CPT : Expériences ── */
    register_post_type( 'mb_experience', [
        'labels' => [
            'name'               => 'Expériences',
            'singular_name'      => 'Expérience',
            'add_new'            => 'Ajouter une expérience',
            'add_new_item'       => 'Ajouter une nouvelle expérience',
            'edit_item'          => 'Modifier l\'expérience',
            'new_item'           => 'Nouvelle expérience',
            'search_items'       => 'Rechercher une expérience',
            'not_found'          => 'Aucune expérience trouvée',
            'menu_name'          => '🗂 Expériences',
        ],
        'public'        => false,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'menu_position' => 21,
        'menu_icon'     => 'dashicons-businessman',
        'supports'      => [ 'title', 'editor', 'custom-fields', 'page-attributes' ],
        'show_in_rest'  => true,
        'rewrite'       => false,
    ]);
}
add_action( 'init', 'mondobarry_register_cpt' );

/* ── Meta boxes pour les champs custom ── */
function mondobarry_meta_boxes() {

    // Meta box Projet
    add_meta_box(
        'mb_projet_meta', '🔧 Détails du projet',
        'mondobarry_projet_meta_cb', 'mb_projet', 'normal', 'high'
    );

    // Meta box Expérience
    add_meta_box(
        'mb_experience_meta', '📅 Détails de l\'expérience',
        'mondobarry_experience_meta_cb', 'mb_experience', 'normal', 'high'
    );
}
add_action( 'add_meta_boxes', 'mondobarry_meta_boxes' );

function mondobarry_projet_meta_cb( $post ) {
    wp_nonce_field( 'mb_meta_nonce', 'mb_meta_nonce_field' );
    $tag    = get_post_meta( $post->ID, '_mb_projet_tag',    true );
    $stack  = get_post_meta( $post->ID, '_mb_projet_stack',  true );
    $url    = get_post_meta( $post->ID, '_mb_projet_url',    true );
    $status = get_post_meta( $post->ID, '_mb_projet_status', true );
    $feat   = get_post_meta( $post->ID, '_mb_projet_featured', true );
    ?>
    <table class="form-table" style="width:100%">
        <tr>
            <th><label>Tag / Catégorie</label></th>
            <td><input type="text" name="mb_projet_tag" value="<?php echo esc_attr($tag); ?>" class="widefat" placeholder="ex: CRM · Web App"></td>
        </tr>
        <tr>
            <th><label>Stack technique</label></th>
            <td><input type="text" name="mb_projet_stack" value="<?php echo esc_attr($stack); ?>" class="widefat" placeholder="ex: WordPress, PHP, MySQL (séparés par des virgules)"></td>
        </tr>
        <tr>
            <th><label>URL du projet</label></th>
            <td><input type="url" name="mb_projet_url" value="<?php echo esc_attr($url); ?>" class="widefat" placeholder="https://..."></td>
        </tr>
        <tr>
            <th><label>Statut</label></th>
            <td>
                <select name="mb_projet_status">
                    <option value="">— Aucun —</option>
                    <option value="live" <?php selected($status, 'live'); ?>>🟢 Live</option>
                    <option value="wip"  <?php selected($status, 'wip');  ?>>🟡 En cours</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label>Projet mis en avant ?</label></th>
            <td><label><input type="checkbox" name="mb_projet_featured" value="1" <?php checked($feat, '1'); ?>> Afficher en grand (2 colonnes)</label></td>
        </tr>
    </table>
    <?php
}

function mondobarry_experience_meta_cb( $post ) {
    wp_nonce_field( 'mb_meta_nonce', 'mb_meta_nonce_field' );
    $date  = get_post_meta( $post->ID, '_mb_exp_date',  true );
    $tags  = get_post_meta( $post->ID, '_mb_exp_tags',  true );
    ?>
    <table class="form-table">
        <tr>
            <th><label>Période</label></th>
            <td><input type="text" name="mb_exp_date" value="<?php echo esc_attr($date); ?>" class="widefat" placeholder="ex: 2020 — présent"></td>
        </tr>
        <tr>
            <th><label>Tags</label></th>
            <td><input type="text" name="mb_exp_tags" value="<?php echo esc_attr($tags); ?>" class="widefat" placeholder="ex: WordPress, Angular, Linux (séparés par des virgules)"></td>
        </tr>
    </table>
    <?php
}

/* ── Sauvegarde des meta boxes ── */
function mondobarry_save_meta( $post_id ) {
    if ( ! isset($_POST['mb_meta_nonce_field']) ) return;
    if ( ! wp_verify_nonce( $_POST['mb_meta_nonce_field'], 'mb_meta_nonce' ) ) return;
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $projet_fields = [
        'mb_projet_tag'      => '_mb_projet_tag',
        'mb_projet_stack'    => '_mb_projet_stack',
        'mb_projet_url'      => '_mb_projet_url',
        'mb_projet_status'   => '_mb_projet_status',
        'mb_projet_featured' => '_mb_projet_featured',
    ];
    foreach ( $projet_fields as $key => $meta_key ) {
        if ( isset($_POST[$key]) ) {
            update_post_meta( $post_id, $meta_key, sanitize_text_field($_POST[$key]) );
        } else {
            delete_post_meta( $post_id, $meta_key );
        }
    }

    $exp_fields = [
        'mb_exp_date' => '_mb_exp_date',
        'mb_exp_tags' => '_mb_exp_tags',
    ];
    foreach ( $exp_fields as $key => $meta_key ) {
        if ( isset($_POST[$key]) ) {
            update_post_meta( $post_id, $meta_key, sanitize_text_field($_POST[$key]) );
        }
    }
}
add_action( 'save_post', 'mondobarry_save_meta' );

/* ── Customizer : section Musique ── */
function mondobarry_customizer_music( $wp_customize ) {
    $wp_customize->add_section( 'mb_music', [
        'title'       => '🎵 Mélomane & Musique',
        'panel'       => 'mb_panel',
        'description' => 'Bloc musical affiché avant le footer',
    ]);

    $wp_customize->add_setting( 'mb_show_music', [
        'default'           => false,
        'sanitize_callback' => 'mondobarry_sanitize_checkbox',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control( 'mb_show_music', [
        'label'   => 'Afficher le bloc Musique',
        'section' => 'mb_music',
        'type'    => 'checkbox',
    ]);

    $wp_customize->add_setting( 'mb_music_title', [
        'default'           => 'Mélomane & Producteur',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control( 'mb_music_title', [
        'label'   => 'Titre de la section',
        'section' => 'mb_music',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting( 'mb_music_desc', [
        'default'           => 'En dehors du code, je produis de la musique.',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control( 'mb_music_desc', [
        'label'   => 'Description',
        'section' => 'mb_music',
        'type'    => 'textarea',
    ]);

    $wp_customize->add_setting( 'mb_music_spotify', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control( 'mb_music_spotify', [
        'label'       => 'Lien Spotify',
        'description' => 'URL de ton profil artiste Spotify',
        'section'     => 'mb_music',
        'type'        => 'url',
    ]);

    $wp_customize->add_setting( 'mb_music_genres', [
        'default'           => 'Afrobeat · Electronic · Ambient',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control( 'mb_music_genres', [
        'label'       => 'Genres / tags musicaux',
        'description' => 'Séparés par · (ex: Afrobeat · Electronic · Ambient)',
        'section'     => 'mb_music',
        'type'        => 'text',
    ]);
}
add_action( 'customize_register', 'mondobarry_customizer_music' );

/* ═══════════════════════════════════════════════
   10. NETTOYAGE <HEAD>
═══════════════════════════════════════════════ */
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
