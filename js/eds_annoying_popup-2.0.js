/* When document is loaded */
jQuery(document).ready(
	
	function($) {
		
		/* Add a div to the page to show the popup text.
			Default popup text is a localized variable received through
			wp_localize_script */
		$('body').prepend('<div id="eds_annoying_popup" style="margin:0 auto;width:1000px;padding:25px 0;text-align:center;background:#000;background:rgba(0,0,0,0.8);color:#FFF;font-family:sans-serif; font-weight:bold; font-size:32px;">' + eds_annoying_popup_vars.the_text + '</div>');
		
		/* Hide it initially */
		$('#eds_annoying_popup').hide();
		
		/* Then show it */
		$('#eds_annoying_popup').delay(3000).slideDown(1000);
		
	}
	
);