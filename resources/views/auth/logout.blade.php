<a class="dropdown-item" href="javascript:void(0);"
    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
    <i class="bx bx-power-off me-2"></i>
    <span class="align-middle">Log Out</span>
</a>
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
