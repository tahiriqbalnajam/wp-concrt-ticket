<?php

class Concrt_Ticket_Admin {

	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/concrt-ticket-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/concrt-ticket-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function create_menu() {

        /**
         * Create a submenu page under Plugins.
         * Framework also add "Settings" to your plugin in plugins list.
         * @link https://github.com/JoeSz/Exopite-Simple-Options-Framework
         */
        $config_submenu = array(
            'type'              => 'menu',                          // Required, menu or metabox
            'id'                => $this->plugin_name,              // Required, meta box id, unique per page, to save: get_option( id )
            'parent'            => 'options-general.php',                   // Parent page of plugin menu (default Settings [options-general.php])
            'submenu'           => true,                            // Required for submenu
            'title'             => 'Ticket Options',               // The title of the options page and the name in admin menu
            'capability'        => 'manage_options',                // The capability needed to view the page
            'plugin_basename'   =>  plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' ),
            'tabbed'            => true,
            // 'multilang'         => false,                        // To turn of multilang, default on.
        );         
        $fields[] = array(
            'title' => 'Main Act ',
            'icon' => 'fa fa-asterisk',
            'name' => 'main_act',
            'fields' => array(
                array(
                    'type'    => 'group',
                    'id'      => 'main_act_options',
                    'options' => array(
                        'repeater'          => true,
                        'accordion'         => true,
                        'button_title'      => esc_html__( 'Add Main Act', 'plugin-name' ),
                        'group_title'       => esc_html__( 'Main Act', 'plugin-name' ),
                        'limit'             => 50,
                        'sortable'          => true,
                    ),
                    'fields'  => array(
                        array(
                            'id'      => 'main_act_name',
                            'type'    => 'text',
                            'title'   => esc_html__( 'Main Act', 'plugin-name' ),
                            'attributes' => array(
                                'data-title' => 'title',
                                'placeholder' => esc_html__( 'Enter Main Act', 'plugin-name' ),
                            ),
                        ),
                        array(
                            'id'      => 'main_act_image',
                            'type'    => 'image',
                            'title'   => 'Select Image',
                        ),
                    ),
                ),
            ),
        );

        $fields[] = array(
            'title' => 'Tour Promoter',
            'icon' => 'fa fa-asterisk',
            'name' => 'tour_promoter',
            'fields' => array(
                array(
                    'type'    => 'group',
                    'id'      => 'tour_promoter_options',
                    'options' => array(
                        'repeater'          => true,
                        'accordion'         => true,
                        'button_title'      => esc_html__( 'Add Tour Promoter', 'plugin-name' ),
                        'group_title'       => esc_html__( 'Tour Promoter', 'plugin-name' ),
                        'limit'             => 50,
                        'sortable'          => true,
                    ),
                    'fields'  => array(
                        array(
                            'id'      => 'tour_promoter_name',
                            'type'    => 'text',
                            'title'   => esc_html__( 'Tour Promoter', 'plugin-name' ),
                            'attributes' => array(
                                'data-title' => 'title',
                                'placeholder' => esc_html__( 'Enter Tour Promoter Name', 'plugin-name' ),
                            ),
                        ),
                        array(
                            'id'      => 'tour_promoter_image',
                            'type'    => 'image',
                            'title'   => 'Select Image',
                        ),
                        array(
                            'id'      => 'tour_promoter_email',
                            'type'    => 'text',
                            'title'   => 'Enter Email-id',
                        ),
                    ),
                ),
            ),
        );

        $fields[] = array(
            'title' => 'Presenting Partners',
            'icon' => 'fa fa-asterisk',
            'name' => 'presenting_partners',
            'fields' => array(
                array(
                    'type'    => 'group',
                    'id'      => 'presenting_partners_options',
                    'options' => array(
                        'repeater'          => true,
                        'accordion'         => true,
                        'button_title'      => esc_html__( 'Add Presenting Partners', 'plugin-name' ),
                        'group_title'       => esc_html__( 'Tour Promoter', 'plugin-name' ),
                        'limit'             => 50,
                        'sortable'          => true,
                    ),
                    'fields'  => array(
                        array(
                            'id'      => 'presenting_partners_name',
                            'type'    => 'text',
                            'title'   => esc_html__( 'Presenting Partners', 'plugin-name' ),
                            'attributes' => array(
                                'data-title' => 'title',
                                'placeholder' => esc_html__( 'Enter Presenting Partners Name', 'plugin-name' ),
                            ),
                        ),
                        array(
                            'id'      => 'presenting_partners_image',
                            'type'    => 'image',
                            'title'   => 'Select Image',
                        ),
                    ),
                ),
            ),
        );
        $options_panel = new Exopite_Simple_Options_Framework( $config_submenu, $fields );
    }

