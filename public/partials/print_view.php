<?php ?>

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
                        <div class="leftpanel2">
                            <span
                                style="font-family: sans-serif; padding: 20px 0 0 0; font-size: 20px; color: black; display: inline-block;">
                                Ticket# <?php echo $order_encrypt_id;?>
                            </span>
                        </div>
                    </div>
                    <div style="display: table-cell; vertical-align: top; width: 30%;">
                        <img src="<?php echo $logo;?>" height="200"/>
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
                                    <b>Local promoter: </b> <?php echo $local_promoter;?>
                                </span>
                                <span
                                    style="display: block; font-family: sans-serif; font-size: 14px; color: black; padding: 20px 0 0 0;">
                                    <b>Tour Promoter: </b> <?php echo $tour_promotor;?>
                                </span>
                            </div>
                        </div>
                        <!-- <div class="leftpanel3"
                            style="display: table; padding-bottom: 30px; border-bottom: 3px solid #898888;">
                            <div class="leftcell" style="width: 55%; display: table-cell; vertical-align: middle;">
                                <span
                                    style="display: block; font-family: sans-serif; font-size: 28px; color: black;font-weight: 600;">
                                    Freie Platzwahl
                                </span>
                            </div>
                            <div class="leftcell" style="width: 45%; display: table-cell; vertical-align: middle;">
                                <span style="display: block; font-family: sans-serif; font-size: 16px; color: orangered;">
                                    category, not applicable
                                </span>
                            </div>
                        </div> -->
                        <div class="leftpanel3" style="display: table; padding: 30px 0;">
                            <div class="leftcell" style="width: 50%; display: table-cell; vertical-align: middle;">
                                <span
                                    style="display: block; font-family: sans-serif; font-size: 28px; color: black;font-weight: 600;">
                                    Gesamtpreis: <?php echo wc_price($total);?>
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
                                $file_name = $order_encrypt_id.'img.png';
                                $dir_path = plugin_dir_path( dirname(__FILE__ ,2 )).'phpqrcode/qrcodes/'.$file_name;
                                $dir_url = plugin_dir_url( dirname(__FILE__ ,2 )).'phpqrcode/qrcodes/'.$file_name;
                                echo 
                                // generating
                                QRcode::png(get_site_url().'/ticket/'.$order_encrypt_id, $dir_path, QR_ECLEVEL_L, 3);
                            
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