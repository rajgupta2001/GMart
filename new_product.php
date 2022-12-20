<?php

//if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
if(session_id() == '' || !isset($_SESSION)){session_start();}

if(!isset($_SESSION["username"])) {
  header("location:index.php");
}

if($_SESSION["type"]!="admin") {
  header("location:index.php");
}

include 'config.php';
?>
<?php 
$isMsg = false;
$msg = "";
if(isset($_POST["add"])){
    $code = $_POST["code"];
    $name = $_POST["name"];
    $price = $_POST["price"];
    $details= $_POST["details"];
    $units = $_POST["units"];
    $uniq_name = uniqid();
    $dir_path = "images/products/";
    $new_imgName = $uniq_name .".".strtolower(pathinfo($_FILES["p_img"]["name"],PATHINFO_EXTENSION));
    $con = mysqli_connect("localhost","root","","bolt");
    
    function uploadFile($dir_path,$new_imgName){
        $location = $dir_path . $new_imgName;
        if(move_uploaded_file($_FILES["p_img"]["tmp_name"],$location) ){
            return true;
        }
        else{
            return false;
        }
    }
    if(uploadFile($dir_path,$new_imgName)){
        $isMsg = true;
        $result =  mysqli_query($con,"INSERT INTO products values('','$code','$name','$details','$new_imgName','$units','$price')");
        if($result){
            $msg = "product added";
        }else{
            $msg = "product not uploaded";
        }
    }

}
?>
<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin || Gmart Grocery Shop</title>
    <link rel="stylesheet" href="css/foundation.css" />
    <script src="js/vendor/modernizr.js"></script>
  </head>
  <body>
  <nav class="top-bar" data-topbar role="navigation">
      <ul class="title-area">
        <li class="name">
          <h1><a href="index.php">Gmart Grocery Shop</a></h1>
        </li>
        <li class="toggle-topbar menu-icon"><a href="#"><span></span></a></li>
      </ul>

      <section class="top-bar-section">
      <!-- Right Nav Section -->
        <ul class="right">
          <li><a href="about.php">About</a></li>
          <li><a href="products.php">Products</a></li>
          <?php

          if(isset($_SESSION['username'])){
            echo '<li><a href="account.php">My Account</a></li>';
            echo '<li><a href="logout.php">Log Out</a></li>';
          }
          else{
            echo '<li><a href="login.php">Log In</a></li>';
            echo '<li><a href="register.php">Register</a></li>';
          }
          ?>
        </ul>
      </section>
    </nav>

<div class="row" style="margin-top:10px;">
<h1>New Product</h1>
<?php 
if($isMsg){
echo '<div data-alert class="alert-box alert round">
'.$msg.'
<a href="#" class="close">&times;</a>
</div>';  
}
?>
<form action="new_product.php" method="POST" enctype="multipart/form-data">
  <div class="grid-container">
    <div class="grid-x grid-padding-x">
      <div class="medium-6 cell">
        <label>product Name
          <input type="text" name ="name"placeholder="product Name">
        </label>
      </div>
      <div class="medium-6 cell">
        <label>product Code
          <input type="text" name="code" placeholder="product Code">
        </label>
      </div>
      <div class="medium-6 cell">
        <label>product  description
          <textarea name="details" placeholder="product description" rows=5></textarea>
        </label>
      </div>

      <div class="medium-6 cell">
        <label>product Price
          <input type="number" name="price" placeholder="rs 3000">
        </label>
      </div>

    <div class="medium-6 cell">
        <label>product units
          <input type="number" name="units" placeholder="400">
        </label>
    </div>
    <div class="medium-6 cell">
    <label for="exampleFileUpload" class="button secondary">Upload Image</label>
    <input type="file" name="p_img" id="exampleFileUpload" class="show-for-sr" style="visibility:hidden">   
    </div>
    <button class="button expanded" name="add" type="submit">Add</button>
    </div>
  </div>
</form>


</div>

    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>
</html>
