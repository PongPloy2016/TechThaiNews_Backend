<?php
$reservations = $data[Tags::RESULT];
$pagination = $data['pagination'];


?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- Message Error -->
            <div class="col-sm-12">
            <?php $this->load->view(AdminPanel::TemplatePath . "/include/messages"); ?>
            </div>

        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="box box-solid">
                    <div class="box-header" style="width : 100%;">
                        <div class=" row ">
                            <div class="pull-left col-md-6 box-title">
                                <b><?= Translate::sprint("My bookings") ?></b>
                            </div>
                            <div class="pull-right col-md-6 text-right">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#modal-create-booking">
                                    <i class="fa fa-plus-circle"></i> <?= _lang("Create New Booking") ?>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <div class="row">
                            <div class="list_general">
                            <?php if (!empty($reservations)) : ?>
                                <ul>
                                <?php foreach ($reservations as $key => $reservation): ?>
                                        <?php

                                            $storeData = $this->mStoreModel->getStoreData($reservation['store_id']);
                                            $ownerData = $this->mUserModel->getUserData($storeData['user_id']);
                                            $clientData = $this->mUserModel->getUserData($reservation['user_id']);
                                            $image = ImageManagerUtils::getFirstImage($storeData['images'], ImageManagerUtils::IMAGE_SIZE_200);

                                        ?>
                                            <li>
                                                <figure><img src="<?= $image ?>" alt=""></figure>
                                                <h4>
                                                    <?= $reservation['store_name'] ?>
                                                    #<?= str_pad($reservation['id'], 7, "0", STR_PAD_LEFT) ?>
                                                </h4>
                                                <div>
                                                    <?php

                                                    if (isset($reservation['status']) && $reservation['status'] != "") {
                                                        $statusParser = explode(";", $reservation['status']);
                                                        echo "<strong style='color:" . $statusParser[1] . "'>" . $statusParser[0] . "</strong>";
                                                    }

                                                    ?>
                                                </div>
                                                <ul class="booking_list">
                                                    <li><strong><?= _lang("Booking ID") ?>:</strong>
                                                        #<?= $reservation['id'] ?></li>
                                                    <li><strong><?= _lang("Booking date") ?>
                                                            :</strong> <?= date("d M Y", strtotime($reservation['created_at'])) ?>
                                                    </li>
                                                <?php
                                                    $is_admin = (SessionManager::getData("manager") == GroupAccess::ADMIN_ACCESS || SessionManager::getData("manager") == 1 || GroupAccess::isGranted('user', MANAGE_USERS));
                                                    if ($is_admin) :
                                                ?>
                                                    <li><strong><i class="fa fa-user"></i> <?= _lang("Booked By") ?>:</strong>
                                                        <span class="label label-primary" style="font-size: 11px;">
                                                            <?= !empty($clientData['name']) ? htmlspecialchars($clientData['name']) : (!empty($clientData['username']) ? htmlspecialchars($clientData['username']) : _lang("Guest")) ?>
                                                        </span>
                                                        <?php if (!empty($clientData['email'])): ?>
                                                            <small class="text-muted">(<?= htmlspecialchars($clientData['email']) ?>)</small>
                                                        <?php endif; ?>
                                                    </li>
                                                <?php endif; ?>
                                                    <li><strong><?= _lang("Booking details") ?>:</strong> <a
                                                                class="bookingDetail" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="bookingDetailed-<?= $reservation['id'] ?>"
                                                                href="#bookingDetailed-<?= $reservation['id'] ?>"><?= _lang("Show Details") ?></a>

                                                        <div class="collapse booking-detailed" id="bookingDetailed-<?= $reservation['id'] ?>">
                                                        <?php
                                                            $this->load->view('booking/backend/booking_popup_detail', array(
                                                                'reservation' => $reservation
                                                            ));
                                                            ?>
                                                        </div>

                                                    </li>
                                                </ul>

                                                <p>
                                                <?php if ($storeData['telephone'] != ""): ?>
                                                        <a href="tel:<?= $storeData['telephone'] ?>"
                                                           class="btn btn-default gray"><i
                                                                    class="fa fa-fw fa-phone"></i> <?= _lang("Call") ?>
                                                        </a>
                                                <?php endif; ?>
                                                <?php if ($storeData['canChat'] == 1): ?>
                                                        <a href="#" data-id="<?= $reservation['id'] ?>"
                                                           class="btn btn-default gray send-message"><i
                                                                    class="fa fa-fw fa-envelope"></i> <?= _lang("Send Message") ?>
                                                        </a>
                                                <?php endif; ?>
                                                </p>
                                            <?php if ($reservation['status_id'] != -1): ?>
                                                    <ul class="buttons">
                                                        <li><a href="#" class="btn btn-default cancel"
                                                               data-id="<?= $reservation['id'] ?>"><i
                                                                        class="fa fa-fw fa-times-circle-o"></i> <?= _lang("Cancel") ?>
                                                            </a></li>
                                                    </ul>
                                            <?php endif; ?>
                                            </li>

                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <div class="result-not-found">
                                    <?=_lang("No booking!")?>
                                </div>
                            <?php endif; ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">

                                <?php
                                    echo $pagination->links(array(
                                        "status" => intval(RequestInput::get("status")),
                                    ), $pagination_url);

                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->


            <!-- /.box -->
        </div>
        <!-- /.col -->
        <!-- /.row -->
