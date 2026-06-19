@extends('layouts.app')

@section('content')
<script>
    window.items = @json($items);
    window.tags = @json($tags);
    window.categories = @json($categories);
    window.unreviewedOutfitsByDay = @json($unreviewedOutfitsByDay);
</script>

<main class="wrapper profile-page">
    <aside class="side-area branding">
        <a href="/" class="back-to-config">← zurück zum outfit-konfigurator</a>
        <div class="brand-content">
            <h1 class="onion-title">on¿on</h1>
            <div class="onion-subtitle">
                <p>Hey <strong>{{ Auth::user()->name }}</strong>,<br>
                schön dass du on¿on nutzt!</p>

        </div>
        
        <div class="profile-actions d-flex flex-column gap-3 mt-4 w-100 align-items-end">
        
            <button type="button" class="btn-pill-white" data-bs-toggle="modal" data-bs-target="#uploadModal">
                Item hinzufügen
            </button>

            <button type="button" class="btn-pill-white" data-bs-toggle="modal" data-bs-target="#addTagModal">
                Tag hinzufügen
            </button>

            
            
            <a href="/user/profile" class="btn-pill-white text-center">
                Einstellungen
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-pill-white">
                    Abmelden
                </button>
            </form> 

        </div>
    </aside>

    <section class="main-configurator" > 
        <h2 class="closet-header mb-5">Dein Kleiderschrank</h2>

        <div class="closet-list w-100">
            @foreach($categories as $category)
                @php
                    $categoryItems = collect($items)->where('category_id', $category['id'] ?? $category->id);
                @endphp

                @if($categoryItems->count() > 0)
                    <div class="category-row mb-4 w-100">
                        <h3 class="category-title">
                            {{ $category['name'] ?? $category->name }}
                        </h3>
                        <div class="closet-capsule">
                            <div class="closet-capsule-scroll">
                                @foreach($categoryItems as $item)
                                    @php
                                        $path = str_replace('/storage/', '', $item->filepath ?? $item['filepath'] ?? '');
                                    @endphp
                                    <div class="closet-item" onclick="window.openEditItemModal({{ json_encode($item) }})">
                                        <img src="{{ asset($path) }}"
                                            alt="Kleidungsstück"
                                            onerror="this.onerror=null; this.src='{{ asset('images/placeholder.png') }}';">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

            @if(count($items) === 0)
                <p style="color: #888; text-align: center; margin-top: 50px;">Dein Kleiderschrank ist noch leer. Füge dein erstes Item hinzu!</p>
            @endif
        </div>
    </section>

    <div class="mt-3">
        @include('modals.upload-clothing')
        @include('modals.add-tag')
        @include('modals.edit-item')
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@endsection