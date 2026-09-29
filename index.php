<?php get_header(); ?>

<main id="mb-main">

<!-- ═══ HERO ═══ -->
<section id="hero">
  <div class="hero-bg-grid"></div>
  <div class="hero-bg-glow"></div>
  <div class="hero-content">
    <?php $photo = mb_get('mb_hero_photo',''); if($photo): ?>
    <div class="hero-photo hero-photo--above reveal">
      <img src="<?php echo esc_url($photo); ?>" alt="<?php echo esc_attr(mb_get('mb_hero_subtitle', get_bloginfo('name'))); ?>" class="hero-avatar">
    </div>
    <?php endif; ?>
    <div class="hero-badge reveal"><span class="badge-dot"></span><?php echo esc_html(mb_get('mb_hero_badge','Disponible — Remote & sur site')); ?></div>
    <h1 class="hero-title reveal">
      <span class="title-line"><?php echo esc_html(mb_get('mb_hero_title1','Chef de Projet')); ?></span>
      <span class="title-line accent"><?php echo esc_html(mb_get('mb_hero_title2','Digital &')); ?></span>
      <span class="title-line"><?php echo esc_html(mb_get('mb_hero_title3','Développeur Web')); ?></span>
    </h1>
    <p class="hero-sub reveal">
      <?php echo esc_html(mb_get('mb_hero_subtitle','Prénom Nom · Ville')); ?><br>
      <span class="hero-mono"><?php echo esc_html(mb_get('mb_hero_stack','WordPress · Angular · IA · DevOps · Admin Système')); ?></span>
    </p>
    <div class="hero-sectors reveal">
      <?php
        $pill_colors = [ 'blue', 'green', 'purple', 'orange' ];
        $sectors = array_filter( array_map( 'trim', explode( ',', mb_get( 'mb_hero_sectors', 'Web, Mobile, DevOps, IA' ) ) ) );
        foreach ( array_values( $sectors ) as $i => $sector ) :
      ?>
      <span class="sector-pill pill-<?php echo esc_attr( $pill_colors[ $i % 4 ] ); ?>"><?php echo esc_html( $sector ); ?></span>
      <?php endforeach; ?>
    </div>
    <div class="hero-actions reveal">
      <a href="#projets" class="btn-primary"><?php echo esc_html(mb_get('mb_hero_btn1','Voir mes projets')); ?></a>
      <a href="#contact" class="btn-ghost"><?php echo esc_html(mb_get('mb_hero_btn2','Me contacter')); ?></a>
    </div>
  </div>
  <div class="hero-terminal reveal">
    <div class="terminal-header">
      <span class="t-dot red"></span><span class="t-dot orange"></span><span class="t-dot green"></span>
      <span class="t-title">mondobarry ~ portfolio</span>
    </div>
    <div class="terminal-body">
      <p><span class="t-prompt">$</span> whoami</p>
      <p class="t-out"><?php echo esc_html( mb_get( 'mb_terminal_name', 'Prénom Nom' ) ); ?></p>
      <p><span class="t-prompt">$</span> cat skills.txt</p>
      <?php foreach ( array_filter( array_map( 'trim', explode( "\n", mb_get( 'mb_terminal_lines', "WordPress · PHP · JavaScript\nDocker · Linux\nCloudflare · Tailscale" ) ) ) ) as $line ) : ?>
      <p class="t-out">→ <?php echo esc_html( $line ); ?></p>
      <?php endforeach; ?>
      <p><span class="t-prompt">$</span> echo $STATUS</p>
      <p class="t-out t-green">✓ <?php echo esc_html( mb_get( 'mb_terminal_status', 'Open to work — remote & on-site' ) ); ?></p>
      <p class="t-cursor"><span class="t-prompt">$</span> <span class="blink">▋</span></p>
    </div>
  </div>
</section>

