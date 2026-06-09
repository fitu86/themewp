<?php
/**
 * The template to display the Author bio
 *
 * @package CARZ
 * @since CARZ 1.0
 */
?>

<div class="author_info author vcard"<?php
	if ( carz_is_on( carz_get_theme_option( 'seo_snippets' ) ) ) {
		?> itemprop="author" itemscope="itemscope" itemtype="<?php echo esc_attr( carz_get_protocol( true ) ); ?>//schema.org/Person"<?php
	}
?>>

	<div class="author_avatar"<?php
		if ( carz_is_on( carz_get_theme_option( 'seo_snippets' ) ) ) {
			?> itemprop="image"<?php
	}
	?>>
		<a class="author_avatar_link" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
			<?php
			$carz_mult = carz_get_retina_multiplier();
			echo get_avatar( get_the_author_meta( 'user_email' ), 120 * $carz_mult );
			?>
		</a>
	</div>

	<div class="author_description">
		<h5 class="author_title"<?php
			if ( carz_is_on( carz_get_theme_option( 'seo_snippets' ) ) ) {
				?> itemprop="name"<?php
			}
		?>><a class="author_link fn" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author"><?php
			the_author();
		?></a></h5>
		<div class="author_label"><?php esc_html_e( 'About Author', 'carz' ); ?></div>
		<div class="author_bio"<?php
			if ( carz_is_on( carz_get_theme_option( 'seo_snippets' ) ) ) {
				?> itemprop="description"<?php
			}
		?>>
			<?php echo wp_kses( wpautop( get_the_author_meta( 'description' ) ), 'carz_kses_content' ); ?>
			<div class="author_links">
				<?php do_action( 'carz_action_user_meta', 'author-bio' ); ?>
			</div>
		</div>

	</div>

</div>
