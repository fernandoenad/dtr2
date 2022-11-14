$().ready(function(){
	//$("table").each(function(){
	//    if ($(this).height() > $(window).height()){
	//      $(this).stickyTableHeaders();
	//    }
	//});
	$( ".sticky-msg-close" ).bind('click', function(){
		$(this).parent().fadeOut(100);
	});
})
function show_loading_msg(){
	var loading_msg = document.createElement('div');
	loading_msg.className='loading';
	$('.loading').remove();
	$('body').append(loading_msg);
	$('.loading').show();
}

function hide_loading_msg(){
	$('.loading').remove();
} 
function show_sticky_msg(title,msg){
	var stm = document.createElement('div'),
	stmh = document.createElement('div'),
	stmc = document.createElement('span'),
	stmm = document.createElement('div')
	stm.className ='sticky-msg';
	stmc.className ='sticky-msg-close';
	stmh.className ='sticky-msg-header';
	stmm.className ='sticky-msg-content';
	stmh.innerHTML = title;
	stmm.innerHTML = msg;
	stm.appendChild(stmh);
	stm.appendChild(stmc);
	stm.appendChild(stmm);
	document.body.appendChild(stm);
	$().ready(function(){
		$( ".sticky-msg-close" ).bind('click', function(){
			$(this).parent().fadeOut(100);
		});
		$( ".sticky-msg" ).draggable({ handle: ".sticky-msg-header" });
	});
}
$(function() {
   $( ".modal" ).draggable({ handle: ".modal-header" });
});