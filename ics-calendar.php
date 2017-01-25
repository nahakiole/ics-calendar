<?php
/*
 * Plugin Name: ICS Calendar
 * Version: 1.0
 * Plugin URI: http://www.robinglauser.ch/
 * Description: A plugin to display a remote ics file as a widget or with shortcode on a page.
 * Author: Robin Glauser
 * Author URI: https://www.robinglauser.ch/
 * Requires at least: 4.0
 * Tested up to: 4.7.1
 *
 * Text Domain: ics-calendar
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Robin Glauser
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load plugin class files
require_once( 'includes/class-ics-calendar.php' );
require_once( 'includes/class-ics-calendar-settings.php' );

// Load plugin libraries
require_once( 'includes/lib/class-ics-calendar-admin-api.php' );
require_once( 'includes/lib/class-ics-calendar-post-type.php' );
require_once( 'includes/lib/class-ics-calendar-taxonomy.php' );
require_once( 'includes/lib/class-ics-calendar-widget.php' );
require_once( 'includes/lib/class-ics-calendar-ics-helper.php' );
require_once( 'includes/lib/class.iCalReader.php' );

/**
 * Returns the main instance of ICS_Calendar to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object ICS_Calendar
 */
function ICS_Calendar() {
	$instance = ICS_Calendar::instance( __FILE__, '1.0.0' );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = ICS_Calendar_Settings::instance( $instance );
	}

	function ics_calendar_register_widgets() {
		register_widget( 'ICS_Calendar_Widget' );

        function ics_calendar_shortcode( $atts ){
            $icshelper = new ICS_Calendar_ICS_Helper();
            $icshelper->get_ics();
        }
        add_shortcode( 'ics_calendar', 'ics_calendar_shortcode' );

	}

	add_action( 'widgets_init', 'ics_calendar_register_widgets' );


	return $instance;
}

ICS_Calendar();
