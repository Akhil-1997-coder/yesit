<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>YES IT LABS ASSIGNMENT</title>
</head>
<body>
    <div class="container">
        <h2 class="text-center">YES IT LABS ASSIGNMENT</h2>

        <!-- Success and Error Messages -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Button to create new profile -->
        <a href="{{ route('profiles.create') }}" class="btn btn-success mb-3">Create New Profile</a>

        <!-- Profile List Table -->
        <h3 class ="mt-3">Profiles List</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Profile Image</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Street Address</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Country</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($profiles as $profile)
                    <tr>
                        <td><img src="{{ asset('storage/' . $profile->profile_image) }}" alt="Profile Image" width="50" class="img-thumbnail"></td>
                        <td>{{ $profile->name }}</td>
                        <td>{{ $profile->phone }}</td>
                        <td>{{ $profile->email }}</td>
                        <td>{{ $profile->street_address }}</td>
                        <td>{{ $profile->city }}</td>
                        <td>{{ $profile->state }}</td>
                        <td>{{ $profile->country }}</td>
                        <td>
                            <!-- Edit Button (Trigger Modal) -->
                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal{{ $profile->id }}">Edit</button>

                            <!-- Delete Button (Trigger Modal) -->
                            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal{{ $profile->id }}">Delete</button>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal{{ $profile->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete this profile?
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('profiles.destroy', $profile->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal{{ $profile->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel">Edit Profile</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Edit Form -->
                                            <form action="{{ route('profiles.update', $profile->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                

                                                <div class="form-group">
                                                    <label for="name">Name</label>
                                                    <input type="text" class="form-control" id="name" name="name" value="{{ $profile->name }}" required>
                                                </div>
                                                

                                                <div class="form-group">
                                                    <label for="phone">Phone</label>
                                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ $profile->phone }}" required>
                                                </div>
                                                

                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input type="email" class="form-control" id="email" name="email" value="{{ $profile->email }}" required>
                                                </div>
                                                

                                                <div class="form-group">
                                                    <label for="street_address">Street Address</label>
                                                    <input type="text" class="form-control" id="street_address" name="street_address" value="{{ $profile->street_address }}" required>
                                                </div>
                                                

                                                <div class="form-group">
                                                    <label for="city">City</label>
                                                    <input type="text" class="form-control" id="city" name="city" value="{{ $profile->city }}" required>
                                                </div>
                                                

                                                <div class="form-group">
                                                    <label for="state">State</label>
                                                    <input type="text" class="form-control" id="state" name="state" value="{{ $profile->state }}" required>
                                                </div>
                                                

                                                <div class="form-group">
                                                    <label for="country">Country</label>
                                                    <input type="text" class="form-control" id="country" name="country" value="{{ $profile->country }}" required>
                                                </div>

                                                <button type="submit" class="btn btn-primary">Update Profile</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Buttons for Import and Export Modals -->
        <button class="btn btn-primary" data-toggle="modal" data-target="#importModal">Import Profiles</button>
        <button class="btn btn-secondary" data-toggle="modal" data-target="#exportModal">Export Profiles</button>
    </div>

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Profiles</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('profiles.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="file">Select CSV file</label>
                            <input type="file" class="form-control" name="file" id="file" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Modal -->
    <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">Export Profiles</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('profiles.export') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">Export as CSV</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Include JS libraries -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
