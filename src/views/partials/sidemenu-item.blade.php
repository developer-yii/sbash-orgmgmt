<li class="nav-item">
	<a href="javascript:void(0)" class="nav-link">
	  <i class="nav-icon fa fa-building"></i>
	  <p>
	    {{ __('orgmgmt')['header']['organization'] }}
	    <i class="fas fa-angle-left right"></i>                
	  </p>
	</a>
	<ul class="nav nav-treeview">	  
		@role('User')			
		<li class="nav-item">
		    <a href="{{ route('organization.mylist') }}" class="ml-4 nav-link">
		      <i class="fa fa-cogs nav-icon"></i>
		      <p>{{ __('orgmgmt')['header']['my_org_list'] }}</p>
		    </a>
		</li>	
		@if(count(auth()->user()->userOrganizations()->get()))
			<li class="nav-item">
			    <a href="{{ route('organization.invite') }}" class="ml-4 nav-link">
			    	<i class="nav-icon fa fa-user-plus"></i>
			      	<p>
			        	{{ __('orgmgmt')['header']['invite_org'] }}
			    	</p>
			    </a>
			</li>
		@endif
		{{-- @if(config('app.project_alias') == null)
		<li class="nav-item">
		    <a href="{{ route('organization.join.list') }}" class="pl-4 nav-link">		    	
		    	<i class="nav-icon fa fa-university"></i>
		      	<p>
		        	{{ __('orgmgmt')['header']['join_organization'] }}
		    	</p>
		    </a>
		</li>
		@endif --}}
		@if(count(auth()->user()->userOrganizations()->get()))
			<li class="nav-item">
			    <a href="{{ route('organization.request.list') }}" class="ml-4 nav-link">		    	
			    	<i class="nav-icon fa fa-list"></i>
			      	<p>
			        	{{ __('orgmgmt')['header']['join_requests'] }}		        	
			    	</p>
			    </a>
			</li>
			<li class="nav-item">
			    <a href="{{ route('organization.members') }}" class="ml-4 nav-link">
			    	<i class="nav-icon fa fa-user-friends"></i>
			      	<p>
			        	{{ __('orgmgmt')['header']['org_members'] }}
			    	</p>
			    </a>
			</li>		
		@endif
		@else
		<li class="nav-item">
		    <a href="{{ route('organization.list') }}" class="ml-4 nav-link">
		    	<i class="nav-icon fa fa-user-friends"></i>
		      	<p>
		        	{{ __('orgmgmt')['header']['org_list'] }}
		    	</p>
		    </a>
		</li>		
		@endrole
	</ul>
</li>
