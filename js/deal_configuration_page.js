/* When document is loaded */
jQuery(document).ready(

	function($) 
	{
		
		/* Hide the feedback div */
		$('#feedback').hide();
		
		/* Any ajax forms on the page, should fire 
			this function when they are submitted */
		$('.ajax-form').live( 'submit', 
			function(e)
			{
			
				/* Do an AJAX post using:
						the method of the form as the type
						 the action as the url
						 the serialized form data as the data
					Also assign a success and error handler
					Set the return type to be a json object
				*/
				$.ajax(
					{
						type: $(this).attr('method'),
						url: $(this).attr('action'),
						data: $(this).serialize(),
						success: onSuccess,
						error: onError,
						dataType:'json'
					}
				);
				
				/* prevent the default submit action from occurring */
				e.preventDefault();
				
			}
			
		);
		
		/* If form submit was successfull */
		function onSuccess(data) {
			
			/* update the deal list container with the new deals */
			$('#deal-list-container').html(data['html']);

			/* fade out the feedback div, and fade it back in with the new status */
			$('#feedback').fadeOut(
				1000, 
				function() 
				{
				
					if(data['error']) $(this).html('<div class="error"><p>'+data['error']+'</p></div>');
					
					if(data['success']) $(this).html('<div class="updated"><p>'+data['success']+'</p></div>');
					
					$(this).fadeIn();
			
				}
			);
			
			/* If we just added a deal, and it was successfull we want to clear our form */
			
			if(!data['error'] && data['action'] == 'AddDeal') $('#add-deal').clearForm();
			
		}
		
		/* If theres an error, print some debuging info to the console */
		function onError(jqXHR, textStatus, errorThrown) {
		
			/* fade out the feedback div, and fade it back in with the new status */
			$('#feedback').fadeOut(
				1000, 
				function() 
				{
				
					$(this).html('<div class="error"><p>'+errorThrown+'</p></div>');
					
					
					$(this).fadeIn();
			
				}
			);
			
		}
		
		/* This is a handy function for automatically resetting a form by Mike Alsup */
		$.fn.clearForm = function() {
		
		  return this.each(function() {
		  
		    var type = this.type, tag = this.tagName.toLowerCase();
		    
		    if (tag == 'form')
		    {
		    
		      return $(':input',this).clearForm();
		    
		    }
		    
		    if (type == 'text' || type == 'password' || tag == 'textarea')
		    {
		      
		      this.value = '';
		   
		    }
		    else if (type == 'checkbox' || type == 'radio')
		    {
		      
		      this.checked = false;
			
			}
		    else if (tag == 'select')
		    {
		      
		      this.selectedIndex = 0;
		     
		     }
		     
		  });
		  
		};
	
	}

);