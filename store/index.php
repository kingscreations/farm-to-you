
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet" />
		<link type="text/css" href="/lib/bootcamp-coders.css"                                        rel="stylesheet" />
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
		<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
		<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>
		<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>		<script type="text/javascript" src="js/controller.js"></script>
		<title>Controller: Store</title>
	</head>
	<body>
		<h1>Controller: Store</h1>
		<form id="tweetController" method="post" action="controller-store.php">
			<label for="storeName">Store Name</label>
			<input type="text" id="storeName" name="storeName" size="140" maxlength="100" /><br />
			<label for="storeDescription">Store Description</label>
			<input type="text" id="storeDescription" name="storeDescription" size="140" maxlength="100" /><br />
			<button type="submit">Submit</button>
		</form>
		<p id="outputArea"></p>
	</body>
</html>