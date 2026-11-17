<?php
/* A device frame (browser, tablet or phone) playing a muted, looping app recording.
   $args: device, video, title, poster (optional), class (optional). Videos play only while visible. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$device = isset( $args['device'] ) ? $args['device'] : 'browser';
$video = isset( $args['video'] ) ? $args['video'] : '';
$title = isset( $args['title'] ) ? $args['title'] : '';
$poster = isset( $args['poster'] ) ? $args['poster'] : '';
$class = isset( $args['class'] ) ? ' ' . $args['class'] : '';
$media = $video ? '<video class="device-video" data-auto-video src="' . esc_url( $video ) . '" muted loop playsinline preload="metadata"' . ( $poster ? ' poster="' . esc_url( $poster ) . '"' : '' ) . ' aria-label="' . esc_attr( sprintf( __( 'Screen recording: %s', 'uncrown-portfolio' ), $title ) ) . '"></video>' : '';
?>
<?php if ( 'browser' === $device ) : ?>
  <div class="device device-browser<?php echo esc_attr( $class ); ?>">
    <div class="device-bar" aria-hidden="true"><span class="device-dots"><i></i><i></i><i></i></span><span class="device-url"><?php echo esc_html( $title ); ?></span><span class="device-live"><span class="live-dot"></span><?php esc_html_e( 'Live demo', 'uncrown-portfolio' ); ?></span></div>
    <div class="device-screen"><?php echo $media; // phpcs:ignore -- escaped above ?></div>
  </div>
<?php elseif ( 'tablet' === $device ) : ?>
  <div class="device-tablet<?php echo esc_attr( $class ); ?>"><div class="tablet-screen"><?php echo $media; // phpcs:ignore ?></div></div>
<?php else : ?>
  <div class="device-phone<?php echo esc_attr( $class ); ?>"><span class="phone-island" aria-hidden="true"></span><div class="phone-screen"><?php echo $media; // phpcs:ignore ?></div></div>
<?php endif; ?>
