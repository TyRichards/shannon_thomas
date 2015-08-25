<?php
/**
 * The theme footer
 * 
 * @package bootstrap-basic
 */
?>
		</div><!--.body-content-->						
		
		<footer class="footer">    		
	      	<section class="small-footer">
	      		<div class="container">
	      			<div class="row">
	      				<div class="col-md-12 visible-xs visible-sm">
							<div class="social-links">
								<a class="facebook" href="http://www.facebook.com/pages/Southlake-Christian-Counseling/93991273837">
									<i class="fa fa-facebook fa-2x"></i>
								</a>
								<a class="twitter" href="https://twitter.com/SouthlakeLCSW">
									<i class="fa fa-twitter fa-2x"></i>
								</a>
							</div>
	      				</div>
	      				<div class="col-md-10">
	      					<div class="footer-credits">		        				
	        					<a style="font-size:13px" href="<?php echo esc_url(home_url('/')); ?>">
	        						© <?php echo date("Y"); ?> <?php echo esc_attr(get_bloginfo('name', 'display')); ?>
	        					</a>								
	      					</div>
			        		<div class="footer-menu">				        	
			        			<?php dynamic_sidebar('footer-credits'); ?>
			        		</div>
		        		</div>
		        		<div class="col-md-2">
			        		<div class="paradox">
			        			<a href="http://paradoxcreative.com" target="_blank">
			        				<p style="font-size:13px">Site crafted by Paradox</p>
			        			</a>
			        		</div>
			        	</div>
			        </div> <!-- .row -->
		        </div>	
	        </section>	
      	</footer>

		<!--wordpress footer-->
		<?php wp_footer(); ?> 
		<!-- Typekit -->
		<script src="//use.typekit.net/you2ofv.js"></script>
		<script>try{Typekit.load();}catch(e){}</script>			
	</body>
</html>