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
 * Get the site's GPG keychain directory either from a constant or by creating one in WordPress'
 * standard content directory.
 *
 * @return string
 */
function get_keychain_dir() {
    if ( defined( 'SECUREMSG_KEYCHAIN_DIR' ) ) {
        return SECUREMSG_KEYCHAIN_DIR;
    }

    return WP_CONTENT_DIR . '/.gpg';
}

/**
 * Store the user's GPG public key in meta. Make sure it's base64-encoded to preserve newlines in the
 * ASCII-armoring.
 *
 * @param int $user_id
 */
function update_extra_profile_fields( $user_id ) {
	if ( current_user_can( 'edit_user', $user_id ) && wp_verify_nonce( $_POST['_securemsg_nonce'], 'change_gpg_key' ) ) {
		if ( isset( $_POST['securemsg_public_key'] ) ) {
            $gpg = new \Crypt_GPG( [ 'homedir' => get_keychain_dir() ] );

            try {
                $key_data = $gpg->importKey($_POST['securemsg_public_key']);

                update_user_meta( $user_id, 'gpg_key_fingerprint', $key_data['fingerprint'] );
            } catch (\Exception $e) {
                error_log( 'Unable to import key!' );
            }
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
	    $fingerprint = get_user_meta( $user->ID, 'gpg_key_fingerprint', true );
		?>
		<h3><?php esc_html_e( 'Secure Messaging', 'securemsg' ); ?></h3>
		<table class="form-table">
            <tr>
                <th><label for="securemsg_key_fingerprint"><?php esc_html_e( 'GPG Key Fingerprint', 'securemsg' ); ?></label></th>
                <td>
                    <input type="text" name="securemsg_key_fingerprint" id="securemsg_key_fingerprint" value="<?php echo esc_attr( $fingerprint ); ?>" class="regular-text" disabled="disabled" />
                    <p class="description">
                        <?php esc_html_e( 'Key fingerprint cannot be changed directly. Update your public key below instead.', 'securemsg' ); ?>
                    </p>
                </td>
            </tr>
			<tr>
				<th><label for="securemsg_public_key"><?php esc_html_e( 'GPG Public Key', 'securemsg' ); ?></label></th>
				<td>
					<textarea name="securemsg_public_key" id="securemsg_public_key" rows="5" cols="30"></textarea>
					<p class="description">
						<?php esc_html_e( 'Your public key will be used to automatically encrypt any messages sent to you by WordPress, ensuring no one but you can read them.', 'securemsg' ); ?>
					</p>
				</td>
			</tr>
            <?php wp_nonce_field( 'change_gpg_key', '_securemsg_nonce' ); ?>
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
    $fingerprint = get_user_meta( $user->ID, 'gpg_key_fingerprint', true );
    if ( empty( $fingerprint ) ) {
        error_log( sprintf( 'No GPG public key set for user: %s', $user->user_email ) );
        return $message;
    }

	try {
        $gpg = new \Crypt_GPG( [ 'homedir' => get_keychain_dir() ] );

	    // Get user key
        $gpg->addEncryptKey( $fingerprint );

		return $gpg->encrypt( $message );
	} catch ( \Exception $e ) {
        error_log( sprintf( 'Unable to find GPG public key for specified user: %s', $user->user_email ) );
		return $message;
	}
}