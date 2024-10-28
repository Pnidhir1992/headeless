<?php

?>
<div class="robot-row-with-sidenav">

    <div class="robot-sidenav">
        <div class="robot-mobile-select">
            <span class="robot-select-content"><?php esc_html_e( 'API Settings', Auto_Robot::DOMAIN ); ?></span>
            <ion-icon name="chevron-down" class="robot-icon-down"></ion-icon>
        </div>

        <ul class="robot-vertical-tabs robot-sidenav-hide-md">

            <li class="robot-vertical-tab current">
                <a href="#" data-nav="robot-apps"><?php esc_html_e( 'API Settings', Auto_Robot::DOMAIN ); ?></a>
            </li>

            <li class="robot-vertical-tab">
                <a href="#" data-nav="robot-others"><?php esc_html_e( 'More APIs', Auto_Robot::DOMAIN ); ?></a>
            </li>

        </ul>

    </div>

    <div class="robot-box-tabs">
           <?php $this->template( 'integrations/sections/tab-apps' ); ?>
           <?php $this->template( 'integrations/sections/tab-others' ); ?>
    </div>
</div>