<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Экспонаты музея: {{ $museum->name_ru }} - Музеи Рима</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/scss/app.scss')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="nav-header w-100">
        <div class="container d-flex align-items-center py-2">
            <div class="logo fs-1 text-center">d</div>
            <div class="site-name flex-grow-1 fs-1 fw-bold ms-3">Экспонаты музея</div>
            <a href="{{ route('museums.show', $museum) }}" class="btn btn-outline-secondary fs-5 px-3 py-2 rounded">
                Назад к музею
            </a>
        </div>
    </nav>

    <main>
        <div class="container mt-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('museums.index') }}">Все музеи</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('museums.show', $museum) }}">{{ $museum->name_ru }}</a></li>
                    <li class="breadcrumb-item active">Экспонаты</li>
                </ol>
            </nav>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Экспонаты музея: {{ $museum->name_ru }}</h1>
                <a href="{{ route('museums.exhibits.create', $museum) }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Добавить экспонат
                </a>
            </div>

            @if($exhibits->isEmpty())
                <div class="alert alert-info">
                    В этом музее пока нет экспонатов.
                </div>
            @else
                <div class="row">
                    @foreach($exhibits as $exhibit)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm position-relative 
                                @if(auth()->check() && $exhibit->user && $exhibit->user->isFriendOfCurrentUser())
                                    friend-highlight
                                @endif">
                                
                                @if(auth()->check() && $exhibit->user && $exhibit->user->isFriendOfCurrentUser())
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge friend-badge" title="Добавлено другом">
                                            <i class="fas fa-user-friends"></i> Друг
                                        </span>
                                    </div>
                                @endif
                                
                                @if($exhibit->image_filename)
                                    <img src="{{ $exhibit->image_url }}" 
                                         class="card-img-top" 
                                         alt="{{ $exhibit->name }}"
                                         style="height: 200px; object-fit: cover;">
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title d-flex align-items-center">
                                        {{ $exhibit->name }}
                                        @if(auth()->check() && $exhibit->user && $exhibit->user->isFriendOfCurrentUser())
                                            <i class="fas fa-user-friends friend-icon ms-2" title="Добавлено другом"></i>
                                        @endif
                                    </h5>
                                    <p class="text-muted small mb-0">
                                        Добавлено: {{ $exhibit->created_at->format('d.m.Y') }}
                                        @if($exhibit->user)
                                            пользователем 
                                            <a href="{{ route('users.show', $exhibit->user->name) }}" 
                                               class="@if(auth()->check() && $exhibit->user->isFriendOfCurrentUser()) friend-name @endif">
                                                {{ $exhibit->user->name }}
                                            </a>
                                        @endif
                                    </p>
                                </div>
                                <div class="card-footer bg-white">
                                    <div class="d-flex justify-content-between">
                                        @if(auth()->user()->is_admin || $exhibit->user_id === auth()->id())
                                            <a href="{{ route('museums.exhibits.edit', [$museum, $exhibit]) }}" 
                                               class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-edit"></i> Редактировать
                                            </a>
                                            <form action="{{ route('museums.exhibits.destroy', [$museum, $exhibit]) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Удалить экспонат «{{ $exhibit->name }}»?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i> Удалить
                                                </button>
                                            </form>
                                        @else
                                            <div class="text-muted small">
                                                Только автор или администратор может управлять этим экспонатом
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </main>


    <footer class="mt-5">
        <div class="container footer">
            <div class="author">Шестаков Дмитрий</div>
            <div class="socials">
                <a href="#"><img src="{{ asset('storage/museums/vk.svg') }}" alt="VK" width="24"></a>
                <a href="#"><img src="{{ asset('storage/museums/telegram.svg') }}" alt="Telegram" width="24"></a>
            </div>
        </div>
    </footer>

    @vite('resources/js/app.js')
</body>
</html>