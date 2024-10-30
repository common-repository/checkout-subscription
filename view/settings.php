<div class="wrap vwms">
	<div class="our-cover">
		<div class="wrapper">
			<div id="particles">
				<canvas class="particles-js-canvas-el"></canvas>
			</div>
			<section class="container">
				<div class="content-container">
					<div class="content">
						<img src="<?php echo esc_url(VWCS_URL_PATH.'/images/logo.png') ?>">
						<header class="content-header">We believe We are your indispensable ONLINE PARTNER</header>
						<a class="btn our-action-btn" href="https://www.visionweb.in/contact-us/" target="_blank" title="">Contact</a>
					</div>
				</div>
			</section>
		</div>
	</div>
	<div class="container">
		<h3 class="pure-aligned-center page-title"><?php echo esc_html_e('Settings','checkout-subscription') ?></h3>
		<div class="row-set">
			<div class="card">
				<h3 class="card-title"><?php echo esc_html_e('General Settings','checkout-subscription') ?></h3>
				<?php
				if(isset($_REQUEST['updated']) && ( $_REQUEST['updated']=='error' || $_REQUEST['updated']=='updated') ) {
					echo $this->notice_action($_REQUEST['updated'],$_REQUEST['message']);
				}
				?>
           		<div class="card-body">
           			<div class="content">
           				<form class="pure-form pure-form-stacked" action="<?php echo esc_url( admin_url('admin.php') ) ?>" method="post">
           					<?php wp_nonce_field( 'save_subscription_setting', 'setting_nonce' ); ?>
           					<input type="hidden" name="action" value="subscriptionsavesettings">
           					<fieldset>
           						<div class="pure-g">
								    <div class="pure-u-1">
								    	<label><?php echo esc_html_e('Enabled *','checkout-subscription') ?></label>
								    	<select class="pure-u-1" name="subscriptionstatus" required>
								    		<option <?php echo get_option('subscriptionstatus')=='no' ? 'selected' : '' ?> value="no">No</option>
								    		<option <?php echo get_option('subscriptionstatus')=='yes' ? 'selected' : '' ?> value="yes">Yes</option>
								    	</select>
								    </div>
								</div>
	              				<div class="pure-g">
								    <div class="pure-u-1">
			          					<label><?php echo esc_html_e('API KEY *','checkout-subscription') ?></label>
			          					<input  type="text" name="sendinblue_api_key" placeholder="<?php echo esc_html_e('API KEY','checkout-subscription') ?>" value="<?php echo esc_attr( get_option('sendinblue_api_key') ) ?>" class="pure-u-1" required>
			          					<small>(<?php echo esc_html_e('Generate API Key using Sendinblue api','checkout-subscription') ?>)</small>
			          				</div>
				          		</div>
				          		<div class="pure-g">
								    <div class="pure-u-1">
								    	<label><?php echo esc_html_e('Auto Subscribe','checkout-subscription') ?></label>
								    	<select class="pure-u-1" name="at_checked">
								    		<option value="">Unchecked</option>
								    		<option <?php echo get_option('at_checked')=='checked' ? 'selected' : '' ?> value="checked">Checked by default</option>
								    	</select>
								    </div>
								</div>
								<div class="pure-g">
								    <div class="pure-u-1">
			          					<label><?php echo esc_html_e('Subscribe Label *','checkout-subscription') ?></label>
			          					<input type="text" name="subscribe_label" placeholder="<?php echo esc_html_e('Subscribe Label','checkout-subscription') ?>" value="<?php echo esc_attr(get_option('subscribe_label')) ?>" class="pure-u-1" required>
			          				</div>
				          		</div>
		                 		<br/>
		                 		<div class="pure-u-1 pure-aligned-center">
		                 			<button type="submit" class="pure-button pure-button-primary"><?php echo esc_html_e('Save Settings','checkout-subscription') ?></button>
		                 		</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>