	public function woocommerce_product_data_tabs($tabs) {

        $tabs['concert_ticket'] = array(
            'label'     => __( 'Concert Ticket', 'concert_ticket' ),
            'target' => 'concert_ticket_options',
            'class'  => 'show_if_concert_ticket',
        );

        return $tabs;
    }

	function custom_product_field() {
        global $post;
        $all_options = get_exopite_sof_option($this->plugin_name); 
        
        echo "<div id='concert_ticket_options' class='panel woocommerce_options_panel'>";
        echo '<p class="form-field comment_status_field ">
                <input type="submit" name="submit" id="submit" class="button button-primary" value="Download Report"  />
              </p>';
            
        // woocommerce_wp_text_input(
        //     array(
        //         'id' => 'ticket_tour_title',
        //         'label' => __('Enter tour name', 'woocommerce'),
        //         'type' => 'text', 
		// 		'custom_attributes' => array( 'required' => 'required' ),
        //     )
        // );

        // if(isset($all_options['main_act_options'])){
        //     $main_act_names = array();
        //     foreach ($all_options['main_act_options'] as $option) {
        //             $main_act_names[] = $option['main_act_name']; // Add main_act_name to the new array
        //     }
        //     $options = array(
        //         ''        => __( 'Select Main Act', 'woocommerce' ),
        //     );
        //     $act_name = array_merge($options, array_combine($main_act_names, $main_act_names));
        //     woocommerce_wp_select(
        //         array(
        //             'id'          => '_custom_main_act',
        //             'label'       => __( 'Main Act', 'woocommerce' ),
        //             'options'     => $act_name, // Use the updated $options array
        //         )
        //     );
        // }

        // if (isset($all_options['tour_promoter_options'])){
        //     $tour_promoter_names = array();
        //     foreach ($all_options['tour_promoter_options'] as $option) {
        //             $tour_promoter_names[] = $option['tour_promoter_name']; // Add main_act_name to the new array
        //     }
        //     $options = array(
        //         ''        => __( 'Select Tour Promoter', 'woocommerce' ),
        //     );
        //     $options = array_merge($options, array_combine($tour_promoter_names, $tour_promoter_names));
        //     woocommerce_wp_select(
        //         array(
        //             'id'          => '_custom_tour_romoter',
        //             'label'       => __( 'Tour Promoter', 'woocommerce' ),
        //             'options'     => $options, // Use the updated $options array
        //         )
        //     );
        // }

        // $tour_promoter_names = array();
        // if (isset($all_options['presenting_partners_options'])){
        //     foreach ($all_options['presenting_partners_options'] as $option) {
        //             $tour_promoter_names[] = $option['presenting_partners_name']; // Add main_act_name to the new array
        //     }
        //     $options = array(
        //         ''        => __( 'Select Presenting Partners', 'woocommerce' ),
        //     );
        //     $options = array_merge($options, array_combine($tour_promoter_names, $tour_promoter_names));
        //     woocommerce_wp_select(
        //         array(
        //             'id'          => '_custom_presenting_partners',
        //             'label'       => __( 'Presenting Partners', 'woocommerce' ),
        //             'options'     => $options, // Use the updated $options array
        //         )
        //     );
        // }

        // woocommerce_wp_text_input(
        //     array(
        //         'id' => '_custom_start_date_field',
        //         'label' => __('Select Sale Start Date', 'woocommerce'),
        //         //'desc_tip' => 'true',
        //         //'description' => __('Select a date using the calendar.', 'your-text-domain'),
        //         'type' => 'datetime-local', // This sets the input type to 'date'

        //     )
        // );
    
        // woocommerce_wp_text_input(
        //     array(
        //         'id' => '_custom_end_date_field',
        //         'label' => __('Select Sale End Date', 'woocommerce'),
        //         'type' => 'datetime-local', // This sets the input type to 'date'
        //     )
        // );

        // echo '<H4"><b style="padding-left:30px;">Add Venue</b></h4>';
        // woocommerce_wp_text_input(
        //     array(
        //         'id' => 'ticket_venue_name',
        //         'label' => __('Venue Name', 'woocommerce'),
        //         'type' => 'text', 
		// 		'custom_attributes' => array( 'required' => 'required' ),
        //     )
        // );
        // woocommerce_wp_text_input(
        //     array(
        //         'id' => 'ticket_venue_street',
        //         'label' => __('Street Number', 'woocommerce'),
        //         'type' => 'text',
		// 		'custom_attributes' => array( 'required' => 'required' ),
        //     )
        // );
        // woocommerce_wp_text_input(
        //     array(
        //         'id' => 'ticket_venue_city',
        //         'label' => __('Venue City', 'woocommerce'),
        //         'type' => 'text',
		// 		'custom_attributes' => array( 'required' => 'required' ), 
        //     )
        // );
        // woocommerce_wp_text_input(
        //     array(
        //         'id' => 'ticket_venue_zipcode',
        //         'label' => __('Venue Zipcode', 'woocommerce'),
        //         'type' => 'text', 
		// 		'custom_attributes' => array( 'required' => 'required' ),
        //     )
        // );  
        echo '</div>';
    }
	

