<?php
$options = get_option('qd_admin');
wp_enqueue_style('qingdongcss1', 'https://fonts.googleapis.com/css?family=Signika:300,400,600,700');
// var_dump($options);
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
// var_dump(rwmb_meta('qd_goods_fictitious'));
?>
<?php if (!isset($_GET['action'])) :
?>
	<header>
		<div class="sticky">
			<div class="container">
				<h2 class="font-18 weight-500 color-dark">
					<a href="index.html" class="logo pull-left">
						<img src="<?php the_post_thumbnail_url(); ?>" alt="logo">
					</a>
					<?php the_title(); ?>
				</h2>
				<section class="btns-group-demo">
					<div class="row">
						<div class="col-md-6 col-sm-6 col-xs-6">
							<a href="?action" class="btn btn-small btn-primary  btn-gray pull-right">购买 ¥<?php rwmb_the_value('qd_goods_price') ?></a>
						</div>
					</div>
				</section>
			</div>
		</div>
	</header>
	<main class="single-item" style="margin-top:<?php echo $options['margin-top']['top']; ?><?php echo $options['margin-top']['unit']; ?>">
		<div class="container">
			<section class="text-block mb-70">
				<h4 class="text-center"><?php the_title(); ?></h4>
				<p class="text-center"><?php rwmb_the_value('qd_goods_introduce') ?> </p>
			</section>
			<section class="btns-group-demo mb-70">
				<div class="row">
					<div class="col-md-9 col-sm-6 col-xs-6">
						<a type="button" href="?action" class="btn btn-small btn-primary btn-gray pull-right">购买 ¥<?php rwmb_the_value('qd_goods_price') ?></a>

					</div>
				</div>
			</section>
		</div>
		<section class="slider mb-60">
			<?php $images = rwmb_meta('qd_goods_image'); ?>
			<?php foreach ($images as $image) : ?>
				<div>
					<img src="<?php echo $image['url']; ?>" alt="">
				</div>
			<?php endforeach ?>
		</section>
	</main>

<?php else : ?>
	<style>
		ol,
		ul {
			margin-left: 0.15rem;
		}
	</style>
	<main class="cart" style="margin-top:<?php echo $options['margin-top']['top']; ?><?php echo $options['margin-top']['unit']; ?>">
		<div class="container">
			<section class="text-block mb-70 text-center">
				<h4>结账</h4>
			</section>
			<section class="cart-block row mb-40">
				<div class="col-md-8">
					<form id="form">
						<?php if (rwmb_meta('qd_goods_fictitious') == '1') : ?>

							<h5>此商品为虚拟商品!</h5>
							<div class="payment mb-30">
								<div class="payment-info">
									<label>凭证*(购买后可通过此凭证查询数据)</label>
									<input type="hidden" name="qd_orders_goods" value="<?php the_ID(); ?>">
									<input type="hidden" name="action" value="qd_add_order_api">
									<input type="text" name="qd_orders_voucher" class="form-control" required>
									<button id="submit" type="button" class="btn btn-primary">发起支付</button>
								</div>
							</div>
						<?php else : ?>
							<h5>收货信息</h5>
							<div class="payment mb-30">
								<div class="payment-info">
									<input type="hidden" name="qd_orders_goods" value="<?php the_ID(); ?>">
									<input type="hidden" name="action" value="qd_add_order_api">
									<label>收件人*</label>
									<input type="text" name="name" class="form-control" required>
									<label>电话*</label>
									<input type="text" name="telephone" class="form-control" required>

									<label>收件地址*</label>
									<textarea name="address" class="form-control" style="height: 85px;" required></textarea>
									<button id="submit" type="button" class="btn btn-primary">发起支付</button>
								</div>
							</div>

						<?php endif; ?>
					</form>
				</div>
				<div class="col-md-4">
					<div class="panel panel-info">
						<div class="panel-heading">
							<h3 class="panel-title">订单摘要</h3>
						</div>
						<ul class="list-group">
							<li class="list-group-item">
								<span class="badge" style="margin-top: 9px;background-color: #4a7597;">¥<?php rwmb_the_value('qd_goods_price') ?></span>
								# <?php the_title(); ?>
							</li>
							<li class="list-group-item">
								<span class="badge" style="margin-top: 9px;background-color: #4a7597;">¥<?php rwmb_the_value('qd_goods_price') ?></span>
								总计:
							</li>
						</ul>
					</div>

				</div>
			</section>

		</div>
	</main>
	<script>
		jQuery(function() {
			jQuery('#submit').click(function() {
				// var t = jQuery('#form').serialize();
				// console.log(t);
				jQuery.ajax({
					type: "POST",
					url: '<?php echo admin_url("admin-ajax.php"); ?>',
					data: jQuery('#form').serialize(),
					success: function(data) {
						console.log(data);
						var obj = JSON.parse(data);
						if (obj.result) {
							window.location.href = obj.url
						} else {
							alert(obj.msg);
						}
					}
				});
				// jQuery.each(t, function() {
				// 	d[this.name] = this.value;
				// });
				// alert(JSON.stringify(d));
				// // {"a1":"a","a2":"b","a3":"c","ax":"0"}
				// var inputa11 = document.getElementById("a11");
				// console.log(inputa11.value); // 输入的值
			});
		});
	</script>
<?php endif; ?>
<?php get_footer(); ?>