set_sizes();
$(window).bind("scroll",function(){
 var ScrollTop = $(window).scrollTop();
 doScrollEffects(ScrollTop);
 
 if($("#load_more_page").length > 0) {
		var ob = $("#load_more_page").parent('div');
		if(isScrolledIntoView(ob)) {
			load_more_page();
			loaded_on_scroll = true;
		}
	}
});
$(document).ready(function(){
	var ScrollTop = $(window).scrollTop();
	doScrollEffects(ScrollTop);
	
	render_resize_prop();

	$('ul#menu').slicknav({
		prependTo:'#MenuResponsive',
		label:menu_label
	});

	if($('.filter-download-center').length > 0) {
		$('.filter-download-center').click(function(){
			if($('.filter-download-center-container').is(":visible")) {
				$('.filter-download-center-container').slideUp("fast");
			}
			else {
				$('.filter-download-center-container').slideDown("fast");
			}
		});
	}
	slick_sliders();
	if($('.quick-search-title').length > 0) {
		$('.quick-search-title').click(function(){
			if($(window).width() <= 1024) {
			if($('.quick-search-form').is(":visible")) {
				$('.quick-search-form').slideUp("fast");
			}
			else {
				$('.quick-search-form').slideDown("fast");
				$('html, body').animate({scrollTop : $('.quick-search-title').offset().top},800);
			}
			}
		});
	}
	
	
	$('.search_submit').click(function(event){
		//var Frm = $("#searchform");
		//event.preventDefault();
		$('input[name="'+nct_name+'"]').remove();
		//Frm.submit();
	});
	$('.responsive-search-icon .search_submit').click(function(){
		var obj = $(this);
		var sB = $("#responsive-search");
		if(sB.is(":visible")) {
			obj.html('<i class="fa fa-search" aria-hidden="true"></i>');
			sB.slideUp("fast",function(){$('input[name="keywords"]').val("");});
		}
		else {
			obj.html('<i class="fa fa-times" aria-hidden="true"></i>');
			sB.slideDown("fast");
		}
	});
	if($("#newsletterForm").length > 0) {
	$("#newsletterForm").find('input[type="submit"]').click(function(event){
		var Frm = $("#newsletterForm");
		var Frm_btn = $(this);
		var Frm_btn_text = Frm_btn.val();
		event.preventDefault();
		$('.newsletter-errors').html("");
		$('input[name="newslettername"]').removeClass("vError");
		$('input[name="newsletteremail"]').removeClass("vError");
		Frm_btn.val(please_wait_lang);
		$.ajax({
			url : Frm.attr("action"),
			type : 'POST',
			data : Frm.serialize(),
			success : function(response){
				if(response.result == 1) {
					$('.newsletter-success').html(response.message).slideDown("fast");
				}
				else {
					if(response.errors.newslettername)
					$('input[name="newslettername"]').addClass("vError");
					if(response.errors.newsletteremail)
					$('input[name="newsletteremail"]').addClass("vError");
					$('.newsletter-errors').html(response.message);
				}
				Frm_btn.val(Frm_btn_text);
			}	
		});
	});
	$('input[name="newslettername"]').focus(function(){
		if($('input[name="newslettername"]').hasClass('vError')) $('input[name="newslettername"]').removeClass('vError');
	});
	$('input[name="newsletteremail"]').focus(function(){
		if($('input[name="newsletteremail"]').hasClass('vError')) $('input[name="newsletteremail"]').removeClass('vError');
	});
}
if($("#supportForm").length > 0) {
	$("#supportForm").find('input[type="submit"]').click(function(event) {
		var Frm = $("#supportForm");
	Frm.find('input[name="offset"]').val(0);
		submit_support(event,0);
	});
}
if($("#product_search_left").length > 0) {
	$("#product_search_left").find('input[type="submit"]').click(function(event){
		var Frm = $("#product_search_left");
		var Frm_btn = $(this);
		event.preventDefault();
		Frm_btn.val(please_wait_lang);
		//var d = Frm.find('select[name="division"]').val();
		//var c = Frm.find('select[name="category"]').val();
		//var l = Frm.find('select[name="line"]').val();
		$.ajax({
			url : siteurl+'products/generate_url',
			type : 'POST',
			//data : {d : d, c : c, l : l},
			data : Frm.serialize(),
			success : function(response){
				var newurl = response.newurl
				Frm.attr("action",newurl);
				//Frm.find('select[name="division"]').attr("disabled","disabled");
				Frm.find('select, input[type="radio"]').attr("disabled","disabled");
				//Frm.find('input').attr("readonly","readonly");
				//Frm.find('select[name="line"]').attr("disabled","disabled");
				$('input[name="'+nct_name+'"]').val(response.nct);
				$('input[name="'+nct_name+'"]').remove();
				Frm.submit();
			}	
		});
	});
}
checkAllDocumentTypes();
if($("#serviceCentersForm").length > 0) {
	$("#serviceCentersForm").find('input[type="submit"]').click(function(event){
		var Frm = $("#serviceCentersForm");
		var formData = new FormData(Frm[0]);
		var Frm_btn = $(this);
		event.preventDefault();
		var Frm_btn_txt = Frm_btn.val();
		Frm_btn.val(please_wait_lang);
		//alert(Frm.serialize());
		$('select, input, button').attr('disabled','disabled');
		$.ajax({
			url : Frm.attr('action'),
			type : 'POST',
			data : formData,
			success : function(response){
				$('input[name="'+nct_name+'"]').val(response.nct);
				$('#service_center_loader').html(response.html);
				$('select, input, button').removeAttr('disabled');
				Frm_btn.val(Frm_btn_txt);
				if(response.display_map == 1 && $('#MapCanvas').length > 0) {
					var locationsObj = response.locationsdata;
					//alert(locationsObj);
					var objLength = locationsObj.length;
					var locations = [];
					var firstLongitude = 0;
					var firstLatitude = 0;
					for(var i = 0;i < objLength;i++) {
						var locationSubObj = locationsObj[i];
						var createSubObj = [];
						createSubObj.push(locationSubObj.div);
						createSubObj.push(locationSubObj.longitude);
						createSubObj.push(locationSubObj.latitude);
						createSubObj.push(locationSubObj.zoom);
						locations.push(createSubObj);
						if(i == 0) {
							firstLongitude = locationSubObj.longitude;
							firstLatitude = locationSubObj.latitude;
						}
					}
					initialize(locations, firstLongitude, firstLatitude);
				}				
				$('html, body').animate({scrollTop : $('#service_center_loader').offset().top - 10},800);
			},
			contentType: false,
       		processData: false	
		});
	});
}

if($('#feedbackForm').length > 0) {
	 $('#feedbackForm').customformvalidate({
		  loader : please_wait_lang
	  });
}

if($('#careersForm').length > 0) {
	 $('#careersForm').customformvalidate({
		  loader : please_wait_lang
	  });
}

if($(".phone-format").length > 0) {
	 $(".phone-format").intlTelInput({
      allowExtensions: false,
       autoFormat: true,
       //autoHideDialCode: true,
        autoPlaceholder: false,
       defaultCountry: "ae",
        // geoIpLookup: function(callback) {
        //   $.get('http://ipinfo.io', function() {}, "jsonp").always(function(resp) {
        //     var countryCode = (resp && resp.country) ? resp.country : "";
        //     callback(countryCode);
        //   });
        // },
        //nationalMode: false,
		nationalMode: false,
        //numberType: "MOBILE",
        //onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
        preferredCountries: ['ae'],
        utilsScript: assetsurl+"js/intl-tel-input-master/build/js/utils.js"
      });
}
	
	
if($("#map").length > 0) {
	var dlLat = $("#map").data('lat');
	var dlLng = $("#map").data('lng');
	var myLatlng = new google.maps.LatLng(dlLat, dlLng);
var mapOptions1 = {
  zoom: 12,
  center: myLatlng,
  //mapTypeId: 'satellite'
};
var map1 = new google.maps.Map(document.getElementById('map'),
    mapOptions1);
	
	 var marker = new google.maps.Marker({
        position: new google.maps.LatLng(dlLat, dlLng),
        map: map1
      });
	
	
}

/*if($('.submit_form').length > 0) {
var FormObj = $('.submit_form');
FormObj.click(function(event){
	var id = this.id;
	var FormName = $('form[name="fileinfo_'+id+'"]');	
	event.preventDefault();
	var SubmitButton = FormObj;
	FormName.find( '.FormResult' ).html("Loading...");
	$(this).attr('disabled','disabled');
	 var formAction = FormName.attr('action');
	 var formData = new FormData(FormName[0]);
	 $.ajax({
		url: formAction,
        type: 'POST',
        data: formData, 
		success: function (data) {
		//Parsedata = JSON.parse(data);
		$('.submit_form').attr('disabled',null);
		FormName.find( '.FormResult' ).html(data);
		$("html, body").animate({ scrollTop: $('#tabs').offset().top }, 1000)
		},
		contentType: false,
        processData: false
	 });
});
}*/
if($('#banner_layer').length > 0) {
$('#banner_layer').slick({
	rtl:rtl,
	infinite: true,
    autoplay:true,
	autoplayspeed:5000,
	speed: 800,
	draggable:true,
	slidesToShow: 1,
  	slidesToScroll: 1,
	arrows:false,
	fade:false,
	dots:true
});
}
if($('#products-gallery').length > 0) {
$('#products-gallery').slick({
	rtl:rtl,
	infinite: true,
	speed: 800,
	draggable:true,
	slidesToShow: 1,
  	slidesToScroll: 1,
	arrows:true,
	fade:false,
	dots:false,
	/*variableWidth:true,*/
	nextArrow:'<button type="button" class="slick-nav-right"><span class="slick-next"><i class="fa fa-angle-'+slick_arrow_right+'" aria-hidden="true"></i></span></button>',
	prevArrow:'<button type="button" class="slick-nav-left"><span class="slick-prev"><i class="fa fa-angle-'+slick_arrow_left+'" aria-hidden="true"></i></span></button>'
});
}
/******about us****************************************************************/
if($('.history-slider').length > 0) {	
//if(rtl) {alert('RTL');} else { alert('LTR'); }
	$('.history-slider').slick({
	  rtl:rtl,
	  infinite: false,
	  speed: 800,
	  arrows:true,
	  nextArrow:'<button type="button" class="slick-nav-right"><span class="slick-next"><i class="fa fa-angle-'+slick_arrow_right+'" aria-hidden="true"></i></span></button>',
	  prevArrow:'<button type="button" class="slick-nav-left"><span class="slick-prev"><i class="fa fa-angle-'+slick_arrow_left+'" aria-hidden="true"></i></span></button>',
	   variableWidth: true,
	   //centerMode:true,
	     slidesToScroll: 4,
		 responsive: [
		{
		  breakpoint: 780,
		  settings: {
			slidesToScroll: 2,
		  }
		},
		{
		  breakpoint: 440,
		  settings: {
			slidesToScroll: 1,
		  }
		},
		]
	});
	$('.history-slider').show();	
}
/******news****************************************************************/
if($('.related-news-slider').length > 0) {
	$('.related-news-slider').slick({
	  rtl:rtl,
	  infinite: true,
	  speed: 800,
	  arrows:true,
	  dots:false,
	  slidesToShow: 3,
	  slidesToScroll:3,
	  nextArrow:'<button type="button" class="slick-nav-right"><span class="slick-next"><i class="fa fa-angle-'+slick_arrow_right+'" aria-hidden="true"></i></span></button>',
	  prevArrow:'<button type="button" class="slick-nav-left"><span class="slick-prev"><i class="fa fa-angle-'+slick_arrow_left+'" aria-hidden="true"></i></span></button>',
	  responsive: [
		{
		  breakpoint: 780,
		  settings: {
			slidesToShow: 2,
	  slidesToScroll:2,
		  }
		},
		{
		  breakpoint: 440,
		  settings: {
			slidesToShow: 1,
	  slidesToScroll:1,
		  }
		}
	  ]
	});		
	}
if($('.latest-events-slider').length > 0) {	
	$('.latest-events-slider').slick({
		rtl:rtl,
	  infinite: true,
	  speed: 800,
	  arrows:false,
	  dots:true,
	  slidesToShow: 1,
	  slidesToScroll:1,
	  nextArrow:'<button type="button" class="slick-next"><i class="fa fa-angle-'+slick_arrow_right+'" aria-hidden="true"></i></button>',
	  prevArrow:'<button type="button" class="slick-prev"><i class="fa fa-angle-'+slick_arrow_left+'" aria-hidden="true"></i></button>',
	  responsive: [
	  {
		breakpoint: 780,
		settings: {
		  arrows:true,
		  dots:false,
		}
	  }
	  ]
	});		
	}
	if($('.news-gallerys-slider').length > 0) {	
	$('.news-gallerys-slider').slick({
		rtl:rtl,
	  infinite: true,
	  speed: 800,
	  arrows:true,
	  dots:false,
	  variableWidth: true,
	  nextArrow:'<button type="button" class="slick-next"><i class="fa fa-angle-'+slick_arrow_right+'" aria-hidden="true"></i></button>',
	  prevArrow:'<button type="button" class="slick-prev"><i class="fa fa-angle-'+slick_arrow_left+'" aria-hidden="true"></i></button>',
	   responsive:[
	   {
		  breakpoint: 780,
		  settings: {
			 variableWidth: false,
			slidesToShow: 2,
			slidesToScroll: 2,
		  }
		},
		 {
		  breakpoint:680,
		  settings: {
			  	 variableWidth: false,
			slidesToShow:1,
			slidesToScroll:1,
		  }
		}
	  ]
	});	
	$('.trigger-news-slider-next').click(function(){$('.news-gallerys-slider').slick('slickNext')});	
	}
	if($('.may-also-like-slider').length > 0) {	
	$('.may-also-like-slider').slick({
		rtl:rtl,
	  infinite: true,
	  speed: 800,
	  arrows:true,
	  dots:false,
	  slidesToShow: 5,
	  slidesToScroll:5,
	    nextArrow:'<button type="button" class="slick-nav-right"><span class="slick-next"><i class="fa fa-angle-'+slick_arrow_right+'" aria-hidden="true"></i></span></button>',
	  prevArrow:'<button type="button" class="slick-nav-left"><span class="slick-prev"><i class="fa fa-angle-'+slick_arrow_left+'" aria-hidden="true"></i></span></button>',
	  responsive: [
		{
		  breakpoint: 1100,
		  settings: {
			slidesToShow: 4,
			slidesToScroll: 4,
		  }
		},
		{
		  breakpoint: 960,
		  settings: {
			slidesToShow: 3,
			slidesToScroll: 3,
		  }
		},
		{
		  breakpoint: 768,
		  settings: {
			slidesToShow: 2,
			slidesToScroll: 2,
		  }
		},
		{
		  breakpoint: 568,
		  settings: {
			slidesToShow: 1,
			slidesToScroll: 1,
		  }
		}
	  ]
	});		
	}
	
		if($('#ResponsiveProductMenu').length > 0) {
		$('#ResponsiveProductMenu').responsiveTabs({
			rotate: false,
			startCollapsed: 'accordion',
			collapsible: 'accordion',
			animation: 'slide',
		});
		if($('.r-tabs-accordion-title').length > 0) {
			var FirstObject = $('.r-tabs-accordion-title:eq(0)').find("a").click();
		}
		//$('.r-tabs-state-active').trigger("click");
	}
	if($('.timeline-left-filter ul li').length > 0) {
		$('.timeline-left-filter ul li:eq(0) a').click();
	}
/**************************************************************************/
});

