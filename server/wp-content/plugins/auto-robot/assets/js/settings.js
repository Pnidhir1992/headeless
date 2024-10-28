(function($){

    "use strict";

    var AutoRobotSettings = {

        init: function()
        {
            this._bind();
            $( document ).ready( AutoRobotSettings._rangeSlider() );
            $( document ).ready( AutoRobotSettings._mainSelect() );

        },

        /**
         * Binds events for the Auto Robot Settings.
         *
         * @since 1.0.0
         * @access private
         * @method _bind
         */
        _bind: function()
        {
            $( document ).on('click', '#robot-save-settings', AutoRobotSettings._saveSettings );
            $( document ).on('click', '.robot-vertical-tab a', AutoRobotSettings._switchTabs );
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

        /**
         * Range Slider
         *
         */
         _rangeSlider: function( ) {

            var slider = $('.range-slider'),
                range = $('.range-slider__range'),
                value = $('.range-slider__value');

            slider.each(function(){

                value.each(function(){
                    var value = $(this).prev().attr('value');
                    $(this).html(value);
                });

                range.on('input', function(){
                    $(this).next(value).html(this.value);
                });
            });

        },

        /**
         * Main Select
         *
         */
         _mainSelect: function( ) {

            // onClick new options list of new select
            var newOptions = $('.list-results > li');
            newOptions.on('click', function(){
                $('.list-value').text($(this).text());
                $('.list-value').val($(this).text());
                $('.list-results > li').removeClass('selected');
                $(this).addClass('selected');
            });

            var aeDropdown = $('.select-list-container');
            aeDropdown.on('click', function(){
                $('.list-results').toggleClass('robot-sidenav-hide-md');
            });

            var robotDropdown = $('.dropdown-handle');
            robotDropdown.on('click', function(){
                $('.list-results').toggleClass('robot-sidenav-hide-md');
            });



        },

        /**
         * Save Settings
         *
         */
        _saveSettings: function( event ) {

            event.preventDefault();

            $(this).html('<div class="text-center"><div class="loader1"><span></span><span></span><span></span><span></span><span></span></div></div>');


            // set post form data
            var formdata = $('.robot-settings-form').serializeArray();
            var fields = {};
            $(formdata ).each(function(index, obj){
                fields[obj.name] = obj.value;
            });
            fields['update_frequency'] = $('.range-slider__value').text();
            fields['update_frequency_unit'] = $('#robot-field-unit-button').val();

            $.ajax({
                    url  : Auto_Robot_Data.ajaxurl,
                    type : 'POST',
                    dataType: 'json',
                    data : {
                        action       : 'auto_robot_save_settings',
                        fields_data  : fields,
                        _ajax_nonce  : Auto_Robot_Data._ajax_nonce,
                    },
                    beforeSend: function() {
                    },
                })
                .fail(function( jqXHR ){
                    console.log( jqXHR.status + ' ' + jqXHR.responseText);
                })
                .done(function ( options ) {
                    if( false === options.success ) {
                        console.log(options);
                    } else {
                        console.log(options);
                        $('#robot-save-settings').html('<span class="robot-loading-text">Save Settings</span>');
                        AutoRobotSettings._displayNoticeMessage(options.data);
                    }
                });

        },

        /**
         * Display Notice Message
         *
         */
        _displayNoticeMessage: function(message) {

            var html = '<div class="message-box robot-message-box success">' + message + '</div>';
            $(html).appendTo(".robot-wrap").slideDown('slow').animate({opacity: 1.0}, 2500).slideUp('slow');

        },
    };

    /**
     * Initialize AutoRobotSettings
     */
    $(function(){
        AutoRobotSettings.init();
    });

})(jQuery);
