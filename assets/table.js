function format(d) {
    return d.message;
}

jQuery(document).ready(function ($) {
    var security = $('#md-security').data('security');
    var dt = new DataTable('#sy-mailer-log', {
        "idSrc": "id",
        rowId: 'id',
        paging: true,
        "pagingType": "full_numbers",
        "processing": true,
        "serverSide": true,
        scrollX: true,
        "ajax": sy_mailer.ajaxurl + '?action=sy_mailer_get_logs&security=' + security,
        "columns": [
            {
                "class": "details-control",
                "orderable": true,
                "data": "id",
                "defaultContent": "",
            },
            {"data": "to"},
            {"data": "timestamp"},
            {"data": "subject"},
            {"data": "error"},
        ],
        "order": [[0, 'desc']],
    });

    var detailRows = [];

    $('#sy-mailer-log tbody').on('click', 'tr td.details-control', function () {
        var td = $(this).closest('tr');
        var tr = $(this);
        var row = dt.row(td);
        var idx = $.inArray(tr.attr('id'), detailRows);

        if (row.child.isShown()) {
            td.removeClass('details');
            row.child.hide();

            detailRows.splice(idx, 1);
        } else {
            td.addClass('details');
            row.child(format(row.data())).show();

            if (idx === -1) {
                detailRows.push(tr.attr('id'));
            }
        }
    });

    dt.on('draw', function () {
        $.each(detailRows, function (i, id) {
            $('#' + id + ' td.details-control').trigger('click');
        });
    });
});
