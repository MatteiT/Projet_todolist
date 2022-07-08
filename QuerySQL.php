<?php 


if(isset($_POST['return'])){
    session_destroy();
    header("Location: home.php");
}

if(isset($_POST["cancel"])){
    header("Location: app.php");
}


if (isset($_POST["update"]) && isset($_POST["task_id"])) {
    $_SESSION["task_id"]=$_POST["task_id"];
    header("Location: ./update.php");
}



$stmt= $pdo->query("SELECT * FROM tasks WHERE user_id={$_SESSION['user']['user_id']}");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);