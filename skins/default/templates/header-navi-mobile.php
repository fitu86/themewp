<?php
/**
 * The template to show mobile menu (used only header_style == 'default')
 *
 * @package CARZ
 * @since CARZ 1.0
 */

$carz_show_widgets = carz_get_theme_option( 'widgets_menu_mobile_fullscreen' );
$carz_show_socials = carz_get_theme_option( 'menu_mobile_socials' );

?>
<div class="menu_mobile_overlay scheme_dark"></div>
<div class="menu_mobile menu_mobile_<?php echo esc_attr( carz_get_theme_option( 'menu_mobile_fullscreen' ) > 0 ? 'fullscreen' : 'narrow' ); ?> scheme_dark">
	<div class="menu_mobile_inner<?php echo esc_attr( $carz_show_widgets == 1  ? ' with_widgets' : '' ); ?>">
        <div class="menu_mobile_header_wrap">
            <?php
            // Logo
            set_query_var( 'carz_logo_args', array( 'type' => 'mobile' ) );
            get_template_part( apply_filters( 'carz_filter_get_template_part', 'templates/header-logo' ) );
            set_query_var( 'carz_logo_args', array() ); ?>

            <span class="menu_mobile_close menu_button_close" tabindex="0"><span class="menu_button_close_text"><?php esc_html_e('Close', 'carz')?></span><span class="menu_button_close_icon"></span></span>
        </div>
        <div class="menu_mobile_content_wrap content_wrap">
            <div class="menu_mobile_content_wrap_inner<?php echo esc_attr($carz_show_socials ? '' : ' without_socials'); ?>"><?php
            // Mobile menu
            $carz_menu_mobile = carz_get_nav_menu( 'menu_mobile' );
            if ( empty( $carz_menu_mobile ) ) {
                $carz_menu_mobile = apply_filters( 'carz_filter_get_mobile_menu', '' );
                if ( empty( $carz_menu_mobile ) ) {
                    $carz_menu_mobile = carz_get_nav_menu( 'menu_main' );
                    if ( empty( $carz_menu_mobile ) ) {
                        $carz_menu_mobile = carz_get_nav_menu();
                    }
                }
            }
            if ( ! empty( $carz_menu_mobile ) ) {
                // Change attribute 'id' - add prefix 'mobile-' to prevent duplicate id on the page
                $carz_menu_mobile = preg_replace( '/([\s]*id=")/', '${1}mobile-', $carz_menu_mobile );
                // Change main menu classes
                $carz_menu_mobile = str_replace(
                array( 'menu_main',   'sc_layouts_menu_nav', 'sc_layouts_menu ' ), // , 'sc_layouts_hide_on_mobile', 'hide_on_mobile'
                array( 'menu_mobile', '', ' ' ), // , '', ''
                    $carz_menu_mobile
                );
                // Wrap menu to the <nav> if not present
                if ( strpos( $carz_menu_mobile, '<nav ' ) !== 0 ) {	// condition !== false is not allowed, because menu can contain inner <nav> elements (in the submenu layouts)
				$carz_menu_mobile = carz_is_on( carz_get_theme_option( 'seo_snippets' ) )
					? sprintf( '<nav class="menu_mobile_nav_area" itemscope="itemscope" itemtype="%1$s//schema.org/SiteNavigationElement">%2$s</nav>', esc_attr( carz_get_protocol( true ) ), $carz_menu_mobile )
					: sprintf( '<nav class="menu_mobile_nav_area">%s</nav>', $carz_menu_mobile );
                }
                // Show menu
                carz_show_layout( apply_filters( 'carz_filter_menu_mobile_layout', $carz_menu_mobile ) );
            }
            // Social icons
            if($carz_show_socials) {
                carz_show_layout( carz_get_socials_links(), '<div class="socials_mobile">', '</div>' );
            }            
            ?>
            </div>
		</div><?php

        if ( $carz_show_widgets == 1 )  {
            ?><div class="menu_mobile_widgets_area"><?php
            // Create Widgets Area
            carz_create_widgets_area( 'widgets_additional_menu_mobile_fullscreen' );
            ?></div><?php
        } ?>

    </div>
</div>
