<?php
/*
 * Plugin Name: Periodic Table 
 * Version: 1.0
 * Plugin URI:
 * Description: Learn the Periodic Table with this handy plugin which displays a random element each day. 
 * Author: Greg McBrien
 * Author URI: https://www.austdynatech.com.au 
 * Requires at least: 4.0
 * Tested up to: 4.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;
// Load plugin class files


function Periodic_Table () {
	require_once( 'includes/class-periodic-table.php' );

	register_activation_hook(__FILE__, array('PeriodicTable','activation'));
	register_deactivation_hook(__FILE__, array('PeriodicTable', 'deactivation'));
	$path = plugin_dir_path(__FILE__);
	$url = plugin_dir_url(__FILE__);
	$instance = new PeriodicTable($path, $url);

}
Periodic_Table();
