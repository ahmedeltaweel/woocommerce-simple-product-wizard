<?php namespace Wordpress\Woocommerce\Add_Product_Wizard;

/**
 * AJAX handler
 *
 * @package Wordpress\Woocommerce\Add_Product_Wizard
 */
class Ajax_Handler extends Component
{
	/**
	 * Constructor
	 *
	 * @return void
	 */
	protected function init()
	{
		parent::init();

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
		{
			$action = filter_var( isset( $_REQUEST[ 'action' ] ) ? $_REQUEST[ 'action' ] : '', FILTER_SANITIZE_STRING );
			if ( method_exists( $this, $action ) )
			{
				// hook into action if it's method exists
				add_action( 'wp_ajax_' . $action, [ &$this, $action ] );
			}
		}
	}

	/**
	 * AJAX Debug response
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $data
	 *
	 * @return void
	 */
	public function debug( $data )
	{
		// return dump
		$this->error( $data );
	}

	/**
	 * AJAX Debug response ( dump )
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $args
	 *
	 * @return void
	 */
	public function dump( $args )
	{
		// return dump
		$this->error( print_r( func_num_args() === 1 ? $args : func_get_args(), true ) );
	}

	/**
	 * AJAX Error response
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $data
	 *
	 * @return void
	 */
	public function error( $data )
	{
		wp_send_json_error( $data );
	}

	/**
	 * AJAX success response
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $data
	 *
	 * @return void
	 */
	public function success( $data )
	{
		wp_send_json_success( $data );
	}

	/**
	 * AJAX JSON Response
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $response
	 *
	 * @return void
	 */
	public function response( $response )
	{
		// send response
		wp_send_json( $response );
	}

