<!-- На аттестацию-->
<div class="form_style">
	<form id="form_popup3">
		<div class="scroll">
				<span style="font-size: medium; color: red;">* - Обязательные поля</span>
				<fieldset>
					<legend>
							<strong>
								<span style="font-size: medium;">Данные о слушателе: *</span>
							</strong>
							<a href="" data-act="addPerson"><span style="font-size: medium;">Добавить+</span></a>
						</legend> 
					<div id="atPerson">
					
						<div style="display: none" class="tpl_form">
							<div class="popup_person container">
								<div>
									<span style="font-size: small;">Фамилия:<br></span>
									<input style="font-size: small;" type="text" name="surname1" data-oblig="true">
								</div>
								<div>
									<span style="font-size: small;">Имя:<br></span>
									<input style="font-size: small;" type="text" name="name1" data-oblig="true">
								</div>
								<div>
									<span style="font-size: small;">Отчество:<br></span>
									<input style="font-size: small;" type="text" name="patronymic1" data-oblig="true">
								</div>
								<div>
									<span style="font-size: small;">Дата аттестации:<br></span>
									<select style="font-size: small; width: 80%;" name="date-att1" data-oblig="true">
										<option value="value1" selected>Value 1</option>
										<option value="value2">Value 2</option>
										<option value="value3">Value 3</option>
									</select>
									<a href="" data-act="deletePerson">&#10006;</a>
								</div><br>
								<div style="margin-top: 20px; margin-left: 20px; width: 100%;">
									<span style="font-size: small;">Вид(ы) средств измерений:<br></span>
								</div>
								<div class="view_izmer_at">
									<input name="view_izmer10" data-oblig="true" style="width: 70%;">
									<a href="" data-act="deleteViewIzm">&#10006;</a>
								</div>
								<a href="" data-act="addViewIzm">Добавить &#8853;</a>
							</div>
							
						</div>
					</div>
				</fieldset><br>
				
				<fieldset>
					<legend>
						<strong>
							<span style="font-size: medium;">Данные предприятия / физ. лица: *</span>
						</strong>
					</legend>
					<div id="atData">
						<label><input name="view_face" type="radio" checked value="enterprise">Предприятие /</label>
						<label><input name="view_face" type="radio" value="individual">Физическое лицо</label><br>
					</div><br>
					<!--<span style="font-size: small;">Организация (полное название):<br></span>
					<input style="font-size: small; width: 100%;" type="text" name="fullcompany"><br>
					<span style="font-size: small;">Организация (сокращенное название):<br></span>
					<input style="font-size: small; width: 100%;" type="text" name="smallcompany"><br>-->
					<span style="font-size: small;"><a href="" class="upload-card-epr" id="form-at-upload">Прикрепить карточку предприятия</a> (.doc, .png, не более 2МБ) <div id="infobox-at" style="width-max: 100%; width: 250px;"></div> и/или заполнить поля<br><br></span>
					<span style="font-size: small;">Юридический адрес предприятия (с почтовым индексом):<br></span>
					<input style="font-size: small; width: 100%;" type="text" name="uradress" data-oblig="true" data-epr="true"><br>
					<span style="font-size: small;">Почтовый адрес предприятия (с почтовым индексом):<br></span>
					<input style="font-size: small; width: 100%;" type="text" name="postadress" data-oblig="true" data-epr="true"><br>
					<span style="font-size: small;">ОГРН:<br></span>
					<input style="font-size: small; width: 100%;" type="text" name="ogrn" data-oblig="true" data-epr="true"><br>
					<span style="font-size: small;">ИНН:<br></span>
					<input style="font-size: small; width: 100%;" type="text" name="inn" data-oblig="true" data-epr="true"><br>
					<span style="font-size: small;">КПП:<br></span>
					<input style="font-size: small; width: 100%;" type="text" name="kpp" data-oblig="true" data-epr="true"><br><br>

						<strong>
							<span style="font-size: medium;">Банковские реквизиты: *<br></span>
						</strong>

					<span style="font-size: small;">Расчетный счет:<br></span>
					<input style="font-size: small; width: 100%;" type="text" name="rschet" data-oblig="true" data-epr="true"><br>
					<span style="font-size: small;">Банк:<br></span>
					<input style="font-size: small; width: 100%;" type="text" name="bank" data-oblig="true" data-epr="true"><br>
					<span style="font-size: small;">Кор. счет:<br></span>
					<input style="font-size: small; width: 100%;" type="text" name="kschet" data-oblig="true" data-epr="true"><br>
					<span style="font-size: small;">БИК:<br></span>
					<input style="font-size: small; width: 100%;" type="text" name="bik" data-oblig="true" data-epr="true"><br><br>

						<strong>
							<span style="font-size: medium;">Лицо, подписывающее договор: *<br></span>
						</strong>
						
					<span style="font-size: small;">Должность:<br></span>
					<input style="font-size: small; width: 100%;" type="text" name="ddolzhnost" data-oblig="true"><br>
					<span style="font-size: small;">Фамилия, имя, отчество:<br></span>
					<input style="font-size: small; width: 100%;" type="text" name="dfio" data-oblig="true"><br>
					<span style="font-size: small;">Основание на подписание договора</span> (Устав, Положение, Доверенность №___от___):<br>
					<input style="font-size: small; width: 100%;" type="text" name="dosnovanie" data-oblig="true">
				</fieldset><br>
				<fieldset>
					<legend>
						<strong>
							<span style="font-size: medium;">Контактное лицо (Ф.И.О.; должность; телефон с указанием кода города; E-mail): *</span>
						</strong>
					</legend><br>
					<span style="font-size: small;">Должность:<br></span>
					<input style="font-size: small; width: 100%;" type="text" name="kont_dolzhnost" data-oblig="true"><br>
					<span style="font-size: small;">Ф.И.О.:<br></span>
					<input style="font-size: small; width: 100%;" type="text" name="kont_fio" data-oblig="true"><br>
					<span style="font-size: small;">Телефон (с указанием кода города):<br></span>
					<input style="font-size: small; width: 100%;" type="tel" name="kont_phone" data-oblig="true"><br>
					<span style="font-size: small;">E-mail:<br></span>
					<input style="font-size: small; width: 100%;" type="email" name="kont_email" data-oblig="true"><br>
				</fieldset>
		</div>
		<div style="margin-top: 20px;">
			<button class="button btn_form">ОТПРАВИТЬ</button>
			<button class="button" type="reset" style="margin-left: 25px; background-color: #ccc; border-color: #ccc;">Сбросить</button>
		</div>
	</form>
</div>
<!-- /На аттестацию-->