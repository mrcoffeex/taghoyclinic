/*quick search*/
$(document).ready( function() {
			$(".result").hide();

$("#key").keyup( function(event){
	var key = $("#key").val();

	if( key != 0){
		$.ajax({
		type: "POST",
		data: ({key: key}),
		url:"quicksearch.php",
		success: function(response) {
		$(".result").slideDown().html(response); 
		}
		})
		
		}else{
		
		$(".result").slideUp();
		$(".result").val("");
		}
 })	
 
}) 