<div>
    <div class="p-5">
        <div class="form-row">
            <div class="form-group col-12 pl-0">
                <label for="inputState"><h5>Personal Information</h5></label>
            </div>
            <div class="form-group col-md-4">
            <label for="first_name">First name</label>
            <input type="text" wire:model="firstName" name="first_name" value="{{ $firstName }}" class="form-control" id="first_name" placeholder="First Name" required>
            @error('firstName') <span class="text-danger">First Name is Required</span> @enderror
        </div>
            <div class="form-group col-md-4">
            <label for="middle_name">Middle Name</label>
            <input type="text" wire:model="middleName" class="form-control" value="{{ $middleName }}" id="middle_name" name="middle_name" placeholder="Middle Name" required>
            @error('middleName') <span class="text-danger">Middle Name is Required</span> @enderror
        </div>
            <div class="form-group col-md-4">
            <label for="last_name">Last Name</label>
            <input type="text" wire:model="lastName" class="form-control" value="{{ $lastName }}" id="last_name" name="last_name" placeholder="Last Name" required>
            @error('lastName') <span class="text-danger">Last Name is Required</span> @enderror
        </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="gender">Gender</label>
                <select id="gender" wire:model="gender" class="form-control" name="gender" required>
                    @if($gender === 'male' || $gender === 'female')
                        <option selected value="{{ $gender }}">{{ ucfirst($gender) }}</option>
                    @else
                        <option selected>Choose...</option>
                    @endif

                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
                @error('gender') <span class="text-danger">Gender is Required</span> @enderror
                </div>
            <div class="form-group col-md-4">
            <label for="email">Email</label>
            <input type="email" wire:model="email" class="form-control" value="{{ $email }}" id="email" name="email" placeholder="example@gmail.com">
            @error('email') <span class="text-danger">Email is Required</span> @enderror
        </div>
            <div class="form-group col-md-4">
            <label for="phone">Phone</label>
            <input type="phone" wire:model="phone" class="form-control" id="phone" value="{{ $phone }}" name="phone" placeholder="09XXXXXXXXX" required>
            @error('phone') <span class="text-danger">Phone is Required</span> @enderror
        </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="birthday">Birthdate</label>
                <input value="{{ $birthday }}" wire:model="birthday" type="date" class="form-control" id="birthday" name="birthday" required>
                @error('birthday') <span class="text-danger">Birthday is Required</span> @enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <label><h6>Current Address: </h6> {{ $barangayDescription . ' ' . $cityDescription . ' '. $provinceDescription .' '. $regionDescription  }}</label>
                </div>

        </div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <label>Search Address</label>
                <livewire:location-search event="setLeaderAddress" />
            </div>

        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" wire:click="save">Save</button>
        </div>
    </div>
</div>
