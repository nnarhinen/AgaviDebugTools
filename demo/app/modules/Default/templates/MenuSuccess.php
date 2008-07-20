
<ul>
	<li><a href="/">Index</a></li>
	<li><a href="<?php echo $ro->gen(null, array('locale' => 'en')); ?>">+ Locale</a></li>
	<li><a href="<?php echo $ro->gen(null, array('locale' => 'en', 'foo'=>'bar', 'arr[1]'=>'value1', 'arr[2]'=>'value2')); ?>">+ Parameters</a></li>
</ul>
	