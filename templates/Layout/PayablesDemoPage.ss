<div class="typography">
	<% if Menu(2) %>
		<% include SideBar %>
		<div id="Content">
	<% end_if %>

	<% if Level(2) %>
	  	<% include BreadCrumbs %>
	<% end_if %>
	
		<h2>$Title</h2>
	
		$Content
		
		<h4>On-line Store</h4>
		<% include Products %>
		<div class="clear"></div>
		<h4>Donation On-line</h4>
		<% include Donation %>
	<% if Menu(2) %>
		</div>
	<% end_if %>
</div>