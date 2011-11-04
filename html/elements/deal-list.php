<!-- This is the deal list table -->

<table id="deal-list" class="wp-list-table widefat">

	<thead>
	
		<tr>
		
			<th scope="col" id="col-product_name"><?php _e( 'Product Name', self::PREFIX); ?></th>
			
			<th scope="col" id="col-sale_price"><?php _e( 'Sale Price', self::PREFIX); ?></th>
			
			<th scope="col" id="col-expires"><?php _e( 'Expires', self::PREFIX); ?></th>
			
			<th scope="col" colspan="2" id="col-actions"><?php _e( 'Actions', self::PREFIX); ?></th>
			
		</tr>
	
	</thead>
	
	<tbody>
	
		<?php
		
		/* Get all the deals */
		$deals = $this->GetDeals();
		
		
		/* If there are deals, loop through each one */
		if( is_array($deals) )
		{
		
			 foreach($deals as $deal)
			 {
			 ?>
			 	
			 	<tr class="row-deal-<?php echo $deal->id; ?>">
			 	
			 		<td><?php echo $deal->product_name; ?></td>
			 		
			 		<td>$<?php 
			 			if(function_exists('money_format'))
			 			{
			 				echo money_format( '%i', $deal->sale_price ); 
			 			}
			 			else
			 			{
			 				echo $deal->sale_price;
			 			}
			 		?></td>
			 		
			 		<td><?php echo date('l F jS Y', strtotime( $deal->expires ) ); ?></td>
			 		
					<td>
					
						<!-- This is the form that will let us toggle the deal status -->
			 			<form class="ajax-form" method="post" action="options-general.php?page=deal-configuration-page">
			 			
			 				<?php 
			 					if( $deal->enabled == true )
			 					{
			 						$button_text = __('Disable', self::PREFIX );
				 					echo '<input type="hidden" name="enabled" value="0" />';
				 				}
				 				else
				 				{
				 					$button_text = __('Enable', self::PREFIX );
				 					echo '<input type="hidden" name="enabled" value="1" />';
				 				}
			 				?>
			 				
			 				<input type="hidden" name="id" value="<?php echo $deal->id ?>" />
			 				
			 				<input type="hidden" name="<?php echo self::PREFIX . 'Action'; ?>" value="SetEnabledStatus" />
			 				
			 				<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('SetEnabledStatus'); ?>" />
			 				
			 				<input type="submit" value="<?php echo $button_text ?>" class="button-secondary" />
			 				
			 			</form>
			 			
			 		</td>
			 		
			 		<td>
			 		
			 			<!-- This is the form that will let us delete the deal -->
			 			<form  class="ajax-form" method="post" action="options-general.php?page=deal-configuration-page">
			 			
			 				<input type="hidden" name="id" value="<?php echo $deal->id ?>" />
			 				
			 				<input type="hidden" name="<?php echo self::PREFIX . 'Action'; ?>" value="DeleteDeal" />
			 				
			 				<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('DeleteDeal'); ?>" />
			 				
			 				<input type="submit" value="<?php _e( 'Delete Deal', self::PREFIX); ?>" class="button-delete" />
			 				
			 			</form>
			 			
			 		</td>
			 		
			 	</tr>
			 	
			 	<?php
			 }
		}
		?>
		 		
	</tbody>
	
</table>