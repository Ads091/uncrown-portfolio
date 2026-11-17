<?php get_header(); ?>
<main id="main-content">
  <section class="page-hero"><div class="container"><p class="section-label"><?php echo esc_html( ctp_brand_name() ); ?></p><h1 class="page-title"><?php echo ctp_split_words( is_home() ? __( 'Updates & news', 'uncrown-portfolio' ) : __( 'Explore the site', 'uncrown-portfolio' ) ); // phpcs:ignore ?></h1></div></section>
  <section class="section page-section"><div class="container"><div class="post-list">
  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?><article <?php post_class( 'post-row' ); ?> data-reveal><p class="post-date"><?php echo esc_html( get_the_date() ); ?></p><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2><p><?php echo esc_html( get_the_excerpt() ); ?></p></article><?php endwhile; the_posts_pagination(); else : ?><p><?php esc_html_e( 'No content found.', 'uncrown-portfolio' ); ?></p><?php endif; ?>
  </div></div></section>
</main>
<?php get_footer(); ?>
