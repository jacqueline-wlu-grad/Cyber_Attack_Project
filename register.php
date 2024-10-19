<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = $firstName = $lastName = $email = "";
$username_err = $password_err = $confirm_password_err = $firstName_err = $lastName_err = $email_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    }
    //Input Sanitization- Counter measure 

    //  elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
    //     $username_err = "Username can only contain letters, numbers, and underscores.";
    // } 
    else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } 
    //Input Sanitization Counter measure 
    // elseif(strlen(trim($_POST["password"])) < 6){
    //     $password_err = "Password must have atleast 6 characters.";
    // }
     else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    if(empty(trim($_POST["firstName"]))){
        $firstName_err = "Please enter a First Name.";     
    } 
     else{
        $firstName = trim($_POST["firstName"]);
    }

    if(empty(trim($_POST["lastName"]))){
        $lastName_err = "Please enter a Last Name.";     
    } 
     else{
        $lastName = trim($_POST["lastName"]);
    }

    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter a Last Name.";     
    } 
     else{
        $email = trim($_POST["email"]);
    }
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password, email, firstName, lastName) VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $param_username, $param_password, $param_email, $param_firstName, $param_lastName);
            
            // Set parameters
            $param_username = $username;

            // Pasword secure hash with random salt 
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            $param_firstName = $firstName;
            $param_lastName = $lastName;
            $param_email = $email;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
       body {
        background: linear-gradient(to bottom, #344e41, #3a5a40);

            font-family: Arial, sans-serif;
            margin: 0;
            padding:  ;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            max-width: 600px;
            margin: 50px auto; /* Adjusted margin to center the container */
            padding: 20px;
            border: 1px solid #F8BBD0;
            border-radius: 5px;
            background: #ffffff;
			 
        }

        h1 {
            color: #C2185B; /* Purple color */
        }

        input[type="text"],
        input[type="password"],
        .btn-primary {
            display: block;
            width: 95%;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn-primary {
            background-color: #588157;
 	/* Purple gradient on hover */
 	border: 1px solid #588157;
            cursor: pointer;
			
            margin-top:30px
            
        }

        .btn-primary:hover {
            background-color: white;
 	/* Purple gradient */
 	color: #344e41;
 	border: 1px solid #588157;
        }

        a {
            text-decoration: none;
            color: #007bff;
        }

        a:hover {
            text-decoration: underline;
        }
        .form-container{
            padding-top: 30px; padding-bottom:30px; background-color : white; max-width: 600px; border: 1px solid #ccc; border-radius: 5px;
        }

    </style>
</head>
<body>
    <div class="wrapper">
        <center>
    <div class ="form-container m-5" >
        <h2>Sign Up</h2>
    </br>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <input type="text" name="username" placeholder= "Username" class="form-control <?php #echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <input type="password" name="password" placeholder= "Password"class="form-control <?php# echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="password" name="confirm_password" placeholder= "Confirm Password" class="form-control <?php #echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder= "Email" style= "width:95%" class="form-control <?php #echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>  
            <div class="form-group">
                <input type="text" name="firstName" placeholder= "First Name"class="form-control <?php #echo (!empty($firstName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $firstName; ?>">
                <span class="invalid-feedback"><?php echo $firstName_err; ?></span>
            </div>  
            <div class="form-group">
               <input type="text" name="lastName" placeholder= "Last Name" class="form-control <?php #echo (!empty($lastName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $lastName; ?>">
                <span class="invalid-feedback"><?php echo $lastName_err; ?></span>
            </div>  
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
</div>
    </center>
    </div>    
</body>
</html>