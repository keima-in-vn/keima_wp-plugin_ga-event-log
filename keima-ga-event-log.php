<?php
/**
 * Plugin Name: keima | GA Event log
 * Description:  This will add shortcode that for getting event log of Google analytics. This plugin is not stand alone, you need to add Google analytics code by other.
 * Version: 1.0.0
 * Plugin URI: 
 * Author: keima.co
 * Author URI: https://www.keima.co/
 * Text Domain: keima-ga-event-log
*/

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

define( 'KEIMA_GA_EVENT_LOG_FILE', __FILE__ );
define( 'KEIMA_GA_EVENT_LOG_DIR', plugin_dir_path( __FILE__ ) );
define( 'KEIMA_GA_EVENT_LOG_VER', '1.0.0' );

if ( ! class_exists( 'KEIMA_GA_EVENT_LOG' ) ) :

  class KEIMA_GA_EVENT_LOG {

    function __construct () {
      // Do nothing.
    }

    function initialize () {
      require_once KEIMA_GA_EVENT_LOG_DIR . 'includes/kgel-shortcode.php';
    }
  }

  function keima_ga_event_log () {
    global $keima_ga_event_log;

    // Instantiate only once.
    if ( ! isset( $keima_ga_event_log ) ) {
      $keima_ga_event_log = new KEIMA_GA_EVENT_LOG();
      $keima_ga_event_log->initialize();
    }
    return $keima_ga_event_log;
  }

  // Instantiate.
  keima_ga_event_log();

endif; // class_exists check

