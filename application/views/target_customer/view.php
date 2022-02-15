<!-- Page header -->
<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex">
			<h4><span class="font-weight-semibold"><?= $this->lang->line('Customer Target'); ?> - View Target</span></h4>
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
					<h5 class="card-title">Target Information</h5>
					<div class="header-elements">
						<div class="list-icons">
							<a class="list-icons-item" data-action="collapse"></a>
						</div>
					</div>
				</div>

				<div class="card-body">
					<form action="<?= base_url(); ?>user-management/update" method="POST">
						<div class="row">
							<div class="col-xl-12">
								<div class="form-group row">
									<label class="col-form-label col-lg-2">Customer</label>
									<div class="col-lg-10">
										<select class="form-control select-search" readonly data-fouc name="i_customer" id="i_customer" data-placeholder="Select Customer" required>
											<option value="<?= $data->i_customer; ?>"><?= $data->i_customer . ' - ' . $data->e_customer_name; ?></option>
										</select>
									</div>
								</div>
							</div>
							<div class="col-xl-12">
								<div class="form-group row">
									<label class="col-form-label col-lg-2">Periode</label>
									<div class="col-lg-10">
										<input type="text" readonly maxlength="4" value="<?= $data->i_periode; ?>" min="2022" class="form-control" placeholder="YYYY" name="i_periode" required>
									</div>
								</div>
							</div>
							<div class="col-xl-12">
								<div class="form-group row" hidden>
									<label class="col-form-label col-lg-2">Target SPB</label>
									<div class="col-lg-10">
										<input type="text" readonly class="form-control" value="<?= number_format($data->v_spb_target); ?>" name="v_spb_target" min="100000" placeholder="Rp. xxx.xxx.xxx.xxx" required>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-lg-2">Target MoU</label>
									<div class="col-lg-10">
										<input type="text" readonly class="form-control" value="<?= number_format($data->v_nota_target); ?>" name="v_nota_target" min="100000" placeholder="Rp. xxx.xxx.xxx.xxx" required>
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