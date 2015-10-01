<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	<script type="text/javascript">
	function loadlist(selobj, url, nameattr)
	{
		/*function to load a list*/
	    $(selobj).empty();
	    $.getJSON(url, {}, function(data)
	    {
	        $.each(data, function(i,obj)
	        {
	            $(selobj).append($('<option></option>').val(obj[nameattr]).html(obj[nameattr]));
	        });
	    });
	}
	</script>

	<title> LaSer Evaluation Engine </title>
</head>

<body>
	<div style="width:800px; margin:0 auto;">
	<h1> Evaluation Engine </h1>
	<form autocomplete="off" action = "init.php" method = "post">
		<div class="col-xs-4" style="padding: 10px;">
			<div class="input-group">
	            <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
    			<input type='text' name="username" id="username" class="form-control" placeholder="Please enter your Name" list='user-list'>
	        </div>
    	</div>
    	<div class="col-xs-4" style="padding: 10px;">
			<button type="submit" class="btn btn-primary">Login</button>
    	</div>
    	<datalist id='user-list'></datalist>
	</form>
	</div>

	<script type="text/javascript">
	$("#username").keyup(function()
	{
	   	loadlist($('datalist#user-list').get(0), 'get-users.php?prefix=' + $('#username').val(),'name');
	});
	</script>
</body>
