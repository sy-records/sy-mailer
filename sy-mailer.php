<?php
/*
Plugin Name: Sy Mailer
Description: A WordPress email plugin that makes it convenient for users to configure SMTP settings.
Version: 1.0.1
Author: 沈唁
Author URI: https://qq52o.me
License: MIT
*/

if (!defined('ABSPATH')) {
    exit;
}

define('SY_MAILER_VERSION', '1.0.1');
define('SY_MAILER_PLUGIN_SLUG', 'sy-mailer');
define('SY_MAILER_PLUGIN_PAGE', plugin_basename(dirname(__FILE__)) . '%2F' . basename(__FILE__));
define('SY_MAILER_URL', plugins_url('/', __FILE__));
define('SY_MAILER_ASSETS_URL', SY_MAILER_URL . 'assets/');

require_once 'vendor/autoload.php';

use SyMailer\Config;
use SyMailer\Db;

register_activation_hook(__FILE__, 'sy_mailer_set_options');
function sy_mailer_set_options()
{
    $options = [
        'from' => '',
        'from_name' => '',
        'host' => '',
        'port' => '',
        'smtp_secure' => '',
        'smtp_auth' => 'yes',
        'username' => '',
        'password' => '',
        'disable_logs' => '',
    ];
    add_option('sy_mailer_options', $options, '', 'yes');

    sy_mailer_install_table();
}

