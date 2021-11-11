$(document).ready(function(){
	var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
});
	function play(sound) {
		var audio = new Audio('/assets/sounds/'+sound+'.wav');
		audio.play();
	}
	$("#masthead-loggedin").click(function() {
	  var x = document.getElementById("masthead-below");
	  if (x.style.display === "none") {
		x.style.display = "block";
	  } else {
		x.style.display = "none";
	  }
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
		$( "#darkthm" ).remove();
		$("#light").attr("hidden", true);
		$("#dark").attr("hidden", false);
		Cookies.set("theme", "default", { expires: 1000 });
	});	
	$("#dark").click(function(){
		$( "head" ).append( "<link id=\"darkthm\" rel=\"stylesheet\" href=\"/assets/css/darkmode.css\" type=\"text/css\">" );
		$("#light").attr("hidden", false);
		$("#dark").attr("hidden", true);
		Cookies.set("theme", "dark", { expires: 1000 });
	});
	$("#action_unlogged").click(function(){
		play("error");
		alert('you must be logged in.');
	});
	contents = $.trim($("#commentContents").val());
	if (contents === null || contents == "" && $("#post").attr("class") != "button button-primary disabled") {
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
		$.post("comment.php",
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
	$("#fromUser").click(function(){
		index = 0;
		if ($("#fromUserVideoList").attr("class") != "card-body") {
			$.post("ajax_watch.php",
			{
				from: index,
				limit: 6,
				user: user_id
			},
			function(data,status){
				if (status == "success") {
					index += 6;
					$('#fromUserVideoList').append(data);
					$("#fromUserVideoList").removeClass("collapsed")
				}
			});
		} else {
			$("#fromUserVideoList").empty();
			$("#fromUserVideoList").addClass("collapsed");
		}
	});
	$("#subscribe").click(function(){
		$.post("subscribe.php",
		{
			subscription: user_id
		},
		function(data,status){
			if (status == "success") {
				if(data == subscribe_string) {
					$("#subscribe").text(subscribe_string);
					$("#subscribe").attr("class", "button button-primary");
					console.log("Unsubscribed " + user_id);
					play("click");
				} else if(data == unsubscribe_string) {
					$("#subscribe").text(unsubscribe_string);
					$("#subscribe").attr("class", "button button-secondary");
					console.log("Subscribed " + user_id);
					play("subscribe");
				} else {
					play("error");
					alert('unexpected output! report to https://github.com/squarebracket-gamerappa/squarebracket/issues');
				}
			}
		});
	});	
	$("#subscribe-watch").click(function(){
		$.post("subscribe.php",
		{
			subscription: user_id
		},
		function(data,status){
			if (status == "success") {
				if(data == subscribe_string) {
					$("#subscribe-watch").text(subscribe_string);
					$("#subscribe-watch").attr("class", "button button-primary button-small");
					console.log("Unsubscribed " + user_id);
					play("click");
				} else if(data == unsubscribe_string) {
					$("#subscribe-watch").text(unsubscribe_string);
					$("#subscribe-watch").attr("class", "button button-secondary button-small");
					console.log("Subscribed " + user_id);
					play("subscribe");
				} else {
					play("error");
					alert('unexpected output! report to https://github.com/squarebracket-gamerappa/squarebracket/issues');
				}
			}
		});
	});	
	$("#like").click(function(){
		if($("#like").attr("class") != "button button-success") {
			$.post("rate.php",
			{
				rating: 1,
				vidid: video_id
			},
			function(data,status){
				if (status == "success") {
					if(data == 1) {
						$("#like").attr("class", "button button-success");
						$("#likes").text(parseInt($("#likes").text()) + 1)
						$("#dislikes").text(parseInt($("#dislikes").text()) - 1)
						$("#dislike").attr("class", "button button-secondary-invis");
						play("like");
					} else if(data == 0) {
						$("#like").click();
					} else {
						play("error");
						alert('unexpected output! report to https://github.com/squarebracket-gamerappa/squarebracket/issues');
					}
				}
			});
		}
	});
	$("#dislike").click(function(){
		if($("#dislike").attr("class") != "button button-danger") {
			$.post("rate.php",
			{
				rating: 0,
				vidid: video_id
			},
			function(data,status){
				if (status == "success") {
					if (data == 1) {
						$("#dislike").attr("class", "button button-danger");
						$("#dislikes").text(parseInt($("#dislikes").text()) + 1)
						$("#likes").text(parseInt($("#likes").text()) - 1)
						$("#like").attr("class", "button button-secondary-invis");
						play("dislike");
					} else if (data == 0) {
						$("#dislike").click();
					} else {
						play("error");
						alert('unexpected output! report to https://github.com/squarebracket-gamerappa/squarebracket/issues');
					}
				}
			});
		}
	});
});
	function openTab(evt, tab) {
	  // Declare all variables
	  var i, tabcontent, tablinks;

	  // Get all elements with class="tabcontent" and hide them
	  tabcontent = document.getElementsByClassName("tabcontent");
	  for (i = 0; i < tabcontent.length; i++) {
		tabcontent[i].style.display = "none";
	  }

	  // Get all elements with class="tablinks" and remove the class "active"
	  tablinks = document.getElementsByClassName("tablinks");
	  for (i = 0; i < tablinks.length; i++) {
		tablinks[i].className = tablinks[i].className.replace(" active", "");
	  }

	  // Show the current tab, and add an "active" class to the button that opened the tab
	  document.getElementById(tab).style.display = "block";
	  evt.currentTarget.className += " active";
	}