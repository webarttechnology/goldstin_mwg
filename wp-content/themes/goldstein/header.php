<?php
/**
 * The header.
 *
 * This is the template that displays all of the <head> section and everything up until main.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

?>
<!doctype html>
<html <?php language_attributes(); ?> <?php twentytwentyone_the_html_classes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/animate.css" type="text/css">
	    <link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Roboto+Serif:opsz,wght@8..144,600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/icofont.css" type="text/css">
         <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" rel="stylesheet">
	
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/style.css" type="text/css">
	<link rel='stylesheet' id='mmenu-css' href='<?php echo get_template_directory_uri(); ?>/assets/mmenu/mmenu-light.css' media='all' />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
	<div class="mynavlayr" onclick="navopenCls()"></div>
	<div class="topheader d-none">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-8 text--start">
					<p class="d-inline-block me-4"><?php the_field('tagline', 'option'); ?></p> 
				</div>
				<div class="col-4 text-end">
				<a href="#">Disclaimer</a> | <a href="#">Blog</a>
				</div>
			</div>
		</div>
	</div>
	<section class="header">
		<!-- <div class="serchbtnmbl"><a href="#" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="icofont-search"></i></a></div> -->
		<div class="rmenubar"><a href="#menu" style="float:right" onclick="navopenCls()"><i class="fa fa-bars"></i></a></div>
		<div class="container">
			<div class="row align-items-center">
				<div class="col-12 col-md-2 col-lg-3 text-start">
					<div class="logo"><a href="<?php echo get_site_url();?>">
						<img src="<?php the_field('logo', 'option'); ?>"></a></div>
				</div>
				<div class="col-12 col-md-7 col-lg-7 text-center">
					<div class="cmenu">
						<?php //wp_nav_menu( array('menu' => 'top_menu', 'container' => '', 'walker' => new Tooltips_Walker , 'items_wrap' => '<ul>%3$s</ul>' ));
						wp_nav_menu( array('menu' => 'top_menu', 'container' => '',  'items_wrap' => '<ul>%3$s</ul>' ));
						
						?>
					</div>
				</div>
				<div class="col-lg-2 text-end d-md-block d-none">
				<div class="rightsec">
					<?php //wp_nav_menu( array('menu' => 'Header Rightside Menu', 'container' => '', 'walker' => new Tooltips_Walker , 'items_wrap' => '<ul>%3$s</ul>' )); 
					wp_nav_menu( array('menu' => 'Header Rightside Menu', 'container' => '',  'items_wrap' => '<ul>%3$s</ul>' )); 
					?>
<!-- 				  <ul>
					 <li><a href="https://webbersunited.com/cms/goldstein/disclaimer/">Disclaimer</a></li>
					  
				  </ul> -->
				 </div>	
				</div>
<!-- 				
				<!-- <div class="col-12 col-md-3 col-lg-2 text-end">
					<a href="#" class="woocmrsicon d-none d-md-inline-block me-3" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="icofont-search"></i></a>
					<a href="#" class="woocmrsicon d-none d-md-inline-block" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"><i class="icofont-navigation-menu"></i></a>
					<?php //wp_nav_menu( array('menu' => 'top_menu', 'container' => '', 'items_wrap' => '<ul class="dropdown-menu rightdrpdwn" aria-labelledby="dropdownMenuButton1">%3$s</ul>' )); ?>


				</div> -->
			</div>
		</div>
	</section>
	
	
	<!-- Modal -->
<div class="modal fade searchmodal" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<a href="#" class="clsbtn" data-bs-dismiss="modal" aria-label="Close"><i class="icofont-close-line"></i></a>
  <div class="modal-dialog modal-xl mt-5">
    <div class="modal-content rounded-0">
<!--       <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div> -->
      <div class="modal-body">
		  <h2 class="text-center text-theme mb-4">WHAT ARE YOU LOOKING FOR?</h2>
        <?php get_search_form();?>
      </div>

    </div>
  </div>
</div>









<?php if (is_front_page()) {?>
	<section class="banner">
<div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
	<div class="carousel-indicators">
		<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
		<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
		<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
	</div>
	<div class="carousel-inner">
		<?php
		$args = array (
		'post_type'              => 'banner',
		'post_status'            => 'publish',
		'order'                  => 'ASC',
		'posts_per_page'=>-1
		);
		$count=1;
		$banner = new WP_Query( $args );
		if ( $banner->have_posts() ) {
		while ( $banner->have_posts() ) {
		$banner->the_post();
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
	?>
		<div class="carousel-item <?php if($count=="1") { ?> active <?php } ?>">
			<img src="<?php echo $image[0]; ?>" class="d-block w-100" alt="...">
			<div class="carousel-caption">
				<h1 class="display-4 text-theme animated fadeInLeftBig" style="animation-delay: .8s;"><?php the_field('title'); ?> </h1>
				<!--<p class="animated fadeInRightBig" style="animation-delay: .8s;"><?php the_field('sub_title'); ?></p>-->
			</div>
		</div>
		<?php $count++; } } wp_reset_postdata(); ?>
	</div>
</div>
</section>	
<?php } else { ?>
	<div class="innrbnr">
		<div class="container">
			<div class="image-caption d-md-block">
				<h1 class="display-4 text-theme animated fadeInLeftBig" style="animation-delay: .8s;"><?php the_title(); ?></h1>
<!-- 				<p class="animated fadeInRightBig text-center text-white" style="animation-delay: .8s;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris placerat efficitur odio, et efficitur leo sollicitudin vel. Mauris, quis placerat ante sollicitudin eget.</p> -->
			</div>
		</div>
	</div>
<?php } ?>
     