<!-- ═══ COMPÉTENCES ═══ -->
<?php if(mb_get('mb_show_competences',true)): ?>
<section id="competences" class="section">
  <div class="container">
    <div class="section-header reveal">
      <span class="section-tag"><?php echo esc_html(mb_get('mb_comp_tag','01 · Skills')); ?></span>
      <h2 class="section-title"><?php echo esc_html(mb_get('mb_comp_title','Compétences')); ?></h2>
      <p class="section-desc"><?php echo esc_html(mb_get('mb_comp_desc','Des compétences construites sur le terrain, amplifiées par l\'IA.')); ?></p>
    </div>
    <div class="skills-grid">
      <?php for($n=1; $n<=6; $n++):
        $icon  = mb_get("mb_comp_{$n}_icon");
        $title = mb_get("mb_comp_{$n}_title");
        $desc  = mb_get("mb_comp_{$n}_desc");
        $tags  = mb_get("mb_comp_{$n}_tags");
        $tag_list = $tags ? array_map('trim', explode(',', $tags)) : [];
        if(!$icon && !$title) continue;
      ?>
      <div class="skill-card reveal">
        <div class="skill-icon"><?php echo esc_html($icon); ?></div>
        <h3><?php echo esc_html($title); ?></h3>
        <p><?php echo esc_html($desc); ?></p>
        <?php if(!empty($tag_list)): ?>
        <div class="skill-tags">
          <?php foreach($tag_list as $t): ?><span><?php echo esc_html(trim($t)); ?></span><?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
      <?php endfor; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ═══ PROJETS ═══ -->
<?php if(mb_get('mb_show_projets',true)):
  $cpt_projets = new WP_Query(['post_type'=>'mb_projet','posts_per_page'=>-1,'orderby'=>'menu_order','order'=>'ASC','post_status'=>'publish']);
  $use_cpt = $cpt_projets->have_posts();
?>
<section id="projets" class="section section-alt">
  <div class="container">
    <div class="section-header reveal">
      <span class="section-tag"><?php echo esc_html(mb_get('mb_proj_tag','02 · Work')); ?></span>
      <h2 class="section-title"><?php echo esc_html(mb_get('mb_proj_title','Projets')); ?></h2>
      <p class="section-desc"><?php echo esc_html(mb_get('mb_proj_desc','Réalisations concrètes, déployées en production.')); ?></p>
    </div>
    <div class="projects-grid">
    <?php if($use_cpt):
      while($cpt_projets->have_posts()): $cpt_projets->the_post();
        $tag    = get_post_meta(get_the_ID(),'_mb_projet_tag',true);
        $stack  = get_post_meta(get_the_ID(),'_mb_projet_stack',true);
        $url    = get_post_meta(get_the_ID(),'_mb_projet_url',true);
        $status = get_post_meta(get_the_ID(),'_mb_projet_status',true);
        $feat   = get_post_meta(get_the_ID(),'_mb_projet_featured',true);
        $stacks = $stack ? array_map('trim',explode(',',$stack)) : [];
    ?>
      <div class="project-card <?php echo $feat?'project-featured':''; ?> reveal">
        <div class="project-meta">
          <?php if($tag): ?><span class="project-tag"><?php echo esc_html($tag); ?></span><?php endif; ?>
          <?php if($status==='live'): ?><span class="project-status live">Live</span><?php endif; ?>
          <?php if($status==='wip'):  ?><span class="project-status wip">En cours</span><?php endif; ?>
        </div>
        <h3><?php the_title(); ?></h3>
        <p><?php echo wp_trim_words(get_the_excerpt()?:get_the_content(),30); ?></p>
        <?php if(!empty($stacks)): ?>
        <div class="project-stack"><?php foreach($stacks as $s): ?><span><?php echo esc_html($s); ?></span><?php endforeach; ?></div>
        <?php endif; ?>
        <?php if($url): ?><a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener" class="project-link">Voir le projet →</a><?php endif; ?>
      </div>
    <?php endwhile; wp_reset_postdata();
    else:
      for($n=1;$n<=6;$n++):
        $p_tag   = mb_get("mb_proj_{$n}_tag");
        $p_stat  = mb_get("mb_proj_{$n}_status");
        $p_title = mb_get("mb_proj_{$n}_title");
        $p_desc  = mb_get("mb_proj_{$n}_desc");
        $p_stack = mb_get("mb_proj_{$n}_stack");
        $p_url   = mb_get("mb_proj_{$n}_url");
        $p_feat  = mb_get("mb_proj_{$n}_featured");
        $p_stacks = $p_stack ? array_map('trim',explode(',',$p_stack)) : [];
        if(!$p_title) continue;
    ?>
      <div class="project-card <?php echo $p_feat==='1'?'project-featured':''; ?> reveal">
        <div class="project-meta">
          <?php if($p_tag): ?><span class="project-tag"><?php echo esc_html($p_tag); ?></span><?php endif; ?>
          <?php if($p_stat==='live'): ?><span class="project-status live">Live</span><?php endif; ?>
          <?php if($p_stat==='wip'):  ?><span class="project-status wip">En cours</span><?php endif; ?>
        </div>
        <h3><?php echo esc_html($p_title); ?></h3>
        <p><?php echo esc_html($p_desc); ?></p>
        <?php if(!empty($p_stacks)): ?>
        <div class="project-stack"><?php foreach($p_stacks as $s): ?><span><?php echo esc_html(trim($s)); ?></span><?php endforeach; ?></div>
        <?php endif; ?>
        <?php if($p_url): ?><a href="<?php echo esc_url($p_url); ?>" target="_blank" rel="noopener" class="project-link">Voir le projet →</a><?php endif; ?>
      </div>
    <?php endfor; endif; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ═══ EXPÉRIENCE ═══ -->
