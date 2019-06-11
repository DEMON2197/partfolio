jQuery(function($) {
	//Pretty Photo
	$("a[rel^='prettyPhoto']").prettyPhoto({
		social_tools: false
	});

        

	//Ajax contact
	var form = $('.contact-form');
		form.submit(function () {
			$this = $(this);
			$.post($(this).attr('action'), function(data) {
			$this.prev().text(data.message).fadeIn().delay(3000).fadeOut();
		},'json');
		return false;
	});

	//Goto Top
	$('.gototop').click(function(event) {
		 event.preventDefault();
		 $('html, body').animate({
			 scrollTop: $("body").offset().top
		 }, 500);
	});	
	//End goto top	

    // ������� �� ������ ����� "���������"
    $('.form-wrapper .btn-primary').on('click', function(){
        $(this).closest('.form-wrapper').find('form.browser-validate').each(function(){this.noValidate = true;});
        $(this).closest('.form-wrapper').find('form').submit();
    });

    // ������������� ��������� ����������� �����
    $('.otherModal').click(function(){
        $('#otherModal h4:first').text($(this).data('formTitle'));
        $('#otherModal .form-subject').val($(this).data('formTitle'));
    })

    // ������� �����
    $('.modal').on('hidden', function(){
        // ��� �������� ���������� ���� ������� ��� ����� � �� � ���������� �� ���������
        var forms = $(this).find('form');
        if(forms.length){
            forms.each(function(){
                formReset(this);
            });
        }
    });

    // �������� ��������� �� ���������� ����
    $("form.browser-validate").on('submit', function (event) {
        // �������� ��������������� ������ ������� � ���� �����
        event = (event ? event : window.event);

        var
        form = (event.target ? event.target : event.srcElement),
        f, field;
        var formValid = true;

        for (f = 0; f < form.elements.length; f++) {
            field = form.elements[f];
            
            // ���������� ������, ��������� ���� � �.�.,
            if (field.nodeName !== "INPUT" &&
                field.nodeName !== "TEXTAREA" &&
                field.nodeName !== "SELECT") continue;

            
            // ��������� ���� ���������� (����������) ����������
            field.value = field.value.trim();
            field.checkValidity();

            if (field.validity.valid) {
                // ��� �� - ���� �������
                delErrorField(field);
            } else {
                setErrorField(field);
                shake($(field));
                formValid = false;
            }
        }

        // ����� �� ������� - ����������� ������
        if(!formValid){
            event.preventDefault();
            return false;
        }

        var fd = new FormData(form);
        var jForm = $(form);

        $.ajax({
            type: jForm.attr('method'),
            url: jForm.attr('action'),
            processData: false,
            contentType: false,
            data: fd ,
			beforeSend: function(){
                
			},
			success: function(data){
								
                $('<audio id="chatAudio"><source src="/static/audio/Notification.wav" type="audio/wav"></audio>').appendTo('body');
                $('#chatAudio')[0].play();
                if(jForm.closest('.modal').length){
                    jForm.closest('.modal').find('.close, .modal-header i.icon-remove').click();
                } else {
                    formReset(form);
                }
                $('#modal-success').modal('show', 1000);                
            }
        });
        return false;
    });


    setTimeout("$('#clients .next').click()", 500);
    setTimeout("$('#clients .next').click()", 2000);

});

/**
 * ���������� ��������� �����
 * @param Object form
 */
function formReset(form){
    form.reset();
    $(form).find('.control-group').removeClass('error').find('.help-inline').text('');
}

/**
 * @param Object field
 * @return string ���������� ��������� �� ������ ��������������� ��� ����� ����
 */
function getErrorMessage(field){
    if($(field).attr('title')){
        return $(field).attr('title');
    }

    return field.validationMessage;
}

/**
 * @param Object field ������������� ��������� �� ������ ��� ����
 */
function setErrorField(field){
    f = $(field);
    f.closest('.control-group').addClass('error').find('.help-inline').text(getErrorMessage(field));
}

/**
 * @param Object field ������� ��������� �� ������ ��� ����
 */
function delErrorField(field){
    f = $(field);
    f.closest('.control-group').removeClass('error').find('.help-inline').text('');
}

// shake element
function shake(elem){
    $('<audio id="chatAudio"><source src="/static/audio/Error.wav" type="audio/wav"></audio>').appendTo('body');
    $('#chatAudio')[0].play();

    var left = parseInt(elem.css('margin-left'));
    var displacement = -5;
    for(var i =0; i<5; i++){
        var loc_left = left+(displacement+(2*Math.abs(displacement))*(i%2));
        elem.animate({'margin-left': loc_left}, 30, function(){if(i==5){elem.css('margin-left', '0px');}});
    }
}


