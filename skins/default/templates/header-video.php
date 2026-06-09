<?php
/**
 * The template to display the background video in the header
 *
 * @package CARZ
 * @since CARZ 1.0.14
 */
$carz_header_video = carz_get_header_video();
$carz_embed_video  = '';
if ( ! empty( $carz_header_video ) && ! carz_is_from_uploads( $carz_header_video ) ) {
	if ( carz_is_youtube_url( $carz_header_video ) && preg_match( '/[=\/]([^=\/]*)$/', $carz_header_video, $matches ) && ! empty( $matches[1] ) ) {
		?><div id="background_video" data-youtube-code="<?php echo esc_attr( $matches[1] ); ?>"></div>
		<?php
	} else {
		?>
		<div id="background_video"><?php carz_show_layout( carz_get_embed_video( $carz_header_video ) ); ?></div>
		<?php
	}
}
