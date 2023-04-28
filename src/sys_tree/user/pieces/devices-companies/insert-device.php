<?php 
// chekc request method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // get company id
  $company_id = isset($_POST['company-id']) && !empty($_POST['company-id']) ? $_POST['company-id'] : '';
  // get device name
  $device_name = isset($_POST['device-name']) && !empty($_POST['device-name']) ? $_POST['device-name'] : '';
  // get device models
  $device_models = isset($_POST['model']) && !empty($_POST['model']) ? $_POST['model'] : '';
  // check if company id is not empty
  if (!empty($company_id) && !empty($device_name)) {
    // create an object of PiecesTypes class
    $dev_company_obj = new Devices();
    // count condition
    $count_condition = "LEFT JOIN `manufacture_companies` ON `manufacture_companies`.`man_company_id` = `devices_info`.`device_company_id` WHERE `manufacture_companies`.`company_id` = " . $_SESSION['company_id'] . "AND `devices_info`.`device_name` = $device_name ";
    // check if name exist or not
    $is_exist = $dev_company_obj->count_records("`device_name`", "`devices_info`", $count_condition);
    // check if type is exist or not
    if ($is_exist > 0) {
      // echo danger message
      $msg = '<div class="alert alert-danger text-capitalize" dir=""><i class="bi bi-exclamation-triangle-fill"></i>&nbsp;' . language('THIS NAME IS ALREADY EXIST', @$_SESSION['systemLang']) . '</div>';
    } else {
      // call insert_new_type function
      $dev_company_obj->insert_new_devices(array($device_name, get_date_now(), $_SESSION['UserID'], $company_id));
      // get current device id 
      $curr_device_id = $dev_company_obj->get_latest_records("`device_id`", "`devices_info`", "", "`device_id`", "1")[0]['device_id'];
      // check model length
      if (!empty($device_models)) {
        // create an object of Models class
        $model_obj = new Models();
        // is inserted flag for models
        $is_inserted_models = false;
        // loop on models to insert it
        foreach ($device_models as $model) {
          // check model if empty
          if (!empty($model)) {
            // insert model
            $model_obj->insert_new_model(array($model, get_date_now(), $_SESSION['UserID'], $curr_device_id));
            $is_inserted_models = true;
          }
        }
      }
      // echo success message
      $msg = '<div class="alert alert-success text-capitalize" dir=""><i class="bi bi-check-circle-fill"></i>&nbsp;' . language('DEVICE WAS ADDED SUCCESSFULLY', @$_SESSION['systemLang']) . '</div>';
      // check model flag
      if (!empty($device_models) && $is_inserted_models) {
        // echo success message
        $msg .= '<div class="alert alert-success text-capitalize" dir=""><i class="bi bi-check-circle-fill"></i>&nbsp;' . language('MODELS WAS ADDED SUCCESSFULLY', @$_SESSION['systemLang']) . '</div>';
      }
    }
?>
  <!-- start devices page -->
  <div class="container" dir="<?php echo @$_SESSION['systemLang'] == 'ar' ? 'rtl' : 'ltr' ?>">
    <!-- start header -->
    <header class="header mb-3">
      <?php redirectHome($msg, "back");  ?>
    </header>
  </div>
  
<?php
  } else {
    // include_once permission error module
    include_once $globmod . 'missing-data.php';
  }
} else {
  // include_once permission error module
  include_once $globmod . 'permission-error.php';
} ?>
