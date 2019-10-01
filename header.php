 <?php
/**
 * The header for our theme
 *
 * This is the template that displays the Header bar. It icludes:
 *	* Site Logo
 *	* Site name
 *	* Page title
 * 	* Menu with links to Home, drop down menus to Pages and Posts and About.
 *
 * Links to other Site-Pages are incorporated in the footer.php template file.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Q5
 * @since 5.0.2
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<title>Quintic <?php wp_title(); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="IT Consultant Stephen Dickinson. Driven by Technology and Information. DotNet RaspberryPi SOP" />
	<meta name="keywords" content="Cambridge DotNet RaspberryPi SOP " />
    <meta name="author" content="Steve Dickinson" />
    <meta http-equiv="content-language" content="en" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />

    <meta property="og:title" content="Quintic Ltd" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="http://www.quintic.co.uk/" />
    <meta property="og:image" content="http://www.quintic.co.uk/images/quintic_logo.gif" />
    <meta  property="og:site_name" content="Quintic" />
    <meta property="og:description" content="IT Consultant Stephen Dickinson. Driven by Technology and Information" />

	<link rel="profile" href="https://quintic.co.uk/" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' );?>" />
	<?php wp_head(); ?>
	<style>

	</style>
</head>

<body <?php body_class(); ?>>
<div>
<header class="q5-header">
<div>
    <div class="QuinticTitleBlock">
        <a href="http://mathworld.wolfram.com/KissSurface.html"><img class="q5-SiteLogo" 
			src="<?php $custom_logo_id = get_theme_mod('custom_logo');
					  $custom_logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
					  echo esc_url($custom_logo_url)?>"
                 title="<?php $custom_logo_id = get_theme_mod('custom_logo');
				 $custom_logo_attachment = get_post($custom_logo_id);
				 sprintf( __($custom_logo_attachment->post_content, ''), the_title_attribute( 'echo=0' ));
				 ?>" /></a>
        <p class="q5-SiteTitle"><?php echo get_bloginfo('name'); ?></p>
    </div>
    <div class="q5-PageTitleBlock">
	<?php if (!is_front_page()) {
		$img = get_the_post_thumbnail_url();
		if ($img != null)
		{
			echo '<img class="q5_title_thumbnail" src="' . esc_url($img) .'" />';
		}
        echo '<span class="q5-PageTitle"> ' . get_the_title() .' </span>';
	}
	?>
    </div>
	<nav class="q5-HeaderMenuBar">
		<ul id="q5-HeaderNavigation">
			<li class="q5_dropdown_entry"><a class="first" href="/">Home</a></li>
		<?php 
			$blogs = array(
				'menu_title' 		=> 'Blogs',
				'post_type' 		=> 'post',
				'filter_callback'	=> 'q5_filter_current_category'
			);
			echo q5_dropdown_posts($blogs);
			$technical = array (
				'menu_title' 		=> 'Technical',
				'post_type' 		=> 'page',
				'filter_callback' 	=> 'q5_filter_technical_category'
			);
			echo q5_dropdown_posts($technical);
			$photography = array (
				'menu_title' 		=> 'Photography',
				'post_type' 		=> 'page',
				'filter_callback' 	=> 'q5_filter_photography_category'
			);
			echo q5_dropdown_posts($photography); 
		?>
			<li class="q5_dropdown_entry"><a class="last" href="quintic/about">About</a></li>
		</ul>
	</nav>
	</div>
</header>
</div>