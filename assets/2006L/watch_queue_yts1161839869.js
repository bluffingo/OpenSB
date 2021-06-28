var videolist = new Array();
var removelist = new Array();

function clear_watch_queue()
{
		if (confirm('Remove all videos from your QuickList?')) {
	                postUrlXMLResponse("/watch_queue_ajax", "&action_clear_queue", self.queueClearedReloadPage);
		}
}

function clear_watch_queue_watch_page()
{
               if (confirm('Are you sure you want to remove all videos from your QuickList?')) {
        	       	postUrlXMLResponse("/watch_queue_ajax", "&action_clear_queue", self.queueCleared);
			var watchqueue_div = document.getElementById('watchqueue');
			var watchqueue_new_div = document.getElementById('watchqueueStartNew');
			if (watchqueue_div) {
				closeDiv('watchqueue');
			}
			if (watchqueue_new_div) {
				closeDiv('watchqueueStartNew');
			}
			
        		quicklist_count=0;
        		document.getElementById('play_all_numb').innerHTML = quicklist_count + " ";                             
			document.getElementById('quicklist_numb').innerHTML = '<a href="/watch_queue?all">' + quicklist_count + '</a>';
			return false;
               }
}
function add_to_watch_queue(video_id)
{
                videolist.push(video_id);
                post_videos_to_server();
                return false;
}
function remove_from_watch_queue(video_id)
{
                removelist.push(video_id);
                delete_videos_from_server();
                return false;
}

function set_pop_status(pop_status)
{
	postUrlXMLResponse("/watch_queue_ajax", "action_set_pop_status&pop_videos="+pop_status, self.popStatusSet)
}

function popStatusSet() {
}

function post_videos_to_server() {
                if (videolist.length>0) {
                        postUrlXMLResponse("/watch_queue_ajax", "&action_add_to_queue&video_id=" + videolist[videolist.length-1], self.videoQueued);
                        videolist.pop();
                }
}

function delete_videos_from_server() {
                if (removelist.length>0) {
			postUrlXMLResponse("/watch_queue_ajax", "&action_remove_from_queue&video_id=" + removelist[removelist.length-1], self.videoRemoved);
                        removelist.pop();
                }
}


function queueCleared(xmlHttpRequest)
{
        window.location=window.location.href;
}

function queueClearedReloadPage(xmlHttpRequest)
{        
	window.location="watch_queue?all";
}

function videoQueued(xmlHttpRequest)
{
        //alert('video added to queue');
        post_videos_to_server();
}

function videoRemoved(xmlHttpRequest)
{
        delete_videos_from_server();
        //alert('Video removed from QuickList');
}

function clicked_add_icon(video_ID, fromRelated) {
	add_to_watch_queue(video_ID);
	quicklist_count++;
	if (fromRelated == 1) {
		showQuickList_first_add();
		document.getElementById('play_all_numb').innerHTML=quicklist_count+'&nbsp;';
		if (document.getElementById('show_all_video_number')) {
			document.getElementById('show_all_video_number').innerHTML = quicklist_count;
		}	
	}
	var button_name = 'add_button_' + video_ID;
	document.getElementById('quicklist_numb').innerHTML='<a href=/watch_queue?all>' + quicklist_count + '</a>';
	document.getElementById(button_name).src='/assets/2006l/icn_add_done_20x20.gif';
	document.getElementById(button_name).blur();
}

function mouse_over_add_icon(video_ID) {
	var button_name = 'add_button_' + video_ID;
	if (document.getElementById(button_name).src.match('done')){
		document.getElementById(button_name).src='/assets/2006l/icn_add_done_20x20.gif';
	}
	else {
		document.getElementById(button_name).src='/assets/2006l/icn_add_over_20x20.gif';
	}			
}

function mouse_out_add_icon(video_ID) {
	var button_name = 'add_button_' + video_ID;
	if (document.getElementById(button_name).src.match('done')){
		document.getElementById(button_name).src='/assets/2006l/icn_add_done_20x20.gif';
	}
	else {
		document.getElementById(button_name).src='/assets/2006l/icn_add_20x20.gif';
	}			
}

