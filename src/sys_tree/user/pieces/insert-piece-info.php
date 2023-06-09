<?php if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!isset($pcs_obj)) {
    // create an object of Pieces class
    $pcs_obj = new Pieces();
  }
  // get latest id in pieces table
  $latest_id = intval($pcs_obj->get_latest_records("`id`", "`pieces_info`", "", "`id`", 1)[0]['id']);
  // get next id
  $id = $latest_id + 1;
  // get piece info from the form
  $full_name  = isset($_POST['full-name'])  && !empty($_POST['full-name'])  ? trim($_POST['full-name'], ' ')  : '';
  $ip         = isset($_POST['ip'])         && !empty($_POST['ip'])         ? trim($_POST['ip'], ' ')         : '';
  $username   = isset($_POST['user-name'])  && !empty($_POST['user-name'])  ? trim($_POST['user-name'], ' ')  : '';
  $password   = isset($_POST['password'])   && !empty($_POST['password'])   ? trim($_POST['password'], ' ')   : '';
  $dir_id     = isset($_POST['direction'])  && !empty($_POST['direction'])  ? trim($_POST['direction'], ' ')  : '';

  // check if client or not
  if (isset($_POST['is-client'])) {
    // get value
    $is_client_value = $_POST['is-client'];
    // switch ... case
    switch ($is_client_value) {      
      case 1:
        // make it transmitter
        $is_client   = 0;
        $device_type = 1;
      break;
      
      case 2:
        // make it receiver
        $is_client   = 0;
        $device_type = 2;
      break;
      
      default:
        // make it default
        $is_client   = -1;
        $device_type = -1;
      break;
    }
  } else {
    // make it default
    $is_client   = -1;
    $device_type = -1;
  }

  // get source id
  $source_id        = isset($_POST['source-id']) ? trim($_POST['source-id'], ' ')   : -1;
  $alt_source_id    = isset($_POST['alt-source-id']) ? trim($_POST['alt-source-id'], ' ')   : -1;
  $device_id        = isset($_POST['device-id']) ? trim($_POST['device-id'], ' ')   : -1;
  $model_id         = isset($_POST['device-model']) ? trim($_POST['device-model'], ' ')   : -1;

  $phone            = trim($_POST['phone-number'], ' ');
  $address          = trim($_POST['address'], ' ');
  $conn_type        = isset($_POST['conn-type'])  && !empty($_POST['conn-type']) ? trim($_POST['conn-type'], ' ')  : '';
  $notes            = empty(trim($_POST['notes'], ' ')) ? 'لا توجد ملاحظات' : trim($_POST['notes'], ' ');
  $visit_time       = isset($_POST['visit-time']) ? $_POST['visit-time'] : 1;
  $ssid             = trim($_POST['ssid'], ' ');
  $pass_conn        = trim($_POST['password-connection'], ' ');
  $frequency        = trim($_POST['frequency'], ' ');
  $wave             = trim($_POST['wave'], ' ');
  $mac_add          = trim($_POST['mac-add'], ' ');
  $internet_source  = trim($_POST['internet-source'], ' ');

  // validate the form
  $form_error = []; // error array

  if ($source_id == $id) {
    $source_id = 0;
  }

  if ($alt_source_id == $id) {
    $alt_source_id = 0;
  }
  
  // check if user is exist in database or not
  $is_exist_name  = $pcs_obj->count_records("`id`", "`pieces_info`", "WHERE `full_name` = $full_name AND `company_id` = " . $_SESSION['company_id']);
  $is_exist_mac   = !empty($macAdd) ? $pcs_obj->count_records("`pieces_mac_addr`.`id`", "`pieces_mac_addr`", "LEFT JOIN `pieces_info` ON `pieces_info`.`id` = `pieces_mac_addr`.`id` WHERE `pieces_mac_addr`.`mac_add` = $mac_add AND `pieces_info`.`company_id` = ".$_SESSION['company_id']) : 0;
  $is_exist_ip    = $ip == '0.0.0.0' ? 0 : $pcs_obj->count_records("`id`", "`pieces_info`", "WHERE `ip` = '$ip' AND `direction_id` = $dir_id AND `company_id` = " . $_SESSION['company_id']);

  // check piece name
  if ($is_exist_name > 0) {
    $form_error[] = 'this username is already exist';
  }

  // check piece mac
  if ($is_exist_mac > 0) {
    $form_error[] = 'this mac add is already exist';
  }

  // check piece mac
  if ($is_exist_ip > 0) {
    $form_error[] = 'this ip add is already exist';
  }

  // check if empty form error
  if (empty($form_error)) {
    // get current date
    $date_now = get_date_now();
    // call insert function
    $is_inserted = $pcs_obj->insert_new_piece(array($full_name, $ip, $username, $password, $conn_type, $dir_id, $source_id, $alt_source_id, $is_client, $device_type, $device_id, $model_id, $_SESSION['UserID'], $date_now, $_SESSION['company_id'], $notes, $visit_time));

    // check address
    if (!empty($address)) {
      // echo "<br>* address is not empty<br>";
      // insert address
      $pcs_obj->insert_address($id, $address);
    }
    
    // check frequency
    if (!empty($frequency)) {
      // echo "<br>* frequency is not empty<br>";
      // insert frequency
      $pcs_obj->insert_frequency($id, $frequency);
    }
    
    // check mac_add
    if (!empty($mac_add)) {
      // echo "<br>* mac add is not empty<br>";
      // insert mac_add
      $pcs_obj->insert_mac_add($id, $mac_add);
    }

    // check pass_connection
    if (!empty($pass_conn)) {
      // echo "<br>* pass connection is not empty<br>";
      // insert pass_conn
      $pcs_obj->insert_pass_connection($id, $pass_conn);
    }
    
    // check phones
    if (!empty($phone)) {
      // echo "<br>* phone is not empty<br>";
      // insert phones
      $pcs_obj->insert_phones($id, $phone);
    }
    
    // check ssid
    if (!empty($ssid)) {
      // echo "<br>* ssid is not empty<br>";
      // insert ssid
      $pcs_obj->insert_ssid($id, $ssid);
    }
    
    // check wave
    if (!empty($wave)) {
      // echo "<br>* wave is not empty<br>";
      // insert wave
      $pcs_obj->insert_wave($id, $wave);
    }
    
    // check internet source
    if (!empty($internet_source)) {
      // echo "<br>* internet source is not empty<br>";
      // insert internet source
      $pcs_obj->insert_internet_source($id, $internet_source);
    }
    
    // prepare flash session variables
    $_SESSION['flash_message'] = 'A NEW PIECE WAS ADDED SUCCESSFULLY';
    $_SESSION['flash_message_icon'] = 'bi-check-circle-fill';
    $_SESSION['flash_message_class'] = 'success';
    $_SESSION['flash_message_status'] = true;
  } else {
    foreach ($form_error as $key => $error) {
      // prepare flash session variables
      $_SESSION['flash_message'][$key] = strtoupper($error);
      $_SESSION['flash_message_icon'][$key] = 'bi-exclamation-triangle-fill';
      $_SESSION['flash_message_class'][$key] = 'danger';
      $_SESSION['flash_message_status'][$key] = false;
    }
  } 
  // redirect to previous page
  redirectHome(null, 'back', 0);
} else {
  // include permission error module
  include_once $globmod . 'permission-error.php';
}