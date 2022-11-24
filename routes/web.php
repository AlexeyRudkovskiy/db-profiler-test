<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('counters', function (\App\Services\ProfilerContract $profiler) {

    \App\Models\Category::create([ 'active' => true, 'name' => 'First Category' ]);
    \App\Models\Category::create([ 'active' => true, 'name' => 'Second Category' ]);
    \App\Models\Category::create([ 'active' => false, 'name' => 'Third Category' ]);

    \App\Models\Counter::create([ 'value' => 1, 'category_id' => 1 ]);
    \App\Models\Counter::create([ 'value' => 2, 'category_id' => 1 ]);
    \App\Models\Counter::create([ 'value' => 10, 'category_id' => 2 ]);
    \App\Models\Counter::create([ 'value' => 11, 'category_id' => 2 ]);
    \App\Models\Counter::create([ 'value' => 12, 'category_id' => 2 ]);
    \App\Models\Counter::create([ 'value' => 21, 'category_id' => 3 ]);
    \App\Models\Counter::create([ 'value' => 22, 'category_id' => 3 ]);
    \App\Models\Counter::create([ 'value' => 23, 'category_id' => 3 ]);
    \App\Models\Counter::create([ 'value' => 24, 'category_id' => 3 ]);

//    $query = $profiler->profileQuery(function () {
//        \App\Models\Counter::all();
//
//        return \App\Models\Counter::query()
//            ->select(['counters.id', 'counters.value'])
//            ->addSelect('counter_updates.action')
//            ->addSelect('counter_updates.user_id')
//            ->join('counter_updates', function ($join) {
//                $join->on('counter_updates.counter_id', 'counters.id');
//            })
//            ->whereIn('counters.id', [ 1, 2 ])
//            ->get();
//    });
//
//    return $query;

    return \App\Models\Counter::latest()->get();
});

Route::prefix('@profiler')->group(function () {
    Route::get('/', function (\App\Services\ProfilerContract $profiler) {
        return view('profiler/categories')
            ->with('profiler', $profiler);
    });

    Route::get('/queries', function () {
        $pagination = \App\Models\ExplainQuery::query()->latest()->select([ 'name', 'created_at', 'id' ])->paginate();
        $pagination->getCollection()->transform(function ($item) {
            $value = $item->toArray();
            $value = array_merge($value, [
                'created_at' => $item->created_at->toDateTimeString()
            ]);
            return $value;
        });

        return $pagination;
    });

    Route::get('/queries/{explainQuery}', function (\App\Models\ExplainQuery $explainQuery, \App\Services\ProfilerContract $profiler) {
        $query = $explainQuery;
        $data = $query->toArray();

        $nullHighlighter = new \Doctrine\SqlFormatter\NullHighlighter();
        $formatter = new \Doctrine\SqlFormatter\SqlFormatter($nullHighlighter);

        $data['query'] = $formatter->format($query->query);
        $data['batch'] = $profiler->getAllRelatedQueriesFor($query);
        $data['batch'] = array_map(function ($row) {
            return [
                'id' => $row->id,
                'name' => $row->name,
                'created_at' => $row->created_at
            ];
        }, $data['batch']);

        return $data;
    });

    Route::post('/queries/{explainQuery}', function (\App\Models\ExplainQuery $explainQuery) {
        $query = $explainQuery->query;
        $bindings = $explainQuery->bindings;
        $bindings = array_map(fn ($binding) => $binding['value'], $bindings);

        return \DB::select($query, $bindings);
    });
});

Route::get('counter', function (\App\Services\ProfilerContract $profiler) {
     $user1 = \App\Models\User::create([ 'name' => 'First', 'email' => now()->timestamp . 'first@example.com', 'password' => 'password' ]);
     $user2 = \App\Models\User::create([ 'name' => 'Second', 'email' => now()->timestamp . 'second@example.com', 'password' => 'password' ]);

    $profiler->profileQuery(function (\App\Services\ProfilerContract $profiler) use ($user1, $user2) {
        $profiler->setNextQueryName("Create first counter");
        /** @var \App\Models\Counter $first */
        $first = \App\Models\Counter::create([ 'value' => 1 ]);

        $profiler->setNextQueryName("Create second counter");
        /** @var \App\Models\Counter $second */
        $second = \App\Models\Counter::create([ 'value' => 1 ]);

        $first->counterUpdates()->create([ 'action' => 'increment', 'user_id' => $user2->id ]);
        $first->counterUpdates()->create([ 'action' => 'increment', 'user_id' => $user1->id ]);
        $first->counterUpdates()->create([ 'action' => 'increment', 'user_id' => $user2->id ]);
        $second->counterUpdates()->create([ 'action' => 'increment', 'user_id' => $user2->id ]);
        $second->counterUpdates()->create([ 'action' => 'increment', 'user_id' => $user1->id ]);

        $profiler->setNextQueryName('Increment first counter');
        $first->increment('value');
        $first->increment('value');
        $first->increment('value');
        $profiler->setNextQueryName('Increment second counter');
        $second->increment('value');
        $second->increment('value');

        $profiler->setNextQueryName('First interesting query');
        $counters = \App\Models\Counter::query()
            ->join('counter_updates', function ($join) {
                return $join->on('counter_updates.counter_id', 'counters.id');
            })
            ->join('users', function ($join) {
                $join->on('users.id', 'counter_updates.user_id');
            })
            ->select([ 'counters.id', 'counter_updates.id', 'counter_updates.action', 'users.name', 'users.email' ])
            ->get();

        $profiler->setNextQueryName('Second interesting query');
        $changesByUser = \App\Models\Counter::query()
            ->join('counter_updates', function ($join) {
                return $join->on('counter_updates.counter_id', 'counters.id');
            })
            ->join('users', function ($join) {
                $join->on('users.id', 'counter_updates.user_id');
            })
            ->groupBy('users.id')
            ->select([ 'users.id', 'users.name', \DB::raw('count(counter_updates.id) as count_updates') ])
            ->get();
    });
});

Route::get('list', function (\App\Services\ProfilerContract $profiler) {
    $explained = \App\Models\ExplainQuery::find(2);
    return $profiler->getAllRelatedQueriesFor($explained);
});

Route::get('t', function (\App\Services\ProfilerContract $profiler) {
    $result = $profiler->profileQuery(function (\App\Services\ProfilerContract $profiler) {
        return null;
    });

    return $result;
});

Route::get('test', function (\App\Services\ProfilerContract $profiler) {
    $profiler->profile('Increment Value', function () {
        \DB::select('set session transaction isolation level serializable;');
        \DB::transaction(function () {
            \App\Models\Counter::find(1)->increment('value');
        });
    });
});

Route::get('queries', function () {
    $profiles = \App\Models\DbProfile::latest()->get();
    return $profiles;
});
