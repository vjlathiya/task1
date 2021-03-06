<?php
include_once('db_connection.php');
include_once('functions.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(0);

// pagination ===============================

$page = 10;
$start = 0;
$running_page = 1;

if (isset($_GET['page'])) {
	$start = get('page');
	$running_page = $start;
	$start --;
	$start = $start * $page;
}

$count_query = "SELECT count(*) as count_id FROM product";
$count_raw = sql($count_query);

foreach ($count_raw as $id) {
 	$total_id = $id['count_id'];
}

// $page_count = ceil($total_id/$page);

// pagination function =-=-=-=-=-=-=-=-=-=

$pagination = pagination($total_id, $page, $page, $running_page,'ecommerce.php?page=',"");

// fetch query ========================

$fetch_query = "SELECT * FROM product ORDER BY id DESC LIMIT $start, $page";
$raw = sql($fetch_query);

// delete ============================
$id = get('id'); 
$where = "id='$id'";
delete('product', $where);

//  Cookies ==========================

$search_cookie = 'search_data';
if (isset($_REQUEST['search_btn'])) {
	$cookie_value = $_REQUEST['search_box'];
	$cookie_exp = time()+3600;
	setcookie($search_cookie, $cookie_value, $cookie_exp);	
}

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

		<!-- jQuery UI -->
 		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

		<title>Ecommerce - Data list</title>
	</head>
	<body>
		
		<div class="container-fluid">
			<h2 class="text-center py-4"> Product List </h2>
			<div class="row">
				<div class="col-md-4 my-4">
					<a href="details_add.php"><button type="button" class="btn btn-primary">Add New Product</button></a>
					<a href="report.php"><button type="button" class="btn btn-primary">Report</button></a>
				</div>
				<div class="col-md-4 my-4">
					<div class="input-group">
					  <input type="text" id="search" name="search_box" class="form-control rounded" placeholder="Search">
					  <button type="submit" name="search_btn" class="btn btn-primary"><i class="fas fa-search"></i></button>
					</div>
				</div>
			</div>
			<table class="table table-striped table-hover">
				<?php
					if (count($raw) > 0 || count($start) < 0) { 
				?>
				<thead>
					<tr class="text-capitalize">
						<th scope="col">id</th>
						<th scope="col">name</th>
						<th scope="col">slug</th>
						<th scope="col">SKU</th>
						<th scope="col">MOQ</th>
						<th scope="col">categories</th>
						<th scope="col">search key words</th>
						<th scope="col">price</th>
						<th scope="col">discount type</th>
						<th scope="col">discount value</th>
						<th scope="col">EDIT</th>
						<th scope="col">DELETE</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach ($raw as $raws) {
							$id = $raws['id'];
							$name = $raws['name'];
							$slug = $raws['slug'];
							$sku = $raws['sku'];
							$moq = $raws['moq'];
							$categories = $raws['categories'];
							$search_keywords = $raws['search_keywords'];
							$price = $raws['price'];
							$discount_type = $raws['discount_type'];
							$discount_value = $raws['discount_value'];
						?>
					<tr>
						<td><?php echo $id; ?></td>
						<td><?php echo $name; ?></td>
						<td><?php echo $slug; ?></td>
						<td><?php echo $sku; ?></td>
						<td><?php echo $moq; ?></td>
						<td><?php echo $categories; ?></td>
						<td><?php echo $search_keywords; ?></td>
						<td><?php echo $price; ?></td>
						<td><?php echo $discount_type; ?></td>
						<td><?php echo $discount_value; ?></td>
						<td>
	                        <a href="details_add.php?id=<?php echo $raws['id'];?>"><button type="submit" name="edit_btn" class="btn btn-info"><i class="fas fa-edit text-gray-dark"></i></button></a>
                        </td>
                        <td>
	                        <a href="ecommerce.php?id=<?php echo $raws['id'];?>"><button type="submit" name="delete_btn" class="btn btn-info"><i class="fas fa-trash text-gray-dark"></i></button></a>
                        </td>
					</tr>
				<?php 
						} 
					} 
					else { 
						echo "<div class='alert alert-danger text-center text-truncate w-25 m-auto' role='alert'>
							Record Not Found !!
						</div>";
					}
				?>
				</tbody>
			</table>
			<div>
				<nav aria-label="Page navigation example">
					<ul class="pagination">
							<li class="page-item m-auto my-4 <?php echo $cur_page ?>"><?php echo $pagination; ?></a></li>
					</ul>
				</nav>
			</div>	
		</div>
		<!-- Bootstrap Bundle with Popper -->
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
		<script type="text/javascript" src="jquery-3.6.0.min.js"></script>
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	  	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	  	<script>
	  		window.onload = function() {
	        $("#search").autocomplete({
	            source: function(request, response) {
	                $.ajax({
	                    url: "autocomplete.php",
	                    dataType: "json",
	                    data: {
	                        term: request.term
	                    },

	                    success: function(data) {
	                        response(data);
	                    }
	                });
	            },
	            minLength: 2,
	            select: function(event, ui) {
	                log("Selected: " + ui.item.value + " aka " + ui.item.id);
	            }
	        });
	    };
	  	</script>
	</body>
</html>
