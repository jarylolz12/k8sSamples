@extends('layouts.app')

@section('content')
<div class="container text-center">
    <div class="d-flex flex-column">
        <form method="POST" action="/custom/customers-suppliers">
            @csrf
            <label>Run script to set assign Suppliers to Customers</label>
            <button type="submit" class="btn btn-primary">Run Script</button>
        </form>
        @isset($message)
        <div class="alert alert-success" role="alert">
            {{ $message }}. Go back to <a href="/administrator">Home</a>
        </div>
        @endisset
    </div>
</div>
@endsection