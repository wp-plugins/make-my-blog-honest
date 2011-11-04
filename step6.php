<?php

// Step 6 - Creating your own database tables

if(!class_exists( 'MakeMyBlogHonest' )) {
	
	class MakeMyBlogHonest
	{
		
		const PREFIX = 'mmbh';
		
		private $plugin_url;
		
/* NEW CODE */
		
		/* This class constant will be used to indicate the plugin table version */
		const DB_VERSION = 4;
		
/* END NEW CODE */
		
		function __construct() {
			
			$this->plugin_url = plugins_url(basename(dirname(__FILE__)));
			
			add_filter( 'bloginfo', array( $this, 'AddEdsCheesySloganToTitle' ), 1, 2 );
		
			add_filter( 'theme_mod_header_image', array( $this, 'AddEdsHorribleBanner' ) );
			
			add_action( 'wp_enqueue_scripts', array( $this, 'AddEdsAnnoyingPopup' ) );
			
			add_action( 'wp_print_styles', array( $this, 'AddEdsDisgustingBackground' ) );
			
			add_action( 'admin_menu', array( $this, 'InsertAdminMenuLink' ) );
			
			add_option( self::PREFIX . '_cheesy_slogan_text', 
				__( 'you can\'t beat his prices!', self::PREFIX ) );
			
			add_option( self::PREFIX . '_horrible_banner_image', '1');
			
			add_option( self::PREFIX . '_disgusting_background_enabled', true);
			
			add_option( self::PREFIX . '_annoying_popup_enabled', true);
			
			add_action( 'admin_init', array( $this, 'RegisterAdminSettings' ) );
						
			add_action( 'wp_enqueue_scripts', array( $this, 'AddEdsAnnoyingPopup' ) );
			
			add_action( 'wp_print_styles', array( $this, 'AddEdsDisgustingBackground' ) );
			
			load_plugin_textdomain( self::PREFIX, null, basename(dirname(__FILE__)).'/language/' );
			
/* NEW CODE */
			
			/* This action will check if our table is up to date or requires an upgrade */
			add_action( 'init', array( $this, 'CheckUpdate' ) , 1 );
			
/* END NEW CODE */
			
		}
		
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
				$this->plugin_url.'/js/eds_annoying_popup-2.0.js',
				array( 'jquery' ),
				'2.0',
				true
			);
			
			wp_localize_script(
				'eds_annoying_popup',
				'eds_annoying_popup_vars',
				array(
					'the_text'=>__( 
						'Acme Anvils only $79.99 until August 28th, 2011!', 
						self::PREFIX 
					)
				)
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
		
		function InsertAdminMenuLink()
		{
			
			$page = add_submenu_page( 
				'options-general.php',
				__( 'Honesty Settings Page', self::PREFIX ),
				__( 'Honesty Settings', self::PREFIX ), 
				'manage_options' , 
				'honesty-settings-page' ,
				array( $this , 'HonestSettingsPageHtml' )
			);
		
		}
		
		function HonestSettingsPageHtml()
		{
			
			include( 'html/honesty-settings-page-step5.php' );
			
		}
		
		function RegisterAdminSettings()
		{
			
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
		
/* NEW CODE */
		
		function CheckUpdate() {
			
			/* $wpdb is a global object we use to query WordPress' database */
			global $wpdb;
			
			/* Get the version number of the table */
			$installed_ver = get_option( self::PREFIX . '_db_version' );
			
			/* If the db version option can't be found it's possible the table no
				longer exists, so lets call our activate function to be sure. */
			if ($installed_ver == false) {

				MakeMyBlogHonest_Activate();

				return false;

			}
			
			/* If the table is up to date, no more work needs to be done */
			if( $installed_ver == self::DB_VERSION ) return false;
			
			/* Set the name of the table we're going to store our data in */
			$deals_table = $wpdb->prefix . self::PREFIX . '_deals';
			
			/* If we need to upgrade the table from v1 or v2 to v3 */
			if( $installed_ver < 3 )
			{
				
				$sql_add_enabled =  'ALTER TABLE ' . $deals_table . 
					' ADD `expires` DATETIME NOT NULL AFTER `enabled`;';
				
				/* Execute the query */
				$wpdb->query( $sql_add_enabled );
				
			}
			
			/* If we need to upgrade the table from v3 to v4 */
			if( $installed_ver < 4 )
			{
			
				$sql_edit_sale_price_type = 'ALTER TABLE ' . 
					$deals_table.' MODIFY `sale_price` float NOT NULL;';
				
				$wpdb->query( $sql_edit_sale_price_type );
				
				$sql_edit_enabled_type = 'ALTER TABLE ' . $deals_table . 
					' MODIFY `enabled` tinyint(1) NOT NULL;';
				
				$wpdb->query( $sql_edit_enabled_type );
	
			}
			
			/* save the new table version saved in the options table */
			update_option( self::PREFIX . '_db_version', self::DB_VERSION );
		
		}
		
/* END NEW CODE */
			
	}
	
	$myHonestBlog = new MakeMyBlogHonest();
	
/* NEW CODE */
	
	/* It's best to do activation and deactivation outside the class, 
		because when activation occurs, the plugin object is not yet
		created */
	register_activation_hook( 
		'makemybloghonest/makemybloghonest.php' , 
		'MakeMyBlogHonest_Activate' 
	);
	
	register_deactivation_hook( 
		'makemybloghonest/makemybloghonest.php' , 
		'MakeMyBlogHonest_Deactivate' 
	);
	
	/* This runs only on activation. Does not run when plugin is upgraded */
	function MakeMyBlogHonest_Activate() {
	
		global $wpdb;

		$deals_table = $wpdb->prefix . MakeMyBlogHonest::PREFIX.'_deals';
		
		/* Make sure we aren't going to overwrite an existing table. */
		if($wpdb->get_var( 'SHOW TABLES LIKE \'' . $deals_table . '\';' ) != $deals_table)
		{
			
			$create_deals_table_sql = 'CREATE TABLE ' . $deals_table. ' (
						`id` mediumint(9) NOT NULL AUTO_INCREMENT,
						`product_name` varchar(256) NOT NULL,
						`sale_price` float NOT NULL,
						`enabled` tinyint(1) NOT NULL,
						`expires` DATETIME NOT NULL,
						UNIQUE KEY id (id)
				);';
			
			/* Create the table */
			$wpdb->query( $create_deals_table_sql );
			
		}
		
		/* Save the table version for future reference */
		add_option( MakeMyBlogHonest::PREFIX.'_db_version', MakeMyBlogHonest::DB_VERSION );
		
	}
	
	/* It's uncommon to need to do anything on plugin deactivation. 
		Don't delete data here the user might want to retrieve when they 
		reactivate your plugin! */
	function MakeMyBlogHonest_Deactivate() {
		
	}
	
	/* To run code when your user deletes your plugin, create a file called
		uninstall.php in your plugins root directory. Check out the uninstall.php
		file to learn more. */
	
/* END NEW CODE */
	
}

/* We've now added our own table to the WordPress database. BooYah! */

?>