<?php
   // Initialize the session
   
   require_once "config.php";
   require_once "functions.php";

   session_start();
   
   // Check if the user is logged in, if not then redirect him to login page
   if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
       header("location: login.php");
       exit;
   }
   

   if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['security'])) {
      // Get the selected value from the form
      $security = htmlspecialchars($_POST['security']);
  
      // Store the value in the session
      $_SESSION['security'] = $security;

  
   }
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="ISO-8859-1">
      <title>ArticleSpace</title>
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
      <link rel="stylesheet" href="styles.css">

      <style>
        
      </style>
   </head>
   <body>
      <!-- Navbar -->
      <div class="navbar">
		<h2>ArticleSpace</h2>
		<ul>
		<li><a href="logout.php" style="color: white;">      Logout</a></li>

			<li style="margin-right: 20px !important;" ><form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<select name = "security">
            <option value="1" <?php echo ($_SESSION['security'] == "1") ? "selected" : ""; ?>>Attack</option>
            <option value="2" <?php echo ($_SESSION['security'] == "2") ? "selected" : ""; ?>>Counter Measure</option>
      
				</select>
                <input type="submit"  value="Ok">
			</form>
		</li>
		</ul>
	</div>
      <!-- Sidebar -->
      <div class="sidebar">
         <ul>
            <li><a href="welcome.php">Home</a></li>
            <li><a href="write_post.php">Write a post (XSS Post)</a></li>
            <li><a href="profile.php?user=<?php echo $_SESSION["username"]; ?>">Profile (XSS Get)</a></li>
         </ul>
      </div>
      <!-- Page content -->
      <div class="user-display">
      <!-- User display section -->
      <div>
            <h1>Editor</h1>
         </div>
               <div>
         
         <form action="<?php echo($_SERVER["SCRIPT_NAME"]);?>" method="POST">
            <div class="m-3">
               <div class="form-group row">
                  <div class="col-8">
                     <input name="title" type="text"  class="form-control"
                        required  placeholder = "Title"/>
                  </div>
               </div>
               <div class="form-group row">
                  <div class="col-8">
                     <textarea name="content" type="text"  class="form-control"
                        required minlength="2" maxlength="2000"  placeholder = "Write Something .."   rows = 8></textarea>
                  </div>
               </div>
               
               <div>
               <button type="submit" class="btn btn-primary" name="form" value="submit">Post</button>  

				</div>
            </div>
         </form>
      </div>
      <div>
      <?php
      if(isset($_POST["title"]) && isset($_POST["content"]))
    {   

        $title =  xss($_POST["title"]);
        $content =  xss($_POST["content"]);    
        $id =   xss($_SESSION["id"]);




       $sql = "INSERT INTO articles (author_id, title, content) VALUES (?, ?, ?)";

      if($stmt = mysqli_prepare($link, $sql)){
         // Bind variables to the prepared statement as parameters
         mysqli_stmt_bind_param($stmt , "sss", $id, $title, $content);

         // Attempt to execute the prepared statement
         if(mysqli_stmt_execute($stmt)){
             // Redirect to login page
             echo "<p style='color:green;'>Article posted successfully!</p>";
             } else {
                echo "Something went wrong. Please try again later. " . $conn->error;
             }

         // Close statement
         mysqli_stmt_close($stmt);
     }

   
        
      echo "</br</br><h4> " . $title . "</h4> </br> <p> " . $content. "</p?>";


    }
    ?>
    </div>
   </body>
</html>
