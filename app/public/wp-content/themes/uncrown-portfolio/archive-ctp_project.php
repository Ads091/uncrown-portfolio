<?php get_header(); $categories = get_terms( array( 'taxonomy' => 'ctp_project_category', 'hide_empty' => true ) ); ?>
<main id="main-content">
  <section class="page-hero"><div class="container">
    <p class="section-label"><?php esc_html_e( 'Work', 'uncrown-portfolio' ); ?></p>
    <h1 class="page-title"><?php echo ctp_split_words( __( 'Every project we have shipped.', 'uncrown-portfolio' ) ); // phpcs:ignore ?></h1>
    <p class="page-intro"><?php esc_html_e( 'Research, prototypes and finished systems from our team. Filter by type to narrow the list.', 'uncrown-portfolio' ); ?></p>
  </div></section>
  <section class="section archive-section"><div class="container">
    <div class="work-toolbar" data-reveal><div class="project-filters" role="group" aria-label="<?php esc_attr_e( 'Filter projects by category', 'uncrown-portfolio' ); ?>"><button class="project-filter is-active" type="button" data-filter="all" aria-pressed="true"><?php esc_html_e( 'All', 'uncrown-portfolio' ); ?></button><?php if ( ! is_wp_error( $categories ) && $categories ) : foreach ( $categories as $category ) : ?><button class="project-filter" type="button" data-filter="<?php echo esc_attr( $category->slug ); ?>" aria-pressed="false"><?php echo esc_html( $category->name ); ?></button><?php endforeach; endif; ?></div></div>
    <?php if ( have_posts() ) : ?>
      <div class="project-grid"><?php $n = 0; while ( have_posts() ) : the_post(); $n++; get_template_part( 'template-parts/project-card', null, array( 'index' => $n ) ); endwhile; ?></div>
      <?php the_posts_pagination(); ?>
    <?php else : ?>
      <div class="empty-state"><h3><?php esc_html_e( 'No projects published yet.', 'uncrown-portfolio' ); ?></h3><p><?php esc_html_e( 'Published projects will appear here.', 'uncrown-portfolio' ); ?></p></div>
    <?php endif; ?>
  </div></section>
</main>
<?php get_footer(); ?>
