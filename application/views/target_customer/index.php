<!-- Page header -->
<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex">
			<h4><span class="font-weight-semibold"><?= $this->lang->line('Customer Target'); ?> </span></h4>
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
					<h5 class="card-title">Target Customer</h5>
					<div class="header-elements">
						<div class="list-icons">
							<a class="list-icons-item" data-action="collapse"></a>
						</div>
					</div>
				</div>
				<div class="card-body">
					<a href="<?= base_url(); ?>target_customer/add" class="pull-right btn btn-xs btn-primary">
						<i class="icon-plus22"></i> &nbsp; Add Target
					</a>
				</div>
				<div class="card-body">
					<?= $this->session->flashdata('message'); ?>
					<table class="table table-striped table-hover table-bordered" id="serverside">
						<thead>
							<tr>
								<th>Periode</th>
								<th>Kode Customer</th>
								<th>Nama Customer</th>
								<th>Target SPB</th>
								<th>Target Nota</th>
								<th>Status</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>

		</div>

	</div>

</div>
<!-- /content area -->