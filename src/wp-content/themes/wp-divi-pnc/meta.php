<?php
/*
 * Template Name: Meta
 * */

$current_page = get_page_uri();

$category_not = ',-' . get_category_by_slug('noticias')->term_id;
$query_string = 'cat=' . get_category_by_slug($current_page)->term_id . $category_not;

$posts_array = query_posts($query_string);
//print "<pre>";
//print_r($posts_array);

?>
<?php get_header(); ?>
<div id="main-content" class="page-template-default">
  <div class="conteudo">
    <div id="content-area" class="clearfix">
      <article id="post-" class="page type-page status-publish hentry">
	<h2 class="entry-title" style="display: none"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><h2>
	    <div class="et_pb_section et_pb_section_0 et_section_regular">
	      <div class="metas-por-assuntos-interna et_pb_row et_pb_row_0">
		<div class="et_pb_column et_pb_column_4_4 et_pb_column_0">
		  <div class="et_pb_blog_grid_wrapper">
		    <div class="et_pb_blog_grid clearfix et_pb_module et_pb_bg_layout_light et_pb_blog_0" data-columns="3">
		      
		      <?php foreach ($posts_array as $post) : ?>
		      <div class="column size-1of3">
			<article class="et_pb_post post type-post status-publish format-standard has-post-thumbnail hentry">
			  <?php
$thumb = '';

$width = (int)apply_filters('et_pb_index_blog_image_width', 1080);

$height = (int)apply_filters('et_pb_index_blog_image_height', 675);
$classtext = 'et_pb_post_main_image';
$titletext = $post->post_title;
$thumbnail = get_thumbnail($width, $height, $classtext, $titletext, $titletext, false, 'Blogimage');
$thumb = $thumbnail["thumb"];
$permalink = get_permalink($post->ID);
?>
			  <div class="et_pb_image_container">
			    <a href="<?php echo $permalink; ?>" class="entry-featured-image-url">
			      <img src="<?php echo $thumb; ?>" alt="<?php echo $titletext; ?>" height="250" width="400">
			    </a>
			  </div>
			  <h2 class="entry-title"><a href="<?php echo $permalink; ?>"><?php echo $titletext; ?></a></h2>
			</article>
		      </div>
		      <?php endforeach; ?>
		    </div>
		  </div>
		  <div class="et_pb_widget_area et_pb_widget_area_left clearfix et_pb_module et_pb_bg_layout_light et_pb_sidebar_0">
		    <div id="nav_menu-9" class="et_pb_widget widget_nav_menu">
		      <?php dynamic_sidebar('sidebar-1'); ?>
		    </div>
		  </div>
		</div>
	      </div>
	    </div>
      </article>
    </div>
  </div>
</div>

<?php get_footer(); ?>
