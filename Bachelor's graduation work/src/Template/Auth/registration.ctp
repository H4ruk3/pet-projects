<?= $this->Html->css('redesign/auth.css') ?>
<div class="dialog">
		<p class="logo">
			<img src="/img/logo_site.png" />
		</p>
				<div id="registration" class="regblock" style="">
			<h1>Выбор тарифа</h1>

			<div class="col-md-4 col-sm-6">
 <div class="pricingTable">
 <h3 class="title">Обычный пользователь</h3>
 <div class="price-value">
 <span class="month">per month</span>
 <span class="amount">
 <span class="currency">$</span>
 00
 <span class="value">00</span>
 </span>
 </div>
 <ul class="pricing-content">
 <li>50GB Disk Space</li>
 <li>50 Email Accounts</li>
 <li>50GB Monthly Bandwidth</li>
 <li>10 Subdomains</li>
 <li>15 Domains</li>
 </ul>
 <a href="/auth/register/user" class="pricingTable-signup">Зарегистрироваться</a>
 </div>
 </div>

			<div class="col-md-4 col-sm-6">
 <div class="pricingTable">
 <h3 class="title">Тренер</h3>
 <div class="price-value">
 <span class="month">per month</span>
 <span class="amount">
 <span class="currency">$</span>
 00
 <span class="value">00</span>
 </span>
 </div>
 <ul class="pricing-content">
 <li>50GB Disk Space</li>
 <li>50 Email Accounts</li>
 <li>50GB Monthly Bandwidth</li>
 <li>10 Subdomains</li>
 <li>15 Domains</li>
 </ul>
 <a href="/auth/register/trainer" class="pricingTable-signup">Зарегистрироваться</a>
 </div>
 </div>

			<div class="col-md-4 col-sm-6">
 <div class="pricingTable">
 <h3 class="title">Корпоративный пользователь</h3>
 <div class="price-value">
 <span class="month">per month</span>
 <span class="amount">
 <span class="currency">$</span>
 00
 <span class="value">00</span>
 </span>
 </div>
 <ul class="pricing-content">
 <li>50GB Disk Space</li>
 <li>50 Email Accounts</li>
 <li>50GB Monthly Bandwidth</li>
 <li>10 Subdomains</li>
 <li>15 Domains</li>
 </ul>
 <a href="/auth/register/corp" class="pricingTable-signup">Зарегистрироваться</a>
 </div>
 </div>
			
		</div>




	</div>
<style>
#registration {
	width: 100%;
    margin-left: 0;
}
@media (min-width: 1025px) {
.dialog {
    //width: 95%;
    max-width: 80%;
    }
}
/* style snippet */
.pricingTable{
 text-align: center;
 background: #fff;
 padding: 30px 0;
}
.pricingTable .title{
 font-size: 22px;
 font-weight: 600;
 color: #2e282a;
 text-transform: uppercase;
 margin: 0 0 30px 0;
 min-height: 50px
}
.pricingTable .price-value{
 padding: 30px 0;
 background: #003f74;
 margin-bottom: 30px;
 position: relative;
}
.pricingTable .price-value:before{
 content: "";
 border-top: 15px solid #fff;
 border-left: 15px solid transparent;
 border-right: 15px solid transparent;
 position: absolute;
 top: 0;
 left: 46%;
}
.pricingTable .month{
 display: block;
 font-size: 15px;
 font-weight: 900;
 color: #fff;
 text-transform: uppercase;
}
.pricingTable .amount{
 display: inline-block;
 font-size: 50px;
 color: #fff;
 position: relative;
}
.pricingTable .currency{
 position: absolute;
 top: -1px;
 left: -35px;
}
.pricingTable .value{
 font-size: 20px;
 position: absolute;
 top: 21px;
 right: -27px;
}
.pricingTable .pricing-content{
 padding: 0;
 margin: 0 0 30px 0;
 list-style: none;
}
.pricingTable .pricing-content li{
 font-size: 16px;
 color: #868686;
 line-height: 35px;
}
.pricingTable .pricingTable-signup{
 display: inline-block;
 padding: 8px 40px;
 background: #fca4a7;
 font-size: 15px;
 font-weight: 600;
 color: #fff;
 text-transform: capitalize;
 border: 2px solid #fca4a7;
 border-radius: 30px;
 transition: all 0.5s ease 0s;
}
.pricingTable .pricingTable-signup:hover{
 background: #fff;
 color: #fca4a7;
}
@media only screen and (max-width: 990px){
 .pricingTable{ margin-bottom: 30px; }
}
</style>
