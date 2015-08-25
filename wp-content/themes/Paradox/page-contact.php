<?php
/*
Template Name: Contact
*/
?>

<?php get_header(); ?>

<section class="primary-content">
    <div class="container">        
        <div class="row">
            <section class="main-col page-content col-sm-7 col-sm-push-5 container">                
                <div id="main" class="site-main" role="main">
                    <?php the_title('<h1>', '</h1>'); ?>

                    <div class="email">
                        <h3 class="email-title">Email</h3>
                        <p>
                            <a href="mailto:<?php echo ot_get_option( 'email' ); ?>"><?php echo ot_get_option( 'email' ); ?></a>
                        </p>
                    </div><!-- end .email -->
                    
                    <div class="numbers">
                        <h3 class="numbers-title">Numbers</h3>
                        <p>
                            P: <?php echo ot_get_option( 'phone_number' ); ?><br />
                            F: <?php echo ot_get_option( 'fax_number' ); ?>
                        </p>
                    </div><!-- end .numbers -->                         
                    
                    <div class="address">
                        <h3 class="address-title">Address</h3>
                        <p><?php echo ot_get_option( 'address' ); ?></p>
                    </div><!-- end .address -->             

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
            <section class="sidebar-col sidebar sidebar-left col-sm-5 col-sm-pull-7">
                <div class="row">
                    <div class="col-sm-10">    
                        <?php
                            if ( has_post_thumbnail() ) {
                                the_post_thumbnail('full', array('class' => 'feature-image attachment-sidebar-thumb img-rounded'));
                            } 
                        ?>

                        <p style="margin-top:0;">
                            <iframe width="100%" height="300" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=211+East+Southlake+Blvd,+Suite+108+Southlake+Texas+76092+&amp;sll=31.168934,-100.076842&amp;sspn=10.444382,19.753418&amp;ie=UTF8&amp;hq=&amp;hnear=211+E+Southlake+Blvd+%23108,+Southlake,+Tarrant,+Texas+76092&amp;t=m&amp;ll=32.940619,-97.148581&amp;spn=0.02161,0.025663&amp;z=14&amp;iwloc=A&amp;output=embed"></iframe><br /><a class="btn btn-primary btn-block" target="_blank" href="https://maps.google.com/maps?f=q&amp;source=embed&amp;hl=en&amp;geocode=&amp;q=211+East+Southlake+Blvd,+Suite+108+Southlake+Texas+76092+&amp;sll=31.168934,-100.076842&amp;sspn=10.444382,19.753418&amp;ie=UTF8&amp;hq=&amp;hnear=211+E+Southlake+Blvd+%23108,+Southlake,+Tarrant,+Texas+76092&amp;t=m&amp;ll=32.940619,-97.148581&amp;spn=0.02161,0.025663&amp;z=14&amp;iwloc=A" style="margin-top: 10px;">View Larger Map</a>                        
                        </p>
                        <div class="forms">                            
                            <h3 class="address-title">New Client Forms</h3>
                            <a class="btn btn-primary btn-sm btn-block" href="<?php echo ot_get_option( 'adult_intake_form' ); ?>" target="_blank">Adult Intake Form</a>
                            <a class="btn btn-primary btn-sm btn-block" href="<?php echo ot_get_option( 'child_intake_form' ); ?>" target="_blank">Child Intake Form</a>
                            <a class="btn btn-primary btn-sm btn-block" href="<?php echo ot_get_option( 'consent_form' ); ?>" target="_blank">Consent Form</a>                  
                            <a class="btn btn-primary btn-sm btn-block" href="<?php echo ot_get_option( 'cc_auth_form' ); ?>" target="_blank">Credit Card Authorization Form</a>                                      
                        </div><!-- end .form -->                        

                        <?php get_sidebar('default'); ?>       
                    </div>                       
                </div>
            </section>        
        </div>
    </div>
</section>

<?php get_footer(); ?> 
