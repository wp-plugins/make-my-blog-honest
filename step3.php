<?php

// Step 3 - Adding action handlers, attaching javascript and css

if(!class_exists( 'MakeMyBlogHonest' )) {
	
	class MakeMyBlogHonest
	{
		
		const PREFIX = 'mmbh';
		
		private $plugin_url;
		
		function __construct() {
			
			$this->plugin_url = plugins_url(basename(dirname(__FILE__)));
			
			add_filter( 'bloginfo', array( $this, 'AddEdsCheesySloganToTitle' ), 1, 2 );
		
			add_filter( 'theme_mod_header_image', array( $this, 'AddEdsHorribleBanner' ) );
			
/* NEW CODE */
			
			/* Here we are adding an action to run when WordPress is enqueuing scripts. 
				The action 'wp_enqueue_scripts' only runs on viewer facing pages. 
				To add scripts to the admin facing pages use 'admin_enqueue_scripts' */
			add_action( 'wp_enqueue_scripts', array( $this, 'AddEdsAnnoyingPopup' ) );

			/* Here we are adding an action to run when WordPress is printing styles. 
				The action 'wp_enqueue_scripts' only runs on viewer facing pages.
				To add styles to the admin facing pages use admin_print_styles */
			add_action( 'wp_enqueue_scripts', array( $this, 'AddEdsDisgustingBackground' ) );
			
			/* For a complete list of actions visit http://is.gd/lktTqj */
			
/* END NEW CODE  */
			
		}
		
		function AddEdsCheesySloganToTitle( $current_value, $field )
		{
		
			if( $field == 'name' ) return $current_value . ' &ndash; you can\'t beat his prices!';
		
		}
		
		function AddEdsHorribleBanner()
		{
		
			return $this->plugin_url . '/images/eds-horrible-banner1.jpg';
		
		}
		
/* NEW CODE */
		
		function AddEdsAnnoyingPopup()
		{
				
			/* Here we register the script that will add an annoying popup to the page */
			wp_register_script( 
				'eds_annoying_popup', 
				$this->plugin_url . '/js/eds_annoying_popup.js', 
				array( 'jquery' ),
				'1.0',
				true
			);

			wp_enqueue_script( 'eds_annoying_popup' );
			
			/* For a complete list of scripts bundled with WordPress visit http://is.gd/qVDsjc */
			
		}
		
		function AddEdsDisgustingBackground()
		{
				
			/* Here we register the script that will add a disgusting background to the page */
			wp_register_style(
				'eds_disgusting_background',
				$this->plugin_url.'/css/eds_disgusting_background.css',
				false,
				'1.0',
				'screen'
			);
			
			wp_enqueue_style( 'eds_disgusting_background' );
			
		}
		
/* END NEW CODE */

	}
	
	$myHonestBlog = new MakeMyBlogHonest();

}

/* We have now attached some scripts and styles to our viewer facing pages using actions. Huzzah! */

?>