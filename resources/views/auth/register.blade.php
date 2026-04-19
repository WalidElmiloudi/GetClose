@extends('layouts.app')
@section('page', 'REGISTER')
@section('content')
    <main
        class="2xl:h-[calc(100vh-160px)] h-[calc(100vh-80px)] w-full h bg-linear-to-b from-red-50 to-white flex justify-center items-center">
        <div
            class="w-[80%] h-[75%] lg:w-[40%] lg:h-[90%] 2xl:w-[30%] 2xl:h-[80%] bg-linear-to-b from-white to-red-50 shadow-2xl rounded-xl -mt-20 lg:m-0 flex flex-col items-center justify-around">
            <h1 class="text-red-700 font-bold text-2xl lg:text-3xl 2xl:text-5xl">GetClose.</h1>
            @include('forms.register')
            <p class="lg:text-lg 2xl:text-2xl">You already have an account ? <span
                    class="text-red-600 font-bold hover:text-red-900"><a href="{{ route('login') }}">login</a></span></p>
        </div>
    </main>
@endsection