function showQuickList_first_add() {
	if (document.getElementById('watchqueueStartNew')) {
		openDiv('watchqueueStartNew');
	}
        if (getQuickCookie() == 'yes') {
		openDiv('watchlist_container');
		openDiv('save_row');
	}
	if (document.getElementById('quicklist_intro')) {
		closeDiv('quicklist_intro');
	}
}

function removeVideo(video_id) {
	var row_id="vid_row_" + video_id;
	remove_from_watch_queue(video_id);
	//keep track of video numbers that display in the left column and reorder the numbers
	var tbody = document.getElementById("watchlist_table").tBodies[0];
	var total_rows = tbody.rows.length-1;
	var current_row = tbody.rows[row_id].cells[0].innerHTML;
	var current_row_in_table = tbody.rows[row_id].rowIndex-1;
	for (i=total_rows;i>current_row_in_table;i--) {
		tbody.rows[i].cells[0].innerHTML = tbody.rows[i].cells[0].innerHTML - 1;
	}
	tbody.removeChild(document.getElementById(row_id));
	quicklist_count--;
	document.getElementById('play_all_numb').innerHTML = quicklist_count + " ";
	document.getElementById('quicklist_numb').innerHTML = '<a href="/watch_queue?all">' + quicklist_count + '</a>';
        if (document.getElementById('show_all_video_number')) {
		document.getElementById('show_all_video_number').innerHTML = quicklist_count;
        }
}

//global variable to keep track of video #1 when user added to a new QuickList and wants to play all
var first_video_id;
var first_video_url;
var first_video_image_url;
var first_video_title;

function print_quicklist_video(img_url, vid_title, username, vid_id, runtime) {
	var vid_number=document.getElementById('watchlist_table').rows.length;
	if (document.getElementById('watchlist_table').rows.length<2) {
	        showQuickList_first_add();
	}

	var new_video_number = quicklist_count;
	var temp_vid_title = unescape(vid_title);

	var div_id_1 ="1_"+vid_id;
	var div_id_2 ="2_"+vid_id;
	var div_id_3 ="3_"+vid_id;
	var div_id_4 ="4_"+vid_id;

	var div_content_1 = new_video_number;
	var div_content_2 = '<a href="/watch?v=' + vid_id + '"><img src="' + img_url + '" width="43" height="33" border="0" style="padding:3px;" align="middle"></a>';
	var div_content_3 = '<div onClick=\'window.location="/watch?v=' + vid_id + '";\' style="cursor:hand;cursor:pointer;"><div class="vtitle"><a href="/watch?v=' + vid_id + '" >' + temp_vid_title + '</a></div><div class="vfacets"><span class="grayText">From: ' + username + '</span></div></div>';
	var div_content_4 = '<pan class="grayText smallText">' + runtime + '</span> &nbsp;<span class="grayText"><a href="#" onClick="removeVideo(\'' + vid_id + '\');return false;" title="Remove Video From QuickList"><img src="/img/icn_trash_10x12.gif" valign="middle" alt="Remove Video" border="0" /></a>&nbsp;';

	var tbody = document.getElementById("watchlist_table").tBodies[0];
	var row = document.createElement("TR");
	row.setAttribute("id","vid_row_"+vid_id);
	var cell1 = document.createElement("TD");
	cell1.setAttribute("id", "1_"+vid_id);
	cell1.setAttribute("width", "8");
        cell1.setAttribute("class", "grayText");

	var cell2 = document.createElement("TD");
	var tempDiv_2 = document.createElement("DIV");
	tempDiv_2.setAttribute("id", "2_"+vid_id);
	tempDiv_2.setAttribute("width", "55");

	var cell3 = document.createElement("TD");
	var tempDiv_3 = document.createElement("DIV");
	tempDiv_3.setAttribute("id", "3_"+vid_id);
	tempDiv_3.setAttribute("align", "left");
	tempDiv_3.setAttribute("width", "313");

	var cell4 = document.createElement("TD");
	cell4.setAttribute("align","right");
	var tempDiv_4 = document.createElement("DIV");
	tempDiv_4.setAttribute("id", "4_"+vid_id);
	tempDiv_4.setAttribute("width", "50");

	cell2.appendChild(tempDiv_2);
	cell3.appendChild(tempDiv_3);
	cell4.appendChild(tempDiv_4);

	row.appendChild(cell1);
	row.appendChild(cell2);
	row.appendChild(cell3);
	row.appendChild(cell4);

	tbody.appendChild(row);
	if (document.getElementById('now_playing_end')) { 
		if (document.getElementById('watchlist_table').rows.length>6) {
        		setTimeout("jumpToNowPlaying(1);", 200);
		}
		else {
        		if (quicklist_count > 5) {
                		jumpToNowPlaying(1);
        		}
		}
	}

	document.getElementById(div_id_1).innerHTML = div_content_1;
	document.getElementById(div_id_2).innerHTML = div_content_2;
	document.getElementById(div_id_3).innerHTML = div_content_3;
	document.getElementById(div_id_4).innerHTML = div_content_4;

	//if first video add dynamically populate next and play all data
	if (document.getElementById('watchlist_table').rows.length<3) {
        	first_video_id = vid_id;
        	first_video_url = "/watch?v=" + vid_id;
        	first_video_image_url = img_url;
        	first_video_title = temp_vid_title.substring(0,30);
       		document.getElementById('next_video_url_1').href = first_video_url;
        	document.getElementById('next_video_url_2').href = first_video_url;
        	document.getElementById('next_video_title').innerHTML = first_video_title;
        	document.getElementById('next_video_image_url').src= first_video_image_url;
                document.getElementById('play_all_buttton').src= "/img/btn_play_quicklist_33x25.gif";
	}

}


