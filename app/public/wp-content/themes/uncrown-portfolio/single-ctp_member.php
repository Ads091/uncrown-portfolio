<?php get_header(); ?>
<main id="main-content">
<?php while ( have_posts() ) : the_post(); $role = get_post_meta( get_the_ID(), '_ctp_member_role', true ); $skills = get_post_meta( get_the_ID(), '_ctp_member_skills', true ); ?>
  <article class="profile">
    <div class="container profile-grid">
      <div class="profile-media" data-tilt>
        <div class="profile-photo"><?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'large' ); } else { ?><span class="member-initial" aria-hidden="true"><?php echo esc_html( strtoupper( mb_substr( get_the_title(), 0, 1 ) ) ); ?></span><?php } ?></div>
        <p class="profile-badge"><span class="live-dot" aria-hidden="true"></span><?php echo esc_html( ctp_brand_name() ); ?></p>
      </div>
      <div class="profile-copy">
        <a class="back-link" href="<?php echo esc_url( home_url( '/#team' ) ); ?>"><span aria-hidden="true">←</span> <?php esc_html_e( 'Team', 'uncrown-portfolio' ); ?></a>
        <p class="member-role"><?php echo esc_html( $role ?: __( 'Team member', 'uncrown-portfolio' ) ); ?></p>
        <h1 class="page-title"><?php echo ctp_split_words( get_the_title(), false ); // phpcs:ignore ?></h1>
        <?php if ( has_excerpt() ) : ?><p class="page-intro"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
        <div class="entry-content" data-reveal><?php the_content(); ?></div>
        <?php $levels = ctp_member_skill_levels( get_the_ID() ); if ( $levels || $skills ) : ?><div class="profile-skills" data-reveal>
          <?php if ( $levels ) : ?><h2><?php esc_html_e( 'Skill levels', 'uncrown-portfolio' ); ?></h2><?php get_template_part( 'template-parts/skill-bars', null, array( 'levels' => $levels ) ); ?><?php endif; ?>
          <?php if ( $skills ) : ?><h2 class="<?php echo $levels ? 'skills-sub' : ''; ?>"><?php esc_html_e( 'Skills & specialization', 'uncrown-portfolio' ); ?></h2><ul class="chip-list chip-list-lg"><?php foreach ( array_filter( array_map( 'trim', explode( ',', $skills ) ) ) as $n => $skill ) : ?><li style="--d:<?php echo (int) $n; ?>"><?php echo esc_html( $skill ); ?></li><?php endforeach; ?></ul><?php endif; ?>
        </div><?php endif; ?>
        <div class="profile-actions" data-reveal><a class="button button-primary" href="<?php echo esc_url( add_query_arg( 'member', get_the_ID(), ctp_get_contact_page_url() ) . '#contact-form' ); ?>" data-magnetic><span><?php echo esc_html( sprintf( __( 'Message %s', 'uncrown-portfolio' ), strtok( get_the_title(), ' ' ) ) ); ?></span><span class="button-icon" aria-hidden="true">→</span></a></div>
      </div>
    </div>
  </article>
<?php endwhile; ?>
</main>
<?php get_footer(); ?>
