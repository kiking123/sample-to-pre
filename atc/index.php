<?php 
	session_start();
	$db = mysqli_connect('localhost', 'root', '', 'konsulta');
	//$results = mysqli_query($db, "SELECT * FROM tsekap_tbl_profile p inner join tsekap_tbl_enlist e on p.PX_pin = e.PX_PIN where p.PROFILE_OTP LIKE '%pppp%' ORDER BY p.PROF_DATE DESC  ");
	$results = mysqli_query($db, "SELECT * FROM tsekap_tbl_profile p inner join tsekap_tbl_enlist e on p.PX_pin = e.PX_PIN ORDER BY p.PROF_DATE DESC  ");

	// initialize variables
	$name = "";
	$username = "";
	//$id = 0;
	$update = false;
	$revert = false;

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

		mysqli_query($db, "UPDATE tsekap_tbl_profile SET PX_PIN='$name', PROFILE_OTP='$username' WHERE PX_PIN=$user_id");
		$_SESSION['message'] = "Address updated!"; 
		header('location: index.php');
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
		$record = mysqli_query($db, "SELECT * FROM tsekap_tbl_profile WHERE PX_PIN = $user_id");

		$n = mysqli_fetch_array($record);
		$name = $n['PX_PIN'];
		$username = $n['PROFILE_OTP'];
	}

	if (isset($_GET['to_revert'])) {
		$user_id = $_GET['to_revert'];
		$revert = true;
		$record = mysqli_query($db, "SELECT * FROM tsekap_tbl_profile WHERE PX_PIN = $user_id");

		$n = mysqli_fetch_array($record);
		$name = $n['PX_PIN'];
		$username = $n['PROFILE_OTP'];
	}

	if (isset($_POST['revert'])) {
		$user_id = $_POST['user_id'];
		$name = $_POST['name'];
		$username = $_POST['username'];

		mysqli_query($db, "UPDATE tsekap_tbl_profile SET PROFILE_OTP='',WITH_ATC='Y',IS_FINALIZE='N' WHERE PX_PIN=$user_id");
		$_SESSION['message'] = "Address updated!"; 
		header('location: index.php');
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

<form method="post" >
<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
		<div class="input-group">
			<label><center>PIN</center></label>
			<input type="text" name="name" value="<?php echo $name; ?>">
		</div>
		<div class="input-group">
			<label><center>ATC</center></label>
			<input type="text" name="username" value="<?php echo $username; ?>">
		</div>
		<div class="input-group">
			<?php if ($update == true){ $revert = false;?>
				<button class="btn" type="submit" name="update" style="background: #556B2F;" ><center>update</center></button>
			<?php }elseif ($revert == true){ $update = false;?>
				<button class="btn" type="submit" name="revert"><center>revert</center></button>
			<?php }else{ ?>
				<button class="btn" type="submit" name="save" >revert</button>
			<?php }?>
					</div>
				</form>

<table>
	<thead>
		<tr>
			<th><center>PIN</center></th>
			<th><center>ATC</center></th>
			<th><center>Date added</center></th>
			<th><center>Type</center></th>
			<th colspan="2"><center>Action</center></th>
		</tr>
	</thead>
	<tbody>
		
	
	
	<?php while ($row = mysqli_fetch_array($results)) { ?>
		<tr>
			<td><?php echo $row['PX_PIN']; ?></td>
			<td><?php echo $row['PROFILE_OTP']; ?></td>
			<td><?php echo $row['DATE_ADDED']; ?></td>
			<td><?php echo $row['PX_TYPE']; ?></td>
			<td>
				<a href="index.php?edit=<?php echo $row['PX_PIN']; ?>" class="edit_btn" >Edit ATC</a>
			</td>
			<td>
				<a href="index.php?to_revert=<?php echo $row['PX_PIN']; ?>" class="del_btn">Revert</a>
			</td>
		</tr>
	<?php } ?>
	</tbody>
</table>


</body>
</html>