function play_all_start_new() {
	tempURL ='/watch?v=' + first_video_id + '&playnext=1';
	window.location=tempURL;
	document.getElementById('play_all_buttton').blur();
}

function showQuickList() {
        if (getQuickCookie() == 'no') {
 	        closeDiv('watchlist_container');
        	closeDiv('save_row');
       		document.getElementById('watch_queue_show_hide').src='/img/btn_watchqueue_show_33x25.gif';
		//jumpToNowPlaying();
        }
}

function jumpToNowPlaying(endOfList) {
	if ( navigator.appName == 'Microsoft Internet Explorer') {
		pixelsFromTop=document.documentElement.scrollTop;
	} 
	else {
		pixelsFromTop=window.pageYOffset;
	}
	if (navigator.userAgent.indexOf('Safari') == -1) {
		if(endOfList==1) {
			if (document.getElementById('now_playing_end')) {
				location.href='#now_playing_end';
			}
		}
		else {
			if (document.getElementById('now_playing')) {
				location.href='#now_playing';
			}
		}
		window.scrollTo(0,pixelsFromTop);
	}
}

function clickedHideShowButton() {
	if( document.getElementById('watch_queue_show_hide').src.match('show')) {
		document.getElementById('watch_queue_show_hide').src='/img/btn_watchqueue_hide_33x25.gif';
		openDiv('watchlist_container');
		openDiv('save_row'); 
		setQuickCookie('yes');
	}
	else{
		document.getElementById('watch_queue_show_hide').src='/img/btn_watchqueue_show_33x25.gif';
		closeDiv('watchlist_container');
		closeDiv('save_row'); 
		setQuickCookie('no');
	}
	document.getElementById('watch_queue_show_hide').blur();
}

function setQuickCookie(yesNo)
{
        var today = new Date();
        var expire = new Date();
        expire.setTime(today.getTime() + 7*24*3600000);
        document.cookie = "quicklist="+yesNo+";expires="+expire.toGMTString()+";domain=youtube.com";
}

function getQuickCookie()
{
        if (document.cookie.length > 0)
        {
                var cookiename = "quicklist";
                var quickStart = document.cookie.indexOf(cookiename+"=");
                if (quickStart != -1)
                {
                        quickStart += cookiename.length+1;
                        quickEnd = document.cookie.indexOf(";", quickStart);
                        if (quickEnd == -1) quickEnd = document.cookie.length;
                        //alert(document.cookie.substring(quickStart, quickEnd));
                        return document.cookie.substring(quickStart, quickEnd);
                }
        }
        return null;
}