var loaded_on_scroll = false;	
function load_more_page()
{
	if(!loaded_on_scroll) {
	var objBtn = $("#load_more_page");
	var objUrl = objBtn.attr("data-url");
	objBtn.html(loading);
	$.get(objUrl,function(response){
		$(".load_more_page_append").append(response.content);
		loaded_on_scroll = false;
		objBtn.attr("data-url",response.new_url);
		if(response.disable_load_more == 1)
		objBtn.parent('div').remove();
		else {
			objBtn.html(load_more);
		}
	});
	}
}
function isScrolledIntoView(el) {
     if  ($(window).scrollTop() > (el.offset().top - $(window).height()) ){
		return true;
	 }
	 else {
		 return false;
	 }
}
$(window).load(function() {
setTimeout("hideAdIcon()",5000);
$('.submit_with_load').click(function(){
$('.overlay').removeClass('display_none');	
});	
});
$(window).bind("resize",function(){set_sizes();slick_sliders();render_resize_prop();});
function set_sizes()
{
	var WW = $(window).width();
	var WH = $(window).height();
	var nh = get_metrix_val(Default_banner_width,Default_banner_height,WW);
	$("#banner,.banner-img, #banner .centered2").css("height",nh);
}
function slick_sliders()
{
	var WW = $(window).width();
	if(WW > 568)
	disable_slick_sliders();
	else
	launch_slick_sliders();
	
	if($('#home-news-slider').length > 0) {	
		$('#home-news-slider').slick({
			rtl:rtl,
		  infinite: true,
		  speed: 800,
		  arrows:false,
		  dots:true,
		  slidesToShow: 3,
		  slidesToScroll:3,
		 nextArrow:'<button type="button" class="slick-next"><i class="fa fa-angle-'+slick_arrow_right+'" aria-hidden="true"></i></button>',
	  prevArrow:'<button type="button" class="slick-prev"><i class="fa fa-angle-'+slick_arrow_left+'" aria-hidden="true"></i></button>',
		 responsive: [
			  {
				breakpoint: 768,
				settings: {
					  arrows:true,
		  dots:false,
				  slidesToShow: 2,
				  slidesToScroll: 2,
				}
			  },
			  {
				breakpoint: 568,
				settings: {
				  slidesToShow: 1,
				  slidesToScroll: 1,
				}
			  }
			]
		});		
	}
}
function launch_slick_sliders()
{
	
	if($('.timeline-left-filter ul').length > 0) {
		$('.timeline-left-filter ul').slick({
			rtl:rtl,
		  infinite: false,
		  speed: 800,
		  arrows:true,
		  dots:false,
		  slidesToScroll:1,
		  variableWidth: true,
		      nextArrow:'<button type="button" class="slick-nav-right"><span class="slick-next"><i class="fa fa-angle-'+slick_arrow_right+'" aria-hidden="true"></i></span></button>',
	  prevArrow:'<button type="button" class="slick-nav-left"><span class="slick-prev"><i class="fa fa-angle-'+slick_arrow_left+'" aria-hidden="true"></i></span></button>',
		});		
	}
}
function disable_slick_sliders()
{
	//$('#home-news-slider').slick("disable");
	if($('.timeline-left-filter .slick-slide').length > 0)
	$('.timeline-left-filter ul').slick("disable");
}
function clear_form(id, redirect){
		var FormName = $('form[name="fileinfo_'+id+'"]');
		FormName.find( 'input[type="text"],input[type="password"]' ).val('');
		FormName.find( 'select' ).val('');
		FormName.find( 'input[type="radio"]' ).val('');
		if(redirect != ""){
			window.location = redirect;	
		}
}
function goBack() {
    window.history.back();
}
function get_metrix_val(v1,v2,w1)
{
	return (v2 * w1) / v1;
}
function changeDivision(elem)
{
	var obj = $(elem);
	var val = obj.find("input[type='radio']").val();
	if(val == '') {
		$("#categories-filtration").html('');
		//$('select[name="line"]').html('<option value="">- '+select_lang+' -</option>');
	}
	else {
		$('ul.left-menu a').removeClass("active");
		obj.addClass("active");
	//	$('select[name="line"]').html('<option value="">- '+select_lang+' -</option>');
		$('select,input').attr('disabled','disabled');
		$.get(siteurl+'dropdowns/get_categories_by_division/'+val,function(response){
			$("#categories-filtration").html(response);
			$('select,input').removeAttr('disabled');
		});
	}
}
/*function changeCategory(elem)
{
	var obj = $(elem);
	var val = obj.val();
	if(val == '') {
		$('select[name="line"]').html('<option value="">- '+select_lang+' -</option>');
	}
	else {
		$('select,input').attr('disabled','disabled');
		$.get(siteurl+'products/get_products_lines_by_category/'+val,function(response){
			$('select[name="line"]').html(response);
			$('select,input').removeAttr('disabled');
		});
	}
}*/
function changeCategory(elem)
{
	var obj = $(elem);
	var val = obj.val();
	/*if(val == '') {
		//$('select[name="line"]').html('<option value="">- '+select_lang+' -</option>');
	}
	else {*/
	//alert(val);
	if(val != '') {
		$('select,input').attr('disabled','disabled');
		$.get(siteurl+'dropdowns/get_catgories_extra/'+val,function(response){
			//$('select[name="line"]').html(response);
			if(response.result == 1) {
				//console.log(obj.parent('.form-item').nextAll('.form-item'));
				obj.parent('.form-item').nextAll('.form-item').remove();
				//if($('select[name="category['+response.id+']"]').length > 0)
				//$('select[name="category['+response.id+']"]').remove();
				$("#categories-filtration").append(response.html);
			}
			$('select,input').removeAttr('disabled');
		});
	}
	else {
		obj.parent('.form-item').nextAll('.form-item').remove();
	}
	//}
}
function load_timeline(y1,y2)
{
	if(!$('#timeline-year-'+y1+'-'+y2+' a').hasClass('active')) {
		$('.timeline-left-filter ul li a').removeClass('active');
		$('#timeline-year-'+y1+'-'+y2+' a').addClass('active');
		if($('.timeline-left-filter .slick-slider').length > 0) {
			//alert("B");
			setTimeout("gotoSlick("+y1+","+y2+")",500);
		}
		$.get(siteurl+'about/load_timeline/'+y1+'/'+y2,function(response){
			if(response.html)
			$('#timeline-loader').html(response.html);
			$('#years-loader').html($('#timeline-year-'+y1+'-'+y2).data('minyear')+' - '+$('#timeline-year-'+y1+'-'+y2).data('maxyear'))
		});
	}
	return false;
}
function gotoSlick(y1,y2)
{
	//alert($("#timeline-year-"+y1+"-"+y2).index());
	$('.timeline-left-filter ul').slickGoTo($("#timeline-year-"+y1+"-"+y2).index());
}
function load_timeline_next()
{
	var current = $('.timeline-left-filter ul li a.active').parent('li').index();
	var c = $('.timeline-left-filter ul li').length;
	if(current >= c - 1) {
		$('.timeline-left-filter ul li:eq(0) a').click();
	}
	else {
		var n = current + 1;
		$('.timeline-left-filter ul li:eq('+n+') a').click();
	}
}
function load_timeline_prev()
{
	var current = $('.timeline-left-filter ul li a.active').parent('li').index();
	var c = $('.timeline-left-filter ul li').length;
	if(current <= 0) {
		var n1 = c - 1;
		$('.timeline-left-filter ul li:eq('+n1+') a').click();
	}
	else {
		var n = current - 1;
		$('.timeline-left-filter ul li:eq('+n+') a').click();
	}
}

