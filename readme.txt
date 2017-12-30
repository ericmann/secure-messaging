=== Secure Messaging ===
Contributors:      ericmann
Donate link:       https://paypal.me/eam
Tags:              PGP, GPG, Security
Requires at least: 4.7.3
Tested up to:      4.9.1
Stable tag:        0.4.1
License:           MIT
License URI:       https://opensource.org/licenses/MIT

GPG security for WordPress messaging.

== Description ==

Automatically encrypt certain WordPress messages using your GPG public key to ensure no one but you can ever read the message.

This is primarily used to secure password reset emails so, even if an attacker were to gain access to your email account, they couldn't change your WordPress password.

== Installation ==

= Requirements =

This plugin requires PHP 7 or greater to operate. It _does not_ check for PHP compatibility directly and _will not work_ if installed on an older server.

The GPG functionality requires GPG to be installed and available to WordPress. The plugin will try to test for this functionality upon activation, but _will not work_ if GPG is unavailable.

= Manual Installation =

1. Upload the entire `/secure-messaging` directory to the `/wp-content/plugins/` directory.
2. Activate Secure Messaging through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

= Does the server sign messages as well? =

Not by default. On many installations, the GPG keychain folder needs to live in the `/wp-content` directory and might be readable by third parties. To avoid leaking GPG secret keys, none are ever added by the system in the first place. This means the server can't sign messages before they're sent.

= Is there a limit to the size of the GPG key I can use? =

Not to my knowledge. We're using Pear's [Crypt_GPG](http://pear.php.net/package/Crypt_GPG) module, which defer's to the server's GPG module directly. So long as GPG itself supports a key, this plugin will as well.

= What if my host doesn't support GPG? =

Not every host does. Most self-hosting platforms will have GPG support by default, but some (like *WP Engine*) do not support the GPG subsystem and will not allow you to proactively encrypt messages.

I highly suggest you look into a managed host like [Liquid Web](https://www.liquidweb.com/managedwordpress/), who _does_ support GPG, for your hosting needs.

*Note:* This plugin has been tested to work with both Liquid Web's managed WordPress hosting platform and the [Dockerized WordPress](https://github.com/10up/wp-local-docker) system published by [10up](https://10up.com/).

== Screenshots ==

None at this time

== Changelog ==

= 0.4.1 =
* Fix: Explicitly add Eric Mann's public key for the GPG subsystem test

= 0.4.0 =
* Update: Store the public key fingerprint instead of the entire key for better references later
* Update: Introduce the `SECUREMSG_KEYCHAIN_DIR` constant for overriding where keys are stored
* Fix: Test for the presence of the GPG subsystem upon activation to prevent downstream errors

= 0.3.0 =
* Update: Switch to the Pear GPG library for better RSA compatibility

= 0.2.0 =
* Update: Use a new GPG library for better PHP compatibility
* Update: Add nonce checks on the profile page
* Update: Add Romanian translations

= 0.1.0 =
* First release

== Upgrade Notice ==

= 0.4.0 =
Both PHP 7 and a server-installed GPG environment are required for proper operation!
