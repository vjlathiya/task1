<?php

include_once('db_connection.php');
include_once('functions.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(0);

$raw = '';
$query_run = '';

$report_name = get('type');

if ($report_name == 'Week') {
	$query_run = "SELECT WEEK(audit_created_date) as type, count(id) as entery FROM product GROUP by WEEK(audit_created_date)";
	$raw = sql($query_run);

}
elseif ($report_name == 'Month') {
	$query_run = "SELECT MONTHNAME(audit_created_date) as type, count(id) as entery FROM product GROUP by MONTH(audit_created_date)";
	$raw = sql($query_run);

}
elseif ($report_name == 'Quarter') {
	$query_run = "SELECT QUARTER(audit_created_date) as type, count(id) as entery FROM product GROUP by QUARTER(audit_created_date)";
	$raw = sql($query_run);

}
elseif ($report_name == 'Year') {
	$query_run = "SELECT YEAR(audit_created_date) as type, count(id) as entery FROM product GROUP by YEAR(audit_created_date)";
	$raw = sql($query_run);

}
else{
	// echo 'query not run';
}
$report_opt = array("Week","Month","Quarter","Year");



?>
<!doctype html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- Bootstrap CSS -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
		<!-- fontawesome -->
		<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
		<title>Ecommerce - Data list</title>
	</head>
	<body>
		<div class="container">
			<h3 class="text-center py-4">Monthely Report</h3>
			<div class="row">
				<form method="get">
					<select class="form-select w-25" name="type">
						<option selected disabled > Select Report Option </option>
						<?php
							foreach ($report_opt as $report) {
						?>
						<option value="<?php echo $report; ?>" <?= (isset($report_name) && $report_name == $report)?'selected':'' ?>><?php echo $report; ?></option>
						<?php } ?>
					</select>
					<div class="d-flex justify-content-between">
						<button type="submit" class="btn btn-primary my-2">Submit</button>
						<a href="ecommerce.php"><button type="button" class="btn btn-success float-right my-2">Product List</button></a>
					</div>
				</form>
				<div class="col-md-6 my-4">
					<table class="table table-striped table-hover">
						<thead>
							<tr class="text-capitalize">
								<th scope="col">Type</th>
								<th scope="col">total entery</th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($raw as $raws) {
								$type = $raws['type'];
								$entery = $raws['entery'];
							?>
							<tr>

								<?php 
									if ($report_name == 'Week') {
										echo "<td> Week "."$type</td>
											  <td> $entery </td>";
									}
									else{

										echo"<td> $type </td>
											<td> $entery </td>";
									}
								?>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<div class="col-md-6 my-5">
					<div id="chartContainer" style="height: 300px; width: 100%;"></div>
				</div>
			</div>
		</div>
	</body>

<!--================= script here ================ -->

	<!-- Bootstrap Bundle with Popper -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
	<script type="text/javascript" src="jquery-3.6.0.min.js"></script>
	<script type="text/javascript" src="jquery.validate.js"></script>
	<script type="text/javascript" src="myjquery.js"></script>
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

	<script>
	   window.onload = function() {
	        var chart = new CanvasJS.Chart("chartContainer", {
	            data: [{
	                type: "column",
	                dataPoints: 
	                [
	                	<?php
							foreach ($raw as $raws) {
								$type = $raws['type'];
								$entery = $raws['entery'];
						?>
		                {	
		                	<?php
		                		$week_graph = json_encode("week"." ".$type);
		                		$comman_graph = json_encode($type);

			                	if ($report_name == 'Week') {
			                		echo "label: $week_graph,
			                    		  y:  $entery,";
			                	}
			                	else{
			                		echo "label: $comman_graph,
			                    		  y:  $entery,";
			                	}
		                	?>
		                },

	   	                <?php } ?>
	                ]
	            }]
	        });
	        chart.render();
	    }
	</script>
</html>