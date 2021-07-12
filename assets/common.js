$(document).ready(function(){
	var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
});
	$(window).click(function() {
	  $("#mainMenu").removeClass("show");
	  $("#themeSelection").removeClass("show");
	});
	$("#openSettings").click(function(event){
		event.stopPropagation();
		$("#mainMenu").addClass("show");
	});
	$("#back").click(function(event){
		event.stopPropagation();
		$("#themeSelection").removeClass("show");
		$("#mainMenu").addClass("show");
	});
	$("#themeSelect").click(function(event){
		event.stopPropagation();
		$("#mainMenu").removeClass("show");
		$("#themeSelection").addClass("show");
	});
	$("#light").click(function(){
		$("#themeSelection").removeClass("show");
		$("#bootstrap").attr("href", "assets/bs.css");
		$("#navbar").attr("class", "navbar navbar-light bg-light navbar-static-top navbar-expand-md");
		Cookies.set("theme", "light", { expires: 1000 });
	});
	$("#vanilla").click(function(){
		$("#themeSelection").removeClass("show");
		$("#bootstrap").attr("href", "https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css");
		$("#navbar").attr("class", "navbar navbar-light bg-light navbar-static-top navbar-expand-md");
		Cookies.set("theme", "vanilla", { expires: 1000 });
	});
	$("#dark").click(function(){
		$("#themeSelection").removeClass("show");
		$("#bootstrap").attr("href", "assets/bs-dark.css");
		$("#navbar").attr("class", "navbar navbar-dark bg-dark navbar-static-top navbar-expand-md");
		Cookies.set("theme", "dark", { expires: 1000 });
	});
	$("#finalium").click(function(){
		$("#themeSelection").removeClass("show");
		$("#bootstrap").attr("href", "assets/bs-finalium.css");
		$("#navbar").attr("class", "navbar navbar-light bg-light navbar-static-top navbar-expand-md");
		Cookies.set("theme", "finalium", { expires: 1000 });
	});
	contents = $.trim($("#commentContents").val());
	if (contents === null || contents == "" && $("#post").attr("class") != "btn btn-primary disabled") {
		$("#post").addClass("disabled");
	}
	$("#commentContents").keydown(function(e){
		if (e.key == "Backspace") {
			contents = $.trim($("#commentContents").val()).slice(0, -1);
		} else {
			contents = $.trim($("#commentContents").val()) + e.key;
		}
		if (contents == "") {
			$("#post").addClass("disabled");
		} else if (contents != "") {
			$("#post").removeClass("disabled")
		}
	});
	
	$("#post").click(function(){
		$("#commentPostingSpinner").removeClass('d-none');
		$.post("comment.php",
		{
			comment: $.trim($("#commentContents").val()),
			vidid: video_id
		},
		function(data,status){
			if (status == "success") {
				$('#comment').prepend(data);
				$("#commentContents").val('');
				$("#post").addClass("disabled");
				$("#commentPostingSpinner").addClass('d-none');
			}
		});
	});
	$("#like").click(function(){
		if($("#like").attr("class") != "text-success") {
			$.post("rate.php",
			{
				rating: 1,
				vidid: video_id
			},
			function(data,status){
				if (status == "success") {
					if(data == 1) {
						$("#like").attr("class", "text-success");
						$("#likes").text(parseInt($("#likes").text()) + 1)
						$("#dislikes").text(parseInt($("#dislikes").text()) - 1)
						$("#dislike").attr("class", "text-body");
					} else if(data == 0) {
						$("#like").click();
					} else {
						alert('unexpected output! report to https://github.com/chazizsquarebracket/squarebracket/issues');
					}
				}
			});
		}
	});	
	$("#dislike").click(function(){
		if($("#dislike").attr("class") != "text-danger") {
			$.post("rate.php",
			{
				rating: 0,
				vidid: video_id
			},
			function(data,status){
				if (status == "success") {
					if (data == 1) {
						$("#dislike").attr("class", "text-danger");
						$("#dislikes").text(parseInt($("#dislikes").text()) + 1)
						$("#likes").text(parseInt($("#likes").text()) - 1)
						$("#like").attr("class", "text-body");
					} else if (data == 0) {
						$("#dislike").click();
					} else {
						alert('unexpected output! report to https://github.com/chazizsquarebracket/squarebracket/issues');
					}
				}
			});
		}
	});
});