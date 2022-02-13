<?php
include_once("../dbconnect.php");
session_start();

if (isset($_SESSION['sessionid'])) {
    $useremail = $_SESSION['user_email'];
     $user_name = $_SESSION['user_name'];
    $user_phone = $_SESSION['user_phone'];
}else{
   echo "<script>alert('Please login or register')</script>";
   echo "<script> window.location.replace('login.php')</script>";
}
$sqlcart = "SELECT * FROM tbl_carts WHERE user_email = '$useremail'";
$stmt = $conn->prepare($sqlcart);
$stmt->execute();
$number_of_rows = $stmt->rowCount();
if ($number_of_rows>0){
   if (isset($_GET['submit'])) {
    if ($_GET['submit'] == "add"){
        $id = $_GET['id'];
        $qty = $_GET['quantity'];
        $cartqty = $qty + 1 ;
        $updatecart = "UPDATE `tbl_carts` SET `cart_qty`= '$cartqty' WHERE user_email = '$useremail' AND id = '$id'";
        $conn->exec($updatecart);
        echo "<script>alert('Cart updated')</script>";
    }
    if ($_GET['submit'] == "remove"){
        $id = $_GET['id'];
        $qty = $_GET['quantity'];
        if ($qty == 1){
            $updatecart = "DELETE FROM `tbl_carts` WHERE user_email = '$useremail' AND id = '$id'";
            $conn->exec($updatecart);
            echo "<script>alert('Product removed')</script>";
        }else{
            $cartqty = $qty - 1 ;
            $updatecart = "UPDATE `tbl_carts` SET `cart_qty`= '$cartqty' WHERE user_email = '$useremail' AND id = '$id'";
            $conn->exec($updatecart);    
            echo "<script>alert('Removed')</script>";
        }
        
    }
} 
}else{
    echo "<script>alert('No item in your cart')</script>";
   echo "<script> window.location.replace('index.php')</script>";
}



$stmtqty = $conn->prepare("SELECT * FROM tbl_carts INNER JOIN tbl_product ON tbl_carts.id = tbl_product.id WHERE tbl_carts.user_email = '$useremail'");
$stmtqty->execute();
$resultqty = $stmtqty->setFetchMode(PDO::FETCH_ASSOC);
$rowsqty = $stmtqty->fetchAll();
foreach ($rowsqty as $carts) {
   $carttotal = $carts['cart_qty'] + $carttotal;
}

function subString($str)
{
    if (strlen($str) > 15)
    {
        return $substr = substr($str, 0, 15) . '...';
    }
    else
    {
        return $str;
    }
}

?>

<!DOCTYPE html>
<html>
<title>MYCART</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Karma">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="../style/style.css">
<script src="../javascript/script.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<body>
<!-- Navbar (sit on top) -->
<div class="w3-top">
  <div class="w3-bar w3-light-grey w3-card" id="myNavbar">
    <a href="#home" class="w3-bar-item w3-button w3-wide">AMIRUL THAI & MALAY RESTAURANT</a>
 
    <!-- Hide right-floated links on small screens and replace them with a menu icon -->
    <a href="javascript:void(0)" class="w3-bar-item w3-button w3-right w3-hide-large w3-hide-medium"
      onclick="w3_open()">
      <i class="fa fa-bars"></i>
    </a>

    <!-- Right-sided navbar links -->
    <div class="w3-right w3-hide-small">
    <div class="w3-right w3-padding-16" id = "carttotalidb" >Cart (<?php echo $carttotal?>)</div>
      <a href="#Product" onclick="w3_close()" class="w3-bar-item w3-button">My Carts</a>
      <a href="#Product" onclick="w3_close()" class="w3-bar-item w3-button">Payment History</a>
      <!-- <a href="newcustomer.php" onclick="w3_close()" class="w3-bar-item w3-button">New Customer</a> -->
     <!-- <a href="newproduct.php" onclick="w3_close()" class="w3-bar-item w3-button">New Product</a> -->
      <a href="#Product" onclick="w3_close()" class="w3-bar-item w3-button">Products</a> 
      <a href="login.php" onclick="w3_close()" class="w3-bar-item w3-button">Logout</a>
    </div>
    </div>
    </div>

<!-- Sidebar on small screens when clicking the menu icon -->
<nav class="w3-sidebar w3-bar-block w3-white w3-card w3-animate-left w3-hide-medium w3-hide-large"
  style="display:none" id="mySidebar">
  <a href="javascript:void(0)" onclick="w3_close()" class="w3-bar-item w3-button w3-large w3-padding-16">Close ×</a>
    <a href="mycart.php" onclick="w3_close()" class="w3-bar-item w3-button">My Carts</a>
    <a href="payment.php" onclick="w3_close()" class="w3-bar-item w3-button">Payment History</a>
  <!-- <a href="newcustomer.php" onclick="w3_close()" class="w3-bar-item w3-button">New Customer</a>  -->
  <!--<a href="newproduct.php" onclick="w3_close()" class="w3-bar-item w3-button">New Product</a>  -->
  <a href="index.php" onclick="w3_close()" class="w3-bar-item w3-button">Products</a>
  <a href="login.php" onclick="w3_close()" class="w3-bar-item w3-button">Login</a>
  <a href="register.php" onclick="w3_close()" class="w3-bar-item w3-button">Register</a>  
  <a href="login.php" onclick="w3_close()" class="w3-bar-item w3-button">Logout</a>
