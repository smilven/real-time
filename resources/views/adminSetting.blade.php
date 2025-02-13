@extends('layouts.app')

@section('content')
<div class="container py-4 pb-0">
    <h2>Update Banner Images</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Banner Type</th>
                    <th>Current Image</th>
                    <th>Upload New Image</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Banner Image</td>
                    <td>
                        @if($bannerImage)
                            <img src="{{ asset('storage/' . $bannerImage->value) }}" alt="Banner Image" class="img-fluid" style="max-height: 150px;">
                        @else
                            <p>No banner image set.</p>
                        @endif
                    </td>
                    <td>
                        <input type="file" class="form-control mb-2" id="banner_image" name="banner_image">
                        <button type="submit" class="btn btn-primary mt-3">Update</button>
                    </td>
                </tr>
                <tr>
                    <td>Middle Banner Image</td>
                    <td>
                        @if($middleBannerImage)
                            <img src="{{ asset('storage/' . $middleBannerImage->value) }}" alt="Middle Banner Image" class="img-fluid" style="max-height: 150px;">
                        @else
                            <p>No middle banner image set.</p>
                        @endif
                    </td>
                    <td>
                        <input type="file" class="form-control mb-2" id="middle_banner_image" name="middle_banner_image">
                        <button type="submit" class="btn btn-primary mt-3">Update</button>
                    </td>
                </tr>
                <tr>
                    <td>Bottom Left Banner Image</td>
                    <td>
                        @if($bottomBannerLeftImage)
                            <img src="{{ asset('storage/' . $bottomBannerLeftImage->value) }}" alt="Bottom Left Banner Image" class="img-fluid" style="max-height: 150px;">
                        @else
                            <p>No bottom left banner image set.</p>
                        @endif
                    </td>
                    <td>
                        <input type="file" class="form-control mb-2" id="bottom_banner_left_image" name="bottom_banner_left_image">
                        <button type="submit" class="btn btn-primary mt-3">Update</button>
                    </td>
                </tr>
                <tr>
                    <td>Bottom Right Banner Image</td>
                    <td>
                        @if($bottomBannerRightImage)
                            <img src="{{ asset('storage/' . $bottomBannerRightImage->value) }}" alt="Bottom Right Banner Image" class="img-fluid" style="max-height: 150px;">
                        @else
                            <p>No bottom right banner image set.</p>
                        @endif
                    </td>
                    <td>
                        <input type="file" class="form-control mb-2" id="bottom_banner_right_image" name="bottom_banner_right_image">
                        <button type="submit" class="btn btn-primary mt-3">Update</button>
                    </td>
                </tr>
            </tbody>
        </table>

    </form>
</div>
@endsection
