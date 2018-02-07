<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_201412info.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$t_201412_add = NULL; // Initialize page object first

class ct_201412_add extends ct_201412 {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F1294FCC-6ACE-4B79-974B-E37DC6993CA6}";

	// Table name
	var $TableName = 't_201412';

	// Page object name
	var $PageObjName = 't_201412_add';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (t_201412)
		if (!isset($GLOBALS["t_201412"]) || get_class($GLOBALS["t_201412"]) == "ct_201412") {
			$GLOBALS["t_201412"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_201412"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_201412', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->COL_2->SetVisibility();
		$this->COL_4->SetVisibility();
		$this->COL_5->SetVisibility();
		$this->COL_6->SetVisibility();
		$this->t_lab_db_id->SetVisibility();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $t_201412;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($t_201412);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();

			// Handle modal response
			if ($this->IsModal) {
				$row = array();
				$row["url"] = $url;
				echo ew_ArrayToJson(array($row));
			} else {
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id"] != "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->setKey("id", $this->id->CurrentValue); // Set up key
			} else {
				$this->setKey("id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		} else {
			if ($this->CurrentAction == "I") // Load default values for blank record
				$this->LoadDefaultValues();
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("t_201412list.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "t_201412list.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "t_201412view.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->COL_2->CurrentValue = NULL;
		$this->COL_2->OldValue = $this->COL_2->CurrentValue;
		$this->COL_4->CurrentValue = NULL;
		$this->COL_4->OldValue = $this->COL_4->CurrentValue;
		$this->COL_5->CurrentValue = NULL;
		$this->COL_5->OldValue = $this->COL_5->CurrentValue;
		$this->COL_6->CurrentValue = NULL;
		$this->COL_6->OldValue = $this->COL_6->CurrentValue;
		$this->t_lab_db_id->CurrentValue = NULL;
		$this->t_lab_db_id->OldValue = $this->t_lab_db_id->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->COL_2->FldIsDetailKey) {
			$this->COL_2->setFormValue($objForm->GetValue("x_COL_2"));
		}
		if (!$this->COL_4->FldIsDetailKey) {
			$this->COL_4->setFormValue($objForm->GetValue("x_COL_4"));
		}
		if (!$this->COL_5->FldIsDetailKey) {
			$this->COL_5->setFormValue($objForm->GetValue("x_COL_5"));
		}
		if (!$this->COL_6->FldIsDetailKey) {
			$this->COL_6->setFormValue($objForm->GetValue("x_COL_6"));
		}
		if (!$this->t_lab_db_id->FldIsDetailKey) {
			$this->t_lab_db_id->setFormValue($objForm->GetValue("x_t_lab_db_id"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->COL_2->CurrentValue = $this->COL_2->FormValue;
		$this->COL_4->CurrentValue = $this->COL_4->FormValue;
		$this->COL_5->CurrentValue = $this->COL_5->FormValue;
		$this->COL_6->CurrentValue = $this->COL_6->FormValue;
		$this->t_lab_db_id->CurrentValue = $this->t_lab_db_id->FormValue;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->id->setDbValue($rs->fields('id'));
		$this->COL_1->setDbValue($rs->fields('COL 1'));
		$this->COL_2->setDbValue($rs->fields('COL 2'));
		$this->COL_3->setDbValue($rs->fields('COL 3'));
		$this->COL_4->setDbValue($rs->fields('COL 4'));
		$this->COL_5->setDbValue($rs->fields('COL 5'));
		$this->COL_6->setDbValue($rs->fields('COL 6'));
		$this->COL_7->setDbValue($rs->fields('COL 7'));
		$this->COL_8->setDbValue($rs->fields('COL 8'));
		$this->t_lab_db_id->setDbValue($rs->fields('t_lab_db_id'));
		if (array_key_exists('EV__t_lab_db_id', $rs->fields)) {
			$this->t_lab_db_id->VirtualValue = $rs->fields('EV__t_lab_db_id'); // Set up virtual field value
		} else {
			$this->t_lab_db_id->VirtualValue = ""; // Clear value
		}
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->COL_1->DbValue = $row['COL 1'];
		$this->COL_2->DbValue = $row['COL 2'];
		$this->COL_3->DbValue = $row['COL 3'];
		$this->COL_4->DbValue = $row['COL 4'];
		$this->COL_5->DbValue = $row['COL 5'];
		$this->COL_6->DbValue = $row['COL 6'];
		$this->COL_7->DbValue = $row['COL 7'];
		$this->COL_8->DbValue = $row['COL 8'];
		$this->t_lab_db_id->DbValue = $row['t_lab_db_id'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->COL_6->FormValue == $this->COL_6->CurrentValue && is_numeric(ew_StrToFloat($this->COL_6->CurrentValue)))
			$this->COL_6->CurrentValue = ew_StrToFloat($this->COL_6->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// COL 1
		// COL 2
		// COL 3
		// COL 4
		// COL 5
		// COL 6
		// COL 7
		// COL 8
		// t_lab_db_id

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// COL 1
		$this->COL_1->ViewValue = $this->COL_1->CurrentValue;
		$this->COL_1->ViewCustomAttributes = "";

		// COL 2
		$this->COL_2->ViewValue = $this->COL_2->CurrentValue;
		$this->COL_2->ViewCustomAttributes = "";

		// COL 3
		$this->COL_3->ViewValue = $this->COL_3->CurrentValue;
		$this->COL_3->ViewCustomAttributes = "";

		// COL 4
		$this->COL_4->ViewValue = $this->COL_4->CurrentValue;
		$this->COL_4->ViewCustomAttributes = "";

		// COL 5
		$this->COL_5->ViewValue = $this->COL_5->CurrentValue;
		$this->COL_5->ViewCustomAttributes = "";

		// COL 6
		$this->COL_6->ViewValue = $this->COL_6->CurrentValue;
		$this->COL_6->ViewCustomAttributes = "";

		// COL 7
		$this->COL_7->ViewValue = $this->COL_7->CurrentValue;
		$this->COL_7->ViewCustomAttributes = "";

		// COL 8
		$this->COL_8->ViewValue = $this->COL_8->CurrentValue;
		$this->COL_8->ViewCustomAttributes = "";

		// t_lab_db_id
		if ($this->t_lab_db_id->VirtualValue <> "") {
			$this->t_lab_db_id->ViewValue = $this->t_lab_db_id->VirtualValue;
		} else {
		if (strval($this->t_lab_db_id->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->t_lab_db_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `COL 2` AS `DispFld`, `COL 3` AS `Disp2Fld`, `COL 4` AS `Disp3Fld`, `id` AS `Disp4Fld` FROM `t_lab_db`";
		$sWhereWrk = "";
		$this->t_lab_db_id->LookupFilters = array("dx1" => '`COL 2`', "dx2" => '`COL 3`', "dx3" => '`COL 4`', "dx4" => '`id`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->t_lab_db_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$arwrk[4] = $rswrk->fields('Disp4Fld');
				$this->t_lab_db_id->ViewValue = $this->t_lab_db_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->t_lab_db_id->ViewValue = $this->t_lab_db_id->CurrentValue;
			}
		} else {
			$this->t_lab_db_id->ViewValue = NULL;
		}
		}
		$this->t_lab_db_id->ViewCustomAttributes = "";

			// COL 2
			$this->COL_2->LinkCustomAttributes = "";
			$this->COL_2->HrefValue = "";
			$this->COL_2->TooltipValue = "";

			// COL 4
			$this->COL_4->LinkCustomAttributes = "";
			$this->COL_4->HrefValue = "";
			$this->COL_4->TooltipValue = "";

			// COL 5
			$this->COL_5->LinkCustomAttributes = "";
			$this->COL_5->HrefValue = "";
			$this->COL_5->TooltipValue = "";

			// COL 6
			$this->COL_6->LinkCustomAttributes = "";
			$this->COL_6->HrefValue = "";
			$this->COL_6->TooltipValue = "";

			// t_lab_db_id
			$this->t_lab_db_id->LinkCustomAttributes = "";
			$this->t_lab_db_id->HrefValue = "";
			$this->t_lab_db_id->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// COL 2
			$this->COL_2->EditAttrs["class"] = "form-control";
			$this->COL_2->EditCustomAttributes = "";
			$this->COL_2->EditValue = ew_HtmlEncode($this->COL_2->CurrentValue);
			$this->COL_2->PlaceHolder = ew_RemoveHtml($this->COL_2->FldCaption());

			// COL 4
			$this->COL_4->EditAttrs["class"] = "form-control";
			$this->COL_4->EditCustomAttributes = "";
			$this->COL_4->EditValue = ew_HtmlEncode($this->COL_4->CurrentValue);
			$this->COL_4->PlaceHolder = ew_RemoveHtml($this->COL_4->FldCaption());

			// COL 5
			$this->COL_5->EditAttrs["class"] = "form-control";
			$this->COL_5->EditCustomAttributes = "";
			$this->COL_5->EditValue = ew_HtmlEncode($this->COL_5->CurrentValue);
			$this->COL_5->PlaceHolder = ew_RemoveHtml($this->COL_5->FldCaption());

			// COL 6
			$this->COL_6->EditAttrs["class"] = "form-control";
			$this->COL_6->EditCustomAttributes = "";
			$this->COL_6->EditValue = ew_HtmlEncode($this->COL_6->CurrentValue);
			$this->COL_6->PlaceHolder = ew_RemoveHtml($this->COL_6->FldCaption());
			if (strval($this->COL_6->EditValue) <> "" && is_numeric($this->COL_6->EditValue)) $this->COL_6->EditValue = ew_FormatNumber($this->COL_6->EditValue, -2, -1, -2, 0);

			// t_lab_db_id
			$this->t_lab_db_id->EditCustomAttributes = "";
			if (trim(strval($this->t_lab_db_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->t_lab_db_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `COL 2` AS `DispFld`, `COL 3` AS `Disp2Fld`, `COL 4` AS `Disp3Fld`, `id` AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `t_lab_db`";
			$sWhereWrk = "";
			$this->t_lab_db_id->LookupFilters = array("dx1" => '`COL 2`', "dx2" => '`COL 3`', "dx3" => '`COL 4`', "dx4" => '`id`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->t_lab_db_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
				$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
				$arwrk[4] = ew_HtmlEncode($rswrk->fields('Disp4Fld'));
				$this->t_lab_db_id->ViewValue = $this->t_lab_db_id->DisplayValue($arwrk);
			} else {
				$this->t_lab_db_id->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->t_lab_db_id->EditValue = $arwrk;

			// Add refer script
			// COL 2

			$this->COL_2->LinkCustomAttributes = "";
			$this->COL_2->HrefValue = "";

			// COL 4
			$this->COL_4->LinkCustomAttributes = "";
			$this->COL_4->HrefValue = "";

			// COL 5
			$this->COL_5->LinkCustomAttributes = "";
			$this->COL_5->HrefValue = "";

			// COL 6
			$this->COL_6->LinkCustomAttributes = "";
			$this->COL_6->HrefValue = "";

			// t_lab_db_id
			$this->t_lab_db_id->LinkCustomAttributes = "";
			$this->t_lab_db_id->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!ew_CheckNumber($this->COL_6->FormValue)) {
			ew_AddMessage($gsFormError, $this->COL_6->FldErrMsg());
		}
		if (!$this->t_lab_db_id->FldIsDetailKey && !is_null($this->t_lab_db_id->FormValue) && $this->t_lab_db_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->t_lab_db_id->FldCaption(), $this->t_lab_db_id->ReqErrMsg));
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// COL 2
		$this->COL_2->SetDbValueDef($rsnew, $this->COL_2->CurrentValue, NULL, FALSE);

		// COL 4
		$this->COL_4->SetDbValueDef($rsnew, $this->COL_4->CurrentValue, NULL, FALSE);

		// COL 5
		$this->COL_5->SetDbValueDef($rsnew, $this->COL_5->CurrentValue, NULL, FALSE);

		// COL 6
		$this->COL_6->SetDbValueDef($rsnew, $this->COL_6->CurrentValue, NULL, FALSE);

		// t_lab_db_id
		$this->t_lab_db_id->SetDbValueDef($rsnew, $this->t_lab_db_id->CurrentValue, 0, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t_201412list.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_t_lab_db_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `COL 2` AS `DispFld`, `COL 3` AS `Disp2Fld`, `COL 4` AS `Disp3Fld`, `id` AS `Disp4Fld` FROM `t_lab_db`";
			$sWhereWrk = "{filter}";
			$this->t_lab_db_id->LookupFilters = array("dx1" => '`COL 2`', "dx2" => '`COL 3`', "dx3" => '`COL 4`', "dx4" => '`id`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` = {filter_value}', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->t_lab_db_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($t_201412_add)) $t_201412_add = new ct_201412_add();

// Page init
$t_201412_add->Page_Init();

// Page main
$t_201412_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_201412_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = ft_201412add = new ew_Form("ft_201412add", "add");

// Validate form
ft_201412add.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_COL_6");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_201412->COL_6->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_t_lab_db_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_201412->t_lab_db_id->FldCaption(), $t_201412->t_lab_db_id->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
ft_201412add.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_201412add.ValidateRequired = true;
<?php } else { ?>
ft_201412add.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_201412add.Lists["x_t_lab_db_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_COL_2","x_COL_3","x_COL_4","x_id"],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_lab_db"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$t_201412_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $t_201412_add->ShowPageHeader(); ?>
<?php
$t_201412_add->ShowMessage();
?>
<form name="ft_201412add" id="ft_201412add" class="<?php echo $t_201412_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_201412_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_201412_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_201412">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($t_201412_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($t_201412->COL_2->Visible) { // COL 2 ?>
	<div id="r_COL_2" class="form-group">
		<label id="elh_t_201412_COL_2" for="x_COL_2" class="col-sm-2 control-label ewLabel"><?php echo $t_201412->COL_2->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_201412->COL_2->CellAttributes() ?>>
<span id="el_t_201412_COL_2">
<input type="text" data-table="t_201412" data-field="x_COL_2" name="x_COL_2" id="x_COL_2" size="30" maxlength="12" placeholder="<?php echo ew_HtmlEncode($t_201412->COL_2->getPlaceHolder()) ?>" value="<?php echo $t_201412->COL_2->EditValue ?>"<?php echo $t_201412->COL_2->EditAttributes() ?>>
</span>
<?php echo $t_201412->COL_2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_201412->COL_4->Visible) { // COL 4 ?>
	<div id="r_COL_4" class="form-group">
		<label id="elh_t_201412_COL_4" for="x_COL_4" class="col-sm-2 control-label ewLabel"><?php echo $t_201412->COL_4->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_201412->COL_4->CellAttributes() ?>>
<span id="el_t_201412_COL_4">
<input type="text" data-table="t_201412" data-field="x_COL_4" name="x_COL_4" id="x_COL_4" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($t_201412->COL_4->getPlaceHolder()) ?>" value="<?php echo $t_201412->COL_4->EditValue ?>"<?php echo $t_201412->COL_4->EditAttributes() ?>>
</span>
<?php echo $t_201412->COL_4->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_201412->COL_5->Visible) { // COL 5 ?>
	<div id="r_COL_5" class="form-group">
		<label id="elh_t_201412_COL_5" for="x_COL_5" class="col-sm-2 control-label ewLabel"><?php echo $t_201412->COL_5->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_201412->COL_5->CellAttributes() ?>>
<span id="el_t_201412_COL_5">
<input type="text" data-table="t_201412" data-field="x_COL_5" name="x_COL_5" id="x_COL_5" size="30" maxlength="5" placeholder="<?php echo ew_HtmlEncode($t_201412->COL_5->getPlaceHolder()) ?>" value="<?php echo $t_201412->COL_5->EditValue ?>"<?php echo $t_201412->COL_5->EditAttributes() ?>>
</span>
<?php echo $t_201412->COL_5->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_201412->COL_6->Visible) { // COL 6 ?>
	<div id="r_COL_6" class="form-group">
		<label id="elh_t_201412_COL_6" for="x_COL_6" class="col-sm-2 control-label ewLabel"><?php echo $t_201412->COL_6->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_201412->COL_6->CellAttributes() ?>>
<span id="el_t_201412_COL_6">
<input type="text" data-table="t_201412" data-field="x_COL_6" name="x_COL_6" id="x_COL_6" size="30" placeholder="<?php echo ew_HtmlEncode($t_201412->COL_6->getPlaceHolder()) ?>" value="<?php echo $t_201412->COL_6->EditValue ?>"<?php echo $t_201412->COL_6->EditAttributes() ?>>
</span>
<?php echo $t_201412->COL_6->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_201412->t_lab_db_id->Visible) { // t_lab_db_id ?>
	<div id="r_t_lab_db_id" class="form-group">
		<label id="elh_t_201412_t_lab_db_id" for="x_t_lab_db_id" class="col-sm-2 control-label ewLabel"><?php echo $t_201412->t_lab_db_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_201412->t_lab_db_id->CellAttributes() ?>>
<span id="el_t_201412_t_lab_db_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_t_lab_db_id"><?php echo (strval($t_201412->t_lab_db_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_201412->t_lab_db_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_201412->t_lab_db_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_t_lab_db_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_201412" data-field="x_t_lab_db_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_201412->t_lab_db_id->DisplayValueSeparatorAttribute() ?>" name="x_t_lab_db_id" id="x_t_lab_db_id" value="<?php echo $t_201412->t_lab_db_id->CurrentValue ?>"<?php echo $t_201412->t_lab_db_id->EditAttributes() ?>>
<input type="hidden" name="s_x_t_lab_db_id" id="s_x_t_lab_db_id" value="<?php echo $t_201412->t_lab_db_id->LookupFilterQuery() ?>">
</span>
<?php echo $t_201412->t_lab_db_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$t_201412_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t_201412_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
ft_201412add.Init();
</script>
<?php
$t_201412_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_201412_add->Page_Terminate();
?>
