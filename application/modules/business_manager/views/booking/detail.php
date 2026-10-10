<?php



?>


    <div id="app" class="framework7-root">
        <div id="create-business" class="page-content no-padding-top" data-item="">
            <!--<a href="#" class="link back close-button">
                <i class="mdi mdi-close"></i>
            </a>-->
            <div class="block">
                <div class="form-container">

                    <div class="list no-hairlines custom-form">

                        <div class="step-1-form">

                            <h1 class="create-title"><?= _lang("Booking") ?> <?="#" . str_pad($reservation['id'], 6, 0, STR_PAD_LEFT)?></h1>



                            <div class="row">


                                <div class="col-100">
                                    <label class="title"><?= _lang("Booking date") ?></label><br/>
                                     <?= date("D M Y h:i:s A", strtotime($reservation['updated_at'])) ?><br>
                                </div>

                                <div class="col-100">
                                    <div class="ostatus row padding-bottom">
                                        <div class="col-30">

                                            <label class="title"><?= _lang("Status") ?></label><br>

                                        <?php

                                            if (isset($reservation['status']) && $reservation['status'] != "") {
                                                $statusParser = explode(";", $reservation['status']);
                                                echo "<strong style='color:" . $statusParser[1] . "'>" . $statusParser[0] . "</strong>";
                                            }

                                            ?>
                                        </div>

                                    </div>
                                </div>


                                <div class="col-100">

                                    <label class="title"><?= _lang("Client Information") ?></label><br/>


                                <?php

                                    $cf_id = intval($reservation['cf_id'] ?? 0);
                                    $cf_data = is_array($reservation['cf_data']) ? $reservation['cf_data'] : json_decode($reservation['cf_data'] ?? '', JSON_OBJECT_AS_ARRAY);

                                    if (is_array($cf_data) && !empty($cf_data)) {
                                        $fields = array();
                                        if ($cf_id > 0) {
                                            $cf_object = CFManagerHelper::getByID($cf_id);
                                            if (!empty($cf_object) && !empty($cf_object['fields'])) {
                                                $fields = json_decode($cf_object['fields'], JSON_OBJECT_AS_ARRAY);
                                            }
                                        }

                                        if (is_array($fields) && !empty($fields)) {
                                            foreach ($fields as $key => $field) {
                                                if (!isset($field['label']) || !isset($cf_data[$field['label']])) continue;
                                                $data = $cf_data[$field['label']];
                                                if ($data == "") $data = "--";

                                                if (isset($field['type']) && $field['type'] == "input.location") {
                                                    if (preg_match("#;#", $data)) {
                                                        $l = explode(";", $data);
                                                        echo "<span><strong>" . htmlspecialchars($field['label']) . "</strong>: <a class='loc-detail' href='#' data-address='" . htmlspecialchars($l[0]) . "' data-lat='" . htmlspecialchars($l[1] ?? '') . "' data-lng='" . htmlspecialchars($l[2] ?? '') . "'><i class='mdi mdi-map-marker'></i>&nbsp;&nbsp;" . htmlspecialchars($l[0]) . "</a> </span><br>";
                                                    } else {
                                                        echo "<span><strong>" . htmlspecialchars($field['label']) . "</strong>: " . htmlspecialchars($data) . " </span><br>";
                                                    }
                                                } else {
                                                    echo "<span><strong>" . htmlspecialchars($field['label']) . "</strong>: " . htmlspecialchars($data) . "</span><br>";
                                                }
                                            }
                                        } else {
                                            foreach ($cf_data as $label => $data) {
                                                if (is_array($data)) $data = json_encode($data);
                                                if ($data == "") $data = "--";
                                                echo "<span><strong>" . htmlspecialchars($label) . "</strong>: " . htmlspecialchars($data) . "</span><br>";
                                            }
                                        }
                                    }


                                    echo "<span><strong>"._lang("Username")."</strong>: ".$this->mUserModel->getFieldById("username", $reservation['user_id'])."</span><br>";

                                    ?>
                                </div>


                            </div>


                            <div class="row">
                                <div class="col-100" style="margin-top: 20px">
                                <div class="col-xs-12 table-responsive">
                                    <table class="table table-striped">
                                        <tbody>
                                        <tr style="text-transform: uppercase">
                                            <td style="color: #0c0c0c;font-weight: bold"><?= _lang("Service(s)") ?></td>
                                        </tr>


                                    <?php
                                        $cart = json_decode($reservation['cart'], JSON_OBJECT_AS_ARRAY);


                                        foreach ($cart as $key => $item){
                                            if(!empty($item)){
                                                $cart[$key] = $item;
                                            }else{
                                                unset($cart[$key]);
                                            }
                                        }



                                        $sub_total = 0;
                                        $currency = "USD";

                                        ?>

                                    <?php if(count($cart)>0): ?>

                                        <?php foreach ($cart as $item): ?>
                                                <tr>
                                                    <td>
                                                    <?php

                                                        if(empty($item))
                                                            continue;


                                                        $callback = NSModuleLinkers::find($item['module'], 'getData');

                                                        if ($callback != NULL) {

                                                            $params = array(
                                                                'id' => $item['module_id']
                                                            );

                                                            $result = call_user_func($callback, $params);

                                                            echo $result['label'];

                                                            if (isset($item['options']))
                                                                echo BookingHelper::optionsBuilderString($item['options']);

                                                        }


                                                        ?>
                                                    </td>

                                                </tr>
                                        <?php endforeach; ?>

                                    <?php else: ?>

                                            <tr>
                                                <td><?= _lang("No services") ?></td>
                                            </tr>

                                    <?php endif; ?>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="clearfix" style="margin-bottom: 20px;"></div>

                                <div class="col-sm-4">
                                </div>


                                <div class="col-sm-4">
                                </div>


                            </div>
                            </div>



                            <div class="row">

                                <div class="col-100">
                                    <br>
                                </div>

                                <div class="col-50">
                                    <a class="big-button button" id="cancel" href="<?=business_manager_url_admin("businesses?active=booking")?>"> <i
                                                class="mdi <?=Translate::getDir()=="rtl"?"mdi-arrow-right":"mdi-arrow-left"?>"></i>&nbsp;<?= _lang("Back") ?></a>
                                </div>
                                <div class="col-50">
                                    <a class="big-button button button-fill" id="edit"><i
                                                class="mdi mdi-pencil"></i>&nbsp;&nbsp;<?= _lang("Edit") ?></a>
                                </div>
                            </div>


                        </div>


                    </div>

                </div>
            </div>
        </div>
    </div>


<?php

$data['reservation'] = $reservation;
$script = $this->load->view('business_manager/booking/scripts/detail-script', $data, TRUE);
AdminTemplateManager::addScript($script);


?>