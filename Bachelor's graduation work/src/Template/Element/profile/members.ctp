<? foreach($members as $member) { ?>
<div class=" col-md-4 col-sm-4 col-xs-4 col-mob" style="margin-bottom: 20px; padding:0; padding-right:10px">
<div class="ui-details" >
								<!-- Heading -->
								<div class="ui-heading clearfix">
									<!-- User Image -->
									<a href="/user/userinfo/<?=$member->id?>"><img src="/img/excersices/<? if ($member->profile->avatar != null) echo($member->profile->avatar); else echo('no_image_available.jpg'); ?>" alt="" class="img-responsive"></a>
									<!-- UserName and Designation -->
									<h2><a href="/user/userinfo/<?=$member->id?>"><?= $member->profile->fam ?? ''?> <?= $member->profile->name ?? '' ?></a> &nbsp;<a href="/user/userinfo/<?=$member->id?>"><i class="fa fa-check bg-lblue"></i></a><span><?= $member->username ?></span></h2>
								</div>							
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-4 col-mob">
										<!-- Details Item -->
										<div class="ui-ditem">
											<!-- Small Heading -->
											<h4><a href="/user/viewuserroutine/<?=$member->id?>">Распорядки</a></h4>
											<!-- Big Heading -->
											<h3><a href="/user/viewuserroutine/<?=$member->id?>"><?=$member->stat->routine?></a></h3>
										</div>
									</div>
									<div class="col-md-4 col-sm-4 col-xs-4 col-mob">
										<!-- Details Item -->
										<div class="ui-ditem">
											<!-- Small Heading -->
											<h4><a href="/user/usertrainingprogram/<?=$member->id?>">Программы тренировок</a></h4>
											<!-- Big Heading -->
											<h3><a href="/user/usertrainingprogram/<?=$member->id?>"><?=$member->stat->trainingprogram?></a></h3>
										</div>
									</div>
									<div class="col-md-4 col-sm-4 col-xs-4 col-mob">
										<!-- Details Item -->
										<div class="ui-ditem">
											<!-- Small Heading -->
											<h4><a href="/user/usernutritionprogram/<?=$member->id?>">Программы питания</a></h4>
											<!-- Big Heading -->	
											<h3><a href="/user/usernutritionprogram/<?=$member->id?>"><?=$member->stat->eatings?></a></h3>
										</div>
									</div>
								</div>
							</div>
							</div>

							<? } ?>