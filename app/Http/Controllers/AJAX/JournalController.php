<?php

namespace App\Http\Controllers\AJAX;

use App\Http\Controllers\Controller;
use App\Http\Requests\AJAX\StoreJournalRequest;
use App\Http\Requests\AJAX\UpdateJournalRequest;
use App\Models\Journal;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class JournalController extends Controller
{
    use ApiResponse;

    public function list()
    {
        try {
            return $this->success('Data retrieved successfully', Journal::orderByDesc('posting_date')->get());
        } catch (Exception $e) {
            return $this->error('Failed to retrieve data', 500, $e->getMessage());
        }
    }

    public function detail($id)
    {
        try {
            return $this->success('Detail retrieved successfully', Journal::findOrFail($id));
        } catch (ModelNotFoundException $e) {
            return $this->error('Journal not found', 404);
        } catch (Exception $e) {
            return $this->error('Failed to retrieve detail', 500, $e->getMessage());
        }
    }

    public function create(StoreJournalRequest $request)
    {
        try {
            $journal = Journal::create($request->validated());
            return $this->success('Journal created successfully', $journal, 201);
        } catch (Exception $e) {
            return $this->error('Failed to create Journal', 500, $e->getMessage());
        }
    }

    public function edit(UpdateJournalRequest $request, $id)
    {
        try {
            $journal = Journal::findOrFail($id);
            $journal->update($request->validated());

            return $this->success('Journal updated successfully', $journal);
        } catch (ModelNotFoundException $e) {
            return $this->error('Journal not found', 404);
        } catch (Exception $e) {
            return $this->error('Failed to update Journal', 500, $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $journal = Journal::findOrFail($id);
            $journal->delete();

            return $this->success('Journal deleted successfully');
        } catch (ModelNotFoundException $e) {
            return $this->error('Journal not found', 404);
        } catch (Exception $e) {
            return $this->error('Failed to delete Journal', 500, $e->getMessage());
        }
    }
}