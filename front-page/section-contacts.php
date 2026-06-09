<div class="front_page_section front_page_section_contacts<?php
	$carz_scheme = carz_get_theme_option( 'front_page_contacts_scheme' );
	if ( ! empty( $carz_scheme ) && ! carz_is_inherit( $carz_scheme ) ) {
		echo ' scheme_' . esc_attr( $carz_scheme );
	}
	echo ' front_page_section_paddings_' . esc_attr( carz_get_theme_option( 'front_page_contacts_paddings' ) );
	if ( carz_get_theme_option( 'front_page_contacts_stack' ) ) {
		echo ' sc_stack_section_on';
	}
?>"
		<?php
		$carz_css      = '';
		$carz_bg_image = carz_get_theme_option( 'front_page_contacts_bg_image' );
		if ( ! empty( $carz_bg_image ) ) {
			$carz_css .= 'background-image: url(' . esc_url( carz_get_attachment_url( $carz_bg_image ) ) . ');';
		}
		if ( ! empty( $carz_css ) ) {
			echo ' style="' . esc_attr( $carz_css ) . '"';
		}
		?>
>
<?php
	// Add anchor
	$carz_anchor_icon = carz_get_theme_option( 'front_page_contacts_anchor_icon' );
	$carz_anchor_text = carz_get_theme_option( 'front_page_contacts_anchor_text' );
if ( ( ! empty( $carz_anchor_icon ) || ! empty( $carz_anchor_text ) ) && shortcode_exists( 'trx_sc_anchor' ) ) {
	echo do_shortcode(
		'[trx_sc_anchor id="front_page_section_contacts"'
									. ( ! empty( $carz_anchor_icon ) ? ' icon="' . esc_attr( $carz_anchor_icon ) . '"' : '' )
									. ( ! empty( $carz_anchor_text ) ? ' title="' . esc_attr( $carz_anchor_text ) . '"' : '' )
									. ']'
	);
}
?>
	<div class="front_page_section_inner front_page_section_contacts_inner
	<?php
	if ( carz_get_theme_option( 'front_page_contacts_fullheight' ) ) {
		echo ' carz-full-height sc_layouts_flex sc_layouts_columns_middle';
	}
	?>
			"
			<?php
			$carz_css      = '';
			$carz_bg_mask  = carz_get_theme_option( 'front_page_contacts_bg_mask' );
			$carz_bg_color_type = carz_get_theme_option( 'front_page_contacts_bg_color_type' );
			if ( 'custom' == $carz_bg_color_type ) {
				$carz_bg_color = carz_get_theme_option( 'front_page_contacts_bg_color' );
			} elseif ( 'scheme_bg_color' == $carz_bg_color_type ) {
				$carz_bg_color = carz_get_scheme_color( 'bg_color', $carz_scheme );
			} else {
				$carz_bg_color = '';
			}
			if ( ! empty( $carz_bg_color ) && $carz_bg_mask > 0 ) {
				$carz_css .= 'background-color: ' . esc_attr(
					1 == $carz_bg_mask ? $carz_bg_color : carz_hex2rgba( $carz_bg_color, $carz_bg_mask )
				) . ';';
			}
			if ( ! empty( $carz_css ) ) {
				echo ' style="' . esc_attr( $carz_css ) . '"';
			}
			?>
	>
		<div class="front_page_section_content_wrap front_page_section_contacts_content_wrap content_wrap">
			<?php

			// Title and description
			$carz_caption     = carz_get_theme_option( 'front_page_contacts_caption' );
			$carz_description = carz_get_theme_option( 'front_page_contacts_description' );
			if ( ! empty( $carz_caption ) || ! empty( $carz_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				// Caption
				if ( ! empty( $carz_caption ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
					?>
					<h2 class="front_page_section_caption front_page_section_contacts_caption front_page_block_<?php echo ! empty( $carz_caption ) ? 'filled' : 'empty'; ?>">
					<?php
						echo wp_kses( $carz_caption, 'carz_kses_content' );
					?>
					</h2>
					<?php
				}

				// Description
				if ( ! empty( $carz_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
					?>
					<div class="front_page_section_description front_page_section_contacts_description front_page_block_<?php echo ! empty( $carz_description ) ? 'filled' : 'empty'; ?>">
					<?php
						echo wp_kses( wpautop( $carz_description ), 'carz_kses_content' );
					?>
					</div>
					<?php
				}
			}

			// Content (text)
			$carz_content = carz_get_theme_option( 'front_page_contacts_content' );
			$carz_layout  = carz_get_theme_option( 'front_page_contacts_layout' );
			if ( 'columns' == $carz_layout && ( ! empty( $carz_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) ) {
				?>
				<div class="front_page_section_columns front_page_section_contacts_columns columns_wrap">
					<div class="column-1_3">
				<?php
			}

			if ( ( ! empty( $carz_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) ) {
				?>
				<div class="front_page_section_content front_page_section_contacts_content front_page_block_<?php echo ! empty( $carz_content ) ? 'filled' : 'empty'; ?>">
					<?php
					echo wp_kses( $carz_content, 'carz_kses_content' );
					?>
				</div>
				<?php
			}

			if ( 'columns' == $carz_layout && ( ! empty( $carz_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) ) {
				?>
				</div><div class="column-2_3">
				<?php
			}

			// Shortcode output
			$carz_sc = carz_get_theme_option( 'front_page_contacts_shortcode' );
			if ( ! empty( $carz_sc ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<div class="front_page_section_output front_page_section_contacts_output front_page_block_<?php echo ! empty( $carz_sc ) ? 'filled' : 'empty'; ?>">
					<?php
					carz_show_layout( do_shortcode( $carz_sc ) );
					?>
				</div>
				<?php
			}

			if ( 'columns' == $carz_layout && ( ! empty( $carz_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) ) {
				?>
				</div></div>
				<?php
			}
			?>

		</div>
	</div>
</div>
