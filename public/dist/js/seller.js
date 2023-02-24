$(document).on('click', '.openPopup', function () {
    var HEIGHT_MYMODAL_MAX = $(window).height() - 120;
    if (HEIGHT_MYMODAL_MAX < 100) {
        HEIGHT_MYMODAL_MAX = 100;
    }
    var dataWidth = '1200px';
    var dataHeight = HEIGHT_MYMODAL_MAX + 'px';
    var dataURL = $(this).attr('data-href');
    var attr_w = $(this).attr('data-width');
    if (attr_w !== undefined && attr_w != null && attr_w.length){
        dataWidth = attr_w;
    }
    var title = $(this).attr('title');
    if (!(title !== undefined && title != null && title.length)) {
        title = "Chọn dữ liệu";
    }
    $('#myModal .modal-body').css('height', dataHeight);
    $('#myModal .modal-dialog').css({"width": dataWidth, "max-width": dataWidth});
    $('#myModal #frameSelect').attr('src', dataURL);
    $('#myModal #frameSelect').show();
    $('#myModal .modal-title').html(title);
    $('#myModal #imgView').hide();
    $('#myModal').modal({show: true});
});