<?php if(mb_get('mb_show_experience',true)):
  $cpt_exp = new WP_Query(['post_type'=>'mb_experience','posts_per_page'=>-1,'orderby'=>'menu_order','order'=>'ASC','post_status'=>'publish']);
  $use_exp_cpt = $cpt_exp->have_posts();
?>
<section id="experience" class="section">
  <div class="container">
    <div class="section-header reveal">
      <span class="section-tag"><?php echo esc_html(mb_get('mb_exp_tag','03 · Journey')); ?></span>
      <h2 class="section-title"><?php echo esc_html(mb_get('mb_exp_title','Expérience')); ?></h2>
    </div>
    <div class="timeline">
    <?php if($use_exp_cpt):
      while($cpt_exp->have_posts()): $cpt_exp->the_post();
        $date = get_post_meta(get_the_ID(),'_mb_exp_date',true);
        $tags = get_post_meta(get_the_ID(),'_mb_exp_tags',true);
        $tag_list = $tags ? array_map('trim',explode(',',$tags)) : [];
    ?>
      <div class="timeline-item reveal">
        <div class="timeline-dot"></div>
        <div class="timeline-content">
          <?php if($date): ?><span class="timeline-date"><?php echo esc_html($date); ?></span><?php endif; ?>
          <h3><?php the_title(); ?></h3>
          <p><?php echo wp_trim_words(get_the_content(),40); ?></p>
          <?php if(!empty($tag_list)): ?>
          <div class="timeline-tags"><?php foreach($tag_list as $t): ?><span><?php echo esc_html($t); ?></span><?php endforeach; ?></div>
          <?php endif; ?>
        </div>
      </div>
    <?php endwhile; wp_reset_postdata();
    else:
      for($n=1;$n<=3;$n++):
        $e_date  = mb_get("mb_exp_{$n}_date");
        $e_title = mb_get("mb_exp_{$n}_title");
        $e_desc  = mb_get("mb_exp_{$n}_desc");
        $e_tags  = mb_get("mb_exp_{$n}_tags");
        $e_tag_list = $e_tags ? array_map('trim',explode(',',$e_tags)) : [];
        if(!$e_title) continue;
    ?>
      <div class="timeline-item reveal">
        <div class="timeline-dot"></div>
        <div class="timeline-content">
          <?php if($e_date): ?><span class="timeline-date"><?php echo esc_html($e_date); ?></span><?php endif; ?>
          <h3><?php echo esc_html($e_title); ?></h3>
          <p><?php echo esc_html($e_desc); ?></p>
          <?php if(!empty($e_tag_list)): ?>
          <div class="timeline-tags"><?php foreach($e_tag_list as $t): ?><span><?php echo esc_html(trim($t)); ?></span><?php endforeach; ?></div>
          <?php endif; ?>
        </div>
      </div>
    <?php endfor; endif; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ═══ INFRASTRUCTURE ═══ -->
