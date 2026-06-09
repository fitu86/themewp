<?php
/**
 * The template to display the socials in the footer
 *
 * @package CARZ
 * @since CARZ 1.0.10
 */


// Socials
if ( carz_is_on( carz_get_theme_option( 'socials_in_footer' ) ) ) {
	$carz_output = carz_get_socials_links();
	if ( '' != $carz_output ) {
		?>
		<div class="footer_socials_wrap socials_wrap">
			<div class="footer_socials_inner">
				<?php carz_show_layout( $carz_output ); ?>
			</div>
		</div>
		<?php
	}
}