	/**
	 * adding product data as new product.
	 *
	 * @return void
	 */
	public function wwapw_add_product_wizard()
	{
		// verify nonce.
		if ( ! wp_verify_nonce( $_REQUEST[ 'WWAPW_wizard_nonce_field' ], 'WWAPW_wizard_nonce' ) )
		{

			// error in nonce.
			$this->error( ' Error nonce is not verified. ' );
		}

		// check for current user can publish products.
		if ( ! current_user_can( 'publish_products' ) )
		{
			// error permission denied.
			$this->error( 'you do not have permission to add products.' );
		}


		/**
		 * first slide.
		 */

		$slide_no = 0;

		// getting product name.
		$field_name   = 'product_name';
		$product_name = filter_input( INPUT_POST, $field_name, FILTER_SANITIZE_STRING );

		if ( false === $product_name || null === $product_name || empty( $product_name ) )
		{
			$error = [ 'product name is invalid or empty.', $field_name, $slide_no ];
			// null, not string or empty.
			$this->error( $error );
		}

		// getting product description.
		$field_name          = 'product_description';
		$product_description = filter_input( INPUT_POST, $field_name, FILTER_SANITIZE_STRING );

		if ( false === $product_description || null === $product_description )
		{
			$error = [ 'product description is invalid.', $field_name, $slide_no ];
			// null, not string.
			$this->error( $error );
		}

		// getting main image id
		$field_name         = 'image_gallery_main';
		$product_main_image = filter_input( INPUT_POST, $field_name, FILTER_VALIDATE_INT );

		if ( false === $product_main_image || null === $product_main_image
		     || empty( $product_main_image ) || $product_main_image < 1
		)
		{
			$error = [ 'Main image is invalid ( empty ) .', $field_name, $slide_no ];
			// empty, null, not integer or negative
			$this->error( $error );
		}

		if ( ! wp_attachment_is_image( $product_main_image ) )
		{

			$error = [ 'Main image is invalid ( does not exist ) .', $field_name, $slide_no ];
			// image does not exist.
			$this->error( $error );
		}

		/**
		 * second slide.
		 */

		$slide_no = 1;

		// getting regular price.
		$field_name            = 'product_regular_price';
		$product_regular_price = filter_input( INPUT_POST, $field_name, FILTER_VALIDATE_FLOAT, [
			FILTER_FLAG_ALLOW_FRACTION,
			FILTER_FLAG_ALLOW_THOUSAND,
		] );

		if ( false === $product_regular_price || null === $product_regular_price || $product_regular_price < 0.01 )
		{
			$error = [
				'Product regular price is invalid ( empty or negative or less than 0.01 ).',
				$field_name,
				$slide_no,
			];
			// empty, null, not float or negative
			$this->error( $error );
		}


		// validating min quantity.
		$product_min_quantity = '';
		$field_name           = 'product_min_quantity';
		if ( ! empty( $_POST[ $field_name ] ) )
		{
			$product_min_quantity = filter_input( INPUT_POST, $field_name, FILTER_VALIDATE_INT, [
				FILTER_FLAG_ALLOW_THOUSAND,
			] );

			if ( false === $product_min_quantity || null === $product_min_quantity || $product_min_quantity < 1 )
			{
				$error = [
					'Product minimum quantity is invalid ( Negative number ).',
					$field_name,
					$slide_no,
				];
				// not float or negative
				$this->error( $error );
			}
		}

		// variable initialization for quantity.
		$product_max_quantity = '';
		$field_name           = 'product_max_quantity';
		if ( ! empty( $_POST[ $field_name ] ) )
		{
			$product_max_quantity = filter_input( INPUT_POST, $field_name, FILTER_VALIDATE_INT, [
				FILTER_FLAG_ALLOW_THOUSAND,
			] );

			if ( false === $product_max_quantity || null === $product_max_quantity || $product_max_quantity < 1 )
			{
				$error = [
					'Product maximum quantity is invalid ( Negative number ).',
					$field_name,
					$slide_no,
				];
				// not float or negative
				$this->error( $error );
			}
			if ( ! empty( $product_min_quantity ) )
			{
				if ( $product_max_quantity < $product_min_quantity )
				{
					$error = [
						'Product maximum quantity is invalid ( less than minimum quantity ).',
						$field_name,
						$slide_no,
					];
					// not float or negative
					$this->error( $error );
				}
			}

		}

		// variable initialization for sale.
		$product_sale_price = '';
		$product_sale_sdate = '';
		$product_sale_edate = '';

		// check if sale price exists.
		$field_name = 'product_sale_price';
		if ( ! empty( $_POST[ $field_name ] ) )
		{

			// getting sale price.
			$product_sale_price = filter_input( INPUT_POST, $field_name, FILTER_VALIDATE_FLOAT, [
				FILTER_FLAG_ALLOW_FRACTION,
				FILTER_FLAG_ALLOW_THOUSAND,
			] );

			if ( false == $product_sale_price || $product_sale_price < 0.01 )
			{
				$error = [ 'Product sale price is invalid ( negative or less than 0.01 ).', $field_name, $slide_no ];
				// not float or negative
				$this->error( $error );
			}

			// getting start date sale price.
			$field_name         = 'product_sale_price_sdate';
			$product_sale_sdate = $_POST[ $field_name ];

			if ( empty( $product_sale_sdate ) )
			{
				$error = [ 'sale price start date is empty.', $field_name, $slide_no ];
				// empty sale price start date.
				$this->error( $error );
			}

			if ( ! Helpers::is_valid_date( $product_sale_sdate ) )
			{
				$error = [ 'sale price start date is not valid.', $field_name, $slide_no ];
				// not a valid date.
				$this->error( $error );
			}

			// getting end date sale price.
			$field_name         = 'product_sale_price_edate';
			$product_sale_edate = $_POST[ $field_name ];

			if ( empty( $product_sale_edate ) )
			{
				$error = [ 'sale price end date is empty.', $field_name, $slide_no ];
				// empty sale price end date.
				$this->error( $error );
			}

			if ( ! Helpers::is_valid_date( $product_sale_edate ) )
			{
				$error = [ 'sale price end date is not valid.', $field_name, $slide_no ];
				// not a valid date.
				$this->error( $error );
			}

			// converting to time stamps
			$product_sale_sdate = strtotime( $product_sale_sdate );
			$product_sale_edate = strtotime( $product_sale_edate );

			// check if end date is before start date
			if ( $product_sale_sdate > $product_sale_edate )
			{
				$error = [ 'Sale start date must be before or equal sale end data.', $field_name, $slide_no ];
				$this->error( $error );
			}
		}

		/**
		 * third slide
		 */
		$slide_no = 2;

		// getting product sku.

		$field_name  = 'product_sku';
		$product_sku = filter_input( INPUT_POST, $field_name, FILTER_SANITIZE_STRING );

		if ( false === $product_sku || null === $product_sku || empty( $product_sku ) )
		{
			$error = [ 'invalid sku ( empty ).', $field_name, $slide_no ];
			// null, not string or empty.
			$this->error( $error );
		}

		// getting stock status.
		$field_name           = 'product_stock_status';
		$product_stock_status = filter_input( INPUT_POST, $field_name, FILTER_SANITIZE_STRING );

		if ( false === $product_stock_status || empty( $product_stock_status )
		     || ! in_array( $product_stock_status, [ 'instock', 'outofstock', ] )
		)
		{
			$error = [ 'invalid stock status.', $field_name, $slide_no ];
			// invalid stock status.
			$this->error( $error );
		}


		// variables initialization .
		$product_stock_managment = 'no';
		$product_stock_quantity  = '';
		$product_stock_backorder = '';

		$field_name = 'product_stock_quantity';
		if ( isset( $_POST[ 'product_stock_management' ] ) )
		{
			// stock management enabled.
			$product_stock_managment = 'yes';

			// getting stock quantity.
			$product_stock_quantity = filter_input( INPUT_POST, $field_name, FILTER_VALIDATE_INT );

			if ( false === $product_stock_quantity || null === $product_stock_quantity || $product_stock_quantity < 0
			)
			{
				$error = [ 'Stock quantity is invalid ( empty , negative ) .', $field_name, $slide_no ];
				// empty, null, not integer or negative
				$this->error( $error );
			}

			// getting stock backorder.

			$field_name              = 'product_stock_back_orders';
			$product_stock_backorder = filter_input( INPUT_POST, $field_name, FILTER_SANITIZE_STRING );

			if ( false === $product_stock_backorder || empty( $product_stock_backorder )
			     || ! in_array( $product_stock_backorder, [ 'no', 'yes', 'notify' ] )
			)
			{
				$error = [ 'invalid stock backorder.', $field_name, $slide_no ];
				// invalid stock status.
				$this->error( $error );
			}
		}


		/**
		 * fourth slide.
		 */

		$slide_no = 3;

		// product weight.
		$product_weight = '';
		$field_name     = 'product_weight';
		if ( ! empty( $_POST[ $field_name ] ) )
		{
			// getting product weight.
			$product_weight = filter_input( INPUT_POST, $field_name, FILTER_VALIDATE_FLOAT );

			if ( false === $product_weight || null === $product_weight || $product_weight < 0.1 )
			{
				$error = [ 'Product weight is invalid ( negative ) .', $field_name, $slide_no ];
				// not integer or negative
				$this->error( $error );
			}
		}

		// product length.
		$product_length = '';
		$field_name     = 'product_length';
		if ( ! empty( $_POST[ $field_name ] ) )
		{
			// getting product length.
			$product_length = filter_input( INPUT_POST, $field_name, FILTER_VALIDATE_FLOAT );

			if ( false === $product_length || null === $product_length || $product_length < 0.1 )
			{
				$error = [ 'Product length is invalid ( negative ) .', $field_name, $slide_no ];
				// not integer or negative
				$this->error( $error );
			}
		}

		// product width.
		$product_width = '';
		$field_name    = 'product_width';
		if ( ! empty( $_POST[ $field_name ] ) )
		{
			// getting  product width.
			$product_width = filter_input( INPUT_POST, $field_name, FILTER_VALIDATE_FLOAT );

			if ( false === $product_width || null === $product_width || $product_width < 0.1 )
			{
				$error = [ 'Product width is invalid ( negative ) .', $field_name, $slide_no ];
				// not integer or negative
				$this->error( $error );
			}
		}

		// product height.
		$product_height = '';
		$field_name     = 'product_height';
		if ( ! empty( $_POST[ $field_name ] ) )
		{
			// getting product height.
			$product_height = filter_input( INPUT_POST, $field_name, FILTER_VALIDATE_FLOAT );

			if ( false === $product_height || null === $product_height || $product_height < 0.1 )
			{
				$error = [ 'Product height is invalid ( negative ) .', $field_name, $slide_no ];
				// not integer or negative
				$this->error( $error );
			}
		}

		// set shipping class to default ( no shipping class. ).
		$product_shipping_class = -1;

//		// shipping class.
//		$field_name = 'product_shipping_class';
//
//		// validate shipping class.
//		$product_shipping_class = filter_input( INPUT_POST, $field_name, FILTER_VALIDATE_INT );
//
//		if ( false === $product_shipping_class )
//		{
//			$error = [ 'Product shipping class is invalid.', $field_name, $slide_no ];
//			// not integer or negative
//			$this->error( $error );
//		}
//
//		$args = [
//			'taxonomy'   => 'product_shipping_class',
//			'hide_empty' => 0,
//			'fields'     => 'ids',
//		];
//
//		// getting all shipping classes.
//		$shipping_classes = get_terms( $args );
//
//		// Adding -1 for no shipping class.
//		$shipping_classes [] = '-1';
//
//		// check if shipping class exists.
//		if ( ! in_array( $product_shipping_class, $shipping_classes ) )
//		{
//			$error = [ 'Product shipping class does not exists.', $field_name, $slide_no ];
//			// not fount.
//			$this->error( $error );
//		}


		/**
		 * fifth slide.
		 */

		$slide_no = 4;

		// getting categories.
		$field_name         = 'cat_input';
		$product_categories = filter_input( INPUT_POST, $field_name, FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY );


		if ( ! $product_categories )
		{
			$error = [ 'Invalid categories.', $field_name, $slide_no ];
			//invalid.
			$this->error( $error );
		}

		if ( empty( $product_categories ) || ! is_array( $product_categories ) )
		{
			$error = [ 'Empty categories.', $field_name, $slide_no ];
			//invalid.
			$this->error( $error );
		}

		// getting all categories.
		$cats = get_categories( [ 'taxonomy' => 'product_cat', 'hide_empty' => false, 'fields' => 'ids' ] );


		for ( $i = 0, $j = count( $product_categories ); $i < $j; $i ++ )
		{
			$cat_id = $product_categories[ $i ];

			if ( ! in_array( $cat_id, $cats ) )
			{
				$error = [ 'Invalid category not found.', $field_name, $slide_no ];
				// invalid category id ( not in the system. ).
				$this->error( $error );
			}
		}


		// getting tags.
		$product_tags = '';
		$field_name   = 'product_tag_selection';
		if ( ! empty( $_POST[ $field_name ] ) )
		{
			// getting tags.
			$product_tags = filter_input( INPUT_POST, $field_name, FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY );

			if ( ! $product_tags )
			{
				$error = [ 'Invalid tags.', $field_name, $slide_no ];
				//invalid.
				$this->error( $error );
			}

			// getting all tags.
			$tags = get_terms( [ 'taxonomy' => 'product_tag', 'hide_empty' => false, 'fields' => 'ids' ] );

			for ( $i = 0, $j = count( $product_tags ); $i < $j; $i ++ )
			{
				$tag_id = &$product_tags[ $i ];

				// check if slug is registered.
				if ( ! in_array( $tag_id, $tags ) )
				{
					// ( not in the system.)
					if ( ! is_numeric( $tag_id ) )
					{
						// add it to the system.
						$tag_id = wp_insert_term( $tag_id, 'product_tag' )[ 'term_id' ];

					} else
					{
						$error = [ 'Invalid tag id.', $field_name, $slide_no ];
						// error.
						$this->error( $error );
					}
				}

				//get the slug and assign it to the tags array.
				$tag_id = get_term_by( 'id', $tag_id, 'product_tag' )->slug;

			}
		}


		/**
		 * sixth slide
		 */
		$slide_no = 5;

		// getting all images.
		// variable initialization.
		$product_images = '';
		$field_name     = 'image_gallery';
		if ( ! empty( $_POST[ $field_name ] ) )
		{

			// getting images ids
			$product_images = filter_input( INPUT_POST, $field_name, FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY );


			if ( ! $product_images )
			{
				$error = [ 'Main image is invalid.', $field_name, $slide_no ];
				// empty, null, not integer or negative
				$this->error( $error );
			}

			for ( $i = 0, $j = count( $product_images ); $i < $j; $i ++ )
			{
				if ( ! wp_attachment_is_image( $product_images[ $i ] ) )
				{
					$error = [ 'One or more gallery images are invalid ( does not exist ) .', $field_name, $slide_no ];
					// image does not exist.
					$this->error( $error );
				}
			}
		}

		// getting after submission action

		// getting after submit condition.
		$field_name             = 'product_after_submit_condition';
		$after_submit_condition = filter_input( INPUT_POST, $field_name, FILTER_VALIDATE_INT );

		if ( false === $after_submit_condition || ! in_array( $after_submit_condition, [ 0, 1 ] ) )
		{
			$error = [ 'invalid after submission selection.', $field_name, $slide_no ];
			//error selection do not found.
			$this->error( $error );
		}

		/**
		 * adding new product.
		 */
		$post_id = $this->add_new_product(
			$product_name,
			$product_description,
			$product_main_image,
			$product_categories,
			$product_tags,
			$product_stock_status,
			$product_regular_price,
			$product_min_quantity,
			$product_max_quantity,
			$product_sale_price,
			$product_sku,
			$product_sale_sdate,
			$product_sale_edate,
			$product_stock_managment,
			$product_stock_backorder,
			$product_stock_quantity,
			$product_weight,
			$product_length,
			$product_width,
			$product_height,
			$product_shipping_class,
			$product_images );

		if ( is_wp_error( $post_id ) )
		{
			$this->error( 'error adding product.' );
		}

		$after_submit_url = '';
		if ( $after_submit_condition == 0 )
		{
			// go to product edit page.
			$after_submit_url = admin_url( "post.php?post=$post_id&action=edit" );

		} elseif ( $after_submit_condition == 1 )
		{
			// add new product.
			$after_submit_url = admin_url( 'edit.php?post_type=product&page=' . WWAPW_WIZARD_PAGE_NAME );

		}

		// sending redirect option.
		$this->success( $after_submit_url );

	}

