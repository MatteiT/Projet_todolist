<?php 
session_start();
require_once("PDO.php");

if (isset($_POST["register"])){     
    if(!empty($_POST['name']) && !empty($_POST['password']) && !empty($_POST['password2']))
    {
        if(($_POST["password"])===($_POST["password2"]))
        {
            $password=htmlentities($_POST['password']);
            $password2=htmlentities($_POST['password2']); 
            $hashedpassword= password_hash($password,  PASSWORD_DEFAULT);
            $name=htmlentities($_POST['name']);

            $sql = "SELECT * FROM users WHERE name=:name";
            $stat = $pdo->prepare($sql);
            $stat->execute([
                ":name" => $name,
            ]);
            $user= $stat->fetch(PDO::FETCH_ASSOC);
                if($user)
                {    
                        $_SESSION['error']= "L'utilisateur existe déjà dans la base de données";
                        header("Location: register.php");
                        return;      
                }else{
                    $sql = "INSERT INTO users (name, password) VALUE (:name, :password)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                    ":name" => $name,
                    ":password" => $hashedpassword,
                    ]);
                    $_SESSION['succès']= "vous êtes bien enregister. Veuillez vous connecté.";
                    header("Location: login.php");
                    return;
                    }       
        }else{
                $_SESSION['error'] = "Les Mots de Pass ne sont pas les même !";
                header("Location: register.php");
                return;
            }
    }else{
        $_SESSION['error'] = "Les champs ne sont pas remplis";
        header("Location: register.php");
        return;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <title>S'enregister</title>
</head>
<body>
<section class="vh-100 h-100">
    <div class="mask d-flex align-items-center justify-content-center h-100 gradient-custom-3">
        <h2 class="text-uppercase text-center mb-5">Vous Enregister</h2>
        <form method="POST">
            <?php
                if (isset($_SESSION['error'])) {
                echo('<div id="alert" class="alert alert-danger" role="alert">');
                echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
                echo('</div>');
                unset($_SESSION['error']);
                }
            ?> 
        <div class="p-4 bg-secondary text-white">
            <!-- NAME input -->
            <div class="form-outline mb-4">
                <input type="text" id="form2Example1" name="name" class="form-control" />
                <label class="form-label" for="form2Example1">Votre nom</label>
            </div>
            <!-- Password input -->
            <div class="form-outline mb-4">
                <input type="password" id="form2Example2" class="form-control" name="password" />
                <label class="form-label" for="form2Example2">Password</label>
            </div>
            <div class="form-outline mb-4">
                <input type="password" id="form2Example2" class="form-control" name="password2" />
                <label class="form-label" for="form2Example2">Password de nouveau</label>
            </div>
            <!-- Submit button -->
            <div class="d-flex flex-row bd-highlight mb-3">
                <button type="submit" class="btn btn-primary btn-block mb-4" name="register">S'enregister</button>
                <button class="btn btn-danger btn-block mb-4"> <a href="index.php" class="link-light"> Retour Home page</a></button>
            </div>
        </div>
        </form>
    </div>        
</section>
</div>
</body>
</html>