function download_file(id)
{
	//alert($.cookie('sdownhloadcharpie'));
	/*if($.cookie('sdownhloadcharpie') == 1) {
		window.location = siteurl+'support/download/'+id;
	}
	else {*/
		$.get(siteurl+'support/get_confirm_download_form/'+id,function(response){
			if(response) {
				fade_popup();
				$(".popup-loader").append(response);
				if($('#downloadBrochureConfirmationForm').length > 0) {
					$('#downloadBrochureConfirmationForm').customformvalidate({
						loader : please_wait_lang,
						callback:function(obj1){
						  if(obj1) {
							  var ParseObjData = JSON.parse(obj1.data);
							  if(ParseObjData.result == 1) {
								// $.cookie('sdownhloadcharpie',1);
								 hide_popup();
								 //window.location = siteurl+'support/download/'+id;
							  }
						  }
						}
					});
				}
			}
		});
	//}
}
function load_support()
{
	$.get(siteurl+'support/popup',function(response){
		if(response.html) {
			fade_popup();
			$(".popup-loader").append(response.html);
			checkAllDocumentTypes();
		}
	});
}

function GoBackWithRefresh(event) {
    if ('referrer' in document) {
        window.location = document.referrer;
    } else {
        window.history.back();
    }
}
$(document).ready(function(){
//$('.fancybox').fancybox();
$('#menu').slicknav({
  prependTo:'#MenuResponsive'
});
$('.products-menu-hover').hover(function(){
	$('.products-menu-hover').addClass('hover');
	$('.menu-shadow').fadeIn('fast');
	$('.products-drop-down').fadeIn('fast');
},function(){
	setTimeout("hoverOut()",100);
});
$('.products-drop-down').hover(function(){
},function(){
	setTimeout("hoverOut_1()",100);
});
$('.drop-down-tabs a').hover(function(){
	var ind = $(this).index();
	$('.drop-down-tabs a').removeClass('active');
	$(this).addClass("active");
	$('.menus').stop(true,true).fadeOut(0);
	$('.menus:eq('+ind+')').stop(true,true).fadeIn(0);
});
});
function hoverOut()
{
	//if(!$('.products-drop-down').is(':hover'))
	if (!$('.products-drop-down:hover').length > 0) {$('.menu-shadow').fadeOut('fast');$('.products-menu-hover').removeClass('hover'); $('.products-drop-down').fadeOut('fast');}
}
function hoverOut_1()
{
	//if(!$('.products-menu-hover').is(':hover'))
	if(!$('.products-menu-hover:hover').length > 0) { $('.menu-shadow').fadeOut('fast');$('.products-menu-hover').removeClass('hover');$('.products-drop-down').fadeOut('fast');}
}

