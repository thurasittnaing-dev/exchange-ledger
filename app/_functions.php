<?php

use App\Models\Master\Department;
use App\Models\Management\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

if (!function_exists('timeFormat')) {
    function timeFormat($time, $second = false): String
    {
        return $second ? date('H:i:s', strtotime($time)) : date('H:i', strtotime($time));
    }
}

if (!function_exists('dateFormat')) {
    function dateFormat($date, $time = false): String
    {
        if ($date) {
            return $time ? date('d-m-Y H:i', strtotime($date)) : date('d-m-Y', strtotime($date));
        }
        return 'N/A';
    }
}

if (!function_exists('emoneyTotal')) {
    function emoneyTotal(): int
    {
        return (int) \App\Models\Account::query()->sum('balance');
    }
}


if (!function_exists('cashBalance')) {
    function cashBalance(): int
    {
        return \App\Models\CashBalance::amount();
    }
}


if (!function_exists('navGroupActive')) {
    function navGroupActive(array $menus): String
    {
        $class = '';

        $routeGroups = [
            'users' => ['users.index', 'users.create', 'users.edit'],
            'permissions' => ['permissions.index', 'permissions.create', 'permissions.edit'],
            'bank_types' => ['bank_types.index', 'bank_types.create', 'bank_types.edit'],
            'accounts' => ['accounts.index', 'accounts.create', 'accounts.edit'],
            'account_balance_histories' => ['account_balance_histories.index', 'account_balance_histories.create'],
            'cash_money' => ['cash_money.index', 'cash_money.create'],
            'transactions' => ['transactions.index', 'transactions.create'],
        ];

        foreach ($menus as $key => $menu) {
            $groups = $routeGroups[$menu];
            if (in_array(Route::currentRouteName(), $groups)) {
                $class =  'active open';
                break;
            }
        }

        return $class;
    }
}

if (!function_exists('navLinkActive')) {
    function navLinkActive(array $routes): String
    {
        $class = '';

        foreach ($routes as $key => $route) {
            if (Route::currentRouteName() == $route) {
                $class =  'active';
                break;
            }
        }

        return $class;
    }
}

if (!function_exists('getUsers')) {
    function getUsers(): Collection
    {
        return User::query()->get();
    }
}


if (!function_exists('usersCount')) {
    function usersCount(): int
    {
        return User::query()->count();
    }
}

if (!function_exists('departmentCount')) {
    function departmentCount(): int
    {
        return Department::query()->count();
    }
}




if (!function_exists('showError')) {
    function showError(Exception $e, string $customMessage = ''): void
    {
        Log::error($customMessage, [
            'error' => $e->getMessage()
        ]);
        if (config('app.debug'))  dd($e);
    }
}

if (!function_exists('convertToMyanmarDate')) {
    function convertToMyanmarDate($date)
    {

        if (!$date instanceof \DateTime) {
            $date = new \DateTime($date);
        }

        $myanmarMonths = [
            1 => "ဇန်နဝါရီ",
            "ဖေဖော်ဝါရီ",
            "မတ်",
            "ဧပြီ",
            "မေ",
            "ဇွန်",
            "ဇူလိုင်",
            "ဩဂုတ်",
            "စက်တင်ဘာ",
            "အောက်တိုဘာ",
            "နိုဝင်ဘာ",
            "ဒီဇင်ဘာ"
        ];

        $en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $my = ['၀', '၁', '၂', '၃', '၄', '၅', '၆', '၇', '၈', '၉'];

        $year = str_replace($en, $my, $date->format('Y'));
        $month = $myanmarMonths[(int)$date->format('m')];
        $day = str_replace($en, $my, (int)$date->format('d'));

        return "{$year} ခုနှစ်၊ {$month}လ၊ {$day} ရက်";
    }
}


if (!function_exists('isActionUnlocked')) {
    function isActionUnlocked($id): bool
    {
        $sessionKey = 'action_unlocked_until.' . $id;

        if (!session()->has($sessionKey)) {
            return false;
        }
        try {
            return \Carbon\Carbon::parse(session($sessionKey))->isFuture();
        } catch (\Exception $e) {
            return false;
        }
    }
}

if (!function_exists('log_model_create')) {
    function log_model_create($model, $action = 'created')
    {
        Log::info("Model Created Log", [
            'user_id'    => auth()->id() ?? 'N/A',
            'user_name'  => auth()->user()?->full_name ?? 'N/A',
            'model'      => get_class($model),
            'action'     => $action,
            'created_values' => $model->toArray(),
            'payload'    => ['ip' => request()->ip(), 'url' => request()->fullUrl()],
            'timestamp'  => now()->toDateTimeString()
        ]);
    }
}

if (!function_exists('log_model_update')) {
    function log_model_update($model, array $newData, $action = 'updated')
    {
        $oldValues = [];
        $newValues = [];

        foreach ($newData as $key => $newValue) {
            if ($model->$key != $newValue) {
                $oldValues[$key] = $model->getOriginal($key) ?? $model->$key;
                $newValues[$key] = $newValue;
            }
        }

        if (count($newValues) > 0) {
            Log::info("Model Updated Log", [
                'user_id'    => auth()->id() ?? 'N/A',
                'user_name'  => auth()->user()?->full_name ?? 'N/A',
                'model'      => get_class($model),
                'action'     => $action,
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'payload'    => ['ip' => request()->ip(), 'url' => request()->fullUrl()],
                'timestamp'  => now()->toDateTimeString()
            ]);
        }
    }
}

if (!function_exists('log_model_delete')) {
    function log_model_delete($model, $action = 'deleted')
    {
        Log::info("Model Deleted Log", [
            'user_id'    => auth()->id() ?? 'N/A',
            'user_name'  => auth()->user()?->full_name ?? 'N/A',
            'model'      => get_class($model),
            'action'     => $action,
            'deleted_values' => $model->toArray(),
            'payload'    => ['ip' => request()->ip(), 'url' => request()->fullUrl()],
            'timestamp'  => now()->toDateTimeString()
        ]);
    }
}
