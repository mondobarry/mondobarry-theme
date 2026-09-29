<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="<?php echo esc_attr( get_bloginfo( 'description' ) ); ?>">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<nav id="mb-nav">
  <a href="<?php echo home_url('/'); ?>" class="nav-logo">
    <span class="logo-mondo">Mondo</span><span class="logo-barry">Barry</span>
    <span class="logo-cursor">_</span>
  </a>
  <div class="nav-links" id="nav-links">
    <a href="#competences">Compétences</a>
    <a href="#projets">Projets</a>
    <a href="#experience">Expérience</a>
    <a href="#infra">Infrastructure</a>
    <a href="#contact" class="nav-cta">Me contacter</a>
  </div>
  <button class="nav-hamburger" id="hamburger" aria-label="Menu">
    <span></span><span></span><span></span>
  </button>
</nav>