	/**
	 * add new product.
	 *
	 * @param $product_name
	 * @param $product_description
	 * @param $product_main_image
	 * @param $product_weight
	 * @param $product_length
	 * @param $product_width
	 * @param $product_height
	 * @param $product_shipping_class
	 * @param $product_categories
	 * @param $product_tags
	 * @param $product_stock_status
	 * @param $product_regular_price
	 * @param $product_min_quantity
	 * @param $product_max_quantity
	 * @param $product_sale_price
	 * @param $product_sku
	 * @param $product_sale_sdate
	 * @param $product_sale_edate
	 * @param $product_stock_managment
	 * @param $product_stock_backorder
	 * @param $product_stock_quantity
	 * @param $product_images
	 *
	 * @return int|\WP_Error $post_id
	 */
	protected function add_new_product(
		$product_name,
		$product_description,
		$product_main_image,
		$product_categories,
		$product_tags,
		$product_stock_status,
		$product_regular_price,
		$product_min_quantity,
		$product_max_quantity,
		$product_sale_price,
		$product_sku,
		$product_sale_sdate,
		$product_sale_edate,
		$product_stock_managment,
		$product_stock_backorder,
		$product_stock_quantity,
		$product_weight,
		$product_length,
		$product_width,
		$product_height,
		$product_shipping_class,
		$product_images
	)
	{
		// post args.
		$post = [
			'post_author'  => get_current_user_id(),
			'post_content' => $product_description,
			'post_status'  => "publish",
			'post_title'   => $product_name,
			'post_parent'  => '',
			'post_type'    => "product",
		];

		//Create post
		$post_id = wp_insert_post( $post );
		if ( ! $post_id )
		{
			$this->error( 'Error inserting post.' );
		}

		// adding main image.
		add_post_meta( $post_id, '_thumbnail_id', $product_main_image );


		// making it simple product.
		wp_set_object_terms( $post_id, 'simple', 'product_type' );


		// setting categories.
		wp_set_object_terms( $post_id, $product_categories, 'product_cat' );


		if ( ! empty( $product_tags ) )
		{
			// setting tags.
			wp_set_object_terms( $post_id, $product_tags, 'product_tag' );
		}


		// adding post metas.
		update_post_meta( $post_id, '_visibility', 'visible' );
		update_post_meta( $post_id, '_stock_status', $product_stock_status );
		update_post_meta( $post_id, 'total_sales', '0' );
		update_post_meta( $post_id, '_downloadable', '' );
		update_post_meta( $post_id, '_virtual', '' );
		update_post_meta( $post_id, '_regular_price', $product_regular_price );
		update_post_meta( $post_id, '_sale_price', $product_sale_price );
		update_post_meta( $post_id, '_purchase_note', "" );
		update_post_meta( $post_id, '_featured', "no" );
		update_post_meta( $post_id, '_weight', $product_weight );
		update_post_meta( $post_id, '_length', $product_length );
		update_post_meta( $post_id, '_width', $product_width );
		update_post_meta( $post_id, '_height', $product_height );
		update_post_meta( $post_id, '_sku', $product_sku );
		update_post_meta( $post_id, '_product_attributes', [] );
		update_post_meta( $post_id, '_sale_price_dates_from', $product_sale_sdate );
		update_post_meta( $post_id, '_sale_price_dates_to', $product_sale_edate );
		update_post_meta( $post_id, '_price', $product_regular_price );
		update_post_meta( $post_id, '_sold_individually', "" );
		update_post_meta( $post_id, '_manage_stock', $product_stock_managment );
		update_post_meta( $post_id, '_backorders', $product_stock_backorder );
		update_post_meta( $post_id, '_stock', $product_stock_quantity );
		update_post_meta( $post_id, 'minimum_allowed_quantity', $product_min_quantity );
		update_post_meta( $post_id, 'maximum_allowed_quantity', $product_max_quantity );


		// attaching post to term.
		wp_set_post_terms( $post_id, [ (int) $product_shipping_class ], 'product_shipping_class' );


		if ( ! empty( $product_images ) )
		{
			// image gallery
			update_post_meta( $post_id, '_product_image_gallery', implode( ',', $product_images ) );
		}

		return $post_id;

	}
}
