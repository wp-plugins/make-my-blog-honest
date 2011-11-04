jQuery(document).ready(function($) {
	
	$('body').append('<div id="mmbhg-guide"><div class="plugin-options"><form method="post"><label for="StepSelect">'+mmbhg.txt_step_select+'</label><select id="mmbhg-step" name="mmbhg_step"></select><input type="hidden" name="action" value="ChangeStep" /><a href="#" id="mmbhg-prev">'+mmbhg.txt_prev+'</a> | <a href="#" id="mmbhg-next">'+mmbhg.txt_next+'</a></form></div>');
	
	for(step in mmbhg_steps)
	{
		$('#mmbhg-step').append('<option value="'+step+'">'+mmbhg_steps[step]+'</option>');
	}
	
	if(mmbhg.current_step)
	{
		$('#mmbhg-step').val(mmbhg.current_step);
	}
	
	$('#mmbhg-step').change(function(e) {
		$(this).closest('form').submit();
		e.preventDefault();		
	});
	
	$('#mmbhg-next').click(function(e) {
		
		var next_item = $('#mmbhg-step option:selected').nextAll('option:first');
		
		if(0 == next_item.length) next_item = $('#mmbhg-step option:first');
		
		$('#mmbhg-step').val(next_item.val());
		
		$('#mmbhg-step').trigger('change');
		
		e.preventDefault();
		
	});
	
	$('#mmbhg-prev').click(function(e) {
		
		var prev_item = $('#mmbhg-step option:selected').prevAll('option:first');
		
		if(0 == prev_item.length) prev_item = $('#mmbhg-step option:last');
		
		$('#mmbhg-step').val(prev_item.val());
		
		$('#mmbhg-step').trigger('change');
		
		e.preventDefault();
		
	});
		
});