$(document).ready( function() {
   page_init();
});

function page_init() {
    mindfly_confirm();
}

function mindfly_confirm() {
   $(".delete_event_link").click(function(){
       var href = $(this).attr('url');
       jConfirm('Вы действительно хотите удалить событие?',
                'Удаление события',
                function(r) {
                    if (r) {
                        window.location.href= href;
                    }
        });
       return false;
   });

   $(".ative_event_link").click(function(){
       var href = $(this).attr('url');
       jConfirm('Вы действительно восстановить удаленное событие?',
                'Восстановление события',
                function(r) {
                    if (r) {
                        window.location.href= href;
                    }
        });
       return false;
   });
}
