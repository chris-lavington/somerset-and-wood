require(["jquery"], function($){
    $(function () {
		$('.toggle').click(function(e) {
		  	e.preventDefault();
			
			var aTag = $(this);

		    var $this = $(this);
		  	if ($this.next().hasClass('opened')) {
		  		$this.find('i.feather').removeClass('icon-minus').addClass('icon-plus');
		  		$this.next().removeClass('opened');
		  		 $('html, body').animate({
				    scrollTop: ($(this).offset().top)
				},500);
		    } else if ($this.next().hasClass('show')) {
		       	$this.find('i.feather').removeClass('icon-minus').addClass('icon-plus');
		       	$this.next().find('i.feather').removeClass('icon-minus').addClass('icon-plus');
		        $this.next().removeClass('show');
		        $this.next().slideUp(350);
		        $('html, body').animate({
				    scrollTop: ($(this).offset().top)
				},500);
		    } else {
		       	$this.parent().siblings().find('.toggle-top').children('i.feather').removeClass('icon-minus').addClass('icon-plus');
		        $this.find('i.feather').removeClass('icon-plus').addClass('icon-minus');
		        $this.parent().parent().find('li .inner').removeClass('show');
		        $this.parent().parent().find('li .inner').slideUp(350);
		        $this.next().toggleClass('show');
		        $this.next().slideToggle(350);
		        $('html, body').animate({
				    scrollTop: ($(this).offset().top)
				},500);
		    }
		});
	 });
});

