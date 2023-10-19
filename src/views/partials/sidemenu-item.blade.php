<li class="nav-item">
	<a href="#" class="nav-link">
	  <i class="nav-icon fa fa-building"></i>
	  <p>
	    {{ __('orgmgmt::organization.header.organization') }}
	    <i class="fas fa-angle-left right"></i>                
	  </p>
	</a>
	<ul class="nav nav-treeview">	  
		@role('User')			
		<li class="nav-item">
		    <a href="{{ route('organization.mylist') }}" class="pl-4 nav-link">
		      <i class="fa fa-cogs nav-icon"></i>
		      <p>{{ __('orgmgmt::organization.header.my_org_list') }}</p>
		    </a>
		</li>	
		@if(count(auth()->user()->userOrganizations()->get()))
			<li class="nav-item">
			    <a href="{{ route('organization.invite') }}" class="pl-4 nav-link">
			    	<i class="nav-icon fa fa-user-plus"></i>
			      	<p>
			        	{{ __('orgmgmt::organization.header.invite_org') }}
			    	</p>
			    </a>
			</li>
		@endif
		<li class="nav-item">
		    <a href="{{ route('organization.join.list') }}" class="pl-4 nav-link">		    	
		    	<i class="nav-icon fa fa-university"></i>
		      	<p>
		        	{{ __('orgmgmt::organization.header.join_organization') }}
		    	</p>
		    </a>
		</li>
		@if(count(auth()->user()->userOrganizations()->get()))
			<li class="nav-item">
			    <a href="{{ route('organization.request.list') }}" class="pl-4 nav-link">		    	
			    	<i class="nav-icon fa fa-list"></i>
			      	<p>
			        	{{ __('orgmgmt::organization.header.join_requests') }}		        	
			    	</p>
			    </a>
			</li>
			<li class="nav-item">
			    <a href="{{ route('organization.members') }}" class="pl-4 nav-link">
			    	<i class="nav-icon fa fa-user-friends"></i>
			      	<p>
			        	{{ __('orgmgmt::organization.header.org_members') }}
			    	</p>
			    </a>
			</li>		
		@endif
		@else
		<li class="nav-item">
		    <a href="{{ route('organization.list') }}" class="pl-4 nav-link">
		    	<i class="nav-icon fa fa-user-friends"></i>
		      	<p>
		        	{{ __('orgmgmt::organization.header.org_list') }}
		    	</p>
		    </a>
		</li>		
		@endrole
	</ul>
</li>
