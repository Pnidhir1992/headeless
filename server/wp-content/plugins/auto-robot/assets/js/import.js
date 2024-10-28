(function($){

    "use strict";

    var AutoRobotImport = {

        init: function()
        {
            // Document ready.
            this._bind();
        },

        /**
         * Binds events for the Auto Robot Import.
         *
         * @since 1.0.0
         * @access private
         * @method _bind
         */
        _bind: function()
        {
            $( document ).on('click', '.robot-vertical-tab a', AutoRobotImport._switchTabs );
        },

        /**
         * Switch Tabs
         *
         */
        _switchTabs: function( event ) {

            event.preventDefault();

            var tab = '#' + $(this).data('nav');

            $('.robot-vertical-tab').removeClass('current');
            $(this).parent().addClass('current');

            $('.robot-box-tab').removeClass('active');
            $('.robot-box-tabs').find(tab).addClass('active');

        },

    };

    /**
     * Initialize AutoRobotImport
     */
    $(function(){
        AutoRobotImport.init();
    });

})(jQuery);
