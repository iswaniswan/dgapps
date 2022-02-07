			<!-- Page header -->
			<div class="page-header page-header-light">
				<div class="page-header-content header-elements-md-inline">
					<div class="page-title d-flex">
						<h4><span class="font-weight-semibold">Push - Add Push</span></h4>
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
								<h5 class="card-title"></h5>
								<div class="header-elements">
									<div class="list-icons">
										<a class="list-icons-item" data-action="collapse"></a>
									</div>
								</div>
							</div>

							<div class="card-body">
								<form action="<?= base_url(); ?>push/simpan" method="POST" class="form-validate">
									<div class="row">
										<div class="col-xl-12">
											<div class="form-group row">
												<label class="col-form-label col-lg-2">Title</label>
												<div class="col-lg-10">
													<input type="text" class="form-control" placeholder="Title" name="title"
														required>
												</div>
											</div>
											<div class="form-group row">
												<label class="col-form-label col-lg-2">Message</label>
												<div class="col-lg-10">
													<textarea rows="3" cols="3" class="form-control" placeholder="Message"
														name="message" required></textarea>
												</div>
											</div>
											<!-- <div class="form-group row">
												<label class="col-form-label col-lg-2">Image</label>
												<div class="col-lg-10">
													<input type="file" class="form-control h-auto" name="image">
												</div>
											</div> -->
											<div class="form-group row">
												<label class="col-form-label col-lg-2">Launch URL</label>
												<div class="col-lg-10">
													<input type="url" class="form-control" placeholder="<?= base_url(); ?>"
														name="url">
												</div>
											</div>
											<div class="text-center">
												<button type="submit" class="btn bg-blue-400">Submit <i
														class="icon-paperplane ml-2"></i></button>
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