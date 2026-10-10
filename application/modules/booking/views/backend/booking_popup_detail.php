<?php
$is_admin = (SessionManager::getData("manager") == GroupAccess::ADMIN_ACCESS || SessionManager::getData("manager") == 1 || GroupAccess::isGranted('user', MANAGE_USERS));
if ($is_admin):
    $clientDataPopup = $this->mUserModel->getUserData($reservation['user_id']);
?>
    <div style="margin-bottom: 10px; padding: 8px 12px; background: #eef7ff; border-left: 4px solid #3c8dbc; border-radius: 4px; font-size: 13px;">
        <strong><i class="fa fa-user"></i> <?= _lang("Booked By") ?>:</strong> 
        <?= !empty($clientDataPopup['name']) ? htmlspecialchars($clientDataPopup['name']) : (!empty($clientDataPopup['username']) ? htmlspecialchars($clientDataPopup['username']) : _lang("Guest")) ?>
        <?php if (!empty($clientDataPopup['email'])): ?>
            <span class="text-muted">(<?= htmlspecialchars($clientDataPopup['email']) ?>)</span>
        <?php endif; ?>
    </div>
<?php endif; ?>

<strong class="uppercase"><?=_lang("Booking Item(s)")?></strong><br/>

<?php

$cart = json_decode($reservation['cart'], JSON_OBJECT_AS_ARRAY);
$sub_total = 0;
$currency = DEFAULT_CURRENCY;


?>

<?php if (count($cart) > 0): ?>
    <table class="table">
    <?php foreach ($cart as $item): ?>
            <tr>
                <td>
                <?php


                    if (empty($item))
                        continue;

                    $callback = NSModuleLinkers::find($item['module'], 'getData');

                    if ($callback != NULL) {

                        $params = array(
                            'id' => $item['module_id']
                        );

                        $result = call_user_func($callback, $params);

                        echo $result['label']." x ".$item['qty'];

                        if (isset($item['options']))
                            echo BookingHelper::optionsBuilderString($item['options']);

                    }


                    ?>
                </td>
                <td align="right" valign="top">
                <?php
                    echo Currency::parseCurrencyFormat($item['amount'] * $item['qty'], DEFAULT_CURRENCY);
                    $sub_total = $sub_total + $item['amount'] * $item['qty'];
                    ?>
                </td>

            </tr>

    <?php endforeach; ?>

        <tr>
            <td>
                <strong><?=_lang("Total")?></strong>
            </td>
            <td align="right" valign="top">
                <?=Currency::parseCurrencyFormat($sub_total, DEFAULT_CURRENCY)?>
            </td>
        </tr>


        <tr>
            <td colspan="2">
                <strong><?=_lang("Booking Payment")?>: </strong>
            <?php

                $invoice = $this->mBookingModel->getInvoice($reservation['id']);

                $pcode = $reservation['payment_status'];
                $payments = Booking_payment::PAYMENT_STATUS;
                if (isset($payments[$pcode])) {
                    if($pcode == "cod"){
                        echo "<span class='badge' style='background-color: " . $payments[$pcode]['color'] . "'>"._lang("Cash payment")."</span><br>";
                    }else if($pcode == "cod_paid"){
                        echo "<span class='badge' style='background-color: " . $payments[$pcode]['color'] . "'>"._lang("Paid with cash")."</span><br>";
                    }else{
                        $inv_method = ($invoice != NULL && !empty($invoice->method)) ? $invoice->method : "";
                        echo "<span class='badge' style='background-color: " . $payments[$pcode]['color'] . "'>" . ucfirst(_lang($payments[$pcode]['label'])) .( ($inv_method != "" && $pcode=="paid")?" (".$inv_method.") ":""). "</span><br>";
                    }
                } else if ($pcode == "cod_paid") {
                    echo "<span class='badge bg-green'>" . ucfirst(_lang("Paid with cash")) . "</span><br>";
                }

                if($pcode=="refunded" && $invoice != NULL){
                    $transaction = $this->mPaymentModel->getRefundTransaction($invoice->id);
                    if(isset($transaction) && $transaction!=NULL)
                        echo "<small>"._lang_f("Refunded to wallet: %s",[$transaction->transaction_id])."</small>";
                }

                ?>
            </td>
        </tr>


    </table>
<?php else: ?>

    <tr>
        <td><?= _lang("No services") ?></td>
    </tr>

<?php endif; ?>





<strong class="uppercase"><?=_lang("Booking Details")?></strong><br/>

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
        foreach ($fields as $k => $field) {
            if (!isset($field['label']) || !isset($cf_data[$field['label']])) {
                continue;
            }

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

?>