<div class="front_page_section front_page_section_features<?php
	$carz_scheme = carz_get_theme_option( 'front_page_features_scheme' );
	if ( ! empty( $carz_scheme ) && ! carz_is_inherit( $carz_scheme ) ) {
		echo ' scheme_' . esc_attr( $carz_scheme );
	}
	echo ' front_page_section_paddings_' . esc_attr( carz_get_theme_option( 'front_page_features_paddings' ) );
	if ( carz_get_theme_option( 'front_page_features_stack' ) ) {
		echo ' sc_stack_section_on';
	}
?>"
		<?php
		$carz_css      = '';
		$carz_bg_image = carz_get_theme_option( 'front_page_features_bg_image' );
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
	$carz_anchor_icon = carz_get_theme_option( 'front_page_features_anchor_icon' );
	$carz_anchor_text = carz_get_theme_option( 'front_page_features_anchor_text' );
if ( ( ! empty( $carz_anchor_icon ) || ! empty( $carz_anchor_text ) ) && shortcode_exists( 'trx_sc_anchor' ) ) {
	echo do_shortcode(
		'[trx_sc_anchor id="front_page_section_features"'
									. ( ! empty( $carz_anchor_icon ) ? ' icon="' . esc_attr( $carz_anchor_icon ) . '"' : '' )
									. ( ! empty( $carz_anchor_text ) ? ' title="' . esc_attr( $carz_anchor_text ) . '"' : '' )
									. ']'
	);
}
?>
	<div class="front_page_section_inner front_page_section_features_inner
	<?php
	if ( carz_get_theme_option( 'front_page_features_fullheight' ) ) {
		echo ' carz-full-height sc_layouts_flex sc_layouts_columns_middle';
	}
	?>
			"
			<?php
			$carz_css      = '';
			$carz_bg_mask  = carz_get_theme_option( 'front_page_features_bg_mask' );
			$carz_bg_color_type = carz_get_theme_option( 'front_page_features_bg_color_type' );
			if ( 'custom' == $carz_bg_color_type ) {
				$carz_bg_color = carz_get_theme_option( 'front_page_features_bg_color' );
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
		<div class="front_page_section_content_wrap front_page_section_features_content_wrap content_wrap">
			<?php
			// Caption
			$carz_caption = carz_get_theme_option( 'front_page_features_caption' );
			if ( ! empty( $carz_caption ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<h2 class="front_page_section_caption front_page_section_features_caption front_page_block_<?php echo ! empty( $carz_caption ) ? 'filled' : 'empty'; ?>"><?php echo wp_kses( $carz_caption, 'carz_kses_content' ); ?></h2>
				<?php
			}

			// Description (text)
			$carz_description = carz_get_theme_option( 'front_page_features_description' );
			if ( ! empty( $carz_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<div class="front_page_section_description front_page_section_features_description front_page_block_<?php echo ! empty( $carz_description ) ? 'filled' : 'empty'; ?>"><?php echo wp_kses( wpautop( $carz_description ), 'carz_kses_content' ); ?></div>
				<?php
			}

			// Content (widgets)
			?>
			<div class="front_page_section_output front_page_section_features_output">
				<?php
				if ( is_active_sidebar( 'front_page_features_widgets' ) ) {
					dynamic_sidebar( 'front_page_features_widgets' );
				} elseif ( current_user_can( 'edit_theme_options' ) ) {
					if ( ! carz_exists_trx_addons() ) {
						carz_customizer_need_trx_addons_message();
					} else {
						carz_customizer_need_widgets_message( 'front_page_features_caption', 'ThemeREX Addons - Services' );
					}
				}
				?>
			</div>
		</div>
	</div>
</div>
