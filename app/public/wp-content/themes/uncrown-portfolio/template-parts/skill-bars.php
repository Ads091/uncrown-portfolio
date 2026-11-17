<?php
/* One member's skill levels as labelled bars. $args['levels'] = name => 0–100; $args['compact'] limits to the top three. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$levels = isset( $args['levels'] ) ? $args['levels'] : array();
$compact = ! empty( $args['compact'] );
if ( ! $levels ) { return; }
if ( $compact ) { arsort( $levels ); $levels = array_slice( $levels, 0, 3, true ); }
?>
<ul class="skill-bars<?php echo $compact ? ' is-compact' : ''; ?>">
  <?php foreach ( $levels as $name => $level ) : ?>
    <li class="skill-bar" style="--v:<?php echo (int) $level; ?>">
      <span class="skill-bar-name"><?php echo esc_html( $name ); ?></span>
      <span class="skill-bar-track" aria-hidden="true"><span class="skill-bar-fill"></span></span>
      <span class="skill-bar-value"><?php echo esc_html( (int) $level ); ?>%</span>
    </li>
  <?php endforeach; ?>
</ul>