	function save_custom_product_field($product_id) {
        

        $ticket_tour_title = $_POST['ticket_tour_title'];
        if (!empty($custom_date)) {
            update_post_meta($product_id, 'ticket_tour_title', esc_attr($ticket_tour_title));
        }

        $custom_date = $_POST['_custom_start_date_field'];
        if (!empty($custom_date)) {
            update_post_meta($product_id, '_custom_start_date_field', esc_attr($custom_date));
        }

        $custom_date = $_POST['_custom_end_date_field'];
        if (!empty($custom_date)) {
            update_post_meta($product_id, '_custom_end_date_field', esc_attr($custom_date));
        }

        $main_act = $_POST['_custom_main_act'];
        if (!empty($main_act)) {
            update_post_meta($product_id, '_custom_main_act', esc_attr($main_act));
        }

        $main_act = $_POST['_custom_tour_romoter'];
        if (!empty($main_act)) {
            update_post_meta($product_id, '_custom_tour_promoter', esc_attr($main_act));
        }

        $main_act = $_POST['_custom_presenting_partners'];
        if (!empty($main_act)) {
            update_post_meta($product_id, '_custom_presenting_partners', esc_attr($main_act));
        }

        $venue_name = $_POST['ticket_venue_name'];
        $venue_street = $_POST['ticket_venue_street'];
        $venue_city = $_POST['ticket_venue_city'];
        $venue_zipcode = $_POST['ticket_venue_zipcode'];
        if (!empty($venue_name)) {
            update_post_meta($product_id, 'ticket_venue_name', esc_attr($venue_name));
        }
        if (!empty($venue_street)) {
            update_post_meta($product_id, 'ticket_venue_street', esc_attr($venue_street));
        }
        if (!empty($venue_city)) {
            update_post_meta($product_id, 'ticket_venue_city', esc_attr($venue_city));
        }
        if (!empty($venue_zipcode)) {
            update_post_meta($product_id, 'ticket_venue_zipcode', esc_attr($venue_zipcode));
        }
        update_post_meta($product_id, '_custom_venue_address', esc_attr($venue_name.', '.$venue_street.', '.$venue_city.', '.$venue_zipcode));

    }

