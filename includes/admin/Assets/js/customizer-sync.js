wp.customize('corbidev_primary_color', function(value) {
    value.bind(function(newval) {
        document.documentElement
            .style.setProperty('--corbidev-primary', newval);
    });
});
