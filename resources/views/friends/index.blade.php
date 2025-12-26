<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Друзья</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/scss/app.scss')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="nav-header w-100">
        <div class="container d-flex align-items-center py-2">
            <div class="logo fs-1 text-center">d</div>
            <div class="site-name flex-grow-1 fs-1 fw-bold ms-3">Друзья</div>
            <a href="{{ route('museums.index') }}" class="btn btn-outline-secondary fs-5 px-3 py-2 rounded">
                На главную
            </a>
        </div>
    </nav>

    <main>
        <div class="container mt-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('museums.index') }}">Главная</a></li>
                    <li class="breadcrumb-item active">Друзья</li>
                </ol>
            </nav>

            <h1>Друзья пользователя {{ $user->name }}</h1>
            <p class="text-muted">Всего друзей: {{ $user->friends()->count() }}</p>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($friends->isEmpty())
                <div class="alert alert-info">
                    У вас пока нет друзей.
                </div>
            @else
                <div class="row">
                    @foreach($friends as $friend)
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $friend->name }}</h5>
                                    <p class="card-text">{{ $friend->email }}</p>
                                    @if($friend->is_admin)
                                        <span class="badge bg-danger">Администратор</span>
                                    @endif
                                    <div class="mt-3">
                                        <a href="{{ route('users.show', $friend->name) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Профиль
                                        </a>
                                        @if($user->id === auth()->id())
                                            <form action="{{ route('friends.remove', $friend) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-user-minus"></i> Удалить из друзей
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                {{ $friends->links() }}
            @endif
        </div>
    </main>
</body>
</html>