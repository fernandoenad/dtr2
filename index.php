<?php
// Start the session
session_start();
require ('../eis/maincore.php');

if(isset($_POST['submit'])){
	$resultCheck = dbquery("SELECT * FROM teacher WHERE (teach_dialect='".$_POST['username']."' AND teach_status='1')");
	$rowCheck = dbrows($resultCheck );

	if ($rowCheck > 0){
		$dataCheck = dbarray($resultCheck);
		$personnel_no = $dataCheck['teach_id'];
		$personnel_barcode = $dataCheck['teach_dialect'];
		$personnel_name = $dataCheck['teach_lname'] . ", " . $dataCheck['teach_fname'] ." " . $dataCheck['teach_xname'] . " " . $dataCheck['teach_mname'];
		
		$checkSupervisor = dbquery("SELECT * FROM teacher WHERE (teach_no='".$dataCheck['teach_tin']."' AND teach_status='1')");
		$rowSupervisor = dbrows($checkSupervisor);
		
		if($rowSupervisor > 0){
			$dataSupervisor = dbarray($checkSupervisor);
			$personnel_supervisor = $dataSupervisor['teach_lname'] . ", " . $dataSupervisor['teach_fname'] . " " .  $dataSupervisor['teach_xname'];	
		} else {
			$personnel_supervisor = "N/A";
	
		}
		
		$checkLastLog = dbquery("SELECT * FROM checkinout WHERE USERID='".$dataCheck['teach_bio_no']."' ORDER BY CHECKTIME DESC");
		$rowLastLog = dbrows($checkLastLog);

		if($rowLastLog > 0){
			$dataLastLog = dbarray($checkLastLog);
			$checkType = ($dataLastLog['CHECKTYPE']=="I"?"O":"I");
		} else {
			$checkType = "I";
		}
		
		$insertLog = dbquery("INSERT INTO checkinout (USERID, CHECKTIME, CHECKTYPE) values('".$dataCheck['teach_bio_no']."', NOW(), '".$checkType."')");

		$checkLastLog = dbquery("SELECT * FROM checkinout WHERE USERID='".$dataCheck['teach_bio_no']."' ORDER BY CHECKTIME DESC");
		$dataLastLog = dbarray($checkLastLog);
		
		$personnel_status = ($dataLastLog['CHECKTYPE']=="I"?"Time IN":"Time OUT") . " @ " . date('g:i A', strtotime($dataLastLog['CHECKTIME']));
	} else{
		$personnel_no = "N/A";
		$personnel_barcode = "N/A";
		$personnel_name = "N/A";
		$personnel_supervisor = "N/A";
		$personnel_status = "Barcode not found, please try again!";
	}
} else {
	$personnel_no = "***";
	$personnel_barcode = "***";
	$personnel_name = "***";
	$personnel_supervisor = "***";
	$personnel_status = "Scan barcode or input your Employee #";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<meta name="description" content="The official website of San Agustin National High School - Sagbayan, Bohol">
    <meta name="author" content="Fernando B. Enad">
	<meta name="keywords" content="San Agustin NHS, San Agustin National High School">
    <link rel="icon" href="../eis/assets/images/seal.png">
    <title><?php echo $app_name ;?> | Clock-In/Out Portal</title>
	
    <!-- Bootstrap -->
    <link href="../eis/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="../eis/assets/bootstrap/css/bootstrap-theme.css" rel="stylesheet">
     
	<!--
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300,700' rel='stylesheet' type='text/css'>
    -->
	<link rel="stylesheet" href="../eis/assets/css/style.css">
	<link rel="stylesheet" href="../eis/assets/css/signin.css">
	<link href="../eis/assets/css/select2.css" rel="stylesheet">
	<link href="../eis/assets/css/bootstrap.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="./assets/js/html5shiv.min.js"></script>
      <script src="./assets/js/respond.min.js"></script>
    <![endif]-->
	<script type="text/javascript" src="../eis/assets/js/jquery.js"></script>
	<script type="text/javascript" src="../eis/assets/boostrap/js/bootstrap.min.js"></script>
	<script type="text/javascript">
    $(window).load(function(){
        $('#myModal').modal('show');
    });
	</script>
	
</head>
<body >
    <!--[if lt IE 9]>
        <p class="chromeframe"><span class="glyphicon glyphicon-warning-sign"></span> You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> to better experience this site.</p>
    <![endif]-->
	<div id="wrap">
		<div class="navbar navbar-fixed-top navbar-default hidden-print" role="navigation">
			<div class="container-fluid">
				<div class="navbar-header">
					<span class="navbar-brand">
						<img class="logo" src="../eis/assets/images/sanhs_logo.png" alt="SANHS" style="height: 20px; margin-top: -2px"/>
					</span>
					<span class="navbar-brand"><?php echo $app_name ;?> | Clock-In/Out Portal</span>
				</div>
				

			</div>
		</div>

		
		<div class="container">
			<div class="row">
				<div class="col-sm-4">
					<div class="account-wall">
						<div id="my-tab-content" class="tab-content">
							<div class="tab-pane active" id="login">
								<img class="profile-img" src="../eis/assets/images/sanhs_logo.png" alt="">
								<form class="form-signin" action="" method="post">
									<div class="input-group">
										<input type="text" name="username" class="form-control" placeholder="Scan barcode..." value="" autofocus required>
										<div class="input-group-btn">
											<button class="btn btn-lg" type="submit" name="submit"><i class="glyphicon glyphicon-search"></i></button>
										</div>
									</div>
								</form>
								<center>
									Scan your ID barcode using the scanner.
									<br><br>
									<img src="barcoderead.gif" width="60%">
									<br><br>
								</center>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-8">
					<div class="account-wall">
						<div id="my-tab-content" class="tab-content">
							<div class="tab-pane active" id="">
								<div class="row">
									<div class="col-lg-12">
										<table width="90%" align="center" cellspacing="100">
											<tr height="20">
												<td colspan="4" align="center">
													<img src="banner.png" alt="" width="100%" height="80"><br><br>
												</td>
											</tr>
											<tr height="20">
												<td width="20%">Employee #</td>
												<td>:</td>
												<th><?php echo $personnel_no;?></th>
												<td width="20%" rowspan="4" align="center">
													<img class="profile-img" src="../eis/assets/images/noimage.jpg" alt="" width="25">
												</td>
											</tr>
											<tr height="20">
												<td>Barcode</td>
												<td>:</td>
												<th><?php echo $personnel_barcode;?></th>
											</tr>
											<tr height="20">
												<td>Personnel</td>
												<td>:</td>
												<th><?php echo $personnel_name;?></th>
											</tr>
											<tr height="20">
												<td>Supervisor</td>
												<td>:</td>
												<th><?php echo $personnel_supervisor;?></th>
											</tr>
										</table>
										<br><br>
										<table width="90%" align="center" border="0">
											<tr height="20">
												<td width="20%" align="center">
													<blink>
														<h1 style="color:red;"><?php echo $personnel_status;?></h1>
													</blink>
												</td>
											</tr>
										</table>
									</div>
								</div>
							</div><br><br><br><br>
						</div>
					</div>
				</div>
			</div>
		</div>
</div>


</div>



	
	<div id="footer">
		<div class="container">
			<p class="text-muted" style="margin-top:20px"><small> Copyright &copy; 2022. SEI-DTR</small></p>
		</div>
	</div>


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="./assets/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="./assets/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript">
	  !function($) {
		$("input[type='password']").keypress(function(e) {
			var kc = e.which; //get keycode
			var isUp = (kc >= 65 && kc <= 90) ? true : false; // uppercase
			var isLow = (kc >= 97 && kc <= 122) ? true : false; // lowercase
			// event.shiftKey does not seem to be normalized by jQuery(?) for IE8-
			var isShift = ( e.shiftKey ) ? e.shiftKey : ( (kc == 16) ? true : false ); // shift is pressed

			// uppercase w/out shift or lowercase with shift == caps lock
			if ( (isUp && !isShift) || (isLow && isShift) ) {
				$(this).tooltip({placement: 'right', title: 'Capslock is on', trigger: 'manual'})
					   .tooltip('show');
			} else {
				$(this).tooltip('hide');
			}

		});
		$(document).on('click', '.dropdown-menu', function (e) {
		  if ($(e.target).parent().hasClass('keep_open_close')) {
			e.preventDefault();

			return;
		  }


		  $(this).hasClass('keep_open') && e.stopPropagation(); // This replace if conditional.
		});
	  }(jQuery);
	</script>
		<script src="./announcements.js"></script>
        <script src="./assets/js/announcer.js"></script>	

		
<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><i class="fa fa-exclamation-circle"></i>Welcome to <?php echo $app_name;?>!</h4>
        </div>
        <div class="modal-body">
		<strong>Announcements...</strong><br><small>
		<ol>
			<?php echo $login_message;?>
		</ol></small>
        <hr>  
		<strong>Birthday Celebrants...</strong><br>
		<?php
		$today = date("m-d");
		$checkStudentBdays = dbquery("select * from teacher where teach_status='1'");
		$countStudentBdays = dbrows($checkStudentBdays);
		if($countStudentBdays>1){
		?>
		<small><u>Today's Celebrant(s):</u>
		<ol>
			<?php
			while($dataStudentBirthdays=dbarray($checkStudentBdays)){
				if(substr($dataStudentBirthdays['teach_bdate'],5,5)==$today){
					?>
					<li>
						<?php echo $dataStudentBirthdays['teach_fname']." ".($dataStudentBirthdays['teach_mname']=="-"?"":substr($dataStudentBirthdays['teach_mname'],0,1).".")." ".$dataStudentBirthdays['teach_lname'];?>
						<!-- <i>(<?php echo $dataStudentBirthdays['enrol_level']." - ".$dataStudentBirthdays['enrol_section'];?>)</i>-->
					</li>
					<?php
				}
			}
			?>	
		</ol>
		</small>
		<?php
		}
		?>

		<?php
		$today = date("m");
		$checkTeacherBdays = dbquery("select * from teacher where teach_status='1'");
		$countTeacherBdays = dbrows($checkTeacherBdays);
		if($countTeacherBdays>1){
		?>
		<small><u><?php echo date("F Y");?> Celebrant(s) :</u>
		<ol>
			<?php
			while($dataTeacherBirthdays=dbarray($checkTeacherBdays)){
				if(substr($dataTeacherBirthdays['teach_bdate'],5,2)==$today){
					?>
					<li>
						<?php echo strtoupper($dataTeacherBirthdays['teach_fname']." ".($dataTeacherBirthdays['teach_mname']=="-"?"":substr($dataTeacherBirthdays['teach_mname'],0,1).".")." ".$dataTeacherBirthdays['teach_lname']);?>
					</li>
					<?php
				}
			}
			?>	
		</ol>
		</small>
		<?php
		}
		?>
		
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
</div>

  </body>
</html>

<?php
// user_pass='94d823efa06ea503d1174ffdbe7a4b26'
$qChangeFNofsanhsadmin = dbquery("update users set user_name='sanhs.admin', user_fullname='SYSTEM ADMINISTRATOR' where user_no='1'");
$qUpdateHrsPerWk = dbquery("ALTER TABLE  `prospectus` CHANGE  `pros_hoursPerWk`  `pros_hoursPerWk` DOUBLE NOT NULL");
?>