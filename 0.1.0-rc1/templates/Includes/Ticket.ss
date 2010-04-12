<div class="object">
	<h5>$MovieTitle</h5>
	<h6 class="price">$Amount.Nice ($Amount.Currency)</h6>
	<p>
		$StartTime - $EndTime<br />
		$Date
	</p>
	<% control Theatre %>
		<% include Theatre %>
	<% end_control %>
	<% if Mode=Confirmation %>
	<% else %>
		<% control Theatre %>
			<div class="description">$Description.Summary(35)</div>
		<% end_control %>
		<br />
		<a class="button" href="$PayableLink">Buy Now</a>
	<% end_if %>
</div>