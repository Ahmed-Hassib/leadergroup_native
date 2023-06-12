<?php if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // get type name
  $company_name = isset($_POST['company-name']) && !empty($_POST['company-name']) ? $_POST['company-name'] : '';
  if (!isset($dev_company_obj)) {
    // create an object of PiecesTypes class
    $dev_company_obj = new ManufuctureCompanies();
  }
  // check if name exist or not
  $is_exist = $dev_company_obj->count_records("`man_company_id`", "`manufacture_companies`", "WHERE `man_company_name` = $company_name AND `company_id` = " . $_SESSION['company_id']);
  // type name validation
  if (!empty($company_name)) {
    // check if type is exist or not
    if ($is_exist > 0) {
      // echo danger message
      $msg = '<div class="alert alert-danger text-capitalize" dir=""><i class="bi bi-exclamation-triangle-fill"></i>&nbsp;' . language('THIS NAME IS ALREADY EXIST', @$_SESSION['systemLang']) . '</div>';
    } else {
      // call insert_new_type function
      $dev_company_obj->insert_new_man_company(array($company_name, get_date_now(), $_SESSION['UserID'], $_SESSION['company_id']));
      // echo success message
      $msg = '<div class="alert alert-success text-capitalize" dir=""><i class="bi bi-check-circle-fill"></i>&nbsp;' . language('COMPANY WAS ADDED SUCCESSFULLY', @$_SESSION['systemLang']) . '</div>';
    }
  } else {
    // data missed
    $msg = '<div class="alert alert-warning text-capitalize" dir=""><i class="bi bi-check-circle-fill"></i>&nbsp;' . language('PIECE TYPE CANNOT BE EMPTY', @$_SESSION['systemLang']) . '</div>';
  }
?>
  <!-- start pieces type page -->
  <div class="container" dir="<?php echo @$_SESSION['systemLang'] == 'ar' ? 'rtl' : 'ltr' ?>">
    <!-- start header -->
    <header class="header mb-3">
      <?php redirectHome($msg, "back"); ?>
    </header>
  </div>
<?php } else {
  // include_once permission error module
  include_once $globmod . 'permission-error.php';
} ?>
