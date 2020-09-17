@extends('layouts.app')

@section('content')
<div class="app-main__outer">
    <div class="app-main__inner">
        {{-- main-header --}}
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-drawer icon-gradient bg-happy-itmeo">
                        </i>
                    </div>
                    <div>Dashboard</div>
                </div>
            </div>
        </div>

        {{-- main --}}
        <div class="row">
            <div class="col-md-4">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Overall Expenses</h5>

                        <table class="mb-0 table">
                            <thead>
                                <tr>
                                    <th>Expense Category</th>
                                    <th>Total</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($categories as $category)
                                <tr>
                                    <td>{{ $category->description }}</td>
                                    <td>{{ number_format($category->amount_total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <total-expenses-chart :categories="{{ $categories }}"></total-expenses-chart>
            </div>
        </div>
    </div>
</div>
@endsection