var Patent = Spine.Model.sub();
Patent.configure("Patent", "id", "title", "filename", "location", "tags");
Patent.extend(Spine.Model.Ajax);

Patent.extend({
	url : "/documents"
});