<div class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show" id="sidebar">
    <div class="c-sidebar-brand d-lg-down-none">
        {{-- <svg class="c-sidebar-brand-full" width="118" height="46" alt="CoreUI Logo">
            <use xlink:href="{{ asset('img/brand/coreui.svg#full') }}"></use>
        </svg>
        <svg class="c-sidebar-brand-minimized" width="46" height="46" alt="CoreUI Logo">
            <use xlink:href="{{ asset('img/brand/coreui.svg#signet') }}"></use>
        </svg> --}}
        <h2>EKNS</h2>
    </div><!--c-sidebar-brand-->

    <ul class="c-sidebar-nav">
        <li class="c-sidebar-nav-item">
            <x-utils.link
                class="c-sidebar-nav-link"
                :href="route('admin.dashboard')"
                :active="activeClass(Route::is('admin.dashboard'), 'c-active')"
                icon="c-sidebar-nav-icon cil-speedometer"
                :text="__('Dashboard')" />
        </li>

        <li class="c-sidebar-nav-title">@lang('Leaders')</li>
        <li class="c-sidebar-nav-item">
            <x-utils.link
                class="c-sidebar-nav-link"
                :href="route('admin.leaders.index')"
                :active="activeClass(Request::is('admin/leaders/*'), 'c-active')"
                icon="c-sidebar-nav-icon fas fa-users"
                :text="__('Leader\'s List')" />

                <x-utils.link
                class="c-sidebar-nav-link"
                :href="route('admin.leaders.location')"
                :active="activeClass(Request::is('admin/leaders/location/*'), 'c-active')"
                icon="c-sidebar-nav-icon fa fa-location-arrow"
                :text="__('Leader\'s Location')" />
        </li>
         <li class="c-sidebar-nav-title">@lang('Messages')</li>
        <li class="c-sidebar-nav-item">
            <x-utils.link
                class="c-sidebar-nav-link"
                :href="route('admin.messages.index')"
                :active="activeClass(Request::is('admin/nofication/*'), 'c-active')"
                icon="c-sidebar-nav-icon fas fa-exclamation"
                :text="__('General Message')" />
                <li class="c-sidebar-nav-item">
        <x-utils.link
                class="c-sidebar-nav-link"
                :href="route('admin.messages.history')"
                :active="activeClass(Request::is('admin/message/history/*'), 'c-active')"
                icon="c-sidebar-nav-icon fas fa-history"
                :text="__('Message History')" />
        <li class="c-sidebar-nav-title">@lang('Households')</li>
        <li class="c-sidebar-nav-item">
            <x-utils.link
                class="c-sidebar-nav-link"
                :href="route('admin.household.index')"
                :active="activeClass(Request::is('admin/household/*'), 'c-active')"
                icon="c-sidebar-nav-icon fas fa-house-user"
                :text="__('Household\'s List')" />

        </li>

        <li class="c-sidebar-nav-title">@lang('Voters')</li>
        <li class="c-sidebar-nav-item">
            <x-utils.link
                class="c-sidebar-nav-link"
                :href="route('admin.voters.index')"
                :active="activeClass(Request::is('admin/voters/*'), 'c-active')"
                icon="c-sidebar-nav-icon fas fa-user-friends"
                :text="__('Voter\'s List')" />
        </li>
        @if ($logged_in_user->hasAllAccess())
            {{-- <li class="c-sidebar-nav-title">@lang('Candidates')</li>
            <li class="c-sidebar-nav-item">
                <x-utils.link
                    class="c-sidebar-nav-link"
                    :href="route('admin.agents.index', ['sorts' => ['id' => 'desc'], 'agent' => true])"
                    :active="activeClass(Request::is('admin/agents/*'), 'c-active')"
                    icon="c-sidebar-nav-icon fas fa-user-tie"
                    :text="__('Candidate\'s List')" />
            </li> --}}
            <li class="c-sidebar-nav-title">@lang('System')</li>
            <li class="c-sidebar-nav-dropdown {{ activeClass(Route::is('admin.auth.user.*') || Route::is('admin.auth.role.*'), 'c-open c-show') }}">
                <x-utils.link
                    href="#"
                    icon="c-sidebar-nav-icon cil-user"
                    class="c-sidebar-nav-dropdown-toggle"
                    :text="__('Access')" />

                <ul class="c-sidebar-nav-dropdown-items">
                    @if (
                        $logged_in_user->hasAllAccess() ||
                        (
                            $logged_in_user->can('admin.access.user.list') ||
                            $logged_in_user->can('admin.access.user.deactivate') ||
                            $logged_in_user->can('admin.access.user.reactivate') ||
                            $logged_in_user->can('admin.access.user.clear-session') ||
                            $logged_in_user->can('admin.access.user.impersonate') ||
                            $logged_in_user->can('admin.access.user.change-password')
                        )
                    )
                        <li class="c-sidebar-nav-item">
                            <x-utils.link
                                :href="route('admin.auth.user.index')"
                                class="c-sidebar-nav-link"
                                :text="__('User Management')"
                                :active="activeClass(Route::is('admin.auth.user.*'), 'c-active')" />
                        </li>
                    @endif

                    @if ($logged_in_user->hasAllAccess())
                        <li class="c-sidebar-nav-item">
                            <x-utils.link
                                :href="route('admin.auth.role.index')"
                                class="c-sidebar-nav-link"
                                :text="__('Role Management')"
                                :active="activeClass(Route::is('admin.auth.role.*'), 'c-active')" />
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        {{-- @if($logged_in_user->can('admin.list.leader') && Auth::user()->hasRole('Encoder')) --}}
            {{-- <li class="c-sidebar-nav-title">@lang('Leaders')</li>
            <li class="c-sidebar-nav-item">
                <x-utils.link
                    class="c-sidebar-nav-link"
                    :href="route('admin.leaders.index')"
                    :active="activeClass(Request::is('admin/leaders/*'), 'c-active')"
                    icon="c-sidebar-nav-icon fas fa-users"
                    :text="__('Leader\'s List')" />
            </li>
            <li class="c-sidebar-nav-title">@lang('Voters')</li>
                <li class="c-sidebar-nav-item">
                    <x-utils.link
                        class="c-sidebar-nav-link"
                        :href="route('admin.total.voters')"
                        :active="activeClass(Request::is('admin/total/voters/*'), 'c-active')"
                        icon="c-sidebar-nav-icon fas fa-list-alt"
                        :text="__('Total Voters')" />
                </li> --}}
        {{-- @endif --}}

        @if ($logged_in_user->hasAllAccess())
            <li class="c-sidebar-nav-dropdown">
                <x-utils.link
                    href="#"
                    icon="c-sidebar-nav-icon cil-list"
                    class="c-sidebar-nav-dropdown-toggle"
                    :text="__('Logs')" />

                <ul class="c-sidebar-nav-dropdown-items">
                    <li class="c-sidebar-nav-item">
                        <x-utils.link
                            :href="route('log-viewer::dashboard')"
                            class="c-sidebar-nav-link"
                            :text="__('Dashboard')" />
                    </li>
                    <li class="c-sidebar-nav-item">
                        <x-utils.link
                            :href="route('log-viewer::logs.list')"
                            class="c-sidebar-nav-link"
                            :text="__('Logs')" />
                    </li>
                </ul>
            </li>
        @endif
    </ul>

    <button class="c-sidebar-minimizer c-class-toggler" type="button" data-target="_parent" data-class="c-sidebar-minimized"></button>
</div><!--sidebar-->
