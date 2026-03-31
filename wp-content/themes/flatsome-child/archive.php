<?php
/**
 * Archive Template (Grab Style)
 * Mimics home.php layout for Category Archives
 */

get_header(); 

// Banner: Use the logic from home.php or category description if preferred.
// For now, mirroring home.php logic as base, but we might want context specific titles.
$page_for_posts_id = get_option('page_for_posts');
$banner_text = get_post_meta($page_for_posts_id, '_grab_press_banner_text', true);
$banner_image = get_post_meta($page_for_posts_id, '_grab_press_banner_image', true);

if(empty($banner_text)) $banner_text = 'Press Centre';
if(empty($banner_image)) $banner_image = '/wp-content/uploads/heropress1.jpg';
$banner_style = "background-image: url('" . esc_url($banner_image) . "');";

// Get current object to determine active state
$queried_object = get_queried_object();
$current_term_id = $queried_object->term_id;
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
                <!-- "Trạm tin tức" links to main blog HOME, so checks if is_home() or is page_for_posts -->
                <li class="<?php echo (is_category('tin-tuc')) ? 'active' : ''; ?>"><a href="/category/tin-tuc/">Trạm tin tức</a></li>
                <?php
                $categories = get_categories( array(
                    'orderby' => 'name',
                    'order'   => 'ASC',
                    'exclude' => 1,
                    'hide_empty' => false
                ) );

                foreach( $categories as $category ) {
                    // Skip 'Trạm tin tức' category to avoid duplication
                    if ($category->slug === 'tin-tuc' || $category->name === 'Trạm tin tức') continue;

                    $is_active = ($current_term_id == $category->term_id) ? 'class="active"' : '';
                    echo '<li ' . $is_active . '><a href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . '</a></li>';
                }
                ?>
			</ul>
		</div>
	</div>
</div>

<main role="main" class="offset-top mainBlog pressDetails">
	<div class="container">
		
		<div class="row press-layout">
			<div class="col-xs-12 col-md-9 large-9 col leftBar press-content">
				
				<?php if ( have_posts() ) : ?>
                    <div class="row press-post-grid">
                    <?php 
                    $i = 0;
                    while ( have_posts() ) : the_post(); 
                        $i++;
                        // Same Layout Logic as home.php
                        if ($i == 1) {
                             $col_class = 'col large-12 medium-12 small-12';
                             $panel_class = 'panel panel-default panel-article bigBx';
                             $img_size = 'large';
                        } else {
                             $col_class = 'col large-6 medium-6 small-12';
                             $panel_class = 'panel panel-default panel-article';
                             $img_size = 'medium';
                        }
                    ?>
                        <div class="<?php echo $col_class; ?>">
                            <div class="col-inner">
                                <article id="post-<?php the_ID(); ?>" <?php post_class($panel_class); ?>>
                                    <?php if(has_post_thumbnail()): ?>
                                        <a href="<?php the_permalink(); ?>" class="plain">
                                            <div class="panel-hero bg-size-cover" style="background-image: url(<?php echo get_the_post_thumbnail_url(null, $img_size); ?>);"></div>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <div class="panel-body">
                                        <div class="row collapse press-meta">
                                            <div class="col small-7">
                                                <span class="post-cat"><strong><?php echo get_the_category_list(', '); ?></strong></span>
                                            </div>
                                            <div class="col small-5 text-right">
                                                <span class="post-date"><?php echo get_the_date(); ?></span>
                                            </div>
                                        </div>
                                        
                                        <h2 class="press-post-title"><a href="<?php the_permalink(); ?>" class="plain"><?php the_title(); ?></a></h2>
                                        
                                        <div class="entry-content">
                                            <?php the_excerpt(); ?>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        </div>
                        
                    <?php endwhile; ?>
                    </div><!-- .row -->

                    <div class="row grab-blog press-pagination-row">
                        <div class="col-xs-12 pagi-div">
                            <?php
                            the_posts_pagination( array(
                                'mid_size'  => 2,
                                'prev_text' => '<i class="fa fa-chevron-left"></i>',
                                'next_text' => '<i class="fa fa-chevron-right"></i>',
                                'type'      => 'list',
                                'class'     => 'pagination-md light-theme simple-pagination'
                            ) );
                            ?>
                        </div>
                    </div>

                <?php else : ?>
                    <p>No posts found.</p>
                <?php endif; ?>
				
			</div>
			
			<div class="col-xs-12 col-md-3 large-3 col rightBar press-sidebar">
				<?php get_sidebar(); ?>
			</div>
			
		</div>
	</div>
</main>

<?php get_footer(); ?>
