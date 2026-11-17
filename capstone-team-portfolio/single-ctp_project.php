<?php get_header(); ?>
<main id="main-content">
<?php while ( have_posts() ) : the_post(); $year = get_post_meta( get_the_ID(), '_ctp_project_year', true ); $external_url = get_post_meta( get_the_ID(), '_ctp_project_url', true ); $terms = get_the_terms( get_the_ID(), 'ctp_project_category' ); ?>
  <article class="case">
    <section class="page-hero"><div class="container">
      <a class="back-link" href="<?php echo esc_url( get_post_type_archive_link( 'ctp_project' ) ); ?>"><span aria-hidden="true">←</span> <?php esc_html_e( 'All projects', 'uncrown-portfolio' ); ?></a>
      <h1 class="page-title"><?php echo ctp_split_words( get_the_title() ); // phpcs:ignore ?></h1>
      <?php if ( has_excerpt() ) : ?><p class="page-intro"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
      <dl class="case-facts">
        <div><dt><?php esc_html_e( 'Year', 'uncrown-portfolio' ); ?></dt><dd><?php echo esc_html( $year ?: get_the_date( 'Y' ) ); ?></dd></div>
        <?php if ( $terms && ! is_wp_error( $terms ) ) : ?><div><dt><?php esc_html_e( 'Type', 'uncrown-portfolio' ); ?></dt><dd><?php echo esc_html( implode( ', ', wp_list_pluck( $terms, 'name' ) ) ); ?></dd></div><?php endif; ?>
        <?php if ( $external_url ) : ?><div><dt><?php esc_html_e( 'Live link', 'uncrown-portfolio' ); ?></dt><dd><a href="<?php echo esc_url( $external_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Visit project', 'uncrown-portfolio' ); ?> ↗</a></dd></div><?php endif; ?>
      </dl>
    </div></section>
    <?php
    $video = ctp_project_video( get_the_ID() ); $features = ctp_project_features( get_the_ID() ); $stack = ctp_project_stack( get_the_ID() );
    $platforms = ctp_project_platforms( get_the_ID() );
    $poster = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'full' ) : '';
    ?>
    <div class="case-marquee" data-marquee aria-hidden="true"><div class="marquee-track"><?php for ( $m = 0; $m < 4; $m++ ) : ?><span><?php echo esc_html( get_the_title() ); ?></span><b>✦</b><?php endfor; ?></div></div>

    <?php if ( $platforms ) : ?>
    <section class="stage<?php echo count( $platforms ) > 1 ? ' is-multi' : ''; ?>" data-stage aria-label="<?php esc_attr_e( 'App previews', 'uncrown-portfolio' ); ?>">
      <div class="container stage-inner">
        <?php
        $main = isset( $platforms['web'] ) ? $platforms['web'] : reset( $platforms );
        get_template_part( 'template-parts/device', null, array( 'device' => $main['device'], 'video' => $main['video'], 'title' => get_the_title(), 'poster' => isset( $platforms['web'] ) ? $poster : '', 'class' => 'stage-main' ) );
        if ( isset( $platforms['web'] ) && isset( $platforms['tablet'] ) ) { echo '<div class="stage-float stage-left"><div class="float-bob">'; get_template_part( 'template-parts/device', null, array( 'device' => 'tablet', 'video' => $platforms['tablet']['video'], 'title' => $platforms['tablet']['title'] ) ); echo '</div></div>'; }
        if ( isset( $platforms['web'] ) && isset( $platforms['phone'] ) ) { echo '<div class="stage-float stage-right"><div class="float-bob">'; get_template_part( 'template-parts/device', null, array( 'device' => 'phone', 'video' => $platforms['phone']['video'], 'title' => $platforms['phone']['title'] ) ); echo '</div></div>'; }
        ?>
        <div class="device-glow" aria-hidden="true"></div>
      </div>
    </section>

    <?php if ( count( $platforms ) > 1 ) : ?>
    <section class="platforms" data-platforms>
      <div class="container platforms-grid">
        <div class="platforms-sticky" aria-hidden="true">
          <div class="platforms-viewport">
            <?php $n = 0; foreach ( $platforms as $slot => $pf ) : ?>
              <div class="pf-device pf-<?php echo esc_attr( $pf['device'] ); ?><?php echo 0 === $n ? ' is-active' : ''; ?>" data-pf-device="<?php echo (int) $n; ?>">
                <?php get_template_part( 'template-parts/device', null, array( 'device' => $pf['device'], 'video' => $pf['video'], 'title' => 'web' === $slot ? get_the_title() : $pf['title'], 'poster' => 'web' === $slot ? $poster : '' ) ); ?>
              </div>
            <?php $n++; endforeach; ?>
          </div>
        </div>
        <div class="platforms-copy">
          <header class="section-head" data-reveal><p class="section-label"><?php esc_html_e( 'One system, every screen', 'uncrown-portfolio' ); ?></p><h2><?php echo esc_html( sprintf( _n( '%d app.', '%d connected apps.', count( $platforms ), 'uncrown-portfolio' ), count( $platforms ) ) ); ?></h2></header>
          <?php $n = 0; foreach ( $platforms as $slot => $pf ) : ?>
            <article class="pf-panel<?php echo 0 === $n ? ' is-active' : ''; ?>" data-pf-panel="<?php echo (int) $n; ?>">
              <span class="pf-num"><?php echo esc_html( sprintf( '%02d', $n + 1 ) ); ?></span>
              <h3><?php echo esc_html( $pf['title'] ); ?></h3>
              <?php if ( $pf['text'] ) : ?><p><?php echo esc_html( $pf['text'] ); ?></p><?php endif; ?>
              <span class="pf-tag"><?php echo esc_html( array( 'browser' => __( 'Web browser', 'uncrown-portfolio' ), 'tablet' => __( 'Tablet', 'uncrown-portfolio' ), 'phone' => __( 'Phone', 'uncrown-portfolio' ) )[ $pf['device'] ] ); ?></span>
              <div class="pf-inline"><?php get_template_part( 'template-parts/device', null, array( 'device' => $pf['device'], 'video' => $pf['video'], 'title' => 'web' === $slot ? get_the_title() : $pf['title'], 'poster' => 'web' === $slot ? $poster : '' ) ); ?></div>
            </article>
          <?php $n++; endforeach; ?>
        </div>
      </div>
    </section>
    <?php endif; ?>
    <?php elseif ( has_post_thumbnail() ) : ?>
    <section class="stage"><div class="container stage-inner"><div class="device device-browser stage-main"><div class="device-bar" aria-hidden="true"><span class="device-dots"><i></i><i></i><i></i></span><span class="device-url"><?php the_title(); ?></span></div><div class="device-screen"><?php the_post_thumbnail( 'full' ); ?></div></div><div class="device-glow" aria-hidden="true"></div></div></section>
    <?php endif; ?>
    <?php if ( $features || $stack ) : ?>
    <section class="case-highlights">
      <div class="container">
        <?php if ( $features ) : ?>
          <header class="section-head" data-reveal><p class="section-label"><?php esc_html_e( 'Key features', 'uncrown-portfolio' ); ?></p><h2><?php esc_html_e( 'What it does.', 'uncrown-portfolio' ); ?></h2></header>
          <ol class="feature-grid">
            <?php foreach ( $features as $n => $feature ) : ?>
              <li class="feature-card" data-reveal data-tilt style="--d:<?php echo (int) ( $n % 3 ); ?>">
                <span class="feature-num"><?php echo esc_html( sprintf( '%02d', $n + 1 ) ); ?></span>
                <h3><?php echo esc_html( $feature['title'] ); ?></h3>
                <?php if ( $feature['text'] ) : ?><p><?php echo esc_html( $feature['text'] ); ?></p><?php endif; ?>
              </li>
            <?php endforeach; ?>
          </ol>
        <?php endif; ?>
        <?php if ( $stack ) : ?>
          <div class="stack-row" data-reveal><p class="section-label"><?php esc_html_e( 'Built with', 'uncrown-portfolio' ); ?></p><ul class="chip-list chip-list-lg"><?php foreach ( $stack as $n => $tech ) : ?><li style="--d:<?php echo (int) $n; ?>"><?php echo esc_html( $tech ); ?></li><?php endforeach; ?></ul></div>
        <?php endif; ?>
      </div>
    </section>
    <?php endif; ?>
    <div class="container case-body">
      <div class="entry-content" data-reveal><?php the_content(); ?></div>
      <div class="case-footer"><?php if ( $external_url ) : ?><a class="button button-primary" href="<?php echo esc_url( $external_url ); ?>" target="_blank" rel="noopener noreferrer" data-magnetic><span><?php esc_html_e( 'Visit project', 'uncrown-portfolio' ); ?></span><span class="button-icon" aria-hidden="true">↗</span></a><?php endif; ?><a class="text-link" href="<?php echo esc_url( get_post_type_archive_link( 'ctp_project' ) ); ?>"><span aria-hidden="true">←</span> <?php esc_html_e( 'Back to all projects', 'uncrown-portfolio' ); ?></a></div>
    </div>
  </article>
<?php endwhile; ?>
</main>
<?php get_footer(); ?>
