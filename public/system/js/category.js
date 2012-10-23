$(document).ready( function() {
   page_init();   
});

function page_init() {
    mindfly_tree();
    mindfly_confirm();
}

function mindfly_confirm() {
   $(".delete_category_link").click(function(){
       var href = $(this).attr('url');
       jConfirm('Вы действительно хотите удалить категорию?',
                'Удаление категории',
                function(r) {
                    if (r) {
                        window.location.href= href;
                    }
        });
       return false;
   });

   $(".ative_category_link").click(function(){
       var href = $(this).attr('url');
       jConfirm('Вы действительно восстановить удаленную категорию?',
                'Восстановление категории',
                function(r) {
                    if (r) {
                        window.location.href= href;
                    }
        });
       return false;
   });


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


function mindfly_tree() {
    $("#categoryList").jstree({        
        "plugins" : [ "themes", "html_data" ],
        "themes" : {
            "icons" : false
        }
    });
    jQuery.jstree._reference($('#categoryList')).open_all();
}


 function tb_remove() {
        $.ajax({
           url: '/calendar/ajax-tree',
           dataType: 'html',
           success: function (data, textStatus) { // вешаем свой обработчик на функцию success
             $('#categoryList').html(data);
             page_init();
             tb_init('a.thickbox, area.thickbox, input.thickbox');
            }
        });
        
 	$("#TB_imageOff").unbind("click");
	$("#TB_closeWindowButton").unbind("click");
	$("#TB_window").fadeOut("fast",function(){$('#TB_window,#TB_overlay,#TB_HideSelect').trigger("unload").unbind().remove();});
	$("#TB_load").remove();
	if (typeof document.body.style.maxHeight == "undefined") {//if IE 6
		$("body","html").css({height: "auto", width: "auto"});
		$("html").css("overflow","");
	}
	document.onkeydown = "";
	document.onkeyup = "";
	return false;
}