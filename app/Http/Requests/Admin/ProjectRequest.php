<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}
	
	protected function prepareForValidation()
	{
		$this->merge([
				'quote_check' => $this->has('quote_check') ? 1 : 0,
				'inspection_check' => $this->has('inspection_check') ? 1 : 0,
				'labour_report_check' => $this->has('labour_report_check') ? 1 : 0,
				'safety_talk_check' => $this->has('safety_talk_check') ? 1 : 0,
				'herbicide_check' => $this->has('herbicide_check') ? 1 : 0,
				'invoice_check' => $this->has('invoice_check') ? 1 : 0,
				'facilitation_check' => $this->has('facilitation_check') ? 1 : 0,
				'assessment_check' => $this->has('assessment_check') ? 1 : 0,
				'moderation_check' => $this->has('moderation_check') ? 1 : 0,
				'database_admin_check' => $this->has('database_admin_check') ? 1 : 0,
				'certification_check' => $this->has('certification_check') ? 1 : 0,
				'quote_id' => $this->input('quote_id') ?: null,
		]);
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
				'project_name' => 'required|string|max:255',
				'project_type_id' => 'required|exists:project_types,id',
				'startDate' => 'required|date',
				'endDate' => 'required|date|after_or_equal:startDate',
				'description' => 'required|string',
				'budget' => 'required|numeric|min:0',
				'actual_budget' => 'nullable|numeric|min:0',
				'status' => 'required|in:1,2,3',
				'location_id' => 'required|exists:locations,id',
				'team_leader_user_id' => 'required|exists:users,id',
				'target_hectares' => 'nullable|numeric|min:0',
				'actual_hectares' => 'nullable|numeric|min:0',
				'number_of_students' => 'nullable|integer|min:0',
				'quote_check' => 'boolean',
				'inspection_check' => 'boolean',
				'labour_report_check' => 'boolean',
				'safety_talk_check' => 'boolean',
				'herbicide_check' => 'boolean',
				'invoice_check' => 'boolean',
				'facilitation_check' => 'boolean',
				'assessment_check' => 'boolean',
				'moderation_check' => 'boolean',
				'database_admin_check' => 'boolean',
				'certification_check' => 'boolean',
				'quote_id' => 'nullable',
				'planned_days' => 'nullable|numeric|min:0',
				'hectares_per_day' => 'nullable|numeric|min:0',
				'total_budget' => 'nullable|numeric|min:0',
				'smme_id' => 'nullable|string',
				'vehicle_kms_target' => 'nullable|integer',
				'actual_vehicle_kms' => 'nullable|integer',
				'assets' => 'nullable|array',
				'assets.*' => 'exists:assets,id',
		];
	}
}