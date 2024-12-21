<form action="{{ route('import-users') }}" method="POST" enctype="multipart/form-data">
    @csrf 
    <label for="csv_file">Sélectionnez le fichier CSV :        </label>
    <input type="file" name="csv_file" required>
    <button type="submit">Importer des utilisateurs    </button>
</form>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
