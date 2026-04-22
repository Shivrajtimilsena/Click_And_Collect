@extends('layouts.auth')

@section('title', request()->routeIs('signup') ? 'Sign Up' : 'Sign In')

@section('content')
<div class="fixed inset-0 flex items-center justify-center bg-surface p-4">
    @include('modals.auth-modal')
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    openAuthModal('{{ $tab }}');
});
</script>
@endsection