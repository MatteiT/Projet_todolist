<?php 
session_start();
require_once("PDO.php");

if(!isset($_SESSION['user'])){
  die('<p style="color: white; background-color: red;">Accès Refusé Sans Insciption</p>');
  return;
}
require("QuerySQL.php");

if(isset($_POST["add"]))
{
    if(!empty(($_POST["task"]))){
        $task= htmlentities($_POST["task"]);
        $sql = "INSERT INTO tasks (title, user_id) VALUE (:title, :user_id)";
        $stat = $pdo->prepare($sql);
        $stat->execute([
            ":title" => $task,
            ":user_id" => $_SESSION['user']['user_id'],
        ]);
        $_SESSION['succès'] = "Felicitations encore une chose de plus à faire !!";
                header("Location: app.php");
                return;
    }else{
            $_SESSION['error'] = "Le Champ est vide !!";
            header("Location: app.php");
            return;
    }
}

if(isset($_POST["delete"])) {
        $sql = "DELETE FROM tasks WHERE task_id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":id" => $_POST["task_id"]
        ]);
        $_SESSION['supr']= "votre Tache est supprimé ! ";
                header("Location: app.php");
                return;
}

if(isset($_POST['SuprTable'])){
        $sql = "DELETE FROM tasks WHERE user_id=:user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":user_id" => $_SESSION['user']['user_id']
        ]);
        $_SESSION['SuprTable']= "Toutes Vos Taches sont supprimées ! ";
        header("Location: app.php");
        return;
}

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <title>ToDo list</title>
</head>
<body>
  <h1>Bienvenue <?php echo strtoupper(htmlentities($_SESSION['user']['name'])) ?></h1>
<section class="container">
  <div class="row d-flex justify-content-center">
        <div class="card" id="list1" style="border-radius: 0.75rem; background-color: #eff1f2; ">
            <h2 class="h1 text-center mt-3 mb-4 pb-3 text-primary">Mes Taches</h2>
            <?php  

              if(isset($_SESSION['supr'])){
                echo('<div id="alert" class="alert alert-danger" role="alert">');
                echo('<p style="color: red;">'.htmlentities($_SESSION['supr'])."</p>\n");
                echo('</div>');
                unset($_SESSION['supr']);
              }
              if(isset($_SESSION['SuprTable'])){
                echo('<div id="alert" class="alert alert-danger" role="alert">');
                echo('<p style="color: red;">'.htmlentities($_SESSION['SuprTable'])."</p>\n");
                echo('</div>');
                unset($_SESSION['SuprTable']);
              }
              if(isset($_SESSION['succès'])){
                echo('<div id="alert" class="alert alert-success" role="alert">');
                echo('<p style="color: green;">'.htmlentities($_SESSION['succès'])."</p>\n");
                echo('</div>');
                unset($_SESSION['succès']);
              }
              if(isset($_SESSION['modd'])){
                echo('<div id="alert" class="alert alert-warning" role="alert">');
                echo('<p style="color: orange;">'.htmlentities($_SESSION['modd'])."</p>\n");
                echo('</div>');
                unset($_SESSION['modd']);
              }
            ?>
            <form method="POST">
                <input type="text"class="form-control form-control-lg" id="exampleFormControlInput1" placeholder="Nouvelle Tâche..." name="task"/> 
                <?php 
                  if(isset($_SESSION['error'])){
                    echo('<div id="alert" class="alert alert-danger" role="alert">');
                    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
                    echo('</div>');
                    unset($_SESSION['error']);
                  }
                ?>
                <button type="submit" class="btn btn-primary" name="add">Ajouter</button>
            </form>
        <?php 
        echo "<table border='1' class='table'>";
        if(!isset($rows)){
          $rows=null;
          header("Location: app.php");
        } else{
        foreach($rows as $row){
        $tab = <<<TACOS
          <tbody>
            <tr>
                <th scope="row">#{$row['task_id']}</td>
                <th scope="row">{$row['title']}</td>
                <form method="POST">
                        <input type="hidden" name="task_id" value="{$row["task_id"]}">
                    <td>
                    <div class="d-flex flex-row bd-highlight">
                        <button class="btn btn-danger btn-block " type="submit" name="delete" value="{$row["task_id"]}">Supprimer</button>
                        <button class="btn btn-warning btn-block " type="submit" name="update" value="{$row["task_id"]}">Modifier</button>
                    </div>
                    </td>
                </form>
                    </td>
            </tr>
          </tbody>
        TACOS;
        echo $tab;
        }
        echo "</table>";
      }
        ?>
      <form method="POST">
        <button type="submit" class="btn btn-danger" name="SuprTable">Supprimer TOUTES LES TACHES !</button>
      </form>
        </div>
    </div>
  </div>
    <div class="d-flex flex-row bd-highlight mb-3">
      <!-- <form  method="post">
      <button class="btn btn-danger btn-block mb-4" name="return"> <a href="./home.php" class="link-light"> Retour Home page</a></button> -->
      <button class="btn btn-danger"><a class="link-light" href="./logout.php">se déconnecter</a></button>
    </form>
    </div>
</section>

</body>
</html>