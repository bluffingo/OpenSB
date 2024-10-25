let uiSounds = false;
const cookie = document.cookie.split('; ').find(row => row.startsWith('SBOPTIONS='));
if (cookie) {
	const encodedOptions = cookie.split('=')[1];
	const decodedOptions = decodeURIComponent(encodedOptions);
	const options = JSON.parse(atob(decodedOptions));
	if (options.hasOwnProperty('sounds')) {
		uiSounds = options.sounds;
	}
}

function play(sound) {
	if (JSON.parse(uiSounds) === true) {
		let audio = new Audio('/assets/sounds/' + sound + '.ogg');
		audio.play();

		audio.addEventListener('ended', function() {
			audio = null;
		});
	}
}

$(document).ready(function(){
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
		$("#bootstrap").attr("href", "assets/bs-vanilla.css");
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
	$("#finalium-dark").click(function(){
		$("#themeSelection").removeClass("show");
		$("#bootstrap").attr("href", "assets/bs-finalium-dark.css");
		$("#navbar").attr("class", "navbar navbar-dark bg-dark navbar-static-top navbar-expand-md");
		Cookies.set("theme", "finalium-dark", { expires: 1000 });
	});
	$("#action_unlogged").click(function(){
		play("error");
		alert('you must be logged in.');
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
		play("click");
		$("#commentPostingSpinner").removeClass('d-none');
		$.post("/api/legacy/comment",
		{
			comment: $.trim($('#commentContents').val()),
			vidid: video_id
		},
		function(data,status){
			if (status == "success") {
				console.log("Commented " + $('#commentContents').val());
				$('#comment').prepend(data);
				$("#commentContents").val('');
				$("#post").addClass("disabled");
				$("#commentPostingSpinner").addClass('d-none');
				play("comment");
			}
		});
	});
	$("#subscribe").click(function(){
		$.post("/api/legacy/subscribe",
		{
			subscription: user_id
		},
		function(data,status){
			if (status == "success") {
				if(data == subscribe_string) {
					$("#subscribe").text(subscribe_string);
					$("#subscribe").attr("class", "btn btn-primary");
					console.log("Unsubscribed " + user_id);
					play("click");
				} else if(data == unsubscribe_string) {
					$("#subscribe").text(unsubscribe_string);
					$("#subscribe").attr("class", "btn btn-secondary");
					console.log("Subscribed " + user_id);
					play("subscribe");
				} else {
					play("error");
					alert('unexpected output! report to https://github.com/bluffingo/OpenSB/issues');
				}
			}
		});
	});	
	$("#like").click(function(){
		if($("#like").attr("class") != "text-success") {
			$.post("/api/legacy/rate",
			{
				rating: 5, // sb ratings are internally 5 stars
				vidid: video_id
			},
			function(data,status){
				if (status == "success") {
					if(data == 1) {
						$("#like").attr("class", "text-success");
						$("#likes").text(parseInt($("#likes").text()) + 1)
						$("#dislikes").text(parseInt($("#dislikes").text()) - 1)
						$("#dislike").attr("class", "text-body");
						play("like");
					} else if(data == 0) {
						$("#like").click();
					} else {
						play("error");
						alert('unexpected output! report to https://github.com/bluffingo/OpenSB/issues');
					}
				}
			});
		}
	});
	$("#dislike").click(function(){
		if($("#dislike").attr("class") != "text-danger") {
			$.post("/api/legacy/rate",
			{
				rating: 1, // sb ratings are internally 5 stars
				vidid: video_id
			},
			function(data,status){
				if (status == "success") {
					if (data == 1) {
						$("#dislike").attr("class", "text-danger");
						$("#dislikes").text(parseInt($("#dislikes").text()) + 1)
						$("#likes").text(parseInt($("#likes").text()) - 1)
						$("#like").attr("class", "text-body");
						play("dislike");
					} else if (data == 0) {
						$("#dislike").click();
					} else {
						play("error");
						alert('unexpected output! report to https://github.com/bluffingo/OpenSB/issues');
					}
				}
			});
		}
	});
});
