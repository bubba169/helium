@extends('helium::layout')

@section('content')

@include('helium::partials.form-error')
@include('helium::partials.message')

@php ($fields = $form->getFields())

<form method='POST'>
    @csrf
    @if (!empty($form->getSections()))
        <div class="nav nav-tabs" role="tablist" aria-orientation="vertical">
            @php ($first = true)
            @foreach ($form->getSections() as $key => $section)
                <a
                    class="nav-item nav-link {{ $first ? 'active' : '' }}"
                    id="{{ $key }}-tab"
                    href="#{{ $key }}"
                    role="tab"
                    aria-controls="{{ $key }}"
                    data-toggle="tab"
                >
                    {{ $section['label'] }}
                </a>
                @php ($first = false)
            @endforeach
        </div>
        <div class="tab-content mt-4">
            @php ($first = true)

            @foreach ($form->getSections() as $key => $section)
                <div
                    class="tab-pane {{ $first ? 'show active' : '' }}"
                    id="{{ $key }}"
                    role="tabpanel"
                    aria-labelledby="{{ $key }}-tab"
                >
                    @foreach ($section['fields'] as $fieldName)
                        @include($fields[$fieldName]->getView(), ['field' => $fields[$fieldName]])
                    @endforeach
                </div>
                @php ($first = false)
            @endforeach
        </div>
    @else
        <div>
            @foreach ($fields as $field)
                @include($field->getView(), ['field' => $field])
            @endforeach
        </div>
    @endif

    <div class="row">
        <div class="col-auto">
            <button type="submit" class="btn btn-success">
                <i class="far fa-save btn__icon" aria-hidden></i>
                <span class="btn__label">Save</span>
            </button>
        </div>
    </div>
</form>

@endsection

