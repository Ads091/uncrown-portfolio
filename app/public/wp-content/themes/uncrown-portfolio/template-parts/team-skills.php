<?php
/* Team skills analytics: the average level per skill across members who listed it, with each member as a dot. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$stats = ctp_team_skill_stats( 10 );
if ( ! $stats ) { return; }
?>
<div class="skills-panel" data-reveal>
  <header class="skills-head">
    <div>
      <h3><?php esc_html_e( 'Team skills', 'uncrown-portfolio' ); ?></h3>
      <p><?php esc_html_e( 'How strong the team is in each skill, based on the levels members set on their profiles.', 'uncrown-portfolio' ); ?></p>
    </div>
    <ul class="skills-legend" aria-hidden="true">
      <li><span class="lg-bar"></span><?php esc_html_e( 'Team average', 'uncrown-portfolio' ); ?></li>
      <li><span class="lg-dot"></span><?php esc_html_e( 'One member', 'uncrown-portfolio' ); ?></li>
    </ul>
  </header>
  <ol class="skill-chart">
    <?php foreach ( $stats as $row ) :
      $names = array();
      foreach ( $row['members'] as $m ) { $names[] = $m['name'] . ' ' . $m['level'] . '%'; }
      $label = sprintf( __( '%1$s: team average %2$d%%, %3$d members. %4$s', 'uncrown-portfolio' ), $row['name'], $row['average'], count( $row['members'] ), implode( ', ', $names ) );
    ?>
      <li class="skill-row" tabindex="0" style="--v:<?php echo (int) $row['average']; ?>" aria-label="<?php echo esc_attr( $label ); ?>">
        <span class="skill-name"><?php echo esc_html( $row['name'] ); ?></span>
        <span class="skill-track" aria-hidden="true">
          <span class="skill-fill"></span>
          <?php foreach ( $row['members'] as $m ) : ?><span class="skill-dot" style="--v:<?php echo (int) $m['level']; ?>"></span><?php endforeach; ?>
        </span>
        <span class="skill-value"><?php echo esc_html( $row['average'] ); ?>%<small><?php echo esc_html( sprintf( _n( '%d member', '%d members', count( $row['members'] ), 'uncrown-portfolio' ), count( $row['members'] ) ) ); ?></small></span>
        <span class="skill-tip" aria-hidden="true">
          <strong><?php echo esc_html( $row['name'] ); ?> · <?php echo esc_html( $row['average'] ); ?>% <?php esc_html_e( 'average', 'uncrown-portfolio' ); ?></strong>
          <?php foreach ( $row['members'] as $m ) : ?><span><?php echo esc_html( $m['name'] ); ?><b><?php echo esc_html( $m['level'] ); ?>%</b></span><?php endforeach; ?>
        </span>
      </li>
    <?php endforeach; ?>
  </ol>
  <details class="skills-table">
    <summary><?php esc_html_e( 'View as table', 'uncrown-portfolio' ); ?></summary>
    <div class="table-scroll"><table>
      <thead><tr><th scope="col"><?php esc_html_e( 'Skill', 'uncrown-portfolio' ); ?></th><th scope="col"><?php esc_html_e( 'Team average', 'uncrown-portfolio' ); ?></th><th scope="col"><?php esc_html_e( 'Members', 'uncrown-portfolio' ); ?></th></tr></thead>
      <tbody><?php foreach ( $stats as $row ) : $names = array(); foreach ( $row['members'] as $m ) { $names[] = $m['name'] . ' (' . $m['level'] . '%)'; } ?><tr><th scope="row"><?php echo esc_html( $row['name'] ); ?></th><td><?php echo esc_html( $row['average'] ); ?>%</td><td><?php echo esc_html( implode( ', ', $names ) ); ?></td></tr><?php endforeach; ?></tbody>
    </table></div>
  </details>
</div>
