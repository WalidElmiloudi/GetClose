@extends('layouts.app')
@section('page', 'PROFILE')
@section('content')
        <main class="h-full lg:h-[calc(100vh-80px)] 2xl:h-[calc(100vh-160px)] flex justify-center items-center">
            <section
                class="h-[calc(100vh-160px)] w-full lg:h-[calc(100vh-160px)] 2xl:h-160 2xl:w-140 lg:w-120 flex flex-col items-center justify-around lg:justify-between shadow-2xl bg-white rounded-2xl">
                <div class="h-30 w-full bg-red-400/10 relative rounded-t-2xl">
                    <div
                        class="h-30 w-30 bg-red-300 rounded-full absolute -bottom-[45%] left-[32%] lg:left-[38%] flex justify-center items-center">
                        <h1 class="text-white font-bold text-6xl">{{ucfirst($user['name'][0])}}</h1>
                    </div>
                </div>
                <div class="w-[90%] h-60 mt-15 rounded-lg bg-red-50 shadow-xl flex flex-col p-4 justify-around">
                    <div class="w-full flex justify-between items-center">
                        <h1 class="text-xl font-bold">Name :</h1>
                        <h2 class="text-lg font-bold">{{$user['name']}}</h2>
                        <button type="button"
                            class="py-1 px-2 rounded-sm bg-green-400 text-white font-bold cursor-pointer hover:bg-green-500 ease-in-out duration-150">EDIT</button>
                    </div>
                    <div class="w-full flex justify-between items-center">
                        <h1 class="text-xl font-bold">Email :</h1>
                        <h2 class="text-lg font-bold">{{$user['email']}}</h2>
                        <button type="button"
                            class="py-1 px-2 rounded-sm bg-green-400 text-white font-bold cursor-pointer hover:bg-green-500 ease-in-out duration-150">EDIT</button>
                    </div>
                    <div class="w-full flex gap-4 items-center">
                        <h1 class="text-xl font-bold">Role :</h1>
                        <h2 class="text-lg font-bold">{{ucfirst($user['role'])}}</h2>
                    </div>
                    <button
                        class="py-1 px-2 bg-green-400 text-2xl font-bold text-white rounded-md cursor-pointer hover:bg-green-500 ease-in-out duration-150">Change
                        Password</button>
                </div>
                <a href="{{ route('logout') }}"
                    class=" flex justify-center items-center lg:hidden py-2 px-2 bg-blue-300 w-[90%] text-3xl rounded-md text-white font-bold cursor-pointer hover:bg-blue-500 ease-in-out duration-150">Logout</a>
                <a href=""
                    class=" flex justify-center items-center py-2 px-2 bg-red-500 w-[90%] text-3xl rounded-md text-white font-bold mb-4 cursor-pointer hover:bg-red-900 ease-in-out duration-150">Delete
                    Account</a>
            </section>
        </main>
@endsection
