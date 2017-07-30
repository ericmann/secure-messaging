<?php
/**
 * Plugin Name: Secure Messaging
 * Plugin URI:  https://eamann.com
 * Description: GPG security for WordPress messaging.
 * Version:     0.3.0
 * Author:      Eric Mann
 * Author URI:  https://eamann.com
 * License:     MIT
 * Text Domain: securemsg
 * Domain Path: /languages
 */

// Useful global constants
define( 'SECUREMSG_VERSION', '0.3.0' );
define( 'SECUREMSG_URL',     plugin_dir_url( __FILE__ ) );
define( 'SECUREMSG_PATH',    dirname( __FILE__ ) . '/' );
define( 'SECUREMSG_INC',     SECUREMSG_PATH . 'includes/' );

// Include files
require_once SECUREMSG_PATH . 'vendor/autoload.php';
require_once SECUREMSG_INC . 'functions/core.php';

// Bootstrap
EAMann\Secure_Messaging\Core\setup();