function change_country(elem,v)
{
	var obj = $(elem);
	var obj_state = $('select[name="state"]');
	var cid = obj.val();
	var url = siteurl+'dropdowns/get_states_by_country/'+cid;
	if(v == 'with_centers')
	url += '?wc=1';
	if(cid != '') {
	
		$.get(url,function(response){
			obj_state.html(response);
		});
	}
	else {
		obj_state.html('');
	}
}

function change_state_get_products(elem)
{
	var obj = $(elem);
	var obj1= $('select[name="product"]');
	var cid = obj.val();
	if(cid != '') {
		$.get(siteurl+'dropdowns/get_products_by_state/'+cid,function(response){
				obj1.html(response);
		});
	}
	else {
		obj1.html('');
	}
}

function checkAllDocumentTypes()
{
	if($("#checkAllDocumentTypes").length > 0) {
		$('#checkAllDocumentTypes').change(function() {
			if ($(this).is(":checked")) {
				$('.checkAllDocumentTypes').prop('checked', true);
			} else {
				$('.checkAllDocumentTypes').prop('checked', false);
			}
			setTimeout("changeCheckBox()",200);
		});
		
		$('.checkAllDocumentTypes').parent('label').click(function(){
			setTimeout("changeCheckBox()",200);
		});
	}
}
var c1 = 0;
var c2 = 0;
function changeCheckBox()
{
	c1 = 0;
	c2 = 0;
	$('.checkAllDocumentTypes').each(function(){
		var obj = $(this);
		var val = obj.val();
		if(obj.is(':checked')) {
			if(val == 7) {
				c1++;
			}
			if(val == 4) {
				c1++;
				c2++;
			}
		}
	});
	//alert(c1+','+c2);
	if(c1 != 0 && c2 != 0) {
		$("#additional-options-container").stop(true,true).slideDown("fast");
		$("#opsystem-container").stop(true,true).slideDown("fast");
		$("#emu-container").stop(true,true).slideDown("fast");
	}
	else if(c1 == 0 && c2 == 0) {
		$("#additional-options-container").stop(true,true).slideUp("fast");
		$("#opsystem-container").stop(true,true).slideUp("fast");
		$("#emu-container").stop(true,true).slideUp("fast");
		$("select[name='opsystem']").val("").change;
		$("select[name='emulation']").val("").change;
	}
	else if(c1 != 0 || c2 != 0) {
		$("#additional-options-container").stop(true,true).slideDown("fast");
		if(c1 == 0) {
			$("#opsystem-container").stop(true,true).slideDown("fast");
			$("#emu-container").stop(true,true).slideDown("fast");
		}
		else {
			$("#opsystem-container").stop(true,true).slideDown("fast");
			$("#emu-container").stop(true,true).slideUp("fast");
			$("select[name='emulation']").val("").change;
		}
	}
	
	
}
function change_careers_division(elem)
{
	var obj = $(elem);
	var obj_dep = $('select[name="department"]');
	var did = obj.val();
	var url = siteurl+'dropdowns/get_departments_by_division/'+did;
	if(did != '') {
		$.get(url,function(response){
			obj_dep.html(response);
		});
	}
	else {
		obj_dep.html('');
	}
}

