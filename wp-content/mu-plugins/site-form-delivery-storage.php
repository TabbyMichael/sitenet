<?php
/**
 * SITE Form Delivery — storage layer.
 *
 * Private CPT site_submission keeps a copy of every CF7 submit so nothing is
 * ever lost, even when email sending fails.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register private CPT for stored submissions.
 */
function site_register_submission_cpt() {
	$labels = array(
		'name'               => _x( 'Form Submissions', 'post type general name', 'site-child' ),
		'singular_name'      => _x( 'Form Submission', 'post type singular name', 'site-child' ),
		'menu_name'          => _x( 'Form Submissions', 'admin menu', 'site-child' ),
		'add_new_item'       => __( 'Add New Submission', 'site-child' ),
		'edit_item'          => __( 'View Submission', 'site-child' ),
		'all_items'          => __( 'All Submissions', 'site-child' ),
		'search_items'       => __( 'Search Submissions', 'site-child' ),
		'not_found'          => __( 'No submissions found.', 'site-child' ),
		'not_found_in_trash' => __( 'No submissions found in Trash.', 'site-child' ),
	);
	$args = array(
		'labels'             => $labels,
		'public'             => false,
		'publicly_queryable' => false,
		'exclude_from_search'=> true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_admin_bar'  => false,
		'show_in_rest'       => false,
		'query_var'          => false,
		'rewrite'            => false,
		'capability_type'    => 'post',
		'capabilities'       => array( 'create_posts' => 'edit_posts' ),
		'map_meta_cap'       => true,
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => 25,
		'menu_icon'          => 'dashicons-email-alt',
		'supports'           => array( 'title', 'editor' ),
	);
	register_post_type( 'site_submission', $args );
}
add_action( 'init', 'site_register_submission_cpt' );

/**
 * Build readable plain-text body from CF7 posted data.
 *
 * @param array $posted_data Raw posted data.
 * @return string Summary lines.
 */
function site_build_submission_body( $posted_data ) {
	$lines = array();
	foreach ( (array) $posted_data as $key => $value ) {
		if ( '' === $key || '_' === substr( (string) $key, 0, 1 ) ) {
			continue;
		}
		if ( is_array( $value ) ) {
			$value = implode( ', ', array_map( 'sanitize_text_field', $value ) );
		} else {
			$value = sanitize_text_field( (string) $value );
		}
		if ( '' === $value ) {
			continue;
		}
		$lines[] = sanitize_text_field( (string) $key ) . ': ' . $value;
	}
	return implode( "\n", $lines );
}

/**
 * Persist one CF7 submission before mail is attempted.
 *
 * @param WPCF7_ContactForm $contact_form Submitted form.
 * @return int Post ID, 0 on failure.
 */
function site_store_cf7_submission( $contact_form ) {
	if ( ! $contact_form instanceof WPCF7_ContactForm ) {
		return 0;
	}
	$submission = WPCF7_Submission::get_instance();
	if ( ! $submission ) {
		return 0;
	}
	$posted  = (array) $submission->get_posted_data();
	$post_id = wp_insert_post(
		array(
			'post_type'    => 'site_submission',
			'post_title'   => sanitize_text_field( $contact_form->title() . ' — ' . current_time( 'mysql' ) ),
			'post_content' => sanitize_textarea_field( site_build_submission_body( $posted ) ),
			'post_status'  => 'publish',
		),
		true
	);
	if ( is_wp_error( $post_id ) || ! $post_id ) {
		return 0;
	}
	update_post_meta( $post_id, '_site_form_id', (int) $contact_form->id() );
	update_post_meta( $post_id, '_site_form_title', sanitize_text_field( $contact_form->title() ) );
	update_post_meta( $post_id, '_site_mail_status', 'pending' );
	foreach ( $posted as $key => $value ) {
		if ( '' === $key || '_' === substr( (string) $key, 0, 1 ) ) {
			continue;
		}
		if ( is_array( $value ) ) {
			$value = implode( ', ', array_map( 'sanitize_text_field', $value ) );
		} else {
			$value = sanitize_text_field( (string) $value );
		}
		update_post_meta( $post_id, '_site_field_' . sanitize_key( $key ), $value );
	}
	$GLOBALS['site_last_submission_id'] = (int) $post_id;
	return (int) $post_id;
}

/**
 * Hook saving into CF7 before it tries to send mail.
 *
 * @param WPCF7_ContactForm $contact_form Submitted form.
 */
function site_cf7_save_before_mail( $contact_form ) {
	site_store_cf7_submission( $contact_form );
}
add_action( 'wpcf7_before_send_mail', 'site_cf7_save_before_mail', 10, 1 );

/**
 * Mark stored copy as sent.
 *
 * @param WPCF7_ContactForm $contact_form Submitted form.
 */
function site_cf7_mark_sent( $contact_form ) {
	if ( ! empty( $GLOBALS['site_last_submission_id'] ) ) {
		update_post_meta( (int) $GLOBALS['site_last_submission_id'], '_site_mail_status', 'sent' );
	}
}
add_action( 'wpcf7_mail_sent', 'site_cf7_mark_sent', 10, 1 );

/**
 * Mark stored copy as failed.
 *
 * @param WPCF7_ContactForm $contact_form Submitted form.
 */
function site_cf7_mark_failed( $contact_form ) {
	if ( ! empty( $GLOBALS['site_last_submission_id'] ) ) {
		update_post_meta( (int) $GLOBALS['site_last_submission_id'], '_site_mail_status', 'failed' );
	}
}
add_action( 'wpcf7_mail_failed', 'site_cf7_mark_failed', 10, 1 );

/**
 * Skip mail sending if SMTP is not configured.
 * This prevents CF7 from showing an error when submission was saved.
 *
 * @param bool   $skip      Whether to skip mail.
 * @return bool Modified skip status.
 */
function site_cf7_skip_mail_if_no_smtp( $skip ) {
	if ( empty( site_smtp_get( 'host' ) ) ) {
		// Update status to indicate no SMTP configured
		if ( ! empty( $GLOBALS['site_last_submission_id'] ) ) {
			update_post_meta( (int) $GLOBALS['site_last_submission_id'], '_site_mail_status', 'pending' );
		}
		return true;
	}
	return $skip;
}
add_filter( 'wpcf7_skip_mail', 'site_cf7_skip_mail_if_no_smtp', 10, 1 );

/**
 * Add Form + Mail columns to the submissions list.
 *
 * @param array $columns Existing columns.
 * @return array Filtered columns.
 */
function site_submission_columns( $columns ) {
	$columns['site_form']   = __( 'Form', 'site-child' );
	$columns['site_status'] = __( 'Mail', 'site-child' );
	return $columns;
}
add_filter( 'manage_site_submission_posts_columns', 'site_submission_columns' );

/**
 * Render custom columns.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function site_submission_column_content( $column, $post_id ) {
	if ( 'site_form' === $column ) {
		echo esc_html( get_post_meta( $post_id, '_site_form_title', true ) );
	}
	if ( 'site_status' === $column ) {
		echo esc_html( get_post_meta( $post_id, '_site_mail_status', true ) );
	}
}
add_action( 'manage_site_submission_posts_custom_column', 'site_submission_column_content', 10, 2 );
