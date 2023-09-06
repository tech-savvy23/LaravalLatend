<?php

use App\Models\Checklist;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->checklists() as $checklist) {
            $createdCL = factory(Checklist::class)->create(['title' => $checklist['title']]);
            if (isset($checklist['types'])) {
                $this->createTypeAndProceed($checklist, $createdCL);
            } else {
                foreach ($checklist['reports'] as $report) {
                    $createdReport = $createdCL->reports()->create(['title'=> $report['title']]);
                    if (isset($report['options'])) {
                        $this->createOptionsAndProceed($report, $createdReport);
                    }
                }
            }
        }
    }

    public function createTypeAndProceed($checklist, $createdCL)
    {
        foreach ($checklist['types'] as $type) {
            $createdType = $createdCL->types()->create(['title' => $type['title']]);
            $this->createReportAndProceed($type, $createdType, $createdCL);
        }
    }

    public function createReportAndProceed($type, $createdType, $createdCL)
    {
        foreach ($type['reports'] as $report) {
            $createdReport = $createdCL->reports()->create([
                'title'=> $report['title'], 'checklist_type_id' => $createdType->id,
            ]);
            if (isset($report['options'])) {
                $this->createOptionsAndProceed($report, $createdReport);
            }
        }
    }

    public function createOptionsAndProceed($report, $createdReport)
    {
        foreach ($report['options'] as $option) {
            $createdOption = $createdReport->options()->create(['title' => $option['title']]);
            if (isset($option['messages'])) {
                $this->createOptionMessages($option, $createdOption);
            }
        }
    }

    public function createOptionMessages($option, $createdOption)
    {
        foreach ($option['messages'] as $message) {
            $createdOption->messages()->create(['report_id'=>$createdOption->report->id, 'message'=>$message]);
        }
    }

    public function checklists()
    {
        return [
            $this->getData('Electrical.php'),
            $this->getData('Meter.php'),
            $this->getData('DGSet.php'),
            $this->getData('Lightning.php'),
        ];
    }

    public function getData($filename)
    {
        return include database_path("seeds/ReportData/{$filename}");
    }
}
