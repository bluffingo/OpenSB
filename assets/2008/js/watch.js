 function _gel(id) {
    return document.getElementById(id);
}

function ref(instance_or_id) {
    return (typeof (instance_or_id) == "string") ? document.getElementById(instance_or_id) : instance_or_id;
}

function hasAncestor(element, ancestor) {
    var el = ref(element);
    var an = ref(ancestor);
    while (el !== document && el != null) {
        if (el === an) return true;
        el = el.parentNode;
    }
    return false;
}

function hasClass(element, _className) {
    if (!element) {
        return;
    }
    var upperClass = _className.toUpperCase();
    if (element.className) {
        var classes = element.className.split(' ');
        for (var i = 0; i < classes.length; i++) {
            if (classes[i].toUpperCase() == upperClass) {
                return true;
            }
        }
    }
    return false;
}

function addClass(element, _class) {
    if (!hasClass(element, _class)) {
        element.className += element.className ? (" " + _class) : _class;
    }
}

    function removeClass(element, _class) {
        var upperClass = _class.toUpperCase();
        var remainingClasses = [];
        if (element.className) {
            var classes = element.className.split(' ');
            for (var i = 0; i < classes.length; i++) {
                if (classes[i].toUpperCase() != upperClass) {
                    remainingClasses[remainingClasses.length] = classes[i];
                }
            }
            element.className = remainingClasses.join(' ');
        }
    }

    function setVisible(divName, onOrOff) {
        var tempDiv = ref(divName);
        if (!tempDiv) {
            return;
        }
        if (onOrOff) {
            // true = visible
            tempDiv.style.visibility = "visible";
        } else {
            // false = hidden
            tempDiv.style.visibility = "hidden";
        }
    }

    function toggleDisplay(divName) {
        var tempDiv = ref(divName);
        if (!tempDiv) {
            return false;
        }
        if ((tempDiv.style.display == "block") || (tempDiv.style.display == "" && tempDiv.className.indexOf("hid") == 0)) {
            tempDiv.style.display = "none";
            return false;
        } else if ((tempDiv.style.display == "none") || (tempDiv.className.indexOf("hid") != 0)) {
            tempDiv.style.display = "block";
            return true;
        }
    }

    function showDiv(divName) {
        var tempDiv = ref(divName);
        if (!tempDiv) {
            return;
        }
        if (hasClass(tempDiv, "wasinline")) {
            tempDiv.style.display = "inline";
            removeClass(tempDiv, "wasinline");
        } else if (hasClass(tempDiv, "wasblock")) {
            tempDiv.style.display = "block";
            removeClass(tempDiv, "block");
        } else {
            tempDiv.style.display = getDisplayStyleByTagName(tempDiv);
        }
    }

    function getDisplayStyleByTagName(o) {
        var n = o.nodeName.toLowerCase();
        return (n == "span" || n == "img" || n == "a") ? "inline" : (n == 'tr' || n == 'td' ? "" : "block");
    }

    function hideDiv(divName) {
        var tempDiv = ref(divName);
        if (!tempDiv) {
            return;
        }
        if (tempDiv.style.display == "inline") {
            addClass(tempDiv, "wasinline");
        } else if (tempDiv.style.display == "block") {
            addClass(tempDiv, "wasblock");
        }
        tempDiv.style.display = "none";
    }

    function hideDivAfter(divName, delay) {
        window.setTimeout(function () {
            hideDiv(divName)
        }, delay);
    }

    function closeLoginPicker() {
        var loginBox = _gel('loginBoxZ');
        if (loginBox) {
            if (loginBox.style.display != 'none')
                loginBox.style.display = 'none';
        }
    }

    function openLoginBoxFromWatchTab(event) {
        var loginBox = _gel('loginBoxZ');
        var totalOffset = getTotalOffset(_gel('watch-actions-area'), false);
        loginBox.style.left = '85px';
        loginBox.style.right = 'auto';
        loginBox.style.top = (totalOffset[1] + 75) + 'px';
        openLoginBox(event, true);
        return false;
    }

    function openLoginBox(event, setPositionManually) {
        if (event) {
            stopPropagation(event);
        }
        var loginBox = _gel('loginBoxZ');
        if (!setPositionManually) {
            loginBox.style.left = 'auto';
            loginBox.style.right = '0px';
            loginBox.style.top = '33px';
        }
        toggleDisplay('loginBoxZ');
        if (_gel('loginNextZ').value == '/signup') {
            _gel('loginNextZ').value = "/index";
        } else if (_gel('loginNextZ').value.indexOf('/signup') == 0) {
            _gel('loginNextZ').value = _gel('loginNextZ').value.substring(13);
        }
        if (_gel('loginBoxZ').style.display != 'none') {
            _gel('loginUserZ').focus();
        }
        _hbLink('LogIn', 'UtilityLinks');
        return false;
    }

    function selectLocale(loc) {
        var current_url, next_url, anchor_url;
        current_url = window.location.href;
///web.archive.org/web/20080531111203/http://check for # sign and insert parameters in between
        current_url = current_url.split('#');
        anchor_url = (current_url.length == 2 ? '#' + current_url[1] : '');
        current_url = current_url[0];
///web.archive.org/web/20080531111203/http://check if the URL string contains any site redirect values;if yes,chop string to remove those values
        if (current_url.indexOf('?locale=') != -1) {
            var url_array = current_url.split("?locale=");
            current_url = url_array[0];
        } else if (current_url.indexOf('&locale=') != -1) {
            url_array = current_url.split("&locale=");
            current_url = url_array[0];
        }
///web.archive.org/web/20080531111203/http://check if the URL string already has parameters;if yes start with "&",if no start with "?"
        next_url = current_url + (current_url.indexOf('?') == -1 ? "?" : "&") + "locale=" + loc + "&persist_locale=1"
            + anchor_url;
        window.location = next_url;
    }

    function closeLocalePicker() {
        var localePickerBox = _gel('localePickerBox');
        if (!localePickerBox) {
            localePickerBox = _gel('localePickerBoxProfile');
        }
        if (localePickerBox) {
            if (localePickerBox.style.display != 'none') {
                localePickerBox.style.display = 'none';
            }
        }
    }

    function enableWatcherShare(token, current_video_id, stringOn) {
        _gel('shareSpan').style.backgroundColor = "#6c0";
        var varg = "";
        if (current_video_id)
            varg = "&v=" + current_video_id;
        getUrlXMLResponse("/watcher?action_start_share=1" + varg + "&t=" + token, showEnabledWatcher(stringOn));
    }

    function disableWatcherShare(token, current_video_id, stringOff) {
        _gel('shareSpan').style.backgroundColor = "#f66";
        var varg = "";
        if (current_video_id)
            varg = "&v=" + current_video_id;
        getUrlXMLResponse("/watcher?action_stop_share=1" + varg + "&t=" + token, showDisabledWatcher(stringOff));
    }

    function showEnabledWatcher(newString) {
        self.sharing_active = true;
        var img = document.getElementById("sharingImg");
        var img2 = _gel('watchSharingImg');
        removeClass(img, 'activeSharingRed');
        addClass(img, 'activeSharingGreen');
        if (img2) {
            removeClass(img2, 'activeSharingRed');
            addClass(img2, 'activeSharingGreen');
        }
        if (document.getElementById("watch-active-sharing-status-on")) {
            hideDiv('watch-active-sharing-status-off');
            showDiv('watch-active-sharing-status-on');
        }
        alert(newString);
        img.title = newString;
        _gel('shareSpan').style.backgroundColor = "#fff";
        if (document.getElementById("activesharing_start_button")) {
            hideDiv('activesharing_start_button');
            showDiv('activesharing_stop_button');
        }
        if (document.getElementById("activesharing_masthead_start")) {
            hideDiv('activesharing_masthead_start');
            showDiv('activesharing_masthead_stop');
        }
    }

    function showDisabledWatcher(newString) {
        self.sharing_active = false;
        var img = document.getElementById("sharingImg");
        var img2 = document.getElementById("watchSharingImg");
        removeClass(img, 'activeSharingGreen');
        addClass(img, 'activeSharingRed');
        if (img2) {
            removeClass(img2, 'activeSharingGreen');
            addClass(img2, 'activeSharingRed');
        }
        if (document.getElementById("watch-active-sharing-status-on")) {
            hideDiv('watch-active-sharing-status-on');
            showDiv('watch-active-sharing-status-off');
        }
        alert(newString);
        img.title = newString;
        _gel('shareSpan').style.backgroundColor = "#fff";
        if (document.getElementById("activesharing_start_button")) {
            showDiv('activesharing_start_button');
            hideDiv('activesharing_stop_button');
        }
        if (document.getElementById("activesharing_masthead_start")) {
            showDiv('activesharing_masthead_start');
            hideDiv('activesharing_masthead_stop');
        }
    }

