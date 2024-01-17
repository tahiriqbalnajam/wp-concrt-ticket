<?php

$all_options = get_exopite_sof_option($this->plugin_name);
$order_id = $order->get_id();
$order = wc_get_order( $order_id );


$stop = 0;
?>
<page size="A4" layout="landscape" id="print_ticket">
<?php 
foreach ( $order->get_items() as $item_key => $item ) {
    $counter = 1;
    $quantity = $item->get_quantity();
    $item_data = $item->get_data();
    $product_id = $item_data['product_id'];
    $encp_product_id = explode('-', $this->encrypt($product_id))[0];
    $product = wc_get_product( $product_id );
    
    if ($product->is_type('ticket')) {
        $variation_id = $item_data['variation_id'];
        get_post_meta( $variation_id, 'unique_order_id', true );
        // $variation = new WC_Product_Variation($variation_id);
        // $variation_attributes = $variation->get_variation_attributes();
        // print_r($variation_attributes);
        // echo $main_act_name = variation_attributes['main_act_name'];
        $tour_name = get_post_meta( $variation_id, 'tour_name', true );
        $main_act_name = get_post_meta( $variation_id, 'main_act_name', true );
        $found_key = array_search($main_act_name, array_column($all_options, 'main_act_name'));
        $logo = $all_options['main_act_options'][$found_key]['main_act_image'];
        
        $start_date_time = get_post_meta( $variation_id, 'start_date_time', true );
        $end_date_time = get_post_meta( $variation_id, 'end_date_time', true );
        $door_close = get_post_meta( $variation_id, 'door_close', true );

        $tour_promotor = get_post_meta( $variation_id, 'tour_promotor', true );
        $local_promoter = get_post_meta( $variation_id, 'nlocal_promoter', true );
        $venue_name = get_post_meta( $variation_id, 'venue_name', true );
        $street_address = get_post_meta( $variation_id, 'street_address', true );
        $zip_code = get_post_meta( $variation_id, 'zip_code', true );
        $city = get_post_meta( $variation_id, 'city', true );
        
        $item_total = intval( $item_data['total'] );
        $total += $item_total; 

        $regular_price = get_post_meta( $variation_id, '_regular_price', true );
        $presale_fee = get_post_meta( $variation_id, 'presale_fee', true );       
        $presale_fee_after_discount = ($presale_fee / 100) * $regular_price; 
        $system_fee = get_post_meta( $variation_id, 'system_fee', true );
        $marketing_fee = get_post_meta( $variation_id, 'marketing_fee', true );
       
        $total = $regular_price + $presale_fee_after_discount + $system_fee + $marketing_fee;
        $encp_variation_id = explode('-', $this->encrypt($variation_id))[0];
       
        for( $i=1; $i<=$quantity; $i++){ 

            if($order_number == $product_id){
                $order_encrypt_id = $order_encrypt_id; 
                $quantity = $quantity+1;
                ob_start();
                include 'print_view.php';
                $content = ob_get_contents();
                ob_get_clean();
                echo $content;
            }
            else if($ordered_id && !$var_id && !$counter_number){
                $stop++;
                $order_encrypt_id = $order_exp_id .'-'. $encp_variation_id .'-'.  $counter;
                ob_start();
                include 'print_view.php';
                $content = ob_get_contents();
                ob_get_clean();
                echo $content;
                $counter++;
            }
            else if($ordered_id && $var_id && $variation_id == $var_id && !$counter_number){
                if($variation_id == $var_id){
                    $stop++;
                    $order_encrypt_id = $order_exp_id .'-'. $encp_variation_id .'-'. $counter; 
                    ob_start();
                    include 'print_view.php';
                    $content = ob_get_contents();
                    ob_get_clean();
                    echo $content;
                    $counter++;
                }
            }
            else if($ordered_id && $var_id && $counter_number ){

                if( $counter < $counter_number){
                    $counter++;
                }
                else if($variation_id == $var_id && $counter == $counter_number){
                    $stop++;
                    if($counter == $counter_number){
                        $order_encrypt_id = $order_exp_id .'-'. $encp_variation_id .'-'. $counter; 
                        ob_start();
                        include 'print_view.php';
                        $content = ob_get_contents();
                        ob_get_clean();
                        echo $content;
                        $counter++;
                    }
                }
            }
            else if($ordered_id && $var_id && !$counter_number && $variation_id != $var_id){
                $tour_name = get_post_meta( $var_id, 'tour_name', true );
                if($tour_name){
              
                }
                else{
                    $order_encrypt_id = $order_exp_id .'-'. $encp_variation_id .'-'.  $counter;
                    ob_start();
                    include 'print_view.php';
                    $content = ob_get_contents();
                    ob_get_clean();
                    echo $content; 
                    $counter++;
                }
            }
            ?>
        <?php }
    }

}

?>
     </page>   
<input type="button" onclick="printDiv('print_ticket')" value="print Ticket!" />
<script>
function printDiv(divName) {
    let printContents = document.getElementById('print_ticket').innerHTML;
    let originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}
</script>
<style>
.woocommerce-order-details, .woocommerce-customer-details {
    display: none !important;
}
</style>