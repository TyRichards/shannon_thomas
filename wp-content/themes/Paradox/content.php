<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>

		<?php if ('post' == get_post_type()) { ?> 
		<div class="entry-meta">
			<div class="post-meta"><?php bootstrapBasicPostOn(); ?></div>
			<a href="<?php the_permalink() ?>#respond" class="discussion btn btn-primary btn-sm">Comment &raquo;</a>
		</div><!-- .entry-meta -->
		<?php } //endif; ?> 

		<div class="post-img">
			<a href="<?php the_permalink() ?>">
				<?php
				if ( has_post_thumbnail() ) {
			    	the_post_thumbnail('full', array('class' => 'core-image img-responsive text-center'));
				} else {
			    	echo "<img src='" . get_template_directory_uri() . "/assets/images/core_values.jpg' class='core-image img-responsive' />";
				} ?>	
			</a>
		</div>		
	</header><!-- .entry-header -->
	
	<?php if (is_search()) { // Only display Excerpts for Search ?> 
	<div class="entry-summary">
		<?php the_excerpt(); ?> 
		<div class="clearfix"></div>
	</div><!-- .entry-summary -->
	<?php } else { ?> 
	<div class="entry-content">
		<?php the_content(bootstrapBasicMoreLinkText()); ?> 
		<div class="clearfix"></div>
		<?php 
		/**
		 * This wp_link_pages option adapt to use bootstrap pagination style.
		 * The other part of this pager is in inc/template-tags.php function name bootstrapBasicLinkPagesLink() which is called by wp_link_pages_link filter.
		 */
		wp_link_pages(array(
			'before' => '<div class="page-links">' . __('Pages:', 'bootstrap-basic') . ' <ul class="pagination">',
			'after'  => '</ul></div>',
			'separator' => ''
		));
		?> 
	</div><!-- .entry-content -->
	<?php } //endif; ?> 

	<!-- <div class="well well-lg text-center">
		<?php // gravity_form(5, true, true, false, null, true, 50); ?>
	</div>		 -->

	<footer class="entry-meta">
		<?php if ('post' == get_post_type()) { // Hide category and tag text for pages on Search ?> 
		<div class="entry-meta-category-tag">
			<?php
				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list(__(', ', 'bootstrap-basic'));
				if (!empty($categories_list)) {
			?> 
			<span class="cat-links">
				<?php echo bootstrapBasicCategoriesList($categories_list); ?> 
			</span>
			<?php } // End if categories ?> 

			<?php
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list('', __(', ', 'bootstrap-basic'));
				if ($tags_list) {
			?> 
			<span class="tags-links">
				<?php echo bootstrapBasicTagsList($tags_list); ?> 
			</span>
			<?php } // End if $tags_list ?> 
		</div><!--.entry-meta-category-tag-->
		<?php } // End if 'post' == get_post_type() ?> 

		<div class="entry-meta-comment-tools">
			<?php if (! post_password_required() && (comments_open() || '0' != get_comments_number())) { ?> 
			<span class="comments-link"><?php bootstrapBasicCommentsPopupLink(); ?></span>
			<?php } //endif; ?> 			
		</div><!--.entry-meta-comment-tools-->

		<div class="share-icons">				    
	    	<?php
				if ( function_exists('sharethis_button') ) {
					sharethis_button();
				}
			?>
			
			<script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery('span.st_pinterest_vcount').attr('st_image', '<?php echo wp_get_attachment_thumb_url( get_post_thumbnail_id( $post->ID ) ); ?>');
				});
			</script>
			
			<a id="bookmark" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/bookmark-icon.png" /></a>
	    </div><!-- end .share-icons -->		
	</footer><!-- .entry-meta -->
</article><!-- #post-## -->