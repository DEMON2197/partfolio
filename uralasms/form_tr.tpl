{config_load file=settings.conf section=$smarty.globals.site_design}
<!-- �� ��������-->
<div class="form_style">
	<form id="form_popup4">
		<div class="scroll">
				<span style="font-size: medium; color: red;">* - ������������ ����</span>
				<fieldset>
					<legend>
						<strong>
							<span style="font-size: medium;">������������ ���������: *</span>
						</strong>	
					</legend> 
					<select name="program_name" style="width: 100%; ">
						{assign var=reiteration value=''}
						{assign var=assoc value=$graphic_array[0][14]}
						{section name=customer loop=$graphic_array}
							{if $smarty.section.customer.index eq 0}
								<optgroup label="{$graphic_array[customer][14]}">
							{/if}
							{if $graphic_array[customer][14]|cat:$graphic_array[customer][2] neq $reiteration}
								{if $assoc neq $graphic_array[customer][14]}
									</optgroup>
									<optgroup label="{$graphic_array[customer][14]}">
									{assign var=assoc value=$graphic_array[customer][14]}
								{/if}
								<option value="{$graphic_array[customer][14]} {$graphic_array[customer][2]}">
										{$graphic_array[customer][2]|truncate:170:"...":true}
								</option>
								{assign var=reiteration value=$graphic_array[customer][14]|cat:$graphic_array[customer][2]}
							{/if}
							{if $smarty.section.customer.iteration eq $graphic_array|@count}
								</optgroup>
							{/if}
						{/section}
					</select>
				</fieldset><br>
				<fieldset>
					<legend>
						<strong>
							<span style="font-size: medium;">������ ��������: *</span>
						</strong>	
					</legend> 
					<select name="time_study">
						{section name=customer loop=$period}
							<option value="{$period[customer]}">{$period[customer]}</option>
						{/section}
					</select>
				</fieldset><br>
				<fieldset>
					<legend>
							<strong>
								<span style="font-size: medium;">������ � ���������: *</span>
							</strong>
							<a href="" data-act="addPerson"><span style="font-size: medium;">��������+</span></a>
						</legend> 
						<div style="display: none"></div>
					<div id="trPerson">
						<div style="display: none" class="tpl_form">
							<div class="popup_person">
								<div>
									<span style="font-size: small;">�������:<br></span>
									<input style="font-size: small;" type="text" name="surname1" data-oblig="true">
								</div>
								<div>
									<span style="font-size: small;">���:<br></span>
									<input style="font-size: small;" type="text" name="name1" data-oblig="true">
								</div>
								<div>
									<span style="font-size: small;">��������:<br></span>
									<input style="font-size: small;" type="text" name="patronymic1" data-oblig="true">
								</div>
								<a href="" data-act="deletePerson">&#10006;</a>
							</div>
						</div>
						
						
					</div>
				</fieldset><br>
				
				<fieldset>
					<legend>
						<strong>
							<span style="font-size: medium;">���������:</span>
						</strong>
					</legend> 
					<span style="font-size: large;">�� ������ ��������</span>
				</fieldset><br>
				<fieldset>
					<legend>
						<strong>
							<span style="font-size: medium;">�����:</span>
						</strong>
					</legend> 
					<span style="font-size: large;">�� ����</span>
				</fieldset><br>
				<fieldset>
					<legend>
						<strong>
							<span style="font-size: medium;">������ ����������� / ���. ����: *</span>
						</strong>
					</legend>
					<div id="atData">
						<label><input name="view_face" type="radio" checked value="enterprise">����������� /</label>
						<label><input name="view_face" type="radio" value="individual">���������� ����</label><br>
					</div><br>
					<span style="font-size: small;"><a href="" class="upload-card-epr" id="form-tr-upload" style="coursor: pointer;">���������� �������� �����������</a> (.doc, .png, �� ����� 2��) <div id="infobox-tr" style="width-max: 100%; width: 250px;"></div> �/��� ��������� ����<br><br></span>
					<span style="font-size: small;">����������� ����� ����������� (� �������� ��������):<br></span>
					<input style="font-size: small; width: 100%;" type="text" name="uradress" data-oblig="true" data-epr="true"><br>
					<span style="font-size: small;">�������� ����� ����������� (� �������� ��������):<br></span>
					<input style="font-size: small; width: 100%;" type="text" name="postadress" data-oblig="true" data-epr="true"><br>
					<span style="font-size: small;">����:<br></span>
					<input style="font-size: small; width: 100%;" type="text" name="ogrn" data-oblig="true" data-epr="true"><br>
					<span style="font-size: small;">���:<br></span>
					<input style="font-size: small; width: 100%;" type="text" name="inn" data-oblig="true" data-epr="true"><br>
					<span style="font-size: small;">���:<br></span>
					<input style="font-size: small; width: 100%;" type="text" name="kpp" data-oblig="true" data-epr="true"><br><br>

						<strong>
							<span style="font-size: medium;">���������� ���������: *<br></span>
						</strong>

					<span style="font-size: small;">��������� ����:<br></span>
					<input style="font-size: small; width: 100%;" type="text" name="rschet" data-oblig="true" data-epr="true"><br>
					<span style="font-size: small;">����:<br></span>
					<input style="font-size: small; width: 100%;" type="text" name="bank" data-oblig="true" data-epr="true"><br>
					<span style="font-size: small;">���. ����:<br></span>
					<input style="font-size: small; width: 100%;" type="text" name="kschet" data-oblig="true" data-epr="true"><br>
					<span style="font-size: small;">���:<br></span>
					<input style="font-size: small; width: 100%;" type="text" name="bik" data-oblig="true" data-epr="true"><br><br>

						<strong>
							<span style="font-size: medium;">����, ������������� �������: *<br></span>
						</strong>
						
					<span style="font-size: small;">���������:<br></span>
					<input style="font-size: small; width: 100%;" type="text" name="ddolzhnost" data-oblig="true"><br>
					<span style="font-size: small;">�������, ���, ��������:<br></span>
					<input style="font-size: small; width: 100%;" type="text" name="dfio" data-oblig="true"><br>
					<span style="font-size: small;">��������� �� ���������� ��������</span> (�����, ���������, ������������ �___��___):<br>
					<input style="font-size: small; width: 100%;" type="text" name="dosnovanie" data-oblig="true">
				</fieldset><br>
				<fieldset>
					<legend>
						<strong>
							<span style="font-size: medium;">���������� ���� (�.�.�.; ���������; ������� � ��������� ���� ������; E-mail): *</span>
						</strong>
					</legend><br>
					<span style="font-size: small;">���������:<br></span>
					<input style="font-size: small; width: 100%;" type="text" name="kont_dolzhnost" data-oblig="true"><br>
					<span style="font-size: small;">�.�.�.:<br></span>
					<input style="font-size: small; width: 100%;" type="text" name="kont_fio" data-oblig="true"><br>
					<span style="font-size: small;">������� (� ��������� ���� ������):<br></span>
					<input style="font-size: small; width: 100%;" type="tel" name="kont_phone" data-oblig="true"><br>
					<span style="font-size: small;">E-mail:<br></span>
					<input style="font-size: small; width: 100%;" type="email" name="kont_email" data-oblig="true"><br>
				</fieldset>
		</div>
		<div style="margin-top: 20px;">
			<button class="button btn_form">���������</button>
			<button class="button" type="reset" style="margin-left: 25px; background-color: #ccc; border-color: #ccc;">��������</button>
		</div>
	</form>
</div>
<!-- /�� ��������-->