<?php

/* This ensures that this file isn't being called directly. Without this,
	someone could simply go to:
	http://mysite.com/wp-content/plugins/MakeMyBlogHonest/uninstall.php
	and our plugin would install and our data would be lost. That doesn't 
	sound like a good thing does it? */
	
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();


/* Namespace the function to avoid conflicts */
function MakeMyBlogHonest_Uninstall()
{	
	global $wpdb;
	
	require_once('step1.php');
	
	$sql = 'DROP TABLE IF EXISTS '. $wpdb->prefix.MakeMyBlogHonest::PREFIX.'_deals;';
	
	/* Drop the deals table */
	$wpdb->query( $sql );
	
	/* Delete our options */
	delete_option(MakeMyBlogHonest::PREFIX.'_cheesy_slogan_text');
	
	delete_option(MakeMyBlogHonest::PREFIX.'_horrible_banner_image');
	
	delete_option(MakeMyBlogHonest::PREFIX.'_disgusting_background_enabled');
	
	delete_option(MakeMyBlogHonest::PREFIX.'_annoying_popup_enabled');
	
	delete_option(MakeMyBlogHonest::PREFIX.'_db_version');
}

/* Call the uninstall function */
MakeMyBlogHonest_Uninstall();

/* NOTE - As of WP 3.2, if your plugin is in a symlinked directory 
	this file likely doesn't get executed. */

?>