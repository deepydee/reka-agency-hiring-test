@if (session()->has('message'))
<div class="alert alert-success" role="alert">
    {{ session('message') }}
</div>
@endif
