<?php
// Start session
session_start();
$stage = "pass";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Tester</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
  <style>.error {color: #FF0000;}</style>
  <link rel="stylesheet" href="style.css">
</head>
<body>


<!-------- Input validation  ---------->

<?php
// define variables and set to empty values

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["stage"] == "input" && $_POST["password"] !== "" && $_POST["username"] !== "")

  { Login();}


if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["stage"] == "home")

  {$_SESSION["stage"] = "pass";
  // $firstNameErr = $lastNameErr = $emailErr = "";
  }

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["stage"] == "back")

  {$_SESSION["stage"] = "input";
  $firstNameErr = $lastNameErr = $emailErr = "";
  }  


if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["stage"] == "pass")

  {$_SESSION["stage"] = "pass";}  


if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["stage"] == "toDB") {

  $firstNameErr = $lastNameErr = $emailErr = "";
  $firstName = $lastName = $email = "";
  $firstNameBox = $lastNameBox = $emailBox = "";
  $checker1Name = $checker2Name = $checkerEmail = false;

// Validating the Firs Name --------------

  if (empty($_POST["firstName"])) {
    $firstNameErr = "First name is required";
    $firstNameBox = "No first name entered";
  } else {
    $firstNameBox = test_input($_POST["firstName"]);
    $firstName = $firstNameBox;
    $checker1Name = true;
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z ]*$/",$firstNameBox)) {
      $firstNameErr = "Only letters and white space allowed";
      $firstNameBox = "No correct first name entered";
      $checker1Name = false;
    }
  }
  
// Validating the Last Name ----------------

if (empty($_POST["lastName"])) {
    $lastNameErr = "Last name is required";
    $lastNameBox = "No last name entered";
  } else {
    $lastNameBox = test_input($_POST["lastName"]);
    $lastName = $lastNameBox;
    $checker2Name = true;
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z ]*$/",$lastNameBox)) {
      $lastNameErr = "Only letters and white space allowed";
      $lastNameBox = "No correct last name entered";
      $checker2Name = false;
    }
  }

// Validating the email  --------------------

  if (empty($_POST["email"])) {
    $emailErr = "Email is required";
    $emailBox = "No email entered";
  } else {
    $emailBox = test_input($_POST["email"]);
    $email = $emailBox;
    $checkerEmail = true;
    // check if e-mail address is well-formed
    if (!filter_var($emailBox, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format";
      $emailBox = "Invalid email format";
      $checkerEmail = false;
    }
  }

$checker = $checkerEmail && $checker2Name && $checker1Name;
$_SESSION["firstName"] = $firstNameBox;
$_SESSION["lastName"] = $lastNameBox;
$_SESSION["email"] = $emailBox;
if ($checker) {$_SESSION["stage"]="sendToDB";}


}

else if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION["stage"] == "sendToDB" && $_POST["stage"] == "report") {
      $_SESSION["stage"] = $_POST["stage"];
    }

//-------------------------------------- 

function Login()

{     $usname = "1234";
      $pass = "1234";
      $_SESSION["usname"] = $usname;
      $_SESSION["pass"] = $pass;
 
    if(empty($_POST['username']))
    {
        $this->HandleError("UserName is empty!");
        return false;
    }
    
    if(empty($_POST['password']))
    {
        $this->HandleError("Password is empty!");
        return false;
    }
    
    if (trim($_POST['username']) == $_SESSION["usname"] && trim($_POST['password']) == $_SESSION["pass"]) {$_SESSION["stage"] = "input";}
    
    return true;
}


function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
  }

// ------------ DATARECORD FUNCTION ----------

