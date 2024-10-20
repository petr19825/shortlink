

// генерация токена
function randomString(length) {
	var chars="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"
    var result = '';
    for (var i = length; i > 0; --i) result += chars[Math.floor(Math.random() * chars.length)];
    return result;
}

// копирование короткого url
function copy(el){
	var links=$(el).attr("atrlink");
    var input = $('<textarea>').val(links).appendTo('body').select();
    document.execCommand('copy');
    input.remove();
	alert("ссылка '"+links+"' успешно скопирована в буфер обмена!");
}

// отправка данных на сервер(токен и url)
function request_data(){
	var b=check_form();
	if (b){
	$(".messenger").html("");
	event.preventDefault();
    var $form = $(this);
	var urlf=$(".inputlink").val();
	var token=randomString(8);
	
	
    $.ajax({
        url: "include/request.php",
        type: 'POST',
		cache: false,
        data: {myurl: urlf, mytoken:token},
        success: function(response) {
			 obn();
			 
        },
        complete: function() {
            
        },
		
		 error: function (error) {
         
        }
    });
   }
   else{
	   
   }
   
  }

// проверка полей на пустоту             
function check_form()  {// обрабатываем отправку формы  
    event.preventDefault();
	var field = new Array("link");//поля обязательные		
	var error=0; // индекс ошибки
	$("form").find(":input").each(function() {// проверяем каждое поле в форме
		for(var i=0;i<field.length;i++){ // если поле присутствует в списке обязательных
			if($(this).attr("name")==field[i]){ //проверяем поле формы на пустоту
			   
				if(!$(this).val()){// если в поле пустое
					$(this).css('border', 'red 1px solid');// устанавливаем рамку красного цвета
					error=1;// определяем индекс ошибки      
											   
				}
				else{
					$(this).css('border', 'gray 1px solid');// устанавливаем рамку обычного цвета
				}
			   
			}              
		}
   })
   
	if(error==0){ // если ошибок нет то отправляем данные
		return true;
	}
	else{
	if(error==1) var err_text = "Не все обязательные поля заполнены!";
		$(".messenger").html(err_text);
		$(".messenger").fadeIn("slow");
	return false; //если в форме встретились ошибки , не  позволяем отослать данные на сервер.
	}
}

// обновляем нащ блок со ссылками на главной странице
function obn () {
      $.ajax({
                url: "include/show.php",
                type: 'GET',
                cache: false,
                success: function(data) {
                    $("#show").html(data);
                },
                error: function(error) {
                }
				
            });
	}
 