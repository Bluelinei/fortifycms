<!DOCTYPE html>
<html>
<head>
	<title>Fortify Agency Manager</title>
	<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet"> 
	<link href="style.css" rel="stylesheet"> 
	<script src="https://code.jquery.com/jquery-3.1.1.js"></script>
	<script src="../framework/toolkit.js"></script>
	<script src="https://use.fontawesome.com/47f9a4e330.js"></script>
</head>
<body>
	<div class="main-wrapper">
		<div class="agencies">
			<p style="font-size: 20px; color: #888; margin-bottom: 10px; padding-left: 20px;">Agencies</p>
			<ul class="agency-list">
				<li class="agency"><a>Blueline Solutions (1)<i class="fa fa-caret-down leftm"></i></a>
					<ul class="users">
						<li><span class="icon-brace rightm"></span><a>tyler</a></li>
						<li><span class="icon-brace rightm"></span><a>zach</a></li>
						<li><span class="icon-brace rightm"></span><a>mark</a></li>
					</ul>
				</li>
				<li class="agency"><a>Girard PD (4501)<i class="fa fa-caret-right leftm"></i></a></li>
				<li class="agency"><a>Akron PD (4502)<i class="fa fa-caret-right leftm"></i></a></li>
				<li class="agency"><a>Mayberry PD (4503)<i class="fa fa-caret-right leftm"></i></a></li>
			</ul>
		</div>
		<div class="editor">
			<div class="fields">
				<p style="margin-bottom: 0px;">Agency ID: 1</p>
				<p>Database Ref: blueline_TN</p>
				<label for="agency-name">Department Name: </label>
				<input id="agency-name" type="text" placeholder="Agency Name"/><br/>
			</div>
			<div class="lists">
				<p>Tags</p><br/>
				<div class=""></div>
			</div>
		</div>
	</div>
</body>
<script>
	//Retrieve all agencies from the database
	function getAgencies()
	{
		var f = new FormData();
		f.append('function', 'agencies');
		ajax('agencymanager.php', f, function(response){
			log(response);
			var data = JSON.parse(response);
		});
	}

	function Agency()
	{
		this.name;
		this.id;
		this.users;
		this.element = new AgencyElement();
		agencies += this;
	}

	function AgencyElement()
	{
		this.dom = $('<li>');
	}

	var agencies = [];

	$(window).on('load', function(){
		getAgencies();
	});
	//Store them for reference
</script>
</html>