function datarecord() {

$servername = "1234";
$username = "1234";
$password = "1234";
$dbname = "1234";


// Create connection
$conn = new mysqli ($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$firstName = $_SESSION["firstName"];
$lastName = $_SESSION["lastName"];
$email = $_SESSION["email"];

echo '<h4>Your name and email are:</h4> '.$firstName.' '.$lastName.', '.$email.'.<br>';

$sql = "INSERT INTO MyGuests (firstname, lastname, email)
VALUES ('$firstName', '$lastName', '$email')";

if ($conn->query($sql) === TRUE) {
    echo "<h4>New information added successfully!</h4><br>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();}
?>

<!-- --------    Starter    ---------- -->

<div class="container">
<div class="jumbotron text-center" style="height: 200px">
  <h1 style="; margin-top: 10px">Tester program</h1>
  <p style="text-align: center;">Writing information about a new guest to the guest database</p> 
</div>

<!-- Input fields -->

<?php 

if ($_SESSION["stage"] !== "sendToDB" && $_SESSION["stage"] !== "report" && $_SESSION["stage"] !== "input" || $_SESSION["stage"] == "pass")  { ?>

<form id='login' action='<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>' method='post' accept-charset='UTF-8'>
<fieldset >
<legend><h4>Login</h4></legend>
<input type='hidden' name='submitted' id='submitted' value='1'>

<p><label for='username'>UserName:</label>
<input type='text' name='username' id='username' maxlength="50" autofocus style="width: 10.6em;">
</p>

<p><label for='password'>Password:</label>
<input type='password' name='password' id='password' maxlength="50" style="width: 11em;">
</p>
<input type='submit' name='Submit' value='Submit' style="width: 9em;">
<input type="hidden" name="stage" value="input">

</fieldset>
</form> <?php }


if ($_SESSION["stage"] == "input")  { ?> 

  <h4>Please fill the boxes and then click "Procced"</h4>
  <p><span class="error">* required field.</span></p>
  <form method="post" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
   <p>First name: <input type="text" name="firstName" value="<?php echo $firstName;?>" autofocus style="width: 11em;">
    <span class="error">*<?php echo $firstNameErr?></span></p>

    <p>Last name: <input type="text" name="lastName" value="<?php echo $lastName;?>" style="width: 11em;">
    <span class="error">*<?php echo $lastNameErr ;?></span></p>

    <p>E-mail: <input type="text" style="width: 13em;" name="email" value="<?php echo $email;?>">
    <span class="error">*<?php echo $emailErr;?></span></p>
    <input type="hidden" name="stage" value="toDB">
    <input type="submit" name="submit" value="Proceed" style="width: 9em;"></form>

    <p><form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <input type="submit" value="Refresh page" style="width: 9em;">
    <input type="hidden" name="stage" value="home"></form></p>

    <p><form method="post" action="index.php">
    <input type="submit" value="Log out" style="width: 9em;">
    <input type="hidden" name="stage" value="home"></form></p>
      
<?php }
   else if ($_SESSION["stage"] == "sendToDB")  { ?>
          
          <h4>This is what we got about new guest:</h4>

<!-- Echo session variables that were set on previous page -->
          The first name is <?php echo $_SESSION["firstName"];?>,<br>
          The last name is <?php echo $_SESSION["lastName"];?>,<br>
          The email is <?php echo $_SESSION["email"];?>.<p></p>

      <form method="post" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      <input type="submit" name="add" value="Add to database" style="width: 9em;">
      <input type="hidden" name="stage" value="report">
      </form>


      <p><form method="post" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      <input type="submit" value="Go input page" style="width: 9em;">
      <input type="hidden" name="stage" value="back"></form></p>

      <!-- Back to homepage  -->
      <p><form method="post" action="index.php">
      <input type="submit" value="Log out" style="width: 9em;">
      <input type="hidden" name="stage" value="home"></form></p>
      <br>
    
    
    <?php }

      else if ($_SESSION["stage"] == "report") {

      echo datarecord(); 

      ?><p><form method="post" action="index.php">
      <input type="submit" value="Log out" style="width: 9em;">
      <input type="hidden" name="stage" value="home"></form></p>
      <p><form method="post" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      <input type="submit" value="Go input page" style="width: 9em;">
      <input type="hidden" name="stage" value="back"></form></p>

    <?php 
    $_SESSION["stage"] = "input";
    }

    ?>
</div>
</body>
</html>		