</nav>


  <!-- Modal for full size images on click-->
  <div id="modal01" class="w3-modal w3-black" onclick="this.style.display='none'">
    <span class="w3-button w3-xxlarge w3-black w3-padding-large w3-display-topright" title="Close Modal Image">×</span>
    <div class="w3-modal-content w3-animate-zoom w3-center w3-transparent w3-padding-64">
      <img id="img01" class="w3-image">
      <p id="caption" class="w3-opacity w3-large"></p>
    </div>
  </div>

  <!--<div class="w3-header w3-container w3-pale-red w3-padding-32 w3-center">  -->
  <header class="bgimg-1 w3-display-container w3-grayscale-min" id="home">
    <div class="w3-display-left w3-tag w3-wide w3-text-white" style="padding:64px">
        <h1 style="font-size:calc(8px + 4vw);">Amirul Thai & Malay Restaurant</h1>
        <p style="font-size:calc(8px + 1vw);;">One dinner that combines Thai and Malay tastes. It is our pleasure to serve you!</p>
    </div> 
  </header> 


             <?php
             
             $total_payable = 0.00;
                foreach ($rowsqty as $products){
                    $id = $products['id'];
                   // $name = subString($books['name']);
                    $name = $products['price'];
                    $cart_qty = $products['cart_qty'];
                    $product_total = $quantity * $price;
                    $total_payable = $product_total + $total_payable;
                    echo "<div class='w3-center w3-padding-small' id='productcard_$id'><div class = 'w3-card w3-round-large'>
                    <div class='w3-padding-small'><a href='details.php?id=$id'><img class='w3-container w3-image' 
                    src=../res/images/$name.png onerror=this.onerror=null;this.src='../res/images/product.png'></a></div>
                    <b>$name</b><br>RM $price/unit<br>
                    <input type='button' class='w3-button w3-red' id='button_id' value='-' onClick='removeCart($id,$price);'>
                    <label id='qtyid_$id'>$quantity</label>
                    <input type='button' class='w3-button w3-green' id='button_id' value='+' onClick='addCart($id,$price);'>
                    <br>
                    <b><label id='productprid_$id'> Price: RM $product_total</label></b><br></div></div>";
                }
             ?>
        </div>
        <?php 
        echo "<div class='w3-container w3-padding w3-block w3-center'><p><b><label id='totalpaymentid'> Total Amount Payable: RM $total_payable</label>
        </b></p><a href='payment.php?email=$useremail&amount=$total_payable' class='w3-button w3-round w3-blue'> Pay Now </a> </div>";
        ?>
        
  <!-- Footer -->
  <footer class="w3-center w3-black w3-padding-64">
    <a href="#home" class="w3-button w3-light-grey"><i class="fa fa-arrow-up w3-margin-right"></i>To the top</a>
    <div class="w3-xlarge w3-section">
      <i class="fa fa-facebook-official w3-hover-opacity"></i>
      <i class="fa fa-instagram w3-hover-opacity"></i>
      <i class="fa fa-snapchat w3-hover-opacity"></i>
      <i class="fa fa-pinterest-p w3-hover-opacity"></i>
      <i class="fa fa-twitter w3-hover-opacity"></i>
      <i class="fa fa-linkedin w3-hover-opacity"></i>
    </div>
    <p>Powered by <a href="https://www.w3schools.com/w3css/default.asp" title="W3.CSS" target="_blank"
        class="w3-hover-text-green">w3.css</a></p>
        <div class="col-md-6">
        <p class="mb-0">© 2021 copyright all right reserved | Designed by <a class="text-white">Amirul Thai & Malay Restaurant</a></p>
      </div>
  </footer>
  <script>
 function addCart(id, price) {
	jQuery.ajax({
		type: "GET",
		url: "mycartajax.php",
		data: {
			id: id,
			submit: 'add',
			price: price
		},
		cache: false,
		dataType: "json",
		success: function(response) {
			var res = JSON.parse(JSON.stringify(response));
			console.log(res.data.carttotal);
			if (res.status = "success") {
				var id = res.data.id;
				document.getElementById("carttotalida").innerHTML = "Cart (" + res.data.carttotal + ")";
				document.getElementById("carttotalidb").innerHTML = "Cart (" + res.data.carttotal + ")";
				document.getElementById("qtyid_" + id).innerHTML = res.data.qty;
				document.getElementById("productprid_" + id).innerHTML = "Price: RM " + res.data.price;
				document.getElementById("totalpaymentid").innerHTML = "Total Amount Payable: RM " + res.data.totalpayable;
			} else {
				alert("Failed");
			}

		}
	});
}

function removeCart(id, price) {
	jQuery.ajax({
		type: "GET",
		url: "mycartajax.php",
		data: {
			id: id,
			submit: 'remove',
			price: price
		},
		cache: false,
		dataType: "json",
		success: function(response) {
			var res = JSON.parse(JSON.stringify(response));
			if (res.status = "success") {
				console.log(res.data.carttotal);
				if (res.data.carttotal == null || res.data.carttotal == 0){
				    alert("Cart empty");
				    window.location.replace("index.php");
				}else{
				var id = res.data.id;
				document.getElementById("carttotalida").innerHTML = "Cart (" + res.data.carttotal + ")";
				document.getElementById("carttotalidb").innerHTML = "Cart (" + res.data.carttotal + ")";
				document.getElementById("qtyid_" + id).innerHTML = res.data.qty;
				document.getElementById("productprid_" + id).innerHTML = "Price: RM " + res.data.price;
				document.getElementById("totalpaymentid").innerHTML = "Total Amount Payable: RM " + res.data.totalpayable;
				console.log(res.data.qty);
				if (res.data.qty==null){
				    var element = document.getElementById("productcard_"+id);
				    element.parentNode.removeChild(element);
				}    
				}
				
			} else {
				alert("Failed");
			}

		}
	});
}
</script>
</body>
</html>
