<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: //codex.wordpress.org/Template_Hierarchy
 *
 * @package CARZ
 * @since CARZ 1.0
 */

$carz_template = apply_filters( 'carz_filter_get_template_part', carz_blog_archive_get_template() );

if ( ! empty( $carz_template ) && 'index' != $carz_template ) {

	get_template_part( $carz_template );

} else {

	carz_storage_set( 'blog_archive', true );

	get_header();

	if ( have_posts() ) {

		// Query params
		$carz_stickies   = is_home()
								|| ( in_array( carz_get_theme_option( 'post_type' ), array( '', 'post' ) )
									&& (int) carz_get_theme_option( 'parent_cat' ) == 0
									)
										? get_option( 'sticky_posts' )
										: false;
		$carz_post_type  = carz_get_theme_option( 'post_type' );
		$carz_args       = array(
								'blog_style'     => carz_get_theme_option( 'blog_style' ),
								'post_type'      => $carz_post_type,
								'taxonomy'       => carz_get_post_type_taxonomy( $carz_post_type ),
								'parent_cat'     => carz_get_theme_option( 'parent_cat' ),
								'posts_per_page' => carz_get_theme_option( 'posts_per_page' ),
								'sticky'         => carz_get_theme_option( 'sticky_style', 'inherit' ) == 'columns'
															&& is_array( $carz_stickies )
															&& count( $carz_stickies ) > 0
															&& get_query_var( 'paged' ) < 1
								);

		carz_blog_archive_start();

		do_action( 'carz_action_blog_archive_start' );

		if ( is_author() ) {
			do_action( 'carz_action_before_page_author' );
			get_template_part( apply_filters( 'carz_filter_get_template_part', 'templates/author-page' ) );
			do_action( 'carz_action_after_page_author' );
		}

		if ( carz_get_theme_option( 'show_filters', 0 ) ) {
			do_action( 'carz_action_before_page_filters' );
			carz_show_filters( $carz_args );
			do_action( 'carz_action_after_page_filters' );
		} else {
			do_action( 'carz_action_before_page_posts' );
			carz_show_posts( array_merge( $carz_args, array( 'cat' => $carz_args['parent_cat'] ) ) );
			do_action( 'carz_action_after_page_posts' );
		}

		do_action( 'carz_action_blog_archive_end' );

		carz_blog_archive_end();

	} else {

		if ( is_search() ) {
			get_template_part( apply_filters( 'carz_filter_get_template_part', 'templates/content', 'none-search' ), 'none-search' );
		} else {
			get_template_part( apply_filters( 'carz_filter_get_template_part', 'templates/content', 'none-archive' ), 'none-archive' );
		}
	}

	get_footer();
}
