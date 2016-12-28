<h1><?php use Wordpress\Woocommerce\Add_Product_Wizard\Helpers;

	echo __( 'Add Product', WWAPW_DOMAIN ); ?></h1>
<div class="wizard-error-area"></div>
<div class="wizard-container">
	<form id="add-product-wizard-form">
		<?php wp_nonce_field( 'WWAPW_wizard_nonce', 'WWAPW_wizard_nonce_field' ) ?>

		<!-- hidden input for handling action ajax.	-->
		<input name="action" value="wwapw_add_product_wizard" hidden>
		<div class="postbox-container">
			<h1><?php echo __( 'Basic Information', WWAPW_DOMAIN ); ?></h1>
			<section>
				<p class="form-field">
					<label for="product-name"><?php echo __( 'Product Name * ', WWAPW_DOMAIN ); ?></label>
					<input id="product-name" type="text" name="product_name" class="wwapw-required">
				</p>
				<br>
				<label for="product_description"><?php echo __( 'Product Description ', WWAPW_DOMAIN ); ?></label>
				<?php wp_editor( '', 'product_description', [
					'media_buttons' => false,
					'textarea_name' => 'product_description',
				] ); ?>
				<br>
				<p class="form-field">
					<label
						for="product-gallery-open-main"><?php echo __( 'Select Main Image *', WWAPW_DOMAIN ); ?></label>
					<input type="button" class="button" name="image_gallery_main"
					       id="product-gallery-open-main" value="<?php echo __( 'Main Image', WWAPW_DOMAIN ); ?>">
				</p>
				<div class="image-gallery-main"></div>
				<p><?php echo __( '(*) Required', WWAPW_DOMAIN ); ?></p>
			</section>

			<h1><?php echo __( 'Pricing', WWAPW_DOMAIN ); ?></h1>
			<section>
				<p class="form-field">
					<label
						for="product-regular-price"><?php echo __( 'Regular Price * (Minimum 0.01)', WWAPW_DOMAIN ); ?></label>
					<input id="product-regular-price" type="number" name="product_regular_price" min="0.01"
					       class="wwapw-required wwapw-input-small code">
				</p>
				<br>
				<p class="form-field">
					<label
						for="product-sale-price"><?php echo __( 'Sale Price (Minimum 0.01)', WWAPW_DOMAIN ); ?></label>
					<input class="wwapw-input-small code" id="product-sale-price" type="number"
					       name="product_sale_price"
					       min="0.01">
				</p>
				<br>
				<a href="#" id="wwapw-view-hidden-link">Schedule</a>
				<br>
				<p class="form-field wwapw-hidden">
					<label for="product-sale-price-sdate"
					       class="wwapw-hidden"><?php echo __( 'Sale Price Dates: ', WWAPW_DOMAIN ); ?></label>
					<input class="wwapw-hidden wwapw-input-small" id="product-sale-price-sdate" type="text"
					       name="product_sale_price_sdate"
					       placeholder="Start date YYYY-MM-DD">
					<br>
					<input class="wwapw-hidden wwapw-input-small" id="product-sale-price-edate" type="text"
					       name="product_sale_price_edate"
					       placeholder="End date YYYY-MM-DD">
				</p>
				<p class="form-field">
					<label
						for="product-min-quantity"><?php echo __( 'Minimum Quantity (Minimum 1)', WWAPW_DOMAIN ); ?></label>
					<input class="wwapw-input-small code" id="product-min-quantity" type="number"
					       name="product_min_quantity"
					       min="1">
				</p>
				<br>
				<p class="form-field">
					<label
						for="product-max-quantity"><?php echo __( 'Maximum Quantity', WWAPW_DOMAIN ); ?></label>
					<input class="wwapw-input-small code" id="product-max-quantity" type="number"
					       name="product_max_quantity"
					       min="1">
				</p>
				<p><?php echo __( '(*) Required', WWAPW_DOMAIN ); ?></p>
			</section>

			<h1><?php echo __( 'Inventory', WWAPW_DOMAIN ); ?></h1>
			<section>
				<p class="form-field">
					<label for="product-sku"><?php echo __( 'SKU * ', WWAPW_DOMAIN ); ?></label>
					<input id="product-sku" type="text" name="product_sku" class="wwapw-required">
				</p>
				<p class="form-field">
					<label for="product-stock-status"><?php echo __( 'Stock Status ', WWAPW_DOMAIN ); ?></label>
					<br>
					<select name="product_stock_status" id="product-stock-status">
						<!-- 1 => in stock , 0 => out of stock -->
						<option value="instock" selected><?php echo __( 'In Stock', WWAPW_DOMAIN ); ?></option>
						<option value="outofstock"><?php echo __( 'Out Of Stock', WWAPW_DOMAIN ); ?></option>
					</select>
				</p>
				<strong>
					<?php echo __( 'Manage stock', WWAPW_DOMAIN ); ?>
				</strong>
				<p class="form-field">
					<input type="checkbox" name="product_stock_management" id="product-stock-management">
					<label for="product-stock-management">
						<?php echo __( 'Enable stock management at product level', WWAPW_DOMAIN ); ?><br>
					</label>
				</p>
				<p class="form-field wwapw-hidden-inventory">
					<label for="product-stock-quantity"
					       class="wwapw-hidden-inventory"><?php echo __( 'Stock quantity ', WWAPW_DOMAIN ); ?></label>
					<input class="wwapw-hidden-inventory code wwapw-input-small" id="product-stock-quantity"
					       type="number"
					       name="product_stock_quantity" min="0">
				</p>
				<p class="form-field wwapw-hidden-inventory">
					<label for="product-stock-back-orders"
					       class="wwapw-hidden-inventory"><?php echo __( 'Allow backorders? ', WWAPW_DOMAIN ); ?></label>
					<br>
					<select class="wwapw-hidden-inventory" name="product_stock_back_orders"
					        id="product-stock-back-orders">
						<!-- 0 => no, 1 => notify , 2 => yes -->
						<option value="no" selected><?php echo __( 'Do Not Allow', WWAPW_DOMAIN ); ?></option>
						<option value="notify"><?php echo __( 'Allow, but notify customer', WWAPW_DOMAIN ); ?></option>
						<option value="yes"><?php echo __( 'Allow', WWAPW_DOMAIN ); ?></option>
					</select>
				</p>
				<p><?php echo __( '(*) Required', WWAPW_DOMAIN ); ?></p>
			</section>

			<h1><?php echo __( 'Dimensions', WWAPW_DOMAIN ); ?></h1>
			<section>
				<p class="form-field">
					<label
						for="product-weight"><?php echo __( 'Product Weight (kg) ', WWAPW_DOMAIN ); ?></label>
					<input class="wwapw-input-small code" id="product-weight" type="number"
					       name="product_weight"
					       min="0.1">
					<br>
					<label
						for="product-length"><?php echo __( 'Product Length', WWAPW_DOMAIN ); ?></label>
					<input class="wwapw-input-small code" id="product-length" type="number"
					       name="product_length"
					       min="0.1">
					<br>
					<label
						for="product-width"><?php echo __( 'Product Width', WWAPW_DOMAIN ); ?></label>
					<input class="wwapw-input-small code" id="product-width" type="number"
					       name="product_width"
					       min="0.1">
					<br>
					<label
						for="product-height"><?php echo __( 'Product Height', WWAPW_DOMAIN ); ?></label>
					<input class="wwapw-input-small code" id="product-height" type="number"
					       name="product_height"
					       min="0.1">
					<br>

					<!--					<label for="product_shipping_class">-->
					<?php //echo __( 'Shipping class', WWAPW_DOMAIN ); ?><!--</label>-->
					<!--					<br>-->
					<!--					--><?php //$args = [
					//						'taxonomy'         => 'product_shipping_class',
					//						'hide_empty'       => 0,
					//						'show_option_none' => __( 'No shipping class', 'woocommerce' ),
					//						'name'             => 'product_shipping_class',
					//						'id'               => 'product_shipping_class',
					//						'class'            => 'select short',
					//					]; ?>
					<!--					--><?php //wp_dropdown_categories( $args ); ?>

				</p>
			</section>

			<h1><?php echo __( 'Category', WWAPW_DOMAIN ); ?></h1>
			<section>

				<label for="product-categories-select-2">
					<?php echo __( 'Select Category *', WWAPW_DOMAIN ); ?>
				</label>
				<br>
				<div class="category-container">
					<?php
					$select_cats = wp_dropdown_categories( [
						'echo'         => 0,
						'taxonomy'     => 'product_cat',
						'hierarchical' => true,
						'show_count'   => true,
						'id'           => 'product-categories-select-2',
						'name'         => 'cat_input[]',
						'hide_empty'   => false,
						'exclude'      => Helpers::get_excluded_categories(),
						'orderby'      => 'name',
						'order'        => 'ASC',
					] );
					$select_cats = str_replace( 'id=', 'multiple=multiple id=', $select_cats );
					echo $select_cats;
					?>
				</div>

				<label for="product-tags-select-2">
					<?php echo __( 'Select Tag', WWAPW_DOMAIN ); ?>
				</label>
				<div class="tags-container">

					<?php
					$select_tags = wp_dropdown_categories( [
						'echo'         => 0,
						'taxonomy'     => 'product_tag',
						'hierarchical' => true,
						'id'           => 'product-tags-select-2',
						'name'         => 'product_tag_selection[]',
						'hide_empty'   => false,
					] );
					$select_tags = str_replace( 'id=', 'multiple=multiple id=', $select_tags );
					echo $select_tags;
					?>
				</div>
				<p><?php echo __( '(*) Required', WWAPW_DOMAIN ); ?></p>
			</section>

			<h1><?php echo __( 'Gallery', WWAPW_DOMAIN ); ?></h1>
			<section>
				<p class="form-field">
					<label
						for="product-gallery-open"><?php echo __( 'Select Additional Images', WWAPW_DOMAIN ); ?></label>
					<input id="product-gallery-open" type="button" name="image_gallery"
					       class="button" value="<?php echo __( 'Select Images', WWAPW_DOMAIN ); ?>">
				</p>
				<br>
				<div class="image-gallery"></div>
				<br>
				<p class="form-field">
					<label
						for="product-after-submit-condition"><?php echo __( 'What to do after submission ?', WWAPW_DOMAIN ); ?>
					</label>
					<br>
					<select name="product_after_submit_condition" id="product-after-submit-condition">
						<option value="1"><?php echo __( 'Add New Product.', WWAPW_DOMAIN ); ?></option>
						<option value="0"><?php echo __( 'View Added Product.', WWAPW_DOMAIN ); ?></option>
					</select>
				</p>
			</section>

		</div>
	</form>
</div>
