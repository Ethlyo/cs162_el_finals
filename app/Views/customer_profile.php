<?php
$customerid = 0;
$orderid = 0;
$message = '';
$orderidforstatuschange = '';
$statusidforchange = '';
$ispaid = '';
/*session_start();
if(isset($_SESSION['customer_ID'])){
    $customerid = $_SESSION['customer_ID'];
}
else{
    echo "<div class=\"w3-container\"><hr><div class=\"w3-center\"><h2>Oops!</h2><p w3-class=\"w3-large\">Go back to Customer page and enter Customer ID</p></div>";
    exit();
}
*/?>

<?php
//connection params
$server = "localhost";
$dbuser = "root";
$dbpass = "";
$db = "simplesheets";


mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
//create database connection
$conn = new mysqli($server, $dbuser, $dbpass, $db);

//check the mysql connection
if($conn->connect_error){
    die("Connection failed" . $conn->connect_error);
}

if(isset($_POST['submit'])){
    if(isset($_POST['customerID'])){
        $sql = "SELECT * FROM customer WHERE customer_id = ";
        $sql .= $_POST['customerID'];
        $sql .= " LIMIT 1";

        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                global $customerid;
                $customerid = $row["customer_id"];
            }
            
        }
        else {
            echo "<div class=\"w3-container\"><hr><div class=\"w3-center\"><h2>Oops!</h2><p w3-class=\"w3-large\">The Customer ID you entered doesn't exist</p></div>";
            echo "<hr><div class=\"w3-center\"><a href=\"../home/customer\" class=\"w3-button w3-theme\">Go Back</a>";
            die();
        }
        /*foreach ($_POST as $value){
            unset($_POST[$value]);
        }
        die();*/
    }
}
if(isset($_POST['customerID'])){

}
else{
    echo "<div class=\"w3-container\"><hr><div class=\"w3-center\"><h2>Oops!</h2><p w3-class=\"w3-large\">No Customer ID Selected</p></div>";
    echo "<hr><div class=\"w3-center\"><a href=\"../home/customer\" class=\"w3-button w3-theme\">Go Back</a>";
    die();

}


if(isset($_POST['submit2'])){
    /*foreach($_POST as $value) :
        echo $value;
    endforeach;
    echo $customerid;*/
    if(isset($_POST['customerID'])){
        global $customerid;
        $customerid = $_POST['customerID'];
    }
    if(count($_POST) > 2){

    $sql = "INSERT INTO orders (customer_id, order_date, paid, order_status)";
    $sql .= "VALUES ('" . $_POST['customerID'] . "', '" . date("Y-m-d") . "', '0', 'Processing')";

    if ($conn->query($sql) === TRUE) {
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $sql = "SELECT * FROM orders ORDER BY order_id DESC LIMIT 0,1";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    global $orderid;
                    $orderid = $row["order_id"];
                }
            }
            
    //try{
    foreach($_POST as $value) :
        if($value != 'submit2' && $value != 'Submit' && $value != $customerid) :
            $sql = "INSERT INTO order_line (product_name, order_id)";
            $sql .= "VALUES ('" . $value . "', '" . $orderid . "')";
            $conn->query($sql);
        endif;
    endforeach;
    echo "<hr><div class=\"w3-container\"><hr><div class=\"w3-center\"><h2 style=\"color:green;\">Order Created</h2><p w3-class=\"w3-large\">Your Order ID is " . $orderid ."</p></div><hr>";
    //}
    /*catch(exception $e){
        $sql = "DELETE FROM orders WHERE order_id =";
        $sql .= $orderid;
        $conn->query($sql);
        echo "<hr><div class=\"w3-container\"><hr><div class=\"w3-center\"><h2>Order Unable To Be Created</h2><p w3-class=\"w3-large\">You May Only Order One Of Each Item Per Day</p></div><hr>";
    }*/
    //finally{

    //}
    }
    else{
        echo "<hr><div class=\"w3-container\"><hr><div class=\"w3-center\"><h2>Please Select Your Items</h2></div><hr>";
    }
}

