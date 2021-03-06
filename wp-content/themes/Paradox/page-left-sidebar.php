<?php
/*
Template Name: Left Sidebar
*/
?>

<?php get_header(); ?>

<section class="primary-content">
    <div class="container">        
        <div class="row">
            <section class="sidebar-col sidebar sidebar-left col-md-4 col-md-offset-1">
                <div class="row">
                    <div class="col-sm-10">    

                        <?php
                            if ( has_post_thumbnail() ) {
                                the_post_thumbnail('full', array('class' => 'feature-image attachment-sidebar-thumb img-rounded'));
                            } else {
                                echo "<img src='" . get_template_directory_uri() . "/assets/images/sidebar-default.jpg' class='feature-image attachment-sidebar-thumb img-rounded' />";
                            }
                        ?>

                        <?php get_sidebar('default'); ?>       
                    </div>                  
                </div>     
            </section>              
            <section class="main-col page-content col-md-6 container">                
                <div id="main" class="site-main" role="main">                    
                    <?php 
                    while (have_posts()) {
                      the_post();

                      get_template_part('content', 'page');

                      echo "\n\n";
                      
                      // If comments are open or we have at least one comment, load up the comment template
                      if (comments_open() || '0' != get_comments_number()) {
                        comments_template();
                      }

                      echo "\n\n";

                    } //endwhile;
                    ?> 
                </div>           

            </section>      
        </div>
    </div>
</section>

<?php get_footer(); ?> 
