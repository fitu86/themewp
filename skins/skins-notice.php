<?php
/**
 * The template to display Admin notices
 *
 * @package CARZ
 * @since CARZ 1.0.64
 */

$carz_skins_url  = get_admin_url( null, 'admin.php?page=trx_addons_theme_panel#trx_addons_theme_panel_section_skins' );
$carz_skins_args = get_query_var( 'carz_skins_notice_args' );
?>
<div class="carz_admin_notice carz_skins_notice notice notice-info is-dismissible" data-notice="skins">
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
		<?php esc_html_e( 'New skins are available', 'carz' ); ?>
	</h3>
	<?php

	// Description
	$carz_total      = $carz_skins_args['update'];	// Store value to the separate variable to avoid warnings from ThemeCheck plugin!
	$carz_skins_msg  = $carz_total > 0
							// Translators: Add new skins number
							? '<strong>' . sprintf( _n( '%d new version', '%d new versions', $carz_total, 'carz' ), $carz_total ) . '</strong>'
							: '';
	$carz_total      = $carz_skins_args['free'];
	$carz_skins_msg .= $carz_total > 0
							? ( ! empty( $carz_skins_msg ) ? ' ' . esc_html__( 'and', 'carz' ) . ' ' : '' )
								// Translators: Add new skins number
								. '<strong>' . sprintf( _n( '%d free skin', '%d free skins', $carz_total, 'carz' ), $carz_total ) . '</strong>'
							: '';
	$carz_total      = $carz_skins_args['pay'];
	$carz_skins_msg .= $carz_skins_args['pay'] > 0
							? ( ! empty( $carz_skins_msg ) ? ' ' . esc_html__( 'and', 'carz' ) . ' ' : '' )
								// Translators: Add new skins number
								. '<strong>' . sprintf( _n( '%d paid skin', '%d paid skins', $carz_total, 'carz' ), $carz_total ) . '</strong>'
							: '';
	?>
	<div class="carz_notice_text">
		<p>
			<?php
			// Translators: Add new skins info
			echo wp_kses_data( sprintf( __( "We are pleased to announce that %s are available for your theme", 'carz' ), $carz_skins_msg ) );
			?>
		</p>
	</div>
	<?php

	// Buttons
	?>
	<div class="carz_notice_buttons">
		<?php
		// Link to the theme dashboard page
		?>
		<a href="<?php echo esc_url( $carz_skins_url ); ?>" class="button button-primary"><i class="dashicons dashicons-update"></i> 
			<?php
			esc_html_e( 'Go to Skins manager', 'carz' );
			?>
		</a>
		<?php
		// Dismiss notice for 7 days
		?>
		<a href="#" role="button" class="button button-secondary carz_notice_button_dismiss" data-notice="skins"><i class="dashicons dashicons-no-alt"></i> 
			<?php
			esc_html_e( 'Dismiss', 'carz' );
			?>
		</a>
		<?php
		// Hide notice forever
		?>
		<a href="#" role="button" class="button button-secondary carz_notice_button_hide" data-notice="skins"><i class="dashicons dashicons-no-alt"></i> 
			<?php
			esc_html_e( 'Never show again', 'carz' );
			?>
		</a>
	</div>
</div>
