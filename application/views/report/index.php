<!-- Page header -->
<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex">
			<h4><span class="font-weight-semibold"><?= $this->lang->line('report'); ?></span></h4>
			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
		</div>
	</div>
</div>
<!-- /page header -->


<!-- Content area -->
<div class="content">

	<!-- Main charts -->
	<!-- <div class="row">
		<div class="col-xl-12">
			<div class="card">
				<div class="card-header header-elements-inline">
					<div class="form-group">
						<h5 class="font-weight-semibold"><?= $this->lang->line('time-range'); ?></h5>
						<div class="d-flex">
							<input type="text" class="form-control mb-3 mb-md-0" id="range-from" placeholder="" value="<?= date('d-m-Y', strtotime('-7 days', strtotime(date('Y-m-d')))); ?>" readonly>&nbsp;
							<input type="text" class="form-control" id="range-to" placeholder="Date to:" value="<?= date('d-m-Y'); ?>" readonly>
						</div>
					</div>
					<div class="header-elements">
						<div class="list-icons">
							<a class="list-icons-item" data-action="collapse"></a>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-4">

							<div class="mb-4">
								<h6 class="font-weight-semibold">Download Sales Call Detail</h6>
								<button type="button" class="btn btn-primary btn-ladda btn-ladda-progress" data="calldetail_report" data-style="zoom-out">
									<span class="ladda-label">Download Sales Call Detail
										<?= $this->lang->line('report'); ?> &nbsp; <i class="icon-download"></i></span>
								</button>
							</div>

							<div class="mb-4">
								<h6 class="font-weight-semibold"><?= $this->lang->line('call'); ?></h6>
								<button type="button" class="btn btn-primary btn-ladda btn-ladda-progress" data="call_report" data-style="zoom-out">
									<span class="ladda-label">Download <?= $this->lang->line('call'); ?> (Tanpa RRKH)
										<?= $this->lang->line('report'); ?> &nbsp; <i class="icon-download"></i></span>
								</button>
							</div>

							<div class="mb-4">
								<h6 class="font-weight-semibold"><?= $this->lang->line('attendance'); ?></h6>
								<button type="button" class="btn btn-primary btn-ladda btn-ladda-progress" data="sfa_attendance" data-style="zoom-out">
									<span class="ladda-label">Download <?= $this->lang->line('attendance'); ?>
										<?= $this->lang->line('report'); ?> &nbsp; <i class="icon-download"></i></span>
								</button>
							</div>



							<div class="mb-3" hidden="">
								<h6 class="font-weight-semibold"><?= $this->lang->line('documentation'); ?></h6>
								<button type="button" class="btn btn-primary btn-ladda btn-ladda-progress" data="documentation" data-style="zoom-out">
									<span class="ladda-label">Download <?= $this->lang->line('documentation'); ?>
										<?= $this->lang->line('report'); ?> &nbsp; <i class="icon-download"></i></span>
								</button>
							</div>
						</div>

						<div class="col-md-4">
							<div class="mb-4">
								<h6 class="font-weight-semibold"><?= $this->lang->line('sales-order'); ?></h6>
								<button type="button" class="btn btn-primary btn-ladda btn-ladda-progress" data="sales_order" data-style="zoom-out">
									<span class="ladda-label">Download <?= $this->lang->line('sales-order'); ?>
										<?= $this->lang->line('report'); ?> &nbsp; <i class="icon-download"></i></span>
								</button>
							</div>

							<div class="mb-4">
								<h6 class="font-weight-semibold"><?= $this->lang->line('customer'); ?></h6>
								<button type="button" class="btn btn-primary btn-ladda btn-ladda-progress" data="customer_report" data-style="zoom-out">
									<span class="ladda-label">Download <?= $this->lang->line('customer'); ?>
										<?= $this->lang->line('report'); ?> &nbsp; <i class="icon-download"></i></span>
								</button>
							</div>

							<div class="mb-3" hidden="">
								<h6 class="font-weight-semibold"><?= $this->lang->line('suggestion'); ?></h6>
								<button type="button" class="btn btn-primary btn-ladda btn-ladda-progress" data="suggestion" data-style="zoom-out">
									<span class="ladda-label">Download <?= $this->lang->line('suggestion'); ?>
										<?= $this->lang->line('report'); ?> &nbsp; <i class="icon-download"></i></span>
								</button>
							</div>

							<div class="mb-4">
								<h6 class="font-weight-semibold"><?= $this->lang->line('lastvisit'); ?></h6>
								<button type="button" class="btn btn-primary btn-ladda btn-ladda-progress" data="lastvisit" data-style="zoom-out">
									<span class="ladda-label">Download <?= $this->lang->line('lastvisit'); ?>
										<?= $this->lang->line('report'); ?> &nbsp; <i class="icon-download"></i></span>
								</button>
							</div>

						</div>

						<div class="col-md-4">
							<div class="mb-4">
								<h6 class="font-weight-semibold">Salesman Per Customer</h6>
								<button type="button" class="btn btn-primary btn-ladda btn-ladda-progress" data="customer_salesman" data-style="zoom-out">
									<span class="ladda-label">Download Salesman Per Customer
										<?= $this->lang->line('report'); ?> &nbsp; <i class="icon-download"></i></span>
								</button>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div> -->

	<!-- <div class="row">
		<div class="col-xl-12">
			<div class="card">
				<div class="card-header header-elements-inline">
					<div class="header-elements">
						<div class="list-icons">
							<a class="list-icons-item" data-action="collapse"></a>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-9">
							<div class="mb-4">
								<h6 class="font-weight-semibold">Download Pencapaian Toko</h6>
								<div class="d-flex">
									<?php
									$lastyear =  date('Y', strtotime('-1 year'));
									$nextyear =  date('Y', strtotime('+1 year'));
									$now =  date('Y');
									?>
									<select id="tahun">
										<option value="<?= $lastyear ?>"><?= $lastyear ?></option>
										<option value="<?= $now ?>" selected><?= $now ?></option>
										<option value="<?= $nextyear ?>"><?= $nextyear ?></option>
									</select>

									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<button type="button" class="btn btn-primary btn-ladda btn-ladda-progress" data="targettoko_report" data-style="zoom-out">
										<span class="ladda-label">Download Pencapaian Toko
											<?= $this->lang->line('report'); ?> &nbsp; <i class="icon-download"></i></span>
									</button>

									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<button type="button" class="btn btn-primary btn-ladda btn-ladda-progress" data="targettoko_detail_report" data-style="zoom-out">
										<span class="ladda-label">Download Pencapaian Toko Detail
											<?= $this->lang->line('report'); ?> &nbsp; <i class="icon-download"></i></span>
									</button>
								</div>
							</div>


						</div>
					</div>
				</div>
			</div>
		</div>
	</div> -->
	<!-- /main charts -->

	<!-- Table Download -->
	<!-- Support tickets -->
	<div class="card">
		<div class="card-header header-elements-sm-inline">
			<h6 class="card-title"><i class="icon-statistics mr-2"></i>Report</h6>
			<div class="header-elements">
				<!-- <a class="text-default daterange-ranges font-weight-semibold cursor-pointer dropdown-toggle">
					<i class="icon-calendar3 mr-2"></i>
					<span></span>
				</a> -->
				<h6 class="card-title mr-2"><?= $this->lang->line('time-range'); ?></h6>
				<div class="d-flex">
					<input type="text" class="form-control mb-3 mb-md-0 ml-1" id="range-from" placeholder="" value="<?= date('d-m-Y', strtotime('-7 days', strtotime(date('Y-m-d')))); ?>" readonly>&nbsp;
					<input type="text" class="form-control" id="range-to" placeholder="Date to:" value="<?= date('d-m-Y'); ?>" readonly>
				</div>
			</div>
		</div>

		<!-- <div class="card-body d-md-flex align-items-md-center justify-content-md-between flex-md-wrap">
			<div class="d-flex align-items-center mb-3 mb-md-0">
				<div id="tickets-status"></div>
				<div class="ml-3">
					<h5 class="font-weight-semibold mb-0">14,327 <span class="text-success font-size-sm font-weight-normal"><i class="icon-arrow-up12"></i> (+2.9%)</span></h5>
					<span class="badge badge-mark border-success mr-1"></span> <span class="text-muted">Jun 16, 10:00 am</span>
				</div>
			</div>

			<div class="d-flex align-items-center mb-3 mb-md-0">
				<a href="#" class="btn bg-transparent border-indigo-400 text-indigo-400 rounded-round border-2 btn-icon">
					<i class="icon-alarm-add"></i>
				</a>
				<div class="ml-3">
					<h5 class="font-weight-semibold mb-0">1,132</h5>
					<span class="text-muted">total tickets</span>
				</div>
			</div>

			<div class="d-flex align-items-center mb-3 mb-md-0">
				<a href="#" class="btn bg-transparent border-indigo-400 text-indigo-400 rounded-round border-2 btn-icon">
					<i class="icon-spinner11"></i>
				</a>
				<div class="ml-3">
					<h5 class="font-weight-semibold mb-0">06:25:00</h5>
					<span class="text-muted">response time</span>
				</div>
			</div>

			<div>
				<a href="#" class="btn bg-teal-400"><i class="icon-statistics mr-2"></i> Report</a>
			</div>
		</div> -->

		<div class="table-responsive">
			<table class="table table-xs text-nowrap">
				<thead>
					<tr>
						<th style="width: 50px">No</th>
						<th style="width: 300px;">Nama Module</th>
						<th>File Download</th>
						<th class="text-center" style="width: 20px;"><i class="icon-arrow-down12"></i></th>
					</tr>
				</thead>
				<tbody>
					<tr class="table-active table-border-double">
						<td colspan="3"><h6 class="mb-0"><strong>Daftar Module [ Berdasarkan Time Range : Tanggal ]</strong></h6></td>
						<td class="text-right">
							<span class="badge bg-success-800 badge-pill">10</span>
						</td>
					</tr>

					<tr>
						<td class="text-center">
							<h6 class="mb-0">1</h6>
						</td>
						<td>
							<div class="d-flex align-items-center">
								<div>
									<a href="#" class="text-default font-weight-semibold letter-icon-title">Sales Call Detail</a>
								</div>
							</div>
						</td>
						<td colspan="2">
							<button type="button" class="btn btn-sm bg-primary-800 btn-block btn-ladda btn-ladda-progress" data="calldetail_report" data-style="zoom-out">
								<span class="ladda-label">Download Sales Call Detail
									<?= $this->lang->line('report'); ?> &nbsp; <i class="icon-download"></i></span>
							</button>
						</td>
					</tr>

					<tr>
						<td class="text-center">
							<h6 class="mb-0">2</h6>
						</td>
						<td>
							<div class="d-flex align-items-center">
								<div>
									<a href="#" class="text-default font-weight-semibold letter-icon-title"><?= $this->lang->line('call'); ?></a>
								</div>
							</div>
						</td>
						<td colspan="2">
							<button type="button" class="btn bg-primary-800 btn-block btn-sm btn-ladda btn-ladda-progress" data="call_report" data-style="zoom-out">
								<span class="ladda-label">Download <?= $this->lang->line('call'); ?> (Tanpa RRKH)
									<?= $this->lang->line('report'); ?> &nbsp; <i class="icon-download"></i></span>
							</button>
						</td>
					</tr>

					<tr>
						<td class="text-center">
							<h6 class="mb-0">3</h6>
						</td>
						<td>
							<div class="d-flex align-items-center">
								<div>
									<a href="#" class="text-default font-weight-semibold letter-icon-title"><?= $this->lang->line('attendance'); ?></a>
								</div>
							</div>
						</td>
						<td colspan="2">
							<button type="button" class="btn bg-primary-800 btn-block btn-sm btn-ladda btn-ladda-progress" data="sfa_attendance" data-style="zoom-out">
								<span class="ladda-label">Download <?= $this->lang->line('attendance'); ?>
									<?= $this->lang->line('report'); ?> &nbsp; <i class="icon-download"></i></span>
							</button>
						</td>
					</tr>

					<tr>
						<td class="text-center">
							<h6 class="mb-0">4</h6>
						</td>
						<td>
							<div class="d-flex align-items-center">
								<div>
									<a href="#" class="text-default font-weight-semibold letter-icon-title"><?= $this->lang->line('sales-order'); ?></a>
								</div>
							</div>
						</td>
						<td colspan="2">
							<button type="button" class="btn bg-primary-800 btn-block btn-sm btn-ladda btn-ladda-progress" data="sales_order" data-style="zoom-out">
								<span class="ladda-label">Download <?= $this->lang->line('sales-order'); ?>
									<?= $this->lang->line('report'); ?> &nbsp; <i class="icon-download"></i></span>
							</button>
						</td>
					</tr>

					<tr>
						<td class="text-center">
							<h6 class="mb-0">5</h6>
						</td>
						<td>
							<div class="d-flex align-items-center">
								<div>
									<a href="#" class="text-default font-weight-semibold letter-icon-title"><?= $this->lang->line('customer'); ?></a>
								</div>
							</div>
						</td>
						<td colspan="2">
							<button type="button" class="btn bg-primary-800 btn-block btn-sm btn-ladda btn-ladda-progress" data="customer_report" data-style="zoom-out">
								<span class="ladda-label">Download <?= $this->lang->line('customer'); ?>
									<?= $this->lang->line('report'); ?> &nbsp; <i class="icon-download"></i></span>
							</button>
						</td>
					</tr>

					<tr>
						<td class="text-center">
							<h6 class="mb-0">6</h6>
						</td>
						<td>
							<div class="d-flex align-items-center">
								<div>
									<a href="#" class="text-default font-weight-semibold letter-icon-title"><?= $this->lang->line('suggestion'); ?></a>
								</div>
							</div>
						</td>
						<td colspan="2">
							<button type="button" class="btn bg-primary-800 btn-block btn-sm btn-ladda btn-ladda-progress" data="suggestion" data-style="zoom-out">
								<span class="ladda-label">Download <?= $this->lang->line('suggestion'); ?>
									<?= $this->lang->line('report'); ?> &nbsp; <i class="icon-download"></i></span>
							</button>
						</td>
					</tr>

					<tr>
						<td class="text-center">
							<h6 class="mb-0">7</h6>
						</td>
						<td>
							<div class="d-flex align-items-center">
								<div>
									<a href="#" class="text-default font-weight-semibold letter-icon-title"><?= $this->lang->line('lastvisit'); ?></a>
								</div>
							</div>
						</td>
						<td colspan="2">
							<button type="button" class="btn bg-primary-800 btn-block btn-sm btn-ladda btn-ladda-progress" data="lastvisit" data-style="zoom-out">
								<span class="ladda-label">Download <?= $this->lang->line('lastvisit'); ?>
									<?= $this->lang->line('report'); ?> &nbsp; <i class="icon-download"></i></span>
							</button>
						</td>
					</tr>

					<tr>
						<td class="text-center">
							<h6 class="mb-0">8</h6>
						</td>
						<td>
							<div class="d-flex align-items-center">
								<div>
									<a href="#" class="text-default font-weight-semibold letter-icon-title">Salesman Per Customer</a>
								</div>
							</div>
						</td>
						<td colspan="2">
							<button type="button" class="btn bg-primary-800 btn-block btn-sm btn-ladda btn-ladda-progress" data="customer_salesman" data-style="zoom-out">
								<span class="ladda-label">Download Salesman Per Customer
									<?= $this->lang->line('report'); ?> &nbsp; <i class="icon-download"></i></span>
							</button>
						</td>
					</tr>

					<tr>
						<td class="text-center">
							<h6 class="mb-0">9</h6>
						</td>
						<td>
							<div class="d-flex align-items-center">
								<div>
									<a href="#" class="text-default font-weight-semibold letter-icon-title">Aktivitas</a>
								</div>
							</div>
						</td>
						<td colspan="2">
							<button type="button" class="btn bg-primary-800 btn-block btn-sm btn-ladda btn-ladda-progress" data="aktivitas" data-style="zoom-out">
								<span class="ladda-label">Download Aktivitas
									<?= $this->lang->line('report'); ?> &nbsp; <i class="icon-download"></i></span>
							</button>
						</td>
					</tr>

					<tr>
						<td class="text-center">
							<h6 class="mb-0">10</h6>
						</td>
						<td>
							<div class="d-flex align-items-center">
								<div>
									<a href="#" class="text-default font-weight-semibold letter-icon-title">Rating Kunjungan</a>
								</div>
							</div>
						</td>
						<td colspan="2">
							<button type="button" class="btn bg-primary-800 btn-block btn-sm btn-ladda btn-ladda-progress" data="rating" data-style="zoom-out">
								<span class="ladda-label">Download Rating Kunjungan
									<?= $this->lang->line('report'); ?> &nbsp; <i class="icon-download"></i></span>
							</button>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="card">
		<div class="card-header header-elements-sm-inline">
			<h6 class="card-title"><i class="icon-statistics mr-2"></i>Report</h6>
			<div class="header-elements">
				<h6 class="card-title mr-2"><?= $this->lang->line('time-range'); ?></h6>
				<div class="d-flex">
					<?php
					$lastyear =  date('Y', strtotime('-1 year'));
					$nextyear =  date('Y', strtotime('+1 year'));
					$now =  date('Y');
					?>
					<select id="tahun" class="form-control select-search mr-2">
						<option value="<?= $lastyear ?>"><?= $lastyear ?></option>
						<option value="<?= $now ?>" selected><?= $now ?></option>
						<option value="<?= $nextyear ?>"><?= $nextyear ?></option>
					</select>
				</div>
			</div>
		</div>

		<div class="table-responsive">
			<table class="table table-xs text-nowrap">
				<thead>
					<tr>
						<th style="width: 50px">No</th>
						<th style="width: 300px;">Nama Module</th>
						<th>File Download</th>
						<th class="text-center" style="width: 20px;"><i class="icon-arrow-down12"></i></th>
					</tr>
				</thead>
				<tbody>
					<tr class="table-active table-border-double">
						<td colspan="3"><h6 class="mb-0"><strong>Daftar Module [ Berdasarkan Time Range : Tahun ]</strong></h6></td>
						<td class="text-right">
							<span class="badge bg-primary-800 badge-pill">2</span>
						</td>
					</tr>

					<tr>
						<td class="text-center">
							<h6 class="mb-0">1</h6>
						</td>
						<td>
							<div class="d-flex align-items-center">
								<div>
									<a href="#" class="text-default font-weight-semibold letter-icon-title">Pencapaian Toko</a>
								</div>
							</div>
						</td>
						<td colspan="2">
							<button type="button" class="btn btn-sm bg-success-800 btn-block btn-ladda btn-ladda-progress" data="targettoko_report" data-style="zoom-out">
								<span class="ladda-label">Download Pencapaian Toko
									<?= $this->lang->line('report'); ?> &nbsp; <i class="icon-download"></i></span>
							</button>
						</td>
					</tr>

					<tr>
						<td class="text-center">
							<h6 class="mb-0">2</h6>
						</td>
						<td>
							<div class="d-flex align-items-center">
								<div>
									<a href="#" class="text-default font-weight-semibold letter-icon-title">Pencapaian Toko Detail</a>
								</div>
							</div>
						</td>
						<td colspan="2">
							<button type="button" class="btn bg-success-800 btn-block btn-sm btn-ladda btn-ladda-progress" data="targettoko_detail_report" data-style="zoom-out">
								<span class="ladda-label">Download Pencapaian Toko Detail
									<?= $this->lang->line('report'); ?> &nbsp; <i class="icon-download"></i></span>
							</button>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<!-- /support tickets -->
	<!-- End Table Download -->
</div>
<!-- /content area -->