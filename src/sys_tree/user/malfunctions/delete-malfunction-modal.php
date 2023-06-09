<!-- Modal -->
<div class="modal fade" id="delete-malfunction-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" dir="<?php echo @$_SESSION['systemLang'] == 'ar' ? 'rtl' : 'ltr' ?>">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-capitalize " id="exampleModalLabel"><?php echo language('CONFIRM', @$_SESSION['systemLang'])." ".language('DELETE', @$_SESSION['systemLang'])." ".language('THE MALFUNCTION', @$_SESSION['systemLang']) ?></h5>
      </div>
      <div class="modal-body">
        <h5 class="h5" <?php echo @$_SESSION['systemLang'] == "ar" ? "dir=rtl" : ""; ?>><?php echo language('ARE YOU SURE TO DELETE', @$_SESSION['systemLang'])." ".language('THE MALFUNCTION', @$_SESSION['systemLang'])." ".( @$_SESSION['systemLang'] == "ar" ? "؟" : "?" )?> </h5>
      </div>
      <div class="modal-footer">
        <?php if ($_SESSION['mal_delete'] == 1) { ?>
          <a class="btn btn-danger text-capitalize fs-12 py-1" id="confirm-delete-malfunction"><i class="bi bi-trash"></i>&nbsp;<?php echo language('DELETE', @$_SESSION['systemLang']) ?></a>
        <?php } ?>
        <button type="button" class="btn btn-outline-secondary py-1 fs-12" data-bs-dismiss="modal"><?php echo language('CLOSE', @$_SESSION['systemLang']) ?></button>
      </div>
    </div>
  </div>
</div>