var search_xhr;
var KeyLength = 2;
$(document).ready(function() {
	$('input[name="keywords"]').keyup(function(){
		var obj = $(this);
		var v = obj.val();
		var action = obj.data("url");
		var cStr = countStr(v);
		if(cStr >= KeyLength) {
			if(search_xhr && search_xhr.readyState != 4){
				search_xhr.abort();
			}
			search_xhr = $.ajax({
				url:action,
				type:"post",
				data:{v:v,csrf_sharp_sn2_name : $('input[name="'+nct_name+'"]').val()},
				success: function(response){
					if(response) {
						var json_response = JSON.parse(response.json);
						$(".search-autocomplete-results").fadeIn("fast").find(".searchAutoCompleteScroller").html(json_response);
/*						$(".search-autocomplete-results").mCustomScrollbar({
							scrollbarPosition: "none",
							scrollEasing: "easeInOut",
							deltaFactor: 1,
							scrollInertia: 1000,
							mouseWheel: {
								scrollAmount: 175,
								preventDefault: true
							},
							advanced: {
								updateOnContentResize: true
							}

						});*/
						if(response.nct)
						$('input[name="'+nct_name+'"]').val(response.nct);
					}
				},
				error: function(xhr, status, error) {
					$(".search-autocomplete-results").fadeOut("fast").find(".searchAutoCompleteScroller").html("");
					//alert("ERROR, PLEASE TRY AGAIN...");
				}	
			});
		}
		else {
			if(cStr == 0)
			$(".search-autocomplete-results").fadeOut("fast").find(".searchAutoCompleteScroller").html("");
		}
	});	
	
	$('html, body').click(function(e) {                    
	   if(!$(e.target).hasClass('ignore-click') )
	   {
		   $(".search-autocomplete-results").fadeOut("fast").find(".searchAutoCompleteScroller").html("");          
	   }
	}); 
});
function countStr(str)
{
	return str.length;
}
function renderAutoCompleteHTML(data)
{
	
}