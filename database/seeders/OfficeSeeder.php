<?php

namespace Database\Seeders;

use App\Models\Office;
use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $offices = [
            [
                'name' => "FO XI",
                'title' => "REGION XI [Davao Region]",
            ],
            [
                'name' => "XI-FO-4PS-AS",
                'title' => "FO Pantawid - Administrative Section",
            ],
            [
                'name' => "XI-FO-4PS-BDM",
                'title' => "FO Pantawid - Beneficiary Data Management Section",
            ],
            [
                'name' => "XI-FO-4PS-CBS",
                'title' => "FO Pantawid - Capability Building Division",
            ],
            [
                'name' => "XI-FO-4PS-CVS",
                'title' => "FO Pantawid - Compliance Verification Section",
            ],
            [
                'name' => "XI-FO-4PS-FDS",
                'title' => "FO Pantawid - Family Development Section",
            ],
            [
                'name' => "XI-FO-4PS-GRS",
                'title' => "FO Pantawid - Grievance Redress Section",
            ],
            [
                'name' => "XI-FO-4PS-IPD",
                'title' => "FO Pantawid - Institutional Partnership Section",
            ],
            [
                'name' => "XI-FO-4PS-MCCT",
                'title' => "FO Pantawid - Modified Conditional Cash Transfer Section",
            ],
            [
                'name' => "XI-FO-4PS-OPS",
                'title' => "FO Pantawid - Operation",
            ],
            [
                'name' => "XI-FO-4PS-PMES",
                'title' => "FO Pantawid - Planning and Monitoring and Evaluation Section",
            ],
            [
                'name' => "XI-FO-4PS-POO-DC",
                'title' => "FO Pantawid Operations Office Davao City ",
            ],
            [
                'name' => "XI-FO-4PS-POO-DDN",
                'title' => "FO Pantawid Operations Office Davao del Norte ",
            ],
            [
                'name' => "XI-FO-4PS-POO-DDO",
                'title' => "FO Pantawid Operations Office Davao de Oro ",
            ],
            [
                'name' => "XI-FO-4PS-POO-DDS",
                'title' => "FO Pantawid Operations Office Davao del Sur ",
            ],
            [
                'name' => "XI-FO-4PS-POO-DO",
                'title' => "FO Pantawid Operations Office Davao Oriental ",
            ],
            [
                'name' => "XI-FO-4PS-POO-DOCC",
                'title' => "FO Pantawid Operations Office Davao Occidental ",
            ],
            [
                'name' => "XI-FO-4PS-RPMO",
                'title' => "FO Pantawid Regional Program Management Office",
            ],
            [
                'name' => "XI-FO-4PS-SMU",
                'title' => "FO Pantawid - Social Marketing Unit",
            ],
            [
                'name' => "XI-FO-4PS-SSDMS",
                'title' => "FO Pantawid - Social Services Delivery and Management Section",
            ],
            [
                'name' => "XI-FO-AD",
                'title' => "FO Administrative Division",
            ],
            [
                'name' => "XI-FO-AD-GSS",
                'title' => "FO General Service Section",
            ],
            [
                'name' => "XI-FO-AD-PS",
                'title' => "FO Procurement Section",
            ],
            [
                'name' => "XI-FO-AD-PSS",
                'title' => "FO Property and Supply Section",
            ],
            [
                'name' => "XI-FO-AD-RAMS",
                'title' => "FO Records Management Section",
            ],
            [
                'name' => "XI-FO-BAC",
                'title' => "FO Bids and Awards Committee",
            ],
            [
                'name' => "XI-FO-CBS-MTA",
                'title' => "FO Minors Travelling Abroad Office",
            ],
            [
                'name' => "XI-FO-CBSS-AH",
                'title' => "FO CBSS Angels Haven",
            ],
            [
                'name' => "XI-FO-CBSS-ANVRC",
                'title' => "FO A/NVRC Island Cluster",
            ],
            [
                'name' => "XI-FO-CBSS-HA",
                'title' => "FO CBSS Home for the Aged",
            ],
            [
                'name' => "XI-FO-CBSS-RHGW",
                'title' => "FO Regional Home for Girls and Women",
            ],
            [
                'name' => "XI-FO-CBSS-RSCC",
                'title' => "FO Reception and Study Center for Children",
            ],
            [
                'name' => "XI-FO-CBSS-RSCY",
                'title' => "FO Regional Rehabilitation Center for Youth",
            ],
            [
                'name' => "XI-FO-COA",
                'title' => "FO Commision on Audit",
            ],
            [
                'name' => "XI-FO-COMBS-ARRS",
                'title' => "FO Adoption Resource and Referral Section",
            ],
            [
                'name' => "XI-FO-COMBS-SFP",
                'title' => "FO Supplementary Feeding Program",
            ],
            [
                'name' => "XI-FO-COMBS-SPO",
                'title' => "FO Social Pension Office",
            ],
            [
                'name' => "XI-FO-DRMD",
                'title' => "FO Disaster Response Management Division",
            ],
            [
                'name' => "XI-FO-DRMD-DRIMS",
                'title' => "FO Regional Response &amp; Information Management Section",
            ],
            [
                'name' => "XI-FO-DRMD-DRRMS",
                'title' => "FO Disaster Response Rehabilitation Managment Section",
            ],
            [
                'name' => "XI-FO-DRMD-RROS",
                'title' => "FO Regiona Resource Operation Section",
            ],
            [
                'name' => "XI-FO-FMD",
                'title' => "FO Financial Management Division",
            ],
            [
                'name' => "XI-FO-FMD-AS",
                'title' => "FO Accounting Section",
            ],
            [
                'name' => "XI-FO-FMD-BS",
                'title' => "FO Budget Section",
            ],
            [
                'name' => "XI-FO-FMD-CS",
                'title' => "FO Cash Section",
            ],
            [
                'name' => "XI-FO-HRMDD",
                'title' => "FO Human Resource Management and Development Division",
            ],
            [
                'name' => "XI-FO-HRMDD-HRPPMS",
                'title' => "FO Human Resource Planning and Performance Management Section",
            ],
            [
                'name' => "XI-FO-HRMDD-HRWS",
                'title' => "FO Human Resource Welfare Section",
            ],
            [
                'name' => "XI-FO-HRMDD-LDS",
                'title' => "FO Learning and Development Section",
            ],
            [
                'name' => "XI-FO-HRMDD-PAS",
                'title' => "FO Personal Administration Section",
            ],
            [
                'name' => "XI-FO-IAU",
                'title' => "FO Internal Audit Unit",
            ],
            [
                'name' => "XI-FO-KC",
                'title' => "FO Kalhi-CIDSS PMO",
            ],
            [
                'name' => "XI-FO-LU",
                'title' => "FO Legal Unit",
            ],
            [
                'name' => "XI-FO-OARDA",
                'title' => "FO Office of Assistant Regional Director for Administration",
            ],
            [
                'name' => "XI-FO-OARDO",
                'title' => "FO Office of Assistant Regional Director for Operation",
            ],
            [
                'name' => "XI-FO-ORD",
                'title' => "FO Office of the Regional Director",
            ],
            [
                'name' => "XI-FO-ORD-STU",
                'title' => "FO Social Technology Unit",
            ],
            [
                'name' => "XI-FO-PPD",
                'title' => "FO Policy and Plans Division",
            ],
            [
                'name' => "XI-FO-PPD-ICT",
                'title' => "FO Information Planning Communication Technology Section",
            ],
            [
                'name' => "XI-FO-PPD-NHTS",
                'title' => "FO National Household Targetting Section",
            ],
            [
                'name' => "XI-FO-PPD-PDPS",
                'title' => "FO Policy Development and Planning Section",
            ],
            [
                'name' => "XI-FO-PPD-SS",
                'title' => "FO Standard Section",
            ],
            [
                'name' => "XI-FO-PPD-UCT",
                'title' => "FO Unconditional Cash Transfer",
            ],
            [
                'name' => "XI-FO-PROMSD",
                'title' => "FO Promotive Service Division",
            ],
            [
                'name' => "XI-FO-PROMSD-EPAHP",
                'title' => "FO Enhance Partnership Against Hunger and Poverty",
            ],
            [
                'name' => "XI-FO-PROMSD-SLP",
                'title' => "FO Sustainable Livelihood Program PMO",
            ],
            [
                'name' => "XI-FO-PSD",
                'title' => "FO Protective Service Division",
            ],
            [
                'name' => "XI-FO-PSD-CBS",
                'title' => "FO Capacity Building Section",
            ],
            [
                'name' => "XI-FO-PSD-CBSS",
                'title' => "FO Center Based Services Section",
            ],
            [
                'name' => "XI-FO-PSD-CIS",
                'title' => "FO Crisis Intervention Section",
            ],
            [
                'name' => "XI-FO-PSD-COMBS",
                'title' => "FO Community Based Services Section",
            ],
            [
                'name' => "XI-FO-SMU",
                'title' => "FO Social Marketing Unit",
            ],
            [
                'name' => "XI-FO-SWAD-DC",
                'title' => "FO Social Welfare and Development Office- Davao City Third District",
            ],
            [
                'name' => "XI-FO-SWAD-DDN",
                'title' => "FO Social Welfare and Development Office- Davao del Norte ",
            ],
            [
                'name' => "XI-FO-SWAD-DDO",
                'title' => "FO Social Welfare and Development Office- Davao de Oro ",
            ],
            [
                'name' => "XI-FO-SWAD-DDS",
                'title' => "FO Social Welfare and Development Office- Davao del Sur ",
            ],
            [
                'name' => "XI-FO-SWAD-DO",
                'title' => "FO Social Welfare and Development Office- Davao Oriental ",
            ],
            [
                'name' => "XI-FO-SWAD-DOCC",
                'title' => "FO Social Welfare and Development Office- Davao Occidental ",
            ],
        ];

        foreach ($offices as $office) {
            $created_office = Office::create($office);
            echo $created_office->name." \n";
        }
    }
}
