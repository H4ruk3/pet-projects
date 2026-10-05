
	<?= $this->Html->css('redesign/auth.css') ?>
	<!-- <script src='https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit'></script> -->
	<div class=dialog>
		<p class="logo">
			<img src="/img/logo_site.png" />
		</p>
		<?= $this->Flash->render() ?>
		<div id="registration" class="regblock" style="<?= isset($isreg)?"":"display: none"?>">
			<H1>Регистрация</H1>
			<form id="register" method="post" onsubmit="return register()">
				<div class="form-group has-feedback">
				<? if (isset($role)) { ?>
				<input type="hidden" name="role" value="<?= isset($role) ? $role : 'user' ?>" />
				<? } ?>
				<input class="form-control" type="email" name="username" placeholder="Введите логин" />
				<label class="form-label">E-mail адрес (логин)</label>
				</div>
				<p>Логином для входа в систему является ваш e-mail адрес</p>
				<div class="form-group has-feedback password">
				<input type="password" class="form-control" id="password" name="password" placeholder="Вы не заполнили поле пароля" /> <span id="eye" onclick="togglepassw(this, '#password')" class="glyphicon glyphicon-eye-open eye"></span>
				<label class="form-label">Пароль</label>
			</div>
				<p>Минимум 5 символов</p>
				<p class="center">
					<input type="hidden" name="g-recaptcha-response" value="skip" />
				</p>
				<p>
				<input type="checkbox" name="accept" value="accept" id=äccept""> <label for="accept">Согласен с использованием моих персональных данных</label>
			</p>
				<input type="submit" name="submit" value="Зарегистрироваться" onclick="checkValidity('#register')">
			</form>
			<p class="center or">или</p>
			<p class="center  social">
				<!--<a href="#"><img src="/img/vk.png" /></a>
				<a href="#"><img src="/img/fb.png" /></a>
			-->
				<a href="#" onclick="return loginform();">Войти</a>
			</p>
			<div class="alert alert-danger" role="alert" style="opacity: 0">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<strong>Ошибка!</strong>
				<div id="message"></div>
			</div>
		</div>

