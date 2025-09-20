<?php

namespace App\Http\Controllers\AJAX;

use App\Http\Controllers\Controller;
use App\Http\Requests\AJAX\StoreChartOfAccountRequest;
use App\Http\Requests\AJAX\UpdateChartOfAccountRequest;
use App\Models\ChartOfAccount;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class ChartOfAccountController extends Controller
{
    use ApiResponse;

    public function list()
    {
        try {
            return $this->success('Data retrieved successfully', ChartOfAccount::all());
        } catch (Exception $e) {
            return $this->error('Failed to retrieve data', 500, $e->getMessage());
        }
    }

    public function detail($id)
    {
        try {
            return $this->success('Detail retrieved successfully', ChartOfAccount::findOrFail($id));
        } catch (ModelNotFoundException $e) {
            return $this->error('COA not found', 404);
        } catch (Exception $e) {
            return $this->error('Failed to retrieve detail', 500, $e->getMessage());
        }
    }

    public function create(StoreChartOfAccountRequest $request)
    {
        try {
            return $this->success('COA created successfully', ChartOfAccount::create($request->validated()), 201);
        } catch (Exception $e) {
            return $this->error('Failed to create COA', 500, $e->getMessage());
        }
    }

    public function edit(UpdateChartOfAccountRequest $request, $id)
    {
        try {
            $coa = ChartOfAccount::findOrFail($id);
            $coa->update($request->validated());

            return $this->success('COA updated successfully', $coa);
        } catch (ModelNotFoundException $e) {
            return $this->error('COA not found', 404);
        } catch (Exception $e) {
            return $this->error('Failed to update COA', 500, $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $coa = ChartOfAccount::findOrFail($id);
            $coa->delete();

            return $this->success('COA deleted successfully');
        } catch (ModelNotFoundException $e) {
            return $this->error('COA not found', 404);
        } catch (Exception $e) {
            return $this->error('Failed to delete COA', 500, $e->getMessage());
        }
    }
}