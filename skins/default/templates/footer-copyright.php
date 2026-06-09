<?php
/**
 * The template to display the copyright info in the footer
 *
 * @package CARZ
 * @since CARZ 1.0.10
 */

// Copyright area
?> 
<div class="footer_copyright_wrap
<?php
$carz_copyright_scheme = carz_get_theme_option( 'copyright_scheme' );
if ( ! empty( $carz_copyright_scheme ) && ! carz_is_inherit( $carz_copyright_scheme  ) ) {
	echo ' scheme_' . esc_attr( $carz_copyright_scheme );
}
?>
				">
	<div class="footer_copyright_inner">
		<div class="content_wrap">
			<div class="copyright_text">
			<?php
				$carz_copyright = carz_get_theme_option( 'copyright' );
			if ( ! empty( $carz_copyright ) ) {
				// Replace {{Y}} or {Y} with the current year
				$carz_copyright = str_replace( array( '{{Y}}', '{Y}' ), date( 'Y' ), $carz_copyright );
				// Replace {{...}} and ((...)) on the <i>...</i> and <b>...</b>
				$carz_copyright = carz_prepare_macros( $carz_copyright );
				// Display copyright
				echo wp_kses( nl2br( $carz_copyright ), 'carz_kses_content' );
			}
			?>
			</div>
		</div>
	</div>
</div>
