<?php
/**
 * The template to display Admin notices
 *
 * @package CARZ
 * @since CARZ 1.0.1
 */

$carz_theme_slug = get_option( 'template' );
$carz_theme_obj  = wp_get_theme( $carz_theme_slug );
?>
<div class="carz_admin_notice carz_welcome_notice notice notice-info is-dismissible" data-notice="admin">
	<?php
	// Theme image
	$carz_theme_img = carz_get_file_url( 'screenshot.jpg' );
	if ( '' != $carz_theme_img ) {
		?>
		<div class="carz_notice_image"><img src="<?php echo esc_url( $carz_theme_img ); ?>" alt="<?php esc_attr_e( 'Theme screenshot', 'carz' ); ?>"></div>
		<?php
	}

	// Title
	?>
	<h3 class="carz_notice_title">
		<?php
		echo esc_html(
			sprintf(
				// Translators: Add theme name and version to the 'Welcome' message
				__( 'Welcome to %1$s v.%2$s', 'carz' ),
				$carz_theme_obj->get( 'Name' ) . ( CARZ_THEME_FREE ? ' ' . __( 'Free', 'carz' ) : '' ),
				$carz_theme_obj->get( 'Version' )
			)
		);
		?>
	</h3>
	<?php

	// Description
	?>
	<div class="carz_notice_text">
		<p class="carz_notice_text_description">
			<?php
			echo str_replace( '. ', '.<br>', wp_kses_data( $carz_theme_obj->description ) );
			?>
		</p>
		<p class="carz_notice_text_info">
			<?php
			echo wp_kses_data( __( 'Attention! Plugin "ThemeREX Addons" is required! Please, install and activate it!', 'carz' ) );
			?>
		</p>
	</div>
	<?php

	// Buttons
	?>
	<div class="carz_notice_buttons">
		<?php
		// Link to the page 'About Theme'
		?>
		<a href="<?php echo esc_url( admin_url() . 'themes.php?page=carz_about' ); ?>" class="button button-primary"><i class="dashicons dashicons-nametag"></i> 
			<?php
			echo esc_html__( 'Install plugin "ThemeREX Addons"', 'carz' );
			?>
		</a>
	</div>
</div>
