<?php

namespace App\Templates\WorkPlan;

use App\Enums\MyanamrNumbers;
use App\Enums\PlanMonths;
use App\Models\Plan\WorkPlan;
use App\Traits\MPDFTrait;
use Illuminate\Support\Facades\File;

class ApproveLetter
{
    use MPDFTrait;

    protected string $template;
    protected $mpdf;
    protected WorkPlan $workPlan;
    protected ?string $description;

    public function __construct(WorkPlan $workPlan, ?array $data)
    {
        $this->workPlan = $workPlan->load(['division', 'district', 'approveOfficer']);
        $this->description = $data['description'];

        $this->template = File::get(storage_path('templates/WorkPlan/ApproveLetter.html'));

        $this->mpdf = $this->mpdf([
            'margin_left' => 11,
            'margin_right' => 11,
            'margin_top' => 33,
            'margin_bottom' => 0,
        ]);

        $this->mpdf->shrink_tables_to_fit = 0;

        $this->prepareLetter();
    }

    protected function prepareLetter(): void
    {
        $workPlan = $this->workPlan;
        $officer = auth()->user();

        $pairs = [
            'CASE_NO' => $workPlan->case_no,
            'TITLE' => $workPlan->title,
            'TO_OFFICER' => $workPlan->division?->name_mm ?? $workPlan->division?->name_en,
            'TO_OFFICE' => $workPlan->district?->name ?? '',
            'REFERENCE' => $workPlan->case_no,
            'DESCRIPTION' => $this->description ?? '',
            'FROM_OFFICER' => $officer?->full_name,
            'FROM_OFFICER_LEVEL'  => $officer?->name_with_role,
            'DATE' => convertToMyanmarDate(($workPlan->approved_at)),
            'PLAN_YEAR' => MyanamrNumbers::convertToMyanmar($workPlan->plan_year),
            'PLAN_MONTH' =>   PlanMonths::tryFrom($workPlan->plan_month)?->mmlabel()
        ];

        foreach ($pairs as $key => $value) {
            $this->template = str_replace("###{$key}###", $value ?? '', $this->template);
        }
    }

    public function generate(string $output = 'I', ?string $fileName = null)
    {
        $fileName ??= "approve-{$this->workPlan->case_no}.pdf";

        $this->mpdf->SetDefaultFont('pyidaungsu');
        $this->mpdf->SetFont('pyidaungsu', '', 12);
        $this->mpdf->WriteHTML($this->template);

        return $this->mpdf->Output($fileName, $output);
    }
}
