/* этот файл подключается всегда */

/**
 * Отправка формы по ctrl+enter
 * что бы сработало нужно добавить 
 * $this->setAttrib('onkeypress', 'ctrlEnter(event, this);');
 */
function ctrlEnter(event, formElem)  {
    if((event.ctrlKey) && ((event.keyCode == 0xA)||(event.keyCode == 0xD)))  {         
         $("button.submit").click();
         $("input[type=submit]").click();
    }
}

$(document).ready(function(){
});












