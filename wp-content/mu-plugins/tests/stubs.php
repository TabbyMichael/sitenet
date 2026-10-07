<?php
/**
 * Minimal WP/CF7 stubs for the standalone test runner.
 */

$GLOBALS['site_test_options']   = array();
$GLOBALS['site_test_postmeta']  = array();
$GLOBALS['site_test_actions']   = array();
$GLOBALS['site_test_filters']   = array();
$GLOBALS['site_test_cpts']      = array();
$GLOBALS['site_test_inserted']  = array();
$GLOBALS['site_test_next_id']   = 100;
$GLOBALS['site_test_home']      = 'https://sitenet.org';
$GLOBALS['site_test_logged_in'] = true;

function add_action( $hook, $cb, $pri = 10, $args = 1 ) {
	$GLOBALS['site_test_actions'][ $hook ][] = array( $cb, $pri, $args );
}
function add_filter( $hook, $cb, $pri = 10, $args = 1 ) {
	$GLOBALS['site_test_filters'][ $hook ][] = array( $cb, $pri, $args );
}
function register_post_type( $slug, $args ) {
	$GLOBALS['site_test_cpts'][ $slug ] = $args;
}
function get_option( $key, $default = false ) {
	return array_key_exists( $key, $GLOBALS['site_test_options'] ) ? $GLOBALS['site_test_options'][ $key ] : $default;
}
function update_option( $key, $value, $autoload = null ) {
	$GLOBALS['site_test_options'][ $key ] = $value;
	return true;
}
function update_post_meta( $id, $key, $value ) {
	$GLOBALS['site_test_postmeta'][ $id ][ $key ] = $value;
	return true;
}
function get_post_meta( $id, $key, $single = false ) {
	$val = isset( $GLOBALS['site_test_postmeta'][ $id ][ $key ] ) ? $GLOBALS['site_test_postmeta'][ $id ][ $key ] : '';
	return $single ? $val : array( $val );
}
function wp_insert_post( $data, $wp_error = false ) {
	$id = $GLOBALS['site_test_next_id']++;
	$GLOBALS['site_test_inserted'][ $id ] = $data;
	return $id;
}
function is_wp_error( $thing ) {
	return $thing instanceof WP_Error;
}
function current_time( $type ) {
	return '2026-10-07 12:00:00';
}
function current_user_can( $cap ) {
	return $GLOBALS['site_test_logged_in'];
}
function home_url() {
	return $GLOBALS['site_test_home'];
}
function wp_parse_url( $url, $part = -1 ) {
	return parse_url( $url, $part );
}
function sanitize_text_field( $s ) {
	return trim( strip_tags( (string) $s ) );
}
function sanitize_textarea_field( $s ) {
	return trim( strip_tags( (string) $s ) );
}
function sanitize_key( $s ) {
	return strtolower( preg_replace( '/[^a-zA-Z0-9_\-]/', '', (string) $s ) );
}
function sanitize_email( $s ) {
	return trim( (string) $s );
}
function is_email( $s ) {
	return false !== filter_var( $s, FILTER_VALIDATE_EMAIL );
}
function esc_html( $s ) {
	return htmlspecialchars( (string) $s, ENT_QUOTES, 'UTF-8' );
}
function esc_html__( $s, $d = null ) {
	return $s;
}
function esc_html_e( $s, $d = null ) {
	echo $s;
}
function esc_attr( $s ) {
	return htmlspecialchars( (string) $s, ENT_QUOTES, 'UTF-8' );
}
function __( $s, $d = null ) {
	return $s;
}
function _x( $s, $c, $d = null ) {
	return $s;
}
function register_setting( $group, $name, $args = array() ) {
	return true;
}
function add_options_page( $t1, $t2, $cap, $slug, $cb ) {
	return $slug;
}
function selected( $a, $b ) {
	echo ( (string) $a === (string) $b ) ? ' selected' : '';
}
function settings_fields( $g ) {
	echo '';
}
function submit_button( $t = null, $type = 'primary', $name = null, $wrap = true ) {
	echo '';
}
function wp_nonce_field( $a, $n ) {
	echo '';
}
function check_admin_referer( $a, $n ) {
	return true;
}
function wp_unslash( $v ) {
	return $v;
}
function wp_mail( $to, $subj, $body ) {
	return true;
}
function get_current_screen() {
	return null;
}

class WP_Error {
	private $msg;
	public function __construct( $code = '', $msg = '' ) {
		$this->msg = $msg;
	}
	public function get_error_message() {
		return $this->msg;
	}
}

class WPCF7_ContactForm {
	private $id;
	private $t;
	public function __construct( $id = 630, $t = 'Contact Form' ) {
		$this->id = $id;
		$this->t  = $t;
	}
	public function id() {
		return $this->id;
	}
	public function title() {
		return $this->t;
	}
}

class WPCF7_Submission {
	private static $instance = null;
	private $data = array();
	public static function get_instance() {
		return self::$instance;
	}
	public static function set_test_data( $data ) {
		$s            = new self();
		$s->data      = $data;
		self::$instance = $s;
	}
	public function get_posted_data() {
		return $this->data;
	}
}

class Fake_Mailer {
	public $Host = '';
	public $Port = 0;
	public $SMTPAuth = false;
	public $Username = '';
	public $Password = '';
	public $SMTPSecure = '';
	public $SMTPDebug = 0;
	public $Debugoutput = null;
	public $From = '';
	public $FromName = '';
	public $smtp_mode = false;
	public function isSMTP() {
		$this->smtp_mode = true;
	}
	public function setFrom( $email, $name = '' ) {
		$this->From     = $email;
		$this->FromName = $name;
	}
}
