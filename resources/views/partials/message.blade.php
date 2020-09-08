@if (session()->has('message'))
    @php($message = session()->get('message'))
    <div class="alert alert-{{ $message['type'] }}">
        {{ $message['message'] }}
    </div>
@endif
