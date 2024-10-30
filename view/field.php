<p class="form-row">
	<label>
		<input type="checkbox" value="1" <?php echo esc_html( get_option('at_checked') ) ?> name="issubscribe">
		<?php echo get_option('subscribe_label')!='' ? esc_html( get_option('subscribe_label') ) : esc_html_e('subscribe','checkout-subscription') ?>
	</label>
</p>