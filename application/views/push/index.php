			<!-- Page header -->
			<div class="page-header page-header-light">
				<div class="page-header-content header-elements-md-inline">
					<div class="page-title d-flex">
						<h4><span class="font-weight-semibold">Push </span></h4>
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
								<h5 class="card-title">Push List</h5>
								<div class="header-elements">
									<div class="list-icons">
										<a class="list-icons-item" data-action="collapse"></a>
									</div>
								</div>
							</div>
							<div class="card-body">
								<a href="<?= base_url(); ?>push/add" class="pull-right btn btn-xs btn-primary">
									<i class="icon-plus22"></i> &nbsp; Add Push
								</a>
							</div>
							<div class="card-body">
							<?= $this->session->flashdata('message'); ?>
							<table class="table table-striped table-hover table-bordered" id="serverside">
								<thead>
									<tr>
										<th>Username</th>
										<th>Title</th>
										<th>Message</th>
										<th>Url</th>
										<th>Recipients</th>
										<th>Created Time</th>
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