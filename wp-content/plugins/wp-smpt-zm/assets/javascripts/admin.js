jQuery( document ).ready(function( $ ){

    $('#wp_mail_smtp_zm_send_test_button').on('click', function(){

        var $this = $(this),
            $to = $('#wp_mail_smtp_zm_to').val(),
            $original_value = $this.val();

        $this.val( _wp_mail_smtp_zm.sending );
        $this.attr('disabled', true);

        $.ajax({
            url: ajaxurl,
            dataType: 'json',
            type: 'POST',
            data: {
                action: 'wp_mail_smtp_zm_ajax',
                _wpnonce: $this.data('send_test_button_nonce'),
                to: $to
            },
            success: function( msg ){

                $( '#sent_target' ).remove();
                $this.after( msg.message );

                $this.val( $original_value );
                $this.attr('disabled', false);

            }
        });

    });

    $('#wp_mail_smtp_zm_gmail_preset').on('click', function( e ){
        e.preventDefault();

        r = confirm( _wp_mail_smtp_zm.before_set_preset + 'Gmail.' );

        if ( r == true){

            var $this = $( this );

            $('#wp_mail_smtp_zm_mailer_smtp').prop('checked',true);
            $('#wp_mail_smtp_zm_smtp_host').val( 'smtp.gmail.com' );
            $('#wp_mail_smtp_zm_smtp_port').val( '465' );
            $('#wp_mail_smtp_zm_encryption_ssl').prop('checked', true );
            $('#wp_mail_smtp_zm_authentication_yes').prop('checked', true );

            alert( _wp_mail_smtp_zm.after_set_preset );
        }

    });

});