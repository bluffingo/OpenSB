function postThreadedComment(comment_form_id) 
{
	if (CheckLogin() == false)
		return false;

	var form = document.forms[comment_form_id];

	if (ThreadedCommentHandler(form)) {
		var add_button = form.add_comment_button;
		add_button.value = "Adding comment..";
		add_button.disabled = true;

	} 
}
function ThreadedCommentHandler(comment_form)
{
        var comment = comment_form.comment;
        var comment_button = comment_form.comment_button;

        if (comment.value.length == 0 || comment.value == null)
        {
                alert("You must enter a comment!");
                comment.focus();
                return false;
        }

        if (comment.value.length > 500)
        {
                alert("Your comment must be shorter than 500 characters!");
                comment.focus();
                return false;
        }

		postFormByForm(comment_form, true, commentResponse);
        return true;
}
function commentResponse(xmlHttpRequest)
{
	response_str = xmlHttpRequest.responseText;
	response_code = response_str.substr(0, response_str.indexOf(" "));
	form_id = response_str.substr(response_str.indexOf(" ")+1);
	
	var form = document.forms[form_id];
	var dstDiv = form.add_comment_button;
	var discard_button = form.discard_comment_button;

	if (response_code == "OK") {
        dstDiv.value = "Comment Posted!";
        dstDiv.disabled = true;
		discard_button.disabled = true;
		discard_button.style.display  = "none";
		alert("Thank You. Your comment has been posted!");
	} else if (response_code == "PENDING") {
        	dstDiv.value = "Comment Pending Approval!";
       		dstDiv.disabled = true;
		alert("Your comment has been posted! It will be visible once it is approved.");
		discard_button.disabled = true;
		discard_button.style.display  = "none";

	} else if (response_code == "LOGIN") {
            alert("An error occured while posting the comment. Please relogin and try again.");
            dstDiv.disabled = false;
	} else if (response_code == "EMAIL") {
            if(confirm("You must confirm your email address before you can submit comments.  Click OK to confirm your email address."))
		{
			window.location="/email_confirm"
		}
            dstDiv.disabled = false;
	} else {
        if(response_code == "BLOCKED") {
            alert("You have been blocked from commenting on this user's videos.");
            dstDiv.disabled = true;
        } else if(response_code == "TOOSOON") {
        	alert("You have recently posted several comments. Please wait for some time before posting another.");
            dstDiv.disabled = false;
        } else if(response_code == "TOOLONG") {
        	alert("The comment you have entered is too long. Please write a shorter comment and try again.");
            dstDiv.disabled = false;
        } else {
            alert("An error occured while posting the comment.");
            dstDiv.disabled = false;
        }
        dstDiv.value = "Post Comment";
    } 

}


function approveComment(comment_id, comment_type)
{
	if (CheckLogin() == false)
		return false;
	
	//if(!confirm("Really approve this comment?"))
	//	return true;
	
	//postFormByForm(form, true, execOnSuccess(commentApproved));
	//postUrl("/comment_servlet",  urlEncodeDict(formVars), true, execOnSuccess(commentApproved));
	
	postUrlXMLResponse("/comment_servlet", "&field_approve_comment&comment_id=" + comment_id + "&comment_type=" + comment_type, self.commentApproved);

	return false;
}
	


function commentApproved(xmlHttpRequest)
{
	alert("Comment approved.")
}


function removeComment(div_id, deleter_user_id, comment_id, comment_type)
{
	self.div_id = div_id
	self.commentRemoved = commentRemoved
	if (CheckLogin() == false)
		return;

	//if (!confirm("Really remove comment?"))
	//	return;
	
	postUrlXMLResponse("/comment_servlet", "deleter_user_id=" + deleter_user_id + "&remove_comment&comment_id=" + comment_id + "&comment_type=" + comment_type, self.commentRemoved);

	return false;
}
function commentRemoved(xmlHttpRequest)
{
	//alert('Comment removed.');
	toggleVisibility(self.div_id, false);
	return;
}
		
function hideCommentReplyForm(form_id) {
	var div_id = "div_" + form_id;
	var reply_id = "reply_" + form_id;
	toggleVisibility(reply_id, true);
	toggleVisibility(div_id, false);
	//setInnerHTML(div_id, "");
}

function handleStateChange(xmlHttpReq) {
	document.getElementById("all_comments_content").innerHTML=getNodeValue(xmlHttpReq.responseXML, "html_content");
	
	style2 = document.getElementById("recent_comments").style;
	style2.display = "none";
	
	var style2 = document.getElementById("all_comments").style;
	style2.display = "";
}

function load_all_comments(video_id, is_watch2) {
	var remove_btn = document.getElementById('all_comments_button');
	if(remove_btn) {
		remove_btn.value = "Loading Comments..";
		remove_btn.disabled = true
	}
		
	if(is_watch2)
		var watch2_str = "&watch2"
	else
		var watch2_str = ""
	
	getUrlXMLResponse("/comment_servlet?get_comments&v=" + video_id + watch2_str, handleStateChange);
	
}