    public function add_custom_prices_to_variations($loop, $variation_data, $variation ) {
        $all_options = get_exopite_sof_option($this->plugin_name); 
       // print_r($all_options);
        // woocommerce_wp_text_input( array(
        //     'id' => 'tax_fee[' . $loop . ']',
        //     'wrapper_class' => 'form-field variable_price_tax_fee form-row form-row-first',
        //     'class' => 'short',
        //     'label' => __( 'Tax (%)', 'woocommerce' ),
        //     'value' => get_post_meta( $variation->ID, 'tax_fee', true )
        // ));
    
       woocommerce_wp_text_input( array(
            'id' => 'presale_fee[' . $loop . ']',
            'wrapper_class' => 'form-field variable_sale_presale_fee form-row form-row-first',
            'class' => 'short',
            'label' => __( 'Presale Fee (%)', 'woocommerce' ),
            'value' => get_post_meta( $variation->ID, 'presale_fee', true )
        ));
    
       woocommerce_wp_text_input( array(
            'id' => 'system_fee[' . $loop . ']',
            'wrapper_class' => 'form-field variable_sale_system_fee form-row form-row-last',
            'class' => 'short',
            'label' => __( 'System Fee', 'woocommerce' ),
            'value' => get_post_meta( $variation->ID, 'system_fee', true )
        ));
    
       woocommerce_wp_text_input( array(
            'id' => 'marketing_fee[' . $loop . ']',
            'wrapper_class' => 'form-field variable_sale_marketing_fee form-row form-row-first',
            'class' => 'short',
            'label' => __( 'Marketing Fee', 'woocommerce' ),
            'value' => get_post_meta( $variation->ID, 'marketing_fee', true )
        ));

        // Tour name
       woocommerce_wp_text_input( array(
            'id' => 'tour_name[' . $loop . ']',
            'wrapper_class' => 'form-field variable_sale_tour_name form-row form-row-last',
            'class' => 'short',
            'label' => __( 'Tour Name', 'woocommerce' ),
            'value' => get_post_meta( $variation->ID, 'tour_name', true )
        ));

       
        if(isset($all_options['main_act_options'])){
            $main_act_names = array();
            foreach ($all_options['main_act_options'] as $option) {
                    $main_act_names[] = $option['main_act_name']; // Add main_act_name to the new array
            }
            $options = array(
                ''        => __( 'Select Main Act', 'woocommerce' ),
            );
            $act_name = array_merge($options, array_combine($main_act_names, $main_act_names));
            $main_act_name = get_post_meta( $variation->ID, 'main_act_name', true );
            woocommerce_wp_select(
                array(
                    'id'          => 'main_act_name[' . $loop . ']',
                    'label'       => __( 'Main Act', 'woocommerce' ),
                    'wrapper_class' => 'form-field variable_sale_tour_name form-row form-row-first',
                    'options'     => $act_name, // Use the updated $options array
                    'value'       => $main_act_name,
                )
            );
        }
        //Tour promotor
        $tour_promoter_names = array();
        if (isset($all_options['tour_promoter_options'])){
            foreach ($all_options['tour_promoter_options'] as $option) {
                    $tour_promoter_names[] = $option['tour_promoter_name']; // Add main_act_name to the new array
            }
            $options = array(
                ''        => __( 'Select Tour Promotor', 'woocommerce' ),
            );
            $options = array_merge($options, array_combine($tour_promoter_names, $tour_promoter_names));
            $tour_promotor = get_post_meta( $variation->ID, 'tour_promotor', true );
            woocommerce_wp_select(
                array(
                    'id' => 'tour_promotor[' . $loop . ']',
                    'wrapper_class' => 'form-field variable_sale_tour_name form-row form-row-last',
                    'class' => 'short',
                    'label' => __( 'Tour Promotor', 'woocommerce' ),
                    'options'     => $options, // Use the updated $options array
                    'value' => $tour_promotor, // Use the first option as the default value

                )
            );
        }
        //Show start date time
        woocommerce_wp_text_input( array(
            'id' => 'nlocal_promoter[' . $loop . ']',
            'wrapper_class' => 'form-field variable_sale_nlocal_promoter form-row form-row-first',
            'class' => 'short',
            'label' => __( 'Local Promoter', 'woocommerce' ),
            'value' => get_post_meta( $variation->ID, 'nlocal_promoter', true )
        ));

       woocommerce_wp_text_input( array(
            'id' => 'start_date_time[' . $loop . ']',
            'type' => 'datetime-local',
            'wrapper_class' => 'form-field variable_sale_marketing_fee form-row form-row-last',
            'class' => 'short',
            'label' => __( 'Start date/time', 'woocommerce' ),
            'value' => get_post_meta( $variation->ID, 'start_date_time', true )
        ));
        //Sale end date time
    //    woocommerce_wp_text_input( array(
    //         'id' => 'end_date_time[' . $loop . ']',
    //         'type' => 'datetime-local',
    //         'wrapper_class' => 'form-field variable_sale_marketing_fee form-row form-row-last',
    //         'class' => 'short',
    //         'label' => __( 'End date/time', 'woocommerce' ),
    //         'value' => get_post_meta( $variation->ID, 'end_date_time', true )
    //     ));

       woocommerce_wp_text_input( array(
            'id' => 'door_close[' . $loop . ']',
            'type' => 'time',
            'wrapper_class' => 'form-field variable_sale_marketing_fee form-row form-row-first',
            'class' => 'short',
            'label' => __( 'Doors', 'woocommerce' ),
            'value' => get_post_meta( $variation->ID, 'door_close', true )
        ));
        //Venue Name
       woocommerce_wp_text_input( array(
            'id' => 'venue_name[' . $loop . ']',
            'wrapper_class' => 'form-field variable_sale_marketing_fee form-row form-row-last',
            'class' => 'short',
            'label' => __( 'Venue name', 'woocommerce' ),
            'value' => get_post_meta( $variation->ID, 'venue_name', true )
        ));
        //Street + Number
       woocommerce_wp_text_input( array(
            'id' => 'street_address[' . $loop . ']',
            'wrapper_class' => 'form-field variable_sale_marketing_fee form-row form-row-first',
            'class' => 'short',
            'label' => __( 'Street Address', 'woocommerce' ),
            'value' => get_post_meta( $variation->ID, 'street_address', true )
        ));
        //Zip-Code
       woocommerce_wp_text_input( array(
            'id' => 'zip_code[' . $loop . ']',
            'wrapper_class' => 'form-field variable_sale_marketing_fee form-row form-row-last',
            'class' => 'short',
            'label' => __( 'Zip code', 'woocommerce' ),
            'value' => get_post_meta( $variation->ID, 'zip_code', true )
        ));
        //City
       woocommerce_wp_text_input( array(
            'id' => 'city[' . $loop . ']',
            'wrapper_class' => 'form-field variable_sale_marketing_fee form-row form-row-first',
            'class' => 'short',
            'label' => __( 'City', 'woocommerce' ),
            'value' => get_post_meta( $variation->ID, 'city', true )
        ));

        echo '<p class="form-field variable_sale_tour_name form-row form-row-first">
                <a href="javascript:;" class="button  alt" id="generate_report" data-variationid="'.$variation->ID.'" onclick=" getReport('.$variation->ID.')">
                    Get Report
                </a>
             </p>
             <p class="form-field variable_sale_tour_name form-row form-row-last">
                <a href="'.site_url().'/ticket/sample/'.$variation->ID.'" class="button"  id="generate_report" target="_blank">
                    Sample Ticket
                </a>
             </p>';

    }

