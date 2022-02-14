<!-- Page header -->
<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex">
			<h4><span class="font-weight-semibold"><?= $this->lang->line('user-customer'); ?> - View User</span></h4>
		</div>
	</div>
</div>
<!-- /page header -->


<!-- Content area -->
<div class="content">

	<div class="row">
		<div class="col-xl-12">

			<div class="card">
				<div class="card-header header-elements-inline">
					<h5 class="card-title">User Information</h5>
					<div class="header-elements">
						<div class="list-icons">
							<a class="list-icons-item" data-action="collapse"></a>
						</div>
					</div>
				</div>

				<div class="card-body">
					<form action="<?= base_url(); ?>user-customer/update" method="POST" class="form-validate">
						<div class="row">
							<div class="col-xl-12">
								<div class="form-group row">
									<label class="col-form-label col-lg-2">Username</label>
									<div class="col-lg-10">
										<input type="hidden" class="form-control" value="<?= $data->username;?>" placeholder="Username" name="username_old" required>
										<input type="text" class="form-control" readonly value="<?= $data->username;?>" placeholder="Username" name="username" required>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-lg-2">Shop Name</label>
									<div class="col-lg-10">
										<input type="text" class="form-control" readonly value="<?= $data->e_name;?>" placeholder="Full Name" name="e_name" required>
									</div>
								</div>
							</div>

							<div class="col-xl-12">
								<div class="form-group row">
									<label class="col-form-label col-lg-2">Customer</label>
									<div class="col-lg-10">
										<select class="form-control select-search" multiple data-fouc name="i_customer[]" id="i_customer" data-placeholder="Select Customer" required>
											<option value=""></option>
                                            <?php if ($customer->num_rows()>0) {
                                                foreach ($customer->result() as $key) {?>
                                                    <option value="<?= $key->i_customer;?>" selected><?= $key->e_customer_name;?></option>
                                                <?php }
                                            }?>
										</select>
									</div>
								</div>
							</div>

						</div>
					</form>
				</div>
			</div>

		</div>

	</div>

</div>
<!-- /content area -->