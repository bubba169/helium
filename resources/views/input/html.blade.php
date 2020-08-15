<div>
    <label for="{{ $field->getId() }}">{{ $field->getLabel() }}</label>

    <textarea
        id="{{ $field->getId() }}"
        name="{{ $field->getName() }}"
        @foreach ($field->getAttributes() as $attribute => $value)
            {{ $attribute }}="{{ $value }}"
        @endforeach
    >{{ old($field->getName(), $field->getValue()) }}</textarea>
</div>
