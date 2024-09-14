<!-- ============================================================== -->
<!-- Right Part contents-->
<!-- ============================================================== -->
<div class="right-part mail-list bg-white">
	<div class="p-15 b-b">
		<div class="d-flex align-items-center">
			<div>
				<span><?php echo $lang['tools-config61'] ?> | <?php echo $lang['tools-config100'] ?> </span>
			</div>

		</div>
	</div>
	<!-- Action part -->
	<!-- Button group part -->
	<div class="bg-light">
		<div class="row justify-content-center">
			<div class="col-md-12">
				<div class="row">
					<div class="col-12">
						<div id="loader" style="display:none"></div>
						<div id="resultados_ajax"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Action part -->

	<div class="row justify-content-center">
		<div class="col-lg-12">
			<div class="row">
				<!-- Column -->
				<div class="col-12">
					<div class="card-body">
						<header><b><?php echo $lang['tools-config57'] ?></b></header>
						<form class="form-horizontal form-material" id="save_config_email" name="save_config_email" method="post">
							<hr />

							<h4 class="card-title"><?php echo $lang['tools-config110'] ?></h4>
							<h6 class="card-subtitle">(<?php echo $lang['tools-config54'] ?>)</h6>

							<div class="row">
								<section class="col-md-12">
									<select class="form-control is-valid" name="mailer" id="mailer">
										<option value="PHP" <?php if ($core->mailer == "PHP") echo " selected=\"selected\""; ?>>Sendmail</option>
										<option value="SMTP" <?php if ($core->mailer == "SMTP") echo " selected=\"selected\""; ?>>SMTP Mailer</option>
									</select>
								</section>
							</div>
							<hr />
							<br><br>


							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="form-control-label" for="inputSuccess1"><?php echo $lang['tools-config101'] ?> </label>
										<input type="text" class="form-control" name="smtp_names" id="smtp_names" value="<?php echo $core->smtp_names; ?>" placeholder="<?php echo $lang['tools-config101'] ?> ">
									</div>
								</div>

								<div class="col-md-12">
									<div class="form-group">
										<label for="lastName1"><?php echo $lang['tools-config102'] ?> </label>
										<input type="text" class="form-control" name="email_address" id="email_address" value="<?php echo $core->email_address; ?>" placeholder="<?php echo $lang['tools-config102'] ?> ">
									</div>
								</div>
							</div>


							<div class="row showsmtp">
								<div class="col-md-12">
									<div class="form-group">
										<h4 class="card-title"><?php echo $lang['tools-config103'] ?> </h4>
									</div>
								</div>

								<div class="col-md-12">
									<div class="form-group">
										<label for="lastName1"><?php echo $lang['tools-config104'] ?> </label>
										<input type="text" class="form-control" name="smtp_host" id="smtp_host" value="<?php echo $core->smtp_host; ?>" placeholder="<?php echo $lang['tools-config104'] ?> ">
									</div>
								</div>


								<div class="col-md-12">
									<div class="form-group">
										<label for="firstName1"><?php echo $lang['tools-config105'] ?> </label>
										<input type="text" class="form-control" name="smtp_user" id="smtp_user" value="<?php echo $core->smtp_user; ?>" placeholder="<?php echo $lang['tools-config105'] ?> ">
									</div>
								</div>

								<div class="col-md-12">
									<div class="form-group">
										<label for="firstName1"><?php echo $lang['tools-config106'] ?> </label>
										<input type="text" class="form-control" name="smtp_password" id="smtp_password" value="<?php echo $core->smtp_password; ?>" placeholder="<?php echo $lang['tools-config106'] ?> ">
									</div>
								</div>

								<div class="col-md-12">
									<div class="form-group">
										<label for="firstName1"><?php echo $lang['tools-config107'] ?> - (<b><?php echo $lang['tools-config108'] ?> <span class="badge badge-info">587</span></b>)</label>
										<input type="text" class="form-control" name="smtp_port" id="smtp_port" value="<?php echo $core->smtp_port; ?>" placeholder="<?php echo $lang['tools-config107'] ?> ">
									</div>
								</div>

								<div class="col-md-12">
									<div class="form-group">
										<label for="firstName1"><?php echo $lang['tools-config108'] ?></label>
										<input type="text" class="form-control" name="smtp_secure" id="smtp_secure" value="<?php echo $core->smtp_secure; ?>" placeholder="<?php echo $lang['tools-config108'] ?>">
									</div>
								</div>

							</div>

							<div class="form-group">
								<div class="col-sm-12">
									<button type="submit" class="btn btn-primary btn-confirmation" name="dosubmit"><?php echo $lang['tools-config56'] ?> <span><i class="icon-ok"></i></span></button>
								</div>
							</div>

						</form>
					</div>
				</div>
				<!-- Column -->
			</div>
		</div>
	</div>

</div>