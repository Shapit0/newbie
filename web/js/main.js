$(document).ready(function(){
	var extendSearch = $("#showButton");
	var specialityForm = $(".column-form-speciality");
	var devSpec =   "<p>Специализация</p>"+
                    "<input type='checkbox' id='speciality1'>"+
                    "<label for='speciality1'><span></span>php</label> <br/>"+
                    "<input type='checkbox' id='speciality2'>"+
                    "<label  for='speciality2'><span></span>jQuery</label>"+
                   "<input type='checkbox' id='speciality3'>"+
                    "<label  for='speciality3'><span></span>верстка</label>'";
	$(".option-one").on("click", function(e){
		extendSearch.css("margin-left", "0").hide(0).css("width", 300).slideDown(200);
		specialityForm.html(devSpec);
	});
	$(".option-two").on("click", function(e){
		specialityForm
		.html("<p>Специализация</p>"+
			"<input type='checkbox' id='speciality1'><label for='speciality1'><span></span>веб-дазайн</label> <br/>"+
			"<input type='checkbox' id='speciality2'><label  for='speciality2'><span></span>дизайн интерфейсов</label>");

		extendSearch.css("margin-left", "300px").hide(0).css("width", 340).slideDown(200);
	});
	$(".option-three").on("click", function(e){
		extendSearch.css("margin-left", "640px").hide(0).css("width", 300).slideDown(200);
		specialityForm.html(devSpec);
	});
	$("#metro_select").click(function(e){
		$("#freelance_container").html("");
	});
	extendSearch.leanModal({overlay : 0.9, top: 200});
	$(".clip").click(function(e){
		e.preventDefault();
		$("#uploadBtn").trigger("click");
	});

	$("input:file").change(function (){
		var filename = $("#uploadBtn").val();
		$("#uploadFile").val(filename);
	});
    var slider = $('#footerGallery').jcarousel({
        wrap: 'circular'
    }).jcarouselAutoscroll({
            interval: 2000,
            target: '+=1',
            autostart: true
    });
    $('.jcarousel-prev.footerArrow').jcarouselControl({
        target: '-=1'
    });
    $('.jcarousel-next.footerArrow').jcarouselControl({
        target: '+=1'
    });
    $(".wrapper.slider-wrapper").hover(function(){
        slider.jcarouselAutoscroll('stop');
    }, function(){
        slider.jcarouselAutoscroll('start');
    });

    var slider2 = $('#aboutGallery').jcarousel({
        wrap: 'circular'
    }).jcarouselAutoscroll({
            interval: 3000,
            target: '+=1',
            autostart: true
    });
    $('.aboutControl.jcarousel-prev').jcarouselControl({
        target: '-=1'
    });
    $('.aboutControl.jcarousel-next').jcarouselControl({
        target: '+=1'
    });
    $('.jcarousel-pagination').jcarouselPagination({
        item: function(page) {
            return '<a href="#' + page + '">' + '</a>';
        },
        'perPage': 3
    }).on('jcarouselpagination:active', 'a', function() {
        $(this).addClass('active');
    }).on('jcarouselpagination:inactive', 'a', function() {
        $(this).removeClass('active');
    });
    $('.aboutCompanyPagination').find('a:first-child').addClass('active');
    $(".jcarousel-pagination").find("a").click(function(e){
        e.preventDefault();
    });
    $(".aboutCompanyWrapper").hover(function(){
        slider2.jcarouselAutoscroll('stop');
    }, function(){
        slider2.jcarouselAutoscroll('start');
    });
    $(".upload").click(function(e){
    	$(this).slideUp(600).closest(".column").find(".send_form").slideDown(600);
    });

});
