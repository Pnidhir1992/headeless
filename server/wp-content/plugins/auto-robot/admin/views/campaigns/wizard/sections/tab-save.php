<?php
$status = isset( $settings['status'] ) ? sanitize_text_field( $settings['status'] ) : 'draft';
?>
<div id="auto-robot-builder-status" class="robot-box robot-box-sticky">
    <div class="robot-box-status">
        <div class="robot-status">
            <div class="robot-status-module">
                <?php esc_html_e( 'Status', Auto_Robot::DOMAIN ); ?>
                    <?php
                    if( $status === 'draft'){
                        ?>
                    <span class="robot-tag robot-tag-draft">
                        <?php esc_html_e( 'draft', Auto_Robot::DOMAIN ); ?>
                    </span>
                    <?php
                    }else if($status === 'publish'){
                        ?>
                    <span class="robot-tag robot-tag-published">
                       <?php esc_html_e( 'published', Auto_Robot::DOMAIN ); ?>
                    </span>
                    <?php
                    }
                    ?>
            </div>
            <div class="robot-status-changes">

            </div>
        </div>
        <div class="robot-actions">
            <button id="robot-campaign-save" class="robot-button" type="button">
                <span class="robot-loading-text">
                    <ion-icon name="reload-circle"></ion-icon>
                    <span class="button-text campaign-save-text">
                        <?php
                        if($status === 'publish'){
                            echo esc_html( 'unpublish', Auto_Robot::DOMAIN );
                        }else{
                            echo esc_html( 'save draft', Auto_Robot::DOMAIN );
                        }
                        ?>
                    </span>
                </span>
            </button>
            <button id="robot-campaign-publish" class="robot-button robot-button-blue" type="button">
                <span class="robot-loading-text">
                    <ion-icon name="save"></ion-icon>
                    <span class="button-text campaign-publish-text">
                        <?php
                        if($status === 'publish'){
                            echo esc_html( 'update', Auto_Robot::DOMAIN );
                        }else{
                            echo esc_html( 'publish', Auto_Robot::DOMAIN );
                        }
                        ?>
                    </span>
                </span>
            </button>
        </div>
    </div>
</div>
