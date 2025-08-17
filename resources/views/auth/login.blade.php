@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-light-grayish-blue py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-lg shadow-md">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-dark-slate-blue">Sign in</h2>
        </div>
        
        <form class="mt-8 space-y-6" method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="rounded-md shadow-sm space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-dark-slate-blue mb-1">Email address</label>
                    <input id="email" name="email" type="email" required 
                           class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-bright-sky-blue focus:border-bright-sky-blue focus:z-10 sm:text-sm">
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-dark-slate-blue mb-1">Password</label>
                    <input id="password" name="password" type="password" required 
                           class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-bright-sky-blue focus:border-bright-sky-blue focus:z-10 sm:text-sm">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" name="remember-me" type="checkbox" 
                           class="h-4 w-4 text-bright-sky-blue focus:ring-bright-sky-blue border-gray-300 rounded">
                    <label for="remember-me" class="ml-2 block text-sm text-dark-slate-blue">
                        Remember me
                    </label>
                </div>

                <div class="text-sm">
                    <a href="#" class="font-medium text-bright-sky-blue hover:text-dark-blue">
                        Forgot your password?
                    </a>
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-bright-sky-blue hover:bg-dark-blue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bright-sky-blue transition-colors duration-200">
                    Sign in
                </button>
            </div>
        </form>
        
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500">Or continue with</span>
            </div>
        </div>
        
        <div class="grid grid-cols-2 gap-3">
            <a href="#" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-dark-slate-blue hover:bg-gray-50">
                <i class="fab fa-google text-lg"></i>
            </a>
            
            <a href="#" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-dark-slate-blue hover:bg-gray-50">
                <i class="fab fa-facebook-f text-lg"></i>
            </a>
        </div>
        
        <div class="text-center text-sm text-gray-600">
            Don't have an account? 
            <a href="{{ route('register') }}" class="font-medium text-bright-sky-blue hover:text-dark-blue">
                Sign up
            </a>
        </div>
    </div>
</div>
@endsection