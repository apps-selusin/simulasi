<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "t_201412info.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$t_201412_edit = NULL; // Initialize page object first

class ct_201412_edit extends ct_201412 {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{252C59E6-E1DC-4A28-9DE4-060829D3BFB7}';

	// Table name
	var $TableName = 't_201412';

	// Page object name
	var $PageObjName = 't_201412_edit';

	// Page headings
	var $Heading = '';
	var $Subheading = '';

	// Page heading
	function PageHeading() {
		global $Language;
		if ($this->Heading <> "")
			return $this->Heading;
		if (method_exists($this, "TableCaption"))
			return $this->TableCaption();
		return "";
	}

	// Page subheading
	function PageSubheading() {
		global $Language;
		if ($this->Subheading <> "")
			return $this->Subheading;
		if ($this->TableName)
			return $Language->Phrase($this->PageID);
		return "";
	}

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
		if (!$this->CheckToken || !ew_IsPost())
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
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_201412', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"]))
			$GLOBALS["gTimer"] = new cTimer();

		// Debug message
		ew_LoadDebugMsg();

		// Open connection
		if (!isset($conn))
			$conn = ew_Connect($this->DBID);
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Is modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->id->Visible = FALSE;
		$this->COL_1->SetVisibility();
		$this->COL_2->SetVisibility();
		$this->COL_3->SetVisibility();
		$this->COL_4->SetVisibility();
		$this->COL_5->SetVisibility();
		$this->COL_6->SetVisibility();
		$this->COL_7->SetVisibility();
		$this->COL_8->SetVisibility();
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
			if ($this->IsModal) { // Show as modal
				$row = array("url" => $url, "modal" => "1");
				$pageName = ew_GetPageName($url);
				if ($pageName != $this->GetListUrl()) { // Not List page
					$row["caption"] = $this->GetModalCaption($pageName);
					if ($pageName == "t_201412view.php")
						$row["view"] = "1";
				} else { // List page should not be shown as modal => error
					$row["error"] = $this->getFailureMessage();
					$this->clearFailureMessage();
				}
				header("Content-Type: application/json; charset=utf-8");
				echo ew_ConvertToUtf8(ew_ArrayToJson(array($row)));
			} else {
				ew_SaveDebugMsg();
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $IsModal = FALSE;
	var $IsMobileOrModal = FALSE;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $AutoHidePager = EW_AUTO_HIDE_PAGER;
	var $RecCnt;
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gbSkipHeaderFooter;

		// Check modal
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$this->IsMobileOrModal = ew_IsMobile() || $this->IsModal;
		$this->FormClassName = "ewForm ewEditForm form-horizontal";

		// Load record by position
		$loadByPosition = FALSE;
		$sReturnUrl = "";
		$loaded = FALSE;
		$postBack = FALSE;

		// Set up current action and primary key
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			if ($this->CurrentAction <> "I") // Not reload record, handle as postback
				$postBack = TRUE;

			// Load key from Form
			if ($objForm->HasValue("x_id")) {
				$this->id->setFormValue($objForm->GetValue("x_id"));
			}
		} else {
			$this->CurrentAction = "I"; // Default action is display

			// Load key from QueryString
			$loadByQuery = FALSE;
			if (isset($_GET["id"])) {
				$this->id->setQueryStringValue($_GET["id"]);
				$loadByQuery = TRUE;
			} else {
				$this->id->CurrentValue = NULL;
			}
			if (!$loadByQuery)
				$loadByPosition = TRUE;
		}