<?php if(mb_get('mb_show_infra',true)): ?>
<section id="infra" class="section section-alt">
  <div class="container">
    <div class="section-header reveal">
      <span class="section-tag"><?php echo esc_html(mb_get('mb_infra_tag','04 · Homelab')); ?></span>
      <h2 class="section-title"><?php echo esc_html(mb_get('mb_infra_title','Infrastructure')); ?></h2>
      <p class="section-desc"><?php echo esc_html(mb_get('mb_infra_desc','Ma stack homelab — administration système en conditions réelles.')); ?></p>
    </div>
    <div class="infra-grid">
      <?php for($n=1;$n<=8;$n++):
        $i_icon  = mb_get("mb_infra_{$n}_icon");
        $i_title = mb_get("mb_infra_{$n}_title");
        $i_tech  = mb_get("mb_infra_{$n}_tech");
        if(!$i_title) continue;
      ?>
      <div class="infra-card reveal">
        <div class="infra-status online"></div>
        <div class="infra-icon"><?php echo esc_html($i_icon); ?></div>
        <h4><?php echo esc_html($i_title); ?></h4>
        <p><?php echo esc_html($i_tech); ?></p>
      </div>
      <?php endfor; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ═══ CONTACT ═══ -->
<?php if(mb_get('mb_show_contact',true)): ?>
<section id="contact" class="section">
  <div class="container">
    <div class="contact-block reveal">
      <span class="section-tag">05 · Contact</span>
      <h2 class="contact-title">Travaillons ensemble</h2>
      <p class="contact-desc"><?php echo nl2br( esc_html( mb_get( 'mb_contact_desc', 'Disponible pour des missions en remote ou sur site.' ) ) ); ?></p>
      <div class="contact-actions">
        <?php $email = mb_get('mb_social_email', ''); if ( $email ) : ?>
        <a href="mailto:<?php echo esc_attr( $email ); ?>" class="btn-primary btn-large">
          ✉ <?php echo esc_html( $email ); ?>
        </a>
        <?php endif; ?>
        <div class="contact-socials">
          <a href="<?php echo esc_url(mb_get('mb_social_linkedin','#')); ?>" target="_blank" rel="noopener" class="social-link">LinkedIn</a>
          <a href="<?php echo esc_url(mb_get('mb_social_github','#')); ?>" target="_blank" rel="noopener" class="social-link">GitHub</a>
          <?php $site = mb_get('mb_social_site', ''); if ( $site ) : ?>
          <a href="<?php echo esc_url( $site ); ?>" class="social-link"><?php echo esc_html( wp_parse_url( $site, PHP_URL_HOST ) ); ?></a>
          <?php endif; ?>
        </div>
      </div>
      <?php if(mb_get('mb_contact_form',true)): ?>
      <div class="contact-form-wrap">
        <h3 class="contact-form-title"><?php echo esc_html(mb_get('mb_contact_title','Envoyer un message')); ?></h3>
        <form id="mb-contact-form" class="contact-form" novalidate>
          <input type="text" name="mb_website" style="display:none" tabindex="-1" autocomplete="off">
          <div class="form-row">
            <div class="form-group"><label for="mb_name">Nom *</label><input type="text" id="mb_name" name="mb_name" placeholder="Votre nom" required></div>
            <div class="form-group"><label for="mb_email">Email *</label><input type="email" id="mb_email" name="mb_email" placeholder="votre@email.com" required></div>
          </div>
          <div class="form-group"><label for="mb_subject">Sujet</label><input type="text" id="mb_subject" name="mb_subject" placeholder="Objet de votre message"></div>
          <div class="form-group"><label for="mb_message">Message *</label><textarea id="mb_message" name="mb_message" rows="5" placeholder="Votre message..." required></textarea></div>
          <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('mb_contact_nonce'); ?>">
          <button type="submit" class="btn-primary btn-submit">
            <span class="btn-text">Envoyer →</span>
            <span class="btn-loading" style="display:none">Envoi en cours…</span>
          </button>
          <div id="mb-form-result" class="form-result" style="display:none"></div>
        </form>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ═══ MUSIQUE ═══ -->
