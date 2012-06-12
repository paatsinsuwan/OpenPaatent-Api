var Degree = Spine.Model.sub();
Degree.extend(Spine.Model.Ajax);

Degree.extend({
	url : '/degrees'
});

Degree.belongsTo('profile', 'Profile');