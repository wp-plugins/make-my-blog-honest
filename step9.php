<?php

// Step 9 - Using Ajax from admin facing pages

if( !class_exists( 'MakeMyBlogHonest' ) ) {
	
	class MakeMyBlogHonest
	{
		
		const PREFIX = 'mmbh';
		
		private $plugin_url;
		
		const DB_VERSION = 4;
		
		var $output = array();
		
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
			
			add_action( 'init', array($this,'RouteActions'),2);
			
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
			
			$page = add_submenu_page( 
				'options-general.php',
				__( 'Deal Configuration Page', self::PREFIX ),
				__( 'Deal Configuration', self::PREFIX ), 
				'manage_options' , 
				'deal-configuration-page' ,
				array( $this , 'DealConfigurationPageHtml' )
			);
			
/* NEW CODE */
			
			/* Add an action here using admin_print_scripts-$page
				where $page is the admin page hook, so that we load the script
				on our admin page only (admin_enqueue_scripts doesn't work
				in this context) */
			add_action( 
				'admin_print_scripts-' . $page , 
				array( $this, 'DealConfigurationPageJs' )
			);
			
			/* Add an action here admin_print_styles-$page
				where $page is the admin page hook, so that we load the style
				on our admin page only */
			add_action( 
				'admin_print_styles-' . $page , 
				array( $this, 'DealConfigurationPageCss' ) 
			);
			
/* END NEW CODE */
		
		}
		
		function HonestSettingsPageHtml()
		{
			
			include( 'html/honesty-settings-page-step5.php' );
			
		}
		
		function DealConfigurationPageHtml()
		{
		
			include( 'html/deal-configuration-page-step8.php' );
		
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
		
		function DealConfigurationPageJs()
		{
			
			/* Here we register the script that will add the AJAX functionality 
				to our admin page */
			wp_register_script(
				self::PREFIX . '_deal_configuration_page',
				$this->plugin_url.'/js/deal_configuration_page.js',
				array( 'jquery' ),
				'1.0',
				true
			);
			
			wp_enqueue_script( self::PREFIX . '_deal_configuration_page' );
		
		}
		
		function DealConfigurationPageCss()
		{
			
			/* Here we register the style that will add some CSS 
				to our admin page */
			wp_register_style( 
				self::PREFIX . '_deal_configuration_page',
				$this->plugin_url.'/css/deal_configuration_page.css',
				false,
				'1.0',
				'screen'
			);
			
			wp_enqueue_style( self::PREFIX . '_deal_configuration_page' );
		
		}
		
/* END NEW CODE */
		
		function RouteActions()
		{
			
			if( 
				!isset( $_POST[ self::PREFIX . 'Action'] ) ||
				!isset( $_POST[ '_wpnonce'] )
			)
			{
				return false;
			}
			
			$action = $_POST[ self::PREFIX . 'Action'];
			$nonce = $_POST[ '_wpnonce' ];
			
			if(!wp_verify_nonce($nonce, $action )) 
				wp_die(__('You are not authorized to perform this action.',self::PREFIX));
			
			$result = $this->DoAction( $action );
			
/* NEW CODE */

			/* After performing the actions, we want to check if this is an 
				AJAX request. If so, we want to return an AJAX response */
			if( $this->IsAjax() )
			{
				
				$this->AjaxResponse( $action );
			
			}
			
/* END NEW CODE */
			
			return $result;
			
		}
		
		function DoAction( $action )
		{
			
			$result = false;

			switch( $action )
			{
				case 'AddDeal':
				
					if(!current_user_can('manage_options') || !is_admin()) 
						wp_die(__('You are not authorized to perform this action.',self::PREFIX));
						
					$product_name = ( isset( $_POST['product_name'] ) ) ? 
						$_POST['product_name'] : '';
					
					$sale_price =	( isset( $_POST['sale_price'] ) ) ? $_POST['sale_price'] : '';
					
					$enabled =	 ( isset( $_POST['enabled'] ) ) ? true : false;
					
					$expires =	 ( isset( $_POST['expires'] ) ) ? $_POST['expires'] : false;
					
					$result = $this->AddDeal( $product_name, $sale_price, $enabled, $expires );
					
				break;
				
				case 'SetEnabledStatus':
					
					if(!current_user_can('manage_options') || !is_admin()) 
						wp_die(__('You are not authorized to perform this action.',self::PREFIX));
					
					$id = ( isset( $_POST['id'] ) ) ? $_POST['id'] : false;
					
					$enabled = ( isset( $_POST['enabled'] ) ) ? $_POST['enabled'] : false;

					$result = $this->SetEnabledStatus( $id, $enabled );
					
				break;
				
				case 'DeleteDeal':
				
					if(!current_user_can('manage_options') || !is_admin()) 
						wp_die(__('You are not authorized to perform this action.',self::PREFIX));
					
					$id = ( isset( $_POST['id'] ) ) ? $_POST['id'] : false;
					
					$result = $this->DeleteDeal( $id );
					
				break;
				
			}
			
			return $result;
			
		}
		
/* NEW CODE */
		
		function AjaxResponse( $action )
		{			
			
			/* Take our output and copy it to our data variable */
			$data = $this->output;

			/* Add the current action to our data variable so JS knows what
				action it should respond to */
			$data['action'] = $action;
			
			/* If AddDeal, SetEnabledStatus or DeleteDeal is called,
				We're going to need to update our deal-list, so lets get that 
				HTML again and add it to our data. */
			if( $action == 'AddDeal' || $action == 'SetEnabledStatus' || $action == 'DeleteDeal' )
			{
			
				/* Start output buffering */
				ob_start();
				
					include( 'html/elements/deal-list.php' );
					
					/* capture the file contents into $data['html'] */
					$data['html'] = ob_get_contents();
					
				/* Clean up the buffer */
				ob_end_clean();
				
			}

			/* Die with a json_encoded version of our $data variable, 
				that our javascript will know how to handle */			
			die(json_encode($data));
			
		}
		
		/* This is a handy helper function for detecting AJAX requests */
		private function IsAjax()
		{
		
			if ( 
				isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) &&
				$_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest" 
			)
			{
			
				return true;
				
			}
			
			return false;
			
		}
		
/* END NEW CODE */
		
		private function GetDeals()
		{
			
			global $wpdb;
			
			$sql = 'SELECT * FROM '. $wpdb->prefix . self::PREFIX . '_deals';
			
			$result = $wpdb->get_results( $sql );
			
			return $result;
			
		}
		
		private function AddDeal( $product_name, $sale_price, $enabled = false, $expires = false )
		{
		
			global $wpdb;
			
			if( empty( $product_name ) ) 
			{
			
				$this->output['error'] = __( 'The product name cannot be empty', 
					self::PREFIX );
				
				return false;
			
			}
			
			if( !is_numeric( $sale_price ) ) 
			{
			
				$this->output['error'] = __( 'The sale price must be a valid number.', 
					self::PREFIX );
				
				return false;
				
			}
			
			if( is_array( $expires ) ) {
			
				if(
					!array_key_exists('Year', $expires) ||
					!array_key_exists('Month', $expires) ||
					!array_key_exists('Day', $expires)
				)
				{
					$this->output['error'] = 
						__( 'A year month and day are required for the expiration date.', 
							self::PREFIX );
				
					return false;
				}
				
				$expires = $expires['Year'] . '-' . $expires['Month'] . '-' . $expires['Day'];
			
			}
			
			
			$sql = $wpdb->prepare( 
				'INSERT INTO ' . $wpdb->prefix . self::PREFIX . 
					'_deals(`product_name`, `sale_price`, `enabled`, `expires`)'.
					' VALUES(%s, %f, %d, %s);', 
				$product_name, 
				$sale_price, 
				$enabled, 
				$expires 
			);
		
			$result = $wpdb->query( $sql );
		
			if( $result !== false )
			{
			
				$this->output['success'] = __('Deal has been added.', self::PREFIX );
				
			}
			else
			{
				
				$this->output['error'] = __( 
					'There was a problem adding the deal, please try again.', 
					self::PREFIX );
				
			}
			
			return $result;
		
		}
		
		private function SetEnabledStatus( $id, $enabled )
		{
			
			global $wpdb;
			
			if( $id == false ) {
			
				$this->output['error'] = __(
					'There was a problem changing the deal status, please try again.', 
					self::PREFIX );
				
				return false;
				
			}
			
			$sql = $wpdb->prepare( 
				'UPDATE ' . $wpdb->prefix . self::PREFIX . 
					'_deals SET `enabled` = %d WHERE `id` = %d;', 
				$enabled, 
				$id 
			);
			
			$result = $wpdb->query( $sql );
			
			if($result !== false)
			{
			
				$this->output['success'] =	__( 'The enabled status has been updated.', 
					self::PREFIX );
				
			}
			else
			{
			
				$this->output['error'] = __( 
					'There was a problem changing the deal status, please try again.', 
					self::PREFIX );
				
			}
			
			return $result;
			
		}
		
		private function DeleteDeal( $id = false )
		{
			
			global $wpdb;
			
			if( $id == false ) {
			
				$this->output['error'] = __( 
					'There was a problem deleting the deal, please try again.', 
					self::PREFIX );
				return false;
				
			}
			
			$sql = $wpdb->prepare( 
				'DELETE FROM ' . $wpdb->prefix . self::PREFIX . 
					'_deals WHERE `id` = %d;', 
				$id
			);
			
			$result = $wpdb->query( $sql );
			
			if($result !== false)
			{
			
				$this->output['success'] =	__( 'The deal has been deleted.', 
					self::PREFIX );
				
			}
			else
			{
			
				$this->output['error'] = __( 
					'There was a problem deleting the deal, please try again..', 
					self::PREFIX );
				
			}
			
			return $result;
		
		}
		
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

/* We've now added AJAX to our admin facing pages that degrades gracefully. Yeehaw! */

?>