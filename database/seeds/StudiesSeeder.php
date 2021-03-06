<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Study;
use Carbon\Carbon;
use Graze\ParallelProcess\Display\Table;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Process\Process;

class StudiesSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        DB::table('studies')->truncate();

        $directory_path = public_path() . "/trials/";
        $directory = new \RecursiveDirectoryIterator($directory_path, RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new \RecursiveIteratorIterator($directory);

        foreach ($iterator as $info) {
            $ext = pathinfo($info->getFilename(), PATHINFO_EXTENSION);
            $file_name = pathinfo($info->getFilename(), PATHINFO_FILENAME);
            $this->process_xml($info->getPathname());
        }
    }

    public function process_xml($file_name) {
        $study = new Study();
        $xml = simplexml_load_file($file_name);
        $study->nct_id = $xml->id_info->nct_id;
        $study->brief_title = $xml->brief_title;
        $study->official_title = $xml->official_title;
        $study->brief_summary = $xml->brief_summary;
        $study->detailed_description = $xml->detailed_description;
        $study->status = $xml->overall_status;
//                    $study->start_date = $xml->start_date;
//                    $study->completion_date = $xml->completion_date;
        $study->phase = $xml->phase;
        $study->study_type = $xml->study_type;
        //    $study->has_expanded_access = $xml->has_expanded_access;
        $study->source = $xml->source;
        $study->sponsors = json_encode($xml->sponsors);
        $study->oversight_info = json_encode($xml->source);
        $study->study_design_info = json_encode($xml->study_design_info);
        $study->primary_outcomes = json_encode($xml->primary_outcome);
        $study->secondary_outcomes = json_encode($xml->primary_outcome);
        $study->intervention = json_encode($xml->intervention);
        $study->eligibility = json_encode($xml->eligibility);
        $study->location = json_encode($xml->location);
        $study->urls = json_encode($xml->link);
        $study->references = json_encode($xml->reference);
        $study->keywords = json_encode($xml->keyword);
        $study->intervention_browse = json_encode($xml->intervention_browse);
        $study->provided_documents = json_encode($xml->provided_document_section);
        $study->save();
    }

}
