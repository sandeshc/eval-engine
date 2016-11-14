<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="css/index.css">
	<script type="text/javascript">
	function loadlist(selobj, url, nameattr)
	{
		/*function to load a list from a JSON output generated from the query given as 'url'*/
		// selobj : element where the list is dumped
		// url : query that outputs JSON object to construct the list
		// nameattr : name of the field in the JSON object to use to construct the list
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

	<title> Generic Search Engine Evaluation System </title>
</head>

<body>
	<h1> Generic Search Engine Evaluation System </h1>
	<div class="container" style="margin-top:50px">
		<div class="row" style="margin-top:20px" >
			<div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
				<form role = "form" autocomplete = "off" action = "init.php" method = "post">
					<fieldset>
						<div class="form-group">
				            <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
			    			<input style="text-align: center;" type='text' name="username" id="username" class="form-control" 
			    				placeholder="Please Enter Your Name" list='user-list'>
				        </div>
				    	<div class="row" style="text-align: center;">
							<div class="col-xs-6 col-sm-6 col-md-6 col-sm-offset-2 col-md-offset-3">
								<button type="submit" class="btn btn-primary btn-block">Start Evaluation</button>
							</div>
				    	</div>
				    	<datalist id='user-list'></datalist>
					</fieldset>
				</form>
		    </div>
		</div>
	</div>

	<script type="text/javascript">
	// Generating drop down list of names of users from the database matching the user-name prefix entered
	$("#username").keyup(function(e)
	{
		if( !(e.which >= 37 && e.which <= 40) && e.which != 13 )
	   		loadlist($('datalist#user-list').get(0), 'get-users.php?prefix=' + $('#username').val(),'name');
	});
	</script>

	<div>
		<hr class="colorgraph">

		<div>
			<h4> <strong> Guidelines for Evaluation </strong> </h4>
			<ul>
				<li> Each result can be marked as <strong>Relevant(2), Partially-Relevant(1) or Irrelevant(0)</strong>,
					 based on the extent of the relevance of the search query to the result (document + context) displayed. </li>
					<br/>
				<li> The <strong>ordering of the results</strong> can be modified to reflect the order of <strong>relevance</strong> among the search results, 
					 with the most relevant results having the least rank (top most result). </li>
					<br/>
				<li> In case the <strong>same result (document & context)</strong> is displayed multiple times then mark the first occourance as usual, and
					 mark the rest of them as Irrelevant(0). </li>
					<br/>
				<li> <strong>Description</strong> represents the ... </li>
				<li> <strong>Context</strong> represents the ... </li>
			</ul>
		</div>
	</div>
</body>
