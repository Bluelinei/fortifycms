<!DOCTYPE html>
<html>
<style>
	* {
		box-sizing: border-box;
		margin:0px;
		padding:0px;
	}
	body {
		margin: 0%;
		background: #ddd;
		font-family: 'Montserrat', sans-serif;
	}

	ul {
		list-style-type: none;
		margin: 0px;
		padding: 0px;
	}

	.agency-list li {
		padding-bottom: 10px;
	}

	.agency-list li:last-child {
		padding-bottom: 0px;
	}

	.agency-list a {
		color: #333;
		font-size: 18px;
		cursor: pointer;
	}

	.main-wrapper {
		position: fixed;
		width: calc(100% - 40px);
		height: calc(100% - 40px);
		top:50%;
		left:50%;
		transform: translate(-50%,-50%);
		border: 2px groove #333;
		background: #eee;
	}

	.agencies {
		position:absolute;
		height: 100%;
		width: 15%;
		padding: 10px;
		border-right: 3px groove #333;
		overflow-y: auto;
	}

	.users li {
		padding: 0px;
		padding-left: 20px;
		margin: 0px;
	}

	.users a {
		font-size: 16px;
	}

	.rightm {
		margin-right: 10px;
	}

	.leftm {
		margin-left: 10px;
	}

	li .icon-brace {
		float:left;
		width: 21px;
		height: 21px;
		background: url('mid.png');
	}

	li:last-child .icon-brace {
		float:left;
		width: 21px;
		height: 21px;
		background: url('end.png');
		background-repeat: no-repeat;
	}
</style>
<head>
	<title>Fortify Agency Manager</title>
	<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet"> 
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
		<div>
			<div class="">
			</div>
			<div class="">
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