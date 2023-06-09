<!-- start home stats container -->
<div class="container" dir="<?php echo @$_SESSION['systemLang'] == 'ar' ? 'rtl' : 'ltr' ?>">
  <!-- start stats -->
  <div class="stats">
    <!-- buttons section -->
    <div class="mb-3 hstack gap-3">
      <?php if ($_SESSION['connection_add'] == 1) { ?>
      <!-- add new connection type -->
      <button type="button" class="btn btn-outline-primary shadow-sm py-1 fs-12" data-bs-toggle="modal" data-bs-target="#addNewPieceConnTypeModal">
        <i class="bi bi-file-plus"></i>
        <?php echo language("ADD NEW CONNECTION TYPE", @$_SESSION['systemLang']) ?>
      </button>
      <?php } ?>
      
      <?php if ($_SESSION['connection_update'] == 1) { ?>
      <!-- edit connection type -->
      <button type="button" class="btn btn-outline-primary shadow-sm py-1 fs-12" data-bs-toggle="modal" data-bs-target="#editPieceConnTypeModal">
        <i class="bi bi-pencil-square"></i>
        <?php echo language("EDIT CONNECTION TYPES", @$_SESSION['systemLang']) ?>
      </button>
      <?php } ?>

      <?php if ($_SESSION['connection_delete'] == 1) { ?>
      <!-- delete connection type -->
      <button type="button" class="btn btn-outline-danger shadow-sm py-1 fs-12" data-bs-toggle="modal" data-bs-target="#deletePieceConnTypeModal">
        <i class="bi bi-pencil-square"></i>
        <?php echo language("DELETE CONNECTION TYPE", @$_SESSION['systemLang']) ?>
      </button>
      <?php } ?>
    </div>

      <!-- start new design -->
      <div class="mb-3 row row-cols-sm-1 g-3 align-items-stretch justify-content-start">
        <!-- total connection types statistics -->
        <div class="col-12">
          <div class="section-block">
            <div class="section-header">
              <h5 class="h5 text-capitalize"><?php echo language('CONNECTION TYPES STATISTICS', @$_SESSION['systemLang']) ?></h5>
              <p class="text-muted "><?php echo language('HERE WILL SHOW SOME STATISTICS ABOUT THE NUMBERS OF PIECES TYPES', @$_SESSION['systemLang']) ?></p>
            <hr>
          </div>
          <?php 
          if (!isset($conn_obj)) {
            // create an object of PiecesConn class
            $conn_obj = new PiecesConn();
          }
          // get all connections 
          $conn_data = $conn_obj->get_all_conn_types($_SESSION['company_id']);
          // data counter
          $types_count = $conn_data[0];
          // data rows
          $types_data = $conn_data[1];
          // check types count
          if ($types_count > 0) {
          ?>
            <div class="row row-cols-sm-2 row-cols-md-3 row-cols-lg-4 gx-3 gy-5">
              <?php
              // counter
              $i = 1;
              // loop on types
              foreach ($types_data as $key => $type) {
                // get count of pieces
                $all_count = $conn_obj->count_records("`id`", "`pieces_info`", "WHERE `connection_type` = ".$type['id']. " AND `pieces_info`.`company_id` = ".$_SESSION['company_id']);
                $pcs_count = $conn_obj->count_records("`id`", "`pieces_info`", "WHERE `is_client` = 0 AND `connection_type` = ".$type['id']. " AND `pieces_info`.`company_id` = ".$_SESSION['company_id']);
                $clients_count = $conn_obj->count_records("`id`", "`pieces_info`", "WHERE `is_client` = 1 AND `connection_type` = ".$type['id']. " AND `pieces_info`.`company_id` = ".$_SESSION['company_id']);
                $unknown_count = $conn_obj->count_records("`id`", "`pieces_info`", "WHERE `is_client` NOT IN (0, 1) AND `connection_type` = ".$type['id']. " AND `pieces_info`.`company_id` = ".$_SESSION['company_id']);
                // check counter
                if($i > 9) { $i = 1; }
              ?>
                <div class="col-12">
                  <div class="card card-stat shadow-sm border border-1">
                    <div class="card-body">
                      <h5 class="h5 card-title text-uppercase"><?php echo $type['connection_name'] ?></h5>
                      <span class="bg-primary icon-container <?php echo @$_SESSION['systemLang'] == 'ar' ? 'icon-container-left' : 'icon-container-right' ?>">
                        <span class="nums">
                          <span class="num" data-goal="<?php echo $all_count ?>">0</span>
                        </span>
                      </span>
                      <a href="?do=show-pieces-conn&conn-id=<?php echo $type['id'] ?>" class="stretched-link text-black"></a>
                    </div>
                    <div class="card-footer text-dark text-end fs-12 ">
                      <div class="nums">
                        <span class="num" data-goal="<?php echo $pcs_count ?>">0</span>
                        <span><?php echo language('PIECE', @$_SESSION['systemLang']) ?></span>
                      </div>
                      <div class="nums">
                        <span class="num" data-goal="<?php echo $clients_count ?>">0</span>
                        <span><?php echo language('CLIENT', @$_SESSION['systemLang']) ?></span>
                      </div>
                      <div class="nums">
                        <span class="num" data-goal="<?php echo $unknown_count ?>">0</span>
                        <span><?php echo language('UNKNOWN', @$_SESSION['systemLang']) ?></span>
                      </div>
                    </div>
                  </div>
                </div>
                <?php $i++; ?>
              <?php } ?>
              <!-- show the number of clients that not assigned the connection type -->
              <div class="col-12">
                <div class="card card-stat bg-danger shadow-sm border border-1">
                  <div class="card-body">
                    <?php 
                    $not_assigned = $conn_obj->count_records("`id`", "`pieces_info`", "WHERE `connection_type` = 0 AND `company_id` = ".$_SESSION['company_id']);
                    $not_assigned_pcs_count = $conn_obj->count_records("`id`", "`pieces_info`", "WHERE `is_client` = 0 AND `connection_type` = 0 AND `pieces_info`.`company_id` = ".$_SESSION['company_id']);
                    $not_assigned_clients_count = $conn_obj->count_records("`id`", "`pieces_info`", "WHERE `is_client` = 1 AND `connection_type` = 0 AND `pieces_info`.`company_id` = ".$_SESSION['company_id']);
                    $not_assigned_unknown_count = $conn_obj->count_records("`id`", "`pieces_info`", "WHERE `is_client` NOT IN (0, 1) AND `connection_type` = 0 AND `pieces_info`.`company_id` = ".$_SESSION['company_id']);
                    ?>
                    <h5 class="h5 card-title text-uppercase"><?php echo language('NOT ASSIGNED', @$_SESSION['systemLang']) ?></h5>
                    <span class="bg-warning icon-container <?php echo @$_SESSION['systemLang'] == 'ar' ? 'icon-container-left' : 'icon-container-right' ?>">
                      <span class="nums">
                        <span class="num" data-goal="<?php echo $not_assigned ?>">0</span>
                      </span>
                    </span>
                    <a href="?do=show-pieces-conn&conn-id=0" class="stretched-link text-black"></a>
                  </div>
                  <div class="card-footer text-white text-end fs-12 ">
                    <div class="nums">
                      <span class="num" data-goal="<?php echo $not_assigned_pcs_count ?>">0</span>
                      <span><?php echo language('PIECE', @$_SESSION['systemLang']) ?></span>
                    </div>
                    <div class="nums">
                      <span class="num" data-goal="<?php echo $not_assigned_clients_count ?>">0</span>
                      <span><?php echo language('CLIENT', @$_SESSION['systemLang']) ?></span>
                    </div>
                    <div class="nums">
                      <span class="num" data-goal="<?php echo $not_assigned_unknown_count ?>">0</span>
                      <span><?php echo language('UNKNOWN', @$_SESSION['systemLang']) ?></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php } else { ?>
            <h5 class="h5 text-capitalize text-danger"><?php echo language('THERE IS NO CONNECTION TYPES TO SHOW', @$_SESSION['systemLang']) ?></h5>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-outline-primary shadow-sm py-1 fs-12" data-bs-toggle="modal" data-bs-target="#addNewPieceConnTypeModal">
              <i class="bi bi-file-plus"></i>
              <?php echo language("ADD NEW CONNECTION TYPE", @$_SESSION['systemLang']) ?>
            </button>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>