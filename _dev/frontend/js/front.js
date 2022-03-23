import 'bootstrap';
import 'jquery';
import 'jquery-ui';
require('jquery-ui/ui/widgets/datepicker');

$(document).ready(function () {
    
    if ($('#booking').length > 0) {
        $(document).on('click', '.booking-shop', function (e) {
            e.preventDefault();
            let url = $(this).data('url');
            $.get(url, function (data) {
                $('#shop-products').html('');
                $(data.shop).each(function(index,row){

                    let html = '';
                    html += '<div class="card shop-service" data-url="'+data.url+'" data-shopid="'+row.shop_id+'" data-serviceid="'+row.service_id+'">';
                    html +='<div class="card-body">';
                    html +='<h5 class="card-title">'+row.name+'</h5>';
                    html +='<span>Price: $'+row.price+'</span>';
                    html +='</div>';
                    html+='</div>';
                    $('#shop-products').append(html);
                });
            });
        });

        $(document).on('click', '.shop-service', function (e) {
            e.preventDefault();
            //let shop_id = $(this).data('shopid');
            let service_id = $(this).data('serviceid');
            let url = $(this).data('url');
            $.get(url,{'id_service': service_id} , function (data) {
                $('#work-calendar').html('');
                data = [1, 2, 3];
                console.log(data);
                let html = '';
                    html += '<div class="card-title"';
                    html +='<div class="card-text">';
                    //html +='<span>'+row.name+'<span>';
                    html +='<p>Schedule an appointment: <input type="text" id="datepicker"></p>';
                    html +='</div>';
                    html+='</div>';
                    $('#work-calendar').append(html);
                // $(data).each(function(index,row){
                //     let html = '';
                //     html += '<div class="card-title"';
                //     html +='<div class="card-text">';
                //     //html +='<span>'+row.name+'<span>';
                //     html +='<p>Date: <input type="text" id="datepicker"></p>';
                //     html +='</div>';
                //     html+='</div>';
                //     $('#avai-times').append(html);
                // });
            });
        });
        
            //var special_date = ["03/22/2022", "03/24/2022"];
            //var date_test = "03/22/2022";
          $( "#datepicker" ).datepicker({
              minDate: 0,
              maxDate: "1M",
      
            //   beforeShowDay: function(date){
            //       let datestring = (date.getMonth()+1) +'/'+ date.getDate() + '/' + date.getFullYear();
            //       console.log(datestring);
            //       if (date in special_date){
            //         return [false];
            //       }
            //       return [true];
            //   }
          });
          
      ;


    }
});