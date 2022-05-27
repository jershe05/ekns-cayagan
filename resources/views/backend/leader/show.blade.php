@extends('backend.layouts.app')

@section('title', __('View Leader'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Leader Dashboard')
        </x-slot>

        <x-slot name="headerActions">
            <i class="fas fa-undo"></i>
            <x-utils.link class="card-header-action" :href="route('admin.leaders.index')" :text="__('Back')" />
        </x-slot>

        <x-slot name="body">
            <x-backend.card>
                <x-slot name="header">
                    <div class="p-1 d-flex align-middle w-100">
                        <div class="p-1">
                            <i class="fas fa-address-card"></i>
                        </div>

                        <div class="p-1">
                            @lang('Personal Information')
                        </div>

                        <div class="p-1">
                            <h2 class="mb-0">
                                <label class="c-switch c-switch-danger" data-toggle="collapse" data-target="#leaderPersonalInfo" aria-expanded="true" aria-controls="collapseOne">
                                    <input type="checkbox" class="c-switch-input" checked>
                                    <span class="c-switch-slider"></span>
                                </label>
                            </h2>
                        </div>
                        <div class=" p-1 float-right">
                            <i class="fas fa-edit"></i>
                            <x-utils.link
                                class="card-header-action"
                                :text="__('Edit')"
                                data-toggle="modal"
                                data-target=".edit"

                                />
                        </div>
                        <div class=" p-1 float-right">
                            <i class="fas fa-address-book"></i>
                            <x-utils.link
                                class="card-header-action"
                                :text="__('Contacts')"
                                data-toggle="modal"
                                data-target=".contacts"

                                />
                        </div>
                        <div class="p-1 float-right">
                            <i class="fas fa-sms"></i>
                            <x-utils.link
                                class="card-header-action"
                                :text="__('Messages')"
                                data-toggle="modal"
                                data-target=".messages"
                            />
                        </div>
                        <div class="p-1 float-right">
                            <i class="fas fa-images"></i>
                            <x-utils.link
                                class="card-header-action"
                                :text="__('Gallery')"
                                data-toggle="modal"
                                data-target=".gallery"
                            />
                        </div>
                        <div class="p-1 float-right">
                            <i class="fas fa-location-arrow"></i>
                            <x-utils.link
                                class="card-header-action"
                                :text="__('Location')"
                                data-toggle="modal"
                                data-target=".location"
                            />
                        </div>
                        <div class="p-1 float-right">
                            <i class="fas fa-history"></i>
                            <x-utils.link
                                class="card-header-action"
                                :text="__('Login History')"
                                data-toggle="modal"
                                data-target=".login-history"
                            />
                        </div>
                        <div class="p-1 float-right">
                            <i class="fa fa-home"></i>
                            <x-utils.link
                                class="card-header-action"
                                :text="__('Household List')"
                                data-toggle="modal"
                                data-target=".household"
                            />
                        </div>
                    </div>
                </x-slot>

                <x-slot name="body">
                    <div class="accordion" id="leaderPersonalInfoParent">
                          <div id="leaderPersonalInfo" class="collapse show" aria-labelledby="headingOne" data-parent="#leaderPersonalInfoParent">
                            <div class="card-body">
                                <table class="table table-hover">

                                    <tr>
                                        <th>@lang('Avatar')</th>
                                        <td><img src="{{ $user->avatar }}" class="user-profile-image" /></td>
                                    </tr>

                                    <tr>
                                        <th>@lang('Name')</th>
                                        <td>{{ $user->first_name .' '. $user->middle_name . ' ' . $user->last_name }}</td>
                                    </tr>

                                    <tr>
                                        <th>@lang('Gender')</th>
                                        <td>{{ ucfirst($user->gender) }}</td>
                                    </tr>

                                     <tr>
                                        <th>@lang('Birthdate')</th>
                                        <td>{{ date("F j, Y", strtotime($user->birthday)) }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Phone')</th>
                                        <td>{{ $user->phone }}</td>
                                    </tr>

                                    <tr>
                                        <th>@lang('E-mail Address')</th>
                                        <td>{{ $user->email }}</td>
                                    </tr>

                                    <tr>
                                        <th>@lang('Status')</th>
                                        <td>@include('backend.auth.user.includes.status', ['user' => $user])</td>
                                    </tr>

                                    <tr>
                                        <th>@lang('Verified')</th>
                                        <td>@include('backend.auth.user.includes.verified', ['user' => $user])</td>
                                    </tr>

                                    {{-- <tr>
                                        <th>@lang('2FA')</th>
                                        <td>@include('backend.auth.user.includes.2fa', ['user' => $user])</td>
                                    </tr> --}}

                                    <tr>
                                        <th>@lang('Timezone')</th>
                                        <td>{{ $user->timezone ?? __('N/A') }}</td>
                                    </tr>

                                    <tr>
                                        <th>@lang('Last Login At')</th>
                                        <td>
                                            @if($user->loginHistories->sortBy(['created_at', 'asc'])->first())

                                            {{ date("F j, Y, g:i a", strtotime($user->loginHistories->sortBy('created_at')->last()->created_at)) }}

                                            @else
                                                @lang('N/A')
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>@lang('Last Known Mobile Device')</th>
                                        <td>{{ $user->loginHistories->sortBy(['created_at', 'asc'])->first()->name ?? __('N/A') }}</td>
                                    </tr>

                                    @if ($user->isSocial())
                                        <tr>
                                            <th>@lang('Provider')</th>
                                            <td>{{ $user->provider ?? __('N/A') }}</td>
                                        </tr>

                                        <tr>
                                            <th>@lang('Provider ID')</th>
                                            <td>{{ $user->provider_id ?? __('N/A') }}</td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <th>@lang('Address')</th>

                                        <td> {{
                                            $user->address->barangay->barangay_description . ' ' .
                                            $user->address->city->city_municipality_description . ' ' .
                                            $user->address->province->province_description . ' ' .
                                            $user->address->region->region_description
                                        }}</td>
                                    </tr>
                                    @if($user->leader->upperLeader)
                                        <tr>
                                            <th>@lang('Upper Leader')</th>

                                            <td><a href="{{ route('admin.leader.show', ['leader' => $user->leader->upperLeader->id]) }}" />
                                            {{
                                                $user->leader->upperLeader->user->first_name . ' ' .
                                                $user->leader->upperLeader->user->middle_name . ' ' .
                                                $user->leader->upperLeader->user->last_name
                                            }}</a></td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <th>@lang('Scope')</th>

                                        <td>
                                            {{ $leader->address->island->name ?? '' }}
                                            {{ $leader->address->region->region_description ?? '' }}
                                            {{ $leader->address->province->province_description ?? '' }}
                                            {{ $leader->address->address->city->city_municipality_description ?? '' }}
                                            {{ $leader->barangay->barangay_description ?? '' }}
                                        </td>
                                    </tr>

                                    {{-- <tr>
                                        <th>@lang('Roles')</th>
                                        <td>{!! $user->roles_label !!}</td>
                                    </tr> --}}

                                    {{-- <tr>
                                        <th>@lang('Additional Permissions')</th>
                                        <td>{!! $user->permissions_label !!}</td>
                                    </tr> --}}
                                </table>
                            </div>
                          </div>
                        </div>
                </x-slot>
            </x-backend.card>
            <div class="modal fade edit" tabindex="-1" role="dialog" aria-labelledby="messages" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="exampleModalLabel"><i class="fas fa-edit pr-2"></i>Edit Personal Information</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                    <livewire:leader.edit-leader-personal-info user="{{ $user->id }}" />
                  </div>
                </div>
              </div>
            <div class="modal fade messages" tabindex="-1" role="dialog" aria-labelledby="messages" aria-hidden="true">
                <div class="modal-dialog modal-lg">

                  <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="exampleModalLabel"><i class="fas fa-sms pr-2"></i>Messages</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                    <livewire:phone-logs.show-messages user="{{ $user->id }}" />
                  </div>
                </div>
              </div>
              <div class="modal fade contacts" tabindex="-1" role="dialog" aria-labelledby="contacts" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="exampleModalLabel"><i class="fas fa-address-book pr-2"></i>Contacts</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <livewire:phone-logs.show-contacts user="{{ $user->id }}" />
                  </div>
                </div>
              </div>
              <div class="modal fade gallery" tabindex="-1" role="dialog" aria-labelledby="gallery" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                  <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="exampleModalLabel"><i class="fas fa-images  pr-2"></i></i>Gallery</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="p-3 d-flex justify-content-center">
                        @foreach ($user->images as $image)

                            <div class="card p-2 m-2" style="width: 22rem;">
                                {{-- <svg class="bd-placeholder-img card-img-top" width="100%" height="180" xmlns="http://www.w3.org/2000/svg" aria-label="Placeholder: Image cap" preserveAspectRatio="xMidYMid slice" role="img">
                                    <title>Placeholder</title>
                                    <rect width="100%" height="100%" fill="#868e96"/>
                                    <text x="50%" y="50%" fill="#dee2e6" dy=".3em">Image cap</text>
                                </svg> --}}
                                <img src="{{ $image->directory_s3 }}" />
                                <div class="card-body">
                                <div class="row"><h5 class="card-title">Date Uploaded : </h5>
                                    <span> {{ date("F j, Y, g:i a", strtotime($image->created_at)) }}</span>
                                </div>
                                <p class="card-text">{{ $image->description }}</p>
                                <a href="{{ route('admin.image.show', ['file' => $image->id]) }}" class="btn btn-primary" target="_blank">Open</a>
                                </div>
                            </div>

                        @endforeach
                          </div>

                  </div>
                </div>
              </div>

              <div class="modal fade location" id="location" tabindex="-1" role="dialog" aria-labelledby="location" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="exampleModalLabel"><i class="fas fa-location-arrow pr-2"></i>Location</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                        <input type="hidden" id="leader-id-location" value="{{ $user->id }}" />
                      <livewire:phone-logs.show-location user="{{ $user->id }}" />
                  </div>
                </div>
              </div>

              <div class="modal fade login-history" tabindex="-1" role="dialog" aria-labelledby="login-history" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="exampleModalLabel"><i class="fas fa-history pr-2"></i></i>Login History</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                        <input type="hidden" id="leader-id-location" value="{{ $user->id }}" />
                      <livewire:phone-logs.show-login-history user="{{ $user->id }}" />
                  </div>
                </div>
              </div>

              <div class="modal fade household" tabindex="-1" role="dialog" aria-labelledby="household" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="exampleModalLabel"><i class="fa fa-home pr-2"></i></i></i>household List</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                        <input type="hidden" id="leader-id-location" value="{{ $user->id }}" />
                        <div class="m-4">
                      <livewire:household.household-list leaderId="{{ $user->id }}" />
                        </div>
                  </div>
                </div>
              </div>

        </x-slot>

        <x-slot name="footer">
            <small class="float-right text-muted">
                <strong>@lang('Account Created'):</strong> @displayDate($user->created_at) ({{ $user->created_at->diffForHumans() }}),
                <strong>@lang('Last Updated'):</strong> @displayDate($user->updated_at) ({{ $user->updated_at->diffForHumans() }})

                @if($user->trashed())
                    <strong>@lang('Account Deleted'):</strong> @displayDate($user->deleted_at) ({{ $user->deleted_at->diffForHumans() }})
                @endif
            </small>
        </x-slot>
    </x-backend.card>

@endsection
