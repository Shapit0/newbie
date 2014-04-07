$(document).ready(function(){


	$.ajax({
	  type: 'POST',
	  url: 'http://n.vnteam.ru/ajax',
	  data: '{"type" : "backend", "pay" : "none", "remote" : "office"}',
	  success: function(data){
	    $('.result').html(data);
	  }
	});
	


});