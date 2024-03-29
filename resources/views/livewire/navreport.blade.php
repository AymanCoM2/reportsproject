<meta name="csrf-token" content="{{ csrf_token() }}">
<div>
    <nav class="sidebar border border-info">
        <input type="hidden" id="realCount" value="{{ $pivotsCount }}">
        <input type="hidden" id="queryId" value="{{ $theQueryId }}">
        <input type="hidden" id="currentUserId" value="{{ request()->user()->id }}">
        <header>
            <div class="image-text">
                <span class="image">
                    <img src="logo.png" alt="" />
                </span>
                <div class="text logo-text">
                    <span class="name">{{ request()->user()->name }}</span>
                    <span class="profession">Role :{{ request()->user()->role->role_name }}</span>
                </div>
            </div>
            <i class="bx bx-chevron-right toggle"></i>
        </header>

        <div class="menu-bar">
            <div class="menu">
                <ul class="menu-links">
                    @foreach ($allUserPivots as $uuid => $singlePivotGroup)
                        <li class="nav-link">
                            <a href="https://jpivot.ifrserp.net?name={{ $uuid }}">
                                <span class="text nav-text p-0">
                                    {{ $singlePivotGroup->pivot_name ? $singlePivotGroup->pivot_name : 'Default' }} (View)
                                </span>
                            </a>
                        </li>
                        <a href="" class="categoryReport" data-type="text" class="p-1 mx-2"
                        data-pk="{{ $singlePivotGroup->id }}"
                        data-url="{{ route('rename-pivot', $singlePivotGroup->id) }}"
                        data-title="Enter pivotname" data-csrf="{{ csrf_token() }}">
                        {{ $singlePivotGroup->pivot_name ? $singlePivotGroup->pivot_name : 'Default' }}</a>
                    @endforeach
                    <hr />
                    <hr />
                    @php
                        $singleQuery = \App\Models\QueryOfReport::where('id', $theQueryId)->first();
                        $tokenForSaving = new \App\Models\TempUd();
                        $tokenForSaving->isUsed = false;
                        $tokenForSaving->user_id = request()->user()->id;
                        $tokenForSaving->query_id = $singleQuery->id;
                        $tokenForSaving->pivotCode = null;
                        $tokenForSaving->isForSavingNewPivot = true;
                        $tokenForSaving->sqlQuery = $singleQuery->sql_query_string;
                        $tokenForSaving->dbName = $singleQuery->db_name;
                        $tokenForSaving->save();
                    @endphp
                    <li class="nav-link" style="background-color: yellow;">
                        <a href="https://jpivot.ifrserp.net?name={{ $tokenForSaving->id }}">
                            <i class='bx bxs-report icon'></i>
                            <span class="text nav-text">New Collection</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="bottom-content">
                <li class="">
                    <a href="#">
                        <i class="bx bx-log-out icon"></i>
                        <span class="text nav-text">Logout</span>
                    </a>
                </li>
            </div>
        </div>
    </nav>
    <section class="home mr-5">
        <iframe id="inlineFrameExample" title="Inline" class="w-100 h-100 "
            src="https://jpivot.ifrserp.net?name={{ $tokenForSaving->id }}">
        </iframe>
    </section>
</div>
