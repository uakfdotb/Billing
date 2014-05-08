
$(function() {

    var order = {}, login = false;

    // [event, selector, method]
    order.formListeners = [
        ['change', 'input, select', 'refreshInputState'],
        ['change', 'input[name=domain_configuration]', 'refreshPayMethod'],
        ['click', '.login-link', 'toggleLogin'],
        ['click', '#login-button', 'postLogin'],
        ['click', 'button.close', 'closeAlert']
    ];

    /*** GENERIC FUNCTIONS ***/

    order.registerListeners = function(listeners) {
        $.each(listeners, function(index, listener){ 
            $('#order-form').on(listener[0], listener[1], order[listener[2]]);
        });
    };

    /*** SPECIFIC FUNCTIONS ***/

    order.getDomainType = function() {
        return $('input[name=domain_configuration]:checked').attr('id');
    };

    order.filterPayMethod = function() {
        return $(this).attr('data-domain-selection') == order.getDomainType();
    };

    order.enableLogin = function() {
        $('.fieldset.new-user').slideUp();
        $('.fieldset').filter(':last').find('.legend span').text('2');
        $('#login-button').fadeIn();
        $('#already-registered .login-link').toggle()
        $('#already-registered span').toggle()
    };

    order.disableLogin = function() {
        $('.fieldset.new-user').slideDown();
        $('.fieldset').filter(':last').find('.legend span').text('4');
        $('#login-button').fadeOut();
        $('#already-registered .login-link').toggle()
        $('#already-registered span').toggle()
        $('#login-warning').slideUp();
        $('#login-failed').slideUp();
    };

    /*** EVENT HANDLING FUNCTIONS ***/

    order.refreshInputState = function() {
        var val = $(this).val(),
            valid = (typeof val != 'string') || (val === '');
        valid ? $(this).removeClass('has-success') : $(this).addClass('has-success');
    };

    order.refreshPayMethod = function() {
        var $payMethodDivs = $('div[data-domain-selection]');
        var $selected = $payMethodDivs.filter(order.filterPayMethod).show();
        $payMethodDivs.not($selected).hide();
    };

    order.toggleLogin = function() {
        login = !login;
        login ? order.enableLogin() : order.disableLogin();
        return false;
    };

    order.postLogin = function() {
        if (($('#email-input').val() == '') || ($('#password-input').val() == '')) {
            return $('#login-warning').slideDown() && false;
        }
        $('#login-warning').slideUp();
        $('#login-button').attr('disabled', 'disabled');
        $('div.checkout').css('cursor', 'wait');
        $('#login-failed').slideUp();
        $.post(window.location.href.replace('order', 'order_login'), {
            token: $('#order-form input[name=token]').val(),
            email: $('#order-form input[name=email]').val(),
            password: $('#order-form input[name=password]').val()
        })
        .always(function() {
            $('#login-button').removeAttr('disabled');
            $('div.checkout').css('cursor', 'auto');
        })
        .fail(function() {
            $('#login-failed').slideDown();
        })
        .done(function(response) {
            if (!response.success) {
                return $('#login-failed').slideDown();
            }
            $('#login-success').slideDown();
            $('#already-registered').slideUp();
            $('input.order-submit').val('Order Now');
            $('strong.order-submit').text('Order Now');
        });
    };

    order.closeAlert = function() {
        $(this).closest('div.alert').slideUp();
    };

    /*** INITIALIZATION ***/

    order.init = function() {
        order.refreshPayMethod();
        order.registerListeners(order.formListeners);
        $('input, select', '#order-form').trigger('change');

        var $body = $('body');
        $body.css('height', 'auto');
        $body.css('height', $body.height());
    };

    order.init();

});
