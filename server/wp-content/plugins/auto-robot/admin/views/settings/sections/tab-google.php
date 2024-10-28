<?php
$settings = get_option('auto_robot_global_settings');
$nonce = wp_create_nonce( 'robot_save_global_settings' );
$robot_google_search_api_key = isset($settings['robot_google_search_api_key']) ? $settings['robot_google_search_api_key'] : '';
$robot_google_search_engine_id = isset($settings['robot_google_search_engine_id']) ? $settings['robot_google_search_engine_id'] : '';
?>
<div id="google" class="robot-box-tab" data-nav="google" >

    <div class="robot-box-header">
        <h2 class="robot-box-title"><?php esc_html_e( 'Google Search API', Auto_Robot::DOMAIN ); ?></h2>
    </div>

    <form class="robot-settings-form" method="post" action="">

    <div class="robot-box-body">
        <div class="robot-box-settings-row">

            <div class="robot-box-settings-col-1">
                <span class="robot-settings-label"><?php esc_html_e( 'API Key', Auto_Robot::DOMAIN ); ?></span>
                <span class="robot-description"><?php esc_html_e( 'Choose the number of items to show per page on your campaigns listing pages.', Auto_Robot::DOMAIN ); ?></span>
            </div>

            <div class="robot-box-settings-col-2">

                <div class="robot-form-field">
                    <input type="text"
                        name="robot_google_search_api_key"
                        placeholder=""
                        value="<?php echo esc_attr( $robot_google_search_api_key ); ?>"
                        min="1"
                        id="robot_google_search_api_key"
                        class="robot-form-control robot-required robot-input-sm robot-field-has-suffix"/>
                </div>

                <p>
                    <span class="robot-description">
                        <a target="_blank" href="https://wpautorobot.com/document/api-settings/how-to-setup-google-custom-search-api-key-settings/">
                            <?php esc_html_e( 'Here is the tutorial to setup google custom search api', Auto_Robot::DOMAIN ); ?>
                        </a>
                    </span>
                </p>

            </div>

        </div>

        <div class="robot-box-settings-row">

            <div class="robot-box-settings-col-1">
                <span class="robot-settings-label"><?php esc_html_e( 'Programmable Search Engine ID', Auto_Robot::DOMAIN ); ?></span>
                <span class="robot-description"><?php esc_html_e( 'The Programmable Search Engine ID to use for this request', Auto_Robot::DOMAIN ); ?></span>
            </div>

            <div class="robot-box-settings-col-2">

                <div class="robot-form-field">
                    <input type="text"
                        name="robot_google_search_engine_id"
                        placeholder=""
                        value="<?php echo esc_attr( $robot_google_search_engine_id ); ?>"
                        min="1"
                        id="robot_google_search_engine_id"
                        class="robot-form-control robot-required robot-input-sm robot-field-has-suffix"/>
                </div>

                <p>
                    <span class="robot-description">
                        <a target="_blank" href="https://wpautorobot.com/document/api-settings/how-to-setup-google-custom-search-engine-site-search-cse-settings/">
                            <?php esc_html_e( 'Here is the tutorial to setup custom search engine', Auto_Robot::DOMAIN ); ?>
                        </a>
                    </span>
                </p>

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


