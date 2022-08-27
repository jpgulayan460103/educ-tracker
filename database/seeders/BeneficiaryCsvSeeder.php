<?php

namespace Database\Seeders;

use App\Models\Beneficiary;
use App\Models\BioParent;
use App\Models\Client;
use App\Models\Composition;
use App\Models\Office;
use App\Models\SchoolLevel;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SwadOffice;
use Carbon\Carbon;
use League\Csv\Reader;


class BeneficiaryCsvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = $this->json();
        $json = json_decode($this->json(), true);
        $i = 0;

        
        foreach ($json as $key => $user_data) {

            
            $i++;
            if($i == 1){
                // var_dump($user_data);
                continue;
            }

            $client = Client::create([
                'last_name' => '',
                'first_name' => '',
                'middle_name' => '',
                'ext_name' => '',
                'full_name' => '',
                'street_number' => '',
                'psgc_id' => 378,
                'mobile_number' => '',
                'birth_date' => Carbon::now(),
                'age' => 0,
                'gender' => '',
                'occupation' => '',
                'monthly_salary' => 0,
                'relationship_beneficiary' => '',
                'sector_id' => 1,
            ]);
            $composition = Composition::create([
                'client_id' => $client->id,
                'user_id' => 1
            ]);
    
            $father = new BioParent([
                'last_name' => '',
                'first_name' => '',
                'middle_name' => '',
                'ext_name' => '',
                'full_name' => 'DSWD',
                'birth_date' => Carbon::now(),
            ]);
            $father->relationship_beneficiary = "father";
            $mother = new BioParent([
                'last_name' => '',
                'first_name' => '',
                'middle_name' => '',
                'ext_name' => '',
                'full_name' => 'DSWD',
                'birth_date' => Carbon::now(),
            ]);
            $mother->relationship_beneficiary = "mother";
            
            $composition->father()->save($father);
            $composition->mother()->save($mother);
            $swad_office_name = $user_data[0];
            $insert_data['last_name'] = $user_data[1];
            $school_level_name = $user_data[2];
            

            $swad_office = SwadOffice::where('name', $swad_office_name)->first();
            $school_level = SchoolLevel::where('name', $school_level_name)->first();
            $insert_data['swad_office_id'] = $swad_office->id;
            $insert_data['school_level_id'] = $school_level->id;
            $insert_data['payout_id'] = 1;
            $insert_data['composition_id'] = $composition->id;
            $insert_data['status'] = "Claimed";
            $insert_data['amount_granted'] = $school_level->amount;
            $format = $swad_office->code."-01-";
            $next_control_number = 1;
            $last_beneficiary = Beneficiary::where('control_number', 'like', "$format%")->orderBy('id','desc')->first();
            if($last_beneficiary){
                $last_control_number_split = explode("-", $last_beneficiary->control_number);
                $last_control_number = end($last_control_number_split);
                $next_control_number = (int)$last_control_number + 1;
            }
            $insert_data['control_number'] = $format.str_pad($next_control_number, 6, "0", STR_PAD_LEFT);
            $beneficiary = Beneficiary::create($insert_data);
            echo "created beneficiary: $beneficiary->last_name \n";
        }
    }

    public function json()
    {
        $reader = Reader::createFromPath(public_path('/dataseeders/beneficiaries.csv'), 'r');
        $results = $reader->getRecords();
        $data = array();
        foreach ($results as $key => $row) {
            $data[] = $row;
        }
        return $this->safe_json_encode($data);
    }

    private function safe_json_encode($value, $options = 0, $depth = 512){
		$encoded = json_encode($value, $options, $depth);
		switch (json_last_error()) {
			case JSON_ERROR_NONE:
				return $encoded;
			case JSON_ERROR_DEPTH:
				return 'Maximum stack depth exceeded'; // or trigger_error() or throw new Exception()
			case JSON_ERROR_STATE_MISMATCH:
				return 'Underflow or the modes mismatch'; // or trigger_error() or throw new Exception()
			case JSON_ERROR_CTRL_CHAR:
				return 'Unexpected control character found';
			case JSON_ERROR_SYNTAX:
				return 'Syntax error, malformed JSON'; // or trigger_error() or throw new Exception()
			case JSON_ERROR_UTF8:
				$clean = $this->utf8ize($value);
				return $this->safe_json_encode($clean, $options, $depth);
			default:
				return 'Unknown error'; // or trigger_error() or throw new Exception()

		}
    }
    
    private function utf8ize($d) {
		if (is_array($d)) {
			foreach ($d as $k => $v) {
				$d[$k] = $this->utf8ize($v);
			}
		} else if (is_string ($d)) {
			return utf8_encode($d);
		}
		return $d;
	}
}
