<?php
session_start();
$login_attempt = true;
if(isset($_POST['pass1'])) {
    $pass1 = $_POST['pass1'];
	$pass2 = $_POST['pass2'];
    if ((strlen($pass1)<8) || (strlen($pass1)>20)) {
        $login_attempt=false;
        $_SESSION['e_pass']="Hasło musi posiadać od 8 do 20 znaków!";
    }
    $_SESSION['fr_pass1'] = $pass1;
	$_SESSION['fr_pass2'] = $pass2;
    require_once "connect.php";
	mysqli_report(MYSQLI_REPORT_STRICT);
	try {
		$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
		if ($polaczenie->connect_errno!=0) {
			throw new Exception(mysqli_connect_errno());
		}
        else {
            if ($login_attempt==true) {					
                if ($polaczenie->query("INSERT INTO uzytkownicy (pass) VALUES ('$pass_hash')")) {
                    $_SESSION['registered']=true;
                    header('Location: index.php');
                }
                else {
                    throw new Exception($polaczenie->error);
                }
            }
            $polaczenie->close();
        }
    }
    catch(Exception $e) {
        echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span>';
        echo '<br />Informacja developerska: '.$e;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form action="index.php" method="post" class="main">
    <!-- HASŁO -->
    <div class="field">
    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16"><path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2"/></svg>    
    <input autocomplete="off" class="input-field" placeholder="Hasło" type="password" name="pass1">
    </div>
    <?php if(isset($_SESSION['e_pass'])) {
        echo '<div class="error">'.$_SESSION['e_pass'].'</div>';
        unset($_SESSION['e_pass']); } 
    ?>
    <!-- POWTÓRZ HASŁO -->
    <div class="field">
    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16"><path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2"/></svg>    
    <input autocomplete="off" class="input-field" placeholder="Powtórz Hasło" type="password" name="pass2">
    </div>
    <?php if(isset($_SESSION['e_pass'])) {
        echo '<div class="error">'.$_SESSION['e_pass'].'</div>';
        unset($_SESSION['e_pass']); } 
    ?>
    <button>Zapisz hasło</button><br/>
</form>
</body>
</html>