<?php

namespace App\Helpers\Filters;

use App\Models\Partner;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FilterUserFullName implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $value = strtolower($value);
        $query->whereHas('user', function (Builder $query) use ($value) {
            $query->whereRaw("concat(first_name, ' ', last_name) like '%$value%' ");
        });
    }
}

class FilterByAuditorName implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $value  = strtolower($value);
        $query->whereHas('booking_allottee', function (Builder $query) use ($value) {
            $query->where('allottee_type', Partner::TYPE_AUDITOR)->whereHas('partner', function (Builder $query) use ($value) {
                $query->where('name', 'LIKE', "%$value%");
            });
        });
    }
}

class FilterByAuditorId implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $value  = strtolower($value);
        $query->whereHas('booking_allottee', function (Builder $query) use ($value) {
            $query->where('allottee_type', Partner::TYPE_AUDITOR)->whereHas('partner', function (Builder $query) use ($value) {
                $query->where('id', '=', $value);
            });
        });
    }
}
