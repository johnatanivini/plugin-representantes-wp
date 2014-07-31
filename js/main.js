jQuery(function($) {
    var tabs$ = $(".nav-tabs a");

    $(window).on("hashchange", function() {
        var hash = window.location.hash, // get current hash
                menu_item$ = tabs$.filter("[href=" + hash + "]"); // get the menu element

        menu_item$.tab("show"); // call bootstrap to show the tab
    }).trigger("hashchange");
})


