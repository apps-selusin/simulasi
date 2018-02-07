<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(3, "mi_cf01_home_php", $Language->MenuPhrase("3", "MenuText"), "cf01_home.php", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(6, "mci_VHP", $Language->MenuPhrase("6", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(1, "mi_t_201412", $Language->MenuPhrase("1", "MenuText"), "t_201412list.php", 6, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(2, "mi_t_lab_db", $Language->MenuPhrase("2", "MenuText"), "t_lab_dblist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->Render();
?>
<!-- End Main Menu -->
