<?php
if (!isset($dev_comp_obj)) {
  // create an object of Database class
  $dev_comp_obj = new ManufuctureCompanies();
}
// get all devices companies data
$manufacture_companies = $dev_comp_obj->get_all_man_companies($_SESSION['company_id']);
?>

<!-- start home stats container -->
<div class="container" dir="<?php echo @$_SESSION['systemLang'] == 'ar' ? 'rtl' : 'ltr' ?>">
  <!-- buttons section -->
  <div class="mb-3 hstack gap-3">
    <?php if ($_SESSION['pcs_add'] == 1) { ?>
    <button type="button" class="btn btn-outline-primary shadow-sm py-1 fs-12" data-bs-toggle="modal" data-bs-target="#addNewDevCompanyModal">
      <i class="bi bi-file-plus"></i>
      <?php echo language("ADD NEW COMPANY", @$_SESSION['systemLang']) ?>
    </button>

    <button type="button" class="btn btn-outline-primary py-1 fs-12" data-bs-toggle="modal" data-bs-target="#addNewDevice">
      <i class="bi bi-plus"></i>
      <?php echo language('ADD NEW DEVICE', @$_SESSION['systemLang']) ?>
      </button>
    <?php } ?>
  </div>

  <?php if ($manufacture_companies != null && count($manufacture_companies) > 0) { ?>
    <!-- start table container -->
    <div class="table-responsive-sm w-100">
      <!-- strst users table -->
      <table class="table table-bordered display compact table-style w-100">
        <thead class="primary text-capitalize">
          <tr>
            <th>#</th>
            <th><?php echo language('COMPANY NAME', @$_SESSION['systemLang']) ?></th>
            <th><?php echo language('NUMBER OF DEVICES', @$_SESSION['systemLang']) ?></th>
            <th><?php echo language('ADDED BY', @$_SESSION['systemLang']) ?></th>
            <th><?php echo language('ADDED DATE', @$_SESSION['systemLang']) ?></th>
            <th><?php echo language('CONTROL', @$_SESSION['systemLang']) ?></th>
          </tr>
        </thead>
        <tbody id="devices-companies">
          <?php foreach ($manufacture_companies as $key => $company) { ?>
            <tr>
              <td><?php echo ++$key ?></td>

              <!-- device`s company name -->
              <td><?php echo $company['man_company_name'] ?></td>

              <!-- total number of devices in this company -->
              <td><?php echo $dev_comp_obj->count_records("`device_id`", "`devices_info`", "WHERE `device_company_id` = " . $company['man_company_id']) ?></td>

              <!-- added by -->
              <td>
                <?php 
                // get username that add company
                $added_by_name =  $dev_comp_obj->select_specific_column("`UserName`", "`users`", "WHERE `UserID` = ". $company['added_by'])[0]['UserName']; 
                // check permission
                if ($_SESSION['user_update'] == 1) { ?> 
                    <a href="<?php echo $nav_up_level ?>users/index.php?do=edit-user-info&userid=<?php echo $company['added_by'] ?>"><?php echo $added_by_name ?></a>
                <?php } else { ?> 
                    <span><?php echo $added_by_name ?></span>
                <?php } ?>
              </td>

              <!-- added date -->
              <td><?php echo $company['added_date'] ?></td>

              <!-- control buttons -->
              <td>
                <!-- edit button -->
                <button type="button" class="btn btn-outline-success py-1 fs-12" data-bs-toggle="modal" data-bs-target="#editDevCompanyModal" data-name="<?php echo $company['man_company_name'] ?>" data-id="<?php echo $company['man_company_id'] ?>" onclick="put_data_into_modal(this, 'edit', 'company-id', 'old-company-name')"><i class="bi bi-pencil-square"></i></button>
                <!-- show all devices button -->
                <a href="?do=devices-companies&action=show-devices&dev-company-id=<?php echo $company['man_company_id'] ?>" class="btn btn-outline-primary py-1 fs-12" style="width: 50px"><?php echo language('PIECES', @$_SESSION['systemLang']) ?></a>
                <!-- edit button -->
                <button type="button" class="btn btn-outline-danger py-1 fs-12" data-bs-toggle="modal" data-bs-target="#deleteDevCompanyModal" data-name="<?php echo $company['man_company_name'] ?>" data-id="<?php echo $company['man_company_id'] ?>" onclick="put_data_into_modal(this, 'delete', 'deleted-company-id', 'deleted-company-name')"><i class="bi bi-trash"></i></button>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  <?php } else { ?> 
    <!-- start header -->
    <header class="header">
      <!-- start page not found 404 -->
      <div class="page-error">
        <img src="<?php echo $assets ?>images/no-data-founded.svg" class="img-fluid" alt="<?php echo language("NO DATA FOUNDED", @$_SESSION['systemLang']) ?>">
        <h4 class="h4 mt-3"><?php echo language("NO DATA FOUNDED", @$_SESSION['systemLang']) ?></h4>
      </div>
      <!-- end page not found 404 -->
    </header>
  <?php } ?>
</div>

<?php 
if ($_SESSION['pcs_add'] == 1) {
  // include add new device company modal
  include_once 'add-man-company-modal.php';
  // include add new device modal
  include_once 'add-device-modal.php';
}
?>
<!-- include edit device company modal -->
<?php include_once 'edit-man-company-modal.php' ?>
<!-- include delete device company modal -->
<?php include_once 'delete-man-company-modal.php' ?>