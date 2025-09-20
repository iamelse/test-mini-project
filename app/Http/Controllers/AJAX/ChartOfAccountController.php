<?php

namespace App\Http\Controllers\AJAX;

use App\Http\Controllers\Controller;
use App\Models\ChartOfAccount;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;

class ChartOfAccountController extends Controller
{
    /**
     * Ambil semua data Chart of Account.
     */
    public function list()
    {
        try {
            $data = ChartOfAccount::orderBy('created_at', 'desc')->get();
            return response()->json([
                'status' => 'success',
                'message' => 'Data retrieved successfully',
                'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ambil detail 1 COA berdasarkan ID.
     */
    public function detail($id)
    {
        try {
            $coa = ChartOfAccount::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'message' => 'Detail retrieved successfully',
                'data' => $coa
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'COA not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve detail',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Simpan COA baru.
     */
    public function create(Request $request)
    {
        try {
            $validated = $request->validate([
                'code'           => 'required|string|max:10|unique:chart_of_accounts,code',
                'name'           => 'required|string|max:100',
                'normal_balance' => 'required|in:DR,CR',
                'is_active'      => 'nullable|boolean'
            ]);

            $coa = ChartOfAccount::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'COA created successfully',
                'data' => $coa
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create COA',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update COA.
     */
    public function edit(Request $request, $id)
    {
        try {
            $coa = ChartOfAccount::findOrFail($id);

            $validated = $request->validate([
                'code'           => 'required|string|max:10|unique:chart_of_accounts,code,' . $coa->id,
                'name'           => 'required|string|max:100',
                'normal_balance' => 'required|in:DR,CR',
                'is_active'      => 'nullable|boolean'
            ]);

            $coa->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'COA updated successfully',
                'data' => $coa
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'COA not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update COA',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus COA.
     */
    public function delete($id)
    {
        try {
            $coa = ChartOfAccount::findOrFail($id);
            $coa->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'COA deleted successfully'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'COA not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete COA',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}