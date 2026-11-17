<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<!doctype html>
<html <?php language_attributes(); ?> data-theme="dark">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main-content"><?php esc_html_e( 'Skip to content', 'uncrown-portfolio' ); ?></a>
<div class="scroll-progress" aria-hidden="true"><span></span></div>
<header class="site-header" data-header>
  <div class="header-bar">
    <a class="site-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( ctp_brand_name() ); ?>">
      <?php if ( has_custom_logo() ) : ?>
        <?php the_custom_logo(); ?>
      <?php else : ?>
        <img class="brand-logo" src="<?php echo esc_url( get_theme_file_uri( '/assets/images/uncrown-mark.png' ) ); ?>" alt="" width="40" height="41">
      <?php endif; ?>
      <span class="brand-name"><?php echo esc_html( ctp_brand_name() ); ?></span>
    </a>
    <nav class="primary-nav" id="primary-menu" aria-label="<?php esc_attr_e( 'Primary navigation', 'uncrown-portfolio' ); ?>">
      <?php
      if ( has_nav_menu( 'primary' ) ) {
          wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => '', 'fallback_cb' => false ) );
      } else {
          $links = array(
              array( is_front_page() ? '#home' : home_url( '/' ), __( 'Home', 'uncrown-portfolio' ), 'nav-home' ),
              array( home_url( '/#team' ), __( 'Team', 'uncrown-portfolio' ), '' ),
              array( home_url( '/#process' ), __( 'Process', 'uncrown-portfolio' ), '' ),
              array( home_url( '/#work' ), __( 'Work', 'uncrown-portfolio' ), '' ),
              array( ctp_get_contact_page_url(), __( 'Say hello', 'uncrown-portfolio' ), 'nav-cta' ),
          );
          echo '<ul>';
          foreach ( $links as $n => $link ) {
              echo '<li style="--i:' . (int) $n . '"><a' . ( $link[2] ? ' class="' . esc_attr( $link[2] ) . '"' : '' ) . ' href="' . esc_url( $link[0] ) . '">' . esc_html( $link[1] ) . '</a></li>';
          }
          echo '</ul>';
      }
      ?>
    </nav>
    <div class="header-tools">
      <button class="icon-button menu-toggle" type="button" aria-expanded="false" aria-controls="primary-menu"><span class="menu-toggle-lines" aria-hidden="true"></span><span class="screen-reader-text"><?php esc_html_e( 'Toggle navigation', 'uncrown-portfolio' ); ?></span></button>
    </div>
  </div>
</header>
