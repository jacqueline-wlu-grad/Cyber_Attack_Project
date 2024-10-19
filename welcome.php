<?php
// Initialize the session
session_start();
require_once "config.php";
require_once "functions.php";


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
				<select name="security">
				<option value="1" <?php echo ($_SESSION['security'] == "1") ? "selected" : ""; ?>>Attack</option>
            <option value="2" <?php echo ($_SESSION['security'] == "2") ? "selected" : ""; ?>>Counter Measure</option>
      
				</select>
                <input type="submit"  value="Ok">
			</form>
		</li>
		</ul>
	</div>
    <!-- <h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1> -->

	<!-- Sidebar -->
	<div class="sidebar">
		<ul>
			<li><a href="welcome.php">Home</a></li>
			<li><a href="write_post.php">Write a post (XSS Post)</a></li>
            <li><a href="profile.php?user=<?php echo htmlspecialchars($_SESSION["username"]); ?>">Profile (XSS Get)</a></li>

		</ul>
	</div>

	<!-- Page content -->
	<div class="user-display">
		<!-- User display section -->
		<h2></h2>
		<div class="user-container">
			<!-- User cards will be dynamically added here -->

			<?php
// ... (code from step 2)
$id = (int)$_SESSION["id"];



$sql = "SELECT title, content, created_at FROM articles WHERE author_id = ?";
$stmt = $link->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Check if any articles were found
if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {

		echo "<div class='user-card'>";
        echo "<div  style = 'height: 40px;  '><h3>".$row["title"]."</h3></div>";
        echo "<p id = 'content' style = 'text-align: justify; overflow: scroll; height: 220px;'> ".  xss($row["content"])."</p>";
		echo "</div>";
       
    }
} else {
    echo "No articles found for the given author ID.";
}


mysqli_stmt_close($stmt); // Close the statement after use


// ... (code from step 2)
?>
		</div>
	</div>

	<!-- JavaScript for fetching and displaying users -->
	</body>
</html>
