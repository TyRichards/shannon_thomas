<?php
/*
Template Name: Full Width
*/
?>

<?php get_header(); ?>

<section class="primary-content">
    <div class="container col-no-padding-xs">        
        <section class="container main-col col-sm-12 page-content">                
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
                <a class="btn btn-danger" href="#">
                    <i class="fa fa-phone fa-1x"></i>
                    Call (972) 492-7920
                </a>
                    &nbsp;&nbsp;
                <a class="btn btn-danger" href="/contact-us">
                    <i class="fa fa-envelope fa-1x"></i>
                    Email Us
                </a> 
            </div>           
        </section>               
    </div>
</section>

<?php get_footer(); ?> 