<?php if(mb_get('mb_show_music',false)):
  $spotify    = mb_get('mb_music_spotify','');
  $genres     = mb_get('mb_music_genres','Afrobeat · Electronic · Ambient');
  $genre_list = array_map('trim',explode('·',$genres));
?>
<section id="music" class="section section-alt">
  <div class="container">
    <div class="music-block reveal">
      <div class="music-icon-wrap">
        <div class="music-icon">
          <svg width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 2C6.477 2 2 6.477 2 12C2 17.523 6.477 22 12 22C17.523 22 22 17.523 22 12C22 6.477 17.523 2 12 2Z" fill="#1DB954"/>
            <path d="M16.5 16.5C14.5 15.2 11.5 14.8 8 15.5C7.6 15.6 7.2 15.3 7.1 14.9C7 14.5 7.3 14.1 7.7 14C11.5 13.2 14.9 13.7 17.2 15.2C17.5 15.4 17.6 15.8 17.4 16.1C17.2 16.4 16.8 16.6 16.5 16.5Z" fill="white"/>
            <path d="M17.5 13.8C15.1 12.3 11.4 11.8 7.5 13C7 13.1 6.5 12.8 6.4 12.3C6.3 11.8 6.6 11.3 7.1 11.2C11.5 9.9 15.6 10.5 18.4 12.2C18.9 12.5 19 13.1 18.7 13.5C18.4 14 17.9 14.1 17.5 13.8Z" fill="white"/>
            <path d="M18.5 11C15.7 9.3 10.8 9.1 7.3 10.2C6.7 10.4 6.1 10 5.9 9.4C5.7 8.8 6.1 8.2 6.7 8C10.7 6.8 16.1 7 19.4 8.9C20 9.2 20.2 10 19.9 10.5C19.6 11.1 18.9 11.3 18.5 11Z" fill="white"/>
          </svg>
        </div>
        <div class="music-bars"><span></span><span></span><span></span><span></span><span></span></div>
      </div>
      <div class="music-content">
        <span class="section-tag">06 · Music</span>
        <h2 class="music-title"><?php echo esc_html(mb_get('mb_music_title','Mélomane & Producteur')); ?></h2>
        <p class="music-desc"><?php echo esc_html(mb_get('mb_music_desc','En dehors du code, je produis de la musique.')); ?></p>
        <div class="music-genres">
          <?php foreach($genre_list as $g): if(trim($g)): ?>
          <span class="music-genre"><?php echo esc_html(trim($g)); ?></span>
          <?php endif; endforeach; ?>
        </div>
        <?php if ( $spotify ) : ?>
        <a href="<?php echo esc_url($spotify); ?>" target="_blank" rel="noopener" class="btn-spotify">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.477 2 2 6.477 2 12C2 17.523 6.477 22 12 22C17.523 22 22 17.523 22 12C22 6.477 17.523 2 12 2ZM16.5 16.5C14.5 15.2 11.5 14.8 8 15.5C7.6 15.6 7.2 15.3 7.1 14.9C7 14.5 7.3 14.1 7.7 14C11.5 13.2 14.9 13.7 17.2 15.2C17.5 15.4 17.6 15.8 17.4 16.1C17.2 16.4 16.8 16.6 16.5 16.5ZM17.5 13.8C15.1 12.3 11.4 11.8 7.5 13C7 13.1 6.5 12.8 6.4 12.3C6.3 11.8 6.6 11.3 7.1 11.2C11.5 9.9 15.6 10.5 18.4 12.2C18.9 12.5 19 13.1 18.7 13.5C18.4 14 17.9 14.1 17.5 13.8ZM18.5 11C15.7 9.3 10.8 9.1 7.3 10.2C6.7 10.4 6.1 10 5.9 9.4C5.7 8.8 6.1 8.2 6.7 8C10.7 6.8 16.1 7 19.4 8.9C20 9.2 20.2 10 19.9 10.5C19.6 11.1 18.9 11.3 18.5 11Z"/></svg>
          Écouter sur Spotify
        </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

</main>

<?php get_footer(); ?>
