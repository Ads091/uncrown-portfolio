<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
$chapters = ctp_story_chapters();
$hero_media = ctp_hero_video();
$custom_video = ( $hero_media && $hero_media['video'] && absint( ctp_get_option( 'ctp_hero_video', 0 ) ) ) ? $hero_media['video'] : '';
$steps = count( $chapters ) + 1;
?>
<section class="story" id="home" data-story style="--steps:<?php echo (int) $steps; ?>" aria-label="<?php esc_attr_e( 'Introduction', 'uncrown-portfolio' ); ?>">
  <div class="story-sticky">
    <div class="story-scenes" aria-hidden="true">
      <img class="story-poster" src="<?php echo esc_url( get_theme_file_uri( '/assets/images/story-poster.jpg' ) ); ?>" alt="" fetchpriority="high" decoding="async" width="1600" height="900">
      <?php if ( $custom_video ) : ?><video class="story-video" data-hero-video data-src="<?php echo esc_url( $custom_video ); ?>" muted loop playsinline preload="none"></video><?php endif; ?>
      <canvas class="story-stage" data-story-stage data-scenes="<?php echo esc_attr( implode( ',', wp_list_pluck( $chapters, 'scene' ) ) ); ?>"></canvas>
    </div>
    <canvas class="story-particles" data-story-particles aria-hidden="true"></canvas>
    <div class="story-overlay" aria-hidden="true"></div>

    <div class="story-hud" aria-hidden="true">
      <span class="hud-tag"><span class="live-dot"></span><?php echo esc_html( strtoupper( ctp_brand_name() ) ); ?></span>
      <span class="hud-count"><b data-story-count>01</b> / <?php echo esc_html( sprintf( '%02d', $steps ) ); ?></span>
      <span class="hud-rail"><span data-story-rail></span></span>
    </div>

    <div class="story-texts">
      <?php foreach ( $chapters as $n => $chapter ) : ?>
        <div class="story-text pos-<?php echo esc_attr( $chapter['pos'] ); ?>" data-story-text>
          <p class="story-kicker"><?php echo esc_html( $chapter['kicker'] ); ?></p>
          <h2 class="story-line"><?php echo ctp_split_chars( $chapter['line'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in helper. ?></h2>
          <p class="story-sub"><?php echo esc_html( $chapter['sub'] ); ?></p>
        </div>
      <?php endforeach; ?>
      <div class="story-text story-final" data-story-text>
        <img class="story-mark" src="<?php echo esc_url( get_theme_file_uri( '/assets/images/uncrown-mark.png' ) ); ?>" alt="" width="180" height="184">
        <h1 class="story-title"><?php echo ctp_split_chars( ctp_get_option( 'ctp_hero_title', 'Earn every crown.' ) ); // phpcs:ignore ?></h1>
        <p class="story-desc"><?php echo esc_html( ctp_get_option( 'ctp_hero_description', 'We are Uncrown, a capstone team that treats every title as something to earn. Meet the people, see the process and explore the work.' ) ); ?></p>
        <div class="hero-actions">
          <a class="button button-primary" href="#team" data-magnetic><span><?php esc_html_e( 'Meet the team', 'uncrown-portfolio' ); ?></span><span class="button-icon" aria-hidden="true">↓</span></a>
          <a class="button button-ghost" href="#work" data-magnetic><span><?php esc_html_e( 'View our work', 'uncrown-portfolio' ); ?></span></a>
        </div>
        <?php echo ctp_social_links_html( 'story-social' ); // phpcs:ignore -- escaped in helper ?>
      </div>
    </div>

    <a class="story-cue" href="#readout" data-story-cue><span class="scroll-cue-line" aria-hidden="true"></span><?php esc_html_e( 'Scroll to enter', 'uncrown-portfolio' ); ?></a>
    <a class="story-skip" href="#home" data-story-skip><?php esc_html_e( 'Skip intro', 'uncrown-portfolio' ); ?> <span aria-hidden="true">↓</span></a>
  </div>
</section>
