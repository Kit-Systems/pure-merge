function licenseNotificator(url) {
    $.ajax({
        url: url,
        dataType: 'JSON',
        success: function (json) {
            if (json.message !== undefined) {
                $.notify(json.message, {
                    className:json.type,
                    globalPosition:'bottom right',
                    showAnimation:'fadeIn',
                    hideAnimation: 'fadeOut',
                    autoHide: false
                });
            }
        }
    });
}