function hideAdIcon()
{
	if(!$('.ad-icon').hasClass('hide'))
	$('.ad-icon').addClass('hide');
}
function doScrollEffects(ScrollTop)
{
 var WindowHeight = $(window).height();
 
 /*if($('.ad-icon').length > 0) {
	if(ScrollTop > 10) {
		if(!$('.ad-icon').hasClass('hide'))
		$('.ad-icon').addClass('hide');
	}
	else {
		if($('.ad-icon').hasClass('hide'))
		$('.ad-icon').removeClass('hide');
	}
 }*/
 
 $('.trans').each(function(){
  var Obj = $(this);
  var ObjTopPos = Obj.offset().top - (WindowHeight);
  //$('#texttt').val(Obj.offset().top);
  if(ScrollTop >= ObjTopPos) {
   if(!Obj.hasClass('ActiveItem')) {
    Obj.addClass('ActiveItem');
   }
  }
  else if(ScrollTop < ObjTopPos - 50) {
   if(Obj.hasClass('ActiveItem')) {
    Obj.removeClass('ActiveItem');
   }
  }
  else {
   // do nothing
   //return false;
  }
 });
}

function get_support_items(elem,id)
{
	var obj = $(elem);
	var Frm = $("#supportForm");
	Frm.find("input[name='category_filter']").val(id);
	Frm.find("input[name='offset']").val(0);
	if(!obj.hasClass("active")) {
		$('.support-left a').removeClass("active");
		obj.addClass("active");
		submit_support(0,1);
	}
}

