<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/* ═══════════════════════════════════════════════════════════════
   CUSTOMIZER — CONTENU ÉDITABLES
   Toutes les sections : Compétences, Projets, Expériences,
   Infrastructure, Footer
   Appelé via add_action('customize_register') dans functions.php
═══════════════════════════════════════════════════════════════ */
function mondobarry_customizer_content( $wp_customize ) {

    /* ════════════════════════════════════════════
       SECTION COMPÉTENCES
    ════════════════════════════════════════════ */
    $wp_customize->add_section( 'mb_competences', [
        'title'       => '⚡ Compétences',
        'panel'       => 'mb_panel',
        'description' => 'Modifiez le titre, la description et les 6 cartes de compétences.',
    ]);

    // En-tête section
    foreach ([
        'mb_comp_tag'   => ['Tag de section',   '01 · Skills'],
        'mb_comp_title' => ['Titre principal',   'Compétences'],
        'mb_comp_desc'  => ['Sous-titre',        'Des compétences construites sur le terrain, amplifiées par l\'IA.'],
    ] as $id => [$label, $default]) {
        $wp_customize->add_setting( $id, [
            'default' => $default, 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'refresh',
        ]);
        $wp_customize->add_control( $id, [
            'label' => $label, 'section' => 'mb_competences', 'type' => 'text',
        ]);
    }

    // 6 cartes de compétences
    $comp_defaults = [
        1 => ['⚡', 'Gestion de Projet',     'Pilotage de projets web et mobile de bout en bout.',                 'Agile, Scrum'],
        2 => ['🌐', 'Développement Web',      'Thèmes et plugins WordPress sur mesure, applications web.',          'WordPress, PHP, JavaScript'],
        3 => ['🤖', 'IA',                     'Intégration d\'outils IA dans les workflows de développement.',      'LLM, Automatisation'],
        4 => ['🖥️', 'Admin Système & DevOps', 'Serveurs Linux, conteneurs Docker, supervision.',                    'Linux, Docker'],
        5 => ['🎨', 'UI/UX & Design',         'Conception d\'interfaces et identité visuelle.',                     'UI/UX, Figma'],
        6 => ['📡', 'Réseaux',                'Infrastructure réseau et télécoms.',                                  'Réseaux'],
    ];

    foreach ( $comp_defaults as $n => [$icon, $title, $desc, $tags] ) {
        $wp_customize->add_setting( "mb_comp_{$n}_icon",  [ 'default' => $icon,  'sanitize_callback' => 'sanitize_text_field', 'transport' => 'refresh' ]);
        $wp_customize->add_setting( "mb_comp_{$n}_title", [ 'default' => $title, 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'refresh' ]);
        $wp_customize->add_setting( "mb_comp_{$n}_desc",  [ 'default' => $desc,  'sanitize_callback' => 'sanitize_textarea_field', 'transport' => 'refresh' ]);
        $wp_customize->add_setting( "mb_comp_{$n}_tags",  [ 'default' => $tags,  'sanitize_callback' => 'sanitize_text_field', 'transport' => 'refresh' ]);

        $wp_customize->add_control( "mb_comp_{$n}_icon",  [ 'label' => "Carte {$n} — Icône (emoji)", 'section' => 'mb_competences', 'type' => 'text' ]);
        $wp_customize->add_control( "mb_comp_{$n}_title", [ 'label' => "Carte {$n} — Titre",         'section' => 'mb_competences', 'type' => 'text' ]);
        $wp_customize->add_control( "mb_comp_{$n}_desc",  [ 'label' => "Carte {$n} — Description",   'section' => 'mb_competences', 'type' => 'textarea' ]);
        $wp_customize->add_control( "mb_comp_{$n}_tags",  [ 'label' => "Carte {$n} — Tags (virgules)", 'section' => 'mb_competences', 'type' => 'text', 'description' => 'ex: WordPress, PHP, MySQL' ]);
    }

    /* ════════════════════════════════════════════
       SECTION PROJETS (fallback — si pas de CPT)
    ════════════════════════════════════════════ */
    $wp_customize->add_section( 'mb_projets_content', [
        'title'       => '💼 Projets — Textes',
        'panel'       => 'mb_panel',
        'description' => 'Modifiez l\'en-tête de la section Projets. Pour les projets eux-mêmes, utilisez 💼 Projets dans le menu.',
    ]);

    foreach ([
        'mb_proj_tag'   => ['Tag de section', '02 · Work'],
        'mb_proj_title' => ['Titre',           'Projets'],
        'mb_proj_desc'  => ['Sous-titre',      'Réalisations concrètes, déployées en production.'],
    ] as $id => [$label, $default]) {
        $wp_customize->add_setting( $id, [
            'default' => $default, 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'refresh',
        ]);
        $wp_customize->add_control( $id, [
            'label' => $label, 'section' => 'mb_projets_content', 'type' => 'text',
        ]);
    }

    // 6 projets fallback (affichés si aucun CPT créé)
    $proj_defaults = [
        1 => ['Web App',  'live', 'Projet exemple 1', 'Décrivez ici un projet mis en avant.', 'WordPress, PHP', '', '1'],
        2 => ['Mobile',   'wip',  'Projet exemple 2', 'Décrivez ici un deuxième projet.',     'Ionic, Angular', '', '0'],
        3 => ['',         '',     '',                 '',                                     '',               '', '0'],
        4 => ['',         '',     '',                 '',                                     '',               '', '0'],
        5 => ['',         '',     '',                 '',                                     '',               '', '0'],
        6 => ['',         '',     '',                 '',                                     '',               '', '0'],
    ];

    foreach ( $proj_defaults as $n => [$tag, $status, $title, $desc, $stack, $url, $feat] ) {
        foreach ([
            "mb_proj_{$n}_tag"      => [$tag,    'sanitize_text_field',    "Projet {$n} — Tag"],
            "mb_proj_{$n}_status"   => [$status,  'sanitize_text_field',   "Projet {$n} — Statut (live / wip / vide)"],
            "mb_proj_{$n}_title"    => [$title,  'sanitize_text_field',    "Projet {$n} — Titre"],
            "mb_proj_{$n}_desc"     => [$desc,   'sanitize_textarea_field',"Projet {$n} — Description"],
            "mb_proj_{$n}_stack"    => [$stack,  'sanitize_text_field',    "Projet {$n} — Stack (virgules)"],
            "mb_proj_{$n}_url"      => [$url,    'esc_url_raw',            "Projet {$n} — URL"],
            "mb_proj_{$n}_featured" => [$feat,   'sanitize_text_field',    "Projet {$n} — Mis en avant (1 ou 0)"],
        ] as $id => [$default, $sanitize, $label]) {
            $wp_customize->add_setting( $id, [
                'default' => $default, 'sanitize_callback' => $sanitize, 'transport' => 'refresh',
            ]);
            $wp_customize->add_control( $id, [
                'label' => $label, 'section' => 'mb_projets_content',
                'type'  => str_contains($id, '_desc') ? 'textarea' : 'text',
            ]);
        }
    }

    /* ════════════════════════════════════════════
       SECTION EXPÉRIENCE
    ════════════════════════════════════════════ */
    $wp_customize->add_section( 'mb_exp_content', [
        'title'       => '🗂 Expérience — Textes',
        'panel'       => 'mb_panel',
        'description' => 'Modifiez l\'en-tête et les 3 entrées de la timeline.',
    ]);

    foreach ([
        'mb_exp_tag'   => ['Tag de section', '03 · Journey'],
        'mb_exp_title' => ['Titre',           'Expérience'],
    ] as $id => [$label, $default]) {
        $wp_customize->add_setting( $id, [
            'default' => $default, 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'refresh',
        ]);
        $wp_customize->add_control( $id, [
            'label' => $label, 'section' => 'mb_exp_content', 'type' => 'text',
        ]);
    }

    $exp_defaults = [
        1 => ['2020 — présent', 'Poste actuel — Entreprise', 'Décrivez vos missions.',  'Compétence 1, Compétence 2'],
        2 => ['2018 — 2020',    'Poste précédent',           'Décrivez vos missions.',  'Compétence 1'],
        3 => ['2017',           'Diplôme — École',           'Décrivez la formation.',  ''],
    ];

    foreach ( $exp_defaults as $n => [$date, $title, $desc, $tags] ) {
        foreach ([
            "mb_exp_{$n}_date"  => [$date,  'sanitize_text_field',    "Exp. {$n} — Période"],
            "mb_exp_{$n}_title" => [$title, 'sanitize_text_field',    "Exp. {$n} — Titre / Poste"],
            "mb_exp_{$n}_desc"  => [$desc,  'sanitize_textarea_field',"Exp. {$n} — Description"],
            "mb_exp_{$n}_tags"  => [$tags,  'sanitize_text_field',    "Exp. {$n} — Tags (virgules)"],
        ] as $id => [$default, $sanitize, $label]) {
            $wp_customize->add_setting( $id, [
                'default' => $default, 'sanitize_callback' => $sanitize, 'transport' => 'refresh',
            ]);
            $wp_customize->add_control( $id, [
                'label' => $label, 'section' => 'mb_exp_content',
                'type'  => str_contains($id, '_desc') ? 'textarea' : 'text',
            ]);
        }
    }

    /* ════════════════════════════════════════════
       SECTION INFRASTRUCTURE
    ════════════════════════════════════════════ */
    $wp_customize->add_section( 'mb_infra_content', [
        'title'       => '🖥️ Infrastructure — Textes',
        'panel'       => 'mb_panel',
        'description' => 'Modifiez les 8 cartes de la section Homelab.',
    ]);

    foreach ([
        'mb_infra_tag'   => ['Tag de section', '04 · Homelab'],
        'mb_infra_title' => ['Titre',           'Infrastructure'],
        'mb_infra_desc'  => ['Sous-titre',      'Ma stack homelab — administration système en conditions réelles.'],
    ] as $id => [$label, $default]) {
        $wp_customize->add_setting( $id, [
            'default' => $default, 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'refresh',
        ]);
        $wp_customize->add_control( $id, [
            'label' => $label, 'section' => 'mb_infra_content', 'type' => 'text',
        ]);
    }

    $infra_defaults = [
        1 => ['📊', 'Monitoring',       'Prometheus + Grafana'],
        2 => ['🐳', 'Conteneurisation', 'Docker / Synology NAS'],
        3 => ['🌐', 'DNS & CDN',        'Pi-hole + Cloudflare'],
        4 => ['🔒', 'VPN Mesh',         'Tailscale'],
        5 => ['🔑', 'Passwords',        'Vaultwarden'],
        6 => ['🏠', 'Domotique',        'Home Assistant'],
        7 => ['🖥️', 'Serveur',          'VPS Linux dédié'],
        8 => ['☁️', 'Hébergement',      'Hébergeur'],
    ];

    foreach ( $infra_defaults as $n => [$icon, $title, $tech] ) {
        foreach ([
            "mb_infra_{$n}_icon"  => [$icon,  "Carte {$n} — Icône (emoji)"],
            "mb_infra_{$n}_title" => [$title, "Carte {$n} — Titre"],
            "mb_infra_{$n}_tech"  => [$tech,  "Carte {$n} — Technologie"],
        ] as $id => [$default, $label]) {
            $wp_customize->add_setting( $id, [
                'default' => $default, 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'refresh',
            ]);
            $wp_customize->add_control( $id, [
                'label' => $label, 'section' => 'mb_infra_content', 'type' => 'text',
            ]);
        }
    }

    /* ════════════════════════════════════════════
       FOOTER
    ════════════════════════════════════════════ */
    $wp_customize->add_section( 'mb_footer_content', [
        'title'       => '📋 Footer',
        'panel'       => 'mb_panel',
        'description' => 'Textes et liens du pied de page.',
    ]);

    foreach ([
        'mb_footer_logo'    => ['Logo texte (gauche)',      'MondoBarry'],
        'mb_footer_tagline' => ['Tagline sous le logo',     'Développeur Web · Admin Système'],
        'mb_footer_copy'    => ['Texte copyright',          '© ' . date('Y') . ' ' . get_bloginfo('name')],
        'mb_footer_link1_label' => ['Lien 1 — Texte',      'LinkedIn'],
        'mb_footer_link1_url'   => ['Lien 1 — URL',        ''],
        'mb_footer_link2_label' => ['Lien 2 — Texte',      'GitHub'],
        'mb_footer_link2_url'   => ['Lien 2 — URL',        ''],
        'mb_footer_link3_label' => ['Lien 3 — Texte',      'Email'],
        'mb_footer_link3_url'   => ['Lien 3 — URL',        ''],
    ] as $id => [$label, $default]) {
        $wp_customize->add_setting( $id, [
            'default' => $default,
            'sanitize_callback' => str_ends_with( $id, '_url' ) ? 'esc_url_raw' : 'sanitize_text_field',
            'transport' => 'refresh',
        ]);
        $wp_customize->add_control( $id, [
            'label' => $label, 'section' => 'mb_footer_content', 'type' => 'text',
        ]);
    }
}
add_action( 'customize_register', 'mondobarry_customizer_content' );
