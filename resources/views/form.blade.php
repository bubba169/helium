@extends('helium::layout')

@section('content')
<div class="row">
    <div class="col">
        <form method='POST'>
            @foreach ($form->getFields() as $field)
                @include($field->getView(), ['field' => $field])
            @endforeach

            <button type="submit">Submit</button>
        </form>
    </div>
</div>

@endsection

