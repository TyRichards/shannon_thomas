<?php
/**
 * The main template file
 * 
 * @package bootstrap-basic
 */
?>

<?php get_header(); ?>

<section class="primary-content">
    <div class="container">        
        <div class="row">         	
            <main class="col-sm-6 col-sm-offset-1 main-col page-content">                
                <div id="main" class="site-main" role="main">                	
					<?php if (have_posts()) { ?> 
					<?php 
					// start the loop
					while (have_posts()) {
						the_post();
						
						/* Include the Post-Format-specific template for the content.
						* If you want to override this in a child theme, then include a file
						* called content-___.php (where ___ is the Post Format name) and that will be used instead.
						*/
						get_template_part('content', get_post_format());
					}// end while
					
					bootstrapBasicPagination();
					?> 
					<?php } else { ?> 
					<?php get_template_part('no-results', 'index'); ?>
					<?php } // endif; ?>					
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