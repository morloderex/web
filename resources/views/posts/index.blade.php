@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Workouts</div>

                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="workouts" class="table table-hover">
                            <thead>
                                <tr v-for="key in columns"
                                    @click="sortBy(key)"
                                    :class="{active: sortKey == key}"
                                    >
                                    @{{ key | capitalize }}
                                    <span :class="sortOrders[key] > 0 ? 'asc' : 'desc'">
                                        <i class="fa fa-arrow"></i>
                                    </span>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="
                                    entry in data
                                    | filterBy filterKey
                                    | orderBy sortKey sortOrders[sortKey]"
                                >
                                    <td v-for="key in columns">
                                      @{{entry[key]}}
                                    </td>
                                  </tr>
                            </tbody>
                        </table>
                        <form id="search">
                            Search <input name="query" v-model="searchQuery">
                        </form>
                        <demo-grid
                            :data="gridData"
                            :columns="gridColumns"
                            :filter-key="searchQuery">
                        </demo-grid>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var workouts = new Vue({
        el: '#workouts',
        data: {
            gridColumns: {{ $posts->keys() }},
            
        }
    })
</script>
@endsection
