$(document).ready( function() {
   calendarInit();
});

function calendarInit() {
    $('.calendar_table a').click(function(){        
       dateI = $(this).attr('id');
       $.ajax({
           url: '/calendar/day-add/',
           data:{
               event_id : $('#event_net').attr('event_id'),
               date : dateI
           },
           dataType: 'html',
           type : 'post',
           success: function (data, textStatus) { // вешаем свой обработчик на функцию success
               $('#event_net').html(data);
               // вновь цепляем событие
               calendarInit();
           }
       });
   });
}
