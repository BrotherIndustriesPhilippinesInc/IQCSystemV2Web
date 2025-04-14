<?php 
    include_once __DIR__ . "/help-popover.php";
?>
<div id="header" class="row justify-content-center py-3 sticky-top">
    
        <!-- First Column -->
        <div class="col d-flex align-items-center">

            <i id="menu-icon" class="icon fa-solid fa-bars" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDark" aria-controls="offcanvasDark"></i>

            <a id="logo" href="/iqcv2"><h1>IQC System</h1></a>
        </div>

        <!-- Second Column -->
        <div class="col d-flex justify-content-end align-items-center gap-3">
            <button class="transparent border-0" popovertarget="help-popover">
                <i id="help-icon" class="icon fa-solid fa-question"></i>
            </button>
            <span>
                <a href="/iqcv2/settings"><i id="settings-icon" class="icon fa-solid fa-gears"></i></a>
            </span>

            <div class="d-flex align-items-center">
                <span>
                    <img src="/iqcv2/resources/icons/UserLogo.svg" alt="user-logo-icon" class="">
                </span>
                <div class="d-flex flex-column text-center">
                    <span id="username" class="header-text ms-2 fw-bold">Lastname, Firstname</span>
                    <span id="section" class="header-text ms-2 fw-bold">BPS</span>
                </div>
            </div>
            
        </div>
</div>
