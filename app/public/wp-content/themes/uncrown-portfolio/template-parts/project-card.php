<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
$ctp_index = isset( $args['index'] ) ? (int) $args['index'] : 0;
$terms = get_the_terms( get_the_ID(), 'ctp_project_category' );
$slugs = ( $terms && ! is_wp_error( $terms ) ) ? wp_list_pluck( $terms, 'slug' ) : array();
$year = get_post_meta( get_the_ID(), '_ctp_project_year', true );
$hue = 30 + ( ( get_the_ID() * 37 ) % 16 );
$video = ctp_project_video( get_the_ID() );
$poster = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'large' ) : '';
?>
<article class="project-card<?php echo $video ? ' has-video' : ''; ?>" data-categories="<?php echo esc_attr( implode( ' ', $slugs ) ); ?>" data-tilt style="--hue:<?php echo (int) $hue; ?>">
  <a class="project-card-link" href="<?php the_permalink(); ?>">
    <div class="project-media">
      <?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'large' ); } else { ?><span class="project-placeholder" aria-hidden="true"><span><?php echo esc_html( mb_substr( get_the_title(), 0, 1 ) ); ?></span></span><?php } ?>
      <?php if ( $video ) : ?><video class="project-video" data-card-video data-src="<?php echo esc_url( $video ); ?>" muted loop playsinline preload="none"<?php echo $poster ? ' poster="' . esc_url( $poster ) . '"' : ''; ?> aria-hidden="true"></video><span class="project-demo" aria-hidden="true"><span class="play-tri"></span><?php esc_html_e( 'Live demo', 'uncrown-portfolio' ); ?></span><?php endif; ?>
      <span class="project-tag"><?php echo ( $terms && ! is_wp_error( $terms ) ) ? esc_html( $terms[0]->name ) : esc_html__( 'Project', 'uncrown-portfolio' ); ?></span>
    </div>
    <div class="project-body">
      <p class="project-meta"><span><?php echo esc_html( $year ?: get_the_date( 'Y' ) ); ?></span><?php if ( $ctp_index ) : ?><span><?php echo esc_html( sprintf( '%02d', $ctp_index ) ); ?></span><?php endif; ?></p>
      <h3><?php the_title(); ?></h3>
      <p class="project-excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
      <span class="project-cta"><?php esc_html_e( 'Open case study', 'uncrown-portfolio' ); ?> <span aria-hidden="true">→</span></span>
    </div>
  </a>
</article>
