<article class="hentry">								<div class="blog-list-content clearfix">										<figure class="blog-image">																							<?php if(get_field('video_url') <> ''):?>				<iframe src="<?php echo get_field('video_url');?>" width="250" height="150" ></iframe> 			<?php else:?>				<?php $image = vt_resize('', get_template_directory_uri().'/images/no-video-available.jpg', 250, 150, true);?>				<img src="<?php echo $image['url'];?>" alt="No Video" width="" height=""/>			<?php endif;?>			</figure>										<div class="blog-content">												<p><?php echo get_the_excerpt() ? excerpt_to_charlength(400, get_the_excerpt()) : excerpt_to_charlength(400, get_the_content()); ?></p>			<a href="<?php the_permalink();?>" class="link blog-link">Read More</a>		</div>	</div></article>