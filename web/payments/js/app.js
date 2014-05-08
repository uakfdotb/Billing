var current_input = null;
function set_radio($inputid) {
	if(current_input == null) {
		$("input#" + $inputid).click();
		$("input#" + $inputid).parent().addClass('focused');
		current_input = $inputid;
	}
	else {
		if(current_input != $inputid) {
			$("#" + current_input).parent().removeClass('focused');
			$("input#" + $inputid).click();
			$("input#" + $inputid).parent().addClass('focused');
			current_input = $inputid;
		}
	}
	$('.submit-button').prop('disabled',false);
	proceedToPayment();
}

$(document).ready(function(){ 
	
});