/// Inclusive of current node!
    function isPanelExpanded(panel) {
        return hasClass(panel, 'expanded');
    }

    function expandPanel(panel) {
        if (!isPanelExpanded(panel)) {
            addClass(panel, 'expanded');
            fireInlineEvent(panel, 'expanded');
        }
    }

    function collapsePanel(panel) {
        if (isPanelExpanded(panel)) {
            removeClass(panel, 'expanded');
            fireInlineEvent(panel, 'collapsed');
        }
    }

    function togglePanel(panel) {
        if (isPanelExpanded(panel)) {
            collapsePanel(panel);
        } else {
            expandPanel(panel);
        }
    }

    function fireInlineEvent(element, eventName) {
        var target = ref(element);
        if (target[eventName] == null) {
            var attributeName = 'on' + eventName.toLowerCase();
            var attribute = target.attributes.getNamedItem(attributeName);
            if (attribute) {
                target[eventName] = function () {
                    eval(attribute.value);
                }
            }
        }
        if (target[eventName]) target[eventName]();
    }

    function watchSelectTab(tab) {
        var el = tab.parentNode.firstChild;
        while (el) {
            removeClass(el, 'watch-tab-sel');
            el = el.nextSibling;
        }
        addClass(tab, 'watch-tab-sel');
        el = _gel(tab.id + '-body').parentNode.firstChild;
        while (el) {
            removeClass(el, 'watch-tab-sel');
            el = el.nextSibling;
        }
        addClass(_gel(tab.id + '-body'), 'watch-tab-sel')
    }

    function toggleMoreShare(hide, show) {
        hideDiv(hide);
        showDiv(show);
    }

    function processShareVideo(eVideoID, divID, component) {
        shareVideo(eVideoID, divID, component);
        showDiv('aggregationServicesDiv');
        toggleMoreShare('more-options', 'fewer-options');
        return false;
    }

    function shareVideo(videoId, divID, component, opt_blogInfoID) {
        var locale = window.ytLocale || 'en_US';
        var el = _gel(divID);
        var action = 'video_id=' + videoId;
        if (component == 'all' && locale) {
            closeAll(divID);
            toggleDisplay(divID);
            toggleMoreShare('more-options', 'fewer-options');
            action = action + '&locale=' + locale + '&action_get_share_video_component=1';
        } else if (component == 'email') {
            closeMoreShareIfOpen();
            closeAll(divID);
            toggleDisplay(divID);
            action = action + '&action_get_share_message_component=1';
        } else if (component == 'blog' && opt_blogInfoID) {
            closeMoreShareIfOpen();
            closeAll(divID);
            toggleDisplay(divID);
            action = action + '&blog_info_id=' + opt_blogInfoID + '&action_get_share_blog_component=1';
        }
        showDiv('aggregationServicesDiv');
        if (el.style.display != "none") {
            if (el.loaded === undefined) {
                var onSuccess = function () {
                    el.loaded = true;
                    if (opt_blogInfoID) {
                        el.currBlog = opt_blogInfoID;
                    }
                }
                var onFailure = function () {
                    el.loaded = undefined;
                    hideDiv(divID);
                }
                showAjaxDivLoggedIn(divID, '/watch_ajax?' + action, new XMLResponseCallback(onSuccess, onFailure));
            } else if (opt_blogInfoID) {
                if (el.currBlog != opt_blogInfoID) {
                    showAjaxDivLoggedIn(divID, '/watch_ajax?' + action, true);
                    el.currBlog = opt_blogInfoID;
                }
            }
        }
    }

    function closeAll(except) {
        var divs = ['watch-share-video-div', 'watch-share-blog-quick', 'shareMessageQuickDiv', 'shareVideoEmailDiv'];
        for (var i = 0; i < divs.length; i++) {
            if ((divs[i] != except) && (_gel(divs[i]))) {
                var theDiv = _gel(divs[i]);
                if (theDiv) {
                    theDiv.style.display = "none";
                }
            }
        }
    }

    function closeMoreShareIfOpen() {
        if ((_gel('watch-share-video-div').style.display != 'none')) {
            toggleMoreShare('fewer-options', 'more-options');
        }
    }

    function shareVideoEmail(videoId) {
        toggleDisplay('shareVideoEmailDiv');
    }

    function shareVideoClose() {
        if (_gel('watch-share-video-div').style.display != "none") {
            toggleDisplay('watch-share-video-div');
        } else {
            toggleDisplay('shareMessageQuickDiv');
        }
        toggleMoreShare('fewer-options', 'more-options');
        toggleDisplay('shareVideoResult');
        hideDivAfter('shareVideoResult', 3000);
    }

    function shareVideoMessageClose() {
        toggleDisplay('shareVideoMessageDiv');
        toggleDisplay('shareVideoResult');
        hideDivAfter('shareVideoResult', 3000);
    }

    function recordServiceUsage(service_name, video_id) {
        getUrl("/sharing_services?name=" + encodeURIComponent(service_name) + "&v=" + video_id, true);
    }

    var defaultRecipientFieldCount = 2;
    var recipientFieldNamePrefix = "recipient";
    var recipientFieldCount = defaultRecipientFieldCount;
    var lastRecipientFieldId = recipientFieldNamePrefix + recipientFieldCount;
    var maxRecipients = 10;

    function resetRecipients() {
        recipientFieldCount = defaultRecipientFieldCount;
        lastRecipientFieldId = recipientFieldNamePrefix + recipientFieldCount;
    }

    function shareVideoFromFlash() {
        shareVideo(pageVideoId, 'watch-share-video-div', 'all');
        smoothScrollIntoView(_gel("watch-share-video-div"), 20);
    }

    var scrollStep = 100;
    var scrollStepDelay = 50;

    function smoothScrollIntoView(node, padding) {
/// padding is 0 by default
        if (!padding)
            padding = 0;
        smoothScrollIntoViewWorker(node, padding, null);
    }

    function smoothScrollIntoViewWorker(node, padding, lastTop) {
        var nodeTop = getPageOffsetTop(node);
        var currentTop = getBodyScrollTop();
        var deltaTop = Math.min(nodeTop - currentTop - padding, scrollStep);
        window.scrollBy(0, deltaTop);
        if (currentTop != lastTop) {
            window.setTimeout(function () {
                smoothScrollIntoViewWorker(node, padding, currentTop)
            }, scrollStepDelay);
        }
    }

