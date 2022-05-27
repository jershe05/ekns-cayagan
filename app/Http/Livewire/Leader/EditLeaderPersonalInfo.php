<?php

namespace App\Http\Livewire\Leader;

use App\Domains\Auth\Models\User;
use App\Domains\Misc\Models\Barangay;
use Livewire\Component;

class EditLeaderPersonalInfo extends Component
{
    public $firstName;
    public $middleName;
    public $lastName;
    public $gender;
    public $email;
    public $phone;
    public $birthday;
    public $regionCode;
    public $regionDescription;
    public $provinceCode;
    public $provinceDescription;
    public $cityCode;
    public $cityDescription;
    public $barangayCode;
    public $barangayDescription;

    public $user;

    public $rules = [
        'firstName' => 'required',
        'middleName' => 'required',
        'lastName' => 'required',
        'gender' => 'required',
        'email' => 'required',
        'phone' => 'required',
        'birthday' => 'required'
    ];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->firstName = $user->first_name;
        $this->middleName = $user->middle_name;
        $this->lastName = $user->last_name;
        $this->gender = $user->gender;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->birthday = $user->birthday;

        $address = $user->address;
        $this->regionCode = $address->region_code;
        $this->regionDescription = $address->region->region_description;
        $this->provinceCode = $address->province_code;
        $this->provincedDescription = $address->province->province_description;
        $this->cityCode = $address->city_code;
        $this->cityDescription = $address->city->city_municipality_description;
        $this->barangayCode = $address->barangay_code;
        $this->barangayDescription = $address->barangay->barangay_description;


    }

    public function getListeners()
    {
        return [
            'setLeaderAddress' => 'setLeaderAddress'
        ];
    }

    public function setLeaderAddress($data)
    {
        if($data['type'] !== 'barangay')
        {
            $this->message = 'Please search for specific barangay...';
            return;
        }

        $this->message = null;
        $barangay = Barangay::where('barangay_code', $data['code']['barangay_code'])->first();

        $this->regionCode = $barangay->region_code;
        $this->regionDescription = $barangay->region->region_description;
        $this->provinceCode = $barangay->province_code;
        $this->provinceDescription = $barangay->province->province_description;
        $this->cityCode = $barangay->city_municipality_code;
        $this->cityDescription = $barangay->city->city_municipality_description;
        $this->barangayCode = $barangay->barangay_code;
        $this->barangayDescription = $barangay->barangay_description;
    }

    public function save()
    {
        $this->validate();
    
        $this->user->first_name = $this->firstName;
        $this->user->middle_name = $this->middleName;
        $this->user->last_name = $this->lastName;
        $this->user->gender = $this->gender;
        $this->user->email = $this->email;
        $this->user->phone = $this->phone;
        $this->user->birthday = $this->birthday;
        $this->user-> gender = $this->gender;

        $this->user->save();

        $address = $this->user->address;
        $address->region_code = $this->regionCode;
        $address->province_code = $this->provinceCode;
        $address->city_code = $this->cityCode;
        $address->barangay_code = $this->barangayCode;
        $address->save();

        return redirect()->route('admin.leader.show', ['leader' => $this->user->leader->id]);
    }

    public function render()
    {
        return view('livewire.leader.edit-leader-personal-info');
    }
}
