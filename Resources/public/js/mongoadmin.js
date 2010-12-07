$(function () {
    $('.size-on-disk').each(function (index, elem) {
        var value = elem.innerHTML;
        var suffixes = ['b', 'Kb', 'Mb', 'Gb', 'Tb']
        var i = 0;

        while (value > 1024) {
            value /= 1024;
            i++;
        }

        elem.innerHTML = value + " " + suffixes[i];
    });
});