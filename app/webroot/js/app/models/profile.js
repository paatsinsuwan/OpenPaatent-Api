var Profile = Spine.Model.sub();
Profile.extend(Spine.Model.Ajax);

Profile.extend({
	url : '/profiles'
});

Profile.belongsTo('user', 'User');