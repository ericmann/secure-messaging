<?php
/**
 * Plugin Name: Secure Messaging
 * Plugin URI:  https://eamann.com
 * Description: GPG security for WordPress messaging.
 * Version:     0.4.1
 * Author:      Eric Mann
 * Author URI:  https://eamann.com
 * License:     MIT
 * Text Domain: securemsg
 * Domain Path: /languages
 */

// Useful global constants
define( 'SECUREMSG_VERSION',  '0.4.1' );
define( 'SECUREMSG_URL',      plugin_dir_url( __FILE__ ) );
define( 'SECUREMSG_PATH',     dirname( __FILE__ ) . '/' );
define( 'SECUREMSG_INC',      SECUREMSG_PATH . 'includes/' );
define( 'SECUREMSG_BASENAME', plugin_basename( __FILE__ ) );

// Include files
require_once SECUREMSG_PATH . 'vendor/autoload.php';

// Activation/Deactivation
register_activation_hook(   __FILE__, '\EAMann\Secure_Messaging\Core\activate' );

// Bootstrap
EAMann\Secure_Messaging\Core\setup();