@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check me-1"></i>
        {!! session('success') !!}
    </div>
@elseif(!empty($success))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check me-1"></i>
        {!! $success !!}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi-fas fa-exclamation-triangle me-1"></i>
        {!! session('error') !!}
    </div>
@elseif(!empty($error))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi-fas fa-exclamation-triangle me-1"></i>
        {!! $error !!}
    </div>
@elseif($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi-fas fa-exclamation-triangle me-1"></i>
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif
