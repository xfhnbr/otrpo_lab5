@csrf

@if(isset($exhibit))
    @method('PUT')
@endif

<div class="row">
    <div class="col-md-8">
        <div class="mb-3">
            <label for="name" class="form-label">Название экспоната *</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                   id="name" name="name" 
                   value="{{ old('name', $exhibit->name ?? '') }}" 
                   required minlength="3" maxlength="255">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">
                Изображение {{ !isset($exhibit) ? '*' : '' }}
            </label>
            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                   id="image" name="image" 
                   accept="image/*"
                   {{ !isset($exhibit) ? 'required' : '' }}>
            <div class="form-text">
                Допустимые форматы: JPEG, PNG, GIF. Максимальный размер: 2MB
            </div>
            
            @if(isset($exhibit) && $exhibit->image_filename)
                <div class="mt-2">
                    <img src="{{ $exhibit->image_url }}" alt="Текущее изображение" 
                         class="img-thumbnail" width="150">
                    <p class="text-muted small mt-1">Текущее изображение</p>
                </div>
            @endif
            
            @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body">
                <h6>Музей:</h6>
                <p class="mb-0">{{ $museum->name_ru }}</p>
                <p class="text-muted small">{{ $museum->address }}</p>
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="museum_id" value="{{ $museum->id }}">

<div class="mt-4">
    <button type="submit" class="btn btn-primary btn-lg">
        <i class="fas fa-save"></i> {{ isset($exhibit) ? 'Обновить экспонат' : 'Добавить экспонат' }}
    </button>
    <a href="{{ route('museums.show', $museum) }}" class="btn btn-secondary btn-lg">
        Назад к музею
    </a>
</div>