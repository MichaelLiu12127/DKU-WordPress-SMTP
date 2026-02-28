<?php
/**
 * Plugin Name: DKU WordPress SMTP
 * Description: Force SMTP for WordPress websites.
 * Author: Web Support Team, IT Office
 * Version: 1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class University_Mail_System {

    public function __construct() {

        // 强制 SMTP
        add_action('phpmailer_init', [$this, 'configure_smtp']);

        // 强制统一发件人
        add_filter('wp_mail_from', [$this, 'force_from_email']);
        add_filter('wp_mail_from_name', [$this, 'force_from_name']);

        // 防止插件修改 Mailer
        add_action('phpmailer_init', [$this, 'force_mailer'], 999);
    }

    public function configure_smtp($phpmailer) {

        $phpmailer->isSMTP();

        $phpmailer->Host       = defined('SMTP_HOST') ? SMTP_HOST : 'smtp.duke.edu';
        $phpmailer->Port       = defined('SMTP_PORT') ? SMTP_PORT : 587;
        $phpmailer->SMTPAuth   = true;
        $phpmailer->SMTPSecure = defined('SMTP_ENCRYPTION') ? SMTP_ENCRYPTION : 'tls';

        $phpmailer->Username   = defined('SMTP_USER') ? SMTP_USER : 'dku_website_smtp';
        $phpmailer->Password   = defined('SMTP_PASS') ? SMTP_PASS : '8eaWCyJ6JraZEpKHsC9HIopE';

        $phpmailer->From       = $this->force_from_email();
        $phpmailer->FromName   = $this->force_from_name();
    }

    public function force_from_email() {
        return defined('SMTP_FROM') ? SMTP_FROM : 'cl677@duke.edu';
    }

    public function force_from_name() {
        return defined('SMTP_FROM_NAME') ? SMTP_FROM_NAME : get_bloginfo('Michael');
    }

    public function force_mailer($phpmailer) {
        $phpmailer->Mailer = 'smtp';
    }
}

new University_Mail_System();
