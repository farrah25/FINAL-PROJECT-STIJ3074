<?php
include_once ("../dbconnect.php");
session_start();
$useremail = "Guest";
$user_name = "Guest";
$user_phone = "-";
if (isset($_SESSION['sessionid'])){
    $useremail = $_SESSION['user_email'];
    $user_name = $_SESSION['user_name'];
    $user_phone = $_SESSION['user_phone'];
}
$carttotal = 0;
if (isset($_GET['submit']))
{
    include_once ("../dbconnect.php");
    if ($_GET['submit'] == "cart")
    {
        if ($useremail != "Guest")
        {
            $id = $_GET['id'];
            $cartqty = "1"; 
            $stmt = $conn->prepare("SELECT * FROM tbl_carts WHERE user_email = '$useremail' AND id = '$id'");
            $stmt->execute();
            $number_of_rows = $stmt->rowCount();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $rows = $stmt->fetchAll();
            if ($number_of_rows > 0)
            {
                foreach ($rows as $carts)
                {
                    $cartqty = $carts['cart_qty'];
                }
                $cartqty = $cartqty + 1;
                $updatecart = "UPDATE `tbl_carts` SET `cart_qty`= '$cartqty' WHERE user_email = '$useremail' AND id = '$id'";
                $conn->exec($updatecart);
                echo "<script>alert('Cart updated')</script>";
                echo "<script> window.location.replace('index.php')</script>";
            }
            else
            {
                $addcart = "INSERT INTO `tbl_carts`(`user_email`, `id`,`cart_qty`) VALUES ('$useremail', $id','$cartqty')";
                try
                {
                    $conn->exec($addcart);
                    echo "<script>alert('Success')</script>";
                    echo "<script> window.location.replace('index.php')</script>";
                }
                catch(PDOException $e)
                {
                    echo "<script>alert('Failed')</script>";
                }
            }

        }
        else
        {
            echo "<script>alert('Please login or register')</script>";
            echo "<script> window.location.replace('login.php')</script>";
        }
    }
    if ($_GET['submit'] == "search")
    {
        $search = $_GET['search'];
        $sqlquery = "SELECT * FROM tbl_product WHERE name LIKE '%$search%'";
    }
}
else
{
    $sqlquery = "SELECT * FROM tbl_product WHERE quantity > 0";
}

$stmtqty = $conn->prepare("SELECT * FROM tbl_carts WHERE user_email = '$useremail'");
$stmtqty->execute();
$resultqty = $stmtqty->setFetchMode(PDO::FETCH_ASSOC);
$rowsqty = $stmtqty->fetchAll();
foreach ($rowsqty as $carts)
{
    $carttotal = $carts['cart_qty'] + $carttotal;
} 


$results_per_page = 10;
if (isset($_GET['pageno']))
{
    $pageno = (int)$_GET['pageno'];
    $page_first_result = ($pageno - 1) * $results_per_page;
}
else
{
    $pageno = 1;
    $page_first_result = 0;

}

$stmt = $conn->prepare($sqlquery);
$stmt->execute();
$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
$rows = $stmt->fetchAll();
$number_of_result = $stmt->rowCount();
$number_of_page = ceil($number_of_result / $results_per_page);
$sqlquery = $sqlquery . " LIMIT $page_first_result , $results_per_page";
$stmt = $conn->prepare($sqlquery);
$stmt->execute();
$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
$rows = $stmt->fetchAll();


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


<style>
   /* Full height image header */
   .bgimg-1 {
    background-position: center;
    background-size: cover;
    background-image: url("../res/images/photo.jpg");
    min-height: 100%;
  } 
</style>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="../style/style.css">
<script src="../javascript/script.js"></script>
<title>AMIRUL THAI & MALAY RESTAURANT</title>
</head>


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
      <a href="mycart.php" onclick="w3_close()" class="w3-bar-item w3-button">My Carts</a>
      <a href="payment.php" onclick="w3_close()" class="w3-bar-item w3-button">Payment History</a>
      <!-- <a href="newcustomer.php" onclick="w3_close()" class="w3-bar-item w3-button">New Customer</a> -->
     <!-- <a href="newproduct.php" onclick="w3_close()" class="w3-bar-item w3-button">New Product</a> -->
      <a href="index.php" onclick="w3_close()" class="w3-bar-item w3-button">Products</a> 
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
    <div class="w3-display-left w3-tag w3-wide w3-text-white" style="padding:50px">
        <h1 style="font-size:calc(8px + 4vw);">Amirul Thai & Malay Restaurant</h1>
        <p style="font-size:calc(8px + 1vw);;">One dinner that combines Thai and Malay tastes. It is our pleasure to serve you!</p>
    </div> 
  </header> 
  <br>
  <br>
    <div class="w3-main w3-content w3-padding" style="max-width:1200px;margin-top:100px">
    <div class="w3-container w3-center"><p>Welcome <?php echo $user_name?> </p></div> 
    <div class="w3-container w3-card w3-padding w3-row w3-round" style="width:100%">
        <form class="w3-container" action="index.php" method="get">
            <div class="w3-twothird"><input class="w3-input w3-border w3-round w3-center" placeholder = "Enter your search term here" type="text" name="search"></div>
            <div class="w3-third"><input class="w3-input w3-border w3-blue w3-round" type="submit" name="submit" value="search"></div>
        </form>
    </div>
    <br>
        
        <div class="w3-grid-template">
             <?php
             $cart = "cart";
                foreach ($rows as $products){
                    $id = $products['id'];
                    $name = $products['name'];
                    $price = $products['price'];
                    $qty = $products['quantity'];
                    $description = $products['description'];
    
                    echo "<div class='w3-center w3-padding-small'><div class = 'w3-card w3-round-large'>
                    <div class='w3-padding-small'><a href='details.php?id=$id'><img class='w3-container w3-image' src=../images/books/$name.png onerror=this.onerror=null;this.src='../res/images/product.png'></a></div>
                    <b>$name</b><br>RM $price / $qty avail<br>
                    <input type='button' class='w3-button w3-blue w3-round' id='button_id' value='Add to Cart' onClick='addCart($id);'><br><br>
                    </div></div>";
                }
             ?>
        </div>
    </div>
    
    <?php
    $num = 1;
    if ($pageno == 1) {
        $num = 1;
    } else if ($pageno == 2) {
        $num = ($num) + $results_per_page;
    } else {
        $num = $pageno * $results_per_page - 9;
    }
    echo "<div class='w3-container w3-row'>";
    echo "<center>";
    for ($page = 1; $page <= $number_of_page; $page++) {
        echo '<a href = "index.php?pageno=' . $page . '" style=
        "text-decoration: none">&nbsp&nbsp' . $page . ' </a>';
    }
    echo " ( " . $pageno . " )";
    echo "</center>";
    echo "</div>";
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
 function addCart(id) {
	jQuery.ajax({
		type: "GET",
		url: "updatecartajax.php",
		data: {
			id: id,
			submit: 'add',
		},
		cache: false,
		dataType: "json",
		success: function(response) {
		    var res = JSON.parse(JSON.stringify(response));
		    console.log("HELLO ");
			console.log(res.status);
			if (res.status == "success") {
			    console.log(res.data.carttotal);
				//document.getElementById("carttotalida").innerHTML = "Cart (" + res.data.carttotal + ")";
				document.getElementById("carttotalidb").innerHTML = "Cart (" + res.data.carttotal + ")";
				alert("Success");
			}
			if (res.status == "failed") {
			    alert("Please login/register account");
			}
			

		}
	});
}
</script>
</body>

</html>