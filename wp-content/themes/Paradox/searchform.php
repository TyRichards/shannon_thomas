<?php
/**
 * Template for displaying search form in bootstrap-basic theme
 * 
 * @package bootstrap-basic
 */
?>
<form role="search" method="get" class="search-form form" action="<?php echo esc_url(home_url('/')); ?>">
	<label for="form-search-input" class="sr-only"><?php _ex('Search for', 'label', 'bootstrap-basic'); ?></label>
	<div class="input-group">
		<input type="search" id="form-search-input" class="form-control" placeholder="<?php echo esc_attr_x('Search &hellip;', 'placeholder', 'bootstrap-basic'); ?>" value="<?php echo esc_attr(get_search_query()); ?>" name="s" title="<?php echo esc_attr_x('Search for:', 'label', 'bootstrap-basic'); ?>">
		<span class="input-group-btn">
			<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
		</span>
	</div>
</form>