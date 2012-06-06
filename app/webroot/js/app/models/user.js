var User = Spine.Model.sub();
User.extend(Spine.Model.Ajax);

User.extend({
	url: "/api/users"
});