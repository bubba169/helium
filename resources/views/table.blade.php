@extends('helium::layout')

@section('content')

    @include('helium::partials.message')

    <table class="table">
        <tr>
            @foreach ($table->getColumns() as $column)
                <th>{{ $column->getLabel() }}</th>
            @endforeach
            <th>{{-- Actions column --}}</th>
        </tr>

        @foreach ($table->getRows() as $row)
            <tr>
                @foreach ($table->getColumns() as $column)
                    <td>@include($column->getView(), ['column' => $column, 'row' => $row])</td>
                @endforeach
                <td>
                    @foreach ($row->getActions() as $action)
                        <a href="{{ $action['url'] }}" class="btn btn-primary" title="{{ $action['title'] }}">
                            @if(!empty($action['icon']))
                                <i class="{{ $action['icon'] }} btn__icon" aria-hidden="true"></i>
                            @endif
                            @if (!empty($action['label']))
                                <span class="btn__label">{{ $action['label'] }}</span>
                            @endif
                        </a>
                    @endforeach
                </td>
            </tr>
        @endforeach
    </table>
@endsection

