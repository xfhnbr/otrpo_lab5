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
            <div class="site-name flex-grow-1 fs-1 fw-bold ms-3">Карта музеев Рима и Ватикана</div>

            <div class="d-flex align-items-center">
                @auth
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle fs-5 px-3 py-2 rounded" 
                                type="button" 
                                id="userDropdown"
                                data-bs-toggle="dropdown" 
                                data-bs-auto-close="true"
                                aria-expanded="false">
                            <i class="fas fa-user me-1"></i> {{ Str::limit(auth()->user()->name, 15) }}
                            @if(auth()->user()->is_admin)
                                <span class="badge bg-danger ms-1">A</span>
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end p-2" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('museums.create') }}">
                                    <i class="fas fa-plus me-3" style="width: 20px; text-align: center;"></i>
                                    <span>Добавить музей</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user-edit me-3" style="width: 20px; text-align: center;"></i>
                                    <span>Профиль</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('users.index') }}">
                                    <i class="fas fa-users me-3" style="width: 20px; text-align: center;"></i>
                                    <span>Все пользователи</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('friends.index') }}">
                                    <i class="fas fa-user-friends me-3" style="width: 20px; text-align: center;"></i>
                                    <span>Друзья</span>
                                </a>
                            </li>
                            @if(auth()->user()->is_admin)
                                <li>
                                    <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('museums.trash') }}">
                                        <i class="fas fa-trash me-3" style="width: 20px; text-align: center;"></i>
                                        <span>Корзина</span>
                                        @php
                                            $trashCount = \App\Models\Museum::onlyTrashed()->count();
                                        @endphp
                                        @if($trashCount > 0)
                                            <span class="badge bg-danger ms-auto">{{ $trashCount }}</span>
                                        @endif
                                    </a>
                                </li>
                            @endif
                            <li><hr class="dropdown-divider my-2"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="w-100">
                                    @csrf
                                    <button type="submit" class="dropdown-item d-flex align-items-center py-2 w-100 border-0 bg-transparent">
                                        <i class="fas fa-sign-out-alt me-3" style="width: 20px; text-align: center;"></i>
                                        <span>Выйти</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <div class="d-flex">
                        <a href="{{ route('login') }}" class="btn btn-outline-primary fs-5 px-3 py-2 rounded me-2">
                            <i class="fas fa-sign-in-alt me-1"></i> Войти
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-primary fs-5 px-3 py-2 rounded">
                            <i class="fas fa-user-plus me-1"></i> Регистрация
                        </a>
                    </div>
                @endauth
            </div>
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
                    У вас пока нет друзей. Добавьте друзей, чтобы видеть их активность.
                </div>
            @else
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-4">
                            <div class="card-header text-white" style="background-color: #94a364 !important;">
                                <h5 class="mb-0">
                                    <i class="fas fa-rss"></i> Лента активности друзей
                                </h5>
                            </div>
                            <div class="card-body">
                                @if($feed->isEmpty())
                                    <div class="alert alert-info">
                                        Ваши друзья пока не добавили ничего нового.
                                    </div>
                                @else
                                    <div class="activity-feed">
                                        @foreach($feed as $activity)
                                            <div class="activity-item mb-4 pb-3 border-bottom">
                                                @if($activity['type'] === 'museum')
                                                    <div class="d-flex align-items-start">
                                                        <div class="me-3">
                                                            <i class="fas fa-landmark fa-2x text-primary" style="color: #94a364 !important;"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1">
                                                                <a href="{{ route('users.show', $activity['user']->name) }}">
                                                                    {{ $activity['user']->name }}
                                                                </a> добавил новый музей
                                                            </h6>
                                                            <h5 class="mb-1">
                                                                <a href="{{ route('museums.show', $activity['item']) }}">
                                                                    {{ $activity['item']->name_ru }}
                                                                </a>
                                                            </h5>
                                                            <p class="text-muted mb-2">
                                                                {{ Str::limit($activity['item']->description, 150) }}
                                                            </p>
                                                            <div class="small text-muted">
                                                                <i class="far fa-clock"></i> 
                                                                {{ $activity['item']->created_at->diffForHumans() }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @elseif($activity['type'] === 'exhibit')
                                                    <div class="d-flex align-items-start">
                                                        <div class="me-3">
                                                            <i class="fas fa-image fa-2x" style="color: #94a364 !important;"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1">
                                                                <a href="{{ route('users.show', $activity['user']->name) }}">
                                                                    {{ $activity['user']->name }}
                                                                </a>
                                                                добавил новый экспонат
                                                            </h6>
                                                            <h5 class="mb-1">
                                                                {{ $activity['item']->name }}
                                                            </h5>
                                                            <p class="mb-2">
                                                                в музее 
                                                                <a href="{{ route('museums.show', $activity['item']->museum) }}">
                                                                    {{ $activity['item']->museum->name_ru }}
                                                                </a>
                                                            </p>
                                                            @if($activity['item']->image_filename)
                                                                <div class="mb-2">
                                                                    <img src="{{ $activity['item']->image_url }}" 
                                                                         alt="{{ $activity['item']->name }}"
                                                                         class="img-thumbnail" 
                                                                         style="max-width: 150px;">
                                                                </div>
                                                            @endif
                                                            <div class="small text-muted">
                                                                <i class="far fa-clock"></i> 
                                                                {{ $activity['item']->created_at->diffForHumans() }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-secondary text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-user-friends"></i> Список друзей ({{ $friends->total() }})
                                </h5>
                            </div>
                            <div class="card-body">
                                @foreach($friends as $friend)
                                    <div class="friend-item d-flex align-items-center mb-3 pb-3 border-bottom">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0">{{ $friend->name }}</h6>
                                            <p class="text-muted small mb-0">{{ $friend->email }}</p>
                                            @if($friend->is_admin)
                                                <span class="badge bg-danger">Администратор</span>
                                            @endif
                                        </div>
                                        <div class="ms-3">
                                            <a href="{{ route('users.show', $friend->name) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Профиль">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($user->id === auth()->id())
                                                <form action="{{ route('friends.remove', $friend) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Удалить {{ $friend->name }} из друзей?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Удалить">
                                                        <i class="fas fa-user-minus"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                                
                                {{ $friends->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </main>
    @vite('resources/js/app.js')
</body>
</html>