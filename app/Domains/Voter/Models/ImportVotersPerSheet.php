<?php
namespace App\Domains\Voter\Models;

use App\Domains\Misc\Models\Precinct;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Domains\Auth\Models\User;
use App\Domains\Misc\Models\Address;
use App\Domains\Misc\Models\Barangay;
use DB;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ImportVotersPerSheet implements ToCollection, WithValidation, WithHeadingRow
{
    private $gender = [
        'M' => 'male',
        'F' => 'female'
    ];

    private $sheetName;
    public function __construct()
    {
        $this->sheetName = '';
    }

    public function collection(Collection $collection)
    {
        ini_set('max_execution_time', 0);
        foreach ($collection as $voter) {
            $this->insertVoter($voter);
        }
        return $collection;
    }

    private function formatBirthdate($birthday)
    {
        return Date::excelToDateTimeObject(intval($birthday));
    }

    private function insertVoter($voterData)
    {
        // dd($voterData);
        $this->getPrecinct($voterData['precinct']);
        if($voterData['first_name'] === '' && $voterData['middle_name'] === '' && $voterData['last_name'] === '')
        {
            return;
        }

        $voter = User::create([
            'added_by' => 0,
            'first_name' => $voterData['first_name'],
            'middle_name' => $voterData['middle_name'],
            'last_name' => $voterData['lastname'],
            'birthday' => $this->formatBirthdate($voterData['birthday']),
            'gender' => $this->setGender($voterData['gender']),
            'phone' => $this->parseContactNumber($voterData['contact']),
            'precinct_id' => $this->getPrecinct($voterData['precinct']),
            'religion' => $voterData['religion'],
            'occupation' => $voterData['occupation']
        ]);

        $barangay = Barangay::where('barangay_code', '021518013')->first();

        $address = Address::create([
            'addressable_type' => User::class,
            'addressable_id' => $voter->id,
            'scope_id' => 1,
            'region_code' => $barangay->region_code,
            'city_code' => $barangay->city_municipality_code,
            'province_code' => $barangay->province_code,
            'barangay_code' => $barangay->barangay_code,
        ], $voter);

    }

    private function setGender($gender)
    {
        if ($gender === 'M' || $gender === 'm' || $gender === 'MALE') {
            return 'male';
        }

        if($gender === "F" || $gender === 'f' || $gender === 'FEMALE') {
            return 'female';
        }

        return 'no-gender';
    }

    public function rules(): array
    {
        $this->getPrecinctList();
        return [
            // 'last_name' => 'required',
            // 'first_name' => 'required',
            // 'barangay' => ['required', Rule::in($this->getBarangays())],
            // 'precint_no' => ['required'],
            // 'contact_number' => 'required',
            // 'birthday' => 'required',
            // 'religion' => 'required',
            // 'occupation' => 'required',
            // 'gender' => 'required'
        ];
    }
    public function customValidationMessages()
    {
        return [
            'first_name.required' => 'First name is required in ' . $this->sheetName,
            'middle_name.required' => 'Middle name is required in ' . $this->sheetName,
            'last_name.required' => 'Last name is required in ' . $this->sheetName,
            'birthday.required' => 'Date of birth is required in ' . $this->sheetName,
            'gender.required' => 'Gender is required in ' . $this->sheetName,
            'contact_number.required' => 'phone is required in ' . $this->sheetName,
            'precint_no.required' => 'precinct # is required in ' . $this->sheetName,
            'religion.required' => 'religion is required in ' . $this->sheetName,
            'occupation.required' => 'occupation is required in ' . $this->sheetName,
            'barangay.required' => 'barangay is required in ' . $this->sheetName,
            'precint_no.in' => 'Precinct number is invalid in ' . $this->sheetName,
            'barangay.in' => ' does not exist in ' . $this->sheetName,
        ];
    }

    private function parseContactNumber($contactNumber)
    {
        if(strlen($contactNumber) !== 11 || strlen($contactNumber) !== 12)
        {
            $contactNumber = substr($contactNumber, 0, 12);
            if (substr($contactNumber,-1) === ".") {
                $contactNumber = substr($contactNumber, 0, 11);
            }
        }

        if (substr($contactNumber, 0, 1) === '6') {
            return '0'. substr($contactNumber, 2, 10);
        }

        if (substr($contactNumber, 0, 1) === '0') {
            return $contactNumber;
        }

        return $contactNumber;

    }

    private function getPrecinct($precinctNumber)
    {
        $precinct = Precinct::where('name', $precinctNumber)->first();

        if(isset($precinct)) {
            return $precinct->id;
        }
        if ($precinctNumber === null || $precinctNumber === '') {
            $precinctNumber = 'None';
        }

        $precinct = Precinct::create([
            'name' => $precinctNumber
        ]);

        return $precinct->id;

    }

    private function getPrecinctList()
    {
        $precincts = Precinct::all();
        $precinctLists = array();
        foreach($precincts as $precinct) {
            array_push($precinctLists, $precinct->name);
        }
        array_push($precinctLists, 'Precinct');

        return $precinctLists;
    }

    private function getBarangays()
    {

        $barangays = Barangay::where('city_municipality_code', '021521')->get();
        $barangayList = array();
        foreach($barangays as $barangay) {
            array_push($barangayList, $barangay->barangay_description);
        }
        return $barangayList;
    }

}
