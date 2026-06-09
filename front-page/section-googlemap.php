<div class="front_page_section front_page_section_googlemap<?php
	$carz_scheme = carz_get_theme_option( 'front_page_googlemap_scheme' );
	if ( ! empty( $carz_scheme ) && ! carz_is_inherit( $carz_scheme ) ) {
		echo ' scheme_' . esc_attr( $carz_scheme );
	}
	echo ' front_page_section_paddings_' . esc_attr( carz_get_theme_option( 'front_page_googlemap_paddings' ) );
	if ( carz_get_theme_option( 'front_page_googlemap_stack' ) ) {
		echo ' sc_stack_section_on';
	}
?>"
		<?php
		$carz_css      = '';
		$carz_bg_image = carz_get_theme_option( 'front_page_googlemap_bg_image' );
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
	$carz_anchor_icon = carz_get_theme_option( 'front_page_googlemap_anchor_icon' );
	$carz_anchor_text = carz_get_theme_option( 'front_page_googlemap_anchor_text' );
if ( ( ! empty( $carz_anchor_icon ) || ! empty( $carz_anchor_text ) ) && shortcode_exists( 'trx_sc_anchor' ) ) {
	echo do_shortcode(
		'[trx_sc_anchor id="front_page_section_googlemap"'
									. ( ! empty( $carz_anchor_icon ) ? ' icon="' . esc_attr( $carz_anchor_icon ) . '"' : '' )
									. ( ! empty( $carz_anchor_text ) ? ' title="' . esc_attr( $carz_anchor_text ) . '"' : '' )
									. ']'
	);
}
?>
	<div class="front_page_section_inner front_page_section_googlemap_inner
		<?php
		$carz_layout = carz_get_theme_option( 'front_page_googlemap_layout' );
		echo ' front_page_section_layout_' . esc_attr( $carz_layout );
		if ( carz_get_theme_option( 'front_page_googlemap_fullheight' ) ) {
			echo ' carz-full-height sc_layouts_flex sc_layouts_columns_middle';
		}
		?>
		"
			<?php
			$carz_css      = '';
			$carz_bg_mask  = carz_get_theme_option( 'front_page_googlemap_bg_mask' );
			$carz_bg_color_type = carz_get_theme_option( 'front_page_googlemap_bg_color_type' );
			if ( 'custom' == $carz_bg_color_type ) {
				$carz_bg_color = carz_get_theme_option( 'front_page_googlemap_bg_color' );
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
		<div class="front_page_section_content_wrap front_page_section_googlemap_content_wrap
		<?php
		if ( 'fullwidth' != $carz_layout ) {
			echo ' content_wrap';
		}
		?>
		">
			<?php
			// Content wrap with title and description
			$carz_caption     = carz_get_theme_option( 'front_page_googlemap_caption' );
			$carz_description = carz_get_theme_option( 'front_page_googlemap_description' );
			if ( ! empty( $carz_caption ) || ! empty( $carz_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				if ( 'fullwidth' == $carz_layout ) {
					?>
					<div class="content_wrap">
					<?php
				}
					// Caption
				if ( ! empty( $carz_caption ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
					?>
					<h2 class="front_page_section_caption front_page_section_googlemap_caption front_page_block_<?php echo ! empty( $carz_caption ) ? 'filled' : 'empty'; ?>">
					<?php
					echo wp_kses( $carz_caption, 'carz_kses_content' );
					?>
					</h2>
					<?php
				}

					// Description (text)
				if ( ! empty( $carz_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
					?>
					<div class="front_page_section_description front_page_section_googlemap_description front_page_block_<?php echo ! empty( $carz_description ) ? 'filled' : 'empty'; ?>">
					<?php
					echo wp_kses( wpautop( $carz_description ), 'carz_kses_content' );
					?>
					</div>
					<?php
				}
				if ( 'fullwidth' == $carz_layout ) {
					?>
					</div>
					<?php
				}
			}

			// Content (text)
			$carz_content = carz_get_theme_option( 'front_page_googlemap_content' );
			if ( ! empty( $carz_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				if ( 'columns' == $carz_layout ) {
					?>
					<div class="front_page_section_columns front_page_section_googlemap_columns columns_wrap">
						<div class="column-1_3">
					<?php
				} elseif ( 'fullwidth' == $carz_layout ) {
					?>
					<div class="content_wrap">
					<?php
				}

				?>
				<div class="front_page_section_content front_page_section_googlemap_content front_page_block_<?php echo ! empty( $carz_content ) ? 'filled' : 'empty'; ?>">
				<?php
					echo wp_kses( $carz_content, 'carz_kses_content' );
				?>
				</div>
				<?php

				if ( 'columns' == $carz_layout ) {
					?>
					</div><div class="column-2_3">
					<?php
				} elseif ( 'fullwidth' == $carz_layout ) {
					?>
					</div>
					<?php
				}
			}

			// Widgets output
			?>
			<div class="front_page_section_output front_page_section_googlemap_output">
				<?php
				if ( is_active_sidebar( 'front_page_googlemap_widgets' ) ) {
					dynamic_sidebar( 'front_page_googlemap_widgets' );
				} elseif ( current_user_can( 'edit_theme_options' ) ) {
					if ( ! carz_exists_trx_addons() ) {
						carz_customizer_need_trx_addons_message();
					} else {
						carz_customizer_need_widgets_message( 'front_page_googlemap_caption', 'ThemeREX Addons - Google map' );
					}
				}
				?>
			</div>
			<?php

			if ( 'columns' == $carz_layout && ( ! empty( $carz_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) ) {
				?>
				</div></div>
				<?php
			}
			?>
		</div>
	</div>
</div>
