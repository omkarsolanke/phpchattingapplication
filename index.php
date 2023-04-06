<?php
 
session_start();

if(isset($_GET['logout'])){    
     
    //Simple exit message
    $logout_message = "<div class='msgln'><span class='left-info'>User <b class='user-username-left'>". $_SESSION['username'] ."</b> has left the chat session.</span><br></div>";
    file_put_contents("log.html", $logout_message, FILE_APPEND | LOCK_EX);
     
    session_destroy();
    header("Location: index.php"); //Redirect the user
}
 
if(isset($_POST['enter'])){
    if($_POST['username'] != ""){
        $_SESSION['username'] = stripslashes(htmlspecialchars($_POST['username']));
    }
    else{
        echo '<span class="error" style="text-align: center;
        color: red;"> <h2>Please type a username</h2></span>';
    }
}

function loginForm(){
    echo
    '<div class="container">
    <br />
    
    
    <br />
    <div class="panel panel-default">
          <div class="panel-heading" align="center"><h2>Enter Username To Enter In Chat</h2></div>
        <div class="panel-body">
            <p class="text-danger"><?php echo $message; ?></p>
            <form method="post" class="row g-3 needs-validation" novalidate action="index.php">
                <div class="form-group">
                    <label>Enter Username</label>
                    <input type="text" name="username" class="form-control" id="username" required />
                </div>
                <div class="form-group">
                    <input type="submit"  class="btn btn-info" value="Login" name="enter" id="enter" name="login"/>
                </div>
            </form>
            <br />
            <br />
            <br />
            <br />
        </div>
    </div>
</div>';
}

?>
 
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">

        <title>PublicTown</title>
        <meta username="description" content="Tuts+ Chat Application" />
       <link rel="stylesheet" href="style.css">
    </head>
    <body>
    <?php
    if(!isset($_SESSION['username'])){
        loginForm();
    }
    else {
    ?>
        <div id="wrapper">
            <div id="menu">
                <p class="welcome">Welcome, <b><?php echo $_SESSION['username']; ?></b></p>
                <p class="logout"><a id="exit" href="#">Exit Chat</a></p>
            </div>
 
            <div id="chatbox">
            <?php
            if(file_exists("log.html") && filesize("log.html") > 0){
                $contents = file_get_contents("log.html");          
                echo $contents;
            }
            ?>
            </div>
            <div class="rowwws">
              <div class="collect">
                <input type="text" class="form-controllr"  id="usermsg" name="usermsg">
              </div>
              <button type="button" class="btnnn primary"  name="submitmsg" id="submitmsg">Send</button>
            </div>
        </div>

        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script type="text/javascript">
            
            // jQuery Document  id="submitmsg" id="usermsg" username="submitmsg"  username="usermsg"
            $(document).ready(function () {
                $("#submitmsg").click(function () {
                    var clientmsg = $("#usermsg").val();
                    $.post("post.php", { text: clientmsg });
                    $("#usermsg").val("");
                    return false;
                });
 
                function loadLog() {
                    var oldscrollHeight = $("#chatbox")[0].scrollHeight - 20; //Scroll height before the request
                    $.ajax({
                        url: "log.html",
                        cache: false,
                        success: function (html) {
                            $("#chatbox").html(html); //Insert chat log into the #chatbox div
 
                            //Auto-scroll           
                            var newscrollHeight = $("#chatbox")[0].scrollHeight - 20; //Scroll height after the request
                            if(newscrollHeight > oldscrollHeight){
                                $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div
                            }   
                        }
                    });
                }
                setInterval (loadLog, 2500);
                $("#exit").click(function () {
                    var exit = confirm("Are you sure you want to end the session?");
                    if (exit == true) {
                    window.location = "index.php?logout=true";
                    }
                });
            });
        </script>
    </body>
</html>
<?php
}
?>