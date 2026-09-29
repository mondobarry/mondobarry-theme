<?php get_header(); ?>

<main id="mb-main">
  <section id="hero" style="min-height:80vh; display:flex; align-items:center; justify-content:center; flex-direction:column; text-align:center;">
    <div class="hero-bg-grid"></div>
    <div style="position:relative; z-index:1;">
      <p style="font-family:var(--font-m); color:var(--accent); font-size:.85rem; letter-spacing:.12em; text-transform:uppercase; margin-bottom:16px;">404</p>
      <h1 style="font-family:var(--font-d); font-size:clamp(2rem,4vw,3.5rem); font-weight:800; letter-spacing:-.03em; margin-bottom:16px;">
        Page introuvable
      </h1>
      <p style="color:var(--muted2); margin-bottom:32px;">Cette page n'existe pas ou a été déplacée.</p>
      <a href="<?php echo esc_url( home_url('/') ); ?>" class="btn-primary">← Retour à l'accueil</a>
    </div>
  </section>
</main>

<?php get_footer(); ?>
