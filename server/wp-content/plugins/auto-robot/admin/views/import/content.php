<div class="robot-row-with-sidenav">

    <div class="robot-sidenav">
        <div class="robot-mobile-select">
            <span class="robot-select-content"><?php esc_html_e( 'Global Settings', Auto_Robot::DOMAIN ); ?></span>
            <ion-icon name="chevron-down" class="robot-icon-down"></ion-icon>
        </div>

        <ul class="robot-vertical-tabs robot-sidenav-hide-md">

            <li class="robot-vertical-tab current">
                <a href="#" data-nav="import"><?php esc_html_e( 'Import', Auto_Robot::DOMAIN ); ?></a>
            </li>

            <li class="robot-vertical-tab">
                <a href="#" data-nav="export"><?php esc_html_e( 'Export', Auto_Robot::DOMAIN ); ?></a>
            </li>

        </ul>

    </div>

    <div class="robot-box-tabs">
         <?php $this->template( 'import/sections/tab-import' ); ?>
         <?php $this->template( 'import/sections/tab-export' ); ?>
    </div>
</div>