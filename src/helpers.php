<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Mulaidarinull\Larascaff\LarascaffConfig;
use Mulaidarinull\Larascaff\Models\Configuration\Menu;
use Mulaidarinull\Larascaff\Models\Configuration\SetupAplication;
use Mulaidarinull\Larascaff\Models\Record;

if (! function_exists('responseSuccess')) {
    function responseSuccess(string $message = 'Berhasil menyimpan data')
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
        ]);
    }
}

if (! function_exists('responseError')) {
    function responseError(string | \Exception $th)
    {
        $message = 'Terjadi kesalahan, silahkan coba beberapa saat lagi';
        $data = null;
        if ($th instanceof \Exception) {
            $message = $th->getMessage();
            if (config('app.debug')) {
                $message .= ' in line ' . $th->getLine() . ' at ' . $th->getFile();
                $data = $th->getTrace();
            }
        } else {
            $message = $th;
        }

        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => $data,
        ], 500);
    }
}

if (! function_exists('getFileNamespace')) {
    /**
     * get namespace from content file
     */
    function getFileNamespace($source)
    {
        if (preg_match('#(namespace)(\\s+)([A-Za-z0-9\\\\]+?)(\\s*);#sm', $source, $m)) {
            return $m[3] ?? null;
        }

        return null;
    }
}

if (! function_exists('menus')) {
    /**
     * @return Collection
     */
    function menus()
    {
        if (! Cache::has('menus')) {
            $menus = (new Menu)->getMenus()->groupBy('category');
            Cache::forever('menus', $menus);
        } else {
            $menus = Cache::get('menus');
        }

        return $menus;
    }
}

if (! function_exists('setRecord')) {
    function setRecord(Model $model)
    {
        app(Record::class)->setRecord($model);
    }
}

if (! function_exists('getRecord')) {
    function getRecord($key = null)
    {
        return app(Record::class)->getRecord($key);
    }
}

if (! function_exists('getPrefix')) {
    function getPrefix()
    {
        return app(LarascaffConfig::class)->getPrefix();
    }
}

if (! function_exists('routeDashboard')) {
    function routeDashboard()
    {
        $route = getPrefix() ? getPrefix() . 'dashboard' : 'dashboard';

        return $route;
    }
}

if (! function_exists('larascaffConfig')) {
    function larascaffConfig()
    {
        return app(LarascaffConfig::class);
    }
}

if (! function_exists('urlMenu')) {
    function urlMenu()
    {
        if (! Cache::has('urlMenu')) {
            $menus = menus()->flatMap(fn ($item) => $item);
            $url = [];
            foreach ($menus as $mm) {
                $url[] = $mm->url;
                foreach ($mm->subMenus as $sm) {
                    $url[] = $sm->url;
                    foreach ($sm->subMenus as $ssm) {
                        $url[] = $ssm->url;
                    }
                }
            }

            Cache::forever('urlMenu', $url);
        } else {
            $url = Cache::get('urlMenu');
        }

        return $url;
    }
}

if (! function_exists('convertDate')) {
    function convertDate($date, $format = 'd-m-Y')
    {
        if (! $date) {
            return null;
        }

        return date_create($date)->format($format);
    }
}

if (! function_exists('user')) {
    function user($key = null): string | int | null | \App\Models\User
    {
        if ($key) {
            return request()->user()?->{$key};
        }

        return request()->user();
    }
}

if (! function_exists('setupApplication')) {
    function setupApplication($key = null): SetupAplication | string | array
    {
        if (! Cache::has('setupApplication')) {
            Cache::forever('setupApplication', SetupAplication::first());
        }
        $setupApplication = Cache::get('setupApplication');
        if ($key) {
            return $setupApplication->{$key};
        }

        return $setupApplication;
    }
}

if (! function_exists('numbering')) {
    function numbering(Model $model, $format, $column = 'nomor', $length = 4)
    {
        $model = $model->select(\Illuminate\Support\Facades\DB::raw("MAX($column) as $column"))->where("$column", 'like', "%{$format}%")->orderByDesc('id')->first();

        return $format . sprintf("%0{$length}s", ((int) substr($model->{$column}, strlen($format), $length)) + 1);
    }
}

if (! function_exists('removeNumberFormat')) {
    function removeNumberFormat($number)
    {
        $num = explode('.', $number);
        if (isset($num[1]) && ((int) $num[0] == $num[0])) {
            if (strlen($num[1]) < 3) {
                return implode('.', $num);
            }

            return str_replace(',', '.', implode('', $num));
        } else {
            $num = explode(',', $number);
            if (! isset($num[1])) {
                return $number;
            }
            if (strlen($num[1]) < 3) {
                return implode('.', $num);
            }

            return str_replace(',', '', $number);
        }
    }
}