if(isset($_POST['submit3'])){
        if(isset($_POST['customerID'])){
        global $customerid;
        $customerid = $_POST['customerID'];
        }
        
        $sql = "SELECT * FROM orders WHERE order_id = ";
        $sql .= $_POST['orderid'];
        $sql .= " AND customer_id = ";
        $sql .= $_POST['customerID'];
        $sql .= " LIMIT 1";

        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
            $sql = "SELECT * FROM `status` WHERE `name` = '";
            $sql .= $_POST['orderstatus'];
            $sql .= "' LIMIT 1";
            global $orderidforstatuschange;
            $orderidforstatuschange = $row["order_id"];
            }
            
            if(isset($_POST['orderstatus'])){
                if($_POST['orderstatus'] == "Processing" or $_POST['orderstatus'] == "processing" or $_POST['orderstatus'] == "Cancelled" or $_POST['orderstatus'] == "cancelled"){
                global $ispaid;
                $ispaid = 'false';
                }
                else{
                global $ispaid;
                $ispaid = 'true';
                }
            }

            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                global $statusidforchange;
                $statusidforchange = $row["name"];
                }

                $sql = "UPDATE orders SET order_status = '";
                $sql .= $statusidforchange;
                $sql .= "', paid = ";
                $sql .= $ispaid;
                $sql .= " WHERE order_id = ";
                $sql .= $orderidforstatuschange;
                $sql .= "";
                $conn->query($sql);
                echo "<hr><div class=\"w3-container\"><hr><div class=\"w3-center\"><h2 style=\"color:green;\">Order Updated</h2>";
            }
            else{
                echo "<hr><div class=\"w3-container\"><hr><div class=\"w3-center\"><h2>The Status You Entered Doesn't Exist</h2><p w3-class=\"w3-large\">Please Choose from Processing, Paid, Packaged, Shipped, Delivered, or Cancelled</p></div><hr>";
            }
        }
        else{
            echo "<hr><div class=\"w3-container\"><hr><div class=\"w3-center\"><h2>The Order You Entered Either Doesn't Exist or Is Not for This Customer ID</h2></div><hr>";
        }
}


?>
<div class="w3-container">
  <hr>
  <div class="w3-center">
    <h2>Current Orders</h2>
    <p w3-class="w3-large">All of your orders appear here.</p>
  </div>
<div class="w3-responsive w3-card-4">
<table class="w3-table w3-striped w3-bordered">
<thead>
<tr class="w3-theme">
  <th>Order ID</th>
  <th>Order Date</th>
  <th>Status</th>
</tr>
</thead>
<tbody>
<?php
    

    $sql = "SELECT order_id, order_date, order_status FROM orders WHERE customer_id = ";
    $sql .= $customerid;

    $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                
                echo "<tr> <td>" . $row["order_id"] . "</td> <td>" . $row["order_date"] . "</td> <td>"  . $row["order_status"] . "</td> </tr>";
            }
        }
        else{
            echo "<tr> <td>No Orders Found</td><tr>";
        }
        
?>
</tbody>
</table>
<form method="POST">
<div class="w3-container w3-theme w3-large">
    <div class="w3-center">
        <h2>Change Order Status</h2>
        <p w3-class="w3-large">Select an Order and a Status</p>
    </div>
    <div class="w3-left">
        <input class="w3-input" name="orderid" type="text"></input>
        <label>Order ID</label>
    </div>
    <div class="w3-left" style="padding-left: 20px">
        <input class="w3-input" name="orderstatus" type="text"></input>
        <label>Order Status</label>
    </div>
    <input class="w3-button w3-theme" type="hidden" name="customerID" value="<?php echo isset($_POST['customerID']) ? $_POST['customerID'] : '' ?>" />
    <div class="w3-right">
        <input class="w3-button w3-theme" name="submit3" type="submit" value="Submit"></input>
    </div>
</div>
</form>
</div>
<hr>

<div class="w3-container">
  <hr>
  <div class="w3-center">
    <h2>Create Order</h2>
    <p w3-class="w3-large">Pick your items here!</p>
  </div>
<div class="w3-responsive w3-card-4">
<table class="w3-table w3-striped w3-bordered">
<thead>
<tr class="w3-theme">
  <th>Item Name</th>
  <th>Description</th>
  <th>Number Available</th>
  <th>Add to Cart</th>
</tr>
</thead>
<tbody>
<form method="POST">
<?php

    $sql = "SELECT product_name, description, quantity_on_hand, quantity_on_hold FROM product";

    $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                
                echo "<tr> <td>" . $row["product_name"] . "</td> <td>" . $row["description"] . "</td> <td>"  . ($row["quantity_on_hand"]-$row['quantity_on_hold']) . "</td> <td><input name=\"" . $row["product_name"] . "\" class=\"w3-check\" type=\"checkbox\" value=\"" . $row["product_name"] . "\"></td> </tr>";
            }
        }
        else{
            echo "<tr> <td>No Items Found</td><tr>";
        }
?>
<input class="w3-button w3-theme" type="hidden" name="customerID" value="<?php echo isset($_POST['customerID']) ? $_POST['customerID'] : '' ?>" />
</tbody>
</table>
<div class="w3-container w3-theme w3-large">
    <div class="w3-center">
        <input class="w3-button w3-theme" name="submit2" type="submit" value="Submit"></input>
    </div>
  </div>
</div>
</form>
<hr>

<script>
function myAccFunc(id) {
  var x = document.getElementById(id);
  if (x.className.indexOf("w3-show") == -1) {
    x.className += " w3-show";
  } else { 
    x.className = x.className.replace(" w3-show", "");
  }
}
</script>