<?php
/**
 * Email Notification Util
 *
 * This class contains helper functions for  email notification.
 *
 * @package     Give
 * @subpackage  Classes/Emails
 * @copyright   Copyright (c) 2016, WordImpress
 * @license     https://opensource.org/licenses/gpl-license GNU Public License
 * @since       2.0
 */

// Exit if access directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class Give_Email_Notification_Util {
	/**
	 * Instance.
	 *
	 * @since  2.0
	 * @access static
	 * @var
	 */
	static private $instance;

	/**
	 * Singleton pattern.
	 *
	 * @since  2.0
	 * @access private
	 * Give_Email_Notification_Util constructor.
	 */
	private function __construct() {
	}


	/**
	 * Get instance.
	 *
	 * @since  2.0
	 * @access static
	 * @return static
	 */
	static function get_instance() {
		if ( null === static::$instance ) {
			self::$instance = new static();
		}

		return self::$instance;
	}


	/**
	 * Check if notification has preview field or not.
	 *
	 * @since  2.0
	 * @access public
	 *
	 * @param Give_Email_Notification $email
	 *
	 * @return bool
	 */
	public static function has_preview( Give_Email_Notification $email ) {
		return $email->config['has_preview'];
	}

	/**
	 * Check if notification has recipient field or not.
	 *
	 * @since  2.0
	 * @access public
	 *
	 * @param Give_Email_Notification $email
	 *
	 * @return bool
	 */
	public static function has_recipient_field( Give_Email_Notification $email ) {
		return $email->config['has_recipient_field'];
	}

	/**
	 * Check if admin can edit notification status or not.
	 *
	 * @since  2.0
	 * @access public
	 *
	 * @param Give_Email_Notification $email
	 *
	 * @return bool
	 */
	public static function is_notification_status_editable( Give_Email_Notification $email ) {
		return $email->config['notification_status_editable'];
	}

	/**
	 * Check if admin can edit notification status or not.
	 *
	 * @since  2.0
	 * @access public
	 *
	 * @param Give_Email_Notification $email
	 *
	 * @return bool
	 */
	public static function is_content_type_editable( Give_Email_Notification $email ) {
		return $email->config['content_type_editable'];
	}

	/**
	 * Check email preview header active or not.
	 *
	 * @since  2.0
	 * @access public
	 *
	 * @param Give_Email_Notification $email
	 *
	 * @return bool
	 */
	public static function is_email_preview_has_header( Give_Email_Notification $email ) {
		return $email->config['has_preview_header'];
	}

	/**
	 * Check email preview header active or not.
	 *
	 * @since  2.0
	 * @access public
	 *
	 * @param Give_Email_Notification $email
	 *
	 * @return bool
	 */
	public static function is_email_preview( Give_Email_Notification $email ) {
		return $email->config['has_preview'];
	}


	/**
	 * Check email active or not.
	 *
	 * @since  2.0
	 * @access public
	 *
	 * @param Give_Email_Notification $email
	 * @param int $form_id
	 *
	 * @return string
	 */
	public static function is_email_notification_active( Give_Email_Notification $email, $form_id = null ) {
		$notification_status = $email->get_notification_status( $form_id );


		$notification_status = empty( $form_id )
			? give_is_setting_enabled( $notification_status )
			: give_is_setting_enabled( give_get_option( "{$email->config['id']}_notification", $email->config['notification_status'] ) ) && give_is_setting_enabled( $notification_status, array( 'enabled', 'global' ) );
			// To check if email notification active or not on per form basis, email notification must be globally active other it will consider as disable.

		return $notification_status;
	}

	/**
	 * Check if admin preview email or not
	 *
	 * @since  2.0
	 * @access public
	 * @return bool   $is_preview
	 */
	public static function can_preview_email() {
		$is_preview = false;

		if (
			current_user_can( 'manage_give_settings' )
			&& ! empty( $_GET['give_action'] )
			&& 'preview_email' === $_GET['give_action']
		) {
			$is_preview = true;
		}

		return $is_preview;
	}

	/**
	 * Check if admin preview email or not
	 *
	 * @since  2.0
	 * @access public
	 * @return bool   $is_preview
	 */
	public static function can_send_preview_email() {
		$is_preview = false;

		if (
			current_user_can( 'manage_give_settings' )
			&& ! empty( $_GET['give_action'] )
			&& 'send_preview_email' === $_GET['give_action']
		) {
			$is_preview = true;
		}

		return $is_preview;
	}


	/**
	 * Get formatted text for email content type.
	 *
	 * @since  2.0
	 * @access public
	 *
	 * @param string $content_type
	 *
	 * @return string
	 */
	public static function get_formatted_email_type( $content_type ) {
		$email_contents = array(
			'text/html'  => __( 'HTML', 'give' ),
			'text/plain' => __( 'Plain', 'give' ),
		);

		return $email_contents[ $content_type ];
	}


	/**
	 * Get email notification option value.
	 *
	 * @since  2.0
	 * @access public
	 *
	 * @param Give_Email_Notification $email
	 * @param string                   $option_name
	 * @param int                      $form_id
	 * @param mixed                    $default
	 *
	 * @return mixed
	 */
	public static function get_value( Give_Email_Notification $email, $option_name, $form_id = null, $default = false ) {
		$option_value = give_get_option( $option_name, $default );

		if (
			! empty( $form_id )
			&& give_is_setting_enabled( get_post_meta( $form_id, "{$email->config['id']}_notification", true ) )
		) {
			$option_value = get_post_meta( $form_id, $option_name, true );
		}

		$option_value = empty( $option_value ) ? $default : $option_value;

		/**
		 * Filter the setting value
		 *
		 * @since 2.0
		 */
		return apply_filters( 'give_email_setting_value', $option_value, $option_name, $email, $form_id, $default );
	}
}
