<?php
/*
Template Name: Products
*/
?>

<?php get_header(); ?>

<section class="primary-content">
    <div class="container">        
        <div class="row">
            <section class="main-col page-content col-sm-8 col-sm-offset-2 container">                
                <div id="main" class="site-main" role="main">


                    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    
                        <div class="service-header">
                            <h1 class="text-center"><?php the_title(); ?></h1>                            
                            
                            <ul class="tab-wrapper list-inline" style="display:none;">                            
                            
                            <?php
                                
                                $args = array( 'post_type' => 'products' );
                            
                                $my_secondary_loop = new WP_Query( $args );
                                
                                $count = $my_secondary_loop->post_count;
                                    $i = 1;
                                
                                if( $my_secondary_loop->have_posts() ):
                                    while( $my_secondary_loop->have_posts() ): $my_secondary_loop->the_post();
                                    
                            ?>
                                    <li <?php if ($i == $count) echo 'class="last"'; ?>><a href="#<?php echo $post->post_name ?>"><?php the_title(); ?></a></li>
                            <?php
                                $i++;
                                endwhile;
                                endif;
                                wp_reset_postdata();
                            ?>
                            
                            </ul>
                            <div class="clear"></div>
                        </header> <!-- end header -->

                        <hr/>                           
                        
                        <div class="clear"></div>
                        
                        <?php
                            if ( has_post_thumbnail() ) {
                                the_post_thumbnail('full', array('class' => 'core-image'));
                            }
                        ?>
                        
                        <?php
                            $args = array( 'post_type' => 'products' );
                            query_posts($args);
                            if (have_posts()) : 
                        ?>
                            
                    <?php while (have_posts()) : the_post(); ?>
                            
                        <div id="<?php echo $post->post_name ?>" <?php post_class('service-wrapper'); ?>>
                            <div class="row">
                                <div class="col-sm-4">
                                    <?php                                
                                        if ( has_post_thumbnail() ) {
                                            the_post_thumbnail('service-image', array('class' => 'service-image img-responsive'));
                                            $style = "";
                                        } else {
                                              $style = "style='width: 100%;'";
                                        } 
                                    ?>
                                </div>
                                <div class="service-content col-sm-7 col-sm-offset-1">
                                    <h3 class="service-title" <?php echo $style; ?>><?php the_title(); ?></h3>
                                    <div class="service-description" <?php echo $style; ?>>
                                        <?php the_content(); ?>
                                    </div><!-- /.service-description -->
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                                    
                    <?php endwhile;  ?>
                    <?php endif; wp_reset_query(); ?>
                                                          
<!--                             <div class="well">
                                <a class="btn btn-primary btn-cta" href="/contact">Contact Us</a>
                                <span class="copy-cta lead">
                                     Contact Southlake Christian Counseling today for more information.
                                </span>
                            </div> -->
                
                    <?php endwhile; endif; ?>

                </div>           
            </section>            
        </div>
    </div>
</section>

<?php get_footer(); ?> 
