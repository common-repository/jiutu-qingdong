<?php
$options = get_option('qd_admin');
// var_dump($options);
wp_enqueue_style('qingdongcss1', 'https://fonts.googleapis.com/css?family=Signika:300,400,600,700');
if ($options['bootstrap'] == '1') {
	wp_enqueue_style('qingdongcss2', '/wp-content/plugins/jiutu-qingdong/static/external/bootstrap/bootstrap.min.css');
}
wp_enqueue_style('qingdongcss3', '/wp-content/plugins/jiutu-qingdong/static/external/slick/slick.css');
wp_enqueue_style('qingdongcss4', '/wp-content/plugins/jiutu-qingdong/static/external/slick/slick-theme.css');
wp_enqueue_style('qingdongcss5', '/wp-content/plugins/jiutu-qingdong/static/external/style.css');

wp_enqueue_script('mapmarkerJS1', '/wp-content/plugins/jiutu-qingdong/static/external/jquery/jquery.js', array(), false, true);
wp_enqueue_script('mapmarkerJS2', '/wp-content/plugins/jiutu-qingdong/static/external/bootstrap/bootstrap.min.js', array(), false, true);
wp_enqueue_script('mapmarkerJS3', '/wp-content/plugins/jiutu-qingdong/static/external/slick/slick.min.js', array(), false, true);
wp_enqueue_script('mapmarkerJS4', '/wp-content/plugins/jiutu-qingdong/static/external/app.js', array(), false, true);
get_header();

?>
<main class="pricing" style="margin-top:<?php echo $options['margin-top']['top']; ?><?php echo $options['margin-top']['unit']; ?>">
	<div class="container">
		<section class="text-block text-center mb-70">
			<h4><?php echo $options['title'] ?></h4>
			<p><?php echo $options['introduce'] ?></p>
		</section>
		<section class="row">
			<?php while (have_posts()) : the_post(); ?>

				<div class="col-md-4">
					<div class="block-icon">
						<img src="<?php the_post_thumbnail_url(); ?>" alt="">
						<div class="text-block">
							<a href="<?php the_permalink(); ?>" style="text-decoration: none;">
								<h4 class="text-center border-decor"><?php the_title(); ?></h4>
							</a>
							<?php if ($options['show_product_introduction']) : ?>
								<p class="text-center qd_goods_introduce"><?php rwmb_the_value('qd_goods_introduce') ?></p>
							<?php endif; ?>
						</div>

					</div>
				</div>
			<?php endwhile; ?>
		</section>
	</div>
</main>


<?php get_footer(); ?>