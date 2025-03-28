$.fn.customformvalidate = function( options ) {
	// Establish our default settings
	var settings = $.extend({
		formresult: ".FormResult",
		formerror: ".form-error",
		loader: "Checking...",
		parentObj: "div",
		errorClass: "vError",
		validateonly: false,
		updateonly: false,
		callback    :   function(){}
	}, options);
	var obj = $(this);
	var FormObj = $(this);
	var formresult = settings.formresult;
	var formerror = settings.formerror;
	FormObj.find("input[type='submit']").click(function(event){
		  event.preventDefault();
		  var SubmitButton = FormObj.find("input[type='submit']");
		  var SB_Text = SubmitButton.val();
		  SubmitButton.attr('disabled','disabled');
		 // FormObj.find( formresult ).html(settings.loader);
		 SubmitButton.val(settings.loader);
		  var formAction = FormObj.attr('action');
		  var formData = new FormData(FormObj[0]);
		 $.ajax({
        url: formAction,
        type: 'POST',
        data: formData,
        success: function (data) {
		   //Parsedata = JSON.parse(data);
		   Parsedata = data;
		   SubmitButton.removeAttr('disabled');
		   SubmitButton.val(SB_Text);
		   if(Parsedata.result == 0) {
			   if(Parsedata.nct)
			   $('input[name="'+nct_name+'"]').val(Parsedata.nct);
			    if(Parsedata.errors) {
					 var errors = Parsedata.errors;
			   $.each(errors, function(i, val){
				    FormObj.find( '#'+i+'-error' ).html(val);
				    FormObj.find( 'input[name="'+i+'"], textarea[name="'+i+'"], select[name="'+i+'"]' ).addClass(settings.errorClass);
					if(i == "cv_validate")
					$('input[name="cv"]').addClass(settings.errorClass);
			   });
				}
				 if(Parsedata.message) {
					  FormObj.find( formresult ).html(Parsedata.message);
				 }
			  
			   FormObj.find( formresult ).html('');
			  var emptyy = '';
			  $(formerror).each(function(){
				  if ($(this).html().trim().length) {
					  if(emptyy == '') {
						  emptyy = $(this).attr('id');
					  }
				  }
			   });
			   if(emptyy != "" && FormObj.attr('id') != 'downloadBrochureConfirmationForm') {
			   	var anmEror = $('#'+emptyy).parent(".form-item").offset().top - 90;
			   	$('html, body').animate({scrollTop : anmEror},1000);
			   }
		   }
		   else if(Parsedata.result == 1) {	
			   //FormObj.find( formresult ).html(Parsedata.message);
			   if(!settings.validateonly) {
				FormObj.find( 'input[type="file"], input[type="text"], input[type="email"], input[type="hidden"], input[type="password"], textarea, select' ).each(function(){
				   var Obj = $(this);
				   if(Obj.attr("type") != "hidden")
				   Obj.val('');	
				   Obj.removeClass(settings.errorClass); 
				   Obj.parent(settings.parentObj).find(formerror).html('');	
				   //var anmEror = FormObj.find(formresult).offset().top - 100;
				  
			   });
			 }
			    if(Parsedata.captcha != null) {
				   $("input[name='captcha']").val('');	
			   	FormObj.find( '.captchaImage' ).html(Parsedata.captcha);
			   }
			 				
			 if(Parsedata.redirect_link != null)
			 window.location = Parsedata.redirect_link;
			 
			 if ( $.isFunction( settings.callback ) ) {
	  			settings.callback.call( obj, {data: data} );
   			 }
		   }
		  },
		  fail:function(){
			    SubmitButton.removeAttr('disabled');
		   SubmitButton.val(SB_Text);
		   alert('Something went wrong, please reload your page and try again.');
			 },
			 error:function(){
			    SubmitButton.removeAttr('disabled');
		   SubmitButton.val(SB_Text);
		   alert('Something went wrong, please reload your page and try again.');
			 },
		  contentType: false,
        processData: false
			 });
	 });
	 FormObj.find( 'input[type="text"],input[type="file"],input[type="email"], input[type="password"], textarea, select' ).focus(function(){
		 var Obj = $(this);
		 Obj.removeClass(settings.errorClass);
		 Obj.parent(settings.parentObj).find(formerror).html('');		 
	 });
	
	if ( $.isFunction( settings.callback ) ) {
		settings.callback.call( this );
	}
}