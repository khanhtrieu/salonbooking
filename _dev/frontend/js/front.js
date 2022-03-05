import 'bootstrap';

$(document).ready(function () {
    if ($('#booking').length > 0) {
        $(document).on('click', '.booking-shop', function (e) {
            e.preventDefault();
            let url = $(this).data('url');
            $.get(url, function (data) {
                $('#shop-products').html('');
                $(data).each(function(index,row){

                    let html = '';
                    html += '<div class="card">';
                    html +='<div class="card-body">';
                    html +='<h5 class="card-title">'+row.name+'</h5>';
                    html +='</div>';
                    html+='</div>';
                    $('#shop-products').append(html);
                });
            });
        });
    }
});