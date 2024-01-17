<?php
    $all_options = get_exopite_sof_option($this->plugin_name);
    $tour_name = get_post_meta( $variation_id, 'tour_name', true );
    $main_act_name = get_post_meta( $variation_id, 'main_act_name', true );
    $found_key = array_search($main_act_name, array_column($all_options, 'main_act_name'));
    $logo = $all_options['main_act_options'][$found_key]['main_act_image'];
    
    $start_date_time = get_post_meta( $variation_id, 'start_date_time', true );
    $end_date_time = get_post_meta( $variation_id, 'end_date_time', true );
    $door_close = get_post_meta( $variation_id, 'door_close', true );

    $tour_promotor = get_post_meta( $variation_id, 'tour_promotor', true );
    $venue_name = get_post_meta( $variation_id, 'venue_name', true );
    $street_address = get_post_meta( $variation_id, 'street_address', true );
    $zip_code = get_post_meta( $variation_id, 'zip_code', true );
    $city = get_post_meta( $variation_id, 'city', true );
    $local_promoter = get_post_meta( $variation_id, 'nlocal_promoter', true );
    $item_total = intval( $item_data['total'] );
    $total += $variation->get_price(); 


	$regular_price = get_post_meta( $variation_id, '_regular_price', true );
	$presale_fee = get_post_meta( $variation_id, 'presale_fee', true );       
	$presale_fee_after_discount = ($presale_fee / 100) * $regular_price; 
	$system_fee = get_post_meta( $variation_id, 'system_fee', true );
	$marketing_fee = get_post_meta( $variation_id, 'marketing_fee', true );
	$total = $regular_price + $presale_fee_after_discount + $system_fee + $marketing_fee;

    

    ?>
    <page size="A4" layout="landscape" id="print_ticket">
        <div class="textLayer" style="padding: 5% 5% 0 5%;">
            <div style="display: table; width:100%">
                <div style="display: table-cell; vertical-align: top; width: 70%;">
                    <div class="leftpanel" style="display: table;">
                        <span dir="ltr"
                            style="display: table-cell; vertical-align: middle; font-family: sans-serif; font-size: 36px; color: black;font-weight: 600;">
                            <?php echo $main_act_name;?>
                        </span>
                    </div>
                    <div class="leftpanel2">
                        <span
                            style="font-family: sans-serif; padding: 20px 0 0 0; font-size: 20px; color: black; display: inline-block;">
                            <?php echo $tour_name;?>
                        </span>
                    </div>
                </div>
                <div style="display: table-cell; vertical-align: top; width: 30%;">
                    <img src="<?php echo $logo;?>" height="200" />
                </div>
            </div>
        </div>
        <div class="divider" style="height: 5px;background-color: black; margin: 10px 5% 0 5%;"></div>
        <div class="contentpanel" style="padding: 5%;">
            <div class="leftpanel3" style="width: 100%; display: table;">
                <div class="leftpanel3" style="width: 55%; display: table-cell; vertical-align: top;">
                    <div class="leftpanel3"
                        style="display: table; padding-bottom: 30px; border-bottom: 3px solid #898888;">
                        <div class="leftcell" style="width: 70%; display: table-cell; vertical-align: middle;">
                            <span
                                style="display: block; font-family: sans-serif; font-size: 28px; color: black;font-weight: 600;">
                                <?php echo date('F j, Y, g:i a', strtotime($start_date_time));?>
                            </span>
                            <span
                                style="display: block; font-family: sans-serif; font-size: 18px; color: black; padding: 10px 0 0 0;">
                                <b>Doors: </b>
                                <?php echo date('g:i a', strtotime($door_close));?>
                            </span>
                        </div>
                    </div>
                    <div class="leftpanel3" style="display: table; padding-bottom: 30px;">
                        <div class="leftcell"
                            style="width: 75%; display: table-cell; vertical-align: middle; padding: 30px 0;">
                            <span
                                style="display: block; font-family: sans-serif; font-size: 28px; color: black;font-weight: 600;">
                                <?php echo $venue_name;?>
                            </span>
                            <span
                                style="display: block; font-family: sans-serif; font-size: 18px; color: black; padding: 10px 0 0 0;">
                                <?php echo $street_address.', '. $zip_code . ', '. $city;?>
                            </span>
                            <span
                                style="display: block; font-family: sans-serif; font-size: 14px; color: black; padding: 20px 0 0 0;">
                                <b>Local Promoter: </b><?php echo $local_promoter;?>
                            </span>
                            <span
                                style="display: block; font-family: sans-serif; font-size: 14px; color: black; padding: 20px 0 0 0;">
                                <b>Tour Promoter: </b><?php echo $tour_promotor;?>
                            </span>
                        </div>
                    </div>
                    <div class="leftpanel3" style="display: table; padding: 30px 0;">
                        <div class="leftcell" style="width: 50%; display: table-cell; vertical-align: middle;">
                            <span
                                style="display: block; font-family: sans-serif; font-size: 28px; color: black;font-weight: 600;">
                                <?php echo wc_price($total);?>
                            </span>
                            <span
                                style="display: block; font-family: sans-serif; font-size: 14px; color: black; padding: 20px 0 0 0;">
                                (inkl. Steuern und Gebühren)
                            </span>
                        </div>
                    </div>
                </div>
                <div class="leftpanel3" style="width: 45%; display: table-cell; vertical-align: top;">
                    <!-- <div class="thumbnail-img" style="width: 100%;">
                        <img alt="" src="thumbnail.png" style="width: 100%;">
                    </div> -->
                    <span style="display: block; font-family: sans-serif; font-size: 16px; color: orangered;">
                        <?php
                            //https://phpqrcode.sourceforge.net/examples/index.php?example=021
                            $tempDir = 'qrcodes';
                            $file_name = $order_id.'img.png';
                            $dir_path = plugin_dir_path( dirname(__FILE__ ,2 )).'phpqrcode/qrcodes/'.$file_name;
                            $dir_url = plugin_dir_url( dirname(__FILE__ ,2 )).'phpqrcode/qrcodes/'.$file_name;
                            echo 
                            // generating
                            QRcode::png(get_site_url().'/ticket/sample/'.$variation_id, $dir_path, QR_ECLEVEL_L, 3);
                           
                            // displaying
                            echo '<img src="'.$dir_url.'" />';
                        ?>
                        <?php //echo '<img src="'.QRcode::png('PHP QR Code :)').'" />';?>
                    </span>
                    <!-- <div style="display: table;">
                        <div class=""
                            style="display: table-cell; vertical-align: top; width: 65%; padding: 20px 10px 0 0;">
                            <span
                                style="display: block; font-family: sans-serif; font-size: 26px; color: black;font-weight: 600;">
                                DEMO-4711, 1/1
                            </span>
                            <span
                                style="display: block; font-family: sans-serif; font-size: 16px; color: black; padding: 10px 0 0 0;">
                                11212121212121212, (1)
                            </span>
                            <span
                                style="display: block; font-family: sans-serif; font-size: 16px; color: black; padding: 20px 0 0 0; word-spacing: 3px;">
                                Bestellt von Christoph bierschenk am
                                2023-04-13, 2023-04-13 00:00:00
                                +0200
                            </span>
                            <span
                                style="display: block; font-family: sans-serif; font-size: 26px; color: black;font-weight: 600; padding: 20px 0 0 0;">
                                DEMO12DEMO
                            </span>
                        </div>
                        <div class=""
                            style="display: table-cell; vertical-align: top; width: 35%; padding: 20px 0 0 0;">
                            <img alt="" src="screen.png" style="width: 100%;">
                        </div>
                    </div> -->
                </div>
            </div>
            <div style="background-color: black; height: 5px; width: 100%; margin:30px 0 100px 0;"></div>
        </div>
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

