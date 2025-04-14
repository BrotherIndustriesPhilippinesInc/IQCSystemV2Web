<?php
function isActive($urlPart) {
  return strpos($_SERVER['REQUEST_URI'], $urlPart) !== false ? ["collapse show","true", ""] : ["collapse", "false", "collapsed"];
}
?>

<div class="offcanvas offcanvas-start primary-background" tabindex="-1" id="offcanvasDark" aria-labelledby="offcanvasDarkLabel">
  <div class="offcanvas-header">
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="d-flex justify-content-center mb-4">
    <span data-bs-toggle="offcanvas" data-bs-target="#offcanvasDark" aria-controls="offcanvasDark">
        <img src="/iqcv2/resources/logo/iqc_logo.png" style="mix-blend-mode: screen;" alt="logo-icon">
    </span>
  </div>
  <div class="offcanvas-body p-0">
    <div class="accordion accordion-flush" id="accordionExample">

      <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button <?= isActive('/choose_lots/')[2] ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="<?= isActive('/choose_lots/')[1] ?>" aria-controls="collapseOne">
              <i class="fa-solid fa-box" style="padding-right: 8px;"></i>Choose Lots
            </button>
          </h2>
          <div id="collapseOne" class="accordion-collapse <?= isActive('/choose_lots/')[0] ?>" data-bs-parent="#accordionExample">
            <div class="accordion-body">
              <a class="nav-item" href="/iqcv2/choose_lots/inspection_result"><div>Inspection Result</div></a>
            </div>
          </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button <?= isActive('/registration/')[2] ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="<?= isActive('/registration/')[1] ?>" aria-controls="collapseTwo">
            <i class="fa-solid fa-list" style="padding-right: 8px;"></i>Registration
          </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse <?= isActive('/registration/')[0] ?>" data-bs-parent="#accordionExample">
          <div class="accordion-body">
              <a class="nav-item" href="/iqcv2/registration/parts_information"><div>Parts Information</div></a>
              <a class="nav-item" href="/iqcv2/registration/check_items"><div>Check Items</div></a>
          </div>
        </div>
      </div>
      
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button <?= isActive('/operations/')[2] ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="<?= isActive('/operations/')[1] ?>" aria-controls="collapseThree">
            <i class="fa-solid fa-gear" style="padding-right: 8px;"></i>Operations
          </button>
        </h2>
        <div id="collapseThree" class="accordion-collapse <?= isActive('/operations/')[0] ?>" data-bs-parent="#accordionExample">
          <div class="accordion-body">
            <a class="nav-item" href="/iqcv2/operations/machine_lot_request"><div>Machine Lot Request</div></a>
          </div>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button <?= isActive('/manage/')[2] ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="<?= isActive('/manage/')[1] ?>" aria-controls="collapseFour">
            <i class="fa-solid fa-clipboard-list" style="padding-right: 8px;"></i>Manage
          </button>
        </h2>
        <div id="collapseFour" class="accordion-collapse <?= isActive('/manage/')[0] ?>" data-bs-parent="#accordionExample">
          <div class="accordion-body">
            <a class="nav-item" href="/iqcv2/manage/inspection_approval"><div>Inspection Approval</div></a>
          </div>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button <?= isActive('/report/')[2] ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="<?= isActive('/report/')[1] ?>" aria-controls="collapseFive">
          <i class="fa-solid fa-flag" style="padding-right: 8px;"></i>Report
          </button>
        </h2>
        <div id="collapseFive" class="accordion-collapse <?= isActive('/report/')[0] ?>" data-bs-parent="#accordionExample">
          <div class="accordion-body">
            <a class="nav-item" href="/iqcv2/report/inspection_background_report"><div>Inspection Background Report</div></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
