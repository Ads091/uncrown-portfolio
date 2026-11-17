<?php get_header(); ?>
<main id="main-content">
  <section class="page-hero"><div class="container"><p class="section-label"><?php echo esc_html( ctp_brand_name() ); ?></p><h1 class="page-title"><?php echo ctp_split_words( get_the_title(), false ); // phpcs:ignore ?></h1></div></section>
  <section class="section page-section"><div class="container"><div class="entry-content"><?php while ( have_posts() ) : the_post(); the_content(); endwhile; ?></div></div></section>
</main>
<?php get_footer(); ?>
