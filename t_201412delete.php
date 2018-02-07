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

$t_201412_delete = NULL; // Initialize page object first

class ct_201412_delete extends ct_201412 {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{252C59E6-E1DC-4A28-9DE4-060829D3BFB7}';

	// Table name
	var $TableName = 't_201412';

	// Page object name
	var $PageObjName = 't_201412_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
			ew_SaveDebugMsg();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("t_201412list.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in t_201412 class, t_201412info.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("t_201412list.php"); // Return to list
			}
		}
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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;
		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['id'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		}
		if (!$DeleteRows) {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t_201412list.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($t_201412_delete)) $t_201412_delete = new ct_201412_delete();

// Page init
$t_201412_delete->Page_Init();

// Page main
$t_201412_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_201412_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = ft_201412delete = new ew_Form("ft_201412delete", "delete");

// Form_CustomValidate event
ft_201412delete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
ft_201412delete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $t_201412_delete->ShowPageHeader(); ?>
<?php
$t_201412_delete->ShowMessage();
?>
<form name="ft_201412delete" id="ft_201412delete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_201412_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_201412_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_201412">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($t_201412_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($t_201412->id->Visible) { // id ?>
		<th class="<?php echo $t_201412->id->HeaderCellClass() ?>"><span id="elh_t_201412_id" class="t_201412_id"><?php echo $t_201412->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_201412->COL_1->Visible) { // COL 1 ?>
		<th class="<?php echo $t_201412->COL_1->HeaderCellClass() ?>"><span id="elh_t_201412_COL_1" class="t_201412_COL_1"><?php echo $t_201412->COL_1->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_201412->COL_2->Visible) { // COL 2 ?>
		<th class="<?php echo $t_201412->COL_2->HeaderCellClass() ?>"><span id="elh_t_201412_COL_2" class="t_201412_COL_2"><?php echo $t_201412->COL_2->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_201412->COL_3->Visible) { // COL 3 ?>
		<th class="<?php echo $t_201412->COL_3->HeaderCellClass() ?>"><span id="elh_t_201412_COL_3" class="t_201412_COL_3"><?php echo $t_201412->COL_3->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_201412->COL_4->Visible) { // COL 4 ?>
		<th class="<?php echo $t_201412->COL_4->HeaderCellClass() ?>"><span id="elh_t_201412_COL_4" class="t_201412_COL_4"><?php echo $t_201412->COL_4->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_201412->COL_5->Visible) { // COL 5 ?>
		<th class="<?php echo $t_201412->COL_5->HeaderCellClass() ?>"><span id="elh_t_201412_COL_5" class="t_201412_COL_5"><?php echo $t_201412->COL_5->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_201412->COL_6->Visible) { // COL 6 ?>
		<th class="<?php echo $t_201412->COL_6->HeaderCellClass() ?>"><span id="elh_t_201412_COL_6" class="t_201412_COL_6"><?php echo $t_201412->COL_6->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_201412->COL_7->Visible) { // COL 7 ?>
		<th class="<?php echo $t_201412->COL_7->HeaderCellClass() ?>"><span id="elh_t_201412_COL_7" class="t_201412_COL_7"><?php echo $t_201412->COL_7->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_201412->COL_8->Visible) { // COL 8 ?>
		<th class="<?php echo $t_201412->COL_8->HeaderCellClass() ?>"><span id="elh_t_201412_COL_8" class="t_201412_COL_8"><?php echo $t_201412->COL_8->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_201412->t_lab_db_id->Visible) { // t_lab_db_id ?>
		<th class="<?php echo $t_201412->t_lab_db_id->HeaderCellClass() ?>"><span id="elh_t_201412_t_lab_db_id" class="t_201412_t_lab_db_id"><?php echo $t_201412->t_lab_db_id->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$t_201412_delete->RecCnt = 0;
$i = 0;
while (!$t_201412_delete->Recordset->EOF) {
	$t_201412_delete->RecCnt++;
	$t_201412_delete->RowCnt++;

	// Set row properties
	$t_201412->ResetAttrs();
	$t_201412->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$t_201412_delete->LoadRowValues($t_201412_delete->Recordset);

	// Render row
	$t_201412_delete->RenderRow();
?>
	<tr<?php echo $t_201412->RowAttributes() ?>>
<?php if ($t_201412->id->Visible) { // id ?>
		<td<?php echo $t_201412->id->CellAttributes() ?>>
<span id="el<?php echo $t_201412_delete->RowCnt ?>_t_201412_id" class="t_201412_id">
<span<?php echo $t_201412->id->ViewAttributes() ?>>
<?php echo $t_201412->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_201412->COL_1->Visible) { // COL 1 ?>
		<td<?php echo $t_201412->COL_1->CellAttributes() ?>>
<span id="el<?php echo $t_201412_delete->RowCnt ?>_t_201412_COL_1" class="t_201412_COL_1">
<span<?php echo $t_201412->COL_1->ViewAttributes() ?>>
<?php echo $t_201412->COL_1->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_201412->COL_2->Visible) { // COL 2 ?>
		<td<?php echo $t_201412->COL_2->CellAttributes() ?>>
<span id="el<?php echo $t_201412_delete->RowCnt ?>_t_201412_COL_2" class="t_201412_COL_2">
<span<?php echo $t_201412->COL_2->ViewAttributes() ?>>
<?php echo $t_201412->COL_2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_201412->COL_3->Visible) { // COL 3 ?>
		<td<?php echo $t_201412->COL_3->CellAttributes() ?>>
<span id="el<?php echo $t_201412_delete->RowCnt ?>_t_201412_COL_3" class="t_201412_COL_3">
<span<?php echo $t_201412->COL_3->ViewAttributes() ?>>
<?php echo $t_201412->COL_3->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_201412->COL_4->Visible) { // COL 4 ?>
		<td<?php echo $t_201412->COL_4->CellAttributes() ?>>
<span id="el<?php echo $t_201412_delete->RowCnt ?>_t_201412_COL_4" class="t_201412_COL_4">
<span<?php echo $t_201412->COL_4->ViewAttributes() ?>>
<?php echo $t_201412->COL_4->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_201412->COL_5->Visible) { // COL 5 ?>
		<td<?php echo $t_201412->COL_5->CellAttributes() ?>>
<span id="el<?php echo $t_201412_delete->RowCnt ?>_t_201412_COL_5" class="t_201412_COL_5">
<span<?php echo $t_201412->COL_5->ViewAttributes() ?>>
<?php echo $t_201412->COL_5->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_201412->COL_6->Visible) { // COL 6 ?>
		<td<?php echo $t_201412->COL_6->CellAttributes() ?>>
<span id="el<?php echo $t_201412_delete->RowCnt ?>_t_201412_COL_6" class="t_201412_COL_6">
<span<?php echo $t_201412->COL_6->ViewAttributes() ?>>
<?php echo $t_201412->COL_6->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_201412->COL_7->Visible) { // COL 7 ?>
		<td<?php echo $t_201412->COL_7->CellAttributes() ?>>
<span id="el<?php echo $t_201412_delete->RowCnt ?>_t_201412_COL_7" class="t_201412_COL_7">
<span<?php echo $t_201412->COL_7->ViewAttributes() ?>>
<?php echo $t_201412->COL_7->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_201412->COL_8->Visible) { // COL 8 ?>
		<td<?php echo $t_201412->COL_8->CellAttributes() ?>>
<span id="el<?php echo $t_201412_delete->RowCnt ?>_t_201412_COL_8" class="t_201412_COL_8">
<span<?php echo $t_201412->COL_8->ViewAttributes() ?>>
<?php echo $t_201412->COL_8->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_201412->t_lab_db_id->Visible) { // t_lab_db_id ?>
		<td<?php echo $t_201412->t_lab_db_id->CellAttributes() ?>>
<span id="el<?php echo $t_201412_delete->RowCnt ?>_t_201412_t_lab_db_id" class="t_201412_t_lab_db_id">
<span<?php echo $t_201412->t_lab_db_id->ViewAttributes() ?>>
<?php echo $t_201412->t_lab_db_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$t_201412_delete->Recordset->MoveNext();
}
$t_201412_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t_201412_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
ft_201412delete.Init();
</script>
<?php
$t_201412_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_201412_delete->Page_Terminate();
?>