</div>


<!-- Modal Create Booking -->
<div class="modal fade" id="modal-create-booking" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #2563eb; color: #fff; border-top-left-radius: 4px; border-top-right-radius: 4px;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.9;"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-weight: 600;"><i class="fa fa-calendar-plus-o"></i> <?= _lang("Create New Booking") ?></h4>
            </div>
            <form id="form-create-booking">
                <div class="modal-body" style="padding: 20px;">
                    <div class="alert alert-danger hidden" id="booking-alert-error"></div>
                    <div class="alert alert-success hidden" id="booking-alert-success"></div>

                    <!-- Store Selector -->
                    <div class="form-group">
                        <label><?= _lang("Select Store") ?> (*):</label>
                        <select class="form-control" id="cb-store-id" name="store_id" required style="height: 40px; font-size: 14px;">
                            <option value=""><?= _lang("-- Select Store --") ?></option>
                            <?php if (!empty($all_stores)): ?>
                                <?php foreach ($all_stores as $sItem): ?>
                                    <option value="<?= $sItem['id_store'] ?>"><?= Text::output($sItem['name']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Service Selector -->
                    <div class="form-group">
                        <label><?= _lang("Select Service / Product") ?> (*):</label>
                        <select class="form-control" id="cb-service-id" name="service_id" required disabled style="height: 40px; font-size: 14px;">
                            <option value=""><?= _lang("-- Select Service --") ?></option>
                        </select>
                    </div>

                    <!-- Quantity & Price Preview -->
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label><?= _lang("Quantity") ?>:</label>
                                <input type="number" class="form-control" id="cb-qty" name="qty" value="1" min="1" required style="height: 40px; font-size: 14px;">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label><?= _lang("Estimated Total Amount") ?>:</label>
                                <input type="text" class="form-control" id="cb-amount-preview" value="0.00 THB" readonly style="height: 40px; font-size: 14px; font-weight: bold; color: #2563eb; background: #f8fafc;">
                            </div>
                        </div>
                    </div>

                    <!-- Payment Status -->
                    <div class="form-group">
                        <label><?= _lang("Payment Method / Status") ?>:</label>
                        <select class="form-control" id="cb-payment-status" name="payment_status" style="height: 40px; font-size: 14px;">
                            <option value="cod"><?= _lang("Cash on delivery / Pay at store (COD)") ?></option>
                            <option value="cod_paid"><?= _lang("Paid with Cash (COD Paid)") ?></option>
                            <option value="paid"><?= _lang("Paid Online / Credit Card") ?></option>
                            <option value="unpaid"><?= _lang("Unpaid") ?></option>
                        </select>
                    </div>

                    <!-- Note / Special Request -->
                    <div class="form-group">
                        <label><?= _lang("Note / Special Request") ?>:</label>
                        <textarea class="form-control" id="cb-note" name="note" rows="3" placeholder="<?= _lang("Enter appointment details, preferred time, or special instructions...") ?>" style="font-size: 14px;"></textarea>
                    </div>
                </div>

                <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="height: 38px; font-weight: 500;"><?= _lang("Cancel") ?></button>
                    <button type="submit" class="btn btn-primary" id="btn-submit-booking" style="height: 38px; font-weight: 600; padding: 0 20px;"><i class="fa fa-check"></i> <?= _lang("Submit Booking") ?></button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php
$script = $this->load->view('booking/backend/scripts/client-bookings-script', NULL, TRUE);
AdminTemplateManager::addScript($script);
?>

<script>
$(document).ready(function() {
    // Store Selection Change -> Load Services
    $('#cb-store-id').on('change', function() {
        var storeId = $(this).val();
        var $srvSelect = $('#cb-service-id');

        $srvSelect.empty().append('<option value=""><?= _lang("-- Loading Services... --") ?></option>').prop('disabled', true);
        $('#cb-amount-preview').val('0.00 THB');

        if (!storeId || storeId <= 0) {
            $srvSelect.html('<option value=""><?= _lang("-- Select Service --") ?></option>');
            return;
        }

        $.ajax({
            url: "<?= site_url("booking/ajax/getServicesByStore") ?>",
            type: "POST",
            dataType: "json",
            data: { store_id: storeId },
            success: function(resp) {
                if (resp && resp.success == 1 && resp.result && resp.result.length > 0) {
                    $srvSelect.empty().append('<option value=""><?= _lang("-- Select Service --") ?></option>');
                    $.each(resp.result, function(idx, item) {
                        var priceFormatted = parseFloat(item.value).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                        $srvSelect.append('<option value="' + item.id + '" data-price="' + item.value + '">' + item.name + ' (' + priceFormatted + ' THB)</option>');
                    });
                    $srvSelect.prop('disabled', false);
                } else {
                    $srvSelect.html('<option value=""><?= _lang("No services available for this store") ?></option>');
                }
            },
            error: function() {
                $srvSelect.html('<option value=""><?= _lang("Error loading services") ?></option>');
            }
        });
    });

    // Calculate Amount Preview
    function updateAmountPreview() {
        var selectedOpt = $('#cb-service-id option:selected');
        var price = parseFloat(selectedOpt.attr('data-price')) || 0;
        var qty = parseInt($('#cb-qty').val()) || 1;
        var total = price * qty;
        var formatted = total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        $('#cb-amount-preview').val(formatted + ' THB');
    }

    $('#cb-service-id, #cb-qty').on('change keyup input', function() {
        updateAmountPreview();
    });

    // Form Submit Booking
    $('#form-create-booking').on('submit', function(e) {
        e.preventDefault();
        var $btn = $('#btn-submit-booking');
        var $errAlert = $('#booking-alert-error');
        var $sucAlert = $('#booking-alert-success');

        $errAlert.addClass('hidden').text('');
        $sucAlert.addClass('hidden').text('');

        var storeId = $('#cb-store-id').val();
        var serviceId = $('#cb-service-id').val();
        var qty = $('#cb-qty').val();
        var paymentStatus = $('#cb-payment-status').val();
        var note = $('#cb-note').val();

        if (!storeId || !serviceId) {
            $errAlert.text('<?= _lang("Please select both a store and a service.") ?>').removeClass('hidden');
            return;
        }

        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> <?= _lang("Creating...") ?>');

        $.ajax({
            url: "<?= site_url("booking/ajax/createBookingSelf") ?>",
            type: "POST",
            dataType: "json",
            data: {
                store_id: storeId,
                service_id: serviceId,
                qty: qty,
                payment_status: paymentStatus,
                note: note
            },
            success: function(resp) {
                if (resp && resp.success == 1) {
                    $sucAlert.html('<i class="fa fa-check-circle"></i> <?= _lang("Booking created successfully! Reloading...") ?>').removeClass('hidden');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    var errMsg = (resp && resp.errors && resp.errors.error) ? resp.errors.error : '<?= _lang("Could not create booking. Please try again.") ?>';
                    $errAlert.text(errMsg).removeClass('hidden');
                    $btn.prop('disabled', false).html('<i class="fa fa-check"></i> <?= _lang("Submit Booking") ?>');
                }
            },
            error: function() {
                $errAlert.text('<?= _lang("Network or server error occurred. Please try again.") ?>').removeClass('hidden');
                $btn.prop('disabled', false).html('<i class="fa fa-check"></i> <?= _lang("Submit Booking") ?>');
            }
        });
    });
});
</script>
