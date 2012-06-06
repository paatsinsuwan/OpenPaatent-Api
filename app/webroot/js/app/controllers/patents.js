var PatentsList = Spine.Controller.sub({
	elements : {".items" : "items"},
	init : function(){
		Patent.bind("create", this.proxy(this.addOne));
		Patent.bind("refresh", this.proxy(this.addAll));
		Patent.fetch();
	},
	addOne : function(patent){
		var view = new PatentsView({
			item : patent
		});
		console.log(view);
		this.items.append(view.render());
	},
	addAll : function(){
		Patent.each(this.proxy(this.addOne));
	}
	
});

var PatentsView = Spine.Controller.sub({
	init : function(){
		this.item.bind("update", this.proxy(this.render));
	},
	render : function(item){
		if (item) this.item = item;
		var template = $("#patents-index-template").tmpl( this.item );
		//console.log("this.item => " + this.item, template);
		return this.html( template );
		//this.replace($('#patents-index-template')).tmpl(items);
		//console.log(this, this.html(template));
		//return this;
	}
});