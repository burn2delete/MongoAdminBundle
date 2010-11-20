$(function () {
    $('#navigation-tree').treeview({
        persist: "cookie",
        cookieId: "mongoadmin-tree",
        animated: "fast",
        collapsed: true
    });
});