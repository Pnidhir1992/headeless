<?php
$addons = Auto_Robot_Admin_Addons::get_list();
?>
<div class="wphobby-wrap">
<div id="robot-admin-addons-list">
	<div class="list">
        <?php foreach ( $addons as $addon => $data ) { ?>
            <div class="addon-container">
				<div class="addon-item">
					<div class="details robot-clear" style="height: 202px;">
						<img src="<?php echo esc_html($data['thumbnail']); ?>">
							<h5 class="addon-name">
                                <?php echo esc_html($data['name']); ?>
                            </h5>
							<p class="addon-desc">
                                <?php echo esc_html($data['desc']); ?>
                            </p>
					</div>
					<div class="actions robot-clear">
						<div class="upgrade-button">
							<a href="<?php echo esc_url('https://wpautorobot.com/pricing') ?>" target="_blank" rel="noopener noreferrer" class="robot-btn robot-btn-light-grey robot-upgrade-modal">
                                <?php esc_html_e( 'Upgrade Now', Auto_Robot::DOMAIN ); ?>
                            </a>
					</div>
					</div>
				</div>
			</div>
        <?php } ?>
    </div>
</div>
</div>

