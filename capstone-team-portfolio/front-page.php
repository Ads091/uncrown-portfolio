<?php
get_header();
$stats = ctp_get_portfolio_stats();
$team = ctp_member_query();
$project_categories = get_terms( array( 'taxonomy' => 'ctp_project_category', 'hide_empty' => true ) );
$projects = new WP_Query( array( 'post_type' => 'ctp_project', 'post_status' => 'publish', 'posts_per_page' => 8, 'orderby' => 'date', 'order' => 'DESC' ) );
$steps = array(
    array( __( 'Discover', 'uncrown-portfolio' ), __( 'We talk to the people affected and read what already exists before deciding anything.', 'uncrown-portfolio' ) ),
    array( __( 'Define', 'uncrown-portfolio' ), __( 'We turn what we learned into a clear problem statement, scope and success measures.', 'uncrown-portfolio' ) ),
    array( __( 'Build', 'uncrown-portfolio' ), __( 'We design, prototype and develop in short cycles, reviewing each other’s work as we go.', 'uncrown-portfolio' ) ),
    array( __( 'Test & refine', 'uncrown-portfolio' ), __( 'We put the work in front of real users, measure the results and fix what they find.', 'uncrown-portfolio' ) ),
);
?>
<main id="main-content">
  <?php if ( ! ctp_get_option( 'ctp_story_off', false ) ) : get_template_part( 'template-parts/story' ); else : ?>
  <?php $hero_media = ctp_hero_video(); ?>
  <section class="hero<?php echo $hero_media ? ' has-video' : ''; ?>" id="home">
    <?php if ( $hero_media ) : ?>
      <div class="hero-media" aria-hidden="true">
        <?php if ( $hero_media['poster'] ) : ?><img class="hero-poster" src="<?php echo esc_url( $hero_media['poster'] ); ?>" alt="" fetchpriority="high"><?php endif; ?>
        <?php if ( $hero_media['video'] ) : ?><video class="hero-video" data-hero-video data-src="<?php echo esc_url( $hero_media['video'] ); ?>" muted loop playsinline preload="none"<?php echo $hero_media['poster'] ? ' poster="' . esc_url( $hero_media['poster'] ) . '"' : ''; ?>></video><?php endif; ?>
        <span class="hero-shade"></span>
      </div>
    <?php endif; ?>
    <canvas class="hero-canvas" data-signal aria-hidden="true"></canvas>
    <div class="hero-glow" aria-hidden="true"></div>
    <div class="container hero-inner">
      <div class="hero-copy">
        <p class="hero-label"><span class="live-dot" aria-hidden="true"></span><?php echo esc_html( ctp_hero_eyebrow() ); ?> <span class="hero-label-sep">/</span> <?php echo esc_html( gmdate( 'Y' ) ); ?></p>
        <h1 class="hero-title"><?php echo ctp_split_words( ctp_get_option( 'ctp_hero_title', 'Earn every crown.' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in helper. ?></h1>
        <p class="hero-text"><?php echo esc_html( ctp_get_option( 'ctp_hero_description', 'We are Uncrown, a capstone team that treats every title as something to earn. Meet the people, see the process and explore the work.' ) ); ?></p>
        <div class="hero-actions">
          <a class="button button-primary" href="#team" data-magnetic><span><?php esc_html_e( 'Meet the team', 'uncrown-portfolio' ); ?></span><span class="button-icon" aria-hidden="true">↓</span></a>
          <a class="button button-ghost" href="#work" data-magnetic><span><?php esc_html_e( 'View our work', 'uncrown-portfolio' ); ?></span></a>
        </div>
      </div>
      <div class="hero-mark" data-tilt>
        <div class="hero-mark-ring" aria-hidden="true"></div>
        <img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/uncrown-mark.png' ) ); ?>" alt="<?php echo esc_attr( sprintf( __( '%s logo', 'uncrown-portfolio' ), ctp_brand_name() ) ); ?>" width="420" height="420">
        <span class="hero-mark-sheen" aria-hidden="true"></span>
      </div>
    </div>
    <a class="scroll-cue" href="#readout"><span class="scroll-cue-line" aria-hidden="true"></span><?php esc_html_e( 'Scroll', 'uncrown-portfolio' ); ?></a>
  </section>

  <?php endif; ?>

  <section class="readout" id="readout" aria-label="<?php esc_attr_e( 'Team at a glance', 'uncrown-portfolio' ); ?>">
    <div class="container readout-grid">
      <?php
      $readout = array(
          array( $stats['members'], __( 'Members', 'uncrown-portfolio' ) ),
          array( $stats['projects'], __( 'Projects published', 'uncrown-portfolio' ) ),
          array( $stats['skills'], __( 'Skills on the team', 'uncrown-portfolio' ) ),
      );
      foreach ( $readout as $n => $item ) :
      ?>
        <div class="readout-item" data-reveal style="--d:<?php echo (int) $n; ?>">
          <span class="readout-label"><?php echo esc_html( $item[1] ); ?></span>
          <strong class="readout-number" data-counter="<?php echo esc_attr( $item[0] ); ?>"><?php echo esc_html( number_format_i18n( $item[0] ) ); ?></strong>
          <span class="readout-bar" aria-hidden="true"><span></span></span>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="section roster" id="team">
    <div class="container">
      <header class="section-head" data-reveal>
        <p class="section-label"><?php esc_html_e( 'Team', 'uncrown-portfolio' ); ?></p>
        <h2><?php esc_html_e( 'Meet the team.', 'uncrown-portfolio' ); ?></h2>
        <p class="section-text"><?php esc_html_e( 'Open a card to see each member’s full profile, skill levels and how to reach them.', 'uncrown-portfolio' ); ?></p>
      </header>
      <?php if ( $team->have_posts() ) : ?>
        <div class="roster-grid">
          <?php while ( $team->have_posts() ) : $team->the_post(); $role = get_post_meta( get_the_ID(), '_ctp_member_role', true ); $skills = get_post_meta( get_the_ID(), '_ctp_member_skills', true ); ?>
            <article class="member-card" data-reveal data-tilt style="--d:<?php echo (int) $team->current_post; ?>">
              <a class="member-card-link" href="<?php the_permalink(); ?>">
                <div class="member-photo">
                  <?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'medium_large' ); } else { ?><span class="member-initial" aria-hidden="true"><?php echo esc_html( strtoupper( mb_substr( get_the_title(), 0, 1 ) ) ); ?></span><?php } ?>
                  <span class="member-open" aria-hidden="true">↗</span>
                </div>
                <div class="member-body">
                  <p class="member-role"><?php echo esc_html( $role ?: __( 'Team member', 'uncrown-portfolio' ) ); ?></p>
                  <h3><?php the_title(); ?></h3>
                  <p class="member-excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
                  <?php $levels = ctp_member_skill_levels( get_the_ID() ); if ( $levels ) : get_template_part( 'template-parts/skill-bars', null, array( 'levels' => $levels, 'compact' => true ) ); elseif ( $skills ) : ?><ul class="chip-list"><?php foreach ( array_slice( array_filter( array_map( 'trim', explode( ',', $skills ) ) ), 0, 3 ) as $skill ) : ?><li><?php echo esc_html( $skill ); ?></li><?php endforeach; ?></ul><?php endif; ?>
                </div>
              </a>
            </article>
          <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <?php get_template_part( 'template-parts/team-skills' ); ?>
      <?php else : ?>
        <div class="empty-state" data-reveal><h3><?php esc_html_e( 'No members published yet.', 'uncrown-portfolio' ); ?></h3><p><?php esc_html_e( 'Add people under Team Members in your WordPress dashboard and they will appear here.', 'uncrown-portfolio' ); ?></p></div>
      <?php endif; ?>
    </div>
  </section>

  <section class="section process" id="process" data-process>
    <div class="container">
      <header class="section-head" data-reveal>
        <p class="section-label"><?php esc_html_e( 'Process', 'uncrown-portfolio' ); ?></p>
        <h2><?php esc_html_e( 'How a project moves through the team.', 'uncrown-portfolio' ); ?></h2>
      </header>
      <div class="process-wrap">
      <span class="process-line" aria-hidden="true"><span></span></span>
      <ol class="process-track">
        <?php foreach ( $steps as $n => $step ) : ?>
          <li class="process-step" data-step="<?php echo (int) $n; ?>">
            <span class="process-node" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $n + 1 ) ); ?></span>
            <h3><?php echo esc_html( $step[0] ); ?></h3>
            <p><?php echo esc_html( $step[1] ); ?></p>
          </li>
        <?php endforeach; ?>
      </ol>
      </div>
    </div>
  </section>

  <section class="section work" id="work">
    <div class="container">
      <header class="section-head" data-reveal>
        <p class="section-label"><?php esc_html_e( 'Work', 'uncrown-portfolio' ); ?></p>
        <h2><?php echo esc_html( ctp_get_option( 'ctp_project_title', 'Things we have made.' ) ); ?></h2>
        <p class="section-text"><?php echo esc_html( ctp_get_option( 'ctp_project_description', 'Projects, prototypes and research from our capstone journey. Scroll through, or filter by type.' ) ); ?></p>
      </header>
      <?php if ( $projects->have_posts() ) : ?>
        <div class="work-toolbar" data-reveal>
          <div class="project-filters" role="group" aria-label="<?php esc_attr_e( 'Filter projects by category', 'uncrown-portfolio' ); ?>"><button class="project-filter is-active" type="button" data-filter="all" aria-pressed="true"><?php esc_html_e( 'All', 'uncrown-portfolio' ); ?></button><?php if ( ! is_wp_error( $project_categories ) && $project_categories ) : foreach ( $project_categories as $category ) : ?><button class="project-filter" type="button" data-filter="<?php echo esc_attr( $category->slug ); ?>" aria-pressed="false"><?php echo esc_html( $category->name ); ?></button><?php endforeach; endif; ?></div>
          <a class="text-link" href="<?php echo esc_url( get_post_type_archive_link( 'ctp_project' ) ); ?>"><?php esc_html_e( 'All projects', 'uncrown-portfolio' ); ?> <span aria-hidden="true">→</span></a>
        </div>
      <?php endif; ?>
    </div>
    <?php if ( $projects->have_posts() ) : ?>
      <div class="work-pin" data-work-pin>
        <div class="work-sticky">
          <div class="work-track" data-work-track>
            <?php while ( $projects->have_posts() ) : $projects->the_post(); get_template_part( 'template-parts/project-card', null, array( 'index' => $projects->current_post + 1 ) ); endwhile; wp_reset_postdata(); ?>
          </div>
        </div>
      </div>
    <?php else : ?>
      <div class="container"><div class="empty-state" data-reveal><h3><?php esc_html_e( 'No projects published yet.', 'uncrown-portfolio' ); ?></h3><p><?php esc_html_e( 'Publish work under Projects in your WordPress dashboard and it will appear here.', 'uncrown-portfolio' ); ?></p></div></div>
    <?php endif; ?>
  </section>

  <section class="hello">
    <div class="container hello-inner" data-reveal>
      <p class="section-label"><?php esc_html_e( 'Contact', 'uncrown-portfolio' ); ?></p>
      <h2><?php esc_html_e( 'Got a project, a question or feedback?', 'uncrown-portfolio' ); ?></h2>
      <a class="hello-button" href="<?php echo esc_url( ctp_get_contact_page_url() ); ?>" data-magnetic><span><?php esc_html_e( 'Say hello', 'uncrown-portfolio' ); ?></span></a>
      <p class="hello-note"><?php esc_html_e( 'Write to the whole team or pick one member. We read everything.', 'uncrown-portfolio' ); ?></p>
      <?php $social = ctp_social_links_html( 'is-large' ); if ( $social ) : ?><div class="hello-social"><p class="section-label"><?php esc_html_e( 'Follow the team', 'uncrown-portfolio' ); ?></p><?php echo $social; // phpcs:ignore ?></div><?php endif; ?>
    </div>
  </section>
</main>
<?php get_footer(); ?>
