<div class="form-group">

    @include('helium::input.common.label')

    <div class="image-preview"></div>

    <input type="hidden"
        @include('helium::input.common.attributes')
        value="{{ $field->getValue() }}"
    >

    <a class="btn btn-success open-media-modal">Select Image</a>
</div>
