
<div class="row">
	<div class="col-md-3 mt-4">
	<?php foreach($years as $year) : ?>
	<p><a class="btn btn-default" href="<?php echo site_url("/posts/post_by_year/".$year['year'] ); ?>"><?php echo $year['year']; ?></a></p>
	<?php endforeach; ?>

</div>
<div class="col-md-9">
<h2><?= $title ?></h2>
<?php foreach($posts as $post) : ?>
	<h5><?php echo $post['title']; ?></h5>
	<div class="row blog">
		<div class="col-md-3">
			<img class="post-thumb mt-4" src="<?php echo site_url(); ?>assets/images/posts/<?php echo $post['post_image']; ?>">
		</div>
		<div class="col-md-9 mt-4 descp">
			<small class="post-date ">Posted on: <?php echo $post['created_at']; ?> in <strong><?php echo $post['name']; ?></strong></small><br>
		<?php echo word_limiter($post['body'], 60); ?>
		<br><br>
		<p><a class="btn btn-default" href="<?php echo site_url('/posts/'.$post['slug']); ?>">Read More</a></p>
		</div>
	</div>
	<hr>
<?php endforeach; ?>
<div class="pagination-links">
		<?php echo $this->pagination->create_links(); ?>
</div>
</div>
</div>