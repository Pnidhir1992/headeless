<?php
$source = isset( $_GET['source'] ) ? sanitize_text_field( $_GET['source'] ) : 'dashboard';
$id = isset( $_GET['id'] ) ? sanitize_text_field( $_GET['id'] ) : '';
// Campaign Settings
$settings = array();
if(!empty($id)){
    $model    = $this->get_single_model( $id );
    $settings = $model->settings;
    $settings['status'] = $model->status;
}
?>
<div class="robot-row-with-sidenav">

    <div class="robot-sidenav">

        <div class="robot-mobile-select">
            <span class="robot-select-content"><?php esc_html_e( 'General', Auto_Robot::DOMAIN ); ?></span>
            <ion-icon name="chevron-down" class="robot-icon-down"></ion-icon>
        </div>

        <ul class="robot-vertical-tabs robot-sidenav-hide-md">

            <li class="robot-vertical-tab">
                <a href="#" data-nav="dashboard"><?php esc_html_e( 'General', Auto_Robot::DOMAIN ); ?></a>
            </li>

            <li class="robot-vertical-tab">
                <a href="#" data-nav="schedule"><?php esc_html_e( 'Schedule', Auto_Robot::DOMAIN ); ?></a>
            </li>

            <li class="robot-vertical-tab">
                <a href="#" data-nav="post-images"><?php esc_html_e( 'Post Images', Auto_Robot::DOMAIN ); ?></a>
            </li>

            <li class="robot-vertical-tab">
                <a href="#" data-nav="post-content"><?php esc_html_e( 'Post Template', Auto_Robot::DOMAIN ); ?></a>
            </li>

            <li class="robot-vertical-tab">
                <a href="#" data-nav="post-status"><?php esc_html_e( 'Post Status', Auto_Robot::DOMAIN ); ?></a>
            </li>

            <li class="robot-vertical-tab">
                <a href="#" data-nav="category"><?php esc_html_e( 'Categories & Tags', Auto_Robot::DOMAIN ); ?></a>
            </li>

            <li class="robot-vertical-tab">
                <a href="#" data-nav="translate"><?php esc_html_e( 'Translate', Auto_Robot::DOMAIN ); ?></a>
            </li>

        </ul>

        <div class="robot-sidenav-settings">
          <a href="#run-campaign-popup" class="open-popup-campaign" data-effect="mfp-zoom-in">
            <button id="robot-run-button" class="robot-button robot-sidenav-hide-md" accesskey="p">
                <?php esc_html_e( 'Run Campaign', Auto_Robot::DOMAIN ); ?>
            </button>
          </a>
      </div>

    </div>

    <form class="robot-campaign-form" method="post" name="robot-campaign-form" action="">

    <div class="robot-box-tabs">
        <?php $this->template( 'campaigns/wizard/sections/tab-save',  $settings); ?>
        <?php $this->template( 'campaigns/wizard/sections/tab-schedule',  $settings); ?>
        <?php $this->template( 'campaigns/wizard/sections/tab-post-images', $settings); ?>
        <?php $this->template( 'campaigns/wizard/sections/tab-dashboard', $settings); ?>
        <?php $this->template( 'campaigns/wizard/sections/tab-post-content', $settings); ?>
        <?php $this->template( 'campaigns/wizard/sections/tab-post-status', $settings); ?>
        <?php $this->template( 'campaigns/wizard/sections/tab-category', $settings); ?>
        <?php $this->template( 'campaigns/wizard/sections/tab-translate', $settings); ?>
    </div>
        <input type="hidden" name="robot_selected_source" value="<?php echo esc_html($source); ?>">
        <input type="hidden" name="campaign_id" value="<?php echo esc_html($id); ?>">
    </form>
</div>

<div id="run-campaign-popup" class="white-popup mfp-with-anim mfp-hide">

		<div class="robot-box-header robot-block-content-center">
			<h3 class="robot-box-title type-title"><?php esc_html_e( 'Campaign Console', Auto_Robot::DOMAIN ); ?></h3>
		</div>

        <div class="robot-box-body robot-campaign-popup-body">
        </div>

        <div class="robot-box-footer robot-box-footer-center">
          <button type="button" class="robot-button robot-run-campaign-button">
              <span class="robot-loading-text"><?php esc_html_e( 'Run', Auto_Robot::DOMAIN ); ?></span>
          </button>
        </div>

		<img src="<?php echo esc_url(AUTO_ROBOT_URL.'/assets/images/robot.png'); ?>" class="robot-image robot-image-center" aria-hidden="true" alt="<?php esc_attr_e( 'Auto Robot', Auto_Robot::DOMAIN ); ?>">
</div>
