<?php
namespace EAMann\Secure_Messaging\Core;

use nicoSWD\GPG\GPG;
use nicoSWD\GPG\PublicKey;

/**
 * Default setup routine
 *
 * @uses add_action()
 * @uses do_action()
 *
 * @return void
 */
function setup() {
	$n = function ( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	add_action( 'init', $n( 'i18n' ) );
	add_action( 'init', $n( 'init' ) );

	// user editing their own profile page.
	add_action( 'personal_options_update',  $n( 'update_extra_profile_fields' ) );
	add_action( 'profile_personal_options', $n( 'extra_profile_fields' ) );

	add_filter( 'retrieve_password_message', $n( 'protect_message' ), 10, 4 );

	do_action( 'securemsg_loaded' );
}

/**
 * Registers the default textdomain.
 *
 * @uses apply_filters()
 * @uses get_locale()
 * @uses load_textdomain()
 * @uses load_plugin_textdomain()
 * @uses plugin_basename()
 *
 * @return void
 */
function i18n() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'securemsg' );
	load_textdomain( 'securemsg', WP_LANG_DIR . '/securemsg/securemsg-' . $locale . '.mo' );
	load_plugin_textdomain( 'securemsg', false, plugin_basename( SECUREMSG_PATH ) . '/languages/' );
}

/**
 * Initializes the plugin and fires an action other plugins can hook into.
 *
 * @uses do_action()
 *
 * @return void
 */
function init() {
	do_action( 'securemsg_init' );
}

/**
 * Store the user's GPG public key in meta. Make sure it's base64-encoded to preserve newlines in the
 * ASCII-armoring.
 *
 * @param int $user_id
 */
function update_extra_profile_fields( $user_id ) {
	if ( current_user_can( 'edit_user', $user_id ) ) {
		if ( isset( $_POST['securemsg_public_key'] ) ) {
			update_user_meta( $user_id, 'gpg_public_key', base64_encode( $_POST['securemsg_public_key'] ) );
		} else {
			delete_user_meta( $user_id, 'gpg_public_key' );
		}
	}
}

/**
 * Display the user's GPG public key, if it's set.
 *
 * @param \WP_User $user
 */
function extra_profile_fields( $user ) {
	if ( current_user_can( 'edit_user', $user->ID ) ) {
		$key = get_user_meta( $user->ID, 'gpg_public_key', true );
		if ( ! empty( $key ) ) {
			$key = base64_decode( $key );
		}
		?>
		<h3><?php esc_html_e( 'Secure Messaging', 'securemsg' ); ?></h3>
		<table class="form-table">
			<tr>
				<th><span><?php esc_html_e( 'GPG Public Key', 'securemsg' ); ?></span></th>
				<td>
					<textarea name="securemsg_public_key" id="securemsg_public_key" rows="5" cols="30"><?php echo esc_textarea( $key ); ?></textarea>
					<p class="description">
						<?php esc_html_e( 'Your public key will be used to automatically encrypt any messages sent to you by WordPress, ensuring no one but you can read them.', 'securemsg' ); ?>
					</p>
				</td>
			</tr>
		</table>
		<?php
	}
}

/**
 * Attempt to encrypt the password reset message if the user has a GPG public key stored in meta.
 *
 * @param string  $message    Plaintext message to protect
 * @param string  $key        Reset key embedded in the message
 * @param string  $user_login Username
 * @param \WP_User $user      WordPress user object
 *
 * @return string
 */
function protect_message( $message, $key, $user_login, $user ) {
	$key = get_user_meta( $user->ID, 'gpg_public_key', true );

	if ( empty( $key ) ) {
		return $message;
	}

	try {
		$gpg = new GPG();
		$pubKey = new PublicKey(base64_decode($key));

		return $gpg->encrypt($pubKey, $message);
	} catch ( \Exception $e ) {
		return $message;
	}
}