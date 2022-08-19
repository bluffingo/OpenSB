document.addEventListener("DOMContentLoaded", function(event) {
    console.log("sb 111 js loaded");

    btns = document.getElementsByClassName("sb-unimplemented");
    for (var i = 0; i < btns.length; i++) {
        btns[i].addEventListener("click", function () {
            Bulma().alert({
                type: 'info',
                title: 'This feature is not implemented',
                body: '111 is incomplete. For a complete experience of squareBracket, Finalium should be used for ' +
                    'the time being.',
                confirm: 'OK',
            });
        });
    }

});