<?php
if (!isset($dir_obj)) {
  // create an object of Direction class
  $dir_obj = new Direction();
}
// get all directions
$directions = $dir_obj->get_all_directions($_SESSION['company_id']);
// data count
$directions_counter = $directions[0];
// data rows
$directions_info = $directions[1]; 
?>
<!-- start add new user page -->
<div class="container" dir="<?php echo @$_SESSION['systemLang'] == 'ar' ? 'rtl' : 'ltr' ?>">
  <div class="mb-3 hstack gap-3">
    <?php if ($_SESSION['dir_add'] == 1) { ?>
    <!-- add new direction -->
    <button type="button" class="btn btn-outline-primary py-1 fs-12" data-bs-toggle="modal" data-bs-target="#addNewDirectionModal">
      <i class="bi bi-node-plus"></i>
      <?php echo language("ADD NEW DIRECTION", @$_SESSION['systemLang']) ?>
    </button>
    <?php } ?>

    <?php if (!empty($directions_info) || $directions_counter != 0) { ?>
      <?php if ($_SESSION['dir_update'] == 1) { ?>
      <!-- edit direction -->
      <button type="button" class="btn btn-outline-primary py-1 fs-12" data-bs-toggle="modal" data-bs-target="#editDirectionModal">
        <i class="bi bi-pencil-square"></i>
        <?php echo language("EDIT DIRECTION", @$_SESSION['systemLang']) ?>
      </button>
      <?php } ?>

      <?php if ($_SESSION['dir_delete'] == 1) { ?>
      <!-- delete direction -->
      <button type="button" class="btn btn-outline-danger py-1 fs-12" data-bs-toggle="modal" data-bs-target="#deleteDirectionModal">
        <i class="bi bi-trash"></i>
        <?php echo language("DELETE DIRECTION", @$_SESSION['systemLang']) ?>
      </button>
      <?php } ?>
    <?php } ?>
  </div>

  <!-- second row -->
  <div class="mb-3">
    <?php if (empty($directions_info) || $directions_counter == 0) { ?>
      <div class="page-error text-center">
        <img src="<?php echo $assets ?>images/no-data-founded.svg" class="img-fluid" alt="<?php echo language("NO DATA FOUNDED", @$_SESSION['systemLang']) ?>">
      </div>
      <h5 class='h5 text-center text-danger '><?php echo language('THERE IS NO DIRECTIONS TO SHOW', @$_SESSION['systemLang']) ?></h5>
    <?php } else { ?>
      <!-- display all employees -->
      <div class="row row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 align-items-stretch justify-content-start">
        <?php foreach ($directions_info as $index => $row) { ?>
          <div class="col-12">
            <div class="card <?php if ($_SESSION['system_theme'] == 2) { echo 'card-effect '; echo @$_SESSION['systemLang'] == "ar" ? "card-effect-right":"card-effect-left"; } ?>">
              <!-- employee details -->
              <div class="card-body">
                <!-- vstack for employee info -->
                <div class="vstack gap-1">
                  <!-- card title -->
                  <h5 class="mb-0 card-title">
                    <?php echo $row['direction_name'] ?>
                    <?php if (get_date_now() == $row['added_date']) { ?>
                      <span class="badge bg-danger py-1 fs-12"><?php echo language('NEW', @$_SESSION['systemLang']) ?></span>
                    <?php } ?>
                  </h5>
                  <!-- horizontal rule -->
                  <hr>
                </div>
                <!-- vstack for some statistics -->
                <div class="vstack gap-1 nums <?php echo @$_SESSION['systemLang'] == 'ar' ? 'text-end' : 'text-start' ?>">
                  <?php
                  // clients condition
                  $clients_conditions = "WHERE `direction_id` = '" . $row['direction_id'] . "' AND `is_client` = 1 AND `company_id` = " . $_SESSION['company_id'];
                  // pieces condition
                  $pieces_conditions = "WHERE `direction_id` = '" . $row['direction_id'] . "' AND `is_client` = 0 AND `company_id` = " . $_SESSION['company_id'];
                  // pieces condition
                  $unkown_conditions = "WHERE `direction_id` = '" . $row['direction_id'] . "' AND `is_client` NOT IN (0, 1) AND `company_id` = " . $_SESSION['company_id'];
                  // count pieces
                  $pieces = $dir_obj->count_records("`id`", "pieces_info", $pieces_conditions);
                  // count clients
                  $clients = $dir_obj->count_records("`id`", "pieces_info", $clients_conditions);
                  // count unkown
                  $unkown = $dir_obj->count_records("`id`", "pieces_info", $unkown_conditions);
                  ?>
                  <!-- clients -->
                  <a href="<?php echo $nav_up_level ?>clients/index.php?do=show-dir-clients&dir-id=<?php echo $row["direction_id"] ?>" class="mb-0 text-capitalize">
                    <i class="bi bi-people"></i>
                    <span><?php echo language('CLIENTS', @$_SESSION['systemLang']) ?></span>
                    <span class="num" data-goal="<?php echo $clients ?>">0</span>
                  </a>
                  <!-- pieces -->
                  <a href="<?php echo $nav_up_level ?>pieces/index.php?do=show-dir-pieces&type=0&dir-id=<?php echo $row["direction_id"] ?>" class="mb-0 text-capitalize">
                    <i class="bi bi-hdd-rack"></i>
                    <span><?php echo language('PIECES', @$_SESSION['systemLang']) ?></span>
                    <span class="num" data-goal="<?php echo $pieces ?>">0</span>
                  </a>
                  <!-- un assigned -->
                  <a href="<?php echo $nav_up_level ?>pieces/index.php?do=show-dir-pieces&type=-1&dir-id=<?php echo $row["direction_id"] ?>" class="mb-0 text-capitalize">
                    <i class="bi bi-exclamation-triangle"></i>
                    <span><?php echo language('UNKNOWN', @$_SESSION['systemLang']) ?></span>
                    <span class="num" data-goal="<?php echo $unkown ?>">0</span>
                  </a>
                  <!-- horizontal rule -->
                  <hr>
                </div>
                <?php if ($clients > 0 || $pieces > 0) { ?>
                  <!-- vstack for some statistics -->
                  <div class="vstack gap-1 <?php echo @$_SESSION['systemLang'] == 'ar' ? 'text-end' : 'text-start' ?>">
                    <p class="mb-0 card-text text-capitalize text-danger  fs-12">
                      <i class="bi bi-exclamation-triangle-fill"></i>&nbsp;<?php echo language('CANNOT DELETE THIS DIRECTION BECAUSE THIS DIR CONTAINS ONE PIECE OR MORE', @$_SESSION['systemLang']) ?>
                    </p>
                    <!-- horizontal rule -->
                    <hr>
                  </div>
                <?php } ?>
                <!-- hstack for buttons -->
                <div class="hstack gap-1 align-items-baseline">
                  <!-- added date -->
                  <p class="card-text text-secondary text-capitalize mt-3 mb-0 fs-12 fs-10-sm <?php echo @$_SESSION['systemLang'] == 'ar' ? 'ms-auto' : 'me-auto' ?>"><?php echo language('ADDED DATE', @$_SESSION['systemLang'])." ".$row['added_date'] ?></p>
                  
                  <?php if ($_SESSION['dir_update'] == 1) { ?>
                  <!-- edit direction -->
                  <button type="button" data-bs-toggle="modal" data-bs-target="#editDirectionModal" class='py-1 btn btn-primary text-capitalize fs-12 fs-10-sm' onclick="put_dir_info(this, 'update')" data-direction-id="<?php echo $row['direction_id'] ?>" data-direction-name="<?php echo $row['direction_name'] ?>" data-direction-ip="<?php echo $row['direction_ip'] ?>"><?php echo language('EDIT', @$_SESSION['systemLang']) ?></button>
                  <?php } ?>

                  <?php if ($_SESSION['dir_delete'] == 1 && $clients < 1 && $pieces < 1 && $unkown < 1) { ?>
                  <!-- delete direction -->
                  <button type="button" data-bs-toggle="modal" data-bs-target="#deleteDirectionModal" class='btn btn-outline-danger text-capitalize py-1 fs-12 fs-10-sm' style="<?php if ($_SESSION['user_delete'] == 0 || $clients > 0 || $pieces > 0) {echo 'cursor: not-allowed';} ?>" onclick="put_dir_info(this, 'delete')" data-direction-id="<?php echo $row['direction_id'] ?>"><?php echo language('DELETE', @$_SESSION['systemLang']) ?></button>
                  <?php } ?>

                  <?php if ($_SESSION['dir_show'] == 1) { ?>
                  <!-- show direction tree -->
                  <a href="?do=show-direction-tree&dir-id=<?php echo $row["direction_id"] ?>" class="btn btn-outline-primary p-1 fs-12 fs-10-sm">
                    <i class="bi bi-eye p-1"></i>
                  </a>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    <?php } ?>
  </div>
  <!-- end stats -->
</div>
  
<?php 
if (!empty($directions_info) || $directions_counter != 0) {
  if ($_SESSION['dir_update'] == 1) {
    // include edit direction modal
    include_once 'edit-direction-modal.php';
  }

  if ($_SESSION['dir_delete'] == 1) {
    // include delete direction modal
    include_once 'delete-direction-modal.php';
  }
} 
?>