<!--  Авторизация  -->
		<div id="login" class="regblock" style="<?= isset($isreg)?"display: none":""?>">
			<H1>Вход</H1>
			<form id="loginform" method="post" action="" onsubmit="return login()">
				<div class="form-group has-feedback" > 
					
					<input type="email" id="loginfield" required name="username" class="form-control" onkeyup="this.setAttribute('value', this.value);"/>
					<label class="form-label">E-mail адрес (логин)</label>
					<span id="glyphicon" class="glyphicon form-control-feedback"></span>
				
				</div>

				
				<div class="form-group has-feedback password">
				<input type="password" id="loginpassword" name="password" class="form-control" required onkeyup="this.setAttribute('value', this.value);"/> <span id="eye" onclick="togglepassw(this, '#loginpassword')" class="glyphicon glyphicon-eye-open eye"></span>
				<label class="form-label">Пароль</label>
			</div>
			<br>
				<p>
				<input type="checkbox" name="rememberMe" value="accept" id="accept"> <label for="accept">Запомнить меня</label>
			</p>
				<input type="submit" name="submit" value="Войти" onclick="checkValidity('#loginform')">
			</form>
			<div class="col-lg-6 hrefs"><a href="#" onclick="return registration();">Регистрация</a></div>
			<div class="col-lg-6 hrefs"><a href="#" onclick="return restore();">Забыли пароль?</a></div>
			<p class="center or">или</p>
			<p class="center social">
				<a href="#" ><svg enable-background="new 0 0 1024 1024" height="40px" version="1.1" viewBox="0 0 1024 1024" width="40px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="Background"><rect fill="none" height="964" id="bg_1_" stroke="#4C75A3" stroke-miterlimit="10" stroke-width="60" width="963.984" x="30.008" y="30"/></g><g id="VK"><path d="M154.504,399.328c7.7,20.099,17.191,39.481,26.987,58.622   c23.277,45.481,47.248,89.673,79.387,129.648c32.479,40.396,70.825,79.544,115.996,105.675   c12.564,7.268,25.77,13.401,39.527,18.061c53.079,17.979,111.295,16.267,129.274,4.281c17.979-11.986-3.424-90.75,12.842-100.167   c16.266-9.418,45.375,11.13,66.777,29.965c21.403,18.834,37.669,35.1,49.656,47.942c7.506,8.042,12.79,17.64,22.575,23.376   c16.186,9.487,37.753,8.435,55.808,8.486c10.066,0.029,20.133,0.062,30.199-0.064c6.803-0.084,13.609,0.136,20.407-0.193   c11.17-0.542,25.779-4.952,24.941-18.927c-0.198-3.304-0.931-7.256-2.244-10.305c-2.822-6.555-6.797-13.358-11.069-19.068   c-22.258-29.75-37.027-44.946-48.156-56.932c-11.13-11.985-56.932-55.22-59.928-63.781c-2.997-8.561-3.96-12.36,4.547-23.918   c8.508-11.558,27.129-40.719,41.684-61.694c9.128-13.155,17.42-26.91,25.612-40.659c9.659-16.21,18.743-32.814,26.486-50.029   c3.462-7.698,6.604-14.711,4.46-23.337c-1.437-5.779-4.687-10.856-10.74-12.282c-3.303-0.778-6.909-0.857-10.29-0.857   c-12.627,0-45.589,0-45.589,0c-5.958,0-11.917-0.087-17.876-0.076c-6.109,0.011-12.268-0.037-18.36,0.485   c-8.666,0.742-13.977,7.425-18.336,14.163c-5.333,8.245-10.187,16.823-15.36,25.172c-3.537,5.705-6.998,11.455-10.398,17.243   c-5.311,9.04-10.784,17.983-16.256,26.927c-2.271,3.71-4.55,7.416-6.789,11.146c-6.421,10.7-62.069,83.899-73.198,86.896   c-11.129,2.997-14.982,2.141-14.982-20.547c0-22.687,0-151.533,0-151.533s-0.855-9.935-10.273-9.935c-9.418,0-53.098,0-53.098,0   h-90.09c0,0-21.189,0.088-13.698,19.138c7.492,19.049,29.644,24.828,29.644,56.611c0,31.783,0,124.566,0,124.566   s-3.745,27.93-27.931,4.815c-19.058-18.215-34.761-39.751-48.636-62.081c-14.426-23.215-26.048-47.333-36.401-72.602   c-2.961-7.227-5.893-14.466-8.934-21.659c-3.174-7.509-6.319-15.031-9.363-22.593c-3.535-8.784-7.317-20.265-16.849-24.309   c-4.792-2.033-10.092-1.933-15.2-1.932c-18.943,0.005-37.885,0.058-56.827,0.022c-5.558-0.01-11.116-0.016-16.674-0.002   c-13.123,0.031-29.445-0.356-32.558,16.038c-1.794,9.443,1.772,19.523,4.757,28.367   C151.321,391.511,152.99,395.375,154.504,399.328z" fill="#4C75A3" id="Vk"/></g></svg></a>
				<a href="#" ><svg enable-background="new 0 0 1024 1024" height="40px" version="1.1" viewBox="0 0 1024 1024" width="40px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="Background_1_"><rect fill="none" height="964" id="bg_1_" stroke="#3B5998" stroke-miterlimit="10" stroke-width="60" width="963.984" x="30.008" y="30"/></g><g id="Facebook"><path d="M672.75,305.641v-101.75c-3.736,0-7.473-0.007-11.209,0.001   c-8.996,0.02-17.993-0.022-26.99,0.001c-10.972,0.028-21.943-0.046-32.915,0.001c-9.667,0.042-19.337-0.097-29.003,0.003   c-14.592,0.151-28.444,0.868-42.438,5.375c-13.493,4.346-26.158,11.955-36.948,21.081c-6.248,5.284-12.031,10.943-17.269,17.234   c-2.121,2.613-4.096,5.33-5.942,8.14c-5.538,8.431-9.913,17.704-13.567,27.535c-0.822,2.213-1.628,4.432-2.398,6.662   c-5.634,16.324-5.82,34.143-5.82,51.297c0,2.148,0,4.363,0,6.629c0,33.984,0,79.291,0,79.291h-97v112h93.5h2.5v281h111v-283.5   L659,538l13.583-110.125l-113.833-0.235v-85c0,0-0.25-36.25,30.5-36.75L672.75,305.641z" fill="#3B5998" id="Facebook_1_"/></g></svg></a>

			</p>
			<!--Сообщение об ошибке авторизации -->
			<div class="alert alert-danger" role="alert" style="opacity: 0">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<strong>Ошибка!</strong>
				<div id="message"></div>
			</div>

			<div class="alert1 alert-warning" role="alert" style="opacity: 0">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<strong>Coach-me</strong>
				<div id="message1"></div>
			</div>
		</div>


<!--  Восстановление пароля  -->
	<div id="restore" class="regblock" style="display: none">
			<H1>Восстановление доступа</H1>
			<form id="restoreform" method="post" action="" onsubmit="return restorepassword()">
				<div class="form-group has-feedback" > 
				<input type="email" class="form-control" required id="restorefield" name="username" placeholder="Введите E-mail" />
				<label class="form-label">E-mail адрес (логин)</label>
				</div>
				<p class="center">
					<div id="captcha2"  class="recaptcha" data-sitekey="6Ldj9jcUAAAAAHKxOe6K49dlC_-MqhFzX4GraZZO"></div>
				</p>
				<input type="submit" name="submit" value="Восстановить">
			</form>
			<p class="center or">или</p>
			<p class="center  social">
				<!--<a href="#"><img src="/img/vk.png" /></a>
				<a href="#"><img src="/img/fb.png" /></a>
			-->
				<a href="#" onclick="return loginform();">Войти</a>
			</p>
			<div class="alert alert-danger" role="alert" style="opacity: 0">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<strong>Ошибка!</strong>
				<div id="message"></div>
			</div>
			
		</div>



	</div>


<?= $this->Html->script('/js/auth.js') ?>

