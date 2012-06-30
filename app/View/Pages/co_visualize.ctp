<div id="content-title">
  <?php if(!empty($documents)): ?>
    <h2>Co-occurrence relation graph</h2>
    <p>
      <ul>
        <?php foreach($documents as $document): ?>
        <?php $doc_id = $document['Document']['id']; ?>
        <li><?php echo $this->Html->link("View", array("controller" => "pages", "action" => "co_visualize", true,  $doc_id), array("class" => "button")); ?> tags for <?php echo $document['Document']['title']; ?></li>
        <?php endforeach; ?>
      </ul>
    </p>
  <?php else: ?>
    <?php
      echo $this->Html->script(array('d3.v2'));
    ?>
    <style type="text/css">
    h2 {
      margin-top: 2em;
    }

    h1, h2 {
      text-rendering: optimizeLegibility;
    }

    h2 a {
      color: #ccc;
      margin-left: -20px;
      position: absolute;
      width: 740px;
    }

    footer {
      font-size: small;
      margin-top: 8em;
    }

    header aside {
      margin-top: 88px;
    }

    header aside,
    footer aside {
      color: #636363;
      text-align: right;
    }

    aside {
      font-size: small;
      margin-left: 780px;
      position: absolute;
      width: 180px;
    }

    .attribution {
      font-size: small;
      margin-bottom: 2em;
    }

    #chart > p, li > p {
      line-height: 1.5em;
    }

    #chart > p {
      width: 720px;
    }

    #chart > blockquote {
      width: 640px;
    }

    #chart li {
      width: 680px;
    }

    a {
      color: steelblue;
    }

    a:not(:hover) {
      text-decoration: none;
    }

    pre, code, textarea {
      font-family: "Menlo", monospace;
    }

    code {
      line-height: 1em;
    }

    textarea {
      font-size: 100%;
    }

    #chart > pre {
      border-left: solid 2px #ccc;
      padding-left: 18px;
      margin: 2em 0 2em -20px;
    }

    .html .value,
    .javascript .string,
    .javascript .regexp {
      color: #756bb1;
    }

    .html .tag,
    .css .tag,
    .javascript .keyword {
      color: #3182bd;
    }

    .comment {
      color: #636363;
    }

    .html .doctype,
    .javascript .number {
      color: #31a354;
    }

    .html .attribute,
    .css .attribute,
    .javascript .class,
    .javascript .special {
      color: #e6550d;
    }

    svg {
      font: 10px sans-serif;
    }

    .axis path, .axis line {
      fill: none;
      stroke: #000;
      shape-rendering: crispEdges;
    }

    sup, sub {
      line-height: 0;
    }

    q:before,
    blockquote:before {
      content: "“";
    }

    q:after,
    blockquote:after {
      content: "”";
    }

    blockquote:before {
      position: absolute;
      left: 2em;
    }

    blockquote:after {
      position: absolute;
    }
    
    </style>
    <script type="text/javascript" charset="utf-8">
      default_url = window.location.pathname;

      var margin = {top: 80, right: 0, bottom: 10, left: 80},
          width = 720,
          height = 720;

      var x = d3.scale.ordinal().rangeBands([0, width]),
          z = d3.scale.linear().domain([0, 4]).clamp(true),
          c = d3.scale.category10().domain(d3.range(10));

      var svg = d3.select("#chart").append("svg")
          .attr("width", width + margin.left + margin.right)
          .attr("height", height + margin.top + margin.bottom)
          .style("margin-left", -margin.left + "px")
        .append("g")
          .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

      d3.json(default_url, function(miserables) {
        var matrix = [],
            nodes = miserables.nodes,
            n = nodes.length;

        // Compute index per node.
        nodes.forEach(function(node, i) {
          node.index = i;
          node.count = 0;
          matrix[i] = d3.range(n).map(function(j) { return {x: j, y: i, z: 0}; });
        });

        // Convert links to matrix; count character occurrences.
        miserables.links.forEach(function(link) {
          matrix[link.source][link.target].z += link.value;
          matrix[link.target][link.source].z += link.value;
          matrix[link.source][link.source].z += link.value;
          matrix[link.target][link.target].z += link.value;
          nodes[link.source].count += link.value;
          nodes[link.target].count += link.value;
        });

        // Precompute the orders.
        var orders = {
          name: d3.range(n).sort(function(a, b) { return d3.ascending(nodes[a].name, nodes[b].name); }),
          count: d3.range(n).sort(function(a, b) { return nodes[b].count - nodes[a].count; }),
          group: d3.range(n).sort(function(a, b) { return nodes[b].group - nodes[a].group; })
        };

        // The default sort order.
        x.domain(orders.name);

        svg.append("rect")
            .attr("class", "background")
            .attr("width", width)
            .attr("height", height);

        var row = svg.selectAll(".row")
            .data(matrix)
          .enter().append("g")
            .attr("class", "row")
            .attr("transform", function(d, i) { return "translate(0," + x(i) + ")"; })
            .each(row);

        row.append("line")
            .attr("x2", width);

        row.append("text")
            .attr("x", -6)
            .attr("y", x.rangeBand() / 2)
            .attr("dy", ".32em")
            .attr("text-anchor", "end")
            .text(function(d, i) { return nodes[i].name; });

        var column = svg.selectAll(".column")
            .data(matrix)
          .enter().append("g")
            .attr("class", "column")
            .attr("transform", function(d, i) { return "translate(" + x(i) + ")rotate(-90)"; });

        column.append("line")
            .attr("x1", -width);

        column.append("text")
            .attr("x", 6)
            .attr("y", x.rangeBand() / 2)
            .attr("dy", ".32em")
            .attr("text-anchor", "start")
            .text(function(d, i) { return nodes[i].name; });

        function row(row) {
          var cell = d3.select(this).selectAll(".cell")
              .data(row.filter(function(d) { return d.z; }))
            .enter().append("rect")
              .attr("class", "cell")
              .attr("x", function(d) { return x(d.x); })
              .attr("width", x.rangeBand())
              .attr("height", x.rangeBand())
              .style("fill-opacity", function(d) { return z(d.z); })
              .style("fill", function(d) { return nodes[d.x].group == nodes[d.y].group ? c(nodes[d.x].group) : null; })
              .on("mouseover", mouseover)
              .on("mouseout", mouseout);
        }

        function mouseover(p) {
          d3.selectAll(".row text").classed("active", function(d, i) { return i == p.y; });
          d3.selectAll(".column text").classed("active", function(d, i) { return i == p.x; });
        }

        function mouseout() {
          d3.selectAll("text").classed("active", false);
        }

        d3.select("#order").on("change", function() {
          clearTimeout(timeout);
          order(this.value);
        });

        function order(value) {
          x.domain(orders[value]);

          var t = svg.transition().duration(2500);

          t.selectAll(".row")
              .delay(function(d, i) { return x(i) * 4; })
              .attr("transform", function(d, i) { return "translate(0," + x(i) + ")"; })
            .selectAll(".cell")
              .delay(function(d) { return x(d.x) * 4; })
              .attr("x", function(d) { return x(d.x); });

          t.selectAll(".column")
              .delay(function(d, i) { return x(i) * 4; })
              .attr("transform", function(d, i) { return "translate(" + x(i) + ")rotate(-90)"; });
        }

        var timeout = setTimeout(function() {
          order("group");
          d3.select("#order").property("selectedIndex", 2).node().focus();
        }, 5000);
      });
    </script>
  <?php endif; ?>
</div>
<div id="chart">
  <select id="order">
    <option value="name">by Name</option>
    <option value="count">by Frequency</option>
    <option value="group">by Cluster</option>
  </select>
</div>