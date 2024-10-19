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

			<li style="margin-right: 20px !important;" >
         <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?user=".$_SESSION["username"]; ?>" method="post">
				<select name = "security">
            <option value="1" <?php echo ($_SESSION['security'] == "1") ? "selected" : ""; ?>>Attack</option>
            <option value="2" <?php echo ($_SESSION['security'] == "2") ? "selected" : ""; ?>>Counter Measure</option>
      
				</select>
                <input type="submit"  value="Ok">
			</form>
		</li>
		</ul>
	</div>
      </div>
      <!-- <h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1> -->
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
            <h1>User Details</h1>
         </div>
		       <div>
        <?php $username = xss($_GET["user"]);
        $firstname =  xss($_SESSION["firstname"]);
        $lastname =  xss($_SESSION["lastname"]);
        $email =  xss($_SESSION["email"]);
        ?>
     
         <form >
            <input type="hidden" th:field="*{image}" />
            <div class="m-3">
               <div class="form-group row">
                  <label class="col-4 col-form-label">Username: </label>
                  <div class="col-8">
                    
                           <?php	
                           echo $username; 
                           
                           ?>
                  </div>
               </div>
               <div class="form-group row">
                  <label class="col-4 col-form-label">First Name: </label>
                  <div class="col-8">
                     <?php 
                           echo $firstname;
                           ?>
                  </div>
               </div>
               <div class="form-group row">
                  <label class="col-4 col-form-label">Last Name: </label>
                  <div class="col-8">
                    <?php 
                           echo $lastname;
                           ?>
                  </div>
               </div>
               <div class="form-group row">
                  <label class="col-4 col-form-label">Email</label>
                  <div class="col-8">
                     <?php
                           echo $email; 
                           ?>
                  </div>
               </div>
              
            </div>
         </form>
      </div>
      
   </body>
</html>
