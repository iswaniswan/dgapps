<link href="<?= base_url('assets/css/bootstrap-datepicker.min.css'); ?>" rel="stylesheet" type="text/css">
<!-- Page header -->
<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex">
			<h4><span class="font-weight-semibold"><?= $this->lang->line('Information'); ?> - Update <?= $this->lang->line('Information'); ?></span></h4>
		</div>
	</div>
</div>
<!-- /page header -->


<!-- Content area -->
<div class="content">

	<div class="row">
		<div class="col-xl-12">

			<div class="card">
				<form action="<?= base_url($this->folder . '/update'); ?>" method="POST" class="form-validate">
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
							<div class="col-md-6">
								<div class="form-group">
									<label>Type Information: </label>
									<select class="form-control select-search" data-fouc name="id_type" id="id_type" data-placeholder="Select Type" required>
										<option value="<?= $data->id_type;?>"><?= $data->e_type_name;?></option>
									</select>
									<input type="hidden" value="<?= $data->id;?>" required name="id">
									<span class="form-text text-muted">Promo</span>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label>Title: </label>
									<input type="text" class="form-control" value="<?= $data->e_title;?>" required name="e_title" placeholder="Enter Title">
									<span class="form-text text-muted">Diskon 30%</span>
								</div>
							</div>
						</div>
						<div class="row">

							<div class="col-md-6">
								<div class="form-group">
									<label>Periode Information: </label>
									<div class="input-daterange input-group" id="datepicker">
										<span class="input-group-prepend">
											<span class="input-group-text"><i class="icon-calendar22"></i></span>
										</span>
										<input type="text" readonly class="form-control" name="d_start" value="<?= $data->d_start;?>" required placeholder="Select Date" />
										<span class="input-group-addon mt-1 mr-2 ml-2"> s/d </span>
										<span class="input-group-prepend">
											<span class="input-group-text"><i class="icon-calendar22"></i></span>
										</span>
										<input type="text" readonly class="form-control" name="d_end" value="<?= $data->d_end;?>" required placeholder="Select Date" />
									</div>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label>Description: </label>
									<textarea class="form-control" name="e_description" placeholder="Note .."><?= $data->e_deskripsi;?></textarea>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer text-muted">
						<div class="d-flex justify-content-end align-items-center">
							<button type="submit" class="btn btn-block btn-outline bg-slate-800 text-slate-800 border-slate-800"><i class="fas fa-paper-plane mr-2 fa-lg"></i>UPDATE</button>
						</div>
					</div>
				</form>
			</div>

		</div>

	</div>

</div>
<!-- /content area -->