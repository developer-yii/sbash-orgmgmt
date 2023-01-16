<li class="nav-item">
	<a href="#" class="nav-link">
	  <i class="nav-icon fa fa-building"></i>
	  <p>
	    Organization
	    <i class="fas fa-angle-left right"></i>                
	  </p>
	</a>
	<ul class="nav nav-treeview">	  
	  <li class="nav-item">
	    <a href="{{ route('organization.settings') }}" class="nav-link">
	      <i class="fa fa-cogs nav-icon"></i>
	      <p>Settings</p>
	    </a>
	  </li>	  
	</ul>
</li>
<li class="nav-item">
    <a href="{{ route('organization.invite') }}" class="nav-link">
    	<i class="nav-icon fa fa-user-plus"></i>
      	<p>
        	Invite to Organization
    	</p>
    </a>
 </li>