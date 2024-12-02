<?php
/**
 * Plugin Name: Anti-DDOS Protection with reCAPTCHA and 2FA
 * Plugin URI: https://github.com/deseom
 * Description: Prevent DDOS attacks, integrate Google reCAPTCHA v3, and provide Two-Factor Authentication (2FA).
 * Version: 1.3
 * Author: Ridwan Sumantri
 * Author URI: https://github.com/deseom
 * License: GPLv2 or later
 */

// ==============================
// 1. Admin Menu for Settings
// ==============================
add_action('admin_menu', function () {
    add_menu_page(
        'Anti-DDOS & 2FA Settings',
        'Anti-DDOS & 2FA',
        'manage_options',
        'anti-ddos-2fa-settings',
        'anti_ddos_2fa_settings_page',
        'dashicons-shield',
        90
    );
});

function anti_ddos_2fa_settings_page() {
    ?>
    <div class="wrap">
        <h1>Anti-DDOS & 2FA Settings</h1>
        <h2>Google reCAPTCHA</h2>
        <form method="post" action="options.php">
            <?php
            settings_fields('anti_ddos_recaptcha_settings');
            do_settings_sections('anti-ddos-recaptcha');
            submit_button();
            ?>
        </form>
        <hr>
        <h2>Two-Factor Authentication (2FA)</h2>
        <?php
        if (!current_user_can('administrator')) {
            echo '<p>You do not have permission to configure 2FA.</p>';
            return;
        }

        // Include the QR Code library
        require_once __DIR__ . '/vendor/autoload.php';

        $current_user = wp_get_current_user();
        $secret = get_user_meta($current_user->ID, 'google_authenticator_secret', true);

        if (!$secret) {
            $ga = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
            $secret = $ga->generateSecret();
            update_user_meta($current_user->ID, 'google_authenticator_secret', $secret);
        }

        // Generate and display the QR Code
        echo '<h3>Scan the QR Code with Google Authenticator:</h3>';
        echo '<img src="' . esc_url(generate_2fa_qr_code($secret)) . '" alt="2FA QR Code" />';

        ?>
        <p><strong>Secret Key:</strong> <?php echo esc_html($secret); ?></p>
        <form method="post">
            <input type="submit" name="disable_2fa" value="Disable 2FA" class="button button-secondary">
        </form>
    </div>
    <?php
}

// ==============================
// 2. Generate QR Code for 2FA
// ==============================
function generate_2fa_qr_code($secret) {
    $site_name = urlencode(get_bloginfo('name'));
    $user_email = urlencode(wp_get_current_user()->user_email);
    $issuer = $site_name;
    $uri = "otpauth://totp/{$site_name}:{$user_email}?secret={$secret}&issuer={$issuer}";

    // Use the QR Code library
    $qrCode = new \Endroid\QrCode\QrCode($uri);
    $qrCode->setSize(200);

    // Use the PngWriter to write the QR code to a file
    $writer = new \Endroid\QrCode\Writer\PngWriter();

    // Define the temporary directory for QR codes
    $upload_dir = wp_upload_dir();
    $temp_dir = $upload_dir['basedir'] . '/2fa-qr-codes';
    if (!file_exists($temp_dir)) {
        mkdir($temp_dir, 0755, true);
    }

    // Define the file path
    $file_path = "{$temp_dir}/2fa_{$secret}.png";

    // Write the QR code to the file
    $result = $writer->write($qrCode);
    $result->saveToFile($file_path);

    // Return the public URL of the QR code
    return $upload_dir['baseurl'] . "/2fa-qr-codes/2fa_{$secret}.png";
}


// ==============================
// 3. reCAPTCHA Settings
// ==============================
add_action('admin_init', function () {
    register_setting('anti_ddos_recaptcha_settings', 'recaptcha_site_key');
    register_setting('anti_ddos_recaptcha_settings', 'recaptcha_secret_key');

    add_settings_section(
        'anti_ddos_recaptcha_section',
        'Google reCAPTCHA Settings',
        null,
        'anti-ddos-recaptcha'
    );

    add_settings_field(
        'recaptcha_site_key',
        'Site Key',
        function () {
            $value = get_option('recaptcha_site_key', '');
            echo '<input type="text" name="recaptcha_site_key" value="' . esc_attr($value) . '" class="regular-text">';
        },
        'anti-ddos-recaptcha',
        'anti_ddos_recaptcha_section'
    );

    add_settings_field(
        'recaptcha_secret_key',
        'Secret Key',
        function () {
            $value = get_option('recaptcha_secret_key', '');
            echo '<input type="text" name="recaptcha_secret_key" value="' . esc_attr($value) . '" class="regular-text">';
        },
        'anti-ddos-recaptcha',
        'anti_ddos_recaptcha_section'
    );
});

// ==============================
// 4. Login reCAPTCHA Verification
// ==============================
add_action('login_enqueue_scripts', function () {
    $site_key = get_option('recaptcha_site_key', '');
    if ($site_key) {
        echo '<script src="https://www.google.com/recaptcha/api.js?render=' . esc_attr($site_key) . '"></script>';
        echo '<script>
            grecaptcha.ready(function () {
                grecaptcha.execute("' . esc_attr($site_key) . '", { action: "login" }).then(function (token) {
                    var recaptchaInput = document.createElement("input");
                    recaptchaInput.type = "hidden";
                    recaptchaInput.name = "g-recaptcha-response";
                    recaptchaInput.value = token;
                    document.getElementById("loginform").appendChild(recaptchaInput);
                });
            });
        </script>';
    }
});

// ==============================
// 5. 2FA Verification on Login
// ==============================
add_filter('authenticate', function ($user, $username, $password) {
    if (is_wp_error($user)) {
        return $user;
    }

    $user_id = $user->ID;
    $secret = get_user_meta($user_id, 'google_authenticator_secret', true);

    if ($secret) {
        require_once __DIR__ . '/vendor/autoload.php';
        $ga = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
        $otp = sanitize_text_field($_POST['2fa_code']);
        if (!$ga->checkCode($secret, $otp)) {
            return new WP_Error('2fa_failed', __('Invalid 2FA code.'));
        }
    }

    return $user;
}, 30, 3);

add_action('login_form', function () {
    ?>
    <p>
        <label for="2fa_code">2FA Code<br>
            <input type="text" name="2fa_code" id="2fa_code" class="input" size="20" required>
        </label>
    </p>
    <?php
});
