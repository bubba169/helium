<div class="form-group">
    @if ($field->getAttribute('type') !== 'hidden')
        <label for="{{ $field->getId() }}">{{ $field->getLabel() }}</label>
    @endif

    <div
        id="{{ $field->getId() }}"

        class="{{ $field->getClasses()->join(' ') }}"

        @foreach ($field->getAttributes() as $attribute => $value)
            {{ $attribute }}="{{ $value }}"
        @endforeach

    >{{ $field->getValue() }}</div>
</div>
