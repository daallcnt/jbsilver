/**
 *	본플러그인은 저작권자의 허락을 듣하여 블루비에서 판매하고 있으며 저작권표시를 삭제하거나 무단 배포 또는 카피하였을경우 저작권법에 따라 처벌 받으실수 있습니다.
 * @name jquery.Slideshow.js
 * @description Slideshow
 * @author 조만묵 (muki2009@nate.com)
 */

(function($) {
	
	var number_skitter = 0,
	skitters = [];
	
	$.fn.slideshow = function(options) {
		return this.each(function() {
			$(this).data('skitter_number', number_skitter);
			skitters.push(new $ss(this, options, number_skitter));
			++number_skitter;
		});
	};

	var defaults = {
		opacity		: 				0.8,
		pauseTime	: 				4500, 
		animation	: 				1000,
		timer		: 				0,
		imgheight	:				0,
		imgwidth	:				$(window).width(),
		heightcut	:				20,
		widthcut	:				20,
		boxwidth	:				200,
		boxheight	:				300,
		bultwidth	:				400,
		bultheight	:				30,
		boxtop		:				50,
		lineheight	:				20,
		boxright	:				10,
		bultTop		:				300,
		bultLeft	:				150,
		controllerboxWidth :		30,
		controllbar :			true,
		prevSlide	:				0,
		nextSlide	:				0,
		currentSlide:				0,
		oldprev		:				0,
		oldnext		:				0,
		oldSlide	:				0,
		totalSlides	:				0,
		maskcolor	:				"#FFFFFF",
		tmpboxwidth	:				42,
		tmpboxheight:				42,
		animation_type	:				"",
		viewbult	:			true,
		viewlist :				false,
		cntview	:				false,
		runing	:				false,
		bultruning	:			true
	};
	
	$.slideshow = function(obj, options, number) {
		this.slide = $(obj);
		this.timer = null;
		this.settings = $.extend({}, defaults,  options || {});
		this.setup();
	};
	
	// Shortcut
	var $ss = $.slideshow;
	
	$ss.fn = $ss.prototype = {};
	
	$ss.fn.extend = $.extend;
	
	$ss.fn.extend({
		
		setup: function() 
		{
			var self = this;
			//윤곽만들기
			this.skin();
			
			this.load();
			this.Resize();
			this.Event();
			//첫셋팅
			
			this.settings.currentSlide = Math.floor(Math.random() * this.settings.totalSlides);
			this.settings.prevSlide = this.settings.currentSlide -1;
			this.settings.nextSlide = this.settings.currentSlide + 1;
			if(this.settings.prevSlide < 0) {	this.settings.prevSlide = (this.settings.totalSlides -1);	 }
			if(this.settings.nextSlide >= this.settings.totalSlides) {	this.settings.nextSlide = 0;	 }
			self.settings.oldSlide = self.settings.currentSlide;
			$(".atitle[rel='" + this.settings.currentSlide + "']", self.slide).addClass("active");
			
			$(".box_skitter_main>.box", self.slide).eq(this.settings.currentSlide).css({'z-index': 10, 'left' : 0, 'opacity': 1});
			
			if(this.settings.cntview == true){
				$(".cntview", self.slide).html((this.settings.currentSlide + 1) + "/" + this.settings.totalSlides).animate({'top' : ( - (this.settings.tmpboxheight + 10) ) + 'px'}, {duration : 1000, easing : "easeOutElastic"});
			}
			if(this.settings.viewlist == true){
				$(".viewlist", self.slide).animate({'left' : ($(window).width() - (this.settings.boxwidth + this.settings.boxright)) + 'px'}, 1000);
			}
			if(this.settings.viewbult == true){
				$(".box_skitter_bultbox", self.slide).animate({'top' : this.settings.bultTop + 'px'}, 1000);
				$(".bult[rel='" + this.settings.currentSlide + "']", self.slide).addClass("active");
			}
			if(this.settings.bultruning == true && this.settings.timer == ""){
				this.settings.timer = setInterval(function(){ self.ani(true); }, this.settings.pauseTime);
			}		
		},
		skin: function(){
			var self = this;
				self.slide.append("<div class='box_skitter_body'></div>");
				$(".box_skitter_body", self.slide).append("<div class='box_skitter_main box_skitter'></div>");
				
			
				$(".box_skitter_main", self.slide).append("<div class='box_skitter_controllbultbox'></div>");		
				if(self.settings.controllbar == true){
					$(".box_skitter_main", self.slide).append("<div class='controller_left controller'></div>");
					$(".box_skitter_main", self.slide).append("<div class='controller_right controller'></div>");
			    }
				$(".box_skitter_controllbultbox", self.slide).append("<div class='box_skitter_bultbox'></div>");	
				if(this.settings.cntview == true){
					$(".box_skitter_controllbultbox", self.slide).append("<div class='cntview'></div>");
				}
				if(this.settings.viewlist == true){
					$(".box_skitter_main", self.slide).append("<div class='viewlist'><ul></ul></div>");
				}

				$(".controller", self.slide).css({'cursor':'pointer'});
			
				
		},
		Resize : function(){
				var self = this;
				$(".box_skitter_body", self.slide).css({'width' : $(window).width() + 'px', 'height': this.settings.imgheight + 'px'});
				self.slide.css({'width' : $(window).width() + 'px', 'height': this.settings.imgheight + 'px'});
				$(".box_skitter", self.slide).css({'position':'relative','float' :'left','overflow':'hidden','width': $(window).width() + 'px','height': this.settings.imgheight + 'px'});
			   if(self.settings.controllbar == true){
				$(".controller_left", self.slide).css({'float' :'left','position':'absolute','left' : ($(window).width() - this.settings.controllerboxWidth) + 'px', 'top' : ((this.settings.imgheight - 50) /2) + 'px', 'z-index': 2000000});
				$(".controller_right", self.slide).css({'float' :'left','position':'absolute', 'top' : ((this.settings.imgheight - 50) /2) + 'px', 'z-index': 2000});
			   }
				$(".box_skitter_controllbultbox").css({'float' :'left','position':'absolute','width': this.settings.imgwidth  + 'px','left' :  (($(window).width()-this.settings.imgwidth)/2) + 'px','height': this.settings.imgheight  + 'px','border':'1px #000000 solid', 'top' : this.settings.imgheight + 'px', 'z-index': 1000});
				$(".box", self.slide).css({'width': $(window).width() + 'px','height': this.settings.imgheight + 'px'});
			    $(".box>a", self.slide).css({'left' :  (($(window).width()-this.settings.imgwidth)/2) + 'px'});

			if(this.settings.viewlist == true){
				$(".viewlist", self.slide).css({'left' : ($(window).width() - (this.settings.boxwidth + this.settings.boxright)) + 'px'}, 1000);
			}

		},
		load: function(options){
			var self = this;
			$(".box_skitter_data", self.slide).find("li").each(function() {
				$(".box_skitter", self.slide).append('<div class="box" rel="' + self.settings.totalSlides + '" style="background-image:url(' + $(this).find("a").attr("bg") + ');background-position:center top;">' + $(this).html() + '</div>');
				if(self.settings.viewlist == true){
					$(".viewlist>ul", self.slide).append('<li  class="atitle" rel="' + self.settings.totalSlides +'"><a href="#">' + $(this).find("a").attr("title") + '</a></li>');
         		}
				$(".box_skitter_bultbox", self.slide).append('<div class="bult" rel="' + self.settings.totalSlides + '"></div>');
				self.settings.totalSlides ++;
			});  
			//블릿컨트롤삽입
			$(".box_skitter_bultbox", self.slide).append('<div class="bultcontroller" rel="' + self.settings.totalSlides + '" mode="Y"></div>');
			$(".box>a", self.slide).children().css({'opacity':0})
			$(".box", self.slide).css({'float' :'left','position':'absolute','width': $(window).width() + 'px','height': this.settings.imgheight + 'px', 'left' :  ($(window).width()) + 'px', 'top' : '0px', 'z-index': 0, 'margin-left': '0px', 'opacity': 0 });
			$(".box>a", self.slide).css({'float' :'left','position':'relative','width': this.settings.imgwidth + 'px','height': this.settings.imgheight + 'px', 'left' :  (($(window).width()-this.settings.imgwidth)/2) + 'px', 'top' : '0px', 'z-index': 0, 'margin-left': '0px' });
			
			
			if(this.settings.viewbult == true){
					$(".box_skitter_bultbox", self.slide).css({'float' :'left','position':'absolute','overflow':'hidden','z-index':40000, 'width' : 'auto', 'height':  this.settings.bultheight + 'px', 'top' : (this.settings.imgheight) + 'px', 'left': (this.settings.bultLeft) + 'px','color':'#FFFFFF' });
			}
			if(this.settings.cntview == true){
					$(".cntview", self.slide).css({'float' :'left','position':'absolute','z-index':30000, 'width' : this.settings.tmpboxwidth+ 'px', 'height':  this.settings.tmpboxheight + 'px','line-height':  this.settings.tmpboxheight + 'px', 'text-align':'center', 'top' : 0 + 'px', 'left': (this.settings.imgwidth - (this.settings.tmpboxwidth + 10) ) + 'px' });
            }

			$(".viewlist", self.slide).css({'float' :'left','position':'absolute','z-index':30000, 'width' : this.settings.boxwidth+ 'px', 'height':  this.settings.boxheight + 'px','opacity': this.settings.opacity,'line-height':  this.settings.lineheight + 'px', 'text-align':'left', 'top' : this.settings.boxtop + 'px', 'left': $(window).width() + 'px','color':'#000000' });

		},
		Event : function(){
			var self = this;
			self.slide.hover(function(){
             
                clearInterval(self.settings.timer);
                self.settings.timer = '';              
				
            }, function(){
               
				if(self.settings.timer == '' && self.settings.bultruning == true ){
					self.settings.timer = setInterval(function(){  self.ani(true);	}, self.settings.pauseTime);
				}

            });
			$(window).resize(function(){
				self.Resize();
			});
			if(this.settings.viewbult == true){
				$(".bult", self.slide).click(function(){			
					if(self.settings.runing == false){
						self.settings.currentSlide = Number($(this).attr("rel")) - 1;
						self.ani(false);
					}
				});
			}
			if(this.settings.viewlist == true){
				$(".atitle", self.slide).click(function(){			
					if(self.settings.runing == false){
						self.settings.currentSlide = Number($(this).attr("rel")) - 1;
						self.ani(false);
					}
				});
			}
			$(".bultcontroller").on("click", function(){
				if($(this).attr("mode") == "Y"){ //작동하고 있는 경우
					$(this).addClass("active").attr("mode", "N");
					clearInterval(self.settings.timer);
					self.settings.timer = '';  
					self.settings.bultruning = false ;
				}else{
					$(this).removeClass("active").attr("mode", "Y");
					self.settings.bultruning = true; 
					if(self.settings.timer == '' && self.settings.bultruning == true ){
						self.settings.timer = setInterval(function(){  self.ani(true);	}, self.settings.pauseTime);
				    }
				}
			});
			$(".controller_left", self.slide).click(function(){			
				self.ani(true);
			});
			$(".controller_right", self.slide).click(function(){	
				if(self.settings.runing == false){
					self.settings.currentSlide = self.settings.currentSlide - 2;
					self.ani(false);
				}
			});
		},
		aniType : function(){
			animations_functions = [
				'MatchFade'					
			];
			return animations_functions[Math.floor(Math.random() * animations_functions.length)];
		},
		aniEffect : function(flg){
			var self = this;
			var animation_type = "";
			if(this.settings.animation_type == "randam"){
				animation_type = self.aniType();
			}else{
				animation_type = this.settings.animation_type;
			}
			
			switch (animation_type) 
			{
				
				default : 
					this.animationLeftSlide(flg);
				break;
			}	
			
			
		},
		animationLeftSlide : function(flg){
			var self = this;
			if(flg == true){			
				$(".box_skitter_main>.box", self.slide).eq(self.settings.oldSlide).css({'z-index': 1, 'opacity' : 1}).animate({'opacity' : 1,'left' : "-" + $(window).width() + 'px'}, self.settings.animation, '', function(){ $(this).css({'z-index': 1, 'opacity' : 0, 'left' : $(window).width() + 'px'}); });
				$(".box_skitter_main>.box", self.slide).eq(self.settings.currentSlide).css({'z-index':100}).animate({'opacity' : 1,'left' : 0 + 'px'}, self.settings.animation, '', function(){ $(this).css({'z-index': 100, 'opacity' : 1, 'left' : 0 + 'px'});self.settings.runing = false; });
			}else{
				$(".box_skitter_main>.box", self.slide).eq(self.settings.oldSlide).css({'z-index': 1, 'opacity' : 1}).animate({'opacity' : 1,'left' : $(window).width() + 'px'}, self.settings.animation, '', function(){ $(this).css({'z-index': 1, 'opacity' : 0, 'left' : $(window).width() + 'px'}); });
				$(".box_skitter_main>.box", self.slide).eq(self.settings.currentSlide).css({'z-index':100, 'left' : "-" + $(window).width() + 'px'}).animate({'opacity' : 1,'left' : 0 + 'px'}, self.settings.animation, '', function(){ $(this).css({'z-index': 100, 'opacity' : 1, 'left' : 0 + 'px'});self.settings.runing = false; });
			}
		},	
		
		finishAnimation: function (options) 
		{
			var self = this;
			
			this.settings.runing = false;
			$(".cuttmp", self.slide).remove();		
			
		},
		ani : function(flg){
			var self = this;
			if(self.settings.runing == false){
				self.settings.runing = true;
				
				self.settings.oldprev = self.settings.prevSlide;
				self.settings.oldnext = self.settings.nextSlide;				
				self.settings.currentSlide ++;
				
				if(self.settings.currentSlide < 0) {	self.settings.currentSlide = (self.settings.totalSlides -1);	 }
				if(self.settings.currentSlide >= self.settings.totalSlides) {	self.settings.currentSlide = 0;	 }
				self.settings.prevSlide = self.settings.currentSlide -1;
				self.settings.nextSlide = self.settings.currentSlide + 1;
				
				if(self.settings.prevSlide < 0) {	self.settings.prevSlide = (self.settings.totalSlides -1);	 }
				if(self.settings.nextSlide >= self.settings.totalSlides) {	self.settings.nextSlide = 0;	 }
				if(this.settings.viewlist == true){
					$(".atitle[rel='" + this.settings.oldSlide + "']", self.slide).removeClass("active");
					$(".atitle[rel='" + this.settings.currentSlide + "']", self.slide).addClass("active");
				}
				if(this.settings.viewbult == true){
					$(".bult[rel='" + this.settings.oldSlide + "']", self.slide).removeClass("active");
					$(".bult[rel='" + this.settings.currentSlide + "']", self.slide).addClass("active");
				}

				

				self.aniEffect(flg);
				if(this.settings.cntview == true){
					$(".cntview", self.slide).html((this.settings.currentSlide + 1) + "/" + this.settings.totalSlides);
				}			
				
				self.settings.oldSlide = self.settings.currentSlide;
				
			}			
		}
	});

})(jQuery);






