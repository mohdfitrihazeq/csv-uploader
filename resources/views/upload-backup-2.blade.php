@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="min-height: 50vh;">
        <div class="card shadow-sm mb-4 w-100" style="max-width: 500px;">
            <div class="card-body">
                <h5 class="card-title mb-3 text-center">Upload CSV File</h5>
                <form action="{{ route('uploads.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="csv_file" class="form-label">Choose CSV File</label>
                        <input type="file" name="csv_file" id="csv_file" class="form-control" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Card for upload history -->
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3">Upload History</h5>

            @if($uploads->isEmpty())
                <p class="mb-0">No uploads yet.</p>
            @else
                <ul id="upload-list" class="list-group">
                    @foreach($uploads as $upload)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-truncate me-3" style="max-width: 60%;">{{ $upload->file_path }}</span>
                            <span class="badge bg-{{ $upload->status === 'completed' ? 'success' : ($upload->status === 'failed' ? 'danger' : 'warning') }} rounded-pill">
                                {{ ucfirst($upload->status) }}
                            </span>
                            <small class="text-muted">{{ $upload->created_at->diffForHumans() }}</small>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <script>
        setInterval(() => {
            fetch('{{ route('uploads.status') }}')
                .then(response => response.json())
                .then(data => {
                    const list = document.getElementById('upload-list');
                    list.innerHTML = '';
                    data.forEach(item => {
                        list.innerHTML += `
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-truncate me-3" style="max-width: 60%;">${item.file_path}</span>
                                <span class="badge bg-${item.status === 'completed' ? 'success' : (item.status === 'failed' ? 'danger' : 'warning')} rounded-pill">
                                    ${item.status.charAt(0).toUpperCase() + item.status.slice(1)}
                                </span>
                                <small class="text-muted">${new Date(item.created_at).toLocaleString()}</small>
                            </li>
                        `;
                    });
                });
        }, 3000);
    </script>
@endsection
