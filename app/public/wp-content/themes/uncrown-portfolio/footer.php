<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<footer class="site-footer">
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand">
        <img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/uncrown-mark.png' ) ); ?>" alt="" width="56" height="57">
        <p><?php echo esc_html( ctp_brand_name() ); ?></p>
      </div>
      <nav class="footer-links" aria-label="<?php esc_attr_e( 'Footer navigation', 'uncrown-portfolio' ); ?>">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'uncrown-portfolio' ); ?></a>
        <a href="<?php echo esc_url( home_url( '/#team' ) ); ?>"><?php esc_html_e( 'Team', 'uncrown-portfolio' ); ?></a>
        <a href="<?php echo esc_url( home_url( '/#process' ) ); ?>"><?php esc_html_e( 'Process', 'uncrown-portfolio' ); ?></a>
        <a href="<?php echo esc_url( get_post_type_archive_link( 'ctp_project' ) ); ?>"><?php esc_html_e( 'All projects', 'uncrown-portfolio' ); ?></a>
        <a href="<?php echo esc_url( ctp_get_contact_page_url() ); ?>"><?php esc_html_e( 'Contact', 'uncrown-portfolio' ); ?></a>
      </nav>
      <?php echo ctp_social_links_html(); // phpcs:ignore -- escaped in helper ?>
      <a class="back-to-top" href="#main-content"><?php esc_html_e( 'Back to top', 'uncrown-portfolio' ); ?> <span aria-hidden="true">↑</span></a>
    </div>
    <div class="footer-mark"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/uncrown-logo.png' ) ); ?>" alt="<?php echo esc_attr( ctp_brand_name() ); ?>" width="900" height="676" loading="lazy"></div>
    <div class="footer-bottom"><span>© <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( ctp_brand_name() ); ?></span><span><?php esc_html_e( 'Capstone project portfolio', 'uncrown-portfolio' ); ?></span></div>
  </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
