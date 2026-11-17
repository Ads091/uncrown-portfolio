<?php get_header(); ?>
<main id="main-content"><section class="page-hero error-hero"><canvas class="hero-canvas" data-signal aria-hidden="true"></canvas><div class="container">
  <p class="section-label"><?php esc_html_e( 'Error 404', 'uncrown-portfolio' ); ?></p>
  <h1 class="page-title"><?php echo ctp_split_words( __( 'Signal lost.', 'uncrown-portfolio' ) ); // phpcs:ignore ?></h1>
  <p class="page-intro"><?php esc_html_e( 'This page moved or never existed. Head back and pick up from the homepage.', 'uncrown-portfolio' ); ?></p>
  <a class="button button-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>" data-magnetic><span><?php esc_html_e( 'Back to homepage', 'uncrown-portfolio' ); ?></span><span class="button-icon" aria-hidden="true">→</span></a>
</div></section></main>
<?php get_footer(); ?>