function sy_mailer_install_table()
{
    global $wpdb;

    $tableName = $wpdb->prefix . 'sy_mailer_logs';

    $sql = "CREATE TABLE IF NOT EXISTS `" . $tableName . "` (
                `id` BIGINT unsigned NOT NULL AUTO_INCREMENT,
				`timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`to` VARCHAR(200) NOT NULL DEFAULT '0',
				`subject` VARCHAR(200) NOT NULL DEFAULT '0',
				`message` TEXT NULL,
				`headers` TEXT NULL,
				`error` TEXT NULL,
				PRIMARY KEY (`id`)
			) DEFAULT CHARACTER SET = utf8 DEFAULT COLLATE utf8_general_ci;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    dbDelta($sql);
}

/**
 * @return string[]
 */
function sy_mailer_setting_page_tabs()
{
    return [
        'config' => esc_html__('Config', 'sy-mailer'),
        'testing' => esc_html__('Testing', 'sy-mailer'),
        'logs' => esc_html__('Logs', 'sy-mailer'),
    ];
}

/**
 * @return string
 */
function sy_mailer_get_current_tab()
{
    $tabs = sy_mailer_setting_page_tabs();
    $parts = explode('-', sanitize_text_field($_GET['page']));
    $now = end($parts);
    return !isset($tabs[$now]) ? 'config' : $now;
}

add_action('admin_menu', 'sy_mailer_add_setting_page');

$options = get_option('sy_mailer_options');
if ('yes' !== $options['disable_logs']) {
    add_filter('wp_mail', 'sy_mailer_log_mails', PHP_INT_MAX);
}
add_action('wp_mail_failed', 'sy_mailer_update_failed_status', PHP_INT_MAX);

function sy_mailer_log_mails($parts)
{
    $data = $parts;

    unset($data['attachments']);

    DB::$id = Db::create()->insert($data);

    return $parts;
}

/**
 * @param WP_Error $wp_error
 */
function sy_mailer_update_failed_status($wp_error)
{
    DB::$phpmailer_error = $wp_error;

    $options = get_option('sy_mailer_options');
    if ('yes' !== $options['disable_logs']) {
        $data = $wp_error->get_error_data('wp_mail_failed');

        unset($data['phpmailer_exception_code']);
        unset($data['attachments']);

        $data['error'] = $wp_error->get_error_message();

        if (!is_numeric(DB::$id)) {
            Db::create()->insert($data);
        } else {
            Db::create()->update($data, ['id' => DB::$id]);
        }
    }
}

add_action('phpmailer_init', 'sy_mailer_init');
function sy_mailer_init($phpmailer)
{
    $options = get_option('sy_mailer_options');
    if (!is_email($options['from']) || empty($options['host'])) {
        return;
    }

    $phpmailer->Mailer = 'smtp';
    $phpmailer->From = $options['from'];
    $phpmailer->FromName = $options['from_name'];
    $phpmailer->Sender = $phpmailer->From;
    $phpmailer->AddReplyTo($phpmailer->From, $phpmailer->FromName);
    $phpmailer->Host = $options['host'];
    $phpmailer->SMTPAuth = 'yes' === $options['smtp_auth'];
    $phpmailer->Port = $options['port'];
    $phpmailer->SMTPSecure = $options['smtp_secure'];

    if ($phpmailer->SMTPAuth) {
        $phpmailer->Username = base64_decode($options['username']);
        $phpmailer->Password = base64_decode($options['password']);
    }
}

/**
 * @param array $options
 * @return bool
 */
function sy_mailer_check_credentials($options = [])
{
    if (!is_admin()) {
        return false;
    }

    if (empty($options)) {
        $options = get_option('sy_mailer_options');
    }

    $keys = ['username', 'password', 'host', 'port', 'smtp_auth'];

    foreach ($keys as $key) {
        if (empty($options[$key])) {
            $html = '<div class="error"><p><strong>' . esc_html__('Configuration error, please check and resave!', 'sy-mailer') . '</strong></p></div>';
            echo wp_kses($html, ['div' => ['class' => []], 'p' => [], 'strong' => []]);
            return false;
        }
    }

    global $phpmailer;

    if (!($phpmailer instanceof PHPMailer\PHPMailer\PHPMailer)) {
        require_once ABSPATH . WPINC . '/PHPMailer/PHPMailer.php';
        require_once ABSPATH . WPINC . '/PHPMailer/SMTP.php';
        require_once ABSPATH . WPINC . '/PHPMailer/Exception.php';
        $phpmailer = new PHPMailer\PHPMailer\PHPMailer(true);
    }

    $smtp = $phpmailer->getSMTPInstance();
    $smtp->Timeout = 15;
    $smtp->Timelimit = 15;
    $phpmailer->Timeout = 15;
    $phpmailer->Timelimit = 15;
    $phpmailer->Mailer = 'smtp';
    $phpmailer->Host = $options['host'];
    $phpmailer->SMTPAuth = 'yes' === $options['smtp_auth'];
    $phpmailer->Port = $options['port'];
    $phpmailer->SMTPKeepAlive = false;

    if ($phpmailer->SMTPAuth) {
        $phpmailer->Username = base64_decode($options['username']);
        $phpmailer->Password = base64_decode($options['password']);
    }

    $phpmailer->SMTPSecure = $options['smtp_secure'];

    try {
        if ($phpmailer->smtpConnect()) {
            return true;
        } else {
            throw new RuntimeException('smtp connect error');
        }
    } catch (Throwable $e) {
        $title = esc_html__('SMTP connection error', 'sy-mailer');
        $content = esc_html__('Configuration error, please check and resave!', 'sy-mailer');
        $html = "<div class='error'>
  <h3>{$title}</h3>
  <p>{$content}</p>
  <p>{$e->getMessage()}</p>
</div>";
        echo wp_kses($html, ['div' => ['class' => []], 'h3' => [], 'p' => []]);
        return false;
    }
}

function sy_mailer_plugin_action_links($links, $file)
{
    if ($file == urldecode(SY_MAILER_PLUGIN_PAGE)) {
        $page = SY_MAILER_PLUGIN_SLUG;
        $links[] = "<a href='admin.php?page={$page}'>" . esc_html__('Settings', 'sy-mailer') . "</a>";
        $links[] = "<a href='admin.php?page={$page}-logs'>" . esc_html__('Logs', 'sy-mailer') . "</a>";
    }
    return $links;
}

add_filter('plugin_action_links', 'sy_mailer_plugin_action_links', 10, 2);

function sy_mailer_add_setting_page()
{
    add_menu_page(
        esc_html__('Sy Mailer', 'sy-mailer'),
        esc_html__('Sy Mailer', 'sy-mailer'),
        'manage_options',
        SY_MAILER_PLUGIN_SLUG,
        'sy_mailer_setting_page',
        'dashicons-email'
    );
    foreach (sy_mailer_setting_page_tabs() as $tab => $name) {
        add_submenu_page(
            SY_MAILER_PLUGIN_SLUG,
            $name,
            $name,
            'manage_options',
            SY_MAILER_PLUGIN_SLUG . "-{$tab}",
            'sy_mailer_setting_page'
        );
    }
}

function sy_mailer_init_host_select()
{
    $lists = Config::lists();
    $html = '<select id="configSelect">';
    $html .= "<option value='' data-host='' data-port='' data-secure=''></option>";
    foreach ($lists as $key => $value) {
        $secure = isset($value['secure']) ? $value['secure'] : '';
        $html .= "<option value='{$key}' data-host='{$value['host']}' data-port='{$value['port']}' data-secure='{$secure}'>{$key}</option>";
    }
    $html .= '</select>';

    $html .= "<script>
        jQuery(document).ready(function($) {
            $('#configSelect').change(function() {
                var host = $(this).find('option:selected').data('host');
                var port = $(this).find('option:selected').data('port');
                var secure = $(this).find('option:selected').data('secure');
                $('#host').val(host);
                $('#port').val(port);
                if (secure === 1) {
                    $('.secure[value=ssl]').prop('checked', true);
                } else {
                    $('.secure:first').prop('checked', true);
                }
            });
        });
    </script>";
    echo wp_kses($html, ['select' => ['id' => []], 'option' => ['value' => [], 'data-host' => [], 'data-port' => [], 'data-secure' => []], 'script' => []]);
}

add_action('wp_ajax_sy_mailer_get_logs', 'sy_mailer_get_logs');
add_action('admin_enqueue_scripts', 'sy_mailer_enqueue_scripts');

function sy_mailer_enqueue_scripts()
{
    $screen = get_current_screen();

    if (!$screen) {
        return;
    }

    if ($screen->id === 'sy-mailer_page_sy-mailer-logs') {
        wp_enqueue_style('datatable', SY_MAILER_ASSETS_URL . 'datatables.min.css', [], SY_MAILER_VERSION);

        wp_register_script('datatable', SY_MAILER_ASSETS_URL . 'datatables.min.js', ['jquery'], SY_MAILER_VERSION, true);
        wp_register_script('sy-mailer-table', SY_MAILER_ASSETS_URL . 'table.js', ['jquery', 'datatable'], SY_MAILER_VERSION, true);
        wp_localize_script('sy-mailer-table', 'sy_mailer', ['ajaxurl' => admin_url('admin-ajax.php')]);

        wp_enqueue_script('sy-mailer-table');
    }
}

function sy_mailer_get_logs()
{
    check_ajax_referer('sy_mailer_logs', 'security');

    $result = Db::create()->get();
    $records_count = Db::create()->records_count();

    foreach ($result as $key => $value) {
        foreach ($value as $index => $data) {
            if ($index == 'message') {
                if (!preg_match('/<br>/', $data, $matches) && !preg_match('/<p>/', $data, $matches)) {
                    $data = nl2br($data);
                }

                $result[$key][$index] = wp_kses_post($data);
            } elseif (is_serialized($data)) {
                $result[$key][$index] = implode(',', array_map('esc_html', maybe_unserialize($data)));
            } else {
                $result[$key][$index] = esc_html($data);
            }
        }
    }

    $response = [
        'draw' => isset($_GET['draw']) ? absint($_GET['draw']) : 1,
        'recordsTotal' => $records_count,
        'recordsFiltered' => $records_count,
        'data' => $result
    ];

    if (isset($_GET['search']['value']) && !empty($_GET['search']['value'])) {
        $response['recordsFiltered'] = count($result);
    }

    die(wp_send_json($response));
}

function sy_mailer_setting_page()
{
    if (!current_user_can('manage_options')) {
        wp_die('Insufficient privileges!');
    }

    if (!empty($_POST) && $_POST['type'] == 'sy_mailer_config') {
        if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['sy_mailer_config-nonce'])), 'sy_mailer_config')) {
            wp_die('Security check not passed!');
        }

        $options = [];
        $options['from'] = sanitize_email(wp_unslash(trim($_POST['from'])));
        $options['from_name'] = sanitize_text_field(trim($_POST['from_name']));
        $options['host'] = sanitize_text_field(wp_unslash(trim($_POST['host'])));
        $options['smtp_secure'] = sanitize_text_field(wp_unslash(trim($_POST['smtp_secure'])));
        $options['port'] = is_numeric(trim($_POST['port'])) ? absint(trim($_POST['port'])) : '';
        $options['smtp_auth'] = sanitize_text_field(wp_unslash(trim($_POST['smtp_auth'])));
        $options['username'] = base64_encode(defined('SY_MAILER_USER') ? SY_MAILER_USER : sanitize_text_field(wp_unslash(trim($_POST['username']))));
        $options['password'] = base64_encode(defined('SY_MAILER_PASS') ? SY_MAILER_PASS : sanitize_text_field(trim($_POST['password'])));
        $options['disable_logs'] = (isset($_POST['disable_logs'])) ? sanitize_text_field(wp_unslash(trim($_POST['disable_logs']))) : '';

        update_option('sy_mailer_options', $options);

        $check = sy_mailer_check_credentials($options);
        if ($check) {
            $html = '<div class="updated"><p><strong>' . esc_html__('Configuration saved successfully!', 'sy-mailer') . '</strong></p></div>';
            echo wp_kses($html, ['div' => ['class' => []], 'p' => [], 'strong' => []]);
        }
    }

    if (!empty($_POST) && $_POST['type'] == 'sy_mailer_testing') {
        if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['sy_mailer_testing-nonce'])), 'sy_mailer_testing')) {
            wp_die('Security check not passed!');
        }

        $to = sanitize_email(wp_unslash(trim($_POST['to'])));
        $subject = sanitize_text_field(trim($_POST['subject']));
        $message = sanitize_textarea_field(trim($_POST['message']));
        $status = false;
        $class = 'error';

        if (!empty($to) && is_email($to) && !empty($subject) && !empty($message)) {
            try {
                $result = wp_mail($to, $subject, $message);
            } catch (Exception $e) {
                $status = $e->getMessage();
            }
        } else {
            $status = esc_html__('Fields are not filled in or there is an error.', 'sy-mailer');
        }

        if (!$status) {
            if ($result === true) {
                $status = esc_html__('Email sent!', 'sy-mailer');
                $class = 'updated';
            } else {
                $status = DB::$phpmailer_error->get_error_message();
            }
        }

        $html = '<div class="' . $class . '"><p><strong>' . $status . '</strong></p></div>';
        echo wp_kses($html, ['div' => ['class' => []], 'p' => [], 'strong' => []]);
    }

    $smtpOptions = get_option('sy_mailer_options', true);
    $currentTab = sy_mailer_get_current_tab();
?>
<div class="wrap">

  <h1><?php esc_html_e('Sy Mailer', 'sy-mailer'); ?><span style="font-size: 13px; padding-left: 10px;"><?php printf(esc_html__('Version: %s', 'sy-mailer'), esc_html(SY_MAILER_VERSION)); ?></span></h1>

  <h3 class="nav-tab-wrapper">
      <?php foreach (sy_mailer_setting_page_tabs() as $tab => $label): ?>
          <?php $href = SY_MAILER_PLUGIN_SLUG . '-' . $tab; ?>
        <a class="nav-tab <?php echo esc_attr($currentTab == $tab ? 'nav-tab-active' : ''); ?>"
           href="?page=<?php echo esc_attr($href); ?>"><?php echo esc_attr($label); ?></a>
      <?php endforeach; ?>
  </h3>

    <?php if ($currentTab == 'config'): ?>
      <form method="post">

        <table class="form-table">
          <tr>
            <th scope="row">
                <?php esc_html_e('From', 'sy-mailer'); ?>
            </th>
            <td>
              <label>
                <input type="email" name="from" value="<?php echo esc_attr($smtpOptions['from']); ?>" size="50"
                       required/>
              </label>
            </td>
          </tr>
          <tr>
            <th scope="row">
                <?php esc_html_e('From Name', 'sy-mailer'); ?>
            </th>
            <td>
              <label>
                <input type="text" name="from_name" value="<?php echo esc_attr($smtpOptions['from_name']); ?>"
                       size="50" required/>
              </label>
            </td>
          </tr>
          <tr>
            <th scope="row">
                <?php esc_html_e('Service Provider', 'sy-mailer'); ?>
            </th>
            <td>
              <label>
                  <?php sy_mailer_init_host_select(); ?>
              </label>
            </td>
          </tr>
          <tr>
            <th scope="row">
                <?php esc_html_e('SMTP Host', 'sy-mailer'); ?>
            </th>
            <td>
              <label>
                <input type="text" name="host" id="host" value="<?php echo esc_attr($smtpOptions['host']); ?>"
                       size="50" required/>
              </label>
            </td>
          </tr>
          <tr>
            <th scope="row">
                <?php esc_html_e('SMTP Port', 'sy-mailer'); ?>
            </th>
            <td>
              <label>
                <input type="text" name="port" id="port" value="<?php echo esc_attr($smtpOptions['port']); ?>" size="50" required/>
              </label>
            </td>
          </tr>
          <tr>
            <th scope="row">
                <?php esc_html_e('SMTP Secure', 'sy-mailer'); ?>
            </th>
            <td>
                <?php foreach (['' => 'None', 'ssl' => 'SSL', 'tls' => 'TLS'] as $secure => $secureName): ?>
                  <label>
                    <input name="smtp_secure" class="secure" type="radio" value="<?php echo esc_attr($secure); ?>"
                        <?php checked($smtpOptions['smtp_secure'], esc_attr($secure)); ?> />
                      <?php echo esc_attr($secureName); ?>
                  </label>
                <?php endforeach; ?>
            </td>
          </tr>
          <tr>
            <th scope="row">
                <?php esc_html_e('SMTP Authentication', 'sy-mailer'); ?>
            </th>
            <td>
                <?php foreach (['no' => 'No', 'yes' => 'Yes'] as $auth => $authName): ?>
                  <label>
                    <input name="smtp_auth" type="radio" value="<?php echo esc_attr($auth); ?>"
                        <?php checked($smtpOptions['smtp_auth'], esc_attr($auth)); ?> />
                      <?php echo esc_attr($authName); ?>
                  </label>
                <?php endforeach; ?>
            </td>
          </tr>
          <tr>
            <th scope="row">
                <?php esc_html_e('Username', 'sy-mailer'); ?>
            </th>
            <td>
              <label>
                <input type="text" name="username"
                       value="<?php echo esc_attr(base64_decode($smtpOptions['username'])); ?>" size="50" required/>
              </label>
            </td>
          </tr>
          <tr>
            <th scope="row">
                <?php esc_html_e('Password', 'sy-mailer'); ?>
            </th>
            <td>
              <label>
                <input type="password" name="password"
                       value="<?php echo esc_attr(base64_decode($smtpOptions['password'])); ?>" size="50" required/>
              </label>
            </td>
          </tr>
          <tr>
            <th scope="row">
                <?php esc_html_e('Disable Logs', 'sy-mailer'); ?>
            </th>
            <td>
              <label>
                <input type="checkbox" name="disable_logs" value="yes"
                    <?php checked($smtpOptions['disable_logs'], 'yes'); ?>/>
              <?php esc_html_e('Disable the email logging feature', 'sy-mailer'); ?>
              </label>
            </td>
          </tr>
        </table>

        <p class="submit">
          <input type="hidden" name="type" value="sy_mailer_config"/>
          <?php wp_nonce_field('sy_mailer_config', 'sy_mailer_config-nonce'); ?>
          <input type="submit" class="button-primary" value="<?php esc_html_e('Save Changes', 'sy-mailer'); ?>"/>
        </p>

      </form>
    <?php elseif ($currentTab == 'testing'): ?>
      <form method="post">

        <table class="form-table">
          <tr>
            <th scope="row">
                <?php esc_html_e('To', 'sy-mailer'); ?>
            </th>
            <td>
              <label>
                <input type="email" name="to" value="" size="50" required/>
              </label>
            </td>
          </tr>
          <tr>
            <th scope="row">
                <?php esc_html_e('Subject', 'sy-mailer'); ?>
            </th>
            <td>
              <label>
                <input type="text" name="subject" value="" size="50" required/>
              </label>
            </td>
          </tr>
          <tr>
            <th scope="row">
                <?php esc_html_e('Message', 'sy-mailer'); ?>
            </th>
            <td>
              <label>
                <textarea type="text" name="message" value="" cols="50" rows="5" required></textarea>
              </label>
            </td>
          </tr>
        </table>

        <p class="submit">
          <input type="hidden" name="type" value="sy_mailer_testing"/>
          <?php wp_nonce_field('sy_mailer_testing', 'sy_mailer_testing-nonce'); ?>
          <input type="submit" class="button-primary" value="<?php esc_html_e('Send Test', 'sy-mailer'); ?>"/>
        </p>

      </form>
    <?php elseif ($currentTab == 'logs'): ?>
      <style>
        #sy-mailer-log_wrapper #dt-length-0 {width: 50px}
        .details-control:before {content: '\25B6';padding-right: 5px;}
        .details-control.details:before {content: '\25BC';padding-right: 5px;}
      </style>
      <div id="md-security" data-security="<?php echo esc_attr(wp_create_nonce('sy_mailer_logs')); ?>"></div>
      <table id="sy-mailer-log" class="display widefat" style="width:100%">
        <thead>
        <tr>
          <th><?php esc_html_e('ID', 'sy-mailer'); ?></th>
          <th><?php esc_html_e('To', 'sy-mailer'); ?></th>
          <th><?php esc_html_e('Timestamp', 'sy-mailer'); ?></th>
          <th><?php esc_html_e('Subject', 'sy-mailer'); ?></th>
          <th><?php esc_html_e('Error', 'sy-mailer'); ?></th>
        </tr>
        </thead>
        <tbody></tbody>
        <tfoot>
        <tr>
          <th><?php esc_html_e('ID', 'sy-mailer'); ?></th>
          <th><?php esc_html_e('To', 'sy-mailer'); ?></th>
          <th><?php esc_html_e('Timestamp', 'sy-mailer'); ?></th>
          <th><?php esc_html_e('Subject', 'sy-mailer'); ?></th>
          <th><?php esc_html_e('Error', 'sy-mailer'); ?></th>
        </tr>
        </tfoot>
      </table>
    <?php endif; ?>

<?php
}
?>