		// Load recordset
		$this->StartRec = 1; // Initialize start position
		if ($this->Recordset = $this->LoadRecordset()) // Load records
			$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
		if ($this->TotalRecs <= 0) { // No record found
			if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$this->Page_Terminate("t_201412list.php"); // Return to list page
		} elseif ($loadByPosition) { // Load record by position
			$this->SetupStartRec(); // Set up start record position

			// Point to current record
			if (intval($this->StartRec) <= intval($this->TotalRecs)) {
				$this->Recordset->Move($this->StartRec-1);
				$loaded = TRUE;
			}
		} else { // Match key values
			if (!is_null($this->id->CurrentValue)) {
				while (!$this->Recordset->EOF) {
					if (strval($this->id->CurrentValue) == strval($this->Recordset->fields('id'))) {
						$this->setStartRecordNumber($this->StartRec); // Save record position
						$loaded = TRUE;
						break;
					} else {
						$this->StartRec++;
						$this->Recordset->MoveNext();
					}
				}
			}
		}

		// Load current row values
		if ($loaded)
			$this->LoadRowValues($this->Recordset);

		// Process form if post back
		if ($postBack) {
			$this->LoadFormValues(); // Get form values
		}

		// Validate form if post back
		if ($postBack) {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}

		// Perform current action
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$loaded) {
					if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
						$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
					$this->Page_Terminate("t_201412list.php"); // Return to list page
				} else {
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "t_201412list.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetupStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id->FldIsDetailKey)
			$this->id->setFormValue($objForm->GetValue("x_id"));
		if (!$this->COL_1->FldIsDetailKey) {
			$this->COL_1->setFormValue($objForm->GetValue("x_COL_1"));
		}
		if (!$this->COL_2->FldIsDetailKey) {
			$this->COL_2->setFormValue($objForm->GetValue("x_COL_2"));
		}
		if (!$this->COL_3->FldIsDetailKey) {
			$this->COL_3->setFormValue($objForm->GetValue("x_COL_3"));
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
		if (!$this->COL_7->FldIsDetailKey) {
			$this->COL_7->setFormValue($objForm->GetValue("x_COL_7"));
		}
		if (!$this->COL_8->FldIsDetailKey) {
			$this->COL_8->setFormValue($objForm->GetValue("x_COL_8"));
		}
		if (!$this->t_lab_db_id->FldIsDetailKey) {
			$this->t_lab_db_id->setFormValue($objForm->GetValue("x_t_lab_db_id"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->id->CurrentValue = $this->id->FormValue;
		$this->COL_1->CurrentValue = $this->COL_1->FormValue;
		$this->COL_2->CurrentValue = $this->COL_2->FormValue;
		$this->COL_3->CurrentValue = $this->COL_3->FormValue;
		$this->COL_4->CurrentValue = $this->COL_4->FormValue;
		$this->COL_5->CurrentValue = $this->COL_5->FormValue;
		$this->COL_6->CurrentValue = $this->COL_6->FormValue;
		$this->COL_7->CurrentValue = $this->COL_7->FormValue;
		$this->COL_8->CurrentValue = $this->COL_8->FormValue;
		$this->t_lab_db_id->CurrentValue = $this->t_lab_db_id->FormValue;
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->ListSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
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
	function LoadRowValues($rs = NULL) {
		if ($rs && !$rs->EOF)
			$row = $rs->fields;
		else
			$row = $this->NewRow(); 

		// Call Row Selected event
		$this->Row_Selected($row);
		if (!$rs || $rs->EOF)
			return;
		$this->id->setDbValue($row['id']);
		$this->COL_1->setDbValue($row['COL 1']);
		$this->COL_2->setDbValue($row['COL 2']);
		$this->COL_3->setDbValue($row['COL 3']);
		$this->COL_4->setDbValue($row['COL 4']);
		$this->COL_5->setDbValue($row['COL 5']);
		$this->COL_6->setDbValue($row['COL 6']);
		$this->COL_7->setDbValue($row['COL 7']);
		$this->COL_8->setDbValue($row['COL 8']);
		$this->t_lab_db_id->setDbValue($row['t_lab_db_id']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['COL 1'] = NULL;
		$row['COL 2'] = NULL;
		$row['COL 3'] = NULL;
		$row['COL 4'] = NULL;
		$row['COL 5'] = NULL;
		$row['COL 6'] = NULL;
		$row['COL 7'] = NULL;
		$row['COL 8'] = NULL;
		$row['t_lab_db_id'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
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

		// Load old record
		$this->OldRecordset = NULL;
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
		}
		$this->LoadRowValues($this->OldRecordset); // Load row values
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->COL_6->FormValue == $this->COL_6->CurrentValue && is_numeric(ew_StrToFloat($this->COL_6->CurrentValue)))
			$this->COL_6->CurrentValue = ew_StrToFloat($this->COL_6->CurrentValue);

		// Convert decimal values if posted back
		if ($this->COL_7->FormValue == $this->COL_7->CurrentValue && is_numeric(ew_StrToFloat($this->COL_7->CurrentValue)))
			$this->COL_7->CurrentValue = ew_StrToFloat($this->COL_7->CurrentValue);

		// Convert decimal values if posted back
		if ($this->COL_8->FormValue == $this->COL_8->CurrentValue && is_numeric(ew_StrToFloat($this->COL_8->CurrentValue)))
			$this->COL_8->CurrentValue = ew_StrToFloat($this->COL_8->CurrentValue);

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
		$this->t_lab_db_id->ViewValue = $this->t_lab_db_id->CurrentValue;
		$this->t_lab_db_id->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// COL 1
			$this->COL_1->LinkCustomAttributes = "";
			$this->COL_1->HrefValue = "";
			$this->COL_1->TooltipValue = "";

			// COL 2
			$this->COL_2->LinkCustomAttributes = "";
			$this->COL_2->HrefValue = "";
			$this->COL_2->TooltipValue = "";

			// COL 3
			$this->COL_3->LinkCustomAttributes = "";
			$this->COL_3->HrefValue = "";
			$this->COL_3->TooltipValue = "";

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

			// COL 7
			$this->COL_7->LinkCustomAttributes = "";
			$this->COL_7->HrefValue = "";
			$this->COL_7->TooltipValue = "";

			// COL 8
			$this->COL_8->LinkCustomAttributes = "";
			$this->COL_8->HrefValue = "";
			$this->COL_8->TooltipValue = "";

			// t_lab_db_id
			$this->t_lab_db_id->LinkCustomAttributes = "";
			$this->t_lab_db_id->HrefValue = "";
			$this->t_lab_db_id->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// COL 1
			$this->COL_1->EditAttrs["class"] = "form-control";
			$this->COL_1->EditCustomAttributes = "";
			$this->COL_1->EditValue = ew_HtmlEncode($this->COL_1->CurrentValue);
			$this->COL_1->PlaceHolder = ew_RemoveHtml($this->COL_1->FldCaption());

			// COL 2
			$this->COL_2->EditAttrs["class"] = "form-control";
			$this->COL_2->EditCustomAttributes = "";
			$this->COL_2->EditValue = ew_HtmlEncode($this->COL_2->CurrentValue);
			$this->COL_2->PlaceHolder = ew_RemoveHtml($this->COL_2->FldCaption());

			// COL 3
			$this->COL_3->EditAttrs["class"] = "form-control";
			$this->COL_3->EditCustomAttributes = "";
			$this->COL_3->EditValue = ew_HtmlEncode($this->COL_3->CurrentValue);
			$this->COL_3->PlaceHolder = ew_RemoveHtml($this->COL_3->FldCaption());

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

			// COL 7
			$this->COL_7->EditAttrs["class"] = "form-control";
			$this->COL_7->EditCustomAttributes = "";
			$this->COL_7->EditValue = ew_HtmlEncode($this->COL_7->CurrentValue);
			$this->COL_7->PlaceHolder = ew_RemoveHtml($this->COL_7->FldCaption());
			if (strval($this->COL_7->EditValue) <> "" && is_numeric($this->COL_7->EditValue)) $this->COL_7->EditValue = ew_FormatNumber($this->COL_7->EditValue, -2, -1, -2, 0);

			// COL 8
			$this->COL_8->EditAttrs["class"] = "form-control";
			$this->COL_8->EditCustomAttributes = "";
			$this->COL_8->EditValue = ew_HtmlEncode($this->COL_8->CurrentValue);
			$this->COL_8->PlaceHolder = ew_RemoveHtml($this->COL_8->FldCaption());
			if (strval($this->COL_8->EditValue) <> "" && is_numeric($this->COL_8->EditValue)) $this->COL_8->EditValue = ew_FormatNumber($this->COL_8->EditValue, -2, -1, -2, 0);

			// t_lab_db_id
			$this->t_lab_db_id->EditAttrs["class"] = "form-control";
			$this->t_lab_db_id->EditCustomAttributes = "";
			$this->t_lab_db_id->EditValue = ew_HtmlEncode($this->t_lab_db_id->CurrentValue);
			$this->t_lab_db_id->PlaceHolder = ew_RemoveHtml($this->t_lab_db_id->FldCaption());

			// Edit refer script
			// id

			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";

			// COL 1
			$this->COL_1->LinkCustomAttributes = "";
			$this->COL_1->HrefValue = "";

			// COL 2
			$this->COL_2->LinkCustomAttributes = "";
			$this->COL_2->HrefValue = "";

			// COL 3
			$this->COL_3->LinkCustomAttributes = "";
			$this->COL_3->HrefValue = "";

			// COL 4
			$this->COL_4->LinkCustomAttributes = "";
			$this->COL_4->HrefValue = "";

			// COL 5
			$this->COL_5->LinkCustomAttributes = "";
			$this->COL_5->HrefValue = "";

			// COL 6
			$this->COL_6->LinkCustomAttributes = "";
			$this->COL_6->HrefValue = "";

			// COL 7
			$this->COL_7->LinkCustomAttributes = "";
			$this->COL_7->HrefValue = "";

			// COL 8
			$this->COL_8->LinkCustomAttributes = "";
			$this->COL_8->HrefValue = "";

			// t_lab_db_id
			$this->t_lab_db_id->LinkCustomAttributes = "";
			$this->t_lab_db_id->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD || $this->RowType == EW_ROWTYPE_EDIT || $this->RowType == EW_ROWTYPE_SEARCH) // Add/Edit/Search row
			$this->SetupFieldTitles();

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
		if (!ew_CheckNumber($this->COL_7->FormValue)) {
			ew_AddMessage($gsFormError, $this->COL_7->FldErrMsg());
		}
		if (!ew_CheckNumber($this->COL_8->FormValue)) {
			ew_AddMessage($gsFormError, $this->COL_8->FldErrMsg());
		}
		if (!$this->t_lab_db_id->FldIsDetailKey && !is_null($this->t_lab_db_id->FormValue) && $this->t_lab_db_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->t_lab_db_id->FldCaption(), $this->t_lab_db_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->t_lab_db_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->t_lab_db_id->FldErrMsg());
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

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// COL 1
			$this->COL_1->SetDbValueDef($rsnew, $this->COL_1->CurrentValue, NULL, $this->COL_1->ReadOnly);

			// COL 2
			$this->COL_2->SetDbValueDef($rsnew, $this->COL_2->CurrentValue, NULL, $this->COL_2->ReadOnly);

			// COL 3
			$this->COL_3->SetDbValueDef($rsnew, $this->COL_3->CurrentValue, NULL, $this->COL_3->ReadOnly);

			// COL 4
			$this->COL_4->SetDbValueDef($rsnew, $this->COL_4->CurrentValue, NULL, $this->COL_4->ReadOnly);

			// COL 5
			$this->COL_5->SetDbValueDef($rsnew, $this->COL_5->CurrentValue, NULL, $this->COL_5->ReadOnly);

			// COL 6
			$this->COL_6->SetDbValueDef($rsnew, $this->COL_6->CurrentValue, NULL, $this->COL_6->ReadOnly);

			// COL 7
			$this->COL_7->SetDbValueDef($rsnew, $this->COL_7->CurrentValue, NULL, $this->COL_7->ReadOnly);

			// COL 8
			$this->COL_8->SetDbValueDef($rsnew, $this->COL_8->CurrentValue, NULL, $this->COL_8->ReadOnly);

			// t_lab_db_id
			$this->t_lab_db_id->SetDbValueDef($rsnew, $this->t_lab_db_id->CurrentValue, 0, $this->t_lab_db_id->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t_201412list.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
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
if (!isset($t_201412_edit)) $t_201412_edit = new ct_201412_edit();

// Page init
$t_201412_edit->Page_Init();

// Page main
$t_201412_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_201412_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = ft_201412edit = new ew_Form("ft_201412edit", "edit");

// Validate form
ft_201412edit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_COL_7");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_201412->COL_7->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_COL_8");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_201412->COL_8->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_t_lab_db_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_201412->t_lab_db_id->FldCaption(), $t_201412->t_lab_db_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_t_lab_db_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_201412->t_lab_db_id->FldErrMsg()) ?>");

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
ft_201412edit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
ft_201412edit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $t_201412_edit->ShowPageHeader(); ?>
<?php
$t_201412_edit->ShowMessage();
?>
<?php if (!$t_201412_edit->IsModal) { ?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($t_201412_edit->Pager)) $t_201412_edit->Pager = new cPrevNextPager($t_201412_edit->StartRec, $t_201412_edit->DisplayRecs, $t_201412_edit->TotalRecs, $t_201412_edit->AutoHidePager) ?>
<?php if ($t_201412_edit->Pager->RecordCount > 0 && $t_201412_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($t_201412_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $t_201412_edit->PageUrl() ?>start=<?php echo $t_201412_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_201412_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $t_201412_edit->PageUrl() ?>start=<?php echo $t_201412_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_201412_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($t_201412_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $t_201412_edit->PageUrl() ?>start=<?php echo $t_201412_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_201412_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $t_201412_edit->PageUrl() ?>start=<?php echo $t_201412_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_201412_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="ft_201412edit" id="ft_201412edit" class="<?php echo $t_201412_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_201412_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_201412_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_201412">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($t_201412_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($t_201412->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_t_201412_id" class="<?php echo $t_201412_edit->LeftColumnClass ?>"><?php echo $t_201412->id->FldCaption() ?></label>
		<div class="<?php echo $t_201412_edit->RightColumnClass ?>"><div<?php echo $t_201412->id->CellAttributes() ?>>
<span id="el_t_201412_id">
<span<?php echo $t_201412->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_201412->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_201412" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($t_201412->id->CurrentValue) ?>">
<?php echo $t_201412->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_201412->COL_1->Visible) { // COL 1 ?>
	<div id="r_COL_1" class="form-group">
		<label id="elh_t_201412_COL_1" for="x_COL_1" class="<?php echo $t_201412_edit->LeftColumnClass ?>"><?php echo $t_201412->COL_1->FldCaption() ?></label>
		<div class="<?php echo $t_201412_edit->RightColumnClass ?>"><div<?php echo $t_201412->COL_1->CellAttributes() ?>>
<span id="el_t_201412_COL_1">
<input type="text" data-table="t_201412" data-field="x_COL_1" name="x_COL_1" id="x_COL_1" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($t_201412->COL_1->getPlaceHolder()) ?>" value="<?php echo $t_201412->COL_1->EditValue ?>"<?php echo $t_201412->COL_1->EditAttributes() ?>>
</span>
<?php echo $t_201412->COL_1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_201412->COL_2->Visible) { // COL 2 ?>
	<div id="r_COL_2" class="form-group">
		<label id="elh_t_201412_COL_2" for="x_COL_2" class="<?php echo $t_201412_edit->LeftColumnClass ?>"><?php echo $t_201412->COL_2->FldCaption() ?></label>
		<div class="<?php echo $t_201412_edit->RightColumnClass ?>"><div<?php echo $t_201412->COL_2->CellAttributes() ?>>
<span id="el_t_201412_COL_2">
<input type="text" data-table="t_201412" data-field="x_COL_2" name="x_COL_2" id="x_COL_2" size="30" maxlength="12" placeholder="<?php echo ew_HtmlEncode($t_201412->COL_2->getPlaceHolder()) ?>" value="<?php echo $t_201412->COL_2->EditValue ?>"<?php echo $t_201412->COL_2->EditAttributes() ?>>
</span>
<?php echo $t_201412->COL_2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_201412->COL_3->Visible) { // COL 3 ?>
	<div id="r_COL_3" class="form-group">
		<label id="elh_t_201412_COL_3" for="x_COL_3" class="<?php echo $t_201412_edit->LeftColumnClass ?>"><?php echo $t_201412->COL_3->FldCaption() ?></label>
		<div class="<?php echo $t_201412_edit->RightColumnClass ?>"><div<?php echo $t_201412->COL_3->CellAttributes() ?>>
<span id="el_t_201412_COL_3">
<input type="text" data-table="t_201412" data-field="x_COL_3" name="x_COL_3" id="x_COL_3" size="30" maxlength="7" placeholder="<?php echo ew_HtmlEncode($t_201412->COL_3->getPlaceHolder()) ?>" value="<?php echo $t_201412->COL_3->EditValue ?>"<?php echo $t_201412->COL_3->EditAttributes() ?>>
</span>
<?php echo $t_201412->COL_3->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_201412->COL_4->Visible) { // COL 4 ?>
	<div id="r_COL_4" class="form-group">
		<label id="elh_t_201412_COL_4" for="x_COL_4" class="<?php echo $t_201412_edit->LeftColumnClass ?>"><?php echo $t_201412->COL_4->FldCaption() ?></label>
		<div class="<?php echo $t_201412_edit->RightColumnClass ?>"><div<?php echo $t_201412->COL_4->CellAttributes() ?>>
<span id="el_t_201412_COL_4">
<input type="text" data-table="t_201412" data-field="x_COL_4" name="x_COL_4" id="x_COL_4" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($t_201412->COL_4->getPlaceHolder()) ?>" value="<?php echo $t_201412->COL_4->EditValue ?>"<?php echo $t_201412->COL_4->EditAttributes() ?>>
</span>
<?php echo $t_201412->COL_4->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_201412->COL_5->Visible) { // COL 5 ?>
	<div id="r_COL_5" class="form-group">
		<label id="elh_t_201412_COL_5" for="x_COL_5" class="<?php echo $t_201412_edit->LeftColumnClass ?>"><?php echo $t_201412->COL_5->FldCaption() ?></label>
		<div class="<?php echo $t_201412_edit->RightColumnClass ?>"><div<?php echo $t_201412->COL_5->CellAttributes() ?>>
<span id="el_t_201412_COL_5">
<input type="text" data-table="t_201412" data-field="x_COL_5" name="x_COL_5" id="x_COL_5" size="30" maxlength="5" placeholder="<?php echo ew_HtmlEncode($t_201412->COL_5->getPlaceHolder()) ?>" value="<?php echo $t_201412->COL_5->EditValue ?>"<?php echo $t_201412->COL_5->EditAttributes() ?>>
</span>
<?php echo $t_201412->COL_5->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_201412->COL_6->Visible) { // COL 6 ?>
	<div id="r_COL_6" class="form-group">
		<label id="elh_t_201412_COL_6" for="x_COL_6" class="<?php echo $t_201412_edit->LeftColumnClass ?>"><?php echo $t_201412->COL_6->FldCaption() ?></label>
		<div class="<?php echo $t_201412_edit->RightColumnClass ?>"><div<?php echo $t_201412->COL_6->CellAttributes() ?>>
<span id="el_t_201412_COL_6">
<input type="text" data-table="t_201412" data-field="x_COL_6" name="x_COL_6" id="x_COL_6" size="30" placeholder="<?php echo ew_HtmlEncode($t_201412->COL_6->getPlaceHolder()) ?>" value="<?php echo $t_201412->COL_6->EditValue ?>"<?php echo $t_201412->COL_6->EditAttributes() ?>>
</span>
<?php echo $t_201412->COL_6->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_201412->COL_7->Visible) { // COL 7 ?>
	<div id="r_COL_7" class="form-group">
		<label id="elh_t_201412_COL_7" for="x_COL_7" class="<?php echo $t_201412_edit->LeftColumnClass ?>"><?php echo $t_201412->COL_7->FldCaption() ?></label>
		<div class="<?php echo $t_201412_edit->RightColumnClass ?>"><div<?php echo $t_201412->COL_7->CellAttributes() ?>>
<span id="el_t_201412_COL_7">
<input type="text" data-table="t_201412" data-field="x_COL_7" name="x_COL_7" id="x_COL_7" size="30" placeholder="<?php echo ew_HtmlEncode($t_201412->COL_7->getPlaceHolder()) ?>" value="<?php echo $t_201412->COL_7->EditValue ?>"<?php echo $t_201412->COL_7->EditAttributes() ?>>
</span>
<?php echo $t_201412->COL_7->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_201412->COL_8->Visible) { // COL 8 ?>
	<div id="r_COL_8" class="form-group">
		<label id="elh_t_201412_COL_8" for="x_COL_8" class="<?php echo $t_201412_edit->LeftColumnClass ?>"><?php echo $t_201412->COL_8->FldCaption() ?></label>
		<div class="<?php echo $t_201412_edit->RightColumnClass ?>"><div<?php echo $t_201412->COL_8->CellAttributes() ?>>
<span id="el_t_201412_COL_8">
<input type="text" data-table="t_201412" data-field="x_COL_8" name="x_COL_8" id="x_COL_8" size="30" placeholder="<?php echo ew_HtmlEncode($t_201412->COL_8->getPlaceHolder()) ?>" value="<?php echo $t_201412->COL_8->EditValue ?>"<?php echo $t_201412->COL_8->EditAttributes() ?>>
</span>
<?php echo $t_201412->COL_8->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_201412->t_lab_db_id->Visible) { // t_lab_db_id ?>
	<div id="r_t_lab_db_id" class="form-group">
		<label id="elh_t_201412_t_lab_db_id" for="x_t_lab_db_id" class="<?php echo $t_201412_edit->LeftColumnClass ?>"><?php echo $t_201412->t_lab_db_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $t_201412_edit->RightColumnClass ?>"><div<?php echo $t_201412->t_lab_db_id->CellAttributes() ?>>
<span id="el_t_201412_t_lab_db_id">
<input type="text" data-table="t_201412" data-field="x_t_lab_db_id" name="x_t_lab_db_id" id="x_t_lab_db_id" size="30" placeholder="<?php echo ew_HtmlEncode($t_201412->t_lab_db_id->getPlaceHolder()) ?>" value="<?php echo $t_201412->t_lab_db_id->EditValue ?>"<?php echo $t_201412->t_lab_db_id->EditAttributes() ?>>
</span>
<?php echo $t_201412->t_lab_db_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$t_201412_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $t_201412_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t_201412_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
<?php if (!$t_201412_edit->IsModal) { ?>
<?php if (!isset($t_201412_edit->Pager)) $t_201412_edit->Pager = new cPrevNextPager($t_201412_edit->StartRec, $t_201412_edit->DisplayRecs, $t_201412_edit->TotalRecs, $t_201412_edit->AutoHidePager) ?>
<?php if ($t_201412_edit->Pager->RecordCount > 0 && $t_201412_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($t_201412_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $t_201412_edit->PageUrl() ?>start=<?php echo $t_201412_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_201412_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $t_201412_edit->PageUrl() ?>start=<?php echo $t_201412_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_201412_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($t_201412_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $t_201412_edit->PageUrl() ?>start=<?php echo $t_201412_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_201412_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $t_201412_edit->PageUrl() ?>start=<?php echo $t_201412_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_201412_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
<?php } ?>
</form>
<script type="text/javascript">
ft_201412edit.Init();
</script>
<?php
$t_201412_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_201412_edit->Page_Terminate();
?>
