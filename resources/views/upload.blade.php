@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="min-height: 50vh;">
        <div class="card shadow mb-4 w-100" style="max-width: 500px;">
            <div class="card-body">
                <h5 class="card-title mb-3 text-center">Upload CSV File</h5>
                <form action="{{ route('uploads.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="csv_file" class="form-label">Choose CSV File</label>
                        <input type="file" name="csv_file" id="csv_file" class="form-control shadow" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Card for upload history -->
    <div class="card shadow">
        <div class="card-body">
            <h5 class="card-title mb-3">Upload History</h5>

            @if($uploads->isEmpty())
                <p class="mb-0">No uploads yet.</p>
            @else
            <ul id="upload-list" class="list-group">
                @foreach($uploads as $upload)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-truncate me-3" style="max-width: 60%;">{{ $upload->file_name }}</span>
                        <span class="shadow badge bg-{{ $upload->status === 'completed' ? 'success' : ($upload->status === 'failed' ? 'danger' : 'warning') }} rounded-pill">
                            {{ ucfirst($upload->status) }}
                        </span>
                        <small class="text-muted">{{ $upload->created_at->diffForHumans() }}</small>
                    </li>
                @endforeach
            </ul>

            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const initialUploads = @json($uploads->map(fn($u) => ['id' => $u->id, 'status' => $u->status]));

    // Initialize previously known statuses
    let alertStatusMap = {};
    initialUploads.forEach(item => {
        alertStatusMap[item.id] = item.status;
    });

    setInterval(() => {
        fetch('{{ route('uploads.status') }}')
            .then(response => response.json())
            .then(data => {
                const list = document.getElementById('upload-list');
                list.innerHTML = '';

                data.forEach(item => {
                    list.innerHTML += `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-truncate me-3" style="max-width: 60%;">${item.file_name}</span>
                            <span class="badge bg-${item.status === 'completed' ? 'success' : (item.status === 'failed' ? 'danger' : 'warning')} rounded-pill">
                                ${item.status.charAt(0).toUpperCase() + item.status.slice(1)}
                            </span>
                            <small class="text-muted">${new Date(item.created_at).toLocaleString()}</small>
                        </li>
                    `;

                    // Only alert if status changed
                    if (!(item.id in alertStatusMap) || alertStatusMap[item.id] !== item.status) {
                        if (item.status === 'processing') {
                            Swal.fire({
                                title: 'Processing File',
                                text: `File "${item.file_name}" is now being processed.`,
                                icon: 'info',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else if (item.status === 'completed') {
                            Swal.fire({
                                title: 'Upload Completed',
                                text: `File "${item.file_name}" has finished processing successfully.`,
                                icon: 'success',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        }
                        alertStatusMap[item.id] = item.status;
                    }
                });
            });
    }, 3000);
</script>

@endsection
