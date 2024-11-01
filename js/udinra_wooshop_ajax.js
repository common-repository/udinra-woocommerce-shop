function udwooajax(){
	jQuery('#loadingmessage').show();
	jQuery('#udwoo_response').hide();
	document.getElementById("udinradec").value = '';
	jQuery.post(udinra_wooshop_script.ajaxurl, jQuery("#udwooform").serialize()
		,
		function(response_from_udinra_wooshop_function){
			jQuery("#udwoo_response").html(response_from_udinra_wooshop_function);
			jQuery('#udwoo_response').show();
			jQuery('#loadingmessage').hide();
		}
	);
}
function udwoonext() {
	document.getElementById("udinradec").value = 'Next';
	jQuery('#loadingmessage').show();
	jQuery('#udwoo_response').hide();
	jQuery.post(udinra_wooshop_script.ajaxurl, jQuery("#udwooform").serialize()
		,
		function(response_from_udinra_wooshop_function){
			jQuery("#udwoo_response").html(response_from_udinra_wooshop_function);
			jQuery('#udwoo_response').show();
			jQuery('#loadingmessage').hide();
		}
	);
}
function udwooprev() {
	document.getElementById("udinradec").value = 'Prev';
	jQuery('#loadingmessage').show();
	jQuery('#udwoo_response').hide();
	jQuery.post(udinra_wooshop_script.ajaxurl, jQuery("#udwooform").serialize()
		,
		function(response_from_udinra_wooshop_function){
			jQuery("#udwoo_response").html(response_from_udinra_wooshop_function);
			jQuery('#udwoo_response').show();
			jQuery('#loadingmessage').hide();
		}
	);
}
