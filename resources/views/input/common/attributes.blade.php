id="{{ $field->getId() }}"
name="{{ $field->getName() }}"
class="{{ $field->getClassList() }}"
placeholder="{{ $field->getPlaceholder() }}"

@foreach ($field->getAttributes() as $attribute => $value)
    @if ($value === true)
        {{ $attribute }}
    @else
        {{ $attribute }}="{{ $value }}"

    @endif
@endforeach
