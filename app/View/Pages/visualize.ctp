<script type="text/x-jQuery-tmpl" id="patents-index-template">
	<p>${title}</p>
</script>
<?php
	echo $this->Html->script(array(
		'jquery-1.7.2.min',
		'jquery.tmpl',
		'/js/spine/lib/spine.js',
		'/js/spine/lib/ajax.js',
		'/js/spine/lib/manager.js',
		'/js/spine/lib/route.js',
		'/js/spine/lib/relation.js',
		'/js/spine/lib/tmpl.js',
		'/js/app/models/user.js',
		'/js/app/models/patent.js',
		'/js/app/controllers/users.js',
		'/js/app/controllers/patents.js',
		'/js/app/application.js',
	));
?>
<div id="documents-container">
	<div class="items">
	</div>
</div>