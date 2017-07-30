=== Secure Messaging ===
Contributors:      ericmann
Donate link:       https://eamann.com
Tags:              PGP, GPG, Security
Requires at least: 4.7.3
Tested up to:      4.8
Stable tag:        0.3.0
License:           MIT
License URI:       https://opensource.org/licenses/MIT

GPG security for WordPress messaging.

== Description ==

Automatically encrypt certain WordPress messages using your GPG public key to ensure no one but you can ever read the message.

This is primarily used to secure password reset emails so, even if an attacker were to gain access to your email account, they couldn't change your WordPress password.

== Installation ==

= Manual Installation =

1. Upload the entire `/secure-messaging` directory to the `/wp-content/plugins/` directory.
2. Activate Secure Messaging through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==


== Screenshots ==


== Changelog ==

= 0.3.0=
* Update: Switch to the Pear GPG library for better RSA compatibility

= 0.2.0 =
* Update: Use a new GPG library for better PHP compatiblity
* Update: Add nonce checks on the profile page
* Update: Add Romanian translations

= 0.1.0 =
* First release

== Upgrade Notice ==

= 0.1.0 =
First Release
