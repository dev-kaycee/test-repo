@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Add New SMME</h1>

        <form action="{{ route('tenant.smmes.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Business Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="registration_number" class="form-label">Registration Number</label>
                <input type="text" class="form-control" id="registration_number" name="registration_number" required>
            </div>
            <div class="mb-3">
                <label for="years_of_experience" class="form-label">Years of Experience</label>
                <input type="number" class="form-control" id="years_of_experience" name="years_of_experience" required>
            </div>
            <div class="mb-3">
                <label for="team_composition" class="form-label">Team Composition</label>
                <textarea class="form-control" id="team_composition" name="team_composition" required></textarea>
            </div>
            <div class="mb-3">
                <label for="documents_verified" class="form-label">Documents Verified</label>
                <select class="form-control" id="documents_verified" name="documents_verified" required>
                    <option value="true">Yes</option>
                    <option value="false">No</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="grade" class="form-label">Grade</label>
                <select class="form-control" id="grade" name="grade" required>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                    <option value="D">E</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="green">Green</option>
                    <option value="yellow">Yellow</option>
                    <option value="red">Red</option>
                </select>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection