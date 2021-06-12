<?php
session_start();
unset($_SESSION['customer_ID']);
$customerID = 0;
?>
<?php
    //connection params
    $server = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $db = "simplesheets";



    //create database connection
    $conn = new mysqli($server, $dbuser, $dbpass, $db);

    //check the mysql connection
    if($conn->connect_error){
        die("Connection failed" . $conn->connect_error);
    }
    if(isset($_POST['submit'])){
        if(isset($_POST['customerID'])){
            /*$sql = "SELECT * FROM customer WHERE customer_id = ";
            $sql .= $_POST['customerID'];
            $sql .= " LIMIT 1";

            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                  $customerID = $row["customer_id"];
                    $_SESSION['customer_ID'] = $customerID;
                }
                
            }
            else {
                echo "The Customer ID You Entered Does Not Exist";
            }
            foreach ($_POST as $value){
                unset($_POST[$value]);
            }*/
        }
        else{
            $sql = "INSERT INTO address";
            $sql .= " (line1, line2, city, state, zip)";
            $sql .= " VALUES (";
            $sql .= "'" . $_POST['line1'] . "', '" . $_POST['line2'] . "', '" . $_POST['city'] . "', '" . $_POST['state'] . "', '" . $_POST['zip'] . "' ";
            $sql .= ")";
        
            if ($conn->query($sql) === TRUE) {
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            
            $sql = "SELECT * FROM address ORDER BY address_id DESC LIMIT 0,1";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                  $customerID = $row["address_id"];
                }
            }

            $sql = "INSERT INTO customer";
            $sql .= " (first_name, last_name, email, phone, dob, shipment_address)";
            $sql .= " VALUES (";
            $sql .= "'" . $_POST['first_name'] . "', '" . $_POST['last_name'] . "', '" . $_POST['email'] . "', '" . $_POST['phone'] . "', '" . $_POST['dob'] . "', '" . $customerID . "' ";
            $sql .= ")";

            if ($conn->query($sql) === TRUE) {
                $sql = "SELECT * FROM customer ORDER BY customer_id DESC LIMIT 0,1";
                    $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<hr><div class=\"w3-container\"><hr><div class=\"w3-center\"><h2 style=\"color:green;\">Customer Created</h2><p w3-class=\"w3-large\">Your Customer ID is " . $row['customer_id'] ."</p></div><hr>";
                            }
                        }
                  
            }
            foreach ($_POST as $value){
                unset($_POST[$value]);
            }
        }

    }

    $conn->close();

?>
<hr>
<style> h3 {text-align: center;}
    </style>
<div class="w3-row-padding">
<div class="w3-half">
<div class="w3-card white">
  <div class="w3-container w3-theme">
    <h3>For New Customers</h3>
  </div>
  <div class="w3-container">
  <h3 class="w3-text-theme">Put Your Information Here</h3>
  </div>
  <form method="POST">
  <ul class="w3-ul w3-border-top">
    <li>
        <h3>First Name</h3>
        <div class="w3-section">      
            <input class="w3-input" type="text" name="first_name" required>
        </div>
    </li>
    <li>
        <h3>Last Name</h3>
        <div class="w3-section">      
            <input class="w3-input" type="text" name="last_name" required>
        </div>
    </li>
    <li>
        <h3>Email</h3>
        <div class="w3-section">      
            <input class="w3-input" type="text" name="email" required>
        </div>
    </li>
    <li>
        <h3>Phone Number</h3>
        <div class="w3-section">      
            <input class="w3-input" type="text" name="phone" required>
        </div>
    </li>
    <li>
        <h3>Date of Birth</h3>
        <div class="w3-section">      
            <input class="w3-input" type="text" name="dob" required>
        </div>
    </li>
    <h3 class="w3-text-theme">Shipping Address</h3>
    <li>
        <h3>Line 1</h3>
        <div class="w3-section">      
            <input class="w3-input" type="text" name="line1" required>
        </div>
    </li>
    <li>
        <h3>Line 2</h3>
        <div class="w3-section">      
            <input class="w3-input" type="text" name="line2">
        </div>
    </li>
    <li>
        <h3>City</h3>
        <div class="w3-section">      
            <input class="w3-input" type="text" name="city" required>
        </div>
    </li>
    <li>
        <h3>State</h3>
        <div class="w3-section">      
            <input class="w3-input" type="text" name="state" required>
        </div>
    </li>
    <li>
        <h3>Zipcode</h3>
        <div class="w3-section">      
            <input class="w3-input" type="text" name="zip" required>
        </div>
    </li>
  </ul>
  <div class="w3-container w3-theme w3-large">
    <div class="w3-center">
        <input class="w3-button w3-theme" name="submit" type="submit" value="Submit"></input>
    </div>
  </div>
  </form>
</div>
</div>


<div class="w3-half">
<div class="w3-card white">
  <div class="w3-container w3-theme">
    <h3>For Returning Customers</h3>
  </div>
  <div class="w3-container">
  <h3 class="w3-text-theme">Put Your Customer ID Here</h3>
  </div>
  <form method="POST" action="customer_profile">
  <ul class="w3-ul w3-border-top">
    <li>
        <h3>CustomerID</h3>
        <div class="w3-section">      
            <input class="w3-input" type="text" name="customerID" required>
        </div>
    </li>
    </ul>
    <div class="w3-container w3-theme w3-large">
    <div class="w3-center">
        <input class="w3-button w3-theme" name="submit" type="submit" value="Submit"></input>
    </div>
  </div>
  </form>
</div>
</div>
</div>
<hr>

