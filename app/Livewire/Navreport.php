<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class Navreport extends Component
{
    public $theQueryId;
    public $pivotsCount;
    public function render()
    {
        $userId = request()->user()->id;
        $allPivots  = \App\Models\QueryPrivot::where('user_id', $userId)
            ->where('query_id', $this->theQueryId)
            ->get();
        $final = [];
        $singleQuery = \App\Models\QueryOfReport::where('id', $this->theQueryId)->first();
        foreach ($allPivots as $singlePivotGroup) {
            $token = new \App\Models\TempUd();
            $token->isUsed = false;
            $token->user_id = request()->user()->id;
            $token->query_id = $singleQuery->id;
            $token->sqlQuery = $singleQuery->sql_query_string;
            $token->dbName = $singleQuery->db_name;
            $token->pivotCode = $singlePivotGroup->query_pivot;
            $token->original = $singlePivotGroup->original;
            $token->isForSavingNewPivot = false;
            $token->save();
            $final[$token->id] = $singlePivotGroup;
        }

        $this->pivotsCount  = \App\Models\QueryPrivot::where('user_id', $userId)
            ->where('query_id', $this->theQueryId)
            ->count();
        return view(
            'livewire.navreport',
            [
                'allUserPivots' => $final,
            ]
        );
    }

    public function mount($theQueryId)
    {
        $this->theQueryId = $theQueryId;
    }
}
