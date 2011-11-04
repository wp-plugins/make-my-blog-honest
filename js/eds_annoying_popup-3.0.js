/* When document is loaded */
jQuery(document).ready(
	
	function($) {
		
		/* Add a div to the page to show the popup text */
		$('body').prepend( '<div style="height:100px;"><div id="eds_annoying_popup" style="margin:0 auto;width:1000px;padding:25px 0;text-align:center;background:#000;background:rgba(0,0,0,0.8);color:#FFF;font-family:sans-serif; font-weight:bold; font-size:32px;"">&nbsp;</div></div>' );
		
		/* Update the deal */
		UpdateDeal();
		
		function UpdateDeal()
		{
		
			/* Create a new object to post to our plugin */
			my_data = new Object();
			
			/* Set the action we want to perform. Prefix the Action variable 
				with the plugin name that we received through wp_localize_script */
			my_data[eds_annoying_popup_vars.prefix+'Action'] = 'GetRandomDeal';
			
			/* Set the _wpnonce value that we received through wp_localize_script */
			my_data['_wpnonce'] = eds_annoying_popup_vars._wpnonce;
			
			
			/* Do an AJAX post */
			$.ajax(
				{
					type: 'POST',
					data: my_data,
					success: onSuccess,
					error: onError,
					dataType:'json'
				}
			);
		
		}
		
		/* If getting deal was successful */
		function onSuccess(data)
		{

			updateDisplay(data.html);
			
		}
		
		/* Fade out the popup and fade back in with new deal */
		function updateDisplay(text) 
		{
			
			$('#eds_annoying_popup').fadeOut(
			
				500,
			
				function() {
					
					$(this).html( text );
					
					$(this).fadeIn(
						
						500,
						
						function() {
							
							setTimeout( UpdateDeal, 5000 );
						
						}		
					)
				}	
			);
		}
		
		/* If theres an error, you can handle it here, or you can print some
			debugging info to the console. */
		function onError(jqXHR, textStatus, errorThrown) {
			
			updateDisplay(eds_annoying_popup_vars.text_error); 
			
		}
		
	}
	
);