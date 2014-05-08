var Theme = function () {

	var chartColors, validationRules = getValidationRules ();

	// Black & Orange
	//chartColors = ["#FF9900", "#333", "#777", "#BBB", "#555", "#999", "#CCC"];

	// Ocean Breeze
	//chartColors = ['#94BA65', '#2B4E72', '#2790B0', '#777','#555','#999','#bbb','#ccc','#eee'];

	// Fire Starter
	//chartColors = ['#750000', '#F90', '#777', '#555','#002646','#999','#bbb','#ccc','#eee'];

	// Mean Green
	//chartColors = ['#5F9B43', '#DB7D1F', '#BA4139', '#777','#555','#999','#bbb','#ccc','#eee'];

	// Colors
	// chartColors = ['#f44646', '#46b5f4', '#46f47b', '#596c7c','#fef789','#999','#bbb','#ccc','#eee'];

	return { init: init, validationRules: validationRules };

	function init () {		
		enhancedAccordion ();

		if ($.fn.lightbox) { 
			$('.ui-lightbox').lightbox();			
		}

		if ($.fn.cirque) {
			$('.ui-cirque').cirque ({  });
		}

		$('#wrapper').append ('<div class="push"></div>');
	}

	function enhancedAccordion () {
		$('.accordion').on('show', function (e) {
	         $(e.target).prev('.accordion-heading').parent ().addClass('open');
	    });
	
	    $('.accordion').on('hide', function (e) {
	        $(this).find('.accordion-toggle').not($(e.target)).parents ('.accordion-group').removeClass('open');
	    });
	    
	    $('.accordion').each (function () {	    	
	    	$(this).find ('.accordion-body.in').parent ().addClass ('open');
	    });
	}
	
	function getValidationRules () {
		var custom = {
	    	focusCleanup: false,
			
			wrapper: 'div',
			errorElement: 'span',
			
			highlight: function(element) {
				$(element).parents ('.control-group').removeClass ('success').addClass('error');
			},
			success: function(element) {
				$(element).parents ('.control-group').removeClass ('error').addClass('success');
				$(element).parents ('.controls:not(:has(.clean))').find ('div:last').before ('<div class="clean"></div>');
			},
			errorPlacement: function(error, element) {
				error.appendTo(element.parents ('.controls'));
			}
	    	
	    };
	    
	    return custom;
	}
	
}();

// use as theme: 'newTheme'
kendo.dataviz.ui.registerTheme('newTheme', {
    "chart": {
        "title": {
            "color": "#777777"
        },
        "legend": {
            "labels": {
                "color": "#777777"
            }
        },
        "chartArea": {},
        "seriesDefaults": {
            "labels": {
                "color": "#000"
            }
        },
        "axisDefaults": {
            "line": {
                "color": "#c7c7c7"
            },
            "labels": {
                "color": "#777777"
            },
            "minorGridLines": {
                "color": "#c7c7c7"
            },
            "majorGridLines": {
                "color": "#c7c7c7"
            },
            "title": {
                "color": "#777777"
            }
        },
        "seriesColors": [
            "#f44646",
            "#46b5f4",
            "#596c7c",
            "#fef789",
            "#e61e26",
            "#46f47b"
        ],
        "tooltip": {}
    },
    "gauge": {
        "pointer": {
            "color": "#f55959"
        },
        "scale": {
            "rangePlaceholderColor": "#747b80",
            "labels": {
                "color": "#777"
            },
            "minorTicks": {
                "color": "#777"
            },
            "majorTicks": {
                "color": "#777"
            },
            "line": {
                "color": "#777"
            }
        }
    }
});