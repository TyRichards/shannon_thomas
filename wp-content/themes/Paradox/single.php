<?php
/**
 * Template for dispalying single post (read full post page).
 * 
 * @package bootstrap-basic
 */
?>

<?php get_header(); ?>

<section class="primary-content">
    <div class="container">        
        <div class="row">         	
            <main class="col-sm-6 col-sm-offset-1 main-col main-col page-content">   
				<div id="main" class="site-main" role="main">
					<?php 
					while (have_posts()) {
						the_post();

						get_template_part('content', get_post_format());

						echo "\n\n";
						
						bootstrapBasicPagination();

						echo "\n\n";
						
						// If comments are open or we have at least one comment, load up the comment template
						if (comments_open() || '0' != get_comments_number()) {
							comments_template();
						}

						echo "\n\n";

					} //endwhile;
					?> 
				</div>
			</main>
		    <aside class="col-sm-4 sidebar sidebar-box">	
				<div class="row">		        
		    		<div class="col-sm-10 col-sm-offset-2 col-md-9 col-md-offset-3">		    	        
            			<?php dynamic_sidebar('sidebar-default'); ?>                     
            		</div>
            	</div>
		    </aside>    			
		</div>
	</div>
</section>
				

<?php get_footer(); ?> 