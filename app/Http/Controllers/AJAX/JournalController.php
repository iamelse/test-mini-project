<?php

namespace App\Http\Controllers\AJAX;

use App\Http\Controllers\Controller;
use App\Http\Requests\AJAX\StoreJournalRequest;
use App\Http\Requests\AJAX\UpdateJournalRequest;
use App\Models\Journal;
use App\Models\JournalLine;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class JournalController extends Controller
{
    use ApiResponse;

    public function list()
    {
        try {
            $journals = Journal::with('lines')
                ->orderByDesc('posting_date')
                ->get()
                ->map(function($journal) {
                    return [
                        'id' => $journal->id,
                        'ref_no' => $journal->ref_no,
                        'posting_date' => $journal->posting_date,
                        'memo' => $journal->memo,
                        'status' => $journal->status,
                        'debit_total' => $journal->lines->sum(fn($line) => $line->debit) ?: 0,
                        'credit_total' => $journal->lines->sum(fn($line) => $line->credit) ?: 0,
                    ];
                });

            return $this->success('Data retrieved successfully', $journals);
        } catch (Exception $e) {
            return $this->error('Failed to retrieve data', 500, $e->getMessage());
        }
    }

    public function detail($id)
    {
        try {
            $journal = Journal::with('lines')->findOrFail($id);

            $journal->debit_total = $journal->lines->sum(fn($line) => $line->debit) ?: 0;
            $journal->credit_total = $journal->lines->sum(fn($line) => $line->credit) ?: 0;

            return $this->success('Detail retrieved successfully', $journal);
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

            // Tambahkan lines jika ada input lines dari frontend
            if ($request->has('lines')) {
                foreach ($request->lines as $line) {
                    $journal->lines()->create($line);
                }
            }

            $journal->debit_total = $journal->lines->sum(fn($line) => $line->debit);
            $journal->credit_total = $journal->lines->sum(fn($line) => $line->credit);

            return $this->success('Journal created successfully', $journal, 201);
        } catch (Exception $e) {
            return $this->error('Failed to create Journal', 500, $e->getMessage());
        }
    }

    public function edit(UpdateJournalRequest $request, $id)
    {
        try {
            $journal = Journal::with('lines')->findOrFail($id);
            $journal->update($request->validated());

            // Update lines jika ada input baru
            if ($request->has('lines')) {
                // Hapus lines lama
                $journal->lines()->delete();
                // Tambahkan line baru
                foreach ($request->lines as $line) {
                    $journal->lines()->create($line);
                }
            }

            $journal->debit_total = $journal->lines->sum(fn($line) => $line->debit);
            $journal->credit_total = $journal->lines->sum(fn($line) => $line->credit);

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