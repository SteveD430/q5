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
	<title><?php bloginfo('name') . ' ' . wp_title(); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php  echo '<meta name="description" content="' . get_bloginfo('description') . '"/>';  ?>
	<?php  echo '<meta name="keywords" content="' . get_bloginfo('tagline') . '"/>';  ?>
	<?php  echo '<meta name="author" content="' . get_the_author_meta('display_name') . '"/>';  ?>
	<?php  echo '<meta name="content-language" content="' . get_locale() . '"/>';  ?>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />

    <?php  echo '<meta property="og:title" content="' . get_bloginfo('name') . '"/>'; ?>
    <meta property="og:type" content="website" />
    <?php  echo '<meta property="og:url" content="' . get_bloginfo('url') . '" />'; ?>
    <?php  echo '<meta property="og:site_name" content="' . get_bloginfo('name') . '"/>'; ?>
	<?php  echo '<meta property="og:description" content="' . get_bloginfo('description') . '"/>';  ?>

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
    <div class="q5_title_block">
        <!-- a href="http://mathworld.wolfram.com/KissSurface.html" --><img class="q5-SiteLogo" 
			src="<?php $custom_logo_id = get_theme_mod('custom_logo');
					  $custom_logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
					  echo esc_url($custom_logo_url)?>"
                 title="<?php $custom_logo_id = get_theme_mod('custom_logo');
				 $custom_logo_attachment = get_post($custom_logo_id);
				 sprintf( __($custom_logo_attachment->post_content, ''), the_title_attribute( 'echo=0' ));
				 ?>" /></a>
				 <?php $words = explode(' ', get_bloginfo('name'));
				       $title = '';
						foreach ($words as $word)
						{
							$title .= '<span class="q5-SiteTitleFirstLetter">' . substr($word, 0, 1) . '</span>';
							$title .= '<span class="q5-SiteTitle">' . substr($word, 1) . '</span>';
						}; 
						echo $title;?>
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
			<li class="q5_dropdown_entry"><a class="first" href="<?php echo get_site_url(); ?>">Home</a></li>
		<?php 
		    global $q5_header_menu_category;
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