<?php

// Step 7 - Routing actions within your plugin

if( !class_exists( 'MakeMyBlogHonest' ) ) {
	
	class MakeMyBlogHonest
	{
		
		const PREFIX = 'mmbh';
		
		private $plugin_url;
		
		const DB_VERSION = 4;
		
/* NEW CODE */
		
		/* Use this variable to output meaningful messages to user */
		var $output = array();
		
/* END NEW CODE */
		
		function __construct() {
			
			$this->plugin_url = plugins_url(basename(dirname(__FILE__)));
			
			add_filter( 'bloginfo', array( $this, 'AddEdsCheesySloganToTitle' ), 1, 2 );
		
			add_filter( 'theme_mod_header_image', array( $this, 'AddEdsHorribleBanner' ) );
			
			add_action( 'wp_enqueue_scripts', array( $this, 'AddEdsAnnoyingPopup' ) );
			
			add_action( 'wp_enqueue_scripts', array( $this, 'AddEdsDisgustingBackground' ) );
			
			add_action( 'admin_menu', array( $this, 'InsertAdminMenuLink' ) );
			
			add_action( 'init', array( $this, 'LoadPluginTextDomain' ), 1 );
			
			add_action( 'init', array( $this, 'AddDefaultOptions' ), 2 );
			
			add_action( 'admin_init', array( $this, 'RegisterAdminSettings' ) );
						
			add_action( 'wp_enqueue_scripts', array( $this, 'AddEdsAnnoyingPopup' ) );
			
			add_action( 'wp_enqueue_scripts', array( $this, 'AddEdsDisgustingBackground' ) );
			
			add_action( 'init', array( $this, 'CheckUpdate' ) , 1 );
			
/* NEW CODE */
			
			/* Add the action that will check if we want our plugin to do work */
			add_action( 'init', array($this,'RouteActions'),2);
			
/* END NEW CODE */
			
		}
		
		function LoadPluginTextDomain()
		{
			  
			load_plugin_textdomain( self::PREFIX, null, basename(dirname(__FILE__)).'/language/' );
		
		}
		
		function AddDefaultOptions()
		{
			
			add_option( self::PREFIX . '_cheesy_slogan_text', 
					__( 'you can\'t beat his prices!', self::PREFIX ) );
	
			add_option( self::PREFIX . '_horrible_banner_image', '1');
	
			add_option( self::PREFIX . '_disgusting_background_enabled', true);
	
			add_option( self::PREFIX . '_annoying_popup_enabled', true);
				
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
			
/* NEW CODE */
			
			/* Adding a second submenu page to our admin navigation */
			$page = add_submenu_page( 
				'options-general.php',
				__( 'Deal Configuration Page', self::PREFIX ),
				__( 'Deal Configuration', self::PREFIX ), 
				'manage_options' , 
				'deal-configuration-page' ,
				array( $this , 'DealConfigurationPageHtml' )
			);
			
/* END NEW CODE */
		
		}
		
		function HonestSettingsPageHtml()
		{
			
			include( 'html/honesty-settings-page-step5.php' );
			
		}
		
/* NEW CODE */
		
		function DealConfigurationPageHtml()
		{
			
			/* Include our Deal Configuration Page HTML */
			include( 'html/deal-configuration-page.php' );
		
		}
		
/* END NEW CODE */
		
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
		
		function RouteActions()
		{

			/* Make sure necessary $_POST variables are set */
			if( 
				!isset( $_POST[ self::PREFIX . 'Action'] ) ||
				!isset( $_POST[ '_wpnonce'] )
			)
			{
				return false;
			}
			
			$action = $_POST[ self::PREFIX . 'Action'];
			$nonce = $_POST[ '_wpnonce' ];
			
			
			/* Verify nonce, if invalid die */
			if(!wp_verify_nonce($nonce, $action )) 
				wp_die(__('You are not authorized to perform this action.',self::PREFIX));
			
			/* Otherwise perform the action */
			$result = $this->DoAction( $action );
			
			return $result;
			
		}
		
		function DoAction( $action )
		{
			
			/* Figure out what action was performed, and set our output variable */
			$result = false;

			switch( $action )
			{
				case 'AddDeal':
				
					$this->output['success'] = "Perform AddDeal Action";
					
				break;
				
				case 'SetEnabledStatus':
					
					$this->output['success'] = "Perform SetEnabledStatus Action";
					
				break;
				
				case 'DeleteDeal':
					
					$this->output['success'] = "Perform DeleteDeal Action";
					
				break;
				
			}
			
			return $result;
			
		}
		
/* END NEW CODE */
		
		function CheckUpdate() {
			
			global $wpdb;
			
			$installed_ver = get_option( self::PREFIX . '_db_version' );
			
			if ($installed_ver == false) {

				$this->InstallTable();

				return false;

			}
			
			if( $installed_ver == self::DB_VERSION ) return false;
			
			$deals_table = $wpdb->prefix . self::PREFIX . '_deals';
			
			if( $installed_ver < 3 )
			{
				
				$sql_add_enabled =	'ALTER TABLE ' . $deals_table . 
					' ADD `expires` DATETIME NOT NULL AFTER `enabled`;';
				
				$wpdb->query( $sql_add_enabled );
				
			}
			
			if( $installed_ver < 4 )
			{
			
				$sql_edit_sale_price_type = 'ALTER TABLE ' . 
					$deals_table.' MODIFY `sale_price` float NOT NULL;';
				
				$wpdb->query( $sql_edit_sale_price_type );
				
				$sql_edit_enabled_type = 'ALTER TABLE ' . $deals_table . 
					' MODIFY `enabled` tinyint(1) NOT NULL;';
				
				$wpdb->query( $sql_edit_enabled_type );
	
			}
			
			update_option( self::PREFIX . '_db_version', self::DB_VERSION );
		
		}
		
		function InstallTable() {
		
			global $wpdb;
	
			$deals_table = $wpdb->prefix . self::PREFIX.'_deals';
			
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
				
				$wpdb->query( $create_deals_table_sql );
				
			}
			
			add_option( self::PREFIX.'_db_version', self::DB_VERSION );
			
		}
					
	}
	
	$myHonestBlog = new MakeMyBlogHonest();
	
}

/* We now have a new admin page with a form that can call our actions. Hot Diggity! */

?>