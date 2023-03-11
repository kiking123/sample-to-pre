<?php 
	session_start();
	$db = mysqli_connect('localhost', 'root', '', 'tmsdup');
	$results = mysqli_query($db, "SELECT * FROM users_tbl");

	// initialize variables
	$name = "";
	$username = "";
	//$id = 0;
	$update = false;

	if (isset($_POST['save'])) {
		$name = $_POST['name'];
		$username = $_POST['username'];

		mysqli_query($db, "INSERT INTO users_tbl (name, username) VALUES ('$name', '$username')"); 
		$_SESSION['message'] = "Address saved"; 
		header('location: crud.php');
	}	

	if (isset($_POST['update'])) {
		$user_id = $_POST['user_id'];
		$name = $_POST['name'];
		$username = $_POST['username'];

		mysqli_query($db, "UPDATE users_tbl SET name='$name', username='$username' WHERE user_id=$user_id");
		$_SESSION['message'] = "Address updated!"; 
		header('location: crud.php');
	}

	if (isset($_GET['del'])) {
		$user_id = $_GET['del'];
		mysqli_query($db, "DELETE FROM users_tbl WHERE user_id=$user_id");
		$_SESSION['message'] = "Address deleted!"; 
		header('location: crud.php');
	}

	if (isset($_GET['edit'])) {
		$user_id = $_GET['edit'];
		$update = true;
		$record = mysqli_query($db, "SELECT * FROM users_tbl WHERE user_id = $user_id");

		$n = mysqli_fetch_array($record);
		$name = $n['name'];
		$username = $n['username'];
	}
?>

<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css">
<head>
	<title>CRUD: CReate, Update, Delete PHP MySQL</title>
</head>
<style type="text/css">
	body {
    font-size: 19px;
}
table{
    width: 50%;
    margin: 30px auto;
    border-collapse: collapse;
    text-align: left;
}
tr {
    border-bottom: 1px solid #cbcbcb;
}
th, td{
    border: none;
    height: 30px;
    padding: 2px;
}
tr:hover {
    background: #F5F5F5;
}

form {
    width: 45%;
    margin: 50px auto;
    text-align: left;
    padding: 20px; 
    border: 1px solid #bbbbbb; 
    border-radius: 5px;
}

.input-group {
    margin: 10px 0px 10px 0px;
}
.input-group label {
    display: block;
    text-align: left;
    margin: 3px;
}
.input-group input {
    height: 30px;
    width: 93%;
    padding: 5px 10px;
    font-size: 16px;
    border-radius: 5px;
    border: 1px solid gray;
}
.btn {
    padding: 10px;
    font-size: 15px;
    color: white;
    background: #5F9EA0;
    border: none;
    border-radius: 5px;
}
.edit_btn {
    text-decoration: none;
    padding: 2px 5px;
    background: #2E8B57;
    color: white;
    border-radius: 3px;
}

.del_btn {
    text-decoration: none;
    padding: 2px 5px;
    color: white;
    border-radius: 3px;
    background: #800000;
}
.msg {
    margin: 30px auto; 
    padding: 10px; 
    border-radius: 5px; 
    color: #3c763d; 
    background: #dff0d8; 
    border: 1px solid #3c763d;
    width: 50%;
    text-align: center;
}
</style>
<body>
	<?php if (isset($_SESSION['message'])): ?>
	<div class="msg">
		<?php 
			echo $_SESSION['message']; 
			unset($_SESSION['message']);
		?>
	</div>
	<?php endif ?>

<table>
	<thead>
		<tr>
			<th>Name</th>
			<th>Address</th>
			<th colspan="2">Action</th>
		</tr>
	</thead>
	<tbody>
		
	
	
	<?php while ($row = mysqli_fetch_array($results)) { ?>
		<tr>
			<td><?php echo $row['name']; ?></td>
			<td><?php echo $row['username']; ?></td>
			<td>
				<a href="crud.php?edit=<?php echo $row['user_id']; ?>" class="edit_btn" >Edit</a>
			</td>
			<td>
				<a href="crud.php?del=<?php echo $row['user_id']; ?>" class="del_btn">Delete</a>
			</td>
		</tr>
	<?php } ?>
	</tbody>
</table>

<form method="post" >
<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
		<div class="input-group">
			<label>Name</label>
			<input type="text" name="name" value="<?php echo $name; ?>">
		</div>
		<div class="input-group">
			<label>Address</label>
			<input type="text" name="username" value="<?php echo $username; ?>">
		</div>
		<div class="input-group">
			<?php if ($update == true): ?>
				<button class="btn" type="submit" name="update" style="background: #556B2F;" >update</button>
			<?php else: ?>
				<button class="btn" type="submit" name="save" >Save</button>
			<?php endif ?>
					</div>
				</form>

</body>
</html>

