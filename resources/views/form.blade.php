@extends('helium::layout')

<form method='POST'>
    @foreach ($form->getFields() as $field)
        @include($field->getView(), ['field' => $field])
    @endforeach

    <button type="submit">Submit</button>
</form>

