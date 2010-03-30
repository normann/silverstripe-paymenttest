<div class="product">
	$Image.FrontImage
	<br /><h5 class="title">$Title</h5>
	<br /><h6 class="price">$Amount.Nice ($Amount.Currency)</h6>
	<% if Mode=Confirmation %>
	<% else %>
	<br /><div class="description">$Description.Summary(35)</div>
	<br /><a class="button" href="$PayableLink">Buy Now</a>
	<% end_if %>
</div>