/// based on quircksmode
    function getPageOffsetTop(element) {
        var curtop = 0;
        if (element.offsetParent) {
            curtop = element.offsetTop
            while (element = element.offsetParent) {
                curtop += element.offsetTop
            }
        }
        return curtop;
    }

    function getBodyScrollTop() {
        if (window.innerHeight) {
            return window.pageYOffset;
        } else if (document && document.documentElement && document.documentElement.scrollTop) {
            return document.documentElement.scrollTop;
        } else if (document && document.body) {
            return document.body.scrollTop;
        }
    }

    function addToFaves(formName, event) {
        watchSelectTab(_gel('watch-tab-favorite'));
        if (isLoggedIn) {
            showDiv('watch-add-faves-div');
            _gel('watch-action-favorite-link').blur();
        } else {
            showDiv('addToFavesLogin');
        }
    }

    var gWatchLoading = '';

    function addToPlaylist(videoId, event) {
        watchSelectTab(_gel('watch-tab-playlists'));
        if (isLoggedIn) {
            if (!gWatchLoading) {
                gWatchLoading = _gel('addToPlaylistDiv').innerHTML;
            } else {
                _gel('addToPlaylistDiv').innerHTML = gWatchLoading;
            }
            showDiv('addToPlaylistDiv');
        } else {
            showDiv('addToPlaylistLogin');
        }
    }

    function submitToPlaylist(self) {
        if (!self.form.playlist_id.value) {
            return;
        }
        self.disabled = true;
        
    }

    function addToPlaylistClose() {
        toggleDisplay('addToPlaylistResult');
        var func = function () {
            hideDiv('addToPlaylistResult');
            watchSelectTab(_gel('watch-tab-share'));
        };
        window.setTimeout(func, 3000);
    }

    function reportConcern(videoId, event) {
        closeAllReportConcernsInfo();
        watchSelectTab(_gel('watch-tab-flag'));
        if (isLoggedIn) {
            showDiv('inappropriateVidDiv');
            if (_gel('inappropriateVidDiv').innerHTML.toLowerCase().indexOf('<div') != -1) {
                return;
            }
            
        } else {
            showDiv('inappropriateMsgsLogin');
        }
    }

    function reportConcernCallback() {
        _gel('inappropriateMsgsDiv').innerHTML = _gel('inappropriateMsgs').innerHTML;
        _gel('inappropriateMsgs').innerHTML = '';
        showDiv('inappropriateMsgsDiv');
    }

    function closeAllFlagMoreInfo() {
        var divs = ['flagMoreInfo1', 'flagMoreInfo2', 'flagMoreInfo3', 'flagMoreInfo4', 'flagMoreInfo5', 'flagMoreInfo6', 'flagError'];
        for (var i = 0; i < divs.length; i++) {
            var theDiv = _gel(divs[i]);
            if (theDiv) {
                theDiv.style.display = 'none';
            }
        }
    }

    function closeAllReportConcernsInfo() {
        var divs = ['reportConcernResult1', 'reportConcernResult2', 'reportConcernResult3', 'reportConcernResult4', 'reportConcernResult5'];
        for (var i = 0; i < divs.length; i++) {
            var theDiv = _gel(divs[i]);
            if (theDiv) {
                theDiv.style.display = 'none';
            }
        }
    }

    function flagError(elName, errorText) {
        if (elName) {
            _gel(elName).innerHTML = errorText;
            toggleDisplay(elName);
        }
    }

    function clearSelectionStyles(elName) {
        if (elName) {
            elName.style.backgroundColor = '';
            elName.style.color = '';
        }
    }

    function setSelectionStyles(elName) {
        if (elName) {
            elName.style.backgroundColor = '#6681ba';
            elName.style.color = '#fff';
        }
    }