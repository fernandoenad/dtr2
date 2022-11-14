<?php
// Calculate script start/end time
function get_microtime() {
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}

// Define script start time
define("START_TIME", get_microtime());
define("IN_FUSION", TRUE);
ini_set('max_execution_time', 300); //300 seconds = 5 minutes

// Establish mySQL database connection
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "sanhsmis";
$link = dbconnect($db_host, $db_user, $db_pass, $db_name);
unset($db_host, $db_user, $db_pass);

// Variables initializing
$mysql_queries_count = 0;
$mysql_queries_time = array();

// MySQL database functions
function dbquery($query) {
	global $mysql_queries_count, $mysql_queries_time; $mysql_queries_count++;
	global $db_connect; 
	
	$query_time = get_microtime();
	$result = @mysqli_query($db_connect, $query);
	$query_time = substr((get_microtime() - $query_time),0,7);

	$mysql_queries_time[$mysql_queries_count] = array($query_time, $query);

	return $result;	
}

function dbcount($field, $table, $conditions = "") {
	global $mysql_queries_count, $mysql_queries_time; $mysql_queries_count++;

	$cond = ($conditions ? " WHERE ".$conditions : "");
	$query_time = get_microtime();
	$result = @mysql_query("SELECT Count".$field." FROM ".$table.$cond);
	$query_time = substr((get_microtime() - $query_time),0,7);

	$mysql_queries_time[$mysql_queries_count] = array($query_time, "SELECT COUNT".$field." FROM ".$table.$cond);

	return $rows;
}

function dbresult($query, $row) {
	global $mysql_queries_count, $mysql_queries_time;

	$query_time = get_microtime();
	$result = @mysql_result($query, $row);
	$query_time = substr((get_microtime() - $query_time),0,7);

	$mysql_queries_time[$mysql_queries_count] = array($query_time, $query);

	return $result;
}

function dbrows($query) {
	$result = @mysqli_num_rows($query);
	
	return $result;
}

function dbarray($query) {
	$result = @mysqli_fetch_assoc($query);
	
	return $result;
}

function dbarraynum($query) {
	$result = @mysql_fetch_row($query);

	return $result;
}

function dbconnect($db_host, $db_user, $db_pass, $db_name) {
	global $db_connect;

	$db_connect = @mysqli_connect($db_host, $db_user, $db_pass);
	$db_select = @mysqli_select_db($db_connect, $db_name);
}

// Site Global Settings
$selectGlobalSettings = dbquery("SELECT * FROM settings WHERE activated='1'");
$rowGlobalSettings = dbarray($selectGlobalSettings);
$current_sy = $rowGlobalSettings['settings_sy'];
$current_sem = $rowGlobalSettings['settings_sem'];
$current_pros = $rowGlobalSettings['settings_pros'];
$current_registrar = $rowGlobalSettings['settings_registrar'];
$current_principal = $rowGlobalSettings['settings_principal'];
$current_psds = $rowGlobalSettings['settings_supervisor'];
$current_representative = $rowGlobalSettings['settings_representative'];
$current_superintendent = $rowGlobalSettings['settings_superintendent'];
$current_bosy = $rowGlobalSettings['settings_bosy'];
$current_eosy = $rowGlobalSettings['settings_eosy'];
$current_closing = $rowGlobalSettings['settings_closing'];
//$current_user = $_SESSION["user_fullname"];
$current_month = $rowGlobalSettings['settings_month'];
$eoyupdate = $rowGlobalSettings['settings_eosynow'];
$earlyregistrationOn = $rowGlobalSettings['settings_earlyreg'];
$current_dow = (date("m")==1?date("Y")."-01-31":$current_bosy);

$login_message = $rowGlobalSettings['settings_loginmessage'];
$admission_message = $rowGlobalSettings['settings_admissionmessage'];

$check_settings = dbquery("select * from license limit 1");
$data_settings = dbarray($check_settings);
$current_school_name= $data_settings['current_school_name'];
$current_school_full = $data_settings['current_school_full'];
$current_school_short = $data_settings['current_school_short'];
$current_school_code = $data_settings['current_school_code'];
$current_school_address = $data_settings['current_school_address'];
$current_school_district = $data_settings['current_school_district'];
$current_school_division = $data_settings['current_school_division'];
$current_school_region = $data_settings['current_school_region'];
$current_school_reg_code = $data_settings['current_school_reg_code'];
$current_school_contact = $data_settings['current_school_contact'];
$current_school_email = $data_settings['current_school_email'];
$current_school_minlevel = $data_settings['current_school_minlevel'];
$current_school_maxlevel = $data_settings['current_school_maxlevel'];
$current_footer = "Copyright &copy 2016. <a href=''>".$current_school_short." Employee Information System</a>. All  rights reserved.";
$current_footer_www = "Copyright &copy 2017. <a href=''>".$current_school_short." Portal</a>. All  rights reserved.";
$current_footer_ams = "Copyright &copy 2017. <a href=''>".$current_school_short." Attendance Management System</a>. All  rights reserved.";

$app_name = "EIS-DTR";
$app_fullname = "SDO Bohol Employee Information System for Daily Time Record";
?>
