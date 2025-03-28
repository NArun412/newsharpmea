<?php
$content_name = $table_name;
$scripts_string ='var getUrl = window.location;
var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split("/")[1]+ "/";

function delete_row(id){
window.location= baseUrl+"back_office/'.$table_name.'/delete/"+id;
return false;
}

function delete_translation(id,id_parent){
window.location= baseUrl+"back_office/'.$table_name.'/delete_translation/"+id+"/"+id_parent;
return false;
}

$(function(){

if(jQuery().tableDnD) {
$("#usersList").tableDnD({
onDrop: function(table, row) {
var ser=$.tableDnD.serialize();
$("#result").load(baseUrl+"back_office/'.$table_name.'/sorted?"+ser);
}
});
}

$("input[name=\'search\']").bind(\'keyup\', function(e){
e.preventDefault();
var id =this.id;
$(\'#usersList tbody tr\').css(\'display\',\'none\');
var searchtxt = $.trim($(this).val());
var bigsearchtxt = searchtxt.toUpperCase(); 
var smallsearchtxt = searchtxt.toLowerCase();
var fbigsearchtxt = searchtxt.toLowerCase().replace(/\b[a-z]/g, function(letter) {
return letter.toUpperCase();
});
if(searchtxt == ""){
$(\'#usersList tbody tr\').css(\'display\',"");	
} else {
$(\'#usersList tbody tr td:contains("\'+searchtxt+\'")\').parent().css(\'display\',"");
$(\'#usersList tbody tr td:contains("\'+bigsearchtxt+\'")\').parent().css(\'display\',"");
$(\'#usersList tbody tr td:contains("\'+fbigsearchtxt+\'")\').parent().css(\'display\',"");
$(\'#usersList tbody tr td:contains("\'+smallsearchtxt+\'")\').parent().css(\'display\',"");
}
});

$("#show_items").change(function(){
	var val = $(this).val();
	$("#result").html(val);
	$("#show_items").submit();
});

});';