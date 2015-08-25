<?php
/*
Template Name: Staff
*/
?>

<?php get_header(); ?>

<section class="primary-content">
    <div class="container">        
        <div class="row">
            <section class="main-col page-content col-sm-10 col-sm-offset-1 container">                
                <div id="main" class="site-main" role="main">
                    <h1 class="text-center"><?php the_title(); ?></h1>
                    <hr/>
                    
                    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>                    
                        
                        <?php                        
                            if ( has_post_thumbnail() ) {
                                the_post_thumbnail('full', array('class' => 'core-image img-responsive'));
                            }                            
                        ?>
                        
                        <?php                        
                            $args = array( 'post_type' => 'staff' );                            
                            $my_secondary_loop = new WP_Query( $args );                            
                            if( $my_secondary_loop->have_posts() ):
                                while( $my_secondary_loop->have_posts() ): $my_secondary_loop->the_post()
                        ?>
                            
                        <div <?php post_class('staff-wrapper'); ?>>
                            <div class="row">
                                <div class="col-sm-5">
                                    <?php 
                                        if ( has_post_thumbnail() ) {
                                            the_post_thumbnail('staff-image', array('class' => 'staff-image img-responsive'));
                                        }
                                    ?>                  
                                </div>
                                <div class="staff-content col-sm-7">
                                    <div class="staff-content">
                                        <h2 class="staff-title"><?php the_title(); ?></h2>
                                        <div class="staff-bio">
                                            <?php the_content(); ?>
                                        </div><!-- /.staff-bio -->
                                    </div>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div><!-- end .staff-wrapper -->
                            
                    <?php endwhile; endif;
                            wp_reset_postdata(); ?>

                        <div class="clear"></div>

                    <?php endwhile; endif; ?>

                </div>           
            </section>            
        </div>
    </div>
</section>

<?php get_footer(); ?> 
