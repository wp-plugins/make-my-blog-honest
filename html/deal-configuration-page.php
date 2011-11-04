<div class="wrap">

	<h2>
	
		<?php _e( 'Deal Configuration Page', self::PREFIX ) ?>
		
	</h2>
	
	<!-- This is the feedback div -->
	<div id="feedback">
	
		<?php
			
			if( array_key_exists( 'error', $this->output) )
			{
				echo '<div class="error"><p>'.$this->output['error'].'</p></div>';
			}
			
			if( array_key_exists( 'success', $this->output) )
			{
				echo '<div class="updated"><p>'.$this->output['success'].'</p></div>';
			}
			
		?>
		
	</div>
	
	<h3>
	
		<?php _e( 'Add a Deal', 'B2Template' ) ?>
		
	</h3>
	
	<!-- This is the add a deal form -->
	<form id="add-deal" class="ajax-form" method="post" action="options-general.php?page=deal-configuration-page">
	
		<?php

			$product_name = ( isset( $_POST['product_name'] ) ) ? 
				$_POST['product_name'] : '';
				
			$sale_price =  ( isset( $_POST['sale_price'] ) ) ? 
				$_POST['sale_price'] : '';
				
			$enabled =  ( isset( $_POST['enabled'] ) ) ? 
				true : false;
				
			$expires =  ( isset( $_POST['expires'] ) ) ? 
				$_POST['expires'] : false;
				
		?>
		
		<p>
		
			<label for="product_name"><?php _e( 'Product Name', self::PREFIX ); ?></label>
			
			<input type="text" id="product_name" name="product_name" value="<?php echo $product_name; ?>" />
		
		</p>
		
		<p>
		
			<label for="sale_price"><?php _e( 'Sale Price', self::PREFIX ); ?> $</label>
			
			<input type="text" id="sale_price" name="sale_price" value="<?php echo $sale_price; ?>" />
		
		</p>
		
		<p>
		
			<label for="enabled"><?php _e( 'Enabled', self::PREFIX ); ?></label>
			
			<input type="checkbox" id="enabled" name="enabled" <?php echo ( $enabled !== false ) ? 'checked="checked"' : ''; ?> value="1" />
		
		</p>

		
		<fieldset id="expires">
		
			<?php
			
				$days = array( 
					'01','02','03','04','05','06','07','08','09','10',
					'11','12','13','14','15','16','17','18','19','20',
					'21','22','23','24','25','26','27','28','29','30','31'
				);
				
				$months = array(
						'01'=> __( 'January', self::PREFIX),
						'02'=> __( 'February', self::PREFIX),
						'03'=> __( 'March', self::PREFIX),
						'04'=> __( 'April', self::PREFIX),
						'05'=> __( 'May', self::PREFIX),
						'06'=> __( 'June', self::PREFIX),
						'07'=> __( 'July', self::PREFIX),
						'08'=> __( 'August', self::PREFIX),
						'09'=> __( 'September', self::PREFIX),
						'10'=> __( 'October', self::PREFIX),
						'11'=> __( 'November', self::PREFIX),
						'12'=> __( 'December', self::PREFIX)
					);
					
					$years = array(
						'2010', '2011', '2012', '2013', '2014', '2015',
						'2016', '2017', '2018', '2019', '2020'
					);
		
			?>
			<legend><?php _e( 'Expires', self::PREFIX ); ?></legend>
			
			<select name="expires[Day]">
			
				<?php
				
					foreach($days as $day)
					{

						$selected = '';

						if ( 
							isset($expires['Day']) && 
							$expires['Day'] == $day 
						) 
						{
						
							$selected = 'selected="selected"';
							
						}

						echo '<option value="'.$day.'" ' . $selected . '>' . $day . '</option>';

					}
					
				?>
				
			</select>
			
			<select name="expires[Month]">
			
				<?php
					
					foreach($months as $month_num => $month_name)
					{
					
						$selected = '';
						
						if( 
							isset( $expires['Month'] ) && 
							$expires['Month'] == $month_num 
						) 
						{
						
							$selected = 'selected="selected"';
						
						}
						
						echo '<option value="' . $month_num . '" ' . $selected . '>' . $month_name . '</option>';
						
					}
					
				?>
				
			</select>
			
			<select name="expires[Year]">
			
				<?php
						
					foreach($years as $year)
					{
					
						$selected = '';
						
						if( 
							isset( $expires['Year'] ) && 
							$expires['Year'] == $year 
						)
						{
						
							 $selected = 'selected="selected"';
						
						}
						
						echo '<option value="' . $year . '" ' . $selected . '>' . $year . '</option>';
					}
					
				?>
				
			</select>
			
		</fieldset>

		<p>
		
			<input type="hidden" name="<?php echo self::PREFIX . 'Action'; ?>" value="AddDeal" />
			
			<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('AddDeal'); ?>" />
			
			<input type="submit" class="button-primary" />
			
		</p>
	
	</form>
	
	<h3>
		
		<?php _e( 'Deals', 'B2Template' ) ?>
	
	</h3>
	
	<!-- this is the deal list area -->
	<div id="deal-list-container">
	
	</div>

</div>