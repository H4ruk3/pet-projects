<script src='https://www.google.com/recaptcha/api.js'></script>
<?= $this->Html->script('/js/auth.js') ?>
	<div class=dialog>
		<p class="logo">
			<img src="/img/logo_site.png" />
		</p>
		<div id="registration" class="regblock">
			<H1>Восстагновление пароля</H1>
			<form id="register" method="post">
				<div class="password">
					<input type="password" reqired minlength="5" id="password" name="password" placeholder="Введите новый парооль" /> <span id="eye" onclick="togglepassw()" class="glyphicon glyphicon-eye-open eye"></span>
				</div>
				<p>Минимум 5 символов</p>
				<input type="submit" name="submit" value="Изменить" onclick="checkValidity('#register')">
			</form>
			<div class="alert alert-danger" role="alert" style="opacity: 0">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<strong>Ошибка!</strong>
				<div id="message"></div>
			</div>
		</div>
	</div>
