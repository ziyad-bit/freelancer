<hr>
<form action="{{ route('proposal.store') }}" method="POST">
    @csrf

    <textarea name="content" required maxlength="250" minlength="10" class="form-control " cols="30" rows="5"></textarea>
    @error('content')
        <small style="color: red">
            {{ $message }}
        </small>
    @enderror

    <div class="mb-3 row">
        <label for="inputPassword" class="col-sm-2 col-form-label">number of days</label>
        <div class="col-sm-10">
            <input type="number" required max="180" min="1" style="margin-top: 5px;width: 25%"
                class="form-control " name="num_of_days">
            @error('num_of_days')
                <small style="color: red">
                    {{ $message }}
                </small>
            @enderror
        </div>
    </div>

    <div class="mb-3 row">
        <label for="inputPassword" class="col-sm-2 col-form-label">price</label>
        <div class="col-sm-10">
            <input type="number" required max="8000" min="5" style="margin-top: 5px;width: 25%"
                class="form-control " name="price">
            @error('price')
                <small style="color: red">
                    {{ $message }}
                </small>
            @enderror
        </div>
    </div>

    <input type="hidden" required name="project_id" value="{{ $project->id }}">

    <button type="submit" style="margin-top: 5px;" class="btn btn-primary" role="button">
        add
    </button>
</form>
