<?php
	// Handle AJAX request (start)
	if( isset($_POST['ajax']) && isset($_POST['prod_name']) && isset($_POST['qty_stk']) && isset($_POST['item_cost'])){
		if(($_POST['prod_name'] != "") && ($_POST['qty_stk'] != "") && ($_POST['item_cost'] != "")){
?>

 <div class="col-xs-6 col-xs-offset-2 row"><br>
	<table class="table table-striped">
	<caption>Product and Quantity information</caption>
	<th><center>Product name</center></th>
	<th><center>Quantity in stock</center></th>
	<th><center>Price per item</center></th>
	<th><center>Datetime submitted</center></th>
	<th><center>Total value number</center></th></tr></thead>
		<?php 
			$data = file_get_contents("store_data.json");
			if($data == ""){
				$a=array();
				$new_data = array(
				'p_name' => $_POST["prod_name"],
				'qty' => $_POST["qty_stk"], 
				'price' => $_POST["item_cost"]
			  );
				array_push($a,$new_data);
				}else{
				$a = json_decode($data, true);

				$new_data = array(
				  'p_name' => $_POST["prod_name"],
				  'qty' => $_POST["qty_stk"], 
				  'price' => $_POST["item_cost"]
				  );

				// pushing the post data each time the page reloads with post values
				array_push($a,$new_data);
					}
				$json = json_encode($a);
				$myfile = fopen("store_data.json", "w+") or die("Unable to open file!");
				fwrite($myfile, $json);
				fclose($myfile);
				$data = file_get_contents("store_data.json");
				$data = json_decode($data, true);
				$total = 0;
				foreach($data as $row){?>
					<tbody>
						<tr><td><center><?php echo $row["p_name"] ?></center></td>
						<td><center><?php echo $row["qty"] ?></center></td>
						<td><center><?php echo $row["price"] ?></center></td>
						<td><center><?php date_default_timezone_set("America/New_York");
									echo date("d/m/Y h:i:s A"); ?></center></td>
						<td><center><?php echo $row["qty"]*$row["price"] ?></center></td></tr>
					<?php if(isset($row["qty"])){
					$total += $row["qty"]*$row["price"]; }?>
					<?php } ?>
					<tr><td colspan="5"><center>Sum total of all the total value numbers: <?php echo $total ?></center></td>
				</tbody>
		</table>
	</div>
		<?php
		}
	 exit;
	}
	// Handle AJAX request (end)
	?>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<link href="style.css" media="all" rel="stylesheet" type="text/css" />
	</head>

	<div class="col-xs-4 col-xs-offset-3">
        <h3>Product Management</h3><br>
		<h5><b>* All fields are required</b><h5>
		<form id="form" name="prod_mgmt" action="javascript:AnyFunction();">
            <div class="form-group">
                <label for="product_name">Product Name: </label>
                <input class="form-control" type="text" name="product_name" id="product_name" required autocomplete="off" autofocus value=""
				placeholder="Enter product name...">
            </div>
            <div class="form-group">
                <label for="quantity_stock">Quantity in stock</label>
                <input class="form-control" type="number" name="quantity_stock" id="quantity_stock" required autocomplete="off" autofocus
				placeholder="Enter the quantity...">
            </div>
            <div class="form-group">
                <label for="item_price">Price per item</label>
                <input class="form-control" type="number" name="item_price" id="item_price" required autocomplete="off" autofocus
				placeholder="Enter the amount in $...">
            </div>
            <button type="submit" id="submit" class="btn btn-success">Submit</button>
		</form>
	</div>
	<div id='response'></div>
		
	<script type="text/javascript">
		function AnyFunction(){}
		$(document).ready(function(){	
			$('#submit').click(function(){
				var prod_name = $('#product_name').val();
				var qty_stk = $('#quantity_stock').val();
				var item_cost = $('#item_price').val();
				$.ajax({
					type: 'post',
					cache: false,
					data: {ajax: 1, prod_name: prod_name, qty_stk: qty_stk, item_cost: item_cost},
					success: function(response){
						$('#response').html(response);
						$( '#form' ).each(function(){
						this.reset();
							});
						}
					});
				});
			});
	</script>

	
	
	
    