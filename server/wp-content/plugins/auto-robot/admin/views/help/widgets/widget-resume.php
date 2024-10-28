<?php
$total_modules = auto_robot_total_forms();
$count_active  = auto_robot_total_forms( 'publish' );
$last_run      = auto_robot_campaigns_last_run();
?>
<div class="robot-box robot-summary">
    
	<div class="robot-summary-image-space" aria-hidden="true"></div>

    <div class="robot-summary-segment">

        <div class="robot-summary-details">

            <?php if ( 0 < $total_modules ) { ?>
                <span class="robot-summary-large"><?php echo esc_html( $total_modules ); ?></span>
            <?php } else { ?>
                <span class="robot-summary-large">0</span>
            <?php } ?>

            <?php if ( 1 === $total_modules ) { ?>
                <span class="robot-summary-sub"><?php esc_html_e( 'Active Campaign', Auto_Robot::DOMAIN ); ?></span>
            <?php } else { ?>
                <span class="robot-summary-sub"><?php esc_html_e( 'Active Campaigns', Auto_Robot::DOMAIN ); ?></span>
            <?php } ?>

            <?php if ( $total_modules > 0 ) { ?>
                <span class="robot-summary-detail"><strong><?php echo esc_html( $last_run ); ?></strong></span>
            <?php } else { ?>
                <span class="robot-summary-detail"><strong><?php esc_html_e( 'Never', Auto_Robot::DOMAIN ); ?></strong></span>
            <?php } ?>

            <span class="robot-summary-sub"><?php esc_html_e( 'Last Run', Auto_Robot::DOMAIN ); ?></span>

        </div>

    </div>

</div>
