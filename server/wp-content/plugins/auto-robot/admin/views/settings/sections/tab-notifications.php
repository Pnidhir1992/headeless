<?php
$settings = get_option('auto_robot_global_settings');
$nonce = wp_create_nonce( 'robot_save_global_settings' );
$robot_campaign_per_page = isset($settings['robot-campaign-per-page']) ? $settings['robot-campaign-per-page'] : 10;
?>
<div id="notifications" class="robot-box-tab active" data-nav="notifications" >

    <div class="robot-box-header">
        <h2 class="robot-box-title"><?php esc_html_e( 'Email Notifications', Auto_Robot::DOMAIN ); ?></h2>
    </div>

    <form class="robot-settings-form" method="post" action="">

    <div class="robot-box-body">
        <div class="robot-box-settings-row">
            <div class="robot-box-settings-col-1">
                <span class="robot-settings-label"><?php esc_html_e( 'Campaigns Report', Auto_Robot::DOMAIN ); ?></span>
                <span class="robot-description"><?php esc_html_e( 'Choose the frequency of campaigns running report.', Auto_Robot::DOMAIN ); ?></span>
            </div>

            <div class="robot-box-settings-col-2">
                <div class="range-slider">
                    <input class="range-slider__range" type="range" value="<?php if(isset($settings['update_frequency'])){echo $settings['update_frequency'];}else{echo esc_html('100');}?>" min="0" max="500">
                    <span class="range-slider__value">100</span>
                </div>

                <span class="robot-description"><?php esc_html_e( 'Time Unit', Auto_Robot::DOMAIN ); ?></span>

                <div class="select-container">
                    <span class="dropdown-handle" aria-hidden="true">
                        <ion-icon name="chevron-down" class="robot-icon-down"></ion-icon>
                    </span>
                    <div class="select-list-container">
                        <button type="button" class="list-value" id="robot-field-unit-button" value="Minutes">
                            <?php
                            if(isset($settings['update_frequency_unit'])){
                                echo $settings['update_frequency_unit'];
                            }else{
                                esc_html_e( 'Minutes', Auto_Robot::DOMAIN );
                            }
                            ?>
                        </button>
                    <ul tabindex="-1" role="listbox" class="list-results robot-sidenav-hide-md" >
                        <li><?php esc_html_e( 'Minutes', Auto_Robot::DOMAIN ); ?></li>
                        <li><?php esc_html_e( 'Hours', Auto_Robot::DOMAIN ); ?></li>
                        <li><?php esc_html_e( 'Days', Auto_Robot::DOMAIN ); ?></li>
                    </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="robot-box-footer">

        <div class="robot-actions-right">

            <button id="robot-save-settings" class="robot-button robot-button-blue" type="button">
                <span class="robot-loading-text"><?php esc_html_e( 'Save Settings', Auto_Robot::DOMAIN ); ?></span>
            </button>

        </div>

    </div>

    </form>



</div>


