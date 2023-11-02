<?php
/**
 * Template Name: Area of Practice Template
 */

get_header();
?>


<div class="bodyContent">
<div class="container">
	<div class="row">
		<?php $fields = CFS()->get( 'add_service' );
	foreach ( $fields as $field ) { ?>
		<div class="col-md-3 col-6">
			
			<div class="productthumb aos-init aos-animate" data-aos="fade-up" data-aos-duration="1000" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $field['add_tooltip_title']; ?>">
				<div class="image">
					<a href="<?php echo $field['add_url']; ?>"><img src="<?php echo $field['add_image']; ?>"></a>
					<div class="icons text-center">
						<a href="<?php echo $field['add_url']; ?>"><i class="icofont-eye-alt"></i></a>
					</div>
				</div>
			<div class="content px-3 pb-3">
			<h4 style="height:auto"><?php echo $field['add_title']; ?></h4>
		</div>
		
	</div>


</div>
		<?php } ?>
</div>
</div>
</div>










<?php
get_footer();
