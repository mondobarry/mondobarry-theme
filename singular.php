<?php get_header(); ?>

<main id="mb-main" style="padding-top:var(--nav-h);">
  <div class="container" style="max-width:780px; margin:0 auto; padding:60px 40px;">
    <?php while ( have_posts() ) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <header style="margin-bottom:40px; padding-bottom:32px; border-bottom:1px solid var(--border);">
        <span style="font-family:var(--font-m); font-size:.75rem; color:var(--accent); letter-spacing:.1em; text-transform:uppercase;">
          <?php echo get_post_type() === 'page' ? 'Page' : get_the_date(); ?>
        </span>
        <h1 style="font-family:var(--font-d); font-size:clamp(1.8rem,3.5vw,2.8rem); font-weight:800; letter-spacing:-.03em; margin-top:12px; line-height:1.1;">
          <?php the_title(); ?>
        </h1>
      </header>
      <div class="entry-content" style="color:var(--muted2); line-height:1.8; font-size:1rem;">
        <?php the_content(); ?>
      </div>
    </article>
    <?php endwhile; ?>
    <div style="margin-top:48px; padding-top:32px; border-top:1px solid var(--border);">
      <a href="<?php echo esc_url( home_url('/') ); ?>" style="color:var(--accent); font-weight:600;">← Retour à l'accueil</a>
    </div>
  </div>
</main>

<?php get_footer(); ?>
