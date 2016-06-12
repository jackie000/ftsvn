jQuery.fn.roll = function(settings){
	settings = jQuery.extend({
		start:0,
		scroll:1
	}, settings);

	var css = function(el,prop) {
		return parseInt($.css(el[0], prop)) || 0;
	};

	var width = function(el) {
		return el[0].offsetWidth + css(el, 'marginLeft') + css(el, 'marginRight');
	};

	var height = function(el) {
		return el[0].offsetHeight + css(el, 'marginTop') + css(el, 'marginBottom');
	};

	return this.each(function(){
		var box = $(this);

		var list = $(".list", box).children();
		var count= list.size();

		var cIndex = settings.start;

		list.css({width: list.width(), height: list.height()});
		var hh = list.height();

		$(".pre_button", box).click(function(){
			if(cIndex == 0){
				cIndex = parseInt(count-1);
			}else{
				cIndex = cIndex - settings.scroll;
			}
			var element = list[cIndex];
			$(".list", box).prepend(element);
		});

		$(".next_button", box).click(function(){
			var element = list[cIndex];
			if(cIndex == (count-1)){
				cIndex = 0
			}else{
				cIndex = cIndex+settings.scroll;
			}
			$(".list", box).append(element);
		});
	});

	

};
