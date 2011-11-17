jQuery(function(){
	alert("hej");
	jQuery("#leftArrow").mouseover(function(){
		jQuery("#sliderImg").animate(
			left: '+=50',
			10000,
			function(){}
		);
		alert("mouseover");
	});
	jQuery("#rightArrow").mouseover(function(){
		jQuery("#sliderImg").animate(
			left: '+=50', 
			10000, 
			function(){}
		);
		alert("mouseover");
	});
	jQuery("#leftArrow").mouseout(function(){
		jQuery("#sliderImg").stop();
		alert("mouseout");
	});
	jQuery("#rightArrow").mouseout(function(){
		jQuery("#sliderImg").stop();
		alert("mouseout");
	});
	
	jQuery("#leftArrow").keypress(function(){
		jQuery("#sliderImg").animate(function(){
			left: '+=50px'}, 
		8000, 
		function(){});
	});
	jQuery("#rightArrow").keypress(function(){
		jQuery("#sliderImg").animate(function(){
			left: '+=50px'}, 
		8000, 
		function(){});
	});
});
