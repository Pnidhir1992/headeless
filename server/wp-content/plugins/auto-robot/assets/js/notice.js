(function($){

    "use strict";

    var AutoRobotNotice = {

        init: function()
        {
            // Document ready.
            this._bind();
        },

        /**
         * Binds events for the Auto Robot Notice.
         *
         * @since 1.0.0
         * @access private
         * @method _bind
         */
        _bind: function()
        {
            $( document ).on('click', '.robot-notice-skip', AutoRobotNotice._skip );

        },

        /**
         * Skip opt in
         *
         */
        _skip: function( event ) {

            event.preventDefault();

            console.log('click skip.');

            $.ajax({
                url  : Auto_Robot_Data.ajaxurl,
                type : 'POST',
                dataType: 'json',
                data : {
                    action       : 'auto_robot_skip_premium',
                    type         : 'skip',         
                    _ajax_nonce  : Auto_Robot_Data._ajax_nonce,
                },
                beforeSend: function() {
                },
            })
            .fail(function( jqXHR ){
                console.log( jqXHR.status + ' ' + jqXHR.responseText);
            })
            .done(function ( option ) {
                if( false === option.success ) {
                    console.log(option);
                } else {
                    console.log(option);
                    window.location.href = "admin.php?page=auto-robot";
                }

            });

        },    

    };

    /**
     * Initialize AutoRobotNotice
     */
    $(function(){
        AutoRobotNotice.init();
    });

})(jQuery);
