<div>
    <div wire:ignore.self class="modal fade" id="addLeader" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h3 class="modal-title" id="exampleModalLabel">Add Leader</h3>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div wire:ignore>
                <livewire:leader.add-sectoral-leader />
                <livewire:leader.leader-search />
                </div>
              <form action="{{ route('admin.leader.store') }}" method="POST">
                @csrf
                    @if($showData)
                    <div class="form-row">
                        <div class="form-group col-12 pl-0">
                            <label for="inputState"><h5>Scope</h5></label>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-10">
                            <label for="address"><i class="fas fa-location-arrow"></i> Scope :
                                {{ 'Purok : '. $purok ?? ''}}
                                {{ $barangay_description ?? ''}}
                                {{ $city_description ?? ''}}
                                {{ $province_description ?? ''}}
                                {{ $region_description ?? ''}}
                            </label>
                        </div>
                    </div>
                        <div class="form-row">
                            <div class="form-group col-12 pl-0">
                                <label for="inputState"><h5>Personal Information</h5></label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-8">
                            <label for="name"><i class="fas fa-user"></i> Name :
                                 {{ $firstName }}
                                 {{ $middleName }}
                                 {{ $lastName }}
                                </label>

                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-8">
                            <label for="gender"><i class="fas fa-venus-mars"></i> Gender : {{ $gender }}</label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-8">
                            <label for="email"><i class="fas fa-at"></i> Email : {{ $email ?? 'N/A'}}</label>
                            </div>

                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-8">
                                <label for="phone"><i class="fas fa-mobile"></i> Phone : </label>
                                <input type="text" class="form-control" name="phone" value="{{ $phone }}">
                            </div>
                            <div class="form-group col-md-8">
                                <label for="birthdate"><i class="fas fa-calendar"></i> Birthdate : {{ $birthday }}</label>
                            </div>

                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-10">
                                <label for="address"><i class="fas fa-location-arrow"></i> Address : {{ $address ?? 'N/A'}}</label>
                            </div>
                        </div>
                        <input type="hidden" class="form-control" name="user_id" value="{{ $userId }}">
                        <input type="hidden" class="form-control" name="region" value="{{ $region }}">
                        <input type="hidden" class="form-control" name="province" value="{{ $province }}">
                        <input type="hidden" class="form-control" name="city" value="{{ $city }}">
                        <input type="hidden" class="form-control" name="barangay" value="{{ $barangay }}">
                        <input type="hidden" class="form-control" name="zone" value="{{ $purok }}">
                        @endif
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" @if(!$showData) disabled @endif>Add Leader</button>
                        </div>

              </form>
            </div>

          </div>
        </div>
      </div>
</div>
