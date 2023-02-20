var news = {
    openUpload: function () {
        dialog_filemanager.open();
    },
    removeAvatar: function (external) {
        $(external).parent().find('.' + external.id).html('');
        $(external).val('');
    }
};
var dialog_filemanager = {
    open: function () {
        var self = this;
        tinyDom = undefined;
        self.dlog = $('<iframe src="' + web_url + '/file/dialogimg?editor=image&field_id=image&type=2&lang=&subfolder=" style="width:900px"></iframe>').dialog({
            title: 'File Manager',
            modal: true,
            classes: 'filemanager',
            width: 900,
            height: 600,
        });
        self.dlog.width(870);
        return self.dlog;
    },
    close: function () {
        if (typeof this.dlog != "undefined")
            this.dlog.dialog('close');
    }
};
