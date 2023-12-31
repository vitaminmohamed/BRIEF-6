<?php
session_start();
include_once('connectDB.php');

$emailLOGIN = "";
$passwordLOGIN = "";
$errorMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emailLOGIN = $_POST["EMAIL"];
    $passwordLOGIN = $_POST["PASSWORD"];
    
    $Loginquery = $cnc->prepare("SELECT * FROM users WHERE EMAILuser = ?");
    $Loginquery->bind_param("s", $emailLOGIN);
    $Loginquery->execute();
    $result = $Loginquery->get_result();
    $userData = $result->fetch_assoc();
    
    if ($result->num_rows === 0) {
        $errorMsg = "Invalid email or password";
    } else {
        $hashedPasswordFromDB = $userData['PASSWORDuser']; 
        if (password_verify($passwordLOGIN, $hashedPasswordFromDB)) {
            $roles = $cnc->prepare("SELECT * FROM roles WHERE user_id = ?");
            $roles->bind_param("i", $userData['IDuser']);
            $roles->execute();
            $resultroles = $roles->get_result();
            $rowroles = $resultroles->fetch_assoc();
            $R = $rowroles['NAMErole'];
            echo $R;
            if ($R =="ADMIN") {
                $_SESSION['emaillogin'] = $emailLOGIN;
                $_SESSION['IDROLE'] = $R;
                header("location: ADMIN.php");
                exit;
            } elseif ($R== 'CLIENT') {
                $_SESSION['emaillogin'] = $emailLOGIN;
                $_SESSION['IDROLE'] = $R;
                $_SESSION['ID'] = $userData['IDuser'];
                header("location: CLIENT.php");
                exit;
            }
        } else {
            $errorMsg = "Invalid email or password";
        }
    }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <title>Document</title>
</head>

<body>


  <div class="w-100 d-flex align-items-center justify-content-center text-center" style="height:100vh">
  <form action="" method="POST" class="my-5 border rounded-2  border-success w-50 h-75 d-flex flex-column justify-content-center align-items-center">
            
  <!--THE ERROR MESSAGE-->
  <?php if ($errorMsg !== ""): ?>
                <div class="CLOSED bg-danger text-light text-center w-50 d-flex my-4 px-4 py-2 align-items-center justify-content-center">
                    <p class="py-0 my-0"><?php echo $errorMsg; ?></p>
                </div>
            <?php endif; ?>

<!-- THE FORM -->
            <div class="mb-3 d-flex align-items-center w-75 justify-content-center gap-2">
                <ion-icon name="mail-outline" class="fs-2 text-success"></ion-icon>
                <input type="email" required class="form-control w-75" id="form3" autocomplete="email" placeholder="EMAIL" name="EMAIL">
                
            </div>
            <div class="mb-3 d-flex align-items-center w-75 justify-content-center gap-2">
            <ion-icon name="lock-closed-outline" class="fs-2 text-success"></ion-icon>
                <input type="password" required class="form-control w-75" autocomplete="current-password" id="form4" placeholder="PASSWORD" name="PASSWORD">
            </div>
            <input type="submit" class="my-4 btn btn-success" value="LOGIN" name="LOGIN">
            <a href="index.php" class="">Register right here !!!</a>
        </form>

  </div>



  <script src="./assets/js/login.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>