<?php
namespace EAMann\Secure_Messaging\Core;

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
 * Activate the plugin
 *
 * @return void
 */
function activate() {
    $pub_key = <<<PKEY
-----BEGIN PGP PUBLIC KEY BLOCK-----

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
MEz4Y7IYUvvgJ23mahDJmOvFpUay
=n8ul
-----END PGP PUBLIC KEY BLOCK-----
PKEY;


    $to_verify = <<<VER
-----BEGIN PGP SIGNED MESSAGE-----
Hash: SHA512

Secure Messaging, Copyright (c) 2017 Eric Mann
-----BEGIN PGP SIGNATURE-----
Comment: GPGTools - https://gpgtools.org

iQIcBAEBCgAGBQJZfmTfAAoJEL7FVeIqFDVT+j8P/R5HyM26EVhshODPtCgwu8c7
AgNKR2/d/gUVBpfi5NQ4EfMD4FynGByHKrTrd0UQlqki5BUkkdEJPclWgOJYNXwr
yOaqUFxvxjQYZu5vOB2wRsoAOtEDS8LqwsWfOKLs+jkP059eV6JgpzcPx12HexLi
UldoRjpF4XkXadzxA5wUlpwl/XVi1ilRzek/fwkjNI9r8ST5P898H7L1vTJNUa9/
IzvXebsZSdVPc9AA+u7FozeCwjgimi/318BAQ8CLpPwBsXH7940YrBcIlNMMYVHc
BlkLd2BVd3nI6hMcaXZoHJSiwu0lv6gk58w0tNDrFWFp0cc0Bn7BFnff2gTre330
eMLK31iojy6QbvXYo6+6bNynLPw3RDPPmrNaesXiuQYYT1yNaT0CffBpEQVtEfB5
7PWLCiR61fA66gh5CZxgDdYpCrMQb7gPnS3N7z2BNsrozX0qJX02xQGSn1G21Y50
kgpaOJz6Rzq2SxJmrS/KYF+Z1geGTURUV+skjkKG+g/eQ3MdcT531Q7fPClqGggM
6RHUYm8KFWOw0j1kaqUf2/FJDcTr5QWLhiDrjDeaFvQhODyDgkOr7dJB8G5fgFBY
U7Ze/wG365HvZr0tPmWG1QnzPkOUYQBolBH2j2eWEvpM9fJL85ViApb04R3GIA6A
G6VgpUaOTJg0ThPJAA03
=1tC4
-----END PGP SIGNATURE-----

VER;

    try {
        // We don't care if things verify, we just need to run an operation.
        $gpg = new \Crypt_GPG( [ 'homedir' => get_keychain_dir() ] );
        $gpg->importKey( $pub_key );
        $gpg->verify( $to_verify );
    } catch (\Exception $e) {
        // GPG is inaccessible. Deactivate
        deactivate_plugins( SECUREMSG_BASENAME );
        exit( esc_html__( 'The GPG subsystem is not available. Secure Messaging has been deactivated.', 'securemsg' ) );
    }
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