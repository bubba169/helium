@php($allErrors = $errors->all())
@if (!empty($allErrors))
    <div class="alert alert-danger">
        Please correct the following:
        <ul>
            @foreach ($allErrors as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
