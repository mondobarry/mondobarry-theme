<footer id="mb-footer">
  <div class="footer-inner">
    <div class="footer-logo">
      <?php
        $logo_text = mb_get('mb_footer_logo', 'MondoBarry');
        $mid = intval(mb_strlen($logo_text) / 2);
        echo esc_html(mb_substr($logo_text, 0, $mid));
      ?><span><?php echo esc_html(mb_substr($logo_text, $mid)); ?></span><span class="logo-cursor">_</span>
    </div>
    <p class="footer-tagline"><?php echo esc_html(mb_get('mb_footer_tagline','Chef de Projet Digital · Développeur Web · Admin Système')); ?></p>
    <div class="footer-links">
      <?php
        for($i=1;$i<=3;$i++){
          $label = mb_get("mb_footer_link{$i}_label");
          $url   = mb_get("mb_footer_link{$i}_url");
          if($label && $url) echo '<a href="'.esc_url($url).'" '.($i<3?'target="_blank" rel="noopener"':'').'>'.esc_html($label).'</a>';
        }
      ?>
    </div>
    <p class="footer-copy"><?php echo esc_html(mb_get('mb_footer_copy','© '.date('Y').' '.get_bloginfo('name'))); ?></p>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
