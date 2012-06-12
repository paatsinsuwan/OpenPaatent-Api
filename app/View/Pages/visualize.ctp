<!-- <script type="text/x-jQuery-tmpl" id="patents-index-template">
	<p>${title}</p>
</script> -->
<div id="chart">
</div>
<script type="text/javascript" charset="utf-8">
  default_url = "<?php echo $this->Html->url(array('controller' => 'pages', 'action' => 'visualize')); ?>";
</script>
<?php
  // ready for spine if needed
	// echo $this->Html->script(array(
	//     'jquery-1.7.2.min',
	//     'jquery.tmpl',
	//     '/js/spine/lib/spine.js',
	//     '/js/spine/lib/ajax.js',
	//     '/js/spine/lib/manager.js',
	//     '/js/spine/lib/route.js',
	//     '/js/spine/lib/relation.js',
	//     '/js/spine/lib/tmpl.js',
	//     '/js/app/models/user.js',
	//     '/js/app/models/patent.js',
	//     '/js/app/models/profile.js',
	//     '/js/app/models/degree.js',
	//     '/js/app/controllers/users.js',
	//     '/js/app/controllers/patents.js',
	//     '/js/app/application.js',
	//   ));
	// 3d js script
	echo $this->Html->script(array(
	 'd3.v2',
	 'cluster'
	));
?>
