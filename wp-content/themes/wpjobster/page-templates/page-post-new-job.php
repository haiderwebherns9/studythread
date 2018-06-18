<?php get_header();
/*
Template Name: Post New Job Template
*/
?>
</div>

<?php if ( have_posts() ): while ( have_posts() ) : the_post(); ?>

<div class="cf main">

		<?php the_content(); ?>

		<form>
			<div class="post-new-job-wrapper">
				<div class="tabs-wrapper">
					<div class="navigator">
						<div class="tab-selector active" style="width: 20%;"><div class="tab-text"><span>1</span> Job Information</div></div>
						<div class="tab-selector" style="width: 20%;"><div class="tab-text"><span>2</span> Buyer Information</div></div>
						<div class="tab-selector" style="width: 20%;"><div class="tab-text"><span>3</span> Photos/Video</div></div>
						<div class="tab-selector" style="width: 20%;"><div class="tab-text"><span>4</span> Extras</div></div>
						<div class="tab-selector" style="width: 20%;"><div class="tab-text"><span>5</span> Publish Your Job</div></div>
					</div>

					<div class="tab-wrapper">



						<!-- Job Information -->
						<div class="tab active">





							<div class="s-row">
								<div class="col100">
									<div class="input-block job-title">
										<label>Job Title</label>
										<textarea name="" placeholder="This is the service i can practice"></textarea>
										<span>80 characters left</span>
									</div>
								</div>

								<div class="col60">
									<div class="s-row">
										<div class="col50">
											<div class="input-block">
												<label>Job Price <span class="lighter">- $</span></label>
												<input type="text" step="any" class="focus-area" placeholder="Enter your price">
												<div class="hidden-tooltip">
													<div class="tooltip-img">
														<img src="<?php echo get_template_directory_uri(); ?>/images/price-tooltip.png">
													</div>
													<p>Aliquam interdum malesuada sem, quis suscipit massa dignissim sed. In nec egestas ex. Praesent pulvinar tortor est</p>
												</div>
											</div>
										</div>
									</div>

									<div class="s-row">
										<div class="col50">
											<div class="input-block">
												<label>Category</label>
												<div class="select-block">
													<select name="job_cat" class="grey_input styledselect focus-area" id="grey_input styledselect" onchange="display_subcat(this.value)" style="z-index: 10; opacity: 0;"><option value="">Select Category</option><option value="41">Business</option><option value="24">Graphics &amp; Design</option><option value="72">Lifestyle</option><option value="44">Marketing</option><option value="40">Online classes &amp; Teaching</option><option value="52">Other</option><option value="42">Programming &amp; IT</option><option value="43">Video &amp; Audio</option><option value="14">Writing &amp; Translation</option></select>
												</div>
												<div class="hidden-tooltip">
													<div class="tooltip-img">
													<img src="<?php echo get_template_directory_uri(); ?>/images/category-tooltip.png">
													</div>
													<p>Aliquam interdum malesuada sem, quis suscipit massa dignissim sed. In nec egestas ex. Praesent pulvinar tortor est</p>
												</div>
											</div>
										</div>

										<div class="col50">
											<div class="input-block">
												<label class="empty"></label>
												<div class="select-block">
													<select name="job_subcat" class="grey_input styledselect focus-area" id="grey_input styledselect" onchange="display_subcat(this.value)" style="z-index: 10; opacity: 0;"><option value="">Select a Subcategory</option><option value="41">Business</option><option value="24">Graphics &amp; Design</option><option value="72">Lifestyle</option><option value="44">Marketing</option><option value="40">Online classes &amp; Teaching</option><option value="52">Other</option><option value="42">Programming &amp; IT</option><option value="43">Video &amp; Audio</option><option value="14">Writing &amp; Translation</option></select>
												</div>
												<div class="hidden-tooltip">
													<div class="tooltip-img">
												   <img src="<?php echo get_template_directory_uri(); ?>/images/category-tooltip.png">
												   </div>
												   <p>Aliquam interdum malesuada sem, quis suscipit massa dignissim sed. In nec egestas ex. Praesent pulvinar tortor est</p>
												</div>
											</div>
										</div>
									</div>

									<div class="s-row">
										<div class="col100">
											<div class="input-block">
												<label>Description</label>
												<textarea name="" placeholder="Describe your job" class="focus-area"></textarea>
												<span>1000 characters left</span>
												<div class="hidden-tooltip">
													<div class="tooltip-img">
													<img src="<?php echo get_template_directory_uri(); ?>/images/description-tooltip.png">
													</div>
													<p>Aliquam interdum malesuada sem, quis suscipit massa dignissim sed. In nec egestas ex. Praesent pulvinar tortor est</p>
												</div>
											</div>
										</div>
									</div>

									<div class="s-row">
										<div class="col100">
											<div class="input-block">
												<label>Tags <span class="lighter">(separate your tags by comm)</span></label>
												<input id="job-tags-multiple" value="tag 1,tag 2, tag 3" class="focus-area"/>
												<!--
												<input type="text" name="" class="focus-area" placeholder="Enter tags for your job"> -->
												<div class="hidden-tooltip">
													<div class="tooltip-img">
													<img src="<?php echo get_template_directory_uri(); ?>/images/tags-tooltip.png">
													</div>
													<p>Aliquam interdum malesuada sem, quis suscipit massa dignissim sed. In nec egestas ex. Praesent pulvinar tortor est</p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>








							<div class="tab-corner-image">
								<img src="<?php echo get_template_directory_uri(); ?>/images/tab1-corner.png" alt="">
							</div>

							<div class="tab-controls">
								<a href="#" title="" class="right">Buyer Information</a>
							</div>
						</div>

						<!-- Buyer Information -->
						<div class="tab">










							<div class="s-row">
								<div class="col60">
									<div class="s-row">
										<div class="col100">
											<div class="input-block">
												<label>Instructions to buyer</label>
												<textarea name="" placeholder="Describe your job" class="focus-area"></textarea>
												<span>350 characters left</span>
												<div class="hidden-tooltip">
													test 2
												</div>
											</div>
										</div>
									</div>

									<div class="s-row">
										<div class="col100">
											<div class="input-block">
											<label>Let's Meet</label>
												<p class="lighter">
												<input type="checkbox" class="grey_input" name="lets_meet" id="lets_meet" value="yes" <?php echo (get_post_meta($pid, 'lets_meet', true) == "yes") ? 'checked' : ''; ?>/>
												<?php echo '<span> '.__("Check to enable","wpjobster").'</span>'; ?>
												</p>
											</div>
										</div>
									</div>

									<div class="s-row">
										<div class="col100">
											<div class="input-block">
												<label>Location</label>
												<input type="text" name="" placeholder="Enter job location" class="focus-area">
												<div class="hidden-tooltip">
													<div class="tooltip-img">
													<img src="<?php echo get_template_directory_uri(); ?>/images/location-tooltip.png">
													</div>
													<p>Aliquam interdum malesuada sem, quis suscipit massa dignissim sed. In nec egestas ex. Praesent pulvinar tortor est</p>
												</div>
											</div>
										</div>
									</div>


									<div class="s-row">
										<div class="col50">
											<div class="input-block">
												<label>Max Days to Deliver</label>
												<div class="select-block">
													<select name="job_cat" class="grey_input styledselect focus-area" id="grey_input styledselect" onchange="display_subcat(this.value)" style="z-index: 10; opacity: 0;">
														<option value="">Please select</option>
														<option value="41">1 Day</option>
														<option value="24">3 Days</option>
														<option value="72">4 Days</option>
														<option value="44">5 Days</option>
														<option value="40">6 Days</option>
														<option value="52">7 Days</option>
														<option value="42">8 Days</option>
														<option value="43">9 Days</option>
														<option value="14">10 Days</option>
														</select>
												</div>
												<div class="hidden-tooltip">
													<div class="tooltip-img">
													<img src="<?php echo get_template_directory_uri(); ?>/images/days-tooltip.png">
													</div>
													<p>Aliquam interdum malesuada sem, quis suscipit massa dignissim sed. In nec egestas ex. Praesent pulvinar tortor est</p>
												</div>
											</div>
										</div>

										<div class="col50">
											<div class="input-block">
												<label>Requires Shipping? <span class="lighter">($)</span></label>
												<div class="select-block">
													<input type="text" name="" placeholder="Optional" class="focus-area">
												</div>
												<div class="hidden-tooltip">
													<div class="tooltip-img">
													<img src="<?php echo get_template_directory_uri(); ?>/images/shipping-tooltip.png">
													</div>
													<p>Aliquam interdum malesuada sem, quis suscipit massa dignissim sed. In nec egestas ex. Praesent pulvinar tortor est</p>
												</div>
											</div>
										</div>
									</div>

									<div class="tab-corner-image">
										<img src="<?php echo get_template_directory_uri(); ?>/images/tab2-corner.png" alt="">
									</div>
								</div>
							</div>
							<div class="tab-controls">
								<a href="#" title="" class="left">Job Information</a>
								<a href="#" title="" class="right">Photos/Video</a>
							</div>
						</div>

						<!-- Photos/Video -->
						<div class="tab">
							<div class="s-row">
								<div class="col60">
									<div class="s-row">
										<div class="col100">
											<div class="input-block">
												<label>Images</label>
												<div>
													<img src="<?php echo get_template_directory_uri(); ?>/images/image-uploader-placeholder.png" class="focus-area">
												</div>
												<div class="hidden-tooltip">
													<div class="tooltip-img">
													<img src="<?php echo get_template_directory_uri(); ?>/images/images-tooltip.png">
													</div>
													<p>Aliquam interdum malesuada sem, quis suscipit massa dignissim sed. In nec egestas ex. Praesent pulvinar tortor est</p>
												</div>
											</div>
										</div>
									</div>

									<div class="s-row">
										<div class="col100">
											<div class="input-block">
												<label>Youtube Video Link</label>
												<input type="text" name="" placeholder="Please enter the URL of the Youtube video" class="focus-area">
												<div class="hidden-tooltip">
													<div class="tooltip-img">
													<img src="<?php echo get_template_directory_uri(); ?>/images/images-tooltip.png">
													</div>
													<p>Aliquam interdum malesuada sem, quis suscipit massa dignissim sed. In nec egestas ex. Praesent pulvinar tortor est</p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>


							<div class="tab-corner-image">
								<img src="<?php echo get_template_directory_uri(); ?>/images/tab3-corner.png" alt="">
							</div>
							<div class="tab-controls">
								<a href="#" title="" class="left">Buyer Information</a>
								<a href="#" title="" class="right">Extras</a>
							</div>
						</div>

						<!-- Extras -->
						<div class="tab">
							<div class="s-row">
								<div class="col60">
									<div class="s-row">
										<div class="col30">
											<div class="input-block">
												<label>Extra</label>
												<input type="text" name="" placeholder="Price for extra" class="focus-area">
												<div class="hidden-tooltip">
													<div class="tooltip-img">
													<img src="<?php echo get_template_directory_uri(); ?>/images/extras-tooltip.png">
													</div>
													<p>Aliquam interdum malesuada sem, quis suscipit massa dignissim sed. In nec egestas ex. Praesent pulvinar tortor est</p>
												</div>
											</div>
										</div>
										<div class="col70">
											<div class="input-block">
												<label class="empty"></label>
												<input type="text" name="" placeholder="Description" class="focus-area">
												<div class="hidden-tooltip">
													<div class="tooltip-img">
													<img src="<?php echo get_template_directory_uri(); ?>/images/extras-tooltip.png">
													</div>
													<p>Aliquam interdum malesuada sem, quis suscipit massa dignissim sed. In nec egestas ex. Praesent pulvinar tortor est</p>
												</div>
												<span>50 characters left</span>
											</div>
										</div>
									</div>

									<a href="" class="add-extra-btn" title="">+Add New Extra</a>
								</div>
							</div>

							<div class="tab-corner-image">
								<img src="<?php echo get_template_directory_uri(); ?>/images/tab4-corner.png" alt="">
							</div>
							<div class="tab-controls">
								<a href="#" title="" class="left">Photos/Video</a>
								<a href="#" title="" class="right">Publish</a>
							</div>
						</div>

						<!-- Publish Your Job -->
						<div class="tab">

						</div>
					</div>
				</div>
			</div>
		</form>

</div>

<?php endwhile; ?>

<?php endif; ?>

	<!-- ################### -->

<?php get_footer(); ?>
