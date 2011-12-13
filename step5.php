<?php

// Step 5 - Internationalizing your plugin

if (!class_exists('MakeMyBlogHonest')) {

	class MakeMyBlogHonest {
	
		const PREFIX = 'mmbh';
	
		private $plugin_url;
	
		function __construct() {
	
			$this->plugin_url = plugins_url(basename(dirname(__FILE__)));
	
			add_filter('bloginfo', array($this, 'AddEdsCheesySloganToTitle'), 1, 2);
			
			add_filter('theme_mod_header_image', array($this, 'AddEdsHorribleBanner'));
			
			add_action('wp_enqueue_scripts', array($this, 'AddEdsAnnoyingPopup'));
			
			add_action('wp_enqueue_scripts', array($this, 'AddEdsDisgustingBackground'));
			
			/* NEW CODE */
			
			add_action('init', array($this, 'LoadPluginTextDomain'), 1);
			
			/* END NEW CODE */
			
			add_action('init', array($this, 'AddDefaultOptions'), 2);
			
			add_action('admin_menu', array($this, 'InsertAdminMenuLink'));
			
			add_action('admin_init', array($this, 'RegisterAdminSettings'));
			
			add_action('wp_enqueue_scripts', array($this, 'AddEdsAnnoyingPopup'));
			
			add_action('wp_enqueue_scripts', array($this, 'AddEdsDisgustingBackground'));
		
		}
	
		/* NEW CODE */
	
		function LoadPluginTextDomain() {
	
			  /* This function tells WordPress where our language files are, and what namespace 
				this translation file is going to use. */
	
			  load_plugin_textdomain(self::PREFIX, null, basename(dirname(__FILE__)) . '/language/');
		}
	
		/* END NEW CODE */
	
		function AddDefaultOptions() {
	
			  /* MODIFIED CODE */
	
			  /* Wrap string in gettext function __() */
			  add_option(self::PREFIX . '_cheesy_slogan_text', __('you can\'t beat his prices!', self::PREFIX));
	
			  /* END MODIFIED CODE */
	
			  add_option(self::PREFIX . '_horrible_banner_image', '1');
	
			  add_option(self::PREFIX . '_disgusting_background_enabled', true);
	
			  add_option(self::PREFIX . '_annoying_popup_enabled', true);
		}
	
		function AddEdsCheesySloganToTitle($current_value, $field) {
	
			  if ($field == 'name')
			return $current_value . ' &ndash; ' .
				get_option(self::PREFIX . '_cheesy_slogan_text');
		}
	
		function AddEdsHorribleBanner() {
	
			  if (get_option(self::PREFIX . '_horrible_banner_image')) {
	
			return $this->plugin_url . '/images/eds-horrible-banner' .
				get_option(self::PREFIX . '_horrible_banner_image') . '.jpg';
			  }
		}
	
		function AddEdsAnnoyingPopup() {
	
			  wp_register_script(
				   'eds_annoying_popup', $this->plugin_url . '/js/eds_annoying_popup-2.0.js', array('jquery'), '2.0', true
			  );
	
			  wp_localize_script(
				   'eds_annoying_popup', 'eds_annoying_popup_vars', array(
			'the_text' => __(
				'Acme Anvils only $79.99 until August 28th, 2011!', self::PREFIX
			)
				   )
			  );
	
			  $popup_enabled = get_option(self::PREFIX .
				   '_annoying_popup_enabled');
	
			  if (true == $popup_enabled) {
	
			wp_enqueue_script('eds_annoying_popup');
			  }
		}
	
		function AddEdsDisgustingBackground() {
	
			  wp_register_style(
				   'eds_disgusting_background', $this->plugin_url . '/css/eds_disgusting_background.css', false, '1.0', 'screen'
			  );
	
			  $background_enabled = get_option(self::PREFIX .
				   '_disgusting_background_enabled');
	
			  if (true == $background_enabled) {
	
			wp_enqueue_style('eds_disgusting_background');
			  }
		}
	
		function InsertAdminMenuLink() {
	
/* MODIFIED CODE */
	
			  /* Wrap strings in gettext function __() */
			  $page = add_submenu_page(
				   'options-general.php', __('Honesty Settings Page', self::PREFIX), __('Honesty Settings', self::PREFIX), 'manage_options', 'honesty-settings-page', array($this, 'HonestSettingsPageHtml')
			  );
	
/* END MODIFIED CODE */

		}
	
		function HonestSettingsPageHtml() {
	
/* MODIFIED CODE */
	
			  /* Wrap strings in gettext function __() */
			  include( 'html/honesty-settings-page-step5.php' );
	
/* END MODIFIED CODE */

		}
	
		function RegisterAdminSettings() {
	
			register_setting(
				   self::PREFIX . 'Settings', self::PREFIX . '_cheesy_slogan_text'
			  );
	
			  register_setting(
				   self::PREFIX . 'Settings', self::PREFIX . '_horrible_banner_image'
			  );
	
			  register_setting(
				   self::PREFIX . 'Settings', self::PREFIX . '_disgusting_background_enabled'
			  );
	
			  register_setting(
					self::PREFIX . 'Settings', self::PREFIX . '_annoying_popup_enabled'
			  );
		}

	}

	$myHonestBlog = new MakeMyBlogHonest();
	
}

/*
  We've now internationalized our plugin. Check it out by opening wp-config.php
  and switching define('WPLANG',''); to define('WPLANG','ES'); Felicitaciones!

 */
?>