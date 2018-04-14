<?php
/*
Plugin Name: Contact Form
Plugin URI: https://github.com/sivadass/wordpress-contact-form-plugin
Description: Simple non-bloated WordPress Contact Form
Version: 1.0
Author: Sivadass
Author URI: https://sivadass.in
*/

function html_form_code() {
	echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post" class="contact-form">';
	echo '<p class="required-fields">';
	echo 'Your email address will be safe with me. Required fields are marked *';
	echo '</p>';
	echo '<p>';
	echo 'Your Name <span>*</span> <br/>';
	echo '<input type="text" name="cf-name" pattern="[a-zA-Z0-9 ]+" value="' . ( isset( $_POST["cf-name"] ) ? esc_attr( $_POST["cf-name"] ) : '' ) . '" size="40" required/>';
	echo '</p>';
	echo '<p>';
	echo 'Your Email <span>*</span> <br/>';
	echo '<input type="email" name="cf-email" value="' . ( isset( $_POST["cf-email"] ) ? esc_attr( $_POST["cf-email"] ) : '' ) . '" size="40" required/>';
	echo '</p>';
	echo '<p>';
	echo 'Subject <span>*</span> <br/>';
	echo '<input type="text" name="cf-subject" pattern="[a-zA-Z ]+" value="' . ( isset( $_POST["cf-subject"] ) ? esc_attr( $_POST["cf-subject"] ) : '' ) . '" size="40" required/>';
	echo '</p>';
	echo '<p>';
	echo 'Your Message <span>*</span> <br/>';
	echo '<textarea rows="4" cols="35" name="cf-message" required>' . ( isset( $_POST["cf-message"] ) ? esc_attr( $_POST["cf-message"] ) : '' ) . '</textarea>';
	echo '</p>';
	echo '<p><button type="submit" name="cf-submitted" class="submit">Send</button></p>';
	echo '</form>';
}

function deliver_mail() {

	// if the submit button is clicked, send the email
	if ( isset( $_POST['cf-submitted'] ) ) {

		// sanitize form values
		$form    = "$(.contact-form)";
		$name    = sanitize_text_field( $_POST["cf-name"] );
		$email   = sanitize_email( $_POST["cf-email"] );
		$subject = sanitize_text_field( $_POST["cf-subject"] );
		$message = esc_textarea( $_POST["cf-message"] );

		// Add your email address here
		$to = "username@johndoe.com";
		
		$headers = "From: $name <$email>" . "\r\n";

		// If email has been process for sending, display a success message
		if ( wp_mail( $to, $subject, $message, $headers ) ) {
			$_POST = array();
			echo '<p class="message success"><i class="icon-success"></i> Thanks for contacting me, expect a response soon :)</p>';
		} else {
			echo '<p class="message error"><i class="icon-error"></i> Sorry, an unexpected error occurred. Please try after sometime.</p>';
		}
	}
}

function cf_shortcode() {
	ob_start();
	deliver_mail();
	html_form_code();

	return ob_get_clean();
}

add_shortcode( 'contact_form', 'cf_shortcode' );

?>