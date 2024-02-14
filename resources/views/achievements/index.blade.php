@extends('layout')
@section('title', 'Награды')

@section('content')

    <x-header image="/img/ui/job.svg">
        <x-slot:sup>какой-то текст </x-slot>
        <x-slot:title>Достижения
            {{-- Работа не волк, в лес не убежит --}}</x-slot>

        <x-slot:description>
            Делайте публикации, оставляйте комментарии и получайте награды
        </x-slot>

    </x-header>

    <x-container >
        <div class="bg-body-tertiary rounded overflow-hidden mb-4 p-4 p-xl-5">
            <div class="row g-4 row-cols-1 row-cols-md-2">
                @foreach($achievements as $achievement)
                    <div class="col d-flex gap-4">
                        <div class="col-3 @if(!$achievement->used) opacity-25 @endif">
                            <img src="{{ $achievement->image() }}" class="flex-shrink-0 rounded img-fluid mb-1 pe-none">
                            @if($achievement->used)<div class="text-primary small text-center">Получено</div>@endif
                        </div>

                        <div class="mb-0 text-balance py-2">
                            <div class="d-block fw-bolder h4 fw-bolder mb-1">{{ $achievement->name() }}</div>
                            <p class="small opacity-50">Открыли {{$achievement->percent}}% пользователей.</p>
                            <p class="mb-0 text-body-secondary">{{ $achievement->description() }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>




    </x-container>

@endsection


