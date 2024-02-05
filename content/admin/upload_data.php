<?php  
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");
?>

<!DOCTYPE html>
<html>
<head>
	<title>Update Data</title>
</head>
<body>
	<form method="post" enctype="multipart/form-data" action="upload_data_final">
		<label>Upload File here ...</label><br>
		<input type="file" name="my_file" required><br>
		<button type="submit" name="upload">Upload</button>
	</form>
</body>
</html>