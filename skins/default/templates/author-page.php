<?php
/**
 * The template to display the user's avatar, bio and socials on the Author page
 *
 * @package CARZ
 * @since CARZ 1.71.0
 */
?>

<div class="author_page author vcard"<?php
	if ( carz_is_on( carz_get_theme_option( 'seo_snippets' ) ) ) {
		?> itemprop="author" itemscope="itemscope" itemtype="<?php echo esc_attr( carz_get_protocol( true ) ); ?>//schema.org/Person"<?php
	}
?>>

	<div class="author_avatar"<?php
		if ( carz_is_on( carz_get_theme_option( 'seo_snippets' ) ) ) {
			?> itemprop="image"<?php
		}
	?>>
		<?php
		$carz_mult = carz_get_retina_multiplier();
		echo get_avatar( get_the_author_meta( 'user_email' ), 120 * $carz_mult );
		?>
	</div>

	<h4 class="author_title"<?php
		if ( carz_is_on( carz_get_theme_option( 'seo_snippets' ) ) ) {
			?> itemprop="name"<?php
		}
	?>><span class="fn"><?php the_author(); ?></span></h4>

	<?php
	$carz_author_description = get_the_author_meta( 'description' );
	if ( ! empty( $carz_author_description ) ) {
		?>
		<div class="author_bio"<?php
			if ( carz_is_on( carz_get_theme_option( 'seo_snippets' ) ) ) {
				?> itemprop="description"<?php
			}
		?>><?php echo wp_kses( wpautop( $carz_author_description ), 'carz_kses_content' ); ?></div>
		<?php
	}
	?>

	<div class="author_details">
		<span class="author_posts_total">
			<?php
			$carz_posts_total = count_user_posts( get_the_author_meta('ID'), 'post' );
			if ( $carz_posts_total > 0 ) {
				// Translators: Add the author's posts number to the message
				echo wp_kses( sprintf( _n( '%s article published', '%s articles published', $carz_posts_total, 'carz' ),
										'<span class="author_posts_total_value">' . number_format_i18n( $carz_posts_total ) . '</span>'
								 		),
							'carz_kses_content'
							);
			} else {
				esc_html_e( 'No posts published.', 'carz' );
			}
			?>
		</span><?php
			ob_start();
			do_action( 'carz_action_user_meta', 'author-page' );
			$carz_socials = ob_get_contents();
			ob_end_clean();
			carz_show_layout( $carz_socials,
				'<span class="author_socials"><span class="author_socials_caption">' . esc_html__( 'Follow:', 'carz' ) . '</span>',
				'</span>'
			);
		?>
	</div>

</div>