    public function save_prices_field_variations( $variation_id, $i ) {
        $meta_fields = array(
            'tax_fee',
            'presale_fee',
            'system_fee',
            'marketing_fee',
            'tour_name',
            'tour_promotor',
            'main_act_name',
            'nlocal_promoter',
            'start_date_time',
            'end_date_time',
            'door_close',
            'venue_name',
            'street_address',
            'zip_code',
            'city'
        );

        foreach ($meta_fields as $field) {
            if (isset($_POST[$field][$i])) {
                update_post_meta($variation_id, $field, esc_attr($_POST[$field][$i]));
            }
        } 

    }
    public function add_custom_field_variation_data( $variations ) {
        $variations['custom_field'] = '<div class="woocommerce_custom_field">Custom Field: <span>' . get_post_meta( $variations[ 'variation_id' ], 'custom_field', true ) . '</span></div>';
        return $variations;
    }

    public function generate_report() {
        ob_start();
        include 'partials/excel_report.php';
        $output = ob_get_clean();
        echo $output;
    }

    public function get_report(){
        
    }

    function get_product_orders($product_id) {
        $orders = array();
        global $wpdb;
        $order_status = ['wc-completed', 'wc-processing', 'wc-on-hold'];
         
        $results = $wpdb->get_col("
            SELECT order_items.order_id
            FROM {$wpdb->prefix}woocommerce_order_items as order_items
            LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
            LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
            WHERE posts.post_type = 'shop_order'
            AND posts.post_status IN ( '" . implode( "','", $order_status ) . "' )
            AND order_items.order_item_type = 'line_item'
            AND order_item_meta.meta_key = '_variation_id'
            AND order_item_meta.meta_value = '".$product_id."'
            ORDER BY order_items.order_id DESC");
     
        return $results;
    }

    public function download_orders_csv() {
        $product_id = $_POST['variation_id'];
        $tour_name = get_post_meta( $product_id, 'tour_name', true );
        $main_act_name = get_post_meta( $product_id, 'main_act_name', true );
        $regular_price = get_post_meta( $product_id, '_regular_price', true );
        $sale_price = get_post_meta( $product_id, '_sale_price', true );
        $price =  ( $sale_price ) ?? $regular_price;
        $price =  $regular_price;
        $venue_name = get_post_meta( $product_id, 'venue_name', true );
        $local_promoter = get_post_meta( $product_id, 'nlocal_promoter', true );
        $street_address = get_post_meta( $product_id, 'street_address', true );
        $zip_code = get_post_meta( $product_id, 'zip_code', true );
        $city = get_post_meta( $product_id, 'city', true );
        $start_date_time = get_post_meta( $product_id, 'start_date_time', true );
        $end_date_time = get_post_meta( $product_id, 'end_date_time', true );
        $presale_fee = get_post_meta( $product_id, 'presale_fee', true );
        $system_fee = get_post_meta( $product_id, 'system_fee', true );
        $marketing_fee = get_post_meta( $product_id, 'marketing_fee', true );
        $orders = $this->get_product_orders($product_id);
        $total = 0;
        $no_of_orders = 0;
        $total_no_of_orders = 0;
        
        foreach ($orders as $order_id) {
            $order = wc_get_order($order_id);
            foreach ( $order->get_items() as $item_key => $item ) {
                $quantity = $item->get_quantity();
                $item_data = $item->get_data();
                $variation_id = $item_data['variation_id'];
                for( $i=1; $i<=$quantity; $i++){ 
                    if($variation_id == $product_id){
                        $total_no_of_orders++;
                    }
                }
            }
            //$order = wc_get_order($order_id);
            //$no_of_orders++;
            //$currency = $order->get_currency();
            // $customer = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
            // $email = $order->get_billing_email();
            //$total += $order->get_total();
            // $date = $order->get_date_created()->format('Y-m-d H:i:s');
            // $csv_data[] = array($order_id, $customer, $email, $total, $date);
        }

        $currency = $order->get_currency();
        $total = $total_no_of_orders*$price;
        $total_sum = $total + $total_no_of_orders*((10 / 100) * $price) + $total_no_of_orders* $system_fee + $total_no_of_orders* $marketing_fee;

        $csv_data[] = array('Subject', $tour_name);
        $csv_data[] = array(' ');
        $csv_data[] = array('Main Act', $main_act_name);
        $show_date = date('d F, Y', strtotime($start_date_time));
        $csv_data[] = array('Show Date', $show_date);
        $csv_data[] = array('Venue Name', $venue_name);
        $csv_data[] = array('Local Promoter', $local_promoter);
        $csv_data[] = array('Street #', $street_address);

        $csv_data[] = array('Zip + City', $zip_code.', '.$city);
        $csv_data[] = array(' ');
        $csv_data[] = array('On Sale Period',  date('d F, Y', strtotime($start_date_time)));
        $csv_data[] = array('Report printed on', date('d F, Y H:i:s'));
        $csv_data[] = array('');
        $csv_data[] = array('');
        $csv_data[] = array('Position', 'Tax','Amount','Single Price', 'Sum');
        $csv_data[] = array('Base Price', '7%', $total_no_of_orders,  $currency.$price, $currency.$total);
        $csv_data[] = array('Presale Fee', '7%', $total_no_of_orders, $currency. (10 / 100) * $price, $currency.$total_no_of_orders*((10 / 100) * $price));
        $csv_data[] = array('System Fee', '7%', $total_no_of_orders, $currency.$system_fee, $currency.$total_no_of_orders* $system_fee);
        $csv_data[] = array('Marketing Fee', '7%', $total_no_of_orders, $currency.$marketing_fee, $currency.$total_no_of_orders* $marketing_fee);
        $csv_data[] = array(' ', ' ', ' ', 'Sum', $currency.$total_sum);
        $csv_data[] = array(' ', ' ', ' ', 'inkl. 7% Tax', $currency.(number_format($total_sum+($total_sum/1.07), 3)),' (in case of 7% Tax)');

        
        $filename = 'orders-' . $product_id . '-' . date('YmdHis') . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');
        $output = fopen('php://output', 'w');
        foreach ($csv_data as $row) {
            fputcsv($output, $row);
        }
        fclose($output);
        exit;
    }


    function show_ticket_to_print( $order ){  ?>
        <div class="order_data_column">
            <h4><?php _e( 'Extra Details' ); ?></h4>
            <?php 
                echo '<p><strong>' . __( 'View Ticket' ) . ':</strong><a href="' .site_url( ).'/ticket/'.get_post_meta( $order->id, 'ticket_order_number', true ) . '" > Click Here </a></p>';
            ?>
        </div>
    <?php }
    
}
