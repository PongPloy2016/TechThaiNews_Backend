<?php

$stores = $data[Tags::RESULT];
$pagination = $data['pagination'];

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Main content -->
    <section class="content" style="padding: 25px 20px;">

        <!-- Message Alerts -->
        <div class="row">
            <div class="col-sm-12">
                <?php $this->load->view(AdminPanel::TemplatePath . "/include/messages"); ?>
            </div>
        </div>

        <!-- Custom Styled Page Header -->
        <div class="ns-page-header">
            <div class="ns-page-title">
                <h2>
                    <i class="mdi mdi-storefront" style="font-size: 28px; color: #3b82f6; margin-right: 8px;"></i>
                    <?= _lang($h1_title) ?>
                    <span class="badge" style="background: #e2e8f0; color: #334155; font-size: 13px; border-radius: 12px; padding: 4px 10px; margin-left: 8px;">
                        <?= count($stores) ?>
                    </span>
                </h2>
                <p><?= _lang("Manage and overview all registered stores and their operational statuses.") ?></p>
            </div>

            <div class="ns-actions-group">
                <form method="get" action="<?php echo $paginate_url ?? admin_url("store/all_stores"); ?>" class="ns-search-form">
                    <i class="mdi mdi-magnify ns-search-icon"></i>
                    <input class="form-control ns-search-input" name="search" type="text"
                           placeholder="<?= _lang("Search stores...") ?>"
                           value="<?= Text::output(RequestInput::get("search")) ?>">
                </form>

                <?php if (GroupAccess::isGranted('store', EDIT_STORE)): ?>
                    <a href="<?= admin_url("store/create") ?>" class="btn btn-create-store">
                        <i class="mdi mdi-plus-circle" style="font-size: 18px;"></i>
                        <span><?= _lang("Create New Store") ?></span>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Store Table Card -->
        <div class="ns-card-table">
            <div class="table-responsive">
                <table class="ns-table">
                    <thead>
                        <tr>
                            <th style="width: 80px; text-align: center;"><?= _lang("Image") ?></th>
                            <th><?= _lang("Store Name & Address") ?></th>
                            <th><?= _lang("Owner") ?></th>
                            <th><?= _lang("Category") ?></th>
                            <th style="text-align: center;"><?= _lang("Status") ?></th>
                            <th style="text-align: center;"><?= _lang("Reviews") ?></th>
                            <th style="text-align: right; padding-right: 24px;"><?= _lang("Actions") ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($stores)) { ?>
                        <?php foreach ($stores AS $store) { ?>
                            <?php
                            $token = $this->mUserBrowser->setToken(Text::encrypt($store['id_store']));
                            $image = ImageManagerUtils::parseFirstImages($store['images'], ImageManagerUtils::IMAGE_SIZE_200);
                            ?>
                            <tr class="store_<?= $token ?>">
                                <!-- Image Thumbnail -->
                                <td style="text-align: center;">
                                    <div class="store-thumb-box" style="background-image: url('<?=$image?>');"></div>
                                </td>

                                <!-- Store Name & Address -->
                                <td>
                                    <div>
                                        <span class="store-title"><?= Text::output($store['name']) ?></span>
                                        <?php if (isset($store['featured']) && $store['featured'] == 1): ?>
                                            <span class="badge-featured">
                                                <i class="mdi mdi-star"></i> <?= _lang("Featured") ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="store-address">
                                        <i class="mdi mdi-map-marker" style="color: #ef4444; font-size: 14px;"></i>
                                        <span><?= Text::output($store['address']) ?></span>
                                    </div>

                                    <?php
                                    if (GroupAccess::isGranted('store', MANAGE_STORES) && ModulesChecker::isEnabled("cf_manager")){
                                        $category = $this->mCategoryModel->getByCategoryByID($store['category_id']);
                                        if(isset($category['cf_id']) && $category['cf_id'] == 0){
                                            echo "<div style='margin-top: 4px;'><a href='".admin_url("store/cf_categories_edit?id=".$store['category_id'])."' style='color: #f59e0b; font-size: 12px;'><i class='mdi mdi-alert-circle-outline'></i> "._lang("Link with checkout field")."</a></div>";
                                        } else if(isset($category['cf_id'])) {
                                            $cf = $this->mCFManager->getCF($category['cf_id']);
                                            if($cf != NULL)
                                            echo "<div style='margin-top: 4px;'><span style='color: #10b981; font-size: 12px;'><i class='mdi mdi-attachment'></i> ".Translate::sprintf("Linked checkout (%s)", array($cf['label']))."</span></div>";
                                        }
                                    }
                                    ?>
                                </td>

                                <!-- Owner -->
                                <td>
                                    <div style="display: flex; align-items: center; gap: 6px;">
                                        <a href="<?= empty($status) ? admin_url("store/all_stores?owner_id=" . $store['user_id']) : admin_url("store/stores?owner_id=" . $store['user_id']) ?>" style="font-weight: 600; color: #3b82f6;">
                                            <?= ucfirst($this->mUserModel->getUserNameById($store['user_id'])) ?>
                                        </a>

                                        <?php if (GroupAccess::isGranted("user", MANAGE_USERS)): ?>
                                            <a target="_blank" href="<?= admin_url("user/edit?id=" . $store['user_id']) ?>" style="color: #94a3b8;" data-toggle="tooltip" title="<?= _lang("Edit User") ?>">
                                                <i class="mdi mdi-open-in-new"></i>
                                            </a>
                                            <a data-toggle="tooltip" title="<?=_lang("Shadowing")?>" href="<?= admin_url("user/shadowing?id=" . $store['user_id']) ?>" style="color: #94a3b8;">
                                                <i class="mdi mdi-eye-outline"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <!-- Category -->
                                <td>
                                    <a href="<?= empty($status) ? admin_url("store/all_stores?category_id=" . $store['category_id']) : admin_url("store/stores?category_id=" . $store['category_id']) ?>" class="badge-category">
                                        <?= _lang(Text::output($this->mStoreModel->getCatName($store['category_id']))) ?>
                                    </a>
                                </td>

                                <!-- Status -->
                                <td style="text-align: center;">
                                    <?php
                                    if ($store['status'] == 1) {
                                        echo '<span class="badge-status-enabled">' . _lang("Enabled") . '</span>';
                                    } else if ($store['status'] == 0) {
                                        echo '<span class="badge-status-disabled">' . _lang("Disabled") . '</span>';
                                    } else if ($store['status'] == -1) {
                                        echo '<span class="badge-status-disabled">' . _lang("Blocked") . '</span>';
                                    }
                                    ?>
                                </td>

                                <!-- Reviews -->
                                <td style="text-align: center;">
                                    <div style="font-weight: 700; color: #f59e0b; font-size: 14px;">
                                        <?= parseToRatingStars(round($store['votes'], 2), "font-size14px") ?>
                                        <span style="font-size: 12px; color: #64748b;">(<?= round($store['votes'], 2) ?>)</span>
                                    </div>
                                    <a href="<?= admin_url("store/reviews?id=" . $store['id_store']) ?>" style="font-size: 12px; color: #64748b; text-decoration: underline;">
                                        <?= _lang("Reviews") ?> (<?=$store['nbr_votes']?>)
                                    </a>
                                </td>

                                <!-- Actions -->
                                <td style="text-align: right; padding-right: 24px;">
                                    <div style="display: inline-flex; align-items: center;">
                                        <?php if (GroupAccess::isGranted('store', MANAGE_STORES)): ?>
                                            <?php if ($store['verified'] == 1): ?>
                                                <?php if ($store['status'] == 1): ?>
                                                    <a href="<?= admin_url("store/status?id=" . $store['id_store']) ?>" class="linkAccess ns-action-btn" data-toggle="tooltip" title="<?= _lang("Disable Store") ?>" onclick="return false;">
                                                        <i class="fa fa-check" style="color: #22c55e;"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <a href="<?= admin_url("store/status?id=" . $store['id_store']) ?>" class="linkAccess ns-action-btn" data-toggle="tooltip" title="<?= _lang("Enable Store") ?>" onclick="return false;">
                                                        <i class="fa fa-times" style="color: #ef4444;"></i>
                                                    </a>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <a href="<?= admin_url("store/verify?id=" . $store['id_store']) ?>&accept=1" class="linkAccess ns-action-btn verify-confirm" data-toggle="tooltip" title="<?= _lang("Confirm") ?>" onclick="return false;">
                                                    <i class="mdi mdi-thumb-up"></i>
                                                </a>
                                                <a href="<?= admin_url("store/verify?id=" . $store['id_store']) ?>&accept=0" class="linkAccess ns-action-btn verify-decline" data-toggle="tooltip" title="<?= _lang("Decline") ?>" onclick="return false;">
                                                    <i class="fa fa-times"></i>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <?php if ($store['user_id'] != $this->mUserBrowser->getData("id_user") && GroupAccess::isGranted('store', EDIT_STORE)) : ?>
                                            <a href="<?= admin_url("store/view?id=" . $store['id_store']) ?>" class="ns-action-btn" data-toggle="tooltip" title="<?= _lang("View Detail") ?>">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        <?php elseif (GroupAccess::isGranted('store', EDIT_STORE)) : ?>
                                            <a href="<?= admin_url("store/edit?id=" . $store['id_store']) ?>" class="ns-action-btn" data-toggle="tooltip" title="<?= _lang("Edit Store") ?>">
                                                <i class="glyphicon glyphicon-edit"></i>
                                            </a>
                                        <?php endif; ?>

                                        <?php if (ModulesChecker::isEnabled("service") && GroupAccess::isGranted('store', EDIT_STORE)): ?>
                                            <a href="<?= $store['user_id']==SessionManager::getData('id_user')?admin_url("store/edit?id=" . $store['id_store']."&tab=service"):admin_url("store/view?id=" . $store['id_store']."&tab=service") ?>" class="ns-action-btn" data-toggle="tooltip" title="<?= _lang("Services") ?>">
                                                <i class="glyphicon glyphicon-list"></i>
                                            </a>
                                        <?php endif; ?>

                                        <?php if (GroupAccess::isGranted('store', DELETE_STORE)): ?>
                                            <a href="#" class="delete ns-action-btn" data-id="<?= $store['id_store'] ?>" data-toggle="tooltip" title="<?= _lang("Delete") ?>">
                                                <i class="glyphicon glyphicon-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="7" align="center" style="padding: 40px; color: #94a3b8;">
                                <i class="mdi mdi-store-off-outline" style="font-size: 48px; display: block; margin-bottom: 10px;"></i>
                                <span style="font-size: 15px; font-weight: 500;"><?= _lang("No stores found", "") ?></span>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Container -->
            <div style="padding: 16px 24px; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                <div style="color: #64748b; font-size: 13px;">
                    <?= _lang("Showing results") ?>
                </div>
                <div class="dataTables_paginate paging_simple_numbers">
                    <?php
                    echo $pagination->links(array(
                        "status" => intval(RequestInput::get("status")),
                        "search" => RequestInput::get("search"),
                        "category_id" => intval(RequestInput::get("category_id")),
                        "owner_id" => intval(RequestInput::get("owner_id")),
                    ), current_url());
                    ?>
                </div>
            </div>
        </div>

    </section>
</div>

<!-- Custom CSS Rules -->
<style>
.ns-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 25px;
    background: #ffffff;
    padding: 20px 24px;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    border: 1px solid rgba(226,232,240,0.8);
}
.ns-page-title h2 {
    margin: 0;
    font-size: 22px;
    font-weight: 700;
    color: #0f172a;
    display: flex;
    align-items: center;
}
.ns-page-title p {
    margin: 4px 0 0 0;
    color: #64748b;
    font-size: 13px;
}
.ns-actions-group {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}
.ns-search-form {
    position: relative;
    margin: 0;
}
.ns-search-input {
    padding: 9px 16px 9px 38px !important;
    border-radius: 10px !important;
    border: 1px solid #cbd5e1 !important;
    font-size: 13.5px !important;
    width: 240px !important;
    transition: all 0.2s ease;
    background: #f8fafc;
    height: auto !important;
}
.ns-search-input:focus {
    background: #fff;
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.15) !important;
}
.ns-search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 18px;
    pointer-events: none;
}
.btn-create-store {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
    color: #fff !important;
    font-weight: 600 !important;
    padding: 9px 18px !important;
    border-radius: 10px !important;
    border: none !important;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25) !important;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.25s ease !important;
    text-decoration: none !important;
}
.btn-create-store:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(37, 99, 235, 0.35) !important;
    color: #fff !important;
}
.ns-card-table {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 4px 25px rgba(0,0,0,0.03);
    border: 1px solid rgba(226, 232, 240, 0.8);
    overflow: hidden;
}
.ns-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin: 0;
}
.ns-table thead th {
    background: #f8fafc;
    color: #475569;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 16px 18px;
    border-bottom: 1px solid #e2e8f0;
}
.ns-table tbody tr {
    transition: all 0.2s ease;
}
.ns-table tbody tr:hover {
    background-color: #f8fafc;
}
.ns-table tbody td {
    padding: 16px 18px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
    color: #334155;
    font-size: 14px;
}
.store-thumb-box {
    width: 54px;
    height: 54px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    background-size: cover;
    background-position: center;
    border: 2px solid #fff;
    margin: 0 auto;
    transition: transform 0.25s ease;
}
.ns-table tbody tr:hover .store-thumb-box {
    transform: scale(1.08);
}
.store-title {
    font-weight: 700;
    font-size: 15px;
    color: #0f172a;
    display: inline-block;
}
.store-address {
    color: #64748b;
    font-size: 12px;
    margin-top: 3px;
    display: flex;
    align-items: center;
    gap: 4px;
}
.badge-featured {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    margin-left: 6px;
    display: inline-flex;
    align-items: center;
    gap: 3px;
}
.badge-status-enabled {
    background: rgba(34, 197, 94, 0.12);
    color: #15803d;
    font-weight: 600;
    font-size: 12px;
    padding: 5px 12px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.badge-status-enabled::before {
    content: '';
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #22c55e;
}
.badge-status-disabled {
    background: rgba(239, 68, 68, 0.12);
    color: #b91c1c;
    font-weight: 600;
    font-size: 12px;
    padding: 5px 12px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.badge-status-disabled::before {
    content: '';
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #ef4444;
}
.badge-category {
    background: #f1f5f9;
    color: #475569;
    font-weight: 600;
    font-size: 12.5px;
    padding: 5px 12px;
    border-radius: 8px;
    display: inline-block;
    transition: all 0.2s;
    text-decoration: none !important;
}
.badge-category:hover {
    background: #e2e8f0;
    color: #1e293b;
}
.ns-action-btn {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
    background: #f1f5f9;
    border: none;
    transition: all 0.2s ease;
    margin-left: 4px;
    text-decoration: none !important;
}
.ns-action-btn:hover {
    background: #3b82f6;
    color: #fff !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3);
}
.ns-action-btn.delete:hover {
    background: #ef4444;
    color: #fff !important;
    box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);
}
.ns-action-btn.verify-confirm {
    background: rgba(34, 197, 94, 0.15);
    color: #15803d;
}
.ns-action-btn.verify-confirm:hover {
    background: #22c55e;
    color: #fff !important;
}
.ns-action-btn.verify-decline {
    background: rgba(239, 68, 68, 0.15);
    color: #b91c1c;
}
.ns-action-btn.verify-decline:hover {
    background: #ef4444;
    color: #fff !important;
}
</style>

<?php

$script = $this->load->view('store/backend/html/scripts/stores-script', NULL, TRUE);
AdminTemplateManager::addScript($script);

?>
