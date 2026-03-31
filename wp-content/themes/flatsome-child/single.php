<?php
/**
 * The blog template file.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

get_header();


// Get Banner Settings
$banner_text = get_post_meta(get_the_ID(), '_grab_press_banner_text', true);
$banner_image = get_post_meta(get_the_ID(), '_grab_press_banner_image', true);

// Fallbacks
if(empty($banner_text)) $banner_text = 'Press Centre';
if(empty($banner_image)) $banner_image = '/wp-content/uploads/heropress1.jpg'; // Path logic might need adjustment if it's absolute, but usually relative works if root is set so

// Inline Style for background
$banner_style = "background-image: url('" . esc_url($banner_image) . "');";

?>

<div class="blogBar">
	<div class="blogGreenBx" style="<?php echo $banner_style; ?>">
		<div class="container">
			<h2><?php echo esc_html($banner_text); ?></h2>
		</div>
	</div>
	
	<div class="navBlog hidden-xs small-hidden">
		<div class="container">
			<ul class="blogmenu">
				<li class="bckBtn">
					<a href="/blog">Back</a>
				</li>
					
			</ul>
		</div>
	</div>
</div>

<main role="main" class="offset-top mainBlog pressDetails">
	<div class="container">
		<!-- Mobile "Press Centre" title was causing duplication. 
             If the intention is to show it ONLY on mobile and HIDE the green box on mobile, we would need to toggle visibility.
			 However, Grab design usually shows the Green Box on mobile too.
			 If the user feels it is "Double", let's hide the one in the white area. 
		-->
		<!-- <div class="row visible-xs hide-for-medium"><h2 class="blogH">...</h2></div> REMOVED -->
		
		<div class="row press-layout">
			<div class="col-xs-12 col-md-8 large-9 col leftBar press-content">
				
				<?php if ( have_posts() ) : ?>
					<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class('panel panel-default panel-article bigBx'); ?>>
					
					<div class="panel-body">
					<div class="press-meta">
							<div><span class="post-cat"><strong><?php echo get_the_category_list(', '); ?></strong></span></div>
							<div><span class="post-date"><?php echo get_the_date(); ?></span></div>
						</div>
					
						
						<h2 class="press-post-title"><?php the_title(); ?></h2>
						
                        <?php if ( has_excerpt() ) : ?>
						<div class="row">
                            <div class="col-xs-12 post-excerpt col">
						        <?php the_excerpt(); ?>
						    </div>
                        </div>
                        <?php endif; ?>
							
						<?php if ( has_post_thumbnail() ) : ?>
						<div class="press-featured-image-wrap">
							<?php the_post_thumbnail('large', array('class' => 'img-responsive press-hero-image')); ?>
						</div>
						<?php endif; ?>
							
	
					<div class="entry-content">
						<?php the_content(); ?>
					</div>
					
				</div>
                </article>

					<?php endwhile; ?>
				<?php endif; ?>
				
			</div>
			
			<div class="col-xs-12 col-md-4 large-3 col rightBar press-sidebar">
				<?php get_sidebar(); ?>
			</div>
			
		</div>
	</div>
</main>

<?php get_footer();
