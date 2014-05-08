;
(function () {
    var app = {
        init: function () {
            this.form.setup();
        },
        form: {
            setup: function () {
                this.emailChange();

                $('.apollo-back a').click(function (e) {
                    e.preventDefault();

                     $('.apollo-register:visible, .apollo-forgotten-password:visible').fadeOut(350, function(){
                    	$('.apollo-login').fadeIn(350);
                    	// $('.apollo').removeClass('register forgotten-password');
                    });
                });

                $('.apollo-register-account .password-link').click(function (e) {
                    e.preventDefault();

                    $('.apollo-login').fadeOut(350, function(){
                    	$('.apollo-forgotten-password').fadeIn(350, function(){
                    		$('.apollo-forgotten-password input:first').focus();
                    	});
                    	$('.apollo').addClass('forgotten-password');
                    });
                });

                 $('#apollo-forgotten-password-form').submit(function(e){
                	e.preventDefault();

                	app.form.handleForgottenPassword($(this));
                });
            },
            emailChange: function () {
                $('.email').change(function () {
                    var t = $(this),
                        md5 = MD5($.trim(t.val().toLowerCase())),
                        gravatar = 'http://www.gravatar.com/avatar/' + md5 + '?d=http://tidy.eideus.com/img/avatar.png&s=205';

                   	$('.apollo-image').css('backgroundImage', 'none');
                    $('<img />').attr('src', gravatar).imagesLoaded(function(){
                		$('.apollo-image').css('backgroundImage', 'url(' + gravatar + ')');
                    });
                    
                });
            },
            handleForgottenPassword: function(form){
            	if(app.checkUserAccount('forgottenPassword', form)){
            		$('.apollo-forgotten-password').fadeOut(350, function(){
						$('.apollo-password-reset').fadeIn(350);
					});
            	}
            	else {
					var fPassword = $('.apollo-forgotten-password'),
            			email = fPassword.find('[type="text"]:first').parents('.control-group');

            		email.addClass('error').find('input:first').popover({
            			title: 'Ooops!',
            			content: 'It looks like we don\'t have an account registered with that email address.',
            			trigger: 'manual',
            			placement: 'right'
            		}).popover('show');
            	}

				// Handle the user's details (data) here via AJAX...
            }
        },
        domReady: function () {},
        windowLoad: function () {}
    };

    app.init();
    $(function () {
            app.domReady();

            $(window).load(app.windowLoad);
        });

})(jQuery)