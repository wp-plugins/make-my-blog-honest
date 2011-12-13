<?php

// Step 4 - Creating a configuration page to save settings

if(!class_exists( 'MakeMyBlogHonest' )) {
	
	class MakeMyBlogHonest
	{
		
		const PREFIX = 'mmbh';
		
		private $plugin_url;
		
		function __construct() {
			
			$this->plugin_url = plugins_url(basename(dirname(__FILE__)));
			
			add_filter( 'bloginfo', array( $this, 'AddEdsCheesySloganToTitle' ), 1, 2 );
		
			add_filter( 'theme_mod_header_image', array( $this, 'AddEdsHorribleBanner' ) );
			
			add_action( 'wp_enqueue_scripts', array( $this, 'AddEdsAnnoyingPopup' ) );
			
			add_action( 'wp_enqueue_scripts', array( $this, 'AddEdsDisgustingBackground' ) );
			
/* NEW CODE */
			
			/* Here we are adding an action to run when WordPress initializes to
				add some default option values to the wp_options table. Call it 
				with priority 2 */
			add_action( 'init', array( $this, 'AddDefaultOptions' ), 2 );
			
			/* Here we are adding an action to run when WordPress is building the 
				left hand Administration menu. */
			add_action( 'admin_menu', array( $this, 'InsertAdminMenuLink' ) );
							
			/* Here we are adding an action to run when the admin facing pages 
				initialize that will register our new settings with WordPress. */
			add_action( 'admin_init', array( $this, 'RegisterAdminSettings' ) );
		
		}
		
		function AddDefaultOptions()
		{
			/* Here we are adding some options to the WordPress options table. 
				The function add_option only works if the option doesn't already exist,
				so we can call it inside our constructor to set the default values for our 
				options. */
			add_option( self::PREFIX . '_cheesy_slogan_text', 'you can\'t beat his prices!' );

			add_option( self::PREFIX . '_horrible_banner_image', '1');
			
			add_option( self::PREFIX . '_disgusting_background_enabled', true);
			
			add_option( self::PREFIX . '_annoying_popup_enabled', true);

		}
		
/* END NEW CODE */

/* MODIFIED CODE */
		
		function AddEdsCheesySloganToTitle( $current_value, $field )
		{
		
			if( $field == 'name' ) return $current_value . ' &ndash; ' . 
				get_option(self::PREFIX . '_cheesy_slogan_text');
		
		}
		
		function AddEdsHorribleBanner()
		{
		
			if( get_option( self::PREFIX . '_horrible_banner_image' ) )
			{
			
				return $this->plugin_url . '/images/eds-horrible-banner' . 
					get_option( self::PREFIX . '_horrible_banner_image' ) . '.jpg';
			
			}
			
		}
		
		function AddEdsAnnoyingPopup()
		{
		
			wp_register_script( 
				'eds_annoying_popup', 
				$this->plugin_url . '/js/eds_annoying_popup.js', 
				array( 'jquery' ),
				'1.0',
				true
			);
			
					
			$popup_enabled = get_option( self::PREFIX . 
				'_annoying_popup_enabled' );
				
			if( true == $popup_enabled ) 
			{
			
				wp_enqueue_script( 'eds_annoying_popup' );
			
			}
			
		}
		
		function AddEdsDisgustingBackground()
		{
		
			wp_register_style(
				'eds_disgusting_background',
				$this->plugin_url.'/css/eds_disgusting_background.css',
				false,
				'1.0',
				'screen'
			);
			
			$background_enabled = get_option( self::PREFIX . 
				'_disgusting_background_enabled' );
				
			if( true == $background_enabled )
			{
				
				wp_enqueue_style( 'eds_disgusting_background' );
				
			}
			
		}

/* END MODIFIED CODE */

/* NEW CODE */	
	
		function InsertAdminMenuLink()
		{
		
			/* Add a submenu page under the general options tab */	
			$page = add_submenu_page( 
				'options-general.php',
				'Honesty Settings Page',
				'Honesty Settings', 
				'manage_options' , 
				'honesty-settings-page' ,
				array( $this, 'HonestSettingsPageHtml' )
			);
			
			/* 'manage_options' is an example of a WordPress role / capability.
				For a complete list of roles and capabilities visit http://is.gd/iygonl */
		
		}
		
		function HonestSettingsPageHtml()
		{

			/* Include our HonestSettingsPage HTML, this is cleaner then echo'ing
				out html inside our php file. */	
			include( 'html/honesty-settings-page.php' );
			
		}
		
		function RegisterAdminSettings()
		{
			
			/* Settings we want WordPress to save for us must be registered first */
			register_setting( 
				self::PREFIX . 'Settings', 
				self::PREFIX . '_cheesy_slogan_text' 
			);
			
			register_setting( 
				self::PREFIX . 'Settings', 
				self::PREFIX . '_horrible_banner_image' 
			);
			
			register_setting( 
				self::PREFIX . 'Settings', 
				self::PREFIX . '_disgusting_background_enabled' 
			);
			
			register_setting( 
				self::PREFIX . 'Settings', 
				self::PREFIX . '_annoying_popup_enabled' 
			);
			
		}
		
/* END NEW CODE */
		
	}
	
	$myHonestBlog = new MakeMyBlogHonest();

}

/* We've now got some plugin options that will be saved by WordPress. Mazel tov! */

?>