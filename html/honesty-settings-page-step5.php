<?php 

/* This file is the same as HonestSettingsPage.php but all strings 
	have been wrapped in the gettext function __(); */ 

?>

<div class="wrap">

	<h2>

		<?php _e( 'Honesty Settings Page', self::PREFIX ) ?>
		
	</h2>
	
		<?php
		
			/* grab the existing option values and save them to variables */
			$cheesy_slogan_text = get_option( self::PREFIX . '_cheesy_slogan_text' );
			
			$horrible_banner_image = get_option( self::PREFIX . '_horrible_banner_image' );
			
			$disgusting_background_enabled = get_option( self::PREFIX . '_disgusting_background_enabled' );
			
			$annoying_popup_enabled = get_option( self::PREFIX . '_annoying_popup_enabled' );
			
		?>	
	
	<!-- This is the plugin options form -->
	<form method="post" action="options.php">
		
		<?php 
		
			/* Print the hidden fields that will let WordPress correctly save our settings.
				Parameter must match the first parameter of register_setting() */	
			settings_fields( self::PREFIX . 'Settings' ); 
			
		?>
		
		<p>
			
			<label for="cheesy_slogan_text"><?php _e( 'Cheesy slogan text', self::PREFIX ) ?></label>
			
			<input id="cheesy_slogan_text" name="<?php echo self::PREFIX; ?>_cheesy_slogan_text" type="text" value="<?php echo $cheesy_slogan_text ?>" />
			
		</p>
		
		<p>
			
			<label for="horrible_banner_image"><?php _e( 'Select a horrible banner', self::PREFIX ) ?></label>
			
			<select id="horrible_banner_image" name="<?php echo self::PREFIX; ?>_horrible_banner_image">
			
				<?php
				
					$banners = array(
						0=>__( 'Automatic', self::PREFIX ),
						1=>__( 'Store front', self::PREFIX ),
						2=>__( 'Check out', self::PREFIX ),
						3=>__( 'Aisles', self::PREFIX )
					);
					
					foreach($banners as $val => $name)
					{
					
				?>
				
				<option value="<?php echo $val;?>" <?php if( $horrible_banner_image == $val ) echo 'selected="selected"'; ?>><?php echo $name; ?></option>
				
				<?php
				
					}
					
				?>			
						
			</select>
			
		</p>	
		
		<p>
			
			<label for="disgusting_background_enabled"><?php _e( 'Enable the disgusting background', self::PREFIX ) ?></label>
			
			<input type="checkbox" id="disgusting_background_enabled" name="<?php echo self::PREFIX; ?>_disgusting_background_enabled" value="1" <?php if( $disgusting_background_enabled == true ) echo 'checked="checked"'; ?> />
			
		</p>	
		
		<p>
			
			<label for="annoying_popup_enabled"><?php _e( 'Enable the annoying popup', self::PREFIX ) ?></label>
			
			<input type="checkbox" id="annoying_popup_enabled" name="<?php echo self::PREFIX; ?>_annoying_popup_enabled" value="1"<?php if( $annoying_popup_enabled == true ) echo 'checked="checked"'; ?> />
			
		</p>
		
		<p class="submit">
			
			<input type="submit" class="button-primary" value="<?php _e( 'Save Changes', self::PREFIX ) ?>" />

		</p>
		
	</form>

</div>