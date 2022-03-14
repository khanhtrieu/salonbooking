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
                    html += '<div class="card shop-service">';
                    html +='<div class="card-body">';
                    html +='<h5 class="card-title">'+row.name+'</h5>';
                    html +='<span>'+row.price+'</span>';
                    html +='</div>';
                    html+='</div>';
                    $('#shop-products').append(html);
                });
            });
        });

        $(document).on('click', '.card-body', function (e) {
            e.preventDefault();
            let url = $(this).data('url');
            $.get(url, function (data) {
                $('#avai-times').html('');
                $(data.test).each(function(index,row){

                    let html = '';
                    html += '<div class="card-title" data-url="'+data.url+'">';
                    html +='<div class="card-text">';
                    html +='<span>'+row.name+'<span>';
                    html +='</div>';
                    html+='</div>';
                    $('#avai-times').append(html);
                });
            });
        });


    }
});