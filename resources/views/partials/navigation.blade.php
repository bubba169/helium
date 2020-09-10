<nav class="cms-nav">
    <ul class="nav flex-column nav-pills nav-fill">
        @foreach ($menu as $link)
            <li class="nav-item">
                <a class="nav-link" href="{{ $link['url'] }}">{{ $link['label'] }}</a>
            </li>
        @endforeach
    </ul>
</nav>