function load_support_page(elem,i,c)
{
	var obj = $(elem);
	if(!obj.hasClass("active")) {
		i = i - 1;
		var l = i;
		var Frm = $("#supportForm");
		Frm.find('input[name="offset"]').val(l);
		$('.support-pagination a').removeClass("active");
		obj.addClass("active");
		submit_support(0,1);
	}
}
function nav_support(l)
{
	var Frm = $("#supportForm");
	Frm.find('input[name="offset"]').val(l);
	submit_support(0,1);
}
function submit_support(event,f)
{
	var Frm = $("#supportForm");
	var Frm_btn = Frm.find('input[type="submit"]');
	if(event != 0)
	event.preventDefault();
	var Frm_btn_text = Frm_btn.val();
	Frm_btn.val(please_wait_lang);
	$('.support-navigation a').addClass("disabled");
	$.ajax({
		url : siteurl+'support/get_results',
		type : 'POST',
		data : Frm.serialize(),
		success : function(response){
			$('input[name="'+nct_name+'"]').val(response.nct);
			if(response.result == 1) {
				if(f == 0)
				$("#support-loader .support-left").html(response.html);
				$("#support-loader .support-right").html(response.items);
				$('html, body').animate({scrollTop : $("#support-loader .support-right").offset().top},800);
			}
			Frm_btn.val(Frm_btn_text);
			$('.support-navigation a').removeClass("disabled");
		}	
	});
}

