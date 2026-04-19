@extends('layouts.app')
@section('page', 'LOGIN')
@section('content')
    <main
        class="2xl:h-[calc(100vh-160px)] h-[calc(100vh-80px)] w-full h bg-linear-to-b from-red-50 to-white flex justify-center items-center">
        <div
            class="w-[80%] lg:w-[40%] 2xl:w-[30%]  bg-linear-to-b from-white to-red-50 shadow-2xl rounded-xl -mt-20 lg:m-0 flex flex-col items-center gap-3 p-4">
            <h1 class="text-red-700 font-bold text-2xl lg:text-3xl 2xl:text-5xl">GetClose.</h1>
            @include('forms.login')
            <p class="lg:text-lg 2xl:text-2xl">You don't have an account ? <span
                    class="text-red-600 font-bold hover:text-red-900"><a href="{{ route('register') }}">sign up</a></span></p>
        </div>
    </main>
@endsection
