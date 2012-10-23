function showPasswordCheckbox(prefix, value) {
    // должна быть запущена только одна копия функции
    if (!window.checkActive) {
        retrun;
    }
    window.checkActive = false;
    if (value) {
         // show
         $('#'+prefix+'_password').show();
         $('#'+prefix+'_password').val($('#'+prefix+'_real_password').val());
         $('#'+prefix+'_real_password').hide();

    } else {
         // hide
         $('#'+prefix+'_real_password').show();
         $('#'+prefix+'_real_password').val($('#'+prefix+'_password').val());
         $('#'+prefix+'_password').hide();     
    }
    window.checkActive = true;
}

function showPasswordCheckboxInput(prefix) {
    $('#'+prefix+'_password-element').append('<input id="'+prefix+'_real_password" type="password" class="text">');
    showPasswordCheckbox(prefix, 1);
    $('#'+prefix+'_show_password').click(function(){        
        showPasswordCheckbox(prefix, $('#'+prefix+'_show_password').is(':checked'));
    });   
}


$(document).ready(function() {
    $('#login').focus();
    $('input[title!=""]').hint();
    window.checkActive = true;
    showPasswordCheckboxInput('auth');
    showPasswordCheckboxInput('reg');       
    


    $('#registration').click(function(){
        // если пользователь нажал зарегистрироваться
        if ($('#reg_show_password').is(':checked')) {
            //если стоит гоалочка показать пароль
             $('#reg_hidden_password').val($('#reg_password').val());
         } else {
             $('#reg_hidden_password').val($('#reg_real_password').val());
         }
         
         // если пароль пуст - то следует спросить его,
         // действительно ли он хочет пустой пароль
         if ($('#reg_hidden_password').val() ==  '') {
             jConfirm('Вы действительно хотите зарегистрироваться без пароля?',
                      'Регистрация',
                 function(r) {
                     if (r) {
                         $('#formRegistration').submit();
                     }
              });
         } else {
             $('#formRegistration').submit();
         }
    });


    $('#login').click(function(){
        // если пользователь нажал войти
        if ($('#auth_show_password').is(':checked')) {
            //если стоит гоалочка показать пароль
             $('#auth_hidden_password').val($('#auth_password').val());
         } else {
             $('#auth_hidden_password').val($('#auth_real_password').val());
         }
         $('#formAutorization').submit(); 

    });

  
   
   
});