function initialize(locations, firstLongitude, firstLatitude) {
    var map = new google.maps.Map(document.getElementById('MapCanvas'), {
      zoom: 12,
      center: new google.maps.LatLng(firstLongitude, firstLatitude),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });
    var infowindow = new google.maps.InfoWindow();
    var marker, i;
	var LatLngList = new Array ();
    for (i = 0; i < locations.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map
      });
      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
	  if(locations.length > 1) {
	  	LatLngList.push(new google.maps.LatLng (locations[i][1], locations[i][2]));
	  }
    }
	if(locations.length > 1) {
	  //  Make an array of the LatLng's of the markers you want to show
	  //var LatLngList = new Array (new google.maps.LatLng (52.537,-2.061), new google.maps.LatLng (52.564,-2.017));
	  //  Create a new viewpoint bound
	  var bounds = new google.maps.LatLngBounds ();
	  //  Go through each...
	  for (var i = 0, LtLgLen = LatLngList.length; i < LtLgLen; i++) {
		//  And increase the bounds to take this point
		bounds.extend (LatLngList[i]);
	  }
	  //  Fit these bounds to the map
	  map.fitBounds (bounds);
	}
}
function render_resize_prop()
{
	if($('.resize-prop').length > 0) {
		$('.resize-prop').each(function(){
			var obj  = $(this);
			var w = obj.attr("data-w");
			var h = obj.attr("data-h");
			resize_prop(obj,w,h);
		});
	}
}
function resize_prop(el,dw,dh)
{
	var nw = el.width();
	var nh = resize_prop_matrix(dw,dh,nw);
	el.css("height",nh);
}
function resize_prop_matrix(dw,dh,nw)
{
	return (nw * dh) / dw;
}