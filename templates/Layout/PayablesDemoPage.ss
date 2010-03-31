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
		<div class="section">
			<h4>Online Store</h4>
			<% include Products %>
		</div>
		
		<div class="clear"></div>
		
		<!--<div class="section">
			<h4>Donation On-line</h4>
			<% include Donation %>
		</div>
		
		<div class="clear"></div>-->
		
		<div class="section">
			<h4>Online Ticket Booking</h4>
			<% include Tickets %>
		</div>
		
		<div class="clear"></div>
		
		<div class="section">
			<h4>Downloadable Ebook</h4>
			<% include Ebooks %>
		</div>
		
	<% if Menu(2) %>
		</div>
	<% end_if %>
</div>