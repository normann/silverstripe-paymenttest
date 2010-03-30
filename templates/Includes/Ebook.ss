<div class="object">
	$CoverPhoto.FrontImage<br />
	<h5 class="title">$Title</h5>
	<h6 class="price">$Amount.Nice ($Amount.Currency)</h6>
	<% if Authors %>
		<h6>
			<% control Authors %>
				$Name<% if Last %><% else %>,&nbsp;<% end_if %>
			<% end_control %>
		</h6>
	<% end_if %>
	<% if Mode=Confirmation %>
	<% else %>
	<div class="description ebookoverview">$Summary.Summary(35)</div>
	<br />
	<a class="button" href="$PayableLink">Buy Now</a>
	<% end_if %>

</div>