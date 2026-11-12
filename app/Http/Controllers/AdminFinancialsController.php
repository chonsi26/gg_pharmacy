<?php

namespace App\Http\Controllers;

use App\Models\Financial;
use App\Models\OrderItem;
use App\Models\Stock;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminFinancialsController extends Controller
{
    /**
     * READ — list financial records for the Financial Records page.
     *
     * Supports the same filters as the front-end toolbar:
     *   ?type=Income|Expense|all
     *   ?category=<name>|all
     *   ?date=YYYY-MM-DD
     *   ?search=<term>   (matches description or category)
     *
     * Also returns income/expense/net totals for the stat cards and the
     * distinct list of categories used to populate the filter dropdown
     * and the "add record" category datalist.
     */
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type'     => ['nullable', 'string'],
            'category' => ['nullable', 'string'],
            'date'     => ['nullable', 'date'],
            'search'   => ['nullable', 'string', 'max:255'],
        ]);

        $records = Financial::query()
            ->ofType($validated['type'] ?? null)
            ->ofCategory($validated['category'] ?? null)
            ->onDate($validated['date'] ?? null)
            ->search($validated['search'] ?? null)
            ->latestFirst()
            ->get();

        $manualIncome  = (float) $records->where('type', Financial::TYPE_INCOME)->sum('amount');
        $manualExpense = (float) $records->where('type', Financial::TYPE_EXPENSE)->sum('amount');

        // Automatic figures: customer order payments (income) and stock
        // unit costs (expense). They have no category/description, so they
        // are left out whenever a category or search filter is active.
        $date          = $validated['date'] ?? null;
        $type          = $validated['type'] ?? null;
        $hasTextFilter = ! empty($validated['category']) && $validated['category'] !== 'all'
                      || ! empty($validated['search']);

        $orderIncome  = 0.0;
        $stockExpense = 0.0;

        if (! $hasTextFilter) {
            if (! $type || in_array($type, ['all', Financial::TYPE_INCOME], true)) {
                // Completed (picked up) orders: sum of order_items.subtotal,
                // i.e. what customers actually paid for the items.
                $orderIncome = (float) OrderItem::query()
                    ->whereHas('order', function ($q) use ($date) {
                        $q->where('status', 'picked_up')
                          ->when($date, fn ($q) => $q->whereDate('picked_up_at', $date));
                    })
                    ->sum('subtotal');
            }

            if (! $type || in_array($type, ['all', Financial::TYPE_EXPENSE], true)) {
                // Every stock row's unit_cost added together.
                $stockExpense = (float) Stock::query()
                    ->when($date, fn ($q) => $q->whereDate('created_at', $date))
                    ->sum('unit_cost');
            }
        }

        $income  = $manualIncome + $orderIncome;
        $expense = $manualExpense + $stockExpense;

        return response()->json([
            'records' => $records,
            'stats' => [
                'income'  => round($income, 2),
                'expense' => round($expense, 2),
                'net'     => round($income - $expense, 2),
                'count'   => $records->count(),
                'breakdown' => [
                    'manual_income'  => $manualIncome,
                    'order_income'   => $orderIncome,
                    'manual_expense' => $manualExpense,
                    'stock_expense'  => $stockExpense,
                ],
            ],
            'categories' => Financial::query()
                ->select('category')
                ->distinct()
                ->orderBy('category')
                ->pluck('category'),
        ]);
    }

    /**
     * CREATE — add a new financial record.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $this->validateRecord($request);

        $record = Financial::create($validated);

        return response()->json([
            'message' => 'Record added.',
            'record'  => $record,
        ], 201);
    }

    /**
     * UPDATE — edit an existing financial record.
     */
    public function update(Request $request, Financial $financial): JsonResponse
    {
        $validated = $this->validateRecord($request);

        $financial->update($validated);

        return response()->json([
            'message' => 'Record updated.',
            'record'  => $financial->fresh(),
        ]);
    }

    /**
     * DELETE — remove a financial record.
     */
    public function destroy(Financial $financial): JsonResponse
    {
        $financial->delete();

        return response()->json([
            'message' => 'Record deleted.',
        ]);
    }

    /**
     * Shared validation rules for store/update.
     */
    private function validateRecord(Request $request): array
    {
        return $request->validate([
            'date'        => ['required', 'date'],
            'type'        => ['required', Rule::in([Financial::TYPE_INCOME, Financial::TYPE_EXPENSE])],
            'category'    => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'amount'      => ['required', 'numeric', 'min:0.01'],
        ]);
    }
}