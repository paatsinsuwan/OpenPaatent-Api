<?php 
  $keyword_types_mode = array(
    array("name" => "general keywords", "field_name" => "all_tags"),
    array("name" => "personal tags", "field_name" => "personal_tags"),
    array("name" => "outside tags", "field_name" => "outside_tags"),
    array("name" => "metadata", "field_name" => "extra_info_tags"),
  );
  $layout_array = array(
    array("name" => "Layout 1", "layout_name" => "layout1"),
    array("name" => "Layout 2", "layout_name" => "layout2"),
    array("name" => "Layout 3", "layout_name" => "layout3"),
    array("name" => "Layout 4", "layout_name" => "layout4"),
  );
?>
<div id="content-title">
  <?php if(!empty($document) && !empty($tag_section_title)): ?>
    <h2>The Correlation Map</h2>
    <p>
      <ul>
      <li><?php echo $document['Document']['title']; ?></li>
      <li><?php echo $tag_section_title; ?></li>
      </ul>
    </p>
  <?php else: ?>
    <h2>please select one of these links below to see visualization</h2>
    <p>
      <ul class="document-item-container">
        <?php foreach($documents as $document): ?>
          <?php 
            $doc_id = $document['Document']['id'];
            $patent_name = $document['Document']['title'];
          ?>
          <li class="document-item"><span><?php echo "Patent title: ".strtolower($document['Document']['title']); ?></span>
          <ul class="visualize-item-container">
            <div id="mask">
            <?php foreach($layout_array as $layout)://($doc_id = 1; $doc_id < 7; $doc_id++): ?>
               
              <li class="visualize-layout-item">
                <span><?php echo "Correlation map of : ". strtolower($layout['layout_name']) ;?></span>
                <div class="layout-container-block">
                  <ul>
                    <?php foreach($keyword_types_mode as $mode): ?>
                      <li><?php echo $this->Html->link("Users keywords type : $mode[name]", array("controller" => "pages", "action" => "flare_visualize", $layout['layout_name'], $doc_id, $mode['field_name'])); ?></li>
                    <?php endforeach; ?>
                  </ul>
                </div>
              </li>
            <?php endforeach; ?>
            </div>
          </ul> 
          </li> 
        <?php endforeach; ?>
      </ul>
    </p>
  <?php endif; ?>
</div>
<div id="chart">
  <style type="text/css">
  #chart {
  	position: relative;
  }
  .navbar .brand, .navbar .nav > li > a {
  	color: #ffffff;
  }

  #chart .invert {
    background: #1f1f1f;
    color: #dcdccc;
  }

  #chart .invert h2, #chart .invert h3 {
    color: #7f9f7f;
  }

  #chart .string, #chart .regexp {
    color: #f39;
  }

  #chart .keyword {
    color: #00c;
  }

  #chart .comment {
    color: #777;
    font-style: oblique;
  }

  #chart .number {
    color: #369;
  }

  #chart .class, #chart .special {
    color: #1181B8;
  }

  /*body {
    font: 300 36px "Helvetica Neue";
    height: 640px;
    margin: 80px 160px 80px 160px;
    overflow: hidden;
    position: relative;
    width: 960px;
  }*/

  #chart a:link, a:visited {
    color: #777;
    text-decoration: none;
  }

  #chart a:hover {
    color: #666;
  }

  #chart blockquote {
    margin: 0;
  }

  #chart blockquote:before {
    content: "“";
    position: absolute;
    left: -.4em;
  }

  #chart blockquote:after {
    content: "”";
    position: absolute;
  }

  body > ul {
    margin: 0;
    padding: 0;
  }

  #chart h1 {
    font-size: 64px;
  }

  #chart h1, h2, h3 {
    font-weight: inherit;
    margin: 0;
  }

  #chart h2, h3 {
    text-align: right;
    font-size: inherit;
    position: absolute;
    bottom: 0;
    right: 0;
  }

  #chart h2 {
    font-size: 24px;
    position: absolute;
  }

  #chart h3 {
    bottom: -20px;
    font-size: 18px;
  }

  #chart .invert {
    background: #1f1f1f;
    color: #dcdccc;
  }

  #chart .invert h2, #chart .invert h3 {
    color: #7f9f7f;
  }

  #chart .string, #chart .regexp {
    color: #f39;
  }

  #chart .keyword {
    color: #00c;
  }

  #chart .comment {
    color: #777;
    font-style: oblique;
  }

  #chart .number {
    color: #369;
  }

  #chart .class, #chart .special {
    color: #1181B8;
  }

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
<?php if(!empty($results)): ?>
  <!--[if IE]> 
    <?php echo $this->Html->script(array('svg', 'ie-svg')); ?>
  <![endif]-->
  <script type="text/javascript" charset="utf-8">
  
    default_url = "<?php echo $this->Html->url($results); ?>";
  </script>
  <?php
  	// 3d js script
  	echo $this->Html->script(array(
     'd3',
  	 'd3.layout',
  	 'package',
  	 'viz'
  	));
  ?>
<?php endif; ?>
