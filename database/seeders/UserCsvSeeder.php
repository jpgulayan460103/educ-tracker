<?php

namespace Database\Seeders;

use App\Models\Office;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SwadOffice;
use League\Csv\Reader;

class UserCsvSeeder extends Seeder
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
            
            $insert_data['username'] = $user_data[0];
            $insert_data['last_name'] = strtoupper($user_data[1]);
            $insert_data['first_name'] = strtoupper($user_data[2]);
            $insert_data['middle_name'] = strtoupper($user_data[3]);
            $insert_data['ext_name'] = $user_data[4];
            $insert_data['password'] = "dswd12345";
            $swad_office_name = $user_data[5];
            $office_name = $user_data[6];

            $swad_office = SwadOffice::where('name', $swad_office_name)->first();
            $insert_data['swad_office_id'] = $swad_office->id;
            $office = Office::where('name', $office_name)->first();
            $insert_data['office_id'] = $office->id;
            $user = User::create($insert_data);
            echo "created user: $user->username \n";
        }
    }

    public function json()
    {
        $reader = Reader::createFromPath(public_path('/dataseeders/users.csv'), 'r');
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
