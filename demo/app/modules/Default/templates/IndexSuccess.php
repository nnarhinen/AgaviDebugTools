<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<base href="<?php echo $this->getContext()->getRouting()->getBaseHref(); ?>" />
	<title>Welcome to Agavi!</title>
	<style type="text/css">

body {
	margin: 1em;
	background: #fff;
	font-family: 'Trebuchet MS', Verdana, Tahoma, Arial, Helvetica, sans-serif;
	font-size: 9pt;
	color: #999;
}

a, a:link, a:visited, a:active, a:hover {
	color: #AAF;
}

h1 {
	color: #666;
}

p.light {
	color: #CCC;
}

p.light a, p.light a:link, p.light a:visited, p.light a:active, p.light a:hover {
	color: #CCF;
}

	</style>
</head>
<body>
	<div>
		<h1>Debug Toolbar Sample</h1>
		<ul>
			<li><a href="/">Plain page</a></li>
			<li><a href="<?php echo $ro->gen(null, array('locale' => 'en')); ?>">+ Locale</a></li>
			<li><a href="<?php echo $ro->gen(null, array('locale' => 'en', 'foo'=>'bar', 'arr[1]'=>'value1', 'arr[2]'=>'value2')); ?>">+ Parameters</a></li>
		</ul>
	</div>
</body>
</html>