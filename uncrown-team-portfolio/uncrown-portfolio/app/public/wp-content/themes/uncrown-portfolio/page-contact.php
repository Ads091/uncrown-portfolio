<?php get_header(); ?>
<main id="main-content">
  <section class="page-hero"><div class="container">
    <p class="section-label"><?php esc_html_e( 'Contact', 'uncrown-portfolio' ); ?></p>
    <h1 class="page-title"><?php echo ctp_split_words( __( 'Say hello to the team.', 'uncrown-portfolio' ) ); // phpcs:ignore ?></h1>
    <p class="page-intro"><?php esc_html_e( 'Send one message to everyone, or choose a single member. Either way it lands in a real inbox.', 'uncrown-portfolio' ); ?></p>
  </div></section>
  <section class="section contact-section"><div class="container contact-grid">
    <aside class="contact-side" data-reveal>
      <h2><?php esc_html_e( 'What happens next', 'uncrown-portfolio' ); ?></h2>
      <ol class="contact-steps">
        <li><strong><?php esc_html_e( 'You write', 'uncrown-portfolio' ); ?></strong><span><?php esc_html_e( 'Pick the team or one member, then tell us what you have in mind.', 'uncrown-portfolio' ); ?></span></li>
        <li><strong><?php esc_html_e( 'It is routed privately', 'uncrown-portfolio' ); ?></strong><span><?php esc_html_e( 'Member addresses are never shown. If a member has none saved, the team inbox gets it.', 'uncrown-portfolio' ); ?></span></li>
        <li><strong><?php esc_html_e( 'We reply by email', 'uncrown-portfolio' ); ?></strong><span><?php esc_html_e( 'Your address is used only to answer you.', 'uncrown-portfolio' ); ?></span></li>
      </ol>
    </aside>
    <div class="contact-card" data-reveal>
      <?php while ( have_posts() ) : the_post(); $contact_content = trim( get_the_content() ); $contact_plain = trim( wp_strip_all_tags( $contact_content ) ); if ( $contact_plain && 'Add your team’s official contact information here.' !== $contact_plain ) : ?><div class="entry-content contact-note"><?php the_content(); ?></div><?php endif; endwhile; echo ctp_render_contact_form(); // phpcs:ignore ?>
    </div>
  </div></section>
</main>
<?php get_footer(); ?>
