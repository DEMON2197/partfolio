$(document).ready(function(){

	var headerStatic = '.header--static';
	var headerScroll = '.header--scroll';
	$(window).on('scroll load resize', function(){
		var w_top = $(window).scrollTop();
		var e_height = $(headerStatic).outerHeight();
		if (w_top > e_height) {
			$(headerScroll).addClass('active');
		} else {
			$(headerScroll).removeClass('active');
		}
	});
    
    $('.news_year_select').on('change', function(){
		$('.form_news_select').submit();
	 	return false;
	});
    
    $('.fancybox').fancybox(({
        autoScale: true,
        padding: 10,
        helpers: {
            overlay: {
            locked: false
        }
    }}));

    

	$('.js-burger').on('click', function(){
		$('.js-mobile-menu').fadeIn('slow');
	 	return false;
	});

	$('.js-mobile-menu__close').on('click', function(){
		$('.js-mobile-menu').fadeOut('slow');
	 	return false;
	});

	var $sync1 = $('.owl-slider'),
		$sync2 = $('.owl-slider-back'),
		flag = false,
		duration = 300;
	$sync1
		.owlCarousel({
			animateOut: 'fadeOut',
			nav: false,
			dots: false,
			items: 1,
			loop: true,
			margin: 0,
			autoplay: true,
			autoplayTimeout: 7000,
			autoplayHoverPause: true
		})
		.on('changed.owl.carousel', function (e) {
			if (!flag) {
				flag = true;
				$(this).siblings($sync2).trigger('to.owl.carousel', [e.item.index, duration, true]);
				$sync2.trigger('to.owl.carousel', [e.item.index, duration, true]);
				flag = false;
			}
		});
	$sync2
		.owlCarousel({
			animateOut: 'fadeOut',
			nav: false,
			dots: false,
			items: 1,
			loop: true,
			margin: 0
		})
		.on('click', '.owl-item', function () {
			$(this).parents($sync2).siblings($sync1).trigger('to.owl.carousel', [$(this).index(), duration, true]);
		})
			
		
	//=================================================================================
		/*отменать обработчик ссылки*/
			$('body').on("click", "a.upload-card-epr.noactive, a[data-act=deleteFile]", function(e){
				e.preventDefault();
			});
	//=================================================================================	

	
	//===============================================================
	/*скрипт для заявки на аттестацию*/
	//===============================================================
	
	/*Данные о слушателе*/
	var object = $('#atPerson .tpl_form');
	$('#atPerson').append(object.html());
	$('#form_popup3 a[data-act=addPerson]').on("click", function(e){
		e.preventDefault();
		if($('#atPerson .popup_person').length <= 5){
			var num = $('#atPerson .popup_person').length;
			$('input[name^="surname"]', object).attr('name', 'surname'+num);
			$('input[name^="name"]', object).attr('name', 'name'+num);
			$('input[name^="patronymic"]', object).attr('name', 'patronymic'+num);
			$('select[name^="date-att"]', object).attr('name', 'date-att'+num);
			$('.view_izmer_at input[name^="view_izmer"]', object).attr('name', 'view_izmer'+num+'0');
			$('#atPerson').append(object.html());
		}
	});
	$('#form_popup3').on('click', 'a[data-act=deletePerson]', function(e) {
		e.preventDefault();
		if($('#atPerson .popup_person').length > 2){
			$(this).parent().parent().remove();
			var j = $('#atPerson>.popup_person');
			for(var i = 1; i < (j.length+1); i++){
				$('#atPerson>.popup_person input[name^="surname"]').eq(i-1).attr("name", "surname"+i);
				$('#atPerson>.popup_person input[name^="name"]').eq(i-1).attr("name", "name"+i);
				$('#atPerson>.popup_person input[name^="patronymic"]').eq(i-1).attr("name", "patronymic"+i);
				$('#atPerson>.popup_person select[name^="date-att"]').eq(i-1).attr("name", "date-att"+i);
				var numj = j.eq(i-1).children('.view_izmer_at');
				for(var num = 0; num < (numj.length); num++){
					numj.children('input[name^="view_izmer"]').eq(num).attr("name", "view_izmer"+i+""+num);
				}
			}
		}
	});
	// добавить вид средств измерения
	$('#form_popup3').on('click', 'a[data-act=addViewIzm]', function(e) {
		e.preventDefault();
		if($(this).siblings('.view_izmer_at').length < 15){
			var num = $(this).parent().index();
			var numj = $(this).siblings('.view_izmer_at').length;
			$('.view_izmer_at input[name^="view_izmer"]', object).attr('name', 'view_izmer'+num+''+numj);
			$(this).before('<div class="view_izmer_at">'+$('.view_izmer_at', object).html()+'</div>');
		}
	});
	// удалить вид средств измерения
	$('#form_popup3').on('click', 'a[data-act=deleteViewIzm]', function(e) {
		e.preventDefault();
		var num = $(this).parent().parent();
		if($(this).parent().siblings('.view_izmer_at').length > 0){
			$(this).parent().remove();
			var numj = num.children('.view_izmer_at');
			for(var i = 0; i < (numj.length); i++){
				numj.eq(i).children('input[name^="view_izmer"]').attr("name", "view_izmer"+(num.index()+1)+""+i);
			}
		}
	});
	/*Данные о слушателе конец*/
	
	
	$('#form_popup3 input[name="view_face"]').click(function() {
		var val = $(this).val();
		if(val == "individual"){
			$('#form_popup3 input[data-epr="true"]').attr({'disabled':true, 'data-oblig':'false'});
			$('#form-at-upload').addClass('noactive');
			$('#infobox-at, #form_popup3 form input').css('display', 'none');
			
		}else if(val == "enterprise") {$('#form_popup3 input[data-epr="true"]').attr({'disabled':false, 'data-oblig':'true'});
			$('#form-at-upload').removeClass('noactive');
			$('#infobox-at, #form_popup3 form input').css('display', 'block');
		}
	});
	$('#form_popup3 button[type="reset"]').click(function(){
		$('#form_popup3 input[data-epr="true"]').attr({'disabled':false, 'data-oblig':'true'});
		$('#form-at-upload').removeClass('noactive');
		$('#infobox-at, #form_popup3 form input').css('display', 'block');
		var fileName = $('#form_popup3 a[data-act="deleteFile"]').attr('data-info');
		$('#form-at-upload form')[0].reset();
		$.ajax({
			type:'POST',
			url:"/_service/uploader.php",
			data:"act=deleteFile&file-name="+fileName,
			cache:false,
			success:function(data){
				$("#infobox-at").html(data);
			}
		});
	});
	
	$("#form-at-upload").ajaxUpload({
		url : "/_service/uploader.php",
		name: "file",
		onSubmit: function() {
			$('#infobox-at').html('Прицепляем ... ');
		},
		onComplete: function(result) {
			$('#infobox-at').html(result);
			var ext = result.split('.').pop();
			if(ext == "docx" || ext == "doc" || ext == "jpg"){
				$('#infobox-at').append('<a href="#" data-act="deleteFile" data-info="'+result+'">&#10006;</a>');
				$('#form_popup3 input[data-epr="true"]').attr('data-oblig', 'false');
			}
		}
	});
	
	$('#form_popup3').on('click', 'a[data-act="deleteFile"]', function(){
		var fileName = $(this).attr('data-info');
		$('#form-at-upload form')[0].reset();
		$.ajax({
			type:'POST',
			url:"/_service/uploader.php",
			data:"act=deleteFile&file-name="+fileName,
			cache:false,
			success:function(data){
				$("#infobox-at").html(data);
				$('#form_popup3 input[data-epr="true"]').attr('data-oblig', 'true');
			}
		});
	});
	
	//===============================================================
	/*скрипт для заявки на аттестацию*/
	//===============================================================
	
	
	
	//===============================================================
	/*скрипт для заявки на обучение*/
	//===============================================================
	
	/*Данные о слушателе*/
	var object_tr = $('#trPerson .tpl_form');
	$('#trPerson').append(object_tr.html());
	$('#form_popup4 a[data-act=addPerson]').on("click", function(e){
		e.preventDefault();
		if($('#trPerson .popup_person').length <= 5){
			var num = $('#trPerson .popup_person').length;
			$('input[name^="surname"]', object_tr).attr('name', 'surname'+num);
			$('input[name^="name"]', object_tr).attr('name', 'name'+num);
			$('input[name^="patronymic"]', object_tr).attr('name', 'patronymic'+num);
			$('#trPerson').append(object_tr.html());
		}
	});
	$('#form_popup4').on('click', 'a[data-act=deletePerson]', function(e) {
		e.preventDefault();
		if($('#trPerson .popup_person').length > 2){
			$(this).parent().remove();
			var j = $('#trPerson>.popup_person');
			for(var i = 1; i < (j.length+1); i++){
				$('#trPerson>.popup_person input[name^="surname"]').eq(i-1).attr("name", "surname"+i);
				$('#trPerson>.popup_person input[name^="name"]').eq(i-1).attr("name", "name"+i);
				$('#trPerson>.popup_person input[name^="patronymic"]').eq(i-1).attr("name", "patronymic"+i);
			}
		}
	});
	/*Данные о слушателе конец*/
	
	
	$('#form_popup4 input[name="view_face"]').click(function() {
		var val = $(this).val();
		if(val == "individual"){
			$('#form_popup4 input[data-epr="true"]').attr({'disabled':true, 'data-oblig':'false'});
			$('#form-tr-upload').addClass('noactive');
			$('#infobox-tr, #form_popup4 form input').css('display', 'none');
		}else if(val == "enterprise"){
			$('#form_popup4 input[data-epr="true"]').attr({'disabled':false, 'data-oblig':'true'});
			$('#form-tr-upload').removeClass('noactive');
			$('#infobox-tr, #form_popup4 form input').css('display', 'block');
		}
	});
	$('#form_popup4 button[type="reset"]').click(function(){
		$('#form_popup4 input[data-epr="true"]').attr({'disabled':false, 'data-oblig':'true'});
		$('#form-tr-upload').removeClass('noactive');
		$('#infobox-tr, #form_popup4 form input').css('display', 'block');
		var fileName = $('#form_popup4 a[data-act="deleteFile"]').attr('data-info');
		$('#form-tr-upload form')[0].reset();
		$.ajax({
			type:'POST',
			url:"/_service/uploader.php",
			data:"act=deleteFile&file-name="+fileName,
			cache:false,
			success:function(data){
				$("#infobox-tr").html(data);
			}
		});
	});
	
	
	$("#form-tr-upload").ajaxUpload({
		url : "/_service/uploader.php",
		name: "file",
		onSubmit: function() {
			$('#infobox-tr').html('Прицепляем ... ');
		},
		onComplete: function(result) {
			$('#infobox-tr').html(result);
			var ext = result.split('.').pop();
			if(ext == "docx" || ext == "doc" || ext == "jpg"){
				$('#infobox-tr').append('<a href="#" data-act="deleteFile" data-info="'+result+'">&#10006;</a>');
				$('#form_popup4 input[data-epr="true"]').attr('data-oblig', 'false');
			}
		}
	});
	
	$('#form_popup4').on('click', 'a[data-act="deleteFile"]', function(e){
		e.preventDefault();
		var fileName = $(this).attr('data-info');
		$('#form-tr-upload form')[0].reset();
		$.ajax({
			type:'POST',
			url:"/_service/uploader.php",
			data:"act=deleteFile&file-name="+fileName,
			cache:false,
			success:function(data){
				$("#infobox-tr").html(data);
				$('#form_popup4 input[data-epr="true"]').attr('data-oblig', 'true');
			}
		});
			
	});
	//===============================================================
	/*скрипт для заявки на обучение*/
	//===============================================================
	
	
	$('select[name=program_name]').on("change", function(e){
		var val = $(this).serialize();
		$.ajax({
          type: 'POST',
          url: '/_service/d_upload_form_content.php',
          data: val,
          success: function(data) {
            $('select[name=time_study]').html(data);
          },
          error:  function(xhr, str){
			alert('Возникла ошибка: ' + xhr.responseCode);
          }
        });
	});

	
	/*отправка форм*/
	$('.button.btn_form').click(function(){
		event.preventDefault();
		var form_name = $(this).parent().parent();
		var oblig = $('*[data-oblig="true"]', form_name).not('.tpl_form *[data-oblig="true"]');
		var stat = false;
		for(var i = 0; i < oblig.length; i++){
			if(oblig.eq(i).val() == ''){
				oblig.eq(i).css('border-color', 'red');
				stat = true;
			};
		}
		if(stat) alert("Не заполнены некоторые поля, проверьте!"); else{
			var val = form_name.serialize();
			var file = "&file_name="+$('a[data-act="deleteFile"]', form_name).attr('data-info');
			$.post(
				'/_service/form_attestation_training.php',
				val+file+"&form_id="+form_name.attr('id'),
				function (result) {
					if(result == 1){
						form_name.parent().hide();
						$('#popup2').show(200); 
					}
				}
			);
		}
	});
});

