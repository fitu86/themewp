<?php
/**
 * The template to display Admin notices
 *
 * @package CARZ
 * @since CARZ 1.0.1
 */

$carz_theme_slug = get_template();
$carz_theme_obj  = wp_get_theme( $carz_theme_slug );

?>
<div class="carz_admin_notice carz_rate_notice notice notice-info is-dismissible" data-notice="rate">
	<?php
	// Theme image
	$carz_theme_img = carz_get_file_url( 'screenshot.jpg' );
	if ( '' != $carz_theme_img ) {
		?>
		<div class="carz_notice_image"><img src="<?php echo esc_url( $carz_theme_img ); ?>" alt="<?php esc_attr_e( 'Theme screenshot', 'carz' ); ?>"></div>
		<?php
	}

	// Title
	$carz_theme_name = '"' . $carz_theme_obj->get( 'Name' ) . ( CARZ_THEME_FREE ? ' ' . __( 'Free', 'carz' ) : '' ) . '"';
	?>
	<h3 class="carz_notice_title"><a href="<?php echo esc_url( carz_storage_get( 'theme_rate_url' ) ); ?>"<?php if ( function_exists( 'carz_external_links_target' ) ) echo carz_external_links_target( true ); ?>>
		<?php
		echo esc_html(
			sprintf(
				// Translators: Add theme name to the 'Welcome' message
				__( 'Help Us Grow - Rate %s Today!', 'carz' ),
				$carz_theme_name
			)
		);
		?>
	</a></h3>
	<?php

	// Description
	?>
	<div class="carz_notice_text">
		<p><?php
			// Translators: Add theme name to the 'Welcome' message
			echo wp_kses_data( sprintf( __( "Thank you for choosing the %s theme for your website! We're excited to see how you've customized your site, and we hope you've enjoyed working with our theme.", 'carz' ), $carz_theme_name ) );
		?></p>
		<p><?php
			// Translators: Add theme name to the 'Welcome' message
			echo wp_kses_data( sprintf( __( "Your feedback really matters to us! If you've had a positive experience, we'd love for you to take a moment to rate %s and share your thoughts on the customer service you received.", 'carz' ), $carz_theme_name ) );
		?></p>
	</div>
	<?php

	// Buttons
	?>
	<div class="carz_notice_buttons">
		<?php
		// Link to the theme download page
		?>
		<a href="<?php echo esc_url( carz_storage_get( 'theme_rate_url' ) ); ?>" class="button button-primary"<?php if ( function_exists( 'carz_external_links_target' ) ) echo carz_external_links_target( true ); ?>><i class="dashicons dashicons-star-filled"></i> 
			<?php
			// Translators: Add the theme name to the button caption
			echo esc_html( sprintf( __( 'Rate %s Now', 'carz' ), $carz_theme_name ) );
			?>
		</a>
		<?php
		// Link to the theme support
		?>
		<a href="<?php echo esc_url( carz_storage_get( 'theme_support_url' ) ); ?>" class="button"<?php if ( function_exists( 'carz_external_links_target' ) ) echo carz_external_links_target( true ); ?>><i class="dashicons dashicons-sos"></i> 
			<?php
			esc_html_e( 'Support', 'carz' );
			?>
		</a>
		<?php
		// Link to the theme documentation
		?>
		<a href="<?php echo esc_url( carz_storage_get( 'theme_doc_url' ) ); ?>" class="button"<?php if ( function_exists( 'carz_external_links_target' ) ) echo carz_external_links_target( true ); ?>><i class="dashicons dashicons-book"></i> 
			<?php
			esc_html_e( 'Documentation', 'carz' );
			?>
		</a>
	</div>
</div>
