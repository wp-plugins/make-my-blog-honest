<?php
/*
Plugin Name: Make My Blog Honest
Plugin URI: http://bloggingsquared.com/plugins/Word11Demo
Description: This plugin will teach you how to build a plug-in of your own, with Honest Eds as the example client.
Version: 1.4
Author: Dan Imbrogno
Author URI: http://bloggingsquared.com
License: GPL2
*/

/* 

	To use this tutorial plugin:
	
	1) Activate the plugin
	2) Open step1.php in your favorite code editor or click "Show Code" in the 
		bar at the bottom right of your screen
	3) Look for the NEW CODE and MODIFIED CODE comments. These indicate
		where code has been updated from the previous step
	4) When you're ready for the next step click the "next" button in the bar at
		the bottom left of your screen and repeat

	You can jump to any step at any time with the select box in the bottom 
	left of your screen
	
*/

/* 
	IGNORE EVERYTHING IN THIS FILE BELOW THIS POINT
	Let's not get ahead of ourselves! The learning starts inside step1.php,
*/

if(!class_exists('MakeMyBlogHonestGuide'))
{

	class MakeMyBlogHonestGuide
	{
		
		const PREFIX = 'mmbhg';
		
		private $plugin_url = false;
		private $td = 'mmbhg';
		
		function __construct()
		{
			
			$this->plugin_url = plugins_url(basename(dirname(__FILE__)));
			
			add_action('init', array($this,'AddOptions'));			
			add_action('init', array($this,'RegisterScriptsAndStyles'));

			add_action('wp_enqueue_scripts',array($this,'PrintStyles'));
			add_action('admin_print_styles',array($this,'PrintStyles'));
			add_action('wp_print_scripts', array($this,'PrintScripts'));
			add_action('admin_print_scripts', array($this,'PrintScripts'));
			
			$step = $this->GetCurrentStep();
			
			$this->LoadPluginStep($step);
			
			if( class_exists('MakeMyBlogHonest') )
			{
				$this->td = MakeMyBlogHonest::PREFIX;
				load_plugin_textdomain( $this->td, null, basename(dirname(__FILE__)).'/language/' );
			}
			
		}
		
		function AddOptions()
		{
		
			add_option(self::PREFIX.'_step',1);
			
		}
		
		function RegisterScriptsAndStyles()
		{

			wp_register_style(
				'makemybloghonestguide',
				$this->plugin_url.'/css/guide.css'
			);
			
			wp_register_script(
				'makemybloghonestguide',
				$this->plugin_url.'/js/guide.js',
				array( 'jquery' ),
				'1.0',
				true
			);
			
		}
		
		function PrintStyles()
		{
		
			global $pagenow;
			
			if('wp-login.php' == $pagenow ) return false; 
			
			wp_enqueue_style('makemybloghonestguide');
			
		}
		
		function PrintScripts()
		{
			
			global $pagenow;
			
			if('wp-login.php' == $pagenow ) return false; 
			
			$steps = $this->GetStepNames();
			
			wp_enqueue_script('makemybloghonestguide');
			
			wp_localize_script('makemybloghonestguide', self::PREFIX,
				 array(
					'txt_step_select'=>__('Select the plugin step',$this->td),
					'txt_next'=>__('next',$this->td),
					'txt_prev'=>__('previous',$this->td),
					'current_step'=>get_option(self::PREFIX.'_step'),
					'nonce_change_step'=>wp_create_nonce('ChangeStep'),
					'l10n_print_after' => self::PREFIX.'_steps = ' . json_encode( $steps ) . ';'
					
				)
			);
		
		}
		
		private function GetCurrentStep()
		{
		
			if(array_key_exists('mmbhg_step',$_POST))
			{
				update_option(self::PREFIX.'_step', $_POST['mmbhg_step']);
			}
			
			$step = get_option(self::PREFIX.'_step');
			if(false == $step) $step = 1;
			return $step;
		}
		
		private function LoadPluginStep($step = 1)
		{
			if($step == 1)
			{
				define( 'WP_UNINSTALL_PLUGIN', true );
				require_once( 'uninstall.php' );
			}
			
			$this->RequireStep($step);
			
		}
		
		private function RequireStep($the_step)
		{
			require($this->GetStep($the_step));
		}
		
		private function GetStep($the_step)
		{
			$steps = $this->GetSteps();
			if(!array_key_exists($the_step, $steps)) throw new Exception('Step not found');
			return $steps[$the_step];
		}
		
		public static function GetSteps()
		{
			return array(
				1=>'step1.php',
				2=>'step2.php',
				3=>'step3.php',
				4=>'step4.php',
				5=>'step5.php',
				6=>'step6.php',
				7=>'step7.php',
				8=>'step8.php',
				9=>'step9.php',
				10=>'step10.php',
				11=>'step11.php'
			);
		}
		
		public function GetStepNames()
		{
		
			return array(
				1=>__('Step 1 - Plugins within classes',$this->td),
				2=>__('Step 2 - Using filters',$this->td),
				3=>__('Step 3 - Using actions, attaching JS and CSS',$this->td),
				4=>__('Step 4 - Configuration pages',$this->td),
				5=>__('Step 5 - Internationalization',$this->td),
				6=>__('Step 6 - Creating database tables',$this->td),
				7=>__('Step 7 - Routing actions',$this->td),
				8=>__('Step 8 - Database table interaction',$this->td),
				9=>__('Step 9 - Ajax on admin pages',$this->td),
				10=>__('Step 10 - Ajax on viewer pages',$this->td),
				11=>__('Step 11 - Time to experiment!',$this->td)
			);
		}
	
	}
	
	$myBlogHonestGuide = new MakeMyBlogHonestGuide();
	
}

?>