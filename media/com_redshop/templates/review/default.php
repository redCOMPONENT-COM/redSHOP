<div>{product_loop_start}
	<p><strong>{product_title}</strong></p>

	<div>{review_loop_start}
		<div id="reviews_wrapper">
			<div id="reviews_rightside">
				<div id="reviews_fullname">{fullname}</div>
				<div id="reviews_title">{title}</div>
				<div id="reviews_comment">{comment}</div>
				<div id="reviews_date">{reviewdate}</div>
			</div>
			<div id="reviews_leftside">
				<div id="reviews_stars">{stars}</div>
			</div>
		</div>
		{review_loop_end}
	</div>
	{product_loop_end}
</div>
