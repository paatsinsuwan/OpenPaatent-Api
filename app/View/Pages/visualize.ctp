<div id="content-title">
  <h2>The Correlation Map</h2>
  <p>
    <ul>
    <li><?php echo $document['Document']['title']; ?></li>
    <li><?php echo $tag_section_title; ?></li>
    </ul>
  </p>
</div>
<div id="chart">
  <style type="text/css">

  path.arc {
    cursor: move;
    fill: #fff;
  }

  .node {
    font-size: 70%;
  }

  .node:hover {
/*    fill: #1f77b4; blue*/
    fill: #A3FF75;
  }

  .link {
    fill: none;
/*    stroke: #1f77b4; blue*/
    stroke: #A3FF75;
    stroke-opacity: .4;
    pointer-events: none;
  }

  .link.source, .link.target {
    stroke-opacity: 1;
    stroke-width: 2px;
  }

  .node.target {
    fill: #d62728 !important;
  }

  .link.source {
    stroke: #d62728;
  }

  .node.source {
/*    fill: #2ca02c; green*/
    fill: #1f77b4;
  }

  .link.target {
/*    stroke: #2ca02c; green*/
    stroke: #1f77b4;
  }

      </style>
</div>

<script type="text/javascript" charset="utf-8">
  
  default_url = "<?php echo $this->Html->url($results); ?>";
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
   //'d3.v2',
	 //'cluster',
	 'jquery-1.7.2.min',
	 'd3',
	 'd3.layout',
	 'package',
	 'viz'
	));
?>
