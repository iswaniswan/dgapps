<link href="<?= base_url('assets/css/bootstrap-datepicker.min.css'); ?>" rel="stylesheet" type="text/css">
<!-- Page header -->
<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex">
			<h4><span class="font-weight-semibold"><?= $this->lang->line('Information'); ?> - Add <?= $this->lang->line('Information'); ?></span></h4>
		</div>
	</div>
</div>
<!-- /page header -->


<!-- Content area -->
<div class="content">

	<div class="row">
		<div class="col-xl-12">

			<div class="card">
				<form action="<?= base_url($this->folder . '/simpan'); ?>" method="POST" class="form-validate">
					<div class="card-header bg-dark text-white header-elements-inline">
						<h5 class="card-title"><?= $this->lang->line('Information'); ?></h5>
						<div class="header-elements">
							<div class="list-icons">
								<a class="list-icons-item" data-action="collapse"></a>
							</div>
						</div>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Type Information: </label>
									<input type="text" class="form-control" required name="e_type_name" placeholder="Enter Type Information">
									<span class="form-text text-muted">Promo</span>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label>Icon: </label>
									<input type="file" name="e_icon" class="form-control-uniform" data-fouc>
									<span class="form-text text-muted">https://apps.dialoguegroup.net/dgapps/assets/images/promo.png</span>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Color Code: </label>
									<input type="color" class="form-control" required name="e_color" placeholder="html code">
									<span class="form-text text-muted">#123456</span>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer text-muted">
						<div class="d-flex justify-content-end align-items-center">
							<button type="submit" class="btn btn-block btn-outline bg-slate-800 text-slate-800 border-slate-800"><i class="fas fa-paper-plane mr-2 fa-lg"></i>SAVE</button>
						</div>
					</div>
				</form>
			</div>

		</div>

	</div>

</div>
<!-- /content area -->