@extends('admin.admin_master')

@section('admin')

<br>
<div class="page-content">
    <div class="container-fluid">

        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">
                    <i class="fa fa-history me-1"></i> Activity Log
                </h4>
            </div>

            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 40px;">#</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Module</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th style="width: 90px;">Details</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($logs as $key => $log)

                                @php
                                    // Color per event type
                                    $eventColors = [
                                        'created' => 'success',
                                        'updated' => 'warning',
                                        'deleted' => 'danger',
                                        'login'   => 'info',
                                        'logout'  => 'secondary',
                                    ];
                                    $badgeColor = $eventColors[$log->event] ?? 'primary';

                                    // Get subject/module name (e.g. "User", "Product", etc.)
                                    $moduleName = $log->subject_type
                                        ? class_basename($log->subject_type)
                                        : '-';

                                    $old = collect(optional($log->properties)->get('old', []));
                                    $new = collect(optional($log->properties)->get('attributes', []));

                                    $hasChanges = $old->isNotEmpty() || $new->isNotEmpty();

                                    // Fields to ignore in the auto-description / diff table
                                    $ignoredFields = ['updated_at', 'created_at', 'deleted_at', 'id'];

                                    // Strip a leading "modulename_" prefix from field names
                                    // e.g. Brand model + "brand_name" -> "name" (avoids "Updated brand brand name")
                                    $moduleSnake = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $moduleName));

                                    $changedFields = $new->keys()
                                        ->reject(fn($f) => in_array($f, $ignoredFields))
                                        ->map(function ($f) use ($moduleSnake) {
                                            $label = str_starts_with($f, $moduleSnake . '_')
                                                ? substr($f, strlen($moduleSnake) + 1)
                                                : $f;

                                            return str_replace('_', ' ', $label);
                                        });

                                    $autoDescription = null;

                                    // Try to get the record's own identifying label (e.g. brand name "RCB")
                                    $subjectLabel = null;
                                    if ($log->subject) {
                                        $subjectLabel = $log->subject->{$moduleSnake . '_name'}
                                            ?? $log->subject->name
                                            ?? $log->subject->title
                                            ?? null;
                                    }

                                    // ---- Special case: Sale module ----
                                    // Show item name(s) + warehouse + customer instead of raw field names.
                                    if ($moduleName === 'Sale' && $log->subject) {

                                        $sale = $log->subject;

                                        $warehouseName = optional($sale->warehouse)->warehouse_name;
                                        $customerName  = optional($sale->customer)->customer_name;

                                        $itemNames = $sale->saleItems
                                            ->map(function ($item) {
                                                return optional($item->product)->name
                                                    ?? optional($item->product)->product_name
                                                    ?? 'Unknown item';
                                            })
                                            ->implode(', ');

                                        $saleSuffix = ($itemNames ? ' ' . $itemNames : '')
                                            . ($customerName ? ' to ' . $customerName : '')
                                            . ($warehouseName ? ' from ' . $warehouseName . ' warehouse' : '');

                                        if ($log->event === 'created') {
                                            $autoDescription = 'Sale' . $saleSuffix;
                                        } elseif ($log->event === 'updated') {
                                            $autoDescription = 'Updated sale' . $saleSuffix;
                                        } elseif ($log->event === 'deleted') {
                                            $autoDescription = 'Deleted sale' . $saleSuffix;
                                        }
                                    }

                                    // ---- Special case: Purchase module ----
                                    // Show item name(s) + warehouse instead of raw field names.
                                    if ($moduleName === 'Purchase' && $log->subject) {

                                        $purchase = $log->subject;

                                        $warehouseName = optional($purchase->warehouse)->warehouse_name;

                                        $itemNames = $purchase->purchaseItems
                                            ->map(function ($item) {
                                                return optional($item->product)->name
                                                    ?? optional($item->product)->product_name
                                                    ?? 'Unknown item';
                                            })
                                            ->implode(', ');

                                        if ($log->event === 'created') {
                                            $autoDescription = 'Purchase'
                                                . ($itemNames ? ' ' . $itemNames : '')
                                                . ($warehouseName ? ' for ' . $warehouseName . ' warehouse' : '');
                                        } elseif ($log->event === 'updated') {
                                            $autoDescription = 'Updated purchase'
                                                . ($itemNames ? ' ' . $itemNames : '')
                                                . ($warehouseName ? ' for ' . $warehouseName . ' warehouse' : '');
                                        } elseif ($log->event === 'deleted') {
                                            $autoDescription = 'Deleted purchase'
                                                . ($itemNames ? ' ' . $itemNames : '')
                                                . ($warehouseName ? ' for ' . $warehouseName . ' warehouse' : '');
                                        }
                                    }

                                    // ---- Generic fallback for all other modules ----
                                    if (!$autoDescription) {
                                        if ($log->event === 'created') {
                                            $autoDescription = 'Created ' . strtolower($moduleName)
                                                . ($subjectLabel ? ' ' . $subjectLabel : '');
                                        } elseif ($log->event === 'deleted') {
                                            $autoDescription = 'Deleted ' . strtolower($moduleName)
                                                . ($subjectLabel ? ' ' . $subjectLabel : '');
                                        } elseif ($changedFields->isNotEmpty()) {
                                            $autoDescription = ucfirst($log->event) . ' '
                                                . strtolower($changedFields->implode(', '))
                                                . ($subjectLabel ? ' ' . $subjectLabel : ' ' . strtolower($moduleName));
                                        }
                                    }

                                    $displayDescription = $autoDescription ?? $log->description;
                                @endphp

                                <tr>
                                    <td>{{ $logs->firstItem() + $key }}</td>

                                    <td>
                                        @if($log->causer)
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="fa fa-user-circle text-secondary"></i>
                                                <span>{{ $log->causer->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-muted">
                                                <i class="fa fa-cogs me-1"></i> System
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="badge bg-{{ $badgeColor }}">
                                            {{ ucfirst($log->event) }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $moduleName }}
                                        </span>
                                    </td>

                                    <td>{{ $displayDescription }}</td>

                                    <td>
                                        <small>{{ $log->created_at->format('M d, Y h:i A') }}</small>
                                    </td>

                                    <td class="text-center">
                                        @if($hasChanges)
                                            <button
                                                class="btn btn-sm btn-outline-primary"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#log-details-{{ $log->id }}"
                                            >
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>

                                @if($hasChanges)
                                    <tr class="collapse" id="log-details-{{ $log->id }}">
                                        <td colspan="7" class="bg-light">
                                            <div class="p-2">
                                                <table class="table table-sm table-bordered mb-0 bg-white">
                                                    <thead>
                                                        <tr>
                                                            <th>Field</th>
                                                            <th>Old Value</th>
                                                            <th>New Value</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $fields = $new->keys()
                                                                ->merge($old->keys())
                                                                ->unique()
                                                                ->reject(fn($f) => in_array($f, $ignoredFields));

                                                            // Map foreign key field names to [ModelClass, display_column]
                                                            // so ids like warehouse_id show as the actual name instead of "1"
                                                            $fkMap = [
                                                                'warehouse_id' => [\App\Models\Warehouse::class, 'warehouse_name'],
                                                                'customer_id'  => [\App\Models\Customer::class, 'customer_name'],
                                                                'supplier_id'  => [\App\Models\Supplier::class, 'supplier_name'],
                                                                'product_id'   => [\App\Models\Product::class, 'name'],
                                                            ];

                                                            $resolveFk = function ($field, $value) use ($fkMap) {
                                                                if ($value === null || $value === '' || !isset($fkMap[$field])) {
                                                                    return $value;
                                                                }

                                                                [$modelClass, $labelColumn] = $fkMap[$field];

                                                                $related = $modelClass::find($value);

                                                                return $related->{$labelColumn} ?? $value;
                                                            };
                                                            // Nicer labels for foreign key fields (e.g. "warehouse_id" -> "Warehouse")
                                                            $fieldLabels = [
                                                                'warehouse_id' => 'Warehouse',
                                                                'customer_id'  => 'Customer',
                                                                'supplier_id'  => 'Supplier',
                                                                'product_id'   => 'Product',
                                                            ];

                                                            // Format date/datetime-looking values into a readable date
                                                            $formatValue = function ($field, $value) {
                                                                if ($value === null || $value === '') {
                                                                    return $value;
                                                                }

                                                                $looksLikeDate = str_ends_with($field, '_date')
                                                                    || str_ends_with($field, '_at');

                                                                if (!$looksLikeDate) {
                                                                    return $value;
                                                                }

                                                                try {
                                                                    return \Carbon\Carbon::parse($value)->format('M d, Y');
                                                                } catch (\Exception $e) {
                                                                    return $value;
                                                                }
                                                            };
                                                        @endphp

                                                        @forelse($fields as $field)
                                                            <tr>
                                                                <td class="fw-semibold">
                                                                    {{ $fieldLabels[$field] ?? ucfirst(str_replace('_', ' ', $field)) }}
                                                                </td>
                                                                <td class="text-danger">
                                                                    {{ $formatValue($field, $resolveFk($field, data_get($old, $field))) ?? '—' }}
                                                                </td>
                                                                <td class="text-success">
                                                                    {{ $formatValue($field, $resolveFk($field, data_get($new, $field))) ?? '—' }}
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="3" class="text-center text-muted">
                                                                    No field changes recorded.
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                @endif

                            @empty

                                <tr>
                                    <td colspan="7" class="text-center">
                                        No Activity Logs Found
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $logs->links() }}
                </div>

            </div>

        </div>

    </div>
</div>

@endsection
