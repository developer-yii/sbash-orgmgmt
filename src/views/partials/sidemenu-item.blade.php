<li class="nav-item">
	<a href="#" class="nav-link">
	  <i class="nav-icon fa fa-building"></i>
	  <p>
	    {{ __('orgmgmt::organization.header.organization') }}
	    <i class="fas fa-angle-left right"></i>                
	  </p>
	</a>
	<ul class="nav nav-treeview">	  
	  <li class="nav-item">
	    <a href="{{ route('organization.settings') }}" class="nav-link">
	      <i class="fa fa-cogs nav-icon"></i>
	      <p>{{ __('orgmgmt::organization.header.settings') }}</p>
	    </a>
	  </li>	
	  <li class="nav-item">
	    <a href="{{ route('organization.invite') }}" class="nav-link">
	    	<i class="nav-icon fa fa-user-plus"></i>
	      	<p>
	        	{{ __('orgmgmt::organization.header.invite_org') }}
	    	</p>
	    </a>
	 </li>
	 <li class="nav-item">
	    <a href="{{ route('organization.members') }}" class="nav-link">
	    	<i class="nav-icon fa fa-user-friends"></i>
	      	<p>
	        	{{ __('orgmgmt::organization.header.org_members') }}
	    	</p>
	    </a>
	 </li>  
	</ul>
</li>
