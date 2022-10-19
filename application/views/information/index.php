<!-- Page header -->
<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex">
			<h4><span class="font-weight-semibold"><?= $this->lang->line('Information'); ?> </span></h4>
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
					<h5 class="card-title"><?= $this->lang->line('Information'); ?> List</h5>
					<div class="header-elements">
						<div class="list-icons">
							<a class="list-icons-item" data-action="collapse"></a>
						</div>
					</div>
				</div>
				<div class="card-body">
					<a href="<?= base_url($this->folder.'/add'); ?>" class="pull-right btn btn-xs bg-primary-800">
						<i class="icon-plus22"></i> &nbsp; Add <?= $this->lang->line('Information'); ?>
					</a>
				</div>
				<div class="card-body">
					<?= $this->session->flashdata('message'); ?>
					<table class="table table-striped table-hover table-bordered" id="serverside">
						<thead>
							<tr>
								<th>#</th>
								<th>Type</th>
								<th>Periode From</th>
								<th>Periode To</th>
								<th>Tittle</th>
								<th>Note</th>
								<th>Status</th>
								<th>Act</th>
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